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

ini_set( 'display_errors', 1 );

	$dt = new DateTime();
	$dt->setTimeZone(new DateTimeZone('Asia/Tokyo'));
	$Today = $dt->format('Y/m/d');
	$EMSG = "";

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
		 	ModoruShori($_SESSION["K_kensaku_RPID"]);
			exit;
		}
	}

	if(isset($_POST['K_TeacherKen'])){
		$_SESSION["ShoriID"] = "KNSAKU_KEN";
		SaveShori();
		SelectShiKu();
	}
	if(isset($_POST['K_TeacherShi'])){
		$_SESSION["ShoriID"] = "KNSAKU_KEN";
		SaveShori();
		SelectShiKu2();
	}

	// 検索処理
	if(isset($_POST['kensaku'])){
	 	$_SESSION["ShoriID"]="KENSAKU";
	}

	// クリア
	if(isset($_POST['clear'])){
	 	$_SESSION["ShoriID"]="CLEAR";
	}

	// クリア
	if(isset($_POST['csv'])){
	 	$_SESSION["ShoriID"]="CSV";
	}

	if(isset($_POST['submitter'])){

//print("submitter=" . $_POST['submitter'] . "<BR>");
		switch ($_POST['submitter']){
			case 'newshori': //新規登録
	 			$_SESSION["ShoriID"]="NEWSHORI";
				break;
			case 'insert': //取り込み
	 			$_SESSION["ShoriID"]="INSERT";
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
			$_SESSION["K_kensaku_RPID"] = $_GET['RPID'];
		      	//print($_SESSION["K_kensaku_RPID"]);
		}
		if(isset($_GET['MODE'])) {
			if($_GET['MODE'] != "Modoru"){
				$_SESSION["T01_kensaku_MODE"] = $_GET['MODE'];
				$_SESSION["ShoriID"]=$_GET['MODE'];
			      	//print($_SESSION["K_kensaku_MODE"] . "<BR>");
			}
		}
//print("ShoriID=" . $_SESSION["ShoriID"] . "<BR>");
				$CodeData = array();
				$CodeData = GetCodeData("年代","","",1);
				$_SESSION["02CodeData"]=$CodeData;

		switch ($_SESSION["ShoriID"]){
			case 'NEW':
				SessionClear();
				ShokiDataGet();
				$CodeData = array();
				$CodeData = GetCodeData("年代","","",1);
				$_SESSION["02CodeData"]=$CodeData;
				$CodeData2 = array();
				$CodeData2 = GetCodeData("状態教師","","",1);
				$_SESSION["03CodeData"]=$CodeData2;
				$CodeData3 = array();
				$CodeData3 = GetCodeData("小学教科","","",1);
				$_SESSION["05CodeData"]=$CodeData3;
				$CodeData4 = array();
				$CodeData4 = GetCodeData("中学教科","","",1);
				$_SESSION["06CodeData"]=$CodeData4;
				$CodeData5 = array();
				$CodeData5 = GetCodeData("高校教科","","",1);
				$_SESSION["07CodeData"]=$CodeData5;
				$CodeData6 = array();
				$CodeData6 = GetCodeData("教師検索並び順","","",1);
				$_SESSION["27CodeData"]=$CodeData6;
				$CodeData7 = array();
				$CodeData7 = GetCodeData("評価","","",1);
				$_SESSION["28CodeData"]=$CodeData7;
				$CodeData8 = array();
				$CodeData8 = GetCodeData("検索条件","","",1);
				$_SESSION["30CodeData"]=$CodeData8;
				$CodeData9 = array();
				$CodeData9 = GetCodeData("メール区分","","",1);
				$_SESSION["29CodeData"]=$CodeData9;

				break;
//			case 'KENSAKU_KEN': 'KENSAKU_SHI':
//				break;

			case 'CLEAR':
				SessionClear();
				ShokiDataGet();
				break;

			case 'KENSAKU':
			case 'CSV':
				SaveShori();
				$EMSG = K_CheckShori();
				if($EMSG == ""){
//					KensakuShori();
					$query2 = "";
					$HyokaCnt = 0;

					// mysqlへの接続
					$mysqli = new mysqli(HOST, USER, PASS);
					if ($mysqli->connect_errno) {
						print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
						exit();
					   		}

					// データベースの選択
					$mysqli->select_db(DBNAME);
					$mysqli->set_charset("utf8");

					$query = "Select a.TeacherID ,Count(*) AS CNT FROM T_AtenaInfo as a inner join";
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
							$query2 = $query2 . " Where b.KensakuHyoji=1";
						}elseif($_SESSION["K_KensakuHyoji1"]==0 && $_SESSION["K_KensakuHyoji2"]==1){
							$query2 = $query2 . " Where b.KensakuHyoji=0";
						}
					}else{
						if($_SESSION["K_KensakuHyoji1"]==1 && $_SESSION["K_KensakuHyoji2"]==0){
							$query2 = $query2 . " And b.KensakuHyoji=1";
						}elseif($_SESSION["K_KensakuHyoji1"]==0 && $_SESSION["K_KensakuHyoji2"]==1){
							$query2 = $query2 . " And b.KensakuHyoji=0";
						}
					}
					for($i=0; $i < $_SESSION["30CodeData"]["30DataCount"]; $i++){
						$idx = $i + 1;
						if($_SESSION["K_KensakuJyoken" .$idx . "_1"] == 1 && $_SESSION["K_KensakuJyoken" .$idx . "_2"] == 0){
							if($query2 == ""){
								$query2 = $query2 . " Where b.KensakuJyoken" . $idx . "='1'";
							}else{
								$query2 = $query2 . " And b.KensakuJyoken" . $idx . "='1'";
							}
						}elseif($_SESSION["K_KensakuJyoken" .$idx . "_1"] == 0 && $_SESSION["K_KensakuJyoken" .$idx . "_2"] == 1){
							if($query2 == ""){
								$query2 = $query2 . " Where b.KensakuJyoken" . $idx . "='0'";
							}else{
								$query2 = $query2 . " And b.KensakuJyoken" . $idx . "='0'";
							}
						}
					}

					$query = $query . $query2;
//print($query);
					$result = $mysqli->query($query);

					if (!$result) {
						print('クエリーが失敗しました。' . $mysqli->error);
						$mysqli->close();
						exit();
					}
					
					while ($row = $result->fetch_assoc()) {
						$_SESSION["db_Count"] = $row['CNT'];
						$_SESSION["TeacherID"] = $row['TeacherID'];
					}
					
					$_SESSION["K_KEY1"]=$_SESSION["TeacherID"];

				 	// データベースの切断
					$mysqli->close();		

					if($_SESSION["db_Count"] == 0){
						$EMSG = "該当のデータがありません。";
					}else {
						if($_SESSION["ShoriID"] == "CSV"){
							header("Location:T02_Kensaku.php?MODE=KEN&RPID=T01_Kensaku&KEY1=" .$_SESSION["K_KEY1"] . "&KEY2=1");
						}else{
							header("Location:T02_Kensaku.php?MODE=KEN&RPID=T01_Kensaku&KEY1=" .$_SESSION["K_KEY1"] . "&KEY2=0");
						}
					}
				}

				break;
			case 'NEWSHORI':
				header("Location:T00_Atena01.php?MODE=NEW&RPID=T01_Kensaku");
				break;
			case 'INSERT':
				NewShori();
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
	$_SESSION["Hyoka-1"] = "0";
	$_SESSION["Hyoka-2"] = "0";
	$_SESSION["Hyoka-3"] = "0";
	$_SESSION["Hyoka-4"] = "0";
	$_SESSION["Hyoka-5"] = "0";
	$_SESSION["Hyoka-6"] = "0";
	$_SESSION["Hyoka-7"] = "0";
	$_SESSION["K_KensakuHyoji1"] = "1";
	$_SESSION["K_KensakuHyoji2"] = "0";
	$_SESSION["K_Mail1"] = "0";
	$_SESSION["K_Mail2"] = "0";
	$_SESSION["K_Mail3"] = "0";
	$_SESSION["K_KensakuJyoken1_1"] = "1";
	$_SESSION["K_KensakuJyoken1_2"] = "1";
	$_SESSION["K_KensakuJyoken2_1"] = "1";
	$_SESSION["K_KensakuJyoken2_2"] = "1";
	$_SESSION["K_KensakuJyoken3_1"] = "1";
	$_SESSION["K_KensakuJyoken3_2"] = "1";

	$_SESSION["K_TeacherStartDay1_COLER"] = "";
	$_SESSION["K_TeacherStartDay2_COLER"] = "";

	$_SESSION["Disp_TeacherID"]=1;
	$_SESSION["Disp_Name1"]=1;
	$_SESSION["Disp_Name2"]=1;
	$_SESSION["Disp_EntryDay"]=1; 
	$_SESSION["Disp_Address"]=1;
	$_SESSION["Disp_Yubin"]=1;
	$_SESSION["Disp_BirthDay"]=1; 
	$_SESSION["Disp_Seibetu"]=1;
	$_SESSION["Disp_Tel"]=1;
	$_SESSION["Disp_Mail"]=1;
	$_SESSION["Disp_Uni"]=1;
	$_SESSION["Disp_Uni2"]=1;
	$_SESSION["Disp_License"]=1;
	$_SESSION["Disp_Exp_Kyou_Juken"] =1;
	$_SESSION["Disp_Gra_Hight_Junior"]=1;
	$_SESSION["Disp_Ensen"]=1;
	$_SESSION["Disp_Other"]=1;
	$_SESSION["Disp_Sub"]=1;
	$_SESSION["Disp_Mail1"]=1;
	$_SESSION["Disp_Mail2"]=0;
	$_SESSION["Disp_Notice"]=1;
	$_SESSION["Disp_Jisseki"]=1;

	$_SESSION["DispName_TeacherID"]="教師ＩＤ";
	$_SESSION["DispName_Name1"]="氏名";
	$_SESSION["DispName_Name2"]="氏名かな";
	$_SESSION["DispName_EntryDay"]="登録日"; 
	$_SESSION["DispName_Address"]="住所";
	$_SESSION["DispName_Yubin"]="郵便番号";
	$_SESSION["DispName_BirthDay"]="年齢"; 
	$_SESSION["DispName_Seibetu"]="性別";
	$_SESSION["DispName_Tel"]="電話番号";
	$_SESSION["DispName_Mail"]="メール";
	$_SESSION["DispName_Uni"]="卒業大学";
	$_SESSION["DispName_License"]="資格";
	$_SESSION["DispName_Exp_Kyou_Juken"] ="教職・受験";
	$_SESSION["DispName_Gra_Hight_Junior"]="出身高校・中学";
	$_SESSION["DispName_Ensen"]="沿線";
	$_SESSION["DispName_Other"]="その他";
	$_SESSION["DispName_Notice"]="その他２";
	$_SESSION["DispName_Sub"]="教科";
	$_SESSION["DispName_Mail2"]="メールコピー用";
	$_SESSION["DispName_Jisseki"]="実績";

	$_SESSION["Sort1"] = "";
	$_SESSION["Sort2"] = "";
	$_SESSION["Sort3"] = "";
	$_SESSION["Sort4"] = "";


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
//	$_SESSION["K_ShokaiDay"] = $_POST['K_ShokaiDay'];
//	$_SESSION["K_Jyotai"] = $_POST['K_Jyotai'];

	for($i=1; $i<=6; $i++){
//print($i . "=★" . $_SESSION["kyouka1-" . $i] . "<BR>");
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
	if(isset($_POST['kyouka_sonota'])){
		$_SESSION["kyouka_sonota"] = $_POST['kyouka_sonota'];
	}

	for($i=1; $i<=7; $i++){
		if(isset($_POST['Hyoka-' . $i])){
			$_SESSION["Hyoka-" . $i] = $_POST['Hyoka-' . $i];
		}else{
			$_SESSION["Hyoka-" . $i] = 0;
		}

	}

//print($_POST['Disp_TeacherID'] . "<BR>");
	if(isset($_POST['Disp_TeacherID'])){
		$_SESSION["Disp_TeacherID"]=1;
	}else{
		$_SESSION["Disp_TeacherID"]=0;
	}
	if(isset($_POST['Disp_Name1'])){
		$_SESSION["Disp_Name1"]=1;
	}else{
		$_SESSION["Disp_Name1"]=0;
	}
	if(isset($_POST['Disp_Name2'])){
		$_SESSION["Disp_Name2"]=1;
	}else{
		$_SESSION["Disp_Name2"]=0;
	}
	if(isset($_POST['Disp_EntryDay'])){
		$_SESSION["Disp_EntryDay"]=1; 
	}else{
		$_SESSION["Disp_EntryDay"]=0; 
	}
	if(isset($_POST['Disp_Address'])){
		$_SESSION["Disp_Address"]=1;
	}else{
		$_SESSION["Disp_Address"]=0;
	}
	if(isset($_POST['Disp_Yubin'])){
		$_SESSION["Disp_Yubin"]=1;
	}else{
		$_SESSION["Disp_Yubin"]=0;
	}
	if(isset($_POST['Disp_BirthDay'])){
		$_SESSION["Disp_BirthDay"]=1; 
	}else{
		$_SESSION["Disp_BirthDay"]=0; 
	}
	if(isset($_POST['Disp_Seibetu'])){
		$_SESSION["Disp_Seibetu"]=1;
	}else{
		$_SESSION["Disp_Seibetu"]=0;
	}
	if(isset($_POST['Disp_Tel'])){
		$_SESSION["Disp_Tel"]=1;
	}else{
		$_SESSION["Disp_Tel"]=0;
	}
	if(isset($_POST['Disp_Mail'])){
		$_SESSION["Disp_Mail"]=1;
	}else{
		$_SESSION["Disp_Mail"]=0;
	}
	if(isset($_POST['Disp_Uni'])){
		$_SESSION["Disp_Uni"]=1;
	}else{
		$_SESSION["Disp_Uni"]=0;
	}
	if(isset($_POST['Disp_License'])){
		$_SESSION["Disp_License"]=1;
	}else{
		$_SESSION["Disp_License"]=0;
	}
	if(isset($_POST['Disp_Exp_Kyou_Juken'])){
		$_SESSION["Disp_Exp_Kyou_Juken"] =1;
	}else{
		$_SESSION["Disp_Exp_Kyou_Juken"] =0;
	}
	if(isset($_POST['Disp_Gra_Hight_Junior'])){
		$_SESSION["Disp_Gra_Hight_Junior"]=1;
	}else{
		$_SESSION["Disp_Gra_Hight_Junior"]=0;
	}
	if(isset($_POST['Disp_Ensen'])){
		$_SESSION["Disp_Ensen"]=1;
	}else{
		$_SESSION["Disp_Ensen"]=0;
	}
	if(isset($_POST['Disp_Other'])){
		$_SESSION["Disp_Other"]=1;
	}else{
		$_SESSION["Disp_Other"]=0;
	}
	if(isset($_POST['Disp_Sub'])){
		$_SESSION["Disp_Sub"]=1;
	}else{
		$_SESSION["Disp_Sub"]=0;
	}
	if(isset($_POST['Disp_Notice'])){
		$_SESSION["Disp_Notice"]=1;
	}else{
		$_SESSION["Disp_Notice"]=0;
	}
	if(isset($_POST['Disp_Jisseki'])){
		$_SESSION["Disp_Jisseki"]=1;
	}else{
		$_SESSION["Disp_Jisseki"]=0;
	}
	if(isset($_POST['Disp_Mail2'])){
		$_SESSION["Disp_Mail2"]=1;
	}else{
		$_SESSION["Disp_Mail2"]=0;
	}
	$_SESSION["Sort1"] = $_POST['Sort1'];
	$_SESSION["Sort2"] = $_POST['Sort2'];
	$_SESSION["Sort3"] = $_POST['Sort3'];
	$_SESSION["Sort4"] = $_POST['Sort4'];

	if(isset($_POST['K_KensakuHyoji1'])){
		$_SESSION["K_KensakuHyoji1"]=1;
	}else{
		$_SESSION["K_KensakuHyoji1"]=0;
	}
	if(isset($_POST['K_KensakuHyoji2'])){
		$_SESSION["K_KensakuHyoji2"]=1;
	}else{
		$_SESSION["K_KensakuHyoji2"]=0;
	}
	if(isset($_POST['K_Mail1'])){
		$_SESSION["K_Mail1"]=1;
	}else{
		$_SESSION["K_Mail1"]=0;
	}
	if(isset($_POST['K_Mail2'])){
		$_SESSION["K_Mail2"]=1;
	}else{
		$_SESSION["K_Mail2"]=0;
	}
	if(isset($_POST['K_Mail3'])){
		$_SESSION["K_Mail3"]=1;
	}else{
		$_SESSION["K_Mail3"]=0;
	}
	if(isset($_POST['K_Mail3'])){
		$_SESSION["K_Mail3"]=1;
	}else{
		$_SESSION["K_Mail3"]=0;
	}
	if(isset($_POST['K_KensakuJyoken1_1'])){
		$_SESSION["K_KensakuJyoken1_1"]=1;
	}else{
		$_SESSION["K_KensakuJyoken1_1"]=0;
	}
	if(isset($_POST['K_KensakuJyoken1_2'])){
		$_SESSION["K_KensakuJyoken1_2"]=1;
	}else{
		$_SESSION["K_KensakuJyoken1_2"]=0;
	}
	if(isset($_POST['K_KensakuJyoken2_1'])){
		$_SESSION["K_KensakuJyoken2_1"]=1;
	}else{
		$_SESSION["K_KensakuJyoken2_1"]=0;
	}
	if(isset($_POST['K_KensakuJyoken2_2'])){
		$_SESSION["K_KensakuJyoken2_2"]=1;
	}else{
		$_SESSION["K_KensakuJyoken2_2"]=0;
	}
	if(isset($_POST['K_KensakuJyoken3_1'])){
		$_SESSION["K_KensakuJyoken3_1"]=1;
	}else{
		$_SESSION["K_KensakuJyoken3_1"]=0;
	}
	if(isset($_POST['K_KensakuJyoken3_2'])){
		$_SESSION["K_KensakuJyoken3_2"]=1;
	}else{
		$_SESSION["K_KensakuJyoken3_2"]=0;
	}

}
//-----------------------------------------------------------
//	チェック処理
//-----------------------------------------------------------
function K_CheckShori(){
$ErrMsg = "";
$Background="background-color: #F5A9F2";

	$_SESSION["K_TeacherStartDay1_COLER"] = "";
	$_SESSION["K_TeacherStartDay2_COLER"] = "";

	//入力有無
//	if($_SESSION["K_TeacherID"]=="" && $_SESSION["K_TeacherName"]=="" && $_SESSION["K_TeacherKana"]=="" && $_SESSION["K_TeacherKen"]=="" && $_SESSION["K_TeacherShi"]=="" && $_SESSION["K_TeacherKu"]=="" && $_SESSION["K_TeacherAdd"]=="" && $_SESSION["K_TeacherTel"]=="" && $_SESSION["K_TeacherOld1"]=="" && $_SESSION["K_TeacherOld2"]=="" && $_SESSION["K_TeacherSei"]=="" && $_SESSION["K_TeacherStartDay1"]=="" && $_SESSION["K_TeacherStartDay2"]=="" ){
//		$ErrMsg = "検索条件を入力してください。";
//	}

	if($_SESSION["K_TeacherStartDay1"] != ""){
		if (strptime($_SESSION["K_TeacherStartDay1"], '%Y-%m-%d')) {
		}else{
			$ErrMsg = "登録日が不正です。";
			$_SESSION["K_TeacherStartDay1_COLER"] = $Background;
		}
	}
	if($_SESSION["K_TeacherStartDay2"] != ""){
		if (strptime($_SESSION["K_TeacherStartDay2"], '%Y-%m-%d')) {
		}else{
			$ErrMsg = "登録日が不正です。";
			$_SESSION["K_TeacherStartDay2_COLER"] = $Background;
		}
	}

	return $ErrMsg;

}
//-----------------------------------------------------------
//	都道府県プルダウン情報取得
//-----------------------------------------------------------
Function ShokiDataGet(){
	// mysqlへの接続
	$mysqli = new mysqli(HOST, USER, PASS);
	if ($mysqli->connect_errno) {
		print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
		exit();
	   		}

	// データベースの選択
	$mysqli->select_db(DBNAME);
	$mysqli->set_charset("utf8");

	$query = "SELECT * FROM K_ToDofuken";
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
			$data[$i][$key] = $value;
		}

		$_SESSION["K_ToDofuken_Code_" .$i]=$data[$i]['Code'];
		$_SESSION["K_ToDofuken_ChiikiCode_" .$i]=$data[$i]['ChiikiCode'];
		$_SESSION["K_ToDofuken_Todofuken_" .$i]=$data[$i]['Todofuken'];

		$i++;
	}
	$_SESSION["K_ToDofuken_DataCount"] = $i;

 	// データベースの切断
	$mysqli->close();		

}
//-----------------------------------------------------------
//	市プルダウン情報取得
//-----------------------------------------------------------
Function SelectShiKu(){

	// mysqlへの接続
	$mysqli = new mysqli(HOST, USER, PASS);

	if ($mysqli->connect_errno) {
		print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
		exit();
	   		}

	// データベースの選択
	$mysqli->select_db(DBNAME);
	$mysqli->set_charset("utf8");

	$query = "SELECT Add_shi As AddData FROM T_KihonInfo ";
	$query = $query . " Where Add_Ken_Code='" . $_SESSION["K_TeacherKen"] . "'";
	$query = $query . " Group by Add_shi";
	$query = $query . " Order by Add_shi";

//print($query ."<BR>");

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

		$_SESSION["T_KihonInfo_AddData_" .$i]=$data[$i]['AddData'];

		$i++;
	}
	$_SESSION["T_KihonInfo_DataCount"] = $i;

	$query = "SELECT Add_ku As AddData FROM T_KihonInfo ";
	$query = $query . " Where Add_Ken_Code='" . $_SESSION["K_TeacherKen"] . "'";
	$query = $query . " Group by Add_ku ";
	$query = $query . " Order by Add_ku";

