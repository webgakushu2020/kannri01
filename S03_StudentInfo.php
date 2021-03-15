<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN"
	"http://www.w3.org/TR/html4/frameset.dtd">

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="Header.css">
	<title>生徒管理画面</title>
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
		if($_POST['submitter']=="modorushori"){
		 	ModoruShori($_SESSION["S03_kensaku_RPID"]);
			exit;
		}
	}

	if(isset($_POST['submitter'])){
		switch ($_POST['submitter']){
			case 'Koza':
				$_SESSION["ShoriID"]="KOZA";
				break;
			case 'Koza2':
				$_SESSION["ShoriID"]="KOZA2";
				break;
			case 'TorokuJyoho':
				$_SESSION["ShoriID"]="TOROKU";
				break;
			case 'TorokuJyoho2':
				$_SESSION["ShoriID"]="TOROKU2";
				break;
			case 'KibouJyoho':
				$_SESSION["ShoriID"]="KIBOU";
				break;
			case 'KibouJyoho2':
				$_SESSION["ShoriID"]="KIBOU2";
				break;
			case 'TokkiJiko':
				$_SESSION["ShoriID"]="TOKKI";
				break;
			case 'TokkiJiko2':
				$_SESSION["ShoriID"]="TOKKI2";
				break;
			case 'updateshori':
				$_SESSION["ShoriID"]="UPDATESHORI";
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
			$_SESSION["S03_kensaku_RPID"] = $_GET['RPID'];
		      	//print($_SESSION["S03_kensaku_RPID"]);
		}
		if(isset($_GET['MODE'])) {
			if($_GET['MODE'] != "Modoru"){
				$_SESSION["S03_kensaku_MODE"] = $_GET['MODE'];
				$_SESSION["ShoriID"]=$_GET['MODE'];
			      	//print($_SESSION["ShoriID"] . "<BR>");
			}
			if($_SESSION["S03_kensaku_RPID"] == "VIEW"){
				$_SESSION["DateFlg"] = 0;
				$_SESSION["DateCount"] = 0;
				$_SESSION["TeacherID"] = $_SESSION["LoginTeacherID"];
				$_SESSION["TName1"] = $_SESSION["LoginTName1"];
				$_SESSION["TName2"] = $_SESSION["LoginTName2"];
			}

		}
		if(isset($_GET['KEY1'])) {
			$_SESSION["S03_Kensaku_KEY1"] = $_GET['KEY1'];
		}
		if(isset($_GET['SEQ'])) {
			$_SESSION["S03_Kensaku_Seq"] = $_GET['SEQ'];
		}

		switch ($_SESSION["ShoriID"]){
			case 'UPD':
			case 'VIEW':
				SessionClear();
				GetData();
				$CodeData = array();
				$CodeData = GetCodeData("希望教師","","",1);
				$_SESSION["18CodeData"]=$CodeData;
				$CodeData = array();
				$CodeData = GetCodeData("小学教科","","",1);
				$_SESSION["05CodeData"]=$CodeData;
				$CodeData = array();
				$CodeData = GetCodeData("中学教科","","",1);
				$_SESSION["06CodeData"]=$CodeData;
				$CodeData = array();
				$CodeData = GetCodeData("高校教科","","",1);
				$_SESSION["07CodeData"]=$CodeData;
				$CodeData = array();
				$CodeData = GetCodeData("その他教科","","",1);
				$_SESSION["08CodeData"]=$CodeData;
				$CodeData = array();
				$CodeData = GetCodeData("続柄","","",1);
				$_SESSION["12CodeData"]=$CodeData;
				$CodeData = array();
				$CodeData = GetCodeData("電話メール区分","","",1);
				$_SESSION["14CodeData"]=$CodeData;
				$CodeData = array();
				$CodeData = GetCodeData("連絡時間帯","","",1);
				$_SESSION["15CodeData"]=$CodeData;
				$CodeData = array();
				$CodeData = GetCodeData("契約コース","","",1);
				$_SESSION["19CodeData"]=$CodeData;
				$CodeData = array();
				$CodeData = GetCodeData("学年","","",1);
				$_SESSION["13CodeData"]=$CodeData;
				$CodeData = array();
				$CodeData = GetCodeData("状態教師","","",1);
				$_SESSION["03CodeData"]=$CodeData;
				$CodeData2 = array();
				$CodeData2 = GetCodeData("折衝方法","","",1);
				$_SESSION["09CodeData"]=$CodeData2;
				$CodeData3 = array();
				$CodeData3 = GetCodeData("折衝相手","","",1);
				$_SESSION["10CodeData"]=$CodeData3;
				$CodeData4 = array();
				$CodeData4 = GetKanriTnato("","");
				$_SESSION["Tanto"]=$CodeData4;
				$CodeData5 = array();
				$CodeData5 = GetCodeData("状態生徒","","",1);
				$_SESSION["04CodeData"]=$CodeData5;

				break;
			case 'KOZA':
				$_SESSION["KozaFlg"]="1";
				break;
			case 'KOZA2':
				$_SESSION["KozaFlg"]="0";
				break;
			case 'TOROKU':
				$_SESSION["TorokuJyohoFlg"]="1";
				break;
			case 'TOROKU2':
				$_SESSION["TorokuJyohoFlg"]="0";
				break;
			case 'KIBOU':
				$_SESSION["KibouFlg"]="1";
				break;
			case 'KIBOU2':
				$_SESSION["KibouFlg"]="0";
				break;
			case 'TOKKI':
				$_SESSION["TokkiFlg"]="1";
				break;
			case 'TOKKI2':
				$_SESSION["TokkiFlg"]="0";
				break;
			case 'UPDATESHORI':
				header("Location:S00_Atena01.php?MODE=UPD&RPID=S03_StudentInfo&KEY1=" . $_SESSION["S03_Kensaku_KEY1"] . "&SEQ=" . $_SESSION["S03_Kensaku_Seq"]);
				break;
		}	
	}
