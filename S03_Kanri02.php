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
	 	ModoruShori($_SESSION["S03_Kanri02_RPID"]);
		exit;
	}

	//終了分表示
	if(isset($_POST['ALLDate'])){
		$_SESSION["DateFlg"] = 1;
		$_SESSION["S03_Kanri02_MODE"] = "ALLDate";
	}
	if(isset($_POST['EndDate'])){
		$_SESSION["DateFlg"] = 0;
		$_SESSION["S03_Kanri02_MODE"] = "EndDate";

	}

	if(isset($_POST['submitter'])){
		if($_POST['submitter']=="UpdateEnd"){
			$_SESSION["S03_Kanri02_MODE"] = "UpdateEnd";
			$EMSG = UpdateEnd_Shori($_SESSION["S03_Kanri02_Kensaku_KEY1"],$_SESSION["S03_Kanri02_Kensaku_SEQ"]);
			$_SESSION["EMSG"]=$EMSG;
		}
	}

	// 生徒選択処理
	for ($m = 0; $m < $_SESSION["DateCount"]; $m++){
		if(isset($_POST["No_" . $m])){
			$Location = "S04_Kanri02.php?MODE=UPD&RPID=S03_Kanri02&KEY1=" . $_SESSION["S03_Kanri02_Kensaku_KEY1"] . "&KEY2=" . $_POST['TeacherID_' . $m] . "&AtenaSEQ=" . $_POST['AtenaSeq_' . $m] . "&SEQ=" . $_POST['Seq_' . $m] . "&SENTEI=" . $_POST['SenteiFlg_' . $m];
		 	header("Location:" . $Location);
			exit;
		}
	}

	if(isset($_POST["upddate"])){
		$_SESSION["S03_Kanri02_MODE"] = $_POST["upddate"];
	}

	$CheckCnt=0;
	if($_SESSION["S03_Kanri02_MODE"] == "CHK"){
		for ($m = 0; $m < $_SESSION["DateCount"]; $m++){
			if(isset($_POST["CheckPrt-" . $m])){
				$_SESSION["CheckPrt-" .$m] = $_POST["CheckPrt-" . $m];
				$_SESSION["S03_CHECKDATA"] = $_POST["CheckPrt-" . $m];
				$CheckCnt ++;
			}else{
				$_SESSION["CheckPrt-" .$m] = "99";
				if($CheckCnt == 0){
					$_SESSION["S03_CHECKDATA"] = "99";
				}
			}
		}
		$_SESSION["CheckCnt"] = $CheckCnt;
	}else{
		$_SESSION["S03_CHECKDATA"] = "99";
		$_SESSION["CheckCnt"] = $CheckCnt;
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
			$_SESSION["S03_Kanri02_RPID"] = $_GET['RPID'];
		      	//print($_SESSION["S03_Kanri02_RPID"]);
		}
		if(isset($_GET['MODE'])) {
			$_SESSION["S03_Kanri02_MODE"] = $_GET['MODE'];
		      	//print($_SESSION["S03_Kanri02_MODE"] . "<BR>");

			if($_SESSION["S03_Kanri02_MODE"] == "UPD"){
				$_SESSION["DateFlg"] = 0;
				$_SESSION["DateCount"] = 0;
				$_SESSION["TeacherID"] = $_SESSION["LoginTeacherID"];
				$_SESSION["TName1"] = $_SESSION["LoginTName1"];
				$_SESSION["TName2"] = $_SESSION["LoginTName2"];
			}
		}

		if(isset($_GET['KEY1'])) {
			$_SESSION["S03_Kanri02_Kensaku_KEY1"] = $_GET['KEY1'];
		}
		if(isset($_GET['SEQ'])) {
			$_SESSION["S03_Kanri02_Kensaku_SEQ"] = $_GET['SEQ'];
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
//		$query = "SELECT * , a.Seq as ASeq, b.Seq as ShosaiSeq FROM T_Tanto as a inner join T_TantoShosai as b ";
//		$query = $query . " on a.TeacherID = b.TeacherID ";
//		$query = $query . " and a.StudentID = b.StudentID ";
//		$query = $query . " and a.Seq = b.AtenaSeq ";
//		$query = $query . " WHERE a.StudentID = '" . $_SESSION["S03_Kanri02_Kensaku_KEY1"] . "'";
//		$query = $query . " And a.Seq = '" . $_SESSION["S03_Kanri02_Kensaku_SEQ"] . "'";
//		$query = $query . " ORDER BY a.TeacherID ASC,a.StartDay DESC ";

		$query = "SELECT * FROM T_TantoShosai";
		$query = $query . " WHERE StudentID = '" . $_SESSION["S03_Kanri02_Kensaku_KEY1"] . "'";
		$query = $query . " And AtenaSeq = '" . $_SESSION["S03_Kanri02_Kensaku_SEQ"] . "'";
		$query = $query . " ORDER BY TeacherID ASC,StartDay DESC ";

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

			if($_SESSION["S03_Kanri02_MODE"] == "UPD"){
				$_SESSION["CheckPrt-" .$i] = "99";
			}

			$StudentID = $data[$i]['StudentID'];
			$TeacherID = $data[$i]['TeacherID'];
			$AtenaSeq = $data[$i]['AtenaSeq'];
			$Seq = $data[$i]['Seq'];
			$db_Name1 = "";
			$db_Name2 = "";

			//------教師名取得------
			$query2 = "SELECT a.Name1,a.Name2,b.Mail1,b.Mail2,b.Tel1,b.Tel2,b.Tel3 FROM T_AtenaInfo as a";
			$query2 = $query2 . " inner join T_KihonInfo as b";
			$query2 = $query2 . " on a.TeacherID = b.TeacherID";
			$query2 = $query2 . " WHERE a.TeacherID = '" . $TeacherID . "'";

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
				$db_Mail1 = $row['Mail1'];
				$db_Mail2 = $row['Mail2'];
				$db_Tel1 = $row['Tel1'];
				$db_Tel2 = $row['Tel2'];
				$db_Tel3 = $row['Tel3'];
			}

			$data[$i]['TeacherName'] = $db_Name1;
			$Mail = $db_Mail1;
