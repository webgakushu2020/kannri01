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
		 	ModoruShori($_SESSION["K01_LoginInfo_RPID"]);
			exit;
		}
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
		}
	}

	for ($m = 0; $m < $_SESSION["K01_Shikaku_Cnt"]; $m++){
		if(isset($_POST["No_" . $m])){
			$_SESSION["ShoriID"]="SHUSEI";
			$_SESSION["KAKUNIFLG"]=0;

			$_SESSION["K01_TeacherID1"] = $_POST['K01_Shikaku_TeacherID_' . $m];
			$_SESSION["K01_Name1"] = $_POST['K01_Shikaku_Name1_' . $m];
			$_SESSION["K01_PassWord"] = $_POST['K01_Shikaku_PassWord_' . $m];
			$_SESSION["K01_PassWord2"] = $_POST['K01_Shikaku_PassWord2_' . $m];
			if($_POST['K01_Shikaku_Shikaku_' . $m] == 0){
				$_SESSION["K01_Shikaku1"] = 1;
			}else{
				$_SESSION["K01_Shikaku1"] = 1;
			}
			if($_POST['K01_Shikaku_Shikaku_' . $m] == 1){
				$_SESSION["K01_Shikaku2"] = 1;
			}else{
				$_SESSION["K01_Shikaku2"] = 0;
			}
			$_SESSION["K01_Shikaku3"] = $_POST['K01_Shikaku_Shikaku1_' . $m];
			$_SESSION["K01_Shikaku4"] = $_POST['K01_Shikaku_Shikaku2_' . $m];
			$_SESSION["K01_YukouTime"] = $_POST['K01_Shikaku_YukouTime_' . $m];

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
			$_SESSION["K01_LoginInfo_RPID"] = $_GET['RPID'];
		      	//print($_SESSION["S04_Kanri02_RPID"]);
		}
		if(isset($_GET['MODE'])) {
			$_SESSION["K01_LoginInfo_MODE"] = $_GET['MODE'];
			$_SESSION["ShoriID"] = $_GET['MODE'];
		      	//print($_SESSION["S04_Kanri02_MODE"] . "<BR>");

			if($_SESSION["K01_LoginInfo_MODE"] == "UPD"){
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
					$_SESSION["ErrMsg"] = 登録ボタンを押してください。;
				} 
				GetData();
				break;
			case 'NEW':
				$EMSG1 = NEWShori(DBNAME);
				if($EMSG1 == "登録しました。"){
					$EMSG1 = NEWShori(DBNAME2);
				}
				$_SESSION["ErrMsg"] = $EMSG1;
				GetData();
				break;
			case 'UPDATE':
				SaveShori();
				$EMSG1 = CheckShori2();
				if($EMSG1 == ""){
					$EMSG1 = UpDateShori(DBNAME);
					//print(DBNAME . "<br>");

					//print($EMSG1 . "<br>");
					if($EMSG1 == "更新しました。"){
						$EMSG1 = UpDateShori(DBNAME2);
					}
				}
				$_SESSION["ErrMsg"] = $EMSG1;
				GetData();
				break;
			case 'DELETE':
				$EMSG1 = DELShori(DBNAME);
				if($EMSG1 == "削除しました。"){
					$EMSG1 = DELShori(DBNAME2);
				}
				$_SESSION["ErrMsg"] = $EMSG1;
				GetData();
				break;
			case 'K01CLEAR':
				SessionClear();
				break;
		}
	}
