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

	// 戻る処理
	if(isset($_POST['modoru'])){
	 	ModoruShori($_SESSION["H00_Menu1_RPID"]);
		exit;
	}

	// 画面遷移
	if(isset($_POST['NEW'])){
		header("Location:H02_Select.php?MODE=NEW&RPID=H00_Menu1");
		exit;
	}
	if(isset($_POST['KENT'])){
		header("Location:H01_Kensaku.php?MODE=KENT&RPID=H00_Menu1");
		exit;
	}
	if(isset($_POST['KENS'])){
		header("Location:H01_Kensaku.php?MODE=KENS&RPID=H00_Menu1");
		exit;
	}
	if(isset($_POST['JSK'])){
		header("Location:J_JissekiIchiran.php?MODE=JKS&RPID=H00_Menu1");
		exit;
	}
	if(isset($_POST['IMF'])){
		header("Location:K_Oshirase.php?MODE=IMF&RPID=H00_Menu1");
		exit;
	}
	if(isset($_POST['UPD'])){
		header("Location:K01_Menu.php?MODE=UPD&RPID=H00_Menu1");
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

		//URLパラメータ
		if(isset($_GET['RPID'])) {
			$_SESSION["H00_Menu1_RPID"] = $_GET['RPID'];
		      	//print($_SESSION["J_JissekiIchiran_RPID"] ."<BR>");
		}

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

<form name="form1" method="post" action="H00_Menu1.php">
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
	<div id="header" class="item">
		<table border="0" width="100%">
			<td align="right">
				<?php if($_SESSION["shikaku"] == 1){?>
					<input type="submit" name="modoru" style="cursor: pointer" value="戻る" />
				<?php } ?>
				<input type="submit" name="logout" style="cursor: pointer" value="ログアウト" />
			</td>
			<tr align="left">
				<td>
					<table border="1" bgcolor="<?php echo TEACHR_COLOR ?>">
						<tr>
							<td width="100" align="center" height="40"><?php echo $TeacherID ?></td>
							<td width="200" align="center" height="40"><?php echo $TName1 ?>（<?php echo $TName2 ?>）</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>
	<BR>	
	<BR>	
	<table border="3">
		<tr align="center">
			<td width="500" align="center"><p>おしらせ</p></td>
		</tr>
		<tr align="center">
			<td width="500" align="left">
			<p>
				<?php echo $Info1 ?><BR>
				<?php echo $Info2 ?><BR>
				<?php echo $Info3 ?><BR>
				<?php echo $Info4 ?><BR>
				<?php echo $Info5 ?><BR>
			</p>
			</td>
		</tr>
	</table>
	<BR>
	<?php if ($shikaku == 0){ ?>
		<table border="0" width="100%">
			<tr align="center">
				<input type="submit" name="NEW" style="cursor: pointer" value="報告書登録" />
			</tr>
		</table>
	<?php }else{ ?>
		<table border="0" width="100%">
			<tr align="center">
				<td>
					<input id="submit_button" type="submit" name="NEW" style="cursor: pointer" value="報告書登録" />
					<input id="submit_button" type="submit" name="KENT" style="cursor: pointer" value="教師別報告書検索" />
					<input id="submit_button" type="submit" name="KENS" style="cursor: pointer" value="生徒別報告書検索" />
				</td>
			</tr>
<!--			<tr align="center">
				<td>
					<input id="submit_button" type="submit" name="JSK" style="cursor: pointer" value="実績一覧照会" />
					<input id="submit_button" type="submit" name="UPD" style="cursor: pointer" value="教師・生徒情報修正" />
					<input id="submit_button" type="submit" name="IMF" style="cursor: pointer" value="お知らせ修正" />
				</td>
			</tr>
-->
		</table>
	<?php } ?>
</form>
</body>
</CENTER>
</html>
