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

	if(isset($_POST['S04_Orgtype'])){
		if($_SESSION["S04_Orgtype"] != $_POST['S04_Orgtype']){
			$_SESSION["ShoriID"]="OrgtypeSEL";
		}
	}
	if(isset($_POST['S04_EndFlg'])){
		if($_SESSION["S04_EndFlg"] != $_POST['S04_EndFlg']){
			$_SESSION["ShoriID"]="ENDKUBUN";
		}
	}
	if(isset($_POST['submitter'])){
		if($_POST['submitter']=="modoru"){
		 	ModoruShori($_SESSION["S04_Kanri02_RPID"]);
			exit;
		}
	}

	if(isset($_POST['submitter'])){
		switch ($_POST['submitter']){
			case 'update':
				$_SESSION["ShoriID"]="UPDATE";
				break;
			case 'newdate':
				$_SESSION["ShoriID"]="NEW";
				break;
			case 'kaijyo':
				$_SESSION["ShoriID"]="KAIJYO";
				break;
			case 'updatekanri':
				$_SESSION["ShoriID"]="UPDATEKANRI";
				break;
			case 'deletekanri':
				$_SESSION["ShoriID"]="DELETEKANRI";
				break;
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
			$_SESSION["S04_Kanri02_RPID"] = $_GET['RPID'];
		      	//print($_SESSION["S04_Kanri02_RPID"]);
		}
		if(isset($_GET['MODE'])) {
			$_SESSION["S04_Kanri02_MODE"] = $_GET['MODE'];
			$_SESSION["ShoriID"] = $_GET['MODE'];
		      	//print($_SESSION["S04_Kanri02_MODE"] . "<BR>");

			if($_SESSION["S04_Kanri02_MODE"] == "UPD"){
				$_SESSION["S04_LoginTeacherID"] = $_SESSION["LoginTeacherID"];
				$_SESSION["S04_LoginTName1"] = $_SESSION["LoginTName1"];
				$_SESSION["S04_LoginTName2"] = $_SESSION["LoginTName2"];
			}
		}

		if(isset($_GET['KEY1'])) {
			$_SESSION["S04_Kanri02_Kensaku_KEY1"] = $_GET['KEY1'];
		}
		if(isset($_GET['KEY1'])) {
			$_SESSION["S04_Kanri02_Kensaku_KEY2"] = $_GET['KEY2'];
		}
		if(isset($_GET['AtenaSEQ'])) {
			$_SESSION["S04_Kanri02_Kensaku_AtenaSEQ"] = $_GET['AtenaSEQ'];
		}
		if(isset($_GET['SEQ'])) {
			$_SESSION["S04_Kanri02_Kensaku_SEQ"] = $_GET['SEQ'];
		}
		if(isset($_GET['SENTEI'])) {
			//0:選定　1:契約　9:選定解除
			$_SESSION["S04_Kanri02_Kensaku_SENTEI"] = $_GET['SENTEI'];
		}

		//セッション情報保存
		//前画面からの情報
		$TeacherID = $_SESSION["TeacherID"];
		$user_name = $_SESSION["user_name"];
		$shikaku = (int) $_SESSION["shikaku"];
		$TName1 = $_SESSION["TName1"];
		$TName2 = $_SESSION["TName2"];

print("ShoriID=" . $_SESSION["ShoriID"]);
		switch ($_SESSION["ShoriID"]){
			case 'UPD':
				SessionClear();
//				if($_SESSION["S04_Kanri02_Kensaku_SENTEI"] == 1){
					GetData();
//				}else{
//					GetData2();
//				}
				if($_SESSION["S04_Orgtype"]=='06'){
					$CodeData = array();
					$CodeData = GetCodeData("LD契約コース","","",1);
					$_SESSION["31CodeData"]=$CodeData;
				}else{
					$CodeData = array();
					$CodeData = GetCodeData("契約コース","","",1);
					$_SESSION["19CodeData"]=$CodeData;
				}
				$CodeData2 = array();
				$CodeData2 = GetCodeData("契約種別","","",1);
				$_SESSION["20CodeData"]=$CodeData2;
				$CodeData2 = array();
				$CodeData2 = GetCodeData("終了区分","","",1);
				$_SESSION["21CodeData"]=$CodeData2;
				$CodeData2 = array();
				$CodeData2 = GetCodeData("契約コースその他","","",1);
				$_SESSION["26CodeData"]=$CodeData2;

				break;
			case 'UPDATE':
				SaveShori();
				$Msg = CheckShori();
//print("Msg=" . $Msg);

				if($Msg ==""){
//print("UPDShori①");
					$EMSG = UPDShori();
					$_SESSION["ErrMsg"] = $EMSG;
					GetData();
					$_SESSION["UPD_DIS"] = "DISABLED";
				}
				break;
			case 'UPDATE2':
//				SaveShori();
//				$Msg = CheckShori();
//				if($Msg ==""){
					$EMSG = UPDShori();
					$_SESSION["ErrMsg"] = $EMSG;
//				}
				break;
			case 'NEW':
				SaveShori();
//				if($_SESSION["shikaku1"]==0 || $_SESSION["S04_StartDay"] !=""){ //資格=強制更新権限
				if($_SESSION["S04_StartDay"] !=""){ //資格=強制更新権限
					$Msg = CheckShoriNEW();
					if($Msg ==""){
						$EMSG = NEWShori(); //契約登録
						$_SESSION["ErrMsg"] = $EMSG;
					}
				}else{
					if($Msg ==""){
						$EMSG = NEWShori2(); //選定のまま更新
						$_SESSION["ErrMsg"] = $EMSG;
					}
				}

				break;
			case 'ENDKUBUN':
				SaveShori();
				$_SESSION["ENDRIYU"] = 1;
				break;
			case 'KAIJYO':
				$EMSG = KaijyoShori();
				if($EMSG == ""){
					$no_login_url = "S03_index.php?MODE=SENTEI&RPID=S02_Kensaku&KEY1=" . $_SESSION["S04_StudentID"] . "&KUBUN=1&Seq=" . $_SESSION["S04_Kanri02_Kensaku_AtenaSEQ"];
					header("Location: {$no_login_url}");
				}
				break;
			case 'UPDATEKANRI':
				SaveShori();
				$Msg = CheckShori();

				if($Msg ==""){
					$_SESSION["UpdFlg"] = 0;
					$EMSG = UPDShori();
					$_SESSION["ErrMsg"] = $EMSG;
					GetData();
					$_SESSION["UPD_DIS"] = "DISABLED";
				}
				break;
			case 'DELETEKANRI':
				SaveShori();
//				$Msg = CheckShori();

//				if($Msg ==""){
//					$_SESSION["UpdFlg"] = 0;
					$EMSG = DLTShori();
					$_SESSION["ErrMsg"] = $EMSG;
					GetData();
//					$_SESSION["UPD_DIS"] = "DISABLED";
//				}
				break;
			case 'OrgtypeSEL':
				SaveShori();
				if($_SESSION["S04_Orgtype"]=='06'){
					$CodeData = array();
					$CodeData = GetCodeData("LD契約コース","","",1);
					$_SESSION["31CodeData"]=$CodeData;
				}else{
					$CodeData = array();
					$CodeData = GetCodeData("契約コース","","",1);
					$_SESSION["19CodeData"]=$CodeData;
				}
				break;
				
		}
	}
//-----------------------------------------------------------
//	セッション情報クリア
//-----------------------------------------------------------
function SessionClear(){

	$_SESSION["S04_TeacherID"] = $_SESSION["S04_Kanri02_Kensaku_KEY2"];
	$_SESSION["S04_StudentID"] = $_SESSION["S04_Kanri02_Kensaku_KEY1"];
	$_SESSION["S04_AtenaSeq"] = $_SESSION["S04_Kanri02_Kensaku_AtenaSEQ"];
	$_SESSION["S04_Seq"] = $_SESSION["S04_Kanri02_Kensaku_SEQ"];
	$_SESSION["TeacherName"] = "";
	$_SESSION["Tel"] = "";
	$_SESSION["Mail"] = "";

	$_SESSION["S04_StartDay"] = "";
	$_SESSION["S04_EndDay"] = "";
	$_SESSION["S04_EndFlg"] = "";
	$_SESSION["S04_Orgtype"] = "";
	$_SESSION["S04_course"] = "";
	$_SESSION["S04_course2"] = "";
	$_SESSION["S04_Pay"] = "";
	$_SESSION["S04_KiteiKaisu"] = "";
	$_SESSION["S04_KiteiJikan"] = "";
	$_SESSION["S04_KiteiKingaku"] = "";
	$_SESSION["S04_KiteiKotuhi"] = "";
	$_SESSION["S04_KyuyoNo"] = "";
	$_SESSION["S04_Kyoka"] = "";
	$_SESSION["ErrMsg"] = "";
	$_SESSION["WarningMsg"] = "";
	$_SESSION["SesshoWarningMsg"] = "";

	$_SESSION["ENDRIYU"] = 0;

	$_SESSION["S04_THouho"] = "";
	$_SESSION["S04_TAite"] = "";
	$_SESSION["S04_SHouho"] = "";
	$_SESSION["S04_SAite"] = "";
	$_SESSION["S04_Naiyo"] = "";

	if($_SESSION["S04_Kanri02_Kensaku_SENTEI"] != 1){
		$_SESSION["UPD_DIS"] = "DISABLED";
	}else{
		$_SESSION["UPD_DIS"] = "";
	}
}
//-----------------------------------------------------------
//	セーブ処理
//-----------------------------------------------------------
function SaveShori(){

	$_SESSION["S04_StartDay"] = $_POST['S04_StartDay'];
	$_SESSION["S04_EndDay"] =  $_POST['S04_EndDay'];
	$_SESSION["S04_Orgtype"] =  $_POST['S04_Orgtype'];
	$_SESSION["S04_course"] =  $_POST['S04_course'];
	$_SESSION["S04_course2"] =  $_POST['S04_course2'];
	$_SESSION["S04_Pay"] =  $_POST['S04_Pay'];
	$_SESSION["S04_KiteiKaisu"] =  $_POST['S04_KiteiKaisu'];
	$_SESSION["S04_KiteiJikan"] =  $_POST['S04_KiteiJikan'];
	$_SESSION["S04_KiteiKingaku"] =  $_POST['S04_KiteiKingaku'];
	$_SESSION["S04_KiteiKotuhi"] =  $_POST['S04_KiteiKotuhi'];
	$_SESSION["S04_KyuyoNo"] =  $_POST['S04_KyuyoNo'];
	$_SESSION["S04_Kyoka"] =  $_POST['S04_Kyoka'];
	$_SESSION["S04_EndFlg"] =  $_POST['S04_EndFlg'];

	if($_SESSION["ENDRIYU"] == 1){
		$_SESSION["S04_THouho"] = $_POST['S04_THouho'];
		$_SESSION["S04_TAite"] = $_POST['S04_TAite'];
		$_SESSION["S04_SHouho"] = $_POST['S04_SHouho'];
		$_SESSION["S04_SAite"] = $_POST['S04_SAite'];
		$_SESSION["S04_Naiyo"] = $_POST['S04_Naiyo'];
	}
}