//			if($db_Mail2 != ""){
//				$Mail = $Mail . "<BR>" . $db_Mail2;
//			}
			$data[$i]['Mail'] = $Mail;

			$Tel = $db_Tel1;
//			if($db_Tel2 != ""){
//				$Tel = $Tel . "<BR>" . $db_Tel2;
//			}
//			if($db_Tel3 != ""){
//				$Tel = $Tel . "<BR>" . $db_Tel3;
//			}
			$data[$i]['Tel'] = $Tel;
			$data[$i]['SenteiFlg'] = 1;
			$data[$i]['SenteiName'] = "契約";

			//------終了分判定------
			if(is_null($data[$i]['EndDay'])){
				$data[$i]['HyojiFlg'] = 1;
				$data[$i]['Check'] = 1;
			}else{
				if (strtotime($Today) <= strtotime($data[$i]['EndDay'])) {
					$data[$i]['HyojiFlg'] = 1;
					$data[$i]['Check'] = 1;
				} else {
					$data[$i]['HyojiFlg'] = 0;
					$data[$i]['SenteiFlg'] = 8;
					$data[$i]['SenteiName'] = "終了";
					$data[$i]['Check'] = 0;
				}
			}

			//----------------折衝履歴を取得----------------------------
			$query3 = "SELECT b.* FROM T_TantoShosai as a inner join TS_SeshoInfo as b on";
			$query3 = $query3 . " a.TeacherID = b.TeacherID";
			$query3 = $query3 . " and a.StudentID = b.StudentID";
			$query3 = $query3 . " and a.AtenaSeq = b.AtenaSeq";
			$query3 = $query3 . " and a.EndSeq = b.EndSeq";
			$query3 = $query3 . " WHERE a.StudentID = '" . $_SESSION["S03_Kanri02_Kensaku_KEY1"] . "'";
			$query3 = $query3 . " and a.TeacherID = '" . $TeacherID . "'";
			$query3 = $query3 . " and a.AtenaSeq = '" . $AtenaSeq . "'";
			$query3 = $query3 . " and a.Seq = '" . $Seq . "'";

			$result3 = $mysqli->query($query3);

			//print($query3 ."<BR>");

			if (!$result3) {
				print('クエリーが失敗しました。' . $mysqli->error);
				$mysqli->close();
				exit();
			}
			$db_Naiyo = "";
			while ($row = $result3->fetch_assoc()) {
				$db_Naiyo = $row['Naiyo'];
			}

			$data[$i]['Naiyo'] = $db_Naiyo;

			$i++;
		}

		//----------------選定中教師選択----------------------------
		// クエリの実行
		$query = "SELECT * FROM S_SenteiInfo WHERE  StudentID = '" . $_SESSION["S03_Kanri02_Kensaku_KEY1"] . "' AND AtenaSeq=" . $_SESSION["S03_Kanri02_Kensaku_SEQ"];
		$query = $query . " And JyotaiFlg<>1 ";//契約
		$query = $query . " And JyotaiFlg<>2 ";//契約変更
		$query = $query . " And JyotaiFlg<>8 ";//契約終了
		$query = $query . " ORDER BY TeacherID";
		

		$result = $mysqli->query($query);

		//print($query ."<BR>");

		if (!$result) {
			print('クエリーが失敗しました。' . $mysqli->error);
			$mysqli->close();
			exit();
		}
		

