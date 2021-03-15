<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN"
	"http://www.w3.org/TR/html4/frameset.dtd">

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="Header.css">
	<title>検索画面</title>
</head>

<?php include 'const.php'; ?>
<?php include 'utility.php'; ?>

<?php
session_start();

//ini_set( 'display_errors', 1 );
$query2 = "";
	$dt = new DateTime();
	$dt->setTimeZone(new DateTimeZone('Asia/Tokyo'));
	$Today = $dt->format('Y/m/d');
	$EMSG = "";


//print("★submitter=" . $_POST['submitter'] . "<BR>");

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
//print("★T02_kensaku_RPID=" . $_SESSION["T02_kensaku_RPID"] . "<BR>");
	if(isset($_POST['submitter'])){
		if($_POST['submitter']=="modoru"){
		 	ModoruShori($_SESSION["T02_kensaku_RPID"]);
			exit;
		}
	}

	// 生徒選択処理
	for ($m = 0; $m < $_SESSION["T02_DataCount"]; $m++){
		if(isset($_POST["No_" . $m])){
			$_SESSION["T03_TeacherID"]=$_POST['T02_TeacherID' . $m];
			$Location = "T03_index.php?MODE=UPD&RPID=T02_Kensaku&KEY1=" . $_POST['T02_TeacherID' . $m] . "&KUBUN=0";
		 	header("Location: {$Location}");
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

		//セッション情報保存
		//前画面からの情報
//		$TeacherID = $_SESSION["TeacherID"];
		$user_name = $_SESSION["user_name"];
		$shikaku = (int) $_SESSION["shikaku"];
		$TName1 = $_SESSION["TName1"];
		$TName2 = $_SESSION["TName2"];

		//URLパラメータ
		if(isset($_GET['RPID'])) {
			$_SESSION["T02_kensaku_RPID"] = $_GET['RPID'];
		      	//print($_SESSION["T02_kensaku_RPID"]);
		}
		if(isset($_GET['MODE'])) {
			if($_GET['MODE'] != "Modoru"){
				$_SESSION["T02_kensaku_MODE"] = $_GET['MODE'];
				$_SESSION["ShoriID"]=$_GET['MODE'];
			      	//print($_SESSION["K_kensaku_MODE"] . "<BR>");
			}
		}
		if(isset($_GET['KEY1'])) {
			$_SESSION["Kensaku_KEY1"] = $_GET['KEY1'];
		}
		if(isset($_GET['KEY2'])) {
			$_SESSION["Kensaku_KEY2"] = $_GET['KEY2'];
		}

		switch ($_SESSION["ShoriID"]){
			case 'KEN':
				SessionClear();
				GetData();
				if($_SESSION["Kensaku_KEY2"]==1){
					$Msg = CSVShori();
					$_SESSION["MSG"] = $Msg;
				}
				break;
		}	
	}
//-----------------------------------------------------------
//	セッション情報クリア
//-----------------------------------------------------------
function SessionClear(){
	$_SESSION["CSVCreat"] = 0;
	$_SESSION["MSG"] = "";
}
//-----------------------------------------------------------
//	セッション情報クリア
//-----------------------------------------------------------
function SessionClearbkp(){

	$_SESSION["TourokuFlg"]=1;
	$_SESSION["T_KihonInfo_DataCount"]=0;
	$_SESSION["T_KihonInfo_DataCount2"]=0;
	$_SESSION["K_ToDofuken_DataCount"]=0;

	for($dataidx=0; $dataidx<100; $dataidx++){
		$_SESSION["T_KihonInfo_AddData_" .$dataidx] = "";
		$_SESSION["T_KihonInfo_AddData2_" .$dataidx] = "";
	}

	$_SESSION["K_TeacherID"] = "";
	$_SESSION["K_TeacherName"] = "";
	$_SESSION["K_TeacherKana"] = "";
	$_SESSION["K_TeacherKen"] = "";
	$_SESSION["K_TeacherShi"] = "";
	$_SESSION["K_TeacherKu"] = "";
	$_SESSION["K_TeacherAdd"] = "";
	$_SESSION["K_TeacherTel"] = "";
	$_SESSION["K_TeacherOld1"] = "";
	$_SESSION["K_TeacherOld2"] = "";
	$_SESSION["K_TeacherSei"] = "";
	$_SESSION["K_TeacherStartDay1"] = "";
	$_SESSION["K_TeacherStartDay2"] = "";
	$_SESSION["K_ShokaiDay"] = "";
	$_SESSION["K_Jyotai"] = "";
	$_SESSION["kyouka1-1"] = "0";
	$_SESSION["kyouka1-2"] = "0";
	$_SESSION["kyouka1-3"] = "0";
	$_SESSION["kyouka1-4"] = "0";
	$_SESSION["kyouka1-5"] = "0";
	$_SESSION["kyouka1-6"] = "0";
	$_SESSION["kyouka2-1"] = "0";
	$_SESSION["kyouka2-2"] = "0";
	$_SESSION["kyouka2-3"] = "0";
	$_SESSION["kyouka2-4"] = "0";
	$_SESSION["kyouka2-5"] = "0";
	$_SESSION["kyouka2-6"] = "0";
	$_SESSION["kyouka3-1"] = "0";
	$_SESSION["kyouka3-2"] = "0";
	$_SESSION["kyouka3-3"] = "0";
	$_SESSION["kyouka3-4"] = "0";
	$_SESSION["kyouka3-5"] = "0";
	$_SESSION["kyouka3-6"] = "0";
	$_SESSION["kyouka3-7"] = "0";
	$_SESSION["kyouka3-8"] = "0";
	$_SESSION["kyouka3-9"] = "0";
	$_SESSION["kyouka3-10"] = "0";
	$_SESSION["kyouka3-11"] = "0";
	$_SESSION["kyouka3-12"] = "0";
	$_SESSION["kyouka3-13"] = "0";
	$_SESSION["kyouka3-14"] = "0";
	$_SESSION["kyouka3-15"] = "0";
	$_SESSION["kyouka3-16"] = "0";
	$_SESSION["kyouka3-17"] = "0";
	$_SESSION["kyouka3-18"] = "0";
	$_SESSION["kyouka3-19"] = "0";
	$_SESSION["kyouka3-20"] = "0";
	$_SESSION["kyouka_sonota"] = "";

	$CodeData = array();
	$CodeData = GetCodeData("評価","","",1);
	$_SESSION["28CodeData"]=$CodeData;

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

		$query = "Select a.TeacherID , a.Name1, a.Name2, a.Hyoka, a.Hyokamemo, a.EntryTime,";
		$query = $query . " b.EntryDay, b.Add_Ken_Code, b.Add_ken, b.Add_shi, b.Add_ku, b.Add_cho, b.Yubin1, b.Yubin2,";

		$query = $query . " CONCAT(IFNULL(b.Add_ken, ''), IFNULL(b.Add_shi, ''), IFNULL(b.Add_ku,''), IFNULL(b.Add_cho,'')) as Address,";
		$query = $query . " CONCAT(IFNULL(b.Yubin1, ''), '-',IFNULL(b.Yubin2, '')) as Yubin,";

		$query = $query . " b.BirthDay, b.Seibetu, b.Tel1, b.Tel2, b.Tel3, b.Mail1, b.Mail2, b.Mail3,";
		$query = $query . " b.Mail1_1, b.Mail1_2, b.Mail1_3, b.Mail2_1, b.Mail2_2, b.Mail2_3, b.Mail3_1,b.Mail3_2,b.Mail3_3,";  
		$query = $query . " CONCAT(IFNULL(b.Tel1, ''),'<BR>',IFNULL(b.Tel2, ''),'<BR>',IFNULL(b.Tel3, '')) as Tel,";
		$query = $query . " CONCAT(IFNULL(b.Mail1, ''),'<BR>',IFNULL(b.Mail2, ''),'<BR>',IFNULL(b.Mail3, '')) as Mail,";

		$query = $query . " b.Uni1, b.Dept1, b.Gradu1, b.Uni2, b.Dept2, b.Gradu2,";

		$query = $query . " CONCAT(IFNULL(b.Uni1, ''),'　',IFNULL(b.Dept1, ''),'　',IFNULL(b.Gradu1, '')) as Uni,";
		$query = $query . " CONCAT(IFNULL(b.Uni2, ''),'　',IFNULL(b.Dept2, ''),'　',IFNULL(b.Gradu2, '')) as Uni2,";
		$query = $query . " b.License1, b.License2, b.License3, b.Exp_Kyou, b.Exp_Juken, b.Gra_Hight, b.Gra_Junior,";
		$query = $query . " CONCAT(REPLACE(IFNULL(b.License1, ''),'\r\n','<BR>'),'<BR>',REPLACE(IFNULL(b.License2, ''),'\r\n','<BR>'),'<BR>',REPLACE(IFNULL(b.License3, ''),'\r\n','<BR>')) as License,";

		$query = $query . " b.Ensen1, b.Ensen2, b.Ensen3, b.Other1, b.Other2, b.Other3, b.Other4, b.Other5,";
		$query = $query . " CONCAT(IFNULL(b.Ensen1, ''),'<BR>',IFNULL(b.Ensen2, '')) as Ensen,";
		$query = $query . " CONCAT(REPLACE(IFNULL(b.Other1, ''),'\r\n','<BR>'),'<BR>',REPLACE(IFNULL(b.Other2, ''),'\r\n','<BR>'),'<BR>',REPLACE(IFNULL(b.Other3, ''),'\r\n','<BR>'),'<BR>',REPLACE(IFNULL(b.Other4, ''),'\r\n','<BR>'),'<BR>',REPLACE(IFNULL(b.Other5, ''),'\r\n','<BR>')) as Other,";

		$query = $query . " c.Sub1_1, c.Sub1_2, c.Sub1_3, c.Sub1_4, c.Sub1_5, c.Sub1_6, c.Sub1_7, c.Sub1_8, c.Sub1_9, c.Sub1_10,";
		$query = $query . " c.Sub2_1, c.Sub2_2, c.Sub2_3, c.Sub2_4, c.Sub2_5, c.Sub2_6, c.Sub2_7, c.Sub2_8, c.Sub2_9, c.Sub2_10,";
		$query = $query . " c.Sub3_1, c.Sub3_2, c.Sub3_3, c.Sub3_4, c.Sub3_5, c.Sub3_6, c.Sub3_7, c.Sub3_8, c.Sub3_9, c.Sub3_10,";
		$query = $query . " c.Sub3_11, c.Sub3_12, c.Sub3_13, c.Sub3_14, c.Sub3_15, c.Sub3_16, c.Sub3_17, c.Sub3_18, c.Sub3_19, c.Sub3_20,";
		$query = $query . " c.Sub3_21, c.Sub3_22, c.Sub3_23, c.Sub3_24, c.Sub3_25,";
		$query = $query . " c.Sub4_1, c.Sub4_2, c.Sub4_3, c.Sub4_4, c.Sub4_5,";
		$query = $query . " c.Notice1, c.Notice2, c.Notice3, c.Notice4, c.Notice5,";
		$query = $query . " CONCAT(IFNULL(c.Notice1, ''),'<BR>',IFNULL(c.Notice2, ''),'<BR>',IFNULL(c.Notice3, ''),'<BR>',IFNULL(c.Notice4, ''),'<BR>',IFNULL(c.Notice5, '')) as Notice";

		$query = $query . " FROM T_AtenaInfo as a inner join";
		$query = $query . " T_KihonInfo as b on";
		$query = $query . " a.TeacherID=b.TeacherID";
		$query = $query . " inner join T_ShosaiInfo as c on";
		$query = $query . " a.TeacherID=c.TeacherID";


		if($_SESSION["K_TeacherID"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where a.TeacherID=" . $_SESSION["K_TeacherID"];
			}else{
				$query2 = $query2 . " And a.TeacherID=" . $_SESSION["K_TeacherID"];
			}
		}
		if($_SESSION["K_TeacherName"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where replace(replace(a.Name1,' ',''),'　','') like '%" . preg_replace("/( |　)/", "", $_SESSION["K_TeacherName"]) . "%'";
			}else{
				$query2 = $query2 . " And replace(replace(a.Name1,' ',''),'　','') like '%" . preg_replace("/( |　)/", "", $_SESSION["K_TeacherName"]) . "%'";
			}
		}

		if($_SESSION["K_TeacherKana"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where replace(replace(a.Name2,' ',''),'　','') like '%" . preg_replace("/( |　)/", "", $_SESSION["K_TeacherKana"]) . "%'";
			}else{
				$query2 = $query2 . " And replace(replace(a.Name2,' ',''),'　','') like '%" . preg_replace("/( |　)/", "", $_SESSION["K_TeacherKana"]) . "%'";
			}
		}
		if($_SESSION["K_TeacherKen"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where b.Add_Ken_Code ='" . $_SESSION["K_TeacherKen"] . "'";
			}else{
				$query2 = $query2 . " And b.Add_Ken_Code ='" . $_SESSION["K_TeacherKen"] . "'";
			}
		}
		if($_SESSION["K_TeacherShi"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where b.Add_shi like '%" . $_SESSION["K_TeacherShi"] . "%'";
			}else{
				$query2 = $query2 . " And b.Add_shi like '%" . $_SESSION["K_TeacherShi"] . "%'";
			}
		}
		if($_SESSION["K_TeacherKu"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where b.Add_ku like '%" . $_SESSION["K_TeacherKu"] . "%'";
			}else{
				$query2 = $query2 . " And b.Add_ku like '%" . $_SESSION["K_TeacherKu"] . "%'";
			}
		}
		if($_SESSION["K_TeacherAdd"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where b.Add_cho like '%" . $_SESSION["K_TeacherAdd"] . "%'";
			}else{
				$query2 = $query2 . " And b.Add_cho like '%" . $_SESSION["K_TeacherAdd"] . "%'";
			}
		}
		if($_SESSION["K_TeacherTel"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where (b.Tel1 ='" . $_SESSION["K_TeacherTel"] . "'";
				$query2 = $query2 . " OR b.Tel2 ='" . $_SESSION["K_TeacherTel"] . "'";
				$query2 = $query2 . " OR b.Tel3 ='" . $_SESSION["K_TeacherTel"] . "')";
			}else{
				$query2 = $query2 . " And (b.Tel1 ='" . $_SESSION["K_TeacherTel"] . "'";
				$query2 = $query2 . " OR b.Tel2 ='" . $_SESSION["K_TeacherTel"] . "'";
				$query2 = $query2 . " OR b.Tel3 ='" . $_SESSION["K_TeacherTel"] . "')";
			}
		}
		if($_SESSION["K_TeacherOld1"]!=""){
			$Old1 = mb_substr($_SESSION["K_TeacherOld1"],0,2);
			$Old2 = mb_substr($_SESSION["K_TeacherOld1"],0,1);
			$OldStart = mb_convert_kana($Old1, "n");
			$OldEnd = mb_convert_kana($Old2, "n") . 9;
			$start = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 1, date('Y') - $OldStart - 1));
			$end = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 1, date('Y') - $OldEnd - 1));
			if($query2 == ""){
				$query2 = $query2 . " Where b.Birthday >= '" . $end . "' and b.Birthday <='" . $start . "'";
			}else{
				$query2 = $query2 . " And ((b.Birthday >= '" . $end . "' and b.Birthday <='" . $start . "') or b.Birthday is null)";  
			}
		}
		if($_SESSION["K_TeacherOld2"]!=""){
			$start = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 1, date('Y') - $_SESSION["K_TeacherOld2"] - 1));
			$end = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 1, date('Y') - $_SESSION["K_TeacherOld2"]));
			if($query2 == ""){
				$query2 = $query2 . " Where b.Birthday >= '" . $start . "' and b.Birthday <='" . $end . "'";
			}else{
				$query2 = $query2 . " And b.Birthday >= '" . $start . "' and b.Birthday <='" . $end . "'";
			}
		}
		if($_SESSION["K_TeacherSei"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where b.Seibetu = '" . $_SESSION["K_TeacherSei"] . "'";
			}else{
				$query2 = $query2 . " And b.Seibetu = '" . $_SESSION["K_TeacherSei"] . "'";
			}
		}
		if($_SESSION["K_TeacherStartDay1"]!="" && $_SESSION["K_TeacherStartDay2"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where b.EntryDay >= '" . $_SESSION["K_TeacherStartDay1"] . "' and b.EntryDay <= '" . $_SESSION["K_TeacherStartDay2"] . "'";
			}else{
				$query2 = $query2 . " And b.EntryDay >= '" . $_SESSION["K_TeacherStartDay1"] . "' and b.EntryDay <= '" . $_SESSION["K_TeacherStartDay2"] . "'";
			}
		}elseif($_SESSION["K_TeacherStartDay1"]!="" && $_SESSION["K_TeacherStartDay2"]==""){
			if($query2 == ""){
				$query2 = $query2 . " Where b.EntryDay >= '" . $_SESSION["K_TeacherStartDay1"] . "'";
			}else{
				$query2 = $query2 . " And b.EntryDay >= '" . $_SESSION["K_TeacherStartDay1"] . "'";
			}
		}elseif($_SESSION["K_TeacherStartDay1"]=="" && $_SESSION["K_TeacherStartDay2"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where b.EntryDay <= '" . $_SESSION["K_TeacherStartDay2"] . "'";
			}else{
				$query2 = $query2 . " And b.EntryDay <= '" . $_SESSION["K_TeacherStartDay2"] . "'";
			}
		}
		for($i=0; $i < $_SESSION["05CodeData"]["05DataCount"]; $i++){
			$idx = $i + 1;
			if($_SESSION["kyouka1-" .$idx]!=0){
				if($query2 == ""){
					$query2 = $query2 . " Where c.Sub1_" . $idx . "='1'";
				}else{
					$query2 = $query2 . " And c.Sub1_" . $idx . "='1'";
				}
			}
		}
		for($i=0; $i < $_SESSION["06CodeData"]["06DataCount"]; $i++){
			$idx = $i + 1;
			if($_SESSION["kyouka2-" .$idx]!=0){
				if($query2 == ""){
					$query2 = $query2 . " Where c.Sub2_" . $idx . "='1'";
				}else{
					$query2 = $query2 . " And c.Sub2_" . $idx . "='1'";
				}
			}
		}
		for($i=0; $i < $_SESSION["07CodeData"]["07DataCount"]; $i++){
			$idx = $i + 1;
			if($_SESSION["kyouka3-" .$idx]!=0){
				if($query2 == ""){
					$query2 = $query2 . " Where c.Sub3_" . $idx . "='1'";
				}else{
					$query2 = $query2 . " And c.Sub3_" . $idx . "='1'";
				}
			}
		}
		if($_SESSION["kyouka_sonota"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where c.Sub4_1 like '%" . $_SESSION["kyouka_sonota"] . "%'";
			}else{
				$query2 = $query2 . " And c.Sub4_1 like '%" . $_SESSION["kyouka_sonota"] . "%'";
			}
		}

		for($i=0; $i < $_SESSION["28CodeData"]["28DataCount"]; $i++){
			$idx = $i + 1;
			if($_SESSION["Hyoka-" .$idx]!=0){
				if($i == $_SESSION["28CodeData"]["28DataCount"]-1){
					if($HyokaCnt==0){
						if($query2 == ""){
							$query2 = $query2 . " Where (a.Hyoka" . "='99'";
						}else{
							$query2 = $query2 . " And (a.Hyoka" . "='99'";
						}
					}else{
						$query2 = $query2 . " or a.Hyoka" . "='99'";
					}
				}else{
					if($HyokaCnt==0){
						if($query2 == ""){
							$query2 = $query2 . " Where (a.Hyoka" . "='" . str_pad($idx, 2, 0, STR_PAD_LEFT) . "'";
						}else{
							$query2 = $query2 . " And (a.Hyoka" . "='" . str_pad($idx, 2, 0, STR_PAD_LEFT) . "'";
						}
					}else{
						$query2 = $query2 . " or a.Hyoka" . "='" . str_pad($idx, 2, 0, STR_PAD_LEFT) . "'";
					}
				}
				$HyokaCnt ++;
			}
		}
		if($HyokaCnt > 0){
			$query2 = $query2 . ")";
		}
		//除外条件
		if($query2 == ""){
			if($_SESSION["K_KensakuHyoji1"]==1 && $_SESSION["K_KensakuHyoji2"]==0){
				$query2 = $query2 . " Where KensakuHyoji=1";
			}elseif($_SESSION["K_KensakuHyoji1"]==0 && $_SESSION["K_KensakuHyoji2"]==1){
				$query2 = $query2 . " Where KensakuHyoji=0";
			}
		}else{
			if($_SESSION["K_KensakuHyoji1"]==1 && $_SESSION["K_KensakuHyoji2"]==0){
				$query2 = $query2 . " And KensakuHyoji=1";
			}elseif($_SESSION["K_KensakuHyoji1"]==0 && $_SESSION["K_KensakuHyoji2"]==1){
				$query2 = $query2 . " And KensakuHyoji=0";
			}
		}
		for($i=0; $i < $_SESSION["30CodeData"]["30DataCount"]; $i++){
			$idx = $i + 1;
			if($query2 == ""){
				if($_SESSION["K_KensakuJyoken" . $idx . "_1"]==1 && $_SESSION["K_KensakuJyoken" . $idx . "_2"]==0){
					$query2 = $query2 . " Where KensakuJyoken" . $idx . "=1";
				}elseif($_SESSION["K_KensakuJyoken" . $idx . "_1"]==0 && $_SESSION["K_KensakuJyoken" . $idx . "_2"]==1){
					$query2 = $query2 . " Where KensakuJyoken" . $idx . "=0";
				}
			}else{
				if($_SESSION["K_KensakuJyoken" . $idx . "_1"]==1 && $_SESSION["K_KensakuJyoken" . $idx . "_2"]==0){
					$query2 = $query2 . " And KensakuJyoken" . $idx . "=1";
				}elseif($_SESSION["K_KensakuJyoken" . $idx . "_1"]==0 && $_SESSION["K_KensakuJyoken" . $idx . "_2"]==1){
					$query2 = $query2 . " And KensakuJyoken" . $idx . "=0";
				}
			}
		}

