<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN"
	"http://www.w3.org/TR/html4/frameset.dtd">

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="Header.css">
	<link rel="stylesheet" type="text/css" href="main.css">
	<title>メニュー画面</title>
</head>

<?php include 'const.php'; ?>
<?php include 'utility.php'; ?>

<?php
session_start();

	// ログアウト処理
	if(isset($_POST['logout'])){
		if($_SESSION["passhoji"] == 1){
		 	LogoutShori2();
		}else{
		 	LogoutShori();
		}
		exit;
	}
	// 画面遷移
	if(isset($_POST['K_Teacher'])){
		header("Location:T01_Kensaku.php?MODE=NEW&RPID=K01_index");
		exit;
	}
	if(isset($_POST['K_Student'])){
		header("Location:S01_Kensaku.php?MODE=NEW&RPID=K01_index");
		exit;
	}
	if(isset($_POST['K_Houkoku'])){
		header("Location:H00_Menu1.php?MODE=NEW&RPID=K01_index");
		exit;
	}
	if(isset($_POST['K_Jisseki'])){
		header("Location:J00_JissekiIchiran.php?MODE=JKS&RPID=K01_index");
		exit;
	}
	if(isset($_POST['K_Data'])){
		header("Location:K01_DataIko.php?MODE=UPD&RPID=K01_index");
		exit;
	}
	if(isset($_POST['K_System'])){
		header("Location:K01_LoginInfo.php?MODE=UPD&RPID=K01_index");
		exit;
	}
	if(isset($_POST['UPD'])){
		header("Location:K01_Menu.php?MODE=UPD&RPID=K01_index");
		exit;
	}
	if(isset($_POST['K_Keika'])){
		header("Location:K03_KeikaKanriIchiran.php?MODE=UPD&RPID=K01_index");
		exit;
	}
	if(isset($_POST['K_Code'])){
		header("Location:K01_CodeKanri.php?MODE=UPD&RPID=K01_index");
		exit;
	}


	// ログイン済みかどうかの変数チェックを行う
	if (!isset($_SESSION["user_name"])) {

		// 変数に値がセットされていない場合は不正な処理と判断し、ログイン画面へリダイレクトさせる
		$no_login_url = "http://{$_SERVER["HTTP_HOST"]}/Login1.php";
		header("Location: {$no_login_url}");
		exit;
	} else {
//		print('ログイン成功');
//		print('ユーザID'.$_SESSION["TeacherID"]);
//		print('ユーザ名'.$_SESSION["user_name"]);
//		print('資格'.$_SESSION["shikaku"]);

		//セッション情報保存
		$TeacherID = $_SESSION["LoginTeacherID"];
		$user_name = $_SESSION["user_name"];
		$shikaku = (int) $_SESSION["shikaku"];

		//教師宛名取得
		list ($TName1, $TName2) = GetTAtena($TeacherID);
		$_SESSION["TName1"] = $TName1;
		$_SESSION["TName2"] = $TName2;
		$_SESSION["LoginTName1"] = $TName1;
		$_SESSION["LoginTName2"] = $TName2;

		//お知らせ情報取得
		list ($Info1, $Info2, $Info3, $Info4, $Info5) = GetInfo();
		

	}

?>
<CENTER>
<body onload="document.form1.NEW.focus();">

<form name="form1" method="post" action="K01_Menu.php" target="_top">
	<div id="header0" class="item">
		<BR>
		<table border="0" width="100%"  bgcolor="<?php echo HEADER_COLOR ?>">
			<tr align="center">
				<td align="center">
					<h2>メニュー画面</h2>
				</td>
			</tr>
		</table>
	</div>
	<table border="0" width="100%">
		<tr align="center">
			<td align="right"><p><button type="submit" name="logout" style="cursor: pointer">ログアウト</button></p></td>
		</tr>
	</table>
	<BR>	
	<table border="0" width="50%">
		<tr align="center">
			<td>
				<input id="submit_button" type="submit" name="K_Teacher" style="cursor: pointer" value="教師管理" />
			</td>
			<td>
				<input id="submit_button" type="submit" name="K_Houkoku" style="cursor: pointer" value="報告管理" />
			</td>
		</tr>
		<tr align="center">
			<td>
				<input id="submit_button" type="submit" name="K_Student" style="cursor: pointer" value="生徒管理" />
			</td>
			<td>
				<input id="submit_button" type="submit" name="K_Jisseki" style="cursor: pointer" value="実績管理" <?php if($_SESSION["shikaku2"]==0){ ?>Disabled<?php } ?>/>
			</td>
		</tr>
		<tr align="center">
			<td>
				<input id="submit_button" type="submit" name="K_Data" style="cursor: pointer" value="データ移行" <?php if($_SESSION["shikaku2"]==0){ ?>Disabled<?php } ?> />
			</td>
			<td>
				<input id="submit_button" type="submit" name="K_System" style="cursor: pointer" value="ログイン管理" <?php if($_SESSION["shikaku2"]==0){ ?>Disabled<?php } ?> />
			</td>
		</tr>
		<tr align="center">
			<td>
				<input id="submit_button" type="submit" name="K_Keika" style="cursor: pointer" value="経過管理" <?php if($_SESSION["shikaku2"]==0){ ?>Disabled<?php } ?> />
			</td>
			<td>
				<input id="submit_button" type="submit" name="K_Code" style="cursor: pointer" value="コード管理" <?php if($_SESSION["shikaku2"]==0){ ?>Disabled<?php } ?> />
			</td>
		</tr>
	</table>
</form>
</body>
</CENTER>
</html>
