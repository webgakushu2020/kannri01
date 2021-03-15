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
		 	ModoruShori($_SESSION["K01_CodeKanri_RPID"]);
			exit;
		}
	}

	if(isset($_POST['K01_CodePul_CodeNo'])){
		$_SESSION["ShoriID"]="CODEPUL";
	}

	if(isset($_POST['submitter'])){
		switch ($_POST['submitter']){
			case 'kakunin':
				$_SESSION["ShoriID"]="KAKUNIN";
				break;
			case 'newdate':
				$_SESSION["ShoriID"]="NEW";
				break;
			case 'update':
				$_SESSION["ShoriID"]="UPDATE";
				break;
			case 'delete':
				$_SESSION["ShoriID"]="DELETE";
				break;
			case 'K01clear':
				$_SESSION["ShoriID"]="K01CLEAR";
				break;
			case 'getno':
				$_SESSION["ShoriID"]="GETNO";
				break;
		}
	}

	for ($m = 0; $m < $_SESSION["K01_Code_Cnt"]; $m++){
		if(isset($_POST["No_" . $m])){
			$_SESSION["ShoriID"]="SHUSEI";
			$_SESSION["KAKUNIFLG"]=0;

			$_SESSION["K01_Code_CodeNo"] = $_POST['K01_Code_CodeNo_' . $m];
			$_SESSION["K01_Code_CodeName"] = $_POST['K01_Code_CodeName_' . $m];
			$_SESSION["K01_Code_Eda"] = $_POST['K01_Code_Eda_' . $m];
			$_SESSION["K01_Code_CodeName1"] = $_POST['K01_Code_CodeName1_' . $m];
			$_SESSION["K01_Code_CodeName2"] = $_POST['K01_Code_CodeName2_' . $m];
			$_SESSION["K01_Code_SortNo"] = $_POST['K01_Code_SortNo_' . $m];
			$_SESSION["K01_Code_UpdateFlg"] = $_POST['K01_Code_UpdateFlg_' . $m];

			break;
		}
	}
	for ($m = 0; $m < $_SESSION["K01_Code_Cnt"]; $m++){
		if(isset($_POST["No2_" . $m])){
			$_SESSION["ShoriID"]="COPY";
			$_SESSION["KAKUNIFLG"]=0;

			$_SESSION["K01_Code_CodeNo"] = $_POST['K01_Code_CodeNo_' . $m];
			$_SESSION["K01_Code_CodeName"] = $_POST['K01_Code_CodeName_' . $m];
			$_SESSION["K01_Code_Eda"] = $_POST['K01_Code_Eda_' . $m];
			$_SESSION["K01_Code_CodeName1"] = $_POST['K01_Code_CodeName1_' . $m];
			$_SESSION["K01_Code_CodeName2"] = $_POST['K01_Code_CodeName2_' . $m];
			$_SESSION["K01_Code_SortNo"] = $_POST['K01_Code_SortNo_' . $m];
			$_SESSION["K01_Code_UpdateFlg"] = $_POST['K01_Code_UpdateFlg_' . $m];
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
			$_SESSION["K01_CodeKanri_RPID"] = $_GET['RPID'];
		      	//print($_SESSION["S04_Kanri02_RPID"]);
		}
		if(isset($_GET['MODE'])) {
			$_SESSION["K01_CodeKanri_MODE"] = $_GET['MODE'];
			$_SESSION["ShoriID"] = $_GET['MODE'];
		      	//print($_SESSION["S04_Kanri02_MODE"] . "<BR>");

			if($_SESSION["K01_CodeKanri_MODE"] == "UPD"){
				$_SESSION["K01_LoginTeacherID"] = $_SESSION["LoginTeacherID"];
				$_SESSION["K01_LoginTName1"] = $_SESSION["LoginTName1"];
				$_SESSION["K01_LoginTName2"] = $_SESSION["LoginTName2"];
			}
		}

		//セッション情報保存
		//前画面からの情報
		$user_name = $_SESSION["user_name"];
		$shikaku = (int) $_SESSION["shikaku"];
		$TName1 = $_SESSION["TName1"];
		$TName2 = $_SESSION["TName2"];

		switch ($_SESSION["ShoriID"]){
			case 'UPD':
//				$i = 0;
				SessionClear();
				$EMSG1 = GetData();
				if($EMSG1 > 0){
					$_SESSION["ErrMsg"] = $EMSG1;
				}

				break;
			case 'KAKUNIN':
				SaveShori();
				$EMSG1 = CheckShori();
				$_SESSION["ErrMsg"] = $EMSG1;
				if ($EMSG1 == ""){
					$_SESSION["KAKUNIFLG"] = 1;
					$_SESSION["ErrMsg"] = 追加ボタンを押してください。;
				} 
				GetData();
				break;
			case 'NEW':
//				SaveShori();
				$EMSG1 = NEWShori();
				$_SESSION["ErrMsg"] = $EMSG1;
				GetData();
				break;
			case 'UPDATE':
				SaveShori();
				$EMSG1 = CheckShori2();
				if($EMSG1 == ""){
					$EMSG1 = UpDateShori();
				}
				$_SESSION["ErrMsg"] = $EMSG1;
				GetData();
				break;

			case 'DELETE':
				$EMSG1 = DELShori();
				$_SESSION["ErrMsg"] = $EMSG1;
				GetData();
				break;
			case 'K01CLEAR':
				SessionClear();
				break;
			case 'GETNO':
				GetNewNo();
				break;
			case 'CODEPUL':
				$_SESSION["SEL_CodeNo"] = $_POST['K01_CodePul_CodeNo'];
				break;
		}
	}
