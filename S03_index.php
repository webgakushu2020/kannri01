<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN"
	"http://www.w3.org/TR/html4/frameset.dtd">

<?php
session_start();

	if(isset($_GET['KEY1'])) {
		$_SESSION["S03_StudentID"] = $_GET['KEY1'];
	}
	if(isset($_GET['SEQ'])) {
		$_SESSION["S03_Seq"] = $_GET['SEQ'];
	}

	if(isset($_GET['MODE'])) {
		$_SESSION["S03_MODE"] = $_GET['MODE'];
	}
	if(isset($_GET['RPID'])) {
		$_SESSION["S03_RPID"] = $_GET['RPID'];
	}

?>
<HTML>
<HEAD>
	<TITLE>生徒情報管理画面</TITLE>
</HEAD>

<?php if($_SESSION["S03_MODE"]=="UPD" || $_SESSION["S03_MODE"]=="Modoru"){?>
	<FRAMESET rows="10%,90%">
			<FRAME src="S03_Kanri_head.php?MODE=UPD&RPID=<?php echo $_SESSION["S03_RPID"]?>&KEY1=<?php echo $_SESSION["S03_StudentID"]?>&SEQ=<?php echo $_SESSION["S03_Seq"]?>&KUBUN=1&BTN=1-1-1-0" name="S03_Kanri_head">
	<FRAMESET cols="40%,60%">
	<FRAMESET rows="50%,50%">
			<FRAME src="S03_Kanri01.php?MODE=UPD&RPID=<?php echo $_SESSION["S03_RPID"]?>&KEY1=<?php echo $_SESSION["S03_StudentID"]?>&SEQ=<?php echo $_SESSION["S03_Seq"]?>&KUBUN=1" name="S03_Kanri01">
			<FRAME src="S03_Kanri02.php?MODE=UPD&RPID=<?php echo $_SESSION["S03_RPID"]?>&KEY1=<?php echo $_SESSION["S03_StudentID"]?>&SEQ=<?php echo $_SESSION["S03_Seq"]?>&KUBUN=1" name="S03_Kanri02">
	</FRAMESET>
			<FRAME src="T03_Kanri03.php?MODE=UPD&RPID=<?php echo $_SESSION["S03_RPID"]?>&KEY1=<?php echo $_SESSION["S03_StudentID"]?>&SEQ=<?php echo $_SESSION["S03_Seq"]?>&KUBUN=1" name="T03_Kanri03">
	</FRAMESET>
<?php }if($_SESSION["S03_MODE"]=="SENTEI"){?>
	<FRAMESET rows="10%,90%">
			<FRAME src="S03_Kanri_head.php?MODE=UPD&RPID=S02_Kensaku&KEY1=<?php echo $_SESSION["S03_StudentID"]?>&SEQ=<?php echo $_SESSION["S03_Seq"]?>&KUBUN=1&BTN=1-1-0-1" name="S03_Kanri_head">
	<FRAMESET cols="40%,60%">
	<FRAMESET rows="50%,50%">
			<FRAME src="S03_Kanri01.php?MODE=UPD&RPID=S02_Kensaku&KEY1=<?php echo $_SESSION["S03_StudentID"]?>&SEQ=<?php echo $_SESSION["S03_Seq"]?>&KUBUN=1" name="S03_Kanri01">
			<FRAME src="S03_Kanri02.php?MODE=UPD&RPID=S02_Kensaku&KEY1=<?php echo $_SESSION["S03_StudentID"]?>&SEQ=<?php echo $_SESSION["S03_Seq"]?>&KUBUN=1" name="S03_Kanri02">
	</FRAMESET>
			<FRAME src="S03_Sentei03.php?MODE=UPD&RPID=S02_Kensaku&KEY1=<?php echo $_SESSION["S03_StudentID"]?>&SEQ=<?php echo $_SESSION["S03_Seq"]?>&KUBUN=1" name="S03_Sentei03">
	</FRAMESET>
<?php }if($_SESSION["S03_MODE"]=="SESHO"){?>
	<FRAMESET rows="10%,90%">
			<FRAME src="S03_Kanri_head.php?MODE=UPD&RPID=S02_Kensaku&KEY1=<?php echo $_SESSION["S03_StudentID"]?>&SEQ=<?php echo $_SESSION["S03_Seq"]?>&KUBUN=1&BTN=1-1-1-0" name="S03_Kanri_head">
	<FRAMESET cols="40%,60%">
	<FRAMESET rows="50%,50%">
			<FRAME src="S03_Kanri01.php?MODE=UPD&RPID=S02_Kensaku&KEY1=<?php echo $_SESSION["S03_StudentID"]?>&SEQ=<?php echo $_SESSION["S03_Seq"]?>&KUBUN=1" name="S03_Kanri01">
			<FRAME src="S03_Kanri02.php?MODE=UPD&RPID=S02_Kensaku&KEY1=<?php echo $_SESSION["S03_StudentID"]?>&SEQ=<?php echo $_SESSION["S03_Seq"]?>&KUBUN=1" name="S03_Kanri02">
	</FRAMESET>
			<FRAME src="T03_Kanri03.php?MODE=UPD&RPID=S02_Kensaku&KEY1=<?php echo $_SESSION["S03_StudentID"]?>&SEQ=<?php echo $_SESSION["S03_Seq"]?>&KUBUN=1" name="T03_Kanri03">
	</FRAMESET>
<?php }if($_SESSION["S03_MODE"]=="SEL"){?>
	<FRAMESET rows="10%,90%">
			<FRAME src="S03_Kanri_head.php?MODE=UPD&RPID=S02_Kensaku&KEY1=<?php echo $_SESSION["S03_StudentID"]?>&SEQ=<?php echo $_SESSION["S03_Seq"]?>&KUBUN=1&BTN=1-1-0-1" name="S03_Kanri_head">
	<FRAMESET cols="40%,60%">
	<FRAMESET rows="50%,50%">
			<FRAME src="S03_Kanri01.php?MODE=UPD&RPID=S02_Kensaku&KEY1=<?php echo $_SESSION["S03_StudentID"]?>&SEQ=<?php echo $_SESSION["S03_Seq"]?>&KUBUN=1" name="S03_Kanri01">
			<FRAME src="S03_Kanri02.php?MODE=UPD&RPID=S02_Kensaku&KEY1=<?php echo $_SESSION["S03_StudentID"]?>&SEQ=<?php echo $_SESSION["S03_Seq"]?>&KUBUN=1" name="S03_Kanri02">
	</FRAMESET>
			<FRAME src="S03_Sentei03.php?MODE=UPD&RPID=S02_Kensaku&KEY1=<?php echo $_SESSION["S03_StudentID"]?>&SEQ=<?php echo $_SESSION["S03_Seq"]?>&KUBUN=1&SEL=1" name="S03_Sentei03">
	</FRAMESET>

<?php } ?>
<NOFRAMES>
	<BODY>
		<P>このページを表示するには、フレームをサポートしているブラウザが必要です。</P>
	</BODY>
</NOFRAMES>
</FRAMESET>
</HTML>