//		$query2 = $query2 . " Order by cast(a.TeacherID as signed)";
//		$query2 = $query2 . " Order by b.Add_Ken_Code, b.Add_ken, b.Add_shi, b.Add_ku, b.Add_cho";
		if($_SESSION["Sort1"]!="" or $_SESSION["Sort2"]!="" or $_SESSION["Sort3"]!="" or $_SESSION["Sort4"]!=""){
			$query2 = $query2 . " Order by ";
			for($idx=1; $idx<=4; $idx++){
				if($_SESSION["Sort" . $idx]!=""){
					switch ($_SESSION["Sort" . $idx]){
						case '教師ID':
							if($query3 == ""){
								$query3 = $query3 . "cast(a.TeacherID as signed)";
							}else{
								$query3 = $query3 . ",cast(a.TeacherID as signed)";
							}
							break;
						case '氏名':
							if($query3 == ""){
								$query3 = $query3 . "a.Name2";
							}else{
								$query3 = $query3 . ",a.Name2";
							}
							break;
						case '住所':
							if($query3 == ""){
								$query3 = $query3 . "b.Add_Ken_Code, b.Add_ken, b.Add_shi, b.Add_ku, b.Add_cho";
							}else{
								$query3 = $query3 . ",b.Add_Ken_Code, b.Add_ken, b.Add_shi, b.Add_ku, b.Add_cho";
							}
							break;
						case '年齢':
							if($query3 == ""){
								$query3 = $query3 . "b.BirthDay desc";
							}else{
								$query3 = $query3 . ",b.BirthDay desc";
							}
							break;
					}	
				}
			}
		}else{
			$query2 = $query2 . " Order by b.Add_Ken_Code, b.Add_ken, b.Add_shi, b.Add_ku, b.Add_cho";
			$query3 = "";
		}
		$query = $query . $query2 . $query3;
