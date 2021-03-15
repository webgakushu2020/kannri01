<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN"
	"http://www.w3.org/TR/html4/frameset.dtd">

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="Header.css">
	<title>生徒選択画面</title>
</head>

<?php include 'const.php'; ?>
<?php include 'utility.php'; ?>

<?php
session_start();

//	print('ユーザID'.$_SESSION["TeacherID"]);
//	print('ユーザ名'.$_SESSION["user_name"]);
//	print('資格'.$_SESSION["shikaku"]);

	$dt = new DateTime();
	$dt->setTimeZone(new DateTimeZone('Asia/Tokyo'));
	$Today = $dt->format('Y/m/d');

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
	 	ModoruShori($_SESSION["H02_Select_RPID"]);
		exit;
	}

	//終了分表示
	if(isset($_POST['ALLDate'])){
		$_SESSION["DateFlg"] = 1;
	}
	if(isset($_POST['EndDate'])){
		$_SESSION["DateFlg"] = 0;
	}

	// 生徒選択処理
	for ($m = 0; $m < $_SESSION["DateCount"]; $m++){
		if(isset($_POST["No_" . $m])){
			$Location = "H03_Report1.php?MODE=UPDT&RPID=H02_Select&STID=" . $_POST['StudentID_' . $m] . "&ATENASEQ=" . $_POST['StudentAtenaSeq_' . $m] . "&SEQ=" . $_POST['KeiyakuSeq_' . $m];
		 	header("Location:" . $Location);
			exit;
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
			$_SESSION["H02_Select_RPID"] = $_GET['RPID'];
		      	//print($_SESSION["H02_Select_RPID"]);
		}
		if(isset($_GET['MODE'])) {
			$_SESSION["H02_Select_MODE"] = $_GET['MODE'];
		      	//print($_SESSION["H02_Select_MODE"] . "<BR>");

			if($_SESSION["H02_Select_MODE"] == "NEW"){
				$_SESSION["DateFlg"] = 0;
				$_SESSION["TeacherID"] = $_SESSION["LoginTeacherID"];
				$_SESSION["TName1"] = $_SESSION["LoginTName1"];
				$_SESSION["TName2"] = $_SESSION["LoginTName2"];
			}
			//検索(KENT)の場合は検索結果のＩＤで索引する。
			if($_SESSION["H02_Select_MODE"] == "KENT"){
				$_SESSION["TeacherID"] = $_GET['TID'];
				list ($TName1, $TName2) = GetTAtena($_SESSION["TeacherID"]);
				$_SESSION["TName1"] = $TName1;
				$_SESSION["TName2"] = $TName2;
			}
		}
		if(isset($_GET['KEY1'])) {
			$_SESSION["H02_Select_KEY1"] = $_GET['KEY1'];
		      	//print($_SESSION["H02_Select_KEY1"]);
		}

		//セッション情報保存
		//前画面からの情報
		$TeacherID = $_SESSION["TeacherID"];
		$user_name = $_SESSION["user_name"];
		$shikaku = (int) $_SESSION["shikaku"];
		$TName1 = $_SESSION["TName1"];
		$TName2 = $_SESSION["TName2"];

		//生徒情報取得
		//GetStudent();
		
		// mysqlへの接続
		$mysqli = new mysqli(HOST, USER, PASS);
		if ($mysqli->connect_errno) {
			print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
			exit();
		   		}

		// データベースの選択
		$mysqli->select_db(DBNAME);
		$mysqli->set_charset("utf8");

		// 入力値のサニタイズ
		$userid = $mysqli->real_escape_string($TeacherID);

		// クエリの実行
		$query = "SELECT * FROM T_TantoShosai WHERE  TeacherID = '" . $userid . "' ORDER BY StudentID ASC,StartDay DESC ";
		$result = $mysqli->query($query);

		//print($query ."<BR>");

		if (!$result) {
			print('クエリーが失敗しました。' . $mysqli->error);
			$mysqli->close();
			exit();
		}
		

		$data = array();
		$i = 0;
		while($arr_item = $result->fetch_assoc()){

			//レコード内の各フィールド名と値を順次参照
			foreach($arr_item as $key => $value){
				//フィールド名と値を表示
				$data[$i][$key] = $value;
			}

			$StudentID = $data[$i]['StudentID'];
			$AtenaSeq = $data[$i]['AtenaSeq'];
			$Seq = $data[$i]['Seq'];
			$db_Name1 = "";
			$db_Name2 = "";

			//------生徒名取得------
			$query2 = "SELECT * FROM S_AtenaInfo WHERE  StudentID = '" . $StudentID . "' AND Seq = " . $AtenaSeq ;
			$result2 = $mysqli->query($query2);

			//print($query2 ."<BR>");

			if (!$result2) {
				print('クエリーが失敗しました。' . $mysqli->error);
				$mysqli->close();
				exit();
			}

			while ($row = $result2->fetch_assoc()) {
				$db_Name1 = $row['Name1'];
				$db_Name2 = $row['Name2'];
			}

			$data[$i]['StudentName'] = $db_Name1 . "　" . $db_Name2;

			//------終了分判定------
			if(is_null($data[$i]['EndDay'])){
				$data[$i]['HyojiFlg'] = 1;
			}else{
				if ((strtotime($data[$i]['StartDay']) <= strtotime($Today)) && (strtotime($Today) <= strtotime($data[$i]['EndDay']))) {
					$data[$i]['HyojiFlg'] = 1;
				} else {
					$data[$i]['HyojiFlg'] = 0;
				}
			}

			$i++;
		}
		$_SESSION["DateCount"] = count($data);	//データ件数

	 	// データベースの切断
		$mysqli->close();		

	}

