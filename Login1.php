<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN"
	"http://www.w3.org/TR/html4/frameset.dtd">

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="Header.css">
	<link rel="stylesheet" type="text/css" href="main.css">
	<title>ログインページ</title>
</head>

<?php 
session_start();

	include 'const.php'; 
	include 'utility.php'; 
?>
<?php

// エラー出力する場合
ini_set('display_errors', 1);

// エラーメッセージを格納する変数を初期化
$errorMessage  = "";
$viewUserId  = "";

//$db['host'] = "mysql103.phy.lolipop.lan";  // DBサーバのurl
//$db['user'] = "LAA0677530";
//$db['pass'] = "kanri01";
//$db['dbname'] = "LAA0677530-test01";

$dt = new DateTime();
$dt->setTimeZone(new DateTimeZone('Asia/Tokyo'));
$Today = $dt->format('Y-m-d');

// ログアウト処理
//if(isset($_POST['logout'])){
// 	LogoutShori();
//	exit;
//}

//セッション初期化
$_SESSION["user_name"]="";
$_SESSION["passhoji"]=0;

//$_SESSION["passhoji"] = 0;

// ログインボタンが押されたかを判定
// 初めてのアクセスでは認証は行わずエラーメッセージは表示しないように
if (isset($_POST["submitter"])) {
	switch ($_POST["submitter"]){
		case 'ログイン':
			$viewUserId  = htmlspecialchars($_POST["user_name"], ENT_QUOTES);

			// １．ユーザIDの入力チェック
			if (empty($_POST["user_name"])) {
		    		$errorMessage = "ユーザ名が未入力です。";
		 	} else if (empty($_POST["password"])) {
		 		$errorMessage = "パスワードが未入力です。";
				$_SESSION["user_name"] = $_POST["user_name"];
			}

			if (!empty($_POST["user_name"]) && !empty($_POST["password"])) {

				// mysqlへの接続
				$mysqli = new mysqli(HOST, USER, PASS);
				if ($mysqli->connect_errno) {
					print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
					exit();
		   		}

				// データベースの選択
				$mysqli->select_db(DBNAME);

				// 入力値のサニタイズ
				$userid = $mysqli->real_escape_string($_POST["user_name"]);

				// クエリの実行
				$query = "SELECT * FROM K_LoginInfo WHERE  TeacherID = '" . $userid . "'";
				$result = $mysqli->query($query);
				if (!$result) {
					print('クエリーが失敗しました。' . $mysqli->error);
					$mysqli->close();
					exit();
				}

				while ($row = $result->fetch_assoc()) {
				$db_hashed_pwd = $row['PassWord'];
				$db_hashed_pwd2 = $row['PassWord2'];	// パスワード(暗号化済み）の取り出し
				$db_YukoKigen = $row['YukouTime'];
				$db_Shikaku = NullToZero($row['Shikaku']);
				$db_TeacherID = $row['TeacherID'];
				$db_PassFlg = $row['PassFlg'];
				}

			 	// データベースの切断
				$mysqli->close();

			 	// ３．画面から入力されたパスワードとデータベースから取得したパスワードのハッシュを比較します。
		 		if (!empty($db_hashed_pwd)) {

					if ($db_PassFlg == 1 && password_verify($_POST["password"], $db_hashed_pwd2)) {
						//有効期限チェック
						if (!empty($db_YukoKigen) && (strtotime($Today) <= strtotime($db_YukoKigen))) {

							// ４．認証成功なら、セッションIDを新規に発行する	
							session_regenerate_id(true);
							$_SESSION["user_name"] = $_POST["user_name"];
							$_SESSION["shikaku"] = $db_Shikaku;
							$_SESSION["LoginTeacherID"] = $db_TeacherID;
							if (isset($_POST["passhoji"])) {
								$_SESSION["passhoji"] = 1;
							}

							// 管理者専用画面へリダイレクト
							$login_url = "http://{$_SERVER["HTTP_HOST"]}/Menu1.php";
							header("Location:Menu1.php");		
							exit;
						} else {
							session_destroy();//セッション破棄
							$errorMessage = "パスワードの有効期限が切れています。管理者に連絡してください。";
						}

					} elseif($db_PassFlg != 1 && strcmp($_POST["password"], $db_hashed_pwd) == 0) {

						//有効期限チェック
						if (!empty($db_YukoKigen) && (strtotime($Today) <= strtotime($db_YukoKigen))) {

							// ４．認証成功なら、セッションIDを新規に発行する	
							session_regenerate_id(true);
							$_SESSION["user_name"] = $_POST["user_name"];
							$_SESSION["shikaku"] = $db_Shikaku;
							$_SESSION["LoginTeacherID"] = $db_TeacherID;
							if (isset($_POST["passhoji"])) {
								$_SESSION["passhoji"] = 1;
							}

							// 管理者専用画面へリダイレクト
							$login_url = "http://{$_SERVER["HTTP_HOST"]}/Login2.php";
							header("Location:Login2.php");		
							exit;
						} else {
							session_destroy();//セッション破棄
							$errorMessage = "パスワードの有効期限が切れています。管理者に連絡してください。";
						}

					} else {
						session_destroy();//セッション破棄
						$errorMessage = "ユーザ名もしくはパスワードが違っています。";
					}
				} else {
						session_destroy();//セッション破棄
						$errorMessage = "パスワード異なります、管理者に連絡してください。";
				}
		  	} else {
		    		// 未入力なら何もしない
		  	}

			break;

		case 'クリア':
			$_SESSION["user_name"] = "";
			break;

		case 'ログアウト':
		 	LogoutShori();
			break;

	}
}
//if (isset($_POST["clear"])) {
//	$_SESSION["user_name"] = "";
//}
?>