//-----------------------------------------------------------
//	セッション情報クリア
//-----------------------------------------------------------
function SessionClear(){
	$_SESSION["ErrMsg"] = "";
	$_SESSION["KAKUNIFLG"]=0;

	$_SESSION["K01_Code_CodeNo"] = "";
	$_SESSION["K01_Code_CodeName"] = "";
	$_SESSION["K01_Code_Eda"] = "";
	$_SESSION["K01_Code_CodeName1"] = "";
	$_SESSION["K01_Code_CodeName2"] = "";
	$_SESSION["K01_Code_SortNo"] = "";
	$_SESSION["K01_Code_UpdateFlg"] = "";

	$_SESSION["K01_Code_CodeNo_ErrMsg"] = "";
	$_SESSION["K01_Code_CodeNo_COLER"] = "";
	$_SESSION["K01_Code_CodeName_ErrMsg"] = "";
	$_SESSION["K01_Code_CodeName_COLER"] = "";
	$_SESSION["K01_Code_Eda_ErrMsg"] = "";
	$_SESSION["K01_Code_Eda_COLER"] = "";
	$_SESSION["K01_Code_CodeName1_ErrMsg"] = "";
	$_SESSION["K01_Code_CodeName1_COLER"] = "";
	$_SESSION["K01_Code_CodeName2_ErrMsg"] = "";
	$_SESSION["K01_Code_CodeName2_COLER"] = "";
	$_SESSION["K01_Code_SortNo_ErrMsg"] = "";
	$_SESSION["K01_Code_SortNo_COLER"] = "";
	$_SESSION["K01_Code_UpdateFlg_ErrMsg"] = "";
	$_SESSION["K01_Code_UpdateFlg_COLER"] = "";

	$_SESSION["K01_CodePul_CodeNo"]="";
	$_SESSION["SEL_CodeNo"]="";
}
//-----------------------------------------------------------
//	セーブ処理
//-----------------------------------------------------------
function SaveShori(){

	if(isset($_POST['K01_Code_CodeNo'])){
		$_SESSION["K01_Code_CodeNo"] = $_POST['K01_Code_CodeNo'];
	}
	if(isset($_POST['K01_Code_CodeName'])){
		$_SESSION["K01_Code_CodeName"] = $_POST['K01_Code_CodeName'];
	}
	if(isset($_POST['K01_Code_Eda'])){
		$_SESSION["K01_Code_Eda"] = $_POST['K01_Code_Eda'];
	}
	$_SESSION["K01_Code_CodeName1"] = $_POST['K01_Code_CodeName1'];
	$_SESSION["K01_Code_CodeName2"] = $_POST['K01_Code_CodeName2'];
	$_SESSION["K01_Code_SortNo"] = $_POST['K01_Code_SortNo'];
	$_SESSION["K01_Code_UpdateFlg"] = $_POST['K01_Code_UpdateFlg'];
	$_SESSION["K01_CodePul_CodeNo"] = $_POST['K01_CodePul_CodeNo'];
}

//-----------------------------------------------------------
//	データ取得
//-----------------------------------------------------------
function GetData(){
$ErrMsg="";

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
	$query = "SELECT A.CodeNo,A.CodeName,B.Eda,B.CodeName1,B.CodeName2,B.SortNo,A.UpdateFlg FROM K_CodeName as A inner join K_Code as B ON A.CodeNo=B.CodeNo Order by B.CodeNo,B.Eda";

	$result = $mysqli->query($query);

//	print($query ."<BR>");

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
			$data[$i][$key] = $value;
			$_SESSION["K01_Code_" . $key . "_" .$i]=$data[$i][$key];
			$_SESSION["moto_K01_Code_" . $key . "_" .$i]=$data[$i][$key];
		}
		$i++;
	}

	$_SESSION["K01_Code_Cnt"] = $i;

	$query = "SELECT CodeNo,CodeName FROM K_CodeName Where UpdateFlg = 1 Group by CodeNo,CodeName Order by CodeNo,CodeName";

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
			$data[$i][$key] = $value;
			$_SESSION["K01_CodePul_" . $key . "_" .$i]=$data[$i][$key];
		}
		$i++;
	}

	$_SESSION["K01_Code_PulCnt"] = $i;
