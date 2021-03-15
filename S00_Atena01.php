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
	$Today2 = $dt->format('Y-m-d');

	$EMSG = "";
	
	$_SESSION["Today"]=$Today;
	$_SESSION["Today2"]=$Today2;

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
		 	ModoruShori($_SESSION["S00_kensaku_RPID"]);
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
			case 'modorushori':
				$_SESSION["ShoriID"]="MODORUSHORI";
				break;
			case 'update':
				$_SESSION["ShoriID"]="UPDATE";
				break;
			case 'newdate':
				$_SESSION["ShoriID"]="NEWDATE";
				break;
			case 'KozaPlus':
				$_SESSION["ShoriID"]="KOZAPLUS";
				break;
			case 'getdata':
				$_SESSION["ShoriID"]="GETDATA";
				break;
			case 'idupd':
				$_SESSION["ShoriID"]="IDUPD";
				break;
			case 'inst':
				$_SESSION["ShoriID"]="INST";
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
			$_SESSION["S00_kensaku_RPID"] = $_GET['RPID'];
		      	//print($_SESSION["S03_kensaku_RPID"]);
		}
		if(isset($_GET['MODE'])) {
			if($_GET['MODE'] != "Modoru"){
				$_SESSION["S00_Atena_MODE"] = $_GET['MODE'];
				$_SESSION["ShoriID"]=$_GET['MODE'];
			      	//print($_SESSION["K_kensaku_MODE"] . "<BR>");
			}
		}
		if(isset($_GET['KEY1'])) {
			$_SESSION["Kensaku_KEY1"] = $_GET['KEY1'];
		}
		if(isset($_GET['SEQ'])) {
			$_SESSION["Kensaku_Seq"] = $_GET['SEQ'];
		}

		switch ($_SESSION["ShoriID"]){
			case 'NEW':
				SessionClear();
				ShokiDataGet();

				break;

			case 'UPD':
				SessionClear();
				ShokiDataGet();
				GetData($_SESSION["Kensaku_KEY1"],$_SESSION["Kensaku_Seq"]);

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
			case 'MODORUSHORI':
				header("Location:S03_index.php?MODE=UPD&RPID=S02_Kensaku&KEY1=" . $_SESSION["Kensaku_KEY1"] . "&KUBUN=0");
				break;
			case 'UPDATESHORI':
				header("Location:S00_Atena01.php?MODE=UPD&RPID=S03_Kanri01&KEY1=" . $_SESSION["Kensaku_KEY1"]) . "&SEQ=" . $_SESSION["Kensaku_Seq"];
				break;
			case 'UPDATE':
				SaveShori();
				$Msg = CheckShori();
				$_SESSION["ErrMsg"] = $Msg;
				if($Msg ==""){
					$EMSG = UpdateShori();
					$_SESSION["ErrMsg"] = $EMSG;
				}
				break;
			case 'NEWDATE':
				SaveShori();
				$Msg = CheckShori();
				$_SESSION["ErrMsg"] = $Msg;
				if($Msg ==""){
					$EMSG = NewShori();
					$_SESSION["ErrMsg"] = $EMSG;
				}
				break;
			case 'KOZAPLUS':
				KozaPlus();
				break;
			case 'GETDATA':
				SaveShori();
				CheckShori_idGet();


//				SaveShori();
//				$_SESSION["S00_StudentID_COLER"]="";
//				$_SESSION["S00_StudentID_ErrMsg"]="";
//				$_SESSION["S00_Name1_COLER"]="";
//				$_SESSION["S00_Name1_ErrMsg"]="";

//				GetData($_SESSION["S00_StudentID"],"");
				break;
			case 'IDUPD':
				$EMSG = ID_UpdateShori();
				if($Msg ==""){
					$_SESSION["ErrMsg"] = "生徒ＩＤを更新しました。";
				}
				break;
			case 'INST':
				SessionClear();
				SaveShori_inst();
				$Msg = CheckShori_inst();
				$_SESSION["ErrMsg2"] = $Msg;
				if($Msg==""){
					InstShori();
				}
				break;

		}	
//print("S00_GenjyoCount=" . $_SESSION["S00_GenjyoCount"] . "<BR>");

	}
//-----------------------------------------------------------
//	セッション情報クリア
//-----------------------------------------------------------
function SessionClear(){
	$_SESSION["ErrMsg"]="";
	$_SESSION["ErrMsg2"]="";

	$_SESSION["KozaFlg"]="0";
	$_SESSION["TorokuJyohoFlg"]="0";
	$_SESSION["KibouFlg"]="0";
	$_SESSION["TokkiFlg"]="0";

	if($_SESSION["S00_Atena_MODE"] == "NEW"){
		$_SESSION["S00_Koza_DataCount"] = 1;
		$_SESSION["S00_Koza_StudentID0"]=$_SESSION["Kensaku_KEY1"];
		$_SESSION["S00_Koza_KozaSeq0"]=	"0";
		$_SESSION["S00_Koza_Start0"]="";
		$_SESSION["S00_Koza_End0"]="";
		$_SESSION["S00_Koza_Kigou0"]="";
		$_SESSION["S00_Koza_Bango0"]="";
		$_SESSION["S00_Koza_Meigi0"]="";
		$_SESSION["S00_Koza_MeigiKana0"]="";
		$_SESSION["S00_Seq"]=0;
		$_SESSION["S00_Koza_Biko0"]="";
	}else{
		$_SESSION["S00_Koza_DataCount"] = 1;
		$_SESSION["S00_Seq"]="";
	}

	$_SESSION["S00_StudentID"]="";
	$_SESSION["S00_EntryTime"]="";
	$_SESSION["S00_Course"]="";
	$_SESSION["S00_EntryDay"]=$_SESSION["Today2"];
	$_SESSION["S00_Hogosha1"]="";
	$_SESSION["S00_HogoshaKana1"]="";
	$_SESSION["S00_Hogo_Zoku1"]="";
	$_SESSION["S00_Hogosha2"]="";
	$_SESSION["S00_HogoshaKana2"]="";
	$_SESSION["S00_Hogo_Zoku2"]="";
	$_SESSION["S00_Kyodai1"]="";
	$_SESSION["S00_Kyo_Zoku1"]="";
	$_SESSION["S00_Kyo_gread1"]="";
	$_SESSION["S00_Kyo_old1"]="";
	$_SESSION["S00_Kyodai2"]="";
	$_SESSION["S00_Kyo_Zoku2"]="";
	$_SESSION["S00_Kyo_gread2"]="";
	$_SESSION["S00_Kyo_ole2"]="";
	$_SESSION["S00_Kyodai3"]="";
	$_SESSION["S00_Kyo_Zoku3"]="";
	$_SESSION["S00_Kyo_gread3"]="";
	$_SESSION["S00_Kyo_old3"]="";
	$_SESSION["S00_Kei_Day"]="";
	$_SESSION["S00_Kei_Tanto"]="";
	$_SESSION["S00_Kei_Aite"]="";
	$_SESSION["S00_Kei_Naiyo"]="";
	$_SESSION["S00_Notice1"]="";
	$_SESSION["S00_Notice2"]="";
	$_SESSION["S00_Notice3"]="";
	$_SESSION["S00_Notice4"]="";
	$_SESSION["S00_Notice5"]="";

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
	for($m=1; $m<=5; $m++){
		$_SESSION["S00_Notice_" . $m]="";
	}

	$_SESSION["S00_BirthDay"]="";
	$_SESSION["S00_Name1"]="";
	$_SESSION["S00_Name2"]="";
	$_SESSION["S00_old"]= "";
	$_SESSION["S00_Old2"]= "";
	$_SESSION["S00_Seibetu"]="";
	$_SESSION["S00_gread"]="";
	$_SESSION["S00_SchoolName"]="";

	$_SESSION["S00_Yubin1_1"]="";
	$_SESSION["S00_Yubin1_2"]="";
	$_SESSION["S00_Add_ken1"]="";
	$_SESSION["S00_Add_Ken_Code1"]="";
	$_SESSION["S00_Add_shi1"]="";
	$_SESSION["S00_Add_ku1"]="";
	$_SESSION["S00_Add_cho1"]="";
	$_SESSION["S00_Yubin2_1"]="";
	$_SESSION["S00_Yubin2_2"]="";
	$_SESSION["S00_Add_ken2"]="";
	$_SESSION["S00_Add_Ken_Code2"]="";
	$_SESSION["S00_Add_shi2"]="";
	$_SESSION["S00_Add_ku2"]="";
	$_SESSION["S00_Add_cho2"]="";
	$_SESSION["S00_CarTF"]="";

	$_SESSION["S00_Kotu_rosen"]="";
	$_SESSION["S00_Kotu_Eki"]="";
	$_SESSION["S00_Kotu_Toho"]="";
	$_SESSION["S00_Kotu_Sonota"]="";

	$_SESSION["S00_Tel_Kubun1"]="";
	$_SESSION["S00_Tel_Kubun2"]="";
	$_SESSION["S00_Tel_Kubun3"]="";
	$_SESSION["S00_Tel1"]="";
	$_SESSION["S00_Tel2"]="";
	$_SESSION["S00_Tel3"]="";
	$_SESSION["S00_Mail_Kubun1"]="";
	$_SESSION["S00_Mail_Kubun2"]="";
	$_SESSION["S00_Mail_Kubun3"]="";
	$_SESSION["S00_Mail1"]="";
	$_SESSION["S00_Mail2"]="";
	$_SESSION["S00_Mail3"]="";

	$_SESSION["S00_ContactTime0"]="";
	$_SESSION["S00_ContactTime1"]="";
	$_SESSION["S00_ContactTime2"]="";
	$_SESSION["S00_ContactTime3"]="";
	$_SESSION["S00_ContactTime4"]="";
	$_SESSION["S00_ContactTimeSonota"]="";
	$_SESSION["S00_Genjyo1"]=0;
	$_SESSION["S00_Genjyo2"]=0;
	$_SESSION["S00_Genjyo3"]=0;
	$_SESSION["S00_Genjyo4"]=0;
	$_SESSION["S00_Genjyo5"]=0;
	$_SESSION["S00_Genjyo6"]=0;
	$_SESSION["S00_Genjyo7"]=0;
	$_SESSION["S00_Genjyo8"]=0;
	$_SESSION["S00_Genjyo9"]=0;
	$_SESSION["S00_Genjyo10"]=0;
	$_SESSION["S00_Genjyo99"]=0;
	$_SESSION["S00_Genjyo_Sonota"]="";
	$_SESSION["S00_SiteJyoho"]=0;
	$_SESSION["S00_Kyoka_Sonota"]="";

	$_SESSION["S00_Sonota_Naiyo"]="";
	$_SESSION["S00_Soudan"]="";
	$_SESSION["S00_ShidoTime"]="";
	$_SESSION["S00_ShidoKibou"]="";
	$_SESSION["S00_Youbi1"]=0;
	$_SESSION["S00_Youbi2"]=0;
	$_SESSION["S00_Youbi3"]=0;
	$_SESSION["S00_Youbi4"]=0;
	$_SESSION["S00_Youbi5"]=0;
	$_SESSION["S00_Youbi6"]=0;
	$_SESSION["S00_Youbi7"]=0;
	$_SESSION["S00_notice1"]="";
	$_SESSION["S00_notice2"]="";
	$_SESSION["S00_notice3"]="";

	$_SESSION["S00_TorokuDay"]="";
	$_SESSION["S00_Youbi_Sonota"]="";

	$_SESSION["S00_KyoushiKibou0"]=0;
	$_SESSION["S00_KyoushiKibou1"]=0;
	$_SESSION["S00_KyoushiKibou2"]=0;
	$_SESSION["S00_KyoushiKibou3"]=0;
	$_SESSION["S00_KyoushiKibou4"]=0;
	$_SESSION["S00_KyoushiKibou5"]=0;
	$_SESSION["S00_KyoushiKibou6"]=0;
	$_SESSION["S00_KyoushiKibou7"]=0;
	$_SESSION["S00_KyoushiKibou8"]=0;
	$_SESSION["S00_KyoushiKibouNaiyo"]="";

	$_SESSION["S00_Yubin1_1_COLER"]="";
	$_SESSION["S00_Yubin1_2_COLER"]="";
	$_SESSION["S00_Yubin2_1_COLER"]="";
	$_SESSION["S00_Yubin2_2_COLER"]="";
	$_SESSION["S00_Yubin1_1_ErrMsg"]="";
	$_SESSION["S00_Yubin1_2_ErrMsg"]="";
	$_SESSION["S00_Yubin2_1_ErrMsg"]="";
	$_SESSION["S00_Yubin2_2_ErrMsg"]="";

	$_SESSION["S00_StudentID_COLER"]="";
	$_SESSION["S00_StudentID_ErrMsg"]="";
	$_SESSION["S00_Seq_COLER"]="";
	$_SESSION["S00_EntryDay_COLER"]="";
	$_SESSION["S00_EntryDay_ErrMsg"]="";
	$_SESSION["S00_BirthDay_COLER"]="";
	$_SESSION["S00_BirthDay_ErrMsg"]="";
	$_SESSION["S00_old_COLER"]="";
	$_SESSION["S00_old_ErrMsg"]="";
	$_SESSION["S00_Name1_COLER"]="";
	$_SESSION["S00_Name1_ErrMsg"]="";
	$_SESSION["S00_Name2_COLER"]="";
	$_SESSION["S00_Name2_ErrMsg"]="";
	$_SESSION["S00_SchoolName_COLER"]="";
	$_SESSION["S00_SchoolName_ErrMsg"]="";
	$_SESSION["S00_gread_ErrMsg"]="";
	$_SESSION["S00_Hogosha1_ErrMsg"]="";
	$_SESSION["S00_Hogosha2_ErrMsg"]="";
	$_SESSION["S00_HogoshaKana1_ErrMsg"]="";
	$_SESSION["S00_HogoshaKana2_ErrMsg"]="";
	$_SESSION["S00_Hogo_Zoku1_ErrMsg"]="";
	$_SESSION["S00_Hogo_Zoku2_ErrMsg"]="";
	$_SESSION["S00_Kyodai1_ErrMsg"]="";
	$_SESSION["S00_Kyodai2_ErrMsg"]="";
	$_SESSION["S00_Kyo_Zoku1_ErrMsg"]="";
	$_SESSION["S00_Kyo_Zoku2_ErrMsg"]="";
	$_SESSION["S00_Kyo_gread2_ErrMsg"]="";
	$_SESSION["S00_Kyo_gread3_ErrMsg"]="";
	$_SESSION["S00_Kyo_old2_ErrMsg"]="";
	$_SESSION["S00_Kyo_old3_ErrMsg"]="";

	$_SESSION["S00_Kotu_rosen_COLER"]="";
	$_SESSION["S00_Kotu_rosen_ErrMsg"]="";
	$_SESSION["S00_Kotu_Eki_COLER"]="";
	$_SESSION["S00_Kotu_Eki_ErrMsg"]="";
	$_SESSION["S00_Kotu_Toho_COLER"]="";
	$_SESSION["S00_Kotu_Toho_ErrMsg"]="";
	$_SESSION["S00_Kotu_Sonota_ErrMsg"]="";
	$_SESSION["S00_Kotu_Sonota_COLER"]="";
	$_SESSION["S00_Tel_ErrMsg1"]="";
	$_SESSION["S00_Mail_ErrMsg1"]="";
	$_SESSION["S00_Tel_COLER1"]="";
	$_SESSION["S00_Mail_COLER1"]="";
	$_SESSION["S00_Tel_ErrMsg2"]="";
	$_SESSION["S00_Mail_ErrMsg2"]="";
	$_SESSION["S00_Tel_COLER2"]="";
	$_SESSION["S00_Mail_COLER2"]="";
	$_SESSION["S00_Tel_ErrMsg3"]="";
	$_SESSION["S00_Mail_ErrMsg3"]="";
	$_SESSION["S00_Tel_COLER3"]="";
	$_SESSION["S00_Mail_COLER3"]="";
	$_SESSION["S00_Kyoka_ErrMsg"]="";
	$_SESSION["S00_Kyoka_Sonota_ErrMsg"]="";
	$_SESSION["S00_Youbi_Sonota_ErrMsg"]="";
	$_SESSION["S00_ShidoTime_ErrMsg"]="";
	$_SESSION["S00_ShidoKibou_ErrMsg"]="";
	$_SESSION["S00_Kyoka_COLER"]="";
	$_SESSION["S00_Kyoka_Sonota_COLER"]="";
	$_SESSION["S00_Youbi_Sonota_COLER"]="";
	$_SESSION["S00_ShidoTime_COLER"]="";
	$_SESSION["S00_ShidoKibou_COLER"]="";
	$_SESSION["S00_KyoushiKibouNaiyo_ErrMsg"]="";
	$_SESSION["S00_KyoushiKibouNaiyo_COLER"]="";
	$_SESSION["S00_notice1_ErrMsg"]="";
	$_SESSION["S00_notice2_ErrMsg"]="";
	$_SESSION["S00_notice3_ErrMsg"]="";
	$_SESSION["S00_notice1_COLER"]="";
	$_SESSION["S00_notice2_COLER"]="";
	$_SESSION["S00_notice3_COLER"]="";
	$_SESSION["S00_Genjyo_Sonota_ErrMsg"]="";
	$_SESSION["S00_Genjyo_Sonota_COLER"]="";

	$_SESSION["S00_Koza_Kigou0_ErrMsg"]="";
	$_SESSION["S00_Koza_Kigou0_COLER"]="";
	$_SESSION["S00_Koza_Bango0_ErrMsg"]="";
	$_SESSION["S00_Koza_Bango0_COLER"]="";
	$_SESSION["S00_Koza_Start0_ErrMsg"]="";
	$_SESSION["S00_Koza_Start0_COLER"]="";
	$_SESSION["S00_Koza_Meigi0_ErrMsg"]="";
	$_SESSION["S00_Koza_Meigi0_COLER"]="";
	$_SESSION["S00_Koza_MeigiKana0_ErrMsg"]="";
	$_SESSION["S00_Koza_MeigiKana0_COLER"]="";
	$_SESSION["S00_Koza_Biko0_ErrMsg"]="";
	$_SESSION["S00_Koza_Biko0_COLER"]="";
	$_SESSION["S00_Koza_End0_ErrMsg"]="";
	$_SESSION["S00_Koza_End0_COLER"]="";
	$_SESSION["S00_Koza_End20_ErrMsg"]="";
	$_SESSION["S00_Koza_End20_COLER"]="";
	$_SESSION["S00_TorokuDay_ErrMsg"]="";
	$_SESSION["S00_TorokuDay_COLER"]="";
	$_SESSION["S00_Soudan_ErrMsg"]="";
	$_SESSION["S00_Soudan_COLER"]="";
	$_SESSION["S00_Sonota_Naiyo_ErrMsg"]="";
	$_SESSION["S00_Sonota_Naiyo_COLER"]="";
	$_SESSION["S00_InstDay_ErrMsg"] = "";
	$_SESSION["S00_InstDay_COLER"] = "";
	$_SESSION["S00_InstKubun_ErrMsg"] = "";
	$_SESSION["S00_InstKubun_COLER"] = "";
	$_SESSION["S00_InstHouho_ErrMsg"] = "";
	$_SESSION["S00_InstHouho_COLER"] = "";

	$_SESSION["S00_ID_UPD"]=0;

	$_SESSION["S00_InstKubun"]="";
	$_SESSION["S00_InstHouho"]="";
	$_SESSION["S00_InstData"]="";
	$_SESSION["S00_InstDay"]=$_SESSION["Today2"];

	$_SESSION["S00_Youbi_Sonota_COLER"] = "";
	$_SESSION["S00_Youbi_COLER"] = "";
	$_SESSION["S00_Hogosha1_COLER"] = "";
	$_SESSION["S00_HogoshaKana1_COLER"] = "";
	$_SESSION["S00_Name1_COLER"] = "";
	$_SESSION["S00_Name2_COLER"] = "";
	$_SESSION["S00_gread_COLER"] = "";
	$_SESSION["S00_Seibetu_COLER"] = "";
	$_SESSION["S00_Genjyo_COLER"]="";
	$_SESSION["S00_Genjyo_Sonota_COLER"]="";
	$_SESSION["S00_Add_shi1_COLER"]="";
	$_SESSION["S00_Add_ku1_COLER"]="";
	$_SESSION["S00_Add_cho1_COLER"]="";
	$_SESSION["S00_Kotu_rosen_COLER"]="";
	$_SESSION["S00_Kotu_Eki_COLER"]="";
	$_SESSION["S00_Tel_COLER1"]="";
	$_SESSION["S00_ContactTime_COLER"]="";
	$_SESSION["S00_ContactTimeSonota_COLER"]="";
	$_SESSION["S00_Mail_COLER1"]="";
	$_SESSION["S00_Sonota_Naiyo_COLER1"]="";
}
//-----------------------------------------------------------
//	初期データ取得
//-----------------------------------------------------------
Function ShokiDataGet(){

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
	$CodeData = GetCodeData("生徒取込区分","","",1);
	$_SESSION["22CodeData"]=$CodeData;
	$CodeData = array();
	$CodeData = GetCodeData("現状","","",1);
	$_SESSION["23CodeData"]=$CodeData;
	$CodeData = array();
	$CodeData = GetCodeData("曜日","","",1);
	$_SESSION["24CodeData"]=$CodeData;
	$CodeData = array();
	$CodeData = GetCodeData("折衝方法","","",1);
	$_SESSION["09CodeData"]=$CodeData;

	$_SESSION["S00_GenjyoCount"] = $_SESSION["23CodeData"]["23DataCount"] - 1;

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
//	データ取得
//-----------------------------------------------------------
Function GetData($key1,$key2){
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

		if($key1 !=""){
			if($query2 == ""){
				$query2 = $query2 . " Where a.StudentID='" . $key1 . "'";
			}else{
				$query2 = $query2 . " And a.StudentID='" . $key1 . "'";
			}
		}
		if($key2 !=""){
			if($query2 == ""){
				$query2 = $query2 . " Where a.Seq='" . $key2 . "'";
			}else{
				$query2 = $query2 . " And a.Seq='" . $key2 . "'";
			}
		}else{
			$query2 = $query2 . " Order by a.Seq ";
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
				$_SESSION["S00_" . $key] = $value;
			}

			$_SESSION["S00_ContactTimeSonota"]=$_SESSION["S00_ContactTime4"];
			$_SESSION["S00_Old2"]=floor ((date('Ymd') - date('Ymd', strtotime($_SESSION['S00_BirthDay'])))/10000);
			$_SESSION["moto_S00_StudentID"]=$_SESSION["S00_StudentID"];
			$_SESSION["moto_S00_Seq"]=$_SESSION["S00_Seq"];

			if($_SESSION["S00_Genjyo10"] >= 1){
				$S00_Genjyo10 = $_SESSION["S00_Genjyo10"];
				$Gengyolen = strlen($_SESSION["S00_Genjyo10"]);
				for($i=0; $i<$Gengyolen; $i++ ){
					$m = $i + 10;
					$_SESSION["S00_Genjyo" . $m] = substr($S00_Genjyo10,$i,1);
					//print("S00_Genjyo" . $m . "=" . substr($S00_Genjyo10,$i,1) . "<BR>");
				}
				$_SESSION["S00_GenjyoCount"] = $m;
			}else{
				$_SESSION["S00_GenjyoCount"] = $_SESSION["23CodeData"]["23DataCount"] - 1;
			}
		}

		//------------------------口座情報------------------------------

		$_SESSION["S00_Koza_StudentID0"]=$_SESSION["Kensaku_KEY1"];
		$_SESSION["S00_Koza_KozaSeq0"]="0";
		$_SESSION["S00_Koza_Start0"]="";
		$_SESSION["S00_Koza_End0"]="";
		$_SESSION["S00_Koza_Kigou0"]="";
		$_SESSION["S00_Koza_Bango0"]="";
		$_SESSION["S00_Koza_Meigi0"]="";
		$_SESSION["S00_Koza_MeigiKana0"]="";
		$_SESSION["S00_Koza_Biko0"]="";

		$_SESSION["S00_Koza_Kigou0_ErrMsg"]="";
		$_SESSION["S00_Koza_Kigou0_COLER"]="";
		$_SESSION["S00_Koza_Bango0_ErrMsg"]="";
		$_SESSION["S00_Koza_Bango0_COLER"]="";
		$_SESSION["S00_Koza_Start0_ErrMsg"]="";
		$_SESSION["S00_Koza_Start0_COLER"]="";
		$_SESSION["S00_Koza_Meigi0_ErrMsg"]="";
		$_SESSION["S00_Koza_Meigi0_COLER"]="";
		$_SESSION["S00_Koza_MeigiKana0_ErrMsg"]="";
		$_SESSION["S00_Koza_MeigiKana0_COLER"]="";
		$_SESSION["S00_Koza_End0_ErrMsg"]="";
		$_SESSION["S00_Koza_End0_COLER"]="";
		$_SESSION["S00_Koza_End20_ErrMsg"]="";
		$_SESSION["S00_Koza_End20_COLER"]="";
		$_SESSION["S00_Koza_Biko0_ErrMsg"]="";
		$_SESSION["S00_Koza_Biko0_COLER"]="";


		$query3 = "Select * ";
		$query3 = $query3 . " FROM S_KozaInfo ";
		$query3 = $query3 . " Where StudentID='" . $key1 . "'";
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
			$_SESSION["S00_Koza_StudentID" .$i]="";
			$_SESSION["S00_Koza_KozaSeq" .$i]="";
			$_SESSION["S00_Koza_Start" .$i]="";
			$_SESSION["S00_Koza_End" .$i]="";
			$_SESSION["S00_Koza_Kigou" .$i]="";
			$_SESSION["S00_Koza_Bango" .$i]="";
			$_SESSION["S00_Koza_Meigi" .$i]="";
			$_SESSION["S00_Koza_MeigiKana" .$i]="";
			$_SESSION["S00_Koza_Biko" .$i]="";

			$_SESSION["S00_Koza_Kigou". $i . "_ErrMsg"]="";
			$_SESSION["S00_Koza_Kigou". $i . "_COLER"]="";
			$_SESSION["S00_Koza_Bango". $i . "_ErrMsg"]="";
			$_SESSION["S00_Koza_Bango". $i . "_COLER"]="";
			$_SESSION["S00_Koza_Start". $i . "_ErrMsg"]="";
			$_SESSION["S00_Koza_Start". $i . "_COLER"]="";
			$_SESSION["S00_Koza_Meigi". $i . "_ErrMsg"]="";
			$_SESSION["S00_Koza_Meigi". $i . "_COLER"]="";
			$_SESSION["S00_Koza_MeigiKana". $i . "_ErrMsg"]="";
			$_SESSION["S00_Koza_MeigiKana". $i . "_COLER"]="";
			$_SESSION["S00_Koza_End". $i . "_ErrMsg"]="";
			$_SESSION["S00_Koza_End". $i . "_COLER"]="";
			$_SESSION["S00_Koza_End2". $i . "_ErrMsg"]="";
			$_SESSION["S00_Koza_End2". $i . "_COLER"]="";
			$_SESSION["S00_Koza_Biko". $i . "_ErrMsg"]="";
			$_SESSION["S00_Koza_Biko". $i . "_COLER"]="";

			$_SESSION["S00_Koza_StudentID" .$i]=$data2[$i]['StudentID'];
			$_SESSION["S00_Koza_KozaSeq" .$i]=$data2[$i]['KozaSeq'];
			$_SESSION["S00_Koza_Start" .$i]=$data2[$i]['Start'];
			$_SESSION["S00_Koza_End" .$i]=$data2[$i]['End'];
			$_SESSION["S00_Koza_Kigou" .$i]=$data2[$i]['Kigou'];
			$_SESSION["S00_Koza_Bango" .$i]=$data2[$i]['Bango'];
			$_SESSION["S00_Koza_Meigi" .$i]=$data2[$i]['Meigi'];
			$_SESSION["S00_Koza_MeigiKana" .$i]=$data2[$i]['MeigiKana'];
			$_SESSION["S00_Koza_Biko" .$i]=$data2[$i]['Biko'];

			$i++;
		}
