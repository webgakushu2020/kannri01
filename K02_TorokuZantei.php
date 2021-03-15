<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN"
	"http://www.w3.org/TR/html4/frameset.dtd">

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="Header.css">
	<title>契約情報更新</title>
</head>

<?php include 'const.php'; ?>
<?php include 'utility.php'; ?>

<?php
session_start();
$_SESSION["ErrMsg"] = "";

//	print('ユーザID'.$_SESSION["TeacherID"]);
//	print('ユーザ名'.$_SESSION["user_name"]);
//	print('資格'.$_SESSION["shikaku"]);

	$dt = new DateTime();
	$dt->setTimeZone(new DateTimeZone('Asia/Tokyo'));
	$Today = $dt->format('Y-m-d');
	$_SESSION["Today"] = $Today;
	$Msg ="";

	if(isset($_POST['submitter'])){
		if($_POST['submitter']=="logout"){
			if($_SESSION["passhoji"] == 1){
			 	LogoutShori2();
			}else{
			 	LogoutShori();
			}
			exit;
		}
	}

	if(isset($_POST['submitter'])){
		if($_POST['submitter']=="modoru"){
		 	ModoruShori($_SESSION["K02_TorokuZantei_RPID"]);
			exit;
		}
	}

	for ($m = 0; $m < $_SESSION["K02_DataCnt"]; $m++){
		if(isset($_POST["delete_" . $m])){
			$_SESSION["ShoriID"]="DELETE";
		}
	}

	if(isset($_POST['submitter'])){
		switch ($_POST['submitter']){
			case 'newdate':
				$_SESSION["ShoriID"]="NEW";
				break;
			case 'kakunin':
				$_SESSION["ShoriID"]="KAKUNIN";
				break;
			case 'shusei':
				$_SESSION["ShoriID"]="SHUSEI";
				break;

//			case 'delete':
//				$_SESSION["ShoriID"]="DELETE";
//				break;
		}
	}

	// ログイン済みかどうかの変数チェックを行う
	if (!isset($_SESSION["user_name"])) {

		// 変数に値がセットされていない場合は不正な処理と判断し、ログイン画面へリダイレクトさせる
		$no_login_url = "http://{$_SERVER["HTTP_HOST"]}/Login1.php";
		header("Location: {$no_login_url}");
		exit;
	} else {

		//URLパラメータ
		if(isset($_GET['RPID'])) {
			$_SESSION["K02_TorokuZantei_RPID"] = $_GET['RPID'];
		      	//print($_SESSION["S04_Kanri02_RPID"]);
		}
		if(isset($_GET['MODE'])) {
			$_SESSION["K02_TorokuZantei_MODE"] = $_GET['MODE'];
			$_SESSION["ShoriID"] = $_GET['MODE'];
		      	//print($_SESSION["S04_Kanri02_MODE"] . "<BR>");

			if($_SESSION["K02_TorokuZantei_MODE"] == "UPD"){
				$_SESSION["K02_LoginTeacherID"] = $_SESSION["LoginTeacherID"];
				$_SESSION["K02_LoginTName1"] = $_SESSION["LoginTName1"];
				$_SESSION["K02_LoginTName2"] = $_SESSION["LoginTName2"];
			}
		}

		//セッション情報保存
		//前画面からの情報
//		$TeacherID = $_SESSION["TeacherID"];
		$user_name = $_SESSION["user_name"];
		$shikaku = (int) $_SESSION["shikaku"];
		$TName1 = $_SESSION["TName1"];
		$TName2 = $_SESSION["TName2"];

//print("ShoriID=" . $_SESSION["ShoriID"]);
		switch ($_SESSION["ShoriID"]){
			case 'UPD':
				$i = 0;
				SessionClear();
				$CodeData = array();
				$CodeData = GetCodeData("学年","","",1);
				$_SESSION["13CodeData"]=$CodeData;
				$CodeData = array();
				$CodeData = GetCodeData("契約コース","","",1);
				$_SESSION["19CodeData"]=$CodeData;
				$CodeData2 = array();
				$CodeData2 = GetCodeData("契約種別","","",1);
				$_SESSION["20CodeData"]=$CodeData2;

				break;
			case 'KAKUNIN':
				SaveShori();
				$Msg = CheckShori();
				if($Msg == 0){
					//データ格納
					DataInput(0);
				}

				break;
			case 'DELETE':
				//データ削除
				DataInput(1);

				break;
			case 'SHUSEI':
				SaveShori2();
				$Msg = CheckShori2();
				$_SESSION["ErrMsg2"] = $Msg;
				if($Msg == ""){
					//データ格納
					DataInput(2);
				}
				break;
			case 'NEW':
				$_SESSION["K02_EntryID"] = $_POST['K02_EntryID'];
				$_SESSION["K02_EntryTime"] = $_POST['K02_EntryTime'];

				$EMSG = NEWShori();
				$_SESSION["ErrMsg"] = $EMSG;
				if($EMSG == "登録しました。"){
					EndShori();
				}
				break;
		}
	}
//-----------------------------------------------------------
//	セッション情報クリア
//-----------------------------------------------------------
function SessionClear(){
	$_SESSION["ErrMsg"] = "";

	$_SESSION["K02_TeacherID1"] = "";
	$_SESSION["K02_TeacherID2"] = "";
	$_SESSION["K02_PassWord"] = "";
	$_SESSION["K02_Shikaku"] = 0;
	$_SESSION["K02_YukouTime"] = "";
	$_SESSION["K02_TeacherName1"] = "";
	$_SESSION["K02_TeacherName2"] = "";
	$_SESSION["K02_StudentID"] = "";
	$_SESSION["K02_Seq"] = "";
	$_SESSION["K02_StudentName1"] = "";
	$_SESSION["K02_StudentName2"] = "";
	$_SESSION["K02_gread"] = "";
	$_SESSION["K02_StartDay"] = "";
	$_SESSION["K02_EndDay"] = "";
	$_SESSION["K02_Orgtype"] = "";
	$_SESSION["K02_course"] = "";
	$_SESSION["K02_Pay"] = "";
	$_SESSION["K02_KiteiKaisu"] = "";
	$_SESSION["K02_KiteiJikan"] = "";
	$_SESSION["K02_KyuyoNo"] = "";
	$_SESSION["K02_EntryID"] = $_SESSION["K02_LoginTeacherID"];
	$_SESSION["K02_EntryTime"] = $_SESSION["Today"];

	$_SESSION["K02_PassWord2"] = NULL;
	$_SESSION["K02_PassFlg"] = 0;
	$_SESSION["K02_DispFL"] = 0;
	$_SESSION["K02_UpdateID"] = NULL;
	$_SESSION["K02_Uptime"] = NULL;

	$_SESSION["K02_Shikaku_ERR_FLG"] = 0;
	$_SESSION["K02_Teacher_ERR_FLG"] = 0;
	$_SESSION["K02_Student_ERR_FLG"] = 0;
	$_SESSION["K02_Keiyaku_ERR_FLG"] = 0;

	$_SESSION["K02_Shikaku_Cnt"] = 0;
	$_SESSION["K02_Kyoshi_Cnt"] = 0;
	$_SESSION["K02_Seito_Cnt"] = 0;
	$_SESSION["K02_Keiyaku_Cnt"] = 0;

	$_SESSION["K02_Kakunin_Flg"] = "";
	$_SESSION["K02_Toroku_Flg"] = "DISABLED";


	$_SESSION["K02_TeacherID2_ErrMsg"] = "";
	$_SESSION["K02_TeacherID2_COLER"] = "";
	$_SESSION["K02_PassWord_ErrMsg"] = "";
	$_SESSION["K02_PassWord_COLER"] = "";
	$_SESSION["K02_YukouTime_ErrMsg"] = "";
	$_SESSION["K02_YukouTime_COLER"] = "";
	$_SESSION["K02_TeacherName1_ErrMsg"] = "";
	$_SESSION["K02_TeacherName1_COLER"] = "";
	$_SESSION["K02_TeacherName2_ErrMsg"] = "";
	$_SESSION["K02_TeacherName2_COLER"] = "";
	$_SESSION["K02_StudentID_ErrMsg"] = "";
	$_SESSION["K02_StudentID_COLER"] = "";
	$_SESSION["K02_Seq_ErrMsg"] = "";
	$_SESSION["K02_Seq_COLER"] = "";
	$_SESSION["K02_StudentName1_ErrMsg"] = "";
	$_SESSION["K02_StudentName1_COLER"] = "";
	$_SESSION["K02_StudentName2_ErrMsg"] = "";
	$_SESSION["K02_StudentName2_COLER"] = "";
	$_SESSION["K02_gread_ErrMsg"] = "";
	$_SESSION["K02_gread_COLER"] = "";
	$_SESSION["K02_StartDay_ErrMsg"] = "";
	$_SESSION["K02_StartDay_COLER"] = "";
	$_SESSION["K02_EndDay_ErrMsg"] = "";
	$_SESSION["K02_EndDay_COLER"] = "";
	$_SESSION["K02_Orgtype_ErrMsg"] = "";
	$_SESSION["K02_Orgtype_COLER"] = "";
	$_SESSION["K02_course_ErrMsg"] = "";
	$_SESSION["K02_course_COLER"] = "";
	$_SESSION["K02_Pay_ErrMsg"] = "";
	$_SESSION["K02_Pay_COLER"] = "";
	$_SESSION["K02_KiteiKaisu_ErrMsg"] = "";
	$_SESSION["K02_KiteiKaisu_COLER"] = "";
	$_SESSION["K02_KiteiJikan_ErrMsg"] = "";
	$_SESSION["K02_KiteiJikan_COLER"] = "";
	$_SESSION["K02_KyuyoNo_ErrMsg"] = "";
	$_SESSION["K02_KyuyoNo_COLER"] = "";

	$_SESSION["K02_Keiyaku_TeacherID"] = "";
	$_SESSION["K02_Keiyaku_Seq"] = "";
	$_SESSION["K02_Keiyaku_StartDay"] = "";
	$_SESSION["K02_Keiyaku_EndDay"] = "";
	$_SESSION["K02_Keiyaku_Orgtype"] = "";
	$_SESSION["K02_Keiyaku_course"] = "";
	$_SESSION["K02_Keiyaku_Pay"] = "";
	$_SESSION["K02_Keiyaku_KiteiKaisu"] = "";
	$_SESSION["K02_Keiyaku_KiteiJikan"] = "";
	$_SESSION["K02_Keiyaku_KyuyoNo"] = "";
 
	$_SESSION["K02_DataCnt"] = 0;

	$_SESSION["K02_TeacherID1_0"] = "";
	$_SESSION["K02_TeacherID2_0"] = "";
	$_SESSION["K02_PassWord_0"] = "";
	$_SESSION["K02_Shikaku_0"] = "";
	$_SESSION["K02_YukouTime_0"] = "";
	$_SESSION["K02_TeacherName1_0"] = "";
	$_SESSION["K02_TeacherName2_0"] = "";
	$_SESSION["K02_StudentID_0"] = "";
	$_SESSION["K02_Seq_0"] = "";
	$_SESSION["K02_StudentName1_0"] = "";
	$_SESSION["K02_StudentName2_0"] = "";
	$_SESSION["K02_gread_0"] = "";
	$_SESSION["K02_StartDay_0"] = "";
	$_SESSION["K02_EndDay_0"] = "";
	$_SESSION["K02_Orgtype_0"] = "";
	$_SESSION["K02_course_0"] = "";
	$_SESSION["K02_Pay_0"] = "";
	$_SESSION["K02_KiteiKaisu_0"] = "";
	$_SESSION["K02_KiteiJikan_0"] = "";
	$_SESSION["K02_KyuyoNo_0"] = "";
	$_SESSION["K02_EntryID_0"] = "";
	$_SESSION["K02_EntryTime_0"] = "";

	$_SESSION["ZUMI_K02_DataCnt"] = 0;
}
//-----------------------------------------------------------
//	セーブ処理
//-----------------------------------------------------------
function SaveShori(){

	$_SESSION["K02_TeacherID1"] = $_POST['K02_TeacherID1'];
	$_SESSION["K02_TeacherID2"] = $_POST['K02_TeacherID2'];
	$_SESSION["K02_PassWord"] = $_POST['K02_PassWord'];
	$_SESSION["K02_Shikaku"] = $_POST['K02_Shikaku'];
	$_SESSION["K02_YukouTime"] = $_POST['K02_YukouTime'];
	$_SESSION["K02_TeacherName1"] = $_POST['K02_TeacherName1'];
	$_SESSION["K02_TeacherName2"] = $_POST['K02_TeacherName2'];
	$_SESSION["K02_StudentID"] = $_POST['K02_StudentID'];
	$_SESSION["K02_Seq"] = $_POST['K02_Seq'];
	$_SESSION["K02_StudentName1"] = $_POST['K02_StudentName1'];
	$_SESSION["K02_StudentName2"] = $_POST['K02_StudentName2'];
	$_SESSION["K02_gread"] = $_POST['K02_gread'];
	$_SESSION["K02_StartDay"] = $_POST['K02_StartDay'];
	$_SESSION["K02_EndDay"] = "";
	$_SESSION["K02_Orgtype"] = $_POST['K02_Orgtype'];
	$_SESSION["K02_course"] = $_POST['K02_course'];
	$_SESSION["K02_Pay"] = $_POST['K02_Pay'];
	$_SESSION["K02_KiteiKaisu"] = $_POST['K02_KiteiKaisu'];
	$_SESSION["K02_KiteiJikan"] = $_POST['K02_KiteiJikan'];
	$_SESSION["K02_KyuyoNo"] = $_POST['K02_KyuyoNo'];
	$_SESSION["K02_EntryID"] = $_POST['K02_EntryID'];
	$_SESSION["K02_EntryTime"] = $_POST['K02_EntryTime'];
	$_SESSION["K02_UpdateID"] = "";
	$_SESSION["K02_UpdateTime"] = "";

}
//-----------------------------------------------------------
//	セーブ処理
//-----------------------------------------------------------
function SaveShori2(){

	$_SESSION["K02_TeacherID1"] = $_SESSION["K02_Shikaku_TeacherID"];
	$_SESSION["K02_TeacherID2"] = $_SESSION["K02_Keiyaku_TeacherID"];
	$_SESSION["K02_PassWord"] = $_SESSION["K02_Shikaku__PassWord"];
	$_SESSION["K02_Shikaku"] = $_SESSION["K02_Shikaku_Shikaku"];
	$_SESSION["K02_YukouTime"] = $_SESSION["K02_Shikaku_YukouTime"];
	$_SESSION["K02_TeacherName1"] = $_POST['W_K02_TeacherName1'];
	$_SESSION["K02_TeacherName2"] = $_POST['W_K02_TeacherName2'];
	$_SESSION["K02_StudentID"] = $_SESSION["K02_Seito_StudentID"];
	$_SESSION["K02_Seq"] = $_SESSION["K02_Seito_Seq"];
	$_SESSION["K02_StudentName1"] = $_POST['W_K02_StudentName1'];
	$_SESSION["K02_StudentName2"] = $_POST['W_K02_StudentName2'];
	$_SESSION["K02_gread"] = $_POST['W_K02_gread'];
	$_SESSION["K02_StartDay"] = $_POST['W_K02_StartDay'];
	$_SESSION["K02_EndDay"] = $_POST['W_K02_EndDay'];
	$_SESSION["K02_Orgtype"] = $_SESSION["K02_Keiyaku_Orgtype"];
	$_SESSION["K02_course"] = $_SESSION["K02_Keiyaku_course"];
	$_SESSION["K02_Pay"] = $_SESSION["K02_Keiyaku_Pay"];
	$_SESSION["K02_KiteiKaisu"] = $_SESSION["K02_Keiyaku_KiteiKaisu"];
	$_SESSION["K02_KiteiJikan"] = $_SESSION["K02_Keiyaku_KiteiJikan"];
	$_SESSION["K02_KyuyoNo"] = $_SESSION["K02_Keiyaku_KyuyoNo"];
	$_SESSION["K02_EntryID"] = $_SESSION["K02_Keiyaku_EntryID"];
	$_SESSION["K02_EntryTime"] = $_SESSION["K02_Keiyaku_EntryTime"];
	$_SESSION["K02_UpdateID"] = $_POST['K02_EntryID'];
	$_SESSION["K02_UpdateTime"] = $_POST['K02_EntryTime'];

	$_SESSION["W_K02_TeacherName1"] = $_POST['W_K02_TeacherName1'];
	$_SESSION["W_K02_TeacherName2"] = $_POST['W_K02_TeacherName2'];
	$_SESSION["W_K02_StudentID"] = $_SESSION["K02_Seito_StudentID"];
	$_SESSION["W_K02_StudentName1"] = $_POST['W_K02_StudentName1'];
	$_SESSION["W_K02_StudentName2"] = $_POST['W_K02_StudentName2'];
	$_SESSION["W_K02_gread"] = $_POST['W_K02_gread'];
	$_SESSION["W_K02_StartDay"] = $_POST['W_K02_StartDay'];
	$_SESSION["W_K02_EndDay"] = $_POST['W_K02_EndDay'];


}