//		$data = array();
//		$i = 0;
		while($arr_item = $result->fetch_assoc()){

			//レコード内の各フィールド名と値を順次参照
			foreach($arr_item as $key => $value){
				//フィールド名と値を表示
				$data[$i][$key] = $value;
			}
			if($_SESSION["S03_Kanri02_MODE"] == "UPD"){
				$_SESSION["CheckPrt-" .$i] = "99";
			}

			$StudentID = $data[$i]['StudentID'];
			$TeacherID = $data[$i]['TeacherID'];
			$AtenaSeq = $data[$i]['AtenaSeq'];
			$Seq = $data[$i]['Seq'];
			$db_Name1 = "";
			$db_Name2 = "";

			//------教師名取得------
			$query2 = "SELECT a.Name1,a.Name2,b.Mail1,b.Mail2,b.Tel1,b.Tel2,b.Tel3 FROM T_AtenaInfo as a";
			$query2 = $query2 . " inner join T_KihonInfo as b";
			$query2 = $query2 . " on a.TeacherID = b.TeacherID";
			$query2 = $query2 . " WHERE a.TeacherID = '" . $TeacherID . "'";

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
				$db_Mail1 = $row['Mail1'];
				$db_Mail2 = $row['Mail2'];
				$db_Tel1 = $row['Tel1'];
				$db_Tel2 = $row['Tel2'];
				$db_Tel3 = $row['Tel3'];
			}

			$data[$i]['TeacherName'] = $db_Name1;
			$Mail = $db_Mail1;
//			if($db_Mail2 != ""){
//				$Mail = $Mail . "<BR>" . $db_Mail2;
//			}
			$data[$i]['Mail'] = $Mail;

			$Tel = $db_Tel1;
//			if($db_Tel2 != ""){
//				$Tel = $Tel . "<BR>" . $db_Tel2;
//			}
//			if($db_Tel3 != ""){
//				$Tel = $Tel . "<BR>" . $db_Tel3;
//			}
			$data[$i]['Tel'] = $Tel;
			$data[$i]['StartDay'] = NULL;
			$data[$i]['EndDay'] = NULL;
			$data[$i]['EndFlg'] = 0;
//			$data[$i]['Pay'] = "";
//			$data[$i]['KiteiKaisu'] = "";
//			$data[$i]['KiteiJikan'] = "";
//			$data[$i]['course'] = "";
			if($data[$i]['JyotaiFlg']==0){
				$data[$i]['SenteiFlg'] = 0;
				$data[$i]['SenteiName'] = "選定";
				$data[$i]['HyojiFlg'] = 1;
				$data[$i]['Check'] = 1;
			}elseif($data[$i]['JyotaiFlg']==9){
				$data[$i]['SenteiFlg'] = 9;
				$data[$i]['SenteiName'] = "選定解除";
				$data[$i]['HyojiFlg'] = 0;
				$data[$i]['Check'] = 0;
			}
			$data[$i]['Naiyo'] = "";

			$i++;
		}
		$_SESSION["DateCount"] = count($data);	//データ件数

	 	// データベースの切断
		$mysqli->close();		

	}
