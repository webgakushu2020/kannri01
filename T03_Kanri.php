<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN"
	"http://www.w3.org/TR/html4/frameset.dtd">

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="Header.css">
	<title>教師管理画面</title>
</head>

<?php include 'const.php'; ?>
<?php include 'utility.php'; ?>

<?php
session_start();

ini_set( 'display_errors', 1 );
$query2 = "";
	$dt = new DateTime();
	$dt->setTimeZone(new DateTimeZone('Asia/Tokyo'));
	$Today = $dt->format('Y/m/d');
	$EMSG = "";
	
	$_SESSION["Today"]=$Today;

	if(isset($_POST['submitter'])){
		if($_POST['submitter']=="logout"){
			if($_SESSION["passhoji"] == 1){
			 	LogoutShori2();
			}else{
			 	LogoutShori();
			}
			exit;
		}
	}

	if(isset($_POST['submitter'])){
		if($_POST['submitter']=="modoru"){
		 	ModoruShori($_SESSION["T03_kensaku_RPID"]);
			exit;
		}
	}

	if(isset($_POST['submitter'])){
		switch ($_POST['submitter']){
			case 'kyoka':
				$_SESSION["ShoriID"]="KYOKA";
				break;
			case 'kyoka2':
				$_SESSION["ShoriID"]="KYOKA2";
				break;
			case 'shikaku':
				$_SESSION["ShoriID"]="SHIKAKU";
				break;
			case 'shikaku2':
				$_SESSION["ShoriID"]="SHIKAKU2";
				break;
			case 'keiken':
				$_SESSION["ShoriID"]="KEIKEN";
				break;
			case 'keiken2':
				$_SESSION["ShoriID"]="KEIKEN2";
				break;
			case 'sonota':
				$_SESSION["ShoriID"]="SONOTA";
				break;
			case 'sonota2':
				$_SESSION["ShoriID"]="SONOTA2";
				break;
			case 'Koza':
				$_SESSION["ShoriID"]="KOZA";
				break;
			case 'Koza2':
				$_SESSION["ShoriID"]="KOZA2";
				break;

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
//		$TeacherID = $_SESSION["TeacherID"];
		$user_name = $_SESSION["user_name"];
		$shikaku = (int) $_SESSION["shikaku"];
		$TName1 = $_SESSION["TName1"];
		$TName2 = $_SESSION["TName2"];

		//URLパラメータ
		if(isset($_GET['RPID'])) {
			$_SESSION["T03_kensaku_RPID"] = $_GET['RPID'];
		      	print($_SESSION["T03_kensaku_RPID"]);
		}
		if(isset($_GET['MODE'])) {
			if($_GET['MODE'] != "Modoru"){
				$_SESSION["T03_kensaku_MODE"] = $_GET['MODE'];
				$_SESSION["ShoriID"]=$_GET['MODE'];
			      	//print($_SESSION["K_kensaku_MODE"] . "<BR>");
			}
		}
		if(isset($_GET['KEY1'])) {
			$_SESSION["Kensaku_KEY1"] = $_GET['KEY1'];
		}

		switch ($_SESSION["ShoriID"]){
			case 'UPD':
				SessionClear();
				GetData();

				break;
			case 'KYOKA':
				$_SESSION["KyokaFlg"]="1";
				break;
			case 'KYOKA2':
				$_SESSION["KyokaFlg"]="0";
				break;
			case 'SHIKAKU':
				$_SESSION["ShikakuFlg"]="1";
				break;
			case 'SHIKAKU2':
				$_SESSION["ShikakuFlg"]="0";
				break;
			case 'KEIKEN':
				$_SESSION["KeikenFlg"]="1";
				break;
			case 'KEIKEN2':
				$_SESSION["KeikenFlg"]="0";
				break;
			case 'SONOTA':
				$_SESSION["SonotaFlg"]="1";
				break;
			case 'SONOTA2':
				$_SESSION["SonotaFlg"]="0";
				break;
			case 'KOZA':
				$_SESSION["KozaFlg"]="1";
				break;
			case 'KOZA2':
				$_SESSION["KozaFlg"]="0";
				break;

		}	
	}
//-----------------------------------------------------------
//	セッション情報クリア
//-----------------------------------------------------------
function SessionClear(){

	$_SESSION["TourokuFlg"]=1;
	$_SESSION["T_KihonInfo_DataCount"]=0;
	$_SESSION["T_KihonInfo_DataCount2"]=0;
	$_SESSION["K_ToDofuken_DataCount"]=0;
	$_SESSION["T03_Koza_DataCount"]=0;

	$_SESSION["KyokaFlg"]="1";
	$_SESSION["ShikakuFlg"]="1";
	$_SESSION["KeikenFlg"]="1";
	$_SESSION["SonotaFlg"]="1";
	$_SESSION["KozaFlg"]="1";

	$_SESSION["T03_TeacherID"]="";
	$_SESSION["T03_EntryTime"]="";
	$_SESSION["T03_BirthDay"]="";
	$_SESSION["T03_Name1"]="";
	$_SESSION["T03_Name2"]="";
	$_SESSION["T03_Old"]= "";
	$_SESSION["T03_Seibetu"]="";

	$_SESSION["T03_Yubin1"]="";
	$_SESSION["T03_Yubin2"]="";
	$_SESSION["T03_Add_ken"]="";
	$_SESSION["T03_Add_Ken_Code"]="";
	$_SESSION["T03_Add_ken"]="";
	$_SESSION["T03_Add_shi"]="";
	$_SESSION["T03_Add_ku"]="";
	$_SESSION["T03_Add_cho"]="";

	$_SESSION["T03_Tel1"]="";
	$_SESSION["T03_Tel2"]="";
	$_SESSION["T03_Tel3"]="";
	$_SESSION["T03_Mail1"]="";
	$_SESSION["T03_Mail2"]="";

	$_SESSION["T03_Uni1"]="";
	$_SESSION["T03_Dept1"]="";
	$_SESSION["T03_Gradu1"]="";

	$_SESSION["T03_License1"]="";
	$_SESSION["T03_License2"]="";
	$_SESSION["T03_License3"]="";

	$_SESSION["T03_Exp_Kyou"]="";
	$_SESSION["T03_Exp_Juken"]="";
	$_SESSION["T03_Gra_Hight"]="";
	$_SESSION["T03_Gra_Junior"]="";

	$_SESSION["T03_Other1"]="";
	$_SESSION["T03_Other2"]="";
	$_SESSION["T03_Other3"]="";
	$_SESSION["T03_Other4"]="";
	$_SESSION["T03_Other5"]="";

	for($m=1; $m<=10; $m++){
		$_SESSION["T03_Sub1_" . $m]="";
	}
	for($m=1; $m<=10; $m++){
		$_SESSION["T03_Sub2_" . $m]="";
	}
	for($m=1; $m<=25; $m++){
		$_SESSION["T03_Sub3_" . $m]="";
	}
	for($m=1; $m<=5; $m++){
		$_SESSION["T03_Sub4_" . $m]="";
	}
	for($m=1; $m<=5; $m++){
		$_SESSION["T03_Notice_" . $m]="";
	}

	$_SESSION["DateFlg"]=0;

}
//-----------------------------------------------------------
//	データ取得
//-----------------------------------------------------------
Function GetData(){

		// mysqlへの接続
		$mysqli = new mysqli(HOST, USER, PASS);
		if ($mysqli->connect_errno) {
			print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
			exit();
		   		}

		// データベースの選択
		$mysqli->select_db(DBNAME);
		$mysqli->set_charset("utf8");

		//------------------------基本情報------------------------------
		$query = "Select a.*, b.*, c.* ";
		$query = $query . " FROM T_AtenaInfo as a inner join";
		$query = $query . " T_KihonInfo as b on";
		$query = $query . " a.TeacherID=b.TeacherID";
		$query = $query . " inner join T_ShosaiInfo as c on";
		$query = $query . " a.TeacherID=c.TeacherID";

		if($_SESSION["Kensaku_KEY1"]!=""){
			if($query2 = ""){
				$query2 = $query2 . " Where a.TeacherID=" . $_SESSION["Kensaku_KEY1"];
			}else{
				$query2 = $query2 . " And a.TeacherID=" . $_SESSION["Kensaku_KEY1"];
			}
		}
		$query = $query . $query2;
print($query);
		$result = $mysqli->query($query);

		if (!$result) {
			print('クエリーが失敗しました。' . $mysqli->error);
			$mysqli->close();
			exit();
		}
		
		$data = array();
		while($arr_item = $result->fetch_assoc()){

			//レコード内の各フィールド名と値を順次参照
			foreach($arr_item as $key => $value){
				//フィールド名と値を表示
				$_SESSION["T03_" . $key] = $value;
			}

			$_SESSION["T03_Old"]=floor ((date('Ymd') - date('Ymd', strtotime($_SESSION['T03_BirthDay'])))/10000);
		}

		//------------------------口座情報------------------------------
		$query3 = "Select * ";
		$query3 = $query3 . " FROM T_KozaInfo ";
		$query3 = $query3 . " Where TeacherID='" . $_SESSION["Kensaku_KEY1"] . "'";
		$query3 = $query3 . " order by KozaSeq Desc";

print($query3);

		$result2 = $mysqli->query($query3);

		if (!$result2) {
			print('クエリーが失敗しました。' . $mysqli->error);
			$mysqli->close();
			exit();
		}

		$data2 = array();
		$i = 0;
		while($arr_item = $result2->fetch_assoc()){

			//レコード内の各フィールド名と値を順次参照
			foreach($arr_item as $key => $value){
				//フィールド名と値を表示
				$data2[$i][$key] = $value;
			}
			$_SESSION["T03_Koza_TeacherID" .$i]="";
			$_SESSION["T03_Koza_KozaSeq" .$i]="";
			$_SESSION["T03_Koza_Start" .$i]="";
			$_SESSION["T03_Koza_End" .$i]="";
			$_SESSION["T03_Koza_Kigou" .$i]="";
			$_SESSION["T03_Koza_Bango" .$i]="";
			$_SESSION["T03_Koza_Meigi" .$i]="";
			$_SESSION["T03_Koza_MeigiKana" .$i]="";

			$_SESSION["T03_Koza_TeacherID" .$i]=$data2[$i]['TeacherID'];
			$_SESSION["T03_Koza_KozaSeq" .$i]=$data2[$i]['KozaSeq'];
			$_SESSION["T03_Koza_Start" .$i]=$data2[$i]['Start'];
			$_SESSION["T03_Koza_End" .$i]=$data2[$i]['End'];
			$_SESSION["T03_Koza_Kigou" .$i]=$data2[$i]['Kigou'];
			$_SESSION["T03_Koza_Bango" .$i]=$data2[$i]['Bango'];
			$_SESSION["T03_Koza_Meigi" .$i]=$data2[$i]['Meigi'];
			$_SESSION["T03_Koza_MeigiKana" .$i]=$data2[$i]['MeigiKana'];

			$i++;
		}
		$_SESSION["T03_Koza_DataCount"] = $i;


		//------------------------生徒情報------------------------------
		// クエリの実行
		$query = "SELECT * FROM T_Tanto WHERE  TeacherID = '" . $_SESSION["Kensaku_KEY1"] . "' ORDER BY StudentID ASC,StartDay DESC ";
		$result = $mysqli->query($query);

		print($query ."<BR>");

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
			$Seq = $data[$i]['Seq'];
			$db_Name1 = "";
			$db_Name2 = "";

			//------生徒名取得------
			$query2 = "SELECT * FROM S_AtenaInfo WHERE  StudentID = '" . $StudentID . "' AND Seq = " . $Seq ;
			$result2 = $mysqli->query($query2);

			print($query2 ."<BR>");

			if (!$result2) {
				print('クエリーが失敗しました。' . $mysqli->error);
				$mysqli->close();
				exit();
			}

			while ($row = $result2->fetch_assoc()) {
				$db_Name1 = $row['Name1'];
				$db_Name2 = $row['Name2'];
				$db_Gread = $row['gread'];
			}

			$data[$i]['StudentName'] = $db_Name1 . "　(" . $db_Name2 . ")";
			$data[$i]['Gread'] = $db_Gread;

			//------終了分判定------
			if(is_null($data[$i]['EndDay'])){
				$data[$i]['HyojiFlg'] = 1;
			}else{
				if ((strtotime($data[$i]['StartDay']) <= strtotime($_SESSION["Today"])) && (strtotime($_SESSION["Today"]) <= strtotime($data[$i]['EndDay']))) {
					$data[$i]['HyojiFlg'] = 1;
				} else {
					$data[$i]['HyojiFlg'] = 0;
				}
			}

			$_SESSION["T03_StudentID" .$i] = $data[$i]['StudentID'];
			$_SESSION["T03_StudentName" .$i] = $data[$i]['StudentName'];
			$_SESSION["T03_Seq" .$i] = $data[$i]['Seq'];
			$_SESSION["T03_Gread" .$i] = $data[$i]['Gread'];
			$_SESSION["T03_StartDay" .$i] = $data[$i]['StartDay'];
			$_SESSION["T03_EndDay" .$i] = $data[$i]['EndDay'];
//			$_SESSION["T03_Address" .$i] = ;

			$i++;
		}
		$_SESSION["T03_Student_DateCount"] = count($data);	//データ件数

print("T03_Student_DateCount" . $_SESSION["T03_Student_DateCount"] . "<BR>");

		//------------------------折衝情報取得------------------------------
		$query4 = "Select * ";
		$query4 = $query4 . " FROM TS_SeshoInfo ";
		$query4 = $query4 . " Where TeacherID='" . $_SESSION["Kensaku_KEY1"] . "'";

print($query4);

		$result4 = $mysqli->query($query4);

		if (!$result4) {
			print('クエリーが失敗しました。' . $mysqli->error);
			$mysqli->close();
			exit();
		}

		$data4 = array();
		$i = 0;
		while($arr_item = $result4->fetch_assoc()){

			//レコード内の各フィールド名と値を順次参照
			foreach($arr_item as $key => $value){
				//フィールド名と値を表示
				$data4[$i][$key] = $value;
			}
			$_SESSION["T04_Sessho_TeacherID" .$i]="";
			$_SESSION["T04_Sessho_StudentID" .$i]="";
			$_SESSION["T04_Sessho_SesshoDay" .$i]="";
			$_SESSION["T04_Sessho_SesshoSeq" .$i]="";
			$_SESSION["T04_Sessho_Kubun" .$i]="";
			$_SESSION["T04_Sessho_Tanto" .$i]="";
			$_SESSION["T04_Sessho_Houho" .$i]="";
			$_SESSION["T04_Sessho_Aite" .$i]="";
			$_SESSION["T04_Sessho_Naiyo" .$i]="";

			$_SESSION["T04_Sessho_TeacherID" .$i]=$data4[$i]['TeacherID'];
			$_SESSION["T04_Sessho_StudentID" .$i]=$data4[$i]['StudentID'];
			$_SESSION["T04_Sessho_SesshoDay" .$i]=$data4[$i]['SesshoDay'];
			$_SESSION["T04_Sessho_SesshoSeq" .$i]=$data4[$i]['SesshoSeq'];
			$_SESSION["T04_Sessho_Kubun" .$i]=$data4[$i]['Kubun'];
			$_SESSION["T04_Sessho_Tanto" .$i]=$data4[$i]['Tanto'];
			$_SESSION["T04_Sessho_Houho" .$i]=$data4[$i]['Houho'];
			$_SESSION["T04_Sessho_Aite" .$i]=$data4[$i]['Aite'];
			$_SESSION["T04_Sessho_Naiyo" .$i]=$data4[$i]['Naiyo'];

			$i++;
		}
		$_SESSION["T04_Sessho_DataCount"] = $i;

	 	// データベースの切断
		$mysqli->close();

}
//-----------------------------------------------------------
//	セーブ処理
//-----------------------------------------------------------
Function SaveShori(){



}