//-----------------------------------------------------------
//	データ取得
//-----------------------------------------------------------
Function GetData(){

		// mysqlへの接続
		$mysqli = new mysqli(HOST, USER, PASS);
		if ($mysqli->connect_errno) {
			print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
			exit();
		}

		// データベースの選択
		$mysqli->select_db(DBNAME);
		$mysqli->set_charset("utf8");

		// クエリの実行
		$query = "SELECT * FROM  T_TantoShosai ";
		$query = $query . " WHERE StudentID = '" . $_SESSION["S04_StudentID"] . "'";
		$query = $query . " And TeacherID = '" . $_SESSION["S04_TeacherID"] . "'";
		$query = $query . " And AtenaSeq = '" . $_SESSION["S04_AtenaSeq"] . "'";
		$query = $query . " And Seq = '" . $_SESSION["S04_Seq"] . "'";
		$query = $query . " ORDER BY TeacherID ASC,StartDay DESC ";

		$result = $mysqli->query($query);

		//print($query ."<BR>");

		if (!$result) {
			print('クエリーが失敗しました。' . $mysqli->error);
			$mysqli->close();
			exit();
		}
		

		$data = array();
		$i = 0;
		while($arr_item = $result->fetch_assoc()){

			//レコード内の各フィールド名と値を順次参照
			foreach($arr_item as $key => $value){
				//フィールド名と値を表示
				$_SESSION["S04_" . $key] = $value;
				$_SESSION["moto_S04_" . $key] = $value;

			}
			if(($_SESSION["S04_Kanri02_Kensaku_SENTEI"] != 1) && (!is_null($_SESSION["S04_EndDay"]))){
				$_SESSION["UPD_DIS"]="DISABLED";
			}

			//------教師名取得------
			$query2 = "SELECT a.Name1,a.Name2,b.Mail1,b.Mail2,b.Tel1,b.Tel2,b.Tel3 FROM T_AtenaInfo as a";
			$query2 = $query2 . " inner join T_KihonInfo as b";
			$query2 = $query2 . " on a.TeacherID = b.TeacherID";
			$query2 = $query2 . " WHERE a.TeacherID = '" . $_SESSION["S04_TeacherID"] . "'";

			$result2 = $mysqli->query($query2);

			//print($query2 ."<BR>");

			if (!$result2) {
				print('クエリーが失敗しました。T_AtenaInfo' . $mysqli->error);
				$mysqli->close();
				exit();
			}
			while ($row = $result2->fetch_assoc()) {
				//フィールド名と値を表示
				$_SESSION["S04_Name1"] = $row['Name1'];
				$_SESSION["S04_Name2"] = $row['Name2'];
				$_SESSION["S04_Mail1"] = $row['Mail1'];
				$_SESSION["S04_Mail2"] = $row['Mail2'];
				$_SESSION["S04_Tel1"] = $row['Tel1'];
				$_SESSION["S04_Tel2"] = $row['Tel2'];
				$_SESSION["S04_Tel3"] = $row['Tel3'];
			}

			$_SESSION["TeacherName"] = $_SESSION["S04_Name1"];
			$Mail = $_SESSION["S04_Mail1"];
			if($_SESSION["S04_Mail2"] != ""){
				$Mail = $Mail . "<BR>" . $_SESSION["S04_Mail2"];
			}
			$_SESSION['Mail'] = $Mail;

			$Tel = $_SESSION['S04_Tel1'];
			if($_SESSION['S04_Tel2'] != ""){
				$Tel = $Tel . "<BR>" . $_SESSION['S04_Tel2'];
			}
			if($_SESSION['S04_Tel3'] != ""){
				$Tel = $Tel . "<BR>" . $_SESSION['S04_Tel3'];
			}
			$_SESSION['Tel'] = $Tel;

			$_SESSION["moto_TeacherName"] = $_SESSION["S04_Name1"];
			$_SESSION['moto_Mail'] = $Mail;
			$_SESSION['moto_Tel'] = $Tel;

			if($_SESSION["S04_EndFlg"] != ""){
				$_SESSION["ENDRIYU"] = 1;
			}

			//------折衝情報取得------
			$query3 = "SELECT THouho,SHouho,TAite,SAite,Naiyo FROM TS_SeshoInfo as a";
			$query3 = $query3 . " WHERE TeacherID = '" . $_SESSION["S04_TeacherID"] . "'";
			$query3 = $query3 . " And StudentID = '" . $_SESSION["S04_StudentID"] . "'";
			$query3 = $query3 . " And AtenaSeq = '" . $_SESSION["S04_AtenaSeq"] . "'";
			$query3 = $query3 . " And EndSeq = '" . $_SESSION["S04_Seq"] . "'";

			$result3 = $mysqli->query($query3);

			//print($query3 ."<BR>");

			if (!$result3) {
				print('クエリーが失敗しました。TS_SeshoInfo' . $mysqli->error);
				$mysqli->close();
				exit();
			}
			while ($row = $result3->fetch_assoc()) {
				//フィールド名と値を表示
				$_SESSION["S04_THouho"] = $row['THouho'];
				$_SESSION["S04_SHouho"] = $row['SHouho'];
				$_SESSION["S04_TAite"] = $row['TAite'];
				$_SESSION["S04_SAite"] = $row['SAite'];
				$_SESSION["S04_Naiyo"] = $row['Naiyo'];
			}

			$i++;
		}

		//選定の場合
		if($_SESSION["S04_Kanri02_Kensaku_SENTEI"] == 0){
			// クエリの実行
			$query = "SELECT * FROM  S_SenteiInfo ";
			$query = $query . " WHERE StudentID = '" . $_SESSION["S04_StudentID"] . "'";
			$query = $query . " And TeacherID = '" . $_SESSION["S04_TeacherID"] . "'";
			$query = $query . " And AtenaSeq = '" . $_SESSION["S04_AtenaSeq"] . "'";
			$query = $query . " And Seq = '" . $_SESSION["S04_Seq"] . "'";

			$result = $mysqli->query($query);

			//print($query ."<BR>");

			if (!$result) {
				print('クエリーが失敗しました。' . $mysqli->error);
				$mysqli->close();
				exit();
			}
			

			$data = array();
			$i = 0;
			while($arr_item = $result->fetch_assoc()){

				//レコード内の各フィールド名と値を順次参照
				foreach($arr_item as $key => $value){
					//フィールド名と値を表示
					$_SESSION["S04_" . $key] = $value;
				}
			}
		}

		$_SESSION["DateCount"] = count($data);	//データ件数

	 	// データベースの切断
		$mysqli->close();
}
//-----------------------------------------------------------
//	データ取得
//-----------------------------------------------------------
Function GetData2(){
		// mysqlへの接続
		$mysqli = new mysqli(HOST, USER, PASS);
		if ($mysqli->connect_errno) {
			print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
			exit();
		}

		// データベースの選択
		$mysqli->select_db(DBNAME);
		$mysqli->set_charset("utf8");

		//------教師名取得------
		$query2 = "SELECT a.Name1,a.Name2,b.Mail1,b.Mail2,b.Tel1,b.Tel2,b.Tel3 FROM T_AtenaInfo as a";
		$query2 = $query2 . " inner join T_KihonInfo as b";
		$query2 = $query2 . " on a.TeacherID = b.TeacherID";
		$query2 = $query2 . " WHERE a.TeacherID = '" . $_SESSION["S04_TeacherID"] . "'";

		$result2 = $mysqli->query($query2);

		//print($query2 ."<BR>");

		if (!$result2) {
			print('クエリーが失敗しました。T_AtenaInfo' . $mysqli->error);
			$mysqli->close();
			exit();
		}
		while ($row = $result2->fetch_assoc()) {
			//フィールド名と値を表示
			$_SESSION["S04_Name1"] = $row['Name1'];
			$_SESSION["S04_Name2"] = $row['Name2'];
			$_SESSION["S04_Mail1"] = $row['Mail1'];
			$_SESSION["S04_Mail2"] = $row['Mail2'];
			$_SESSION["S04_Tel1"] = $row['Tel1'];
			$_SESSION["S04_Tel2"] = $row['Tel2'];
			$_SESSION["S04_Tel3"] = $row['Tel3'];
		}

		$_SESSION["TeacherName"] = $_SESSION["S04_Name1"];
		$Mail = $_SESSION["S04_Mail1"];
		if($_SESSION["S04_Mail2"] != ""){
			$Mail = $Mail . "<BR>" . $_SESSION["S04_Mail2"];
		}
		$_SESSION['Mail'] = $Mail;

		$Tel = $_SESSION['S04_Tel1'];
		if($_SESSION['S04_Tel2'] != ""){
			$Tel = $Tel . "<BR>" . $_SESSION['S04_Tel2'];
		}
		if($_SESSION['S04_Tel3'] != ""){
			$Tel = $Tel . "<BR>" . $_SESSION['S04_Tel3'];
		}
		$_SESSION['Tel'] = $Tel;

		$_SESSION["moto_TeacherName"] = $_SESSION["S04_Name1"];
		$_SESSION['moto_Mail'] = $Mail;
		$_SESSION['moto_Tel'] = $Tel;

	 	// データベースの切断
		$mysqli->close();

}
//-----------------------------------------------------------
//	チェック処理
//-----------------------------------------------------------
function CheckShori(){
$ErrMsg="";
$WarningMsg="";
$_SESSION["ErrMsg"] = "";
$_SESSION["WarningMsg"] = "";
$_SESSION["SesshoWarningMsg"] = "";
$_SESSION["UpdFlg"] = 0;
$StartDay = "";
$EndDay = "";

	//開始日
	if($_SESSION["S04_StartDay"] != ""){
		if (strptime($_SESSION["S04_StartDay"], '%Y-%m-%d')) {
		}else{
			$ErrMsg = "日付が不正です。";
			$_SESSION["S04_StartDay"] = "";
			$_SESSION["ErrMsg"] = $ErrMsg;
			return $ErrMsg;
		}
	}else{
		$ErrMsg = "開始日を入力してください。";
		$_SESSION["ErrMsg"] = $ErrMsg;
		return $ErrMsg;
	}
	//終了日
	if($_SESSION["S04_EndDay"] != ""){
		if (strptime($_SESSION["S04_EndDay"], '%Y-%m-%d')) {
		}else{
			$ErrMsg = "日付が不正です。";
			$_SESSION["S04_EndDay"] = "";
			$_SESSION["ErrMsg"] = $ErrMsg;
			return $ErrMsg;
		}
	}
	//コース
	if($_SESSION["S04_course"] == ""){
		$ErrMsg = "コースを選択してください。";
		$_SESSION["ErrMsg"] = $ErrMsg;
		return $ErrMsg;
	}

	//時給・回数・時間
	//S_AtenaInfo/T_Tanto/T_TantoShosai
	if($_SESSION["ShoriID"]=="UPDATE"){
		if($_SESSION["moto_S04_Pay"] != ""){
			if($_SESSION["S04_Pay"] != $_SESSION["moto_S04_Pay"]){
				$WarningMsg = " 時給:" . $_SESSION["moto_S04_Pay"] . "⇒" . $_SESSION["S04_Pay"];
			}
		}
		if($_SESSION["moto_S04_KiteiKaisu"] != ""){
			if($_SESSION["S04_KiteiKaisu"] != $_SESSION["moto_S04_KiteiKaisu"]){
				$WarningMsg = $WarningMsg . " 規定回数:" . $_SESSION["moto_S04_KiteiKaisu"] . "⇒" . $_SESSION["S04_KiteiKaisu"];
			}
		}
		if($_SESSION["moto_S04_KiteiJikan"] != ""){
			if($_SESSION["S04_KiteiJikan"] != $_SESSION["moto_S04_KiteiJikan"]){
				$WarningMsg = $WarningMsg . " 規定時間:" . $_SESSION["moto_S04_KiteiJikan"] . "⇒" . $_SESSION["S04_KiteiJikan"];
			}
		}
		if($WarningMsg != ""){
//			$WarningMsg2 = $WarningMsg . "を変更します。よろしいですか。";
			$WarningMsg3 = $WarningMsg . "を変更。";
			$_SESSION["SesshoWarningMsg"] = $WarningMsg3 . $_SESSION["S04_Naiyo"];

			$StartDay = date('Y/m/d', strtotime($_SESSION["S04_StartDay"]));
			$TodayD = date('Y/m/d', strtotime($_SESSION["Today"]));
			if($StartDay<=$TodayD){
				$_SESSION["UpdFlg"] = 1; //規定が変更された場合
			}
			if($_SESSION["UpdFlg"] == 1 && $_SESSION["S04_EndDay"] == ""){
				$ErrMsg = $WarningMsg . "を変更する場合は新規契約となります。<BR>現在の契約の終了日（終了予定日）を入力してください。<BR>契約変更でない場合は職権修正ボタンを押してください。";
				$_SESSION["ErrMsg"] = $ErrMsg;
				return $ErrMsg;
			}

		}

	}

	//開始日終了日の前後関係チェック
//print("S04_StartDay" .$_SESSION["S04_StartDay"] ."<BR>");
//print("S04_EndDay" .$_SESSION["S04_EndDay"] ."<BR>");

	if($_SESSION["S04_StartDay"] != "" && $_SESSION["S04_EndDay"] != ""){	
		$StartDay = date('Y/m/d', strtotime($_SESSION["S04_StartDay"]));
		$EndDay = date('Y/m/d', strtotime($_SESSION["S04_EndDay"]));

		if($StartDay > $EndDay){
			$ErrMsg = "終了日が開始日より前の日付です。";
			$_SESSION["ErrMsg"] = $ErrMsg;
			return $ErrMsg;
		}
	}

	if($_SESSION["UpdFlg"] == 0){
		//終了日の終了区分
		if($_SESSION["S04_EndDay"] != "" && $_SESSION["S04_EndFlg"] == ""){	
			$ErrMsg = "終了区分を入力してください。";
			$_SESSION["ErrMsg"] = $ErrMsg;
			return $ErrMsg;
		}

		//終了日
		if($_SESSION["S04_EndDay"] == "" && $_SESSION["S04_EndFlg"] != ""){	
			$ErrMsg = "終了日を入力してください。";
			$_SESSION["ErrMsg"] = $ErrMsg;
			return $ErrMsg;
		}

		//終了理由
		if($_SESSION["S04_EndDay"] != "" && $_SESSION["S04_Naiyo"] == ""){
			$ErrMsg = "終了理由を入力してください。";
			$_SESSION["ErrMsg"] = $ErrMsg;
			return $ErrMsg;
		}
	}

}
//-----------------------------------------------------------
//	チェック処理(NEWのとき）
//-----------------------------------------------------------
function CheckShoriNEW(){
$ErrMsg="";
$_SESSION["ErrMsg"] = "";

	//開始日
	if($_SESSION["S04_StartDay"] != ""){
		if (strptime($_SESSION["S04_StartDay"], '%Y-%m-%d')) {
		}else{
			$ErrMsg = "日付が不正です。";
			$_SESSION["S04_StartDay"] = "";
			$_SESSION["ErrMsg"] = $ErrMsg;
			return $ErrMsg;
		}
	}else{

		$ErrMsg = "開始日を入力してください。";
		$_SESSION["ErrMsg"] = $ErrMsg;

		return $ErrMsg;
	}

}
//-----------------------------------------------------------
//	登録処理　契約登録
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

	$query = "INSERT INTO T_TantoShosai ";
	$query = $query . "values(";
	$query = $query . "'" . $_SESSION["S04_TeacherID"] . "'";
	$query = $query . ",'" . $_SESSION["S04_StudentID"] . "'";
	$query = $query . "," . $_SESSION["S04_AtenaSeq"];
	$query = $query . "," . $_SESSION["S04_Seq"];