//-----------------------------------------------------------
//	セッション情報クリア
//-----------------------------------------------------------
function SessionClear(){
	$_SESSION["ErrMsg"] = "";
	$_SESSION["KAKUNIFLG"]=0;

	$_SESSION["K01_TeacherID1_ErrMsg"] = "";
	$_SESSION["K01_TeacherID1_COLER"] = "";
	$_SESSION["K01_PassWord_ErrMsg"] = "";
	$_SESSION["K01_PassWord_COLER"] = "";
	$_SESSION["K01_Shikaku_ErrMsg"] = "";
	$_SESSION["K01_Shikaku_COLER"] = "";
	$_SESSION["K01_YukouTime_ErrMsg"] = "";
	$_SESSION["K01_YukouTime_COLER"] = "";

	$_SESSION["K01_TeacherID1"] = "";
	$_SESSION["K01_Name1"] = "";
	$_SESSION["K01_PassWord"] = "";
	$_SESSION["K01_PassWord2"] = "";
	$_SESSION["K01_Shikaku1"] = 1;
	$_SESSION["K01_Shikaku2"] = 0;
	$_SESSION["K01_Shikaku3"] = 0;
	$_SESSION["K01_Shikaku4"] = 0;
	$_SESSION["K01_YukouTime"] = "2099-03-31";

}
//-----------------------------------------------------------
//	セーブ処理
//-----------------------------------------------------------
function SaveShori(){

	if(isset($_POST['K01_TeacherID1'])){
		$_SESSION["K01_TeacherID1"] = $_POST['K01_TeacherID1'];
	}
	$_SESSION["K01_PassWord"] = $_POST['K01_PassWord'];
	$_SESSION["K01_PassWord2"] = $_POST['K01_PassWord2'];
	$_SESSION["K01_YukouTime"] = $_POST['K01_YukouTime'];

	if(isset($_POST['K01_Shikaku1'])){
		$_SESSION["K01_Shikaku1"]=1;
	}else{
		$_SESSION["K01_Shikaku1"]=0;
	}
	if(isset($_POST['K01_Shikaku2'])){
		$_SESSION["K01_Shikaku2"]=1;
	}else{
		$_SESSION["K01_Shikaku2"]=0;
	}
	if(isset($_POST['K01_Shikaku3'])){
		$_SESSION["K01_Shikaku3"]=1;
	}else{
		$_SESSION["K01_Shikaku3"]=0;
	}
	if(isset($_POST['K01_Shikaku4'])){
		$_SESSION["K01_Shikaku4"]=1;
	}else{
		$_SESSION["K01_Shikaku4"]=0;
	}


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
	$query = "SELECT * FROM K_LoginInfo Order by TeacherID";

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
			$_SESSION["K01_Shikaku_" . $key . "_" .$i]=$data[$i][$key];
		}
		list($name1,$name2) = GetTAtena($_SESSION["K01_Shikaku_TeacherID_" .$i]);
		$_SESSION["K01_Shikaku_Name1_" .$i] = $name1;
		$_SESSION["K01_Shikaku_Name2_" .$i] = $name2;
		$i++;
	}

	$_SESSION["K01_Shikaku_Cnt"] = $i;

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

