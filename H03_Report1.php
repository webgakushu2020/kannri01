<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN"
	"http://www.w3.org/TR/html4/frameset.dtd">
<?php
//*******************************************************************************
//	報告画面
//
//	2015/12/10
//	中島tomo
//
//	処理概要
//	月別の報告を登録・更新
//
//	GetDataFlg・・・年・月が変更になったらデータを再取得する
//	INPUTFLG・・・入力がある日に１　初期値０
//	ErrFlg・・・更新時にエラーがある場合１
//	EntryFlg・・・「入力済」ボタンが押下されたら１　初期値１
//	AllDayFlg・・・「全日」ボタンが押下されたら１　初期値０
//テスト２
//*******************************************************************************
?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="Header.css">
	<link rel="stylesheet" type="text/css" href="main.css">
	<title>報告書入力画面</title>
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

//------変数定義------
$TodayYSel = $TodayY;	//選択年
$TodayMSel = $TodayM;	//選択月
$YobiSel = 9;		//曜日番号をセット（9:空白 0:日～6:土）
$EMSG = "";
//--------------------


//-----------------------
//main処理
//-----------------------

	// ログアウト処理

//	if(isset($_POST['logout'])){
//		if($_SESSION["passhoji"] == 1){
//		 	LogoutShori2();
//		}else{
//		 	LogoutShori();
//		}
//		exit;
//	}

	// 戻る処理
//	if(isset($_POST['modoru'])){
//	 	ModoruShori($_SESSION["H03_Report1_RPID"]);
//		exit;
//	}

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
		 	ModoruShori($_SESSION["H03_Report1_RPID"]);
			exit;
		}
	}

	//曜日選択処理（プルダウン）
	if(isset($_POST['torokuYoubi']) || isset($_POST['torokuNen']) || isset($_POST['torokutuki'])){
		$_SESSION["ShoriID"]="SEL";
	}

	if(isset($_POST['submitter'])){
		switch ($_POST['submitter']){
			case 'update':
				$_SESSION["ShoriID"]="UPDATE";
				break;
			case 'allday':
				$_SESSION["ShoriID"]="ALLDAY";
				break;
			case 'entry':
				$_SESSION["ShoriID"]="ENTRY";
				break;
			case 'clear':
				$_SESSION["ShoriID"]="CLEAR";
				break;
			case 'copy':
				$_SESSION["ShoriID"]="COPY";
				break;
			case 'insert':
				$_SESSION["ShoriID"]="CALINS";
				break;
			case 'Houkoku':
				$_SESSION["ShoriID"]="HOUKOKU";
				break;
			case 'HoukokuKaijyo':
				$_SESSION["ShoriID"]="KAIJYO";
				break;
			case 'print1':
				$_SESSION["ShoriID"]="PRINT";
				break;
			case 'back':
				$_SESSION["ShoriID"]="BACK";
				break;
		}
	}
	
	if(isset($_POST['submitter'])){
		$Shuseicha = explode("_", $_POST['submitter']);
		$Shuseicha0 = $Shuseicha[0];
		if(isset($Shuseicha[1])){
			$Shuseicha1 = $Shuseicha[1];
		}else{
			$Shuseicha1 = "";
		}
		if($Shuseicha0 == "shusei"){
			$_SESSION["ShoriID"]="SHUSEI";
			$_SESSION["DISABLEDFLG_" . $Shuseicha1] = 0;
			$EMSG = SaveShori();
		}
	}

	if(isset($_POST['submitter'])){
		$Tenyuryoku = explode("_", $_POST['submitter']);
		$Tenyuryoku0 = $Tenyuryoku[0];
		if(isset($Tenyuryoku[1])){
			$Tenyuryoku1 = $Tenyuryoku[1];
		}else{
			$Tenyuryoku1 = "";
		}
		if($Tenyuryoku0 == "tenyuryoku"){
			$_SESSION["ShoriID"]="TENEY";
			$_SESSION["Tenyu_" . $Tenyuryoku1] = 1;
			$EMSG = SaveShori();
		}
	}

		//登録ボタン押下
//		if(isset($_POST['update'])){
//			$_SESSION["ShoriID"]="UPDATE";
//		}

		//全日ボタン押下
//		if(isset($_POST['allday'])){
//			$_SESSION["ShoriID"]="ALLDAY";
//		}

		//入力済ボタン押下
//		if(isset($_POST['entry'])){
//			$_SESSION["ShoriID"]="ENTRY";
//		}

		//カレンダーボタン押下
//		if(isset($_POST['calendar'])){
//			$_SESSION["ShoriID"]="CALENDER";
//		}

		//クリアボタン押下
//		if(isset($_POST['clear'])){
//			$_SESSION["ShoriID"]="CLEAR";
//		}

		//コピーボタン押下
//		if(isset($_POST['copy'])){
//			$_SESSION["ShoriID"]="COPY";
//		}

		//カレンダー決定ボタン押下
//		if(isset($_POST['insert'])){
//			$_SESSION["ShoriID"]="CALINS";
//		}

		//報告ボタン押下
//		if(isset($_POST['Houkoku'])){
//			$_SESSION["ShoriID"]="HOUKOKU";
//		}

		//報告解除ボタン押下
//		if(isset($_POST['HoukokuKaijyo'])){
//			$_SESSION["ShoriID"]="KAIJYO";
//		}

		//印刷画面ボタン押下
//		if(isset($_POST['print1'])){
//			$_SESSION["ShoriID"]="PRINT";
//		}

		//入力画面へ戻るボタン押下
//		if(isset($_POST['back'])){
//			$_SESSION["ShoriID"]="BACK";
//		}
		
//		for($h=1; $h<=$_SESSION["TodayM_end"]; $h++){
//			if(isset($_POST['shusei_'.$h])){
//				$_SESSION["ShoriID"]="SHUSEI";
//				$_SESSION["DISABLEDFLG_" . $h] = 0;
//			}
//		}

//----------------------------
//初期処理
//----------------------------
print("ここ①");
	// ログイン済みかどうかの変数チェックを行う
	if (!isset($_SESSION["user_name"])) {

		// 変数に値がセットされていない場合は不正な処理と判断し、ログイン画面へリダイレクトさせる
		$no_login_url = "http://{$_SERVER["HTTP_HOST"]}/Login1.php";
		header("Location: {$no_login_url}");
		exit;
	} else {

		//URLパラメータ
		if(isset($_GET['RPID'])) {
			$_SESSION["H03_Report1_RPID"] = $_GET['RPID'];
		      	//print($_SESSION["H03_Report1_RPID"] ."<BR>");
		}
		if(isset($_GET['MODE'])) {
			$_SESSION["H03_Report1_MODE"] = $_GET['MODE'];
			$_SESSION["ShoriID"]=$_GET['MODE'];
		      	//print($_SESSION["H03_Report1_MODE"] ."<BR>");
		}

		//----S_Kensaku2.phpから遷移した場合
		if(isset($_GET['TID'])) {
			$_SESSION["TeacherID"] = $_GET['TID'];
		      	//print($_SESSION["TeacherID"] ."<BR>");
		}

		//----S_Select.phpから遷移した場合
		//----S_Kensaku2.phpから遷移した場合
		if(isset($_GET['STID'])) {
			$_SESSION["StudentID"] = $_GET['STID'];
		      	//print($_SESSION["StudentID"] ."<BR>");
		}
		if(isset($_GET['ATENASEQ'])) {
			$_SESSION["ATENASEQ"] = $_GET['ATENASEQ'];
		      	//print($_SESSION["SEQ"] ."<BR>");
		}

		if(isset($_GET['SEQ'])) {
			$_SESSION["SEQ"] = $_GET['SEQ'];
		      	//print($_SESSION["SEQ"] ."<BR>");
		}

		//前画面からの情報
		$TeacherID = $_SESSION["TeacherID"];
		$user_name = $_SESSION["user_name"];
		$shikaku = (int) $_SESSION["shikaku"];
		$TName1 = $_SESSION["TName1"];
		$TName2 = $_SESSION["TName2"];

//print("処理：" .$_SESSION["ShoriID"] . "<BR>");
		switch ($_SESSION["ShoriID"]){
			case 'UPDT': case 'KENT': case 'KENS':
				print("ここ②");
				//-----該当月の最後の日を算出する------
				$dt2 = new DateTime();
				$dt2->setDate($TodayYSel, $TodayMSel, 1);
				$TodayM_end = $dt2->format('Y/m/t');
				$TodayM_end = substr($TodayM_end, -2);
				$_SESSION["Today"]=$Today;
				$_SESSION["TodayM_end"]=$TodayM_end;
				$_SESSION["TodayYSel"]=$TodayYSel;
				$_SESSION["TodayMSel"]=$TodayMSel;

				//セッションクリア
				SessionClear();

				//データ取得
				GetData();
				print("ここ③");
				$_SESSION["WeekEnd"] = GetCalender();

				break;

			case 'UPDATE':
				//セーブ処理
				$EMSG = SaveShori();
				//チェック処理
//				list ($WMSG, $EMSG) = CheckShori();

				//更新処理
				if ($EMSG == ""){
					$EMSG = UpdateShori();
					if($EMSG == "登録しました。"){
						$_SESSION["TourokuFlg"]=1;
					}
					$EMSG = UpdateShoriShonin();
				}

				break;
			case 'RTN':	//更新後



			case 'SEL':
				if (($_POST['torokuNen'] != $_SESSION["TodayYSel"]) || ($_POST['torokutuki'] != $_SESSION["TodayMSel"])){
					$_SESSION["YobiSel"] = 9;
					$_SESSION["AllDayFlg"] = 0;
					$_SESSION["EntryFlg"] = 1;
					$_SESSION["CALFlg"] = 0;
				}else{
					$_SESSION["YobiSel"] = $_POST['torokuYoubi'];
				}
//print("torokuYoubi:" . $_POST['torokuYoubi'] . "<BR>");
				if($_SESSION["YobiSel"] != 9){
					SaveShori();
					switch ($_SESSION["YobiSel"]) {
						case 9:	//空白
						        $YobiSel = 9;
						        break;			
						case 1:	//月曜日
						        $YobiSel = 1;
						        break;			
						case 2:	//火曜日
						        $YobiSel = 2;
						        break;			
						case 3:	//水曜日
						        $YobiSel = 3;
						        break;			
						case 4:	//木曜日
						        $YobiSel = 4;
						        break;			
						case 5:	//金曜日
						        $YobiSel = 5;
						        break;			
						case 6:	//土曜日
						        $YobiSel = 6;
						        break;			
						case 0:	//日曜日
						        $YobiSel = 0;
						        break;			
					}
					$_SESSION["YobiSel"] = $YobiSel;
					$_SESSION["GetDataFlg"]=0;
				}
				if(isset($_POST['torokuNen'])){
//print("torokuNen:" . $_POST['torokuNen'] . "<BR>");
//print("TodayYSel:" . $_SESSION["TodayYSel"] . "<BR>");
					if ($_POST['torokuNen'] != $_SESSION["TodayYSel"]){
						$dtsel = new DateTime();
						$dtsel->setDate($_POST['torokuNen'], $_POST['torokutuki'], 1);
						$TodayM_end = $dtsel->format('Y/m/t');
						$TodayM_end = substr($TodayM_end, -2);
						$TodayYSel = $_POST['torokuNen'];	//選択年
						$_SESSION["TodayM_end"]=$TodayM_end;
						$_SESSION["TodayYSel"]=$TodayYSel;
						$_SESSION["GetDataFlg"]=1;
					}
				}
				if(isset($_POST['torokutuki'])){
//print("torokutuki:" . $_POST['torokutuki'] . "<BR>");
//print("TodayMSel:" . $_SESSION["TodayMSel"] . "<BR>");

					if ($_POST['torokutuki'] != $_SESSION["TodayMSel"]){
						$dtsel = new DateTime();
						$dtsel->setDate($_POST['torokuNen'], $_POST['torokutuki'], 1);
						$TodayM_end = $dtsel->format('Y/m/t');
						$TodayM_end = substr($TodayM_end, -2);
						$TodayMSel = $_POST['torokutuki'];	//選択月
						$_SESSION["TodayM_end"]=$TodayM_end;
						$_SESSION["TodayMSel"]=$TodayMSel;
						$_SESSION["GetDataFlg"]=1;
					}
				}
				//データ取得
//print("GetDataFlg" . $_SESSION["GetDataFlg"] . "<BR>");
				if($_SESSION["GetDataFlg"]==1){
					SessionClear2();
					GetData();
				}
				
				//カレンダーデータ取得
				$_SESSION["WeekEnd"] = GetCalender();

				break;

			case 'COPY': //コピー処理
				//画面内容セーブ
				SaveShori();

				$EMSG = C_CheckShori();

				//内容保存
				if($EMSG == ""){
					CopyShori();
				}
				break;
			case 'ENTRY':
				$_SESSION["EntryFlg"] = 1;
				$_SESSION["AllDayFlg"] = 0;
				$_SESSION["CALFlg"] = 0;
				break;
			case 'ALLDAY':
				$_SESSION["EntryFlg"] = 0;
				$_SESSION["AllDayFlg"] = 1;
				$_SESSION["CALFlg"] = 0;
				break;
			case 'CALINS':
				SaveShori();
				$_SESSION["CALFlg"] = 1;
				for($h=1; $h<=$_SESSION["WeekEnd"]; $h++){
					for($h2=1; $h2<=7; $h2++){
						if(isset($_POST['Calcheck_' . $h . "_" . $h2])){
							$Calidx = $_SESSION["Week_" . $h . "_" . $h2];
							$_SESSION["CALFLG_" . $Calidx] = 1;
						}
					}
				}
				break;
			case 'CLEAR':
//				for ($idx=1; $idx <= $_SESSION["TodayM_end"]; $idx++){
//					$_SESSION["Check_" .$idx]	=	$_POST['check_' . $idx];
//print("★" . $idx . "=" .$_POST['check_' . $idx] . "<BR>");
//				}
				$EMSG = SaveShori();
				$EMSG = C_CheckShori2();
				//内容保存
				if($EMSG == ""){
					for($dataidx=1; $dataidx<=31; $dataidx++){
						if(isset($_POST['check_' . $dataidx])){
							$_SESSION["StartTime_" .$dataidx]="9999";
							$_SESSION["Hours_" .$dataidx]="0";
							$_SESSION["Minutes_" .$dataidx]="0";
							$_SESSION["Kubun_" .$dataidx]="9";
							$_SESSION["Naiyo_" .$dataidx]="";
							$_SESSION["NaiyoPrint_" .$dataidx]="";
							$_SESSION["INPUTFLG_" .$dataidx]=0;
							$_SESSION["CALFLG_" .$dataidx]=0;
							$_SESSION["JissekiMinTe_" .$dataidx]="00";
						}
					}
				}
				break;
			case 'HOUKOKU':
				$EMSG = SaveShori();
				if($EMSG == ""){
				//	$EMSG = H_CheckShori();
				}
				//更新処理
				if ($EMSG == ""){
					$EMSG = UpdateShori();
					if($EMSG == "登録しました。"){
						$_SESSION["TourokuFlg"]=1;
					}
					$EMSG = UpdateShoriShonin();
					GetData();
				}

				break;
			case 'KAIJYO':
				$EMSG = SaveShori2();
				//解除処理
				if ($EMSG == ""){
					$EMSG = KaijyoShori();
					$EMSG = KaijyoShoriShonin();
					GetData();
				}

				break;
			case 'PRINT':
				$EMSG = SaveShori();
				$_SESSION["PrintFlg"]=1;
				
				break;

			case 'BACK':
				$_SESSION["PrintFlg"]=0;
				
				break;

		}	
	}