//	$query = $query . ",'" . $_SESSION["S04_StartDay"] . "'";
	if($_SESSION["S04_StartDay"] !=""){
		$query = $query . ",'" . $_SESSION["S04_StartDay"] . "'";
	}else{
		$query = $query . ",NULL";
	}
	if($_SESSION["S04_EndDay"] !=""){
		$query = $query . ",'" . $_SESSION["S04_EndDay"] . "'";
	}else{
		$query = $query . ",NULL";
	}
	$query = $query . ",NULL";
	$query = $query . ",NULL";
	$query = $query . ",'" . $_SESSION["S04_Orgtype"] . "'";
	$query = $query . ",'" . $_SESSION["S04_course"] . "'";
	$query = $query . ",'" . $_SESSION["S04_course2"] . "'";
	$query = $query . ",'" . $_SESSION["S04_Pay"] . "'";
	$query = $query . ",'" . $_SESSION["S04_KiteiKaisu"] . "'";
	$query = $query . ",'" . $_SESSION["S04_KiteiJikan"] . "'";
	$query = $query . ",'" . $_SESSION["S04_KiteiKingaku"] . "'";
	$query = $query . ",'" . $_SESSION["S04_KiteiKotuhi"] . "'";
	$query = $query . ",'" . $_SESSION["S04_Kyoka"] . "'";
	$query = $query . ",'" . $_SESSION["S04_KyuyoNo"] . "'";
	$query = $query . ",'1'";
	$query = $query . ",'" . $_SESSION["S04_LoginTeacherID"] . "'";
	$query = $query . ",'" . $_SESSION["Today"] . "'";
	$query = $query . ",'" . $_SESSION["S04_LoginTeacherID"] . "'";
	$query = $query . ",'" . $_SESSION["Today"] . "'";
	$query = $query . ")";