//-----------------------------------------------------------
//	完了処理
//-----------------------------------------------------------
Function UpdateEnd_Shori($id,$seq){

$ErrMSG="";

	// mysqlへの接続
	$mysqli = new mysqli(HOST, USER, PASS);
	if ($mysqli->connect_errno) {
		print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
		exit();
	}

	// データベースの選択
	$mysqli->select_db(DBNAME);
	$mysqli->set_charset("utf8");

	//トランザクションをはじめる準備
	$Query = "set autocommit = 0";
	$mysqli->query( $Query);

	//トランザクション開始
	$Query = "begin";
	$mysqli->query( $Query);

	//学年更新
	$query = "UPDATE S_AtenaInfo SET ";
	$query = $query . " updateflg = '1'";
	$query = $query . " WHERE  StudentID = '" . $id . "'";
	$query = $query . " AND  Seq = '" . $seq . "'";
	$query = $query . " AND  updateflg = 0";

	$result = $mysqli->query($query);

//	print($query);

	if (!$result) {
		$ErrMSG = "クエリーが失敗しました。（UpdShori_Kanryoエラー）" . $mysqli->error;
		$ErrFlg = 1;
	}


	if($ErrFlg == 0){
		
		// コミット
		$mysqli->query("commit");

		$RtnMSG = "更新しました。";
		$mysqli->close();

		return $RtnMSG;
	}
	if($ErrFlg == 1){
		$mysqli->query("rollback");

		$mysqli->close();

		return $ErrMSG;
	}

}
?>
<script type="text/javascript" src="utility.js"></script>
<script type="text/javascript">
	function setDate(pdata){
		var checkno = pdata;
		parent.S03_Kanri_head.document.form1.checkdata.value=checkno;
		if(checkno!="99"){
			document.form1.upddate.value = "CHK"
			document.form1.submit()
		}else{
			document.form1.upddate.value = "UPD"
		}
	}
</script>
<CENTER>
<body onload="setDate(99)">
<form id="target" name="form1" method="post" action="S03_Kanri02.php">
	<table border="0">
		<tr align="Right">
			<td align="right">
				<input type="hidden" id="submitter" name="submitter" value="" />
			</td>
		</tr>
	</table>
	<table border="0" width="100%">
		<font size="5" color="#ff0000"><?php echo $EMSG ?></font>
	</table>
	<table border="0" width="100%">
		<tr align="center">
			<td align="right">
				<input type="button" id="UpdateEnd" name="UpdateEnd" onClick="sbmfnc(this,'');" style="cursor: pointer" value="年度更新完了" <?php if($_SESSION["shikaku2"]==0){ ?>Disabled<?php } ?>/>
			</td>
		</tr>
		<tr align="center">
			<td align="center">
				<input type="submit" <?php if ($_SESSION["DateFlg"] == 0) {?> disabled <?php } ?> name="EndDate" style="cursor: pointer" value="終了分非表示" />
				<input type="submit" <?php if ($_SESSION["DateFlg"] == 1) {?> disabled <?php } ?> name="ALLDate" style="cursor: pointer" value="全データ表示" />
				<input type="hidden" name="upddate" value="" />
			</td>
		</tr>
	</table>