//----------------------------------------------------
//ローカル関数
//----------------------------------------------------
//----------------------------------------------------
//セッション初期化
//----------------------------------------------------
function SessionClear(){

	$_SESSION["GetDataFlg"]=0;
	$_SESSION["EntryFlg"]=1;
	$_SESSION["AllDayFlg"]=0;
	$_SESSION["HyojiFlg"] = 0;
	$_SESSION["YobiSel"]="9";
	$_SESSION["CALFlg"] = 0;
	$_SESSION["PrintFlg"]=0;
	$_SESSION["TourokuFlg"]=0;

	//コピーセッション
	$_SESSION["StartHourSel"]="9999";
	$_SESSION["JissekiHourSel"]=CON_JISSEKI_HOUR_START;
	$_SESSION["JissekiMinSel"]="0";
	$_SESSION["JissekiKubunCopy"]="1";


	//セッション初期化
	for($dataidx=0; $dataidx<=31; $dataidx++){
		$_SESSION["StartTime_" .$dataidx]="9999";
		$_SESSION["Hours_" .$dataidx]="0";
		$_SESSION["Minutes_" .$dataidx]="0";
		$_SESSION["JissekiMinTe_" .$dataidx]="0";
		$_SESSION["Kubun_" .$dataidx]="9";
		$_SESSION["Naiyo_" .$dataidx]="";
		$_SESSION["NaiyoPrint_" .$dataidx]="";
		$_SESSION["INPUTFLG_" .$dataidx]=0;
		$_SESSION["CALFLG_" .$dataidx]=0;
		$_SESSION["DISABLEDFLG_" .$dataidx]=0;
		$_SESSION["Tenyu_" .$dataidx]=0;
		$_SESSION["Entrytime_" .$dataidx]="";
		$_SESSION["Uptime_" .$dataidx]="";
	}
		$_SESSION["KubunSel"]=1;
		$_SESSION["INPUTFLG_"]=0;
		$_SESSION["HourSumH"] = "";
		$_SESSION["HourSumM"] = "";
		$_SESSION["Pay"] = 0;
		$_SESSION["Koutuhi1"] = "";
		$_SESSION["Kaisu1"] = "";
		$_SESSION["Koutuhi2"] = "";
		$_SESSION["Kaisu2"] = "";
		$_SESSION["Koutuhi3"] = "";
		$_SESSION["Kaisu3"] = "";
		$_SESSION["Koutuhi4"] = "";
		$_SESSION["Kaisu4"] = "";
		$_SESSION["Koutuhi5"] = "";
		$_SESSION["Kaisu5"] = "";
		$_SESSION["MeisaiSum1"] = "";
		$_SESSION["MeisaiSum2"] = "";
		$_SESSION["MeisaiSum3"] = "";
		$_SESSION["MeisaiSum4"] = "";
		$_SESSION["MeisaiSum5"] = "";
		$_SESSION["KoutuhiSum"] = "";
		$_SESSION["SonotaKoutuhi"] = "";
		$_SESSION["SonotaBiko"] = "";
		$_SESSION["Seisan"] = 2;
		$_SESSION["SeisanBiko"] = "";
		$_SESSION["ZanPM"] = "1";
		$_SESSION["ZanHoursH"] = "99";
		$_SESSION["ZanHoursM"] = "990";
		$_SESSION["Yousu"] = "";
		$_SESSION["YousuPrint"] = "";
		$_SESSION["Shukudai"] = "";
		$_SESSION["Kiryoku"] = "";
		$_SESSION["Gakuryoku"] = "";
		$_SESSION["Hansei"] = "";
		$_SESSION["HanseiPrint"] = "";
		$_SESSION["Keikaku"] = "";
		$_SESSION["SeitoKankei"] = "";
		$_SESSION["Seiseki1"] = "";
		$_SESSION["Seiseki2"] = "";
		$_SESSION["Seiseki3"] = "";
		$_SESSION["Seiseki4"] = "";
		$_SESSION["Seiseki5"] = "";
		$_SESSION["Seiseki6"] = "";
		$_SESSION["Seiseki7"] = "";
		$_SESSION["Seiseki8"] = "";
		$_SESSION["Seiseki9"] = "";
		$_SESSION["Seiseki10"] = "";
		$_SESSION["HoukokuFL"] = "";
		$_SESSION["Entrytime"]="";
		$_SESSION["Uptime"]="";
		$_SESSION["Entrytime2"]="";
		$_SESSION["Uptime2"]="";
}
//----------------------------------------------------
//セッション初期化(日情報のみ）
//----------------------------------------------------
function SessionClear2(){

	//セッション初期化
	for($dataidx=0; $dataidx<=31; $dataidx++){
		$_SESSION["StartTime_" .$dataidx]="9999";
		$_SESSION["Hours_" .$dataidx]="0";
		$_SESSION["Minutes_" .$dataidx]="0";
		$_SESSION["JissekiMinTe_" .$dataidx]="0";
		$_SESSION["Kubun_" .$dataidx]="9";
		$_SESSION["Naiyo_" .$dataidx]="";
		$_SESSION["NaiyoPrint_" .$dataidx]="";
		$_SESSION["INPUTFLG_" .$dataidx]=0;
		$_SESSION["CALFLG_" .$dataidx]=0;
		$_SESSION["DISABLEDFLG_" .$dataidx]=0;
		$_SESSION["Tenyu_" .$dataidx]=0;
		$_SESSION["Entrytime_" .$dataidx]="";
		$_SESSION["Uptime_" .$dataidx]="";
	}
		$_SESSION["KubunSel"]=1;
		$_SESSION["INPUTFLG_"]=0;
		$_SESSION["HourSumH"] = "";
		$_SESSION["HourSumM"] = "";
		$_SESSION["Pay"] = 0;
		$_SESSION["Koutuhi1"] = "";
		$_SESSION["Kaisu1"] = "";
		$_SESSION["Koutuhi2"] = "";
		$_SESSION["Kaisu2"] = "";
		$_SESSION["Koutuhi3"] = "";
		$_SESSION["Kaisu3"] = "";
		$_SESSION["Koutuhi4"] = "";
		$_SESSION["Kaisu4"] = "";
		$_SESSION["Koutuhi5"] = "";
		$_SESSION["Kaisu5"] = "";
		$_SESSION["MeisaiSum1"] = "";
		$_SESSION["MeisaiSum2"] = "";
		$_SESSION["MeisaiSum3"] = "";
		$_SESSION["MeisaiSum4"] = "";
		$_SESSION["MeisaiSum5"] = "";
		$_SESSION["KoutuhiSum"] = "";
		$_SESSION["SonotaKoutuhi"] = "";
		$_SESSION["SonotaBiko"] = "";
		$_SESSION["Seisan"] = 2;
		$_SESSION["SeisanBiko"] = "";
		$_SESSION["ZanPM"] = "1";
		$_SESSION["ZanHoursH"] = "99";
		$_SESSION["ZanHoursM"] = "990";
		$_SESSION["Yousu"] = "";
		$_SESSION["YousuPrint"] = "";
		$_SESSION["Shukudai"] = "";
		$_SESSION["Kiryoku"] = "";
		$_SESSION["Gakuryoku"] = "";
		$_SESSION["Hansei"] = "";
		$_SESSION["HanseiPrint"] = "";
		$_SESSION["Keikaku"] = "";
		$_SESSION["SeitoKankei"] = "";
		$_SESSION["Seiseki1"] = "";
		$_SESSION["Seiseki2"] = "";
		$_SESSION["Seiseki3"] = "";
		$_SESSION["Seiseki4"] = "";
		$_SESSION["Seiseki5"] = "";
		$_SESSION["Seiseki6"] = "";
		$_SESSION["Seiseki7"] = "";
		$_SESSION["Seiseki8"] = "";
		$_SESSION["Seiseki9"] = "";
		$_SESSION["Seiseki10"] = "";
		$_SESSION["HoukokuFL"] = "";
}
//----------------------------------------------------
//生徒情報取得
//----------------------------------------------------
function GetData()
{
	$JissekiHSum=0;
	$JissekiMSum=0;
	$KaisuSum="";

	//生徒情報取得
	$StudentID = $_SESSION["StudentID"];
	$StudentAtenaSEQ = $_SESSION["ATENASEQ"];
	$StudentSEQ = $_SESSION["SEQ"];

	list ($TName1, $TName2) = GetSAtena($StudentID,$StudentAtenaSEQ);
	$_SESSION["SName1"] = $TName1;
	$_SESSION["SName2"] = $TName2;
	

	//生徒名編集
	$StudentName = $_SESSION["SName1"] . "　" . $_SESSION["SName2"];
	$_SESSION["StudentName"] = $StudentName;

	//月別報告内容取得
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
	$userTeacherID = $mysqli->real_escape_string($_SESSION["TeacherID"]);
	$userStudentID = $mysqli->real_escape_string($_SESSION["StudentID"]);
	$userStudentAtenaSEQ = $mysqli->real_escape_string($_SESSION["ATENASEQ"]);
	$userStudentSEQ = $mysqli->real_escape_string($_SESSION["SEQ"]);

	// ===== 日別明細データ取得 =====
	$query = "SELECT * FROM T_ReportDay WHERE  TeacherID = '" . $userTeacherID . "'";
	$query = $query . " AND StudentID = '" . $userStudentID . "'";
	$query = $query . " AND Seq = " . $userStudentAtenaSEQ;
	$query = $query . " AND KeiyakuSeq = " . $userStudentSEQ;
	$query = $query . " AND Year = '" . $_SESSION["TodayYSel"] . "'";
	$query = $query . " AND Month = '" . $_SESSION["TodayMSel"] . "'";
	$query = $query . " ORDER BY Year ASC,Month ASC,Day ASC ";

	$result = $mysqli->query($query);

	//print("●●".$query ."<BR>");

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
//		print ("---" . $i ."---<BR>");
//		print ("key===" . $key ."<BR>");

		//セッション格納
		$dataidx = $data[$i]['Day'];
		$_SESSION["Day_" .$dataidx]=$data[$i]['Day'];
		$_SESSION["StartTime_" .$dataidx]=$data[$i]['StartTime'];
		$_SESSION["Hours_" .$dataidx]=$data[$i]['Hours'];
		$_SESSION["Minutes_" .$dataidx]=$data[$i]['Minutes'];
		$_SESSION["JissekiMinTe_" .$dataidx]=$data[$i]['Minutes'];
		$_SESSION["Kubun_" .$dataidx]=$data[$i]['Kubun'];
		$_SESSION["Naiyo_" .$dataidx]=$data[$i]['Naiyo'];
		$_SESSION["NaiyoPrint_" .$dataidx]=str_replace("\r\n","<BR>",$data[$i]['Naiyo']);
		$_SESSION["HoukokuFL_" .$dataidx]=$data[$i]['HoukokuFL'];
		$_SESSION["EntryID_" .$dataidx]=$data[$i]['EntryID'];
		$_SESSION["UpdateID_" .$dataidx]=$data[$i]['UpdateID'];
		if(isset($data[$i]['EntryTime'])){
			$_SESSION["Entrytime_" .$dataidx]=$data[$i]['EntryTime'];
		}else{
			$_SESSION["Entrytime_" .$dataidx]="";
		}
		if(isset($data[$i]['UpTime'])){
			$_SESSION["Uptime_" .$dataidx]=$data[$i]['UpTime'];
		}else{
			$_SESSION["Uptime_" .$dataidx]="";
		}
		$_SESSION["INPUTFLG_" .$dataidx]=1;
		$_SESSION["DISABLEDFLG_" .$dataidx]=1;
		$_SESSION["Tenyu_" .$dataidx]=1;

//		print ("TeacherID_" . $dataidx ."===" . $_SESSION["TeacherID_" .$dataidx] ."<BR>");
//		print ("StudentID_" . $dataidx ."===" . $_SESSION["StudentID_" .$dataidx] ."<BR>");
//		print ("Seq_" . $dataidx ."===" . $_SESSION["Seq_" .$dataidx] ."<BR>");
//		print ("Year_" . $dataidx ."===" . $_SESSION["Year_" .$dataidx] ."<BR>");
//		print ("Month_" . $dataidx ."===" . $_SESSION["Month_" .$dataidx] ."<BR>");
//		print ("Day_" . $dataidx ."===" . $_SESSION["Day_" .$dataidx] ."<BR>");
//		print ("StartTime_" . $dataidx ."===" . $_SESSION["StartTime_" .$dataidx] ."<BR>");
//		print ("Hours_" . $dataidx ."===" . $_SESSION["Hours_" .$dataidx] ."<BR>");
//		print ("Minutes_" . $dataidx ."===" . $_SESSION["Minutes_" .$dataidx] ."<BR>");
//		print ("Kubun_" . $dataidx ."===" . $_SESSION["Kubun_" .$dataidx] ."<BR>");
//		print ("Naiyo_" . $dataidx ."===" . $_SESSION["Naiyo_" .$dataidx] ."<BR>");
//		print ("HoukokuFL_" . $dataidx ."===" . $_SESSION["HoukokuFL_" .$dataidx] ."<BR>");
//		print ("INPUTFLG_" . $dataidx ."===" . $_SESSION["INPUTFLG_" .$dataidx] ."<BR>");

		$i++;
	}

	$_SESSION["DataCount"] = $i;