//-----------------------------------------------------------
//	セッション情報クリア
//-----------------------------------------------------------
function SessionClear(){

	$_SESSION["S03_Koza_DataCount"]=1;

	$_SESSION["KozaFlg"]="0";
	$_SESSION["TorokuJyohoFlg"]="0";
	$_SESSION["KibouFlg"]="0";
	$_SESSION["TokkiFlg"]="0";

	$_SESSION["S03_StudentID"]="";
	$_SESSION["S03_EntryTime"]="";
	$_SESSION["S03_BirthDay"]="";
	$_SESSION["S03_Name1"]="";
	$_SESSION["S03_Name2"]="";
	$_SESSION["S03_Old2"]= "";
	$_SESSION["S03_old"]= "";
	$_SESSION["S03_Seibetu"]="";
	$_SESSION["S03_gread"]="";
	$_SESSION["S03_SchoolName"]="";

	$_SESSION["S03_Yubin1_1"]="";
	$_SESSION["S03_Yubin2_1"]="";
	$_SESSION["S03_Add_ken1"]="";
	$_SESSION["S03_Add_shi1"]="";
	$_SESSION["S03_Add_ku1"]="";
	$_SESSION["S03_Add_cho1"]="";

	$_SESSION["S03_Tel_Kubun1"]="";
	$_SESSION["S03_Tel_Kubun2"]="";
	$_SESSION["S03_Tel_Kubun3"]="";
	$_SESSION["S03_Tel1"]="";
	$_SESSION["S03_Tel2"]="";
	$_SESSION["S03_Tel3"]="";
	$_SESSION["S03_Mail_Kubun1"]="";
	$_SESSION["S03_Mail_Kubun2"]="";
	$_SESSION["S03_Mail_Kubun3"]="";
	$_SESSION["S03_Mail1"]="";
	$_SESSION["S03_Mail2"]="";
	$_SESSION["S03_Mail3"]="";

	$_SESSION["S03_ContactTime0"]="";
	$_SESSION["S03_ContactTime1"]="";
	$_SESSION["S03_ContactTime2"]="";
	$_SESSION["S03_ContactTime3"]="";
	$_SESSION["S03_ContactTime4"]="";

	$_SESSION["S03_Genjyo1"]="";
	$_SESSION["S03_Genjyo2"]="";
	$_SESSION["S03_Genjyo3"]="";
	$_SESSION["S03_Genjyo4"]="";
	$_SESSION["S03_Genjyo5"]="";
	$_SESSION["S03_Genjyo6"]="";
	$_SESSION["S03_Genjyo7"]="";
	$_SESSION["S03_Genjyo8"]="";
	$_SESSION["S03_Sonota_Naiyo"]="";

	$_SESSION["S03_Soudan"]="";

	$_SESSION["S03_Kyoka1"]="";
	$_SESSION["S03_Kyoka2"]="";
	$_SESSION["S03_Kyoka3"]="";
	$_SESSION["S03_Kyoka4"]="";
	$_SESSION["S03_Kyoka5"]="";
	$_SESSION["S03_ShidoTime"]="";
	$_SESSION["S03_ShidoKibou"]="";
	$_SESSION["S03_Youbi1"]="";
	$_SESSION["S03_Youbi2"]="";
	$_SESSION["S03_Youbi3"]="";
	$_SESSION["S03_Youbi4"]="";
	$_SESSION["S03_Youbi5"]="";
	$_SESSION["S03_Youbi6"]="";
	$_SESSION["S03_Youbi7"]="";
	$_SESSION["S03_KyoushiKibou0"]="";
	$_SESSION["S03_KyoushiKibou1"]="";
	$_SESSION["S03_KyoushiKibou2"]="";
	$_SESSION["S03_KyoushiKibou3"]="";
	$_SESSION["S03_KyoushiKibou4"]="";
	$_SESSION["S03_KyoushiKibou5"]="";
	$_SESSION["S03_KyoushiKibou6"]="";
	$_SESSION["S03_KyoushiKibou7"]="";
	$_SESSION["S03_KyoushiKibouNaiyo"]="";
	$_SESSION["S03_Notice1"]="";
	$_SESSION["S03_Notice2"]="";
	$_SESSION["S03_Notice3"]="";
	$_SESSION["S03_Notice4"]="";
	$_SESSION["S03_Notice5"]="";

	$_SESSION["S03_notice1"]="";
	$_SESSION["S03_notice2"]="";
	$_SESSION["S03_notice3"]="";

	$_SESSION["S03_Hogosha1"]="";
	$_SESSION["S03_Hogo_Zoku1"]="";
	$_SESSION["S03_Hogosha2"]="";
	$_SESSION["S03_Hogo_Zoku2"]="";
	$_SESSION["S03_Yubin1_2"]="";
	$_SESSION["S03_Yubin2_2"]="";
	$_SESSION["S03_Add_ken2"]="";
	$_SESSION["S03_Add_shi2"]="";
	$_SESSION["S03_Add_ku2"]="";
	$_SESSION["S03_Add_cho2"]="";
	$_SESSION["S03_Kotu_rosen"]="";
	$_SESSION["S03_Kotu_Eki"]="";
	$_SESSION["S03_Kotu_Toho"]="";
	$_SESSION["S03_CarTF"]="";
	$_SESSION["S03_Kotu_Sonota"]="";
	$_SESSION["S03_Koza_Kigou0"]="";
	$_SESSION["S03_Koza_Bango0"]="";
	$_SESSION["S03_Koza_Start0"]="";
	$_SESSION["S03_Koza_Meigi0"]="";
	$_SESSION["S03_Koza_MeigiKana0"]="";
	$_SESSION["S03_Koza_End0"]="";
	$_SESSION["S03_Koza_Biko0"]="";

	$_SESSION["Kyodai1"]="";
	$_SESSION["Kyo_Zoku1"]="";
	$_SESSION["Kyodai2"]="";
	$_SESSION["Kyo_Zoku2"]="";
	$_SESSION["Kyodai3"]="";
	$_SESSION["Kyo_Zoku3"]="";

	for($m=1; $m<=10; $m++){
		$_SESSION["S00_Sub1_" . $m]=0;
	}
	for($m=1; $m<=10; $m++){
		$_SESSION["S00_Sub2_" . $m]=0;
	}
	for($m=1; $m<=25; $m++){
		$_SESSION["S00_Sub3_" . $m]=0;
	}
	for($m=1; $m<=5; $m++){
		$_SESSION["S00_Sub4_" . $m]=0;
	}

}
//-----------------------------------------------------------
//	データ取得
//-----------------------------------------------------------
Function GetData(){
$query2 = "";
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
		$query = $query . " FROM S_AtenaInfo as a inner join";
		$query = $query . " S_KihonInfo as b on";
		$query = $query . " a.StudentID=b.StudentID";
		$query = $query . " and a.Seq=b.AtenaSeq";
		$query = $query . " inner join S_TourokuInfo as c on";
		$query = $query . " a.StudentID=c.StudentID";
		$query = $query . " and a.Seq=c.AtenaSeq";

		if($_SESSION["S03_Kensaku_KEY1"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where a.StudentID='" . $_SESSION["S03_Kensaku_KEY1"]. "'";
			}else{
				$query2 = $query2 . " And a.StudentID='" . $_SESSION["S03_Kensaku_KEY1"]. "'";
			}
		}
		if($_SESSION["S03_Kensaku_Seq"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where a.Seq='" . $_SESSION["S03_Kensaku_Seq"]. "'";
			}else{
				$query2 = $query2 . " And a.Seq='" . $_SESSION["S03_Kensaku_Seq"]. "'";
			}
		}

		$query = $query . $query2;
//print($query);
		$result = $mysqli->query($query);

		if (!$result) {
			print('クエリーが失敗しました。①' . $mysqli->error);
			$mysqli->close();
			exit();
		}
		
		$data = array();
		while($arr_item = $result->fetch_assoc()){

			//レコード内の各フィールド名と値を順次参照
			foreach($arr_item as $key => $value){
				//フィールド名と値を表示
				$_SESSION["S03_" . $key] = $value;
			}

			if(is_null($_SESSION["S03_BirthDay"])){
				$_SESSION["S03_Old2"]="";
			}else{
				$_SESSION["S03_Old2"]=floor ((date('Ymd') - date('Ymd', strtotime($_SESSION['S03_BirthDay'])))/10000);
			}
		}
//print($_SESSION["S03_Seibetu"] . "<BR>");

//print($_SESSION["2"] . "<BR>");
		//------------------------口座情報------------------------------
		$query3 = "Select * ";
		$query3 = $query3 . " FROM S_KozaInfo ";
		$query3 = $query3 . " Where StudentID='" . $_SESSION["S03_Kensaku_KEY1"] . "'";
		$query3 = $query3 . " order by KozaSeq Desc";

//print($query3);

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
			$_SESSION["S03_Koza_StudentID" .$i]="";
			$_SESSION["S03_Koza_KozaSeq" .$i]="";
			$_SESSION["S03_Koza_Start" .$i]="";
			$_SESSION["S03_Koza_End" .$i]="";
			$_SESSION["S03_Koza_Kigou" .$i]="";
			$_SESSION["S03_Koza_Bango" .$i]="";
			$_SESSION["S03_Koza_Meigi" .$i]="";
			$_SESSION["S03_Koza_MeigiKana" .$i]="";
			$_SESSION["S03_Koza_Biko" .$i]="";

			$_SESSION["S03_Koza_StudentID" .$i]=$data2[$i]['StudentID'];
			$_SESSION["S03_Koza_KozaSeq" .$i]=$data2[$i]['KozaSeq'];
			$_SESSION["S03_Koza_Start" .$i]=$data2[$i]['Start'];
			$_SESSION["S03_Koza_End" .$i]=$data2[$i]['End'];
			$_SESSION["S03_Koza_Kigou" .$i]=$data2[$i]['Kigou'];
			$_SESSION["S03_Koza_Bango" .$i]=$data2[$i]['Bango'];
			$_SESSION["S03_Koza_Meigi" .$i]=$data2[$i]['Meigi'];
			$_SESSION["S03_Koza_MeigiKana" .$i]=$data2[$i]['MeigiKana'];
			$_SESSION["S03_Koza_Biko" .$i]=$data2[$i]['Biko'];

			$i++;
		}
		if($i > 0){
			$_SESSION["S03_Koza_DataCount"] = $i;
		}