$_SESSION["K01_TeacherID1_ErrMsg"] = "";
$_SESSION["K01_TeacherID1_COLER"] = "";
$_SESSION["K01_PassWord_ErrMsg"] = "";
$_SESSION["K01_PassWord_COLER"] = "";
$_SESSION["K01_Shikaku_ErrMsg"] = "";
$_SESSION["K01_Shikaku_COLER"] = "";
$_SESSION["K01_YukouTime_ErrMsg"] = "";
$_SESSION["K01_YukouTime_COLER"] = "";

	//-----資格情報-----
	//教師ＩＤが入力されている場合は他の項目必須入力
	if($_SESSION["K01_TeacherID1"] == ""){
		$ErrMsg = "未入力";
		$_SESSION["K01_TeacherID1_ErrMsg"] = $ErrMsg;
		$_SESSION["K01_TeacherID1_COLER"] = $Background;
		$ErrCnt++;
	}
	if($_SESSION["K01_PassWord"] == ""){
		$ErrMsg = "未入力";
		$_SESSION["K01_PassWord_ErrMsg"] = $ErrMsg;
		$_SESSION["K01_PassWord_COLER"] = $Background;
		$ErrCnt++;
	}
	if($_SESSION["K01_Shikaku1"] == 0 && $_SESSION["K01_Shikaku2"] == 0 && $_SESSION["K01_Shikaku3"] == 0 && $_SESSION["K01_Shikaku4"] == 0){
		$ErrMsg = "未入力";
		$_SESSION["K01_Shikaku_ErrMsg"] = $ErrMsg;
		$_SESSION["K01_Shikaku_COLER"] = $Background;
		$ErrCnt++;
	}
	if($_SESSION["K01_YukouTime"] == ""){
		$ErrMsg = "未入力";
		$_SESSION["K01_YukouTime_ErrMsg"] = $ErrMsg;
		$_SESSION["K01_YukouTime_COLER"] = $Background;
		$ErrCnt++;
	}else{
		if($_SESSION["K01_YukouTime"] != ""){
			if (strptime($_SESSION["K01_YukouTime"], '%Y-%m-%d')) {
			}else{
				$ErrMsg = "入力値不正";
				$_SESSION["K01_YukouTime_ErrMsg"] = $ErrMsg;
				$_SESSION["K01_YukouTime_COLER"] = $Background;
				$ErrCnt++;
			}
		}
	}

	if($ErrCnt > 0){
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
		if($_SESSION["K01_TeacherID1"] != ""){
			$query = "SELECT * FROM K_LoginInfo";
			$query = $query . " WHERE TeacherID = '" . $_SESSION["K01_TeacherID1"] . "'";

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
					$_SESSION["K01_Shikaku_" . $key] = $value;
				}
				$ShikakuCnt++;
			}
			if($ShikakuCnt > 0){
				list($name1,$name2) = GetTAtena($_SESSION["K01_Shikaku_TeacherID"]);
				$_SESSION["K01_Shikaku_Name1"] = $name1;
				$_SESSION["K01_Shikaku_Name2"] = $name2;
			}
		}
		$_SESSION["K01_Shikaku_Cnt"] = $ShikakuCnt;

		if($_SESSION["K01_Shikaku_Cnt"] > 0){
			$ErrMsg = "この教師IDはすでに登録されています。<BR>";
			$ErrMsg = $ErrMsg . $_SESSION["K01_Shikaku_TeacherID"] . $_SESSION["K01_Shikaku_Name1"];
		}

	 	// データベースの切断
		$mysqli->close();
	}
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

	//-----資格情報-----
	if($_SESSION["K01_PassWord"] == ""){
		$ErrMsg = "未入力";
		$_SESSION["K01_PassWord_ErrMsg"] = $ErrMsg;
		$_SESSION["K01_PassWord_COLER"] = $Background;
		$ErrCnt++;
	}
	if($_SESSION["K01_Shikaku1"] == 0 && $_SESSION["K01_Shikaku2"] == 0 && $_SESSION["K01_Shikaku3"] == 0 && $_SESSION["K01_Shikaku4"] == 0){
		$ErrMsg = "未入力";
		$_SESSION["K01_Shikaku_ErrMsg"] = $ErrMsg;
		$_SESSION["K01_Shikaku_COLER"] = $Background;
		$ErrCnt++;
	}
	if($_SESSION["K01_YukouTime"] == ""){
		$ErrMsg = "未入力";
		$_SESSION["K01_YukouTime_ErrMsg"] = $ErrMsg;
		$_SESSION["K01_YukouTime_COLER"] = $Background;
		$ErrCnt++;
	}else{
		if($_SESSION["K01_YukouTime"] != ""){
			if (strptime($_SESSION["K01_YukouTime"], '%Y-%m-%d')) {
			}else{
				$ErrMsg = "入力値不正";
				$_SESSION["K01_YukouTime_ErrMsg"] = $ErrMsg;
				$_SESSION["K01_YukouTime_COLER"] = $Background;
				$ErrCnt++;
			}
		}
	}

	$ErrAllCnt = $ErrCnt;
	$_SESSION["K01_ErrAllCnt"] = $ErrAllCnt;

	$ErrCnt = 0;

	return $_SESSION["K01_ErrAllCnt"];

}
//-----------------------------------------------------------
//	更新処理
//-----------------------------------------------------------
Function UpdateShori($dbname){
$ErrFlg = 0;

	// mysqlへの接続
	$mysqli = new mysqli(HOST, USER, PASS);
	if ($mysqli->connect_errno) {
		print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
		exit();
	}

	// データベースの選択
	$mysqli->select_db($dbname);
	$mysqli->set_charset("utf8");

	//トランザクションをはじめる準備
	$Query = "set autocommit = 0";
	$mysqli->query( $Query);

	//トランザクション開始
	$Query = "begin";
	$mysqli->query( $Query);
	//資格情報登録
	$query = "UPDATE K_LoginInfo SET";
	$query = $query . " PassWord = '" . $_SESSION["K01_PassWord"] . "'";
	$query = $query . ", PassWord2 = '" . $_SESSION["K01_PassWord2"] . "'";
	if($_SESSION["K01_Shikaku2"] == 1){
		$query = $query . ", Shikaku = '1'";
	}else{
		$query = $query . ", Shikaku = '0'";
	}
	if($_SESSION["K01_Shikaku3"] == 1){
		$query = $query . ", Shikaku1 = '1'";
	}else{
		$query = $query . ", Shikaku1 = '0'";
	}
	if($_SESSION["K01_Shikaku4"] == 1){
		$query = $query . ", Shikaku2 = '1'";
	}else{
		$query = $query . ", Shikaku2 = '0'";
	}
//		$query = $query . ", Shikaku3 = ";
//	$query = $query . ", EntryID = ";
//	$query = $query . ", EntryTime = ";
	$query = $query . ", YukouTime = '" . $_SESSION["K01_YukouTime"] . "'";
	if($_SESSION["K01_PassWord2"] == ""){
		$query = $query . ", PassFlg = '0'";
	}else{
		$query = $query . ", PassFlg = '1'";
	}
	$query = $query . " Where TeacherID = '" . $_SESSION["K01_TeacherID1"] . "'";

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
Function NEWShori($dbname){
$ErrFlg = 0;

	// mysqlへの接続
	$mysqli = new mysqli(HOST, USER, PASS);
	if ($mysqli->connect_errno) {
		print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
		exit();
	}

	// データベースの選択
	$mysqli->select_db($dbname);
	$mysqli->set_charset("utf8");

	//トランザクションをはじめる準備
	$Query = "set autocommit = 0";
	$mysqli->query( $Query);

	//トランザクション開始
	$Query = "begin";
	$mysqli->query( $Query);

	//資格情報登録
	$query = "INSERT INTO K_LoginInfo ";
	$query = $query . "values(";
	$query = $query . "'" . $_SESSION["K01_TeacherID1"] . "'";
	$query = $query . ",'" . $_SESSION["K01_PassWord"] . "'";
	$query = $query . ",NULL";
	if($_SESSION["K01_Shikaku2"]==1){
		$query = $query . ",'1'";
	}else{
		$query = $query . ",'0'";
	}
	if($_SESSION["K01_Shikaku3"]==1){
		$query = $query . ",'1'";
	}else{
		$query = $query . ",'0'";
	}
	if($_SESSION["K01_Shikaku4"]==1){
		$query = $query . ",'1'";
	}else{
		$query = $query . ",'0'";
	}
	$query = $query . ",'0'";
	$query = $query . ",'" . $_SESSION["LoginTeacherID"] . "'";
	$query = $query . ",'" . $_SESSION["Today"] . "'";
	$query = $query . ",'" . $_SESSION["K01_YukouTime"] . "'";
	$query = $query . ",'0'";
	$query = $query . ")";

	//print($query);

	$result = $mysqli->query($query);
	if (!$result) {
		$ErrMSG = "クエリーが失敗しました。" . $mysqli->error;
		$ErrFlg = 1;
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
Function DELShori($dbname){
$ErrFlg = 0;

	// mysqlへの接続
	$mysqli = new mysqli(HOST, USER, PASS);
	if ($mysqli->connect_errno) {
		print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
		exit();
	}

	// データベースの選択
	$mysqli->select_db($dbname);
	$mysqli->set_charset("utf8");

	//トランザクションをはじめる準備
	$Query = "set autocommit = 0";
	$mysqli->query( $Query);

	//トランザクション開始
	$Query = "begin";
	$mysqli->query( $Query);

	//資格情報登録
	$query = "DELETE FROM K_LoginInfo ";
	$query = $query . "Where TeacherID= '" . $_SESSION["K01_TeacherID1"] . "'";

	//print($query);

	$result = $mysqli->query($query);
	if (!$result) {
		$ErrMSG = "クエリーが失敗しました。" . $mysqli->error;
		$ErrFlg = 1;
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
?>
<script type="text/javascript">
function setWarning(wMsg){
	if(window.confirm(wMsg)){
		b.form.submit();
	}
}
function clr(){

  document.form1.K01_PassWord2.value="";

}

</script>
<script type="text/javascript" src="utility.js"></script>

<CENTER>
<body onload="" onKeyPress="OnKey(event.keyCode,'');">
<form name="form1" method="post" action="K01_LoginInfo.php">
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
			<td width="1000" align="center" bgcolor="<?php echo TEACHR_COLOR ?>" colspan="2">ログイン情報</td>
		</tr>
	</table>
	<BR><BR>
	<table border="1" >
		<tr>
			<td id="midashi" width="200" align="center" bgcolor="#c0c0c0">教師ＩＤ</td>
			<td id="midashi" width="150" align="center" bgcolor="#c0c0c0">初期パスワード</td>
			<td id="midashi" width="150" align="center" bgcolor="#c0c0c0">新パスワード</td>
			<td id="midashi" width="300" align="center" bgcolor="#c0c0c0">資格</td>
			<td id="midashi" width="150" align="center" bgcolor="#c0c0c0">有効期限</td>
			<td id="midashi" width="150" align="center" bgcolor="#c0c0c0"></td>
		</tr>
		<tr>
			<td width="200">
				<?php if($_SESSION["ShoriID"]=="SHUSEI"){?>
					<?php echo $_SESSION["K01_TeacherID1"] ?>
				<?php }else{ ?>
					<?php if($_SESSION["KAKUNIFLG"]==1){?>
						<?php echo $_SESSION["K01_TeacherID1"] ?>
					<?php }else{ ?>
						<input class="inputtype" type="text" size="10" maxlength="10" name="K01_TeacherID1" style="ime-mode: disabled;<?php echo $_SESSION["K01_TeacherID1_COLER"] ?>" value="<?php echo $_SESSION["K01_TeacherID1"] ?>" onkeyup="checkInputText(this)">
					<?php } ?>
				<?php } ?>
				<?php echo $_SESSION["K01_Name1"]?>
				<BR>
				<font size="2" color="#ff0000"><?php echo $_SESSION["K01_TeacherID1_ErrMsg"]?></font>
			</td>
			<td width="150">
				<?php if($_SESSION["KAKUNIFLG"]==1){?>
					<?php echo $_SESSION["K01_PassWord"] ?>
				<?php }else{ ?>
					<input class="inputtype" type="text" size="10" maxlength="10" name="K01_PassWord" style="ime-mode: disabled;<?php echo $_SESSION["K01_PassWord_COLER"] ?>" value="<?php echo $_SESSION["K01_PassWord"] ?>" onkeyup="checkInputText(this)" >
					<font size="2" color="#ff0000"><?php echo $_SESSION["K01_PassWord_ErrMsg"]?></font>
				<?php } ?>
			</td>
			<td width="300">
				<input readonly class="inputtype" type="text" size="30" maxlength="100" name="K01_PassWord2" style="ime-mode: disabled;<?php echo $_SESSION["K01_PassWord2_COLER"] ?>" value="<?php echo $_SESSION["K01_PassWord2"] ?>" onkeyup="checkInputText(this)">
				<input type="button" name="K01_PassWord2_clear" value="クリア" onclick="clr()">
			</td>
			<td width="300">
				<?php if($_SESSION["KAKUNIFLG"]==1){?>
					<?php if($_SESSION["K01_Shikaku2"]==1){?>
						■報告のみ　■システム閲覧　
					<?php }else{ ?>
						■報告のみ　
					<?php } ?>
					<?php if($_SESSION["K01_Shikaku3"]==1){?>
						■契約更新　
					<?php } ?>
					<?php if($_SESSION["K01_Shikaku4"]==1){?>
						■一般更新　
					<?php } ?>
				<?php }else{ ?>
					<input readonly type="checkbox" name="K01_Shikaku1" value="<?php echo $_SESSION["K01_Shikaku1"] ?>" <?php if($_SESSION["K01_Shikaku1"] == 1){?> CHECKED <?php } ?>>報告のみ　
					<input type="checkbox" name="K01_Shikaku2" value="<?php echo $_SESSION["K01_Shikaku2"] ?>" <?php if($_SESSION["K01_Shikaku2"] == 1){?> CHECKED <?php } ?>>システム閲覧　<BR>
					<input type="checkbox" name="K01_Shikaku3" value="<?php echo $_SESSION["K01_Shikaku3"] ?>" <?php if($_SESSION["K01_Shikaku3"] == 1){?> CHECKED <?php } ?>>契約更新　
					<input type="checkbox" name="K01_Shikaku4" value="<?php echo $_SESSION["K01_Shikaku4"] ?>" <?php if($_SESSION["K01_Shikaku4"] == 1){?> CHECKED <?php } ?>>一般更新　
				<?php } ?>
				<BR>
				<font size="2" color="#ff0000"><?php echo $_SESSION["K01_Shikaku_ErrMsg"]?></font>
			</td>
			<td width="150" align="left">
				<?php if($_SESSION["KAKUNIFLG"]==1){?>
					<?php echo $_SESSION["K01_YukouTime"] ?>
				<?php }else{ ?>
					<input class="inputtype" type="text" size="10" maxlength="10" name="K01_YukouTime" style="ime-mode: disabled;<?php echo $_SESSION["K01_YukouTime_COLER"] ?>" value="<?php echo $_SESSION["K01_YukouTime"] ?>">
				<?php } ?>
				<font size="2" color="#ff0000"><?php echo $_SESSION["K01_YukouTime_ErrMsg"]?></font>
			</td>

			<td width="150" align="center">
				<?php if($_SESSION["ShoriID"]=="SHUSEI"){?>
					<input type="button" id="update" name="update" onClick="sbmfnc(this,'');" style="cursor: pointer" value="更新" />
					<input type="button" id="delete" name="delete" onClick="sbmfnc(this,'');" style="cursor: pointer" value="削除" />
				<?php }else{ ?>
					<?php if($_SESSION["KAKUNIFLG"]==1){?>
						<input type="button" id="newdate" name="newdate" onClick="sbmfnc(this,'');" style="cursor: pointer" value="登録" />
					<?php }else{ ?>
						<input type="button" id="kakunin" name="kakunin" onClick="sbmfnc(this,'');" style="cursor: pointer" value="確認" />
					<?php } ?>
				<?php } ?>
				<input type="button" id="K01clear" name="K01clear" onClick="sbmfnc(this,'');" style="cursor: pointer" value="クリア" />
			</td>
		</tr>
	</table>
	<table border="0" >
		■報告のみ⇒報告システムのみ使用可能■システム閲覧⇒管理システム使用可能■契約更新⇒契約情報の更新可能■一般更新⇒登録・更新可能（一般更新権限がない場合は参照のみ）
	</table>
	<BR><BR>
	<table border="1" >
		<tr>
			<td id="midashi" width="150" align="center" bgcolor="#c0c0c0">選択</td>
			<td id="midashi" width="150" align="center" bgcolor="#c0c0c0">教師ＩＤ</td>
			<td id="midashi" width="150" align="center" bgcolor="#c0c0c0">教師名</td>
			<td id="midashi" width="150" align="center" bgcolor="#c0c0c0">初期パスワード</td>
			<td id="midashi" width="150" align="center" bgcolor="#c0c0c0">新パスワード</td>
			<td id="midashi" width="300" align="center" bgcolor="#c0c0c0">資格</td>
			<td id="midashi" width="150" align="center" bgcolor="#c0c0c0">有効期限</td>

		</tr>
		<?php for($i=0; $i<$_SESSION["K01_Shikaku_Cnt"]; $i++){ ?>
			<tr>
				<td height="30" align="center"><input type="submit" name="No_<?php echo $i ?>" size="10" value="<?php echo $i+1 ?>"></td>
				<td align="center"><?php echo $_SESSION["K01_Shikaku_TeacherID_" .$i] ?></td>
				<td align="center"><?php echo $_SESSION["K01_Shikaku_Name1_" .$i] ?></td>
				<td align="center"><?php echo $_SESSION["K01_Shikaku_PassWord_" .$i] ?></td>
				<td align="center"><?php if($_SESSION["K01_Shikaku_PassWord2_" .$i]==""){?>未登録<?php }else{ ?>済<?php } ?></td>
				<td width="300" align="left">
					<?php 
						if($_SESSION["K01_Shikaku_Shikaku_" .$i] == "0"){
							echo ■報告のみ;
						}else{
							echo ■報告のみ　■システム閲覧;
						} 
					?>
					<?php 
						if($_SESSION["K01_Shikaku_Shikaku1_" .$i] == "1"){
							echo ■契約更新;
						}else{
						} 
					?>
					<?php 
						if($_SESSION["K01_Shikaku_Shikaku2_" .$i] == "1"){
							echo ■一般更新;
						}else{
						} 
					?>
				</td>
				<td align="center"><?php if(is_null($_SESSION["K01_Shikaku_YukouTime_" .$i])){ }else{ echo date('Y年n月j日', strtotime($_SESSION["K01_Shikaku_YukouTime_" .$i])); } ?></td>

				<input type="hidden" name="K01_Shikaku_TeacherID_<?php echo $i ?>" value="<?php echo $_SESSION["K01_Shikaku_TeacherID_" .$i]; ?>">
				<input type="hidden" name="K01_Shikaku_Name1_<?php echo $i ?>" value="<?php echo $_SESSION["K01_Shikaku_Name1_" .$i]; ?>">
				<input type="hidden" name="K01_Shikaku_PassWord_<?php echo $i ?>" value="<?php echo $_SESSION["K01_Shikaku_PassWord_" .$i]; ?>">
				<input type="hidden" name="K01_Shikaku_PassWord2_<?php echo $i ?>" value="<?php echo $_SESSION["K01_Shikaku_PassWord2_" .$i]; ?>">
				<input type="hidden" name="K01_Shikaku_Shikaku_<?php echo $i ?>" value="<?php echo $_SESSION["K01_Shikaku_Shikaku_" .$i]; ?>">
				<input type="hidden" name="K01_Shikaku_Shikaku1_<?php echo $i ?>" value="<?php echo $_SESSION["K01_Shikaku_Shikaku1_" .$i]; ?>">
				<input type="hidden" name="K01_Shikaku_Shikaku2_<?php echo $i ?>" value="<?php echo $_SESSION["K01_Shikaku_Shikaku2_" .$i]; ?>">
				<input type="hidden" name="K01_Shikaku_YukouTime_<?php echo $i ?>" value="<?php echo $_SESSION["K01_Shikaku_YukouTime_" .$i]; ?>">

			</tr>
		<?php } ?>
	</table>
	<BR><BR>
</form>
</body>
</CENTER>
</html>