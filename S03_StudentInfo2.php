<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN"
	"http://www.w3.org/TR/html4/frameset.dtd">

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="Headerprint.css">
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
	$Today = $dt->format('Y-m-d');
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
		if(isset($_GET['SELDATA'])) {
			$_SESSION["SELDATA"] = $_GET['SELDATA'];
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
                
                //ファイルダウンロード
                $EMSG = fileDownload_ichiran($_SESSION["DateCount"],"Tea_TeacherID");
                
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
//			$query2 = "SELECT a.Name1,a.Name2,b.Mail1,b.Mail2,b.Tel1,b.Tel2,b.Tel3 FROM T_AtenaInfo as a";
			$query2 = "SELECT a.*, b.*, c.* FROM T_AtenaInfo as a";
			$query2 = $query2 . " inner join T_KihonInfo as b";
			$query2 = $query2 . " on a.TeacherID = b.TeacherID";
			$query2 = $query2 . " inner join T_ShosaiInfo as c";
			$query2 = $query2 . " on a.TeacherID = c.TeacherID";
			$query2 = $query2 . " WHERE a.TeacherID = '" . $TeacherID . "'";

			$result2 = $mysqli->query($query2);

			//print($query2 ."<BR>");

			if (!$result2) {
				print('クエリーが失敗しました。' . $mysqli->error);
				$mysqli->close();
				exit();
			}

			while($arr_item = $result2->fetch_assoc()){
			//レコード内の各フィールド名と値を順次参照
			foreach($arr_item as $key => $value){
				//フィールド名と値を表示
				$data[$i][$key] = $value;
				$_SESSION["Tea_" . $key . $i] = $value;
				$_SESSION["Tea_key" . $i] = $key;
//print($key . "=" . $_SESSION["Tea_" . $key . $i] . "<BR>");
			}
			$_SESSION["SenteiFlg" . $i] = 1;
			$_SESSION["SenteiName" . $i] = "契約";

			//------終了分判定------
			if(is_null($data[$i]['EndDay'])){
				$_SESSION["HyojiFlg" . $i] = 1;
			}else{
				if (strtotime($_SESSION["Today"]) <= strtotime($data[$i]['EndDay'])) {
					$_SESSION["HyojiFlg" . $i] = 1;
				} else {
					$_SESSION["HyojiFlg" . $i] = 0;
					$_SESSION["SenteiFlg" . $i] = 8;
					$_SESSION["SenteiName" . $i] = "終了";
				}
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
			//$_SESSION["TeacherCount"] = $i;
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
//			$query2 = "SELECT a.Name1,a.Name2,b.Mail1,b.Mail2,b.Tel1,b.Tel2,b.Tel3 FROM T_AtenaInfo as a";
//			$query2 = $query2 . " inner join T_KihonInfo as b";
//			$query2 = $query2 . " on a.TeacherID = b.TeacherID";
//			$query2 = $query2 . " WHERE a.TeacherID = '" . $TeacherID . "'";

			$query2 = "SELECT a.*, b.*, c.* FROM T_AtenaInfo as a";
			$query2 = $query2 . " inner join T_KihonInfo as b";
			$query2 = $query2 . " on a.TeacherID = b.TeacherID";
			$query2 = $query2 . " inner join T_ShosaiInfo as c";
			$query2 = $query2 . " on a.TeacherID = c.TeacherID";
			$query2 = $query2 . " WHERE a.TeacherID = '" . $TeacherID . "'";

			$result2 = $mysqli->query($query2);

			//print($query2 ."<BR>");

			if (!$result2) {
				print('クエリーが失敗しました。' . $mysqli->error);
				$mysqli->close();
				exit();
			}

			while($arr_item = $result2->fetch_assoc()){
			//レコード内の各フィールド名と値を順次参照
			foreach($arr_item as $key => $value){
				//フィールド名と値を表示
				$data[$i][$key] = $value;
				$_SESSION["Tea_" . $key . $i] = $value;
				$_SESSION["Tea_key" . $i] = $key;
//print($key . "=" . $_SESSION["Tea_" . $key . $i] . "<BR>");
			}

//			while ($row = $result2->fetch_assoc()) {
//				$db_Name1 = $row['Name1'];
//				$db_Name2 = $row['Name2'];
//				$db_Mail1 = $row['Mail1'];
//				$db_Mail2 = $row['Mail2'];
//				$db_Tel1 = $row['Tel1'];
//				$db_Tel2 = $row['Tel2'];
//				$db_Tel3 = $row['Tel3'];
//			}

			$_SESSION["TeacherName" . $i] = $db_Name1;
			$Mail = $db_Mail1;
			$_SESSION["Mail" . $i] = $Mail;

			$Tel = $db_Tel1;
			$_SESSION["Tel" . $i] = $Tel;
			$_SESSION["StartDay" . $i] = NULL;
			$_SESSION["StartFlg" . $i] = 0;
			$_SESSION["EndDay" . $i] = NULL;
			$_SESSION["EndFlg" . $i] = 0;
//			$_SESSION["Pay" . $i] = "";
//			$_SESSION["KiteiKaisu" . $i] = "";
//			$_SESSION["KiteiJikan" . $i] = "";
//			$_SESSION["course" . $i] = "";
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

<form name="form1" method="post" action="S03_StudentInfo2.php">
	<table>
		<tr>
			<td width="800">
				<div id="tbl-bdr">
				<table>
					<tr>
						<td align="left" bgcolor="#c0c0c0" width="100">生徒ID</td>
						<td align="left" width="500"><?php echo $_SESSION["S03_StudentID"] ?>-<?php echo $_SESSION["S03_Seq"] ?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">登録日</td>
						<td align="left" width="200"><?php if(is_null($_SESSION["S03_EntryDay"])){ }else{ echo date('Y年n月j日', strtotime($_SESSION["S03_EntryDay"])); } ?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">生徒名</td>
						<td align="left"><?php echo $_SESSION["S03_Name1"]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">生徒名かな</td>
						<td align="left"><?php echo $_SESSION["S03_Name2"]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">生年月日</td>
						<td align="left" width="120"><?php if(is_null($_SESSION["S03_BirthDay"])){ }else{ echo date('Y年n月j日', strtotime($_SESSION["S03_BirthDay"])); } ?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">年齢（生年月日換算）</td>
						<td align="left" width="120"><?php echo $_SESSION["S03_Old2"]?>歳</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">学校名</td>
						<td align="left"><?php echo $_SESSION["S03_SchoolName"]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">学年</td>
						<td align="left">
						<?php 
							for($dataidx=0; $dataidx < $_SESSION["13CodeData"]["13DataCount"]; $dataidx++){
								if($_SESSION["S03_gread"] == $_SESSION["13CodeData"]["13_Eda_" . $dataidx]){
									echo $_SESSION["13CodeData"]["13_CodeName2_" . $dataidx];
								}
							}
						?>
						</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">年齢（学年換算）</td>
						<td align="left"><?php echo $_SESSION["S03_old"]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">性別</td>
						<td align="left">
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
						<td align="left" bgcolor="#c0c0c0">保護者①</td>
						<td align="left"><?php echo $_SESSION["S03_Hogosha1"]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">保護者①かな</td>
						<td align="left"><?php echo $_SESSION["S03_HogoshaKana1"]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">保護者①続柄</td>
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
						<td align="left" bgcolor="#c0c0c0">保護者②</td>
						<td align="left"><?php echo $_SESSION["S03_Hogosha2"]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">保護者②かな</td>
						<td align="left"><?php echo $_SESSION["S03_HogoshaKana2"]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">保護者②続柄</td>
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
<!--
					<tr>
						<td align="left" bgcolor="#c0c0c0">住所</td>
						<td align="left"><?php echo $_SESSION["S03_Yubin1_1"]?>-<?php echo $_SESSION["S03_Yubin1_2"]?>　<?php echo $_SESSION["S03_Add_ken1"]?><?php echo $_SESSION["S03_Add_ken1"]?><?php echo $_SESSION["S03_Add_shi1"]?><?php echo $_SESSION["S03_Add_ku1"]?><?php echo $_SESSION["S03_Add_cho1"]?></td>
					</tr>
-->
					<tr>
						<td align="left" bgcolor="#c0c0c0">住所・郵便番号</td>
						<td align="left"><?php echo $_SESSION["S03_Yubin1_1"]?>-<?php echo $_SESSION["S03_Yubin1_2"]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">住所・県</td>
						<td align="left"><?php echo $_SESSION["S03_Add_ken1"]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">住所・市区町</td>
						<td align="left"><?php echo $_SESSION["S03_Add_shi1"]?><?php echo $_SESSION["S03_Add_ku1"]?><?php echo $_SESSION["S03_Add_cho1"]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">その他</td>
						<td align="left"><?php echo $_SESSION["S03_Yubin2_1"]?>-<?php echo $_SESSION["S03_Yubin2_2"]?>　<?php echo $_SESSION["S03_Add_ken2"]?><?php echo $_SESSION["S03_Add_shi2"]?><?php echo $_SESSION["S03_Add_ku2"]?><?php echo $_SESSION["S03_Add_cho2"]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">沿線</td>
						<td align="left"><?php echo $_SESSION["S03_Kotu_rosen"]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">駅</td>
						<td align="left"><?php echo $_SESSION["S03_Kotu_Eki"]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">徒歩・バス</td>
						<td align="left"><?php echo $_SESSION["S03_Kotu_Toho"]?>分</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">車使用</td>
						<td align="left"><?php if($_SESSION["S03_CarTF"]==1){?>可能<?php }elseif(is_null($_SESSION["S03_CarTF"]) || $_SESSION["S03_CarTF"]==""){ ?><?php }else{ ?>不可<?php } ?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">その他</td>
						<td align="left"><?php echo $_SESSION["S03_Kotu_Sonota"]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">兄弟①</td>
						<td align="left">
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
						<td align="left" bgcolor="#c0c0c0">兄弟②</td>
						<td align="left">
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
					<?php for($idx=1; $idx <= 3; $idx++){ ?>
						<tr>
							<td align="left" bgcolor="#c0c0c0">電話<?php echo $idx ?></td>
							<td align="left">
								<?php 
									for($dataidx=0; $dataidx < $_SESSION["14CodeData"]["14DataCount"]; $dataidx++){
										if($_SESSION["S03_Tel_Kubun" .$idx] == $_SESSION["14CodeData"]["14_Eda_" . $dataidx]){
											echo $_SESSION["14CodeData"]["14_CodeName1_" . $dataidx];
										}
									} 
								?>
							</td>
						</tr>
						<tr>
							<td align="left" bgcolor="#c0c0c0">電話番号</td>
							<td align="left"><?php echo $_SESSION["S03_Tel" .$idx]?></td>
						</tr>
					<?php } ?>
					<?php for($idx=1; $idx <= 3; $idx++){ ?>
						<tr>
							<td align="left" bgcolor="#c0c0c0">メール<?php echo $idx ?></td>
							<td align="left">
								<?php 
									for($dataidx=0; $dataidx < $_SESSION["14CodeData"]["14DataCount"]; $dataidx++){
										if($_SESSION["S03_Mail_Kubun" .$idx] == $_SESSION["14CodeData"]["14_Eda_" . $dataidx]){
											echo $_SESSION["14CodeData"]["14_CodeName1_" . $dataidx];
										}
									} 
								?>
							</td>
						</tr>
						<tr>
							<td align="left" bgcolor="#c0c0c0">メールアドレス</td>
							<td align="left"><?php echo $_SESSION["S03_Mail" .$idx]?></td>
						</tr>
					<?php } ?>
					<tr>
						<td align="left" bgcolor="#c0c0c0">連絡可能時間</td>
						<td align="left">
							<?php for($dataidx=0; $dataidx < $_SESSION["15CodeData"]["15DataCount"]; $dataidx++){ 
								if($_SESSION["S03_ContactTime" .$dataidx] == 1){
									echo $_SESSION["15CodeData"]["15_CodeName1_" . $dataidx] . "　";
								}
							 } ?>　
							<?php echo $_SESSION["S03_ContactTime4"] ?>
						</td>
					</tr>


					<tr>
						<td align="left" bgcolor="#c0c0c0">申込日</td>
						<td align="left"><?php echo $_SESSION["S03_TorokuDay"]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">現状</td>
						<td align="left">
							<?php if($_SESSION["S03_Genjyo1"] == 1){?>不登校<?php } ?>　
							<?php if($_SESSION["S03_Genjyo2"] == 1){?>成績不振<?php } ?>　
							<?php if($_SESSION["S03_Genjyo3"] == 1){?>中退<?php } ?>　
							<?php if($_SESSION["S03_Genjyo4"] == 1){?>浪人<?php } ?>　
							<?php if($_SESSION["S03_Genjyo5"] == 1){?>ADHD<?php } ?>　
							<?php if($_SESSION["S03_Genjyo6"] == 1){?>LD<?php } ?>　
							<?php if($_SESSION["S03_Genjyo7"] == 1){?>アスペルガー<?php } ?>　
						</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">現状その他</td>
						<td align="left"><?php echo $_SESSION["S03_Genjyo_Sonota"]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">相談①</td>
						<td width="120" align="left"><?php echo $_SESSION["S03_Sonota_Naiyo"]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">相談②</td>
						<td width="120" align="left"><?php echo $_SESSION["S03_Soudan"]?></td>
					</tr>

					<tr>
						<td align="left" bgcolor="#c0c0c0">教科</td>
						<td align="left">
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
						</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">教科その他</td>
						<td align="left"><?php echo $_SESSION["S00_Kyoka_Sonota"] ?></td>
					</tr
					<tr>
						<td align="left" bgcolor="#c0c0c0">曜日</td>
						<td align="left">
							<?php if($_SESSION["S03_Youbi1"] == 1){?>月<?php } ?>　
							<?php if($_SESSION["S03_Youbi2"] == 1){?>火<?php } ?>　
							<?php if($_SESSION["S03_Youbi3"] == 1){?>水<?php } ?>　
							<?php if($_SESSION["S03_Youbi4"] == 1){?>木<?php } ?>　
							<?php if($_SESSION["S03_Youbi5"] == 1){?>金<?php } ?>　
							<?php if($_SESSION["S03_Youbi6"] == 1){?>土<?php } ?>　
							<?php if($_SESSION["S03_Youbi7"] == 1){?>日<?php } ?>　
						</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">曜日・回数等</td>
						<td align="left"><?php echo $_SESSION["S03_Youbi_Sonota"]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">時間</td>
						<td align="left">
							<?php echo $_SESSION["S03_ShidoTime"]?>
						</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">指導内容</td>
						<td align="left">
							<?php echo $_SESSION["S03_ShidoKibou"]?>
						</td>

					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">希望教師①</td>
						<td align="left">
							<?php for($dataidx=0; $dataidx < $_SESSION["18CodeData"]["18DataCount"]; $dataidx++){ ?>
								<?php if($_SESSION["S03_KyoushiKibou" . $dataidx] == 1){?><?php echo $_SESSION["18CodeData"]["18_CodeName1_" . $dataidx] ?><?php } ?>　
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">希望教師②</td>
						<td align="left">
							<?php echo $_SESSION["S03_KyoushiKibouNaiyo"]?>
						</td>
					</tr>

					<tr>
						<td align="left" bgcolor="#c0c0c0">特記事項①</td>
						<td align="left"><?php echo $_SESSION["S03_Notice1"]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">特記事項②</td>
						<td align="left"><?php echo $_SESSION["S03_Notice2"]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">特記事項③</td>
						<td align="left"><?php echo $_SESSION["S03_Notice3"]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">特記事項④</td>
						<td align="left"><?php echo $_SESSION["S03_Notice4"]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">特記事項⑤</td>
						<td align="left"><?php echo $_SESSION["S03_Notice5"]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">特記事項⑥</td>
						<td align="left"><?php echo $_SESSION["S03_notice1"]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">特記事項⑦</td>
						<td align="left"><?php echo $_SESSION["S03_notice2"]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">特記事項⑧</td>
						<td align="left"><?php echo $_SESSION["S03_notice3"]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">1.記号番号</td>
						<td align="left" ><?php echo $_SESSION["S03_Koza_Kigou0"]?>-<?php echo $_SESSION["S03_Koza_Bango0"]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">1.名義人</td>
						<td align="left" ><?php echo $_SESSION["S03_Koza_Meigi0"]?>　(<?php echo $_SESSION["S03_Koza_MeigiKana0"]?>)</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">1.開始日</td>
						<td align="left" ><?php if(is_null($_SESSION["S03_Koza_Start0"]) || $_SESSION["S03_Koza_Start0"]==""){ }else{ echo date('Y年n月j日', strtotime($_SESSION["S03_Koza_Start0"])); } ?>　</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">1.終了日</td>
						<td align="left" ><?php if(is_null($_SESSION["S03_Koza_End0"]) || $_SESSION["S03_Koza_End0"]==""){ }else{ echo date('Y年n月j日', strtotime($_SESSION["S03_Koza_End0"])); } ?>　</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">1.備考</td>
						<td align="left" ><?php echo $_SESSION["S03_Koza_Biko0"]?>　(<?php echo $_SESSION["S03_Koza_Biko0"]?>)</td>
					</tr>
					<?php for($m=1; $m<$_SESSION["S03_Koza_DataCount"]; $m++){ ?>
						<?php $m2 = $m + 1; ?>
						<tr>
							<td align="left" bgcolor="#c0c0c0"><?php echo $m2 ?>.記号番号</td>
							<td align="left" ><?php echo $_SESSION["S03_Koza_Kigou" .$m]?>-<?php echo $_SESSION["S03_Koza_Bango" .$m]?></td>
						</tr>
						<tr>
							<td align="left" bgcolor="#c0c0c0"><?php echo $m2 ?>.名義人</td>
							<td align="left" ><?php echo $_SESSION["S03_Koza_Meigi" .$m]?>　(<?php echo $_SESSION["S03_Koza_MeigiKana" .$m]?>)</td>
						</tr>
						<tr>
							<td align="left" bgcolor="#c0c0c0"><?php echo $m2 ?>.開始日</td>
							<td align="left" ><?php if(is_null($_SESSION["S03_Koza_Start" .$m]) || $_SESSION["S03_Koza_Start" .$m]==""){ }else{ echo date('Y年n月j日', strtotime($_SESSION["S03_Koza_Start" .$m])); } ?>　</td>
						</tr>
						<tr>
							<td align="left" bgcolor="#c0c0c0"><?php echo $m2 ?>.終了日</td>
							<td align="left" ><?php if(is_null($_SESSION["S03_Koza_End" .$m]) || $_SESSION["S03_Koza_End" .$m]==""){ }else{ echo date('Y年n月j日', strtotime($_SESSION["S03_Koza_End" .$m])); } ?>　</td>
						</tr>
						<tr>
							<td align="left" bgcolor="#c0c0c0"><?php echo $m2 ?>.備考</td>
							<td align="left" ><?php echo $_SESSION["S03_Koza_Biko" .$m]?>　(<?php echo $_SESSION["S03_Koza_Biko" .$m]?>)</td>
						</tr>
					<?php } ?>
				</table>
				</div>
			</td>
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
			<?php if ($_SESSION["HyojiFlg" . $i] == 1 && $_SESSION["SELDATA"]==$i) { ?>
			<td valign="top" width="800">
				<div id="tbl-bdr">
				<table>
					<tr>
						<td align="left" bgcolor="<?php echo KITEI_COLOR ?>" width="200">契約区分</td>
						<td align="left" width="200">
							<?php echo $_SESSION["SenteiName" . $i] ?>
						</td>
                        <td rowspan="7" width=110 align="center">
                            <img src="<?php echo $_SESSION["ImageName_" . $i . "_0"] ?>" width="110" height="120" alt="顔写真">
                        </td>
					</tr>
					<tr>
						<td align="left" bgcolor="<?php echo KITEI_COLOR ?>">開始日</td>
						<td align="left" ><?php if(is_null($_SESSION["StartDay" . $i])){ }else{ echo date('Y年n月j日', strtotime($_SESSION["StartDay" . $i])); }?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="<?php echo KITEI_COLOR ?>">終了日</td>
						<td align="left" ><?php if(is_null($_SESSION["EndDay" . $i])){ }else{echo date('Y年n月j日', strtotime($_SESSION["EndDay" . $i])); }?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="<?php echo KITEI_COLOR ?>">種別</td>
						<td align="left">
							<?php
								for($dataidx=0; $dataidx < $_SESSION["20CodeData"]["20DataCount"]; $dataidx++){ 
									if($_SESSION["20CodeData"]["20_Eda_" . $dataidx] == $_SESSION["Orgtype" . $i]){  
										echo $_SESSION["20CodeData"]["20_CodeName2_" . $dataidx];
									}
								} 
							?>
						</td>
					</tr>
					<tr>
						<td align="left" bgcolor="<?php echo KITEI_COLOR ?>">コース</td>
						<td align="left">
							<?php
								for($dataidx=0; $dataidx < $_SESSION["19CodeData"]["19DataCount"]; $dataidx++){ 
									if($_SESSION["19CodeData"]["19_Eda_" . $dataidx] == $_SESSION["course" . $i]){  
										echo $_SESSION["19CodeData"]["19_CodeName2_" . $dataidx];
									}
								} 
							?>
						</td>
					</tr>
					<tr>
						<td align="left" bgcolor="<?php echo KITEI_COLOR ?>">コース２</td>
						<td align="left">
							<?php
								for($dataidx=0; $dataidx < $_SESSION["26CodeData"]["26DataCount"]; $dataidx++){ 
									if($_SESSION["26CodeData"]["26_Eda_" . $dataidx] == $_SESSION["course2" . $i]){  
										echo $_SESSION["26CodeData"]["26_CodeName2_" . $dataidx];
									}
								} 
							?>
						</td>
					</tr>
					<tr>
						<td align="left" bgcolor="<?php echo KITEI_COLOR ?>">時給</td>
						<td align="left"><?php if($_SESSION["Pay" . $i] !=""){ ?><?php echo number_format($_SESSION["Pay" . $i]); ?><?php } ?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="<?php echo KITEI_COLOR ?>">回数</td>
						<td align="left"><?php echo $_SESSION["KiteiKaisu" . $i] ?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="<?php echo KITEI_COLOR ?>">時間</td>
						<td align="left"><?php echo $_SESSION["KiteiJikan" . $i] ?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="<?php echo KITEI_COLOR ?>">規定金額</td>
						<td align="left"><?php if($_SESSION["KiteiKingaku" . $i] !=""){ ?><?php echo number_format($_SESSION["KiteiKingaku" . $i]); ?><?php } ?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="<?php echo KITEI_COLOR ?>">交通費</td>
						<td align="left"><?php if($_SESSION["KiteiKotuhi" . $i] !=""){ ?><?php echo number_format($_SESSION["KiteiKotuhi" . $i]); ?><?php } ?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="<?php echo KITEI_COLOR ?>">給与NO</td>
						<td align="left"><?php echo $_SESSION["KyuyoNo" . $i] ?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="<?php echo KITEI_COLOR ?>">教科</td>
						<td align="left"><?php echo $_SESSION["Kyoka" . $i] ?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0" width="100">教師ID</td>
						<td align="left"><?php echo $_SESSION["Tea_TeacherID" . $i] ?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">登録日</td>
						<td align="left" ><?php if(is_null($_SESSION["Tea_EntryDay" . $i])){ }else{ echo date('Y年n月j日', strtotime($_SESSION["Tea_EntryDay" . $i])); } ?></td>

					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">生年月日</td>
						<td align="left" ><?php if(is_null($_SESSION["Tea_BirthDay" . $i])){ }else{ echo date('Y年n月j日', strtotime($_SESSION["Tea_BirthDay" . $i])); } ?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">教師名</td>
						<td align="left"><?php echo $_SESSION["Tea_Name1" . $i]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">教師名かな</td>
						<td align="left"><?php echo $_SESSION["Tea_Name2" . $i]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">教師名評価</td>
						<td align="left">
							<?php for($dataidx=0; $dataidx < $_SESSION["28CodeData"]["28DataCount"]; $dataidx++){ ?>
								<?php if($_SESSION["28CodeData"]["28_Eda_" . $dataidx] == $_SESSION["Tea_Hyoka" . $i]){ ?> 
									<?php if($_SESSION["Tea_Hyoka" . $i]==88 ||$_SESSION["Tea_Hyoka" . $i]==99){?><font color="red"><?php } ?><?php echo $_SESSION["28CodeData"]["28_CodeName2_" . $dataidx] ?><?php if($_SESSION["Tea_Hyoka" . $i]==88 ||$_SESSION["Tea_Hyoka" . $i]==99){?></font><?php } ?>
								<?php } ?>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">年齢</td>
						<td align="left"><?php echo $_SESSION["Tea_Old" . $i]?>歳</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">性別</td>
						<td align="left"><?php if($_SESSION["Tea_Seibetu" . $i]==1){?>男<?php }else{?>女<?php } ?></td>
					</tr>
<!--
					<tr>
						<td align="left" bgcolor="#c0c0c0">住所</td>
						<td align="left" ><?php echo $_SESSION["Tea_Yubin1" . $i]?>-<?php echo $_SESSION["Tea_Yubin2" . $i]?>　<?php echo $_SESSION["Tea_Add_ken" . $i]?><?php echo $_SESSION["Tea_Add_shi" . $i]?><?php echo $_SESSION["Tea_Add_ku" . $i]?><?php echo $_SESSION["Tea_Add_cho" . $i]?></td>
					</tr>
-->
					<tr>
						<td align="left" bgcolor="#c0c0c0">住所・郵便番号</td>
						<td align="left" ><?php echo $_SESSION["Tea_Yubin1" . $i]?>-<?php echo $_SESSION["Tea_Yubin2" . $i]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">住所・県</td>
						<td align="left" ><?php echo $_SESSION["Tea_Add_ken" . $i]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">住所・町</td>
						<td align="left" ><?php echo $_SESSION["Tea_Add_shi" . $i]?><?php echo $_SESSION["Tea_Add_ku" . $i]?><?php echo $_SESSION["Tea_Add_cho" . $i]?></td>
					</tr>

					<tr>
						<td align="left" bgcolor="#c0c0c0">個人情報変更</td>
						<td align="left" ><?php echo $_SESSION["Tea_Notice1" . $i]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">電話1</td>
						<td align="left"><?php echo $_SESSION["Tea_Tel1" . $i]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">電話2</td>
						<td align="left"><?php echo $_SESSION["Tea_Tel2" . $i]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">電話3</td>
						<td align="left"><?php echo $_SESSION["Tea_Tel3" . $i]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">メール1</td>
						<td align="left"><?php echo $_SESSION["Tea_Mail1" . $i]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">メール2</td>
						<td align="left"><?php echo $_SESSION["Tea_Mail2" . $i]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">メール3</td>
						<td align="left"><?php echo $_SESSION["Tea_Mail3" . $i]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">卒業大学①</td>
						<td align="left" >
							<?php echo $_SESSION["Tea_Uni1" . $i]?>　<?php echo $_SESSION["Tea_Dept1" . $i]?>
						</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">卒業年①</td>
						<td align="left" >
							<?php 
								if(is_null($_SESSION["Tea_Gradu1" . $i])){
								}else{
									if (is_numeric($_SESSION["Tea_Gradu1" . $i])) {
										echo $_SESSION["Tea_Gradu1" . $i];
										if(strlen($_SESSION["Tea_Gradu1" . $i])!=1){
											echo "年卒";
										}else{
											echo "年生";
										}
									}else{
										echo $_SESSION["Tea_Gradu1" . $i];
									}
								} 
							?>
						</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">卒業大学②</td>
						<td align="left" >
							<?php echo $_SESSION["Tea_Uni2" . $i]?>　<?php echo $_SESSION["Tea_Dept2" . $i]?>
						</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">卒業年②</td>
						<td align="left" >
							<?php 
								if(is_null($_SESSION["Tea_Gradu2" . $i])){
								}else{
									if (is_numeric($_SESSION["Tea_Gradu2" . $i])) {
										echo $_SESSION["Tea_Gradu2" . $i];
										if(strlen($_SESSION["Tea_Gradu2" . $i])!=1){
											echo "年卒";
										}else{
											echo "年生";
										}
									}else{
										echo $_SESSION["Tea_Gradu2" . $i];
									}
								} 
							?>
						</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">沿線①</td>
						<td align="left" >
							<?php echo $_SESSION["Tea_Ensen1" . $i]?>
						</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">沿線②</td>
						<td align="left" >
							<?php echo $_SESSION["Tea_Ensen2" . $i]?><BR>
						</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">沿線③</td>
						<td align="left" >
							【車使用】<?php if($_SESSION["Tea_Ensen3" . $i]==1){?>　可<?php } ?>
						</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">小学生</td>
						<td align="left">
							<?php if($_SESSION["Tea_Sub1_1" . $i] == 1){?>国<?php } ?>　
							<?php if($_SESSION["Tea_Sub1_2" . $i] == 1){?>算<?php } ?>　
							<?php if($_SESSION["Tea_Sub1_3" . $i] == 1){?>理<?php } ?>　
							<?php if($_SESSION["Tea_Sub1_4" . $i] == 1){?>社<?php } ?>　
							<?php if($_SESSION["Tea_Sub1_5" . $i] == 1){?>英<?php } ?>　
							<?php if($_SESSION["Tea_Sub1_6" . $i] == 1){?>私立受験<?php } ?>
						</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">中学生</td>
						<td align="left">
							<?php if($_SESSION["Tea_Sub2_1" . $i] == 1){?>国<?php } ?>　
							<?php if($_SESSION["Tea_Sub2_2" . $i] == 1){?>算<?php } ?>　
							<?php if($_SESSION["Tea_Sub2_3" . $i] == 1){?>理<?php } ?>　
							<?php if($_SESSION["Tea_Sub2_4" . $i] == 1){?>社<?php } ?>　
							<?php if($_SESSION["Tea_Sub2_5" . $i] == 1){?>英<?php } ?>　
							<?php if($_SESSION["Tea_Sub2_6" . $i] == 1){?>高校受験<?php } ?>　
						</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0"rowspan="4">高校生</td>
						<td align="left">
							<?php if($_SESSION["Tea_Sub3_1" . $i] == 1){?>現文<?php } ?>　
							<?php if($_SESSION["Tea_Sub3_2" . $i] == 1){?>古文<?php } ?>　
							<?php if($_SESSION["Tea_Sub3_3" . $i] == 1){?>漢文<?php } ?>　
							<?php if($_SESSION["Tea_Sub3_4" . $i] == 1){?>小論文<?php } ?>　
							<?php if($_SESSION["Tea_Sub3_5" . $i] == 1){?>英語<?php } ?>　
							<?php if($_SESSION["Tea_Sub3_6" . $i] == 1){?>大学受験<?php } ?>　

						</td>
					</tr>
					<tr>
						<td align="left">
							<?php if($_SESSION["Tea_Sub3_7" . $i] == 1){?>数Ⅰ<?php } ?>　
							<?php if($_SESSION["Tea_Sub3_8" . $i] == 1){?>数Ａ<?php } ?>　
							<?php if($_SESSION["Tea_Sub3_9" . $i] == 1){?>数Ⅱ<?php } ?>　
							<?php if($_SESSION["Tea_Sub3_10" . $i] == 1){?>数Ｂ<?php } ?>　
							<?php if($_SESSION["Tea_Sub3_11" . $i] == 1){?>数Ⅲ<?php } ?>　
							<?php if($_SESSION["Tea_Sub3_12" . $i] == 1){?>数Ｃ<?php } ?>　
						</td>
					</tr>
					<tr>
						<td align="left">
							<?php if($_SESSION["Tea_Sub3_13" . $i] == 1){?>物理<?php } ?>　
							<?php if($_SESSION["Tea_Sub3_14" . $i] == 1){?>化学<?php } ?>　
							<?php if($_SESSION["Tea_Sub3_15" . $i] == 1){?>生物<?php } ?>　
							<?php if($_SESSION["Tea_Sub3_16" . $i] == 1){?>地学<?php } ?>　
						</td>
					</tr>
					<tr>
						<td align="left">
							<?php if($_SESSION["Tea_Sub3_17" . $i] == 1){?>日本史<?php } ?>　
							<?php if($_SESSION["Tea_Sub3_18" . $i] == 1){?>世界史<?php } ?>　
							<?php if($_SESSION["Tea_Sub3_19" . $i] == 1){?>政経<?php } ?>　
							<?php if($_SESSION["Tea_Sub3_20" . $i] == 1){?>地理<?php } ?>　
						</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">その他</td>
						<td align="left">
							<?php echo $_SESSION["Tea_Sub4_1" . $i]?>
						</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">資格①</td>
						<td align="left"><?php echo $_SESSION["Tea_License1" . $i]?>　</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">資格②</td>
						<td align="left"><?php echo $_SESSION["Tea_License2" . $i]?>　</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">資格③</td>
						<td align="left"><?php echo $_SESSION["Tea_License3" . $i]?>　</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">出身中学</td>
						<td align="left"><?php echo $_SESSION["Tea_Gra_Hight" . $i]?>　</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">教職課程</td>
						<td align="left"><?php if($_SESSION["Tea_Exp_Kyou" . $i] == "1"){ ?>有<?php }elseif($_SESSION["Tea_Exp_Kyou" . $i] == "0"){ ?>無<?php } ?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">出身高校</td>
						<td align="left" ><?php echo $_SESSION["Tea_Gra_Junior" . $i]?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">中学受験</td>
						<td align="left"><?php if($_SESSION["Tea_Exp_Juken" . $i] == "1"){ ?>有<?php }elseif($_SESSION["Tea_Exp_Juken" . $i] == "0"){ ?>無<?php } ?></td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">備考①</td>
						<td align="left"><?php echo $_SESSION["Tea_Other1" . $i]?>　</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">備考②</td>
						<td align="left"><?php echo $_SESSION["Tea_Other2" . $i]?>　</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">備考③</td>
						<td align="left"><?php echo $_SESSION["Tea_Other3" . $i]?>　</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">備考④</td>
						<td align="left"><?php echo $_SESSION["Tea_Other4" . $i]?>　</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">紹介文</td>
						<td align="left"><?php echo $_SESSION["Tea_Other5" . $i]?>　</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">その他①</td>
						<td align="left"><?php echo $_SESSION["Tea_Notice2" . $i]?>　</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">その他②</td>
						<td align="left"><?php echo $_SESSION["Tea_Notice3" . $i]?>　</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">その他③</td>
						<td align="left"><?php echo $_SESSION["Tea_Notice4" . $i]?>　</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">その他④</td>
						<td align="left"><?php echo $_SESSION["Tea_Notice5" . $i]?>　</td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">顔画像</td>
						<td align="left">
                            <a href="<?php echo $_SESSION["ImageName_" . $i . "_0"] ?>" target="_blank"><?php echo $_SESSION["ImageName_" . $i . "_0"] ?></a><BR>
                        </td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">履歴書</td>
						<td align="left">
                            <?php for ($h = 0; $h< $_SESSION["FileNameCnt_" . $i]; $h++) { ?>
                                <a href="<?php echo $_SESSION["FileName_" . $i . "_" . $h] ?>" target="_blank"><?php echo $_SESSION["FileName_" . $i . "_" . $h] ?></a><BR>
                            <?php } ?>
                        </td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">その他書類</td>
						<td align="left">
                            <?php for ($h = 0; $h< $_SESSION["FileNameCnt_sonota_" . $i]; $h++) { ?>
                                <a href="<?php echo $_SESSION["FileName_sonota_" . $i . "_" . $h] ?>" target="_blank"><?php echo $_SESSION["FileName_sonota_" . $i . "_" . $h] ?></a><BR>
                            <?php } ?>
                        </td>
					</tr>
					<tr>
						<td align="left" bgcolor="#c0c0c0">その他画像</td>
						<td align="left">
                            <?php for ($h = 0; $h< $_SESSION["ImageNameCnt_sonota_" . $i]; $h++) { ?>
                                <a href="<?php echo $_SESSION["ImageName_sonota_" . $i . "_" . $h] ?>" target="_blank"><?php echo $_SESSION["ImageName_sonota_" . $i . "_" . $h] ?></a><BR>
                            <?php } ?>
                        </td>
					</tr>
				</table>
				</div>
			</div>
			</td>
			<?php $no++; ?>
			<?php } else { ?>
			<?php } ?>
			<?php } ?>
		</tr>
	</table>
</form>
</body>
</CENTER>
</html>