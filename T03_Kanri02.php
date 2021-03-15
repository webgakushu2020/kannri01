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
	 	ModoruShori($_SESSION["S_Select_RPID"]);
		exit;
	}
	$_SESSION["DateFlg"] = 0;

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
			header("Location:S03_Kanri01.php?MODE=VIEW&RPID=T03_Kanri02&KEY1=" . $_POST['StudentID_' . $m] . "&SEQ=" . $_POST['StudentAtenaSeq_' . $m] . "&KUBUN=1");
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
			$_SESSION["S_Select_RPID"] = $_GET['RPID'];
		      	//print($_SESSION["S_Select_RPID"]);
		}
		if(isset($_GET['MODE'])) {
			$_SESSION["S_Select_MODE"] = $_GET['MODE'];
		      	//print($_SESSION["S_Select_MODE"] . "<BR>");

			if($_SESSION["S_Select_MODE"] == "NEW"){
				$_SESSION["DateFlg"] = 0;
				$_SESSION["TeacherID"] = $_SESSION["LoginTeacherID"];
				$_SESSION["TName1"] = $_SESSION["LoginTName1"];
				$_SESSION["TName2"] = $_SESSION["LoginTName2"];
			}
		}
		if(isset($_GET['KEY1'])) {
			$_SESSION["T03_Kensaku_KEY1"] = $_GET['KEY1'];
		      	//print($_SESSION["S_Select_KEY1"]);
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

		// クエリの実行
		$query = "SELECT * FROM T_TantoShosai WHERE  TeacherID = '" . $_SESSION["T03_Kensaku_KEY1"] . "' ORDER BY StudentID ASC,StartDay DESC ";
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

			$data[$i]['StudentName'] = $db_Name2 . "<BR>" .$db_Name1 ;

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
<form name="form1" method="post" action="T03_Kanri02.php">
	<table border="0" width="100%">
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">生　徒　一　覧</td>
		</tr>
		<tr align="center">
			<td align="center">
				<input type="submit" <?php if ($_SESSION["DateFlg"] == 0) {?> disabled <?php } ?> name="EndDate" style="cursor: pointer" value="終了分非表示" />
				<input type="submit" <?php if ($_SESSION["DateFlg"] == 1) {?> disabled <?php } ?> name="ALLDate" style="cursor: pointer" value="全データ表示" />
			</td>
		</tr>
	</table>
<div id="tbl-bdr">
	<table>
		<tr>
			<td width="30" align="center" bgcolor="#c0c0c0">ＮＯ</td>
			<td width="100" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">生徒ＩＤ</td>
			<td width="150" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">生徒名</td>
			<td width="50" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">枝番</td>
			<td width="120" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">開始日</td>
			<td width="120" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">終了日</td>
			<?php if($shikaku == 1){ ?>
				<td width="50" align="center" bgcolor="<?php echo KITEI_COLOR ?>">時給</td>
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
					<td width="100" align="center"><?php echo $data[$i]['StudentID'] ?>-<?php echo $data[$i]['AtenaSeq'] ?></td>
					<td width="150" align="center"><?php echo $data[$i]['StudentName'] ?></td>
					<td width="50" align="center"><?php echo $data[$i]['Seq'] ?></td>
					<td width="120" align="center"><?php if(is_null($data[$i]['StartDay'])){ }else{ echo date('Y年n月j日', strtotime($data[$i]['StartDay'])); }?></td>
					<td width="120" align="center"><?php if(is_null($data[$i]['EndDay'])){ }else{echo date('Y年n月j日', strtotime($data[$i]['EndDay'])); }?></td>
					<?php if($shikaku == 1){ ?>
						<td width="50" align="center"><?php echo number_format($data[$i]['Pay']); ?></td>
						<td width="50" align="center"><?php echo $data[$i]['KiteiKaisu'] ?></td>
						<td width="50" align="center"><?php echo $data[$i]['KiteiJikan'] ?></td>
					<?php } ?>
					<input type="hidden" name="postshori" value="">
					<input type="hidden" name="valuename_<?php echo $i ?>" value="<?php echo $i ?>">
					<input type="hidden" name="StudentID_<?php echo $i ?>" value="<?php echo $data[$i]['StudentID']; ?>">
					<input type="hidden" name="StudentName_<?php echo $i ?>" value="<?php echo $data[$i]['StudentName']; ?>">
					<input type="hidden" name="StudentAtenaSeq_<?php echo $i ?>" value="<?php echo $data[$i]['AtenaSeq']; ?>">
					<input type="hidden" name="StudentSeq_<?php echo $i ?>" value="<?php echo $data[$i]['Seq']; ?>">
					<input type="hidden" name="SHyojiFlg_<?php echo $i ?>" value="<?php echo $data[$i]['HyojiFlg']; ?>">
				</tr>
				<?php $no++; ?>
			<?php } else { ?>
				<?php if ($_SESSION["DateFlg"] == 1) { ?>
				<tr>
					<td width="30" align="center"><input type="submit" name="No_<?php echo $i ?>" style="cursor: pointer" value="<?php echo $no2 ?>" /></td>
					<td width="100" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i]['StudentID'] ?>-<?php echo $data[$i]['AtenaSeq'] ?></td>
					<td width="150" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i]['StudentName'] ?></td>
					<td width="50" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i]['Seq'] ?></td>
					<td width="120" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php if(is_null($data[$i]['StartDay'])){ }else{echo date('Y年n月j日', strtotime($data[$i]['StartDay'])); }?></td>
					<td width="120" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php if(is_null($data[$i]['EndDay'])){ }else{echo date('Y年n月j日', strtotime($data[$i]['EndDay'])); }?></td>
					<?php if($shikaku == 1){ ?>
						<td width="50" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo number_format($data[$i]['Pay']); ?></td>
						<td width="50" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i]['KiteiKaisu'] ?></td>
						<td width="50" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i]['KiteiJikan'] ?></td>
					<?php } ?>
					<input type="hidden" name="postshori" value="">
					<input type="hidden" name="valuename_<?php echo $i ?>" value="<?php echo $i ?>">
					<input type="hidden" name="StudentID_<?php echo $i ?>" value="<?php echo $data[$i]['StudentID']; ?>">
					<input type="hidden" name="StudentName_<?php echo $i ?>" value="<?php echo $data[$i]['StudentName']; ?>">
					<input type="hidden" name="StudentAtenaSeq_<?php echo $i ?>" value="<?php echo $data[$i]['AtenaSeq']; ?>">
					<input type="hidden" name="StudentSeq_<?php echo $i ?>" value="<?php echo $data[$i]['Seq']; ?>">
					<input type="hidden" name="SHyojiFlg_<?php echo $i ?>" value="<?php echo $data[$i]['HyojiFlg']; ?>">
				</tr>
				<?php } ?>
				<?php $no2=$no2+1;?>
			<?php } ?>
		<?php } ?>
	</table>
</div>
</form>
</CENTER>
</html>