?>

<script type="text/javascript" src="utility.js"></script>

<CENTER>
<body onload="<?php if($_SESSION["K_kensaku_MODE"] == "KENT") {?> document.form1.K_TeacherID.focus(); <?php }else{ ?> document.form1.K_StudentID.focus(); <?php } ?>">

<form name="form1" method="post" action="T03_Kanri.php">
	<div id="header0" class="item">
		<BR>
		<table border="0" width="100%"  bgcolor="<?php echo HEADER_COLOR ?>">
			<tr align="center">
				<td align="center">
					<h2>教師検索画面</h2>
				</td>
			</tr>
		</table>
	</div>
	<table border="0" width="100%">
		<tr align="Right">
			<td align="Right">
				[ログイン]　<?php echo $_SESSION["LoginTName1"] ?>
			</td>
		</tr>
		<tr align="Right">
			<td align="right">
				<input type="hidden" id="submitter" name="submitter" value="" />
				<input type="button" id="modoru" name="modoru" onClick="sbmfnc(this,<?php echo $_SESSION["TourokuFlg"] ?>)" style="cursor: pointer" value="戻る" />
				<input type="button" id="logout" name="logout" onClick="sbmfnc(this,<?php echo $_SESSION["TourokuFlg"] ?>)" style="cursor: pointer" value="ログアウト" />
			</td>
		</tr>
	</table>
	<BR>
	<table border="0" width="100%">
		<font size="5" color="#ff0000"><?php echo $EMSG ?></font>
	</table>
	<table border="0">
		<tr align="center">
			<td align="left" width="400">
				<input type="button" id="print" name="print"  onClick="sbmfnc(this,'')" style="cursor: pointer" value="印刷画面" />
				<input type="button" id="sessho" name="sessho" onClick="sbmfnc(this,'')" style="cursor: pointer" value="折　　衝" />
			</td>
			<td align="right" width="400">
				<input type="button" id="print" name="update" onClick="sbmfnc(this,'')" style="cursor: pointer" value="情報更新" />
			</td>
		</tr>
	</table>