//print($query);
	$result = $mysqli->query($query);
	if (!$result) {
		$ErrMSG = "クエリーが失敗しました。T_TantoShosai" . $mysqli->error;
		$ErrFlg = 1;
	}

	if($ErrFlg == 0){
		$query2 = "INSERT INTO T_Tanto ";
		$query2 = $query2 . "values(";
		$query2 = $query2 . "'" . $_SESSION["S04_TeacherID"] . "'";
		$query2 = $query2 . ",'" . $_SESSION["S04_StudentID"] . "'";
		$query2 = $query2 . "," . $_SESSION["S04_AtenaSeq"];
		$query2 = $query2 . ",'" . $_SESSION["S04_StartDay"] . "'";
		if($_SESSION["S04_EndDay"] !=""){
			$query2 = $query2 . ",'" . $_SESSION["S04_EndDay"] . "'";
		}else{
			$query2 = $query2 . ",NULL";
		}
		$query2 = $query2 . ",'" . $_SESSION["S04_Orgtype"] . "'";
		$query2 = $query2 . ",'" . $_SESSION["S04_course"] . "'";
		$query2 = $query2 . ",'" . $_SESSION["S04_Pay"] . "'";
		$query2 = $query2 . ",'" . $_SESSION["S04_KiteiKaisu"] . "'";
		$query2 = $query2 . ",'" . $_SESSION["S04_KiteiJikan"] . "'";
		$query2 = $query2 . ",'" . $_SESSION["S04_KyuyoNo"] . "'";
		$query2 = $query2 . ",'1'";
		$query2 = $query2 . ",'" . $_SESSION["S04_LoginTeacherID"] . "'";
		$query2 = $query2 . ",'" . $_SESSION["Today"] . "'";
		$query2 = $query2 . ",'" . $_SESSION["S04_LoginTeacherID"] . "'";
		$query2 = $query2 . ",'" . $_SESSION["Today"] . "'";
		$query2 = $query2 . ")";

		//★$result2 = $mysqli->query($query2);

		//print($query2);

		//if (!$result2) {
		//	$ErrMSG = "クエリーが失敗しました。" . $mysqli->error;
		//	$ErrFlg = 1;
		//}

		if($ErrFlg == 0){
			$query3 = "UPDATE S_SenteiInfo SET JyotaiFlg = 1";
			$query3 = $query3 . " WHERE  TeacherID = '" . $_SESSION["S04_TeacherID"] . "'";
			$query3 = $query3 . " AND StudentID = '" . $_SESSION["S04_StudentID"] . "'";
			$query3 = $query3 . " AND AtenaSeq = '" . $_SESSION["S04_AtenaSeq"] . "'";
			$query3 = $query3 . " AND Seq = '" . $_SESSION["S04_Seq"] . "'";

			$result3 = $mysqli->query($query3);

			//print($query3);

			if (!$result3) {
				$ErrMSG = "クエリーが失敗しました。（選定処理エラー）" . $mysqli->error;
				$ErrFlg = 1;
			}

			if($ErrFlg == 0){
				
				// コミット
				$mysqli->query("commit");

				$RtnMSG = "登録しました。①";
				$mysqli->close();

				return $RtnMSG;
			}
		}
	}
	if($ErrFlg == 1){
		$mysqli->query("rollback");

		$mysqli->close();

		return $ErrMSG;
	}

}
//-----------------------------------------------------------
//	登録処理　選定登録
//-----------------------------------------------------------
Function NEWShori2(){
$ErrFlg = 0;

//print("NEWShori2");

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

	$query3 = "UPDATE S_SenteiInfo SET ";
	$query3 = $query3 . "Orgtype='" . $_SESSION["S04_Orgtype"] . "'";
	$query3 = $query3 . ",course='" . $_SESSION["S04_course"] . "'";
	$query3 = $query3 . ",course2='" . $_SESSION["S04_course2"] . "'";
	$query3 = $query3 . ",Pay='" . $_SESSION["S04_Pay"] . "'";
	$query3 = $query3 . ",KiteiKaisu='" . $_SESSION["S04_KiteiKaisu"] . "'";
	$query3 = $query3 . ",KiteiJikan='" . $_SESSION["S04_KiteiJikan"] . "'";
	$query3 = $query3 . ",KiteiKingaku='" . $_SESSION["S04_KiteiKingaku"] . "'";
	$query3 = $query3 . ",KiteiKotuhi='" . $_SESSION["S04_KiteiKotuhi"] . "'";
	$query3 = $query3 . ",Kyoka='" . $_SESSION["S04_Kyoka"] . "'";
	$query3 = $query3 . ",KyuyoNo='" . $_SESSION["S04_KyuyoNo"] . "'";
	$query3 = $query3 . " WHERE  TeacherID = '" . $_SESSION["S04_TeacherID"] . "'";
	$query3 = $query3 . " AND StudentID = '" . $_SESSION["S04_StudentID"] . "'";
	$query3 = $query3 . " AND AtenaSeq = '" . $_SESSION["S04_AtenaSeq"] . "'";
	$query3 = $query3 . " AND Seq = '" . $_SESSION["S04_Seq"] . "'";

	//print($query3);
	$result3 = $mysqli->query($query3);

	if (!$result3) {
		$ErrMSG = "クエリーが失敗しました。（選定処理エラー）" . $mysqli->error;
		$ErrFlg = 1;
	}

	if($ErrFlg == 0){
		
		// コミット
		$mysqli->query("commit");

		$RtnMSG = "登録しました。（選定登録）";
		$mysqli->close();

		return $RtnMSG;
	}

	if($ErrFlg == 1){
		$mysqli->query("rollback");

		$mysqli->close();

		return $ErrMSG;
	}

}
//-----------------------------------------------------------
//	更新処理
//-----------------------------------------------------------
Function UPDShori(){

//print("UPDShori");

$ErrFlg = 0;
$_SESSION["S04_SesshoSeq"] = 0;
$_SESSION["S04_EndSeq"] = 0;

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

	//シーケンス取得
	//SesshoSeq：同日に登録がある場合、シーケンス＋１
	$query = "Select SesshoSeq From TS_SeshoInfo WHERE";
	$query = $query . " TeacherID='" . $_SESSION["S04_TeacherID"] . "'";
	$query = $query . " AND StudentID='" . $_SESSION["S04_StudentID"] . "'";
	$query = $query . " AND AtenaSeq='" . $_SESSION["S04_AtenaSeq"] . "'";
	$query = $query . " AND SesshoDay='" . $_SESSION["Today"] . "'";
	$query = $query . " Order by SesshoSeq ASC";
	$result = $mysqli->query($query);

	//print($query ."<BR>");

	if (!$result) {
		$ErrMSG = "クエリーが失敗しました。(UPDShori-TS_SeshoInfo)" . $mysqli->error;
		$ErrFlg = 1;
	}
	while ($row = $result->fetch_assoc()) {
		//フィールド名と値を表示
		$_SESSION["S04_SesshoSeq"] = $row['SesshoSeq'];
	}

//***********************************************************************************************
	//EndSeq：EndFlgの最大を取得
	$query = "Select EndSeq From TS_SeshoInfo WHERE";
	$query = $query . " TeacherID='" . $_SESSION["S04_TeacherID"] . "'";
	$query = $query . " AND StudentID='" . $_SESSION["S04_StudentID"] . "'";
	$query = $query . " AND AtenaSeq='" . $_SESSION["S04_AtenaSeq"] . "'";
	$query = $query . " AND EndSeq IS NOT NULL";
	$query = $query . " Order by EndSeq DESC";
//	$result = $mysqli->query($query);

	//print($query ."<BR>");

	if (!$result) {
		$ErrMSG = "クエリーが失敗しました(UPDShori-TS_SeshoInfo2)。" . $mysqli->error;
		$ErrFlg = 1;
	}
	while ($row = $result->fetch_assoc()) {
		//フィールド名と値を表示
		$_SESSION["S04_EndSeq"] = $row['EndSeq'];
	}

	//EndSeq：EndFlgの最大を取得
	$query = "Select EndSeq From T_TantoShosai ";
	$query = $query . " WHERE  TeacherID = '" . $_SESSION["S04_TeacherID"] . "'";
	$query = $query . " AND StudentID = '" . $_SESSION["S04_StudentID"] . "'";
	$query = $query . " AND AtenaSeq = '" . $_SESSION["S04_AtenaSeq"] . "'";
	$query = $query . " AND Seq = '" . $_SESSION["S04_Seq"] . "'";
	$result = $mysqli->query($query);

	//print($query ."<BR>");

	if (!$result) {
		$ErrMSG = "クエリーが失敗しました。(UPDShori-T_TantoShosai)" . $mysqli->error;
		$ErrFlg = 1;
	}
	while ($row = $result->fetch_assoc()) {
		//フィールド名と値を表示
		$_SESSION["S04_SEL_EndSeq"] = $row['EndSeq'];
	}
//***********************************************************************************************

//print("UpdFlg=" . $_SESSION["UpdFlg"] . "<BR>");
	$S04_SesshoSeq = $_SESSION["S04_SesshoSeq"] + 1;
//print($_SESSION["S04_SesshoSeq"] . "<BR>");
//print($S04_SesshoSeq . "<BR>");

//	$S04_EndSeq = $_SESSION["S04_EndSeq"] + 1;

	if($_SESSION["UpdFlg"] == 1){
		//============時給・回数・時間変更あり============
		//-----T_TantoShosaiアップデート-----
		$query = "UPDATE T_TantoShosai SET EndDay='" . $_SESSION["S04_EndDay"] . "'";
		$query = $query . " ,EndFlg='". S_JYOTAI_KEIYAKUHENKOU . "'";
		$query = $query . " ,EndSeq='". $_SESSION["S04_Seq"] . "'";
		$query = $query . " WHERE  TeacherID = '" . $_SESSION["S04_TeacherID"] . "'";
		$query = $query . " AND StudentID = '" . $_SESSION["S04_StudentID"] . "'";
		$query = $query . " AND AtenaSeq = '" . $_SESSION["S04_AtenaSeq"] . "'";
		$query = $query . " AND Seq = '" . $_SESSION["S04_Seq"] . "'";

		$result = $mysqli->query($query);

		//print($query ."<BR>");

		if (!$result) {
			$ErrMSG = "クエリーが失敗しました。（選定処理エラー）" . $mysqli->error;
			$ErrFlg = 1;
		}

		//-----T_Tantoアップデート-----
		if($ErrFlg == 0){
			$query2 = "UPDATE T_Tanto SET EndDay='" . $_SESSION["S04_EndDay"] . "'";
			$query2 = $query2 . " WHERE  TeacherID = '" . $_SESSION["S04_TeacherID"] . "'";
			$query2 = $query2 . " AND StudentID = '" . $_SESSION["S04_StudentID"] . "'";
			$query2 = $query2 . " AND Seq = '" . $_SESSION["S04_AtenaSeq"] . "'";

			//★$result2 = $mysqli->query($query2);

			//print($query2 ."<BR>");

			//if (!$result2) {
			//	$ErrMSG = "クエリーが失敗しました。（選定処理エラー）" . $mysqli->error;
			//	$ErrFlg = 1;
			//}
		}

		//-----S_SenteiInfoアップデート-----
		if($ErrFlg == 0){
			$query2 = "UPDATE S_SenteiInfo SET JyotaiFlg='2'";
			$query2 = $query2 . " WHERE  TeacherID = '" . $_SESSION["S04_TeacherID"] . "'";
			$query2 = $query2 . " AND StudentID = '" . $_SESSION["S04_StudentID"] . "'";
			$query2 = $query2 . " AND AtenaSeq = '" . $_SESSION["S04_AtenaSeq"] . "'";
			$query2 = $query2 . " AND Seq = '" . $_SESSION["S04_Seq"] . "'";

			$result2 = $mysqli->query($query2);

			//print($query2 ."<BR>");

			if (!$result2) {
				$ErrMSG = "クエリーが失敗しました。（S_SenteiInfoエラー）" . $mysqli->error;
				$ErrFlg = 1;
			}
		}


		//-----T_TantoShosaiインサート-----
		if($ErrFlg == 0){
			$S04_Seq = $_SESSION["S04_Seq"] + 1;
			$S04_EndDay = $_SESSION["S04_EndDay"];
//print("$S04_EndDay=" . $S04_EndDay . "<BR>");
//print("$S04_EndDay=" . date('Y/m/d', strtotime($S04_EndDay)) . "<BR>");

			$S04_StartDay = date('Y-m-d', strtotime('+1 day',strtotime($S04_EndDay)));

			$query = "INSERT INTO T_TantoShosai ";
			$query = $query . "values(";
			$query = $query . "'" . $_SESSION["S04_TeacherID"] . "'";
			$query = $query . ",'" . $_SESSION["S04_StudentID"] . "'";
			$query = $query . ",'" . $_SESSION["S04_AtenaSeq"] . "'";
			$query = $query . "," . $S04_Seq;
			$query = $query . ",'" . $S04_StartDay . "'";
			$query = $query . ",NULL";
			$query = $query . ",NULL";
			$query = $query . ",NULL";
			$query = $query . ",'" . $_SESSION["S04_Orgtype"] . "'";
			$query = $query . ",'" . $_SESSION["S04_course"] . "'";
			$query = $query . ",'" . $_SESSION["S04_course2"] . "'";
			$query = $query . ",'" . $_SESSION["S04_Pay"] . "'";
			$query = $query . ",'" . $_SESSION["S04_KiteiKaisu"] . "'";
			$query = $query . ",'" . $_SESSION["S04_KiteiJikan"] . "'";
			$query = $query . ",'" . $_SESSION["S04_KiteiKingaku"] . "'";
			$query = $query . ",'" . $_SESSION["S04_KiteiKotuhi"] . "'";
			$query = $query . ",'" . $_SESSION["S04_Kyoka"] . "'";
			$query = $query . ",'" . $_SESSION["S04_KyuyoNo"] . "'";
			$query = $query . ",'1'";
			$query = $query . ",'" . $_SESSION["S04_LoginTeacherID"] . "'";
			$query = $query . ",'" . $_SESSION["Today"] . "'";
			$query = $query . ",'" . $_SESSION["S04_LoginTeacherID"] . "'";
			$query = $query . ",'" . $_SESSION["Today"] . "'";
			$query = $query . ")";
		//print($query);
			$result = $mysqli->query($query);
			if (!$result) {
				$ErrMSG = "クエリーが失敗しました。（T_TantoShosaiエラー）" . $mysqli->error;
				$ErrFlg = 1;
			}
		}

//*****************************************************************************************************
		//-----T_Tantoインサート-----
		if($ErrFlg == 0){
			$query2 = "INSERT INTO T_Tanto ";
			$query2 = $query2 . "values(";
			$query2 = $query2 . "'" . $_SESSION["S04_TeacherID"] . "'";
			$query2 = $query2 . ",'" . $_SESSION["S04_StudentID"] . "'";
			$query2 = $query2 . ",'" . $_SESSION["S04_AtenaSeq"] . "'";
			$query2 = $query2 . ",'" . $S04_StartDay . "'";
			$query2 = $query2 . ",NULL";
			$query2 = $query2 . ",'" . $_SESSION["S04_Orgtype"] . "'";
			$query2 = $query2 . ",'" . $_SESSION["S04_course"] . "'";
			$query2 = $query2 . ",'" . $_SESSION["S04_Pay"] . "'";
			$query2 = $query2 . ",'" . $_SESSION["S04_KiteiKaisu"] . "'";
			$query2 = $query2 . ",'" . $_SESSION["S04_KiteiJikan"] . "'";
			$query2 = $query2 . ",'" . $_SESSION["S04_KyuyoNo"] . "'";
			$query2 = $query2 . ",'1'";
			$query2 = $query2 . ",'" . $_SESSION["S04_LoginTeacherID"] . "'";
			$query2 = $query2 . ",'" . $_SESSION["Today"] . "'";
			$query2 = $query2 . ",'" . $_SESSION["S04_LoginTeacherID"] . "'";
			$query2 = $query2 . ",'" . $_SESSION["Today"] . "'";
			$query2 = $query2 . ")";

			//$result2 = $mysqli->query($query2);

			//print($query2 ."<BR>");

			//if (!$result2) {
			//	$ErrMSG = "クエリーが失敗しました。" . $mysqli->error;
			//	$ErrFlg = 1;
			//}
		}
//*****************************************************************************************************

		//-----S_SenteiInfoインサート-----
		if($ErrFlg == 0){
			$query2 = "INSERT INTO S_SenteiInfo ";
			$query2 = $query2 . "values(";
			$query2 = $query2 . "'" . $_SESSION["S04_TeacherID"] . "'";
			$query2 = $query2 . ",'" . $_SESSION["S04_StudentID"] . "'";
			$query2 = $query2 . ",'" . $_SESSION["S04_AtenaSeq"] . "'";			
			$query2 = $query2 . "," . $S04_Seq;
			$query2 = $query2 . ",'1'";
			$query2 = $query2 . ",NULL";
			$query2 = $query2 . ",NULL";
			$query2 = $query2 . ",NULL";
			$query2 = $query2 . ",NULL";
			$query2 = $query2 . ",NULL";
			$query2 = $query2 . ",NULL";
			$query2 = $query2 . ",NULL";
			$query2 = $query2 . ",NULL";
			$query2 = $query2 . ",NULL";
			$query2 = $query2 . ",NULL";
			$query2 = $query2 . ")";

			$result2 = $mysqli->query($query2);

			//print($query2 ."<BR>");

			if (!$result2) {
				$ErrMSG = "クエリーが失敗しました。(S_SenteiInfo)" . $mysqli->error;
				$ErrFlg = 1;
			}
		}

		//-----TS_SeshoInfoインサート-----
		if($ErrFlg == 0){
			if($ErrFlg == 0){
				$query4 = "INSERT INTO TS_SeshoInfo ";
				$query4 = $query4 . "values(";
				$query4 = $query4 . "'" . $_SESSION["S04_TeacherID"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["S04_StudentID"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["S04_AtenaSeq"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["Today"] . "'";
				$query4 = $query4 . ",'" . $S04_SesshoSeq . "'";
				$query4 = $query4 . ",'" . S_JYOTAI_KEIYAKUHENKOU . "'";
				$query4 = $query4 . ",'0'";
				$query4 = $query4 . ",'" . $_SESSION["S04_EndDay"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["S04_Seq"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["S04_LoginTeacherID"] . "'";
				$query4 = $query4 . ",NULL";
				$query4 = $query4 . ",NULL";
				$query4 = $query4 . ",NULL";
				$query4 = $query4 . ",NULL";
				$query4 = $query4 . ",'" . $_SESSION["SesshoWarningMsg"] . "'";
				$query4 = $query4 . ",NULL";
				$query4 = $query4 . ",'0'";
				$query4 = $query4 . ")";

				$result4 = $mysqli->query($query4);

				//print($query4 ."<BR>");

				if (!$result4) {
					$ErrMSG = "クエリーが失敗しました。(TS_SeshoInfo)" . $mysqli->error;
					$ErrFlg = 1;
				}
			}
		}
		if($ErrFlg == 0){
			
			// コミット
			$mysqli->query("commit");

			$RtnMSG = "更新しました。";
			$mysqli->close();

			return $RtnMSG;
		}
		if($ErrFlg == 1){
			$mysqli->query("rollback");

			$mysqli->close();

			return $ErrMSG;
		}
	}else{
		//時給・回数・時間変更なし
		//-----T_TantoShosaiアップデート-----
		$query = "UPDATE T_TantoShosai SET ";
		$query = $query . " StartDay='". $_SESSION["S04_StartDay"] . "'";
		//初めて終了日が入力された場合
//		if(is_null($_SESSION["moto_S04_EndDay"]) && $_SESSION["S04_EndDay"] != ""){
			$query = $query . " ,EndDay='". $_SESSION["S04_EndDay"] . "'";
			if($_SESSION["S04_EndDay"] != ""){
				$query = $query . " ,EndFlg='". $_SESSION["S04_EndFlg"] . "'";
			}else{
				$query = $query . " ,EndDay=NULL";
			}
//			$query = $query . " ,EndSeq='". $S04_EndSeq . "'";
//		}else{
//			$query = $query . " ,EndDay=NULL";
//		}
		$query = $query . " ,Orgtype='". $_SESSION["S04_Orgtype"] . "'";
		$query = $query . " ,course='". $_SESSION["S04_course"] . "'";
		$query = $query . " ,course2='". $_SESSION["S04_course2"] . "'";
		$query = $query . " ,Pay='". $_SESSION["S04_Pay"] . "'";
		$query = $query . " ,KiteiKaisu='". $_SESSION["S04_KiteiKaisu"] . "'";
		$query = $query . " ,KiteiJikan='". $_SESSION["S04_KiteiJikan"] . "'";
		$query = $query . " ,KiteiKingaku='". $_SESSION["S04_KiteiKingaku"] . "'";
		$query = $query . " ,KiteiKotuhi='". $_SESSION["S04_KiteiKotuhi"] . "'";
		$query = $query . " ,Kyoka='". $_SESSION["S04_Kyoka"] . "'";
		$query = $query . " ,KyuyoNo='". $_SESSION["S04_KyuyoNo"] . "'";
		$query = $query . " ,DispFL='1'";
		$query = $query . " ,UpdateID='" . $_SESSION["S04_LoginTeacherID"] . "'";
		$query = $query . " ,UpTime='" . $_SESSION["Today"] . "'";
		$query = $query . " WHERE  TeacherID = '" . $_SESSION["S04_TeacherID"] . "'";
		$query = $query . " AND StudentID = '" . $_SESSION["S04_StudentID"] . "'";
		$query = $query . " AND AtenaSeq = '" . $_SESSION["S04_AtenaSeq"] . "'";
		$query = $query . " AND Seq = '" . $_SESSION["S04_Seq"] . "'";

		$result = $mysqli->query($query);

		//print("$query = " . $query ."<BR>");

		if (!$result) {
			$ErrMSG = "クエリーが失敗しました。（選定処理エラー）" . $mysqli->error;
			$ErrFlg = 1;
		}

		if($ErrFlg == 0){
			$query2 = "UPDATE T_Tanto SET ";
			$query2 = $query2 . " StartDay='". $_SESSION["S04_StartDay"] . "'";
			if($_SESSION["S04_EndDay"] != ""){
				$query2 = $query2 . " ,EndDay='". $_SESSION["S04_EndDay"] . "'";
			}else{
				$query2 = $query2 . " ,EndDay=NULL";
			}
			$query2 = $query2 . " ,Orgtype='". $_SESSION["S04_Orgtype"] . "'";
			$query2 = $query2 . " ,course='". $_SESSION["S04_course"] . "'";
			$query2 = $query2 . " ,course2='". $_SESSION["S04_course2"] . "'";
			$query2 = $query2 . " ,Pay='". $_SESSION["S04_Pay"] . "'";
			$query2 = $query2 . " ,KiteiKaisu='". $_SESSION["S04_KiteiKaisu"] . "'";
			$query2 = $query2 . " ,KiteiJikan='". $_SESSION["S04_KiteiJikan"] . "'";
			$query2 = $query2 . " ,KyuyoNo='". $_SESSION["S04_KyuyoNo"] . "'";
			$query2 = $query2 . " ,DispFL='1'";
			$query2 = $query2 . " ,UpdateID='" . $_SESSION["S04_LoginTeacherID"] . "'";
			$query2 = $query2 . " ,UpTime='" . $_SESSION["Today"] . "'";
			$query2 = $query2 . " WHERE  TeacherID = '" . $_SESSION["S04_TeacherID"] . "'";
			$query2 = $query2 . " AND StudentID = '" . $_SESSION["S04_StudentID"] . "'";
			$query2 = $query2 . " AND Seq = '" . $_SESSION["S04_Seq"] . "'";

			//★$result2 = $mysqli->query($query2);

			//print($query2 ."<BR>");

			//if (!$result2) {
			//	$ErrMSG = "クエリーが失敗しました。（選定処理エラー）" . $mysqli->error;
			//	$ErrFlg = 1;
			//}
		}

		if($ErrFlg == 0){
			//初めて終了日が入力された場合は折衝履歴を作成する
			if(is_null($_SESSION["moto_S04_EndDay"]) && $_SESSION["S04_EndDay"] != ""){
				$query4 = "INSERT INTO TS_SeshoInfo ";
				$query4 = $query4 . "values(";
				$query4 = $query4 . "'" . $_SESSION["S04_TeacherID"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["S04_StudentID"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["S04_AtenaSeq"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["Today"] . "'";
				$query4 = $query4 . ",'" . $S04_SesshoSeq . "'";
				$query4 = $query4 . ",'" . $_SESSION["S04_EndFlg"] . "'";
				if($_SESSION["S04_EndFlg"]=="10"){
					$query4 = $query4 . ",'1'";
				}else{
					$query4 = $query4 . ",'0'";
				}
				$query4 = $query4 . ",'" . $_SESSION["S04_EndDay"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["S04_Seq"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["S04_LoginTeacherID"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["S04_THouho"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["S04_SHouho"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["S04_TAite"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["S04_SAite"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["S04_Naiyo"] . "'";
				$query4 = $query4 . ",NULL";
				$query4 = $query4 . ",'0'";
				$query4 = $query4 . ")";

			}else{
				$query4 = "UPDATE TS_SeshoInfo SET ";
				$query4 = $query4 . " THouho='" . $_SESSION["S04_THouho"] . "'";
				$query4 = $query4 . " ,SHouho='" . $_SESSION["S04_SHouho"] . "'";
				$query4 = $query4 . " ,TAite='" . $_SESSION["S04_TAite"] . "'";
				$query4 = $query4 . " ,SAite='" . $_SESSION["S04_SAite"] . "'";
				$query4 = $query4 . " ,Naiyo='" . $_SESSION["S04_Naiyo"] . "'";
				$query4 = $query4 . " WHERE  TeacherID = '" . $_SESSION["S04_TeacherID"] . "'";
				$query4 = $query4 . " AND StudentID = '" . $_SESSION["S04_StudentID"] . "'";
				$query4 = $query4 . " AND AtenaSeq = '" . $_SESSION["S04_AtenaSeq"] . "'";
				$query4 = $query4 . " AND EndSeq = '" . $_SESSION["S04_Seq"] . "'";
			}
			$result4 = $mysqli->query($query4);

			//print("$result4 = " . $query4 ."<BR>");

			if (!$result4) {
				$ErrMSG = "クエリーが失敗しました。（TS_SeshoInfoエラー）" . $mysqli->error;
				$ErrFlg = 1;
			}
		}

		if($ErrFlg == 0){
			//S_SenteiInfo 終了日が入力されたら 8で更新			
			if(is_null($_SESSION["moto_S04_EndDay"]) && $_SESSION["S04_EndDay"] != ""){
				$query5 = "UPDATE S_SenteiInfo SET JyotaiFlg='8'";
				$query5 = $query5 . " WHERE  TeacherID = '" . $_SESSION["S04_TeacherID"] . "'";
				$query5 = $query5 . " AND StudentID = '" . $_SESSION["S04_StudentID"] . "'";
				$query5 = $query5 . " AND AtenaSeq = '" . $_SESSION["S04_AtenaSeq"] . "'";
				$query5 = $query5 . " AND Seq = '" . $_SESSION["S04_Seq"] . "'";

				$result5 = $mysqli->query($query5);

				//print("$result5 ="  . $query5 ."<BR>");

				if (!$result5) {
					$ErrMSG = "クエリーが失敗しました。（S_SenteiInfoエラー）" . $mysqli->error;
					$ErrFlg = 1;
				}
			}
		}

		if($ErrFlg == 0){
			
			// コミット
			$mysqli->query("commit");

			$RtnMSG = "更新しました。";
			$mysqli->close();

			return $RtnMSG;
		}
		if($ErrFlg == 1){
			$mysqli->query("rollback");

			$mysqli->close();

			return $ErrMSG;
		}

	}

}
//-----------------------------------------------------------
//	削除処理
//-----------------------------------------------------------
Function DLTShori(){
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

	$query = "DELETE FROM T_TantoShosai ";
	$query = $query . " WHERE  TeacherID = '" . $_SESSION["S04_TeacherID"] . "'";
	$query = $query . " AND StudentID = '" . $_SESSION["S04_StudentID"] . "'";
	$query = $query . " AND AtenaSeq = '" . $_SESSION["S04_AtenaSeq"] . "'";
	$query = $query . " AND Seq = '" . $_SESSION["S04_Seq"] . "'";

//print($query);
	$result = $mysqli->query($query);
	if (!$result) {
		$ErrMSG = "クエリーが失敗しました。T_TantoShosai" . $mysqli->error;
		$ErrFlg = 1;
	}

	if($ErrFlg == 0){
		$query3 = "DELETE FROM S_SenteiInfo ";
		$query3 = $query3 . " WHERE  TeacherID = '" . $_SESSION["S04_TeacherID"] . "'";
		$query3 = $query3 . " AND StudentID = '" . $_SESSION["S04_StudentID"] . "'";
		$query3 = $query3 . " AND AtenaSeq = '" . $_SESSION["S04_AtenaSeq"] . "'";
		$query3 = $query3 . " AND Seq = '" . $_SESSION["S04_Seq"] . "'";

		$result3 = $mysqli->query($query3);

		//print($query3);

		if (!$result3) {
			$ErrMSG = "クエリーが失敗しました。（選定処理エラー）" . $mysqli->error;
			$ErrFlg = 1;
		}

		if($ErrFlg == 0){
			
			// コミット
			$mysqli->query("commit");

			$RtnMSG = "削除しました。";
			$mysqli->close();

			return $RtnMSG;
		}
	}

	if($ErrFlg == 1){
		$mysqli->query("rollback");

		$mysqli->close();

		return $ErrMSG;
	}

}
//-----------------------------------------------------------
//	解除処理
//-----------------------------------------------------------
Function KaijyoShori(){
	// mysqlへの接続
	$mysqli = new mysqli(HOST, USER, PASS);
	if ($mysqli->connect_errno) {
		print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
		exit();
	}

	// データベースの選択
	$mysqli->select_db(DBNAME);
	$mysqli->set_charset("utf8");

	$query5 = "UPDATE S_SenteiInfo SET JyotaiFlg='9'";
	$query5 = $query5 . " WHERE  TeacherID = '" . $_SESSION["S04_TeacherID"] . "'";
	$query5 = $query5 . " AND StudentID = '" . $_SESSION["S04_StudentID"] . "'";
	$query5 = $query5 . " AND AtenaSeq = '" . $_SESSION["S04_AtenaSeq"] . "'";
	$query5 = $query5 . " AND Seq = '" . $_SESSION["S04_Seq"] . "'";

	$result5 = $mysqli->query($query5);

	//print($query5 ."<BR>");

	if (!$result5) {
		$ErrMSG = "クエリーが失敗しました。（S_SenteiInfoエラー）" . $mysqli->error;
		$ErrFlg = 1;
	}

	$mysqli->close();
	
	return $ErrMSG;
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
<form name="form1" method="post" action="S04_Kanri02.php">
	<table border="0" width="100%">
		<tr align="left">
			<td>
				<font size="5" color="#ff0000"><?php echo $_SESSION["ErrMsg"] ?></font>
			</td>
		</tr>
	</table>
	<table border="0" width="100%">
		<td align="right">
			<input type="hidden" id="submitter" name="submitter" value="" />
			<input type="button" id="modoru" name="modoru" onClick="sbmfnc(this,1)" style="cursor: pointer" value="教師一覧へ戻る" />
		</td>
	</table>
	<table border="0" width="100%">
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="<?php echo KITEI_COLOR ?>">契約情報更新</td>
		</tr>
	</table>
	<div id="tbl-bdr">
		<table>
			<tr>
				<td width="80" align="center" bgcolor="<?php echo TEACHR_COLOR ?>" colspan="2">教師名</td>
				<td class="inputtype" width="200" align="center"><?php echo $_SESSION["S04_TeacherID"] ?>-<?php echo $_SESSION["S04_Seq"] ?>　<?php echo $_SESSION["TeacherName"] ?></td>
				<td width="80" align="center" bgcolor="<?php echo TEACHR_COLOR ?>" colspan="2">電話番号</td>
				<td class="inputtype" width="150" align="center"><?php echo $_SESSION['Tel'] ?></td>
				<td width="80" align="center" bgcolor="<?php echo TEACHR_COLOR ?>" colspan="2">メール</td>
				<td class="inputtype" width="200" align="center"><?php echo $_SESSION['Mail'] ?></td>
			</tr>
		</table>
	</div>
	<BR>
	<table border="0" >
		<tr>
			<td id="midashi_Kanri" width="150" align="center" bgcolor="#c0c0c0">開始日</td>
			<td align="left"><input class="inputtype" type="text" size="10" maxlength="10" name="S04_StartDay" style="ime-mode: disabled;" value="<?php echo $_SESSION["S04_StartDay"] ?>"></td>
			<?php if($_SESSION["S04_Kanri02_Kensaku_SENTEI"] != 0){?>
				<td id="midashi_Kanri" width="150" align="center" bgcolor="#c0c0c0">終了日</td>
				<td align="left">
					<input class="inputtype" type="text" size="10" maxlength="10" name="S04_EndDay" style="ime-mode: disabled;" value="<?php echo $_SESSION["S04_EndDay"] ?>">
				</td>
				<td id="midashi_Kanri" width="150" align="center" bgcolor="#c0c0c0">終了区分</td>
				<td align="left">
					<select name="S04_EndFlg" class="selecttype2" onchange="window.onbeforeunload = null;this.form.submit()">
						<option value="" <?php if($_SESSION["S04_EndFlg"] == ""){ ?> SELECTED <?php } ?>></option>
						<?php for($dataidx=0; $dataidx < $_SESSION["21CodeData"]["21DataCount"]; $dataidx++){ ?>
							<option value="<?php echo $_SESSION["21CodeData"]["21_Eda_" . $dataidx] ?>" <?php if($_SESSION["21CodeData"]["21_Eda_" . $dataidx] == $_SESSION["S04_EndFlg"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["21CodeData"]["21_CodeName1_" . $dataidx] ?></option>
						<?php } ?>
					</select>
				</td>
			<?php } ?>
		</tr>
		<tr>
			<td id="midashi_Kanri" width="150" align="center" bgcolor="#c0c0c0">種別</td>
			<td align="left">
				<select name="S04_Orgtype" onchange="this.form.submit()" class="selecttype2">
					<option value="" <?php if($_SESSION["S04_Orgtype"] == ""){ ?> SELECTED <?php } ?>></option>
					<?php for($dataidx=0; $dataidx < $_SESSION["20CodeData"]["20DataCount"]; $dataidx++){ ?>
						<option value="<?php echo $_SESSION["20CodeData"]["20_Eda_" . $dataidx] ?>" <?php if($_SESSION["20CodeData"]["20_Eda_" . $dataidx] == $_SESSION["S04_Orgtype"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["20CodeData"]["20_CodeName1_" . $dataidx] ?></option>
					<?php } ?>
				</select>
			</td>
			<td id="midashi_Kanri" width="150" align="center" bgcolor="#c0c0c0">コース</td>
			<td align="left">
				<select name="S04_course" class="selecttype2">
					<option value="" <?php if($_SESSION["S04_course"] == ""){ ?> SELECTED <?php } ?>></option>
					<?php if($_SESSION["S04_Orgtype"] == "06"){?>
						<?php for($dataidx=0; $dataidx < $_SESSION["31CodeData"]["31DataCount"]; $dataidx++){ ?>
							<option value="<?php echo $_SESSION["31CodeData"]["31_Eda_" . $dataidx] ?>" <?php if($_SESSION["31CodeData"]["31_Eda_" . $dataidx] == $_SESSION["S04_course"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["31CodeData"]["31_CodeName1_" . $dataidx] ?></option>
						<?php } ?>
					<?php }else{ ?>
						<?php for($dataidx=0; $dataidx < $_SESSION["19CodeData"]["19DataCount"]; $dataidx++){ ?>
							<option value="<?php echo $_SESSION["19CodeData"]["19_Eda_" . $dataidx] ?>" <?php if($_SESSION["19CodeData"]["19_Eda_" . $dataidx] == $_SESSION["S04_course"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["19CodeData"]["19_CodeName1_" . $dataidx] ?></option>
						<?php } ?>
					<?php } ?>
				</select>
			</td>
			<td id="midashi_Kanri" width="150" align="center" bgcolor="#c0c0c0">コース２</td>
			<td align="left">
				<select name="S04_course2" class="selecttype2">
					<option value="" <?php if($_SESSION["S04_course2"] == ""){ ?> SELECTED <?php } ?>></option>
					<?php for($dataidx=0; $dataidx < $_SESSION["26CodeData"]["26DataCount"]; $dataidx++){ ?>
						<option value="<?php echo $_SESSION["26CodeData"]["26_Eda_" . $dataidx] ?>" <?php if($_SESSION["26CodeData"]["26_Eda_" . $dataidx] == $_SESSION["S04_course2"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["26CodeData"]["26_CodeName1_" . $dataidx] ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td id="midashi_Kanri" width="150" align="center" bgcolor="<?php echo KITEI_COLOR ?>">時給</td>
			<td align="left"><input class="inputtype" type="text" size="10" maxlength="10" name="S04_Pay" style="ime-mode: disabled;" value="<?php echo $_SESSION["S04_Pay"] ?>"></td>
			<td id="midashi_Kanri" width="150" align="center" bgcolor="<?php echo KITEI_COLOR ?>">回数</td>
			<td align="left"><input class="inputtype" type="text" size="10" maxlength="10" name="S04_KiteiKaisu" style="ime-mode: disabled;" value="<?php echo $_SESSION["S04_KiteiKaisu"] ?>"></td>
			<td id="midashi_Kanri" width="150" align="center" bgcolor="<?php echo KITEI_COLOR ?>">時間</td>
			<td align="left"><input class="inputtype" type="text" size="10" maxlength="10" name="S04_KiteiJikan" style="ime-mode: disabled;" value="<?php echo $_SESSION["S04_KiteiJikan"] ?>"></td>
		</tr>
		<tr>
			<td id="midashi_Kanri" width="150" align="center" bgcolor="#c0c0c0">規定金額</td>
			<td align="left"><input class="inputtype" type="text" size="10" maxlength="10" name="S04_KiteiKingaku" style="ime-mode: disabled;" value="<?php echo $_SESSION["S04_KiteiKingaku"] ?>"></td>
			<td id="midashi_Kanri" width="150" align="center" bgcolor="#c0c0c0">交通費</td>
			<td align="left"><input class="inputtype" type="text" size="10" maxlength="10" name="S04_KiteiKotuhi" style="ime-mode: disabled;" value="<?php echo $_SESSION["S04_KiteiKotuhi"] ?>"></td>
			<td id="midashi_Kanri" width="150" align="center" bgcolor="#c0c0c0">振込No</td>
			<td align="left"><input class="inputtype" type="text" size="10" maxlength="10" name="S04_KyuyoNo" style="ime-mode: disabled;" value="<?php echo $_SESSION["S04_KyuyoNo"] ?>"></td>
		</tr>
		<tr>
			<td id="midashi_Kanri" width="150" align="center" bgcolor="#c0c0c0">教科</td>
			<td align="left" colspan="5"><input class="inputtype" type="text" size="60" maxlength="80" name="S04_Kyoka" style="ime-mode: disabled;" value="<?php echo $_SESSION["S04_Kyoka"] ?>"></td>
		</tr>

		<?php if($_SESSION["ENDRIYU"] == 1 ){ ?>
			<tr>
				<td id="midashi_Kanri" width="150" align="center" bgcolor="<?php echo TEACHR_COLOR ?>">教師</td>
				<td align="center" width="50"><?php echo $_SESSION["TeacherName"]?></td>
				<td id="midashi_Kanri" width="150" align="center" bgcolor="<?php echo TEACHR_COLOR ?>">連絡方法</td>
				<td align="left">
					<select name="S04_THouho" class="selecttype2">
						<option value="" <?php if($_SESSION["S04_THouho"] == ""){ ?> SELECTED <?php } ?>></option>
						<?php for($dataidx=0; $dataidx < $_SESSION["09CodeData"]["09DataCount"]; $dataidx++){ ?>
							<option value="<?php echo $_SESSION["09CodeData"]["09_Eda_" . $dataidx] ?>" <?php if($_SESSION["09CodeData"]["09_Eda_" . $dataidx] == $_SESSION["S04_THouho"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["09CodeData"]["09_CodeName1_" . $dataidx] ?></option>
						<?php } ?>
					</select>
				</td>
				<td id="midashi_Kanri" width="150" align="center" bgcolor="<?php echo TEACHR_COLOR ?>">折衝相手</td>
				<td align="left">
					<select name="S04_TAite" class="selecttype2">
						<option value="" <?php if($_SESSION["S04_TAite"] == ""){ ?> SELECTED <?php } ?>></option>
						<?php for($dataidx=0; $dataidx < $_SESSION["10CodeData"]["10DataCount"]; $dataidx++){ ?>
							<option value="<?php echo $_SESSION["10CodeData"]["10_Eda_" . $dataidx] ?>" <?php if($_SESSION["10CodeData"]["10_Eda_" . $dataidx] == $_SESSION["S04_TAite"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["10CodeData"]["10_CodeName1_" . $dataidx] ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td id="midashi_Kanri" width="150" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">生徒</td>
				<td align="center" width="50"><?php echo GetStudentName($_SESSION["S04_StudentID"])?></td>
				<td id="midashi_Kanri" width="150" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">連絡方法</td>
				<td align="left">
					<select name="S04_SHouho" class="selecttype2">
						<option value="" <?php if($_SESSION["S04_SHouho"] == ""){ ?> SELECTED <?php } ?>></option>
						<?php for($dataidx=0; $dataidx < $_SESSION["09CodeData"]["09DataCount"]; $dataidx++){ ?>
							<option value="<?php echo $_SESSION["09CodeData"]["09_Eda_" . $dataidx] ?>" <?php if($_SESSION["09CodeData"]["09_Eda_" . $dataidx] == $_SESSION["S04_SHouho"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["09CodeData"]["09_CodeName1_" . $dataidx] ?></option>
						<?php } ?>
					</select>
				</td>
				<td id="midashi_Kanri" width="150" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">折衝相手</td>
				<td align="left">
					<select name="S04_SAite" class="selecttype2">
						<option value="" <?php if($_SESSION["S04_SAite"] == ""){ ?> SELECTED <?php } ?>></option>
						<?php for($dataidx=0; $dataidx < $_SESSION["10CodeData"]["10DataCount"]; $dataidx++){ ?>
							<option value="<?php echo $_SESSION["10CodeData"]["10_Eda_" . $dataidx] ?>" <?php if($_SESSION["10CodeData"]["10_Eda_" . $dataidx] == $_SESSION["S04_SAite"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["10CodeData"]["10_CodeName1_" . $dataidx] ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td id="midashi_Kanri" width="150" align="center" bgcolor="#c0c0c0">終了理由</td>
				<td colspan=5><textarea name="S04_Naiyo" cols="80" rows="5"><?php echo $_SESSION["S04_Naiyo"] ?></textarea></td>
			</tr>
		<?php } ?>
	</table>
	<table border="0" width="100%">
		<tr>
			<td width="100" align="center">
				<?php if($_SESSION["S04_Kanri02_Kensaku_SENTEI"] == 0){?>
					<input type="button" id="kaijyo" name="kaijyo" onClick="this.form.target='_top';sbmfnc(this,'');" style="cursor: pointer" value="選定解除" <?php if($_SESSION["shikaku2"]==0){ ?>Disabled<?php } ?>/>
					<input type="button" id="newdate" name="newdate" onClick="sbmfnc(this,'');" style="cursor: pointer" value="登録" <?php if($_SESSION["shikaku2"]==0){ ?>Disabled<?php } ?>/>
				<?php }else{ ?>
					<input type="button" id="update" name="update" onClick="sbmfnc(this,'');" style="cursor: pointer" value="更新" <?php echo $_SESSION["UPD_DIS"] ?><?php if($_SESSION["shikaku2"]==0){ ?>Disabled<?php } ?>/>
					<?php if(($_SESSION["UpdFlg"] == 1) || ($_SESSION["S04_Kanri02_Kensaku_SENTEI"] == 8) || ($_SESSION["S04_Kanri02_Kensaku_SENTEI"] == 9)){ ?>
						<?php if($_SESSION["shikaku1"]==1){ ?>
							<input type="button" id="updatekanri" name="updatekanri" onClick="sbmfnc(this,'');" style="cursor: pointer" value="職権修正"/>
							<input type="button" id="deletekanri" name="deletekanri" onClick="sbmfnc(this,'');" style="cursor: pointer" value="職権削除"/>
						<?php } ?>
					<?php } ?>
				<?php } ?>
			</td>
		</tr>
	</table>

</form>
</body>
</CENTER>
</html>