<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN"
	"http://www.w3.org/TR/html4/frameset.dtd">

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="Header.css">
	<title>検索画面</title>
</head>

<?php include 'const.php'; ?>
<?php include 'utility.php'; ?>

<?php
session_start();


	$dt = new DateTime();
	$dt->setTimeZone(new DateTimeZone('Asia/Tokyo'));
	$Today = $dt->format('Y/m/d');
	$EMSG = "";

	// ログアウト処理
	if(isset($_POST['logout'])){
	 	LogoutShori();
		exit;
	}

	// 戻る処理
	if(isset($_POST['modoru'])){
	 	ModoruShori($_SESSION["K_kensaku_RPID"]);
		exit;
	}

	// 検索処理
	if(isset($_POST['kensaku'])){
	 	$_SESSION["ShoriID"]="KENSAKU";
	}

	// ログイン済みかどうかの変数チェックを行う
	if (!isset($_SESSION["user_name"])) {

		// 変数に値がセットされていない場合は不正な処理と判断し、ログイン画面へリダイレクトさせる
		$no_login_url = "http://{$_SERVER["HTTP_HOST"]}/Login1.php";
		header("Location: {$no_login_url}");
		exit;
	} else {

		//セッション情報保存
		//前画面からの情報
		$TeacherID = $_SESSION["TeacherID"];
		$user_name = $_SESSION["user_name"];
		$shikaku = (int) $_SESSION["shikaku"];
		$TName1 = $_SESSION["TName1"];
		$TName2 = $_SESSION["TName2"];

		//URLパラメータ
		if(isset($_GET['RPID'])) {
			$_SESSION["K_kensaku_RPID"] = $_GET['RPID'];
		      	//print($_SESSION["K_kensaku_RPID"]);
		}
		if(isset($_GET['MODE'])) {


			if($_GET['MODE'] != "Modoru"){
				$_SESSION["K_kensaku_MODE"] = $_GET['MODE'];
				$_SESSION["ShoriID"]=$_GET['MODE'];
			      	//print($_SESSION["K_kensaku_MODE"] . "<BR>");
			}
		}

		switch ($_SESSION["ShoriID"]){
			case 'KENT': case 'KENS':
				SessionClear();
				break;

			case 'KENSAKU':

				$EMSG = K_CheckShori();
				if($EMSG == ""){
					SaveShori();

					// mysqlへの接続
					$mysqli = new mysqli(HOST, USER, PASS);
					if ($mysqli->connect_errno) {
						print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
						exit();
					   		}

					// データベースの選択
					$mysqli->select_db(DBNAME);
					$mysqli->set_charset("utf8");

					switch ($_SESSION["K_kensaku_MODE"]){
						//教師ID
						case 'KENT':
							// 入力値のサニタイズ
							$userid = $mysqli->real_escape_string($_SESSION["K_TeacherID"]);
							$username = $mysqli->real_escape_string($_SESSION["K_TeacherName"]);
							$_SESSION["K_KEY1"] = $_SESSION["K_TeacherID"];
							$_SESSION["K_KEY2"] = $_SESSION["K_TeacherName"];

							switch ($_SESSION["KensakuFlg"]){
								case '10': case '11':
									$query = "SELECT Count(*) AS Kensu ,TeacherID AS ID FROM T_TantoShosai WHERE  TeacherID = '" . $userid ."'";
									break;

								case '01':
									$query = "SELECT Count(*) AS Kensu ,TeacherID AS ID FROM T_AtenaInfo WHERE Name1 like '%" . $username ."%' or Name2 like '%" . $username ."%'";
									break;

							}

							break;

						//生徒ID
						case 'KENS':
							// 入力値のサニタイズ
							$userid = $mysqli->real_escape_string($_SESSION["K_StudentID"]);
							$username = $mysqli->real_escape_string($_SESSION["K_StudentName"]);
							$_SESSION["K_KEY1"] = $_SESSION["K_StudentID"];
							$_SESSION["K_KEY2"] = $_SESSION["K_StudentName"];

							switch ($_SESSION["KensakuFlg"]){
								case '10': case '11':
									$query = "SELECT Count(*) AS Kensu ,StudentID AS ID FROM T_TantoShosai WHERE  StudentID = '" . $userid ."'";
									break;

								case '01':
									$query = "SELECT Count(*) AS Kensu ,StudentID AS ID FROM S_AtenaInfo WHERE Name1 like '%" . $username ."%' or Name2 like '%" . $username ."%'";
									break;
							}
						break;
					}

					$result = $mysqli->query($query);

					if (!$result) {
						print('クエリーが失敗しました。' . $mysqli->error);
						$mysqli->close();
						exit();
					}
					
					while ($row = $result->fetch_assoc()) {
						$_SESSION["db_Count"] = $row['Kensu'];
						$_SESSION["db_ID"] = $row['ID'];
					}

					//検索結果が１件の場合はＤＢのＩＤと置き換える。（名前検索の場合にＩＤがないため）
					if($_SESSION["db_Count"] == 1){
						$_SESSION["K_KEY1"] = $_SESSION["db_ID"];
					}

				 	// データベースの切断
					$mysqli->close();		

					if($_SESSION["db_Count"] == 0){
						$EMSG = "該当のデータがありません。";
					}else {
						header("Location:H01_Kensaku2.php?MODE=" . $_SESSION["K_kensaku_MODE"] . "&RPID=H01_Kensaku&KEY1=" .$_SESSION["K_KEY1"] . "&KEY2=" .$_SESSION["K_KEY2"]. "&CNT=" .$_SESSION["db_Count"]. "&KFLG=" .$_SESSION["KensakuFlg"]);
					}
				}

				break;
		}	
	}