//print($query ."<BR>");

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

		$_SESSION["T_KihonInfo_AddData2_" .$i]=$data[$i]['AddData'];

		$i++;
	}
	$_SESSION["T_KihonInfo_DataCount2"] = $i;

 	// データベースの切断
	$mysqli->close();

}
//-----------------------------------------------------------
//	市プルダウン情報取得
//-----------------------------------------------------------
Function SelectShiKu2(){

	// mysqlへの接続
	$mysqli = new mysqli(HOST, USER, PASS);

	if ($mysqli->connect_errno) {
		print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
		exit();
	   		}

	// データベースの選択
	$mysqli->select_db(DBNAME);
	$mysqli->set_charset("utf8");

	$query = "SELECT Add_shi As AddData FROM T_KihonInfo ";
	$query = $query . " Where Add_Ken_Code='" . $_SESSION["K_TeacherKen"] . "'";
	$query = $query . " Group by Add_shi";
	$query = $query . " Order by Add_shi";

//print($query ."<BR>");

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

		$_SESSION["T_KihonInfo_AddData_" .$i]=$data[$i]['AddData'];

		$i++;
	}
	$_SESSION["T_KihonInfo_DataCount"] = $i;

	$query = "SELECT Add_ku As AddData FROM T_KihonInfo ";
	$query = $query . " Where Add_Ken_Code='" . $_SESSION["K_TeacherKen"] . "'";
	$query = $query . " And Add_shi='" . $_SESSION["K_TeacherShi"] . "'";
	$query = $query . " Group by Add_ku ";
	$query = $query . " Order by Add_ku";