//print($_SESSION["K01_Code_PulCnt"]);

 	// データベースの切断
	$mysqli->close();

	return $ErrMsg;

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

	$_SESSION["K01_Code_CodeNo_ErrMsg"] = "";
	$_SESSION["K01_Code_CodeNo_COLER"] = "";
	$_SESSION["K01_Code_Eda_ErrMsg"] = "";
	$_SESSION["K01_Code_Eda_COLER"] = "";
	$_SESSION["K01_Code_CodeName1_ErrMsg"] = "";
	$_SESSION["K01_Code_CodeName1_COLER"] = "";
	$_SESSION["K01_Code_CodeName2_ErrMsg"] = "";
	$_SESSION["K01_Code_CodeName2_COLER"] = "";
	$_SESSION["K01_Code_SortNo_ErrMsg"] = "";
	$_SESSION["K01_Code_SortNo_COLER"] = "";
	$_SESSION["K01_Code_UpdateFlg_ErrMsg"] = "";
	$_SESSION["K01_Code_UpdateFlg_COLER"] = "";

	//-----資格情報-----
	//管理Noが入力されている場合は他の項目必須入力
	if($_SESSION["K01_Code_CodeNo"] == ""){
		$ErrMsg = "未入力";
		$_SESSION["K01_Code_CodeNo_ErrMsg"] = $ErrMsg;
		$_SESSION["K01_Code_CodeNo_COLER"] = $Background;
		$ErrCnt++;
	}
	if($_SESSION["K01_Code_Eda"] == ""){
		$ErrMsg = "未入力";
		$_SESSION["K01_Code_Eda_ErrMsg"] = $ErrMsg;
		$_SESSION["K01_Code_Eda_COLER"] = $Background;
		$ErrCnt++;
	}
	if($_SESSION["K01_Code_CodeName1"] == ""){
		$ErrMsg = "未入力";
		$_SESSION["K01_Code_CodeName1_ErrMsg"] = $ErrMsg;
		$_SESSION["K01_Code_CodeName1_COLER"] = $Background;
		$ErrCnt++;
	}
	if($_SESSION["K01_Code_CodeName2"] == ""){
		$ErrMsg = "未入力";
		$_SESSION["K01_Code_CodeName2_ErrMsg"] = $ErrMsg;
		$_SESSION["K01_Code_CodeName2_COLER"] = $Background;
		$ErrCnt++;
	}
	if($_SESSION["K01_Code_SortNo"] == ""){
		$ErrMsg = "未入力";
		$_SESSION["K01_Code_SortNo_ErrMsg"] = $ErrMsg;
		$_SESSION["K01_Code_SortNo_COLER"] = $Background;
		$ErrCnt++;
	}
	if($_SESSION["K01_Code_UpdateFlg"] != ""){
		if($_SESSION["moto_K01_Code_UpdateFlg"] != $_SESSION["K01_Code_UpdateFlg"] && $_SESSION["K01_Code_UpdateFlg"]==0){
			$ErrMsg = "修正不可のデータです。管理者に修正許可を得てください。";
		}
	}
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
	if($_SESSION["K01_Code_CodeNo"] != ""){
		$query = "SELECT Count(*) as Cnt FROM K_Code";
		$query = $query . " WHERE CodeNo = '" . $_SESSION["K01_Code_CodeNo"] . "'";
		$query = $query . " And Eda = '" . $_SESSION["K01_Code_Eda"] . "'";

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
				$_SESSION["K01_CodeCK_" . $key] = $value;
			}
		}
	}

	if($_SESSION["K01_CodeCK_Cnt"] > 0){
		$ErrMsg = "このコードNoはすでに登録済みです。<BR>";
	}

 	// データベースの切断
	$mysqli->close();

	$ErrAllCnt = $ErrCnt;
	$_SESSION["K01_ErrAllCnt"] = $ErrAllCnt;

	$ErrCnt = 0;

	return $ErrMsg;

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

	$_SESSION["K01_Code_CodeNo_ErrMsg"] = "";
	$_SESSION["K01_Code_CodeNo_COLER"] = "";
	$_SESSION["K01_Code_Eda_ErrMsg"] = "";
	$_SESSION["K01_Code_Eda_COLER"] = "";
	$_SESSION["K01_Code_CodeName1_ErrMsg"] = "";
	$_SESSION["K01_Code_CodeName1_COLER"] = "";
	$_SESSION["K01_Code_CodeName2_ErrMsg"] = "";
	$_SESSION["K01_Code_CodeName2_COLER"] = "";
	$_SESSION["K01_Code_SortNo_ErrMsg"] = "";
	$_SESSION["K01_Code_SortNo_COLER"] = "";
	$_SESSION["K01_Code_UpdateFlg_ErrMsg"] = "";
	$_SESSION["K01_Code_UpdateFlg_COLER"] = "";

	//-----資格情報-----
	//管理Noが入力されている場合は他の項目必須入力
	if($_SESSION["K01_Code_CodeNo"] == ""){
		$ErrMsg = "未入力";
		$_SESSION["K01_Code_CodeNo_ErrMsg"] = $ErrMsg;
		$_SESSION["K01_Code_CodeNo_COLER"] = $Background;
		$ErrCnt++;
	}
	if($_SESSION["K01_Code_Eda"] == ""){
		$ErrMsg = "未入力";
		$_SESSION["K01_Code_Eda_ErrMsg"] = $ErrMsg;
		$_SESSION["K01_Code_Eda_COLER"] = $Background;
		$ErrCnt++;
	}
	if($_SESSION["K01_Code_CodeName1"] == ""){
		$ErrMsg = "未入力";
		$_SESSION["K01_Code_CodeName1_ErrMsg"] = $ErrMsg;
		$_SESSION["K01_Code_CodeName1_COLER"] = $Background;
		$ErrCnt++;
	}
	if($_SESSION["K01_Code_CodeName2"] == ""){
		$ErrMsg = "未入力";
		$_SESSION["K01_Code_CodeName2_ErrMsg"] = $ErrMsg;
		$_SESSION["K01_Code_CodeName2_COLER"] = $Background;
		$ErrCnt++;
	}
	if($_SESSION["K01_Code_SortNo"] == ""){
		$ErrMsg = "未入力";
		$_SESSION["K01_Code_SortNo_ErrMsg"] = $ErrMsg;
		$_SESSION["K01_Code_SortNo_COLER"] = $Background;
		$ErrCnt++;
	}

	$ErrAllCnt = $ErrCnt;
	$_SESSION["K01_ErrAllCnt"] = $ErrAllCnt;

	$ErrCnt = 0;

	return $_SESSION["K01_ErrAllCnt"];

}
//-----------------------------------------------------------
//	更新処理
//-----------------------------------------------------------
Function UpdateShori(){
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
	//資格情報登録
	$query = "UPDATE K_Code SET";
	$query = $query . " CodeName1 = '" . $_SESSION["K01_Code_CodeName1"] . "'";
	$query = $query . ", CodeName2 = '" . $_SESSION["K01_Code_CodeName2"] . "'";
	$query = $query . ", SortNo = '" . $_SESSION["K01_Code_SortNo"] . "'";
	$query = $query . " Where CodeNo = '" . $_SESSION["K01_Code_CodeNo"] . "'";
	$query = $query . " And Eda = '" . $_SESSION["K01_Code_Eda"] . "'";

	//print($query);

	$result = $mysqli->query($query);
	if (!$result) {
		$ErrMSG = "クエリーが失敗しました。" . $mysqli->error;
		$ErrFlg = 1;
	}

	if($ErrFlg == 0){
		
		// コミット
		$mysqli->query("commit");

		$RtnMSG = "更新しました。";

	}else{
		$mysqli->query("rollback");

		return $ErrMSG;
	}
	
	$mysqli->close();

	return $RtnMSG;
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

	//コード情報登録
	$query = "SELECT Count(*) as NewCnt FROM K_CodeName";
	$query = $query . " WHERE CodeNo = '" . $_SESSION["K01_Code_CodeNo"] . "'";

	$result = $mysqli->query($query);

	//print($query ."<BR>");

	if (!$result) {
		print('クエリーが失敗しました。' . $mysqli->error);
		$ErrFlg = 1;
	}

	$i = 0;
	while($arr_item = $result->fetch_assoc()){
		//レコード内の各フィールド名と値を順次参照
		foreach($arr_item as $key => $value){
			//フィールド名と値を表示
			$_SESSION["K01_CodeCK_" . $key] = $value;
		}
	}
	if($ErrFlg == 0){
		if($_SESSION["K01_CodeCK_NewCnt"] == 0){
			//コード情報登録
			$query = "INSERT INTO K_CodeName ";
			$query = $query . "values(";
			$query = $query . "'" . $_SESSION["K01_Code_CodeNo"] . "'";
			$query = $query . ",'" . $_SESSION["K01_Code_CodeName"] . "'";
			$query = $query . ")";

			//print($query);

			$result = $mysqli->query($query);
			if (!$result) {
				$ErrMSG = "クエリーが失敗しました。" . $mysqli->error;
				$ErrFlg = 1;
			}
			if($ErrFlg == 0){
				//コード情報登録
				$query = "INSERT INTO K_Code ";
				$query = $query . "values(";
				$query = $query . "'" . $_SESSION["K01_Code_CodeNo"] . "'";
				$query = $query . ",'" . $_SESSION["K01_Code_Eda"] . "'";
				$query = $query . ",'" . $_SESSION["K01_Code_CodeName1"] . "'";
				$query = $query . ",'" . $_SESSION["K01_Code_CodeName2"] . "'";
				$query = $query . ",'" . $_SESSION["K01_Code_SortNo"] . "'";
				$query = $query . ")";

				//print($query);

				$result = $mysqli->query($query);
				if (!$result) {
					$ErrMSG = "クエリーが失敗しました。" . $mysqli->error;
					$ErrFlg = 1;
				}
			}
		}else{
			//コード情報登録
			$query = "INSERT INTO K_Code ";
			$query = $query . "values(";
			$query = $query . "'" . $_SESSION["K01_Code_CodeNo"] . "'";
			$query = $query . ",'" . $_SESSION["K01_Code_Eda"] . "'";
			$query = $query . ",'" . $_SESSION["K01_Code_CodeName1"] . "'";
			$query = $query . ",'" . $_SESSION["K01_Code_CodeName2"] . "'";
			$query = $query . ",'" . $_SESSION["K01_Code_SortNo"] . "'";
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
	
	$mysqli->close();

	return $RtnMSG;

}
//-----------------------------------------------------------
//	削除処理
//-----------------------------------------------------------
Function DELShori(){
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

	//資格情報登録
	$query = "DELETE FROM K_Code ";
	$query = $query . "Where CodeNo= '" . $_SESSION["K01_Code_CodeNo"] . "'";
	$query = $query . " And Eda= '" . $_SESSION["K01_Code_Eda"] . "'";

	//print($query);

	$result = $mysqli->query($query);
	if (!$result) {
		$ErrMSG = "クエリーが失敗しました。" . $mysqli->error;
		$ErrFlg = 1;
	}
	if($ErrFlg == 0){
		$query = "Select Count(*) as Cnt FROM K_Code ";
		$query = $query . "Where CodeNo= '" . $_SESSION["K01_Code_CodeNo"] . "'";

		//print($query);

		$result = $mysqli->query($query);
		if (!$result) {
			$ErrMSG = "クエリーが失敗しました。" . $mysqli->error;
			$ErrFlg = 1;
		}

		$i = 0;
		while($arr_item = $result->fetch_assoc()){
			//レコード内の各フィールド名と値を順次参照
			foreach($arr_item as $key => $value){
				//フィールド名と値を表示
				$_SESSION["K01_" . $key] = $value;
			}
		}
		if($ErrFlg == 0){
			if($_SESSION["K01_Cnt"] == 0){
				$query = "DELETE FROM K_CodeName ";
				$query = $query . "Where CodeNo= '" . $_SESSION["K01_Code_CodeNo"] . "'";

				//print($query);
				$result = $mysqli->query($query);
				if (!$result) {
					$ErrMSG = "クエリーが失敗しました。" . $mysqli->error;
					$ErrFlg = 1;
				}
			}
		}
	}

	if($ErrFlg == 0){
		
		// コミット
		$mysqli->query("commit");

		$RtnMSG = "削除しました。";

	}else{
		$mysqli->query("rollback");

		return $ErrMSG;
	}
	
	$mysqli->close();

	return $RtnMSG;
}
//-----------------------------------------------------------
//	最新NO取得
//-----------------------------------------------------------
function GetNewNo(){
$ErrMsg="";

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
	$query = "SELECT * FROM K_CodeName Order by CodeNo Desc limit 1";

	$result = $mysqli->query($query);

//	print($query ."<BR>");

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
			$_SESSION["K01_NewCode_" . $key]=$value;
		}
	}

	$NewCodeNo = (int)$_SESSION["K01_NewCode_CodeNo"];
	$NewCodeNo++;
	$_SESSION["K01_Code_CodeNo"]=$NewCodeNo;


 	// データベースの切断
	$mysqli->close();

	return $ErrMsg;

}
?>
<script type="text/javascript">
</script>
<script type="text/javascript" src="utility.js"></script>