//==========================契約データ取得==========================
		$query = "SELECT * FROM T_TantoShosai";
		$query = $query . " WHERE StudentID = '" . $_SESSION["S03_Kensaku_KEY1"] . "'";
		$query = $query . " And AtenaSeq = '" . $_SESSION["S03_Kensaku_Seq"] . "'";
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
				$_SESSION[$key . $i] = $value;
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

			$_SESSION["TeacherName" . $i] = $db_Name1;
			$Mail = $db_Mail1;
			$_SESSION["Mail" . $i] = $Mail;

			$Tel = $db_Tel1;
			$_SESSION["Tel" . $i] = $Tel;
			$_SESSION["SenteiFlg" . $i] = 1;
			$_SESSION["SenteiName" . $i] = "契約";

			//------終了分判定------
			if(is_null($data[$i]['EndDay'])){
				$_SESSION["HyojiFlg" . $i] = 1;
			}else{
				if (strtotime($Today) <= strtotime($data[$i]['EndDay'])) {
					$_SESSION["HyojiFlg" . $i] = 1;
				} else {
					$_SESSION["HyojiFlg" . $i] = 0;
					$_SESSION["SenteiFlg" . $i] = 8;
					$_SESSION["SenteiName" . $i] = "終了";
				}
			}

			//----------------折衝履歴を取得----------------------------
			$query3 = "SELECT b.* FROM T_TantoShosai as a inner join TS_SeshoInfo as b on";
			$query3 = $query3 . " a.TeacherID = b.TeacherID";
			$query3 = $query3 . " and a.StudentID = b.StudentID";
			$query3 = $query3 . " and a.AtenaSeq = b.AtenaSeq";
			$query3 = $query3 . " and a.EndSeq = b.EndSeq";
			$query3 = $query3 . " WHERE a.StudentID = '" . $_SESSION["S03_Kensaku_KEY1"] . "'";
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

			$_SESSION["Naiyo" . $i] = $db_Naiyo;
			$i++;
		}

		//----------------選定中教師選択----------------------------
		// クエリの実行
		$query = "SELECT * FROM S_SenteiInfo WHERE  StudentID = '" . $_SESSION["S03_Kensaku_KEY1"] . "' AND AtenaSeq=" . $_SESSION["S03_Kensaku_Seq"];
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
		

		while($arr_item = $result->fetch_assoc()){

			//レコード内の各フィールド名と値を順次参照
			foreach($arr_item as $key => $value){
				//フィールド名と値を表示
				$data[$i][$key] = $value;
				$_SESSION[$key . $i] = $value;
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

			$_SESSION["TeacherName" . $i] = $db_Name1;
			$Mail = $db_Mail1;
			$_SESSION["Mail" . $i] = $Mail;

			$Tel = $db_Tel1;
			$_SESSION["Tel" . $i] = $Tel;
			$_SESSION["StartDay" . $i] = NULL;
			$_SESSION["EndDay" . $i] = NULL;
			$_SESSION["EndFlg" . $i] = 0;
			$_SESSION["Pay" . $i] = "";
			$_SESSION["KiteiKaisu" . $i] = "";
			$_SESSION["KiteiJikan" . $i] = "";
			$_SESSION["course" . $i] = "";
			if($data[$i]['JyotaiFlg']==0){
				$_SESSION["SenteiFlg" . $i] = 0;
				$_SESSION["SenteiName" . $i] = "選定";
				$_SESSION["HyojiFlg" . $i] = 1;
			}elseif($data[$i]['JyotaiFlg']==9){
				$_SESSION["SenteiFlg" . $i] = 9;
				$_SESSION["SenteiName" . $i] = "選定解除";
				$_SESSION["HyojiFlg" . $i] = 0;
			}
			$_SESSION["Naiyo" . $i] = "";
			$i++;
		}
		$_SESSION["DateCount"] = count($data);	//データ件数


//------------------------折衝情報取得------------------------------
		$query4 = "Select * ";
		$query4 = $query4 . " FROM TS_SeshoInfo ";
		$query4 = $query4 . " WHERE StudentID='" . $_SESSION["StudentID"] . "'";
		$query4 = $query4 . " ORDER BY SesshoDay Desc, SesshoSeq Desc";

//print($query4);

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
			$_SESSION["T04_Sessho_Jyotai" .$i]="";
			$_SESSION["T04_Sessho_JyotaiKubun" .$i]="";
			$_SESSION["T04_Sessho_YakusokuDay" .$i]="";
			$_SESSION["T04_Sessho_Tanto" .$i]="";
			$_SESSION["T04_Sessho_THouho" .$i]="";
			$_SESSION["T04_Sessho_TAite" .$i]="";
			$_SESSION["T04_Sessho_SHouho" .$i]="";
			$_SESSION["T04_Sessho_SAite" .$i]="";
			$_SESSION["T04_Sessho_Naiyo" .$i]="";
			$_SESSION["T04_Sessho_Hikitugi" .$i]="";
			$_SESSION["T04_Sessho_HikitugiKubun" .$i]="";

			$_SESSION["T04_Sessho_TeacherID" .$i]=$data4[$i]['TeacherID'];
			$_SESSION["T04_Sessho_StudentID" .$i]=$data4[$i]['StudentID'];
			$_SESSION["T04_Sessho_AtenaSeq" .$i]=$data4[$i]['AtenaSeq'];
			$_SESSION["T04_Sessho_SesshoDay" .$i]=$data4[$i]['SesshoDay'];
			$_SESSION["T04_Sessho_SesshoSeq" .$i]=$data4[$i]['SesshoSeq'];
			$_SESSION["T04_Sessho_Jyotai" .$i]=$data4[$i]['Jyotai'];
			$_SESSION["T04_Sessho_JyotaiKubun" .$i]=$data4[$i]['JyotaiKubun'];
			$_SESSION["T04_Sessho_YakusokuDay" .$i]=$data4[$i]['YakusokuDay'];
			$_SESSION["T04_Sessho_Tanto" .$i]=$data4[$i]['Tanto'];
			$_SESSION["T04_Sessho_THouho" .$i]=$data4[$i]['THouho'];
			$_SESSION["T04_Sessho_TAite" .$i]=$data4[$i]['TAite'];
			$_SESSION["T04_Sessho_SHouho" .$i]=$data4[$i]['SHouho'];
			$_SESSION["T04_Sessho_SAite" .$i]=$data4[$i]['SAite'];
			$_SESSION["T04_Sessho_Naiyo" .$i]=$data4[$i]['Naiyo'];
			$_SESSION["T04_Sessho_Hikitugi" .$i]=$data4[$i]['Hikitugi'];
			$_SESSION["T04_Sessho_HikitugiKubun" .$i]=$data4[$i]['HikitugiKubun'];

			$i++;
		}
		$_SESSION["T04_Sessho_DataCount"] = $i;

		//------------------------生徒情報------------------------------
		$data = array();
		$i = 0;

		$query = "SELECT * FROM T_TantoShosai WHERE  StudentID = '" . $_SESSION["StudentID"] . "'";
		$query = $query . " AND EndDay is null";
		$query = $query . " ORDER BY TeacherID ASC,StartDay DESC ";
		$result = $mysqli->query($query);

		//print($query ."<BR>");

		if (!$result) {
			print('クエリーが失敗しました。' . $mysqli->error);
			$mysqli->close();
			exit();
		}
		

		while($arr_item = $result->fetch_assoc()){

			//レコード内の各フィールド名と値を順次参照
			foreach($arr_item as $key => $value){
				//フィールド名と値を表示
				$data[$i][$key] = $value;
			}

			$TeacherID = $data[$i]['TeacherID'];
			$Seq = $data[$i]['Seq'];
			$db_Name1 = "";
			$db_Name2 = "";

			//------教師名取得------
			$query2 = "SELECT * FROM T_AtenaInfo WHERE  TeacherID = '" . $TeacherID . "'" ;
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

			$data[$i]['TeacherName'] = $db_Name1 . "　(" . $db_Name2 . ")";

			$_SESSION["T03_TeacherID" .$i] = $data[$i]['TeacherID'];
			$_SESSION["T03_TeacherName" .$i] = $data[$i]['TeacherName'];
			$_SESSION["T03_Seq" .$i] = $data[$i]['Seq'];

			$i++;
		}

		$query = "SELECT * FROM S_SenteiInfo WHERE  StudentID = '" . $_SESSION["StudentID"] . "'";
		$query = $query . " AND JyotaiFlg=0";
		$query = $query . " ORDER BY TeacherID ASC";
		$result = $mysqli->query($query);

		//print($query ."<BR>");

		if (!$result) {
			print('クエリーが失敗しました。' . $mysqli->error);
			$mysqli->close();
			exit();
		}
		

		while($arr_item = $result->fetch_assoc()){

			//レコード内の各フィールド名と値を順次参照
			foreach($arr_item as $key => $value){
				//フィールド名と値を表示
				$data[$i][$key] = $value;
			}

			$TeacherID = $data[$i]['TeacherID'];
			$Seq = $data[$i]['Seq'];
			$db_Name1 = "";
			$db_Name2 = "";

			//------教師名取得------
			$query2 = "SELECT * FROM T_AtenaInfo WHERE  TeacherID = '" . $TeacherID . "'" ;
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

			$data[$i]['TeacherName'] = $db_Name1 . "　(" . $db_Name2 . ")";

			$_SESSION["T03_TeacherID" .$i] = $data[$i]['TeacherID'];
			$_SESSION["T03_TeacherName" .$i] = $data[$i]['TeacherName'];
			$_SESSION["T03_Seq" .$i] = $data[$i]['Seq'];

			$i++;
		}

		$_SESSION["T03_Teacher_DateCount"] = count($data);	//データ件数


	 	// データベースの切断
		$mysqli->close();


}

