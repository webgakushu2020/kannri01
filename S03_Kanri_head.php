<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN"
	"http://www.w3.org/TR/html4/frameset.dtd">

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="Header.css">
	<title>教師管理画面</title>
</head>

<?php include 'const.php'; ?>
<?php include 'utility.php'; ?>

<?php
session_start();

ini_set( 'display_errors', 1 );
$query2 = "";
	$dt = new DateTime();
	$dt->setTimeZone(new DateTimeZone('Asia/Tokyo'));
	$Today = $dt->format('Y/m/d');
	$EMSG = "";
	
	$_SESSION["Today"]=$Today;

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
		 	ModoruShori($_SESSION["S03_Kanri_head_RPID"]);
			exit;
		}
	}

	if(isset($_POST['submitter'])){
		if($_POST['submitter']=="PRINT2"){
			if($_SESSION["CheckCnt"] >=2 ){
				$EMSG = "教師が複数選択されています。１件にしてください。";
				$_SESSION["ShoriID"]="ERR";
			}else{
				$_SESSION["ShoriID"]="PRINT2";
			}
		}
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
//		$TeacherID = $_SESSION["TeacherID"];
		$user_name = $_SESSION["user_name"];
		$shikaku = (int) $_SESSION["shikaku"];
		$TName1 = $_SESSION["TName1"];
		$TName2 = $_SESSION["TName2"];

		//URLパラメータ

		if(isset($_GET['RPID'])) {
			$_SESSION["S03_Kanri_head_RPID"] = $_GET['RPID'];
		      	//print($_SESSION["T03_kensaku_RPID"]);
		}
		if(isset($_GET['MODE'])) {
			if($_GET['MODE'] != "Modoru"){
				$_SESSION["S03_Kanri_head_MODE"] = $_GET['MODE'];
				$_SESSION["ShoriID"]=$_GET['MODE'];
			      	//print($_SESSION["K_kensaku_MODE"] . "<BR>");
			}
		}
		if(isset($_GET['KEY1'])) {
			$_SESSION["Kensaku_KEY1"] = $_GET['KEY1'];
		}
		if(isset($_GET['SEQ'])) {
			$_SESSION["Kensaku_Seq"] = $_GET['SEQ'];
		}
	
		if(isset($_GET['BTN'])) {
			$_SESSION["Kensaku_BTN"] = $_GET['BTN'];
		}

		switch ($_SESSION["ShoriID"]){
			case 'UPD':
				$BTN = $_SESSION["Kensaku_BTN"];
				$BTNS = explode("-", $_SESSION["Kensaku_BTN"]);
				$_SESSION["BTN1"] = $BTNS[0];
				$_SESSION["BTN2"] = $BTNS[1];
				$_SESSION["BTN3"] = $BTNS[2];
				$_SESSION["BTN4"] = $BTNS[3];
				$_SESSION["CHECKDATA2"] = "";
				break;
			case 'SENTEI':
//				$Location = "S03_index.php?MODE=SENTEI&RPID=S02_Kensaku&KEY1=" . $_SESSION["Kensaku_KEY1"] . "&KUBUN=1" . "&Seq=" .$_SESSION["Kensaku_Seq"];
//			 	header("Location: {$Location}");
				break;
		}
	}


?>
<script type="text/javascript">
function printshori(pdata){
/*alert("printshori　" + pdata);*/
		window.open('S03_StudentInfo2.php?MODE=VIEW&RPID=S03_index&KEY1=<?php echo $_SESSION["Kensaku_KEY1"] ?>&KUBUN=1&SEQ=<?php echo $_SESSION["Kensaku_Seq"] ?>&SELDATA=' + pdata);
}

</script>
<script type="text/javascript" src="utility.js">
</script>
<CENTER>
<body onload="<?php if($_SESSION["ShoriID"] == "PRINT2"){ ?> printshori(<?php echo $_SESSION["S03_CHECKDATA"]?>); <?php } ?>">
<form name="form1" method="post" action="S03_Kanri_head.php">
	<table border="0" width="100%">
		<font size="5" color="#ff0000"><?php echo $EMSG ?></font>
	</table>
	<table border="0" width="100%">
		<tr align="Right">
			<td align="left">
				<input type="hidden" id="submitter" name="submitter" value="" />
				<input type="button" id="SESHO" name="SESHO" onClick="top.location.href='S03_index.php?MODE=SESHO&RPID=S02_Kensaku&KEY1=<?php echo $_SESSION["Kensaku_KEY1"] ?>&KUBUN=1&SEQ=<?php echo $_SESSION["Kensaku_Seq"] ?>'" style="cursor: pointer" value="折衝情報" <?php if($_SESSION["BTN3"]==1){?>DISABLED<?php } ?>/>
				<input type="button" id="SENTEI" name="SENTEI" onClick="top.location.href='S03_index.php?MODE=SENTEI&RPID=S02_Kensaku&KEY1=<?php echo $_SESSION["Kensaku_KEY1"] ?>&KUBUN=1&SEQ=<?php echo $_SESSION["Kensaku_Seq"] ?>'" style="cursor: pointer" value="選定・契約" <?php if($_SESSION["BTN4"]==1){?>DISABLED<?php } ?><?php if($_SESSION["shikaku2"]==0){ ?>Disabled<?php } ?>/>
<!--				<input type="button" id="PRINT" name="PRINT" onClick="window.open('S03_StudentInfo.php?MODE=VIEW&RPID=S03_index&KEY1=<?php echo $_SESSION["Kensaku_KEY1"] ?>&KUBUN=1&SEQ=<?php echo $_SESSION["Kensaku_Seq"] ?>')" style="cursor: pointer" value="印刷画面"/>
-->
				<input type="button" id="PRINT2" name="PRINT2" onClick="sbmfnc(this,'')" style="cursor: pointer" value="印刷画面"/>
				<input type="hidden" name="checkdata" value="" />
			</td>
			<td align="right">
				<input type="button" id="modoru" name="modoru" onClick="top.location.href='<?php echo $_SESSION["S03_Kanri_head_RPID"]?>.php?MODE=Modoru'" style="cursor: pointer" value="戻る" />
				<input type="button" id="logout" name="logout" onClick="top.location.href='index.php'" style="cursor: pointer" value="ログアウト" />
				[ログイン]　<?php echo $_SESSION["LoginTName1"] ?>
			</td>
		</tr>
	</table>
</form>
</body>
</CENTER>
</html>