?>
<CENTER>
<form name="form1" method="post" action="H02_Select.php">
	<div id="header0" class="item">
		<BR>
		<table border="0" width="100%"  bgcolor="<?php echo HEADER_COLOR ?>">
			<tr align="center">
				<td align="center">
					<h2>生徒選択画面</h2>
				</td>
			</tr>
		</table>
	</div>
	<div id="header" class="item">
		<table border="0" width="100%">
			<tr align="Right">
				<td align="Right">
					[ログイン]　<?php echo $_SESSION["LoginTName1"] ?>
				</td>
			</tr>
			<tr align="center">
				<td align="right">				
					<input type="submit" name="modoru" style="cursor: pointer" value="戻る" />
					<input type="submit" name="logout" style="cursor: pointer" value="ログアウト" />
				</td>
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
		</table>
	</div>
	<BR><BR>
	<table border="0" width="100%">
		<tr align="center">
			<td align="center">
				<input type="submit" <?php if ($_SESSION["DateFlg"] == 0) {?> disabled <?php } ?> name="EndDate" style="cursor: pointer" value="終了分非表示" />
				<input type="submit" <?php if ($_SESSION["DateFlg"] == 1) {?> disabled <?php } ?> name="ALLDate" style="cursor: pointer" value="全データ表示" />
			</td>
		</tr>
	</table>
	<table border="1">
		<tr>
			<td width="30" align="center" bgcolor="#c0c0c0">ＮＯ</td>
			<td width="100" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">生徒ＩＤ</td>
			<td width="250" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">生徒名</td>
			<td width="50" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">枝番</td>
			<td width="150" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">開始日</td>
			<td width="150" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">終了日</td>
			<td width="100" align="center" bgcolor="<?php echo KITEI_COLOR ?>">契約枝番</td>
			<?php if($shikaku == 1){ ?>
				<td width="100" align="center" bgcolor="<?php echo KITEI_COLOR ?>">時給</td>
				<td width="50" align="center" bgcolor="<?php echo KITEI_COLOR ?>">回数</td>
				<td width="50" align="center" bgcolor="<?php echo KITEI_COLOR ?>">時間</td>
			<?php } ?>
		</tr>
		<?php $no=1;?>
		<?php $no2=1;?>
		<?php for ($i = 0; $i< count($data); $i++) { ?>

			<?php if ($_SESSION["DateFlg"] == 0 && $data[$i]['HyojiFlg'] == 1) { ?>
				<tr>
					<td width="30" align="center"><input type="submit" name="No_<?php echo $i ?>" style="cursor: pointer" value="<?php echo $no ?>" /></td>
					<td width="100" align="center"><?php echo $data[$i]['StudentID'] ?></td>
					<td width="250" align="center"><?php echo $data[$i]['StudentName'] ?></td>
					<td width="50" align="center"><?php echo $data[$i]['AtenaSeq'] ?></td>
					<td width="150" align="center"><?php if(is_null($data[$i]['StartDay'])){ }else{ echo date('Y年n月j日', strtotime($data[$i]['StartDay'])); }?></td>
					<td width="150" align="center"><?php if(is_null($data[$i]['EndDay'])){ }else{echo date('Y年n月j日', strtotime($data[$i]['EndDay'])); }?></td>
					<td width="50" align="center"><?php echo $data[$i]['Seq'] ?></td>
					<?php if($shikaku == 1){ ?>
						<td width="100" align="center"><?php echo number_format($data[$i]['Pay']); ?></td>
						<td width="100" align="center"><?php echo $data[$i]['KiteiKaisu'] ?></td>
						<td width="100" align="center"><?php echo $data[$i]['KiteiJikan'] ?></td>
					<?php } ?>
					<input type="hidden" name="postshori" value="">
					<input type="hidden" name="valuename_<?php echo $i ?>" value="<?php echo $i ?>">
					<input type="hidden" name="StudentID_<?php echo $i ?>" value="<?php echo $data[$i]['StudentID']; ?>">
					<input type="hidden" name="StudentName_<?php echo $i ?>" value="<?php echo $data[$i]['StudentName']; ?>">
					<input type="hidden" name="StudentAtenaSeq_<?php echo $i ?>" value="<?php echo $data[$i]['AtenaSeq']; ?>">
					<input type="hidden" name="KeiyakuSeq_<?php echo $i ?>" value="<?php echo $data[$i]['Seq']; ?>">
					<input type="hidden" name="SHyojiFlg_<?php echo $i ?>" value="<?php echo $data[$i]['HyojiFlg']; ?>">
				</tr>
				<?php $no++; ?>
			<?php } else { ?>
				<?php if ($_SESSION["DateFlg"] == 1) { ?>
				<tr>
					<td width="30" align="center"><input type="submit" name="No_<?php echo $i ?>" style="cursor: pointer" value="<?php echo $no2 ?>" /></td>
					<td width="100" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i]['StudentID'] ?></td>
					<td width="250" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i]['StudentName'] ?></td>
					<td width="50" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i]['AtenaSeq'] ?></td>
					<td width="150" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo date('Y年n月j日', strtotime($data[$i]['StartDay'])); ?></td>
					<td width="150" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo date('Y年n月j日', strtotime($data[$i]['EndDay'])); ?></td>
					<td width="50" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i]['Seq'] ?></td>
					<?php if($shikaku == 1){ ?>
						<td width="100" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo number_format($data[$i]['Pay']); ?></td>
						<td width="100" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i]['KiteiKaisu'] ?></td>
						<td width="100" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i]['KiteiJikan'] ?></td>
					<?php } ?>
					<input type="hidden" name="postshori" value="">
					<input type="hidden" name="valuename_<?php echo $i ?>" value="<?php echo $i ?>">
					<input type="hidden" name="StudentID_<?php echo $i ?>" value="<?php echo $data[$i]['StudentID']; ?>">
					<input type="hidden" name="StudentName_<?php echo $i ?>" value="<?php echo $data[$i]['StudentName']; ?>">
					<input type="hidden" name="StudentAtenaSeq_<?php echo $i ?>" value="<?php echo $data[$i]['AtenaSeq']; ?>">
					<input type="hidden" name="KeiyakuSeq_<?php echo $i ?>" value="<?php echo $data[$i]['Seq']; ?>">
					<input type="hidden" name="SHyojiFlg_<?php echo $i ?>" value="<?php echo $data[$i]['HyojiFlg']; ?>">
				</tr>
				<?php } ?>
				<?php $no2=$no2+1;?>
			<?php } ?>
		<?php } ?>
	</table>

</form>
</CENTER>
</html>