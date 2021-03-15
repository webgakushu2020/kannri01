<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN"
	"http://www.w3.org/TR/html4/frameset.dtd">

<?php
session_start();
	// ログイン済みかどうかの変数チェックを行う
	if (!isset($_SESSION["user_name"])) {

		// 変数に値がセットされていない場合は不正な処理と判断し、ログイン画面へリダイレクトさせる
		$no_login_url = "http://{$_SERVER["HTTP_HOST"]}/Login1.php";
		header("Location: {$no_login_url}");
		exit;
	} else {
	}
?>
<HTML>
<HEAD>
	<TITLE>メニュー画面</TITLE>
</HEAD>
<FRAMESET rows="30%,70%">
		<FRAME src="K01_Menu.php">
		<FRAME src="T03_Kanri03.php?MODE=ALL&RPID=K01_Menu&KEY1=&KUBUN=2">
</FRAMESET>

<NOFRAMES>
	<BODY>
		<P>このページを表示するには、フレームをサポートしているブラウザが必要です。</P>
	</BODY>
</NOFRAMES>
</FRAMESET>
</HTML>