//-----------------------------------------------------------
//	チェック処理
//-----------------------------------------------------------
function CheckShori(){
$ErrCnt=0;
$ErrAllCnt=0;
$ErrMsg="";
$ErrMsg2="";
$Background="background-color: #F5A9F2";

	$_SESSION["K02_TeacherID2_ErrMsg"] = "";
	$_SESSION["K02_TeacherID2_COLER"] = "";
	$_SESSION["K02_PassWord_ErrMsg"] = "";
	$_SESSION["K02_PassWord_COLER"] = "";
	$_SESSION["K02_YukouTime_ErrMsg"] = "";
	$_SESSION["K02_YukouTime_COLER"] = "";
	$_SESSION["K02_TeacherName1_ErrMsg"] = "";
	$_SESSION["K02_TeacherName1_COLER"] = "";
	$_SESSION["K02_TeacherName2_ErrMsg"] = "";
	$_SESSION["K02_TeacherName2_COLER"] = "";
	$_SESSION["K02_StudentID_ErrMsg"] = "";
	$_SESSION["K02_StudentID_COLER"] = "";
	$_SESSION["K02_Seq_ErrMsg"] = "";
	$_SESSION["K02_Seq_COLER"] = "";
	$_SESSION["K02_StudentName1_ErrMsg"] = "";
	$_SESSION["K02_StudentName1_COLER"] = "";
	$_SESSION["K02_StudentName2_ErrMsg"] = "";
	$_SESSION["K02_StudentName2_COLER"] = "";
	$_SESSION["K02_gread_ErrMsg"] = "";
	$_SESSION["K02_gread_COLER"] = "";
	$_SESSION["K02_StartDay_ErrMsg"] = "";
	$_SESSION["K02_StartDay_COLER"] = "";
	$_SESSION["K02_EndDay_ErrMsg"] = "";
	$_SESSION["K02_EndDay_COLER"] = "";
	$_SESSION["K02_Orgtype_ErrMsg"] = "";
	$_SESSION["K02_Orgtype_COLER"] = "";
	$_SESSION["K02_course_ErrMsg"] = "";
	$_SESSION["K02_course_COLER"] = "";
	$_SESSION["K02_Pay_ErrMsg"] = "";
	$_SESSION["K02_Pay_COLER"] = "";
	$_SESSION["K02_KiteiKaisu_ErrMsg"] = "";
	$_SESSION["K02_KiteiKaisu_COLER"] = "";
	$_SESSION["K02_KiteiJikan_ErrMsg"] = "";
	$_SESSION["K02_KiteiJikan_COLER"] = "";
	$_SESSION["K02_KyuyoNo_ErrMsg"] = "";
	$_SESSION["K02_KyuyoNo_COLER"] = "";
	$_SESSION["K02_Shikaku_ERR_FLG"] = 0;
	$_SESSION["K02_Teacher_ERR_FLG"] = 0;
	$_SESSION["K02_Student_ERR_FLG"] = 0;
	$_SESSION["K02_Keiyaku_ERR_FLG"] = 0;

	$_SESSION["K02_Shikaku_Cnt"] = 0;
	$_SESSION["K02_Kyoshi_Cnt"] = 0;
	$_SESSION["K02_Seito_Cnt"] = 0;
	$_SESSION["K02_Keiyaku_Cnt"] = 0;


	//----------存在チェック----------
	// mysqlへの接続
	$mysqli = new mysqli(HOST, USER, PASS);
	if ($mysqli->connect_errno) {
		print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
		exit();
	}

	// データベースの選択
	$mysqli->select_db(DBNAME);
	$mysqli->set_charset("utf8");

	//資格情報
	$ShikakuCnt=0;
	if($_SESSION["K02_TeacherID1"] != ""){
		$query = "SELECT * FROM K_LoginInfo";
		$query = $query . " WHERE TeacherID = '" . $_SESSION["K02_TeacherID1"] . "'";

		$result = $mysqli->query($query);

		//print($query ."<BR>");

		if (!$result) {
			print('クエリーが失敗しました。' . $mysqli->error);
			$mysqli->close();
			exit();
		}

		$i = 0;
		while($arr_item = $result->fetch_assoc()){
			//レコード内の各フィールド名と値を順次参照
			foreach($arr_item as $key => $value){
				//フィールド名と値を表示
				$_SESSION["K02_Shikaku_" . $key] = $value;
			}
			$ShikakuCnt++;
		}
	}else{

	}
	$_SESSION["K02_Shikaku_Cnt"] = $ShikakuCnt;

	//教師情報
	$KyoshiCnt=0;
	if($_SESSION["K02_TeacherID2"] != ""){
		$query = "SELECT * FROM T_AtenaInfo";
		$query = $query . " WHERE TeacherID = '" . $_SESSION["K02_TeacherID2"] . "'";

		$result = $mysqli->query($query);

		//print($query ."<BR>");

		if (!$result) {
			print('クエリーが失敗しました。' . $mysqli->error);
			$mysqli->close();
			exit();
		}

		$i = 0;
		while($arr_item = $result->fetch_assoc()){
			//レコード内の各フィールド名と値を順次参照
			foreach($arr_item as $key => $value){
				//フィールド名と値を表示
				$_SESSION["K02_Kyoshi_" . $key] = $value;
			}
			$KyoshiCnt++;
		}
	}
	$_SESSION["K02_Kyoshi_Cnt"] = $KyoshiCnt;

	//生徒情報
	$SeitoCnt=0;
	if($_SESSION["K02_StudentID"] != "" && $_SESSION["K02_Seq"] != ""){
		$query = "SELECT * FROM S_AtenaInfo";
		$query = $query . " WHERE StudentID = '" . $_SESSION["K02_StudentID"] . "'";
		$query = $query . " And Seq = '" . $_SESSION["K02_Seq"] . "'";

		$result = $mysqli->query($query);

		//print($query ."<BR>");

		if (!$result) {
			print('クエリーが失敗しました。' . $mysqli->error);
			$mysqli->close();
			exit();
		}

		$i = 0;
		while($arr_item = $result->fetch_assoc()){
			//レコード内の各フィールド名と値を順次参照
			foreach($arr_item as $key => $value){
				//フィールド名と値を表示
				$_SESSION["K02_Seito_" . $key] = $value;
			}

			$_SESSION["W_K02_gread"] = $_SESSION["K02_Seito_gread"];

			$SeitoCnt++;
		}
	}
	$_SESSION["K02_Seito_Cnt"] = $SeitoCnt;

	//契約情報
	$KeiyakuCnt=0;
	if(($_SESSION["K02_TeacherID2"] != "") && ($_SESSION["K02_StudentID"] != "")){
		$query = "SELECT * FROM T_Tanto";
		$query = $query . " WHERE TeacherID = '" . $_SESSION["K02_TeacherID2"] . "'";
		$query = $query . " AND StudentID = '" . $_SESSION["K02_StudentID"] . "'";
		$query = $query . " AND Seq = '" . $_SESSION["K02_Seq"] . "'";

		$result = $mysqli->query($query);

		//print($query ."<BR>");

		if (!$result) {
			print('クエリーが失敗しました。' . $mysqli->error);
			$mysqli->close();
			exit();
		}

		$i = 0;
		while($arr_item = $result->fetch_assoc()){
			//レコード内の各フィールド名と値を順次参照
			foreach($arr_item as $key => $value){
				//フィールド名と値を表示
				$_SESSION["K02_Keiyaku_" . $key] = $value;
			}
			
			$_SESSION["W_K02_TeacherName1"] = GetTeacherName($_SESSION["K02_Keiyaku_TeacherID"]);
			$_SESSION["W_K02_TeacherName2"] = GetTeacherName2($_SESSION["K02_Keiyaku_TeacherID"]);
			$_SESSION["W_K02_StudentName1"] = GetStudentName($_SESSION["K02_Keiyaku_StudentID"]);
			$_SESSION["W_K02_StudentName2"] = GetStudentName2($_SESSION["K02_Keiyaku_StudentID"]);
			$_SESSION["W_K02_StartDay"] = $_SESSION["K02_Keiyaku_StartDay"];
			$_SESSION["W_K02_EndDay"] = $_SESSION["K02_Keiyaku_EndDay"];
			
			$KeiyakuCnt++;
		}
	}
	$_SESSION["K02_Keiyaku_Cnt"] = $KeiyakuCnt;

	if($KeiyakuCnt > 0){
		$ErrAllCnt = $KeiyakuCnt;
	}

	//-----資格情報-----
	//教師ＩＤが入力されている場合は他の項目必須入力
	if($_SESSION["K02_TeacherID1"] != ""){
		if($_SESSION["K02_Shikaku_Cnt"] == 0){
			if($_SESSION["K02_PassWord"] == ""){
				$ErrMsg = "未入力";
				$_SESSION["K02_PassWord_ErrMsg"] = $ErrMsg;
				$_SESSION["K02_PassWord_COLER"] = $Background;
				$ErrCnt++;
			}
			if($_SESSION["K02_YukouTime"] == ""){
				$ErrMsg = "未入力";
				$_SESSION["K02_YukouTime_ErrMsg"] = $ErrMsg;
				$_SESSION["K02_YukouTime_COLER"] = $Background;
				$ErrCnt++;
			}
		}
	}
	if($_SESSION["K02_YukouTime"] != ""){
		if (strptime($_SESSION["K02_YukouTime"], '%Y-%m-%d')) {
		}else{
			$ErrMsg = "入力値不正";
			$_SESSION["K02_YukouTime_ErrMsg"] = $ErrMsg;
			$_SESSION["K02_YukouTime_COLER"] = $Background;
			$ErrCnt++;
		}
	}
	if($ErrCnt > 0){
		$_SESSION["K02_Shikaku_ERR_FLG"] = 1;
		$ErrAllCnt = $ErrCnt;
	}
	$ErrCnt = 0;

	//-----教師情報-----
	//教師ＩＤが入力されている場合は他の項目必須入力
	if($_SESSION["K02_TeacherID2"] != ""){
		if($_SESSION["K02_Kyoshi_Cnt"] == 0){
			if($_SESSION["K02_TeacherName1"] == ""){
				$ErrMsg = "未入力";
				$_SESSION["K02_TeacherName1_ErrMsg"] = $ErrMsg;
				$_SESSION["K02_TeacherName1_COLER"] = $Background;
				$ErrCnt++;
			}
			if($_SESSION["K02_TeacherName2"] == ""){
				$ErrMsg = "未入力";
				$_SESSION["K02_TeacherName2_ErrMsg"] = $ErrMsg;
				$_SESSION["K02_TeacherName2_COLER"] = $Background;
				$ErrCnt++;
			}
		}
	}else{
		$ErrMsg = "未入力";
		$_SESSION["K02_TeacherID2_ErrMsg"] = $ErrMsg;
		$_SESSION["K02_TeacherID2_COLER"] = $Background;
		$ErrCnt++;
	}
	if($_SESSION["K02_TeacherID1"] != "" && $_SESSION["K02_TeacherID2"] !=""){
		if($_SESSION["K02_TeacherID1"] <> $_SESSION["K02_TeacherID2"]){
			$ErrMsg = "資格情報と教師情報のＩＤに相違";
			$_SESSION["K02_TeacherID2_ErrMsg"] = $ErrMsg;
			$_SESSION["K02_TeacherID2_COLER"] = $Background;
			$ErrCnt++;
		}
	}
	if($ErrCnt > 0){
		$_SESSION["K02_Teacher_ERR_FLG"] = 1;
		$ErrAllCnt = $ErrCnt;
	}
	$ErrCnt = 0;

	//-----生徒情報-----
	//生徒ＩＤが入力されている場合は他の項目必須入力
	if($_SESSION["K02_StudentID"] != ""){
		If($_SESSION["K02_Seito_Cnt"]==0){
			if($_SESSION["K02_Seq"] == ""){
				$ErrMsg = "未入力";
				$_SESSION["K02_Seq_ErrMsg"] = $ErrMsg;
				$_SESSION["K02_Seq_COLER"] = $Background;
				$ErrCnt++;
			}
			if($_SESSION["K02_StudentName1"] == ""){
				$ErrMsg = "未入力";
				$_SESSION["K02_StudentName1_ErrMsg"] = $ErrMsg;
				$_SESSION["K02_StudentName1_COLER"] = $Background;
				$ErrCnt++;
			}
			if($_SESSION["K02_StudentName2"] == ""){
				$ErrMsg = "未入力";
				$_SESSION["K02_StudentName2_ErrMsg"] = $ErrMsg;
				$_SESSION["K02_StudentName2_COLER"] = $Background;
				$ErrCnt++;
			}
			if($_SESSION["K02_gread"] == ""){
				$ErrMsg = "未入力";
				$_SESSION["K02_gread_ErrMsg"] = $ErrMsg;
				$_SESSION["K02_gread_COLER"] = $Background;
				$ErrCnt++;
			}
		}
	}else{
		$ErrMsg = "未入力";
		$_SESSION["K02_StudentID_ErrMsg"] = $ErrMsg;
		$_SESSION["K02_StudentID_COLER"] = $Background;
		$ErrCnt++;
		if($_SESSION["K02_Seq"] == ""){
			$ErrMsg = "未入力";
			$_SESSION["K02_Seq_ErrMsg"] = $ErrMsg;
			$_SESSION["K02_Seq_COLER"] = $Background;
			$ErrCnt++;
		}
	}
	if($ErrCnt > 0){
		$_SESSION["K02_Student_ERR_FLG"] = 1;
		$ErrAllCnt = $ErrCnt;
	}
	$ErrCnt = 0;

	//-----契約情報-----
	//生徒ＩＤが入力されている場合は他の項目必須入力
	if($_SESSION["K02_StudentID"] != ""){
		if($_SESSION["K02_StartDay"] == ""){
			$ErrMsg = "未入力";
			$_SESSION["K02_StartDay_ErrMsg"] = $ErrMsg;
			$_SESSION["K02_StartDay_COLER"] = $Background;
			$ErrCnt++;
		}
//		if($_SESSION["K02_EndDay"] == ""){
//			$ErrMsg = "未入力";
//			$_SESSION["K02_EndDay_ErrMsg"] = $ErrMsg;
//			$_SESSION["K02_EndDay_COLER"] = $Background;
//			$ErrCnt++;
//		}
		if($_SESSION["K02_Orgtype"] == ""){
			$ErrMsg = "未入力";
			$_SESSION["K02_Orgtype_ErrMsg"] = $ErrMsg;
			$_SESSION["K02_Orgtype_COLER"] = $Background;
			$ErrCnt++;
		}
		if($_SESSION["K02_course"] == ""){
			$ErrMsg = "未入力";
			$_SESSION["K02_course_ErrMsg"] = $ErrMsg;
			$_SESSION["K02_course_COLER"] = $Background;
			$ErrCnt++;
		}
		if($_SESSION["K02_Pay"] == ""){
			$ErrMsg = "未入力";
			$_SESSION["K02_Pay_ErrMsg"] = $ErrMsg;
			$_SESSION["K02_Pay_COLER"] = $Background;
			$ErrCnt++;
		}
		if($_SESSION["K02_KiteiKaisu"] == ""){
			$ErrMsg = "未入力";
			$_SESSION["K02_KiteiKaisu_ErrMsg"] = $ErrMsg;
			$_SESSION["K02_KiteiKaisu_COLER"] = $Background;
			$ErrCnt++;
		}
		if($_SESSION["K02_KiteiJikan"] == ""){
			$ErrMsg = "未入力";
			$_SESSION["K02_KiteiJikan_ErrMsg"] = $ErrMsg;
			$_SESSION["K02_KiteiJikan_COLER"] = $Background;
			$ErrCnt++;
		}
		if($_SESSION["K02_KyuyoNo"] == ""){
			$ErrMsg = "未入力";
			$_SESSION["K02_KyuyoNo_ErrMsg"] = $ErrMsg;
			$_SESSION["K02_KyuyoNo_COLER"] = $Background;
			$ErrCnt++;
		}
	}
	if($_SESSION["K02_StartDay"] != ""){
		if (strptime($_SESSION["K02_StartDay"], '%Y-%m-%d')) {
		}else{
			$ErrMsg = "入力値不正";
			$_SESSION["K02_StartDay_ErrMsg"] = $ErrMsg;
			$_SESSION["K02_StartDay_COLER"] = $Background;
			$ErrCnt++;
		}
	}
//	if($_SESSION["K02_EndDay"] != ""){
//		if (strptime($_SESSION["K02_EndDay"], '%Y-%m-%d')) {
//		}else{
//			$ErrMsg = "入力値不正";
//			$_SESSION["K02_EndDay_ErrMsg"] = $ErrMsg;
//			$_SESSION["K02_EndDay_COLER"] = $Background;
//			$ErrCnt++;
//		}
//	}
	if($ErrCnt > 0){
		$_SESSION["K02_Keiyaku_ERR_FLG"] = 1;
		$ErrAllCnt = $ErrCnt;
	}
	
	$_SESSION["K02_ErrAllCnt"] = $ErrAllCnt;

	if($_SESSION["K02_ErrAllCnt"] == 0){
		$_SESSION["K02_Kakunin_Flg"] = "DISABLED";
		$_SESSION["K02_Toroku_Flg"] = "";
	}

 	// データベースの切断
	$mysqli->close();

	return $_SESSION["K02_ErrAllCnt"];

}
//-----------------------------------------------------------
//	チェック処理
//-----------------------------------------------------------
function CheckShori2(){
$ErrCnt=0;
$ErrAllCnt=0;
$ErrMsg="";
$ErrMsg2="";
$Background="background-color: #F5A9F2";

	$_SESSION["W_K02_StartDay_COLER"] = "";
	$_SESSION["W_K02_EndDay_COLER"] = "";

	if($_SESSION["W_K02_StartDay"] == ""){
		$_SESSION["W_K02_StartDay_COLER"] = $Background;
		$ErrCnt++;
	}
	if($_SESSION["W_K02_EndDay"] == ""){
		$_SESSION["W_K02_EndDay_COLER"] = $Background;
		$ErrCnt++;
	}
	if($_SESSION["W_K02_StartDay"] != ""){
		if (strptime($_SESSION["W_K02_StartDay"], '%Y-%m-%d')) {
		}else{
			$_SESSION["W_K02_StartDay_COLER"] = $Background;
			$ErrCnt++;
		}
	}
	if($_SESSION["W_K02_EndDay"] != ""){
		if (strptime($_SESSION["W_K02_EndDay"], '%Y-%m-%d')) {
		}else{
			$_SESSION["W_K02_EndDay_COLER"] = $Background;
			$ErrCnt++;
		}
	}

	if($ErrCnt > 0){
		return "入力値エラー";
	}else{
		return "";
	}

}
//-----------------------------------------------------------
//	登録データ保存処理
//-----------------------------------------------------------
Function DataInput($pra){

	if($pra == 0){
		$i = $_SESSION["K02_DataCnt"];
		$i2 = $i - 1;

		if(($i > 0 ) && ($_SESSION["K02_TeacherID2_" . $i2] == $_SESSION["K02_TeacherID2"] && $_SESSION["K02_StudentID_" . $i2] == $_SESSION["K02_StudentID"] && $_SESSION["K02_Seq_" . $i2] == $_SESSION["K02_Seq"])){
		}else{
			$_SESSION["K02_TeacherID1_" . $i] = $_SESSION["K02_TeacherID1"];
			$_SESSION["K02_TeacherID2_" . $i] = $_SESSION["K02_TeacherID2"];
			if($_SESSION["K02_Kyoshi_Cnt"] > 0){ 
				$_SESSION["K02_TeacherName1_" . $i] = $_SESSION["K02_Kyoshi_Name1"];
				$_SESSION["K02_TeacherName2_" . $i] = $_SESSION["K02_Kyoshi_Name2"];
			}else{
				$_SESSION["K02_TeacherName1_" . $i] = $_SESSION["K02_TeacherName1"];
				$_SESSION["K02_TeacherName2_" . $i] = $_SESSION["K02_TeacherName2"];
			}
			$_SESSION["K02_PassWord_" . $i] = $_SESSION["K02_PassWord"];
			$_SESSION["K02_Shikaku_" . $i] = $_SESSION["K02_Shikaku"];
			$_SESSION["K02_YukouTime_" . $i] = $_SESSION["K02_YukouTime"];
			$_SESSION["K02_StudentID_" . $i] = $_SESSION["K02_StudentID"];
			$_SESSION["K02_Seq_" . $i] = $_SESSION["K02_Seq"];
			if($_SESSION["K02_Seito_Cnt"] > 0){ 
				$_SESSION["K02_StudentName1_" . $i] = $_SESSION["K02_Seito_Name1"];
				$_SESSION["K02_StudentName2_" . $i] = $_SESSION["K02_Seito_Name2"];
			}else{
				$_SESSION["K02_StudentName1_" . $i] = $_SESSION["K02_StudentName1"];
				$_SESSION["K02_StudentName2_" . $i] = $_SESSION["K02_StudentName2"];
			}
			$_SESSION["K02_gread_" . $i] = $_SESSION["K02_gread"];
			$_SESSION["K02_StartDay_" . $i] = $_SESSION["K02_StartDay"];
			$_SESSION["K02_EndDay_" . $i] = $_SESSION["K02_EndDay"];
			$_SESSION["K02_Orgtype_" . $i] = $_SESSION["K02_Orgtype"];
			$_SESSION["K02_course_" . $i] = $_SESSION["K02_course"];
			$_SESSION["K02_Pay_" . $i] = $_SESSION["K02_Pay"];
			$_SESSION["K02_KiteiKaisu_" . $i] = $_SESSION["K02_KiteiKaisu"];
			$_SESSION["K02_KiteiJikan_" . $i] = $_SESSION["K02_KiteiJikan"];
			$_SESSION["K02_KyuyoNo_" . $i] = $_SESSION["K02_KyuyoNo"];
			$_SESSION["K02_EntryID_" . $i] = $_SESSION["K02_EntryID"];
			$_SESSION["K02_EntryTime_" . $i] = $_SESSION["K02_EntryTime"];
			$_SESSION["K02_UpdateID_" . $i] = $_SESSION["K02_UpdateID"];
			$_SESSION["K02_UpdateTime_" . $i] = $_SESSION["K02_UpdateTime"];

			$i++;
		}

		$_SESSION["K02_DataCnt"] = $i;
	}elseif($pra == 1){
		$h=0;
		for ($m = 0; $m < $_SESSION["K02_DataCnt"]; $m++){
			if(isset($_POST["delete_" . $m])){
			}else{
				$_SESSION["K02_TeacherID1_" . $h] = $_SESSION["K02_TeacherID1_" . $m];
				$_SESSION["K02_TeacherID2_" . $h] = $_SESSION["K02_TeacherID2_" . $m];
				$_SESSION["K02_TeacherName1_" . $h] = $_SESSION["K02_TeacherName1_" . $m];
				$_SESSION["K02_TeacherName2_" . $h] = $_SESSION["K02_TeacherName2_" . $m];
				$_SESSION["K02_PassWord_" . $h] = $_SESSION["K02_PassWord_" . $m];
				$_SESSION["K02_Shikaku_" . $h] = $_SESSION["K02_Shikaku_" . $m];
				$_SESSION["K02_YukouTime_" . $h] = $_SESSION["K02_YukouTime_" . $m];
				$_SESSION["K02_StudentID_" . $h] = $_SESSION["K02_StudentID_" . $m];
				$_SESSION["K02_Seq_" . $h] = $_SESSION["K02_Seq_" . $m];
				$_SESSION["K02_StudentName1_" . $h] = $_SESSION["K02_StudentName1_" . $m];
				$_SESSION["K02_StudentName2_" . $h] = $_SESSION["K02_StudentName2_" . $m];
				$_SESSION["K02_gread_" . $h] = $_SESSION["K02_gread_" . $m];
				$_SESSION["K02_StartDay_" . $h] = $_SESSION["K02_StartDay_" . $m];
				$_SESSION["K02_EndDay_" . $h] = $_SESSION["K02_EndDay_" . $m];
				$_SESSION["K02_Orgtype_" . $h] = $_SESSION["K02_Orgtype_" . $m];
				$_SESSION["K02_course_" . $h] = $_SESSION["K02_course_" . $m];
				$_SESSION["K02_Pay_" . $h] = $_SESSION["K02_Pay_" . $m];
				$_SESSION["K02_KiteiKaisu_" . $h] = $_SESSION["K02_KiteiKaisu_" . $m];
				$_SESSION["K02_KiteiJikan_" . $h] = $_SESSION["K02_KiteiJikan_" . $m];
				$_SESSION["K02_KyuyoNo_" . $h] = $_SESSION["K02_KyuyoNo_" . $m];
				$_SESSION["K02_EntryID_" . $h] = $_SESSION["K02_EntryID_" . $m];
				$_SESSION["K02_EntryTime_" . $h] = $_SESSION["K02_EntryTime_" . $m];
				$_SESSION["K02_UpdateID_" . $h] = $_SESSION["K02_UpdateID_" . $m];
				$_SESSION["K02_UpdateTime_" . $h] = $_SESSION["K02_UpdateTime_" . $m];
				
				$h++;
			}
		}
		$_SESSION["K02_DataCnt"] = $h;
	}elseif($pra == 2){
		$i = $_SESSION["K02_DataCnt"];
		$i2 = $i - 1;

		if(($i > 0 ) && ($_SESSION["K02_TeacherID2_" . $i2] == $_SESSION["K02_TeacherID2"] && $_SESSION["K02_StudentID_" . $i2] == $_SESSION["K02_StudentID"] && $_SESSION["K02_Seq_" . $i2] == $_SESSION["K02_Seq"])){
		}else{
			$_SESSION["K02_TeacherID1_" . $i] = $_SESSION["K02_TeacherID1"];
			$_SESSION["K02_TeacherID2_" . $i] = $_SESSION["K02_TeacherID2"];
			$_SESSION["K02_TeacherName1_" . $i] = $_SESSION["K02_TeacherName1"];
			$_SESSION["K02_TeacherName2_" . $i] = $_SESSION["K02_TeacherName2"];
			$_SESSION["K02_PassWord_" . $i] = $_SESSION["K02_PassWord"];
			$_SESSION["K02_Shikaku_" . $i] = $_SESSION["K02_Shikaku"];
			$_SESSION["K02_YukouTime_" . $i] = $_SESSION["K02_YukouTime"];
			$_SESSION["K02_StudentID_" . $i] = $_SESSION["K02_StudentID"];
			$_SESSION["K02_Seq_" . $i] = $_SESSION["K02_Seq"];
			$_SESSION["K02_StudentName1_" . $i] = $_SESSION["K02_StudentName1"];
			$_SESSION["K02_StudentName2_" . $i] = $_SESSION["K02_StudentName2"];
			$_SESSION["K02_gread_" . $i] = $_SESSION["K02_gread"];
			$_SESSION["K02_StartDay_" . $i] = $_SESSION["K02_StartDay"];
			$_SESSION["K02_EndDay_" . $i] = $_SESSION["K02_EndDay"];
			$_SESSION["K02_Orgtype_" . $i] = $_SESSION["K02_Orgtype"];
			$_SESSION["K02_course_" . $i] = $_SESSION["K02_course"];
			$_SESSION["K02_Pay_" . $i] = $_SESSION["K02_Pay"];
			$_SESSION["K02_KiteiKaisu_" . $i] = $_SESSION["K02_KiteiKaisu"];
			$_SESSION["K02_KiteiJikan_" . $i] = $_SESSION["K02_KiteiJikan"];
			$_SESSION["K02_KyuyoNo_" . $i] = $_SESSION["K02_KyuyoNo"];
			$_SESSION["K02_EntryID_" . $i] = $_SESSION["K02_EntryID"];
			$_SESSION["K02_EntryTime_" . $i] = $_SESSION["K02_EntryTime"];
			$_SESSION["K02_UpdateID_" . $i] = $_SESSION["K02_UpdateID"];
			$_SESSION["K02_UpdateTime_" . $i] = $_SESSION["K02_UpdateTime"];

			$i++;
		}

		$_SESSION["K02_DataCnt"] = $i;
	}
	
}
//-----------------------------------------------------------
//	登録処理
//-----------------------------------------------------------
Function NEWShori(){
$ErrFlg = 0;

	// mysqlへの接続
	$mysqli = new mysqli(HOST, USER, PASS);
	if ($mysqli->connect_errno) {
		print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
		exit();
	}

	// データベースの選択
	$mysqli->select_db(DBNAME);
	$mysqli->set_charset("utf8");

	//トランザクションをはじめる準備
	$Query = "set autocommit = 0";
	$mysqli->query( $Query);

	//トランザクション開始
	$Query = "begin";
	$mysqli->query( $Query);

	for($t=0; $t < $_SESSION["K02_DataCnt"]; $t++){
		//資格情報登録
//		if(($_SESSION["K02_Shikaku_Cnt"] == 0) && ($_SESSION["K02_TeacherID1"] != "")){
			//資格情報重複チェック
			$ShikakuCnt=0;
//			$query = "SELECT * FROM K_LoginInfo";
			$query = "DELETE FROM K_LoginInfo";
			$query = $query . " WHERE TeacherID = '" . $_SESSION["K02_TeacherID1_" . $t] . "'";

			$result = $mysqli->query($query);

			//print($query ."<BR>");

			if (!$result) {
				$ErrMSG = "クエリーが失敗しました。" . $mysqli->error;
				$ErrFlg = 1;
			}
//			while($arr_item = $result->fetch_assoc()){
//				$ShikakuCnt++;
//			}

//			if($ShikakuCnt > 0){
//				$ErrMSG = "資格情報重複エラー";
//				$ErrFlg = 1;
//			}
			
			//資格情報登録
			if($ErrFlg != 1){
				$query = "INSERT INTO K_LoginInfo ";
				$query = $query . "values(";
				$query = $query . "'" . $_SESSION["K02_TeacherID1_" . $t] . "'";
				$query = $query . ",'" . $_SESSION["K02_PassWord_" . $t] . "'";
				$query = $query . ",NULL";
				$query = $query . ",'" . $_SESSION["K02_Shikaku_" . $t] . "'";
				$query = $query . ",'" . $_SESSION["K02_EntryID_" . $t] . "'";
				$query = $query . ",'" . $_SESSION["K02_EntryTime_" . $t] . "'";
				$query = $query . ",'" . $_SESSION["K02_YukouTime_" . $t] . "'";
				$query = $query . ",'0'";
				$query = $query . ")";

				//print($query);

				$result = $mysqli->query($query);
				if (!$result) {
					$ErrMSG = "クエリーが失敗しました。" . $mysqli->error;
					$ErrFlg = 1;
				}
			}
//		}
		
		//教師情報登録処理
//		if($ErrFlg != 1 && $_SESSION["K02_Kyoshi_Cnt"] == 0){
		if($ErrFlg != 1){
			//教師情報重複チェック
			$KyoshiCnt = 0;
//			$query = "SELECT * FROM T_AtenaInfo";
			$query = "DELETE FROM T_AtenaInfo";
			$query = $query . " WHERE TeacherID = '" . $_SESSION["K02_TeacherID1_" . $t] . "'";

			$result = $mysqli->query($query);

			//print($query ."<BR>");

			if (!$result) {
				print('クエリーが失敗しました。' . $mysqli->error);
				$mysqli->close();
				exit();
			}

//			$i = 0;
//			while($arr_item = $result->fetch_assoc()){
//				$KyoshiCnt++;
//			}
//			if($KyoshiCnt > 0){
//				$ErrMSG = "教師情報重複エラー";
//				$ErrFlg = 1;
//			}

			//教師情報登録
			if($ErrFlg != 1){
				$query = "INSERT INTO T_AtenaInfo ";
				$query = $query . "values(";
				$query = $query . "'" . $_SESSION["K02_TeacherID1_" . $t] . "'";
				$query = $query . ",'" . $_SESSION["K02_TeacherName1_" . $t] . "'";
				$query = $query . ",'" . $_SESSION["K02_TeacherName2_" . $t] . "'";
				$query = $query . ",'" . $_SESSION["K02_EntryID_" . $t] . "'";
				$query = $query . ",'" . $_SESSION["K02_EntryTime_" . $t] . "'";
				$query = $query . ")";

				//print($query);

				$result = $mysqli->query($query);
				if (!$result) {
					$ErrMSG = "クエリーが失敗しました。" . $mysqli->error;
					$ErrFlg = 1;
				}
			}
		}
		
		//生徒情報登録処理
//		if($ErrFlg != 1 && $_SESSION["K02_Seito_Cnt"] == 0){
		if($ErrFlg != 1){
			//生徒情報重複チェック
			$SeitoCnt = 0;
//			$query = "SELECT * FROM S_AtenaInfo";
			$query = "DELETE FROM S_AtenaInfo";
			$query = $query . " WHERE StudentID = '" . $_SESSION["K02_StudentID_" . $t] . "'";
			$query = $query . " And Seq = '" . $_SESSION["K02_Seq_" . $t] . "'";

			$result = $mysqli->query($query);

			//print($query ."<BR>");

			if (!$result) {
				print('クエリーが失敗しました。' . $mysqli->error);
				$mysqli->close();
				exit();
			}

//			$i = 0;
//			while($arr_item = $result->fetch_assoc()){
//				$SeitoCnt++;
//			}
//			if($SeitoCnt > 0){
//				$ErrMSG = "生徒情報重複エラー";
//				$ErrFlg = 1;
//			}

			//生徒情報登録
			if($ErrFlg != 1){
				$query = "INSERT INTO S_AtenaInfo ";
				$query = $query . "values(";
				$query = $query . "'" . $_SESSION["K02_StudentID_" . $t] . "'";
				$query = $query . ",'" . $_SESSION["K02_Seq_" . $t] . "'";
				$query = $query . ",'" . $_SESSION["K02_StudentName1_" . $t] . "'";
				$query = $query . ",'" . $_SESSION["K02_StudentName2_" . $t] . "'";
				$query = $query . ",NULL";
				$query = $query . ",'" . $_SESSION["K02_gread_" . $t] . "'";
				$query = $query . ",'" . $_SESSION["K02_EntryID_" . $t] . "'";
				$query = $query . ",'" . $_SESSION["K02_EntryTime_" . $t] . "'";
				$query = $query . ")";

				//print($query);

				$result = $mysqli->query($query);
				if (!$result) {
					$ErrMSG = "クエリーが失敗しました。" . $mysqli->error;
					$ErrFlg = 1;
				}
			}
		}

		//契約情報登録処理
//		if($ErrFlg != 1 && $_SESSION["K02_Keiyaku_Cnt"] == 0){
		if($ErrFlg != 1){
			//契約情報重複チェック
			$KeiyakuCnt = 0;
//			$query = "SELECT * FROM T_Tanto";
			$query = "DELETE FROM T_Tanto";
			$query = $query . " WHERE TeacherID = '" . $_SESSION["K02_TeacherID2_" . $t] . "'";
			$query = $query . " AND StudentID = '" . $_SESSION["K02_StudentID_" . $t] . "'";
			$query = $query . " AND Seq = '" . $_SESSION["K02_Seq_" . $t] . "'";

			$result = $mysqli->query($query);

			//print($query ."<BR>");

			if (!$result) {
				print('クエリーが失敗しました。' . $mysqli->error);
				$mysqli->close();
				exit();
			}

//			$i = 0;
//			while($arr_item = $result->fetch_assoc()){
//				$KeiyakuCnt++;
//			}
//			if($KeiyakuCnt > 0){
//				$ErrMSG = "契約情報重複エラー";
//				$ErrFlg = 1;
//			}

			//契約情報登録
			if($ErrFlg != 1){
				$query = "INSERT INTO T_Tanto ";
				$query = $query . "values(";
				$query = $query . "'" . $_SESSION["K02_TeacherID2_" . $t] . "'";
				$query = $query . ",'" . $_SESSION["K02_StudentID_" . $t] . "'";
				$query = $query . ",'" . $_SESSION["K02_Seq_" . $t] . "'";
				$query = $query . ",'" . $_SESSION["K02_StartDay_" . $t] . "'";
				if($_SESSION["K02_EndDay_" . $t] == ""){
					$query = $query . ",NULL";
				}else{
					$query = $query . ",'" . $_SESSION["K02_EndDay_" . $t] . "'";
				}
				$query = $query . ",'" . $_SESSION["K02_Orgtype_" . $t] . "'";
				$query = $query . ",'" . $_SESSION["K02_course_" . $t] . "'";
				$query = $query . ",'" . $_SESSION["K02_Pay_" . $t] . "'";
				$query = $query . ",'" . $_SESSION["K02_KiteiKaisu_" . $t] . "'";
				$query = $query . ",'" . $_SESSION["K02_KiteiJikan_" . $t] . "'";
				$query = $query . ",'" . $_SESSION["K02_KyuyoNo_" . $t] . "'";
				$query = $query . ",'1'";
				$query = $query . ",'" . $_SESSION["K02_EntryID_" . $t] . "'";
				$query = $query . ",'" . $_SESSION["K02_EntryTime_" . $t] . "'";
				if($_SESSION["K02_UpdateID_" . $t] == ""){
					$query = $query . ",NULL";
				}else{
					$query = $query . ",'" . $_SESSION["K02_UpdateID_" . $t] . "'";
				}
				if($_SESSION["K02_UpdateTime_" . $t] == ""){
					$query = $query . ",NULL";
				}else{
					$query = $query . ",'" . $_SESSION["K02_UpdateTime_" . $t] . "'";
				}
				$query = $query . ")";

				//print($query);

				$result = $mysqli->query($query);
				if (!$result) {
					$ErrMSG = "クエリーが失敗しました。" . $mysqli->error;
					$ErrFlg = 1;
				}
			}
		}
		if($ErrFlg == 0){
			
			// コミット
			$mysqli->query("commit");

			$RtnMSG = "登録しました。";

		}else{
			$mysqli->query("rollback");

			return $ErrMSG;
		}
	}
	
	$mysqli->close();

	return $RtnMSG;

}
//-----------------------------------------------------------
//	登録後処理
//-----------------------------------------------------------
Function EndShori(){

	$h = 0;
	for ($m = 0; $m < $_SESSION["ZUMI_K02_DataCnt"]; $m++){
		$_SESSION["ZUMI_K02_TeacherID1_" . $h] = $_SESSION["ZUMI_K02_TeacherID1_" . $m];
		$_SESSION["ZUMI_K02_TeacherID2_" . $h] = $_SESSION["ZUMI_K02_TeacherID2_" . $m];
		$_SESSION["ZUMI_K02_TeacherName1_" . $h] = $_SESSION["ZUMI_K02_TeacherName1_" . $m];
		$_SESSION["ZUMI_K02_TeacherName2_" . $h] = $_SESSION["ZUMI_K02_TeacherName2_" . $m];
		$_SESSION["ZUMI_K02_PassWord_" . $h] = $_SESSION["ZUMI_K02_PassWord_" . $m];
		$_SESSION["ZUMI_K02_Shikaku_" . $h] = $_SESSION["ZUMI_K02_Shikaku_" . $m];
		$_SESSION["ZUMI_K02_YukouTime_" . $h] = $_SESSION["ZUMI_K02_YukouTime_" . $m];
		$_SESSION["ZUMI_K02_StudentID_" . $h] = $_SESSION["ZUMI_K02_StudentID_" . $m];
		$_SESSION["ZUMI_K02_Seq_" . $h] = $_SESSION["ZUMI_K02_Seq_" . $m];
		$_SESSION["ZUMI_K02_StudentName1_" . $h] = $_SESSION["ZUMI_K02_StudentName1_" . $m];
		$_SESSION["ZUMI_K02_StudentName2_" . $h] = $_SESSION["ZUMI_K02_StudentName2_" . $m];
		$_SESSION["ZUMI_K02_gread_" . $h] = $_SESSION["ZUMI_K02_gread_" . $m];
		$_SESSION["ZUMI_K02_StartDay_" . $h] = $_SESSION["ZUMI_K02_StartDay_" . $m];
		$_SESSION["ZUMI_K02_EndDay_" . $h] = $_SESSION["ZUMI_K02_EndDay_" . $m];
		$_SESSION["ZUMI_K02_Orgtype_" . $h] = $_SESSION["ZUMI_K02_Orgtype_" . $m];
		$_SESSION["ZUMI_K02_course_" . $h] = $_SESSION["ZUMI_K02_course_" . $m];
		$_SESSION["ZUMI_K02_Pay_" . $h] = $_SESSION["ZUMI_K02_Pay_" . $m];
		$_SESSION["ZUMI_K02_KiteiKaisu_" . $h] = $_SESSION["ZUMI_K02_KiteiKaisu_" . $m];
		$_SESSION["ZUMI_K02_KiteiJikan_" . $h] = $_SESSION["ZUMI_K02_KiteiJikan_" . $m];
		$_SESSION["ZUMI_K02_KyuyoNo_" . $h] = $_SESSION["ZUMI_K02_KyuyoNo_" . $m];
		$_SESSION["ZUMI_K02_EntryID_" . $h] = $_SESSION["ZUMI_K02_EntryID_" . $m];
		$_SESSION["ZUMI_K02_EntryTime_" . $h] = $_SESSION["ZUMI_K02_EntryTime_" . $m];
		
		$h++;
	}
	for ($m = 0; $m < $_SESSION["K02_DataCnt"]; $m++){
		$_SESSION["ZUMI_K02_TeacherID1_" . $h] = $_SESSION["K02_TeacherID1_" . $m];
		$_SESSION["ZUMI_K02_TeacherID2_" . $h] = $_SESSION["K02_TeacherID2_" . $m];
		$_SESSION["ZUMI_K02_TeacherName1_" . $h] = $_SESSION["K02_TeacherName1_" . $m];
		$_SESSION["ZUMI_K02_TeacherName2_" . $h] = $_SESSION["K02_TeacherName2_" . $m];
		$_SESSION["ZUMI_K02_PassWord_" . $h] = $_SESSION["K02_PassWord_" . $m];
		$_SESSION["ZUMI_K02_Shikaku_" . $h] = $_SESSION["K02_Shikaku_" . $m];
		$_SESSION["ZUMI_K02_YukouTime_" . $h] = $_SESSION["K02_YukouTime_" . $m];
		$_SESSION["ZUMI_K02_StudentID_" . $h] = $_SESSION["K02_StudentID_" . $m];
		$_SESSION["ZUMI_K02_Seq_" . $h] = $_SESSION["K02_Seq_" . $m];
		$_SESSION["ZUMI_K02_StudentName1_" . $h] = $_SESSION["K02_StudentName1_" . $m];
		$_SESSION["ZUMI_K02_StudentName2_" . $h] = $_SESSION["K02_StudentName2_" . $m];
		$_SESSION["ZUMI_K02_gread_" . $h] = $_SESSION["K02_gread_" . $m];
		$_SESSION["ZUMI_K02_StartDay_" . $h] = $_SESSION["K02_StartDay_" . $m];
		$_SESSION["ZUMI_K02_EndDay_" . $h] = $_SESSION["K02_EndDay_" . $m];
		$_SESSION["ZUMI_K02_Orgtype_" . $h] = $_SESSION["K02_Orgtype_" . $m];
		$_SESSION["ZUMI_K02_course_" . $h] = $_SESSION["K02_course_" . $m];
		$_SESSION["ZUMI_K02_Pay_" . $h] = $_SESSION["K02_Pay_" . $m];
		$_SESSION["ZUMI_K02_KiteiKaisu_" . $h] = $_SESSION["K02_KiteiKaisu_" . $m];
		$_SESSION["ZUMI_K02_KiteiJikan_" . $h] = $_SESSION["K02_KiteiJikan_" . $m];
		$_SESSION["ZUMI_K02_KyuyoNo_" . $h] = $_SESSION["K02_KyuyoNo_" . $m];
		$_SESSION["ZUMI_K02_EntryID_" . $h] = $_SESSION["K02_EntryID_" . $m];
		$_SESSION["ZUMI_K02_EntryTime_" . $h] = $_SESSION["K02_EntryTime_" . $m];
		
		$h++;
	}

	$_SESSION["ZUMI_K02_DataCnt"] = $h;

	for ($m = 0; $m < $_SESSION["K02_DataCnt"]; $m++){
		$_SESSION["K02_TeacherID1_" . $m] = "";
		$_SESSION["K02_TeacherID2_" . $m] = "";
		$_SESSION["K02_TeacherName1_" . $m] = "";
		$_SESSION["K02_TeacherName2_" . $m] = "";
		$_SESSION["K02_PassWord_" . $m] = "";
		$_SESSION["K02_Shikaku_" . $m] = "";
		$_SESSION["K02_YukouTime_" . $m] = "";
		$_SESSION["K02_StudentID_" . $m] = "";
		$_SESSION["K02_Seq_" . $m] = "";
		$_SESSION["K02_StudentName1_" . $m] = "";
		$_SESSION["K02_StudentName2_" . $m] = "";
		$_SESSION["K02_gread_" . $m] = "";
		$_SESSION["K02_StartDay_" . $m] = "";
		$_SESSION["K02_EndDay_" . $m] = "";
		$_SESSION["K02_Orgtype_" . $m] = "";
		$_SESSION["K02_course_" . $m] = "";
		$_SESSION["K02_Pay_" . $m] = "";
		$_SESSION["K02_KiteiKaisu_" . $m] = "";
		$_SESSION["K02_KiteiJikan_" . $m] = "";
		$_SESSION["K02_KyuyoNo_" . $m] = "";
		$_SESSION["K02_EntryID_" . $m] = "";
		$_SESSION["K02_EntryTime_" . $m] = "";	
	}

	$_SESSION["K02_DataCnt"] = 0;

}
?>
<script type="text/javascript">
function setWarning(wMsg){
	if(window.confirm(wMsg)){
		b.form.submit();
	}
}

