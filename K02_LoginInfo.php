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
		 	ModoruShori($_SESSION["K02_LoginInfo_RPID"]);
			exit;
		}
	}

	if(isset($_POST['submitter'])){
		switch ($_POST['submitter']){
			case 'newdate':
				$_SESSION["ShoriID"]="NEW";
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
			$_SESSION["K02_LoginInfo_RPID"] = $_GET['RPID'];
		      	//print($_SESSION["S04_Kanri02_RPID"]);
		}
		if(isset($_GET['MODE'])) {
			$_SESSION["K02_LoginInfo_MODE"] = $_GET['MODE'];
			$_SESSION["ShoriID"] = $_GET['MODE'];
		      	//print($_SESSION["S04_Kanri02_MODE"] . "<BR>");

			if($_SESSION["K02_LoginInfo_MODE"] == "UPD"){
				$_SESSION["K02_LoginTeacherID"] = $_SESSION["LoginTeacherID"];
				$_SESSION["K02_LoginTName1"] = $_SESSION["LoginTName1"];
				$_SESSION["K02_LoginTName2"] = $_SESSION["LoginTName2"];
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
				$i = 0;
				SessionClear();

				break;

			case 'NEW':
				SaveShori();
				$EMSG1 = CheckShori();
				if($EMSG1 > 0){
					$EMSG2 = CheckShori2();
				}
				if($EMSG2 == ""){
					$EMSG3 = NEWShori();
					$_SESSION["ErrMsg"] = $EMSG3;
				}else{
					$_SESSION["ErrMsg"] = $EMSG2;
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
	$_SESSION["K02_PassWord"] = "";
	$_SESSION["K02_Shikaku1"] = 1;
	$_SESSION["K02_Shikaku2"] = 0;
	$_SESSION["K02_Shikaku3"] = 0;
	$_SESSION["K02_Shikaku4"] = 0;
	$_SESSION["K02_YukouTime"] = "2099-03-31";
	$_SESSION["K02_EntryID"] = $_SESSION["K02_LoginTeacherID"];
	$_SESSION["K02_EntryTime"] = $_SESSION["Today"];

	$_SESSION["K02_TeacherID1_ErrMsg"] = "";
	$_SESSION["K02_TeacherID1_COLER"] = "";
	$_SESSION["K02_PassWord_ErrMsg"] = "";
	$_SESSION["K02_PassWord_COLER"] = "";
	$_SESSION["K02_Shikaku_ErrMsg"] = "";
	$_SESSION["K02_Shikaku_COLER"] = "";
	$_SESSION["K02_YukouTime_ErrMsg"] = "";
	$_SESSION["K02_YukouTime_COLER"] = "";

}
//-----------------------------------------------------------
//	セーブ処理
//-----------------------------------------------------------
function SaveShori(){

	$_SESSION["K02_TeacherID1"] = $_POST['K02_TeacherID1'];
	$_SESSION["K02_PassWord"] = $_POST['K02_PassWord'];
	$_SESSION["K02_Shikaku1"] = $_POST['K02_Shikaku1'];
	$_SESSION["K02_Shikaku2"] = $_POST['K02_Shikaku2'];
	$_SESSION["K02_Shikaku3"] = $_POST['K02_Shikaku3'];
	$_SESSION["K02_Shikaku4"] = $_POST['K02_Shikaku4'];
	$_SESSION["K02_YukouTime"] = $_POST['K02_YukouTime'];

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

	//-----資格情報-----
	//教師ＩＤが入力されている場合は他の項目必須入力
	if($_SESSION["K02_TeacherID1"] == ""){
		$ErrMsg = "未入力";
		$_SESSION["K02_TeacherID1_ErrMsg"] = $ErrMsg;
		$_SESSION["K02_TeacherID1_COLER"] = $Background;
		$ErrCnt++;
	}
	if($_SESSION["K02_PassWord"] == ""){
		$ErrMsg = "未入力";
		$_SESSION["K02_PassWord_ErrMsg"] = $ErrMsg;
		$_SESSION["K02_PassWord_COLER"] = $Background;
		$ErrCnt++;
	}
	if($_SESSION["K02_Shikaku1"] == 0 && $_SESSION["K02_Shikaku2"] == 0 && $_SESSION["K02_Shikaku3"] == 0 && $_SESSION["K02_Shikaku4"] == 0){
		$ErrMsg = "未入力";
		$_SESSION["K02_Shikaku_ErrMsg"] = $ErrMsg;
		$_SESSION["K02_Shikaku_COLER"] = $Background;
		$ErrCnt++;
	}
	if($_SESSION["K02_YukouTime"] == ""){
		$ErrMsg = "未入力";
		$_SESSION["K02_YukouTime_ErrMsg"] = $ErrMsg;
		$_SESSION["K02_YukouTime_COLER"] = $Background;
		$ErrCnt++;
	}else{
		if($_SESSION["K02_YukouTime"] != ""){
			if (strptime($_SESSION["K02_YukouTime"], '%Y-%m-%d')) {
			}else{
				$ErrMsg = "入力値不正";
				$_SESSION["K02_YukouTime_ErrMsg"] = $ErrMsg;
				$_SESSION["K02_YukouTime_COLER"] = $Background;
				$ErrCnt++;
			}
		}
	}

	if($ErrCnt > 0){
		$ErrAllCnt = $ErrCnt;
	}
	$ErrCnt = 0;

	$_SESSION["K02_ErrAllCnt"] = $ErrAllCnt;

	return $_SESSION["K02_ErrAllCnt"];

}
//-----------------------------------------------------------
//	チェック処理
//-----------------------------------------------------------
function CheckShori2(){
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
		if($ShikakuCnt > 0){
			list($name1,$name2) = GetTAtena($_SESSION["K02_Shikaku_TeacherID"]);
			$_SESSION["K02_Shikaku_Name1"] = $name1;
			$_SESSION["K02_Shikaku_Name2"] = $name2;
		}
	}
	$_SESSION["K02_Shikaku_Cnt"] = $ShikakuCnt;

	if($_SESSION["K02_Shikaku_Cnt"] > 0){
		$ErrMsg = "この教師IDはすでに登録されています。<BR>";
		$ErrMsg = $ErrMsg . $_SESSION["K02_Shikaku_TeacherID"] . $_SESSION["K02_Shikaku_Name1"];
	}

 	// データベースの切断
	$mysqli->close();

	return $ErrMsg;

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

	//資格情報登録
	$query = "INSERT INTO K_LoginInfo ";
	$query = $query . "values(";
	$query = $query . "'" . $_SESSION["K02_TeacherID1"] . "'";
	$query = $query . ",'" . $_SESSION["K02_PassWord"] . "'";
	$query = $query . ",NULL";
	if($_SESSION["K02_Shikaku2"]==1){
		$query = $query . ",'1'";
	}else{
		$query = $query . ",'0'";
	}
	if($_SESSION["K02_Shikaku3"]==1){
		$query = $query . ",'1'";
	}else{
		$query = $query . ",'0'";
	}
	if($_SESSION["K02_Shikaku4"]==1){
		$query = $query . ",'1'";
	}else{
		$query = $query . ",'0'";
	}
	$query = $query . ",'0'";
	$query = $query . ",'" . $_SESSION["K02_EntryID"] . "'";
	$query = $query . ",'" . $_SESSION["K02_EntryTime"] . "'";
	$query = $query . ",'" . $_SESSION["K02_YukouTime"] . "'";
	$query = $query . ",'0'";
	$query = $query . ")";

	print($query);

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
<form name="form1" method="post" action="K02_LoginInfo.php">
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
			<td align="center" bgcolor="<?php echo TEACHR_COLOR ?>" colspan="2">ログイン情報</td>
		</tr>
		<tr>
			<td id="midashi" width="150" align="center" bgcolor="#c0c0c0">教師ＩＤ</td>
			<td>
				<input class="inputtype" type="text" size="10" maxlength="10" name="K02_TeacherID1" style="ime-mode: disabled;" value="<?php echo $_SESSION["K02_TeacherID1"] ?>" onkeyup="checkInputText(this)">
			</td>
		</tr>
		<tr>
			<td id="midashi" width="150" align="center" bgcolor="#c0c0c0">パスワード</td>
			<td>
				<input class="inputtype" type="text" size="10" maxlength="10" name="K02_PassWord" style="ime-mode: disabled;<?php echo $_SESSION["K02_PassWord_COLER"] ?>" value="<?php echo $_SESSION["K02_PassWord"] ?>" onkeyup="checkInputText(this)">
				<font size="2" color="#ff0000"><?php echo $_SESSION["K02_PassWord_ErrMsg"]?></font>
			</td>
		</tr>
		<tr>
			<td id="midashi" width="150" align="center" bgcolor="#c0c0c0">資格</td>
			<td>
				<input type="checkbox" name="K02_Shikaku1" value="<?php echo $_SESSION["K02_Shikaku1"] ?>" <?php if($_SESSION["K02_Shikaku1"] == 1){?> CHECKED <?php } ?>>報告のみ　　※一般教師はここのみチェック<BR>
				<input type="checkbox" name="K02_Shikaku2" value="<?php echo $_SESSION["K02_Shikaku2"] ?>" <?php if($_SESSION["K02_Shikaku2"] == 1){?> CHECKED <?php } ?>>登録　　　　　※事務担当、システム操作可能<BR>
				<input type="checkbox" name="K02_Shikaku3" value="<?php echo $_SESSION["K02_Shikaku3"] ?>" <?php if($_SESSION["K02_Shikaku3"] == 1){?> CHECKED <?php } ?>>契約更新　　※契約情報を職権修正できるようにする<BR>
				<input type="checkbox" name="K02_Shikaku4" value="<?php echo $_SESSION["K02_Shikaku4"] ?>" <?php if($_SESSION["K02_Shikaku4"] == 1){?> CHECKED <?php } ?>>営業　　　　　※未使用<BR>
			</td>
		</tr>
		<tr>
			<td id="midashi" width="150" align="center" bgcolor="#c0c0c0">有効期限</td>
			<td align="left">
				<input class="inputtype" type="text" size="10" maxlength="10" name="K02_YukouTime" style="ime-mode: disabled;<?php echo $_SESSION["K02_YukouTime_COLER"] ?>" value="<?php echo $_SESSION["K02_YukouTime"] ?>">
				<font size="2" color="#ff0000"><?php echo $_SESSION["K02_YukouTime_ErrMsg"]?></font>
			</td>
		</tr>
	</table>
	<table border="0" width="100%">
		<tr>
			<td width="100" align="center">
				<input type="button" id="newdate" name="newdate" onClick="sbmfnc(this,'');" style="cursor: pointer" value="登録" />
			</td>
		</tr>
	</table>
	<BR><BR>
</form>
</body>
</CENTER>
</html>