<script type="text/javascript" src="utility.js"></script>
<script type="text/javascript">
</script>
<CENTER>
<body onload="document.form1.user_name.focus();" onKeyPress="OnKey(event.keyCode,'login');">

<form name="form1" action="Login1.php" method="POST">
	<div id="header0" class="item">
		<BR>
		<table border="0" width="100%"  bgcolor="<?php echo HEADER_COLOR?>">
			<tr align="center">
				<td align="center">
					<h2>ログイン画面</h2>
				</td>
			</tr>
		</table>
	</div>
	<BR>
	<table border="0" width="100%">
		<tr align="center">
			<td align="right">
				<input type="button" name="logout" id="logout" value="ログアウト" onclick="sbmfnc(this)" style="cursor: pointer" />
			</td>
		</tr>
		<tr align="left">
			<td><font size="5" color="#ff0000"><?php echo $errorMessage ?></font></td>
		</tr>
	</table>
	<BR>
	<table border="0">
		<tr align="left">
			<td>ユーザ名</td>
			<td><input type="text" name="user_name" value="<?php echo $_SESSION["user_name"] ?>" /></td>
		</tr>
		<tr align="left">
			<td>パスワード</td>
			<td><input type="password" name="password" value"" /></td>
		</tr>
<!--
		<tr align="center">
			<td colspan="2"><input type="checkbox" name="passhoji" value"" <?php if($_SESSION["passhoji"] == 1) {?> checked <?php }?>/>ログイン状態を保持</td>
		</tr>
-->
		<tr align="center">
			<td colspan="2">　　　</td>
		</tr>
		<tr align="center">
			<td colspan="2">
				<input type="hidden" id="submitter" name="submitter" value="" />
				<input type="button" name="login" id="login" value="ログイン" onclick="sbmfnc(this)" style="cursor: pointer" />
				<input type="button" name="clear" id="clear" value="クリア" onclick="sbmfnc(this)" style="cursor: pointer" />
			</td>
		</tr>

	</table>
</form>
</body>
</CENTER>
</html>
