<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN"
	"http://www.w3.org/TR/html4/frameset.dtd">

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="Header.css">
	<title>一覧画面</title>
</head>

<?php include 'const.php'; ?>
<?php include 'utility.php'; ?>

<?php
session_start();

//	print('ユーザID'.$_SESSION["TeacherID"]);
//	print('ユーザ名'.$_SESSION["user_name"]);
//	print('資格'.$_SESSION["shikaku"]);

	//現在日の登録
	$dt = new DateTime();
	$dt->setTimeZone(new DateTimeZone('Asia/Tokyo'));
	$Today = $dt->format('Y/m/d');

	$TodayY = $dt->format('Y');
	$TodayM = $dt->format('n');

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
	 	ModoruShori($_SESSION["H01_Kensaku2_RPID"]);
		exit;
	}

	//終了分表示
	if(isset($_POST['ALLDate'])){
		$_SESSION["DateFlg"] = 1;
	}
	if(isset($_POST['EndDate'])){
		$_SESSION["DateFlg"] = 0;
	}

	// 教師選択処理
	for ($m = 0; $m < $_SESSION["DateCount"]; $m++){
		if($_SESSION["H01_Kensaku2_MODE"] == "KENT"){
			if(isset($_POST["No_" . $m])){
				$Location = "H02_Select.php?MODE=" . $_SESSION["H01_Kensaku2_MODE"] . "&RPID=H01_Kensaku2&TID=" . $_POST['TeacherID_' . $m];
			 	header("Location:" . $Location);
				exit;
			}
		}
		if($_SESSION["H01_Kensaku2_MODE"] == "KENS"){
			if(isset($_POST["No_" . $m])){
				$Location = "H03_Report1.php?MODE=" . $_SESSION["H01_Kensaku2_MODE"] . "&RPID=H01_Kensaku2&TID=" . $_POST['TeacherID_' . $m] ."&STID=" . $_POST['StudentID_' . $m] . "&ATENASEQ=" . $_POST['StudentAtenaSeq_' . $m] . "&SEQ=" . $_POST['KeiyakuSeq_' . $m];
			 	header("Location:" . $Location);
				exit;
			}
		}
	}

	// ログイン済みかどうかの変数チェックを行う
	if (!isset($_SESSION["user_name"])) {

		// 変数に値がセットされていない場合は不正な処理と判断し、ログイン画面へリダイレクトさせる
		$no_login_url = "http://{$_SERVER["HTTP_HOST"]}/Login1.php";
		header("Location: {$no_login_url}");
		exit;
	} else {

		//セッション情報保存
		//前画面からの情報
		$TeacherID = $_SESSION["TeacherID"];
		$user_name = $_SESSION["user_name"];
		$shikaku = (int) $_SESSION["shikaku"];
		$TName1 = $_SESSION["TName1"];
		$TName2 = $_SESSION["TName2"];

		//URLパラメータ
		if(isset($_GET['RPID'])) {
			$_SESSION["H01_Kensaku2_RPID"] = $_GET['RPID'];
		      	//print($_SESSION["H01_Kensaku2_RPID"] ."<BR>");
		}
		if(isset($_GET['MODE'])) {
			if($_GET['MODE'] != "Modoru"){
				$_SESSION["H01_Kensaku2_MODE"] = $_GET['MODE'];
				$_SESSION["ShoriID"]=$_GET['MODE'];
			      	//print($_SESSION["H01_Kensaku2_MODE"] . "<BR>");
			}
		}
		if(isset($_GET['KEY1'])) {
			$_SESSION["H01_Kensaku2_KEY1"] = $_GET['KEY1'];
		      	//print($_SESSION["H01_Kensaku2_KEY1"] ."<BR>");
		}
		if(isset($_GET['KEY2'])) {
			$_SESSION["H01_Kensaku2_KEY2"] = $_GET['KEY2'];
		      	//print($_SESSION["H01_Kensaku2_KEY2"] ."<BR>");
		}
		if(isset($_GET['CNT'])) {
			$_SESSION["H01_Kensaku2_CNT"] = $_GET['CNT'];
		      	//print($_SESSION["H01_Kensaku2_CNT"] ."<BR>");
		}
		if(isset($_GET['KFLG'])) {
			$_SESSION["H01_Kensaku2_KFLG"] = $_GET['KFLG'];
		      	//print($_SESSION["H01_Kensaku2_KFLG"] ."<BR>");
		}

		//件数が１件の場合は自動で遷移
		if($_SESSION["H01_Kensaku2_CNT"] == 1 && $_SESSION["H01_Kensaku2_MODE"] == "KENT"){
			if($_SESSION["H01_Kensaku2_MODE"] == "KENT"){
				header("Location:H02_Select.php?MODE=" . $_SESSION["H01_Kensaku2_MODE"] . "&RPID=H01_Kensaku&TID=" .$_SESSION["H01_Kensaku2_KEY1"]);
				exit;
			}
//			if($_SESSION["H01_Kensaku2_MODE"] == "KENS"){
//				header("Location:H01_Report1.php?MODE=" . $_SESSION["H01_Kensaku2_MODE"] . "&RPID=H01_Kensaku&TID=" .$_SESSION["H01_Kensaku2_KEY1"]);
//				exit;
//			}

		}else{
			if(($_SESSION["H01_Kensaku2_MODE"] == "KENT") && ($_SESSION["H01_Kensaku2_KFLG"]=="10" || $_SESSION["H01_Kensaku2_KFLG"]=="11")){
				//教師ＩＤ検索は１件と同様
				header("Location:H02_Select.php?MODE=" . $_SESSION["H01_Kensaku2_MODE"] . "&RPID=H01_Kensaku&TID=" .$_SESSION["H01_Kensaku2_KEY1"]);
				exit;
			}else{

				// mysqlへの接続
				$mysqli = new mysqli(HOST, USER, PASS);
				if ($mysqli->connect_errno) {
					print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
					exit();
				}

				// データベースの選択
				$mysqli->select_db(DBNAME);
				$mysqli->set_charset("utf8");

				switch ($_SESSION["H01_Kensaku2_MODE"]){
					//教師ID
					case 'KENT':
						switch ($_SESSION["H01_Kensaku2_KFLG"]){
							case '01':
								$username = $mysqli->real_escape_string($_SESSION["H01_Kensaku2_KEY2"]);
								$query = "SELECT * FROM T_AtenaInfo WHERE  Name1 like '%" . $username . "%' or Name2 like '%" . $username . "%' ORDER BY TeacherID ASC";
								$result = $mysqli->query($query);

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

									$TeacherID = $data[$i][TeacherID];
									$Name1 = $data[$i][Name1];
									$Name2 = $data[$i][Name2];

									$data[$i][TeacherName] = $Name1 . "　" . $Name2;

									$i++;
								}

								$_SESSION["DateCount"] = count($data);	//データ件数

								break;
						}
						break;
					case 'KENS':
						switch ($_SESSION["H01_Kensaku2_KFLG"]){
							case '10': case '11':
								$userid = $mysqli->real_escape_string($_SESSION["H01_Kensaku2_KEY1"]);

								// クエリの実行
								$query = "SELECT * FROM T_TantoShosai WHERE  StudentID = '" . $userid . "' ORDER BY StudentID ASC,AtenaSeq ASC";
								$result = $mysqli->query($query);

								if (!$result) {
									print('クエリーが失敗しました。' . $mysqli->error);
									$mysqli->close();
									exit();
								}

								$data = array();
								$data3 = array();
								$i = 0;
								while($arr_item = $result->fetch_assoc()){

									//レコード内の各フィールド名と値を順次参照
									foreach($arr_item as $key => $value){
										//フィールド名と値を表示
										$data[$i][$key] = $value;
									}

									$TeacherID = $data[$i][TeacherID];
									$StudentID = $data[$i][StudentID];
									$AtenaSeq = $data[$i][AtenaSeq];
									$Seq = $data[$i][Seq];
									$db_Name1 = "";
									$db_Name2 = "";

									//------教師名取得------
									$query2 = "SELECT * FROM T_AtenaInfo WHERE  TeacherID = '" . $TeacherID . "'";
									$result2 = $mysqli->query($query2);

									//print($query2 ."<BR>");

									if (!$result2) {
										print('クエリーが失敗しました。' . $mysqli->error);
										$mysqli->close();
										exit();
									}

									while ($row = $result2->fetch_assoc()) {
										$db_SName1 = $row['Name1'];
										$db_SName2 = $row['Name2'];
									}

									$data[$i][TeacherName] = $db_SName1 . "　" . $db_SName2;

									//------生徒名取得------
									$query3 = "SELECT * FROM S_AtenaInfo WHERE  StudentID = '" . $StudentID  . "' AND Seq = " . $AtenaSeq ;
									$result3 = $mysqli->query($query3);

									//print($query3 ."<BR>");

									if (!$result3) {
										print('クエリーが失敗しました。' . $mysqli->error);
										$mysqli->close();
										exit();
									}

									while ($row = $result3->fetch_assoc()) {
										$db_SName1 = $row['Name1'];
										$db_SName2 = $row['Name2'];
									}

									$data[$i][StudentName] = $db_SName1;

									//------回数取得------
									$data[$i][Kaisu1] = "";
									$data[$i][Kaisu2] = "";
									$data[$i][Kaisu3] = "";
									$data[$i][Kaisu4] = "";
									$data[$i][Kaisu5] = "";
									$data[$i][Kaisu6] = "";
									$data[$i][Kaisu7] = "";
									$data[$i][Kaisu8] = "";
									$data[$i][Kaisu9] = "";
									$data[$i][Kaisu10] = "";
									$data[$i][Kaisu11] = "";
									$data[$i][Kaisu12] = "";

									$query4 = "SELECT * FROM T_ReportMonth WHERE TeacherID = '" . $TeacherID . "' AND StudentID = '" . $StudentID  . "' AND AtenaSeq = " . $AtenaSeq . "' AND Seq = " . $Seq;
									$result4 = $mysqli->query($query4);

									//print($query4 ."<BR>");

									if (!$result4) {
										print('クエリーが失敗しました。' . $mysqli->error);
										$mysqli->close();
										exit();
									}
									$h = 0;
									while($arr_item2 = $result4->fetch_assoc()){

										//レコード内の各フィールド名と値を順次参照
										foreach($arr_item2 as $key => $value){
											//フィールド名と値を表示
											$data3[$h][$key] = $value;
										}
										$Year_Flg = $data3[$h][Year];										
										$Month_Flg = $data3[$h][Month];
										$Kaisu_Flg = $data3[$h][KaisuSum];

										$data[$i][Kaisu . $Month_Flg] = $data3[$h][KaisuSum];

										$h++;
									}



										//print($query4 ."<BR>");

										if (!$result4) {
											print('クエリーが失敗しました。' . $mysqli->error);
											$mysqli->close();
											exit();
										}
										$h = 0;
										while($arr_item3 = $result4->fetch_assoc()){

											//レコード内の各フィールド名と値を順次参照
											foreach($arr_item3 as $key => $value){
												//フィールド名と値を表示
												$data3[$h][$key] = $value;
											}
											$Year_Flg = $data3[$h][Year];										
											$Month_Flg = $data3[$h][Month];
											$Kaisu_Flg = $data3[$h][KaisuSum];

											$data2[$m][Kaisu . $Month_Flg] = $data3[$h][KaisuSum];

											$h++;
										}





									//------終了分判定------
									if(is_null($data[$i][EndDay])){
										$data[$i][HyojiFlg] = 1;
									}else{
										if ((strtotime($data[$i][StartDay]) <= strtotime($Today)) && (strtotime($Today) <= strtotime($data[$i][EndDay]))) {
											$data[$i][HyojiFlg] = 1;
										} else {
											$data[$i][HyojiFlg] = 0;
										}
									}
									$i++;
								}

								$_SESSION["DateCount"] = count($data);	//データ件数

								break;
							case '01':
								$userid = $mysqli->real_escape_string($_SESSION["H01_Kensaku2_KEY2"]);

								$query = "SELECT * FROM S_AtenaInfo WHERE  Name1 like '%" . $userid . "%' or Name2 like '%" . $userid . "%' ORDER BY StudentID ASC";
								//print($query . "<BR>");

								$result = $mysqli->query($query);

								if (!$result) {
									print('クエリーが失敗しました。' . $mysqli->error);
									$mysqli->close();
									exit();
								}

								$data = array();
								$data2 = array();
								$data3 = array();
								$i = 0;
								$m = 0;
								while($arr_item = $result->fetch_assoc()){
									//レコード内の各フィールド名と値を順次参照
									foreach($arr_item as $key => $value){
										//フィールド名と値を表示
										$data[$i][$key] = $value;
									}

									$StudentID = $data[$i][StudentID];
									$AtenaSeq = $data[$i][Seq];
									$Name1 = $data[$i][Name1];
									$Name2 = $data[$i][Name2];

									// クエリの実行
									$query = "SELECT * FROM T_TantoShosai WHERE  StudentID = '" . $StudentID . "' AND AtenaSeq=" . $AtenaSeq . " ORDER BY StudentID ASC,StartDay DESC ";
									//print($query . "<BR>");

									$result2 = $mysqli->query($query);

									if (!$result2) {
										print('クエリーが失敗しました。' . $mysqli->error);
										$mysqli->close();
										exit();
									}

									while($arr_item2 = $result2->fetch_assoc()){

										//レコード内の各フィールド名と値を順次参照
										foreach($arr_item2 as $key => $value){
											//フィールド名と値を表示
											$data2[$m][$key] = $value;
										}

										$TeacherID2 = $data2[$m][TeacherID];
										$StudentID2 = $data2[$m][StudentID];
										$AtenaSeq2 = $data2[$m][Seq];
										$db_Name1 = "";
										$db_Name2 = "";

										//------教師名取得------
										$query2 = "SELECT * FROM T_AtenaInfo WHERE  TeacherID = '" . $TeacherID2 . "'";

										$result2 = $mysqli->query($query2);

										//print($query2 ."<BR>");

										if (!$result2) {
											print('クエリーが失敗しました。' . $mysqli->error);
											$mysqli->close();
											exit();
										}

										while ($row = $result2->fetch_assoc()) {
											$db_SName1 = $row['Name1'];
											$db_SName2 = $row['Name2'];
										}

										$data2[$m][StudentName] = $Name1;
										$data2[$m][TeacherName] = $db_SName1;

										//------回数取得------
										$data2[$m][Kaisu1] = "";
										$data2[$m][Kaisu2] = "";
										$data2[$m][Kaisu3] = "";
										$data2[$m][Kaisu4] = "";
										$data2[$m][Kaisu5] = "";
										$data2[$m][Kaisu6] = "";
										$data2[$m][Kaisu7] = "";
										$data2[$m][Kaisu8] = "";
										$data2[$m][Kaisu9] = "";
										$data2[$m][Kaisu10] = "";
										$data2[$m][Kaisu11] = "";
										$data2[$m][Kaisu12] = "";

										$query4 = "SELECT * FROM T_ReportMonth WHERE TeacherID = '" . $TeacherID2 . "' AND StudentID = '" . $StudentID2  . "' AND AtenaSeq = " . $AtenaSeq2 ;
										$result4 = $mysqli->query($query4);

										//print($query4 ."<BR>");

										if (!$result4) {
											print('クエリーが失敗しました。' . $mysqli->error);
											$mysqli->close();
											exit();
										}
										$h = 0;
										while($arr_item3 = $result4->fetch_assoc()){

											//レコード内の各フィールド名と値を順次参照
											foreach($arr_item3 as $key => $value){
												//フィールド名と値を表示
												$data3[$h][$key] = $value;
											}
											$Year_Flg = $data3[$h][Year];										
											$Month_Flg = $data3[$h][Month];
											$Kaisu_Flg = $data3[$h][KaisuSum];

											$data2[$m][Kaisu . $Month_Flg] = $data3[$h][KaisuSum];

											$h++;
										}

										//------終了分判定------
										if(is_null($data2[$m][EndDay])){
											$data2[$m][HyojiFlg] = 1;
										}else{
											if ((strtotime($data2[$m][StartDay]) <= strtotime($Today)) && (strtotime($Today) <= strtotime($data2[$m][EndDay]))) {
												$data2[$m][HyojiFlg] = 1;
											} else {
												$data2[$m][HyojiFlg] = 0;
											}
										}
										
										//print($data2[$m][StudentID] . "<BR>");
										//print($data2[$m][StudentName] . "<BR>");
										//print($data2[$m][TeacherID] . "<BR>");
										//print($data2[$m][TeacherName] . "<BR>");
										//print($data2[$m][Seq] . "<BR>");
										//print($data2[$m][HyojiFlg] . "<BR>");
										
										$m++;
									}

									$i++;
								}

								$_SESSION["DateCount"] = count($data2);	//データ件数
						}
						break;
				}
		 	// データベースの切断
			$mysqli->close();
			}
		
		}
		

	}