//print($query);
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
				$_SESSION["T02_" . $key .$i]="";
				$data[$i][$key] = $value;
				$_SESSION["T02_" . $key .$i]=$data[$i][$key];
			}

//			$_SESSION["T02_TeacherID" .$i]=$data[$i]['TeacherID'];
//			$_SESSION["T02_Name1" .$i]=$data[$i]['Name1'];
//			$_SESSION["T02_BirthDay" .$i]=$data[$i]['BirthDay'];
//			$_SESSION["T02_Seibetu" .$i]=$data[$i]['Seibetu'];
//			$_SESSION["T02_Tel1" .$i]=$data[$i]['Tel1'];
//			$_SESSION["T02_Tel2" .$i]=$data[$i]['Tel2'];
//			$_SESSION["T02_Tel3" .$i]=$data[$i]['Tel3'];
//			$_SESSION["T02_Add_Ken_Code" .$i]=$data[$i]['Add_Ken_Code'];
//			$_SESSION["T02_Add_ken" .$i]=$data[$i]['Add_ken'];
//			$_SESSION["T02_Add_shi" .$i]=$data[$i]['Add_shi'];
//			$_SESSION["T02_Add_ku" .$i]=$data[$i]['Add_ku'];
//			$_SESSION["T02_Add_cho" .$i]=$data[$i]['Add_cho'];
//			$_SESSION["T02_EntryTime" .$i]=$data[$i]['EntryTime'];
//			$_SESSION["T02_Mail1" .$i]=$data[$i]['Mail1'];
//			$_SESSION["T02_Mail2" .$i]=$data[$i]['Mail2'];
//			$_SESSION["T02_Mail3" .$i]=$data[$i]['Mail3'];
//			$_SESSION["T02_EntryDay" .$i]=$data[$i]['EntryDay'];

			$query4 = "Select A.*,B.Name1,B.Name2,B.old,B.gread from T_TantoShosai as A inner join S_AtenaInfo as B on A.StudentID=B.StudentID and A.AtenaSeq=B.Seq";
			$query4 = $query4 . " Where A.TeacherID=" . $_SESSION["T02_TeacherID" .$i];
			$query4 = $query4 . " Order by A.EndDay desc,A.StartDay";