<div id="tbl-bdr">
	<table>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">教師ID</td>
			<td align="center" width="50"><?php echo $_SESSION["T03_TeacherID"] ?></td>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">登録日</td>
			<td align="center" width="150"><?php if(is_null($_SESSION["T03_EntryTime"])){ }else{ echo date('Y年n月j日', strtotime($_SESSION["T03_EntryTime"])); } ?></td>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">生年月日</td>
			<td align="center" width="150"><?php if(is_null($_SESSION["T03_BirthDay"])){ }else{ echo date('Y年n月j日', strtotime($_SESSION["T03_BirthDay"])); } ?></td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">教師名</td>
			<td align="left" colspan="3"><?php echo $_SESSION["T03_Name1"]?>　(<?php echo $_SESSION["T03_Name2"]?>)</td>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">年齢・性別</td>
			<td align="left"><?php echo $_SESSION["T03_Old"]?>歳　<?php if($_SESSION["T03_Seibetu"]==0){?>男<?php }else{?>女<?php } ?></td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">住所</td>
			<td align="left" colspan="5"><?php echo $_SESSION["T03_Yubin1"]?>-<?php echo $_SESSION["T03_Yubin2"]?>　<?php echo $_SESSION["T03_Add_ken"]?><?php echo $_SESSION["T03_Add_shi"]?><?php echo $_SESSION["T03_Add_ku"]?><?php echo $_SESSION["T03_Add_cho"]?></td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">メール1</td>
			<td align="left" colspan="3"><?php echo $_SESSION["T03_Mail1"]?></td>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">電話1</td>
			<td align="left" colspan="3"><?php echo $_SESSION["T03_Tel1"]?></td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">メール2</td>
			<td align="left" colspan="3"><?php echo $_SESSION["T03_Mail2"]?></td>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">電話2</td>
			<td align="left" colspan="3"><?php echo $_SESSION["T03_Tel2"]?></td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">メール3</td>
			<td align="left" colspan="3"></td>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">電話3</td>
			<td align="left" colspan="3"><?php echo $_SESSION["T03_Tel3"]?></td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">卒業大学</td>
			<td align="left" colspan="5"><?php echo $_SESSION["T03_Uni1"]?>　<?php echo $_SESSION["T03_Dept1"]?>　<?php echo $_SESSION["T03_Gradu1"]?>年卒</td>
		</tr>
		<?php if($_SESSION["KyokaFlg"] == "0"){ ?>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="7"><input type="button" id="kyoka" name="kyoka" onClick="sbmfnc(this,'')" style="cursor: pointer" value="-教科" /></td>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">小学生</td>
				<td align="left" colspan="4">
					<input type="checkbox" name="T03_Sub1_1" value="<?php echo $_SESSION["T03_Sub1_1"]?>" <?php if($_SESSION["T03_Sub1_1"] == 1){?> CHECKED <?php } ?> disabled>国
					<input type="checkbox" name="T03_Sub1_2" value="<?php echo $_SESSION["T03_Sub1_2"]?>" <?php if($_SESSION["T03_Sub1_2"] == 1){?> CHECKED <?php } ?> disabled>算
					<input type="checkbox" name="T03_Sub1_3" value="<?php echo $_SESSION["T03_Sub1_3"]?>" <?php if($_SESSION["T03_Sub1_3"] == 1){?> CHECKED <?php } ?> disabled>理
					<input type="checkbox" name="T03_Sub1_4" value="<?php echo $_SESSION["T03_Sub1_4"]?>" <?php if($_SESSION["T03_Sub1_4"] == 1){?> CHECKED <?php } ?> disabled>社
					<input type="checkbox" name="T03_Sub1_5" value="<?php echo $_SESSION["T03_Sub1_5"]?>" <?php if($_SESSION["T03_Sub1_5"] == 1){?> CHECKED <?php } ?> disabled>英
					<input type="checkbox" name="T03_Sub1_6" value="<?php echo $_SESSION["T03_Sub1_6"]?>" <?php if($_SESSION["T03_Sub1_6"] == 1){?> CHECKED <?php } ?> disabled>私立受験
				</td>
			</tr>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">中学生</td>
				<td align="left" colspan="4">
					<input type="checkbox" name="T03_Sub2_1" value="<?php echo $_SESSION["T03_Sub2_1"]?>" <?php if($_SESSION["T03_Sub2_1"] == 1){?> CHECKED <?php } ?> disabled>国
					<input type="checkbox" name="T03_Sub2_2" value="<?php echo $_SESSION["T03_Sub2_2"]?>" <?php if($_SESSION["T03_Sub2_2"] == 1){?> CHECKED <?php } ?> disabled>算
					<input type="checkbox" name="T03_Sub2_3" value="<?php echo $_SESSION["T03_Sub2_3"]?>" <?php if($_SESSION["T03_Sub2_3"] == 1){?> CHECKED <?php } ?> disabled>理
					<input type="checkbox" name="T03_Sub2_4" value="<?php echo $_SESSION["T03_Sub2_4"]?>" <?php if($_SESSION["T03_Sub2_4"] == 1){?> CHECKED <?php } ?> disabled>社
					<input type="checkbox" name="T03_Sub2_5" value="<?php echo $_SESSION["T03_Sub2_5"]?>" <?php if($_SESSION["T03_Sub2_5"] == 1){?> CHECKED <?php } ?> disabled>英
					<input type="checkbox" name="T03_Sub2_6" value="<?php echo $_SESSION["T03_Sub2_6"]?>" <?php if($_SESSION["T03_Sub2_6"] == 1){?> CHECKED <?php } ?> disabled>高校受験
				</td>
			</tr>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0"rowspan="4">高校生</td>
				<td align="left" colspan="4" height="30">
					<input type="checkbox" name="T03_Sub3_1" value="<?php echo $_SESSION["T03_Sub3_1"]?>" <?php if($_SESSION["T03_Sub3_1"] == 1){?> CHECKED <?php } ?> disabled>現文
					<input type="checkbox" name="T03_Sub3_2" value="<?php echo $_SESSION["T03_Sub3_2"]?>" <?php if($_SESSION["T03_Sub3_2"] == 1){?> CHECKED <?php } ?> disabled>古文
					<input type="checkbox" name="T03_Sub3_3" value="<?php echo $_SESSION["T03_Sub3_3"]?>" <?php if($_SESSION["T03_Sub3_3"] == 1){?> CHECKED <?php } ?> disabled>漢文
					<input type="checkbox" name="T03_Sub3_4" value="<?php echo $_SESSION["T03_Sub3_4"]?>" <?php if($_SESSION["T03_Sub3_4"] == 1){?> CHECKED <?php } ?> disabled>小論文
					<input type="checkbox" name="T03_Sub3_5" value="<?php echo $_SESSION["T03_Sub3_5"]?>" <?php if($_SESSION["T03_Sub3_5"] == 1){?> CHECKED <?php } ?> disabled>英語
					<input type="checkbox" name="T03_Sub3_6" value="<?php echo $_SESSION["T03_Sub3_6"]?>" <?php if($_SESSION["T03_Sub3_6"] == 1){?> CHECKED <?php } ?> disabled>大学受験

				</td>
			</tr>
			<tr>
				<td align="left" colspan="4" height="30">
					<input type="checkbox" name="T03_Sub3_7" value="<?php echo $_SESSION["T03_Sub3_7"]?>" <?php if($_SESSION["T03_Sub3_7"] == 1){?> CHECKED <?php } ?> disabled>数Ⅰ
					<input type="checkbox" name="T03_Sub3_8" value="<?php echo $_SESSION["T03_Sub3_8"]?>" <?php if($_SESSION["T03_Sub3_8"] == 1){?> CHECKED <?php } ?> disabled>数Ａ
					<input type="checkbox" name="T03_Sub3_9" value="<?php echo $_SESSION["T03_Sub3_9"]?>" <?php if($_SESSION["T03_Sub3_9"] == 1){?> CHECKED <?php } ?> disabled>数Ⅱ
					<input type="checkbox" name="T03_Sub3_10" value="<?php echo $_SESSION["T03_Sub3_10"]?>" <?php if($_SESSION["T03_Sub3_10"] == 1){?> CHECKED <?php } ?> disabled>数Ｂ
					<input type="checkbox" name="T03_Sub3_11" value="<?php echo $_SESSION["T03_Sub3_11"]?>" <?php if($_SESSION["T03_Sub3_11"] == 1){?> CHECKED <?php } ?> disabled>数Ⅲ
					<input type="checkbox" name="T03_Sub3_12" value="<?php echo $_SESSION["T03_Sub3_12"]?>" <?php if($_SESSION["T03_Sub3_12"] == 1){?> CHECKED <?php } ?> disabled>数Ｃ
				</td>
			</tr>
			<tr>
				<td align="left" colspan="4" height="30">
					<input type="checkbox" name="T03_Sub3_13" value="<?php echo $_SESSION["T03_Sub3_13"]?>" <?php if($_SESSION["T03_Sub3_13"] == 1){?> CHECKED <?php } ?> disabled>物理
					<input type="checkbox" name="T03_Sub3_14" value="<?php echo $_SESSION["T03_Sub3_14"]?>" <?php if($_SESSION["T03_Sub3_14"] == 1){?> CHECKED <?php } ?> disabled>化学
					<input type="checkbox" name="T03_Sub3_15" value="<?php echo $_SESSION["T03_Sub3_15"]?>" <?php if($_SESSION["T03_Sub3_15"] == 1){?> CHECKED <?php } ?> disabled>生物
					<input type="checkbox" name="T03_Sub3_16" value="<?php echo $_SESSION["T03_Sub3_16"]?>" <?php if($_SESSION["T03_Sub3_16"] == 1){?> CHECKED <?php } ?> disabled>地学
				</td>
			</tr>
			<tr>
				<td align="left" colspan="4" height="30">
					<input type="checkbox" name="T03_Sub3_17" value="<?php echo $_SESSION["T03_Sub3_17"]?>" <?php if($_SESSION["T03_Sub3_17"] == 1){?> CHECKED <?php } ?> disabled>日本史
					<input type="checkbox" name="T03_Sub3_18" value="<?php echo $_SESSION["T03_Sub3_18"]?>" <?php if($_SESSION["T03_Sub3_18"] == 1){?> CHECKED <?php } ?> disabled>世界史
					<input type="checkbox" name="T03_Sub3_19" value="<?php echo $_SESSION["T03_Sub3_19"]?>" <?php if($_SESSION["T03_Sub3_19"] == 1){?> CHECKED <?php } ?> disabled>政経
					<input type="checkbox" name="T03_Sub3_20" value="<?php echo $_SESSION["T03_Sub3_20"]?>" <?php if($_SESSION["T03_Sub3_20"] == 1){?> CHECKED <?php } ?> disabled>地理
				</td>
			</tr>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">その他</td>
				<td align="left" colspan="4">

				</td>
			</tr>
		<?php }else{ ?>
			<tr>
				<td align="left" colspan="6"><input type="button" id="kyoka2" name="kyoka2" onClick="sbmfnc(this,'')" style="cursor: pointer" value="+教科" /></td>
			</tr>
		<?php } ?>

		<?php if($_SESSION["ShikakuFlg"] == "0"){ ?>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="3"><input type="button" id="shikaku" name="shikaku" onClick="sbmfnc(this,'')" style="cursor: pointer" value="-資格" /></td>
				<td align="left" colspan="5" height="30"><?php echo $_SESSION["T03_License1"]?>　</td>
			</tr>
			<tr>
				<td align="left" colspan="5" height="30"><?php echo $_SESSION["T03_License2"]?>　</td>
			</tr>
			<tr>
				<td align="left" colspan="5" height="30"><?php echo $_SESSION["T03_License3"]?>　</td>
			</tr>
		<?php }else{ ?>
			<tr>
				<td align="left" colspan="6"><input type="button" id="shikaku2" name="shikaku2" onClick="sbmfnc(this,'')" style="cursor: pointer" value="+資格" /></td>
			</tr>
		<?php } ?>

		<?php if($_SESSION["KeikenFlg"] == "0"){ ?>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="2"><input type="button" id="keiken" name="keiken" onClick="sbmfnc(this,'')" style="cursor: pointer" value="-経験" /></td>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">出身中学</td>
				<td align="left" colspan="2"><?php echo $_SESSION["T03_Gra_Hight"]?>　</td>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">教職課程</td>
				<td align="left"><?php echo $_SESSION["T03_Exp_Kyou"]?>　</td>
			</tr>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">出身高校</td>
				<td align="left" colspan="2"><?php echo $_SESSION["T03_Gra_Junior"]?>　</td>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">中学受験</td>
				<td align="left"><?php echo $_SESSION["T03_Exp_Juken"]?>　</td>
			</tr>
		<?php }else{ ?>
			<tr>
				<td align="left" colspan="6"><input type="button" id="keiken2" name="keiken2" onClick="sbmfnc(this,'')" style="cursor: pointer" value="+経験" /></td>
			</tr>
		<?php } ?>

		<?php if($_SESSION["SonotaFlg"] == "0"){ ?>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="3"><input type="button" id="sonota" name="sonota" onClick="sbmfnc(this,'')" style="cursor: pointer" value="-その他" /></td>
				<td align="left" colspan="5" height="30"><?php echo $_SESSION["T03_Other1"]?>　</td>
			</tr>
			<tr>
				<td align="left" colspan="5" height="30"><?php echo $_SESSION["T03_Other2"]?>　</td>
			</tr>
			<tr>
				<td align="left" colspan="5" height="30"><?php echo $_SESSION["T03_Other3"]?>　</td>
			</tr>
		<?php }else{ ?>
			<tr>
				<td align="left" colspan="6"><input type="button" id="sonota2" name="sonota2" onClick="sbmfnc(this,'')" style="cursor: pointer" value="+その他" /></td>
			</tr>
		<?php } ?>

		<?php if($_SESSION["KozaFlg"] == "0"){ ?>
			<?php $Row = $_SESSION["T03_Koza_DataCount"]*2;?>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="<?php echo $Row?>"><input type="button" id="Koza" name="Koza" onClick="sbmfnc(this,'')" style="cursor: pointer" value="-口座情報" /></td>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">記号番号</td>
				<td align="left" colspan="2"><?php echo $_SESSION["T03_Koza_Kigou0"]?>-<?php echo $_SESSION["T03_Koza_Bango0"]?></td>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">開始日</td>
				<td align="left"><?php if(is_null($_SESSION["T03_Koza_Start0"])){ }else{ echo date('Y年n月j日', strtotime($_SESSION["T03_Koza_Start0"])); } ?>　</td>
			</tr>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">名義人</td>
				<td align="left" colspan="2"><?php echo $_SESSION["T03_Koza_Meigi0"]?>　(<?php echo $_SESSION["T03_Koza_MeigiKana0"]?>)</td>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">終了日</td>
				<td align="left"><?php if(is_null($_SESSION["T03_Koza_End0"])){ }else{ echo date('Y年n月j日', strtotime($_SESSION["T03_Koza_End0"])); } ?>　</td>
			</tr>
			<?php for($m=1; $m<$_SESSION["T03_Koza_DataCount"]; $m++){ ?>
				<tr>
					<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">記号番号</td>
					<td align="left" colspan="2"><?php echo $_SESSION["T03_Koza_Kigou" .$m]?>-<?php echo $_SESSION["T03_Koza_Bango" .$m]?></td>
					<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">開始日</td>
					<td align="left"><?php if(is_null($_SESSION["T03_Koza_Start" .$m])){ }else{ echo date('Y年n月j日', strtotime($_SESSION["T03_Koza_Start" .$m])); } ?>　</td>
				</tr>
				<tr>
					<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">名義人</td>
					<td align="left" colspan="2"><?php echo $_SESSION["T03_Koza_Meigi" .$m]?>　(<?php echo $_SESSION["T03_Koza_MeigiKana" .$m]?>)</td>
					<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">終了日</td>
					<td align="left"><?php if(is_null($_SESSION["T03_Koza_End" .$m])){ }else{ echo date('Y年n月j日', strtotime($_SESSION["T03_Koza_End" .$m])); } ?>　</td>
				</tr>
			<?php } ?>
		<?php }else{ ?>
			<tr>
				<td align="left" colspan="6"><input type="button" id="Koza2" name="Koza2" onClick="sbmfnc(this,'')" style="cursor: pointer" value="+口座情報" /></td>
			</tr>
		<?php } ?>
	</table>
	<BR>
	<table border="0">
		<tr>
			<td align="center" bgcolor="#c0c0c0" rowspan="2"><B>ＮＯ</B></td>
			<td id="midashi_Kanri" width="150" align="center" bgcolor="#c0c0c0">日付</td>
			<td id="midashi_Kanri" width="150" align="center" bgcolor="#c0c0c0">区分</td>
			<td id="midashi_Kanri" width="200" align="center" bgcolor="#c0c0c0">生徒</td>
			<td id="midashi_Kanri" width="150" align="center" bgcolor="#c0c0c0">方法</td>
			<td id="midashi_Kanri" width="150" align="center" bgcolor="#c0c0c0">相手</td>
			<td id="midashi_Kanri" width="150" align="center" bgcolor="#c0c0c0">担当</td>
		</tr>
		<tr>
			<td id="midashi_Kanri" width="450" align="center" bgcolor="#c0c0c0" colspan="6">内容</td>
		</tr>
		<?php for($i=0; $i<$_SESSION["T04_Sessho_DataCount"]; $i++){ ?>
			<?php $idx = $i + 1; ?>
			<tr>
				<td align="center" rowspan="2"><input type="submit" name="No_<?php echo $i ?>" style="cursor: pointer" value="<?php echo $idx ?>" /></td>
				<td width="150" align="center" ><?php echo $_SESSION["T04_Sessho_SesshoDay" .$i] ?></td>
				<td width="150" align="left" ><?php echo $_SESSION["T04_Sessho_Kubun" .$i] ?></td>
				<td width="200" align="center" ><?php if($_SESSION["T04_Sessho_StudentID" .$i]!=0){ ?><?php echo $_SESSION["T04_Sessho_StudentID" .$i] ?><?php } ?></td>
				<td width="150" align="center" ><?php echo $_SESSION["T04_Sessho_Houho" .$i] ?></td>
				<td width="150" align="center" ><?php echo $_SESSION["T04_Sessho_Aite" .$i] ?></td>
				<td width="150" align="center" ><?php echo $_SESSION["T04_Sessho_Tanto" .$i] ?></td>
			</tr>
			<tr>
				<td width="450" align="left" colspan="6"><?php echo $_SESSION["T04_Sessho_Naiyo" .$i] ?></td>
			</tr>
		<?php } ?>
	</table>
	<BR>
	<table border="0">
		<tr>
			<td align="center" bgcolor="#c0c0c0" rowspan="2"><B>ＮＯ</B></td>
			<td id="midashi_Kanri" width="100" align="center" bgcolor="#c0c0c0">生徒ＩＤ</td>
			<td id="midashi_Kanri" width="400" align="center" bgcolor="#c0c0c0">生徒名</td>
			<td id="midashi_Kanri" width="100" align="center" bgcolor="#c0c0c0">学年</td>
			<td id="midashi_Kanri" width="150" align="center" bgcolor="#c0c0c0">開始日</td>
		</tr>
		<tr>
			<td id="midashi_Kanri" width="450" align="center" bgcolor="#c0c0c0" colspan="3">住所</td>
			<td id="midashi_Kanri" width="150" align="center" bgcolor="#c0c0c0">終了日</td>
		</tr>
		<?php for($i=0; $i<$_SESSION["T03_Student_DateCount"]; $i++){ ?>
			<?php $idx = $i + 1; ?>
			<tr>
				<td align="center" rowspan="2"><input type="submit" name="No_<?php echo $i ?>" style="cursor: pointer" value="<?php echo $idx ?>" /></td>
				<td width="100" align="center" ><?php echo $_SESSION["T03_StudentID" .$i] ?></td>
				<td width="400" align="left" ><?php echo $_SESSION["T03_StudentName" .$i] ?></td>
				<td width="100" align="center" ><?php echo $_SESSION["T03_Gread" .$i] ?></td>
				<td width="150" align="center" ><?php if(is_null($_SESSION["T03_StartDay" .$i])){ }else{ echo date('Y年n月j日', strtotime($_SESSION["T03_StartDay" .$i])); }?></td>
			</tr>
			<tr>
				<td width="450" align="center" colspan="3">　　</td>
				<td width="150" align="center" ><?php if(is_null($_SESSION["T03_EndDay" .$i])){ }else{ echo date('Y年n月j日', strtotime($_SESSION["T03_EndDay" .$i])); }?></td>
			</tr>
		<?php } ?>
	</table>
</div>
</form>
</body>
</CENTER>
</html>