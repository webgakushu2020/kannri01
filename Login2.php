<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN"
	"http://www.w3.org/TR/html4/frameset.dtd">

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="Header.css">
	<link rel="stylesheet" type="text/css" href="main.css">
	<title>パスワード登録</title>
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

$dt = new DateTime();
$dt->setTimeZone(new DateTimeZone('Asia/Tokyo'));
$Today = $dt->format('Y-m-d');
$PassOK=0;
$_SESSION["pass"] = "";

// ログアウト処理
if(isset($_POST['logout'])){
 	LogoutShori();
	exit;
}

// メニュー画面へ
if(isset($_POST['Manu'])){
	header("Location:H00_Menu1.php?MODE=NEW&RPID=K01_index");		
	exit;
}

//$_SESSION["passhoji"] = 0;

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


// ログインボタンが押されたかを判定
// 初めてのアクセスでは認証は行わずエラーメッセージは表示しないように

if (isset($_POST["login"])) {

	if(empty($_POST["password0"])){
		$errorMessage = "仮パスワードを入力してください。";
	}

	if($errorMessage == ""){
		$_SESSION["pass"] = $_POST["password0"];

		// mysqlへの接続
		$mysqli = new mysqli(HOST, USER, PASS);
		if ($mysqli->connect_errno) {
			print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
			exit();
	   		}

		// データベースの選択
		$mysqli->select_db(DBNAME);

		// クエリの実行
		$query = "SELECT Count(*) as cnt FROM K_LoginInfo WHERE  TeacherID = '" . $TeacherID . "' AND PassWord=" . $_SESSION["pass"];
//print($query);
		$result = $mysqli->query($query);
		if (!$result) {
			print('クエリーが失敗しました。' . $mysqli->error);
			$mysqli->close();
			exit();
		}

		while ($row = $result->fetch_assoc()) {
			// パスワード(暗号化済み）の取り出し
			$db_Count = $row['cnt'];
		}

		if($db_Count == 0){
			$errorMessage = "仮パスワードが違っています。";
		} else {

			if(empty($_POST["password2"])){
				$errorMessage = "新パスワードを入力してください。";
			}
			if($errorMessage == "" && empty($_POST["password3"])){
				$errorMessage = "新パスワード（確認）を入力してください。";
			}

			if($errorMessage == ""){

				$raw_passwd = $_POST["password2"];
				$raw_passwd2 = $_POST["password3"];

				list($less_flg, $weak_flg, $strong_flg) = checkPassword($raw_passwd);
				if ($less_flg) {
					$errorMessage = '８文字以上入力してください。';
				} elseif ($weak_flg) {
					$strength = '弱い';
				} elseif ($strong_flg) {
					$strength = '強い';
				} else {
					$strength = '普通';
				}

				if($errorMessage == ""){

					if($_POST["password2"] == $_POST["password3"]){

						$hashed_passwd = password_hash($raw_passwd,PASSWORD_DEFAULT);

						$query2 = "UPDATE K_LoginInfo SET PassWord2='" . $hashed_passwd . "',PassFlg=1";
						$query2 = $query2 . " WHERE TeacherID = '" . $TeacherID . "' AND PassWord='" . $_POST["password0"] ."'";
				//print($query2);
						$result = $mysqli->query($query2);

						if (!$result) {
							print('クエリーが失敗しました。' . $mysqli->error);
							$mysqli->close();
							exit();
						}

						$PassOK=1;
					}else{
						$errorMessage = "パスワードが一致しません。";
					}
				}
			}
		}
	 	// データベースの切断
		$mysqli->close();
	}
	if($errorMessage == ""){
		$_SESSION["pass"] = $_POST["password0"];

		// mysqlへの接続
		$mysqli = new mysqli(HOST, USER, PASS);
		if ($mysqli->connect_errno) {
			print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
			exit();
	   		}

		// データベースの選択
		$mysqli->select_db(DBNAME2);

		// クエリの実行
		$query = "SELECT Count(*) as cnt FROM K_LoginInfo WHERE  TeacherID = '" . $TeacherID . "' AND PassWord=" . $_SESSION["pass"];
//print($query);
		$result = $mysqli->query($query);
		if (!$result) {
			print('クエリーが失敗しました。' . $mysqli->error);
			$mysqli->close();
			exit();
		}

		while ($row = $result->fetch_assoc()) {
			// パスワード(暗号化済み）の取り出し
			$db_Count = $row['cnt'];
		}

		if($db_Count == 0){
			$errorMessage = "仮パスワードが違っています。";
		} else {

			if(empty($_POST["password2"])){
				$errorMessage = "新パスワードを入力してください。";
			}
			if($errorMessage == "" && empty($_POST["password3"])){
				$errorMessage = "新パスワード（確認）を入力してください。";
			}

			if($errorMessage == ""){

				$raw_passwd = $_POST["password2"];
				$raw_passwd2 = $_POST["password3"];

				list($less_flg, $weak_flg, $strong_flg) = checkPassword($raw_passwd);
				if ($less_flg) {
					$errorMessage = '８文字以上入力してください。';
				} elseif ($weak_flg) {
					$strength = '弱い';
				} elseif ($strong_flg) {
					$strength = '強い';
				} else {
					$strength = '普通';
				}

				if($errorMessage == ""){

					if($_POST["password2"] == $_POST["password3"]){

						$hashed_passwd = password_hash($raw_passwd,PASSWORD_DEFAULT);

						$query2 = "UPDATE K_LoginInfo SET PassWord2='" . $hashed_passwd . "',PassFlg=1";
						$query2 = $query2 . " WHERE TeacherID = '" . $TeacherID . "' AND PassWord='" . $_POST["password0"] ."'";
				//print($query2);
						$result = $mysqli->query($query2);

						if (!$result) {
							print('クエリーが失敗しました。' . $mysqli->error);
							$mysqli->close();
							exit();
						}

						$PassOK=1;
					}else{
						$errorMessage = "パスワードが一致しません。";
					}
				}
			}
		}
	 	// データベースの切断
		$mysqli->close();
	}
}