//print($_SESSION["S00_Koza_StudentID0"] . "<BR>");
//print($_SESSION["S00_Koza_KozaSeq0"] . "<BR>");
		if($i > 0){
			$_SESSION["S00_Koza_DataCount"] = $i;
		}
		
	 	// データベースの切断
		$mysqli->close();
}
//-----------------------------------------------------------
//	口座追加処理
//-----------------------------------------------------------
Function KozaPlus(){

	$KozaCnt = $_SESSION["S00_Koza_DataCount"] + 1;
	$_SESSION["S00_Koza_DataCount"]=$KozaCnt;

	$KozaIdx = $KozaCnt-1;
	$KozaIdx2 = $_SESSION["S00_Koza_KozaSeq0"] + 1;

//print($KozaIdx2);

	$_SESSION["S00_Koza_StudentID" . $KozaIdx]=$_SESSION["Kensaku_KEY1"];
	$_SESSION["S00_Koza_KozaSeq" . $KozaIdx]=$KozaIdx2;
	$_SESSION["S00_Koza_Start" . $KozaIdx]="";
	$_SESSION["S00_Koza_End" . $KozaIdx]="";
	$_SESSION["S00_Koza_Kigou" . $KozaIdx]="";
	$_SESSION["S00_Koza_Bango" . $KozaIdx]="";
	$_SESSION["S00_Koza_Meigi" . $KozaIdx]="";
	$_SESSION["S00_Koza_MeigiKana" . $KozaIdx]="";
	$_SESSION["S00_Koza_Biko" . $KozaIdx]="";

	$_SESSION["S00_Koza_Kigou" . $KozaIdx . "_ErrMsg"]="";
	$_SESSION["S00_Koza_Kigou" . $KozaIdx . "_COLER"]="";
	$_SESSION["S00_Koza_Bango" . $KozaIdx . "_ErrMsg"]="";
	$_SESSION["S00_Koza_Bango" . $KozaIdx . "_COLER"]="";
	$_SESSION["S00_Koza_Start" . $KozaIdx . "_ErrMsg"]="";
	$_SESSION["S00_Koza_Start" . $KozaIdx . "_COLER"]="";
	$_SESSION["S00_Koza_Meigi" . $KozaIdx . "_ErrMsg"]="";
	$_SESSION["S00_Koza_Meigi" . $KozaIdx . "_COLER"]="";
	$_SESSION["S00_Koza_MeigiKana" . $KozaIdx . "_ErrMsg"]="";
	$_SESSION["S00_Koza_MeigiKana" . $KozaIdx . "_COLER"]="";
	$_SESSION["S00_Koza_End" . $KozaIdx . "_ErrMsg"]="";
	$_SESSION["S00_Koza_End" . $KozaIdx . "_COLER"]="";
	$_SESSION["S00_Koza_End2" . $KozaIdx . "_ErrMsg"]="";
	$_SESSION["S00_Koza_End2" . $KozaIdx . "_COLER"]="";
	$_SESSION["S00_Biko2" . $KozaIdx . "_ErrMsg"]="";
	$_SESSION["S00_Biko2" . $KozaIdx . "_COLER"]="";

}
//-----------------------------------------------------------
//	セーブ処理
//-----------------------------------------------------------
Function SaveShori(){

	//新規の時だけ
	if($_SESSION["S00_Atena_MODE"] == "NEW"){
		$_SESSION["S00_InstKubun"]=$_POST['S00_InstKubun'];
		$_SESSION["S00_InstHouho"]=$_POST['S00_InstHouho'];
		$_SESSION["S00_InstData"]=$_POST['S00_InstData'];
		$_SESSION["S00_InstDay"]=$_POST['S00_InstDay'];
	}

	$_SESSION["S00_StudentID"]=$_POST['S00_StudentID'];
	$_SESSION["S00_Seq"]=$_POST['S00_Seq'];
	$_SESSION["S00_EntryDay"]=$_POST['S00_EntryDay'];
//	$_SESSION["S00_Course"]=$_POST['S00_Course'];
	$_SESSION["S00_Hogosha1"]=$_POST['S00_Hogosha1'];
	$_SESSION["S00_HogoshaKana1"]=$_POST['S00_HogoshaKana1'];
	$_SESSION["S00_Hogo_Zoku1"]=$_POST['S00_Hogo_Zoku1'];
	$_SESSION["S00_Hogosha2"]=$_POST['S00_Hogosha2'];
	$_SESSION["S00_HogoshaKana2"]=$_POST['S00_HogoshaKana2'];
	$_SESSION["S00_Hogo_Zoku2"]=$_POST['S00_Hogo_Zoku2'];

	for($m=1; $m<=6; $m++){
		if(isset($_POST['S00_Sub1_' . $m])){
			$_SESSION["S00_Sub1_" . $m]=1;
		}else{
			$_SESSION["S00_Sub1_" . $m]=0;
		}
	}
	for($m=1; $m<=6; $m++){
		if(isset($_POST['S00_Sub2_' . $m])){
			$_SESSION["S00_Sub2_" . $m]=1;
		}else{
			$_SESSION["S00_Sub2_" . $m]=0;

		}
	}
	for($m=1; $m<=20; $m++){
		if(isset($_POST['S00_Sub3_' . $m])){
			$_SESSION["S00_Sub3_" . $m]=1;
		}else{
			$_SESSION["S00_Sub3_" . $m]=0;
		}
	}
	$_SESSION["S00_Sub4_1"]=$_POST['S00_Sub4_1'];
	$_SESSION["S00_Kyoka_Sonota"]=$_POST['S00_Kyoka_Sonota'];

	$_SESSION["S00_BirthDay"]=$_POST['S00_BirthDay'];
	$_SESSION["S00_Name1"]=$_POST['S00_Name1'];
	$_SESSION["S00_Name2"]=$_POST['S00_Name2'];
	$_SESSION["S00_old"]= $_POST['S00_old'];
	if(isset($_POST['S00_Seibetu'])){
		$_SESSION["S00_Seibetu"]=$_POST['S00_Seibetu'];
	}
	$_SESSION["S00_gread"]=$_POST['S00_gread'];
	$_SESSION["S00_SchoolName"]=$_POST['S00_SchoolName'];

	$_SESSION["S00_Yubin1_1"]=$_POST['S00_Yubin1_1'];
	$_SESSION["S00_Yubin1_2"]=$_POST['S00_Yubin1_2'];
	if(isset($_POST['S00_Add_Ken_Code1'])){
		$_SESSION["S00_Add_Ken_Code1"]=$_POST['S00_Add_Ken_Code1'];
		for($dataidx=0; $dataidx < $_SESSION["K_ToDofuken_DataCount"]; $dataidx++){
			if($_SESSION["K_ToDofuken_Code_" .$dataidx] == $_SESSION["S00_Add_Ken_Code1"]){ 
				$_SESSION["S00_Add_ken1"] = $_SESSION["K_ToDofuken_Todofuken_" .$dataidx];
			}
		}

	}

	$_SESSION["S00_Add_shi1"]=$_POST['S00_Add_shi1'];
	$_SESSION["S00_Add_ku1"]=$_POST['S00_Add_ku1'];
	$_SESSION["S00_Add_cho1"]=$_POST['S00_Add_cho1'];
	$_SESSION["S00_Yubin2_1"]=$_POST['S00_Yubin2_1'];
	$_SESSION["S00_Yubin2_2"]=$_POST['S00_Yubin2_2'];
	if(isset($_POST['S00_Add_Ken_Code2'])){
		$_SESSION["S00_Add_Ken_Code2"]=$_POST['S00_Add_Ken_Code2'];
		for($dataidx=0; $dataidx < $_SESSION["K_ToDofuken_DataCount"]; $dataidx++){
			if($_SESSION["K_ToDofuken_Code_" .$dataidx] == $_SESSION["S00_Add_Ken_Code2"]){ 
				$_SESSION["S00_Add_ken2"] = $_SESSION["K_ToDofuken_Todofuken_" .$dataidx];
			}
		}

	}

	$_SESSION["S00_Add_shi2"]=$_POST['S00_Add_shi2'];
	$_SESSION["S00_Add_ku2"]=$_POST['S00_Add_ku2'];
	$_SESSION["S00_Add_cho2"]=$_POST['S00_Add_cho2'];

	$_SESSION["S00_Kyodai1"]=$_POST['S00_Kyodai1'];
	$_SESSION["S00_Kyo_Zoku1"]=$_POST['S00_Kyo_Zoku1'];
	$_SESSION["S00_Kyo_gread1"]=$_POST['S00_Kyo_gread1'];
	$_SESSION["S00_Kyo_old1"]=$_POST['S00_Kyo_old1'];

	$_SESSION["S00_Kyodai2"]=$_POST['S00_Kyodai2'];
	$_SESSION["S00_Kyo_Zoku2"]=$_POST['S00_Kyo_Zoku2'];
	$_SESSION["S00_Kyo_gread2"]=$_POST['S00_Kyo_gread2'];
	$_SESSION["S00_Kyo_old2"]=$_POST['S00_Kyo_old2'];

	if(isset($_POST['S00_CarTF'])){
		$_SESSION["S00_CarTF"]=$_POST['S00_CarTF'];
	}
	$_SESSION["S00_Kotu_rosen"]=$_POST['S00_Kotu_rosen'];
	$_SESSION["S00_Kotu_Eki"]=$_POST['S00_Kotu_Eki'];
	$_SESSION["S00_Kotu_Toho"]=$_POST['S00_Kotu_Toho'];
	$_SESSION["S00_Kotu_Sonota"]=$_POST['S00_Kotu_Sonota'];

	$_SESSION["S00_Tel_Kubun1"]=$_POST['S00_Tel_Kubun1'];
	$_SESSION["S00_Tel_Kubun2"]=$_POST['S00_Tel_Kubun2'];
	$_SESSION["S00_Tel_Kubun3"]=$_POST['S00_Tel_Kubun3'];
	$_SESSION["S00_Tel1"]=$_POST['S00_Tel1'];
	$_SESSION["S00_Tel2"]=$_POST['S00_Tel2'];
	$_SESSION["S00_Tel3"]=$_POST['S00_Tel3'];
	$_SESSION["S00_Mail_Kubun1"]=$_POST['S00_Mail_Kubun1'];
	$_SESSION["S00_Mail_Kubun2"]=$_POST['S00_Mail_Kubun2'];
	$_SESSION["S00_Mail_Kubun3"]=$_POST['S00_Mail_Kubun3'];
	$_SESSION["S00_Mail1"]=$_POST['S00_Mail1'];
	$_SESSION["S00_Mail2"]=$_POST['S00_Mail2'];
	$_SESSION["S00_Mail3"]=$_POST['S00_Mail3'];


	for($dataidx=0; $dataidx < $_SESSION["15CodeData"]["15DataCount"]; $dataidx++){
		if(isset($_POST['S00_ContactTime'. $dataidx])){
			$_SESSION["S00_ContactTime" . $dataidx]=1;
		}else{
			$_SESSION["S00_ContactTime" . $dataidx]=0;
		}
	}

	$_SESSION["S00_ContactTimeSonota"]=$_POST['S00_ContactTimeSonota'];

	for($i=0; $i<9; $i++){
		$m = $i + 1;
		if(isset($_POST['S00_Genjyo' . $m])){
			$_SESSION["S00_Genjyo" . $m]=1;
		}else{
			$_SESSION["S00_Genjyo" . $m]=0;
		}
	}
	if($_SESSION["S00_GenjyoCount"]>9){
		for($i=10; $i<=$_SESSION["S00_GenjyoCount"]; $i++){
			if(isset($_POST['S00_Genjyo' . $i])){
				$_SESSION["S00_Genjyo" . $i]=1;
			}else{
				$_SESSION["S00_Genjyo" . $i]=0;
			}
			if(isset($_POST['S00_Genjyo' . $i])){
				$S00_Genjyo10 = $S00_Genjyo10 . "1";
			}else{
				$S00_Genjyo10 = $S00_Genjyo10 . "0";
			}
		}
		$_SESSION["S00_Genjyo10_Data"]=$S00_Genjyo10;
	}

	if(isset($_POST['S00_Genjyo99'])){
		$_SESSION["S00_Genjyo99"]=1;
	}else{
		$_SESSION["S00_Genjyo99"]=0;
	}



	$_SESSION["S00_Sonota_Naiyo"]=$_POST['S00_Sonota_Naiyo'];  

	$_SESSION["S00_Soudan"]=$_POST['S00_Soudan'];
	$_SESSION["S00_ShidoTime"]=$_POST['S00_ShidoTime'];
	$_SESSION["S00_ShidoKibou"]=$_POST['S00_ShidoKibou'];

	if(isset($_POST['S00_Youbi1'])){
		$_SESSION["S00_Youbi1"]=1;
	}else{
		$_SESSION["S00_Youbi1"]=0;
	}
	if(isset($_POST['S00_Youbi2'])){
		$_SESSION["S00_Youbi2"]=1;
	}else{
		$_SESSION["S00_Youbi2"]=0;
	}
	if(isset($_POST['S00_Youbi3'])){
		$_SESSION["S00_Youbi3"]=1;
	}else{
		$_SESSION["S00_Youbi3"]=0;
	}
	if(isset($_POST['S00_Youbi4'])){
		$_SESSION["S00_Youbi4"]=1;
	}else{
		$_SESSION["S00_Youbi4"]=0;
	}
	if(isset($_POST['S00_Youbi5'])){
		$_SESSION["S00_Youbi5"]=1;
	}else{
		$_SESSION["S00_Youbi5"]=0;
	}
	if(isset($_POST['S00_Youbi6'])){
		$_SESSION["S00_Youbi6"]=1;
	}else{
		$_SESSION["S00_Youbi6"]=0;
	}
	if(isset($_POST['S00_Youbi7'])){
		$_SESSION["S00_Youbi7"]=1;
	}else{
		$_SESSION["S00_Youbi7"]=0;
	}

	for($dataidx=0; $dataidx < $_SESSION["18CodeData"]["18DataCount"]; $dataidx++){
		if(isset($_POST['S00_KyoushiKibou'. $dataidx])){
			$_SESSION["S00_KyoushiKibou" . $dataidx]=1;
		}else{
			$_SESSION["S00_KyoushiKibou" . $dataidx]=0;
		}
	}

	$_SESSION["S00_KyoushiKibouNaiyo"]=$_POST['S00_KyoushiKibouNaiyo'];
	$_SESSION["S00_Notice1"]=$_POST['S00_Notice1'];
	$_SESSION["S00_Notice2"]=$_POST['S00_Notice2'];
	$_SESSION["S00_Notice3"]=$_POST['S00_Notice3'];
	$_SESSION["S00_Notice4"]=$_POST['S00_Notice4'];
	$_SESSION["S00_Notice5"]=$_POST['S00_Notice5'];
	$_SESSION["S00_notice1"]=$_POST['S00_notice1'];
	$_SESSION["S00_notice2"]=$_POST['S00_notice2'];
	$_SESSION["S00_notice3"]=$_POST['S00_notice3'];
	$_SESSION["S00_TorokuDay"]=$_POST['S00_TorokuDay'];
	$_SESSION["S00_Genjyo_Sonota"]=$_POST['S00_Genjyo_Sonota'];
	$_SESSION["S00_Youbi_Sonota"]=$_POST['S00_Youbi_Sonota'];

	for($m=0; $m<$_SESSION["S00_Koza_DataCount"]; $m++){
//		$_SESSION["S00_Koza_StudentID" . $m]=$_POST['S00_Koza_StudentID' . $m];
//		$_SESSION["S00_Koza_KozaSeq" . $m]=$_POST['S00_Koza_KozaSeq' . $m];
		$_SESSION["S00_Koza_Start" . $m]=$_POST['S00_Koza_Start' . $m];
		$_SESSION["S00_Koza_End" . $m]=$_POST['S00_Koza_End' . $m];
		$_SESSION["S00_Koza_Kigou" . $m]=$_POST['S00_Koza_Kigou' . $m];
		$_SESSION["S00_Koza_Bango" . $m]=$_POST['S00_Koza_Bango' . $m];
		$_SESSION["S00_Koza_Meigi" . $m]=$_POST['S00_Koza_Meigi' . $m];
		$_SESSION["S00_Koza_MeigiKana" . $m]=$_POST['S00_Koza_MeigiKana' . $m];
		$_SESSION["S00_Koza_Biko" . $m]=$_POST['S00_Koza_Biko' . $m];
//print("S00_Koza_DataCount=" . $_SESSION["S00_Koza_DataCount"] . "<BR>");
//print("S00_Koza_StudentID" . $m . "=" . $_SESSION["S00_Koza_StudentID" . $m] . "<BR>");
//print("S00_Koza_KozaSeq" . $m . "=" . $_SESSION["S00_Koza_KozaSeq" . $m] . "<BR>");

	}
}
//-----------------------------------------------------------
//	セーブ処理
//-----------------------------------------------------------
Function SaveShori_inst(){
	$_SESSION["S00_InstKubun"]=$_POST['S00_InstKubun'];
	$_SESSION["S00_InstHouho"]=$_POST['S00_InstHouho'];
	$_SESSION["S00_InstData"]=$_POST['S00_InstData'];
	$_SESSION["S00_InstDay"]=$_POST['S00_InstDay'];

}
//-----------------------------------------------------------
//	チェック処理
//-----------------------------------------------------------
Function CheckShori(){
$ErrCnt=0;
$ErrMsg="";
$ErrMsg2="";
$Background="background-color: #F5A9F2";

//print("CheckShori<BR>");
	$_SESSION["S00_Yubin1_1_COLER"]="";
	$_SESSION["S00_Yubin1_2_COLER"]="";
	$_SESSION["S00_Yubin2_1_COLER"]="";
	$_SESSION["S00_Yubin2_2_COLER"]="";
	$_SESSION["S00_Yubin1_1_ErrMsg"]="";
	$_SESSION["S00_Yubin1_2_ErrMsg"]="";
	$_SESSION["S00_Yubin2_1_ErrMsg"]="";
	$_SESSION["S00_Yubin2_2_ErrMsg"]="";
	$_SESSION["S00_StudentID_COLER"]="";
	$_SESSION["S00_StudentID_ErrMsg"]="";
	$_SESSION["S00_Seq_COLER"]="";
	$_SESSION["S00_EntryDay_ErrMsg"]="";
	$_SESSION["S00_EntryDay_COLER"]="";
	$_SESSION["S00_BirthDay_COLER"]="";
	$_SESSION["S00_BirthDay_ErrMsg"]="";
	$_SESSION["S00_old_COLER"]="";
	$_SESSION["S00_old_ErrMsg"]="";
	$_SESSION["S00_Name1_COLER"]="";
	$_SESSION["S00_Name1_ErrMsg"]="";
	$_SESSION["S00_Name2_COLER"]="";
	$_SESSION["S00_Name2_ErrMsg"]="";
	$_SESSION["S00_SchoolName_COLER"]="";
	$_SESSION["S00_SchoolName_ErrMsg"]="";
	$_SESSION["S00_gread_ErrMsg"]="";
	$_SESSION["S00_Hogosha1_ErrMsg"]="";
	$_SESSION["S00_Hogosha2_ErrMsg"]="";
	$_SESSION["S00_HogoshaKana1_ErrMsg"]="";
	$_SESSION["S00_HogoshaKana2_ErrMsg"]="";
	$_SESSION["S00_Hogo_Zoku1_ErrMsg"]="";
	$_SESSION["S00_Hogo_Zoku2_ErrMsg"]="";
	$_SESSION["S00_Kotu_rosen_COLER"]="";
	$_SESSION["S00_Kotu_rosen_ErrMsg"]="";
	$_SESSION["S00_Kotu_Eki_COLER"]="";
	$_SESSION["S00_Kotu_Eki_ErrMsg"]="";
	$_SESSION["S00_Kotu_Toho_COLER"]="";
	$_SESSION["S00_Kotu_Toho_ErrMsg"]="";
	$_SESSION["S00_Kotu_Sonota_ErrMsg"]="";
	$_SESSION["S00_Kotu_Sonota_COLER"]="";
	$_SESSION["S00_Tel_ErrMsg1"]="";
	$_SESSION["S00_Mail_ErrMsg1"]="";
	$_SESSION["S00_Tel_COLER1"]="";
	$_SESSION["S00_Mail_COLER1"]="";
	$_SESSION["S00_Tel_ErrMsg2"]="";
	$_SESSION["S00_Mail_ErrMsg2"]="";
	$_SESSION["S00_Tel_COLER2"]="";
	$_SESSION["S00_Mail_COLER2"]="";
	$_SESSION["S00_Tel_ErrMsg3"]="";
	$_SESSION["S00_Mail_ErrMsg3"]="";
	$_SESSION["S00_Tel_COLER3"]="";
	$_SESSION["S00_Mail_COLER3"]="";
	$_SESSION["S00_Kyoka_ErrMsg"]="";
	$_SESSION["S00_Kyoka_Sonota_ErrMsg"]="";
	$_SESSION["S00_Youbi_Sonota_ErrMsg"]="";
	$_SESSION["S00_ShidoTime_ErrMsg"]="";
	$_SESSION["S00_ShidoKibou_ErrMsg"]="";
	$_SESSION["S00_Kyoka_COLER"]="";
	$_SESSION["S00_Kyoka_Sonota_COLER"]="";
	$_SESSION["S00_Youbi_Sonota_COLER"]="";
	$_SESSION["S00_ShidoTime_COLER"]="";
	$_SESSION["S00_ShidoKibou_COLER"]="";
	$_SESSION["S00_KyoushiKibouNaiyo_ErrMsg"]="";
	$_SESSION["S00_KyoushiKibouNaiyo_COLER"]="";
	$_SESSION["S00_Notice1_ErrMsg"]="";
	$_SESSION["S00_Notice2_ErrMsg"]="";
	$_SESSION["S00_Notice3_ErrMsg"]="";
	$_SESSION["S00_Notice4_ErrMsg"]="";
	$_SESSION["S00_Notice5_ErrMsg"]="";
	$_SESSION["S00_Notice1_COLER"]="";
	$_SESSION["S00_Notice2_COLER"]="";
	$_SESSION["S00_Notice3_COLER"]="";
	$_SESSION["S00_Notice4_COLER"]="";
	$_SESSION["S00_Notice5_COLER"]="";
	$_SESSION["S00_notice1_ErrMsg"]="";
	$_SESSION["S00_notice2_ErrMsg"]="";
	$_SESSION["S00_notice3_ErrMsg"]="";
	$_SESSION["S00_notice1_COLER"]="";
	$_SESSION["S00_notice2_COLER"]="";
	$_SESSION["S00_notice3_COLER"]="";
	$_SESSION["S00_Genjyo_Sonota_ErrMsg"]="";
	$_SESSION["S00_Genjyo_Sonota_COLER"]="";

	$_SESSION["S00_TorokuDay_ErrMsg"]="";
	$_SESSION["S00_Soudan_ErrMsg"]="";
	$_SESSION["S00_TorokuDay_COLER"]="";
	$_SESSION["S00_Soudan_COLER"]="";
	$_SESSION["S00_InstDay_ErrMsg"] = "";
	$_SESSION["S00_InstDay_COLER"] = "";
	$_SESSION["S00_InstKubun_ErrMsg"] = "";
	$_SESSION["S00_InstKubun_COLER"] = "";
	$_SESSION["S00_InstHouho_ErrMsg"] = "";
	$_SESSION["S00_InstHouho_COLER"] = "";


	if($_SESSION["S00_Atena_MODE"] == "NEW"){
		//取込日
		if($_SESSION["S00_InstDay"]==""){
			$ErrMsg = "未入力不可";
			$_SESSION["S00_InstDay_ErrMsg"] = $ErrMsg;
			$_SESSION["S00_InstDay_COLER"] = $Background;
			$ErrCnt++;
		}
		//取込区分
		if($_SESSION["S00_InstKubun"]==""){
			$ErrMsg = "未入力不可";
			$_SESSION["S00_InstKubun_ErrMsg"] = $ErrMsg;
			$_SESSION["S00_InstKubun_COLER"] = $Background;
			$ErrCnt++;
		}
		//取込方法
		if($_SESSION["S00_InstHouho"]==""){
			$ErrMsg = "未入力不可";
			$_SESSION["S00_InstHouho_ErrMsg"] = $ErrMsg;
			$_SESSION["S00_InstHouho_COLER"] = $Background;
			$ErrCnt++;
		}
		//登録日
		if($_SESSION["S00_EntryDay"]==""){
			$ErrMsg = "未入力不可";
			$_SESSION["S00_EntryDay_ErrMsg"] = $ErrMsg;
			$_SESSION["S00_EntryDay_COLER"] = $Background;
			$ErrCnt++;
		}


	}
	if(($_SESSION["S00_StudentID"] != $_SESSION["moto_S00_StudentID"]) || ($_SESSION["S00_Seq"] != $_SESSION["moto_S00_Seq"])){
		//生徒ＩＤ（生徒ＩＤが変更された時のみ）
		//登録日チェック（未入力、型、未来）
		if($_SESSION["S00_StudentID"] != ""){
			if($_SESSION["S00_StudentID"] == "0"){
				$ErrMsg = "手入力してください";
				$_SESSION["S00_StudentID_ErrMsg"] = $ErrMsg;
				$_SESSION["S00_StudentID_COLER"] = $Background;
				$ErrCnt++;
			}else{
				//かぶりチェック
				// mysqlへの接続
				$mysqli = new mysqli(HOST, USER, PASS);
				if ($mysqli->connect_errno) {
					print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
					exit();
				   		}

				// データベースの選択
				$mysqli->select_db(DBNAME);
				$mysqli->set_charset("utf8");

				$query = "SELECT Count(*) as AtenaCnt FROM S_AtenaInfo";
				$query = $query . " Where StudentID='" . $_SESSION["S00_StudentID"] . "'";
				$query = $query . " And Seq='" . $_SESSION["S00_Seq"] . "'";

				//print($query);

				$result = $mysqli->query($query);

				if (!$result) {
					print('クエリーが失敗しました。' . $mysqli->error);
					$mysqli->close();
					exit();
				}
				while($arr_item = $result->fetch_assoc()){
					//レコード内の各フィールド名と値を順次参照
					foreach($arr_item as $key => $value){
						//フィールド名と値を表示
						$data[$key] = $value;
					}
				}
				
		//print("AtenaCnt=" . $data['AtenaCnt'] . "<BR>");
				
				if($data['AtenaCnt'] >= 1){
					$ErrMsg = "この生徒ＩＤはすでに登録されています";
					$_SESSION["S00_StudentID_ErrMsg"] = $ErrMsg;
					$_SESSION["S00_StudentID_COLER"] = $Background;
					$_SESSION["S00_Seq_COLER"] = $Background;
					$ErrCnt++;
				}

				if($_SESSION["S00_Atena_MODE"] == "NEW"){
					$query = "SELECT Count(*) as AtenaCnt FROM S_AtenaInfo";
					$query = $query . " Where replace(replace(Name1,' ',''),'　','')='" . preg_replace("/( |　)/", "", $_SESSION["S00_Name1"]) . "'";

					//print($query);

					$result = $mysqli->query($query);

					if (!$result) {
						print('クエリーが失敗しました。' . $mysqli->error);
						$mysqli->close();
						exit();
					}
					while($arr_item = $result->fetch_assoc()){
						//レコード内の各フィールド名と値を順次参照
						foreach($arr_item as $key => $value){
							//フィールド名と値を表示
							$data[$key] = $value;
						}
					}
					
			//print("AtenaCnt=" . $data['AtenaCnt'] . "<BR>");
					
					if($data['AtenaCnt'] >= 1){
						$ErrMsg = "同姓同名のデータ存在します";
						$_SESSION["S00_StudentID_ErrMsg"] = $ErrMsg;
						$_SESSION["S00_StudentID_COLER"] = $Background;
						$_SESSION["S00_Seq_COLER"] = $Background;
						$ErrCnt++;
					}
				}
			 	// データベースの切断
				$mysqli->close();		
			}
		}else{
			$ErrMsg = "未入力不可";
			$_SESSION["S00_StudentID_ErrMsg"] = $ErrMsg;
			$_SESSION["S00_StudentID_COLER"] = $Background;
			$ErrCnt++;
		}
	}
	//}
	//登録日チェック（未入力、型、未来）
	if($_SESSION["S00_EntryDay"] != ""){
		if (strptime($_SESSION["S00_EntryDay"], '%Y-%m-%d')) {
		}else{
			$ErrMsg = "登録日不正";
			$_SESSION["S00_EntryDay_ErrMsg"] = $ErrMsg;
			$_SESSION["S00_EntryDay_COLER"] = $Background;
			$ErrCnt++;
		}
	}
	//生年月日チェック（型、未来）
	if($_SESSION["S00_BirthDay"] != ""){
		if (strptime($_SESSION["S00_BirthDay"], '%Y-%m-%d')) {
		}else{
			$ErrMsg = "生年月日不正";
			$_SESSION["S00_BirthDay_ErrMsg"] = $ErrMsg;
			$_SESSION["S00_BirthDay_COLER"] = $Background;
			$ErrCnt++;
		}
	}
	//生徒名チェック　（未入力）
	if($_SESSION["S00_Name1"] == ""){
		$ErrMsg = "未入力不可";
		$_SESSION["S00_Name1_ErrMsg"] = $ErrMsg;
		$_SESSION["S00_Name1_COLER"] = $Background;
		$ErrCnt++;
	}

	//生徒カナチェック　（未入力）
	if($_SESSION["S00_Name2"] == ""){
		$ErrMsg = "未入力不可";
		$_SESSION["S00_Name2_ErrMsg"] = $ErrMsg;
		$_SESSION["S00_Name2_COLER"] = $Background;
		$ErrCnt++;
	}else{
		if (preg_match("/^[ぁ-んー]+$/u", substr($_SESSION["S00_Name2"], 0, 3))) {
		    // ひらがな
		}else{
			if (mb_substr($_SESSION["S00_Name2"], 0, 1)=="※") {

				//※
			}else{
				$ErrMsg = "ひらがなで入力";
				$_SESSION["S00_Name2_ErrMsg"] = $ErrMsg;
				$_SESSION["S00_Name2_COLER"] = $Background;
				$ErrCnt++;
			}
		}
	}

	//メールチェック　（@があるか）
	if($_SESSION["S00_Mail1"] != ""){
		if(strpos($_SESSION["S00_Mail1"],'@') === false){
			$ErrMsg = "メール型不正";
			$_SESSION["S00_Mail_ErrMsg1"] = $ErrMsg;
			$_SESSION["S00_Mail_COLER1"] = $Background;
			$ErrCnt++;
		}
	}
	if($_SESSION["S00_Mail2"] != ""){
		if(strpos($_SESSION["S00_Mail2"],'@') === false){
			$ErrMsg = "メール型不正";
			$_SESSION["S00_Mail_ErrMsg2"] = $ErrMsg;
			$_SESSION["S00_Mail_COLER2"] = $Background;
			$ErrCnt++;
		}
	}
	if($_SESSION["S00_Mail3"] != ""){
		if(strpos($_SESSION["S00_Mail3"],'@') === false){
			$ErrMsg = "メール型不正";
			$_SESSION["S00_Mail_ErrMsg3"] = $ErrMsg;
			$_SESSION["S00_Mail_COLER3"] = $Background;
			$ErrCnt++;
		}
	}
	//郵便番号桁数チェック
	if($_SESSION["S00_Yubin1_1"] != ""){
		$Yubin1_1 = mb_strlen($_SESSION["S00_Yubin1_1"]);
		if ($Yubin1_1 >= 4) {
			$ErrMsg = "桁数不正";
			$_SESSION["S00_Yubin1_2_ErrMsg"] = $ErrMsg;
			$_SESSION["S00_Yubin1_1_COLER"] = $Background;
			$ErrCnt++;
		}
	}
	if($_SESSION["S00_Yubin1_2"] != ""){
		$Yubin1_2 = mb_strlen($_SESSION["S00_Yubin1_2"]);
		if ($Yubin1_2 >= 5) {
			$ErrMsg = "桁数不正";
			$_SESSION["S00_Yubin1_2_ErrMsg"] = $ErrMsg;
			$_SESSION["S00_Yubin1_2_COLER"] = $Background;
			$ErrCnt++;
		}
	}
	//郵便番号桁数チェック
	if($_SESSION["S00_Yubin2_1"] != ""){
		$Yubin2_1 = mb_strlen($_SESSION["S00_Yubin2_1"]);
		if ($Yubin2_1 >= 4) {
			$ErrMsg = "桁数不正";
			$_SESSION["S00_Yubin2_2_ErrMsg"] = $ErrMsg;
			$_SESSION["S00_Yubin2_1_COLER"] = $Background;
			$ErrCnt++;
		}
	}
	if($_SESSION["S00_Yubin2_2"] != ""){
		$Yubin2_2 = mb_strlen($_SESSION["S00_Yubin2_2"]);
		if ($Yubin2_2 >= 5) {
			$ErrMsg = "桁数不正";
			$_SESSION["S00_Yubin2_2_ErrMsg"] = $ErrMsg;
			$_SESSION["S00_Yubin2_2_COLER"] = $Background;
			$ErrCnt++;
		}
	}

	//口座情報チェック
	$KozaCnt=0;
	for($m=0; $m<$_SESSION["S00_Koza_DataCount"]; $m++){

		$_SESSION["S00_Koza_Kigou" . $m ."_ErrMsg"]="";
		$_SESSION["S00_Koza_Kigou" . $m ."_COLER"]="";
		$_SESSION["S00_Koza_Bango" . $m ."_ErrMsg"]="";
		$_SESSION["S00_Koza_Bango" . $m ."_COLER"]="";
		$_SESSION["S00_Koza_Start" . $m ."_ErrMsg"]="";
		$_SESSION["S00_Koza_Start" . $m ."_COLER"]="";
		$_SESSION["S00_Koza_Meigi" . $m ."_ErrMsg"]="";
		$_SESSION["S00_Koza_Meigi" . $m ."_COLER"]="";
		$_SESSION["S00_Koza_MeigiKana" . $m ."_ErrMsg"]="";
		$_SESSION["S00_Koza_MeigiKana" . $m ."_COLER"]="";
		$_SESSION["S00_Koza_End" . $m ."_ErrMsg"]="";
		$_SESSION["S00_Koza_End" . $m ."_COLER"]="";
		$_SESSION["S00_Koza_End2" . $m ."_ErrMsg"]="";
		$_SESSION["S00_Koza_End2" . $m ."_COLER"]="";
		$_SESSION["S00_Koza_Biko" . $m ."_ErrMsg"]="";
		$_SESSION["S00_Koza_Biko" . $m ."_COLER"]="";

		if($_SESSION["S00_Koza_Kigou" . $m] == "" && $_SESSION["S00_Koza_Bango" . $m] == "" && $_SESSION["S00_Koza_Start" . $m] == "" && $_SESSION["S00_Koza_Meigi" . $m] == "" && $_SESSION["S00_Koza_MeigiKana" . $m] == "" && $_SESSION["S00_Koza_End" . $m] == ""){
		}else{
			//口座番号チェック（未入力、数値・桁数）
			if($_SESSION["S00_Koza_Kigou" . $m] == ""){
				$ErrMsg = "口座番号未入力";
				$_SESSION["S00_Koza_Kigou" . $m . "_ErrMsg"] = $ErrMsg;
				$_SESSION["S00_Koza_Kigou" . $m . "_COLER"] = $Background;
				$ErrCnt++;
			}
			if($_SESSION["S00_Koza_Bango" . $m] == ""){
				$ErrMsg = "口座番号未入力";
				$_SESSION["S00_Koza_Bango" . $m . "_ErrMsg"] = $ErrMsg;
				$_SESSION["S00_Koza_Bango" . $m . "_COLER"] = $Background;
				$ErrCnt++;
			}

			//口座開始（未入力、型）
			if($_SESSION["S00_Koza_Start" . $m] != ""){
				if (strptime($_SESSION["S00_Koza_Start" . $m], '%Y-%m-%d')) {
				}else{
					$ErrMsg = "口座開始不正";
					$_SESSION["S00_Koza_Start" . $m . "_ErrMsg"] = $ErrMsg;
					$_SESSION["S00_Koza_Start" . $m . "_COLER"] = $Background;
					$ErrCnt++;
				}
			}else{
				$ErrMsg = "未入力";
				$_SESSION["S00_Koza_Start" . $m . "_ErrMsg"] = $ErrMsg;
				$_SESSION["S00_Koza_Start" . $m . "_COLER"] = $Background;
				$ErrCnt++;
			}
			//名義人（未入力）
			if($_SESSION["S00_Koza_Meigi" . $m] == ""){
				$ErrMsg = "名義人未入力";
				$_SESSION["S00_Koza_Meigi" . $m . "_ErrMsg"] = $ErrMsg;
				$_SESSION["S00_Koza_Meigi" . $m . "_COLER"] = $Background;
				$ErrCnt++;
			}
			//名義人カナ（未入力）
			if($_SESSION["S00_Koza_MeigiKana" . $m] == ""){
				$ErrMsg = "名義人カナ未入力";
				$_SESSION["S00_Koza_MeigiKana" . $m . "_ErrMsg"] = $ErrMsg;
				$_SESSION["S00_Koza_MeigiKana" . $m . "_COLER"] = $Background;
				$ErrCnt++;
			}
			//終了日（型）
			if($_SESSION["S00_Koza_End" . $m] != ""){
				if (strptime($_SESSION["S00_Koza_End" . $m], '%Y-%m-%d')) {
				}else{
					$ErrMsg = "口座終了不正";
					$_SESSION["S00_Koza_End" . $m . "_ErrMsg"] = $ErrMsg;
					$_SESSION["S00_Koza_End" . $m . "_COLER"] = $Background;
					$ErrCnt++;
				}
			}
			//終了日が未入力の明細が複数ある場合
			if($_SESSION["S00_Koza_End" . $m] == ""){
				$KozaCnt++;
			}
			if($KozaCnt >= 2){
				$ErrMsg = "終了していない口座が複数あります";
				$_SESSION["S00_Koza_End2" . $m . "_ErrMsg"] = $ErrMsg;
				$_SESSION["S00_Koza_End2" . $m . "_COLER"] = $Background;
				$ErrCnt++;
			}
		}
	}


	if($ErrCnt > 0){
		$ErrMsg2 = "エラーがあります。確認してください。";
	}

	if($_SESSION["S00_Atena_MODE"] != "NEW"){
		if(($ErrCnt == 0) && ((($_SESSION["S00_StudentID"] != $_SESSION["moto_S00_StudentID"]) || ($_SESSION["S00_Seq"] != $_SESSION["moto_S00_Seq"])))){
			$ErrMsg2 = "生徒ＩＤが変更されます。口座情報は引継しません。<BR>よろしければ、ＩＤ変更ボタンを押してください。";
			$_SESSION["S00_ID_UPD"]=1;
		}
	}

	return $ErrMsg2;

	//教師名かなチェック　（なし）
	//郵便番号チェック　（なし）
	//都道府県チェック　（なし）
	//市町村チェック　（なし）
	//区チェック　（なし）
	//町チェック　（なし）
	//その他チェック　（なし）
	//電話番号チェック　（なし）
	//大学・学部（なし）
	//卒業年（なし）
	//教科・資格・経験・その他（なし）


}
//-----------------------------------------------------------
//	ID取得チェック処理
//-----------------------------------------------------------
Function CheckShori_idGet(){
$_SESSION["ErrMsg"] = "";

	$_SESSION["S00_StudentID"]=$_POST['S00_StudentID'];
	$_SESSION["S00_Name2"]=$_POST['S00_Name2'];

	if($_SESSION["S00_Name2"] == "" && $_SESSION["S00_StudentID"] == ""){
		$_SESSION["ErrMsg"] = "新規登録の場合は【生徒かな】を入力してください。<BR>更新の場合はすでに登録されている【生徒ID】を入力してください。";
		$Background="background-color: #F5A9F2";
		$ErrMsg = "未入力不可";
		$_SESSION["S00_Name2_ErrMsg"] = $ErrMsg;
		$_SESSION["S00_Name2_COLER"] = $Background;
		$_SESSION["S00_StudentID_ErrMsg"] = $ErrMsg;
		$_SESSION["S00_StudentID_COLER"] = $Background;

		$ErrCnt++;
	}else{
		$_SESSION["S00_Name2_ErrMsg"] = "";
		$_SESSION["S00_Name2_COLER"] = "";
		$_SESSION["S00_StudentID_ErrMsg"] = "";
		$_SESSION["S00_StudentID_COLER"] = "";

		if($_SESSION["S00_StudentID"] != ""){
			GetData($_SESSION["S00_StudentID"],"");
			$_SESSION["S00_Seq"] = (int)$_SESSION["S00_Seq"] + 1;

		}else{
			$_SESSION["S00_StudentID"] = "";
			$G_StudentID = IdGetShori();
			$_SESSION["S00_StudentID"] =$G_StudentID;
			$_SESSION["S00_Seq"] =0;
		}

	}
}
//-----------------------------------------------------------
//	取込チェック処理
//-----------------------------------------------------------
Function CheckShori_inst(){
$ErrMsg="";

	//取込日
	if($_SESSION["S00_InstDay"]==""){
		$ErrMsg = "取込日を入力してください。";
	}

	//取込区分
	if($_SESSION["S00_InstKubun"]==""){
		$ErrMsg = "取込区分を入力してください。";
	}

	//取込区分
	if($_SESSION["S00_InstHouho"]==""){
		$ErrMsg = "取込区分を入力してください。";
	}

	//取込データ
	if($_SESSION["S00_InstHouho"]=="04"){
		if($_SESSION["S00_InstData"]==""){
			$ErrMsg = "取込データをコピーしてください。";
		}
	}

	return $ErrMsg;
}
//-----------------------------------------------------------
//	更新処理
//-----------------------------------------------------------
Function UpdateShori(){
$ErrFlg = 0;

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

	$query = "UPDATE S_AtenaInfo SET ";
	$query = $query . " Name1 = '" . $_SESSION["S00_Name1"] . "'";
	$query = $query . ", Name2 = '" . $_SESSION["S00_Name2"] . "'";
	$query = $query . ", old = '" . $_SESSION["S00_old"] . "'";
	$query = $query . ", gread = '" . $_SESSION["S00_gread"] . "'";
	$query = $query . " WHERE  StudentID = '" . $_SESSION["S00_StudentID"] . "'";
	$query = $query . " AND  Seq = '" . $_SESSION["S00_Seq"] . "'";

	$result = $mysqli->query($query);

	//print($query);

	if (!$result) {
		$ErrMSG = "クエリーが失敗しました。（S_AtenaInfoエラー）" . $mysqli->error;
		$ErrFlg = 1;
	}

	if($ErrFlg == 0){
		$query2 = "UPDATE S_KihonInfo SET ";
		if($_SESSION["S00_EntryDay"] == ""){
			$query2 = $query2 . " EntryDay = NULL";
		}else{
			$query2 = $query2 . " EntryDay = '" . $_SESSION["S00_EntryDay"] . "'";
		}
		$query2 = $query2 . ", Hogosha1 = '" . $_SESSION["S00_Hogosha1"] . "'";
		$query2 = $query2 . ", HogoshaKana1 = '" . $_SESSION["S00_HogoshaKana1"] . "'";
		$query2 = $query2 . ", Hogo_Zoku1 = '" . $_SESSION["S00_Hogo_Zoku1"] . "'";
		$query2 = $query2 . ", Hogosha2 = '" . $_SESSION["S00_Hogosha2"] . "'";
		$query2 = $query2 . ", HogoshaKana2 = '" . $_SESSION["S00_HogoshaKana2"] . "'";
		$query2 = $query2 . ", Hogo_Zoku2 = '" . $_SESSION["S00_Hogo_Zoku2"] . "'";
		$query2 = $query2 . ", Kyodai1 = '" . $_SESSION["S00_Kyodai1"] . "'";
		$query2 = $query2 . ", Kyo_Zoku1 = '" . $_SESSION["S00_Kyo_Zoku1"] . "'";
		$query2 = $query2 . ", Kyo_gread1 = '" . $_SESSION["S00_Kyo_gread1"] . "'";
		$query2 = $query2 . ", Kyo_old1 = '" . $_SESSION["S00_Kyo_old1"] . "'";
		$query2 = $query2 . ", Kyodai2 = '" . $_SESSION["S00_Kyodai2"] . "'";
		$query2 = $query2 . ", Kyo_Zoku2 = '" . $_SESSION["S00_Kyo_Zoku2"] . "'";
		$query2 = $query2 . ", Kyo_gread2 = '" . $_SESSION["S00_Kyo_gread2"] . "'";
		$query2 = $query2 . ", Kyo_old2 = '" . $_SESSION["S00_Kyo_old2"] . "'";
		$query2 = $query2 . ", Kyodai3 = '" . $_SESSION["S00_Kyodai3"] . "'";
		$query2 = $query2 . ", Kyo_Zoku3 = '" . $_SESSION["S00_Kyo_Zoku3"] . "'";
		$query2 = $query2 . ", Kyo_gread3 = '" . $_SESSION["S00_Kyo_gread3"] . "'";
		$query2 = $query2 . ", Kyo_old3 = '" . $_SESSION["S00_Kyo_old3"] . "'";
		$query2 = $query2 . ", Add_Ken_Code1 = '" . $_SESSION["S00_Add_Ken_Code1"] . "'";
		$query2 = $query2 . ", Add_ken1 = '" . $_SESSION["S00_Add_ken1"] . "'";
		$query2 = $query2 . ", Add_shi1 = '" . $_SESSION["S00_Add_shi1"] . "'";
		$query2 = $query2 . ", Add_ku1 = '" . $_SESSION["S00_Add_ku1"] . "'";
		$query2 = $query2 . ", Add_cho1 = '" . $_SESSION["S00_Add_cho1"] . "'";
		$query2 = $query2 . ", Yubin1_1 = '" . $_SESSION["S00_Yubin1_1"] . "'";
		$query2 = $query2 . ", Yubin1_2 = '" . $_SESSION["S00_Yubin1_2"] . "'";
		$query2 = $query2 . ", Add_Ken_Code2 = '" . $_SESSION["S00_Add_Ken_Code2"] . "'";
		$query2 = $query2 . ", Add_ken2 = '" . $_SESSION["S00_Add_ken2"] . "'";
		$query2 = $query2 . ", Add_shi2 = '" . $_SESSION["S00_Add_shi2"] . "'";
		$query2 = $query2 . ", Add_ku2 = '" . $_SESSION["S00_Add_ku2"] . "'";
		$query2 = $query2 . ", Add_cho2 = '" . $_SESSION["S00_Add_cho2"] . "'";
		$query2 = $query2 . ", Yubin2_1 = '" . $_SESSION["S00_Yubin2_1"] . "'";
		$query2 = $query2 . ", Yubin2_2 = '" . $_SESSION["S00_Yubin2_2"] . "'";
		if($_SESSION["S00_BirthDay"] == ""){
			$query2 = $query2 . ", BirthDay = NULL";
		}else{
			$query2 = $query2 . ", BirthDay = '" . $_SESSION["S00_BirthDay"] . "'";
		}
		$query2 = $query2 . ", Seibetu = '" . $_SESSION["S00_Seibetu"] . "'";
		$query2 = $query2 . ", SchoolName = '" . $_SESSION["S00_SchoolName"] . "'";
		$query2 = $query2 . ", Tel_Kubun1 = '" . $_SESSION["S00_Tel_Kubun1"] . "'";
		$query2 = $query2 . ", Tel1 = '" . $_SESSION["S00_Tel1"] . "'";
		$query2 = $query2 . ", Tel_Kubun2 = '" . $_SESSION["S00_Tel_Kubun2"] . "'";
		$query2 = $query2 . ", Tel2 = '" . $_SESSION["S00_Tel2"] . "'";
		$query2 = $query2 . ", Tel_Kubun3 = '" . $_SESSION["S00_Tel_Kubun3"] . "'";
		$query2 = $query2 . ", Tel3 = '" . $_SESSION["S00_Tel3"] . "'";
		$query2 = $query2 . ", Mail_Kubun1 = '" . $_SESSION["S00_Mail_Kubun1"] . "'";
		$query2 = $query2 . ", Mail1 = '" . $_SESSION["S00_Mail1"] . "'";
		$query2 = $query2 . ", Mail_Kubun2 = '" . $_SESSION["S00_Mail_Kubun2"] . "'";
		$query2 = $query2 . ", Mail2 = '" . $_SESSION["S00_Mail2"] . "'";
		$query2 = $query2 . ", Mail_Kubun3 = '" . $_SESSION["S00_Mail_Kubun3"] . "'";
		$query2 = $query2 . ", Mail3 = '" . $_SESSION["S00_Mail3"] . "'";
		$query2 = $query2 . ", ContactTime0 = '" . $_SESSION["S00_ContactTime0"] . "'";
		$query2 = $query2 . ", ContactTime1 = '" . $_SESSION["S00_ContactTime1"] . "'";
		$query2 = $query2 . ", ContactTime2 = '" . $_SESSION["S00_ContactTime2"] . "'";
		$query2 = $query2 . ", ContactTime3 = '" . $_SESSION["S00_ContactTime3"] . "'";
		$query2 = $query2 . ", ContactTime4 = '" . $_SESSION["S00_ContactTimeSonota"] . "'";
		$query2 = $query2 . ", Kotu_rosen = '" . $_SESSION["S00_Kotu_rosen"] . "'";
		$query2 = $query2 . ", Kotu_Eki = '" . $_SESSION["S00_Kotu_Eki"] . "'";
		$query2 = $query2 . ", Kotu_Toho = '" . $_SESSION["S00_Kotu_Toho"] . "'";
		$query2 = $query2 . ", CarTF = '" . $_SESSION["S00_CarTF"] . "'";
		$query2 = $query2 . ", Kotu_Sonota = '" . $_SESSION["S00_Kotu_Sonota"] . "'";
		$query2 = $query2 . ", Kei_Day = '" . $_SESSION["S00_Kei_Day"] . "'";
		$query2 = $query2 . ", Kei_Tanto = '" . $_SESSION["S00_Kei_Tanto"] . "'";
		$query2 = $query2 . ", Kei_Aite = '" . $_SESSION["S00Kei_Aite"] . "'";
		$query2 = $query2 . ", Kei_Naiyo = '" . $_SESSION["S00_Kei_Naiyo"] . "'";
		$query2 = $query2 . ", Notice1 = '" . $_SESSION["S00_Notice1"] . "'";
		$query2 = $query2 . ", Notice2 = '" . $_SESSION["S00_Notice2"] . "'";
		$query2 = $query2 . ", Notice3 = '" . $_SESSION["S00_Notice3"] . "'";
		$query2 = $query2 . ", Notice4 = '" . $_SESSION["S00_Notice4"] . "'";
		$query2 = $query2 . ", Notice5 = '" . $_SESSION["S00_Notice5"] . "'";
		$query2 = $query2 . " WHERE  StudentID = '" . $_SESSION["S00_StudentID"] . "'";
		$query2 = $query2 . " And  AtenaSeq = '" . $_SESSION["S00_Seq"] . "'";

		$result2 = $mysqli->query($query2);

		//print($query2);

		if (!$result2) {
			$ErrMSG = "クエリーが失敗しました。（S_KihonInfoエラー）" . $mysqli->error;
			$ErrFlg = 1;
		}
	}

	if($ErrFlg == 0){

		$query3 = "UPDATE S_TourokuInfo SET ";
		$query3 = $query3 . " TorokuDay = '" . $_SESSION["S00_TorokuDay"] . "'";
		$query3 = $query3 . ", Genjyo1 = '" . $_SESSION["S00_Genjyo1"] . "'";
		$query3 = $query3 . ", Genjyo2 = '" . $_SESSION["S00_Genjyo2"] . "'";
		$query3 = $query3 . ", Genjyo3 = '" . $_SESSION["S00_Genjyo3"] . "'";
		$query3 = $query3 . ", Genjyo4 = '" . $_SESSION["S00_Genjyo4"] . "'";
		$query3 = $query3 . ", Genjyo5 = '" . $_SESSION["S00_Genjyo5"] . "'";
		$query3 = $query3 . ", Genjyo6 = '" . $_SESSION["S00_Genjyo6"] . "'";
		$query3 = $query3 . ", Genjyo7 = '" . $_SESSION["S00_Genjyo7"] . "'";
		$query3 = $query3 . ", Genjyo8 = '" . $_SESSION["S00_Genjyo8"] . "'";
		$query3 = $query3 . ", Genjyo9 = '" . $_SESSION["S00_Genjyo9"] . "'";
		$query3 = $query3 . ", Genjyo10 = '" . $_SESSION["S00_Genjyo10_Data"] . "'";
		$query3 = $query3 . ", Genjyo99 = '" . $_SESSION["S00_Genjyo99"] . "'";
		$query3 = $query3 . ", Genjyo_Sonota = '" . $_SESSION["S00_Genjyo_Sonota"] . "'";
		$query3 = $query3 . ", Sonota_Naiyo = '" . $_SESSION["S00_Sonota_Naiyo"] . "'";
		$query3 = $query3 . ", Soudan = '" . $_SESSION["S00_Soudan"] . "'";
		$query3 = $query3 . ", SiteJyoho = '" . $_SESSION["S00_SiteJyoho"] . "'";
		$query3 = $query3 . ", Sub1_1 = '" . $_SESSION["S00_Sub1_1"] . "'";
		$query3 = $query3 . ", Sub1_2 = '" . $_SESSION["S00_Sub1_2"] . "'";
		$query3 = $query3 . ", Sub1_3 = '" . $_SESSION["S00_Sub1_3"] . "'";
		$query3 = $query3 . ", Sub1_4 = '" . $_SESSION["S00_Sub1_4"] . "'";
		$query3 = $query3 . ", Sub1_5 = '" . $_SESSION["S00_Sub1_5"] . "'";
		$query3 = $query3 . ", Sub1_6 = '" . $_SESSION["S00_Sub1_6"] . "'";
		$query3 = $query3 . ", Sub1_7 = '" . $_SESSION["S00_Sub1_7"] . "'";
		$query3 = $query3 . ", Sub1_8 = '" . $_SESSION["S00_Sub1_8"] . "'";
		$query3 = $query3 . ", Sub1_9 = '" . $_SESSION["S00_Sub1_9"] . "'";
		$query3 = $query3 . ", Sub1_10 = '" . $_SESSION["S00_Sub1_10"] . "'";
		$query3 = $query3 . ", Sub2_1 = '" . $_SESSION["S00_Sub2_1"] . "'";
		$query3 = $query3 . ", Sub2_2 = '" . $_SESSION["S00_Sub2_2"] . "'";
		$query3 = $query3 . ", Sub2_3 = '" . $_SESSION["S00_Sub2_3"] . "'";
		$query3 = $query3 . ", Sub2_4 = '" . $_SESSION["S00_Sub2_4"] . "'";
		$query3 = $query3 . ", Sub2_5 = '" . $_SESSION["S00_Sub2_5"] . "'";
		$query3 = $query3 . ", Sub2_6 = '" . $_SESSION["S00_Sub2_6"] . "'";
		$query3 = $query3 . ", Sub2_7 = '" . $_SESSION["S00_Sub2_7"] . "'";
		$query3 = $query3 . ", Sub2_8 = '" . $_SESSION["S00_Sub2_8"] . "'";
		$query3 = $query3 . ", Sub2_9 = '" . $_SESSION["S00_Sub2_9"] . "'";
		$query3 = $query3 . ", Sub2_10 = '" . $_SESSION["S00_Sub2_10"] . "'";
		$query3 = $query3 . ", Sub3_1 = '" . $_SESSION["S00_Sub3_1"] . "'";
		$query3 = $query3 . ", Sub3_2 = '" . $_SESSION["S00_Sub3_2"] . "'";
		$query3 = $query3 . ", Sub3_3 = '" . $_SESSION["S00_Sub3_3"] . "'";
		$query3 = $query3 . ", Sub3_4 = '" . $_SESSION["S00_Sub3_4"] . "'";
		$query3 = $query3 . ", Sub3_5 = '" . $_SESSION["S00_Sub3_5"] . "'";
		$query3 = $query3 . ", Sub3_6 = '" . $_SESSION["S00_Sub3_6"] . "'";
		$query3 = $query3 . ", Sub3_7 = '" . $_SESSION["S00_Sub3_7"] . "'";
		$query3 = $query3 . ", Sub3_8 = '" . $_SESSION["S00_Sub3_8"] . "'";
		$query3 = $query3 . ", Sub3_9 = '" . $_SESSION["S00_Sub3_9"] . "'";
		$query3 = $query3 . ", Sub3_10 = '" . $_SESSION["S00_Sub3_10"] . "'";
		$query3 = $query3 . ", Sub3_11 = '" . $_SESSION["S00_Sub3_11"] . "'";
		$query3 = $query3 . ", Sub3_12 = '" . $_SESSION["S00_Sub3_12"] . "'";
		$query3 = $query3 . ", Sub3_13 = '" . $_SESSION["S00_Sub3_13"] . "'";
		$query3 = $query3 . ", Sub3_14 = '" . $_SESSION["S00_Sub3_14"] . "'";
		$query3 = $query3 . ", Sub3_15 = '" . $_SESSION["S00_Sub3_15"] . "'";
		$query3 = $query3 . ", Sub3_16 = '" . $_SESSION["S00_Sub3_16"] . "'";
		$query3 = $query3 . ", Sub3_17 = '" . $_SESSION["S00_Sub3_17"] . "'";
		$query3 = $query3 . ", Sub3_18 = '" . $_SESSION["S00_Sub3_18"] . "'";
		$query3 = $query3 . ", Sub3_19 = '" . $_SESSION["S00_Sub3_19"] . "'";
		$query3 = $query3 . ", Sub3_20 = '" . $_SESSION["S00_Sub3_20"] . "'";
		$query3 = $query3 . ", Sub3_21 = '" . $_SESSION["S00_Sub3_21"] . "'";
		$query3 = $query3 . ", Sub3_22 = '" . $_SESSION["S00_Sub3_22"] . "'";
		$query3 = $query3 . ", Sub3_23 = '" . $_SESSION["S00_Sub3_23"] . "'";
		$query3 = $query3 . ", Sub3_24 = '" . $_SESSION["S00_Sub3_24"] . "'";
		$query3 = $query3 . ", Sub3_25 = '" . $_SESSION["S00_Sub3_25"] . "'";
		$query3 = $query3 . ", Sub4_1 = '" . $_SESSION["S00_Sub4_1"] . "'";
		$query3 = $query3 . ", Sub4_2 = '" . $_SESSION["S00_Sub4_2"] . "'";
		$query3 = $query3 . ", Sub4_3 = '" . $_SESSION["S00_Sub4_3"] . "'";
		$query3 = $query3 . ", Sub4_4 = '" . $_SESSION["S00_Sub4_4"] . "'";
		$query3 = $query3 . ", Sub4_5 = '" . $_SESSION["S00_Sub4_5"] . "'";
		$query3 = $query3 . ", Kyoka_Sonota = '" . $_SESSION["S00_Kyoka_Sonota"] . "'";
		$query3 = $query3 . ", Youbi1 = '" . $_SESSION["S00_Youbi1"] . "'";
		$query3 = $query3 . ", Youbi2 = '" . $_SESSION["S00_Youbi2"] . "'";
		$query3 = $query3 . ", Youbi3 = '" . $_SESSION["S00_Youbi3"] . "'";
		$query3 = $query3 . ", Youbi4 = '" . $_SESSION["S00_Youbi4"] . "'";
		$query3 = $query3 . ", Youbi5 = '" . $_SESSION["S00_Youbi5"] . "'";
		$query3 = $query3 . ", Youbi6 = '" . $_SESSION["S00_Youbi6"] . "'";
		$query3 = $query3 . ", Youbi7 = '" . $_SESSION["S00_Youbi7"] . "'";
		$query3 = $query3 . ", Youbi_Sonota = '" . $_SESSION["S00_Youbi_Sonota"] . "'";
		$query3 = $query3 . ", ShidoTime = '" . $_SESSION["S00_ShidoTime"] . "'";
		$query3 = $query3 . ", ShidoKibou = '" . $_SESSION["S00_ShidoKibou"] . "'";
		$query3 = $query3 . ", KyoushiKibou0 = '" . $_SESSION["S00_ShidoKibou0"] . "'";
		$query3 = $query3 . ", KyoushiKibou1 = '" . $_SESSION["S00_KyoushiKibou1"] . "'";
		$query3 = $query3 . ", KyoushiKibou2 = '" . $_SESSION["S00_KyoushiKibou2"] . "'";
		$query3 = $query3 . ", KyoushiKibou3 = '" . $_SESSION["S00_KyoushiKibou3"] . "'";
		$query3 = $query3 . ", KyoushiKibou4 = '" . $_SESSION["S00_KyoushiKibou4"] . "'";
		$query3 = $query3 . ", KyoushiKibou5 = '" . $_SESSION["S00_KyoushiKibou5"] . "'";
		$query3 = $query3 . ", KyoushiKibou6 = '" . $_SESSION["S00_KyoushiKibou6"] . "'";
		$query3 = $query3 . ", KyoushiKibou7 = '" . $_SESSION["S00_KyoushiKibou7"] . "'";
		$query3 = $query3 . ", KyoushiKibouNaiyo = '" . $_SESSION["S00_KyoushiKibouNaiyo"] . "'";
		$query3 = $query3 . ", notice1 = '" . $_SESSION["S00_notice1"] . "'";
		$query3 = $query3 . ", notice2 = '" . $_SESSION["S00_notice2"] . "'";
		$query3 = $query3 . ", notice3 = '" . $_SESSION["S00_notice3"] . "'";
		$query3 = $query3 . " WHERE  StudentID = '" . $_SESSION["S00_StudentID"] . "'";
		$query3 = $query3 . " And  AtenaSeq = '" . $_SESSION["S00_Seq"] . "'";

		$result3 = $mysqli->query($query3);

		//print($query3);

		if (!$result3) {
			$ErrMSG = "クエリーが失敗しました。（S_TourokuInfoエラー）" . $mysqli->error;
			$ErrFlg = 1;
		}
	}

	if($ErrFlg == 0){
		for($m=0; $m<$_SESSION["S00_Koza_DataCount"]; $m++){
			if($ErrFlg == 0){
				$query4 = "Select Count(*) as CNT from S_KozaInfo ";
				$query4 = $query4 . " Where StudentID='" . $_SESSION["S00_Koza_StudentID" .$m] . "'";
				$query4 = $query4 . " And KozaSeq='" . $_SESSION["S00_Koza_KozaSeq" .$m] . "'";
				$result4 = $mysqli->query($query4);
//print($query4 . "<BR>");
				if (!$result4) {
					$ErrMSG = "クエリーが失敗しました。（S_KozaInfoエラー）" . $mysqli->error;
					$ErrFlg = 1;
				}
				while($arr_item = $result4->fetch_assoc()){

					//レコード内の各フィールド名と値を順次参照
					foreach($arr_item as $key => $value){
						//フィールド名と値を表示
						$_SESSION["KozaCNT"] = $value;
					}
				}
				if($ErrFlg == 0){
					if($_SESSION["KozaCNT"] > 0){
						$query4 = "UPDATE S_KozaInfo SET ";
						if($_SESSION["S00_Koza_Start" . $m] == ""){
							$query4 = $query4 . "Start = NULL";
						}else{
							$query4 = $query4 . "Start = '" . $_SESSION["S00_Koza_Start" . $m] . "'";
						}
						if($_SESSION["S00_Koza_End" . $m] == ""){
							$query4 = $query4 . ", End = NULL";
						}else{
							$query4 = $query4 . ", End = '" . $_SESSION["S00_Koza_End" . $m] . "'";
						}
						$query4 = $query4 . ", Kigou = '" . $_SESSION["S00_Koza_Kigou" . $m] . "'";
						$query4 = $query4 . ", Bango = '" . $_SESSION["S00_Koza_Bango" . $m] . "'";
						$query4 = $query4 . ", Meigi = '" . $_SESSION["S00_Koza_Meigi" . $m] . "'";
						$query4 = $query4 . ", MeigiKana = '" . $_SESSION["S00_Koza_MeigiKana" . $m] . "'";
						$query4 = $query4 . ", Biko = '" . $_SESSION["S00_Koza_Biko" . $m] . "'";
						$query4 = $query4 . " Where StudentID='" . $_SESSION["S00_Koza_StudentID" .$m] . "'";
						$query4 = $query4 . " And KozaSeq='" . $_SESSION["S00_Koza_KozaSeq" .$m] . "'";

						$result4 = $mysqli->query($query4);
						//print($query4);

						if (!$result4) {
							$ErrMSG = "クエリーが失敗しました。（S_KozaInfoエラー）" . $mysqli->error;
							$ErrFlg = 1;
						}
					}else{
						$query3 = "INSERT INTO S_KozaInfo ";
						$query3 = $query3 . " VALUES (";
						$query3 = $query3 . "'" . $_SESSION["S00_Koza_StudentID0"] . "',";
						$query3 = $query3 . "'" . $_SESSION["S00_Koza_KozaSeq" . $m] . "',";
						if($_SESSION["S00_Koza_Start" . $m] == ""){
							$query3 = $query3 . "NULL,";
						}else{
							$query3 = $query3 . "'" . $_SESSION["S00_Koza_Start" . $m] . "',";
						}
						if($_SESSION["S00_Koza_End" . $m] == ""){
							$query3 = $query3 . "NULL,";
						}else{
							$query3 = $query3 . "'" . $_SESSION["S00_Koza_End" . $m] . "',";
						}
						$query3 = $query3 . "'" . $_SESSION["S00_Koza_Kigou" . $m] . "',";
						$query3 = $query3 . "'" . $_SESSION["S00_Koza_Bango" . $m] . "',";
						$query3 = $query3 . "'" . $_SESSION["S00_Koza_Meigi" . $m] . "',";
						$query3 = $query3 . "'" . $_SESSION["S00_Koza_MeigiKana" . $m] . "',";
						$query3 = $query3 . "'" . $_SESSION["S00_Koza_Biko" . $m] . "')";

						$result3 = $mysqli->query($query3);

						//print($query3);

						if (!$result3) {
							$ErrMSG = "クエリーが失敗しました。（S_KozaInfoエラー）" . $mysqli->error;
							$ErrFlg = 1;
						}
					}
				}
			}
		}
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
//-----------------------------------------------------------
//	更新処理
//-----------------------------------------------------------
Function ID_UpdateShori(){
$ErrFlg = 0;

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

	$query = "UPDATE S_AtenaInfo SET ";
	$query = $query . " StudentID = '" . $_SESSION["S00_StudentID"] . "'";
	$query = $query . ", Seq = '" . $_SESSION["S00_Seq"] . "'";
	$query = $query . ", Name1 = '" . $_SESSION["S00_Name1"] . "'";
	$query = $query . ", Name2 = '" . $_SESSION["S00_Name2"] . "'";
	$query = $query . ", old = '" . $_SESSION["S00_old"] . "'";
	$query = $query . ", gread = '" . $_SESSION["S00_gread"] . "'";
	$query = $query . " WHERE  StudentID = '" . $_SESSION["moto_S00_StudentID"] . "'";
	$query = $query . " AND  Seq = '" . $_SESSION["moto_S00_Seq"] . "'";

	$result = $mysqli->query($query);

	//print($query);

	if (!$result) {
		$ErrMSG = "クエリーが失敗しました。（S_AtenaInfoエラー）" . $mysqli->error;
		$ErrFlg = 1;
	}

	if($ErrFlg == 0){
		$query2 = "UPDATE S_KihonInfo SET ";
		$query2 = $query2 . " StudentID = '" . $_SESSION["S00_StudentID"] . "'";
		$query2 = $query2 . ", AtenaSeq = '" . $_SESSION["S00_Seq"] . "'";

		if($_SESSION["S00_EntryDay"] == ""){
			$query2 = $query2 . ", EntryDay = NULL";
		}else{
			$query2 = $query2 . ", EntryDay = '" . $_SESSION["S00_EntryDay"] . "'";
		}
		$query2 = $query2 . ", Hogosha1 = '" . $_SESSION["S00_Hogosha1"] . "'";
		$query2 = $query2 . ", HogoshaKana1 = '" . $_SESSION["S00_HogoshaKana1"] . "'";
		$query2 = $query2 . ", Hogo_Zoku1 = '" . $_SESSION["S00_Hogo_Zoku1"] . "'";
		$query2 = $query2 . ", Hogosha2 = '" . $_SESSION["S00_Hogosha2"] . "'";
		$query2 = $query2 . ", HogoshaKana2 = '" . $_SESSION["S00_HogoshaKana2"] . "'";
		$query2 = $query2 . ", Hogo_Zoku2 = '" . $_SESSION["S00_Hogo_Zoku2"] . "'";
		$query2 = $query2 . ", Kyodai1 = '" . $_SESSION["S00_Kyodai1"] . "'";
		$query2 = $query2 . ", Kyo_Zoku1 = '" . $_SESSION["S00_Kyo_Zoku1"] . "'";
		$query2 = $query2 . ", Kyo_gread1 = '" . $_SESSION["S00_Kyo_gread1"] . "'";
		$query2 = $query2 . ", Kyo_old1 = '" . $_SESSION["S00_Kyo_old1"] . "'";
		$query2 = $query2 . ", Kyodai2 = '" . $_SESSION["S00_Kyodai2"] . "'";
		$query2 = $query2 . ", Kyo_Zoku2 = '" . $_SESSION["S00_Kyo_Zoku2"] . "'";
		$query2 = $query2 . ", Kyo_gread2 = '" . $_SESSION["S00_Kyo_gread2"] . "'";
		$query2 = $query2 . ", Kyo_old2 = '" . $_SESSION["S00_Kyo_old2"] . "'";
		$query2 = $query2 . ", Kyodai3 = '" . $_SESSION["S00_Kyodai3"] . "'";
		$query2 = $query2 . ", Kyo_Zoku3 = '" . $_SESSION["S00_Kyo_Zoku3"] . "'";
		$query2 = $query2 . ", Kyo_gread3 = '" . $_SESSION["S00_Kyo_gread3"] . "'";
		$query2 = $query2 . ", Kyo_old3 = '" . $_SESSION["S00_Kyo_old3"] . "'";
		$query2 = $query2 . ", Add_Ken_Code1 = '" . $_SESSION["S00_Add_Ken_Code1"] . "'";
		$query2 = $query2 . ", Add_ken1 = '" . $_SESSION["S00_Add_ken1"] . "'";
		$query2 = $query2 . ", Add_shi1 = '" . $_SESSION["S00_Add_shi1"] . "'";
		$query2 = $query2 . ", Add_ku1 = '" . $_SESSION["S00_Add_ku1"] . "'";
		$query2 = $query2 . ", Add_cho1 = '" . $_SESSION["S00_Add_cho1"] . "'";
		$query2 = $query2 . ", Yubin1_1 = '" . $_SESSION["S00_Yubin1_1"] . "'";
		$query2 = $query2 . ", Yubin1_2 = '" . $_SESSION["S00_Yubin1_2"] . "'";
		$query2 = $query2 . ", Add_Ken_Code2 = '" . $_SESSION["S00_Add_Ken_Code2"] . "'";
		$query2 = $query2 . ", Add_ken2 = '" . $_SESSION["S00_Add_ken2"] . "'";
		$query2 = $query2 . ", Add_shi2 = '" . $_SESSION["S00_Add_shi2"] . "'";
		$query2 = $query2 . ", Add_ku2 = '" . $_SESSION["S00_Add_ku2"] . "'";
		$query2 = $query2 . ", Add_cho2 = '" . $_SESSION["S00_Add_cho2"] . "'";
		$query2 = $query2 . ", Yubin2_1 = '" . $_SESSION["S00_Yubin2_1"] . "'";
		$query2 = $query2 . ", Yubin2_2 = '" . $_SESSION["S00_Yubin2_2"] . "'";
		if($_SESSION["S00_BirthDay"] == ""){
			$query2 = $query2 . ", BirthDay = NULL";
		}else{
			$query2 = $query2 . ", BirthDay = '" . $_SESSION["S00_BirthDay"] . "'";
		}
		$query2 = $query2 . ", Seibetu = '" . $_SESSION["S00_Seibetu"] . "'";
		$query2 = $query2 . ", SchoolName = '" . $_SESSION["S00_SchoolName"] . "'";
		$query2 = $query2 . ", Tel_Kubun1 = '" . $_SESSION["S00_Tel_Kubun1"] . "'";
		$query2 = $query2 . ", Tel1 = '" . $_SESSION["S00_Tel1"] . "'";
		$query2 = $query2 . ", Tel_Kubun2 = '" . $_SESSION["S00_Tel_Kubun2"] . "'";
		$query2 = $query2 . ", Tel2 = '" . $_SESSION["S00_Tel2"] . "'";
		$query2 = $query2 . ", Tel_Kubun3 = '" . $_SESSION["S00_Tel_Kubun3"] . "'";
		$query2 = $query2 . ", Tel3 = '" . $_SESSION["S00_Tel3"] . "'";
		$query2 = $query2 . ", Mail_Kubun1 = '" . $_SESSION["S00_Mail_Kubun1"] . "'";
		$query2 = $query2 . ", Mail1 = '" . $_SESSION["S00_Mail1"] . "'";
		$query2 = $query2 . ", Mail_Kubun2 = '" . $_SESSION["S00_Mail_Kubun2"] . "'";
		$query2 = $query2 . ", Mail2 = '" . $_SESSION["S00_Mail2"] . "'";
		$query2 = $query2 . ", Mail_Kubun3 = '" . $_SESSION["S00_Mail_Kubun3"] . "'";
		$query2 = $query2 . ", Mail3 = '" . $_SESSION["S00_Mail3"] . "'";
		$query2 = $query2 . ", ContactTime0 = '" . $_SESSION["S00_ContactTime0"] . "'";
		$query2 = $query2 . ", ContactTime1 = '" . $_SESSION["S00_ContactTime1"] . "'";
		$query2 = $query2 . ", ContactTime2 = '" . $_SESSION["S00_ContactTime2"] . "'";
		$query2 = $query2 . ", ContactTime3 = '" . $_SESSION["S00_ContactTime3"] . "'";
		$query2 = $query2 . ", ContactTime4 = '" . $_SESSION["S00_ContactTimeSonota"] . "'";
		$query2 = $query2 . ", Kotu_rosen = '" . $_SESSION["S00_Kotu_rosen"] . "'";
		$query2 = $query2 . ", Kotu_Eki = '" . $_SESSION["S00_Kotu_Eki"] . "'";
		$query2 = $query2 . ", Kotu_Toho = '" . $_SESSION["S00_Kotu_Toho"] . "'";
		$query2 = $query2 . ", CarTF = '" . $_SESSION["S00_CarTF"] . "'";
		$query2 = $query2 . ", Kotu_Sonota = '" . $_SESSION["S00_Kotu_Sonota"] . "'";
		$query2 = $query2 . ", Kei_Day = '" . $_SESSION["S00_Kei_Day"] . "'";
		$query2 = $query2 . ", Kei_Tanto = '" . $_SESSION["S00_Kei_Tanto"] . "'";
		$query2 = $query2 . ", Kei_Aite = '" . $_SESSION["S00Kei_Aite"] . "'";
		$query2 = $query2 . ", Kei_Naiyo = '" . $_SESSION["S00_Kei_Naiyo"] . "'";
		$query2 = $query2 . ", Notice1 = '" . $_SESSION["S00_Notice1"] . "'";
		$query2 = $query2 . ", Notice2 = '" . $_SESSION["S00_Notice2"] . "'";
		$query2 = $query2 . ", Notice3 = '" . $_SESSION["S00_Notice3"] . "'";
		$query2 = $query2 . ", Notice4 = '" . $_SESSION["S00_Notice4"] . "'";
		$query2 = $query2 . ", Notice5 = '" . $_SESSION["S00_Notice5"] . "'";
		$query2 = $query2 . " WHERE  StudentID = '" . $_SESSION["moto_S00_StudentID"] . "'";
		$query2 = $query2 . " And  AtenaSeq = '" . $_SESSION["moto_S00_Seq"] . "'";

		$result2 = $mysqli->query($query2);

		//print($query2);

		if (!$result2) {
			$ErrMSG = "クエリーが失敗しました。（S_KihonInfoエラー）" . $mysqli->error;
			$ErrFlg = 1;
		}
	}

	if($ErrFlg == 0){

		$query3 = "UPDATE S_TourokuInfo SET ";
		$query3 = $query3 . " StudentID = '" . $_SESSION["S00_StudentID"] . "'";
		$query3 = $query3 . ", AtenaSeq = '" . $_SESSION["S00_Seq"] . "'";
		$query3 = $query3 . ", TorokuDay = '" . $_SESSION["S00_TorokuDay"] . "'";
		$query3 = $query3 . ", Genjyo1 = '" . $_SESSION["S00_Genjyo1"] . "'";
		$query3 = $query3 . ", Genjyo2 = '" . $_SESSION["S00_Genjyo2"] . "'";
		$query3 = $query3 . ", Genjyo3 = '" . $_SESSION["S00_Genjyo3"] . "'";
		$query3 = $query3 . ", Genjyo4 = '" . $_SESSION["S00_Genjyo4"] . "'";
		$query3 = $query3 . ", Genjyo5 = '" . $_SESSION["S00_Genjyo5"] . "'";
		$query3 = $query3 . ", Genjyo6 = '" . $_SESSION["S00_Genjyo6"] . "'";
		$query3 = $query3 . ", Genjyo7 = '" . $_SESSION["S00_Genjyo7"] . "'";
		$query3 = $query3 . ", Genjyo8 = '" . $_SESSION["S00_Genjyo8"] . "'";
		$query3 = $query3 . ", Genjyo9 = '" . $_SESSION["S00_Genjyo9"] . "'";
		$query3 = $query3 . ", Genjyo10 = '" . $_SESSION["S00_Genjyo10_Data"] . "'";
		$query3 = $query3 . ", Genjyo99 = '" . $_SESSION["S00_Genjyo99"] . "'";
		$query3 = $query3 . ", Genjyo_Sonota = '" . $_SESSION["S00_Genjyo_Sonota"] . "'";
		$query3 = $query3 . ", Sonota_Naiyo = '" . $_SESSION["S00_Sonota_Naiyo"] . "'";
		$query3 = $query3 . ", Soudan = '" . $_SESSION["S00_Soudan"] . "'";
		$query3 = $query3 . ", SiteJyoho = '" . $_SESSION["S00_SiteJyoho"] . "'";
		$query3 = $query3 . ", Sub1_1 = '" . $_SESSION["S00_Sub1_1"] . "'";
		$query3 = $query3 . ", Sub1_2 = '" . $_SESSION["S00_Sub1_2"] . "'";
		$query3 = $query3 . ", Sub1_3 = '" . $_SESSION["S00_Sub1_3"] . "'";
		$query3 = $query3 . ", Sub1_4 = '" . $_SESSION["S00_Sub1_4"] . "'";
		$query3 = $query3 . ", Sub1_5 = '" . $_SESSION["S00_Sub1_5"] . "'";
		$query3 = $query3 . ", Sub1_6 = '" . $_SESSION["S00_Sub1_6"] . "'";
		$query3 = $query3 . ", Sub1_7 = '" . $_SESSION["S00_Sub1_7"] . "'";
		$query3 = $query3 . ", Sub1_8 = '" . $_SESSION["S00_Sub1_8"] . "'";
		$query3 = $query3 . ", Sub1_9 = '" . $_SESSION["S00_Sub1_9"] . "'";
		$query3 = $query3 . ", Sub1_10 = '" . $_SESSION["S00_Sub1_10"] . "'";
		$query3 = $query3 . ", Sub2_1 = '" . $_SESSION["S00_Sub2_1"] . "'";
		$query3 = $query3 . ", Sub2_2 = '" . $_SESSION["S00_Sub2_2"] . "'";
		$query3 = $query3 . ", Sub2_3 = '" . $_SESSION["S00_Sub2_3"] . "'";
		$query3 = $query3 . ", Sub2_4 = '" . $_SESSION["S00_Sub2_4"] . "'";
		$query3 = $query3 . ", Sub2_5 = '" . $_SESSION["S00_Sub2_5"] . "'";
		$query3 = $query3 . ", Sub2_6 = '" . $_SESSION["S00_Sub2_6"] . "'";
		$query3 = $query3 . ", Sub2_7 = '" . $_SESSION["S00_Sub2_7"] . "'";
		$query3 = $query3 . ", Sub2_8 = '" . $_SESSION["S00_Sub2_8"] . "'";
		$query3 = $query3 . ", Sub2_9 = '" . $_SESSION["S00_Sub2_9"] . "'";
		$query3 = $query3 . ", Sub2_10 = '" . $_SESSION["S00_Sub2_10"] . "'";
		$query3 = $query3 . ", Sub3_1 = '" . $_SESSION["S00_Sub3_1"] . "'";
		$query3 = $query3 . ", Sub3_2 = '" . $_SESSION["S00_Sub3_2"] . "'";
		$query3 = $query3 . ", Sub3_3 = '" . $_SESSION["S00_Sub3_3"] . "'";
		$query3 = $query3 . ", Sub3_4 = '" . $_SESSION["S00_Sub3_4"] . "'";
		$query3 = $query3 . ", Sub3_5 = '" . $_SESSION["S00_Sub3_5"] . "'";
		$query3 = $query3 . ", Sub3_6 = '" . $_SESSION["S00_Sub3_6"] . "'";
		$query3 = $query3 . ", Sub3_7 = '" . $_SESSION["S00_Sub3_7"] . "'";
		$query3 = $query3 . ", Sub3_8 = '" . $_SESSION["S00_Sub3_8"] . "'";
		$query3 = $query3 . ", Sub3_9 = '" . $_SESSION["S00_Sub3_9"] . "'";
		$query3 = $query3 . ", Sub3_10 = '" . $_SESSION["S00_Sub3_10"] . "'";
		$query3 = $query3 . ", Sub3_11 = '" . $_SESSION["S00_Sub3_11"] . "'";
		$query3 = $query3 . ", Sub3_12 = '" . $_SESSION["S00_Sub3_12"] . "'";
		$query3 = $query3 . ", Sub3_13 = '" . $_SESSION["S00_Sub3_13"] . "'";
		$query3 = $query3 . ", Sub3_14 = '" . $_SESSION["S00_Sub3_14"] . "'";
		$query3 = $query3 . ", Sub3_15 = '" . $_SESSION["S00_Sub3_15"] . "'";
		$query3 = $query3 . ", Sub3_16 = '" . $_SESSION["S00_Sub3_16"] . "'";
		$query3 = $query3 . ", Sub3_17 = '" . $_SESSION["S00_Sub3_17"] . "'";
		$query3 = $query3 . ", Sub3_18 = '" . $_SESSION["S00_Sub3_18"] . "'";
		$query3 = $query3 . ", Sub3_19 = '" . $_SESSION["S00_Sub3_19"] . "'";
		$query3 = $query3 . ", Sub3_20 = '" . $_SESSION["S00_Sub3_20"] . "'";
		$query3 = $query3 . ", Sub3_21 = '" . $_SESSION["S00_Sub3_21"] . "'";
		$query3 = $query3 . ", Sub3_22 = '" . $_SESSION["S00_Sub3_22"] . "'";
		$query3 = $query3 . ", Sub3_23 = '" . $_SESSION["S00_Sub3_23"] . "'";
		$query3 = $query3 . ", Sub3_24 = '" . $_SESSION["S00_Sub3_24"] . "'";
		$query3 = $query3 . ", Sub3_25 = '" . $_SESSION["S00_Sub3_25"] . "'";
		$query3 = $query3 . ", Sub4_1 = '" . $_SESSION["S00_Sub4_1"] . "'";
		$query3 = $query3 . ", Sub4_2 = '" . $_SESSION["S00_Sub4_2"] . "'";
		$query3 = $query3 . ", Sub4_3 = '" . $_SESSION["S00_Sub4_3"] . "'";
		$query3 = $query3 . ", Sub4_4 = '" . $_SESSION["S00_Sub4_4"] . "'";
		$query3 = $query3 . ", Sub4_5 = '" . $_SESSION["S00_Sub4_5"] . "'";
		$query3 = $query3 . ", Kyoka_Sonota = '" . $_SESSION["S00_Kyoka_Sonota"] . "'";
		$query3 = $query3 . ", Youbi1 = '" . $_SESSION["S00_Youbi1"] . "'";
		$query3 = $query3 . ", Youbi2 = '" . $_SESSION["S00_Youbi2"] . "'";
		$query3 = $query3 . ", Youbi3 = '" . $_SESSION["S00_Youbi3"] . "'";
		$query3 = $query3 . ", Youbi4 = '" . $_SESSION["S00_Youbi4"] . "'";
		$query3 = $query3 . ", Youbi5 = '" . $_SESSION["S00_Youbi5"] . "'";
		$query3 = $query3 . ", Youbi6 = '" . $_SESSION["S00_Youbi6"] . "'";
		$query3 = $query3 . ", Youbi7 = '" . $_SESSION["S00_Youbi7"] . "'";
		$query3 = $query3 . ", Youbi_Sonota = '" . $_SESSION["S00_Youbi_Sonota"] . "'";
		$query3 = $query3 . ", ShidoTime = '" . $_SESSION["S00_ShidoTime"] . "'";
		$query3 = $query3 . ", ShidoKibou = '" . $_SESSION["S00_ShidoKibou"] . "'";
		$query3 = $query3 . ", KyoushiKibou0 = '" . $_SESSION["S00_ShidoKibou0"] . "'";
		$query3 = $query3 . ", KyoushiKibou1 = '" . $_SESSION["S00_KyoushiKibou1"] . "'";
		$query3 = $query3 . ", KyoushiKibou2 = '" . $_SESSION["S00_KyoushiKibou2"] . "'";
		$query3 = $query3 . ", KyoushiKibou3 = '" . $_SESSION["S00_KyoushiKibou3"] . "'";
		$query3 = $query3 . ", KyoushiKibou4 = '" . $_SESSION["S00_KyoushiKibou4"] . "'";
		$query3 = $query3 . ", KyoushiKibou5 = '" . $_SESSION["S00_KyoushiKibou5"] . "'";
		$query3 = $query3 . ", KyoushiKibou6 = '" . $_SESSION["S00_KyoushiKibou6"] . "'";
		$query3 = $query3 . ", KyoushiKibou7 = '" . $_SESSION["S00_KyoushiKibou7"] . "'";
		$query3 = $query3 . ", KyoushiKibouNaiyo = '" . $_SESSION["S00_KyoushiKibouNaiyo"] . "'";
		$query3 = $query3 . ", notice1 = '" . $_SESSION["S00_notice1"] . "'";
		$query3 = $query3 . ", notice2 = '" . $_SESSION["S00_notice2"] . "'";
		$query3 = $query3 . ", notice3 = '" . $_SESSION["S00_notice3"] . "'";
		$query3 = $query3 . " WHERE  StudentID = '" . $_SESSION["moto_S00_StudentID"] . "'";
		$query3 = $query3 . " And  AtenaSeq = '" . $_SESSION["moto_S00_Seq"] . "'";

		$result3 = $mysqli->query($query3);

		//print($query3);

		if (!$result3) {
			$ErrMSG = "クエリーが失敗しました。（S_TourokuInfoエラー）" . $mysqli->error;
			$ErrFlg = 1;
		}
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
//-----------------------------------------------------------
//	登録処理
//-----------------------------------------------------------
Function NewShori(){
$ErrFlg = 0;

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

	$query = "INSERT INTO S_AtenaInfo ";
	$query = $query . " VALUES (";
	$query = $query . "'" . $_SESSION["S00_StudentID"] . "',";
	$query = $query . "'" . $_SESSION["S00_Seq"] . "',";
	$query = $query . "'" . $_SESSION["S00_Name1"] . "',";
	$query = $query . "'" . $_SESSION["S00_Name2"] . "',";
	$query = $query . "'" . $_SESSION["S00_old"] . "',";
	$query = $query . "'" . $_SESSION["S00_gread"] . "',";
	$query = $query . "'0',";
	$query = $query . "'0',";
	$query = $query . "'0',";
	$query = $query . "'99',";
	$query = $query . "'" . $_SESSION["Today2"] . "')";

	$result = $mysqli->query($query);

	//print($query);

	if (!$result) {
		$ErrMSG = "クエリーが失敗しました。（S_AtenaInfoエラー）" . $mysqli->error;
		$ErrFlg = 1;
	}

	if($ErrFlg == 0){
		$query2 = "INSERT INTO S_KihonInfo ";
		$query2 = $query2 . " VALUES (";
		$query2 = $query2 . "'" . $_SESSION["S00_StudentID"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Seq"] . "',";
		if($_SESSION["S00_EntryDay"]==""){
			$query2 = $query2 . "NULL,";
		}else{
			$query2 = $query2 . "'" . $_SESSION["S00_EntryDay"] . "',";
		}
		$query2 = $query2 . "'" . $_SESSION["S00_Hogosha1"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_HogoshaKana1"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Hogo_Zoku1"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Hogosha2"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_HogoshaKana2"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Hogo_Zoku2"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Kyodai1"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Kyo_Zoku1"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Kyo_gread1"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Kyo_old1"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Kyodai2"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Kyo_Zoku2"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Kyo_gread2"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Kyo_old2"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Kyodai3"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Kyo_Zoku3"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Kyo_gread3"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Kyo_old3"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Add_Ken_Code1"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Add_ken1"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Add_shi1"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Add_ku1"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Add_cho1"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Yubin1_1"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Yubin1_2"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Add_Ken_Code2"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Add_ken2"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Add_shi2"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Add_ku2"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Add_cho2"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Yubin2_1"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Yubin2_2"] . "',";
		if($_SESSION["S00_BirthDay"]==""){
			$query2 = $query2 . "NULL,";
		}else{
			$query2 = $query2 . "'" . $_SESSION["S00_BirthDay"] . "',";
		}
		$query2 = $query2 . "'" . $_SESSION["S00_Seibetu"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_SchoolName"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Tel_Kubun1"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Tel1"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Tel_Kubun2"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Tel2"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Tel_Kubun3"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Tel3"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Mail_Kubun1"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Mail1"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Mail_Kubun2"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Mail2"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Mail_Kubun3"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Mail3"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_ContactTime0"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_ContactTime1"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_ContactTime2"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_ContactTime3"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_ContactTimeSonota"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Kotu_rosen"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Kotu_Eki"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Kotu_Toho"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_CarTF"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Kotu_Sonota"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Kei_Day"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Kei_Tanto"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Kei_Aite"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Kei_Naiyo"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Notice1"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Notice2"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Notice3"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Notice4"] . "',";
		$query2 = $query2 . "'" . $_SESSION["S00_Notice5"] . "')";

		$result2 = $mysqli->query($query2);

		//print($query2);

		if (!$result2) {
			$ErrMSG = "クエリーが失敗しました。（S_KihonInfoエラー）" . $mysqli->error;
			$ErrFlg = 1;
		}
	}

	if($ErrFlg == 0){
		$query3 = "INSERT INTO S_TourokuInfo ";
		$query3 = $query3 . " VALUES (";
		$query3 = $query3 . "'" . $_SESSION["S00_StudentID"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Seq"] . "',";
		if($_SESSION["S00_TorokuDay"]==""){
			$query3 = $query3 . "NULL,";
		}else{
			$query3 = $query3 . "'" . $_SESSION["S00_TorokuDay"] . "',";
		}
		$query3 = $query3 . "'" . $_SESSION["S00_Genjyo1"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Genjyo2"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Genjyo3"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Genjyo4"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Genjyo5"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Genjyo6"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Genjyo7"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Genjyo8"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Genjyo9"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Genjyo10_Data"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Genjyo99"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Genjyo_Sonota"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sonota_Naiyo"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Soudan"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_SiteJyoho"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub1_1"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub1_2"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub1_3"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub1_4"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub1_5"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub1_6"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub1_7"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub1_8"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub1_9"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub1_10"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub2_1"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub2_2"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub2_3"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub2_4"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub2_5"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub2_6"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub2_7"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub2_8"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub2_9"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub2_10"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub3_1"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub3_2"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub3_3"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub3_4"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub3_5"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub3_6"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub3_7"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub3_8"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub3_9"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub3_10"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub3_11"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub3_12"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub3_13"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub3_14"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub3_15"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub3_16"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub3_17"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub3_18"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub3_19"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub3_20"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub3_21"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub3_22"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub3_23"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub3_24"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub3_25"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub4_1"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub4_2"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub4_3"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub4_4"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Sub4_5"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Kyoka_Sonota"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Youbi1"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Youbi2"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Youbi3"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Youbi4"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Youbi5"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Youbi6"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Youbi7"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Youbi_Sonota"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_ShidoTime"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_ShidoKibou"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_KyoushiKibou0"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_KyoushiKibou1"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_KyoushiKibou2"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_KyoushiKibou3"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_KyoushiKibou4"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_KyoushiKibou5"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_KyoushiKibou6"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_KyoushiKibou7"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_KyoushiKibouNaiyo"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_notice1"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_notice2"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_notice3"] . "')";

		$result3 = $mysqli->query($query3);

		//print($query3);

		if (!$result3) {
			$ErrMSG = "クエリーが失敗しました。（S_KihonInfoエラー）" . $mysqli->error;
			$ErrFlg = 1;
		}
	}

	if($ErrFlg == 0){
		$SesshoSeq = 0;
		$query = "SELECT SesshoSeq,Count(*) as CNT FROM TS_SeshoInfo ";
		$query = $query . " where TeacherID=0";
		$query = $query . " And StudentID='" . $_SESSION["S00_StudentID"] . "'";
		$query = $query . " And AtenaSeq='" . $_SESSION["S00_Seq"] . "'";
		$query = $query . " And SesshoDay='" . $_SESSION["S00_TorokuDay"] . "'";
		$query = $query . " Order by SesshoSeq Desc";
		$query = $query . " LIMIT 1";

		$result = $mysqli->query($query);

		if (!$result) {
			print('クエリーが失敗しました。' . $mysqli->error);
			$ErrFlg = 1;
		}
		
		while($arr_item = $result->fetch_assoc()){

			//レコード内の各フィールド名と値を順次参照
			foreach($arr_item as $key => $value){
				//フィールド名と値を表示
				$_SESSION["W00_" . $key] = $value;
			}
		}
		if($_SESSION["W00_CNT"] > 0){
			$SesshoSeq = (int)$_SESSION["W00_AtenaSeq"] + 1;
		}
	}

	if($ErrFlg == 0){
		
		$query3 = "INSERT INTO TS_SeshoInfo ";
		$query3 = $query3 . " VALUES (";
		$query3 = $query3 . "0,";
		$query3 = $query3 . "'" . $_SESSION["S00_StudentID"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_Seq"] . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_EntryDay"] . "',";
		$query3 = $query3 . "'" . $SesshoSeq . "',";
		$query3 = $query3 . "'" . $_SESSION["S00_InstKubun"] . "',";
		$query3 = $query3 . "'1',";
		$query3 = $query3 . "'" . $_SESSION["S00_EntryDay"] . "',";
		$query3 = $query3 . "'0',";	//終了シーケンス
		$query3 = $query3 . "'" . $_SESSION["LoginTeacherID"] . "',";
		$query3 = $query3 . "NULL,";
		$query3 = $query3 . "'" . $_SESSION["S00_InstHouho"] . "',";
		$query3 = $query3 . "NULL,";
		$query3 = $query3 . "NULL,";
		$query3 = $query3 . "'" . $_SESSION["S00_Sonota_Naiyo"] . "',";
		$query3 = $query3 . "NULL,";
		$query3 = $query3 . "'0')";

		$result3 = $mysqli->query($query3);

		//print($query3);

		if (!$result3) {
			$ErrMSG = "クエリーが失敗しました。（S_KihonInfoエラー）" . $mysqli->error;
			$ErrFlg = 1;
		}
	}

//	if($ErrFlg == 0){
//		for($m=0; $m<$_SESSION["S00_Koza_DataCount"]; $m++){
//			if($ErrFlg == 0){
//print($m . "<BR>");
//				$query3 = "INSERT INTO S_KozaInfo ";
//				$query3 = $query3 . " VALUES (";
//				$query3 = $query3 . "'" . $_SESSION["S00_StudentID"] . "',";
//				$query3 = $query3 . $m . ",";
//				if($_SESSION["S00_Koza_Start" . $m] == ""){
//					$query3 = $query3 . "NULL,";
//				}else{
//					$query3 = $query3 . "'" . $_SESSION["S00_Koza_Start" . $m] . "',";
//				}
//				if($_SESSION["S00_Koza_End" . $m] == ""){
//					$query3 = $query3 . "NULL,";
//				}else{
//					$query3 = $query3 . "'" . $_SESSION["S00_Koza_End" . $m] . "',";
//				}
//				$query3 = $query3 . "'" . $_SESSION["S00_Koza_Kigou" . $m] . "',";
//				$query3 = $query3 . "'" . $_SESSION["S00_Koza_Bango" . $m] . "',";
//				$query3 = $query3 . "'" . $_SESSION["S00_Koza_Meigi" . $m] . "',";
//				$query3 = $query3 . "'" . $_SESSION["S00_Koza_MeigiKana" . $m] . "',";
//				$query3 = $query3 . "'" . $_SESSION["S00_Koza_Biko" . $m] . "')";

//				$result3 = $mysqli->query($query3);

				//print($query3);

//				if (!$result3) {
//					$ErrMSG = "クエリーが失敗しました。（S_KozaInfoエラー）" . $mysqli->error;
//					$ErrFlg = 1;
//				}
//			}
//		}
//	}


	for($m=0; $m<$_SESSION["S00_Koza_DataCount"]; $m++){
		if($ErrFlg == 0){
			$query4 = "Select Count(*) as CNT from S_KozaInfo ";
			$query4 = $query4 . " Where StudentID='" . $_SESSION["S00_Koza_StudentID" .$m] . "'";
			$query4 = $query4 . " And KozaSeq='" . $_SESSION["S00_Koza_KozaSeq" .$m] . "'";
			$result4 = $mysqli->query($query4);
//print($query4 . "<BR>");
			if (!$result4) {
				$ErrMSG = "クエリーが失敗しました。（S_KozaInfoエラー）" . $mysqli->error;
				$ErrFlg = 1;
			}
			while($arr_item = $result4->fetch_assoc()){

				//レコード内の各フィールド名と値を順次参照
				foreach($arr_item as $key => $value){
					//フィールド名と値を表示
					$_SESSION["KozaCNT"] = $value;
				}
			}
			if($ErrFlg == 0){
				if($_SESSION["KozaCNT"] > 0){
					$query4 = "UPDATE S_KozaInfo SET ";
					if($_SESSION["S00_Koza_Start" . $m] == ""){
						$query4 = $query4 . "Start = NULL";
					}else{
						$query4 = $query4 . "Start = '" . $_SESSION["S00_Koza_Start" . $m] . "'";
					}
					if($_SESSION["S00_Koza_End" . $m] == ""){
						$query4 = $query4 . ", End = NULL";
					}else{
						$query4 = $query4 . ", End = '" . $_SESSION["S00_Koza_End" . $m] . "'";
					}
					$query4 = $query4 . ", Kigou = '" . $_SESSION["S00_Koza_Kigou" . $m] . "'";
					$query4 = $query4 . ", Bango = '" . $_SESSION["S00_Koza_Bango" . $m] . "'";
					$query4 = $query4 . ", Meigi = '" . $_SESSION["S00_Koza_Meigi" . $m] . "'";
					$query4 = $query4 . ", MeigiKana = '" . $_SESSION["S00_Koza_MeigiKana" . $m] . "'";
					$query4 = $query4 . ", Biko = '" . $_SESSION["S00_Koza_Biko" . $m] . "'";
					$query4 = $query4 . " Where StudentID='" . $_SESSION["S00_Koza_StudentID" .$m] . "'";
					$query4 = $query4 . " And KozaSeq='" . $_SESSION["S00_Koza_KozaSeq" .$m] . "'";

					$result4 = $mysqli->query($query4);
					//print($query4);

					if (!$result4) {
						$ErrMSG = "クエリーが失敗しました。（S_KozaInfoエラー）" . $mysqli->error;
						$ErrFlg = 1;
					}
				}else{
					$query3 = "INSERT INTO S_KozaInfo ";
					$query3 = $query3 . " VALUES (";
					$query3 = $query3 . "'" . $_SESSION["S00_Koza_StudentID0"] . "',";
					$query3 = $query3 . "'" . $_SESSION["S00_Koza_KozaSeq" . $m] . "',";
					if($_SESSION["S00_Koza_Start" . $m] == ""){
						$query3 = $query3 . "NULL,";
					}else{
						$query3 = $query3 . "'" . $_SESSION["S00_Koza_Start" . $m] . "',";
					}
					if($_SESSION["S00_Koza_End" . $m] == ""){
						$query3 = $query3 . "NULL,";
					}else{
						$query3 = $query3 . "'" . $_SESSION["S00_Koza_End" . $m] . "',";
					}
					$query3 = $query3 . "'" . $_SESSION["S00_Koza_Kigou" . $m] . "',";
					$query3 = $query3 . "'" . $_SESSION["S00_Koza_Bango" . $m] . "',";
					$query3 = $query3 . "'" . $_SESSION["S00_Koza_Meigi" . $m] . "',";
					$query3 = $query3 . "'" . $_SESSION["S00_Koza_MeigiKana" . $m] . "',";
					$query3 = $query3 . "'" . $_SESSION["S00_Koza_Biko" . $m] . "')";

					$result3 = $mysqli->query($query3);

					//print($query3);

					if (!$result3) {
						$ErrMSG = "クエリーが失敗しました。（S_KozaInfoエラー）" . $mysqli->error;
						$ErrFlg = 1;
					}
				}
			}
		}
	}

		
	if($ErrFlg == 0){
		
		// コミット
		$mysqli->query("commit");

		$RtnMSG = "登録しました。";
		$mysqli->close();

		return $RtnMSG;
	}
	if($ErrFlg == 1){
		$mysqli->query("rollback");

		$mysqli->close();

		return $ErrMSG;
	}

}
//-----------------------------------------------------------
//	生徒ＩＤ取得処理
//-----------------------------------------------------------
Function IdGetShori(){
	$_SESSION["W00_StudentID"]="";

	$W_Name = mb_substr($_SESSION["S00_Name2"], 0, 1, "UTF-8" );
	$W_Name = mb_substr(mb_convert_kana(mb_convert_kana($W_Name, 'h'), 'H'), 0, 1);
//print($W_Name . "<BR>");
	$W_Name2 = dakutenBuild($W_Name);
//print($W_Name2 . "<BR>");

	list($W_IdNo1,$W_IdNo2,$W_Def) = GetIdNoHenshu2($W_Name);

	// mysqlへの接続
	$mysqli = new mysqli(HOST, USER, PASS);
	if ($mysqli->connect_errno) {
		print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
		exit();
	}

	// データベースの選択
	$mysqli->select_db(DBNAME);
	$mysqli->set_charset("utf8");


	$query = "SELECT * FROM S_AtenaInfo ";
//	if($W_Name2 == ""){
//		$query = $query . "where Name2 like '" . $W_Name . "%'";
//	}else{
//		$query = $query . "where Name2 like '" . $W_Name . "%' OR Name2 like '" . $W_Name2 . "%'";
//	}
	$query = $query . " where StudentID >= " . $W_IdNo1 . " and StudentID <= " . $W_IdNo2;
	$query = $query . " order by StudentID desc";
	$query = $query . " LIMIT 1";
//print($query);
	$result = $mysqli->query($query);

	if (!$result) {
		print('クエリーが失敗しました。' . $mysqli->error);
		$mysqli->close();
		exit();
	}
	
	while($arr_item = $result->fetch_assoc()){

		//レコード内の各フィールド名と値を順次参照
		foreach($arr_item as $key => $value){
			//フィールド名と値を表示
			$_SESSION["W00_" . $key] = $value;
		}
	}
	$mysqli->close();

//print("W00_TeacherID" . $_SESSION["W00_StudentID"]);
	if($_SESSION["W00_StudentID"] == ""){
		$Get_ID = $W_Def;
	}else{
		$Get_ID = (int)$_SESSION["W00_StudentID"] + 1;
	}

	return $Get_ID;
}
//-----------------------------------------------------------
//	濁点をつける
//-----------------------------------------------------------
function dakutenBuild($str)  
{  
//    $res = array();  

//print($res);

    $dakuten = array('か','き','く','け','こ','さ','し','す','せ','そ','た','ち','つ','て','と','は','ひ','ふ','へ','ほ');  
    $to = array('が','ぎ','ぐ','げ','ご','ざ','じ','ず','ぜ','ぞ','だ','ぢ','づ','で','ど','ば','び','ぶ','べ','ぼ');  
      
    if(preg_match('/'.implode('|',$dakuten).'/',$str)){  
        $res =  str_replace($dakuten, $to, $str);  
    }  
    $handakuten = array('は','ひ','ふ','へ','ほ');  
    $to = array('ぱ','ぴ','ぷ','ぺ','ぽ');  
      
    if(preg_match('/'.implode('|',$handakuten).'/',$str)){  
        $res =  str_replace($handakuten, $to, $str);  
    }  
//print($res);
    return $res;  
} 
//-----------------------------------------------------------
//	取得処理
//-----------------------------------------------------------
Function InstShori(){
//取込区分により処理を分ける
$line = "";
$Background="background-color: #9FF781";
$Background2="#9FF781";
$GenjyoIndex = "";
$GenjyoCnt = 0;
$AddFlg=0;

	$_SESSION["S00_Youbi_Sonota"] = "";
	$_SESSION["S00_Youbi_Sonota_COLER"] = "";
	for($dataidx=0; $dataidx < $_SESSION["24CodeData"]["24DataCount"]; $dataidx++){
		$_SESSION["S00_Youbi" . $YoubiIndex] = 0;
	}
	$_SESSION["S00_Youbi_COLER"] = "";
	$_SESSION["S00_Hogosha1"] = "";
	$_SESSION["S00_Hogosha1_COLER"] = "";
	$_SESSION["S00_HogoshaKana1"] = "";
	$_SESSION["S00_HogoshaKana1_COLER"] = "";
	$_SESSION["S00_Name1"] = ""; 
	$_SESSION["S00_Name1_COLER"] = "";
	$_SESSION["S00_Name2"] = ""; 
	$_SESSION["S00_Name2_COLER"] = "";
	$_SESSION["S00_gread"] = "";
	$_SESSION["S00_gread_COLER"] = "";
	$_SESSION["S00_Seibetu"] = "";
	$_SESSION["S00_Seibetu_COLER"] = "";
	for($dataidx=0; $dataidx < $_SESSION["23CodeData"]["23DataCount"]; $dataidx++){
		$_SESSION["S00_Genjyo" . $GenjyoIndex] = 0;
	}
	$_SESSION["S00_Genjyo99"] = 0;
	$_SESSION["S00_Genjyo_COLER"]="";
	$_SESSION["S00_Genjyo_Sonota"] = "";
	$_SESSION["S00_Genjyo_Sonota_COLER"]="";
	$_SESSION["S00_Yubin1_1"] = "";
	$_SESSION["S00_Yubin1_2"] = "";
	$_SESSION["S00_Yubin1_1_COLER"]="";
	$_SESSION["S00_Yubin1_2_COLER"]="";
	$_SESSION["S00_Yubin2_1_COLER"]="";
	$_SESSION["S00_Yubin2_2_COLER"]="";
	$_SESSION["S00_Yubin1_1_ErrMsg"]="";
	$_SESSION["S00_Yubin1_2_ErrMsg"]="";
	$_SESSION["S00_Yubin2_1_ErrMsg"]="";
	$_SESSION["S00_Yubin2_2_ErrMsg"]="";
	$_SESSION["S00_Add_Ken_Code1"] = "";
	$_SESSION["S00_Add_shi1"] = "";
	$_SESSION["S00_Add_ku1"] = "";
	$_SESSION["S00_Add_cho1"] = "";
	$_SESSION["S00_Add_shi1_COLER"]="";
	$_SESSION["S00_Add_ku1_COLER"]="";
	$_SESSION["S00_Add_cho1_COLER"]="";
	$_SESSION["S00_Kotu_rosen"] = "";
	$_SESSION["S00_Kotu_rosen_COLER"]="";
	$_SESSION["S00_Kotu_Eki"] = "";
	$_SESSION["S00_Kotu_Eki_COLER"]="";
	$_SESSION["S00_Tel1"] = "";
	$_SESSION["S00_Tel_COLER1"]="";
	for($dataidx=0; $dataidx < $_SESSION["15CodeData"]["15DataCount"]; $dataidx++){
		$_SESSION["S00_ContactTime" . $ContactTimeIndex] = 0;
	}
	$_SESSION["S00_ContactTime_COLER"]="";
	$_SESSION["S00_ContactTimeSonota"] = "";
	$_SESSION["S00_ContactTimeSonota_COLER"]="";
	$_SESSION["S00_Mail1"] = "";
	$_SESSION["S00_Mail_COLER1"]="";
	$_SESSION["S00_Sonota_Naiyo"] = "";
	$_SESSION["S00_Sonota_Naiyo_COLER1"]="";
	$_SESSION["S00_TorokuDay_COLER"]="";
	$_SESSION["S00_KyoushiKibouNaiyo"]="";
	$_SESSION["S00_KyoushiKibouNaiyo_COLER"]="";

	$_SESSION["S00_TorokuDay"]=$_SESSION["S00_InstDay"];
	$_SESSION["S00_TorokuDay_COLER"]=$Background;

	$line = explode("\n", $_SESSION["S00_InstData"]); // とりあえず行に分割
	$line = array_map('trim', $line); // 各行にtrim()をかける
	$line = str_replace("＝", "", $line);
	$line = str_replace(",", "、", $line);
	$line = array_filter($line, 'strlen'); // 文字数が0の行を取り除く
	$line = array_values($line); // これはキーを連番に振りなおしてるだけ

	$lineCnt = count($line);
		$Moto_Array = array('【 ご希望コース 】', '【 ご希望の指導回数 】', '【 ご希望指導曜日 】','【 保護者氏名 】', '【 保護者ふりがな 】', '【 生徒氏名 】', '【 生徒ふりがな 】', '【 学年 】', '【 性別 】', '【 現状 】', '【 現状:その他詳細 】', '【 郵便番号 】', '【 都道府県 】', '【 市区町村番地 】', '【 最寄沿線 】', '【 最寄駅 】', '【 電話番号 】', '【 電話連絡可能な時間帯 】', '【 連絡可能な時間帯:その他詳細 】', '【 メールアドレス 】', '【 その他のメールアドレス 】', '【 ご質問・ご要望 】', '【 サイトを知ったきっかけ 】','送信された日時：', '【 希望する先生 】');
		$Moto_ArrayCnt = count($Moto_Array);
		
		for($idx=0; $idx<$lineCnt; $idx++){
			$AddFlg=0;
			//print($idx . "=" . $line[$idx] . "<BR>");
			for($m=0; $m<$Moto_ArrayCnt; $m++){
				if(strpos($line[$idx],$Moto_Array[$m]) !== false){
					$lineindex = $Moto_Array[$m];
					$AddFlg=0;
					break;
				}else{
					$AddFlg=1;
				}
			}
//print($lineindex . "<BR>");
			switch ($lineindex){
				case '【 ご希望コース 】':
					break;
				case '【 ご希望の指導回数 】':
					$S00_Youbi_Sonota = str_replace($lineindex, "", $line[$idx]);
					$_SESSION["S00_Youbi_Sonota"] = trim($S00_Youbi_Sonota);
					if($_SESSION["S00_Youbi_Sonota"] != ""){
						$_SESSION["S00_Youbi_Sonota_COLER"]=$Background;
					}

					break;
				case '【 ご希望指導曜日 】':
					$S00_Youbi = str_replace($lineindex, "", $line[$idx]);
					$S00_Youbi_Array = explode(",", $S00_Youbi);
					$S00_YoubiCnt = count($S00_Youbi_Array);
					for($i=0; $i<$S00_YoubiCnt; $i++){
						$S00_Youbi_Array[$i] = trim($S00_Youbi_Array[$i]);
						for($dataidx=0; $dataidx < $_SESSION["24CodeData"]["24DataCount"]; $dataidx++){
							if($S00_Youbi_Array[$i] == $_SESSION["24CodeData"]["24_CodeName1_" . $dataidx]){
								$YoubiIndex = (int)$_SESSION["24CodeData"]["24_Eda_" . $dataidx];
								$_SESSION["S00_Youbi" . $YoubiIndex] = 1;
								$YoubiIndex++; 
							}
						}
					}
					if($YoubiIndex > 0){
						$_SESSION["S00_Youbi_COLER"]=$Background2;
					}



					break;

				case '【 保護者氏名 】':
					$S00_Hogosha1 = str_replace($lineindex, "", $line[$idx]);
					$S00_Hogosha1 = preg_replace("/( |　)/", "", $S00_Hogosha1);
					$_SESSION["S00_Hogosha1"] = trim($S00_Hogosha1);
					if($_SESSION["S00_Hogosha1"] != ""){
						$_SESSION["S00_Hogosha1_COLER"]=$Background;
					}
					break;

				case '【 保護者ふりがな 】':
					$S00_HogoshaKana1 = str_replace($lineindex, "", $line[$idx]);
					$S00_HogoshaKana1 = mb_convert_kana(trim($S00_HogoshaKana1), 'c');
					$_SESSION["S00_HogoshaKana1"] = preg_replace("/( |　)/", "", $S00_HogoshaKana1);
					if($_SESSION["S00_HogoshaKana1"] != ""){
						$_SESSION["S00_HogoshaKana1_COLER"]=$Background;
					}
					break;

				case '【 生徒氏名 】':
					$S00_Name1 = str_replace($lineindex, "", $line[$idx]);
					$S00_Name1 = preg_replace("/( |　)/", "", $S00_Name1);
					$_SESSION["S00_Name1"] = trim($S00_Name1);
					if($_SESSION["S00_Name1"] != ""){
						$_SESSION["S00_Name1_COLER"]=$Background;
					}
					break;

				case '【 生徒ふりがな 】':
					$S00_Name2 = str_replace($lineindex, "", $line[$idx]);
					$S00_Name2 = mb_convert_kana(trim($S00_Name2), 'c');
					$_SESSION["S00_Name2"] = preg_replace("/( |　)/", "", $S00_Name2);
					if($_SESSION["S00_Name2"] != ""){
						$_SESSION["S00_Name2_COLER"]=$Background;
					}
					break;

				case '【 学年 】':
					$S00_gread = str_replace($lineindex, "", $line[$idx]);
					$S00_gread = trim($S00_gread);
					for($dataidx=0; $dataidx < $_SESSION["13CodeData"]["13DataCount"]; $dataidx++){
						if($S00_gread == $_SESSION["13CodeData"]["13_CodeName2_" . $dataidx]){
							$_SESSION["S00_gread"] = $_SESSION["13CodeData"]["13_Eda_" . $dataidx];
						}
					}
					if($_SESSION["S00_gread"] != ""){
						$_SESSION["S00_gread_COLER"]=$Background2;
					}

					break;

				case '【 性別 】':
					$S00_Seibetu = str_replace($lineindex, "", $line[$idx]);
					$S00_Seibetu = trim($S00_Seibetu);
					if($S00_Seibetu != ""){
						if($S00_Seibetu=="男" || $S00_Seibetu=="男子"){
							$_SESSION["S00_Seibetu"] = 1;
						}else{
							$_SESSION["S00_Seibetu"] = 2;
						}
					}
					if($_SESSION["S00_Seibetu"] != ""){
						$_SESSION["S00_Seibetu_COLER"]=$Background2;
					}
					break;

				case '【 現状 】':
					$S00_Genjyo = str_replace($lineindex, "", $line[$idx]);
					$S00_Genjyo_Array = explode(",", $S00_Genjyo);
					$S00_GenjyoCnt = count($S00_Genjyo_Array);

					for($i=0; $i<$S00_GenjyoCnt; $i++){
						$S00_Genjyo_Array[$i] = trim($S00_Genjyo_Array[$i]);
						for($dataidx=0; $dataidx < $_SESSION["23CodeData"]["23DataCount"]; $dataidx++){
							if($S00_Genjyo_Array[$i] == $_SESSION["23CodeData"]["23_CodeName2_" . $dataidx]){
								$GenjyoIndex = (int)$_SESSION["23CodeData"]["23_Eda_" . $dataidx];
								if($GenjyoIndex==99){
									$_SESSION["S00_Genjyo99"] = 1;
								}else{
									$_SESSION["S00_Genjyo" . $GenjyoIndex] = 1;
								}
								$GenjyoCnt++; 
							}
						}
					}
					if($GenjyoCnt > 0){
						$_SESSION["S00_Genjyo_COLER"]=$Background2;
					}

					break;

				case '【 現状:その他詳細 】':
					$S00_Genjyo_Sonota = str_replace($lineindex, "", $line[$idx]);
					if($AddFlg==1){
						$_SESSION["S00_Genjyo_Sonota"] = $_SESSION["S00_Genjyo_Sonota"] . trim($S00_Genjyo_Sonota);
					}else{
						$_SESSION["S00_Genjyo_Sonota"] = trim($S00_Genjyo_Sonota);
					}
					if($_SESSION["S00_Genjyo_Sonota"] != ""){
						$_SESSION["S00_Genjyo_Sonota_COLER"]=$Background;
					}

					break;

				case '【 郵便番号 】':
					$S00_Yubin = str_replace($lineindex, "", $line[$idx]);
					$S00_Yubin1_1 = substr($S00_Yubin, 0, 4);
					$S00_Yubin1_2 = substr($S00_Yubin, 4, 4);

					$_SESSION["S00_Yubin1_1"] = $S00_Yubin1_1;
					$_SESSION["S00_Yubin1_2"] = $S00_Yubin1_2;

					if($_SESSION["S00_Yubin1_1"] != ""){
						$_SESSION["S00_Yubin1_1_COLER"]=$Background;
					}
					if($_SESSION["S00_Yubin1_2"] != ""){
						$_SESSION["S00_Yubin1_2_COLER"]=$Background;
					}

					break;

				case '【 都道府県 】':
					$S00_Add_Ken_Code1 = str_replace($lineindex, "", $line[$idx]);
					$S00_Add_Ken_Code1 = trim($S00_Add_Ken_Code1);
					list ($Code, $ChiikiCode, $Todofuken) = GetTodofuken($S00_Add_Ken_Code1,"");
					$_SESSION["S00_Add_Ken_Code1"] = $Code;
					break;

				case '【 市区町村番地 】':
					$S00_Add = str_replace($lineindex, "", $line[$idx]);
					$S00_Add = trim($S00_Add);
					$S00_Add_shi1 = str_replace($lineindex, "", $line[$idx]);
					$S00_Add_shi1 = trim($S00_Add_shi1);
					$S00_Add_shi_cnt = strpos($S00_Add_shi1, '市');
					if($S00_Add_shi_cnt > 0){
						$_SESSION["S00_Add_shi1"] = substr($S00_Add_shi1, 0, $S00_Add_shi_cnt+3);
					}else{
						$S00_Add_shi_cnt = strpos($S00_Add_shi1, '町');
						if($S00_Add_shi_cnt > 0){
							$_SESSION["S00_Add_shi1"] = substr($S00_Add_shi1, 0, $S00_Add_shi_cnt+3);
						}else{
							$S00_Add_shi_cnt = strpos($S00_Add_shi1, '村');
							if($S00_Add_shi_cnt > 0){
								$_SESSION["S00_Add_shi1"] = substr($S00_Add_shi1, 0, $S00_Add_shi_cnt+3);
							}
						}
					}

					$S00_Add_ku1 = str_replace($_SESSION["S00_Add_shi1"], "", $S00_Add);
					$S00_Add_ku1 = trim($S00_Add_ku1);
					$S00_Add_ku_cnt = strpos($S00_Add_ku1, '区');
					if($S00_Add_ku_cnt > 0){
						$_SESSION["S00_Add_ku1"] = substr($S00_Add_ku1, 0, $S00_Add_ku_cnt+3);
					}
					$S00_Add_cho1 = str_replace($_SESSION["S00_Add_ku1"], "", $S00_Add_ku1);
					$_SESSION["S00_Add_cho1"] = $S00_Add_cho1;

					if($_SESSION["S00_Add_shi1"] != ""){
						$_SESSION["S00_Add_shi1_COLER"]=$Background;
					}
					if($_SESSION["S00_Add_ku1"] != ""){
						$_SESSION["S00_Add_ku1_COLER"]=$Background;
					}
					if($_SESSION["S00_Add_cho1"] != ""){
						$_SESSION["S00_Add_cho1_COLER"]=$Background;
					}

					break;

				case '【 最寄沿線 】':
					$S00_Kotu_rosen = str_replace($lineindex, "", $line[$idx]);
					$_SESSION["S00_Kotu_rosen"] = trim($S00_Kotu_rosen);
					if($_SESSION["S00_Kotu_rosen"] != ""){
						$_SESSION["S00_Kotu_rosen_COLER"]=$Background;
					}

					break;

				case '【 最寄駅 】':
					$S00_Kotu_Eki = str_replace($lineindex, "", $line[$idx]);
					$_SESSION["S00_Kotu_Eki"] = trim($S00_Kotu_Eki);
					if($_SESSION["S00_Kotu_Eki"] != ""){
						$_SESSION["S00_Kotu_Eki_COLER"]=$Background;
					}

					break;

				case '【 電話番号 】':
					$S00_Tel1 = str_replace($lineindex, "", $line[$idx]);
					$_SESSION["S00_Tel1"] = mb_convert_kana(trim($S00_Tel1), 'a');
					if($_SESSION["S00_Tel1"] != ""){
						$_SESSION["S00_Tel_COLER1"]=$Background;
					}

					break;

				case '【 電話連絡可能な時間帯 】':
					$S00_ContactTime = str_replace($lineindex, "", $line[$idx]);
					$S00_ContactTime_Array = explode(",", $S00_ContactTime);
					$S00_ContactTimeCnt = count($S00_ContactTime_Array);
					for($i=0; $i<$S00_ContactTimeCnt; $i++){
						$S00_ContactTime_Array[$i] = trim($S00_ContactTime_Array[$i]);
						for($dataidx=0; $dataidx < $_SESSION["15CodeData"]["15DataCount"]; $dataidx++){
							if($S00_ContactTime_Array[$i] == $_SESSION["15CodeData"]["15_CodeName2_" . $dataidx]){
								$ContactTimeIndex = (int)$_SESSION["15CodeData"]["15_Eda_" . $dataidx];
								if($ContactTimeIndex==99){
									$_SESSION["S00_ContactTime3"] = 1;
								}else{
									$ContactTimeIndex = $ContactTimeIndex-1;
									$_SESSION["S00_ContactTime" . $ContactTimeIndex] = 1;
								}
								$ContactTimeCnt++; 
							}
						}
					}
					if($ContactTimeCnt > 0){
						$_SESSION["S00_ContactTime_COLER"]=$Background2;
					}

					break;

				case '【 連絡可能な時間帯:その他詳細 】':
					$S00_ContactTimeSonota = str_replace($lineindex, "", $line[$idx]);
					if($AddFlg==1){
						$_SESSION["S00_ContactTimeSonota"] = $_SESSION["S00_ContactTimeSonota"] . trim($S00_ContactTimeSonota);
					}else{
						$_SESSION["S00_ContactTimeSonota"] = trim($S00_ContactTimeSonota);
					}
					if($_SESSION["S00_ContactTimeSonota"] != ""){
						$_SESSION["S00_ContactTimeSonota_COLER"]=$Background;
					}

					break;

				case '【 メールアドレス 】':
					$S00_Mail1 = str_replace($lineindex, "", $line[$idx]);
					$_SESSION["S00_Mail1"] = trim($S00_Mail1);
					if($_SESSION["S00_Mail1"] != ""){
						$_SESSION["S00_Mail_COLER1"]=$Background;
					}

					break;

				case '【 その他のメールアドレス 】':
					$S00_Mail2 = str_replace($lineindex, "", $line[$idx]);
					$_SESSION["S00_Mail2"] = trim($S00_Mail2);
					if($_SESSION["S00_Mail2"] != ""){
						$_SESSION["S00_Mail_COLER2"]=$Background;
					}

					break;

				case '【 ご質問・ご要望 】':
					$S00_Sonota_Naiyo = str_replace($lineindex, "", $line[$idx]);

					if($AddFlg==1){
						$_SESSION["S00_Sonota_Naiyo"] = $_SESSION["S00_Sonota_Naiyo"] . "&#13;" . trim($S00_Sonota_Naiyo);
					}else{
						$_SESSION["S00_Sonota_Naiyo"] = trim($S00_Sonota_Naiyo);
					}
					if($_SESSION["S00_Sonota_Naiyo"] != ""){
						$_SESSION["S00_Sonota_Naiyo_COLER"]=$Background;
					}

					break;
				case '【 サイトを知ったきっかけ 】':

					$S00_notice3 = str_replace($lineindex, "", $line[$idx]);

					if($AddFlg==1){
						$_SESSION["S00_notice3"] = $_SESSION["S00_notice3"] . trim($S00_notice3);
					}else{
						$_SESSION["S00_notice3"] = trim($S00_notice3);
					}
					if($_SESSION["S00_notice3"] != ""){
						$_SESSION["S00_notice3_COLER"]=$Background;
					}
					break;
				case '送信された日時：':
					$S00_InstDay = str_replace($lineindex, "", $line[$idx]);
					$S00_InstDay = substr($S00_InstDay, 0, 10);
					$S00_InstDay = str_replace("/", "-", $S00_InstDay);
					if(trim($S00_InstDay) != ""){
						$_SESSION["S00_InstDay"] = trim($S00_InstDay);
						$_SESSION["S00_EntryDay"] = trim($S00_InstDay);
						$_SESSION["S00_TorokuDay"] = trim($S00_InstDay);
					}
					if($_SESSION["S00_InstDay"] != ""){
						$_SESSION["S00_InstDay_COLER1"]=$Background;
						$Naiyo = $_SESSION["S00_Sonota_Naiyo"];
						$_SESSION["S00_Sonota_Naiyo"] = $line[$idx] . "&#13;" . $Naiyo;
					}
					break;
				case '【 希望する先生 】':

					$S00_KyoushiKibouNaiyo = str_replace($lineindex, "", $line[$idx]);

					if($AddFlg==1){
						$_SESSION["S00_KyoushiKibouNaiyo"] = $_SESSION["S00_KyoushiKibouNaiyo"] . trim($S00_KyoushiKibouNaiyo);
					}else{
						$_SESSION["S00_KyoushiKibouNaiyo"] = trim($S00_KyoushiKibouNaiyo);
					}
					if($_SESSION["S00_KyoushiKibouNaiyo"] != ""){
						$_SESSION["S00_KyoushiKibouNaiyo_COLER"]=$Background;
					}
					break;

			}
		}
	if($_SESSION["S00_InstKubun"]=="01"){
		
	}else if($_SESSION["S00_InstKubun"]=="02"){
	}
	$_SESSION["ErrMsg"]="取込内容を確認してください。<BR>生徒ＩＤを取得して登録してください。";
}
?>
<script type="text/javascript" src="utility.js"></script>

<CENTER>
<body>

<form name="form1" method="post" action="S00_Atena01.php">
	<table border="0" width="100%">
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">生　徒　情　報</td>
		</tr>
	</table>
	<?php if($_SESSION["S00_Atena_MODE"]=="NEW"){?>
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

	<?php }else{ ?>
		<table border="0" width="100%">
			<tr align="Right">
				<td align="right">
					<input type="hidden" id="submitter" name="submitter" value="" />
					<input type="button" id="modorushori" name="modorushori" onClick="sbmfnc(this,<?php echo $_SESSION["TourokuFlg"] ?>)" style="cursor: pointer" value="戻る" />
				</td>
			</tr>
		</table>
	<?php } ?>
	<table border="0" width="100%">
		<font size="5" color="#ff0000"><?php echo $_SESSION["ErrMsg"] ?></font>
	</table>
	<table border="0" width="100%">
		<tr align="center">
			<?php if($_SESSION["S00_Atena_MODE"]=="NEW"){?>
					<input type="button" id="newdate" name="newdate" onClick="sbmfnc(this,<?php echo $_SESSION["TourokuFlg"] ?>)" style="cursor: pointer" value="登録" />
			<?php }else{ ?>
					<input type="button" id="update" name="update" onClick="sbmfnc(this,<?php echo $_SESSION["TourokuFlg"] ?>)" style="cursor: pointer" value="更新" <?php if($_SESSION["S00_ID_UPD"]==1){?>disabled<?php }?>/>
			<?php } ?>
		</tr>
	</table>
	<table border="0">
		<tr>
			<?php if($_SESSION["S00_Atena_MODE"]=="NEW"){?>
				<td>
					<font size="5" color="#ff0000"><?php echo $_SESSION["ErrMsg2"] ?></font>
					<div id="tbl-bdr">
					<table>
						<tr>
							<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">
								取込日
							</td>
							<td align="center" width="120">
								<input class="inputtype" type="text" size="15" maxlength="10" name="S00_InstDay" style="ime-mode: disabled;<?php echo $_SESSION["S00_InstDay_COLER"] ?>" value="<?php echo $_SESSION["S00_InstDay"] ?>">
								<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_InstDay_ErrMsg"] ?></font>
							</td>
							<td id="midashi_Kanri" align="right" bgcolor="#c0c0c0" colspan="2">
								<input type="button" id="inst" name="inst" onClick="sbmfnc(this,<?php echo $_SESSION["TourokuFlg"] ?>)" style="cursor: pointer" value="取込⇒" />
							</td>
						</tr>
						<tr>
							<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">
								取込区分
							</td>
							<td align="left">
								<select name="S00_InstKubun" class="selecttype2">
									<option value="" <?php if($_SESSION["S00_InstKubun"] == ""){ ?> SELECTED <?php } ?>></option>
									<?php for($dataidx=0; $dataidx < $_SESSION["22CodeData"]["22DataCount"]; $dataidx++){ ?>
										<option value="<?php echo $_SESSION["22CodeData"]["22_Eda_" . $dataidx] ?>" <?php if($_SESSION["22CodeData"]["22_Eda_" . $dataidx] == $_SESSION["S00_InstKubun"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["22CodeData"]["22_CodeName2_" . $dataidx] ?></option>
									<?php } ?>
								</select>
								<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_InstKubun_ErrMsg"] ?></font>
							</td>
							<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">
								取込方法
							</td>
							<td align="left">
								<select name="S00_InstHouho" class="selecttype2">
									<option value="" <?php if($_SESSION["S00_InstHouho"] == ""){ ?> SELECTED <?php } ?>></option>
									<?php for($dataidx=0; $dataidx < $_SESSION["09CodeData"]["09DataCount"]; $dataidx++){ ?>
										<option value="<?php echo $_SESSION["09CodeData"]["09_Eda_" . $dataidx] ?>" <?php if($_SESSION["09CodeData"]["09_Eda_" . $dataidx] == $_SESSION["S00_InstHouho"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["09CodeData"]["09_CodeName2_" . $dataidx] ?></option>
									<?php } ?>
								</select>
								<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_InstHouho_ErrMsg"] ?></font>
							</td>
						</tr>
						<tr>
							<td colspan="4">
								<textarea name="S00_InstData" rows="135" cols="80"><?php echo $_SESSION["S00_InstData"] ?></textarea>
							</td>
						</tr>
					</table>
					</div>
				</td>
			<?php } ?>
			<td>
				<div id="tbl-bdr">
				<table>
					<tr>
						<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">生徒ID</td>
						<td align="center" width="150">
								<input class="inputtype" type="text" size="10" maxlength="10" name="S00_StudentID" style="ime-mode: disabled;<?php echo $_SESSION["S00_StudentID_COLER"] ?>" value="<?php echo $_SESSION["S00_StudentID"] ?>"<?php if($_SESSION["shikaku1"]==0){ ?>ReadOnly<?php } ?>>
								<input class="inputtype" type="text" size="5" maxlength="2" name="S00_Seq" style="ime-mode: disabled;<?php echo $_SESSION["S00_Seq_COLER"] ?>" value="<?php echo $_SESSION["S00_Seq"] ?>"<?php if($_SESSION["shikaku1"]==0){ ?>ReadOnly<?php } ?>>
								<?php if($_SESSION["S00_Atena_MODE"]=="NEW"){?>
									<input type="button" id="getdata" name="getdata" onClick="sbmfnc(this,<?php echo $_SESSION["TourokuFlg"] ?>)" style="cursor: pointer" value="取得" />
								<?php }?>
								<?php if($_SESSION["S00_ID_UPD"]==1){?>
									<input type="button" id="idupd" name="idupd" onClick="sbmfnc(this,<?php echo $_SESSION["TourokuFlg"] ?>)" style="cursor: pointer" value="ID更新" />
								<?php }?>
								<BR><font size="2" color="#ff0000"><?php echo $_SESSION["S00_StudentID_ErrMsg"] ?></font>
						</td>
						<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">登録日</td>
						<td align="center" width="120">
							<input class="inputtype" type="text" size="15" maxlength="10" name="S00_EntryDay" style="ime-mode: disabled;<?php echo $_SESSION["S00_EntryDay_COLER"] ?>" value="<?php echo $_SESSION["S00_EntryDay"] ?>">
							<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_EntryDay_ErrMsg"] ?></font>
						</td>
						<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">生年月日</td>
						<td align="left">
							<input class="inputtype" type="text" size="15" maxlength="10" name="S00_BirthDay" style="ime-mode: disabled;<?php echo $_SESSION["S00_BirthDay_COLER"] ?>" value="<?php echo $_SESSION["S00_BirthDay"] ?>">
							<?php if($_SESSION['S00_BirthDay'] != ""){ ?>
								<?php $BirthDay = floor ((date('Ymd') - date('Ymd', strtotime($_SESSION['S00_BirthDay'])))/10000) ?>
								<?php echo $BirthDay ?>
							<?php } ?>歳
							<input class="inputtype" type="text" size="10" maxlength="10" name="S00_old" style="ime-mode: disabled;<?php echo $_SESSION["S00_old_COLER"] ?>" value="<?php echo $_SESSION["S00_old"] ?>">
							<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_BirthDay_ErrMsg"] ?></font>
						</td>
						<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">性別</td>
						<td align="left" bgcolor="<?php echo $_SESSION["S00_Seibetu_COLER"]?>">
							<input type="radio" name="S00_Seibetu" value="1" <?php if($_SESSION["S00_Seibetu"]==1){?> checked <?php } ?>>男
							<input type="radio" name="S00_Seibetu" value="2" <?php if($_SESSION["S00_Seibetu"]==2){?> checked <?php } ?>>女
						</td>
					</tr>
					<tr>
						<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">生徒名</td>
						<td align="left" colspan="3">
							<input class="inputtype" type="text" size="40" maxlength="30" name="S00_Name1" style="ime-mode: disabled;<?php echo $_SESSION["S00_Name1_COLER"] ?>" value="<?php echo $_SESSION["S00_Name1"] ?>">
							<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Name1_ErrMsg"] ?></font>
						</td>
						<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">生徒かな</td>
						<td align="left" colspan="3">
							<input class="inputtype" type="text" size="40" maxlength="30" name="S00_Name2" style="ime-mode: disabled;<?php echo $_SESSION["S00_Name2_COLER"] ?>" value="<?php echo $_SESSION["S00_Name2"] ?>">
							<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Name2_ErrMsg"] ?></font>
						</td>
					</tr>
					<tr>
						<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">学校名</td>
						<td align="left" colspan="5">
							<input class="inputtype" type="text" size="40" maxlength="30" name="S00_SchoolName" style="ime-mode: disabled;<?php echo $_SESSION["S00_SchoolName_COLER"] ?>" value="<?php echo $_SESSION["S00_SchoolName"] ?>">
							<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_SchoolName_ErrMsg"] ?></font>
						</td>
						<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">学年</td>
						<td align="left" bgcolor="<?php echo $_SESSION["S00_gread_COLER"]?>">
							<select name="S00_gread" class="selecttype2">
								<option value="" <?php if($_SESSION["S00_gread"] == ""){ ?> SELECTED <?php } ?>></option>
								<?php for($dataidx=0; $dataidx < $_SESSION["13CodeData"]["13DataCount"]; $dataidx++){ ?>
									<option value="<?php echo $_SESSION["13CodeData"]["13_Eda_" . $dataidx] ?>" <?php if($_SESSION["13CodeData"]["13_Eda_" . $dataidx] == $_SESSION["S00_gread"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["13CodeData"]["13_CodeName2_" . $dataidx] ?></option>
								<?php } ?>
							</select>
							<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_gread_ErrMsg"] ?></font>
						</td>
					</tr>
					<tr>
						<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">保護者名①</td>
						<td align="left" colspan="5">
							<input class="inputtype" type="text" size="40" maxlength="30" name="S00_Hogosha1" style="ime-mode: disabled;<?php echo $_SESSION["S00_Hogosha1_COLER"] ?>" value="<?php echo $_SESSION["S00_Hogosha1"] ?>">
							<input class="inputtype" type="text" size="40" maxlength="30" name="S00_HogoshaKana1" style="ime-mode: disabled;<?php echo $_SESSION["S00_HogoshaKana1_COLER"] ?>" value="<?php echo $_SESSION["S00_HogoshaKana1"] ?>">
							<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Hogosha1_ErrMsg"] ?></font><font size="1" color="#ff0000"><?php echo $_SESSION["S00_HogoshaKana1_ErrMsg"] ?></font>
						</td>
						<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">続柄</td>
						<td align="left">
							<select name="S00_Hogo_Zoku1" class="selecttype2">
								<option value="" <?php if($_SESSION["S00_Hogo_Zoku1"] == ""){ ?> SELECTED <?php } ?>></option>
								<?php for($dataidx=0; $dataidx < $_SESSION["12CodeData"]["12DataCount"]; $dataidx++){ ?>
									<option value="<?php echo $_SESSION["12CodeData"]["12_Eda_" . $dataidx] ?>" <?php if($_SESSION["12CodeData"]["12_Eda_" . $dataidx] == $_SESSION["S00_Hogo_Zoku1"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["12CodeData"]["12_CodeName2_" . $dataidx] ?></option>
								<?php } ?>
							</select>
							<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Hogo_Zoku1_ErrMsg"] ?></font>
						</td>
					</tr>
					<tr>
						<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">保護者名②</td>
						<td align="left" colspan="5">
							<input class="inputtype" type="text" size="40" maxlength="30" name="S00_Hogosha2" style="ime-mode: disabled;<?php echo $_SESSION["S00_Hogosha2_COLER"] ?>" value="<?php echo $_SESSION["S00_Hogosha2"] ?>">
							<input class="inputtype" type="text" size="40" maxlength="30" name="S00_HogoshaKana2" style="ime-mode: disabled;<?php echo $_SESSION["S00_HogoshaKana2_COLER"] ?>" value="<?php echo $_SESSION["S00_HogoshaKana2"] ?>">
							<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Hogosha2_ErrMsg"] ?></font><font size="1" color="#ff0000"><?php echo $_SESSION["S00_HogoshaKana2_ErrMsg"] ?></font>
						</td>
						<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">続柄</td>
						<td align="left">
							<select name="S00_Hogo_Zoku2" class="selecttype2">
								<option value="" <?php if($_SESSION["S00_Hogo_Zoku2"] == ""){ ?> SELECTED <?php } ?>></option>
								<?php for($dataidx=0; $dataidx < $_SESSION["12CodeData"]["12DataCount"]; $dataidx++){ ?>
									<option value="<?php echo $_SESSION["12CodeData"]["12_Eda_" . $dataidx] ?>" <?php if($_SESSION["12CodeData"]["12_Eda_" . $dataidx] == $_SESSION["S00_Hogo_Zoku2"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["12CodeData"]["12_CodeName2_" . $dataidx] ?></option>
								<?php } ?>
							</select>
							<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Hogo_Zoku2_ErrMsg"] ?></font>
						</td>
					</tr>

					<tr>
						<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">住所</td>
						<td align="left" colspan="7">
							【郵便番号】
							<input class="inputtype" type="text" size="10" maxlength="3" name="S00_Yubin1_1" style="ime-mode: disabled;<?php echo $_SESSION["S00_Yubin1_1_COLER"] ?>" value="<?php echo $_SESSION["S00_Yubin1_1"] ?>" onkeyup="checkText(this)">-
							<input class="inputtype" type="text" size="10" maxlength="4" name="S00_Yubin1_2" style="ime-mode: disabled;<?php echo $_SESSION["S00_Yubin1_2_COLER"] ?>" value="<?php echo $_SESSION["S00_Yubin1_2"] ?>" onkeyup="checkText(this)"><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Yubin1_2_ErrMsg"] ?></font><BR>
							【都道府県】
							<select name="S00_Add_Ken_Code1" class="selecttype">
								<option value="" <?php if($_SESSION["S00_Add_Ken_Code1"] == ""){ ?> SELECTED <?php } ?>>　</option>
								<?php for($dataidx=0; $dataidx < $_SESSION["K_ToDofuken_DataCount"]; $dataidx++){ ?>
									<option value="<?php echo $_SESSION["K_ToDofuken_Code_" .$dataidx]?>" <?php if($_SESSION["K_ToDofuken_Code_" .$dataidx] == $_SESSION["S00_Add_Ken_Code1"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["K_ToDofuken_Todofuken_" .$dataidx]?></option>
								<?php } ?>
							</select>
							【市町村】　<input class="inputtype" type="text" size="20" maxlength="20" name="S00_Add_shi1" style="ime-mode: disabled;<?php echo $_SESSION["S00_Add_shi1_COLER"] ?>" value="<?php echo $_SESSION["S00_Add_shi1"] ?>">
							【　区　】　<input class="inputtype" type="text" size="20" maxlength="20" name="S00_Add_ku1" style="ime-mode: disabled;<?php echo $_SESSION["S00_Add_ku1_COLER"] ?>" value="<?php echo $_SESSION["S00_Add_ku1"] ?>">
							<BR><input class="inputtype" type="text" size="80" maxlength="50" name="S00_Add_cho1" style="ime-mode: disabled;<?php echo $_SESSION["S00_Add_cho1_COLER"] ?>" value="<?php echo $_SESSION["S00_Add_cho1"] ?>">
						</td>
					</tr>
					<tr>
						<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">その他</td>
						<td align="left" colspan="7">
							【郵便番号】
							<input class="inputtype" type="text" size="10" maxlength="3" name="S00_Yubin2_1" style="ime-mode: disabled;<?php echo $_SESSION["S00_Yubin2_1_COLER"] ?>" value="<?php echo $_SESSION["S00_Yubin2_1"] ?>" onkeyup="checkText(this)">-
							<input class="inputtype" type="text" size="10" maxlength="4" name="S00_Yubin2_2" style="ime-mode: disabled;<?php echo $_SESSION["S00_Yubin2_2_COLER"] ?>" value="<?php echo $_SESSION["S00_Yubin2_2"] ?>" onkeyup="checkText(this)"><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Yubin2_2_ErrMsg"] ?></font><BR>
							【都道府県】
							<select name="S00_Add_Ken_Code2" class="selecttype">
								<option value="" <?php if($_SESSION["S00_Add_Ken_Code2"] == ""){ ?> SELECTED <?php } ?>>　</option>
								<?php for($dataidx=0; $dataidx < $_SESSION["K_ToDofuken_DataCount"]; $dataidx++){ ?>
									<option value="<?php echo $_SESSION["K_ToDofuken_Code_" .$dataidx]?>" <?php if($_SESSION["K_ToDofuken_Code_" .$dataidx] == $_SESSION["S00_Add_Ken_Code2"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["K_ToDofuken_Todofuken_" .$dataidx]?></option>
								<?php } ?>
							</select>
							【市町村】　<input class="inputtype" type="text" size="20" maxlength="20" name="S00_Add_shi2" style="ime-mode: disabled;" value="<?php echo $_SESSION["S00_Add_shi2"] ?>">
							【　区　】　<input class="inputtype" type="text" size="20" maxlength="20" name="S00_Add_ku2" style="ime-mode: disabled;" value="<?php echo $_SESSION["S00_Add_ku2"] ?>">
							<BR><input class="inputtype" type="text" size="80" maxlength="50" name="S00_Add_cho2" style="ime-mode: disabled;" value="<?php echo $_SESSION["S00_Add_cho2"] ?>">
						</td>

					</tr>
					<tr>
						<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">兄弟①</td>
						<td align="left" colspan="7">
							<input class="inputtype" type="text" size="40" maxlength="30" name="S00_Kyodai1" style="ime-mode: disabled;<?php echo $_SESSION["S00_Kyodai1_COLER"] ?>" value="<?php echo $_SESSION["S00_Kyodai1"] ?>">
							　学年
							<select name="S00_Kyo_gread1" class="selecttype2">
								<option value="" <?php if($_SESSION["S00_Kyo_gread1"] == ""){ ?> SELECTED <?php } ?>></option>
								<?php for($dataidx=0; $dataidx < $_SESSION["13CodeData"]["13DataCount"]; $dataidx++){ ?>
									<option value="<?php echo $_SESSION["13CodeData"]["13_Eda_" . $dataidx] ?>" <?php if($_SESSION["13CodeData"]["13_Eda_" . $dataidx] == $_SESSION["S00_Kyo_gread1"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["13CodeData"]["13_CodeName2_" . $dataidx] ?></option>
								<?php } ?>
							</select>
							　年齢<input class="inputtype" type="text" size="10" maxlength="10" name="S00_Kyo_old1" style="ime-mode: disabled;<?php echo $_SESSION["S00_Kyo_old1_COLER"] ?>" value="<?php echo $_SESSION["S00_Kyo_old1"] ?>">
							　続柄
							<select name="S00_Kyo_Zoku1" class="selecttype2">
								<option value="" <?php if($_SESSION["S00_Kyo_Zoku1"] == ""){ ?> SELECTED <?php } ?>></option>
								<?php for($dataidx=0; $dataidx < $_SESSION["12CodeData"]["12DataCount"]; $dataidx++){ ?>
									<option value="<?php echo $_SESSION["12CodeData"]["12_Eda_" . $dataidx] ?>" <?php if($_SESSION["12CodeData"]["12_Eda_" . $dataidx] == $_SESSION["S00_Kyo_Zoku1"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["12CodeData"]["12_CodeName2_" . $dataidx] ?></option>
								<?php } ?>
							</select>
							<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Kyodai1_ErrMsg"] ?></font>
						</td>
					</tr>
					<tr>
						<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">兄弟②</td>
						<td align="left" colspan="7">
							<input class="inputtype" type="text" size="40" maxlength="30" name="S00_Kyodai2" style="ime-mode: disabled;<?php echo $_SESSION["S00_Kyodai2_COLER"] ?>" value="<?php echo $_SESSION["S00_Kyodai2"] ?>">
							　学年
							<select name="S00_Kyo_gread2" class="selecttype2">
								<option value="" <?php if($_SESSION["S00_Kyo_gread2"] == ""){ ?> SELECTED <?php } ?>></option>
								<?php for($dataidx=0; $dataidx < $_SESSION["13CodeData"]["13DataCount"]; $dataidx++){ ?>
									<option value="<?php echo $_SESSION["13CodeData"]["13_Eda_" . $dataidx] ?>" <?php if($_SESSION["13CodeData"]["13_Eda_" . $dataidx] == $_SESSION["S00_Kyo_gread2"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["13CodeData"]["13_CodeName2_" . $dataidx] ?></option>
								<?php } ?>
							</select>
							　年齢<input class="inputtype" type="text" size="10" maxlength="10" name="S00_Kyo_old2" style="ime-mode: disabled;<?php echo $_SESSION["S00_Kyo_old2_COLER"] ?>" value="<?php echo $_SESSION["S00_Kyo_old2"] ?>">
							　続柄
							<select name="S00_Kyo_Zoku2" class="selecttype2">
								<option value="" <?php if($_SESSION["S00_Kyo_Zoku2"] == ""){ ?> SELECTED <?php } ?>></option>
								<?php for($dataidx=0; $dataidx < $_SESSION["12CodeData"]["12DataCount"]; $dataidx++){ ?>
									<option value="<?php echo $_SESSION["12CodeData"]["12_Eda_" . $dataidx] ?>" <?php if($_SESSION["12CodeData"]["12_Eda_" . $dataidx] == $_SESSION["S00_Kyo_Zoku2"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["12CodeData"]["12_CodeName2_" . $dataidx] ?></option>
								<?php } ?>
							</select>
							<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Kyodai2_ErrMsg"] ?></font>
						</td>
					</tr>

					<tr>
						<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="2">交通</td>
						<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">沿線</td>
						<td align="left" colspan="2">
							<input class="inputtype" type="text" size="40" maxlength="30" name="S00_Kotu_rosen" style="ime-mode: disabled;<?php echo $_SESSION["S00_Kotu_rosen_COLER"] ?>" value="<?php echo $_SESSION["S00_Kotu_rosen"] ?>">
							<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Kotu_rosen_ErrMsg"] ?></font>
						</td>
						<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">駅・バス</td>
						<td align="left" colspan="3">
							<input class="inputtype" type="text" size="30" maxlength="100" name="S00_Kotu_Eki" style="ime-mode: disabled;<?php echo $_SESSION["S00_Kotu_Eki_COLER"] ?>" value="<?php echo $_SESSION["S00_Kotu_Eki"] ?>">
							<input class="inputtype" type="text" size="30" maxlength="100" name="S00_Kotu_Toho" style="ime-mode: disabled;<?php echo $_SESSION["S00_Kotu_Toho_COLER"] ?>" value="<?php echo $_SESSION["S00_Kotu_Toho"] ?>">分
							<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Kotu_Eki_ErrMsg"] ?></font>
							<font size="1" color="#ff0000"><?php echo $_SESSION["S00_Kotu_Toho_ErrMsg"] ?></font>
						</td>
					</tr>
					<tr>
						<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">車使用</td>
						<td align="left" colspan="2">
							<input type="radio" name="S00_CarTF" value="1" <?php if($_SESSION["S00_CarTF"]==1){?> checked <?php } ?>>可
							<input type="radio" name="S00_CarTF" value="2" <?php if($_SESSION["S00_CarTF"]==2){?> checked <?php } ?>>不可
						</td>
						<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">その他</td>
						<td align="left" colspan="3">
							<input class="inputtype" type="text" size="40" maxlength="100" name="S00_Kotu_Sonota" style="ime-mode: disabled;<?php echo $_SESSION["S00_Kotu_Sonota_COLER"] ?>" value="<?php echo $_SESSION["S00_Kotu_Sonota"] ?>">
							<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Kotu_Sonota_ErrMsg"] ?></font>
						</td>
					</tr>
					<tr>
						<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" colspan="4">電話</td>
						<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" colspan="4">メール</td>
					</tr>
					<?php for($idx=1; $idx <= 3; $idx++){ ?>
						<tr>
							<td align="left">
								<select name="S00_Tel_Kubun<?php echo $idx?>" class="selecttype2">
									<option value="" <?php if($_SESSION["S00_Tel_Kubun" .$idx] == ""){ ?> SELECTED <?php } ?>></option>
									<?php for($dataidx=0; $dataidx < $_SESSION["14CodeData"]["14DataCount"]; $dataidx++){ ?>
										<option value="<?php echo $_SESSION["14CodeData"]["14_Eda_" . $dataidx] ?>" <?php if($_SESSION["14CodeData"]["14_Eda_" . $dataidx] == $_SESSION["S00_Tel_Kubun" .$idx]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["14CodeData"]["14_CodeName2_" . $dataidx] ?></option>
									<?php } ?>
								</select>
							</td>
							<td align="left" colspan="3">
								<input class="inputtype" type="text" size="70" maxlength="50" name="S00_Tel<?php echo $idx?>" style="ime-mode: disabled;<?php echo $_SESSION["S00_Tel_COLER" . $idx] ?>" value="<?php echo $_SESSION["S00_Tel" .$idx] ?>">
								<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Tel_ErrMsg" .$idx] ?></font>
							</td>
							<td align="left">
								<select name="S00_Mail_Kubun<?php echo $idx?>" class="selecttype2">
									<option value="" <?php if($_SESSION["S00_Mail_Kubun" .$idx] == ""){ ?> SELECTED <?php } ?>></option>
									<?php for($dataidx=0; $dataidx < $_SESSION["14CodeData"]["14DataCount"]; $dataidx++){ ?>
										<option value="<?php echo $_SESSION["14CodeData"]["14_Eda_" . $dataidx] ?>" <?php if($_SESSION["14CodeData"]["14_Eda_" . $dataidx] == $_SESSION["S00_Mail_Kubun" .$idx]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["14CodeData"]["14_CodeName2_" . $dataidx] ?></option>
									<?php } ?>
								</select>
							</td>
							<td align="left" colspan="3">
								<input class="inputtype" type="text" size="70" maxlength="50" name="S00_Mail<?php echo $idx?>" style="ime-mode: disabled;<?php echo $_SESSION["S00_Mail_COLER" . $idx] ?>" value="<?php echo $_SESSION["S00_Mail" .$idx] ?>">
								<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Mail_ErrMsg" .$idx] ?></font>
							</td>

						</tr>
					<?php } ?>
					<tr>
						<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" colspan="2">連絡可能時間</td>
						<td align="left" colspan="6" bgcolor="<?php echo $_SESSION["S00_ContactTime_COLER"]?>">
							<?php for($dataidx=0; $dataidx < $_SESSION["15CodeData"]["15DataCount"]; $dataidx++){ ?>
								<input type="checkbox" name="S00_ContactTime<?php echo $dataidx?>" value="<?php echo $_SESSION["S00_ContactTime" .$dataidx]?>" <?php if($_SESSION["S00_ContactTime" .$dataidx] == 1){?> CHECKED <?php } ?>><?php echo $_SESSION["15CodeData"]["15_CodeName1_" . $dataidx] ?>　
							<?php } ?>
							<input class="inputtype" type="text" size="100" maxlength="100" name="S00_ContactTimeSonota" style="ime-mode: disabled;<?php echo $_SESSION["S00_ContactTimeSonota_COLER"] ?>" value="<?php echo $_SESSION["S00_ContactTimeSonota"] ?>">
						</td>
					</tr>

					<?php if($_SESSION["KozaFlg"] == "0"){ ?>
						<?php $Row = $_SESSION["S00_Koza_DataCount"]*3;?>
						<?php 
							if($_SESSION["S00_Koza_End0"] != ""){
								$DISABLED = "disabled";
							}else{
								$DISABLED = "";
							}
						?>
						<tr>
							<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="<?php echo $Row?>">
								<input type="button" id="Koza" name="Koza" onClick="sbmfnc(this,'')" style="cursor: pointer" value="-口座情報" /><BR>
								<input type="button" id="KozaPlus" name="KozaPlus" onClick="sbmfnc(this,'')" style="cursor: pointer" value="口座追加" />
							</td>
							<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">【<?php echo $_SESSION["S00_Koza_KozaSeq0"] ?>】記号番号</td>
							<td align="left" colspan="4">
								<input class="inputtype" type="text" size="10" maxlength="5" name="S00_Koza_Kigou0" style="ime-mode: disabled;<?php echo $_SESSION["S00_Koza_Kigou0_COLER"] ?>" value="<?php echo $_SESSION["S00_Koza_Kigou0"] ?>" onkeyup="checkText(this)">-
								<input class="inputtype" type="text" size="10" maxlength="8" name="S00_Koza_Bango0" style="ime-mode: disabled;<?php echo $_SESSION["S00_Koza_Bango0_COLER"] ?>" value="<?php echo $_SESSION["S00_Koza_Bango0"] ?>" onkeyup="checkText(this)">
								<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Koza_Kigou0_ErrMsg"] ?></font>
								<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Koza_Bango0_ErrMsg"] ?></font>
							</td>
							<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">開始日</td>
							<td align="left">
								<input class="inputtype" type="text" size="15" maxlength="10" name="S00_Koza_Start0" style="ime-mode: disabled;<?php echo $_SESSION["S00_Koza_Start0_COLER"] ?>" value="<?php echo $_SESSION["S00_Koza_Start0"] ?>">
								<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Koza_Start0_ErrMsg"] ?></font>
							</td>
						</tr>
						<tr>
							<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">名義人</td>
							<td align="left" colspan="4">
								<input class="inputtype" type="text" size="30" maxlength="30" name="S00_Koza_Meigi0" style="ime-mode: disabled;<?php echo $_SESSION["S00_Koza_Meigi0_COLER"] ?>" value="<?php echo $_SESSION["S00_Koza_Meigi0"] ?>">
								カナ<input class="inputtype" type="text" size="30" maxlength="30" name="S00_Koza_MeigiKana0" style="ime-mode: disabled;<?php echo $_SESSION["S00_Koza_MeigiKana0_COLER"] ?>" value="<?php echo $_SESSION["S00_Koza_MeigiKana0"] ?>">
								<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Koza_Meigi0_ErrMsg"] ?></font>
								<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Koza_MeigiKana0_ErrMsg"] ?></font>
							</td>
							<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">終了日</td>
							<td align="left">
								<input class="inputtype" type="text" size="15" maxlength="10" name="S00_Koza_End0" style="ime-mode: disabled;<?php echo $_SESSION["S00_Koza_End0_COLER"] ?>" value="<?php echo $_SESSION["S00_Koza_End0"] ?>">
								<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Koza_End0_ErrMsg"] ?></font>
							</td>
						</tr>
						<tr>
							<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">備考</td>
							<td align="left" colspan="6">
								<input class="inputtype" type="text" size="100" maxlength="100" name="S00_Koza_Biko0" style="ime-mode: disabled;<?php echo $_SESSION["S00_Koza_Biko0_COLER"] ?>" value="<?php echo $_SESSION["S00_Koza_Biko0"] ?>">
							</td>
						</tr>

						<?php for($m=1; $m<$_SESSION["S00_Koza_DataCount"]; $m++){ ?>
							<?php 
								$No = $m + 1;
								if($_SESSION["S00_Koza_End" . $m] != ""){
									$DISABLED = "";
								}else{
									$DISABLED = "";
								}
							?>
							<tr>
								<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">【<?php echo $_SESSION["S00_Koza_KozaSeq" .$m] ?>】記号番号</td>
								<td align="left" colspan="4">
									<input class="inputtype" type="text" <?php echo $DISABLED ?> size="10" maxlength="5" name="S00_Koza_Kigou<?php echo $m ?>" style="ime-mode: disabled;<?php echo $_SESSION["S00_Koza_Kigou" . $m . "_COLER"] ?>" value="<?php echo $_SESSION["S00_Koza_Kigou" .$m] ?>" onkeyup="checkText(this)">-
									<input class="inputtype" type="text" <?php echo $DISABLED ?> size="10" maxlength="8" name="S00_Koza_Bango<?php echo $m ?>" style="ime-mode: disabled;<?php echo $_SESSION["S00_Koza_Bango" . $m . "_COLER"] ?>" value="<?php echo $_SESSION["S00_Koza_Bango" .$m] ?>" onkeyup="checkText(this)">
									<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Koza_Kigou" . $m . "_ErrMsg"] ?></font>
									<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Koza_Bango" . $m . "_ErrMsg"] ?></font>
								</td>
								<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">開始日</td>
								<td align="left">
									<input class="inputtype" type="text" <?php echo $DISABLED ?> size="15" maxlength="10" name="S00_Koza_Start<?php echo $m ?>" style="ime-mode: disabled;<?php echo $_SESSION["S00_Koza_Start" . $m . "_COLER"] ?>" value="<?php echo $_SESSION["S00_Koza_Start" .$m] ?>">
									<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Koza_Start" . $m . "_ErrMsg"] ?></font>
								</td>
							</tr>
							<tr>
								<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">名義人</td>
								<td align="left" colspan="4">
									<input class="inputtype" type="text" <?php echo $DISABLED ?> size="30" maxlength="30" name="S00_Koza_Meigi<?php echo $m ?>" style="ime-mode: disabled;<?php echo $_SESSION["S00_Koza_Meigi" . $m . "_COLER"] ?>" value="<?php echo $_SESSION["S00_Koza_MeigiKana" .$m] ?>">
									カナ<input class="inputtype" type="text" <?php echo $DISABLED ?> size="30" maxlength="30" name="S00_Koza_MeigiKana<?php echo $m ?>" style="ime-mode: disabled;<?php echo $_SESSION["S00_Koza_MeigiKana" . $m . "_COLER"] ?>" value="<?php echo $_SESSION["S00_Koza_MeigiKana" .$m] ?>">
									<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Koza_Meigi" . $m . "_ErrMsg"] ?></font>
									<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Koza_MeigiKana" . $m . "_ErrMsg"] ?></font>
								</td>
								<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">終了日</td>
								<td align="left">
									<input class="inputtype" type="text" size="15" maxlength="10" name="S00_Koza_End<?php echo $m ?>" style="ime-mode: disabled;<?php echo $_SESSION["S00_Koza_End2" . $m . "_COLER"] ?>" value="<?php echo $_SESSION["S00_Koza_End" .$m] ?>">
									<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Koza_End2" . $m . "_ErrMsg"] ?></font>
								</td>
							</tr>
							<tr>
								<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">備考</td>
								<td align="left" colspan="6">
									<input class="inputtype" type="text" size="100" maxlength="100" name="S00_Koza_Biko<?php echo $m ?>" style="ime-mode: disabled;<?php echo $_SESSION["S00_Koza_Biko" . $m . "_COLER"] ?>" value="<?php echo $_SESSION["S00_Koza_Biko" .$m] ?>">
								</td>
							</tr>

						<?php } ?>


					<?php }else{ ?>
						<tr>
							<td align="left" colspan="8"><input type="button" id="Koza2" name="Koza2" onClick="sbmfnc(this,'')" style="cursor: pointer" value="+口座情報" /></td>
						</tr>
					<?php } ?>
					<?php if($_SESSION["TorokuJyohoFlg"] == "0"){ ?>
						<tr>
							<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="6"> <input type="button" id="TorokuJyoho" name="TorokuJyoho" onClick="sbmfnc(this,'')" style="cursor: pointer" value="-登録情報" /></td>
							<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">申込日</td>
							<td align="left" colspan="6">
								<input class="inputtype" type="text" size="15" maxlength="10" name="S00_TorokuDay" style="ime-mode: disabled;<?php echo $_SESSION["S00_TorokuDay_COLER"] ?>" value="<?php echo $_SESSION["S00_TorokuDay"] ?>">
								<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_TorokuDay_ErrMsg"] ?></font>
							</td>
						</tr>
						<tr>
							<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="3">現状</td>
							<td align="left" colspan="6" bgcolor="<?php echo $_SESSION["S00_Genjyo_COLER"]?>">
								<?php $CodeCnt = ceil($_SESSION["S00_GenjyoCount"] / 2);?>
								<?php $CodeCnt2 = $CodeCnt + 1;?>
								<?php for($dataidx=0; $dataidx < $CodeCnt; $dataidx++){ ?>
									<?php $m = $dataidx + 1;?>
									<input type="checkbox" name="S00_Genjyo<?php echo $m ?>" value="<?php echo $_SESSION["23CodeData"]["23_Eda_" . $dataidx]?>" <?php if($_SESSION["S00_Genjyo" . $m] == 1){?> CHECKED <?php } ?>><?php echo $_SESSION["23CodeData"]["23_CodeName1_" . $dataidx] ?>　
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td align="left" colspan="6" bgcolor="<?php echo $_SESSION["S00_Genjyo_COLER"]?>">
								<?php for($dataidx=$CodeCnt; $dataidx < $_SESSION["S00_GenjyoCount"]; $dataidx++){ ?>
									<?php $m = $dataidx + 1;?>
									<input type="checkbox" name="S00_Genjyo<?php echo $m ?>" value="<?php echo $_SESSION["23CodeData"]["23_Eda_" . $dataidx]?>" <?php if($_SESSION["S00_Genjyo" . $m] == 1){?> CHECKED <?php } ?>><?php echo $_SESSION["23CodeData"]["23_CodeName1_" . $dataidx] ?>　
								<?php } ?>
<!--								<input type="checkbox" name="S00_Genjyo99" value="<?php echo $_SESSION["23CodeData"]["23_Eda_" . $dataidx]?>" <?php if($_SESSION["S00_Genjyo99"] == 1){?> CHECKED <?php } ?>><?php echo $_SESSION["23CodeData"]["23_CodeName1_" . $dataidx] ?>　
-->
							</td>
						</tr>
						<tr>
							<td align="left" colspan="6" bgcolor="<?php echo $_SESSION["S00_Genjyo_COLER"]?>">
								<input type="checkbox" name="S00_Genjyo99" value="<?php echo $_SESSION["S00_Genjyo99"]?>" <?php if($_SESSION["S00_Genjyo99"] == 1){?> CHECKED <?php } ?>>その他　
								<input class="inputtype" type="text" size="100" maxlength="100" name="S00_Genjyo_Sonota" style="ime-mode: disabled;<?php echo $_SESSION["S00_Genjyo_Sonota_COLER"] ?>" value="<?php echo $_SESSION["S00_Genjyo_Sonota"] ?>">
								<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Genjyo_Sonota_ErrMsg"] ?></font>

							</td>
						</tr>
						<tr>
							<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="2">相談</td>
							<td align="left" colspan="6">
								<input class="inputtype" type="text" size="150" maxlength="500" name="S00_Soudan" style="ime-mode: disabled;<?php echo $_SESSION["S00_Soudan_COLER"] ?>" value="<?php echo $_SESSION["S00_Soudan"] ?>">
								<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Soudan_ErrMsg"] ?></font>
							</td>
						</tr>
						<tr>
							<td align="left" colspan="6">
								<textarea name="S00_Sonota_Naiyo" rows="10" cols="150" style="ime-mode: disabled;<?php echo $_SESSION["S00_Sonota_Naiyo_COLER"] ?>"><?php echo $_SESSION["S00_Sonota_Naiyo"] ?></textarea>
								<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Sonota_Naiyo_ErrMsg"] ?></font>
							</td>
						</tr>
					<?php }else{ ?>
						<tr>
							<td align="left" colspan="8"><input type="button" id="TorokuJyoho2" name="TorokuJyoho2" onClick="sbmfnc(this,'')" style="cursor: pointer" value="+登録情報" /></td>
						</tr>
					<?php } ?>
					<?php if($_SESSION["KibouFlg"] == "0"){ ?>
						<tr>
							<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="12"> <input type="button" id="KibouJyoho" name="KibouJyoho" onClick="sbmfnc(this,'')" style="cursor: pointer" value="-希望情報" /></td>
							<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">小学生</td>
							<td align="left" colspan="6">
								<input type="checkbox" name="S00_Sub1_1" value="<?php echo $_SESSION["S00_Sub1_1"]?>" <?php if($_SESSION["S00_Sub1_1"] == 1){?> CHECKED <?php } ?>>国
								<input type="checkbox" name="S00_Sub1_2" value="<?php echo $_SESSION["S00_Sub1_2"]?>" <?php if($_SESSION["S00_Sub1_2"] == 1){?> CHECKED <?php } ?>>算
								<input type="checkbox" name="S00_Sub1_3" value="<?php echo $_SESSION["S00_Sub1_3"]?>" <?php if($_SESSION["S00_Sub1_3"] == 1){?> CHECKED <?php } ?>>理
								<input type="checkbox" name="S00_Sub1_4" value="<?php echo $_SESSION["S00_Sub1_4"]?>" <?php if($_SESSION["S00_Sub1_4"] == 1){?> CHECKED <?php } ?>>社
								<input type="checkbox" name="S00_Sub1_5" value="<?php echo $_SESSION["S00_Sub1_5"]?>" <?php if($_SESSION["S00_Sub1_5"] == 1){?> CHECKED <?php } ?>>英
								<input type="checkbox" name="S00_Sub1_6" value="<?php echo $_SESSION["S00_Sub1_6"]?>" <?php if($_SESSION["S00_Sub1_6"] == 1){?> CHECKED <?php } ?>>私立受験
							</td>
						</tr>
						<tr>
							<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">中学生</td>
							<td align="left" colspan="6">
								<input type="checkbox" name="S00_Sub2_1" value="<?php echo $_SESSION["S00_Sub2_1"]?>" <?php if($_SESSION["S00_Sub2_1"] == 1){?> CHECKED <?php } ?>>国
								<input type="checkbox" name="S00_Sub2_2" value="<?php echo $_SESSION["S00_Sub2_2"]?>" <?php if($_SESSION["S00_Sub2_2"] == 1){?> CHECKED <?php } ?>>算
								<input type="checkbox" name="S00_Sub2_3" value="<?php echo $_SESSION["S00_Sub2_3"]?>" <?php if($_SESSION["S00_Sub2_3"] == 1){?> CHECKED <?php } ?>>理
								<input type="checkbox" name="S00_Sub2_4" value="<?php echo $_SESSION["S00_Sub2_4"]?>" <?php if($_SESSION["S00_Sub2_4"] == 1){?> CHECKED <?php } ?>>社
								<input type="checkbox" name="S00_Sub2_5" value="<?php echo $_SESSION["S00_Sub2_5"]?>" <?php if($_SESSION["S00_Sub2_5"] == 1){?> CHECKED <?php } ?>>英
								<input type="checkbox" name="S00_Sub2_6" value="<?php echo $_SESSION["S00_Sub2_6"]?>" <?php if($_SESSION["S00_Sub2_6"] == 1){?> CHECKED <?php } ?>>高校受験
							</td>
						</tr>
						<tr>
							<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0"rowspan="4">高校生</td>
							<td align="left" colspan="6" height="30">
								<input type="checkbox" name="S00_Sub3_1" value="<?php echo $_SESSION["S00_Sub3_1"]?>" <?php if($_SESSION["S00_Sub3_1"] == 1){?> CHECKED <?php } ?>>現文
								<input type="checkbox" name="S00_Sub3_2" value="<?php echo $_SESSION["S00_Sub3_2"]?>" <?php if($_SESSION["S00_Sub3_2"] == 1){?> CHECKED <?php } ?>>古文
								<input type="checkbox" name="S00_Sub3_3" value="<?php echo $_SESSION["S00_Sub3_3"]?>" <?php if($_SESSION["S00_Sub3_3"] == 1){?> CHECKED <?php } ?>>漢文
								<input type="checkbox" name="S00_Sub3_4" value="<?php echo $_SESSION["S00_Sub3_4"]?>" <?php if($_SESSION["S00_Sub3_4"] == 1){?> CHECKED <?php } ?>>小論文
								<input type="checkbox" name="S00_Sub3_5" value="<?php echo $_SESSION["S00_Sub3_5"]?>" <?php if($_SESSION["S00_Sub3_5"] == 1){?> CHECKED <?php } ?>>英語
								<input type="checkbox" name="S00_Sub3_6" value="<?php echo $_SESSION["S00_Sub3_6"]?>" <?php if($_SESSION["S00_Sub3_6"] == 1){?> CHECKED <?php } ?>>大学受験

							</td>
						</tr>
						<tr>
							<td align="left" colspan="6" height="30">
								<input type="checkbox" name="S00_Sub3_7" value="<?php echo $_SESSION["S00_Sub3_7"]?>" <?php if($_SESSION["S00_Sub3_7"] == 1){?> CHECKED <?php } ?>>数Ⅰ
								<input type="checkbox" name="S00_Sub3_8" value="<?php echo $_SESSION["S00_Sub3_8"]?>" <?php if($_SESSION["S00_Sub3_8"] == 1){?> CHECKED <?php } ?>>数Ａ
								<input type="checkbox" name="S00_Sub3_9" value="<?php echo $_SESSION["S00_Sub3_9"]?>" <?php if($_SESSION["S00_Sub3_9"] == 1){?> CHECKED <?php } ?>>数Ⅱ
								<input type="checkbox" name="S00_Sub3_10" value="<?php echo $_SESSION["S00_Sub3_10"]?>" <?php if($_SESSION["S00_Sub3_10"] == 1){?> CHECKED <?php } ?>>数Ｂ
								<input type="checkbox" name="S00_Sub3_11" value="<?php echo $_SESSION["S00_Sub3_11"]?>" <?php if($_SESSION["S00_Sub3_11"] == 1){?> CHECKED <?php } ?>>数Ⅲ
								<input type="checkbox" name="S00_Sub3_12" value="<?php echo $_SESSION["S00_Sub3_12"]?>" <?php if($_SESSION["S00_Sub3_12"] == 1){?> CHECKED <?php } ?>>数Ｃ
							</td>
						</tr>
						<tr>
							<td align="left" colspan="6" height="30">
								<input type="checkbox" name="S00_Sub3_13" value="<?php echo $_SESSION["S00_Sub3_13"]?>" <?php if($_SESSION["S00_Sub3_13"] == 1){?> CHECKED <?php } ?>>物理
								<input type="checkbox" name="S00_Sub3_14" value="<?php echo $_SESSION["S00_Sub3_14"]?>" <?php if($_SESSION["S00_Sub3_14"] == 1){?> CHECKED <?php } ?>>化学
								<input type="checkbox" name="S00_Sub3_15" value="<?php echo $_SESSION["S00_Sub3_15"]?>" <?php if($_SESSION["S00_Sub3_15"] == 1){?> CHECKED <?php } ?>>生物
								<input type="checkbox" name="S00_Sub3_16" value="<?php echo $_SESSION["S00_Sub3_16"]?>" <?php if($_SESSION["S00_Sub3_16"] == 1){?> CHECKED <?php } ?>>地学
							</td>
						</tr>
						<tr>
							<td align="left" colspan="6" height="30">
								<input type="checkbox" name="S00_Sub3_17" value="<?php echo $_SESSION["S00_Sub3_17"]?>" <?php if($_SESSION["S00_Sub3_17"] == 1){?> CHECKED <?php } ?>>日本史
								<input type="checkbox" name="S00_Sub3_18" value="<?php echo $_SESSION["S00_Sub3_18"]?>" <?php if($_SESSION["S00_Sub3_18"] == 1){?> CHECKED <?php } ?>>世界史
								<input type="checkbox" name="S00_Sub3_19" value="<?php echo $_SESSION["S00_Sub3_19"]?>" <?php if($_SESSION["S00_Sub3_19"] == 1){?> CHECKED <?php } ?>>政経
								<input type="checkbox" name="S00_Sub3_20" value="<?php echo $_SESSION["S00_Sub3_20"]?>" <?php if($_SESSION["S00_Sub3_20"] == 1){?> CHECKED <?php } ?>>地理
							</td>
						</tr>
						<tr>
							<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">その他</td>
							<td align="left" colspan="6">
								<input class="inputtype" type="text" size="80" maxlength="40" name="S00_Kyoka_Sonota" style="ime-mode: disabled;" value="<?php echo $_SESSION["S00_Kyoka_Sonota"] ?>">
							</td>
						</tr>
						<tr>
							<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">曜日</td>
							<td align="left" colspan="6" bgcolor="<?php echo $_SESSION["S00_Youbi_COLER"]?>">
								<input type="checkbox" name="S00_Youbi1" value="<?php echo $_SESSION["S00_Youbi1"]?>" <?php if($_SESSION["S00_Youbi1"] == 1){?> CHECKED <?php } ?>>月　
								<input type="checkbox" name="S00_Youbi2" value="<?php echo $_SESSION["S00_Youbi2"]?>" <?php if($_SESSION["S00_Youbi2"] == 1){?> CHECKED <?php } ?>>火　
								<input type="checkbox" name="S00_Youbi3" value="<?php echo $_SESSION["S00_Youbi3"]?>" <?php if($_SESSION["S00_Youbi3"] == 1){?> CHECKED <?php } ?>>水　
								<input type="checkbox" name="S00_Youbi4" value="<?php echo $_SESSION["S00_Youbi4"]?>" <?php if($_SESSION["S00_Youbi4"] == 1){?> CHECKED <?php } ?>>木　
								<input type="checkbox" name="S00_Youbi5" value="<?php echo $_SESSION["S00_Youbi5"]?>" <?php if($_SESSION["S00_Youbi5"] == 1){?> CHECKED <?php } ?>>金　
								<input type="checkbox" name="S00_Youbi6" value="<?php echo $_SESSION["S00_Youbi6"]?>" <?php if($_SESSION["S00_Youbi6"] == 1){?> CHECKED <?php } ?>>土　
								<input type="checkbox" name="S00_Youbi7" value="<?php echo $_SESSION["S00_Youbi7"]?>" <?php if($_SESSION["S00_Youbi7"] == 1){?> CHECKED <?php } ?>>日　
								<BR><input class="inputtype" type="text" size="100" maxlength="100" name="S00_Youbi_Sonota" style="ime-mode: disabled;<?php echo $_SESSION["S00_Youbi_Sonota_COLER"] ?>" value="<?php echo $_SESSION["S00_Youbi_Sonota"] ?>">
								<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Youbi_Sonota_ErrMsg"] ?></font>

							</td>
						</tr>
						<tr>
							<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">時間</td>
							<td align="left" colspan="6">
								<input class="inputtype" type="text" size="100" maxlength="100" name="S00_ShidoTime" style="ime-mode: disabled;<?php echo $_SESSION["S00_ShidoTime_COLER"] ?>" value="<?php echo $_SESSION["S00_ShidoTime"] ?>">
								<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_ShidoTime_ErrMsg"] ?></font>
							</td>
						</tr>
						<tr>
							<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">指導内容</td>
							<td align="left" colspan="6">
								<input class="inputtype" type="text" size="100" maxlength="100" name="S00_ShidoKibou" style="ime-mode: disabled;<?php echo $_SESSION["S00_ShidoKibou_COLER"] ?>" value="<?php echo $_SESSION["S00_ShidoKibou"] ?>">
								<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_ShidoKibou_ErrMsg"] ?></font>
							</td>

						</tr>
						<tr>
							<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="2">希望教師</td>
							<td align="left" colspan="6">
								<?php for($dataidx=0; $dataidx < $_SESSION["18CodeData"]["18DataCount"]; $dataidx++){ ?>
									<input type="checkbox" name="S00_KyoushiKibou<?php echo $dataidx?>" value="<?php echo $_SESSION["S00_KyoushiKibou" . $dataidx]?>" <?php if($_SESSION["S00_KyoushiKibou" . $dataidx] == 1){?> CHECKED <?php } ?>><?php echo $_SESSION["18CodeData"]["18_CodeName1_" . $dataidx] ?>　
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td align="left" colspan="6">
								<input class="inputtype" type="text" size="100" maxlength="100" name="S00_KyoushiKibouNaiyo" style="ime-mode: disabled;<?php echo $_SESSION["S00_KyoushiKibouNaiyo_COLER"] ?>" value="<?php echo $_SESSION["S00_KyoushiKibouNaiyo"] ?>">
								<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_KyoushiKibouNaiyo_ErrMsg"] ?></font>
							</td>
						</tr>
					<?php }else{ ?>
						<tr>
							<td align="left" colspan="8"><input type="button" id="KibouJyoho2" name="KibouJyoho2" onClick="sbmfnc(this,'')" style="cursor: pointer" value="+希望情報" /></td>
						</tr>
					<?php } ?>
					<?php if($_SESSION["TokkiFlg"] == "0"){ ?>
						<tr>
							<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="8"> <input type="button" id="TokkiJiko" name="TokkiJiko" onClick="sbmfnc(this,'')" style="cursor: pointer" value="-特記事項" /></td>
							<td align="left" colspan="7">
								<textarea name="S00_Notice1" rows="4" cols="150"><?php echo $_SESSION["S00_Notice1"] ?></textarea>
								<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Notice1_ErrMsg"] ?></font>
							</td>
						</tr>
						<tr>
							<td align="left" colspan="7">
								<textarea name="S00_Notice2" rows="4" cols="150"><?php echo $_SESSION["S00_Notice2"] ?></textarea>
								<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Notice2_ErrMsg"] ?></font>
							</td>
						</tr>
						<tr>
							<td align="left" colspan="7">
								<textarea name="S00_Notice3" rows="4" cols="150"><?php echo $_SESSION["S00_Notice3"] ?></textarea>
								<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Notice3_ErrMsg"] ?></font>
							</td>
						</tr>
						<tr>
							<td align="left" colspan="7">
								<textarea name="S00_Notice4" rows="4" cols="150"><?php echo $_SESSION["S00_Notice4"] ?></textarea>
								<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Notice4_ErrMsg"] ?></font>
							</td>
						</tr>
						<tr>
							<td align="left" colspan="7">
								<textarea name="S00_Notice5" rows="4" cols="150"><?php echo $_SESSION["S00_Notice5"] ?></textarea>
								<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_Notice5_ErrMsg"] ?></font>
							</td>
						</tr>
						<tr>
							<td align="left" colspan="7">
								<input class="inputtype" type="text" size="150" maxlength="500" name="S00_notice1" style="ime-mode: disabled;<?php echo $_SESSION["S00_notice1_COLER"] ?>" value="<?php echo $_SESSION["S00_notice1"] ?>">
								<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_notice1_ErrMsg"] ?></font>
							</td>
						</tr>
						<tr>
							<td align="left" colspan="7">
								<input class="inputtype" type="text" size="150" maxlength="500" name="S00_notice2" style="ime-mode: disabled;<?php echo $_SESSION["S00_notice2_COLER"] ?>" value="<?php echo $_SESSION["S00_notice2"] ?>">
								<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_notice2_ErrMsg"] ?></font>
							</td>
						</tr>
						<tr>
							<td align="left" colspan="7">
								<input class="inputtype" type="text" size="150" maxlength="500" name="S00_notice3" style="ime-mode: disabled;<?php echo $_SESSION["S00_notice3_COLER"] ?>" value="<?php echo $_SESSION["S00_notice3"] ?>">
								<BR><font size="1" color="#ff0000"><?php echo $_SESSION["S00_notice3_ErrMsg"] ?></font>
							</td>
						</tr>

					<?php }else{ ?>
						<tr>
							<td align="left" colspan="8"><input type="button" id="TokkiJiko2" name="TokkiJiko2" onClick="sbmfnc(this,'')" style="cursor: pointer" value="+特記事項" /></td>
						</tr>
					<?php } ?>
				</table>
				</div>
			</td>
		</tr>
	</table>
<BR><BR>
</form>
</body>
</CENTER>
</html>