?>

<script type="text/javascript" src="utility.js"></script>

<CENTER>
<body>

<form name="form1" method="post" action="S03_StudentInfo.php">
<div id="tbl-bdr">
	<table>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">生徒ID</td>
			<td align="center" width="50"><?php echo $_SESSION["S03_StudentID"] ?>-<?php echo $_SESSION["S03_Seq"] ?></td>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">登録日</td>
			<td align="center" width="120"><?php if(is_null($_SESSION["S03_EntryDay"])){ }else{ echo date('Y年n月j日', strtotime($_SESSION["S03_EntryDay"])); } ?></td>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">コース</td>
			<td align="center" width="120" colspan="2"></td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">生徒名</td>
			<td align="left" colspan="4"><?php echo $_SESSION["S03_Name1"]?>　(<?php echo $_SESSION["S03_Name2"]?>)</td>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">生年月日</td>
			<td align="center" width="120"><?php if(is_null($_SESSION["S03_BirthDay"])){ }else{ echo date('Y年n月j日', strtotime($_SESSION["S03_BirthDay"])); } ?><?php echo $_SESSION["S03_Old2"]?>歳</td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">学校名</td>
			<td align="left" colspan="4"><?php echo $_SESSION["S03_SchoolName"]?>　
			<?php 
				for($dataidx=0; $dataidx < $_SESSION["13CodeData"]["13DataCount"]; $dataidx++){
					if($_SESSION["S03_gread"] == $_SESSION["13CodeData"]["13_Eda_" . $dataidx]){
						echo $_SESSION["13CodeData"]["13_CodeName2_" . $dataidx];
					}
				}
			?>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">年齢・性別</td>
			<td align="left"><?php echo $_SESSION["S03_old"]?>　
				<?php 
					if($_SESSION["S03_Seibetu"]=="1"){
						echo "男";
					}elseif($_SESSION["S03_Seibetu"]=="2"){
						echo "女";
					}else{
					} 
				?>
			</td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">保護者①</td>
			<td align="left" colspan="4"><?php echo $_SESSION["S03_Hogosha1"]?>（<?php echo $_SESSION["S03_HogoshaKana1"]?>）</td>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">続柄</td>
			<td align="left">
				<?php 
					for($dataidx=0; $dataidx < $_SESSION["12CodeData"]["12DataCount"]; $dataidx++){
						if($_SESSION["S03_Hogo_Zoku1"] == $_SESSION["12CodeData"]["12_Eda_" . $dataidx]){
							echo $_SESSION["12CodeData"]["12_CodeName1_" . $dataidx];
						}
					}
				?>
			</td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">保護者②</td>
			<td align="left" colspan="4"><?php echo $_SESSION["S03_Hogosha2"]?>（<?php echo $_SESSION["S03_HogoshaKana2"]?>）</td>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">続柄</td>
			<td align="left">
				<?php 
					for($dataidx=0; $dataidx < $_SESSION["12CodeData"]["12DataCount"]; $dataidx++){
						if($_SESSION["S03_Hogo_Zoku2"] == $_SESSION["12CodeData"]["12_Eda_" . $dataidx]){
							echo $_SESSION["12CodeData"]["12_CodeName1_" . $dataidx];
						}
					}
				?>
			</td>
		</tr>

		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">住所</td>
			<td align="left" colspan="6"><?php echo $_SESSION["S03_Yubin1_1"]?>-<?php echo $_SESSION["S03_Yubin1_2"]?>　<?php echo $_SESSION["S03_Add_ken1"]?><?php echo $_SESSION["S03_Add_shi1"]?><?php echo $_SESSION["S03_Add_ku1"]?><?php echo $_SESSION["S03_Add_cho1"]?></td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">その他</td>
			<td align="left" colspan="6"><?php echo $_SESSION["S03_Yubin2_1"]?>-<?php echo $_SESSION["S03_Yubin2_2"]?>　<?php echo $_SESSION["S03_Add_ken2"]?><?php echo $_SESSION["S03_Add_shi2"]?><?php echo $_SESSION["S03_Add_ku2"]?><?php echo $_SESSION["S03_Add_cho2"]?></td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="2">交通</td>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">沿線</td>
			<td align="left" colspan="2"><?php echo $_SESSION["S03_Kotu_rosen"]?></td>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">駅・バス</td>
			<td align="left" colspan="2"><?php echo $_SESSION["S03_Kotu_Eki"]?>　<?php echo $_SESSION["S03_Kotu_Toho"]?>分</td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">車使用</td>
			<td align="left" colspan="2"><?php if($_SESSION["S03_CarTF"]==1){?>可能<?php }elseif(is_null($_SESSION["S03_CarTF"])){ ?><?php }else{ ?>不可<?php } ?></td>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">その他</td>
			<td align="left" colspan="2"><?php echo $_SESSION["S03_Kotu_Sonota"]?></td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">兄弟①</td>
			<td align="left" colspan="6">
				<?php echo $_SESSION["S03_Kyodai1"]?>
				　<?php for($dataidx=0; $dataidx < $_SESSION["13CodeData"]["13DataCount"]; $dataidx++){
					if($_SESSION["S03_Kyo_gread1"] == $_SESSION["13CodeData"]["13_Eda_" . $dataidx]){
						echo $_SESSION["13CodeData"]["13_CodeName2_" . $dataidx];
					}
				}
				?>
				　<?php echo $_SESSION["S03_Kyo_old1"]?>歳
				　<?php 
					for($dataidx=0; $dataidx < $_SESSION["12CodeData"]["12DataCount"]; $dataidx++){
						if($_SESSION["S03_Kyo_Zoku1"] == $_SESSION["12CodeData"]["12_Eda_" . $dataidx]){
							echo $_SESSION["12CodeData"]["12_CodeName1_" . $dataidx];
						}
					}
				?>
			</td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">兄弟②</td>
			<td align="left" colspan="6">
				<?php echo $_SESSION["S03_Kyodai2"]?>
				　<?php for($dataidx=0; $dataidx < $_SESSION["13CodeData"]["13DataCount"]; $dataidx++){
					if($_SESSION["S03_Kyo_gread2"] == $_SESSION["13CodeData"]["13_Eda_" . $dataidx]){
						echo $_SESSION["13CodeData"]["13_CodeName2_" . $dataidx];
					}
				}
				?>
				　<?php echo $_SESSION["S03_Kyo_old2"]?>歳
				　<?php 
					for($dataidx=0; $dataidx < $_SESSION["12CodeData"]["12DataCount"]; $dataidx++){
						if($_SESSION["S03_Kyo_Zoku2"] == $_SESSION["12CodeData"]["12_Eda_" . $dataidx]){
							echo $_SESSION["12CodeData"]["12_CodeName1_" . $dataidx];
						}
					}
				?>
			</td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" colspan="3">電話</td>
			<td align="center" bgcolor="#c0c0c0" colspan="4">メール</td>
		</tr>
		<?php for($idx=1; $idx <= 3; $idx++){ ?>
			<tr>
				<td align="left">
					<?php 
						for($dataidx=0; $dataidx < $_SESSION["14CodeData"]["14DataCount"]; $dataidx++){
							if($_SESSION["S03_Tel_Kubun" .$idx] == $_SESSION["14CodeData"]["14_Eda_" . $dataidx]){
								echo $_SESSION["14CodeData"]["14_CodeName1_" . $dataidx];
							}
						} 
					?>
				</td>
				<td align="left" colspan="2"><?php echo $_SESSION["S03_Tel" .$idx]?></td>
				<td align="left">
					<?php 
						for($dataidx=0; $dataidx < $_SESSION["14CodeData"]["14DataCount"]; $dataidx++){
							if($_SESSION["S03_Mail_Kubun" .$idx] == $_SESSION["14CodeData"]["14_Eda_" . $dataidx]){
								echo $_SESSION["14CodeData"]["14_CodeName1_" . $dataidx];
							}
						} 
					?>
				</td>
				<td align="left" colspan="3"><?php echo $_SESSION["S03_Mail" .$idx]?></td>
			</tr>
		<?php } ?>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" colspan="2">連絡可能時間</td>
			<td align="left" colspan="5">
				<?php for($dataidx=0; $dataidx < $_SESSION["15CodeData"]["15DataCount"]; $dataidx++){ 
					if($_SESSION["S03_ContactTime" .$dataidx] == 1){
						echo $_SESSION["15CodeData"]["15_CodeName1_" . $dataidx] . "　";
					}
				 } ?>　
				<?php echo $_SESSION["S03_ContactTime4"] ?>
			</td>
		</tr>

		<?php if($_SESSION["KozaFlg"] == "0"){ ?>
			<?php $Row = $_SESSION["S03_Koza_DataCount"]*3;?>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="<?php echo $Row?>">口座情報</td>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">記号番号</td>
				<td align="left" colspan="2"><?php echo $_SESSION["S03_Koza_Kigou0"]?>-<?php echo $_SESSION["S03_Koza_Bango0"]?></td>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">開始日</td>
				<td align="left" colspan="2"><?php if(is_null($_SESSION["S03_Koza_Start0"]) || $_SESSION["S03_Koza_Start0"]==""){ }else{ echo date('Y年n月j日', strtotime($_SESSION["S03_Koza_Start0"])); } ?>　</td>
			</tr>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">名義人</td>
				<td align="left" colspan="2"><?php echo $_SESSION["S03_Koza_Meigi0"]?>　(<?php echo $_SESSION["S03_Koza_MeigiKana0"]?>)</td>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">終了日</td>
				<td align="left" colspan="2"><?php if(is_null($_SESSION["S03_Koza_End0"]) || $_SESSION["S03_Koza_End0"]==""){ }else{ echo date('Y年n月j日', strtotime($_SESSION["S03_Koza_End0"])); } ?>　</td>
			</tr>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">備考</td>
				<td align="left" colspan="5"><?php echo $_SESSION["S03_Koza_Biko0"]?>　(<?php echo $_SESSION["S03_Koza_Biko0"]?>)</td>
			</tr>
			<?php for($m=1; $m<$_SESSION["S03_Koza_DataCount"]; $m++){ ?>
				<tr>
					<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">記号番号</td>
					<td align="left" colspan="2"><?php echo $_SESSION["S03_Koza_Kigou" .$m]?>-<?php echo $_SESSION["S03_Koza_Bango" .$m]?></td>
					<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">開始日</td>
					<td align="left" colspan="2"><?php if(is_null($_SESSION["S03_Koza_Start" .$m]) || $_SESSION["S03_Koza_Start" .$m]==""){ }else{ echo date('Y年n月j日', strtotime($_SESSION["S03_Koza_Start" .$m])); } ?>　</td>
				</tr>
				<tr>
					<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">名義人</td>
					<td align="left" colspan="2"><?php echo $_SESSION["S03_Koza_Meigi" .$m]?>　(<?php echo $_SESSION["S03_Koza_MeigiKana" .$m]?>)</td>
					<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">終了日</td>
					<td align="left" colspan="2"><?php if(is_null($_SESSION["S03_Koza_End" .$m]) || $_SESSION["S03_Koza_End" .$m]==""){ }else{ echo date('Y年n月j日', strtotime($_SESSION["S03_Koza_End" .$m])); } ?>　</td>
				</tr>
				<tr>
					<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">備考</td>
					<td align="left" colspan="5"><?php echo $_SESSION["S03_Koza_Biko" .$m]?>　(<?php echo $_SESSION["S03_Koza_Biko" .$m]?>)</td>
				</tr>
			<?php } ?>
		<?php }else{ ?>
			<tr>
				<td align="left" colspan="7"><input type="button" id="Koza2" name="Koza2" onClick="sbmfnc(this,'')" style="cursor: pointer" value="+口座情報" /></td>
			</tr>
		<?php } ?>
		<?php if($_SESSION["TorokuJyohoFlg"] == "0"){ ?>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="6">登録情報</td>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">申込日</td>
				<td align="left" colspan="5"><?php echo $_SESSION["S03_TorokuDay"]?></td>
			</tr>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="3">現状</td>
				<td align="left" colspan="5">
					<?php if($_SESSION["S03_Genjyo1"] == 1){?>不登校<?php } ?>　
					<?php if($_SESSION["S03_Genjyo2"] == 1){?>成績不振<?php } ?>　
					<?php if($_SESSION["S03_Genjyo3"] == 1){?>中退<?php } ?>　
					<?php if($_SESSION["S03_Genjyo4"] == 1){?>浪人<?php } ?>　
				</td>
			</tr>
			<tr>
				<td align="left" colspan="5">
					<?php if($_SESSION["S03_Genjyo5"] == 1){?>ADHD<?php } ?>　
					<?php if($_SESSION["S03_Genjyo6"] == 1){?>LD<?php } ?>　
					<?php if($_SESSION["S03_Genjyo7"] == 1){?>アスペルガー<?php } ?>　
				</td>
			</tr>
			<tr>
				<td align="left" colspan="5">
					<?php if($_SESSION["S03_Genjyo8"] == 1){?>その他<?php } ?>　
					（<?php echo $_SESSION["S03_Genjyo_Sonota"]?>）
				</td>
			</tr>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="2">相談</td>
				<td width="120" align="left" colspan="5"><?php echo $_SESSION["S03_Sonota_Naiyo"]?></td>
			</tr>
			<tr>
				<td width="120" align="left" colspan="5"><?php echo $_SESSION["S03_Soudan"]?></td>
			</tr>
		<?php }else{ ?>
			<tr>
				<td align="left" colspan="7"><input type="button" id="TorokuJyoho2" name="TorokuJyoho2" onClick="sbmfnc(this,'')" style="cursor: pointer" value="+登録情報" /></td>
			</tr>
		<?php } ?>
		<?php if($_SESSION["KibouFlg"] == "0"){ ?>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="6">希望情報</td>
				<td align="center" bgcolor="#c0c0c0">教科</td>
				<td align="left" colspan="5">
					<?php 
						for($m=1; $m<=6; $m++){
							$kyoka = "小学";
							if($_SESSION["S03_Sub1_" . $m] == 1){
								$m2 = $m - 1;
								echo $kyoka . $_SESSION["05CodeData"]["05_CodeName1_" . $m2] . "　";
							}
						}
						for($m=1; $m<=6; $m++){
							$kyoka = "中学";
							if($_SESSION["S03_Sub2_" . $m] == 1){
								$m2 = $m - 1;
								echo $kyoka . $_SESSION["06CodeData"]["06_CodeName1_" . $m2] . "　";
							}
						}
						for($m=1; $m<=20; $m++){
							$kyoka = "高校";
							if($_SESSION["S03_Sub3_" . $m] == 1){
								$m2 = $m - 1;
								echo $kyoka . $_SESSION["07CodeData"]["07_CodeName1_" . $m2] . "　";
							}
						}
					?>
					<BR>
					<?php echo $_SESSION["S03_Sub4_1"] ?>　<?php echo $_SESSION["S00_Kyoka_Sonota"] ?>
				</td>
			</tr>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">曜日</td>
				<td align="left" colspan="5">
					<?php if($_SESSION["S03_Youbi1"] == 1){?>月<?php } ?>　
					<?php if($_SESSION["S03_Youbi2"] == 1){?>火<?php } ?>　
					<?php if($_SESSION["S03_Youbi3"] == 1){?>水<?php } ?>　
					<?php if($_SESSION["S03_Youbi4"] == 1){?>木<?php } ?>　
					<?php if($_SESSION["S03_Youbi5"] == 1){?>金<?php } ?>　
					<?php if($_SESSION["S03_Youbi6"] == 1){?>土<?php } ?>　
					<?php if($_SESSION["S03_Youbi7"] == 1){?>日<?php } ?>　
					<BR><?php echo $_SESSION["S03_Youbi_Sonota"]?>
				</td>
			</tr>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">時間</td>
				<td align="left" colspan="5">
					<?php echo $_SESSION["S03_ShidoTime"]?>
				</td>
			</tr>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">指導内容</td>
				<td align="left" colspan="5">
					<?php echo $_SESSION["S03_ShidoKibou"]?>
				</td>

			</tr>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="2">希望教師</td>
				<td align="left" colspan="5">
					<?php for($dataidx=0; $dataidx < $_SESSION["18CodeData"]["18DataCount"]; $dataidx++){ ?>
						<?php if($_SESSION["S03_KyoushiKibou" . $dataidx] == 1){?><?php echo $_SESSION["18CodeData"]["18_CodeName1_" . $dataidx] ?><?php } ?>　
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td align="left" colspan="5">
					<?php echo $_SESSION["S03_KyoushiKibouNaiyo"]?>
				</td>
			</tr>
		<?php }else{ ?>
			<tr>
				<td align="left" colspan="7"><input type="button" id="KibouJyoho2" name="KibouJyoho2" onClick="sbmfnc(this,'')" style="cursor: pointer" value="+希望情報" /></td>
			</tr>
		<?php } ?>
		<?php if($_SESSION["TokkiFlg"] == "0"){ ?>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="8">特記事項</td>
				<td align="left" colspan="6"><?php echo $_SESSION["S03_Notice1"]?></td>
			</tr>
			<tr>
				<td align="left" colspan="6"><?php echo $_SESSION["S03_Notice2"]?></td>
			</tr>
			<tr>
				<td align="left" colspan="6"><?php echo $_SESSION["S03_Notice3"]?></td>
			</tr>
			<tr>
				<td align="left" colspan="6"><?php echo $_SESSION["S03_Notice4"]?></td>
			</tr>
			<tr>
				<td align="left" colspan="6"><?php echo $_SESSION["S03_Notice5"]?></td>
			</tr>
			<tr>
				<td align="left" colspan="6"><?php echo $_SESSION["S03_notice1"]?></td>
			</tr>
			<tr>
				<td align="left" colspan="6"><?php echo $_SESSION["S03_notice2"]?></td>
			</tr>
			<tr>
				<td align="left" colspan="6"><?php echo $_SESSION["S03_notice3"]?></td>
			</tr>

		<?php }else{ ?>
			<tr>
				<td align="left" colspan="7"><input type="button" id="TokkiJiko2" name="TokkiJiko2" onClick="sbmfnc(this,'')" style="cursor: pointer" value="+特記事項" /></td>
			</tr>
		<?php } ?>
	</table>
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
			if(!is_null($_SESSION["EndFlg" . $i])){
				$CodeName_Where = "Eda = '" . $_SESSION["EndFlg" . $i] . "'";
				$CodeData = GetCodeData("終了区分",$CodeName_Where,"",1);
				$_SESSION["21CodeName"]=$CodeData;
				if($_SESSION["21CodeName"]["CountFlg"]==1){
					$_SESSION["21CodeName"]["21_CodeName1_0"]="";
				}
			}else{
				$_SESSION["21CodeName"]["21_CodeName1_0"]="";
			}
		?>
			<?php if ($_SESSION["DateFlg"] == 0 && $_SESSION["HyojiFlg" . $i] == 1) { ?>
				<tr>
					<td align="center" rowspan="3">
						<?php echo $_SESSION["SenteiName" . $i] ?>
						<BR><BR>
						<font size="2"><?php echo $_SESSION["21CodeName"]["21_CodeName1_0"]?></font>
					</td>
					<td id="midashi_Ichiran" align="center"><?php echo $_SESSION["TeacherID" . $i] ?>-<?php echo $_SESSION["AtenaSeq" . $i] ?>-<?php echo $_SESSION["Seq" . $i] ?></td>
					<td id="midashi_Ichiran" align="center" colspan="2"><?php echo $_SESSION["TeacherName" . $i] ?></td>
					<td align="center" colspan="2"><?php echo $_SESSION["Tel" . $i] ?><BR><?php echo $_SESSION["Mail" . $i] ?></td>
				</tr>
				<tr>
					<td id="midashi_Ichiran" align="center"><?php if($_SESSION["Pay" . $i] !=""){ ?><?php echo number_format($_SESSION["Pay" . $i]); ?><?php } ?></td>
					<td align="center"><?php echo $_SESSION["KiteiKaisu" . $i] ?></td>
					<td align="center"><?php echo $_SESSION["KiteiJikan" . $i] ?></td>
					<td align="center" width="120"><?php if(is_null($_SESSION["StartDay" . $i])){ }else{ echo date('Y/n/j', strtotime($_SESSION["StartDay" . $i])); }?></td>
					<td align="center" width="120"><?php if(is_null($_SESSION["EndDay" . $i])){ }else{echo date('Y/n/j', strtotime($_SESSION["EndDay" . $i])); }?></td>
				</tr>
				<tr>
					<td id="midashi_Ichiran" align="left" colspan="5"><?php echo $_SESSION["Naiyo" . $i] ?></td>

					<input type="hidden" name="postshori" value="">
					<input type="hidden" name="valuename_<?php echo $i ?>" value="<?php echo $i ?>">
					<input type="hidden" name="TeacherID_<?php echo $i ?>" value="<?php echo $_SESSION["TeacherID" . $i]; ?>">
					<input type="hidden" name="TeacherName_<?php echo $i ?>" value="<?php echo $_SESSION["TeacherName" . $i]; ?>">
					<input type="hidden" name="AtenaSeq_<?php echo $i ?>" value="<?php echo $_SESSION["AtenaSeq" . $i]; ?>">
					<input type="hidden" name="Seq_<?php echo $i ?>" value="<?php echo $_SESSION["Seq" . $i]; ?>">
					<input type="hidden" name="SenteiFlg_<?php echo $i ?>" value="<?php echo $_SESSION["SenteiFlg" . $i]; ?>">
					<input type="hidden" name="SHyojiFlg_<?php echo $i ?>" value="<?php echo $_SESSION["HyojiFlg" . $i]; ?>">
				</tr>
				<?php $no++; ?>
			<?php } else { ?>
				<?php if ($_SESSION["DateFlg"] == 1) { ?>
				<tr>
					<td align="center" rowspan="3">
						<input type="submit" name="No_<?php echo $i ?>" style="cursor: pointer;<?php if($_SESSION["SenteiFlg" . $i]==1){?>color:red;<?php } ?>" value="<?php echo $_SESSION["SenteiName" . $i] ?>">
						<BR><BR>
						<font size="2"><?php echo $_SESSION["21CodeName"]["21_CodeName1_0"]?></font>
					</td>
					<td id="midashi_Ichiran" align="center"<?php if ($_SESSION["HyojiFlg" . $i] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $_SESSION["TeacherID" . $i] ?>-<?php echo $_SESSION["AtenaSeq" . $i] ?>-<?php echo $_SESSION["Seq" . $i] ?></td>
					<td id="midashi_Ichiran" align="center"<?php if ($_SESSION["HyojiFlg" . $i] == 0) {?>bgcolor="#c0c0c0"<?php }?> colspan="2"><?php echo $_SESSION["TeacherName" . $i] ?></td>
					<td align="center"<?php if ($_SESSION["HyojiFlg" . $i] == 0) {?>bgcolor="#c0c0c0"<?php }?> colspan="2"><?php echo $_SESSION["Tel" . $i] ?><BR><?php echo $_SESSION["Mail" . $i] ?></td>
				</tr>
				<tr>
					<td id="midashi_Ichiran" align="center"<?php if ($_SESSION["HyojiFlg" . $i] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php if($_SESSION["Pay" . $i] !=""){ ?><?php echo number_format($_SESSION["Pay" . $i]); ?><?php } ?></td>
					<td align="center"<?php if ($_SESSION["HyojiFlg" . $i] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $_SESSION["KiteiKaisu" . $i] ?></td>
					<td align="center"<?php if ($_SESSION["HyojiFlg" . $i] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $_SESSION["KiteiJikan" . $i] ?></td>
					<td align="center"<?php if ($_SESSION["HyojiFlg" . $i] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php if(is_null($_SESSION["StartDay" . $i])){ }else{ echo date('Y/n/j', strtotime($_SESSION["StartDay" . $i])); }?></td>
					<td align="center"<?php if ($_SESSION["HyojiFlg" . $i] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php if(is_null($_SESSION["EndDay" . $i])){ }else{echo date('Y/n/j', strtotime($_SESSION["EndDay" . $i])); }?></td>
				</tr>
				<tr>
					<td id="midashi_Ichiran" align="left"<?php if ($_SESSION["HyojiFlg" . $i] == 0) {?>bgcolor="#c0c0c0"<?php }?> colspan="5"><?php echo $_SESSION["Naiyo" . $i] ?></td>

					<input type="hidden" name="postshori" value="">
					<input type="hidden" name="valuename_<?php echo $i ?>" value="<?php echo $i ?>">
					<input type="hidden" name="TeacherID_<?php echo $i ?>" value="<?php echo $_SESSION["TeacherID" . $i]; ?>">
					<input type="hidden" name="TeacherName_<?php echo $i ?>" value="<?php echo $_SESSION["TeacherName" . $i]; ?>">
					<input type="hidden" name="AtenaSeq_<?php echo $i ?>" value="<?php echo $_SESSION["AtenaSeq" . $i]; ?>">
					<input type="hidden" name="Seq_<?php echo $i ?>" value="<?php echo $_SESSION["Seq" . $i]; ?>">
					<input type="hidden" name="SenteiFlg_<?php echo $i ?>" value="<?php echo $_SESSION["SenteiFlg" . $i]; ?>">
					<input type="hidden" name="SHyojiFlg_<?php echo $i ?>" value="<?php echo $_SESSION["HyojiFlg" . $i]; ?>">
				</tr>
				<?php } ?>
				<?php $no2=$no2+1;?>
			<?php } ?>
		<?php } ?>
	</table>
<!-- ===============================================折衝情報============================================================= -->
	<table border="0">
		<tr>
			<td id="midashi_Kanri" width="100" align="center" bgcolor="#c0c0c0" rowspan="2">日付</td>
			<td id="midashi_Kanri" width="300" align="center" bgcolor="#c0c0c0" rowspan="2">状態</td>
			<td id="midashi_Kanri" width="100" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">方法</td>
			<td id="midashi_Kanri" width="100" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">相手</td>
			<td id="midashi_Kanri" width="200" align="center" bgcolor="<?php echo TEACHR_COLOR ?>">教師</td>
			<td id="midashi_Kanri" width="150" align="center" bgcolor="<?php echo TEACHR_COLOR ?>">方法</td>
			<td id="midashi_Kanri" width="150" align="center" bgcolor="<?php echo TEACHR_COLOR ?>">相手</td>
			<td id="midashi_Kanri" width="100" align="center" bgcolor="#c0c0c0" rowspan="2">担当</td>
		<tr>
				<td id="midashi_Kanri" width="450" align="center" bgcolor="#c0c0c0" colspan="5">内容</td>
		</tr>
		<?php $no=1;?>
		<?php for($i=0; $i<$_SESSION["T04_Sessho_DataCount"]; $i++){ ?>
			<?php $idx = $i + 1; ?>
			<?php
				$CodeData = array();
				$CodeName_Where = "Eda = '" . $_SESSION["T04_Sessho_Jyotai" .$i] . "'";
				$CodeData = GetCodeData("状態教師",$CodeName_Where,"",1);
				$_SESSION["03CodeName"]=$CodeData;
				$CodeData = GetCodeData("状態生徒",$CodeName_Where,"",1);
				$_SESSION["04CodeName"]=$CodeData;
				if(is_null($_SESSION["T04_Sessho_THouho" .$i])){
					$_SESSION["09TCodeName"]["09_CodeName1_0"]="";
				}else{
					$CodeData2 = array();
					$CodeName_Where2 = "Eda = '" . $_SESSION["T04_Sessho_THouho" .$i] . "'";
					$CodeData2 = GetCodeData("折衝方法",$CodeName_Where2,"",1);
					$_SESSION["09TCodeName"]=$CodeData2;
					if($_SESSION["09TCodeName"]["CountFlg"]==1){
						$_SESSION["09TCodeName"]["09_CodeName1_0"]="";
					}
				}
				if(is_null($_SESSION["T04_Sessho_SHouho" .$i])){
					$_SESSION["09SCodeName"]["09_CodeName1_0"]="";
				}else{
					$CodeData2 = array();
					$CodeName_Where2 = "Eda = '" . $_SESSION["T04_Sessho_SHouho" .$i] . "'";
					$CodeData2 = GetCodeData("折衝方法",$CodeName_Where2,"",1);
					$_SESSION["09SCodeName"]=$CodeData2;
					if($_SESSION["09SCodeName"]["CountFlg"]==1){
						$_SESSION["09SCodeName"]["09_CodeName1_0"]="";
					}
				}
				if(is_null($_SESSION["T04_Sessho_TAite" .$i])){
					$_SESSION["10TCodeName"]["10_CodeName1_0"]="";
				}else{
					$CodeData3 = array();
					$CodeName_Where3 = "Eda = '" . $_SESSION["T04_Sessho_TAite" .$i] . "'";
					$CodeData3 = GetCodeData("折衝相手",$CodeName_Where3,"",1);
					$_SESSION["10TCodeName"]=$CodeData3;
					if($_SESSION["10TCodeName"]["CountFlg"]==1){
						$_SESSION["10TCodeName"]["10_CodeName1_0"]="";
					}
				}
				if(is_null($_SESSION["T04_Sessho_SAite" .$i])){
					$_SESSION["10SCodeName"]["10_CodeName1_0"]="";
				}else{
					$CodeData3 = array();
					$CodeName_Where3 = "Eda = '" . $_SESSION["T04_Sessho_SAite" .$i] . "'";
					$CodeData3 = GetCodeData("折衝相手",$CodeName_Where3,"",1);
					$_SESSION["10SCodeName"]=$CodeData3;
					if($_SESSION["10SCodeName"]["CountFlg"]==1){
						$_SESSION["10SCodeName"]["10_CodeName1_0"]="";
					}
				}
				if(is_null($_SESSION["T04_Sessho_Tanto" .$i])){
					$_SESSION["TantoName"]["TeacherID0"]="";
				}else{
					$CodeData4 = array();
					$CodeName_Where4 = "A.TeacherID='" . $_SESSION["T04_Sessho_Tanto" .$i] . "'";
					$CodeData4 = GetKanriTnato($CodeName_Where4,"");
					$_SESSION["TantoName"]=$CodeData4;
				}
				$Sessho_Day = explode("-", $_SESSION["T04_Sessho_SesshoDay" .$i]);
				$Sessho_Y = $Sessho_Day[0];
				$Sessho_M = $Sessho_Day[1];
				$Sessho_D = $Sessho_Day[2];

				list ($YoubiNo, $Youbi) = GetYoubi($Sessho_Y,$Sessho_M,$Sessho_D);
			?>
			<tr>
				<td width="100" align="center" rowspan="2"><?php echo $_SESSION["T04_Sessho_SesshoDay" .$i] ?>(<?php echo $Youbi?>)</td>
				<td width="200" align="left" rowspan="2">
					<B><?php if($_SESSION["T04_Sessho_JyotaiKubun" .$i]=="0"){ ?> <?php echo $_SESSION["04CodeName"]["04_CodeName1_0"] ?> <?php }else{ ?> <?php echo $_SESSION["04CodeName"]["04_CodeName1_0"] ?> <?php } ?></B><BR>
					<font color="#0000FF"><?php echo $_SESSION["T04_Sessho_YakusokuDay" .$i] ?></font>
				</td>
				<td width="100" align="center" ><?php echo $_SESSION["09SCodeName"]["09_CodeName1_0"] ?></td>
				<td width="100" align="center" ><?php echo $_SESSION["10SCodeName"]["10_CodeName1_0"] ?></td>
				<td width="200" align="left"><?php if($_SESSION["T04_Sessho_TeacherID" .$i]!=0){ ?><?php echo GetTeacherName($_SESSION["T04_Sessho_TeacherID" .$i]) ?><?php } ?></td>
				<td width="100" align="center"><?php echo $_SESSION["09TCodeName"]["09_CodeName1_0"] ?></td>
				<td width="100" align="center"><?php echo $_SESSION["10TCodeName"]["10_CodeName1_0"] ?></td>
				<td width="100" align="center" rowspan="2"><?php echo $_SESSION["TantoName"]["Name1_0"] ?></td>
			</tr>
			<tr>
				<td id="midashi_Ichiran" width="450" align="left" colspan="5">
					<B><?php echo $_SESSION["T04_Sessho_Naiyo" .$i] ?></B>
					<BR>
					<B><?php if($_SESSION["T04_Sessho_HikitugiKubun" .$i]=="1"){ ?><a href="#" onClick="disp(<?php echo $i?>);return false"><font color="#FF0000"><?php }else{ ?><font color="#0000FF"><?php } ?><?php echo $_SESSION["T04_Sessho_Hikitugi" .$i]?><?php if($_SESSION["T04_Sessho_HikitugiKubun" .$i]=="1"){ ?></font></a><?php }else{ ?></font><?php } ?></B>
				</td>
			</tr>
			<?php $no++; ?>
		<?php } ?>
	</table>
</div>
</form>
</body>
</CENTER>
</html>