//print($query4 . "<BR>");

			$result2 = $mysqli->query($query4);

			if (!$result2) {
				print('クエリーが失敗しました。' . $mysqli->error);
				$mysqli->close();
				exit();
			}

			$data2 = array();
			$m = 0;
			$KeiyakuCnt = 0;
			$JissekiCnt = 0;
			$StudentData = "";
			while($arr_item = $result2->fetch_assoc()){

				//レコード内の各フィールド名と値を順次参照
				foreach($arr_item as $key => $value){
					//フィールド名と値を表示
					$data[$m][$key] = $value;
					$_SESSION["TS02_" . $key .$m]=$data[$m][$key];
				}
				if(is_Null($_SESSION["TS02_EndDay" .$m])){
					$KeiyakuCnt = $KeiyakuCnt + 1;
					$KeiyakuColer1 = "<font color=red>";
					$KeiyakuColer2 = "</font>";
				}else{
					$JissekiCnt = $JissekiCnt + 1;
					$KeiyakuColer1 = "";
					$KeiyakuColer2 = "";
				}
				$StudentData = $StudentData . $KeiyakuColer1 . $_SESSION["TS02_Name1" .$m] . "　" . $_SESSION["TS02_Pay" .$m] . "　" . $_SESSION["TS02_KiteiKaisu" .$m] . "　" . $_SESSION["TS02_KiteiJikan" .$m] . "　" . $_SESSION["TS02_StartDay" .$m] . "～" . $_SESSION["TS02_EndDay" .$m] . $KeiyakuColer2 . "<BR>";
			}

			if($KeiyakuCnt > 0){
				$_SESSION["T02_KeiyakuFlg" . $i] = "契約有";
			}else{
				if($KeiyakuCnt > 0){
					$_SESSION["T02_KeiyakuFlg" . $i] = "実績有";
				}
			}
			$_SESSION["T02_StudentData" . $i] = $StudentData;