<CENTER>
<body onload="" onKeyPress="OnKey(event.keyCode,'');">
<form name="form1" method="post" action="K01_CodeKanri.php">
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
	<BR>
	<table border="0" >
		<tr>
			<td width="1000" align="center" bgcolor="<?php echo TEACHR_COLOR ?>" colspan="2">コード管理</td>
		</tr>
	</table>
	<BR><BR>
	<table border="1" >
		<table>
			<tr>
				<td>
					<select name="K01_CodePul_CodeNo" onchange="this.form.submit()" class="selecttype2">
						<option value="" <?php if($_SESSION["K01_CodePul_CodeNo"] == ""){ ?> SELECTED <?php } ?>></option>
						<?php for($dataidx=0; $dataidx < $_SESSION["K01_Code_PulCnt"]; $dataidx++){ ?>
							<option value="<?php echo $_SESSION["K01_CodePul_CodeNo" . "_" .$dataidx] ?>" <?php if($_SESSION["K01_CodePul_CodeNo" . "_" .$dataidx] == $_SESSION["K01_CodePul_CodeNo"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["K01_CodePul_CodeName" . "_" .$dataidx] ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
		</table>
	</table>
	<BR>
	<table border="1" >
		<tr>
			<td id="midashi" width="50"  align="center" bgcolor="#c0c0c0">No</td>
			<td id="midashi" width="200" align="center" bgcolor="#c0c0c0">管理No</td>
			<td id="midashi" width="50"  align="center" bgcolor="#c0c0c0">区分名</td>
			<td id="midashi" width="300" align="center" bgcolor="#c0c0c0">コードNo</td>
			<td id="midashi" width="300" align="center" bgcolor="#c0c0c0">コード名①</td>
			<td id="midashi" width="50"  align="center" bgcolor="#c0c0c0">コード名②</td>
			<td id="midashi" width="100" align="center" bgcolor="#c0c0c0">並び順</td>
			<td id="midashi" align="center" bgcolor="#c0c0c0">修正可否</td>
		</tr>
		<?php for($i=0; $i<$_SESSION["K01_Code_Cnt"]; $i++){ ?>
			<?php if($_SESSION["K01_Code_CodeNo_" .$i] == $_SESSION["SEL_CodeNo"]){ ?>
				<tr>
					<td height="30" align="center"><input type="submit" name="No_<?php echo $i ?>" size="10" value="修正"><input type="submit" name="No2_<?php echo $i ?>" size="10" value="コピー"></td>
					<td width="50"  align="center"><?php echo $_SESSION["K01_Code_CodeNo_" .$i] ?></td>
					<td width="200" align="center"><?php echo $_SESSION["K01_Code_CodeName_" .$i] ?></td>
					<td width="50"  align="center"><?php echo $_SESSION["K01_Code_Eda_" .$i] ?></td>
					<td width="300" align="center"><?php echo $_SESSION["K01_Code_CodeName1_" .$i] ?></td>
					<td width="300" align="center"><?php echo $_SESSION["K01_Code_CodeName2_" .$i] ?></td>
					<td width="50"  align="center"><?php echo $_SESSION["K01_Code_SortNo_" .$i] ?></td>
					<td width="50"  align="center"><?php echo $_SESSION["K01_Code_UpdateFlg_" .$i] ?></td>
					<input type="hidden" name="K01_Code_CodeNo_<?php echo $i ?>" value="<?php echo $_SESSION["K01_Code_CodeNo_" .$i]; ?>">
					<input type="hidden" name="K01_Code_CodeName_<?php echo $i ?>" value="<?php echo $_SESSION["K01_Code_CodeName_" .$i]; ?>">
					<input type="hidden" name="K01_Code_Eda_<?php echo $i ?>" value="<?php echo $_SESSION["K01_Code_Eda_" .$i]; ?>">
					<input type="hidden" name="K01_Code_CodeName1_<?php echo $i ?>" value="<?php echo $_SESSION["K01_Code_CodeName1_" .$i]; ?>">
					<input type="hidden" name="K01_Code_CodeName2_<?php echo $i ?>" value="<?php echo $_SESSION["K01_Code_CodeName2_" .$i]; ?>">
					<input type="hidden" name="K01_Code_SortNo_<?php echo $i ?>" value="<?php echo $_SESSION["K01_Code_SortNo_" .$i]; ?>">
					<input type="hidden" name="K01_Code_UpdateFlg_<?php echo $i ?>" value="<?php echo $_SESSION["K01_Code_UpdateFlg_" .$i]; ?>">
				</tr>
			<?php } ?>
		<?php } ?>
	</table>
	<BR>
	<table border="1" >
		<tr>
			<td id="midashi" width="230" align="center" bgcolor="#c0c0c0">管理No</td>
			<td id="midashi" width="150" align="center" bgcolor="#c0c0c0">区分名</td>
			<td id="midashi" width="150" align="center" bgcolor="#c0c0c0">コードNo</td>
			<td id="midashi" width="300" align="center" bgcolor="#c0c0c0">コード名①</td>
			<td id="midashi" width="300" align="center" bgcolor="#c0c0c0">コード名②</td>
			<td id="midashi" width="150" align="center" bgcolor="#c0c0c0">並び順</td>
			<td id="midashi" width="150" align="center" bgcolor="#c0c0c0">修正可否</td>
			<td id="midashi" width="150" align="center" bgcolor="#c0c0c0"></td>
		</tr>
		<tr>
			<td width="230">
				<?php if($_SESSION["ShoriID"]=="SHUSEI" || $_SESSION["ShoriID"]=="COPY"){?>
					<?php echo $_SESSION["K01_Code_CodeNo"] ?>
				<?php }else{ ?>
					<?php if($_SESSION["KAKUNIFLG"]==1){?>
						<?php echo $_SESSION["K01_Code_CodeNo"] ?>
					<?php }else{ ?>
						<input class="inputtype" type="text" size="10" maxlength="10" name="K01_Code_CodeNo" style="ime-mode: disabled;<?php echo $_SESSION["K01_Code_CodeNo_COLER"] ?>" value="<?php echo $_SESSION["K01_Code_CodeNo"] ?>" onkeyup="checkInputText(this)">
					<?php } ?>
				<?php } ?>
				<?php if($_SESSION["ShoriID"]=="UPD"){?>
					<input type="button" id="getno" name="getno" onClick="sbmfnc(this,'');" style="cursor: pointer" value="最新No" />
				<?php } ?>
				<BR>
				<font size="2" color="#ff0000"><?php echo $_SESSION["K01_Code_CodeNo_ErrMsg"]?></font>
			</td>
			<td width="300">
				<?php if($_SESSION["ShoriID"]=="SHUSEI" || $_SESSION["ShoriID"]=="COPY"){?>
					<?php echo $_SESSION["K01_Code_CodeName"] ?>
				<?php }else{ ?>
					<?php if($_SESSION["KAKUNIFLG"]==1){?>
						<?php echo $_SESSION["K01_Code_CodeName"] ?>
					<?php }else{ ?>
						<input class="inputtype" type="text" size="30" maxlength="25" name="K01_Code_CodeName" style="ime-mode: disabled;<?php echo $_SESSION["K01_Code_CodeName_COLER"] ?>" value="<?php echo $_SESSION["K01_Code_CodeName"] ?>" >
						<font size="2" color="#ff0000"><?php echo $_SESSION["K01_Code_CodeName_ErrMsg"]?></font>
					<?php } ?>
				<?php } ?>
			</td>
			<td width="150">
				<?php if($_SESSION["ShoriID"]=="SHUSEI"){?>
					<?php echo $_SESSION["K01_Code_Eda"] ?>
				<?php }else{ ?>
					<?php if($_SESSION["KAKUNIFLG"]==1){?>
						<?php echo $_SESSION["K01_Code_Eda"] ?>
					<?php }else{ ?>
						<input class="inputtype" type="text" size="20" maxlength="10" name="K01_Code_Eda" style="ime-mode: disabled;<?php echo $_SESSION["K01_Code_Eda_COLER"] ?>" value="<?php echo $_SESSION["K01_Code_Eda"] ?>" onkeyup="checkInputText(this)" >
						<font size="2" color="#ff0000"><?php echo $_SESSION["K01_Code_Eda_ErrMsg"]?></font>
					<?php } ?>
				<?php } ?>
			</td>
			<td width="300">
				<?php if($_SESSION["KAKUNIFLG"]==1){?>
					<?php echo $_SESSION["K01_Code_CodeName1"] ?>
				<?php }else{ ?>
					<input class="inputtype" type="text" size="30" maxlength="25" name="K01_Code_CodeName1" style="ime-mode: disabled;<?php echo $_SESSION["K01_Code_CodeName1_COLER"] ?>" value="<?php echo $_SESSION["K01_Code_CodeName1"] ?>" >
					<font size="2" color="#ff0000"><?php echo $_SESSION["K01_Code_CodeName1_ErrMsg"]?></font>
				<?php } ?>
			</td>
			<td width="300">
				<?php if($_SESSION["KAKUNIFLG"]==1){?>
					<?php echo $_SESSION["K01_Code_CodeName2"] ?>
				<?php }else{ ?>
					<input class="inputtype" type="text" size="30" maxlength="25" name="K01_Code_CodeName2" style="ime-mode: disabled;<?php echo $_SESSION["K01_Code_CodeName2_COLER"] ?>" value="<?php echo $_SESSION["K01_Code_CodeName2"] ?>" >
					<font size="2" color="#ff0000"><?php echo $_SESSION["K01_Code_CodeName2_ErrMsg"]?></font>
				<?php } ?>
			</td>
			<td width="150">
				<?php if($_SESSION["KAKUNIFLG"]==1){?>
					<?php echo $_SESSION["K01_Code_SortNo"] ?>
				<?php }else{ ?>
					<input class="inputtype" type="text" size="10" maxlength="10" name="K01_Code_SortNo" style="ime-mode: disabled;<?php echo $_SESSION["K01_Code_SortNo_COLER"] ?>" value="<?php echo $_SESSION["K01_Code_SortNo"] ?>" onkeyup="checkInputText(this)" >
					<font size="2" color="#ff0000"><?php echo $_SESSION["K01_Code_SortNo_ErrMsg"]?></font>
				<?php } ?>
			</td>
			<td width="150">
				<?php if($_SESSION["KAKUNIFLG"]==1){?>
					<?php echo $_SESSION["K01_Code_UpdateFlg"] ?>
				<?php }else{ ?>
					<input class="inputtype" type="text" size="10" maxlength="10" name="K01_Code_UpdateFlg" style="ime-mode: disabled;<?php echo $_SESSION["K01_Code_UpdateFlg_COLER"] ?>" value="<?php echo $_SESSION["K01_Code_UpdateFlg"] ?>" onkeyup="checkInputText(this)" >
					<font size="2" color="#ff0000"><?php echo $_SESSION["K01_Code_UpdateFlg_ErrMsg"]?></font>
				<?php } ?>
			</td>
			<td width="150" align="center">
				<?php if($_SESSION["ShoriID"]=="SHUSEI"){?>
					<input type="button" id="update" name="update" onClick="sbmfnc(this,'');" style="cursor: pointer" value="更新" />
					<input type="button" id="delete" name="delete" onClick="sbmfnc(this,'');" style="cursor: pointer" value="削除" />
				<?php }else{ ?>
					<?php if($_SESSION["KAKUNIFLG"]==1){?>
						<input type="button" id="newdate" name="newdate" onClick="sbmfnc(this,'');" style="cursor: pointer" value="追加" />
					<?php }else{ ?>
						<input type="button" id="kakunin" name="kakunin" onClick="sbmfnc(this,'');" style="cursor: pointer" value="確認" />
					<?php } ?>
				<?php } ?>
				<input type="button" id="K01clear" name="K01clear" onClick="sbmfnc(this,'');" style="cursor: pointer" value="クリア" />
			</td>
	</table>
	<BR><BR>
	<table border="0" >
		<tr>
			<td width="1000" align="center" bgcolor="<?php echo TEACHR_COLOR ?>" colspan="2">登録済みデータ</td>
		</tr>
	</table>
	<BR><BR>
	<table border="1" >
		<tr>
			<td id="midashi" width="50"  align="center" bgcolor="#c0c0c0">管理No</td>
			<td id="midashi" width="200" align="center" bgcolor="#c0c0c0">区分名</td>
			<td id="midashi" width="50"  align="center" bgcolor="#c0c0c0">コードNo</td>
			<td id="midashi" width="300" align="center" bgcolor="#c0c0c0">コード名①</td>
			<td id="midashi" width="300" align="center" bgcolor="#c0c0c0">コード名②</td>
			<td id="midashi" width="50"  align="center" bgcolor="#c0c0c0">並び順</td>
			<td id="midashi" width="100" align="center" bgcolor="#c0c0c0">修正可否</td>
		</tr>
		<?php for($i=0; $i<$_SESSION["K01_Code_Cnt"]; $i++){ ?>
			<tr>
				<td width="50" align="center"><?php echo $_SESSION["K01_Code_CodeNo_" .$i] ?></td>
				<td width="200"align="center"><?php echo $_SESSION["K01_Code_CodeName_" .$i] ?></td>
				<td width="50" align="center"><?php echo $_SESSION["K01_Code_Eda_" .$i] ?></td>
				<td width="300"align="center"><?php echo $_SESSION["K01_Code_CodeName1_" .$i] ?></td>
				<td width="300"align="center"><?php echo $_SESSION["K01_Code_CodeName2_" .$i] ?></td>
				<td width="50"align="center"><?php echo $_SESSION["K01_Code_SortNo_" .$i] ?></td>
				<td width="50"align="center"><?php echo $_SESSION["K01_Code_UpdateFlg_" .$i] ?></td>
			</tr>
		<?php } ?>
	</table>
	<BR><BR>
</form>
</body>
</CENTER>
</html>