//	if($_SESSION["DataCount"] == 0){
//		SessionClear2();
//	}

	// ===== 実績時間　回数　取得 =====
	$query2 = "SELECT Sum(Hours) as JissekiHSum,Sum(Minutes) as JissekiMSum,Count(*) as KaisuSum FROM T_ReportDay WHERE  TeacherID = '" . $userTeacherID . "'";
	$query2 = $query2 . " AND StudentID = '" . $userStudentID . "'";
	$query2 = $query2 . " AND Seq = " . $userStudentAtenaSEQ;
	$query2 = $query2 . " AND KeiyakuSeq = " . $userStudentSEQ;
	$query2 = $query2 . " AND Year = '" . $_SESSION["TodayYSel"] . "'";
	$query2 = $query2 . " AND Month = '" . $_SESSION["TodayMSel"] . "'";
	$query2 = $query2 . " AND Hours <> '99' AND Minutes <> '990'";
	$query2 = $query2 . " Group by TeacherID,StudentID,Seq,Year,Month";

	$result2 = $mysqli->query($query2);

	//print("●●".$query2 ."<BR>");

	if (!$result2) {
		print('クエリーが失敗しました。' . $mysqli->error);
		$mysqli->close();
		exit();
	}
	
	while ($row = $result2->fetch_assoc()) {
		$JissekiHSum = $row['JissekiHSum'];
		$JissekiMSum = $row['JissekiMSum'];
		$KaisuSum = $row['KaisuSum'];
	}

	//実績分を時間に変換
	$MtoH = floor($JissekiMSum / 60);
	$MtoH_Amari = floor($JissekiMSum % 60);

	$JissekiHSum2 = $JissekiHSum + $MtoH;

	$_SESSION["JissekiHSum"] = $JissekiHSum2;
	$_SESSION["JissekiMSum"] = $MtoH_Amari;
	$_SESSION["KaisuSum"] = $KaisuSum;

	// ===== 月別報告データ取得 =====
	$query3 = "SELECT * FROM T_ReportMonth WHERE  TeacherID = '" . $userTeacherID . "'";
	$query3 = $query3 . " AND StudentID = '" . $userStudentID . "'";
	$query3 = $query3 . " AND Seq = " . $userStudentAtenaSEQ;
	$query3 = $query3 . " AND KeiyakuSeq = " . $userStudentSEQ;
	$query3 = $query3 . " AND Year = '" . $_SESSION["TodayYSel"] . "'";
	$query3 = $query3 . " AND Month = '" . $_SESSION["TodayMSel"] . "'";

	$result3 = $mysqli->query($query3);

	//print("●3●".$query3 ."<BR>");

	if (!$result3) {
		print('クエリーが失敗しました。' . $mysqli->error);
		$mysqli->close();
		exit();
	}

	while($arr_item2 = $result3->fetch_assoc()){
		//レコード内の各フィールド名と値を順次参照
		foreach($arr_item2 as $key => $value){
			//フィールド名と値を表示
			$data[$key] = $value;
		}
		$_SESSION["HourSumH"] = $data['HourSumH'];
		$_SESSION["HourSumM"] = $data['HourSumM'];
		$_SESSION["Pay"] = $data['Pay'];
		$_SESSION["Koutuhi1"] = $data['Koutuhi1'];
		$_SESSION["Kaisu1"] = $data['Kaisu1'];
		$_SESSION["Koutuhi2"] = $data['Koutuhi2'];
		$_SESSION["Kaisu2"] = $data['Kaisu2'];
		$_SESSION["Koutuhi3"] = $data['Koutuhi3'];
		$_SESSION["Kaisu3"] = $data['Kaisu3'];
		$_SESSION["Koutuhi4"] = $data['Koutuhi4'];
		$_SESSION["Kaisu4"] = $data['Kaisu4'];
		$_SESSION["Koutuhi5"] = $data['Koutuhi5'];
		$_SESSION["Kaisu5"] = $data['Kaisu5'];
		$_SESSION["MeisaiSum1"] = (int)$data['Koutuhi1'] * (int)$data['Kaisu1'];
		$_SESSION["MeisaiSum2"] = (int)$data['Koutuhi2'] * (int)$data['Kaisu2'];
		$_SESSION["MeisaiSum3"] = (int)$data['Koutuhi3'] * (int)$data['Kaisu3'];
		$_SESSION["MeisaiSum4"] = (int)$data['Koutuhi4'] * (int)$data['Kaisu4'];
		$_SESSION["MeisaiSum5"] = (int)$data['Koutuhi5'] * (int)$data['Kaisu5'];
		$_SESSION["KoutuhiSum"] = $data['KoutuhiSum'];
		$_SESSION["SonotaKoutuhi"] = $data['SonotaKoutuhi'];
		$_SESSION["SonotaBiko"] = $data['SonotaBiko'];
		$_SESSION["Seisan"] = $data['Seisan'];
		$_SESSION["SeisanBiko"] = $data['SeisanBiko'];
		$_SESSION["ZanPM"] = $data['ZanPM'];
		$_SESSION["ZanHoursH"] = $data['ZanHoursH'];
		$_SESSION["ZanHoursM"] = $data['ZanHoursM'];
		$_SESSION["Yousu"] = $data['Yousu'];
		$_SESSION["YousuPrint"] = str_replace("\r\n","<BR>",$data['Yousu']);
		$_SESSION["Shukudai"] = $data['Shukudai'];
		$_SESSION["Kiryoku"] = $data['Kiryoku'];
		$_SESSION["Gakuryoku"] = $data['Gakuryoku'];
		$_SESSION["Hansei"] = $data['Hansei'];
		$_SESSION["HanseiPrint"] = str_replace("\r\n","<BR>",$data['Hansei']);
		$_SESSION["Keikaku"] = $data['Keikaku'];
		$_SESSION["SeitoKankei"] = $data['SeitoKankei'];
		$_SESSION["Seiseki1"] = $data['Seiseki1'];
		$_SESSION["Seiseki2"] = $data['Seiseki2'];
		$_SESSION["Seiseki3"] = $data['Seiseki3'];
		$_SESSION["Seiseki4"] = $data['Seiseki4'];
		$_SESSION["Seiseki5"] = $data['Seiseki5'];
		$_SESSION["Seiseki6"] = $data['Seiseki6'];
		$_SESSION["Seiseki7"] = $data['Seiseki7'];
		$_SESSION["Seiseki8"] = $data['Seiseki8'];
		$_SESSION["Seiseki9"] = $data['Seiseki9'];
		$_SESSION["Seiseki10"] = $data['Seiseki10'];
		$_SESSION["HoukokuFL"] = $data['HoukokuFL'];
		$_SESSION["EntryID2"] = $data['EntryID'];
		$_SESSION["Entrytime2"] = $data['EntryTime'];
		$_SESSION["UpdateID2"] = $data['UpdateID'];
		$_SESSION["Uptime2"] = $data['UpTime'];
	}

	//時給の取得
	if($_SESSION["Pay"] == 0 || $_SESSION["Pay"] == ""){
		$query4 = "SELECT * FROM T_TantoShosai WHERE  TeacherID = '" . $userTeacherID . "'";
		$query4 = $query4 . " AND StudentID = '" . $userStudentID . "'";
		$query4 = $query4 . " AND AtenaSeq = " . $userStudentAtenaSEQ;
		$query4 = $query4 . " AND Seq = " . $userStudentSEQ;

		$result4 = $mysqli->query($query4);

		//print("●3●".$query3 ."<BR>");

		if (!$result4) {
			print('クエリーが失敗しました。' . $mysqli->error);
			$mysqli->close();
			exit();
		}

		while($arr_item2 = $result4->fetch_assoc()){
			//レコード内の各フィールド名と値を順次参照
			foreach($arr_item2 as $key => $value){
				//フィールド名と値を表示
				$data[$key] = $value;
			}
		}

		//$_SESSION["Pay"] = $data[Pay];
	}

 	// データベースの切断
	$mysqli->close();
}
//----------------------------------------------------
//セーブ処理
//----------------------------------------------------
function SaveShori()
{
	$_SESSION["TodayYSel"]	=	$_POST['torokuNen'];
	$_SESSION["TodayMSel"]	=	$_POST['torokutuki'];
	$_SESSION["YobiSel"] = 		$_POST['torokuYoubi'];

	$_SESSION["StartHourSel"]=	$_POST['StartHourCopy'];
	$_SESSION["JissekiHourSel"]=	$_POST['JissekiHourCopy'];
	$_SESSION["JissekiMinSel"]=	$_POST['JissekiMinCopy'];
	$_SESSION["JissekiKubunCopy"]=	$_POST['JissekiKubunCopy'];

	//日別情報
	for ($idx=1; $idx <= $_SESSION["TodayM_end"]; $idx++){
		if(isset($_POST['check_' . $idx])){
			$_SESSION["Check_" .$idx]	=	$_POST['check_' . $idx];
		}
		$_SESSION["Day_" .$idx]		=	$idx;
		if (isset($_POST['H_StartHour_' . $idx])){
			$_SESSION["StartTime_" .$idx]	=	$_POST['H_StartHour_' . $idx];
		}
		if (isset($_POST['H_JissekiHourSel_' . $idx])){
			$_SESSION["Hours_" .$idx]	=	$_POST['H_JissekiHourSel_' . $idx];
		}
		if (isset($_POST['H_JissekiKubun_' . $idx])){
			$_SESSION["Kubun_" .$idx]	=	$_POST['H_JissekiKubun_' . $idx];
		}
		if (isset($_POST['H_JissekiNaiyo_' . $idx])){
			$_SESSION["Naiyo_" .$idx]	=	$_POST['H_JissekiNaiyo_' . $idx];
			$_SESSION["NaiyoPrint_" .$idx]	=	str_replace("\r\n","<BR>",$_POST['H_JissekiNaiyo_' . $idx]);
		}
		if($_SESSION["Tenyu_" .$idx]==1){
			if (isset($_POST['H_JissekiMinTe_' . $idx])){
				$_SESSION["Minutes_" .$idx]	=	$_POST['H_JissekiMinTe_' . $idx];
				$_SESSION["JissekiMinTe_" .$idx]	=	$_POST['H_JissekiMinTe_' . $idx];
			}
		}else{
			if (isset($_POST['H_JissekiMinSel_' . $idx])){
				$_SESSION["Minutes_" .$idx]	=	$_POST['H_JissekiMinSel_' . $idx] * 10;
			}
		}
		$_SESSION["HoukokuFL_" .$idx]=	0;

		if ($_SESSION["StartTime_" .$idx] == "9999"){
			$_SESSION["INPUTFLG_" .$idx]		=	0;
		}else{
//print("①");
			$_SESSION["INPUTFLG_" .$idx]		=	1;
		}
		if (($_SESSION["INPUTFLG_" .$idx] == 0) && ($_SESSION["Hours_" .$idx] == "0")){
			$_SESSION["INPUTFLG_" .$idx]		=	0;
		}else{
//print("②");
			$_SESSION["INPUTFLG_" .$idx]		=	1;
		}
		if (($_SESSION["INPUTFLG_" .$idx] == 0) && ($_SESSION["Minutes_" .$idx] == "0")){	//×１０なので
			$_SESSION["INPUTFLG_" .$idx]		=	0;
		}else{
//print("③");
			$_SESSION["INPUTFLG_" .$idx]		=	1;
		}
		if (($_SESSION["INPUTFLG_" .$idx] == 0) && ($_SESSION["Kubun_" .$idx] == "9")){
			$_SESSION["INPUTFLG_" .$idx]		=	0;
		}else{
//print("④");
			$_SESSION["INPUTFLG_" .$idx]		=	1;
		}
		if (($_SESSION["INPUTFLG_" .$idx] == 0) && (empty($_POST['Naiyo_' . $idx]))){
			$_SESSION["INPUTFLG_" .$idx]		=	0;
		}else{
//print("⑤");
			$_SESSION["INPUTFLG_" .$idx]		=	1;
		}
	}

	//月別情報
	$_SESSION["Koutuhi1"] = $_POST['H_Koutuhi1'];
	$_SESSION["Kaisu1"] = $_POST['H_Kaisu1'];
	$_SESSION["Koutuhi2"] = $_POST['H_Koutuhi2'];
	$_SESSION["Kaisu2"] = $_POST['H_Kaisu2'];
	$_SESSION["Koutuhi3"] = $_POST['H_Koutuhi3'];
	$_SESSION["Kaisu3"] = $_POST['H_Kaisu3'];
	$_SESSION["Koutuhi4"] = $_POST['H_Koutuhi4'];
	$_SESSION["Kaisu4"] = $_POST['H_Kaisu4'];
	$_SESSION["Koutuhi5"] = $_POST['H_Koutuhi5'];
	$_SESSION["Kaisu5"] = $_POST['H_Kaisu5'];
	$_SESSION["MeisaiSum1"] = $_POST['H_MeisaiSum1'];
	$_SESSION["MeisaiSum2"] = $_POST['H_MeisaiSum2'];
	$_SESSION["MeisaiSum3"] = $_POST['H_MeisaiSum3'];
	$_SESSION["MeisaiSum4"] = $_POST['H_MeisaiSum4'];
	$_SESSION["MeisaiSum5"] = $_POST['H_MeisaiSum5'];
	$_SESSION["KoutuhiSum"] = $_POST['H_KoutuhiSum'];
	$_SESSION["SonotaKoutuhi"] = $_POST['H_SonotaKoutuhi'];
	$_SESSION["SonotaBiko"] = $_POST['H_SonotaBiko'];
	$_SESSION["Seisan"] = $_POST['H_Seisan'];
	if(isset($_POST['H_SeisanBiko'])){
		$_SESSION["SeisanBiko"] = $_POST['H_SeisanBiko'];
	}
	$_SESSION["ZanPM"] = $_POST['H_ZanPM'];
	$_SESSION["ZanHoursH"] = $_POST['H_ZanHoursH'];
	$_SESSION["ZanHoursM"] = $_POST['H_ZanHoursM'] * 10;

	if(isset($_POST['H_Yousu'])){
		$_SESSION["Yousu"] = $_POST['H_Yousu'];
		$_SESSION["YousuPrint"] = str_replace("\r\n","<BR>",$_POST['H_Yousu']);
	}
	if(isset($_POST['H_Shukudai'])){
		$_SESSION["Shukudai"] = $_POST['H_Shukudai'];
	}
	if(isset($_POST['H_Kiryoku'])){
		$_SESSION["Kiryoku"] = $_POST['H_Kiryoku'];
	}
	if(isset($_POST['H_Gakuryoku'])){
		$_SESSION["Gakuryoku"] = $_POST['H_Gakuryoku'];
	}
	if(isset($_POST['H_Hansei'])){
		$_SESSION["Hansei"] = $_POST['H_Hansei'];
		$_SESSION["HanseiPrint"] = str_replace("\r\n","<BR>",$_POST['H_Hansei']);
	}
	if(isset($_POST['H_Keikaku'])){
		$_SESSION["Keikaku"] = $_POST['H_Keikaku'];
	}
	if(isset($_POST['H_SeitoKankei'])){
		$_SESSION["SeitoKankei"] = $_POST['H_SeitoKankei'];
	}
	$_SESSION["Seiseki1"] = $_POST['H_Seiseki1'];
	$_SESSION["Seiseki2"] = $_POST['H_Seiseki2'];
	$_SESSION["Seiseki3"] = $_POST['H_Seiseki3'];
	$_SESSION["Seiseki4"] = $_POST['H_Seiseki4'];
	$_SESSION["Seiseki5"] = $_POST['H_Seiseki5'];
	$_SESSION["Seiseki6"] = $_POST['H_Seiseki6'];
	$_SESSION["Seiseki7"] = $_POST['H_Seiseki7'];
	$_SESSION["Seiseki8"] = $_POST['H_Seiseki8'];
	$_SESSION["Seiseki9"] = $_POST['H_Seiseki9'];
	$_SESSION["Seiseki10"] = $_POST['H_Seiseki10'];


}
//----------------------------------------------------
//セーブ処理（解除時）
//----------------------------------------------------
function SaveShori2()
{

	$_SESSION["TodayYSel"]	=	$_POST['torokuNen'];
	$_SESSION["TodayMSel"]	=	$_POST['torokutuki'];
	$_SESSION["YobiSel"] = 		$_POST['torokuYoubi'];


}
//----------------------------------------------------
//更新処理処理
//----------------------------------------------------
function UpdateShori()
{
	$ErrMSG = "";
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

	// 入力値のサニタイズ
	$userTeacherID = $mysqli->real_escape_string($_SESSION["TeacherID"]);
	$userStudentID = $mysqli->real_escape_string($_SESSION["StudentID"]);
	$userStudentAtenaSEQ = $mysqli->real_escape_string($_SESSION["ATENASEQ"]);
	$userStudentSEQ = $mysqli->real_escape_string($_SESSION["SEQ"]);

	$query = "DELETE FROM T_ReportDay WHERE  TeacherID = '" . $userTeacherID . "'";
	$query = $query . " AND StudentID = '" . $userStudentID . "'";
	$query = $query . " AND Seq = " . $userStudentAtenaSEQ;
	$query = $query . " AND KeiyakuSeq = " . $userStudentSEQ;
	$query = $query . " AND Year = '" . $_SESSION["TodayYSel"] . "'";
	$query = $query . " AND Month = '" . $_SESSION["TodayMSel"] . "'";

	$result = $mysqli->query($query);
	if (!$result) {
		$ErrMSG = "クエリーが失敗しました。（日別削除エラー）" . $mysqli->error;
		$ErrFlg = 1;
	}

	if ($ErrFlg == 0){
		for ($idx=1; $idx <= $_SESSION["TodayM_end"]; $idx++){
			if ($ErrFlg == 0){
				if($_SESSION["INPUTFLG_" .$idx] == 1){
					$query2 = "INSERT INTO T_ReportDay ";
					$query2 = $query2 . "values(";
					$query2 = $query2 . "'" . $userTeacherID . "'";
					$query2 = $query2 . ",'" . $userStudentID . "'";
					$query2 = $query2 . "," . $userStudentAtenaSEQ;
					$query2 = $query2 . "," . $userStudentSEQ;
					$query2 = $query2 . ",'" . $_SESSION["TodayYSel"] . "'";
					$query2 = $query2 . ",'" . $_SESSION["TodayMSel"] . "'";
					$query2 = $query2 . ",'" . $_SESSION["Day_" .$idx] . "'";
					$query2 = $query2 . ",'" . $_SESSION["StartTime_" .$idx] . "'";
					$query2 = $query2 . ",'" . $_SESSION["Hours_" .$idx] . "'";
					$query2 = $query2 . ",'" . $_SESSION["Minutes_" .$idx] . "'";
					$query2 = $query2 . ",'" . $_SESSION["Kubun_" .$idx] . "'";
					$query2 = $query2 . ",'" . $_SESSION["Naiyo_" .$idx] . "'";
					$query2 = $query2 . ",'" . $_SESSION["HoukokuFL_" .$idx] . "'";
					if(isset($_SESSION["EntryID_" .$idx])){
						$query2 = $query2 . ",'" . $userTeacherID . "'";
					}else{
						$query2 = $query2 . ",'" . $_SESSION["EntryID_" .$idx] . "'";
					}
					if(isset($_SESSION["Entrytime_" .$idx])){
						$query2 = $query2 . ",'" . $_SESSION["Today"] . "'";
					}else{
						$query2 = $query2 . ",'" . $_SESSION["Entrytime_" .$idx] . "'";
					}
					$query2 = $query2 . ",'" . $userTeacherID . "'";
					$query2 = $query2 . ",'" . $_SESSION["Today"] . "'";
					$query2 = $query2 . ")";
	//print($query2);
					$result = $mysqli->query($query2);
					if (!$result) {
						$ErrMSG = "クエリーが失敗しました。（日別インサートエラー）" . $mysqli->error;
						$ErrFlg = 1;
					}
				}
			}
		}
	}

	//T_ReportDay正常終了後

	if($ErrFlg == 0){
		//指導回数　時間取得
		$query5 = "SELECT Sum(Hours) as JissekiHSum,Sum(Minutes) as JissekiMSum,Count(*) as KaisuSum FROM T_ReportDay WHERE  TeacherID = '" . $userTeacherID . "'";
		$query5 = $query5 . " AND StudentID = '" . $userStudentID . "'";
		$query5 = $query5 . " AND Seq = " . $userStudentAtenaSEQ;
		$query5 = $query5 . " AND KeiyakuSeq = " . $userStudentSEQ;
		$query5 = $query5 . " AND Year = '" . $_SESSION["TodayYSel"] . "'";
		$query5 = $query5 . " AND Month = '" . $_SESSION["TodayMSel"] . "'";
		$query5 = $query5 . " AND Kubun <> '7'";
		$query5 = $query5 . " Group by TeacherID,StudentID,Seq,Year,Month";

		//print("●●".$query5 ."<BR>");

		$result5 = $mysqli->query($query5);


		if (!$result5) {
			print('クエリーが失敗しました。（合計時間の取得エラー）' . $mysqli->error);
			$mysqli->close();
			exit();
		}

		while ($row = $result5->fetch_assoc()) {
			$JissekiHSum = $row['JissekiHSum'];
			$JissekiMSum = $row['JissekiMSum'];
			$KaisuSum = $row['KaisuSum'];
		}

		//実績分を時間に変換
		$MtoH = floor($JissekiMSum / 60);
		$MtoH_Amari = floor($JissekiMSum % 60);

		$JissekiHSum2 = $JissekiHSum + $MtoH;

		$_SESSION["JissekiHSum"] = $JissekiHSum2;
		$_SESSION["JissekiMSum"] = $MtoH_Amari;
		$_SESSION["KaisuSum"] = $KaisuSum;

	}

	if($ErrFlg == 0){
		//月別報告を登録
		$query3 = "DELETE FROM T_ReportMonth WHERE  TeacherID = '" . $userTeacherID . "'";
		$query3 = $query3 . " AND StudentID = '" . $userStudentID . "'";
		$query3 = $query3 . " AND Seq = " . $userStudentAtenaSEQ;
		$query3 = $query3 . " AND KeiyakuSeq = " . $userStudentSEQ;
		$query3 = $query3 . " AND Year = '" . $_SESSION["TodayYSel"] . "'";
		$query3 = $query3 . " AND Month = '" . $_SESSION["TodayMSel"] . "'";

		$result3 = $mysqli->query($query3);
		if (!$result3) {
			$ErrMSG = "クエリーが失敗しました。（月別削除エラー）" . $mysqli->error;
			$ErrFlg = 1;
		}

		//INSERT処理
		if($ErrFlg == 0){
			$query4 = "INSERT INTO T_ReportMonth ";
			$query4 = $query4 . "values(";
			$query4 = $query4 . "'" . $userTeacherID . "'";
			$query4 = $query4 . ",'" . $userStudentID . "'";
			$query4 = $query4 . "," . $userStudentAtenaSEQ;
			$query4 = $query4 . "," . $userStudentSEQ;
			$query4 = $query4 . ",'" . $_SESSION["TodayYSel"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["TodayMSel"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["KaisuSum"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["JissekiHSum"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["JissekiMSum"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["Pay"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["Koutuhi1"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["Kaisu1"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["Koutuhi2"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["Kaisu2"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["Koutuhi3"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["Kaisu3"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["Koutuhi4"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["Kaisu4"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["Koutuhi5"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["Kaisu5"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["KoutuhiSum"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["SonotaKoutuhi"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["SonotaBiko"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["Seisan"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["SeisanBiko"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["ZanPM"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["ZanHoursH"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["ZanHoursM"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["Yousu"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["Shukudai"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["Kiryoku"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["Gakuryoku"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["Hansei"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["Keikaku"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["SeitoKankei"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["Seiseki1"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["Seiseki2"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["Seiseki3"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["Seiseki4"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["Seiseki5"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["Seiseki6"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["Seiseki7"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["Seiseki8"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["Seiseki9"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["Seiseki10"] . "'";
			$query4 = $query4 . ",'" . $_SESSION["HoukokuFL"] . "'";
			if(isset($_SESSION["EntryID2"])){
				$query4 = $query4 . ",'" . $userTeacherID . "'";
			}else{
				$query4 = $query4 . ",'" . $_SESSION["EntryID2"] . "'";
			}
			if(isset($_SESSION["Entrytime2"])){
				$query4 = $query4 . ",'" . $_SESSION["Today"] . "'";
			}else{
				$query4 = $query4 . ",'" . $_SESSION["Entrytime2"] . "'";
			}
			$query4 = $query4 . ",'" . $userTeacherID . "'";
			$query4 = $query4 . ",'" . $_SESSION["Today"] . "'";
			$query4 = $query4 . ")";
//print($query4);
			$result4 = $mysqli->query($query4);
			if (!$result4) {
				$ErrMSG = "クエリーが失敗しました。（月別インサートエラー）" . $mysqli->error;
				$ErrFlg = 1;
			}
		}
	}

	if($ErrFlg == 0){
		if ($_SESSION["ShoriID"] == "HOUKOKU"){
			$query6 = "UPDATE T_ReportDay SET HoukokuFL = 1";
			$query6 = $query6 . " WHERE  TeacherID = '" . $userTeacherID . "'";
			$query6 = $query6 . " AND StudentID = '" . $userStudentID . "'";
			$query6 = $query6 . " AND Seq = " . $userStudentAtenaSEQ;
			$query6 = $query6 . " AND KeiyakuSeq = " . $userStudentSEQ;
			$query6 = $query6 . " AND Year = '" . $_SESSION["TodayYSel"] . "'";
			$query6 = $query6 . " AND Month = '" . $_SESSION["TodayMSel"] . "'";

			$result6 = $mysqli->query($query6);
			if (!$result6) {
				$ErrMSG = "クエリーが失敗しました。（日別報告処理更新エラー）" . $mysqli->error;
				$ErrFlg = 1;
			}

			if($ErrFlg == 0){
				$query7 = "UPDATE T_ReportMonth SET HoukokuFL = 1";
				$query7 = $query7 . " WHERE  TeacherID = '" . $userTeacherID . "'";
				$query7 = $query7 . " AND StudentID = '" . $userStudentID . "'";
				$query7 = $query7 . " AND Seq = " . $userStudentAtenaSEQ;
				$query7 = $query7 . " AND KeiyakuSeq = " . $userStudentSEQ;
				$query7 = $query7 . " AND Year = '" . $_SESSION["TodayYSel"] . "'";
				$query7 = $query7 . " AND Month = '" . $_SESSION["TodayMSel"] . "'";

				$result7 = $mysqli->query($query7);
				if (!$result7) {
					$ErrMSG = "クエリーが失敗しました。（月別報告処理更新エラー）" . $mysqli->error;
					$ErrFlg = 1;
				}
			}
			
			if($ErrFlg == 0){
				$mysqli->query("commit");
				$RtnMSG = "報告処理が完了しました。";
			}

		} else {
			// コミット
			$mysqli->query("commit");

			$RtnMSG = "登録しました。";
		}
		$mysqli->close();

		return $RtnMSG;

	}else{
		$mysqli->query("rollback");

		$mysqli->close();

		return $ErrMSG;
	}


}
//----------------------------------------------------
//更新処理処理
//----------------------------------------------------
function UpdateShoriShonin()
{
	$ErrMSG = "";
	$ErrFlg = 0;
	$KubunCnt = 0;
	// mysqlへの接続
	$mysqli = new mysqli(HOST, USER, PASS);
	if ($mysqli->connect_errno) {
		print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
		exit();
	}

	// データベースの選択
	$mysqli->select_db(DBNAME2);
	$mysqli->set_charset("utf8");

	//トランザクションをはじめる準備
	$Query = "set autocommit = 0";
	$mysqli->query( $Query);

	//トランザクション開始
	$Query = "begin";
	$mysqli->query( $Query);


	// 入力値のサニタイズ
	$userTeacherID = $mysqli->real_escape_string($_SESSION["TeacherID"]);
	$userStudentID = $mysqli->real_escape_string($_SESSION["StudentID"]);
	$userStudentAtenaSEQ = $mysqli->real_escape_string($_SESSION["ATENASEQ"]);
	$userStudentSEQ = $mysqli->real_escape_string($_SESSION["SEQ"]);

	//①承認日が入力されている場合は更新しない→exit
	//②承認日なし
	//③オンライン指導のデータがある場合のみインサート
	//④オンラインデータあり→承認管理データがない場合はデータ作成
	//⑤オンラインデータなし→承認管理データ作成しない
	//⑥報告ボタン押下時、


	$query0 = "Select *,Count(*) as 件数 FROM SN_ShoninKanri WHERE  TeacherID = '" . $userTeacherID . "'";
	$query0 = $query0 . " AND StudentID = '" . $userStudentID . "'";
	$query0 = $query0 . " AND Seq = " . $userStudentAtenaSEQ;
	$query0 = $query0 . " AND KeiyakuSeq = " . $userStudentSEQ;
	$query0 = $query0 . " AND Year = '" . $_SESSION["TodayYSel"] . "'";
	$query0 = $query0 . " AND Month = '" . $_SESSION["TodayMSel"] . "'";

	$result0 = $mysqli->query($query0);

//print("query0=" . $query0);
	if (!$result0) {
		print('クエリーが失敗しました。（SN_ShoninKanri）' . $mysqli->error);
		$mysqli->close();
		exit();
	}

	while ($row = $result0->fetch_assoc()) {
		$ShoninCnt = $row['件数'];
		$ShoninDay = $row['ShoninDay'];
	}
	if($ShoninCnt == 0){
		$ErrFlg = 0;
	}else{
		if(is_null($ShoninDay)){
			$ErrFlg = 0;
		}else{
			$ErrFlg = 1;
			$ErrMSG = "ご家庭承認済みのデータです。承認システムは更新しません。報告システムのみ登録しました。";
		}
	}

	if ($ErrFlg == 0){
		$query = "DELETE FROM SN_ReportDay WHERE  TeacherID = '" . $userTeacherID . "'";
		$query = $query . " AND StudentID = '" . $userStudentID . "'";
		$query = $query . " AND Seq = " . $userStudentAtenaSEQ;
		$query = $query . " AND KeiyakuSeq = " . $userStudentSEQ;
		$query = $query . " AND Year = '" . $_SESSION["TodayYSel"] . "'";
		$query = $query . " AND Month = '" . $_SESSION["TodayMSel"] . "'";

		$result = $mysqli->query($query);
		if (!$result) {
			$ErrMSG = "クエリーが失敗しました。（日別削除エラー）" . $mysqli->error;
			$ErrFlg = 1;
		}
	}
	if ($ErrFlg == 0){
		for ($idx=1; $idx <= $_SESSION["TodayM_end"]; $idx++){
			if ($ErrFlg == 0){
				if($_SESSION["INPUTFLG_" .$idx] == 1){
//					if($_SESSION["Kubun_" .$idx] == 11){
						$query2 = "INSERT INTO SN_ReportDay ";
						$query2 = $query2 . "values(";
						$query2 = $query2 . "'" . $userTeacherID . "'";
						$query2 = $query2 . ",'" . $userStudentID . "'";
						$query2 = $query2 . "," . $userStudentAtenaSEQ;
						$query2 = $query2 . "," . $userStudentSEQ;
						$query2 = $query2 . ",'" . $_SESSION["TodayYSel"] . "'";
						$query2 = $query2 . ",'" . $_SESSION["TodayMSel"] . "'";
						$query2 = $query2 . ",'" . $_SESSION["Day_" .$idx] . "'";
						$query2 = $query2 . ",'" . $_SESSION["StartTime_" .$idx] . "'";
						$query2 = $query2 . ",'" . $_SESSION["Hours_" .$idx] . "'";
						$query2 = $query2 . ",'" . $_SESSION["Minutes_" .$idx] . "'";
						$query2 = $query2 . ",'" . $_SESSION["Kubun_" .$idx] . "'";
						$query2 = $query2 . ",'" . $_SESSION["Naiyo_" .$idx] . "'";
						$query2 = $query2 . ",'" . $_SESSION["HoukokuFL_" .$idx] . "'";
						if(isset($_SESSION["EntryID_" .$idx])){
							$query2 = $query2 . ",'" . $userTeacherID . "'";
						}else{
							$query2 = $query2 . ",'" . $_SESSION["EntryID_" .$idx] . "'";
						}
						if(isset($_SESSION["Entrytime_" .$idx])){
							$query2 = $query2 . ",'" . $_SESSION["Today"] . "'";
						}else{
							$query2 = $query2 . ",'" . $_SESSION["Entrytime_" .$idx] . "'";
						}
						$query2 = $query2 . ",'" . $userTeacherID . "'";
						$query2 = $query2 . ",'" . $_SESSION["Today"] . "'";
						$query2 = $query2 . ")";
		//print($query2);
						$result = $mysqli->query($query2);
						if (!$result) {
							$ErrMSG = "クエリーが失敗しました。（日別インサートエラー）" . $mysqli->error;
							$ErrFlg = 1;
						}
						$KubunCnt ++; 
//					}
				}
			}
		}
	}

	//T_ReportDay正常終了後

	if($ErrFlg == 0){
		//指導回数　時間取得
		$query5 = "SELECT Sum(Hours) as JissekiHSum,Sum(Minutes) as JissekiMSum,Count(*) as KaisuSum FROM SN_ReportDay WHERE  TeacherID = '" . $userTeacherID . "'";
		$query5 = $query5 . " AND StudentID = '" . $userStudentID . "'";
		$query5 = $query5 . " AND Seq = " . $userStudentAtenaSEQ;
		$query5 = $query5 . " AND KeiyakuSeq = " . $userStudentSEQ;
		$query5 = $query5 . " AND Year = '" . $_SESSION["TodayYSel"] . "'";
		$query5 = $query5 . " AND Month = '" . $_SESSION["TodayMSel"] . "'";
//		$query5 = $query5 . " AND Kubun = '11'";
		$query5 = $query5 . " Group by TeacherID,StudentID,Seq,Year,Month";

		//print("●●".$query5 ."<BR>");

		$result5 = $mysqli->query($query5);


		if (!$result5) {
			print('クエリーが失敗しました。（合計時間の取得エラー）' . $mysqli->error);
			$mysqli->close();
			exit();
		}

		while ($row = $result5->fetch_assoc()) {
			$JissekiHSum = $row['JissekiHSum'];
			$JissekiMSum = $row['JissekiMSum'];
			$KaisuSum = $row['KaisuSum'];
		}

		//実績分を時間に変換
		$MtoH = floor($JissekiMSum / 60);
		$MtoH_Amari = floor($JissekiMSum % 60);

		$JissekiHSum2 = $JissekiHSum + $MtoH;

		$_SESSION["JissekiHSum_Shonin"] = $JissekiHSum2;
		$_SESSION["JissekiMSum_Shonin"] = $MtoH_Amari;
		$_SESSION["KaisuSum_Shonin"] = $KaisuSum;

	}
	
	if($ErrFlg == 0){
		//月別報告を登録
		$query3 = "DELETE FROM SN_ReportMonth WHERE  TeacherID = '" . $userTeacherID . "'";
		$query3 = $query3 . " AND StudentID = '" . $userStudentID . "'";
		$query3 = $query3 . " AND Seq = " . $userStudentAtenaSEQ;
		$query3 = $query3 . " AND KeiyakuSeq = " . $userStudentSEQ;
		$query3 = $query3 . " AND Year = '" . $_SESSION["TodayYSel"] . "'";
		$query3 = $query3 . " AND Month = '" . $_SESSION["TodayMSel"] . "'";

		$result3 = $mysqli->query($query3);
		if (!$result3) {
			$ErrMSG = "クエリーが失敗しました。（月別削除エラー）" . $mysqli->error;
			$ErrFlg = 1;
		}
//print("KubunCnt=" . $KubunCnt);
		if($KubunCnt > 0){
			//INSERT処理
			if($ErrFlg == 0){
				$query4 = "INSERT INTO SN_ReportMonth ";
				$query4 = $query4 . "values(";
				$query4 = $query4 . "'" . $userTeacherID . "'";
				$query4 = $query4 . ",'" . $userStudentID . "'";
				$query4 = $query4 . "," . $userStudentAtenaSEQ;
				$query4 = $query4 . "," . $userStudentSEQ;
				$query4 = $query4 . ",'" . $_SESSION["TodayYSel"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["TodayMSel"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["KaisuSum_Shonin"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["JissekiHSum_Shonin"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["JissekiMSum_Shonin"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["Pay"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["Koutuhi1"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["Kaisu1"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["Koutuhi2"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["Kaisu2"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["Koutuhi3"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["Kaisu3"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["Koutuhi4"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["Kaisu4"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["Koutuhi5"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["Kaisu5"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["KoutuhiSum"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["SonotaKoutuhi"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["SonotaBiko"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["Seisan"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["SeisanBiko"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["ZanPM"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["ZanHoursH"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["ZanHoursM"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["Yousu"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["Shukudai"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["Kiryoku"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["Gakuryoku"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["Hansei"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["Keikaku"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["SeitoKankei"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["Seiseki1"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["Seiseki2"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["Seiseki3"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["Seiseki4"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["Seiseki5"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["Seiseki6"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["Seiseki7"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["Seiseki8"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["Seiseki9"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["Seiseki10"] . "'";
				$query4 = $query4 . ",'" . $_SESSION["HoukokuFL"] . "'";
				if(isset($_SESSION["EntryID2"])){
					$query4 = $query4 . ",'" . $userTeacherID . "'";
				}else{
					$query4 = $query4 . ",'" . $_SESSION["EntryID2"] . "'";
				}
				if(isset($_SESSION["Entrytime2"])){
					$query4 = $query4 . ",'" . $_SESSION["Today"] . "'";
				}else{
					$query4 = $query4 . ",'" . $_SESSION["Entrytime2"] . "'";
				}
				$query4 = $query4 . ",'" . $userTeacherID . "'";
				$query4 = $query4 . ",'" . $_SESSION["Today"] . "'";
				$query4 = $query4 . ")";
	//print($query4);
				$result4 = $mysqli->query($query4);
				if (!$result4) {
					$ErrMSG = "クエリーが失敗しました。（月別インサートエラー）" . $mysqli->error;
					$ErrFlg = 1;
				}
			}
		}
	}

	if($KubunCnt > 0){	//オンライン指導のデータがある場合
		if($ErrFlg == 0){
			//承認システムの生徒ログインデータを登録
			$query0 = "SELECT Count(*) as 件数 FROM SN_S_LoginInfo ";
			$query0 = $query0 . " WHERE StudentID = '" . $userStudentID . "'";
			$result0= $mysqli->query($query0);
		
		//print($query0 . "<BR>");
		
			if (!$result0) {
				$ErrMSG = "クエリーが失敗しました。（承認生徒ログイン管理エラー）" . $mysqli->error;
				$ErrFlg = 1;
			}
		
			if ($ErrFlg == 0){
				while ($row = $result0->fetch_assoc()) {
					$LoginCnt = $row['件数'];
				}
				if($LoginCnt == 0){
					$PassWord = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, 8);
//print($PassWord . "<BR>");
					$query1 = "INSERT INTO SN_S_LoginInfo ";
					$query1 = $query1 . "values(";
					$query1 = $query1 . "'" . $userStudentID . "'";
					$query1 = $query1 . ",'" . $PassWord . "'";
					$query1 = $query1 . ",NULL";
					$query1 = $query1 . ",'" . $_SESSION["LoginTeacherID"] . "'";
					$query1 = $query1 . ",'" . $_SESSION["Today"] . "'";
					$query1 = $query1 . ",'2099-03-31'";
					$query1 = $query1 . ",'0'";
					$query1 = $query1 . ")";
		//print($query1 . "<BR>");
					$result1 = $mysqli->query($query1);
					if (!$result1) {
						$ErrMSG = "クエリーが失敗しました。（承認ログイン管理インサートエラー）" . $mysqli->error;
						$ErrFlg = 1;
					}
				}
			}
		}

		if($ErrFlg == 0){
			//契約管理DBに更新
			$query0 = "SELECT Count(*) as 件数 FROM SN_KeiyakuKanri ";
			$query0 = $query0 . " WHERE  TeacherID = '" . $userTeacherID . "'";
			$query0 = $query0 . " AND  StudentID = '" . $userStudentID . "'";
			$query0 = $query0 . " AND  AtenaSeq = '" . $userStudentAtenaSEQ . "'";
			$result0= $mysqli->query($query0);
		
		//print($query0);
		
			if (!$result0) {
				$ErrMSG = "クエリーが失敗しました。（承認契約管理エラー）" . $mysqli->error;
				$ErrFlg = 1;
			}
		
			if ($ErrFlg == 0){
				while ($row = $result0->fetch_assoc()) {
					$KeiyakuKanriCnt = $row['件数'];
				}
				if($KeiyakuKanriCnt == 0){
					$query1 = "INSERT INTO SN_KeiyakuKanri ";
					$query1 = $query1 . "values(";
					$query1 = $query1 . "'" . $userTeacherID . "'";
					$query1 = $query1 . ",'" . $userStudentID . "'";
					$query1 = $query1 . ",'" . $userStudentAtenaSEQ . "'";
					$query1 = $query1 . ",'" . $_SESSION["TName1"] . "'";
					$query1 = $query1 . ",'" . $_SESSION["SName1"] . "'";
					$query1 = $query1 . ")";
		//print($query1);
					$result1 = $mysqli->query($query1);
					if (!$result1) {
						$ErrMSG = "クエリーが失敗しました。（承認契約管理インサートエラー）" . $mysqli->error;
						$ErrFlg = 1;
					}
				}
			}
		}
		if($ErrFlg == 0){
			//契約管理DBに更新
			$query0 = "SELECT Count(*) as 件数 FROM SN_ShoninKanri ";
			$query0 = $query0 . " WHERE  TeacherID = '" . $userTeacherID . "'";
			$query0 = $query0 . " AND  StudentID = '" . $userStudentID . "'";
			$query0 = $query0 . " AND  Seq = '" . $userStudentAtenaSEQ . "'";
			$query0 = $query0 . " AND KeiyakuSeq = '" . $userStudentSEQ . "'";
			$query0 = $query0 . " AND Year = '" . $_SESSION["TodayYSel"] . "'";
			$query0 = $query0 . " AND Month = '" . $_SESSION["TodayMSel"] . "'";

			$result0= $mysqli->query($query0);
		
		//print($query0);
		
			if (!$result0) {
				$ErrMSG = "クエリーが失敗しました。（承認管理エラー）" . $mysqli->error;
				$ErrFlg = 1;
			}
		
			if ($ErrFlg == 0){
				while ($row = $result0->fetch_assoc()) {
					$ShoninKanriCnt = $row['件数'];
				}
				if($ShoninKanriCnt == 0){
					$query1 = "INSERT INTO SN_ShoninKanri ";
					$query1 = $query1 . "values(";
					$query1 = $query1 . "'" . $userTeacherID . "'";
					$query1 = $query1 . ",'" . $userStudentID . "'";
					$query1 = $query1 . ",'" . $userStudentAtenaSEQ . "'";
					$query1 = $query1 . ",'" . $userStudentSEQ . "'";
					$query1 = $query1 . ",'" . $_SESSION["TodayYSel"] . "'";
					$query1 = $query1 . ",'" . $_SESSION["TodayMSel"] . "'";
					$query1 = $query1 . ",NULL";
					$query1 = $query1 . ",NULL";
					$query1 = $query1 . ",NULL";
					$query1 = $query1 . ",NULL";
					$query1 = $query1 . ",NULL";
					$query1 = $query1 . ")";
		
		//print($query1);
					$result1 = $mysqli->query($query1);
					if (!$result1) {
						$ErrMSG = "クエリーが失敗しました。（SN_ShoninKanriインサートエラー）" . $mysqli->error;
						$ErrFlg = 1;
					}
				}
			}
		}
	}else{
		$query0 = "DELETE FROM SN_ShoninKanri ";
		$query0 = $query0 . " WHERE  TeacherID = '" . $userTeacherID . "'";
		$query0 = $query0 . " AND  StudentID = '" . $userStudentID . "'";
		$query0 = $query0 . " AND  Seq = '" . $userStudentAtenaSEQ . "'";
		$query0 = $query0 . " AND KeiyakuSeq = '" . $userStudentSEQ . "'";
		$query0 = $query0 . " AND Year = '" . $_SESSION["TodayYSel"] . "'";
		$query0 = $query0 . " AND Month = '" . $_SESSION["TodayMSel"] . "'";

		$result0= $mysqli->query($query0);
	
		//print($query0);
	
		if (!$result0) {
			$ErrMSG = "クエリーが失敗しました。（承認管理削除エラー）" . $mysqli->error;
			$ErrFlg = 1;
		}
	}

	if($ErrFlg == 0){
		if ($_SESSION["ShoriID"] == "HOUKOKU"){
			$query6 = "UPDATE SN_ReportDay SET HoukokuFL = 1";
			$query6 = $query6 . " WHERE  TeacherID = '" . $userTeacherID . "'";
			$query6 = $query6 . " AND StudentID = '" . $userStudentID . "'";
			$query6 = $query6 . " AND Seq = " . $userStudentAtenaSEQ;
			$query6 = $query6 . " AND KeiyakuSeq = " . $userStudentSEQ;
			$query6 = $query6 . " AND Year = '" . $_SESSION["TodayYSel"] . "'";
			$query6 = $query6 . " AND Month = '" . $_SESSION["TodayMSel"] . "'";

			$result6 = $mysqli->query($query6);
			if (!$result6) {
				$ErrMSG = "クエリーが失敗しました。（日別報告処理更新エラー）" . $mysqli->error;
				$ErrFlg = 1;
			}

			if($ErrFlg == 0){
				$query7 = "UPDATE SN_ReportMonth SET HoukokuFL = 1";
				$query7 = $query7 . " WHERE  TeacherID = '" . $userTeacherID . "'";
				$query7 = $query7 . " AND StudentID = '" . $userStudentID . "'";
				$query7 = $query7 . " AND Seq = " . $userStudentAtenaSEQ;
				$query7 = $query7 . " AND KeiyakuSeq = " . $userStudentSEQ;
				$query7 = $query7 . " AND Year = '" . $_SESSION["TodayYSel"] . "'";
				$query7 = $query7 . " AND Month = '" . $_SESSION["TodayMSel"] . "'";

				$result7 = $mysqli->query($query7);
				if (!$result7) {
					$ErrMSG = "クエリーが失敗しました。（月別報告処理更新エラー）" . $mysqli->error;
					$ErrFlg = 1;
				}
			}
			
			if($ErrFlg == 0){
				$mysqli->query("commit");
				$RtnMSG = "報告処理が完了しました。";
			}

		} else {
			// コミット
			$mysqli->query("commit");

			$RtnMSG = "登録しました。";
		}
		$mysqli->close();

		return $RtnMSG;

	}else{
		$mysqli->query("rollback");

		$mysqli->close();

		return $ErrMSG;
	}


}
//----------------------------------------------------
//解除処理処理
//----------------------------------------------------
function KaijyoShori()
{

	$ErrMSG = "";
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


	// 入力値のサニタイズ
	$userTeacherID = $mysqli->real_escape_string($_SESSION["TeacherID"]);
	$userStudentID = $mysqli->real_escape_string($_SESSION["StudentID"]);
	$userStudentAtenaSEQ = $mysqli->real_escape_string($_SESSION["ATENASEQ"]);
	$userStudentSEQ = $mysqli->real_escape_string($_SESSION["SEQ"]);

	$query = "UPDATE T_ReportDay SET HoukokuFL = 0";
	$query = $query . " WHERE  TeacherID = '" . $userTeacherID . "'";
	$query = $query . " AND StudentID = '" . $userStudentID . "'";
	$query = $query . " AND Seq = " . $userStudentAtenaSEQ;
	$query = $query . " AND KeiyakuSeq = " . $userStudentSEQ;
	$query = $query . " AND Year = '" . $_SESSION["TodayYSel"] . "'";
	$query = $query . " AND Month = '" . $_SESSION["TodayMSel"] . "'";

	$result = $mysqli->query($query);
	if (!$result) {
		$ErrMSG = "クエリーが失敗しました。（日別報告解除エラー）" . $mysqli->error;
		$ErrFlg = 1;
	}

	if($ErrFlg == 0){
		$query2 = "UPDATE T_ReportMonth SET HoukokuFL = 0";
		$query2 = $query2 . " WHERE  TeacherID = '" . $userTeacherID . "'";
		$query2 = $query2 . " AND StudentID = '" . $userStudentID . "'";
		$query2 = $query2 . " AND Seq = " . $userStudentAtenaSEQ;
		$query2 = $query2 . " AND KeiyakuSeq = " . $userStudentSEQ;
		$query2 = $query2 . " AND Year = '" . $_SESSION["TodayYSel"] . "'";
		$query2 = $query2 . " AND Month = '" . $_SESSION["TodayMSel"] . "'";

		$result2 = $mysqli->query($query2);
		if (!$result2) {
			$ErrMSG = "クエリーが失敗しました。（月別報告解除エラー）" . $mysqli->error;
			$ErrFlg = 1;
		}
	}
	
	if($ErrFlg == 0){
		$mysqli->query("commit");
		$RtnMSG = "報告解除しました。";
		return $RtnMSG;
	} else {
		$mysqli->query("rollback");
		$mysqli->close();
		return $ErrMSG;
	}

}
//----------------------------------------------------
//解除処理処理（承認システム側）
//----------------------------------------------------
function KaijyoShoriShonin()
{

	$ErrMSG = "";
	$ErrFlg = 0;
	// mysqlへの接続
	$mysqli = new mysqli(HOST, USER, PASS);
	if ($mysqli->connect_errno) {
		print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
		exit();
	}

	// データベースの選択
	$mysqli->select_db(DBNAME2);
	$mysqli->set_charset("utf8");

	//トランザクションをはじめる準備
	$Query = "set autocommit = 0";
	$mysqli->query( $Query);

	//トランザクション開始
	$Query = "begin";
	$mysqli->query( $Query);


	// 入力値のサニタイズ
	$userTeacherID = $mysqli->real_escape_string($_SESSION["TeacherID"]);
	$userStudentID = $mysqli->real_escape_string($_SESSION["StudentID"]);
	$userStudentAtenaSEQ = $mysqli->real_escape_string($_SESSION["ATENASEQ"]);
	$userStudentSEQ = $mysqli->real_escape_string($_SESSION["SEQ"]);


	$query0 = "Select ShoninDay ,Count(*) as 件数 FROM SN_ShoninKanri WHERE  TeacherID = '" . $userTeacherID . "'";
	$query0 = $query0 . " AND StudentID = '" . $userStudentID . "'";
	$query0 = $query0 . " AND Seq = " . $userStudentAtenaSEQ;
	$query0 = $query0 . " AND KeiyakuSeq = " . $userStudentSEQ;
	$query0 = $query0 . " AND Year = '" . $_SESSION["TodayYSel"] . "'";
	$query0 = $query0 . " AND Month = '" . $_SESSION["TodayMSel"] . "'";
//	$query0 = $query0 . " AND ShoninDay is null";

//print($query0);
	$result0 = $mysqli->query($query0);

	if (!$result0) {
		print('クエリーが失敗しました。（SN_ShoninKanri）' . $mysqli->error);
		$mysqli->close();
		exit();
	}

	while ($row = $result0->fetch_assoc()) {
		$ShoninCnt = $row['件数'];
		$ShoninDay = $row['ShoninDay'];
	}
	if($ShoninCnt > 0){
		if(is_null($ShoninDay)){
			$ErrFlg = 0;
		}else{
			$ErrFlg = 1;
			$ErrMSG = "ご家庭承認済みのデータです。承認システムは解除しません。";
		}
	}else{
		$ErrFlg = 1;
	}

	if($ErrFlg == 0){
		$query = "UPDATE SN_ReportDay SET HoukokuFL = 0";
		$query = $query . " WHERE  TeacherID = '" . $userTeacherID . "'";
		$query = $query . " AND StudentID = '" . $userStudentID . "'";
		$query = $query . " AND Seq = " . $userStudentAtenaSEQ;
		$query = $query . " AND KeiyakuSeq = " . $userStudentSEQ;
		$query = $query . " AND Year = '" . $_SESSION["TodayYSel"] . "'";
		$query = $query . " AND Month = '" . $_SESSION["TodayMSel"] . "'";
	
		$result = $mysqli->query($query);
		if (!$result) {
			$ErrMSG = "クエリーが失敗しました。（日別報告解除エラー）" . $mysqli->error;
			$ErrFlg = 1;
		}
	}
	if($ErrFlg == 0){
		$query2 = "UPDATE SN_ReportMonth SET HoukokuFL = 0";
		$query2 = $query2 . " WHERE  TeacherID = '" . $userTeacherID . "'";
		$query2 = $query2 . " AND StudentID = '" . $userStudentID . "'";
		$query2 = $query2 . " AND Seq = " . $userStudentAtenaSEQ;
		$query2 = $query2 . " AND KeiyakuSeq = " . $userStudentSEQ;
		$query2 = $query2 . " AND Year = '" . $_SESSION["TodayYSel"] . "'";
		$query2 = $query2 . " AND Month = '" . $_SESSION["TodayMSel"] . "'";

		$result2 = $mysqli->query($query2);
		if (!$result2) {
			$ErrMSG = "クエリーが失敗しました。（月別報告解除エラー）" . $mysqli->error;
			$ErrFlg = 1;
		}
	}
	
	if($ErrFlg == 0){
		$mysqli->query("commit");
		$RtnMSG = "報告解除しました。";
		return $RtnMSG;
	} else {
		$mysqli->query("rollback");
		$mysqli->close();
		return $ErrMSG;
	}

}
//----------------------------------------------------
//コピーチェック処理
//----------------------------------------------------
function C_CheckShori()
{
	$checkCnt=0;
	for ($idx=1; $idx <= $_SESSION["TodayM_end"]; $idx++){
		if(isset($_POST['check_' . $idx])){
			$checkCnt++;
		}
	}
	if($checkCnt == 0){
		return "コピーする日付にチェックしてください。";
	}

}
//----------------------------------------------------
//コピー処理
//----------------------------------------------------
function CopyShori()
{

	for ($idx=1; $idx <= $_SESSION["TodayM_end"]; $idx++){
		if(isset($_POST['check_' . $idx])){
			$_SESSION["StartTime_" .$idx]	=	$_SESSION["StartHourSel"];
			$_SESSION["Hours_" .$idx]	=	$_SESSION["JissekiHourSel"];
			$_SESSION["Minutes_" .$idx]	=	$_SESSION["JissekiMinSel"] * 10;
			$_SESSION["Kubun_" .$idx]	=	$_SESSION["JissekiKubunCopy"];
		}
	}
}
//----------------------------------------------------
//クリアチェック処理
//----------------------------------------------------
function C_CheckShori2()
{
	$checkCnt=0;
	for ($idx=1; $idx <= $_SESSION["TodayM_end"]; $idx++){
		if(isset($_POST['check_' . $idx])){

			$checkCnt++;
		}
	}
	if($checkCnt == 0){
		return "クリアする日付にチェックしてください。";
	}

}

//----------------------------------------------------
//共通関数
//----------------------------------------------------

function GetSAtena($arg1,$arg2)
{

//print ("GetTAtena-Strat<br>");

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
	$studentid = $mysqli->real_escape_string($arg1);
	$studentseq = $mysqli->real_escape_string($arg2);

	// クエリの実行
	$query = "SELECT * FROM S_AtenaInfo WHERE StudentID = '" . $studentid . "' AND Seq = " . $studentseq ;
	$result = $mysqli->query($query);

	//print($query ."<BR>");

	if (!$result) {
		print('クエリーが失敗しました。' . $mysqli->error);
		$mysqli->close();
		exit();
	}

	while ($row = $result->fetch_assoc()) {
		$db_Name1 = $row['Name1'];
		$db_Name2 = $row['Name2'];
		
	}
	//print($db_Name1 ."<BR>");
	//print($db_Name2 ."<BR>");

 	// データベースの切断
	$mysqli->close();

	return array ($db_Name1, $db_Name2);

//print ("GetTAtena-End");


}

//----------------------------------------------------
//曜日の取得
//----------------------------------------------------
function WeekNoGet($argY,$argM,$argD){

		//日本語の曜日配列
		$weekjp = array(
		  '日', //0
		  '月', //1
		  '火', //2
		  '水', //3
		  '木', //4
		  '金', //5
		  '土'  //6
		);
		 		 
		//指定日のタイムスタンプを取得
		$timestamp = mktime(0, 0, 0, $argM, $argD, $argY);
		 
		//指定日の曜日番号（日:0  月:1  火:2  水:3  木:4  金:5  土:6）を取得
		$weekno = date('w', $timestamp);
		 
		//指定日の日本語曜日を出力
		return array ($weekno, $weekjp[$weekno]);

}
//----------------------------------------------------
//カレンダーの取得
//----------------------------------------------------
function GetCalender(){


	$calidx = 1;
	$weekidx = 0;

	for ($i = 1; $i <= $_SESSION["TodayM_end"]; $i++){

		//曜日算出
		list ($YoubiNo, $Youbi) = WeekNoGet($_SESSION["TodayYSel"],$_SESSION["TodayMSel"],$i);

		//曜日色判定
		if ($YoubiNo == 0){
			$colorCD = "#FF0000";
		} elseif ($YoubiNo == 6){
			$colorCD = "#0000FF";
		} else {
			$colorCD = "#000000";
		}
		
		switch ($YoubiNo){
			case 1:	//月曜日
				$FirstDay = 1;
			        break;				
			case 2:	//火曜日
				$FirstDay = 2;
			        break;				
			case 3:	//水曜日
				$FirstDay = 3;
			        break;				
			case 4:	//木曜日
				$FirstDay = 4;
			        break;				
			case 5:	//金曜日
				$FirstDay = 5;
			        break;				
			case 6:	//土曜日
				$FirstDay = 6;
			        break;				
			case 0:	//日曜日
				$FirstDay = 7;
			        break;				
		}
//print("★" .$YoubiNo . "/" . $FirstDay . "/" .$calidx ."/" .$weekidx);

		if (($FirstDay > 1) && ($calidx == 1) && ($weekidx == 0)){
//print("①");
			for ($idx = 1; $idx < $FirstDay; $idx++){
				$_SESSION["Week_" . $calidx . "_" . $idx] = "";
				$_SESSION["Weekcoler_" . $calidx . "_" . $idx] = "";
			}
			$weekidx = $FirstDay - 1;
			$_SESSION["Week_" . $calidx . "_" . $FirstDay] = $i;
			$_SESSION["Weekcoler_" . $calidx . "_" . $FirstDay] = $colorCD;
			$weekidx++;
		}else{
//print("②");

			$_SESSION["Week_" . $calidx . "_" . $FirstDay] = $i;
			$_SESSION["Weekcoler_" . $calidx . "_" . $FirstDay] = $colorCD;
			$weekidx++;
		}

//print($i . "----" . $weekidx);
//print("/" . $calidx . "<BR>");
//print("Week_" . $calidx . "_" . $FirstDay ."⇒" . $_SESSION["Week_" . $calidx . "_" . $FirstDay] . "<BR>");
//print("Weekcoler_" . $calidx . "_" . $FirstDay ."⇒" . $_SESSION["Weekcoler_" . $calidx . "_" . $FirstDay] . "<BR>");

		//7日毎に変数をプラス１
		$weekidx_amari = $weekidx % 7;

		//余りが０
		if ($weekidx_amari == 0){
			$weekidx=0;	//初期化
			$calidx++;	//次の週
		}


	}
	//最期の週の空白埋める
	if($weekidx==0){
		return $calidx-1;
	}else{
		for($m=$weekidx+1; $m<=7; $m++){
			$_SESSION["Week_" . $calidx . "_" . $m] = "";
			$_SESSION["Weekcoler_" . $calidx . "_" . $m] = "";
		}
		return $calidx;
	}

}
?>
<script type="text/javascript">

	function BoxChecked(check){
		for(count = 1; count <= 31; count++){

			var elements = document.getElementsByName(['check_'+count]) ;

			if( elements.length ) {
				document.form1.elements['check_'+count].checked = check;	//チェックボックスをON/OFFにする
			}

		}
	}

//	function CalenderSel(num){
//		if (num == 0){
//			document.getElementById("disp").style.display="block";
//		}else{
//			document.getElementById("disp").style.display="none";
//		}
//	}

	function bottondisplay(val1,val2,val3,num1,num2){
		var check1;
	        var columnName = val1;
	        var columnName2 = val2;
        	check1 = document.form1[columnName].checked;
	        if (check1 == true) {
			document.form1.getElementsByName([columnName2]).backgroundColor = "#FFFF00";
	        } else {
			document.form1.getElementsByName([columnName2]).backgroundColor = val3;
	        }
	}

	function setForm(formObj,flag) {
		num = formObj.elements.length; //要素の数の取得
//		for (i=0; i<num; i++){
//			formObj.elements[i].disabled = flag;
//		}
		formObj.torokuNen.disabled = false;
		formObj.torokutuki.disabled = false;
		formObj.modoru.disabled = false;
		formObj.logout.disabled = false;
		formObj.torokuYoubi.disabled = false;
		formObj.allday.disabled = false;
		formObj.entry.disabled = false;
		formObj.HoukokuKaijyo.disabled = false;
		formObj.print1.disabled = false;
		formObj.back.disabled = false;
		formObj.submitter.disabled = false;

	}

	function OnKeySum() {

		Koutuhi1 = document.form1.H_Koutuhi1.value;
		Kaisu1 = document.form1.H_Kaisu1.value;
		MeisaiSum1 = Koutuhi1 * Kaisu1;
		document.form1.H_MeisaiSum1.value = MeisaiSum1;

		Koutuhi2 = document.form1.H_Koutuhi2.value;
		Kaisu2 = document.form1.H_Kaisu2.value;
		MeisaiSum2 = Koutuhi2 * Kaisu2;
		document.form1.H_MeisaiSum2.value = MeisaiSum2;

		Koutuhi3 = document.form1.H_Koutuhi3.value;
		Kaisu3 = document.form1.H_Kaisu3.value;
		MeisaiSum3 = Koutuhi3 * Kaisu3;
		document.form1.H_MeisaiSum3.value = MeisaiSum3;

		Koutuhi4 = document.form1.H_Koutuhi4.value;
		Kaisu4 = document.form1.H_Kaisu4.value;
		MeisaiSum4 = Koutuhi4 * Kaisu4;
		document.form1.H_MeisaiSum4.value = MeisaiSum4;

		Koutuhi5 = document.form1.H_Koutuhi5.value;
		Kaisu5 = document.form1.H_Kaisu5.value;
		MeisaiSum5 = Koutuhi5 * Kaisu5;
		document.form1.H_MeisaiSum5.value = MeisaiSum5;

		KoutuhiSum = MeisaiSum1 + MeisaiSum2 + MeisaiSum3 + MeisaiSum4 + MeisaiSum5;
		document.form1.H_KoutuhiSum.value = KoutuhiSum;

	}
	window.onbeforeunload = function(e) {
		e.returnValue = "本当にページを閉じますか？";
	}

	function checkText(txt_obj){
		//テキストインプット内の入力値を変数化
		var str = txt_obj.value;
		//入力値に 0～9 以外があれば
		if(str.match(/[^0-9]+/)){
			//alert("半角数字のみを入力してください。");
			// 0～9 以外を削除
			txt_obj.value = str.replace(/[^0-9]+/g,"");
		}
	}

</script>
<script type="text/javascript" src="utility.js"></script>


<CENTER>
<body onload="<?php if($_SESSION["HoukokuFL"] == 1){ ?> setForm(document.form1,true); <?php } ?>;" onKeyPress="OnKey(event.keyCode,'');">
<form name="form1" method="post" action="H03_Report1.php">
<?php if($_SESSION["PrintFlg"]==0){ ?>
	<div id="header0" class="item">
		<BR>
		<table border="0" width="100%"  bgcolor="<?php echo HEADER_COLOR ?>">
			<tr align="center">
				<td align="center">
					<h2>報告書入力画面</h2>
				</td>
			</tr>
		</table>
	</div>
	<?php if($_SESSION["HoukokuFL"] == 1){ ?>
		<div id="header1" class="item">
	<?php }else{ ?>
		<div id="header" class="item">
	<?php } ?>
		<table border="0" width="100%">
			<tr align="Right">
				<td align="Right">
					[ログイン]　<?php echo $_SESSION["LoginTName1"] ?>
				</td>
			</tr>
			<td align="right">

				<input type="hidden" id="submitter" name="submitter" value="" />
				<input type="button" id="modoru" name="modoru" onClick="sbmfnc(this,<?php echo $_SESSION["TourokuFlg"] ?>)" style="cursor: pointer" value="戻る" />
				<input type="button" id="logout" name="logout" onClick="sbmfnc(this,<?php echo $_SESSION["TourokuFlg"] ?>)" style="cursor: pointer" value="ログアウト" />
			</td>
		</table>
		<table border="0" width="100%">
			<tr align="left">
				<td>
					<table border="1" bgcolor="<?php echo TEACHR_COLOR ?>">
						<tr>
							<td width="100" align="center"><?php echo $TeacherID ?></td>
							<td width="200" align="center"><?php echo $TName1 ?></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr align="left">
				<td>
					<table border="1">
						<tr>
							<td width="80" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">生徒ID</td>
							<td width="80" align="center"><?php echo $_SESSION["StudentID"] ?></td>
							<td width="80" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">生徒名</td>
							<td width="300" align="left"><?php echo $_SESSION["StudentName"] ?></td>
							<td width="80" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">枝番</td>
							<td width="20" align="center"><?php echo $_SESSION["ATENASEQ"] ?></td>
							<td width="100" align="center" bgcolor="<?php echo KITEI_COLOR ?>">時給</td>
							<td width="100" align="center"><?php echo number_format($_SESSION["Pay"]) ?></td>
							<td width="100" align="center" bgcolor="<?php echo KITEI_COLOR ?>">契約枝番</td>
							<td width="100" align="center"><?php echo $_SESSION["SEQ"] ?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<table border="0" width="100%">
			<?php if($_SESSION["HoukokuFL"] == 1){ ?>
				<tr align="left">
					<td><font size="5" color="#ff0000">※報告済のため変更できません。解除の場合は管理者へ連絡してください。</font></td>
				</tr>
			<?php } ?>
		</table>
	</div>

	<div id="middle" class="item">
		<div id="left_frame" class="item">
			<table border="0">
				<BR><BR>
				<tr>
					<td colspan="3"  bgcolor="#FFDAB9"><B>＜登録年月選択＞</B></td>
				</tr>
				<tr>
					<td width="80" align="center" bgcolor="#c0c0c0"><B>年月</B></td>
					<td width="50" align="center">
						<select name="torokuNen" onchange="window.onbeforeunload = null;this.form.submit()">
							<option value="<?php echo $TodayY+1 ?>" <?php if ($_SESSION["TodayYSel"] == $TodayY+1){ ?> SELECTED <?php } ?>><?php echo $TodayY + 1 ?></option>
							<?php for ($d = 0; $d <= 5; $d++) { 
								$KijyunM = $TodayY - $d;
								if ($KijyunM == $_SESSION["TodayYSel"]){
									print "<option value=" . $KijyunM . " SELECTED>" . $KijyunM . "</option>";
								}else{
									print "<option value=" . $KijyunM . ">" . $KijyunM . "</option>";
								}
							 } ?>
						</select>
					</td>
					<td width="50" align="center">
						<select name="torokutuki" onchange="window.onbeforeunload = null;this.form.submit()">
							<?php 
							for ($m = 4; $m <= 12; $m++){
								if ($_SESSION["TodayMSel"] == $m){
									print "<option value=" . $m . " SELECTED>" . $m ."月</option>";
								}else{
									print "<option value=" . $m . ">" . $m ."月</option>";
								}
							}
							for ($mm = 1; $mm <= 3; $mm++){	
								if ($_SESSION["TodayMSel"] == $mm){
									print "<option value=" . $mm . " SELECTED>" . $mm ."月</option>";
								}else{
									print "<option value=" . $mm . ">" . $mm ."月</option>";
								}
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="3">　　　　　</td>
				</tr>

				<tr>
					<td colspan="3" bgcolor="#FFDAB9"><B>＜絞り込み＞</B></td>
				</tr>
				<tr>
					<td width="80" align="center" bgcolor="#c0c0c0"><B>曜日</B></td>
					<td width="50" align="left"  colspan="2">
						<select name="torokuYoubi" onchange="window.onbeforeunload = null;this.form.submit()">
							<option value="9" <?php if ($_SESSION["YobiSel"] == 9){ ?>SELECTED<?php } ?>></option>
							<option value="1" <?php if ($_SESSION["YobiSel"] == 1){ ?>SELECTED<?php } ?>>月</option>
							<option value="2" <?php if ($_SESSION["YobiSel"] == 2){ ?>SELECTED<?php } ?>>火</option>
							<option value="3" <?php if ($_SESSION["YobiSel"] == 3){ ?>SELECTED<?php } ?>>水</option>
							<option value="4" <?php if ($_SESSION["YobiSel"] == 4){ ?>SELECTED<?php } ?>>木</option>
							<option value="5" <?php if ($_SESSION["YobiSel"] == 5){ ?>SELECTED<?php } ?>>金</option>
							<option value="6" <?php if ($_SESSION["YobiSel"] == 6){ ?>SELECTED<?php } ?>>土</option>
							<option value="0" <?php if ($_SESSION["YobiSel"] == 0){ ?>SELECTED<?php } ?>>日</option>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<input type="button" id="allday" name="allday" onClick="sbmfnc(this,'')" <?php if($_SESSION["AllDayFlg"] == 1 || $_SESSION["HoukokuFL"] == 1){ ?> disabled <?php } ?> style="cursor: pointer" value="全　日" />
						<input type="button" id="entry" name="entry" onClick="sbmfnc(this,'')" <?php if($_SESSION["EntryFlg"] == 1 || $_SESSION["HoukokuFL"] == 1){ ?> disabled <?php } ?> style="cursor: pointer" value="入力済" />
					</td>
				</tr>
				<tr>
				</tr>
					<td colspan="3">　　　　　</td>

				<tr>
					<td colspan="3" bgcolor="#FFDAB9"><B>＜カレンダー選択＞</B></td>
				</tr>
<!--				<tr>
					<td colspan="3"><input type="button" name="calendar" onClick="CalenderSel(0)" value="カレンダー選択" /></td>
				</tr>
-->
			</table>
			<div id="disp">
				<table border="1">
					<tr>
						<td colspan=7 align="center"  bgcolor="#c0c0c0"><?php echo $_SESSION["TodayYSel"] ?>年　<?php echo $_SESSION["TodayMSel"] ?>月　　　　
<!--
						<input type="button" value="×" onclick="CalenderSel(1)"></td>
					</tr>
-->
					<tr>
						<td align="center" width="30">月</td>
						<td align="center" width="30">火</td>
						<td align="center" width="30">水</td>
						<td align="center" width="30">木</td>
						<td align="center" width="30">金</td>
						<td align="center" width="30"><font color="#0000FF">土</font></td>
						<td align="center" width="30"><font color="#FF0000">日</font></td>
					</tr>
					<?php for($h=1; $h<=$_SESSION["WeekEnd"]; $h++){?>
						<tr>
								<?php for($h2=1; $h2<=7; $h2++){
									$hidx = $_SESSION["Week_" . $h . "_" . $h2];
									
									if($_SESSION["INPUTFLG_" .$hidx] == 1){
										$BGcolor = "#EE82EE";
									}else{
										$BGcolor = "#FFFFFF";
									}
								?>
									<td align="center" bgcolor='<?php echo $BGcolor ?>'>
										<?php if($_SESSION["Week_" . $h . "_" . $h2] != ""){?>
											<?php echo $_SESSION["Week_" . $h . "_" . $h2] ?><BR>
											<input type="checkbox" name="Calcheck_<?php echo $h ?>_<?php echo $h2 ?>" 
												value="<?php echo $_SESSION["Week_" . $h . "_" . $h2] ?>" 
												id="botton_<?php echo $h ?>_<?php echo $h2 ?>"
												<?php if($_SESSION["INPUTFLG_" .$hidx] == 1){?>CHECKED<?php } ?>
											>
										<?php } ?>
									</td>
								<?php } ?>
						</tr>
					<?php } ?>
					<tr>
						<td colspan=7 align="center"><input type="button" id="insert" name="insert" onClick="sbmfnc(this,'');" style="cursor: pointer" value="決定"  <?php if($_SESSION["HoukokuFL"] == 1){ ?> disabled <?php } ?>/></td>
					</tr>
				</table>
			</div>
		</div>

		<div id="right_frame" class="item">
			<table border="0" width="100%">
				<tr><td  bgcolor="#FFDAB9" align="center">
					<font size="5">■<?php echo $_SESSION["TodayYSel"] ?>年<?php echo $_SESSION["TodayMSel"] ?>月の報告内容を表示しています。■</font>
				</td></tr>	
			</table>
			<BR>
			<table border="0" width="100%">
				<font size="5" color="#ff0000"><?php echo $EMSG ?></font>
			</table>
			<table border="0" width="100%">
				<TR><TD align="right">
					<input type="button" id="print1" name="print1" onClick="sbmfnc(this,'');" style="cursor: pointer" value="印刷画面" />
				</TD></TR>
			</table>
			<table border="1">
				<tr>
					<td width="130" align="center" bgcolor="#c0c0c0" colspan="2"><input type="button" id="copy" name="copy" onClick="sbmfnc(this,'');" style="cursor: pointer" value="▼コピー▼" /></td>
					<td width="100" align="center" colspan="2">
						<select name="StartHourCopy">
							<option value="9999"></option>
							<?php
							$HourUnit = 60 / CON_HOUR_UNIT;	//１時間を割る
							$EndTime = (24 - CON_HOUR_START) * $HourUnit;	//24時までの数を算出
							for ($i = 0; $i <= $EndTime; $i++) {
								$HourBase = date("H:i", strtotime("+". $i * CON_HOUR_UNIT ." minute",mktime(CON_HOUR_START, 0, 0, 1, 1, 2000)));
								if ($_SESSION["StartHourSel"] == $HourBase){
									echo "<option value=" . $HourBase . " SELECTED>" . $HourBase ;
								}else{
									echo "<option value=" . $HourBase . ">" . $HourBase ;
								}
							}
							?>
						</select>
					</td>
					<td width="250" align="center" colspan="2">
						<select name="JissekiHourCopy">
							<?php for ($jh = CON_JISSEKI_HOUR_START; $jh <= CON_JISSEKI_HOUR_END; $jh++){ 
								if ($_SESSION["JissekiHourSel"] == $jh) {
									print "<option value=" . $jh . " SELECTED>" . $jh ."</option>";
								}else{
									print "<option value=" . $jh . ">" . $jh ."</option>";
								}
							} ?>
						</select>
						:
						<select name="JissekiMinCopy">
							<option value="0">00</option>
							<?php for ($jmi = 1; $jmi <= 5; $jmi++){ 
								if ($_SESSION["JissekiMinSel"] == $jmi) {
									print "<option value=" . $jmi . " SELECTED>" . $jmi * 10 ."</option>";
								}else{
									print "<option value=" . $jmi . ">" . $jmi * 10 ."</option>";
								}
							} ?>
						</select>
					</td>
					<td width="100" align="center">
						<select name="JissekiKubunCopy">
							<option value="1" <?php if($_SESSION["KubunSel"] == 1){ ?> SELECTED <?php } ?>>指導</option>
							<option value="2" <?php if($_SESSION["KubunSel"] == 2){ ?> SELECTED <?php } ?>>体験</option>
							<option value="11" <?php if($_SESSION["KubunSel"] == 11){ ?> SELECTED <?php } ?>>オンライン指導</option>
							<option value="3" <?php if($_SESSION["KubunSel"] == 3){ ?> SELECTED <?php } ?>>研修</option>
							<option value="5" <?php if($_SESSION["KubunSel"] == 5){ ?> SELECTED <?php } ?>>振替</option>
							<option value="6" <?php if($_SESSION["KubunSel"] == 6){ ?> SELECTED <?php } ?>>追加</option>
							<option value="7" <?php if($_SESSION["KubunSel"] == 7){ ?> SELECTED <?php } ?>>キャンセル</option>
							<option value="8" <?php if($_SESSION["KubunSel"] == 8){ ?> SELECTED <?php } ?>>カウンセリング</option>
							<option value="10" <?php if($_SESSION["KubunSel"] == 10){ ?> SELECTED <?php } ?>>営業訪問</option>
							<option value="4" <?php if($_SESSION["KubunSel"] == 4){ ?> SELECTED <?php } ?>>その他</option>
						</select>
					</td>
					<td width="400" align="left">※チェックした日の明細にコピーします</td>
				</tr>
				<tr>
					<td width="130" align="center" bgcolor="#c0c0c0" colspan="2">指導日<BR>
						<input type="button" name="all_copy" onClick="BoxChecked(true);" style="cursor: pointer" value="全"  <?php if($_SESSION["HoukokuFL"] == 1){ ?> disabled <?php } ?>/>
						<input type="button" name="all_clear" onClick="BoxChecked(false);" style="cursor: pointer" value="消"  <?php if($_SESSION["HoukokuFL"] == 1){ ?> disabled <?php } ?>/>
					</td>
					<td width="100" align="center" bgcolor="#c0c0c0" colspan="2">開始</td>
					<td width="250" align="center" bgcolor="#c0c0c0" colspan="2">指導時間</td>
					<td width="100" align="center" bgcolor="#c0c0c0">内容</td>
					<td width="400" align="center" bgcolor="#c0c0c0">指導内容</td>
				</tr>
				<?php $HyoujiCnt = 0; ?>
				<?php for ($i = 1; $i <= $_SESSION["TodayM_end"]; $i++) { 
					$_SESSION["HyojiFlg"] = 0;
					$idx = $i;
					//曜日算出
					list ($YoubiNo, $Youbi) = WeekNoGet($_SESSION["TodayYSel"],$_SESSION["TodayMSel"],$i);

					//曜日色判定
					if ($YoubiNo == 0){
						$colorCD = "#FF0000";
					} elseif ($YoubiNo == 6){
						$colorCD = "#0000FF";
					} else {
						$colorCD = "#000000";
					}
					
					//表示判定
					if($_SESSION["CALFlg"] == 1){
						if($_SESSION["CALFLG_" . $i] == 1){
							$_SESSION["HyojiFlg"] = 1;
						}
					}else{
						if (($_SESSION["YobiSel"] == 9) && ($_SESSION["EntryFlg"] == 0)){
			//print("①<BR>");
							$_SESSION["HyojiFlg"] = 1;
						}else if (($_SESSION["YobiSel"] == 9) && ($_SESSION["EntryFlg"] == 1)){
			//print("②" . $_SESSION["INPUTFLG_" .$i] . "<BR>");
							if ($_SESSION["INPUTFLG_" .$i] == 1){
								$_SESSION["HyojiFlg"] = 1;
							}
						}else if (($_SESSION["YobiSel"] != 9) && ($_SESSION["EntryFlg"] == 0)){
			//print("③" . $YoubiNo . "<BR>");
							if($_SESSION["YobiSel"] == $YoubiNo){
								$_SESSION["HyojiFlg"] = 1;
							}
						}else if (($_SESSION["YobiSel"] != 9) && ($_SESSION["EntryFlg"] == 1)){
			//print($_SESSION["INPUTFLG_" .$i] . "/" . $_SESSION["YobiSel"] . "/" . $YoubiNo . "<BR>");
							if (($_SESSION["INPUTFLG_" .$i] == 1) && ($_SESSION["YobiSel"] == $YoubiNo)){
								$_SESSION["HyojiFlg"] = 1;
							}
						}
					}
					if ($_SESSION["HyojiFlg"] == 1) {
				?>
						<tr align="center" <?php if($_SESSION["DISABLEDFLG_" .$idx] == 1){?> bgcolor="#F0F8FF" <?php } ?>>
							<td width="110" align="center">
								<font size="4" color=<?php echo $colorCD ?>>
									<B><?php echo $i ?> <?php echo $Youbi ?></B>
								</font>
								<?php if($_SESSION["DISABLEDFLG_" .$idx] == 1){?>
								<BR><BR>
								<input type="button" id="shusei_<?php echo $i?>" name="shusei_<?php echo $i?>" onClick="sbmfnc(this,'');" style="cursor: pointer" value="修正"  <?php if($_SESSION["HoukokuFL"] == 1){ ?> disabled <?php } ?>/>
								<?php } ?>
							</td>
							<td width="20" align="center">
								<input type="checkbox" name="check_<?php echo $i?>" value="<?php $i?>" <?php if($_SESSION["INPUTFLG_" .$idx] == 0){?> CHECKED <?php } ?><?php if($_SESSION["DISABLEDFLG_" .$idx] == 1){?> disabled <?php } ?>>
							</td>
							<td width="100" align="center" colspan="2">
								<select name="H_StartHour_<?php echo $i?>" <?php if($_SESSION["DISABLEDFLG_" .$idx] == 1){?>style="background-color: #F0F8FF;" <?php } ?>>
									<option value="9999"></option>
									<?php
									$HourUnit = 60 / CON_HOUR_UNIT;	//１時間を割る
									$EndTime = (24 - CON_HOUR_START) * $HourUnit;	//24時までの数を算出
									for ($ii = 0; $ii <= $EndTime; $ii++) {
										$HourBase = date("H:i", strtotime("+". $ii * CON_HOUR_UNIT ." minute",mktime(CON_HOUR_START, 0, 0, 1, 1, 2000)));
										if ($_SESSION["StartTime_" . $idx] == $HourBase){
											echo "<option value=" . $HourBase . " SELECTED>" . $HourBase ;
										}else{
											if($_SESSION["DISABLEDFLG_" .$idx] == 1){
												echo "<option value=" . $HourBase . " disabled>" . $HourBase ;
											}else{
												echo "<option value=" . $HourBase . ">" . $HourBase ;
											}
										}
									}
									?>
								</select>
							</td>
							<td width="250" align="center" colspan="2">
								<select name="H_JissekiHourSel_<?php echo $i?>" <?php if($_SESSION["DISABLEDFLG_" .$idx] == 1){?>style="background-color: #F0F8FF;" <?php } ?>>
									<?php for ($jh = CON_JISSEKI_HOUR_START; $jh <= CON_JISSEKI_HOUR_END; $jh++){ 
										if ($_SESSION["Hours_" . $idx] == $jh) {
											print "<option value=" . $jh . " SELECTED>" . $jh ."</option>";
										}else{
											if($_SESSION["DISABLEDFLG_" .$idx] == 1){
												print "<option value=" . $jh . " disabled>" . $jh ."</option>";
											}else{
												print "<option value=" . $jh . ">" . $jh ."</option>";
											}
										}
									} ?>
								</select>
								:
								<?php if($_SESSION["Tenyu_" .$idx]==0){ ?>
									<select name="H_JissekiMinSel_<?php echo $i?>" <?php if($_SESSION["DISABLEDFLG_" .$idx] == 1){?>style="background-color: #F0F8FF;" <?php } ?>>
										<option value="0">00</option>
										<?php for ($jmi = 1; $jmi <= 5; $jmi++){ 
											$M_Date = $_SESSION["Minutes_" . $idx]/10;
											if ($M_Date == $jmi) {
												print "<option value=" . $jmi . " SELECTED>" . $jmi * 10 ."</option>";
											}else{
												if($_SESSION["DISABLEDFLG_" .$idx] == 1){
													print "<option value=" . $jmi . " disabled>" . $jmi * 10 ."</option>";
												}else{
													print "<option value=" . $jmi . ">" . $jmi * 10 ."</option>";
												}
											}
										} ?>
									</select>
									<input type="button" id="tenyuryoku_<?php echo $i?>" name="tenyuryoku_<?php echo $i?>" onClick="sbmfnc(this,'');" style="cursor: pointer" value="手"  <?php if($_SESSION["HoukokuFL"] == 1){ ?> disabled <?php } ?><?php if($_SESSION["DISABLEDFLG_" .$i] == 1){?> disabled <?php } ?>/>
								<?php }else{ ?>
									<input type="text" size="5" maxlength="2" name="H_JissekiMinTe_<?php echo $i?>" value="<?php echo $_SESSION["JissekiMinTe_" . $idx] ?>" onkeyup="checkText(this)" <?php if($_SESSION["DISABLEDFLG_" .$i] == 1){?> readonly style="background-color: #F0F8FF;" <?php } ?>>
								<?php } ?>
							</td>
							<td width="80" align="center">
								<select name="H_JissekiKubun_<?php echo $i?>" <?php if($_SESSION["DISABLEDFLG_" .$idx] == 1){?>style="background-color: #F0F8FF;" <?php } ?>>
									<option value="9" <?php if($_SESSION["Kubun_" . $idx] == 9){ ?> SELECTED <?php }elseif($_SESSION["DISABLEDFLG_" .$idx] == 1){ ?>disabled<?php } ?>></option>
									<option value="1" <?php if($_SESSION["Kubun_" . $idx] == 1){ ?> SELECTED <?php }elseif($_SESSION["DISABLEDFLG_" .$idx] == 1){ ?>disabled<?php } ?>>指導</option>
									<option value="2" <?php if($_SESSION["Kubun_" . $idx] == 2){ ?> SELECTED <?php }elseif($_SESSION["DISABLEDFLG_" .$idx] == 1){ ?>disabled<?php } ?>>体験</option>
									<option value="11" <?php if($_SESSION["Kubun_" . $idx] == 11){ ?> SELECTED <?php }elseif($_SESSION["DISABLEDFLG_" .$idx] == 1){ ?>disabled<?php } ?>>オンライン指導</option>
									<option value="3" <?php if($_SESSION["Kubun_" . $idx] == 3){ ?> SELECTED <?php }elseif($_SESSION["DISABLEDFLG_" .$idx] == 1){ ?>disabled<?php } ?>>研修</option>
									<option value="5" <?php if($_SESSION["Kubun_" . $idx] == 5){ ?> SELECTED <?php }elseif($_SESSION["DISABLEDFLG_" .$idx] == 1){ ?>disabled<?php } ?>>振替</option>
									<option value="6" <?php if($_SESSION["Kubun_" . $idx] == 6){ ?> SELECTED <?php }elseif($_SESSION["DISABLEDFLG_" .$idx] == 1){ ?>disabled<?php } ?>>追加</option>
									<option value="7" <?php if($_SESSION["Kubun_" . $idx] == 7){ ?> SELECTED <?php }elseif($_SESSION["DISABLEDFLG_" .$idx] == 1){ ?>disabled<?php } ?>>キャンセル</option>
									<option value="8" <?php if($_SESSION["Kubun_" . $idx] == 8){ ?> SELECTED <?php }elseif($_SESSION["DISABLEDFLG_" .$idx] == 1){ ?>disabled<?php } ?>>カウンセリング</option>
									<option value="10" <?php if($_SESSION["Kubun_" . $idx] == 10){ ?> SELECTED <?php }elseif($_SESSION["DISABLEDFLG_" .$idx] == 1){ ?>disabled<?php } ?>>営業訪問</option>
									<option value="4" <?php if($_SESSION["Kubun_" . $idx] == 4){ ?> SELECTED <?php }elseif($_SESSION["DISABLEDFLG_" .$idx] == 1){ ?>disabled<?php } ?>>その他</option>
								</select>
							</td>
							<td width="400" align="left">
								<textarea name="H_JissekiNaiyo_<?php echo $i?>" cols="60" rows="5"<?php if($_SESSION["DISABLEDFLG_" .$i] == 1){?> readonly style="background-color: #F0F8FF;" <?php } ?>><?php echo $_SESSION["Naiyo_" . $idx] ?></textarea>
							</td>
						</tr>
				<?php 
					$HyoujiCnt++;
					}
				}
				if($HyoujiCnt==0){
				?>	
						<tr align="center">
							<td colspan=8><font size=5><BR>報告書の登録はありません。<BR><BR></font></td>
						</tr>
				<?php } ?>
						<tr>
							<td width="100" align="center" bgcolor="#c0c0c0" colspan="2">合　計<BR>
							<td width="50" align="center" bgcolor="#c0c0c0">回数</td>
							<td width="50" align="center"><B><?php echo $_SESSION["KaisuSum"] ?></B></td>
							<td width="90" align="center" bgcolor="#c0c0c0">時間</td>
							<td width="90" align="center"><B><?php echo $_SESSION["JissekiHSum"]?>:<?php echo $_SESSION["JissekiMSum"]?></B></td>
							<td width="100" align="center" bgcolor="#c0c0c0">過不足時間</td>
							<td width="100" align="left">
								<select name="H_ZanPM">
									<option value="1" <?php if($_SESSION["ZanPM"] == 1){ ?> SELECTED <?php } ?>>＋</option>
									<option value="2" <?php if($_SESSION["ZanPM"] == 2){ ?> SELECTED <?php } ?>>－</option>
								</select>
								<select name="H_ZanHoursH">
									<option value="99"></option>
									<?php for ($jh = CON_JISSEKI_HOUR_START; $jh <= CON_JISSEKI_HOUR_END; $jh++){ 
										if ($_SESSION["ZanHoursH"] == $jh) {
											print "<option value=" . $jh . " SELECTED>" . $jh ."</option>";
										}else{
											print "<option value=" . $jh . ">" . $jh ."</option>";
										}
									} ?>
								</select>
								:
								<select name="H_ZanHoursM">
									<option value="99"></option>
									<option value="0" <?php if($_SESSION["ZanHoursM"]=="0"){?> SELECTED <?php }?>>00</option>
									<?php for ($jmi = 1; $jmi <= 5; $jmi++){ 
										$M_Date = $_SESSION["ZanHoursM"]/10;
										if ($M_Date == $jmi) {
											print "<option value=" . $jmi . " SELECTED>" . $jmi * 10 ."</option>";
										}else{
											print "<option value=" . $jmi . ">" . $jmi * 10 ."</option>";
										}
									} ?>
								</select>
							</td>
						</tr>
			</table>
			<table border="0" width="100%">
				<tr align="center">
					<td width="100" align="center">
						<input type="button" id="clear" name="clear" onClick="sbmfnc(this,'');" style="cursor: pointer" value="入力内容をクリア"  <?php if($_SESSION["HoukokuFL"] == 1){ ?> disabled <?php } ?>/>
						<input type="button" id="update" name="update" onClick="sbmfnc(this,'');" style="cursor: pointer" value="登録" <?php if($_SESSION["HoukokuFL"] == 1){ ?> disabled <?php } ?> />
					</td>
				</tr>
			</table>
			<BR>
			<table border="1" width="800">
				<tr>
					<td width="300" align="center" bgcolor="#c0c0c0" colspan="2">交通費（ご家庭分）</td>
					<td width="300" align="center" bgcolor="#c0c0c0" colspan="2">交通費（その他）の内容</td>
				</tr>
				<tr>
					<td width="500" align="left" colspan="2">
						<input type="text" size="8" maxlength="7" name="H_Koutuhi1" value="<?php echo $_SESSION["Koutuhi1"] ?>" onkeyup="checkText(this)">
						×	
						<input type="text" size="8" maxlength="7" name="H_Kaisu1" onblur="OnKeySum();" value="<?php echo $_SESSION["Kaisu1"] ?>" onkeyup="checkText(this)">
						=<input type="text" size="8" maxlength="7" name="H_MeisaiSum1" value="<?php echo $_SESSION["MeisaiSum1"] ?>" onkeyup="checkText(this)">
						<BR>
						<input type="text" size="8" maxlength="7" name="H_Koutuhi2" value="<?php echo $_SESSION["Koutuhi2"] ?>" onkeyup="checkText(this)">
						×	
						<input type="text" size="8" maxlength="7" name="H_Kaisu2" onblur="OnKeySum();" value="<?php echo $_SESSION["Kaisu2"] ?>" onkeyup="checkText(this)">
						=<input type="text" size="8" maxlength="7" name="H_MeisaiSum2" value="<?php echo $_SESSION["MeisaiSum2"] ?>" onkeyup="checkText(this)">
						<BR>
						<input type="text" size="8" maxlength="7" name="H_Koutuhi3" value="<?php echo $_SESSION["Koutuhi3"] ?>" onkeyup="checkText(this)">
						×	
						<input type="text" size="8" maxlength="7" name="H_Kaisu3" onblur="OnKeySum();" value="<?php echo $_SESSION["Kaisu3"] ?>" onkeyup="checkText(this)">
						=<input type="text" size="8" maxlength="7" name="H_MeisaiSum3" value="<?php echo $_SESSION["MeisaiSum3"] ?>" onkeyup="checkText(this)">
						<BR>
						<input type="text" size="8" maxlength="7" name="H_Koutuhi4" value="<?php echo $_SESSION["Koutuhi4"] ?>" onkeyup="checkText(this)">
						×	
						<input type="text" size="8" maxlength="7" name="H_Kaisu4" onblur="OnKeySum();" value="<?php echo $_SESSION["Kaisu4"] ?>" onkeyup="checkText(this)">
						=<input type="text" size="8" maxlength="7" name="H_MeisaiSum4" value="<?php echo $_SESSION["MeisaiSum4"] ?>" onkeyup="checkText(this)">
						<BR>
						<input type="text" size="8" maxlength="7" name="H_Koutuhi5" value="<?php echo $_SESSION["Koutuhi5"] ?>" onkeyup="checkText(this)">
						×	
						<input type="text" size="8" maxlength="7" name="H_Kaisu5" onblur="OnKeySum();" value="<?php echo $_SESSION["Kaisu5"] ?>" onkeyup="checkText(this)">
						=<input type="text" size="8" maxlength="7" name="H_MeisaiSum5" value="<?php echo $_SESSION["MeisaiSum5"] ?>" onkeyup="checkText(this)">
					</td>
					<td width="300" align="left" rowspan="4"><textarea name="H_SonotaBiko" cols="50" rows="13"><?php echo $_SESSION["SonotaBiko"] ?></textarea></td>
				</tr>
				<tr>
					<td width="150" align="center" bgcolor="#c0c0c0">交通費合計</td>
					<td width="150" align="left">
						<input type="text" size="8" maxlength="7" name="H_KoutuhiSum" value="<?php echo $_SESSION["KoutuhiSum"] ?>" onkeyup="checkText(this)">
					</td>
				</tr>
				<tr>
					<td width="150" align="center" bgcolor="#c0c0c0">交通費（その他）</td>
					<td width="150" align="left"><input type="text" size="20" maxlength="7" name="H_SonotaKoutuhi" value="<?php echo $_SESSION["SonotaKoutuhi"] ?>" onkeyup="checkText(this)"></td>
				</tr>
				<tr>
					<td width="150" align="center" bgcolor="#c0c0c0">ご家庭受領</td>
					<td width="150" align="left">
						<input type="radio" name="H_Seisan" value="1" <?php if($_SESSION["Seisan"]==1){?> checked <?php } ?>>済
						<input type="radio" name="H_Seisan" value="2" <?php if($_SESSION["Seisan"]==2){?> checked <?php } ?>>未
					</td>
				</tr>

			</table>
			<BR>
			<table border="1">
				<tr>
					<td width="700" align="center" bgcolor="#c0c0c0" colspan="6">生徒のようす</td>
				</tr>
				<tr>
					<td width="700" align="center" colspan="6"><textarea name="H_Yousu" cols="120" rows="5"><?php echo $_SESSION["Yousu"] ?></textarea></td>
				</tr>
				<tr>
					<td width="50" align="center" bgcolor="#c0c0c0">宿題</td>
					<td width="100" align="left">
						<input type="radio" name="H_Shukudai" value="1" <?php if($_SESSION["Shukudai"]==1){?> checked <?php } ?>>良
						<input type="radio" name="H_Shukudai" value="2" <?php if($_SESSION["Shukudai"]==2){?> checked <?php } ?>>普
						<input type="radio" name="H_Shukudai" value="3" <?php if($_SESSION["Shukudai"]==3){?> checked <?php } ?>>悪
					</td>
					<td width="50" align="center" bgcolor="#c0c0c0">気力</td>
					<td width="100" align="left">
						<input type="radio" name="H_Kiryoku" value="1" <?php if($_SESSION["Kiryoku"]==1){?> checked <?php } ?>>良
						<input type="radio" name="H_Kiryoku" value="2" <?php if($_SESSION["Kiryoku"]==2){?> checked <?php } ?>>普
						<input type="radio" name="H_Kiryoku" value="3" <?php if($_SESSION["Kiryoku"]==3){?> checked <?php } ?>>悪
					</td>
					<td width="50" align="center" bgcolor="#c0c0c0">学力向上</td>
					<td width="100" align="left">
						<input type="radio" name="H_Gakuryoku" value="1" <?php if($_SESSION["Gakuryoku"]==1){?> checked <?php } ?>>良
						<input type="radio" name="H_Gakuryoku" value="2" <?php if($_SESSION["Gakuryoku"]==2){?> checked <?php } ?>>普
						<input type="radio" name="H_Gakuryoku" value="3" <?php if($_SESSION["Gakuryoku"]==3){?> checked <?php } ?>>悪
					</td>
				</tr>
			</table>
			<table border="1">
				<tr>
					<td width="700" align="center" bgcolor="#c0c0c0" colspan="4">回顧・反省</td>
				</tr>
				<tr>
					<td width="700" align="center" colspan="4"><textarea name="H_Hansei" cols="120" rows="5"><?php echo $_SESSION["Hansei"] ?></textarea></td>
				</tr>
				<tr>
					<td width="200" align="center" bgcolor="#c0c0c0">計画に基づいた指導をしているか？</td>
					<td width="100" align="left">
						<input type="radio" name="H_Keikaku" value="1" <?php if($_SESSION["Keikaku"]==1){?> checked <?php } ?>>はい
						<input type="radio" name="H_Keikaku" value="2" <?php if($_SESSION["Keikaku"]==2){?> checked <?php } ?>>いいえ
					</td>
					<td width="200" align="center" bgcolor="#c0c0c0">生徒とうまくいっているか？</td>
					<td width="100" align="left">
						<input type="radio" name="H_SeitoKankei" value="1" <?php if($_SESSION["SeitoKankei"]==1){?> checked <?php } ?>>はい
						<input type="radio" name="H_SeitoKankei" value="2" <?php if($_SESSION["SeitoKankei"]==2){?> checked <?php } ?>>いいえ
					</td>
			</table>
			<BR>
			<table border="1">
				<tr>
					<td width="80" align="center" bgcolor="#c0c0c0">英語</td>
					<td width="80" align="center" bgcolor="#c0c0c0">数学</td>
					<td width="80" align="center" bgcolor="#c0c0c0">国語</td>
					<td width="80" align="center" bgcolor="#c0c0c0">理科</td>
					<td width="80" align="center" bgcolor="#c0c0c0">社会</td>
					<td width="80" align="center" bgcolor="#c0c0c0">音楽</td>
					<td width="80" align="center" bgcolor="#c0c0c0">美術</td>
					<td width="80" align="center" bgcolor="#c0c0c0">保体</td>
					<td width="80" align="center" bgcolor="#c0c0c0">技術</td>
					<td width="80" align="center" bgcolor="#c0c0c0">家庭</td>			
				</tr>
				<tr>
					<td width="80" align="center"><input type="text" size="5" maxlength="5" name="H_Seiseki1" value="<?php echo $_SESSION["Seiseki1"] ?>"></td>
					<td width="80" align="center"><input type="text" size="5" maxlength="5" name="H_Seiseki2" value="<?php echo $_SESSION["Seiseki2"] ?>"></td>
					<td width="80" align="center"><input type="text" size="5" maxlength="5" name="H_Seiseki3" value="<?php echo $_SESSION["Seiseki3"] ?>"></td>
					<td width="80" align="center"><input type="text" size="5" maxlength="5" name="H_Seiseki4" value="<?php echo $_SESSION["Seiseki4"] ?>"></td>
					<td width="80" align="center"><input type="text" size="5" maxlength="5" name="H_Seiseki5" value="<?php echo $_SESSION["Seiseki5"] ?>"></td>
					<td width="80" align="center"><input type="text" size="5" maxlength="5" name="H_Seiseki6" value="<?php echo $_SESSION["Seiseki6"] ?>"></td>
					<td width="80" align="center"><input type="text" size="5" maxlength="5" name="H_Seiseki7" value="<?php echo $_SESSION["Seiseki7"] ?>"></td>
					<td width="80" align="center"><input type="text" size="5" maxlength="5" name="H_Seiseki8" value="<?php echo $_SESSION["Seiseki8"] ?>"></td>
					<td width="80" align="center"><input type="text" size="5" maxlength="5" name="H_Seiseki9" value="<?php echo $_SESSION["Seiseki9"] ?>"></td>
					<td width="80" align="center"><input type="text" size="5" maxlength="5" name="H_Seiseki10" value="<?php echo $_SESSION["Seiseki10"] ?>"></td>
				</tr>
			</table>
			<BR>
			<table border="0" width="100%">
				<tr>
					<td width="100" align="center">
						<input type="button" id="update" name="update" onClick="sbmfnc(this,'');" style="cursor: pointer" value="登録"  <?php if($_SESSION["HoukokuFL"] == 1){ ?> disabled <?php } ?> />
						<input type="button" id="Houkoku" name="Houkoku" onClick="sbmfnc(this,'');" style="cursor: pointer" value="報告"  <?php if($_SESSION["HoukokuFL"] == 1){ ?> disabled <?php } ?> />
						<?php if($_SESSION["shikaku"] == 1){?>
							<input type="button" id="HoukokuKaijyo" name="HoukokuKaijyo" onClick="sbmfnc(this,'');" style="cursor: pointer" value="報告解除"  />
						<?php } ?>
					</td>
				</tr>
			</table>
			<BR>
			<BR>
			<BR>
			<BR>
			<table border="0" width="100%">
				<tr align="right">
					<td width="100" align="center">Copyrightc TOS Tutorial Center All Rights Reserved. since1982</td>
				</tr>
			</table>
		</div>
	</div>
<?php }else{ ?>
			<table border="0" width="800">
				<tr>
					<td width="100" align="right">
						<input type="hidden" id="submitter" name="submitter" value="" />
						<input type="button" id="back" name="back" onClick="sbmfnc(this,'');" style="cursor: pointer" value="入力画面へ戻る" />
					</td>
				</tr>
			</table>
			<BR>
			<table border="0" width="800">
				<tr>
					<td align="center">
						<font size="5">　　　<?php echo $_SESSION["TodayYSel"] ?>年<?php echo $_SESSION["TodayMSel"] ?>月分　指導報告書</font>
					</td>
					<td align="right">
						<table class="print01">
							<tr>
								<td align="center">ご家庭</td>
							</tr>
							<tr>
								<td align="center"><BR>印<BR><BR></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<BR>
			<table class="print01">
				<tr>
					<td><B>生徒ID</B></td>
					<td><B>生徒氏名</B></td>
					<td><B>学年</B></td>
				</tr>
				<tr>
					<td width="100" height="50" align="center"><?php echo $_SESSION["StudentID"] ?></td>
					<td width="500" align="left"><?php echo $_SESSION["SName1"] ?></td>
					<td width="198">　</td>
				</tr>
			</table>
			<table class="print01">
				<tr>
					<td><B>教師ID</B></td>
					<td><B>教師氏名</B></td>
					<td><B>住所</B></td>
				</tr>
				<tr>
					<td width="100" height="50" align="center"><?php echo $TeacherID ?></td>
					<td width="500" align="left"><?php echo $TName1 ?></td>
					<td width="198">　</td>
				</tr>
			</table>
			<BR>
			<table class="print01">
				<tr>
					<td align="center"><B>日</td>
					<td align="center"><B>開始時間</B></td>
					<td align="center"><B>時間</td>
					<td align="center"><B>区分</td>
					<td align="center"><B>指導内容</B></td>
				</tr>
				<?php for ($idx=1; $idx <= $_SESSION["TodayM_end"]; $idx++){ ?>
					<?php if($_SESSION["INPUTFLG_" .$idx] == 1){ ?>
						<?php list ($YoubiNo, $Youbi) = WeekNoGet($_SESSION["TodayYSel"],$_SESSION["TodayMSel"],$idx); ?>
						<?php 
							
							//GetCodeData(1,$_SESSION["Kubun_" .$idx],"指導区分",pWhere,pOrder);
						?>
						<tr>
							<td width="80" height="30" align="center"><?php echo $_SESSION["TodayMSel"] ?>/<?php echo $_SESSION["Day_" .$idx] ?>（<?php echo $Youbi ?>）</td>
							<td width="80" align="center"><?php echo $_SESSION["StartTime_" .$idx] ?></td>
							<td width="80" align="center"><?php echo $_SESSION["Hours_" .$idx] ?>：<?php echo $_SESSION["Minutes_" .$idx] ?></td>
							<td width="80" align="center">
								<?php
									switch ($_SESSION["Kubun_" .$idx]){
										case 1:	
											$PKubun = "指導";
										        break;				
										case 2:	
											$PKubun = "体験";
										        break;				
										case 3:	
											$PKubun = "研修";
										        break;				
										case 4:	
											$PKubun = "その他";
										        break;				
										case 5:	
											$PKubun = "振替";
										        break;				
										case 6:	
											$PKubun = "追加";
										        break;				
										case 7:	
											$PKubun = "キャンセル";
										        break;				
										case 8:	
											$PKubun = "カウンセリング";
										        break;				
										case 10:	
											$PKubun = "営業訪問";
										        break;				
										case 11:	
											$PKubun = "オンライン";
										        break;				
                                    }
									echo $PKubun;
								?>
							</td>
							<td width="450" align="left"><?php echo $_SESSION["NaiyoPrint_" .$idx] ?></td>
						</tr>
					<?php } ?>
				<?php } ?>
				<tr>
					<td height="30">　</td>
					<td>　</td>
					<td>　</td>
					<td>　</td>
					<td>　</td>
				</tr>
				<tr>
					<td height="30">　</td>
					<td>　</td>
					<td>　</td>
					<td>　</td>
					<td>　</td>
				</tr>
				<tr>
					<td height="30" align="center"><B>合計</B></td>
					<td align="center"><?php echo $_SESSION["KaisuSum"] ?>回</td>
					<td align="center"><?php echo $_SESSION["JissekiHSum"]?>:<?php echo $_SESSION["JissekiMSum"]?></td>
					<td align="center"><B>残時間</B></td>
					<td align="left"><?php if($_SESSION["ZanPM"]==1){ ?>+<?php }else{ ?>-<?php } ?>
							<?php if($_SESSION["ZanHoursH"]=="99"){?>0<?php }else{ ?>
							<?php echo $_SESSION["ZanHoursH"] ?><?php } ?>
							:
							<?php if($_SESSION["ZanHoursM"]=="990"){?>0<?php }else{ ?>
							<?php echo $_SESSION["ZanHoursM"] ?><?php } ?>
					</td>
				</tr>
			</table>
			<BR>
			<table class="print01">
				<tr>
					<td width="400" align="center"><B>交通費（ご家庭分）</B></td>
					<td width="399" align="center"><B>その他</B></td>
				</tr>
				<tr>
					<td>
						<?php echo number_format((int)$_SESSION["Koutuhi1"]) ?>×<?php echo number_format((int)$_SESSION["Kaisu1"]) ?> = <?php echo number_format((int)$_SESSION["MeisaiSum1"]) ?>
						<BR>
						<?php echo number_format((int)$_SESSION["Koutuhi2"]) ?>×<?php echo number_format((int)$_SESSION["Kaisu2"]) ?> = <?php echo number_format((int)$_SESSION["MeisaiSum2"]) ?>
						<BR>
						<?php echo number_format((int)$_SESSION["Koutuhi3"]) ?>×<?php echo number_format((int)$_SESSION["Kaisu3"]) ?> = <?php echo number_format((int)$_SESSION["MeisaiSum3"]) ?>
						<BR>
						<?php echo number_format((int)$_SESSION["Koutuhi4"]) ?>×<?php echo number_format((int)$_SESSION["Kaisu4"]) ?> = <?php echo number_format((int)$_SESSION["MeisaiSum4"]) ?>
						<BR>
						<?php echo number_format((int)$_SESSION["Koutuhi5"]) ?>×<?php echo number_format((int)$_SESSION["Kaisu5"]) ?> = <?php echo number_format((int)$_SESSION["MeisaiSum5"]) ?>
						<BR>
						合計　<?php echo number_format((int)$_SESSION["KoutuhiSum"]) ?>円
					</td>
					<td>
						<?php if($_SESSION["shikaku"]!=0){ ?>
							その他　　<?php echo $_SESSION["SonotaKoutuhi"] ?>円<BR>
							<BR>
							<その他内容>
							<BR>
								<?php echo $_SESSION["SonotaBiko"] ?>
							<BR><BR>
							ご家庭受領：<?php if($_SESSION["Seisan"]==1){?>済<?php }else{ ?>未<?php } ?>
						<?php } ?>
					</td>
				</tr>
			</table>
			<BR>
			<table class="print01">
				<tr>
					<td width="800" align="center"><B>生徒の様子</B></td>
				</tr>
				<tr>
					<td width="800" height="100"><?php echo $_SESSION["YousuPrint"] ?></td>
				</tr>
				<tr>
					<td>
						【宿題】
						<?php if($_SESSION["Shukudai"]==1){?>　良　<?php }elseif($_SESSION["Shukudai"]==2){ ?>　普　<?php }elseif($_SESSION["Shukudai"]==3){ ?>　悪　<?php } ?>
						【気力】
						<?php if($_SESSION["Kiryoku"]==1){?>　良　<?php }elseif($_SESSION["Kiryoku"]==2){ ?>　普　<?php }elseif($_SESSION["Kiryoku"]==3){ ?>　悪　<?php } ?>
						【学力向上】
						<?php if($_SESSION["Gakuryoku"]==1){?>　良　<?php }elseif($_SESSION["Gakuryoku"]==2){ ?>　普　<?php }elseif($_SESSION["Gakuryoku"]==3){ ?>　悪　<?php } ?>
					</td>
				</tr>
			</table>
			<table class="print01">
				<tr>
					<td width="800" align="center"><B>回顧・反省</B></td>
				</tr>
				<tr>
					<td width="800" height="100"><?php echo $_SESSION["HanseiPrint"] ?></td>
				</tr>
				<tr>
					<td>
						計画に基づいた指導をしているか？　
							<?php if($_SESSION["Keikaku"]==1){?>　はい　<?php }elseif($_SESSION["Keikaku"]==2){ ?>　いいえ　<?php } ?>
						生徒とうまくいっているか？
							<?php if($_SESSION["SeitoKankei"]==1){?>　はい　<?php }elseif($_SESSION["SeitoKankei"]==2){ ?>　いいえ　<?php } ?>
					</td>
				</tr>
			</table>
			<BR>
			<table class="print01">
				<tr>
					<td width="80" align="center"><B>英語</B></td>
					<td width="80" align="center"><B>数学</B></td>
					<td width="80" align="center"><B>国語</B></td>
					<td width="80" align="center"><B>理科</B></td>
					<td width="80" align="center"><B>社会</B></td>
					<td width="80" align="center"><B>音楽</B></td>
					<td width="80" align="center"><B>美術</B></td>
					<td width="80" align="center"><B>保体</B></td>
					<td width="80" align="center"><B>技術</B></td>
					<td width="80" align="center"><B>家庭</B></td>
				</tr>
				<tr>
					<td align="center"><?php echo $_SESSION["Seiseki1"] ?></td>
					<td align="center"><?php echo $_SESSION["Seiseki2"] ?></td>
					<td align="center"><?php echo $_SESSION["Seiseki3"] ?></td>
					<td align="center"><?php echo $_SESSION["Seiseki4"] ?></td>
					<td align="center"><?php echo $_SESSION["Seiseki5"] ?></td>
					<td align="center"><?php echo $_SESSION["Seiseki6"] ?></td>
					<td align="center"><?php echo $_SESSION["Seiseki7"] ?></td>
					<td align="center"><?php echo $_SESSION["Seiseki8"] ?></td>
					<td align="center"><?php echo $_SESSION["Seiseki9"] ?></td>
					<td align="center"><?php echo $_SESSION["Seiseki10"] ?></td>
				</tr>
			</table>
			<BR>
			<table class="print01">
				<tr>
					<td width="820" align="center"><B>ご家庭から</B></td>
				</tr>
				<tr>
					<td width="820" height="100" align="center">　</td>
				</tr>
			</table>
<?php } ?>
</form>
</body>
</CENTER>
</html>