//print("T02_KeiyakuFlg=" . $_SESSION["T02_KeiyakuFlg" . $i] . "<BR>");
//print("T02_StudentData=" . $_SESSION["T02_StudentData" . $i] . "<BR>");

			$_SESSION["T02_KeiyakuDataCount"] = $m;

			$i++;
		}
		$_SESSION["T02_DataCount"] = $i;

	 	// データベースの切断
		$mysqli->close();

}
//-----------------------------------------------------------
//	セーブ処理
//-----------------------------------------------------------
Function SaveShori(){

	$_SESSION["K_TeacherID"] = $_POST['K_TeacherID'];
	$_SESSION["K_TeacherName"] = $_POST['K_TeacherName'];
	$_SESSION["K_TeacherKana"] = $_POST['K_TeacherKana'];
	$_SESSION["K_TeacherKen"] = $_POST['K_TeacherKen'];
	$_SESSION["K_TeacherShi"] = $_POST['K_TeacherShi'];
	$_SESSION["K_TeacherKu"] = $_POST['K_TeacherKu'];
	$_SESSION["K_TeacherAdd"] = $_POST['K_TeacherAdd'];
	$_SESSION["K_TeacherTel"] = $_POST['K_TeacherTel'];
	$_SESSION["K_TeacherOld1"] = $_POST['K_TeacherOld1'];
	$_SESSION["K_TeacherOld2"] = $_POST['K_TeacherOld2'];
	if(isset($_POST['K_TeacherSei'])){
		$_SESSION["K_TeacherSei"] = $_POST['K_TeacherSei'];
	}
	$_SESSION["K_TeacherStartDay1"] = $_POST['K_TeacherStartDay1'];
	$_SESSION["K_TeacherStartDay2"] = $_POST['K_TeacherStartDay2'];
	$_SESSION["K_ShokaiDay"] = $_POST['K_ShokaiDay'];
	$_SESSION["K_Jyotai"] = $_POST['K_Jyotai'];

	for($i=1; $i<=6; $i++){
		if(isset($_POST['kyouka1-' . $i])){
			$_SESSION["kyouka1-" . $i] = $_POST['kyouka1-' . $i];
		}
	}
	for($i=1; $i<=6; $i++){
		if(isset($_POST['kyouka2-' . $i])){
			$_SESSION["kyouka2-" . $i] = $_POST['kyouka2-' . $i];
		}
	}
	for($i=1; $i<=20; $i++){
		if(isset($_POST['kyouka3-' . $i])){
			$_SESSION["kyouka3-" . $i] = $_POST['kyouka3-' . $i];
		}
	}
	if(isset($_POST['kyouka_sonota' . $i])){
		$_SESSION["kyouka_sonota"] = $_POST['kyouka_sonota'];
	}

}
//-----------------------------------------------------------
//	CSV処理
//-----------------------------------------------------------
Function CSVShori2(){
$errflg = 0;
$errmsg = "";

	$filename = "Teacher.csv";
	ftruncate($filename,0);

//print("filename" . $filename . "<BR>");
//print("i" . $i . "<BR>");

		//ファイルオープン
		$handle = fopen( $filename, 'a+');
		if ($handle){
			$errmsg = "成功";
		}else{
			$errmsg = "オープンに失敗しました";
			$errflg = 1;
		}

//print($errmsg . "<BR>");

		$Line = "aaaa";

//print($Line . "<BR>");
		fwrite($handle,  $Line);

		$flag = fclose($handle);
//print($flag . "<BR>");
		if ($flag){
			$errmsg = "";
		}else{
			$errmsg = "クローズに失敗しました";
			$errflg = 1;
		}

	if($errflg == 0){
		$_SESSION["CSVCreat"] = 1;
	}else{
		$_SESSION["CSVCreat"] = 0;
	}
	return $errmsg;

}
//-----------------------------------------------------------
//	CSV処理
//-----------------------------------------------------------
Function CSVShori(){
$errflg = 0;
$errmsg = "";

	$filename = "Teacher.csv";
	$handle = fopen( $filename, 'a');
	ftruncate($handle,0);

	for($i=0; $i<$_SESSION["T02_DataCount"]; $i++){
//print("filename" . $filename . "<BR>");
//print("i" . $i . "<BR>");

		//ファイルオープン
		$handle = fopen( $filename, 'a');

		if($i==0){
			$Line = "No,";
			$Line = $Line . "教師ID,";
			$Line = $Line . "教師氏名,";
			$Line = $Line . "教師かな,";
			$Line = $Line . "生年月日,";
			$Line = $Line . "性別,";
			$Line = $Line . "郵便番号,";
			$Line = $Line . "都道府県,";
			$Line = $Line . "住所,";
			$Line = $Line . "電話番号,";
			$Line = $Line . "メール,";
			$Line = $Line . "登録日,";
			$Line = $Line . "出身校１,";
			$Line = $Line . "出身校２,";
			$Line = $Line . "資格,";
			$Line = $Line . "教職,";
			$Line = $Line . "受験,";
			$Line = $Line . "出身高校,";
			$Line = $Line . "出身中学,";
			$Line = $Line . "教科,";
			$Line = $Line . "その他１,";
			$Line = $Line . "その他２";
			$Line = $Line . "\n";
		}else{
			$Line = $i+1 . ",";
			$Line = $Line . $_SESSION["T02_TeacherID" .$i] . ",";
			$Line = $Line . $_SESSION["T02_Name1" .$i] . ",";
			$Line = $Line . $_SESSION["T02_Name2" .$i] . ",";
			if(is_null($_SESSION["T02_BirthDay" .$i])){
				$Line = $Line . ",";
			}else{
				$Line = $Line . floor ((date('Ymd') - date('Ymd', strtotime($_SESSION["T02_BirthDay" .$i])))/10000) . ",";
			}
			if($_SESSION["T02_Seibetu" .$i]=="1"){
				$Line = $Line . "男" . ",";
			}else{
				$Line = $Line . "女" . ",";
			}
			$Line = $Line . $_SESSION["T02_Yubin" .$i] . ",";
			$Line = $Line . $_SESSION["T02_Add_ken" .$i] . ",";
			$Line = $Line . str_replace(",", "、", $_SESSION["T02_Address" .$i]) . ",";
			$Line = $Line . str_replace(",", "、", str_replace("<BR>", "　", $_SESSION["T02_Tel" .$i])) . ",";
			$Line = $Line . str_replace("<BR>", "　", $_SESSION["T02_Mail" .$i]) . ",";
			if(is_null($_SESSION["T02_EntryDay" .$i])){
				$Line = $Line . ",";
			}else{
				$Line = $Line . date('Y年n月j日', strtotime($_SESSION["T02_EntryDay" .$i])) . ",";
			} 
			$Line = $Line . str_replace(",", "、", $_SESSION["T02_Uni" .$i]) . ",";
			$Line = $Line . str_replace(",", "、", $_SESSION["T02_Uni2" .$i]) . ",";
			$Line = $Line . str_replace(",", "、", $_SESSION["T02_License" .$i]) . ",";
			if($_SESSION["T02_Exp_Kyou" .$i]==1){
				$Line = $Line . "有" . ",";
			}else{
				$Line = $Line . ",";
			}
			if($_SESSION["T02_Exp_Juken" .$i]==1){
				$Line = $Line . "有" . ",";
			}else{
				$Line = $Line . ",";
			}
			$Line = $Line . str_replace(",", "、", $_SESSION["T02_Gra_Hight" .$i]) . ",";
			$Line = $Line . str_replace(",", "、", $_SESSION["T02_Gra_Junior" .$i]) . ",";
			$Line = $Line . str_replace(",", "、", $_SESSION["T02_Ensen" .$i]) . ",";
			$Line = $Line . str_replace(",", "、", $_SESSION["T02_Sub4_1" .$i]) . ",";
			$Line = $Line . str_replace(",", "、", $_SESSION["T02_Other" .$i]) . ",";
			$Line = $Line . str_replace(",", "、", $_SESSION["T02_Notice" .$i]) . ",";
			$Line = nl2br($Line);
			$Line = str_replace("<BR>", "　", $Line);
			$Line = str_replace("<br />", "　", $Line);
			$Line = str_replace(" ", "", $Line);
			$Line = $Line . "\n";
			$Line2 = $Line;
		}

		$Line = mb_convert_encoding($Line,"SJIS","auto");
//print($Line2 ."<BR>");
		if ($handle){
		    if (flock($handle, LOCK_EX)){
		        if (fwrite($handle, $Line) === FALSE){
		        	$errmsg = $Line."のファイル書き込みに失敗しました<br>";
				$errflg = 1;
		        }else{
		        	$errmsg = "";
				$errflg = 0;
		        }

		        flock($handle, LOCK_UN);
		    }else{
		        $errmsg = "ファイルロックに失敗しました<br>";
			$errflg = 1;
		    }
		}

		$flag = fclose($handle);

		if ($flag){
			$errmsg = "";
		}else{
			$errmsg = "クローズに失敗しました";
			$errflg = 1;
		}
		if($errflg == 1){
			break;
		}
	}
	if($errflg == 0){
		$_SESSION["CSVCreat"] = 1;
	}else{
		$_SESSION["CSVCreat"] = 0;
	}
	return $errmsg;

}
?>