</script>
<script type="text/javascript" src="utility.js"></script>

<CENTER>
<body onload="" onKeyPress="OnKey(event.keyCode,'');">
<form name="form1" method="post" action="K02_TorokuZantei.php">
	<table border="0" width="100%">
		<tr align="center">
			<td><font size="5" color="#ff0000"><?php echo $_SESSION["ErrMsg"] ?></font></td>
		</tr>
	</table>
	<table border="0" width="100%">
		<td align="right">
			<input type="hidden" id="submitter" name="submitter" value="" />
			<input type="button" id="modoru" name="modoru" onClick="sbmfnc(this,1)" style="cursor: pointer" value="メニューへ戻る" />
		</td>
	</table>
	<table border="0" width="100%">
		<tr>
			<td align="center" bgcolor="<?php echo KITEI_COLOR ?>">教師情報・生徒情報登録画面</td>
		</tr>
	</table>
	<BR>
	<table border="1" >
		<tr>
			<td>
				<table border="0" >
					<tr>
						<td align="center" bgcolor="<?php echo TEACHR_COLOR ?>" colspan="2">ログイン情報</td>
					</tr>
					<tr>
						<td  width="150" align="center" bgcolor="#c0c0c0">教師ＩＤ</td>
						<td>
							<input class="inputtype" type="text" size="10" maxlength="10" name="K02_TeacherID1" style="ime-mode: disabled;" value="<?php echo $_SESSION["K02_TeacherID1"] ?>" onkeyup="checkInputText(this)">
						</td>
					</tr>
					<tr>
						<td  width="150" align="center" bgcolor="#c0c0c0">パスワード</td>
						<td>
							<input class="inputtype" type="text" size="10" maxlength="10" name="K02_PassWord" style="ime-mode: disabled;<?php echo $_SESSION["K02_PassWord_COLER"] ?>" value="<?php echo $_SESSION["K02_PassWord"] ?>" onkeyup="checkInputText(this)">
							<font size="2" color="#ff0000"><?php echo $_SESSION["K02_PassWord_ErrMsg"]?></font>
						</td>
					</tr>
					<tr>
						<td  width="150" align="center" bgcolor="#c0c0c0">資格</td>
						<td>
							<input type="radio" name="K02_Shikaku" value="0" <?php if($_SESSION["K02_Shikaku"]==0){?> checked <?php } ?>>一般
							<input type="radio" name="K02_Shikaku" value="1" <?php if($_SESSION["K02_Shikaku"]==1){?> checked <?php } ?>>管理
						</td>
					</tr>
					<tr>
						<td  width="150" align="center" bgcolor="#c0c0c0">有効期限</td>
						<td align="left">
							<input class="inputtype" type="text" size="10" maxlength="10" name="K02_YukouTime" style="ime-mode: disabled;<?php echo $_SESSION["K02_YukouTime_COLER"] ?>" value="<?php echo $_SESSION["K02_YukouTime"] ?>">
							<font size="2" color="#ff0000"><?php echo $_SESSION["K02_YukouTime_ErrMsg"]?></font>
						</td>
					</tr>
					<tr>
						<td colspan="2">　
						</td>
					</tr>
					<tr>
						<td colspan="2">　
						</td>
					</tr>
					<tr>
						<td colspan="2">　
						</td>
					</tr>
					<tr>
						<td colspan="2">　
						</td>
					</tr>
				</table>
			</td>
			<td>
				<table border="0" >
					<tr>
						<td align="center" bgcolor="<?php echo TEACHR_COLOR ?>" colspan="2">教師情報</td>
					</tr>
					<tr>
						<td  width="150" align="center" bgcolor="#c0c0c0">教師ＩＤ</td>
						<td>
							<input class="inputtype" type="text" size="10" maxlength="10" name="K02_TeacherID2" style="ime-mode: disabled;<?php echo $_SESSION["K02_TeacherID2_COLER"] ?>" value="<?php echo $_SESSION["K02_TeacherID2"] ?>" onkeyup="checkInputText(this)">
							<font size="2" color="#ff0000">※必須</font>
							<font size="2" color="#ff0000"><?php echo $_SESSION["K02_TeacherID2_ErrMsg"]?></font>
						</td>
					</tr>
					<tr>
						<td  width="150" align="center" bgcolor="#c0c0c0">教師氏名</td>
						<td>
							<input class="inputtype" type="text" size="30" maxlength="40" name="K02_TeacherName1" style="ime-mode: disabled;<?php echo $_SESSION["K02_TeacherName1_COLER"] ?>" value="<?php echo $_SESSION["K02_TeacherName1"] ?>">
							<font size="2" color="#ff0000"><?php echo $_SESSION["K02_TeacherName1_ErrMsg"]?></font>
						</td>
					</tr>
					<tr>
						<td  width="150" align="center" bgcolor="#c0c0c0">教師氏名かな</td>
						<td>
							<input class="inputtype" type="text" size="30" maxlength="40" name="K02_TeacherName2" style="ime-mode: disabled;<?php echo $_SESSION["K02_TeacherName2_COLER"] ?>" value="<?php echo $_SESSION["K02_TeacherName2"] ?>">
							<font size="2" color="#ff0000"><?php echo $_SESSION["K02_TeacherName2_ErrMsg"]?></font>
						</td>
					</tr>
					<tr>
						<td colspan="2">　
						</td>
					</tr>
					<tr>
						<td colspan="2">　
						</td>
					</tr>
					<tr>
						<td colspan="2">　
						</td>
					</tr>
					<tr>
						<td colspan="2">　
						</td>
					</tr>
					<tr>
						<td colspan="2">　
						</td>
					</tr>

				</table>
			</td>
			<td>
				<table border="0" >
					<tr>
						<td align="center" bgcolor="<?php echo STUDENT_COLOR ?>" colspan="2">生徒情報</td>
					</tr>
					<tr>
						<td  width="150" align="center" bgcolor="#c0c0c0">生徒ＩＤ</td>
						<td>
							<input class="inputtype" type="text" size="10" maxlength="10" name="K02_StudentID" style="ime-mode: disabled;<?php echo $_SESSION["K02_StudentID_COLER"] ?>" value="<?php echo $_SESSION["K02_StudentID"] ?>" onkeyup="checkInputText(this)">
							<font size="2" color="#ff0000">※必須</font>
							<font size="2" color="#ff0000"><?php echo $_SESSION["K02_StudentID_ErrMsg"]?></font>
						</td>
					</tr>
					<tr>
						<td  width="150" align="center" bgcolor="#c0c0c0">枝番</TD>
						<td>
							<input class="inputtype" type="text" size="10" maxlength="10" name="K02_Seq" style="ime-mode: disabled;<?php echo $_SESSION["K02_Seq_COLER"] ?>" value="<?php echo $_SESSION["K02_Seq"] ?>" onkeyup="checkInputText(this)">
							<font size="2" color="#ff0000">※必須</font>
							<font size="2" color="#ff0000"><?php echo $_SESSION["K02_Seq_ErrMsg"]?></font>
						</td>
					</tr>
					<tr>
						<td  width="150" align="center" bgcolor="#c0c0c0">生徒氏名</td>
						<td>
							<input class="inputtype" type="text" size="30" maxlength="40" name="K02_StudentName1" style="ime-mode: disabled;<?php echo $_SESSION["K02_StudentName1_COLER"] ?>" value="<?php echo $_SESSION["K02_StudentName1"] ?>">
							<font size="2" color="#ff0000"><?php echo $_SESSION["K02_StudentName1_ErrMsg"]?></font>
						</td>
					</tr>
					<tr>
						<td  width="150" align="center" bgcolor="#c0c0c0">生徒氏名かな</td>
						<td>
							<input class="inputtype" type="text" size="30" maxlength="40" name="K02_StudentName2" style="ime-mode: disabled;<?php echo $_SESSION["K02_StudentName2_COLER"] ?>" value="<?php echo $_SESSION["K02_StudentName2"] ?>">
							<font size="2" color="#ff0000"><?php echo $_SESSION["K02_StudentName2_ErrMsg"]?></font>
						</td>
					</tr>
					<tr>
						<td  width="150" align="center" bgcolor="#c0c0c0">学年</td>
						<td>
							<select name="K02_gread" class="selecttype2">
								<option value="" <?php if($_SESSION["K02_gread"] == ""){ ?> SELECTED <?php } ?>></option>
								<?php for($dataidx=0; $dataidx < $_SESSION["13CodeData"]["13DataCount"]; $dataidx++){ ?>
									<option value="<?php echo $_SESSION["13CodeData"]["13_Eda_" . $dataidx] ?>" <?php if($_SESSION["13CodeData"]["13_Eda_" . $dataidx] == $_SESSION["K02_gread"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["13CodeData"]["13_CodeName1_" . $dataidx] ?></option>
								<?php } ?>
							</select>
							<font size="2" color="#ff0000"><?php echo $_SESSION["K02_gread_ErrMsg"]?></font>
						</td>
					</tr>
					<tr>
						<td colspan="2">　
						</td>
					</tr>
					<tr>
						<td colspan="2">　
						</td>
					</tr>
					<tr>
						<td colspan="2">　
						</td>
					</tr>
				</table>
			</td>
			<td>
				<table border="0" >
					<tr>
						<td align="center" bgcolor="<?php echo KITEI_COLOR ?>" colspan="2">契約情報</td>
					</tr>
					<tr>
						<td  width="150" align="center" bgcolor="#c0c0c0">契約開始</td>
						<td>
							<input class="inputtype" type="text" size="10" maxlength="10" name="K02_StartDay" style="ime-mode: disabled;<?php echo $_SESSION["K02_StartDay_COLER"] ?>" value="<?php echo $_SESSION["K02_StartDay"] ?>">
							<font size="2" color="#ff0000"><?php echo $_SESSION["K02_StartDay_ErrMsg"]?></font>
						</td>
					</tr>
					<tr>
						<td  width="150" align="center" bgcolor="#c0c0c0">契約終了</td>
						<td>
							<input class="inputtype" type="text" size="10" maxlength="10" name="K02_EndDay" style="ime-mode: disabled;<?php echo $_SESSION["K02_EndDay_COLER"] ?>" value="<?php echo $_SESSION["K02_EndDay"] ?>" disabled>
							<font size="2" color="#ff0000"><?php echo $_SESSION["K02_EndDay_ErrMsg"]?></font>
						</td>
					</tr>
					<tr>
						<td  width="150" align="center" bgcolor="#c0c0c0">種別</td>
						<td>
							<select name="K02_Orgtype" class="selecttype2">
								<option value="" <?php if($_SESSION["K02_Orgtype"] == ""){ ?> SELECTED <?php } ?>></option>
								<?php for($dataidx=0; $dataidx < $_SESSION["20CodeData"]["20DataCount"]; $dataidx++){ ?>
									<option value="<?php echo $_SESSION["20CodeData"]["20_Eda_" . $dataidx] ?>" <?php if($_SESSION["20CodeData"]["20_Eda_" . $dataidx] == $_SESSION["K02_Orgtype"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["20CodeData"]["20_CodeName1_" . $dataidx] ?></option>
								<?php } ?>
							</select>
							<font size="2" color="#ff0000"><?php echo $_SESSION["K02_Orgtype_ErrMsg"]?></font>
						</td>
					</tr>
					<tr>
						<td  width="150" align="center" bgcolor="#c0c0c0">コース</td>
						<td>
							<select name="K02_course" class="selecttype2">
								<option value="" <?php if($_SESSION["K02_course"] == ""){ ?> SELECTED <?php } ?>></option>
								<?php for($dataidx=0; $dataidx < $_SESSION["19CodeData"]["19DataCount"]; $dataidx++){ ?>
									<option value="<?php echo $_SESSION["19CodeData"]["19_Eda_" . $dataidx] ?>" <?php if($_SESSION["19CodeData"]["19_Eda_" . $dataidx] == $_SESSION["K02_course"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["19CodeData"]["19_CodeName1_" . $dataidx] ?></option>
								<?php } ?>
							</select>
							<font size="2" color="#ff0000"><?php echo $_SESSION["K02_course_ErrMsg"]?></font>
						</td>
					</tr>
					<tr>
						<td  width="150" align="center" bgcolor="#c0c0c0">時給</td>
						<td>
							<input class="inputtype" type="text" size="10" maxlength="10" name="K02_Pay" style="ime-mode: disabled;<?php echo $_SESSION["K02_Pay_COLER"] ?>" value="<?php echo $_SESSION["K02_Pay"] ?>" onkeyup="checkInputText(this)">
							<font size="2" color="#ff0000"><?php echo $_SESSION["K02_Pay_ErrMsg"]?></font>
						</td>
					</tr>
					<tr>
						<td  width="150" align="center" bgcolor="#c0c0c0">規定回数</td>
						<td>
							<input class="inputtype" type="text" size="10" maxlength="10" name="K02_KiteiKaisu" style="ime-mode: disabled;<?php echo $_SESSION["K02_KiteiKaisu_COLER"] ?>" value="<?php echo $_SESSION["K02_KiteiKaisu"] ?>" onkeyup="checkInputText(this)">
							<font size="2" color="#ff0000"><?php echo $_SESSION["K02_KiteiKaisu_ErrMsg"]?></font>
						</td>
					</tr>
					<tr>
						<td  width="150" align="center" bgcolor="#c0c0c0">規定時間</td>
						<td>
							<input class="inputtype" type="text" size="10" maxlength="10" name="K02_KiteiJikan" style="ime-mode: disabled;<?php echo $_SESSION["K02_KiteiJikan_COLER"] ?>" value="<?php echo $_SESSION["K02_KiteiJikan"] ?>" onkeyup="checkInputText(this)">
							<font size="2" color="#ff0000"><?php echo $_SESSION["K02_KiteiJikan_ErrMsg"]?></font>
						</td>
					</tr>
					<tr>
						<td  width="150" align="center" bgcolor="#c0c0c0">給与ＮＯ</td>
						<td>
							<input class="inputtype" type="text" size="10" maxlength="10" name="K02_KyuyoNo" style="ime-mode: disabled;<?php echo $_SESSION["K02_KyuyoNo_COLER"] ?>" value="<?php echo $_SESSION["K02_KyuyoNo"] ?>" onkeyup="checkInputText(this)">
							<font size="2" color="#ff0000"><?php echo $_SESSION["K02_KyuyoNo_ErrMsg"]?></font>
						</td>
					</tr>

				</table>
			</td>
		</tr>
		<tr>
			<td valign="top">
				<table border="0" width="100%">
					<?php if($_SESSION["K02_Shikaku_ERR_FLG"] == 1){?>
						<tr>
							<td align="center" bgcolor="#F5A9F2" colspan="2">項目エラー</td>
						</tr>
					<?php }else{ ?>
						<?php if($_SESSION["K02_Shikaku_Cnt"] > 0){?>
							<tr>
								<td align="center" bgcolor="#F5A9F2" colspan="2">登録済</td>
							</tr>
							<tr>
								<td align="left" colspan="2"><font color="#ff0000">※この教師ＩＤは登録済みです。<BR>※インサートしません。</font></td>
							</tr>
							<tr>
								<td align="left" colspan="2"><?php echo $_SESSION["K02_Shikaku_TeacherID"] ?></td>
							</tr>
							<tr>
								<td align="left" colspan="2"><?php echo $_SESSION["K02_Shikaku_PassWord"] ?></td>
							</tr>
							<tr>
								<td align="left" colspan="2"><?php echo $_SESSION["K02_Shikaku_Shikaku"] ?></td>
							</tr>
							<tr>
								<td align="left" colspan="2"><?php echo $_SESSION["K02_Shikaku_YukouTime"] ?></td>
							</tr>
						<?php }else{ ?>
							<?php if($_SESSION["K02_TeacherID1"] == ""){?>
								<tr>
									<td align="center" bgcolor="#F5A9F2" colspan="2">未入力</td>
								</tr>
								<tr>
									<td align="left" colspan="2"><font color="#ff0000">※インサートしません。</font></td>
								</tr>
							<?php }else{ ?>
								<tr>
									<td align="center" bgcolor="<?php echo TEACHR_COLOR ?>" colspan="2">未登録</td>
								</tr>
							<?php } ?>
						<?php } ?>
					<?php } ?>
				</table>
			</td>
			<td valign="top">
				<table border="0" width="100%">
					<?php if($_SESSION["K02_Teacher_ERR_FLG"] == 1){?>
						<tr>
							<td align="center" bgcolor="#F5A9F2" colspan="2">項目エラー</td>
						</tr>
					<?php }else{ ?>
						<?php if($_SESSION["K02_Kyoshi_Cnt"] > 0){?>
							<tr>
								<td align="center" bgcolor="#F5A9F2" colspan="2">登録済</td>
							</tr>
							<tr>
								<td align="left" colspan="2"><font color="#ff0000">※この教師ＩＤは登録済みです。<BR>※インサートしません。</font></td>
							</tr>
							<tr>
								<td align="left" colspan="2"><?php echo $_SESSION["K02_Kyoshi_TeacherID"] ?></td>
							</tr>
							<tr>
								<td align="left" colspan="2"><?php echo $_SESSION["K02_Kyoshi_Name1"] ?></td>
							</tr>
							<tr>
								<td align="left" colspan="2"><?php echo $_SESSION["K02_Kyoshi_Name2"] ?></td>
							</tr>
						<?php }else{ ?>
							<?php if($_SESSION["K02_TeacherID2"] == ""){?>
								<tr>
									<td align="center" bgcolor="#F5A9F2" colspan="2">未入力</td>
								</tr>
							<?php }else{ ?>
								<tr>
									<td align="center" bgcolor="<?php echo TEACHR_COLOR ?>" colspan="2">未登録</td>
								</tr>
							<?php } ?>
						<?php } ?>
					<?php } ?>
				</table>
			</td>
			<td valign="top">
				<table border="0" width="100%">
					<?php if($_SESSION["K02_Student_ERR_FLG"] == 1){?>
						<tr>
							<td align="center" bgcolor="#F5A9F2" colspan="2">項目エラー</td>
						</tr>
					<?php }else{ ?>
						<?php if($_SESSION["K02_Seito_Cnt"] > 0){?>
							<tr>
								<td align="center" bgcolor="#F5A9F2" colspan="2">登録済</td>
							</tr>
							<?php if($_SESSION["K02_Keiyaku_Cnt"] > 0){?>
								<tr>
									<td align="left" colspan="2"><font color="#ff0000">※教師ＩＤ、生徒ＩＤは登録済みです。<BR>★別契約の場合は枝番を変更してください。</font></td>
								</tr>
							<?php }else{ ?>
								<tr>
									<td align="left" colspan="2"><font color="#ff0000">※この生徒IDは登録済みです。<BR>※インサートしません。</font></td>
								</tr>
							<?php } ?>
							<tr>
								<td align="left" colspan="2"><?php echo $_SESSION["K02_Seito_StudentID"] ?></td>
							</tr>
							<tr>
								<td align="left" colspan="2"><?php echo $_SESSION["K02_Seito_Seq"] ?></td>
							</tr>
							<tr>
								<td align="left" colspan="2"><?php echo $_SESSION["K02_Seito_Name1"] ?></td>
							</tr>
							<tr>
								<td align="left" colspan="2"><?php echo $_SESSION["K02_Seito_Name2"] ?></td>
							</tr>
							<tr>
								<td align="left" colspan="2"><?php echo $_SESSION["K02_Seito_gread"] ?></td>
							</tr>
						<?php }else{ ?>
							<?php if($_SESSION["K02_StudentID"] == ""){?>
								<tr>
									<td align="center" bgcolor="#F5A9F2" colspan="2">未入力</td>
								</tr>
							<?php }else{ ?>
								<tr>
									<td align="center" bgcolor="<?php echo TEACHR_COLOR ?>" colspan="2">未登録</td>
								</tr>
							<?php } ?>
						<?php } ?>
					<?php } ?>
				</table>
			</td>
			<td valign="top">
				<table border="0" width="100%">
					<?php if($_SESSION["K02_Keiyaku_Cnt"] > 0){?>
						<tr>
							<td align="center" bgcolor="#F5A9F2" colspan="2">登録済です。</td>
						</tr>
						<tr>
							<td align="center" bgcolor="#F5A9F2" colspan="2">登録済み情報を確認してください</td>
						</tr>
					<?php }else{ ?>
						<?php if($_SESSION["K02_Keiyaku_ERR_FLG"] == 1){?>
							<tr>
								<td align="center" bgcolor="#F5A9F2" colspan="2">項目エラー</td>
							</tr>
						<?php }else{ ?>
							<?php if($_SESSION["K02_StartDay"] == ""){?>
								<tr>
									<td align="center" bgcolor="#F5A9F2" colspan="2">未入力</td>
								</tr>
							<?php }else{ ?>
								<tr>
									<td align="center" bgcolor="<?php echo TEACHR_COLOR ?>" colspan="2">未登録</td>
								</tr>
							<?php } ?>
						<?php } ?>
					<?php } ?>
				</table>
			</td>
		</tr>
	</table>
	<BR>
	<?php if($_SESSION["K02_Keiyaku_Cnt"] > 0){?>
		<table border="0" >
			<tr>
				<td align="center" colspan="17" bgcolor="#F5A9F2" ><font color="#ff0000"><B>登録済み情報</B></font></td>
			</tr>
			<tr>
				<td  width="100" align="center" bgcolor="#c0c0c0">教師ID</td>
				<td  width="200" align="center" bgcolor="#c0c0c0">教師氏名</td>
				<td  width="200" align="center" bgcolor="#c0c0c0">教師かな</td>
				<td  width="100" align="center" bgcolor="#c0c0c0">生徒ID</td>
				<td  width="200" align="center" bgcolor="#c0c0c0">生徒氏名</td>
				<td  width="200" align="center" bgcolor="#c0c0c0">生徒かな</td>
				<td  width="100" align="center" bgcolor="#c0c0c0">枝番</td>
				<td  width="100" align="center" bgcolor="#c0c0c0">契約開始</td>
				<td  width="100" align="center" bgcolor="#c0c0c0">契約終了</td>
				<td  width="100" align="center" bgcolor="#c0c0c0">学年</td>
				<td  width="100" align="center" bgcolor="#c0c0c0">種別</td>
				<td  width="100" align="center" bgcolor="#c0c0c0">コース</td>
				<td  width="100" align="center" bgcolor="#c0c0c0">時給</td>
				<td  width="100" align="center" bgcolor="#c0c0c0">回数</td>
				<td  width="100" align="center" bgcolor="#c0c0c0">時間</td>
				<td  width="100" align="center" bgcolor="#c0c0c0">給与NO</td>
				<td  width="100" align="center" bgcolor="#c0c0c0">修正</td>
			</tr>
			<tr>
				<td align="center"><?php echo $_SESSION["K02_Keiyaku_TeacherID"] ?></td>
				<td align="center">
					<input class="inputtype" type="text" size="20" maxlength="40" name="W_K02_TeacherName1" style="ime-mode: disabled;" value="<?php echo $_SESSION["W_K02_TeacherName1"] ?>">
				</td>
				<td align="center">
					<input class="inputtype" type="text" size="20" maxlength="40" name="W_K02_TeacherName2" style="ime-mode: disabled;" value="<?php echo $_SESSION["W_K02_TeacherName2"] ?>">
				</td>
				<td align="center"><?php echo $_SESSION["K02_Keiyaku_StudentID"] ?></td>
				<td align="center">
					<input class="inputtype" type="text" size="20" maxlength="40" name="W_K02_StudentName1" style="ime-mode: disabled;" value="<?php echo $_SESSION["W_K02_StudentName1"] ?>">
				</td>
				<td align="center">
					<input class="inputtype" type="text" size="20" maxlength="40" name="W_K02_StudentName2" style="ime-mode: disabled;" value="<?php echo $_SESSION["W_K02_StudentName2"] ?>">
				</td>
				<td align="center"><?php echo $_SESSION["K02_Keiyaku_Seq"] ?></td>
				<td align="center">
					<input class="inputtype" type="text" size="10" maxlength="10" name="W_K02_StartDay" style="ime-mode: disabled;<?php echo $_SESSION["W_K02_StartDay_COLER"] ?>" value="<?php echo $_SESSION["W_K02_StartDay"] ?>">
				</td>
				<td align="center">
					<input class="inputtype" type="text" size="10" maxlength="10" name="W_K02_EndDay" style="ime-mode: disabled;<?php echo $_SESSION["W_K02_EndDay_COLER"] ?>" value="<?php echo $_SESSION["W_K02_EndDay"] ?>">
				</td>
				<td align="center">
					<select name="W_K02_gread" class="selecttype2">
						<option value="" <?php if($_SESSION["W_K02_gread"] == ""){ ?> SELECTED <?php } ?>></option>
						<?php for($dataidx=0; $dataidx < $_SESSION["13CodeData"]["13DataCount"]; $dataidx++){ ?>
							<option value="<?php echo $_SESSION["13CodeData"]["13_Eda_" . $dataidx] ?>" <?php if($_SESSION["13CodeData"]["13_Eda_" . $dataidx] == $_SESSION["W_K02_gread"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["13CodeData"]["13_CodeName1_" . $dataidx] ?></option>
						<?php } ?>
					</select>
				</td>
				<td align="center"><?php echo $_SESSION["K02_Keiyaku_Orgtype"] ?></td>
				<td align="center"><?php echo $_SESSION["K02_Keiyaku_course"] ?></td>
				<td align="center"><?php echo $_SESSION["K02_Keiyaku_Pay"] ?></td>
				<td align="center"><?php echo $_SESSION["K02_Keiyaku_KiteiKaisu"] ?></td>
				<td align="center"><?php echo $_SESSION["K02_Keiyaku_KiteiJikan"] ?></td>
				<td align="center"><?php echo $_SESSION["K02_Keiyaku_KyuyoNo"] ?></td>
				<td align="center">
					<input type="button" id="shusei" name="shusei" onClick="sbmfnc(this,'');" style="cursor: pointer" value="修正" />
				</td>
			</tr>
		</table>
	<?php } ?>
	<BR>
	<table border="0" width="100%">
		<tr align="center">
			<td><font size="5" color="#ff0000"><?php echo $_SESSION["ErrMsg2"] ?></font></td>
		</tr>
	</table>
	<table border="0" width="100%">
		<tr>
			<td width="100" align="center">
				<input type="button" id="kakunin" name="kakunin" onClick="sbmfnc(this,'');" style="cursor: pointer" value="確認" />
			</td>
		</tr>
	</table>

	<BR>
	<table border="0" >
		<tr>
			<td align="center" colspan="17" bgcolor="<?php echo TEACHR_COLOR ?>"><font color="#ff0000"><B>登　　録　　情　　報　　（内容を確認して登録ボタンを押してください。）</B></font></td>
		</tr>
		<tr>
			<td  width="80" align="center" bgcolor="#c0c0c0">教師ID</td>
			<td  width="200" align="center" bgcolor="#c0c0c0">教師氏名</td>
			<td  width="200" align="center" bgcolor="#c0c0c0">教師かな</td>
			<td  width="80" align="center" bgcolor="#c0c0c0">生徒ID</td>
			<td  width="200" align="center" bgcolor="#c0c0c0">生徒氏名</td>
			<td  width="200" align="center" bgcolor="#c0c0c0">生徒かな</td>
			<td  width="50" align="center" bgcolor="#c0c0c0">枝番</td>
			<td  width="100" align="center" bgcolor="#c0c0c0">契約開始</td>
			<td  width="100" align="center" bgcolor="#c0c0c0">契約終了</td>
			<td  width="100" align="center" bgcolor="#c0c0c0">学年</td>
			<td  width="100" align="center" bgcolor="#c0c0c0">種別</td>
			<td  width="100" align="center" bgcolor="#c0c0c0">コース</td>
			<td  width="80" align="center" bgcolor="#c0c0c0">時給</td>
			<td  width="80" align="center" bgcolor="#c0c0c0">回数</td>
			<td  width="80" align="center" bgcolor="#c0c0c0">時間</td>
			<td  width="80" align="center" bgcolor="#c0c0c0">給与NO</td>
			<td  width="80" align="center" bgcolor="#c0c0c0">削除</td>
		</tr>
		<?php for($data=0; $data < $_SESSION["K02_DataCnt"]; $data++){?>
			<tr>
				<td align="center"><?php echo $_SESSION["K02_TeacherID2_" . $data] ?></td>
				<td align="center"><?php echo $_SESSION["K02_TeacherName1_" . $data] ?></td>
				<td align="center"><?php echo $_SESSION["K02_TeacherName2_" . $data] ?></td>
				<td align="center"><?php echo $_SESSION["K02_StudentID_" . $data] ?></td>
				<td align="center"><?php echo $_SESSION["K02_StudentName1_" . $data] ?></td>
				<td align="center"><?php echo $_SESSION["K02_StudentName2_" . $data] ?></td>
				<td align="center"><?php echo $_SESSION["K02_Seq_" . $data] ?></td>
				<td align="center"><?php echo $_SESSION["K02_StartDay_" . $data] ?></td>
				<td align="center"><?php echo $_SESSION["K02_EndDay_" . $data] ?></td>
				<td align="center"><?php echo $_SESSION["K02_gread_" . $data] ?></td>
				<td align="center"><?php echo $_SESSION["K02_Orgtype_" . $data] ?></td>
				<td align="center"><?php echo $_SESSION["K02_course_" . $data] ?></td>
				<td align="center"><?php echo $_SESSION["K02_Pay_" . $data] ?></td>
				<td align="center"><?php echo $_SESSION["K02_KiteiKaisu_" . $data] ?></td>
				<td align="center"><?php echo $_SESSION["K02_KiteiJikan_" . $data] ?></td>
				<td align="center"><?php echo $_SESSION["K02_KyuyoNo_" . $data] ?></td>
				<td align="center">
					<input type="submit" name="delete_<?php echo $data ?>" style="cursor: pointer" value="削除" />
				</td>
			</tr>
				<input type="hidden" name="postshori" value="">
				<input type="hidden" name="valuename_<?php echo $data ?>" value="<?php echo $data ?>">
				<input type="hidden" name="K02_TeacherID_<?php echo $data ?>" value="<?php echo $_SESSION["K02_TeacherID2_" . $data]; ?>">
				<input type="hidden" name="K02_StudentID_<?php echo $data ?>" value="<?php echo $_SESSION["K02_StudentID_" . $data]; ?>">
				<input type="hidden" name="K02_Seq_<?php echo $data ?>" value="<?php echo $_SESSION["K02_Seq_" . $data]; ?>">
		<?php } ?>
	</table>
	<BR><BR>
	<table border="0" width="100%">
		<tr>
			<td width="100" align="center">
				登録ＩＤ
				<input class="inputtype" type="text" size="10" maxlength="10" name="K02_EntryID" style="ime-mode: disabled;" value="<?php echo $_SESSION["K02_EntryID"] ?>" onkeyup="checkInputText(this)">
				登録日
				<input class="inputtype" type="text" size="10" maxlength="10" name="K02_EntryTime" style="ime-mode: disabled;" value="<?php echo $_SESSION["K02_EntryTime"] ?>">
			</td>
		</tr>
		<tr>
			<td width="100" align="center">
				<input type="button" id="newdate" name="newdate" onClick="sbmfnc(this,'');" style="cursor: pointer" value="登録" />
			</td>
		</tr>
	</table>
	<BR><BR>
	<table border="0" >
		<tr>
			<td align="center" colspan="16" bgcolor="#c0c0c0"><B>登録しました</B></td>
		</tr>
		<tr>
			<td  width="80" align="center" bgcolor="#c0c0c0">教師ID</td>
			<td  width="200" align="center" bgcolor="#c0c0c0">教師氏名</td>
			<td  width="200" align="center" bgcolor="#c0c0c0">教師かな</td>
			<td  width="80" align="center" bgcolor="#c0c0c0">生徒ID</td>
			<td  width="200" align="center" bgcolor="#c0c0c0">生徒氏名</td>
			<td  width="200" align="center" bgcolor="#c0c0c0">生徒かな</td>
			<td  width="50" align="center" bgcolor="#c0c0c0">枝番</td>
			<td  width="100" align="center" bgcolor="#c0c0c0">契約開始</td>
			<td  width="100" align="center" bgcolor="#c0c0c0">契約終了</td>
			<td  width="100" align="center" bgcolor="#c0c0c0">学年</td>
			<td  width="100" align="center" bgcolor="#c0c0c0">種別</td>
			<td  width="100" align="center" bgcolor="#c0c0c0">コース</td>
			<td  width="80" align="center" bgcolor="#c0c0c0">時給</td>
			<td  width="80" align="center" bgcolor="#c0c0c0">回数</td>
			<td  width="80" align="center" bgcolor="#c0c0c0">時間</td>
			<td  width="80" align="center" bgcolor="#c0c0c0">給与NO</td>
		</tr>
		<?php for($data=0; $data < $_SESSION["ZUMI_K02_DataCnt"]; $data++){?>
			<tr>
				<td align="center"><?php echo $_SESSION["ZUMI_K02_TeacherID2_" . $data] ?></td>
				<td align="center"><?php echo $_SESSION["ZUMI_K02_TeacherName1_" . $data] ?></td>
				<td align="center"><?php echo $_SESSION["ZUMI_K02_TeacherName2_" . $data] ?></td>
				<td align="center"><?php echo $_SESSION["ZUMI_K02_StudentID_" . $data] ?></td>
				<td align="center"><?php echo $_SESSION["ZUMI_K02_StudentName1_" . $data] ?></td>
				<td align="center"><?php echo $_SESSION["ZUMI_K02_StudentName2_" . $data] ?></td>
				<td align="center"><?php echo $_SESSION["ZUMI_K02_Seq_" . $data] ?></td>
				<td align="center"><?php echo $_SESSION["ZUMI_K02_StartDay_" . $data] ?></td>
				<td align="center"><?php echo $_SESSION["ZUMI_K02_EndDay_" . $data] ?></td>
				<td align="center"><?php echo $_SESSION["ZUMI_K02_gread_" . $data] ?></td>
				<td align="center"><?php echo $_SESSION["ZUMI_K02_Orgtype_" . $data] ?></td>
				<td align="center"><?php echo $_SESSION["ZUMI_K02_course_" . $data] ?></td>
				<td align="center"><?php echo $_SESSION["ZUMI_K02_Pay_" . $data] ?></td>
				<td align="center"><?php echo $_SESSION["ZUMI_K02_KiteiKaisu_" . $data] ?></td>
				<td align="center"><?php echo $_SESSION["ZUMI_K02_KiteiJikan_" . $data] ?></td>
				<td align="center"><?php echo $_SESSION["ZUMI_K02_KyuyoNo_" . $data] ?></td>
			</tr>
		<?php } ?>
	</table>
</form>
</body>
</CENTER>
</html>