//-----------------------------------------------------------
//	セッション情報クリア
//-----------------------------------------------------------
function SessionClear(){

	$_SESSION["K_TeacherID"] = "";
	$_SESSION["K_TeacherName"] = "";
	$_SESSION["K_StudentID"] = "";
	$_SESSION["K_StudentName"] = "";
	$_SESSION["KensakuFlg"] = "";

}
//-----------------------------------------------------------
//	画面情報保存
//-----------------------------------------------------------
Function K_CheckShori(){

	if($_SESSION["K_kensaku_MODE"] == "KENT"){
		if($_POST['K_TeacherID']=="" && $_POST['K_TeacherName']=="") {
			return "検索条件を入力してください。";
		}else{
			return "";
		}  
	}
	if($_SESSION["K_kensaku_MODE"] == "KENS"){
		if($_POST['K_StudentID']=="" && $_POST['K_StudentName']=="") {
			return "検索条件を入力してください。";
		}else{
			return "";
		}  
	}
}

Function SaveShori(){

	if($_SESSION["K_kensaku_MODE"] == "KENT"){

		if($_POST['K_TeacherID']=="") {
			$KensakuFlg = "0";

		}else{
			$_SESSION["K_TeacherID"] = $_POST['K_TeacherID'];
			$KensakuFlg = "1";

		}

		if($_POST['K_TeacherName']=="") {
			$KensakuFlg = $KensakuFlg . "0";

		}else{
			$_SESSION["K_TeacherName"] =	$_POST['K_TeacherName'];
			$KensakuFlg = $KensakuFlg . "1";

		}
	}
	if($_SESSION["K_kensaku_MODE"] == "KENS"){
		if($_POST['K_StudentID']=="") {
			$KensakuFlg = "0";

		}else{
			$_SESSION["K_StudentID"] =	$_POST['K_StudentID'];
			$KensakuFlg = "1";

		}
		if($_POST['K_StudentName']=="") {
			$KensakuFlg = $KensakuFlg . "0";

		}else{
			$_SESSION["K_StudentName"] =	$_POST['K_StudentName'];
			$KensakuFlg = $KensakuFlg . "1";

		}
	}

	$_SESSION["KensakuFlg"] = $KensakuFlg;

//print($_SESSION["K_TeacherID"] . "<BR>");
//print($_SESSION["K_TeacherName"] . "<BR>");
//print($_SESSION["K_StudentID"] . "<BR>");
//print($_SESSION["K_StudentName"] . "<BR>");

}

?>
<CENTER>
<body onload="<?php if($_SESSION["K_kensaku_MODE"] == "KENT") {?> document.form1.K_TeacherID.focus(); <?php }else{ ?> document.form1.K_StudentID.focus(); <?php } ?>">

<form name="form1" method="post" action="H01_Kensaku.php">
	<div id="header0" class="item">
		<BR>
		<table border="0" width="100%"  bgcolor="<?php echo HEADER_COLOR ?>">
			<tr align="center">
				<td align="center">
					<h2>教師検索画面</h2>
				</td>
			</tr>
		</table>
	</div>
	<table border="0" width="100%">
		<tr align="Right">
			<td align="Right">
				[ログイン]　<?php echo $_SESSION["LoginTName1"] ?>
			</td>
		</tr>
		<tr align="Right">
			<td align="right">
				<input type="submit" name="modoru" style="cursor: pointer" value="戻る" />
				<input type="submit" name="logout" style="cursor: pointer" value="ログアウト" />
			</td>
		</tr>
	</table>
	<BR><BR><BR>
	<table border="0" width="100%">
		<font size="5" color="#ff0000"><?php echo $EMSG ?></font>
	</table>
	<?php if($_SESSION["K_kensaku_MODE"] == "KENT") {?>
		<table border="0">
			<tr>
				<td width="80" align="center" bgcolor="#c0c0c0">教師ID</td>
				<td width="80" align="left"><input type="text" size="10" maxlength="10" name="K_TeacherID" style="ime-mode: disabled;" value="<?php echo $_SESSION["K_TeacherID"] ?>"></td>
			</tr>
			<tr>
				<td width="80" align="center" bgcolor="#c0c0c0">教師名</td>
				<td width="300" align="left"><input type="text" size="30" maxlength="20" name="K_TeacherName" style="ime-mode: active;" value="<?php echo $_SESSION["K_TeacherName"] ?>"></td>
			</tr>
		</table>
		<BR>
		<BR>
	<?php } ?>
	<?php if($_SESSION["K_kensaku_MODE"] == "KENS") {?>
		<table border="0">
			<tr>
				<td width="80" align="center" bgcolor="#c0c0c0">生徒ID</td>
				<td width="80" align="left"><input type="text" size="10" maxlength="10" name="K_StudentID" style="ime-mode: disabled;" value="<?php echo $_SESSION["K_StudentID"] ?>"></td>
			</tr>
			<tr>
				<td width="80" align="center" bgcolor="#c0c0c0">生徒名</td>
				<td width="300" align="left"><input type="text" size="30" maxlength="20" name="K_StudentName" style="ime-mode: active;" value="<?php echo $_SESSION["K_StudentName"] ?>"></td>
			</tr>
		</table>
		<BR>
		<BR>
	<?php } ?>
	<table border="0">
		<tr>
			<td><input id="submit_button" type="submit" name="kensaku" style="cursor: pointer" value="検索" /></td>
		</tr>
	</table>

</form>
</body>
</CENTER>
</html>