<script type="text/javascript" src="utility.js"></script>

<CENTER>
<body onload="<?php if($_SESSION["K_kensaku_MODE"] == "KENT") {?> document.form1.K_TeacherID.focus(); <?php }else{ ?> document.form1.K_StudentID.focus(); <?php } ?>">

<form name="form1" method="post" action="T02_Kensaku.php">
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
		<tr align="left">
			<td align="left">
				<?php if($_SESSION["CSVCreat"] == 1){ ?><a href="Teacher.csv" target="_blank">ファイルを開く</a><?php } ?>
			</td>
		</tr>
	</table>
	<BR>
	<table border="0" width="100%">
		<font size="5" color="#ff0000"><?php echo $_SESSION["MSG"] ?></font>
	</table>
	<BR><BR>
	<table border="0" width="100%">
		<tr><td>
			<table border="1">
				<tr>
					<td width="10" height="30" align="center" bgcolor="#c0c0c0">No</td>
					<?php if($_SESSION["Disp_TeacherID"]==1){?>
						<td width="100" align="center" bgcolor="#c0c0c0"><?php echo $_SESSION["DispName_TeacherID"]?></td>
					<?php } ?>
					<?php if($_SESSION["Disp_Name1"]==1){?>
						<td width="150" align="center" bgcolor="#c0c0c0"><?php echo $_SESSION["DispName_Name1"]?></td>
					<?php } ?>
					<?php if($_SESSION["Disp_BirthDay"]==1){?>
						<td width="50" align="center" bgcolor="#c0c0c0"><?php echo $_SESSION["DispName_BirthDay"]?></td>
					<?php } ?>
					<?php if($_SESSION["Disp_Seibetu"]==1){?>
						<td width="50" align="center" bgcolor="#c0c0c0"><?php echo $_SESSION["DispName_Seibetu"]?></td>
					<?php } ?>
					<?php if($_SESSION["Disp_Address"]==1){?>
						<td width="400" align="center" bgcolor="#c0c0c0"><?php echo $_SESSION["DispName_Address"]?></td>
					<?php } ?>
					<?php if($_SESSION["Disp_Tel"]==1){?>
						<td width="120" align="center" bgcolor="#c0c0c0"><?php echo $_SESSION["DispName_Tel"]?></td>
					<?php } ?>
					<?php if($_SESSION["Disp_Mail"]==1){?>
						<td width="100" align="center" bgcolor="#c0c0c0"><?php echo $_SESSION["DispName_Mail"]?></td>
					<?php } ?>
					<?php if($_SESSION["Disp_EntryDay"]==1){?>
						<td width="120" align="center" bgcolor="#c0c0c0"><?php echo $_SESSION["DispName_EntryDay"]?></td>
					<?php } ?>
					<?php if($_SESSION["Disp_Uni"]==1){?>
						<td width="200" align="center" bgcolor="#c0c0c0"><?php echo $_SESSION["DispName_Uni"]?></td>
					<?php } ?>
					<?php if($_SESSION["Disp_License"]==1){?>
						<td width="200" align="center" bgcolor="#c0c0c0"><?php echo $_SESSION["DispName_License"]?></td>
					<?php } ?>
					<?php if($_SESSION["Disp_Exp_Kyou_Juken"]==1){?>
						<td width="100" align="center" bgcolor="#c0c0c0"><?php echo $_SESSION["DispName_Exp_Kyou_Juken"]?></td>
					<?php } ?>
					<?php if($_SESSION["Disp_Gra_Hight_Junior"]==1){?>
						<td width="100" align="center" bgcolor="#c0c0c0"><?php echo $_SESSION["DispName_Gra_Hight_Junior"]?></td>
					<?php } ?>
					<?php if($_SESSION["Disp_Ensen"]==1){?>
						<td width="120" align="center" bgcolor="#c0c0c0"><?php echo $_SESSION["DispName_Ensen"]?></td>
					<?php } ?>
					<?php if($_SESSION["Disp_Sub"]==1){?>
						<td width="200" align="center" bgcolor="#c0c0c0"><?php echo $_SESSION["DispName_Sub"]?></td>
					<?php } ?>
					<?php if($_SESSION["Disp_Other"]==1){?>
						<td width="400" align="center" bgcolor="#c0c0c0"><?php echo $_SESSION["DispName_Other"]?></td>
					<?php } ?>
					<?php if($_SESSION["Disp_Notice"]==1){?>
						<td width="100" align="center" bgcolor="#c0c0c0"><?php echo $_SESSION["DispName_Notice"]?></td>
					<?php } ?>
					<?php if($_SESSION["Disp_Jisseki"]==1){?>
						<td width="200" align="center" bgcolor="#c0c0c0"><?php echo $_SESSION["DispName_Jisseki"]?></td>
					<?php } ?>

				</tr>
				<?php for($i=0; $i<$_SESSION["T02_DataCount"]; $i++){ ?>
					<tr>
						<td height="30" align="center"><input type="submit" name="No_<?php echo $i ?>" size="10" value="<?php echo $i+1 ?>"></td>
						<?php if($_SESSION["Disp_TeacherID"]==1){?>
							<td align="center"><BR><?php echo $_SESSION["T02_TeacherID" .$i] ?><BR>
                                <?php if($_SESSION["T02_DataCount"] <=100){?>
                                    <?php fileDownload($_SESSION["T02_TeacherID" .$i]); ?>
                                    <img src="<?php echo $_SESSION["ImageNameDisp_0"] ?>" width="110" height="120" alt="顔写真">
                                <?php } ?>
                            <BR></td>
						<?php } ?>
						<?php if($_SESSION["Disp_Name1"]==1){?>
							<td align="left">
								<?php echo $_SESSION["T02_Name1" .$i] . "<BR>" . $_SESSION["T02_Name2" .$i] . "<BR>"?>
								<?php for($dataidx=0; $dataidx < $_SESSION["28CodeData"]["28DataCount"]; $dataidx++){ ?>
									<?php if($_SESSION["28CodeData"]["28_Eda_" . $dataidx] == $_SESSION["T02_Hyoka" .$i]){ ?> 
										<?php if($_SESSION["T02_Hyoka" .$i]==88 ||$_SESSION["T02_Hyoka" .$i]==99){?><font color="red"><?php } ?>【<?php echo $_SESSION["28CodeData"]["28_CodeName1_" . $dataidx] ?>】<?php if($_SESSION["T02_Hyoka" .$i]==88 ||$_SESSION["T02_Hyoka" .$i]==99){?></font><?php } ?>
									<?php } ?>
								<?php } ?>
							</td>
						<?php } ?>
						<?php if($_SESSION["Disp_BirthDay"]==1){?>
							<td align="center"><?php if(is_null($_SESSION["T02_BirthDay" .$i])){?><?php }else{ ?><?php echo floor ((date('Ymd') - date('Ymd', strtotime($_SESSION["T02_BirthDay" .$i])))/10000); ?><?php } ?></td>
						<?php } ?>
						<?php if($_SESSION["Disp_Seibetu"]==1){?>
							<td align="center"><?php if($_SESSION["T02_Seibetu" .$i]=="1"){?>男<?php }else{?>女<?php } ?></td>
						<?php } ?>
						<?php if($_SESSION["Disp_Address"]==1){?>
							<td align="left"><?php echo $_SESSION["T02_Yubin" .$i] . "　" . $_SESSION["T02_Address" .$i]?></td>
						<?php } ?>
						<?php if($_SESSION["Disp_Tel"]==1){?>
							<td align="left"><?php echo $_SESSION["T02_Tel" .$i] ?></td>
						<?php } ?>
						<?php if($_SESSION["Disp_Mail"]==1){?>
							<td align="left"><?php echo $_SESSION["T02_Mail" .$i] ?></td>
						<?php } ?>
						<?php if($_SESSION["Disp_EntryDay"]==1){?>
							<td align="center"><?php if(is_null($_SESSION["T02_EntryDay" .$i])){ }else{ echo date('Y年n月j日', strtotime($_SESSION["T02_EntryDay" .$i])); } ?></td>
						<?php } ?>
						<?php if($_SESSION["Disp_Uni"]==1){?>
							<td align="left"><?php echo $_SESSION["T02_Uni" .$i] ?></td>
						<?php } ?>
						<?php if($_SESSION["Disp_License"]==1){?>
							<td align="left"><?php echo $_SESSION["T02_License" .$i] ?></td>
						<?php } ?>
						<?php if($_SESSION["Disp_Exp_Kyou_Juken"]==1){?>
							<td align="left"><?php if($_SESSION["T02_Exp_Kyou" .$i]==1){?>有<?php } ?> / <?php if($_SESSION["T02_Exp_Juken" .$i]==1){?>有<?php } ?></td>
						<?php } ?>
						<?php if($_SESSION["Disp_Gra_Hight_Junior"]==1){?>
							<td align="left"><?php echo $_SESSION["T02_Gra_Hight" .$i] . "/" . $_SESSION["T02_Gra_Junior" .$i] ?></td>
						<?php } ?>
						<?php if($_SESSION["Disp_Ensen"]==1){?>
							<td align="left"><?php echo $_SESSION["T02_Ensen" .$i] ?><BR><?php if($_SESSION["T02_Ensen3" .$i]==1){ ?>【車使用】可<?php } ?></td>
						<?php } ?>
						<?php if($_SESSION["Disp_Sub"]==1){?>
							<td align="left"><?php echo $_SESSION["T02_Sub4_1" .$i] ?></td>
						<?php } ?>
						<?php if($_SESSION["Disp_Other"]==1){?>
							<td align="left"><?php echo $_SESSION["T02_Other" .$i] ?></td>
						<?php } ?>
						<?php if($_SESSION["Disp_Notice"]==1){?>
							<td align="left"><?php echo $_SESSION["T02_Notice" .$i] ?></td>
						<?php } ?>
						<?php if($_SESSION["Disp_Jisseki"]==1){?>
							<td align="left"><?php echo $_SESSION["T02_StudentData" .$i] ?></td>
						<?php } ?>

						<input type="hidden" name="T02_TeacherID<?php echo $i ?>" value="<?php echo $_SESSION["T02_TeacherID" .$i]; ?>">

					</tr>
				<?php } ?>
			</table>
			</td>
			<?php if($_SESSION["Disp_Mail2"]==1){?>
			<td>
			<table border="1">
				<tr>
					<td width="300" height="30" align="center" bgcolor="#c0c0c0">mailアドレス</td>
				</tr>
				<?php for($i=0; $i<$_SESSION["T02_DataCount"]; $i++){ ?>
					<tr>
						<td height="30" align="left">
							<?php if($_SESSION["K_Mail1"]==0 && $_SESSION["K_Mail2"]==0){ ?>
								<?php echo $_SESSION["T02_Mail1" .$i] ?><BR>
								<?php echo $_SESSION["T02_Mail2" .$i] ?><BR>
								<?php echo $_SESSION["T02_Mail3" .$i] ?><BR>
							<?php }elseif($_SESSION["K_Mail1"]==1 && $_SESSION["K_Mail2"]==0){?>
								<?php if($_SESSION["T02_Mail1_1" .$i]==0){?>
									<?php echo $_SESSION["T02_Mail1" .$i] ?><BR>
								<?php } ?>
								<?php if($_SESSION["T02_Mail2_1" .$i]==0){?>
									<?php echo $_SESSION["T02_Mail2" .$i] ?><BR>
								<?php } ?>
								<?php if($_SESSION["T02_Mail3_1" .$i]==0){?>
									<?php echo $_SESSION["T02_Mail3" .$i] ?><BR>
								<?php } ?>
							<?php }elseif($_SESSION["K_Mail1"]==1 && $_SESSION["K_Mail2"]==1){?>
								<?php if($_SESSION["T02_Mail1_1" .$i]==0 && $_SESSION["T02_Mail1_2" .$i]==0){?>
									<?php echo $_SESSION["T02_Mail1" .$i] ?><BR>
								<?php } ?>
								<?php if($_SESSION["T02_Mail2_1" .$i]==0 && $_SESSION["T02_Mail2_2" .$i]){?>
									<?php echo $_SESSION["T02_Mail2" .$i] ?><BR>
								<?php } ?>
								<?php if($_SESSION["T02_Mail3_1" .$i]==0 && $_SESSION["T02_Mail3_2" .$i]==0){?>
									<?php echo $_SESSION["T02_Mail3" .$i] ?><BR>
								<?php } ?>
							<?php }elseif($_SESSION["K_Mail1"]==0 && $_SESSION["K_Mail2"]==1){?>
								<?php if($_SESSION["T02_Mail1_2" .$i]==0){?>
									<?php echo $_SESSION["T02_Mail1" .$i] ?><BR>
								<?php } ?>
								<?php if($_SESSION["T02_Mail2_2" .$i]==0){?>
									<?php echo $_SESSION["T02_Mail2" .$i] ?><BR>
								<?php } ?>
								<?php if($_SESSION["T02_Mail3_2" .$i]==0){?>
									<?php echo $_SESSION["T02_Mail3" .$i] ?><BR>
								<?php } ?>
							<?php }?>
						</td>
					</tr>
				<?php } ?>
			</table>
			</td>
			<?php } ?>
		</tr>
	</table>

</form>
</body>
</CENTER>
</html>