//print($query ."<BR>");

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

		$_SESSION["T_KihonInfo_AddData2_" .$i]=$data[$i]['AddData'];

		$i++;
	}
	$_SESSION["T_KihonInfo_DataCount2"] = $i;

 	// データベースの切断
	$mysqli->close();

}
?>

<script type="text/javascript" src="utility.js"></script>

<CENTER>
<body onload="document.form1.K_TeacherID.focus();">

<form name="form1" method="post" action="T01_Kensaku.php">
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
				<input type="button" id="newshori" name="newshori" onClick="sbmfnc(this,<?php echo $_SESSION["TourokuFlg"] ?>)" style="cursor: pointer" value="新規登録"  <?php if($_SESSION["shikaku2"]==0){ ?>Disabled<?php } ?>/>
			</td>
		</tr>
	</table>
	<BR>
	<table border="0" width="100%">
		<font size="5" color="#ff0000"><?php echo $EMSG ?></font>
	</table>
	<table border="0">
		<tr>
			<td id="midashi" align="center" bgcolor="#c0c0c0">教師ID</td>
			<td align="left"><input class="inputtype" type="text" size="20" maxlength="10" name="K_TeacherID" style="ime-mode: disabled;" value="<?php echo $_SESSION["K_TeacherID"] ?>"></td>
			<td id="midashi" align="center" bgcolor="#c0c0c0">教師名</td>
			<td align="left"><input class="inputtype" type="text" size="30" maxlength="20" name="K_TeacherName" style="ime-mode: active;" value="<?php echo $_SESSION["K_TeacherName"] ?>"></td>
			<td id="midashi" align="center" bgcolor="#c0c0c0">教師カナ</td>
			<td align="left"><input class="inputtype" type="text" size="30" maxlength="20" name="K_TeacherKana" style="ime-mode: active;" value="<?php echo $_SESSION["K_TeacherKana"] ?>"></td>
		</tr>
		<tr>
			<td id="midashi" align="center" bgcolor="#c0c0c0">都道府県</td>
			<td align="left">
				<select name="K_TeacherKen" class="selecttype" onchange="window.onbeforeunload = null;this.form.submit()">
					<option value="" <?php if($_SESSION["K_TeacherKen"] == ""){ ?> SELECTED <?php } ?>>　</option>
					<?php for($dataidx=0; $dataidx < $_SESSION["K_ToDofuken_DataCount"]; $dataidx++){ ?>
						<option value="<?php echo $_SESSION["K_ToDofuken_Code_" .$dataidx]?>" <?php if($_SESSION["K_ToDofuken_Code_" .$dataidx] == $_SESSION["K_TeacherKen"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["K_ToDofuken_Todofuken_" .$dataidx]?></option>
					<?php } ?>
				</select>
			</td>
			<td id="midashi" align="center" bgcolor="#c0c0c0">市区町村</td>
			<td align="left" colspan=3>
				<select name="K_TeacherShi" class="selecttype2" onchange="window.onbeforeunload = null;this.form.submit()">
					<option value="" <?php if($_SESSION["K_TeacherShi"] ==""){ ?> SELECTED <?php } ?>>　</option>
					<?php for($dataidx=0; $dataidx < $_SESSION["T_KihonInfo_DataCount"]; $dataidx++){ ?>
						<option value="<?php echo $_SESSION["T_KihonInfo_AddData_" .$dataidx]?>" <?php if($_SESSION["T_KihonInfo_AddData_" .$dataidx] ==$_SESSION["K_TeacherShi"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["T_KihonInfo_AddData_" .$dataidx]?></option>
					<?php } ?>
				</select>
				<select name="K_TeacherKu" class="selecttype2">
					<option value="" <?php if($_SESSION["K_TeacherKu"] ==""){ ?> SELECTED <?php } ?>>　</option>
					<?php for($dataidx=0; $dataidx < $_SESSION["T_KihonInfo_DataCount2"]; $dataidx++){ ?>
						<option value="<?php echo $_SESSION["T_KihonInfo_AddData2_" .$dataidx]?>" <?php if($_SESSION["T_KihonInfo_AddData2_" .$dataidx] ==$_SESSION["K_TeacherKu"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["T_KihonInfo_AddData2_" .$dataidx]?></option>
					<?php } ?>
				</select>
				<input class="inputtype" type="text" size="50" maxlength="80" name="K_TeacherAdd" style="ime-mode: active;" value="<?php echo $_SESSION["K_TeacherAdd"] ?>">
			</td>
		</tr>
		<tr>
			<td id="midashi" align="center" bgcolor="#c0c0c0">電話番号</td>
			<td align="left"><input class="inputtype" type="text" size="20" maxlength="20" name="K_TeacherTel" style="ime-mode: active;" value="<?php echo $_SESSION["K_TeacherTel"] ?>"></td>
			<td id="midashi" align="center" bgcolor="#c0c0c0">年齢</td>
			<td align="left">
				<select name="K_TeacherOld1" class="selecttype2">
					<option value="" <?php if($_SESSION["K_TeacherOld1"] == ""){ ?> SELECTED <?php } ?>></option>
					<?php for($dataidx=0; $dataidx < $_SESSION["02CodeData"]["02DataCount"]; $dataidx++){ ?>
						<option value="<?php echo $_SESSION["02CodeData"]["02_CodeName2_" . $dataidx] ?>" <?php if($_SESSION["02CodeData"]["02_CodeNo_" . $dataidx] == $_SESSION["K_TeacherOld1"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["02CodeData"]["02_CodeName2_" . $dataidx] ?></option>
					<?php } ?>
				</select>
				<select name="K_TeacherOld2" class="selecttype2">
					<option value="" <?php if($_SESSION["K_TeacherOld2"] == ""){ ?> SELECTED <?php } ?>></option>
					<?php for($dataidx=18; $dataidx < 60; $dataidx++){ ?>
						<option value="<?php echo $dataidx ?>" <?php if($_SESSION["K_TeacherOld2"] == $dataidx){ ?> SELECTED <?php } ?>><?php echo $dataidx ?></option>
					<?php } ?>
				</select>
			</td>
			<td id="midashi" width="80" align="center" bgcolor="#c0c0c0">性別</td>
			<td align="left">
				<input type="radio" name="K_TeacherSei" value="1" <?php if($_SESSION["K_TeacherSei"]==1){?> checked <?php } ?>>男
				<input type="radio" name="K_TeacherSei" value="2" <?php if($_SESSION["K_TeacherSei"]==2){?> checked <?php } ?>>女
			</td>
		</tr>
		<tr>
			<td id="midashi" width="80" align="center" bgcolor="#c0c0c0">登録日</td>
			<td width="600" align="left" colspan=6>
				<input type="text" size="20" class="inputtype" maxlength="10" name="K_TeacherStartDay1" style="ime-mode: active;<?php echo $_SESSION["K_TeacherStartDay1_COLER"] ?>" value="<?php echo $_SESSION["K_TeacherStartDay1"] ?>">
				～
				<input type="text" size="20" class="inputtype" maxlength="10" name="K_TeacherStartDay2" style="ime-mode: active;<?php echo $_SESSION["K_TeacherStartDay2_COLER"] ?>" value="<?php echo $_SESSION["K_TeacherStartDay2"] ?>">
			</td>
		</tr>
		<tr>
			<td id="midashi" width="80" align="center" bgcolor="#c0c0c0">評価</td>
			<td width="600" align="left" colspan=6>
				<?php for($i=0; $i < $_SESSION["28CodeData"]["28DataCount"]; $i++){ ?>
					<?php $idx = $i + 1?>
					<input type="checkbox" name="Hyoka-<?php echo $idx?>" value="<?php echo $idx ?>" <?php if($_SESSION["Hyoka-" .$idx]==$idx){?> CHECKED <?php } ?>><?php echo $_SESSION["28CodeData"]["28_CodeName1_" . $i] ?>
				<?php } ?>
			</td>
		</tr>

<!--
		<tr>
			<td id="midashi" width="80" align="center" bgcolor="#c0c0c0">初回日</td>
			<td width="600" align="left" colspan=6>
				<input type="text" size="20" class="inputtype" maxlength="10" name="K_ShokaiDay" style="ime-mode: active;" value="<?php echo $_SESSION["K_ShokaiDay"] ?>">
			</td>
		</tr>
		<tr>
			<td id="midashi" width="80" align="center" bgcolor="#c0c0c0">状態</td>
			<td width="600" align="left" colspan=6>
				<select name="K_Jyotai" class="selecttype2">
					<option value="" <?php if($_SESSION["K_Jyotai"] == ""){ ?> SELECTED <?php } ?>></option>
					<?php for($dataidx=0; $dataidx < $_SESSION["03CodeData"]["03DataCount"]; $dataidx++){ ?>
						<option value="<?php echo $_SESSION["03CodeData"]["03_CodeNo_" . $dataidx] ?>" <?php if($_SESSION["03CodeData"]["03_CodeNo_" . $dataidx] == $_SESSION["K_Jyotai"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["03CodeData"]["03_CodeName1_" . $dataidx] ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
-->
		<tr>
			<td id="midashi" width="80" align="center" bgcolor="#c0c0c0">
				教科
			</td>
			<td width="600" align="left" colspan=6>
				【小学生】
				<?php for($i=0; $i < $_SESSION["05CodeData"]["05DataCount"]; $i++){ ?>
					<?php $idx = $i + 1?>
					<input type="checkbox" name="kyouka1-<?php echo $idx?>" value="<?php echo $idx ?>" <?php if($_SESSION["kyouka1-" .$idx]==$idx){?> CHECKED <?php } ?>><?php echo $_SESSION["05CodeData"]["05_CodeName2_" . $i] ?>
				<?php } ?>
				<BR>【中学生】
				<?php for($i=0; $i < $_SESSION["06CodeData"]["06DataCount"]; $i++){ ?>
					<?php $idx = $i + 1?>
					<input type="checkbox" name="kyouka2-<?php echo $idx?>" value="<?php echo $idx ?>" <?php if($_SESSION["kyouka2-" .$idx]==$idx){?> CHECKED <?php } ?>><?php echo $_SESSION["06CodeData"]["06_CodeName2_" . $i] ?>
				<?php } ?>
				<BR>【高校生】<BR>
				　　　　　　
				<?php for($i=0; $i < 6; $i++){ ?>
					<?php $idx = $i + 1?>
					<input type="checkbox" name="kyouka3-<?php echo $idx?>" value="<?php echo $idx ?>" <?php if($_SESSION["kyouka3-" .$idx]==$idx){?> CHECKED <?php } ?>><?php echo $_SESSION["07CodeData"]["07_CodeName2_" . $i] ?>
				<?php } ?>
				<BR>
				　　　　　　
				<?php for($i=6; $i < 12; $i++){ ?>
					<?php $idx = $i + 1?>
					<input type="checkbox" name="kyouka3-<?php echo $idx?>" value="<?php echo $idx ?>" <?php if($_SESSION["kyouka3-" .$idx]==$idx){?> CHECKED <?php } ?>><?php echo $_SESSION["07CodeData"]["07_CodeName2_" . $i] ?>
				<?php } ?>
				<BR>
				　　　　　　
				<?php for($i=12; $i < 16; $i++){ ?>
					<?php $idx = $i + 1?>
					<input type="checkbox" name="kyouka3-<?php echo $idx?>" value="<?php echo $idx ?>" <?php if($_SESSION["kyouka3-" .$idx]==$idx){?> CHECKED <?php } ?>><?php echo $_SESSION["07CodeData"]["07_CodeName2_" . $i] ?>
				<?php } ?>
				<BR>
				　　　　　　
				<?php for($i=16; $i < $_SESSION["07CodeData"]["07DataCount"]; $i++){ ?>
					<?php $idx = $i + 1?>
					<input type="checkbox" name="kyouka3-<?php echo $idx?>" value="<?php echo $idx ?>" <?php if($_SESSION["kyouka3-" .$idx]==$idx){?> CHECKED <?php } ?>><?php echo $_SESSION["07CodeData"]["07_CodeName2_" . $i] ?>
				<?php } ?>
				　　　　　　
				<BR>【その他】<input class="inputtype" type="text" size="30" maxlength="20" name="kyouka_sonota" style="ime-mode: active;" value="<?php echo $_SESSION["kyouka_sonota"] ?>">
			</td>
		</tr>

	</table>
	<BR>
	<table border="0">
		<tr>
			<td id="midashi" align="center" bgcolor="#c0c0c0">表示項目</td>
			<td>
				<input type="checkbox" name="Disp_TeacherID" value="<?php echo $_SESSION["Disp_TeacherID"] ?>" <?php if($_SESSION["Disp_TeacherID"] == 1){?> CHECKED <?php } ?>><?php echo $_SESSION["DispName_TeacherID"] ?>　
				<input type="checkbox" name="Disp_Name1" value="<?php echo $_SESSION["Disp_Name1"] ?>" <?php if($_SESSION["Disp_Name1"] == 1){?> CHECKED <?php } ?>><?php echo $_SESSION["DispName_Name1"] ?>　
				<input type="checkbox" name="Disp_BirthDay" value="<?php echo $_SESSION["Disp_BirthDay"] ?>" <?php if($_SESSION["Disp_BirthDay"] == 1){?> CHECKED <?php } ?>><?php echo $_SESSION["DispName_BirthDay"] ?>　
				<input type="checkbox" name="Disp_Seibetu" value="<?php echo $_SESSION["Disp_Seibetu"] ?>" <?php if($_SESSION["Disp_Seibetu"] == 1){?> CHECKED <?php } ?>><?php echo $_SESSION["DispName_Seibetu"] ?>　
				<input type="checkbox" name="Disp_Address" value="<?php echo $_SESSION["Disp_Address"] ?>" <?php if($_SESSION["Disp_Address"] == 1){?> CHECKED <?php } ?>><?php echo $_SESSION["DispName_Address"] ?>　
				<input type="checkbox" name="Disp_Tel" value="<?php echo $_SESSION["Disp_Tel"] ?>" <?php if($_SESSION["Disp_Tel"] == 1){?> CHECKED <?php } ?>><?php echo $_SESSION["DispName_Tel"] ?>　
				<input type="checkbox" name="Disp_Mail" value="<?php echo $_SESSION["Disp_Mail"] ?>" <?php if($_SESSION["Disp_Mail"] == 1){?> CHECKED <?php } ?>><?php echo $_SESSION["DispName_Mail"] ?>　
				<input type="checkbox" name="Disp_EntryDay" value="<?php echo $_SESSION["Disp_EntryDay"] ?>" <?php if($_SESSION["Disp_EntryDay"] == 1){?> CHECKED <?php } ?>><?php echo $_SESSION["DispName_EntryDay"] ?>　
				<BR><BR>
				<input type="checkbox" name="Disp_Uni" value="<?php echo $_SESSION["Disp_Uni"] ?>" <?php if($_SESSION["Disp_Uni"] == 1){?> CHECKED <?php } ?>><?php echo $_SESSION["DispName_Uni"] ?>
				<input type="checkbox" name="Disp_License" value="<?php echo $_SESSION["Disp_License"] ?>" <?php if($_SESSION["Disp_License"] == 1){?> CHECKED <?php } ?>><?php echo $_SESSION["DispName_License"] ?>　
				<input type="checkbox" name="Disp_Exp_Kyou_Juken" value="<?php echo $_SESSION["Disp_Exp_Kyou_Juken"] ?>" <?php if($_SESSION["Disp_Exp_Kyou_Juken"] == 1){?> CHECKED <?php } ?>><?php echo $_SESSION["DispName_Exp_Kyou_Juken"] ?>　
				<input type="checkbox" name="Disp_Gra_Hight_Junior" value="<?php echo $_SESSION["Disp_Gra_Hight_Junior"] ?>" <?php if($_SESSION["Disp_Gra_Hight_Junior"] == 1){?> CHECKED <?php } ?>><?php echo $_SESSION["DispName_Gra_Hight_Junior"] ?>　
				<input type="checkbox" name="Disp_Ensen" value="<?php echo $_SESSION["Disp_Ensen"] ?>" <?php if($_SESSION["Disp_Ensen"] == 1){?> CHECKED <?php } ?>><?php echo $_SESSION["DispName_Ensen"] ?>　
				<input type="checkbox" name="Disp_Sub" value="<?php echo $_SESSION["Disp_Sub"] ?>" <?php if($_SESSION["Disp_Sub"] == 1){?> CHECKED <?php } ?>><?php echo $_SESSION["DispName_Sub"] ?>　
				<input type="checkbox" name="Disp_Other" value="<?php echo $_SESSION["Disp_Other"] ?>" <?php if($_SESSION["Disp_Other"] == 1){?> CHECKED <?php } ?>><?php echo $_SESSION["DispName_Other"] ?>　
				<input type="checkbox" name="Disp_Notice" value="<?php echo $_SESSION["Disp_Notice"] ?>" <?php if($_SESSION["Disp_Notice"] == 1){?> CHECKED <?php } ?>><?php echo $_SESSION["DispName_Notice"] ?>　
				<input type="checkbox" name="Disp_Jisseki" value="<?php echo $_SESSION["Disp_Jisseki"] ?>" <?php if($_SESSION["Disp_Jisseki"] == 1){?> CHECKED <?php } ?>><?php echo $_SESSION["DispName_Jisseki"] ?>　
				<input type="checkbox" name="Disp_Mail2" value="<?php echo $_SESSION["Disp_Mail2"] ?>" <?php if($_SESSION["Disp_Mail2"] == 1){?> CHECKED <?php } ?>><?php echo $_SESSION["DispName_Mail2"] ?>　
			</td>
		</tr>
		<tr>
			<td id="midashi" align="center" bgcolor="#c0c0c0">表示並び順</td>
			<td align="left">
				<select name="Sort1" class="selecttype2">
					<option value="" <?php if($_SESSION["Sort1"] == ""){ ?> SELECTED <?php } ?>></option>
					<?php for($dataidx=0; $dataidx < $_SESSION["27CodeData"]["27DataCount"]; $dataidx++){ ?>
						<option value="<?php echo $_SESSION["27CodeData"]["27_CodeName2_" . $dataidx] ?>" <?php if($_SESSION["27CodeData"]["27_CodeName2_" . $dataidx] == $_SESSION["Sort1"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["27CodeData"]["27_CodeName2_" . $dataidx] ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td id="midashi" align="center" bgcolor="#c0c0c0">表示条件</td>
			<td align="left">
				一覧表示
				<input type="checkbox" name="K_KensakuHyoji1" value="<?php echo $_SESSION["K_KensakuHyoji1"]?>" <?php if($_SESSION["K_KensakuHyoji1"] == 1){?> CHECKED <?php } ?>>ON
				<input type="checkbox" name="K_KensakuHyoji2" value="<?php echo $_SESSION["K_KensakuHyoji2"]?>" <?php if($_SESSION["K_KensakuHyoji2"] == 1){?> CHECKED <?php } ?>>OFF
				<BR><BR>
				<?php for($dataidx=0; $dataidx < $_SESSION["30CodeData"]["30DataCount"]; $dataidx++){ ?>
					<?php $dataidx2 = $dataidx + 1;?>
					<?php echo $_SESSION["30CodeData"]["30_CodeName2_" . $dataidx] ?>
					<input type="checkbox" name="K_KensakuJyoken<?php echo $dataidx2 ?>_1" value="<?php echo $_SESSION["K_KensakuJyoken" . $dataidx2] . "_1" ?>" <?php if($_SESSION["K_KensakuJyoken" . $dataidx2 . "_1"] == 1){?> CHECKED <?php } ?>>有
					<input type="checkbox" name="K_KensakuJyoken<?php echo $dataidx2 ?>_2" value="<?php echo $_SESSION["K_KensakuJyoken" . $dataidx2] . "_2" ?>" <?php if($_SESSION["K_KensakuJyoken" . $dataidx2 . "_1"] == 1){?> CHECKED <?php } ?>>無
				<?php } ?>
				<BR><BR>
				<?php for($dataidx=0; $dataidx < $_SESSION["29CodeData"]["29DataCount"]; $dataidx++){ ?>
					<?php $dataidx2 = $dataidx + 1;?>
					<input type="checkbox" name="K_Mail<?php echo $dataidx2 ?>" value="<?php echo $_SESSION["K_Mail" . $dataidx2]?>" <?php if($_SESSION["K_Mail" . $dataidx2] == 1){?> CHECKED <?php } ?>>メール<?php echo $_SESSION["29CodeData"]["29_CodeName1_" . $dataidx] ?>除外
				<?php } ?>
			</td>
		</tr>
	</table>
	<BR>
	<BR>
	<table border="0">
		<tr>
			<td><input id="submit_button" type="submit" name="kensaku" style="cursor: pointer" value="検索" /></td>
			<td><input id="submit_button" type="submit" name="clear" style="cursor: pointer" value="最初から" /></td>
			<td><input id="submit_button" type="submit" name="csv" style="cursor: pointer" value="CSV作成"  <?php if($_SESSION["shikaku2"]==0){ ?>Disabled<?php } ?>/></td>

		</tr>
	</table>
	<BR><BR>

</form>
</body>
</CENTER>
</html>