<div id="tbl-bdr">
	<table>
		<tr>
			<td id="midashi_Kanri" width="30" align="center" bgcolor="#c0c0c0" rowspan="3">契約区分</td>
			<td id="midashi_Kanri" width="100" align="center" bgcolor="<?php echo TEACHR_COLOR ?>">教師ID</td>
			<td id="midashi_Kanri" width="150" align="center" bgcolor="<?php echo TEACHR_COLOR ?>" colspan="2">教師名</td>
			<td id="midashi_Kanri" width="150" align="center" bgcolor="<?php echo TEACHR_COLOR ?>" colspan="2">電話番号/メール</td>
		</tr>
		<tr>
			<td id="midashi_Kanri" width="50" align="center" bgcolor="<?php echo KITEI_COLOR ?>">時給</td>
			<td id="midashi_Kanri" width="50" align="center" bgcolor="<?php echo KITEI_COLOR ?>">回数</td>
			<td id="midashi_Kanri" width="50" align="center" bgcolor="<?php echo KITEI_COLOR ?>">時間</td>
			<td id="midashi_Kanri" width="120" align="center" bgcolor="<?php echo KITEI_COLOR ?>">開始日</td>
			<td id="midashi_Kanri" width="120" align="center" bgcolor="<?php echo KITEI_COLOR ?>">終了日</td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" colspan="5">折衝内容</td>
		</tr>
		<?php $no=1;?>
		<?php $no2=1;?>
		<?php for ($i = 0; $i< $_SESSION["DateCount"]; $i++) {
			if(!is_null($data[$i]['EndFlg'])){
				$CodeName_Where = "Eda = '" . $data[$i]['EndFlg'] . "'";
				$CodeData = GetCodeData("終了区分",$CodeName_Where,"",1);
				$_SESSION["21CodeName"]=$CodeData;
				if($_SESSION["21CodeName"]["CountFlg"]==1){
					$_SESSION["21CodeName"]["21_CodeName1_0"]="";
				}
			}else{
				$_SESSION["21CodeName"]["21_CodeName1_0"]="";
			}
		?>
			<?php if ($_SESSION["DateFlg"] == 0 && $data[$i]['HyojiFlg'] == 1) { ?>
				<tr>
					<td align="center" rowspan="3">
						<input type="checkbox" name="CheckPrt-<?php echo $i?>" value="<?php echo $i ?>" onClick="setDate(<?php echo $i?>);" <?php if($_SESSION["CheckPrt-" .$i]==$i){?> CHECKED <?php } ?>>
						<input type="submit" name="No_<?php echo $i ?>" style="cursor: pointer;<?php if($data[$i]['SenteiFlg']==1){?>color:red;<?php } ?>" value="<?php echo $data[$i]['SenteiName'] ?>">
						<BR><BR>
						<font size="2"><?php echo $_SESSION["21CodeName"]["21_CodeName1_0"]?></font>
					</td>
					<td id="midashi_Ichiran" align="center"><?php echo $data[$i]['TeacherID'] ?>-<?php echo $data[$i]['AtenaSeq'] ?>-<?php echo $data[$i]['Seq'] ?></td>
					<td id="midashi_Ichiran" align="center" colspan="2"><?php echo $data[$i]['TeacherName'] ?></td>
					<td align="center" colspan="2"><?php echo $data[$i]['Tel'] ?><BR><?php echo $data[$i]['Mail'] ?></td>
				</tr>
				<tr>
					<td id="midashi_Ichiran" align="center"><?php if($data[$i]['Pay'] !=""){ ?><?php echo number_format($data[$i]['Pay']); ?><?php } ?></td>
					<td align="center"><?php echo $data[$i]['KiteiKaisu'] ?></td>
					<td align="center"><?php echo $data[$i]['KiteiJikan'] ?></td>
					<td align="center" width="120"><?php if(is_null($data[$i]['StartDay'])){ }else{ echo date('Y/n/j', strtotime($data[$i]['StartDay'])); }?></td>
					<td align="center" width="120"><?php if(is_null($data[$i]['EndDay'])){ }else{echo date('Y/n/j', strtotime($data[$i]['EndDay'])); }?></td>
				</tr>
				<tr>
					<td id="midashi_Ichiran" align="left" colspan="5"><?php echo $data[$i]['Naiyo'] ?></td>

					<input type="hidden" name="postshori" value="">
					<input type="hidden" name="valuename_<?php echo $i ?>" value="<?php echo $i ?>">
					<input type="hidden" name="TeacherID_<?php echo $i ?>" value="<?php echo $data[$i]['TeacherID']; ?>">
					<input type="hidden" name="TeacherName_<?php echo $i ?>" value="<?php echo $data[$i]['TeacherName']; ?>">
					<input type="hidden" name="AtenaSeq_<?php echo $i ?>" value="<?php echo $data[$i]['AtenaSeq']; ?>">
					<input type="hidden" name="Seq_<?php echo $i ?>" value="<?php echo $data[$i]['Seq']; ?>">
					<input type="hidden" name="SenteiFlg_<?php echo $i ?>" value="<?php echo $data[$i]['SenteiFlg']; ?>">
					<input type="hidden" name="SHyojiFlg_<?php echo $i ?>" value="<?php echo $data[$i]['HyojiFlg']; ?>">
				</tr>
				<?php $no++; ?>
			<?php } else { ?>
				<?php if ($_SESSION["DateFlg"] == 1) { ?>
				<tr>
					<td align="center" rowspan="3">
						<?php if ($data[$i]['HyojiFlg'] == 1) {?>
							<input type="checkbox" name="CheckPrt-<?php echo $i?>" value="<?php echo $i ?>" onClick="setDate(<?php echo $i ?>);" <?php if($_SESSION["CheckPrt-" .$i]==$i){?> CHECKED <?php } ?>>
						<?php } ?>
						<input type="submit" name="No_<?php echo $i ?>" style="cursor: pointer;<?php if($data[$i]['SenteiFlg']==1){?>color:red;<?php } ?>" value="<?php echo $data[$i]['SenteiName'] ?>">
						<BR><BR>
						<font size="2"><?php echo $_SESSION["21CodeName"]["21_CodeName1_0"]?></font>
					</td>
					<td id="midashi_Ichiran" align="center"<?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i]['TeacherID'] ?>-<?php echo $data[$i]['AtenaSeq'] ?>-<?php echo $data[$i]['Seq'] ?></td>
					<td id="midashi_Ichiran" align="center"<?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?> colspan="2"><?php echo $data[$i]['TeacherName'] ?></td>
					<td align="center"<?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?> colspan="2"><?php echo $data[$i]['Tel'] ?><BR><?php echo $data[$i]['Mail'] ?></td>
				</tr>
				<tr>
					<td id="midashi_Ichiran" align="center"<?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php if($data[$i]['Pay'] !=""){ ?><?php echo number_format($data[$i]['Pay']); ?><?php } ?></td>
					<td align="center"<?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i]['KiteiKaisu'] ?></td>
					<td align="center"<?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i]['KiteiJikan'] ?></td>
					<td align="center"<?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php if(is_null($data[$i]['StartDay'])){ }else{ echo date('Y/n/j', strtotime($data[$i]['StartDay'])); }?></td>
					<td align="center"<?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php if(is_null($data[$i]['EndDay'])){ }else{echo date('Y/n/j', strtotime($data[$i]['EndDay'])); }?></td>
				</tr>
				<tr>
					<td id="midashi_Ichiran" align="left"<?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?> colspan="5"><?php echo $data[$i]['Naiyo'] ?></td>

					<input type="hidden" name="postshori" value="">
					<input type="hidden" name="valuename_<?php echo $i ?>" value="<?php echo $i ?>">
					<input type="hidden" name="TeacherID_<?php echo $i ?>" value="<?php echo $data[$i]['TeacherID']; ?>">
					<input type="hidden" name="TeacherName_<?php echo $i ?>" value="<?php echo $data[$i]['TeacherName']; ?>">
					<input type="hidden" name="AtenaSeq_<?php echo $i ?>" value="<?php echo $data[$i]['AtenaSeq']; ?>">
					<input type="hidden" name="Seq_<?php echo $i ?>" value="<?php echo $data[$i]['Seq']; ?>">
					<input type="hidden" name="SenteiFlg_<?php echo $i ?>" value="<?php echo $data[$i]['SenteiFlg']; ?>">
					<input type="hidden" name="SHyojiFlg_<?php echo $i ?>" value="<?php echo $data[$i]['HyojiFlg']; ?>">
				</tr>
				<?php } ?>
				<?php $no2=$no2+1;?>
			<?php } ?>
		<?php } ?>
	</table>
</div>
</form>
</body>
</CENTER>
</html>