function checkPassword($password)
{
    // LESS 短いパスワード
    $less_flg = FALSE;
    $length = strlen($password);
    if ($length < 8) {
        $less_flg = TRUE;
    }

    // WARK 弱いパスワード
    $weak_flg = FALSE;
    if (! $less_flg) {
         // 連続したパターン  11111111 12121212 123123123
        if (preg_match('/^([0-9a-z]{1,3})\1+$/i', $password)) {
            $weak_flg = TRUE;
        } elseif (preg_match('/^[0-9]+$/', $password)) {
            $num_flg = TRUE;

            // 連続した数字 12345678 98765432
            for ($i = 0; $i < $length; $i++) {
                $num[$i] = substr($password, $i, 1);
                if ($i > 0) {
                    $diff[$i] = $num[$i] - $num[$i - 1];
                    if ($i > 1) {
                        if ($diff[$i] != $diff[$i - 1]) {
                            $num_flg = FALSE;
                            break;
                        }
                    }
                }
            }
            if ($num_flg) {
                $weak_flg = TRUE;
            }
        } elseif (preg_match('/^[a-z]+$/i', $password)) {
            $alpha_flg = TRUE;

            // 連続したアルファベット abcdefgh zxywvuts
            for ($i = 0; $i < $length; $i++) {
                $dec[$i] = hexdec(substr($password, $i, 1));
                if ($i > 0) {
                    $diff[$i] = $dec[$i] - $dec[$i - 1];
                    if ($i > 1) {
                        if ($diff[$i] != $diff[$i - 1]) {
                            $alpha_flg = FALSE;
                            break;
                        }
                    }
                }
            }
            if ($alpha_flg) {
                $weak_flg = TRUE;
            }
        }
        if (! $weak_flg) {
            // 指定のキーワードにマッチ
            $ng_password = array('password', 'qwertyui');
            foreach ($ng_password as $ngPassVal) {
                if ($password == $ngPassVal) {
                    $weak_flg = TRUE;
                    break;
                }
            }
        }
    }

    // STRONG 強いパスワード
    $strong_flg = FALSE;
    if (! $less_flg && ! $weak_flg) {
        // 英数混在 10文字以上 a1b2c3d4e5 abcde12345
        if (preg_match('/^(?=.*[0-9])(?=.*[a-z])[0-9a-z]{10,}$/i', $password)) {
            $strong_flg = TRUE;
        }
    }
    
    return array($less_flg, $weak_flg, $strong_flg);
}



?>
<CENTER>

<script type="text/javascript">
	function OnKey(code){
		if(code == 13){
			var clickMe = document.getElementById("login");
			document.form1.login.click();
		}
	}
</script>

<body onload="document.form1.user_name.focus();">

<form name="form1" action="Login2.php" method="POST">
	<?php if($PassOK == 0){ ?>
		<div id="header0" class="item">
			<BR>
			<table border="0" width="100%"  bgcolor="<?php echo HEADER_COLOR?>">
				<tr align="center">
					<td align="center">
						<h2>パスワード登録</h2>
					</td>
				</tr>
			</table>
		</div>
		<BR>
		<table border="0" width="100%">
			<tr align="center">
				<td align="right"><p><button type="submit" name="logout" style="cursor: pointer">ログアウト</button></p></td>
			</tr>
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
			<tr align="left">
				<td><font size="5" color="#ff0000"><?php echo $errorMessage ?></font></td>

			</tr>
		</table>
		<BR>
		<table border="0">
			<tr align="left">
				<td>仮パスワード</td>
				<td><input type="password" name="password0" value"<?php echo $_SESSION["pass"] ?>" /></td>
			</tr>
			<tr align="left">
				<td>新パスワード</td>
				<td><input type="password" name="password2" value"" /></td>
			</tr>
			<tr align="left">
				<td>新パスワード（確認）</td>
				<td><input type="password" name="password3" value"" /></td>
			</tr>
			<tr align="center">
				<td colspan="2">※半角英数字８文字以上を入力してください。</td>
			</tr>
			<tr align="center">
				<td colspan="2">　　</td>
			</tr>
			<tr align="center">
				<td colspan="2"><input id="submit_button" type="submit" name="login" style="cursor: pointer" value="登録" />
			</tr>

		</table>
	<?php }else{ ?>
		<div id="header0" class="item">
			<BR>
			<table border="0" width="100%"  bgcolor="<?php echo HEADER_COLOR?>">
				<tr align="center">
					<td align="center">
						<h2>パスワード登録</h2>
					</td>
				</tr>
			</table>
		</div>
		<BR>
		<table border="0" width="100%">
			<tr align="center">
				<td align="right"><p><button type="submit" name="logout" style="cursor: pointer">ログアウト</button></p></td>
			</tr>
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
			<tr align="center">
				<td><font size="5" color="#ff0000">パスワードの登録が完了しました。<BR>メニューボタンを押して進んでください。</font></td>
			</tr>
		</table>
		<BR>
		<table border="0">
			<tr align="center">
				<td colspan="2"><input id="submit_button" type="submit" name="Manu" style="cursor: pointer" value="メニューへ" />
			</tr>
		</table>
	<?php } ?>
</form>
</body>
</CENTER>
</html>
