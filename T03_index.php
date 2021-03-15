<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN"
	"http://www.w3.org/TR/html4/frameset.dtd">

<?php
session_start();

	if(isset($_GET['RPID'])) {
		$_SESSION["T03_RPID"] = $_GET['RPID'];
	}
?>
<HTML>
<HEAD>
	<TITLE>教師情報管理画面</TITLE>
</HEAD>
<FRAMESET rows="10%,90%">
		<FRAME src="T03_Kanri_head.php?MODE=UPD&RPID=<?php echo $_SESSION["T03_RPID"]?>&KEY1=<?php echo $_SESSION["T03_TeacherID"]?>&KUBUN=0">
<FRAMESET cols="40%,60%">
<FRAMESET rows="50%,50%">
		<FRAME src="T03_Kanri01.php?MODE=UPD&RPID=<?php echo $_SESSION["T03_RPID"]?>&KEY1=<?php echo $_SESSION["T03_TeacherID"]?>&KUBUN=0">
		<FRAME src="T03_Kanri02.php?MODE=UPD&RPID=<?php echo $_SESSION["T03_RPID"]?>&KEY1=<?php echo $_SESSION["T03_TeacherID"]?>&KUBUN=0">
</FRAMESET>
		<FRAME src="T03_Kanri03.php?MODE=UPD&RPID=<?php echo $_SESSION["T03_RPID"]?>&KEY1=<?php echo $_SESSION["T03_TeacherID"]?>&KUBUN=0">
</FRAMESET>

<NOFRAMES>
	<BODY>
		<P>このページを表示するには、フレームをサポートしているブラウザが必要です。</P>
	</BODY>
</NOFRAMES>
</FRAMESET>
</HTML>