?>
<CENTER>
<form name="form1" method="post" action="H01_Kensaku2.php">
	<div id="header0" class="item">
		<BR>
		<table border="0" width="100%"  bgcolor="<?php echo HEADER_COLOR ?>">
			<tr align="center">
				<td align="center">
				<?php if($_SESSION["H01_Kensaku2_MODE"] == "KENT") {?>
					<h2>教師選択画面</h2>
				<?php }else{ ?>
					<h2>生徒選択画面</h2>
				<?php } ?>
				</td>
			</tr>
		</table>
	</div>
	<table border="0" width="100%">
		<tr align="Right">
			<td align="Right">
				ログイン　<?php echo $_SESSION["LoginTName1"] ?><?php echo $_SESSION["LoginTName2"] ?>
			</td>
		</tr>
		<td align="right">
			<input type="submit" name="modoru" style="cursor: pointer" value="戻る" />
			<input type="submit" name="logout" style="cursor: pointer" value="ログアウト" />
		</td>
	</table>
	<BR><BR><BR>	<?php if($_SESSION["H01_Kensaku2_MODE"] == "KENT") {?>
		<table border="1">
			<tr>
				<td width="30" align="center" bgcolor="#c0c0c0">ＮＯ</td>
				<td width="100" align="center" bgcolor="<?php echo TEACHR_COLOR ?>">教師ＩＤ</td>
				<td width="250" align="center" bgcolor="<?php echo TEACHR_COLOR ?>">教師名</td>
			</tr>
			<?php for ($i = 0; $i< count($data); $i++) { ?>
				<tr>
					<td width="30" align="center"><input type="submit" name="No_<?php echo $i ?>" style="cursor: pointer" value="<?php echo $i+1 ?>" /></td>
					<td width="100" align="center"><?php echo $data[$i][TeacherID] ?></td>
					<td width="250" align="center"><?php echo $data[$i][TeacherName] ?></td>
					<input type="hidden" name="postshori" value="">
					<input type="hidden" name="valuename_<?php echo $i ?>" value="<?php echo $i ?>">
					<input type="hidden" name="TeacherID_<?php echo $i ?>" value="<?php echo $data[$i][TeacherID]; ?>">
					<input type="hidden" name="TeacherName_<?php echo $i ?>" value="<?php echo $data[$i][TeacherName]; ?>">
				</tr>
			<?php } ?>
		</table>
	<?php } ?>
	<?php if($_SESSION["H01_Kensaku2_MODE"] == "KENS" && ($_SESSION["H01_Kensaku2_KFLG"] =="10" || $_SESSION["H01_Kensaku2_KFLG"] == "11")) {?>
		<table border="1">
			<tr>
				<td width="30" align="center" bgcolor="#c0c0c0" rowspan="2">ＮＯ</td>
				<td width="400" align="center" bgcolor="<?php echo STUDENT_COLOR ?>" colspan="3">生徒</td>
				<td width="250" align="center" bgcolor="<?php echo TEACHR_COLOR ?>" colspan="2">教師</td>
				<td width="100" align="center" bgcolor="<?php echo KITEI_COLOR ?>" colspan="3">規定</td>
				<td width="360" align="center" bgcolor="<?php echo JISSEKI_COLOR ?>" colspan="12">実績</td>
			</tr>
			<tr>
				<td width="100" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">ＩＤ</td>
				<td width="250" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">名前</td>
				<td width="50" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">枝番</td>
				<td width="100" align="center" bgcolor="<?php echo TEACHR_COLOR ?>">ＩＤ</td>
				<td width="250" align="center" bgcolor="<?php echo TEACHR_COLOR ?>">名前</td>
				<?php if($shikaku==1){?>
					<td width="50" align="center" bgcolor="<?php echo KITEI_COLOR ?>">枝番</td>
					<td width="50" align="center" bgcolor="<?php echo KITEI_COLOR ?>">回数</td>
					<td width="50" align="center" bgcolor="<?php echo KITEI_COLOR ?>">時間</td>
				<?php } ?>

				<?php if($TodayM == 12){
					$TodayM_Start = 1;
				}else{
					$TodayM_Start = $TodayM + 1;
				}
				for($m=0; $m<12; $m++){
					$Month = $TodayM_Start + $m;
					if($Month > 12){
						$Month = $Month - 12;
					}
				?>
						<td width="30" align="center" bgcolor="<?php echo JISSEKI_COLOR ?>"><?php echo $Month ?></td>
				<?php } ?>
			</tr>
			<?php for ($i = 0; $i< count($data); $i++) { ?>
				<tr>
					<td width="30" align="center" <?php if ($data[$i][HyojiFlg] == 0) {?>bgcolor="#c0c0c0"<?php }?>><input type="submit" name="No_<?php echo $i ?>" style="cursor: pointer" value="<?php echo $i+1 ?>" /></td>
					<td width="100" align="center" <?php if ($data[$i][HyojiFlg] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i][StudentID] ?></td>
					<td width="250" align="center" <?php if ($data[$i][HyojiFlg] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i][StudentName] ?></td>
					<td width="50" align="center" <?php if ($data[$i][HyojiFlg] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i][AtenaSeq] ?></td>
					<td width="100" align="center" <?php if ($data[$i][HyojiFlg] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i][TeacherID] ?></td>
					<td width="250" align="center" <?php if ($data[$i][HyojiFlg] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i][TeacherName] ?></td>
					<?php if($shikaku==1){?>
						<td width="50" align="center" <?php if ($data[$i][HyojiFlg] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i][Seq] ?></td>
						<td width="50" align="center" <?php if ($data[$i][HyojiFlg] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i][KiteiKaisu] ?></td>
						<td width="50" align="center" <?php if ($data[$i][HyojiFlg] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i][KiteiJikan] ?></td>
					<?php } ?>

					<?php if($TodayM == 12){
						$TodayM_Start = 1;
					}else{
						$TodayM_Start = $TodayM + 1;
					}
					for($m=0; $m<12; $m++){
						$Month = $TodayM_Start + $m;
						if($Month > 12){
							$Month = $Month - 12;
						}
					?>
						<td width="30" align="center" <?php if ($data[$i][HyojiFlg] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i][Kaisu . $Month] ?></td>
					<?php } ?>
					<input type="hidden" name="postshori" value="">
					<input type="hidden" name="valuename_<?php echo $i ?>" value="<?php echo $i ?>">
					<input type="hidden" name="TeacherID_<?php echo $i ?>" value="<?php echo $data[$i][TeacherID]; ?>">
					<input type="hidden" name="TeacherName_<?php echo $i ?>" value="<?php echo $data[$i][TeacherName]; ?>">
					<input type="hidden" name="StudentID_<?php echo $i ?>" value="<?php echo $data[$i][StudentID]; ?>">
					<input type="hidden" name="StudentName_<?php echo $i ?>" value="<?php echo $data[$i][StudentName]; ?>">
					<input type="hidden" name="StudentAtenaSeq_<?php echo $i ?>" value="<?php echo $data[$i][AtenaSeq]; ?>">
					<input type="hidden" name="KeiyakuSeq_<?php echo $i ?>" value="<?php echo $data[$i][Seq]; ?>">
				</tr>
			<?php } ?>
		</table>
	<?php } ?>
	<?php if($_SESSION["H01_Kensaku2_MODE"] == "KENS" && $_SESSION["H01_Kensaku2_KFLG"] =="01") { ?>
		<table border="1">
			<tr>
				<td width="30" align="center" bgcolor="#c0c0c0" rowspan="2">ＮＯ</td>
				<td width="400" align="center" bgcolor="<?php echo STUDENT_COLOR ?>" colspan="3">生徒</td>
				<td width="250" align="center" bgcolor="<?php echo TEACHR_COLOR ?>" colspan="2">教師</td>
				<td width="100" align="center" bgcolor="<?php echo KITEI_COLOR ?>" colspan="3">規定</td>
				<td width="360" align="center" bgcolor="<?php echo JISSEKI_COLOR ?>" colspan="12">実績</td>
			</tr>
			<tr>
				<td width="100" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">ＩＤ</td>
				<td width="250" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">名前</td>
				<td width="50" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">枝番</td>
				<td width="100" align="center" bgcolor="<?php echo TEACHR_COLOR ?>">ＩＤ</td>
				<td width="250" align="center" bgcolor="<?php echo TEACHR_COLOR ?>">名前</td>
				<?php if($shikaku==1){?>
					<td width="50" align="center" bgcolor="<?php echo KITEI_COLOR ?>">枝番</td>
					<td width="50" align="center" bgcolor="<?php echo KITEI_COLOR ?>">回数</td>
					<td width="50" align="center" bgcolor="<?php echo KITEI_COLOR ?>">時間</td>
				<?php } ?>
				<?php if($TodayM == 12){
					$TodayM_Start = 1;
				}else{
					$TodayM_Start = $TodayM + 1;
				}
				for($m=0; $m<12; $m++){
					$Month = $TodayM_Start + $m;
					if($Month > 12){
						$Month = $Month - 12;
					}
				?>
						<td width="30" align="center" bgcolor="<?php echo JISSEKI_COLOR ?>"><?php echo $Month ?></td>
				<?php } ?>
			</tr>
			<?php for ($i = 0; $i< count($data2); $i++) { ?>
				<tr>
					<td width="30" align="center" <?php if ($data2[$i][HyojiFlg] == 0) {?>bgcolor="#c0c0c0"<?php }?>><input type="submit" name="No_<?php echo $i ?>" style="cursor: pointer" value="<?php echo $i+1 ?>" /></td>
					<td width="100" align="center" <?php if ($data2[$i][HyojiFlg] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data2[$i][StudentID] ?></td>
					<td width="250" align="center" <?php if ($data2[$i][HyojiFlg] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data2[$i][StudentName] ?></td>
					<td width="50" align="center" <?php if ($data2[$i][HyojiFlg] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data2[$i][AtenaSeq] ?></td>
					<td width="100" align="center" <?php if ($data2[$i][HyojiFlg] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data2[$i][TeacherID] ?></td>
					<td width="250" align="center" <?php if ($data2[$i][HyojiFlg] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data2[$i][TeacherName] ?></td>
					<?php if($shikaku==1){?>
						<td width="50" align="center" <?php if ($data2[$i][HyojiFlg] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data2[$i][Seq] ?></td>
						<td width="50" align="center" <?php if ($data2[$i][HyojiFlg] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data2[$i][KiteiKaisu] ?></td>
						<td width="50" align="center" <?php if ($data2[$i][HyojiFlg] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data2[$i][KiteiJikan] ?></td>
					<?php } ?>
					<?php if($TodayM == 12){
						$TodayM_Start = 1;
					}else{
						$TodayM_Start = $TodayM + 1;
					}
					for($m=0; $m<12; $m++){
						$Month = $TodayM_Start + $m;
						if($Month > 12){
							$Month = $Month - 12;
						}
					?>
						<td width="30" align="center" <?php if ($data2[$i][HyojiFlg] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data2[$i][Kaisu . $Month] ?></td>
					<?php } ?>
					<input type="hidden" name="postshori" value="">
					<input type="hidden" name="valuename_<?php echo $i ?>" value="<?php echo $i ?>">
					<input type="hidden" name="TeacherID_<?php echo $i ?>" value="<?php echo $data2[$i][TeacherID]; ?>">
					<input type="hidden" name="TeacherName_<?php echo $i ?>" value="<?php echo $data2[$i][TeacherName]; ?>">
					<input type="hidden" name="StudentID_<?php echo $i ?>" value="<?php echo $data2[$i][StudentID]; ?>">
					<input type="hidden" name="StudentName_<?php echo $i ?>" value="<?php echo $data2[$i][StudentName]; ?>">
					<input type="hidden" name="StudentAtenaSeq_<?php echo $i ?>" value="<?php echo $data2[$i][AtenaSeq]; ?>">
					<input type="hidden" name="KeiyakuSeq_<?php echo $i ?>" value="<?php echo $data2[$i][Seq]; ?>">
				</tr>
			<?php } ?>
		</table>
	<?php } ?>
</form>
</CENTER>
</html>