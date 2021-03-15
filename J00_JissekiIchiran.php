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

	$TodayYSel = $TodayY;	//選択年
	$TodayMSel = $TodayM;	//選択月


	$dt2 = new DateTime();
	$dt2->setDate($TodayYSel, $TodayMSel, 1);
	$TodayM_end = $dt2->format('Y/m/t');
	$TodayM_end = substr($TodayM_end, -2);
	$_SESSION["Today"]=$Today;
	$_SESSION["TodayM_end"]=$TodayM_end;
	$_SESSION["TodayYSel"]=$TodayYSel;
	$_SESSION["TodayMSel"]=$TodayMSel;

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
//	 	ModoruShori($_SESSION["J00_JissekiIchiran_RPID"]);
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
		 	ModoruShori($_SESSION["J00_JissekiIchiran_RPID"]);
			exit;
		}
	}
	if(isset($_POST['J_Orgtype'])){
		$_SESSION["ShoriID"]="OrgtypeSEL";
	}

	//検索ボタン押下
	if(isset($_POST['kensaku'])){
		$_SESSION["ShoriID"]="KENSAKU";
	}
	//検索ボタン押下
	if(isset($_POST['clear'])){
		$_SESSION["ShoriID"]="CLEAR";
	}
	//表示切替ボタン押下
	if(isset($_POST['change'])){
		$_SESSION["ShoriID"]="CHANGE";
	}

	// 教師選択処理
	for ($m = 0; $m < $_SESSION["DateCount"]; $m++){
		if(isset($_POST["No_" . $m])){
			list ($TName1, $TName2) = GetTAtena($_POST['TeacherID_' . $m]);
			 $_SESSION["TName1"]=$TName1;
			 $_SESSION["TName2"]=$TName2;
			$Location = "H03_Report1.php?MODE=KENS" . "&RPID=J00_JissekiIchiran&TID=" . $_POST['TeacherID_' . $m] ."&STID=" . $_POST['StudentID_' . $m] . "&ATENASEQ=" . $_POST['StudentAtenaSeq_' . $m]  . "&SEQ=" . $_POST['StudentSeq_' . $m];
		 	header("Location:" . $Location);
			exit;
		}
	}

	for ($m = 0; $m < $_SESSION["DateCount"]; $m++){
		if(isset($_POST["J00_StudentID_" . $m])){
//			$Location = "H03_Report1.php?MODE=KENS" . "&RPID=J00_JissekiIchiran&TID=" . $_POST['TeacherID_' . $m] ."&STID=" . $_POST['StudentID_' . $m] . "&ATENASEQ=" . $_POST['StudentAtenaSeq_' . $m]  . "&SEQ=" . $_POST['StudentSeq_' . $m];
//		 	header("Location:" . $Location);
//			exit;
			$Location = "S03_index.php?MODE=UPD&RPID=J00_JissekiIchiran&KEY1=" . $_POST['StudentID_' . $m] . "&KUBUN=1" . "&SEQ=" .$_POST['StudentAtenaSeq_' . $m];
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
		$TeacherID = $_SESSION["TeacherID"];
		$user_name = $_SESSION["user_name"];
		$shikaku = (int) $_SESSION["shikaku"];
		$TName1 = $_SESSION["TName1"];
		$TName2 = $_SESSION["TName2"];

		//URLパラメータ
		if(isset($_GET['RPID'])) {
			$_SESSION["J00_JissekiIchiran_RPID"] = $_GET['RPID'];
		      	//print($_SESSION["J00_JissekiIchiran_RPID"] ."<BR>");
		}
		if(isset($_GET['MODE'])) {
			if($_GET['MODE'] != "Modoru"){
				$_SESSION["J00_JissekiIchiran_MODE"] = $_GET['MODE'];
				$_SESSION["ShoriID"]=$_GET['MODE'];
			      	//print($_SESSION["J00_JissekiIchiran_MODE"] . "<BR>");
			}
		}
		switch ($_SESSION["ShoriID"]){
			case 'JKS':
				$_SESSION["J_KyuyoID"]="";
				$_SESSION["J_TeacherID"]="";
				$_SESSION["J_TeacherName"]="";
				$_SESSION["J_StudentID"]="";
				$_SESSION["J_StudentName"]="";
				$_SESSION["J_gread"]="";
				$_SESSION["J_updateflg"]=2;
				$_SESSION["J_StartDay1"]="";
				$_SESSION["J_StartDay2"]="";
				$_SESSION["J_EndDay1"]="";
				$_SESSION["J_EndDay2"]="";
				$_SESSION["J_Orgtype"]="";
				$_SESSION["J_course"]="";
				$_SESSION["J_course2"]="";
				$_SESSION["J_oldflg"]="";
				$_SESSION["J_greadflg"]="";

				$_SESSION["J_ShuryoBun"]=2;
				$_SESSION["J_HokokuUmu1"]=1;
				$_SESSION["J_HokokuUmu2"]=1;
				$_SESSION["J_ShoninUmu1"]=1;
				$_SESSION["J_ShoninUmu2"]=1;
				$_SESSION["J_Sort1"]=1;
				$_SESSION["J_Sort2"]=7;
				$_SESSION["J_Sort3"]=0;
				$_SESSION["J_Sort4"]=0;
				$_SESSION["J_Sort5"]=0;
				$_SESSION["J_SortType1"]=0;
				$_SESSION["J_SortType2"]=0;
				$_SESSION["J_SortType3"]=0;
				$_SESSION["J_SortType4"]=0;
				$_SESSION["J_SortType5"]=0;
				$_SESSION["Kanihyoji"]=0;
				$_SESSION["J_Naiyo"]="";

				//開始年
				$_SESSION["TodayY_Start"]=$TodayYSel-1;
				$_SESSION["TodayY_End"] = $TodayYSel;
				$_SESSION["H_TodayY_End"] = $TodayYSel;
				 
				//開始月
				if($_SESSION["TodayMSel"] == 12){
					$_SESSION["TodayM_Start"] = 1;
				}else{
					$_SESSION["TodayM_Start"] = $TodayM + 1;
				}
				if($_SESSION["TodayMSel"] == 12){
					$_SESSION["TodayM_End"] = 12;
					$_SESSION["H_TodayM_End"] = 12;
				}else{
					$_SESSION["TodayM_End"]=$TodayM;
					$_SESSION["H_TodayM_End"]=$TodayM;
				}


				//月数
				$_SESSION["MCount"]=12;

				$CodeData = array();
				$CodeData = GetCodeData("学年","","",1);
				$_SESSION["13CodeData"]=$CodeData;
				$CodeData = array();
				$CodeData = GetCodeData("契約コース","","",1);
				$_SESSION["19CodeData"]=$CodeData;
				$CodeData2 = array();
				$CodeData2 = GetCodeData("契約種別","","",1);
				$_SESSION["20CodeData"]=$CodeData2;
				$CodeData2 = array();
				$CodeData2 = GetCodeData("契約コースその他","","",1);
				$_SESSION["26CodeData"]=$CodeData2;
				$CodeData = array();
				$CodeData = GetCodeData("LD契約コース","","",1);
				$_SESSION["31CodeData"]=$CodeData;

				break;

			case 'KENSAKU': case 'CHANGE':
				$_SESSION["J_KyuyoID"]=$_POST['J_KyuyoID'];
				$_SESSION["J_TeacherID"]=$_POST['J_TeacherID'];
				$_SESSION["J_TeacherName"]=$_POST['J_TeacherName'];
				$_SESSION["J_StudentID"]=$_POST['J_StudentID'];
				$_SESSION["J_StudentName"]=$_POST['J_StudentName'];
				$_SESSION["J_StartDay1"]=$_POST['J_StartDay1'];
				$_SESSION["J_StartDay2"]=$_POST['J_StartDay2'];
				$_SESSION["J_EndDay1"]=$_POST['J_EndDay1'];
				$_SESSION["J_EndDay2"]=$_POST['J_EndDay2'];
				$_SESSION["J_Orgtype"]=$_POST['J_Orgtype'];
				$_SESSION["J_course"]=$_POST['J_course'];
				$_SESSION["J_course2"]=$_POST['J_course2'];
				$_SESSION["J_oldflg"]=$_POST['J_oldflg'];
				$_SESSION["J_greadflg"]=$_POST['J_greadflg'];
				$_SESSION["J_updateflg"]=$_POST['J_updateflg'];

				$_SESSION["J_gread"]=$_POST['J_gread'];
				$_SESSION["J_ShuryoBun"]=$_POST['J_ShuryoBun'];
				$_SESSION["J_JissekiNen1"]=$_POST['J_JissekiNen1'];
				$_SESSION["J_Jissekituki1"]=$_POST['J_Jissekituki1'];
				$_SESSION["J_JissekiNen2"]=$_POST['J_JissekiNen2'];
				$_SESSION["J_Jissekituki2"]=$_POST['J_Jissekituki2'];
				$_SESSION["J_Naiyo"]=$_POST['J_Naiyo'];
				$_SESSION["J_HokokuUmu1"]=$_POST['J_HokokuUmu1'];
				$_SESSION["J_HokokuUmu2"]=$_POST['J_HokokuUmu2'];
				$_SESSION["J_ShoninUmu1"]=$_POST['J_ShoninUmu1'];
				$_SESSION["J_ShoninUmu2"]=$_POST['J_ShoninUmu2'];
				$_SESSION["J_Sort1"]=$_POST['J_Sort1'];
				$_SESSION["J_Sort2"]=$_POST['J_Sort2'];
				$_SESSION["J_Sort3"]=$_POST['J_Sort3'];
				$_SESSION["J_Sort4"]=$_POST['J_Sort4'];
				$_SESSION["J_Sort5"]=$_POST['J_Sort5'];
				$_SESSION["J_SortType1"]=$_POST['J_SortType1'];
				$_SESSION["J_SortType2"]=$_POST['J_SortType2'];
				$_SESSION["J_SortType3"]=$_POST['J_SortType3'];
				$_SESSION["J_SortType4"]=$_POST['J_SortType4'];
				$_SESSION["J_SortType5"]=$_POST['J_SortType5'];
				$_SESSION["Kanihyoji"]=$_POST['Kanihyoji'];

				$_SESSION["TodayY_Start"] = $_POST['J_JissekiNen1'];
				$_SESSION["TodayY_End"] = $_POST['J_JissekiNen2'];
				$_SESSION["TodayM_Start"] = $_POST['J_Jissekituki1'];
				$_SESSION["TodayM_End"] = $_POST['J_Jissekituki2'];
				$_SESSION["H_TodayY_End"] = $_POST['J_HoukokuNen'];
				$_SESSION["H_TodayM_End"] = $_POST['J_Houkokutuki'];

				$HaniStart = $_POST['J_JissekiNen1'] . "/" . str_pad($_POST['J_Jissekituki1'], 2, 0, STR_PAD_LEFT) . "/" . "01";
				$HaniEnd = $_POST['J_JissekiNen2'] . "/" . str_pad($_POST['J_Jissekituki2'], 2, 0, STR_PAD_LEFT) . "/" . "01";

				$date1=strtotime($HaniStart);
				$date2=strtotime($HaniEnd);
				$month1=date("Y",$date1)*12+date("m",$date1);
				$month2=date("Y",$date2)*12+date("m",$date2);

				$diff = $month2 - $month1 + 1;
				$_SESSION["MCount"]=$diff;

				break;

			case 'CLEAR':
				$_SESSION["J_KyuyoID"]="";
				$_SESSION["J_TeacherID"]="";
				$_SESSION["J_TeacherName"]="";
				$_SESSION["J_StudentID"]="";
				$_SESSION["J_StudentName"]="";
				$_SESSION["J_StartDay1"]="";
				$_SESSION["J_StartDay2"]="";
				$_SESSION["J_EndDay1"]="";
				$_SESSION["J_EndDay2"]="";
				$_SESSION["J_Orgtype"]="";
				$_SESSION["J_course"]="";
				$_SESSION["J_course2"]="";
				$_SESSION["J_oldflg"]="";
				$_SESSION["J_greadflg"]="";
				$_SESSION["J_updateflg"]=2;
				$_SESSION["J_gread"]="";
				$_SESSION["J_ShuryoBun"]=2;
				$_SESSION["J_HokokuUmu1"]=1;
				$_SESSION["J_HokokuUmu2"]=1;
				$_SESSION["J_ShoninUmu1"]=1;
				$_SESSION["J_ShoninUmu2"]=1;
				$_SESSION["J_Sort1"]=1;
				$_SESSION["J_Sort2"]=7;
				$_SESSION["J_Sort3"]=0;
				$_SESSION["J_Sort4"]=0;
				$_SESSION["J_Sort5"]=0;
				$_SESSION["J_SortType1"]=0;
				$_SESSION["J_SortType2"]=0;
				$_SESSION["J_SortType3"]=0;
				$_SESSION["J_SortType4"]=0;
				$_SESSION["J_SortType5"]=0;
				$_SESSION["Kanihyoji"]=$_POST['Kanihyoji'];
				$_SESSION["J_Naiyo"]="";

				//開始年
				$_SESSION["TodayY_Start"]=$TodayYSel-1;
				$_SESSION["TodayY_End"] = $TodayYSel;
				$_SESSION["H_TodayY_End"] = $TodayYSel;
				 
				//開始月
				if($_SESSION["TodayMSel"] == 12){
					$_SESSION["TodayM_Start"] = 1;
				}else{
					$_SESSION["TodayM_Start"] = $TodayM + 1;
				}
				if($_SESSION["TodayMSel"] == 12){
					$_SESSION["TodayM_End"] = 12;
					$_SESSION["H_TodayM_End"] = 12;
				}else{
					$_SESSION["TodayM_End"]=$TodayM;
					$_SESSION["H_TodayM_End"]=$TodayM;
				}

				//月数
				$_SESSION["MCount"]=12;

				break;
			case 'OrgtypeSEL':
				$_SESSION["J_Orgtype"] =  $_POST['J_Orgtype'];
				if($_SESSION["J_Orgtype"]=="06"){
					$CodeData = array();
					$CodeData = GetCodeData("LD契約コース","","",1);
					$_SESSION["31CodeData"]=$CodeData;
				}else{
					$CodeData = array();
					$CodeData = GetCodeData("契約コース","","",1);
					$_SESSION["19CodeData"]=$CodeData;
				}
				break;

		}


		// mysqlへの接続
		$mysqli = new mysqli(HOST, USER, PASS);
		if ($mysqli->connect_errno) {
			print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
			exit();
		}

		// データベースの選択
		$mysqli->select_db(DBNAME);
		$mysqli->set_charset("utf8");

		$KensakuFlg = 0;
		$SortFlg = 0;
		// クエリの実行
		$query = "select A.*,B.Name1 as TName1,B.Name2 as TName2,C.Name1 as SName1,C.Name2 as SName2,C.gread,C.oldflg,C.greadflg,C.updateflg from T_TantoShosai as A ";
		$query = $query . "inner join T_AtenaInfo as B on ";
		$query = $query . "A.TeacherID = B.TeacherID ";
		$query = $query . "inner join S_AtenaInfo as C on ";
		$query = $query . "A.StudentID = C.StudentID and ";
		$query = $query . "A.AtenaSeq = C.Seq ";
		
		if(!empty($_SESSION["J_KyuyoID"])){
			if($KensakuFlg ==0){
				$query = $query . "Where A.KyuyoNo='" . $_SESSION["J_KyuyoID"] ."' ";
				$KensakuFlg = 1;
			}else{
				$query = $query . "AND A.KyuyoNo='" . $_SESSION["J_KyuyoID"] . "' ";
			}
		}
		if(!empty($_SESSION["J_TeacherID"])){
			if($KensakuFlg ==0){
				$query = $query . "Where A.TeacherID='" . $_SESSION["J_TeacherID"] ."' ";
				$KensakuFlg = 1;
			}else{
				$query = $query . "AND A.TeacherID='" . $_SESSION["J_TeacherID"] . "' ";
			}
		}
		if(!empty($_SESSION["J_TeacherName"])){
			if($KensakuFlg ==0){
				$query = $query . "Where B.Name1 like '%" . $_SESSION["J_TeacherName"] ."%' ";
				$KensakuFlg = 1;
			}else{
				$query = $query . "AND B.Name1 like '%" . $_SESSION["J_TeacherName"] ."%' ";
			}
		}
		if(!empty($_SESSION["J_StudentID"])){
			if($KensakuFlg ==0){
				$query = $query . "Where A.StudentID='" . $_SESSION["J_StudentID"] ."' ";
				$KensakuFlg = 1;
			}else{
				$query = $query . "AND A.StudentID='" . $_SESSION["J_StudentID"] . "' ";
			}
		}
		if(!empty($_SESSION["J_StudentName"])){
			if($KensakuFlg ==0){
				$query = $query . "Where C.Name1 like '%" . $_SESSION["J_StudentName"] ."%' ";
				$KensakuFlg = 1;
			}else{
				$query = $query . "AND C.Name1 like '%" . $_SESSION["J_StudentName"] ."%' ";
			}
		}
		if($KensakuFlg ==0){
			if(!empty($_SESSION["J_StartDay1"]) && empty($_SESSION["J_StartDay2"])){
				$query = $query . "Where A.StartDay >= '" . $_SESSION["J_StartDay1"] ."'";
				$KensakuFlg = 1;
			}elseif(!empty($_SESSION["J_StartDay1"]) && !empty($_SESSION["J_StartDay2"])){
				$query = $query . "Where A.StartDay Between '" . $_SESSION["J_StartDay1"] . "' and '" . $_SESSION["J_StartDay2"] ."'";
				$KensakuFlg = 1;
			}elseif(empty($_SESSION["J_StartDay1"]) && !empty($_SESSION["J_StartDay2"])){
				$query = $query . "Where A.StartDay <= '" . $_SESSION["J_StartDay2"] ."'";
				$KensakuFlg = 1;
			}else{
			}
		}else{
			if(!empty($_SESSION["J_StartDay1"]) && empty($_SESSION["J_StartDay2"])){
				$query = $query . " AND A.StartDay >= '" . $_SESSION["J_StartDay1"] ."'";
			}elseif(!empty($_SESSION["J_StartDay1"]) && !empty($_SESSION["J_StartDay2"])){
				$query = $query . " AND A.StartDay Between '" . $_SESSION["J_StartDay1"] . "' and '" . $_SESSION["J_StartDay2"] ."'";
			}elseif(empty($_SESSION["J_StartDay1"]) && !empty($_SESSION["J_StartDay2"])){
				$query = $query . " AND A.StartDay <= " . $_SESSION["J_StartDay2"] ."'";
			}else{
			}
		}
		if($KensakuFlg ==0){
			if(!empty($_SESSION["J_EndDay1"]) && empty($_SESSION["J_EndDay2"])){
				$query = $query . "Where A.EndDay >= '" . $_SESSION["J_EndDay1"] ."'";
				$KensakuFlg = 1;
			}elseif(!empty($_SESSION["J_EndDay1"]) && !empty($_SESSION["J_EndDay2"])){
				$query = $query . "Where A.EndDay Between '" . $_SESSION["J_EndDay1"] . "' and '" . $_SESSION["J_EndDay2"] ."'";
				$KensakuFlg = 1;
			}elseif(empty($_SESSION["J_EndDay1"]) && !empty($_SESSION["J_EndDay2"])){
				$query = $query . "Where A.EndDay <= '" . $_SESSION["J_EndDay2"] ."'";
				$KensakuFlg = 1;
			}else{
			}
		}else{
			if(!empty($_SESSION["J_EndDay1"]) && empty($_SESSION["J_EndDay2"])){
				$query = $query . " AND A.EndDay >= '" . $_SESSION["J_EndDay1"] ."'";
			}elseif(!empty($_SESSION["J_EndDay1"]) && !empty($_SESSION["J_EndDay2"])){
				$query = $query . " AND A.EndDay Between '" . $_SESSION["J_EndDay1"] . "' and '" . $_SESSION["J_EndDay2"] ."'";
			}elseif(empty($_SESSION["J_EndDay1"]) && !empty($_SESSION["J_EndDay2"])){
				$query = $query . " AND A.EndDay <= '" . $_SESSION["J_EndDay2"] ."'";
			}else{
			}
		}
		if(!empty($_SESSION["J_Orgtype"])){
			if($KensakuFlg ==0){
				$query = $query . "Where A.Orgtype='" . $_SESSION["J_Orgtype"] ."' ";
				$KensakuFlg = 1;
			}else{
				$query = $query . "AND A.Orgtype='" . $_SESSION["J_Orgtype"] . "' ";
			}
		}
		if(!empty($_SESSION["J_course"])){
			if($KensakuFlg ==0){
				$query = $query . "Where A.course='" . $_SESSION["J_course"] ."' ";
				$KensakuFlg = 1;
			}else{
				$query = $query . "AND A.course='" . $_SESSION["J_course"] . "' ";
			}
		}
		if(!empty($_SESSION["J_course2"])){
			if($KensakuFlg ==0){
				$query = $query . "Where A.course2='" . $_SESSION["J_course2"] ."' ";
				$KensakuFlg = 1;
			}else{
				$query = $query . "AND A.course2='" . $_SESSION["J_course2"] . "' ";
			}
		}
		if(!empty($_SESSION["J_gread"])){
			if($KensakuFlg ==0){
				$query = $query . "Where C.gread='" . $_SESSION["J_gread"] ."' ";
				$KensakuFlg = 1;
			}else{
				$query = $query . "AND C.gread='" . $_SESSION["J_gread"] . "' ";
			}
		}

		if($_SESSION["J_updateflg"]!=""){
			if($_SESSION["J_updateflg"] != 2){
				if($KensakuFlg ==0){
					$query = $query . "Where C.updateflg='" . $_SESSION["J_updateflg"] ."' ";
					$KensakuFlg = 1;
				}else{
					$query = $query . "AND C.updateflg='" . $_SESSION["J_updateflg"] . "' ";
				}
			}
		}

		$querychar="";
		for($m = 1; $m <= 5; $m++){
			switch ($_SESSION["J_Sort" .$m]){
				case '0':
					break;
				case '1':
					if($SortFlg == 1){
						$querychar = $querychar . ",";
					}
					$querychar = $querychar . "A.TeacherID";
					if($_SESSION["J_SortType" .$m]==0){
						$querychar = $querychar . " ASC";
					}else{
						$querychar = $querychar . " DESC";
					}
					$SortFlg = 1;
					break;
				case '2':
					if($SortFlg == 1){
						$querychar = $querychar . ",";
					}
					$querychar = $querychar . "B.Name1";
					if($_SESSION["J_SortType" .$m]==0){
						$querychar = $querychar . " ASC";
					}else{
						$querychar = $querychar . " DESC";
					}
					$SortFlg = 1;
					break;
				case '3':
					if($SortFlg == 1){
						$querychar = $querychar . ",";
					}
					$querychar = $querychar . "A.StudentID";
					if($_SESSION["J_SortType" .$m]==0){
						$querychar = $querychar . " ASC";
					}else{
						$querychar = $querychar . " DESC";
					}
					$SortFlg = 1;
					break;
				case '4':
					if($SortFlg == 1){
						$querychar = $querychar . ",";
					}
					$querychar = $querychar . "C.Name1";
					if($_SESSION["J_SortType" .$m]==0){
						$querychar = $querychar . " ASC";
					}else{
						$querychar = $querychar . " DESC";
					}
					$SortFlg = 1;
					break;
				case '5':
					if($SortFlg == 1){
						$querychar = $querychar . ",";
					}
					$querychar = $querychar . "A.StartDay";
					if($_SESSION["J_SortType" .$m]==0){
						$querychar = $querychar . " ASC";
					}else{
						$querychar = $querychar . " DESC";
					}
					$SortFlg = 1;
					break;
				case '6':
					if($SortFlg == 1){
						$querychar = $querychar . ",";
					}
					$querychar = $querychar . "A.EndDay";
					if($_SESSION["J_SortType" .$m]==0){
						$querychar = $querychar . " ASC";
					}else{
						$querychar = $querychar . " DESC";
					}
					$SortFlg = 1;
					break;
				case '7':
					if($SortFlg == 1){
						$querychar = $querychar . ",";
					}
					$querychar = $querychar . "A.KyuyoNo";
					if($_SESSION["J_SortType" .$m]==0){
						$querychar = $querychar . " ASC";
					}else{
						$querychar = $querychar . " DESC";
					}
					$SortFlg = 1;
					break;
			}
		}


		if($SortFlg == 1){
			$query = $query . " Order by " . $querychar;
		}

		//print($query);
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
		while($arr_item = $result->fetch_assoc()){
			//レコード内の各フィールド名と値を順次参照
			foreach($arr_item as $key => $value){
				//フィールド名と値を表示
				$data[$i][$key] = $value;
			}

			$StudentID = $data[$i]['StudentID'];
			$TeacherID = $data[$i]['TeacherID'];
			$Seq = $data[$i]['Seq'];
			$AtenaSeq = $data[$i]['AtenaSeq'];
			$TName1 = $data[$i]['TName1'];
			$TName2 = $data[$i]['TName2'];
			$SName1 = $data[$i]['SName1'];
			$SName2 = $data[$i]['SName2'];
			
			$data[$i]['StudentName'] = $data[$i]['SName1'];
			$data[$i]['TeacherName'] = $data[$i]['TName1'];
			$data[$i]['gread'] = $data[$i]['gread'];	//20170210
			$data[$i]['Orgtype'] = $data[$i]['Orgtype'];	//20180131
			$data[$i]['course'] = $data[$i]['course'];	//20180131
			$data[$i]['course2'] = $data[$i]['course2'];	//20180131
			$data[$i]['oldflg'] = $data[$i]['oldflg'];	//20180320
			$data[$i]['greadflg'] = $data[$i]['greadflg'];	//20180320
			$data[$i]['updateflg'] = $data[$i]['updateflg'];	//20180320

			//------回数取得------
			$data[$i]['Kaisu1'] = "";
			$data[$i]['Kaisu2'] = "";
			$data[$i]['Kaisu3'] = "";
			$data[$i]['Kaisu4'] = "";
			$data[$i]['Kaisu5'] = "";
			$data[$i]['Kaisu6'] = "";
			$data[$i]['Kaisu7'] = "";
			$data[$i]['Kaisu8'] = "";
			$data[$i]['Kaisu9'] = "";
			$data[$i]['Kaisu10'] = "";
			$data[$i]['Kaisu11'] = "";
			$data[$i]['Kaisu12'] = "";

			$query2 = "SELECT * FROM T_ReportMonth WHERE TeacherID = '" . $TeacherID . "' AND StudentID = '" . $StudentID  . "' AND Seq = " . $AtenaSeq . " AND KeiyakuSeq = " . $Seq ;
			$query2 = $query2 . " Order by Year desc,Month asc";
			$result2 = $mysqli->query($query2);

			//print($query2 ."<BR>");

			if (!$result2) {
				print('クエリーが失敗しました。' . $mysqli->error);
				$mysqli->close();
				exit();
			}
			$h = 0;
			while($arr_item3 = $result2->fetch_assoc()){

				//レコード内の各フィールド名と値を順次参照
				foreach($arr_item3 as $key => $value){
					//フィールド名と値を表示
					$data2[$h][$key] = $value;
				}
				$Year_Flg = $data2[$h]['Year'];										
				$Month_Flg = $data2[$h]['Month'];
				$Kaisu_Flg = $data2[$h]['KaisuSum'];

				$data[$i]['Year' . "_" . $Year_Flg ."_" . $Month_Flg] = $data2[$h]['Year'];
				$data[$i]['Kaisu' . "_" . $Year_Flg ."_" . $Month_Flg] = $data2[$h]['KaisuSum'];
				$data[$i]['HourSumH' . "_" . $Year_Flg ."_" . $Month_Flg] = $data2[$h]['HourSumH'];
				$data[$i]['HourSumM' . "_" . $Year_Flg ."_" . $Month_Flg] = $data2[$h]['HourSumM'];
				if($data2[$h]['ZanPM']==1){
					$ZanPM = "+";
				}else{
					$ZanPM = "-";
				}
				if($data2[$h]['ZanHoursH']==99){
					$ZanHoursH = 0;
				}else{
					$ZanHoursH = $data2[$h]['ZanHoursH'];
				}
				if($data2[$h]['ZanHoursM']==990){
					$ZanHoursM = 0;
				}else{
					$ZanHoursM = $data2[$h]['ZanHoursM'];
				}
				if($ZanHoursH == 0 && $ZanHoursM == 0){
					$data[$i]['Zan' . "_" . $Year_Flg ."_" . $Month_Flg] = "";
				}else{
					$data[$i]['Zan' . "_" . $Year_Flg ."_" . $Month_Flg] = $ZanPM . $ZanHoursH . ":" . $ZanHoursM;
				}
				if(isset($data2[$h]['HoukokuFL'])){
					$data[$i]['HoukokuFL' . "_" . $Year_Flg ."_" . $Month_Flg] = $data2[$h]['HoukokuFL'];
				}else{
					$data[$i]['HoukokuFL' . "_" . $Year_Flg ."_" . $Month_Flg] = 0;
				}
//print($Year_Flg ."_" . $Month_Flg . "<BR>");
//print($data[$i][Kaisu . "_" . $Year_Flg ."_" . $Month_Flg] . "<BR>");
//print($data[$i][HourSumH . "_" . $Year_Flg ."_" . $Month_Flg] . "<BR>");
//print($data[$i][HourSumM . "_" . $Year_Flg ."_" . $Month_Flg] . "<BR>");
//print($data[$i][HoukokuFL . "_" . $Year_Flg ."_" . $Month_Flg] . "<BR>");
//print($data[$i][Zan . "_" . $Year_Flg ."_" . $Month_Flg] . "<BR>");

				$h++;
			}

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
			
			$TaikenCnt = 0;
			$EigyoCnt = 0;
			$OnlineCnt = 0;
			
			//指導内容を取得
			if($_SESSION["MCount"]==1){
				$query3 = "SELECT cast(Day as SIGNED) as DayInt,Kubun FROM T_ReportDay WHERE TeacherID = '" . $TeacherID . "' AND StudentID = '" . $StudentID  . "' AND Seq = " . $AtenaSeq . " AND KeiyakuSeq = " . $Seq ;
				$query3 = $query3 . " AND Year = '" . $_SESSION["TodayY_Start"] . "' AND Month = '" . $_SESSION["TodayM_Start"] . "'";
				$query3 = $query3 . " Order by cast(Day as SIGNED)";
				$result3 = $mysqli->query($query3);

				//print($query3 ."<BR>");
				if (!$result3) {
					print('クエリーが失敗しました。' . $mysqli->error);
					$mysqli->close();
					exit();
				}
				$j = 0;
				$Day = "";										
				$Kubun = "";
				$Where = "";
				$DayData = "";
				$DayDataTaiken = "";
				$DayDataEigyo = "";
				$DayDataOnline = "";
				$TaikenFlg = 0;
				$EigyoFlg = 0;
				$OnlineFlg = 0;
				while($arr_item3 = $result3->fetch_assoc()){

					//レコード内の各フィールド名と値を順次参照
					foreach($arr_item3 as $key => $value){
						//フィールド名と値を表示
						$data3[$j][$key] = $value;
					}
					$Day = $data3[$j]['DayInt'];										
					$Kubun = str_pad($data3[$j]['Kubun'], 2, 0, STR_PAD_LEFT);
					$Where = " Eda='" . $Kubun . "'";

					$CodeData = array();
					$CodeData = GetCodeData("指導区分",$Where,"",1);
					$_SESSION["01CodeData"]=$CodeData;
					if($Kubun == "02" || $Kubun == "10" || $Kubun == "11"){
						$DayData = $DayData . $Day . "日" . "<font color=red >" . $_SESSION["01CodeData"]["01_CodeName1_0"] . "</font>　";
					}else{
						$DayData = $DayData . $Day . "日" . $_SESSION["01CodeData"]["01_CodeName1_0"] . "　";
					}
					if($Kubun == "02"){
						$DayDataTaiken = $DayDataTaiken . $Day . "日" . "<font color=red >" . $_SESSION["01CodeData"]["01_CodeName1_0"] . "</font>　";

						$TaikenFlg = 1;
						$TaikenCnt++;
					}
					if($Kubun == "10"){
						$DayDataEigyo = $DayDataEigyo . $Day . "日" . "<font color=red >" . $_SESSION["01CodeData"]["01_CodeName1_0"] . "</font>　";

						$EigyoFlg = 1;
						$EigyoCnt++;
					}
					if($Kubun == "11"){
						$DayDataOnline = $DayDataOnline . $Day . "日" . "<font color=red >" . $_SESSION["01CodeData"]["01_CodeName1_0"] . "</font>　";

						$OnlineFlg = 1;
						$OnlineCnt++;
					}

					$j++;
				}
				$data[$i]['ManthNaiyo'] = $DayData;
				$data[$i]['ManthNaiyoTaiken'] = $DayDataTaiken;
				$data[$i]['ManthNaiyoEigyo'] = $DayDataEigyo;
				$data[$i]['ManthNaiyoOnline'] = $DayDataOnline;

				$data[$i]['TaikenFlg'] = $TaikenFlg;
				$data[$i]['EigyoFlg'] = $EigyoFlg;
				$data[$i]['OnlineFlg'] = $OnlineFlg;

				$data[$i]['TaikenCnt'] = $TaikenCnt;
				$data[$i]['EigyoCnt'] = $EigyoCnt;
				$data[$i]['OnlineCnt'] = $OnlineCnt;

			}
			
			$i++;
		}

		$_SESSION["DateCount"] = count($data);	//データ件数

	 	// データベースの切断
		$mysqli->close();
}
Function GetShoninDay($TID,$SID,$ASEQ,$KSEQ,$Y,$M){
//---------------承認データ取得---------------
			// mysqlへの接続
			$mysqli = new mysqli(HOST, USER, PASS);
			if ($mysqli->connect_errno) {
				print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
				exit();
			}
	
			// データベースの選択
			$mysqli->select_db(DBNAME2);
			$mysqli->set_charset("utf8");

			// クエリの実行
			$query4 = "select ShoninDay from SN_ShoninKanri ";
			$query4 = $query4 . " WHERE ";
			$query4 = $query4 . "TeacherID = '" . $TID . "'";
			$query4 = $query4 . " AND StudentID = '" . $SID  . "'";
			$query4 = $query4 . " AND Seq = '" . $ASEQ . "'"; 
			$query4 = $query4 . " AND KeiyakuSeq = '" . $KSEQ . "'";
			$query4 = $query4 . " AND Year = '" . $Y . "'";
			$query4 = $query4 . " AND Month = '" . $M . "'";
			$result = $mysqli->query($query4);

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
					$ShoninDay = $value;
				}
			}

		 	// データベースの切断
			$mysqli->close();
			return $ShoninDay;
}
?>
<script type="text/javascript" src="utility.js"></script>

<CENTER>
<form name="form1" method="post" action="J00_JissekiIchiran.php">
	<div id="header0" class="item">
		<BR>
		<table border="0" width="100%"  bgcolor="<?php echo HEADER_COLOR ?>">
			<tr align="center">
				<td align="center">
				<?php if($_SESSION["J00_JissekiIchiran_MODE"] == "KENT") {?>
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
			<input type="hidden" id="submitter" name="submitter" value="" />
			<input type="button" id="modoru" name="modoru" onClick="sbmfnc(this,1)" style="cursor: pointer" value="戻る" />
			<input type="button" id="logout" name="logout" onClick="sbmfnc(this,1)" style="cursor: pointer" value="ログアウト" />
<!--
			<input type="submit" name="modoru" style="cursor: pointer" value="戻る" />
			<input type="submit" name="logout" style="cursor: pointer" value="ログアウト" />
-->
		</td>
	</table>
	<BR><BR><BR>

	<table border="1">
		<tr>
			<td width="500" align="center" colspan="2" bgcolor="#c0c0c0">検索条件</td>
			<td width="200" align="center" bgcolor="#c0c0c0">並び替え</td>
			<td rowspan="2">
				<BR>
				<BR>
				<input type="submit" id="submit_button" name="kensaku" style="cursor: pointer" value="検索" />
				<BR>
				<BR>
				<BR>
				<BR>
				<BR>
				<input type="submit" id="submit_button" name="clear" style="cursor: pointer" value="クリア" />
			</td>
		</tr>
		<tr>
			<td>
				<table border="0">
					<tr>
						<td width="80" align="center" bgcolor="<?php echo KITEI_COLOR ?>">給与No</td>
						<td width="80" align="left"><input type="text" size="10" maxlength="10" name="J_KyuyoID" style="ime-mode: disabled;" value="<?php echo $_SESSION["J_KyuyoID"] ?>"></td>
					</tr>
					<tr>
						<td width="80" align="center" bgcolor="<?php echo TEACHR_COLOR ?>">教師ID</td>
						<td width="80" align="left"><input type="text" size="10" maxlength="10" name="J_TeacherID" style="ime-mode: disabled;" value="<?php echo $_SESSION["J_TeacherID"] ?>"></td>
					</tr>
					<tr>
						<td width="80" align="center" bgcolor="<?php echo TEACHR_COLOR ?>">教師名</td>
						<td width="300" align="left"><input type="text" size="30" maxlength="20" name="J_TeacherName" style="ime-mode: active;" value="<?php echo $_SESSION["J_TeacherName"] ?>"></td>
					</tr>
					<tr>
						<td width="80" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">生徒ID</td>
						<td width="80" align="left"><input type="text" size="10" maxlength="10" name="J_StudentID" style="ime-mode: disabled;" value="<?php echo $_SESSION["J_StudentID"] ?>"></td>
					</tr>
					<tr>
						<td width="80" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">生徒名</td>
						<td width="300" align="left"><input type="text" size="30" maxlength="20" name="J_StudentName" style="ime-mode: active;" value="<?php echo $_SESSION["J_StudentName"] ?>"></td>
					</tr>
					<tr>
						<td width="80" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">学年</td>
						<td width="300" align="left">
							<select name="J_gread" class="selecttype2">
								<option value="" <?php if($_SESSION["J_gread"] == ""){ ?> SELECTED <?php } ?>></option>
								<?php for($dataidx=0; $dataidx < $_SESSION["13CodeData"]["13DataCount"]; $dataidx++){ ?>
									<option value="<?php echo $_SESSION["13CodeData"]["13_Eda_" . $dataidx] ?>" <?php if($_SESSION["13CodeData"]["13_Eda_" . $dataidx] == $_SESSION["J_gread"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["13CodeData"]["13_CodeName2_" . $dataidx] ?></option>
								<?php } ?>
							</select>

						</td>
					</tr>
					<tr>
						<td width="80" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">年度更新</td>
						<td align="left">
							<input type="radio" name="J_updateflg" value="2" <?php if($_SESSION["J_updateflg"]==2){?> checked <?php } ?>>全件
							<input type="radio" name="J_updateflg" value="1" <?php if($_SESSION["J_updateflg"]==1){?> checked <?php } ?>>済
							<input type="radio" name="J_updateflg" value="0" <?php if($_SESSION["J_updateflg"]==0){?> checked <?php } ?>>未
						</td>
					</tr>
				</table>
			</td>
			<td>
				<table border="0">
					<tr>
						<td width="80" align="center" bgcolor="<?php echo KITEI_COLOR ?>">終了分</td>
						<td width="300" align="left">
							<input type="radio" name="J_ShuryoBun" value="1" <?php if($_SESSION["J_ShuryoBun"]==1){?> checked <?php } ?>>表示
							<input type="radio" name="J_ShuryoBun" value="2" <?php if($_SESSION["J_ShuryoBun"]==2){?> checked <?php } ?>>非表示
						</td>
					</tr>
					<tr>
						<td width="80" align="center" bgcolor="<?php echo KITEI_COLOR ?>">開始日</td>
						<td width="300" align="left">
							<input type="text" size="10" maxlength="10" name="J_StartDay1" style="ime-mode: disabled;" value="<?php echo $_SESSION["J_StartDay1"] ?>">
							～
							<input type="text" size="10" maxlength="10" name="J_StartDay2" style="ime-mode: disabled;" value="<?php echo $_SESSION["J_StartDay2"] ?>">
						</td>
					</tr>
					<tr>
						<td width="80" align="center" bgcolor="<?php echo KITEI_COLOR ?>">終了日</td>
						<td width="300" align="left">
							<input type="text" size="10" maxlength="10" name="J_EndDay1" style="ime-mode: disabled;" value="<?php echo $_SESSION["J_EndDay1"] ?>">
							～
							<input type="text" size="10" maxlength="10" name="J_EndDay2" style="ime-mode: disabled;" value="<?php echo $_SESSION["J_EndDay2"] ?>">
						</td>
					</tr>
					<tr>
						<td width="80" align="center" bgcolor="<?php echo KITEI_COLOR ?>">種別</td>
						<td align="left">
							<select name="J_Orgtype" onchange="this.form.submit()" class="selecttype2">
								<option value="" <?php if($_SESSION["J_Orgtype"] == ""){ ?> SELECTED <?php } ?>></option>
								<?php for($dataidx=0; $dataidx < $_SESSION["20CodeData"]["20DataCount"]; $dataidx++){ ?>
									<option value="<?php echo $_SESSION["20CodeData"]["20_Eda_" . $dataidx] ?>" <?php if($_SESSION["20CodeData"]["20_Eda_" . $dataidx] == $_SESSION["J_Orgtype"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["20CodeData"]["20_CodeName1_" . $dataidx] ?></option>
								<?php } ?>
							</select>
						</td>
					</tr>
					<tr>
						<td width="80" align="center" bgcolor="<?php echo KITEI_COLOR ?>">コース</td>
						<td align="left">
							<select name="J_course" class="selecttype2">
								<option value="" <?php if($_SESSION["J_course"] == ""){ ?> SELECTED <?php } ?>></option>
								<?php if($_SESSION["J_Orgtype"] == "06"){ ?>
									<?php for($dataidx=0; $dataidx < $_SESSION["31CodeData"]["31DataCount"]; $dataidx++){ ?>
										<option value="<?php echo $_SESSION["31CodeData"]["31_Eda_" . $dataidx] ?>" <?php if($_SESSION["31CodeData"]["31_Eda_" . $dataidx] == $_SESSION["J_course"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["31CodeData"]["31_CodeName1_" . $dataidx] ?></option>
									<?php } ?>
								<?php }else{ ?>
									<?php for($dataidx=0; $dataidx < $_SESSION["19CodeData"]["19DataCount"]; $dataidx++){ ?>
										<option value="<?php echo $_SESSION["19CodeData"]["19_Eda_" . $dataidx] ?>" <?php if($_SESSION["19CodeData"]["19_Eda_" . $dataidx] == $_SESSION["J_course"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["19CodeData"]["19_CodeName1_" . $dataidx] ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</td>
					</tr>
					<tr>
						<td width="80" align="center" bgcolor="<?php echo KITEI_COLOR ?>">コース２</td>
						<td align="left">
							<select name="J_course2" class="selecttype2">
								<option value="" <?php if($_SESSION["J_course2"] == ""){ ?> SELECTED <?php } ?>></option>
								<?php for($dataidx=0; $dataidx < $_SESSION["26CodeData"]["26DataCount"]; $dataidx++){ ?>
									<option value="<?php echo $_SESSION["26CodeData"]["26_Eda_" . $dataidx] ?>" <?php if($_SESSION["26CodeData"]["26_Eda_" . $dataidx] == $_SESSION["J_course2"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["26CodeData"]["26_CodeName1_" . $dataidx] ?></option>
								<?php } ?>
							</select>
						</td>
					</tr>
					<tr>
						<td width="80" align="center" bgcolor="<?php echo JISSEKI_COLOR ?>">実績月</td>
						<td width="50" align="left">

							<select name="J_JissekiNen1">
								<?php for ($d = 0; $d <= 5; $d++) { 
									$KijyunM = $TodayY - $d;
									if ($KijyunM == $_SESSION["TodayY_Start"]){
										print "<option value=" . $KijyunM . " SELECTED>" . $KijyunM . "</option>";
									}else{
										print "<option value=" . $KijyunM . ">" . $KijyunM . "</option>";
									}
								 } ?>
							</select>
							<select name="J_Jissekituki1">
								<?php 
								for ($m = 4; $m <= 12; $m++){
									if ($_SESSION["TodayM_Start"] == $m){
										print "<option value=" . $m . " SELECTED>" . $m ."月</option>";
									}else{
										print "<option value=" . $m . ">" . $m ."月</option>";
									}
								}
								for ($mm = 1; $mm <= 3; $mm++){	
									if ($_SESSION["TodayM_Start"] == $mm){
										print "<option value=" . $mm . " SELECTED>" . $mm ."月</option>";
									}else{
										print "<option value=" . $mm . ">" . $mm ."月</option>";
									}
								}
								?>
							</select>
							～
							<select name="J_JissekiNen2">
								<?php for ($d = 0; $d <= 5; $d++) { 
									$KijyunM = $TodayY - $d;
									if ($KijyunM == $_SESSION["TodayY_End"]){
										print "<option value=" . $KijyunM . " SELECTED>" . $KijyunM . "</option>";
									}else{
										print "<option value=" . $KijyunM . ">" . $KijyunM . "</option>";
									}
								 } ?>
							</select>
							<select name="J_Jissekituki2">
								<?php 
								for ($m = 4; $m <= 12; $m++){
									if ($_SESSION["TodayM_End"] == $m){
										print "<option value=" . $m . " SELECTED>" . $m ."月</option>";
									}else{
										print "<option value=" . $m . ">" . $m ."月</option>";
									}
								}
								for ($mm = 1; $mm <= 3; $mm++){	
									if ($_SESSION["TodayM_End"] == $mm){
										print "<option value=" . $mm . " SELECTED>" . $mm ."月</option>";
									}else{
										print "<option value=" . $mm . ">" . $mm ."月</option>";
									}
								}
								?>
							</select>
							<select name="J_Naiyo">
								<option value="" <?php if($_SESSION["J_Naiyo"] == ""){ ?> SELECTED <?php } ?>></option>
								<option value="02" <?php if($_SESSION["J_Naiyo"] == "02"){ ?> SELECTED <?php } ?>>体験</option>
								<option value="10" <?php if($_SESSION["J_Naiyo"] == "10"){ ?> SELECTED <?php } ?>>営業</option>
								<option value="11" <?php if($_SESSION["J_Naiyo"] == "11"){ ?> SELECTED <?php } ?>>オンライン</option>
							</select>
							<input type="checkbox" name="J_ShoninUmu1" value="1" <?php if($_SESSION["J_ShoninUmu1"]==1){?> checked <?php } ?>>承認済
							<input type="checkbox" name="J_ShoninUmu2" value="1" <?php if($_SESSION["J_ShoninUmu2"]==1){?> checked <?php } ?>>未承認
						</td>
					</tr>
					<tr>
						<td width="80" align="center" bgcolor="<?php echo JISSEKI_COLOR ?>">報告有無</td>
						<td width="300" align="left">
							<select name="J_HoukokuNen">
								<?php for ($d = 0; $d <= 5; $d++) { 
									$KijyunM = $TodayY - $d;
									if ($KijyunM == $_SESSION["H_TodayY_End"]){
										print "<option value=" . $KijyunM . " SELECTED>" . $KijyunM . "</option>";
									}else{
										print "<option value=" . $KijyunM . ">" . $KijyunM . "</option>";
									}
								 } ?>
							</select>
							<select name="J_Houkokutuki">
								<?php 
								for ($m = 4; $m <= 12; $m++){
									if ($_SESSION["H_TodayM_End"] == $m){
										print "<option value=" . $m . " SELECTED>" . $m ."月</option>";
									}else{
										print "<option value=" . $m . ">" . $m ."月</option>";
									}
								}
								for ($mm = 1; $mm <= 3; $mm++){	
									if ($_SESSION["H_TodayM_End"] == $mm){
										print "<option value=" . $mm . " SELECTED>" . $mm ."月</option>";
									}else{
										print "<option value=" . $mm . ">" . $mm ."月</option>";
									}
								}
								?>
							</select>
							<input type="checkbox" name="J_HokokuUmu1" value="1" <?php if($_SESSION["J_HokokuUmu1"]==1){?> checked <?php } ?>>報告済
							<input type="checkbox" name="J_HokokuUmu2" value="1" <?php if($_SESSION["J_HokokuUmu2"]==1){?> checked <?php } ?>>未報告
						</td>
					</tr>
				</table>
			</td>
			<td>
				<table border="0">
					<tr>
						<td width="80" align="center" bgcolor="#c0c0c0">キー①</td>
						<td width="150" align="left"> 
							<select name="J_Sort1">
								<option value="0" <?php if($_SESSION["J_Sort1"]==0){ ?> SELECTED <?php } ?>></option>
								<option value="7" <?php if($_SESSION["J_Sort1"]==7){ ?> SELECTED <?php } ?>>給与No</option>
								<option value="1" <?php if($_SESSION["J_Sort1"]==1){ ?> SELECTED <?php } ?>>教師ID</option>
								<option value="2" <?php if($_SESSION["J_Sort1"]==2){ ?> SELECTED <?php } ?>>教師名</option>
								<option value="3" <?php if($_SESSION["J_Sort1"]==3){ ?> SELECTED <?php } ?>>生徒ID</option>
								<option value="4" <?php if($_SESSION["J_Sort1"]==4){ ?> SELECTED <?php } ?>>生徒名</option>
								<option value="5" <?php if($_SESSION["J_Sort1"]==5){ ?> SELECTED <?php } ?>>開始日</option>
								<option value="6" <?php if($_SESSION["J_Sort1"]==6){ ?> SELECTED <?php } ?>>終了日</option>
							</select>
							<select name="J_SortType1">
								<option value="0" <?php if($_SESSION["J_SortType1"]==0){ ?> SELECTED <?php } ?>>昇順</option>
								<option value="1" <?php if($_SESSION["J_SortType1"]==1){ ?> SELECTED <?php } ?>>降順</option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="80" align="center" bgcolor="#c0c0c0">キー②</td>
						<td width="150" align="left"> 
							<select name="J_Sort2">
								<option value="0" <?php if($_SESSION["J_Sort2"]==0){ ?> SELECTED <?php } ?>></option>
								<option value="7" <?php if($_SESSION["J_Sort2"]==7){ ?> SELECTED <?php } ?>>給与No</option>
								<option value="1" <?php if($_SESSION["J_Sort2"]==1){ ?> SELECTED <?php } ?>>教師ID</option>
								<option value="2" <?php if($_SESSION["J_Sort2"]==2){ ?> SELECTED <?php } ?>>教師名</option>
								<option value="3" <?php if($_SESSION["J_Sort2"]==3){ ?> SELECTED <?php } ?>>生徒ID</option>
								<option value="4" <?php if($_SESSION["J_Sort2"]==4){ ?> SELECTED <?php } ?>>生徒名</option>
								<option value="5" <?php if($_SESSION["J_Sort2"]==5){ ?> SELECTED <?php } ?>>開始日</option>
								<option value="6" <?php if($_SESSION["J_Sort2"]==6){ ?> SELECTED <?php } ?>>終了日</option>
							</select>
							<select name="J_SortType2">
								<option value="0" <?php if($_SESSION["J_SortType2"]==0){ ?> SELECTED <?php } ?>>昇順</option>
								<option value="1" <?php if($_SESSION["J_SortType2"]==1){ ?> SELECTED <?php } ?>>降順</option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="80" align="center" bgcolor="#c0c0c0">キー③</td>
						<td width="150" align="left"> 
							<select name="J_Sort3">
								<option value="0" <?php if($_SESSION["J_Sort3"]==0){ ?> SELECTED <?php } ?>></option>
								<option value="7" <?php if($_SESSION["J_Sort3"]==7){ ?> SELECTED <?php } ?>>給与No</option>
								<option value="1" <?php if($_SESSION["J_Sort3"]==1){ ?> SELECTED <?php } ?>>教師ID</option>
								<option value="2" <?php if($_SESSION["J_Sort3"]==2){ ?> SELECTED <?php } ?>>教師名</option>
								<option value="3" <?php if($_SESSION["J_Sort3"]==3){ ?> SELECTED <?php } ?>>生徒ID</option>
								<option value="4" <?php if($_SESSION["J_Sort3"]==4){ ?> SELECTED <?php } ?>>生徒名</option>
								<option value="5" <?php if($_SESSION["J_Sort3"]==5){ ?> SELECTED <?php } ?>>開始日</option>
								<option value="6" <?php if($_SESSION["J_Sort3"]==6){ ?> SELECTED <?php } ?>>終了日</option>
							</select>
							<select name="J_SortType3">
								<option value="0" <?php if($_SESSION["J_SortType3"]==0){ ?> SELECTED <?php } ?>>昇順</option>
								<option value="1" <?php if($_SESSION["J_SortType3"]==1){ ?> SELECTED <?php } ?>>降順</option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="80" align="center" bgcolor="#c0c0c0">キー④</td>
						<td width="150" align="left"> 
							<select name="J_Sort4">
								<option value="0" <?php if($_SESSION["J_Sort4"]==0){ ?> SELECTED <?php } ?>></option>
								<option value="7" <?php if($_SESSION["J_Sort4"]==7){ ?> SELECTED <?php } ?>>給与No</option>
								<option value="1" <?php if($_SESSION["J_Sort4"]==1){ ?> SELECTED <?php } ?>>教師ID</option>
								<option value="2" <?php if($_SESSION["J_Sort4"]==2){ ?> SELECTED <?php } ?>>教師名</option>
								<option value="3" <?php if($_SESSION["J_Sort4"]==3){ ?> SELECTED <?php } ?>>生徒ID</option>
								<option value="4" <?php if($_SESSION["J_Sort4"]==4){ ?> SELECTED <?php } ?>>生徒名</option>
								<option value="5" <?php if($_SESSION["J_Sort4"]==5){ ?> SELECTED <?php } ?>>開始日</option>
								<option value="6" <?php if($_SESSION["J_Sort4"]==6){ ?> SELECTED <?php } ?>>終了日</option>
							</select>
							<select name="J_SortType4">
								<option value="0" <?php if($_SESSION["J_SortType4"]==0){ ?> SELECTED <?php } ?>>昇順</option>
								<option value="1" <?php if($_SESSION["J_SortType4"]==1){ ?> SELECTED <?php } ?>>降順</option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="80" align="center" bgcolor="#c0c0c0">キー⑤</td>
						<td width="150" align="left"> 
							<select name="J_Sort5">
								<option value="0" <?php if($_SESSION["J_Sort5"]==0){ ?> SELECTED <?php } ?>></option>
								<option value="7" <?php if($_SESSION["J_Sort5"]==7){ ?> SELECTED <?php } ?>>給与No</option>
								<option value="1" <?php if($_SESSION["J_Sort5"]==1){ ?> SELECTED <?php } ?>>教師ID</option>
								<option value="2" <?php if($_SESSION["J_Sort5"]==2){ ?> SELECTED <?php } ?>>教師名</option>
								<option value="3" <?php if($_SESSION["J_Sort5"]==3){ ?> SELECTED <?php } ?>>生徒ID</option>
								<option value="4" <?php if($_SESSION["J_Sort5"]==4){ ?> SELECTED <?php } ?>>生徒名</option>
								<option value="5" <?php if($_SESSION["J_Sort5"]==5){ ?> SELECTED <?php } ?>>開始日</option>
								<option value="6" <?php if($_SESSION["J_Sort5"]==6){ ?> SELECTED <?php } ?>>終了日</option>
							</select>
							<select name="J_SortType5">
								<option value="0" <?php if($_SESSION["J_SortType5"]==0){ ?> SELECTED <?php } ?>>昇順</option>
								<option value="1" <?php if($_SESSION["J_SortType5"]==1){ ?> SELECTED <?php } ?>>降順</option>
							</select>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<BR><BR>

	<table border="0">
		<TR>
			<TD>
				<table border="1">
					<tr>
						<td width="30" align="center" bgcolor="#c0c0c0" rowspan="2">ＮＯ</td>
						<td width="150" height="20" align="center" bgcolor="<?php echo TEACHR_COLOR ?>"colspan="2">教師</td>
						<td width="170" align="center" bgcolor="<?php echo STUDENT_COLOR ?>"colspan="6">生徒</td>
						<td width="50" align="center" bgcolor="<?php echo KITEI_COLOR ?>"colspan="8">規定</td>
					</tr>
					<tr>
						<td width="50" height="40" align="center" bgcolor="<?php echo TEACHR_COLOR ?>">ＩＤ</td>
						<td width="100" align="center" bgcolor="<?php echo TEACHR_COLOR ?>">名前</td>
						<td width="50" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">　</td>
						<td width="50" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">ＩＤ</td>
						<td width="100" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">名前</td>
						<td width="100" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">学年</td>
						<td width="20" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">宛名<BR>枝番</td>
						<td width="20" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">契約<BR>枝番</td>
						<td width="100" align="center" bgcolor="<?php echo KITEI_COLOR ?>">種別</td>
						<td width="150" align="center" bgcolor="<?php echo KITEI_COLOR ?>">コース</td>
						<td width="150" align="center" bgcolor="<?php echo KITEI_COLOR ?>">コース２</td>
						<td width="80" align="center" bgcolor="<?php echo KITEI_COLOR ?>">開始日</td>
						<td width="80" align="center" bgcolor="<?php echo KITEI_COLOR ?>">終了日</td>
						<td width="50" align="center" bgcolor="<?php echo KITEI_COLOR ?>">回数</td>
						<td width="50" align="center" bgcolor="<?php echo KITEI_COLOR ?>">時間</td>
						<td width="50" align="center" bgcolor="<?php echo KITEI_COLOR ?>">時給</td>
					</tr>
					<?php for ($i = 0; $i< count($data); $i++) { ?>
						<?php 
							//終了分
							if($_SESSION["J_ShuryoBun"]==2 && $data[$i]['HyojiFlg'] == 0){
								$HyojiFlg = 0;
							}else{
								$HyojiFlg = 1;
							}
							$H_YE = $_SESSION["H_TodayY_End"];
							$H_ME = $_SESSION["H_TodayM_End"];

							$HyojiFlg2 = 0;
							if($_SESSION["J_HokokuUmu1"]==1 && $_SESSION["J_HokokuUmu2"]==1){
								$HyojiFlg2 = 1;
							}elseif($_SESSION["J_HokokuUmu1"]==1 && $_SESSION["J_HokokuUmu2"]!=1){
								if($data[$i]['HoukokuFL' . "_" . $H_YE ."_" . $H_ME] == 1){
									$HyojiFlg2 = 1;
								}
							}elseif($_SESSION["J_HokokuUmu1"]!=1 && $_SESSION["J_HokokuUmu2"]==1){
								if($data[$i]['HoukokuFL' . "_" . $H_YE ."_" . $H_ME] != 1){
									$HyojiFlg2 = 1;
								}
							}
							$HyojiFlg3 = 0;
							if($_SESSION["J_Naiyo"]=="" || $_SESSION["MCount"] > 1){
								$HyojiFlg3 = 1;
							}else{
								if($_SESSION["J_Naiyo"]=="02"){
									if($data[$i]['TaikenFlg'] == 1){
										$HyojiFlg3 = 1;
									}else{
										$HyojiFlg3 = 0;
									}
								}
								if($_SESSION["J_Naiyo"]=="10"){
									if($data[$i]['EigyoFlg'] == 1){
										$HyojiFlg3 = 1;
									}else{
										$HyojiFlg3 = 0;
									}
								}
								if($_SESSION["J_Naiyo"]=="11"){
									if($data[$i]['OnlineFlg'] == 1){
										$HyojiFlg3 = 1;
										$ShoninDay = GetShoninDay($data[$i]['TeacherID'],$data[$i]['StudentID'],$data[$i]['AtenaSeq'],$data[$i]['Seq'],$_SESSION["TodayY_Start"],$_SESSION["TodayM_Start"]);
										if($_SESSION["J_ShoninUmu1"]==1 && $_SESSION["J_ShoninUmu2"]==1){
											$HyojiFlg3 = 1;
										}elseif($_SESSION["J_ShoninUmu1"]==1 && $_SESSION["J_ShoninUmu2"]==0){
											if($ShoninDay != ""){
												$HyojiFlg3 = 1;
											}else{
												$HyojiFlg3 = 0;
											}
										}elseif($_SESSION["J_ShoninUmu1"]==0 && $_SESSION["J_ShoninUmu2"]==1){
											if($ShoninDay != ""){
												$HyojiFlg3 = 0;
											}else{
												$HyojiFlg3 = 1;
											}
										}else{
											$HyojiFlg3 = 1;
										}
									}else{
										$HyojiFlg3 = 0;
									}
								}
							}

						if($HyojiFlg == 1 && $HyojiFlg2 == 1 && $HyojiFlg3 == 1){
						?>
							<tr <?php if ($data[$i]['updateflg'] == 1) {?>bgcolor="#F6CEF5"<?php }?>>
								<td width="30" height="40" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><input type="submit" name="No_<?php echo $i ?>" style="cursor: pointer" value="<?php echo $data[$i]['KyuyoNo'] ?>" /></td>
								<td width="50" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i]['TeacherID'] ?></td>
								<td width="100" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i]['TName1'] ?></td>
								<td width="50" height="40" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><input type="submit" name="J00_StudentID_<?php echo $i ?>" style="cursor: pointer" value="" /></td>
								<td width="50" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i]['StudentID'] ?></td>
								<td width="100" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i]['SName1'] ?></td>
								<td width="70" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }elseif($data[$i]['greadflg'] == 1){?>bgcolor="#FFFF00"<?php }?>>
									<?php 
										for($dataidx=0; $dataidx < $_SESSION["13CodeData"]["13DataCount"]; $dataidx++){
											if($data[$i]['gread'] == $_SESSION["13CodeData"]["13_Eda_" . $dataidx]){
												echo $_SESSION["13CodeData"]["13_CodeName2_" . $dataidx];
											}
										}
									?>
								</td>
								<td width="50" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i]['AtenaSeq'] ?></td>
								<td width="50" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i]['Seq'] ?></td>
								<td width="50" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>>
									<?php 
										for($dataidx=0; $dataidx < $_SESSION["20CodeData"]["20DataCount"]; $dataidx++){
											if($data[$i]['Orgtype'] == $_SESSION["20CodeData"]["20_Eda_" . $dataidx]){
												echo $_SESSION["20CodeData"]["20_CodeName2_" . $dataidx];
											}
										}
									?>
								</td>
								<td width="50" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>>
									<?php 
										if($data[$i]['Orgtype'] == "06"){
											for($dataidx=0; $dataidx < $_SESSION["31CodeData"]["31DataCount"]; $dataidx++){
												if($data[$i]['course'] == $_SESSION["31CodeData"]["31_Eda_" . $dataidx]){
													echo $_SESSION["31CodeData"]["31_CodeName1_" . $dataidx];
												}
											}
										}else{
											for($dataidx=0; $dataidx < $_SESSION["19CodeData"]["19DataCount"]; $dataidx++){
												if($data[$i]['course'] == $_SESSION["19CodeData"]["19_Eda_" . $dataidx]){
													echo $_SESSION["19CodeData"]["19_CodeName1_" . $dataidx];
												}
											}
										}
									?>
								</td>
								<td width="50" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>>
									<?php 
										for($dataidx=0; $dataidx < $_SESSION["26CodeData"]["26DataCount"]; $dataidx++){
											if($data[$i]['course2'] == $_SESSION["26CodeData"]["26_Eda_" . $dataidx]){
												echo $_SESSION["26CodeData"]["26_CodeName1_" . $dataidx];
											}
										}
									?>
								</td>
								<td width="80" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php if(is_null($data[$i]['StartDay'])){ }else{echo date('Y/n/j', strtotime($data[$i]['StartDay'])); }?></td>
								<td width="80" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php if(is_null($data[$i]['EndDay'])){ }else{echo date('Y/n/j', strtotime($data[$i]['EndDay'])); }?></td>
								<td width="50" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i]['KiteiKaisu'] ?></td>
								<td width="50" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i]['KiteiJikan'] ?></td>
								<td width="50" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i]['Pay'] ?></td>
								<input type="hidden" name="postshori" value="">
								<input type="hidden" name="valuename_<?php echo $i ?>" value="<?php echo $i ?>">
								<input type="hidden" name="TeacherID_<?php echo $i ?>" value="<?php echo $data[$i]['TeacherID']; ?>">
								<input type="hidden" name="TeacherName_<?php echo $i ?>" value="<?php echo $data[$i]['TeacherName']; ?>">
								<input type="hidden" name="StudentID_<?php echo $i ?>" value="<?php echo $data[$i]['StudentID']; ?>">
								<input type="hidden" name="StudentName_<?php echo $i ?>" value="<?php echo $data[$i]['StudentName']; ?>">
								<input type="hidden" name="StudentAtenaSeq_<?php echo $i ?>" value="<?php echo $data[$i]['AtenaSeq']; ?>">
								<input type="hidden" name="StudentSeq_<?php echo $i ?>" value="<?php echo $data[$i]['Seq']; ?>">
							</tr>
						<?php } ?>
					<?php } ?>
				</table>
			</TD>
			<TD>
				<table border="1">
					<tr>
<!--						<?php if($_SESSION["MCount"]==1){ ?>
							<td width="50" height="66" align="center" bgcolor="<?php echo KITEI_COLOR ?>">教師ID</td>
							<td width="50" height="66" align="center" bgcolor="<?php echo KITEI_COLOR ?>">教師名</td>
							<td width="50" height="66" align="center" bgcolor="<?php echo KITEI_COLOR ?>">生徒ID</td>
							<td width="50" height="66" align="center" bgcolor="<?php echo KITEI_COLOR ?>">生徒名</td>
						<?php } ?>
-->
						<td width="50" height="66" align="center" bgcolor="<?php echo KITEI_COLOR ?>">給与No</td>
					</tr>
					<?php for ($i = 0; $i< count($data); $i++) { ?>
						<?php 
							//終了分
							if($_SESSION["J_ShuryoBun"]==2 && $data[$i]['HyojiFlg'] == 0){
								$HyojiFlg = 0;
							}else{
								$HyojiFlg = 1;
							}
							$H_YE = $_SESSION["H_TodayY_End"];
							$H_ME = $_SESSION["H_TodayM_End"];

							$HyojiFlg2 = 0;
							if($_SESSION["J_HokokuUmu1"]==1 && $_SESSION["J_HokokuUmu2"]==1){
								$HyojiFlg2 = 1;
							}elseif($_SESSION["J_HokokuUmu1"]==1 && $_SESSION["J_HokokuUmu2"]!=1){
								if($data[$i]['HoukokuFL' . "_" . $H_YE ."_" . $H_ME] == 1){
									$HyojiFlg2 = 1;
								}
							}elseif($_SESSION["J_HokokuUmu1"]!=1 && $_SESSION["J_HokokuUmu2"]==1){
								if($data[$i]['HoukokuFL' . "_" . $H_YE ."_" . $H_ME] != 1){
									$HyojiFlg2 = 1;
								}
							}
							$HyojiFlg3 = 0;
							if($_SESSION["J_Naiyo"]=="" || $_SESSION["MCount"] > 1){
								$HyojiFlg3 = 1;
							}else{
								if($_SESSION["J_Naiyo"]=="02"){
									if($data[$i]['TaikenFlg'] == 1){
										$HyojiFlg3 = 1;
									}else{
										$HyojiFlg3 = 0;
									}
								}
								if($_SESSION["J_Naiyo"]=="10"){
									if($data[$i]['EigyoFlg'] == 1){
										$HyojiFlg3 = 1;
									}else{
										$HyojiFlg3 = 0;
									}
								}
								if($_SESSION["J_Naiyo"]=="11"){
									if($data[$i]['OnlineFlg'] == 1){
										$HyojiFlg3 = 1;
										$ShoninDay = GetShoninDay($data[$i]['TeacherID'],$data[$i]['StudentID'],$data[$i]['AtenaSeq'],$data[$i]['Seq'],$_SESSION["TodayY_Start"],$_SESSION["TodayM_Start"]);
										if($_SESSION["J_ShoninUmu1"]==1 && $_SESSION["J_ShoninUmu2"]==1){
											$HyojiFlg3 = 1;
										}elseif($_SESSION["J_ShoninUmu1"]==1 && $_SESSION["J_ShoninUmu2"]==0){
											if($ShoninDay != ""){
												$HyojiFlg3 = 1;
											}else{
												$HyojiFlg3 = 0;
											}
										}elseif($_SESSION["J_ShoninUmu1"]==0 && $_SESSION["J_ShoninUmu2"]==1){
											if($ShoninDay != ""){
												$HyojiFlg3 = 0;
											}else{
												$HyojiFlg3 = 1;
											}
										}else{
											$HyojiFlg3 = 1;
										}
									}else{
										$HyojiFlg3 = 0;
									}
								}
							}

							if($HyojiFlg == 1 && $HyojiFlg2 == 1 && $HyojiFlg3 == 1){
						?>
							<tr>
<!--								<?php if($_SESSION["MCount"]==1){ ?>
									<td width="50" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i]['TeacherID'] ?></td>
									<td width="100" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i]['TName1'] ?></td>
									<td width="50" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i]['StudentID'] ?></td>
									<td width="100" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i]['SName1'] ?></td>
								<?php } ?>
-->
								<td width="50" height="40" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php }?>><?php echo $data[$i]['KyuyoNo'] ?></td>
							</tr>
						<?php } ?>
					<?php } ?>
				</table>
			</TD>
			<TD>
				<table border="1">
					<?php if($_SESSION["MCount"]==1){ ?>
						<tr>
							<td width="50" height="18" align="center" bgcolor="<?php echo JISSEKI_COLOR ?>" colspan="4">実績</td>
						</tr>
						<tr>
							<?php
							$year = $_SESSION["TodayY_Start"];
							for($m=0; $m<$_SESSION["MCount"]; $m++){
								$Month = $_SESSION["TodayM_Start"] + $m;
								$Md = floor($Month / 12);
								$Md2 = floor($Month % 12);

								if($Md2 != 0){
									$Month = $Month - 12 * $Md;
								}else{
									$Month = $Month - 12 * ($Md-1);
								}
								//2017/02/10↓
								//if($Month == 1 ){
								//	$year = $year + 1;
								//}

							?>
									<td width="50" height="18" align="center" bgcolor="<?php echo JISSEKI_COLOR ?>" colspan="4"><?php echo $year ?>/<?php echo $Month ?></td>
							<?php } ?>
						</tr>
						<tr>
							<td width="50" height="18" align="center" bgcolor="<?php echo JISSEKI_COLOR ?>">回数</td>
							<td width="50" height="18" align="center" bgcolor="<?php echo JISSEKI_COLOR ?>">時間</td>
							<td width="50" height="18" align="center" bgcolor="<?php echo JISSEKI_COLOR ?>">残り時間</td>
							<td width="100" height="18" align="center" bgcolor="<?php echo JISSEKI_COLOR ?>">承認日</td>
						</tr>
						<?php for ($i = 0; $i< count($data); $i++) { ?>
							<?php 
								//終了分
								if($_SESSION["J_ShuryoBun"]==2 && $data[$i]['HyojiFlg'] == 0){
									$HyojiFlg = 0;
								}else{
									$HyojiFlg = 1;
								}
								$H_YE = $_SESSION["H_TodayY_End"];
								$H_ME = $_SESSION["H_TodayM_End"];

								$HyojiFlg2 = 0;
								if($_SESSION["J_HokokuUmu1"]==1 && $_SESSION["J_HokokuUmu2"]==1){
									$HyojiFlg2 = 1;
								}elseif($_SESSION["J_HokokuUmu1"]==1 && $_SESSION["J_HokokuUmu2"]!=1){
									if($data[$i]['HoukokuFL' . "_" . $H_YE ."_" . $H_ME] == 1){
										$HyojiFlg2 = 1;
									}
								}elseif($_SESSION["J_HokokuUmu1"]!=1 && $_SESSION["J_HokokuUmu2"]==1){
									if($data[$i]['HoukokuFL' . "_" . $H_YE ."_" . $H_ME] != 1){
										$HyojiFlg2 = 1;
									}
								}
								$HyojiFlg3 = 0;
								if($_SESSION["J_Naiyo"]==""){
									$HyojiFlg3 = 1;
								}else{
									if($_SESSION["J_Naiyo"]=="02"){
										if($data[$i]['TaikenFlg'] == 1){
											$HyojiFlg3 = 1;
										}else{
											$HyojiFlg3 = 0;
										}
									}
									if($_SESSION["J_Naiyo"]=="10"){
										if($data[$i]['EigyoFlg'] == 1){
											$HyojiFlg3 = 1;
										}else{
											$HyojiFlg3 = 0;
										}
									}
									if($_SESSION["J_Naiyo"]=="11"){
										if($data[$i]['OnlineFlg'] == 1){
											$HyojiFlg3 = 1;
											$ShoninDay = GetShoninDay($data[$i]['TeacherID'],$data[$i]['StudentID'],$data[$i]['AtenaSeq'],$data[$i]['Seq'],$_SESSION["TodayY_Start"],$_SESSION["TodayM_Start"]);
											if($_SESSION["J_ShoninUmu1"]==1 && $_SESSION["J_ShoninUmu2"]==1){
												$HyojiFlg3 = 1;
											}elseif($_SESSION["J_ShoninUmu1"]==1 && $_SESSION["J_ShoninUmu2"]==0){
												if($ShoninDay != ""){
													$HyojiFlg3 = 1;
												}else{
													$HyojiFlg3 = 0;
												}
											}elseif($_SESSION["J_ShoninUmu1"]==0 && $_SESSION["J_ShoninUmu2"]==1){
												if($ShoninDay != ""){
													$HyojiFlg3 = 0;
												}else{
													$HyojiFlg3 = 1;
												}
											}else{
												$HyojiFlg3 = 1;
											}
										}else{
											$HyojiFlg3 = 0;
										}
									}
								}

								if($HyojiFlg == 1 && $HyojiFlg2 == 1 && $HyojiFlg3 == 1){
							?>
								<tr>								
									<?php
									$Year = $_SESSION["TodayY_Start"];
									for($m=0; $m<$_SESSION["MCount"]; $m++){
										$Month = $_SESSION["TodayM_Start"] + $m;
										if($Month > 12){
											$Month = $Month - 12;
										}
										$HyojiKaisuu = $data[$i][Kaisu . "_" . $Year ."_" . $Month];
										$HyojiKaisuu = $HyojiKaisuu - $data[$i]["TaikenCnt"];
										$HyojiKaisuu = $HyojiKaisuu - $data[$i]["EigyoCnt"];
										$HyojiKaisuu = $HyojiKaisuu - $data[$i]["OnlineCnt"];


										if($_SESSION["J_Naiyo"] == "02"){
											$HyojiKaisuu = $data[$i]["TaikenCnt"];
										}
										if($_SESSION["J_Naiyo"] == "10"){
											$HyojiKaisuu = $data[$i]["EigyoCnt"];
										}
										if($_SESSION["J_Naiyo"] == "11"){
											$HyojiKaisuu = $data[$i]["OnlineCnt"];
										}
									?>
										<td width="50" height="40" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php } ?>>
											<?php if(isset($data[$i]['HoukokuFL' . "_" . $Year ."_" . $Month])){?>
												<?php if($data[$i]['HoukokuFL' . "_" . $Year ."_" . $Month] != 1){?>
													<font color="#ff0000"><B>
												<?php } ?>
											<?php } ?>
												<?php echo $HyojiKaisuu ?><BR>
											<?php if(isset($data[$i]['HoukokuFL' . "_" . $Year ."_" . $Month])){?>
												<?php if($data[$i]['HoukokuFL' . "_" . $Year ."_" . $Month] != 1){?>
													</B></font>
												<?php } ?>
											<?php } ?>
										</td>
										<td width="50" height="40" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php } ?>>
											<?php if(isset($data[$i]['HoukokuFL' . "_" . $Year ."_" . $Month])){?>
												<?php if($data[$i]['HoukokuFL' . "_" . $Year ."_" . $Month] != 1){?>
													<font color="#ff0000"><B>
												<?php } ?>
											<?php } ?>
												<?php echo $data[$i]['HourSumH' . "_" . $Year ."_" . $Month] ?>
												<?php if($data[$i]['HourSumM' . "_" . $Year ."_" . $Month]!=""){ ?>
													:
												<?php } ?>
												<?php echo $data[$i]['HourSumM' . "_" . $Year ."_" . $Month] ?>
											<?php if(isset($data[$i]['HoukokuFL' . "_" . $Year ."_" . $Month])){?>
												<?php if($data[$i]['HoukokuFL' . "_" . $Year ."_" . $Month] != 1){?>
													</B></font>
												<?php } ?>
											<?php } ?>
										</td>
										<td width="50" height="40" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php } ?>>
											<?php if(isset($data[$i]['HoukokuFL' . "_" . $Year ."_" . $Month])){?>
												<?php if($data[$i]['HoukokuFL' . "_" . $Year ."_" . $Month] != 1){?>
													<font color="#ff0000"><B>
												<?php } ?>
											<?php } ?>
												<?php echo $data[$i]['Zan' . "_" . $Year ."_" . $Month] ?>
											<?php if(isset($data[$i]['HoukokuFL' . "_" . $Year ."_" . $Month])){?>
												<?php if($data[$i]['HoukokuFL' . "_" . $Year ."_" . $Month] != 1){?>
													</B></font>
												<?php } ?>
											<?php } ?>
										</td>
										<td>
											<?php echo GetShoninDay($data[$i]['TeacherID'],$data[$i]['StudentID'],$data[$i]['AtenaSeq'],$data[$i]['Seq'],$year,$Month) ?>
										</td>
									<?php } ?>
								</tr>
							<?php } ?>
						<?php } ?>
					<?php }else{ ?>
						<tr>
							<td width="50" height="20" align="center" bgcolor="<?php echo JISSEKI_COLOR ?>" colspan="<?php echo $_SESSION["MCount"] ?>">実績</td>
						</tr>
						<tr>
							<?php
							$year = $_SESSION["TodayY_Start"];
							for($m=0; $m<$_SESSION["MCount"]; $m++){
								$Month = $_SESSION["TodayM_Start"] + $m;
								$Md = floor($Month / 12);
								$Md2 = floor($Month % 12);

								if($Md2 != 0){
									$Month = $Month - 12 * $Md;
								}else{
									$Month = $Month - 12 * ($Md-1);
								}
								//2017/02/10↓ $m!=0追加
								if($m!=0 && $Month == 1 ){
									$year = $year + 1;
								}

							?>
									<td width="50" height="18" align="center" bgcolor="<?php echo JISSEKI_COLOR ?>"><?php echo $year ?></td>
							<?php } ?>
						</tr>
						<tr>
							<?php
							for($m=0; $m<$_SESSION["MCount"]; $m++){
								$Month = $_SESSION["TodayM_Start"] + $m;
								$Md = floor($Month / 12);
								$Md2 = floor($Month % 12);

								if($Md2 != 0){
									$Month = $Month - 12 * $Md;
								}else{
									$Month = $Month - 12 * ($Md-1);
								}

							?>
									<td width="50" height="18" align="center" bgcolor="<?php echo JISSEKI_COLOR ?>"><?php echo $Month ?></td>
							<?php } ?>
						</tr>
						<?php for ($i = 0; $i< count($data); $i++) { ?>
							<?php 
								//終了分
								if($_SESSION["J_ShuryoBun"]==2 && $data[$i]['HyojiFlg'] == 0){
									$HyojiFlg = 0;
								}else{
									$HyojiFlg = 1;
								}
								$H_YE = $_SESSION["H_TodayY_End"];
								$H_ME = $_SESSION["H_TodayM_End"];

								$HyojiFlg2 = 0;
								if($_SESSION["J_HokokuUmu1"]==1 && $_SESSION["J_HokokuUmu2"]==1){
									$HyojiFlg2 = 1;
								}elseif($_SESSION["J_HokokuUmu1"]==1 && $_SESSION["J_HokokuUmu2"]!=1){
									if($data[$i]['HoukokuFL' . "_" . $H_YE ."_" . $H_ME] == 1){
										$HyojiFlg2 = 1;
									}
								}elseif($_SESSION["J_HokokuUmu1"]!=1 && $_SESSION["J_HokokuUmu2"]==1){
									if($data[$i]['HoukokuFL' . "_" . $H_YE ."_" . $H_ME] != 1){
										$HyojiFlg2 = 1;
									}
								}

								if($HyojiFlg == 1 && $HyojiFlg2 == 1){
							?>
								<tr>								
									<?php
									$Year = $_SESSION["TodayY_Start"];
									for($m=0; $m<$_SESSION["MCount"]; $m++){
										$Month = $_SESSION["TodayM_Start"] + $m;
										//2017/02/10↓
										$Md = floor($Month / 12);
										$Md2 = floor($Month % 12);

										if($Md2 != 0){
											$Month = $Month - 12 * $Md;
										}else{
											$Month = $Month - 12 * ($Md-1);
										}
										if($m!=0 && $Month==1){
											$Year = $Year+1;
										}
									?>
										<td width="50" height="40" align="center" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php } ?>>
											<?php if(isset($data[$i]['HoukokuFL' . "_" . $Year ."_" . $Month])){?>
												<?php if($data[$i]['HoukokuFL' . "_" . $Year ."_" . $Month] != 1){?>
													<font color="#ff0000"><B>
												<?php } ?>
											<?php } ?>
											<?php if(isset($data[$i]['Kaisu' . "_" . $Year ."_" . $Month])){?>
												<?php echo $data[$i]['Kaisu' . "_" . $Year ."_" . $Month] ?><BR>
											<?php } ?>
											<?php if(isset($data[$i]['HourSumH' . "_" . $Year ."_" . $Month])){?>
												<?php echo $data[$i]['HourSumH' . "_" . $Year ."_" . $Month] ?>
											<?php } ?>
											<?php if(isset($data[$i]['HourSumM' . "_" . $Year ."_" . $Month])){?>
												<?php if($data[$i]['HourSumM' . "_" . $Year ."_" . $Month]!=""){ ?>
													:
												<?php } ?>
												<?php echo $data[$i]['HourSumM' . "_" . $Year ."_" . $Month] ?>
											<?php } ?>
											<?php if(isset($data[$i]['HoukokuFL' . "_" . $Year ."_" . $Month])){?>
												<?php if($data[$i]['HoukokuFL' . "_" . $Year ."_" . $Month] != 1){?>
													</B></font>
												<?php } ?>
											<?php } ?>
										</td>
									<?php } ?>
								</tr>
							<?php } ?>
						<?php } ?>
					<?php } ?>
				</table>
			</TD>
			<TD>
				<table border="1">
					<?php if($_SESSION["MCount"]==1){ ?>
						<tr>
							<td width="50" height="18" align="center" bgcolor="<?php echo JISSEKI_COLOR ?>" colspan="3">実績</td>
						</tr>
						<tr>
							<?php
							$year = $_SESSION["TodayY_Start"];
							for($m=0; $m<$_SESSION["MCount"]; $m++){
								$Month = $_SESSION["TodayM_Start"] + $m;
								$Md = floor($Month / 12);
								$Md2 = floor($Month % 12);

								if($Md2 != 0){
									$Month = $Month - 12 * $Md;
								}else{
									$Month = $Month - 12 * ($Md-1);
								}

							?>
							<td width="50" height="18" align="center" bgcolor="<?php echo JISSEKI_COLOR ?>"><?php echo $year ?>/<?php echo $Month ?></td>
							<?php } ?>
						</tr>
						<tr>
							<td width="500" height="18" align="center" bgcolor="<?php echo JISSEKI_COLOR ?>">内容</td>
						</tr>
						<?php for ($i = 0; $i< count($data); $i++) { ?>
							<?php 
								//終了分
								if($_SESSION["J_ShuryoBun"]==2 && $data[$i]['HyojiFlg'] == 0){
									$HyojiFlg = 0;
								}else{
									$HyojiFlg = 1;
								}
								$H_YE = $_SESSION["H_TodayY_End"];
								$H_ME = $_SESSION["H_TodayM_End"];

								$HyojiFlg2 = 0;
								if($_SESSION["J_HokokuUmu1"]==1 && $_SESSION["J_HokokuUmu2"]==1){
									$HyojiFlg2 = 1;
								}elseif($_SESSION["J_HokokuUmu1"]==1 && $_SESSION["J_HokokuUmu2"]!=1){
									if($data[$i]['HoukokuFL' . "_" . $H_YE ."_" . $H_ME] == 1){
										$HyojiFlg2 = 1;
									}
								}elseif($_SESSION["J_HokokuUmu1"]!=1 && $_SESSION["J_HokokuUmu2"]==1){
									if($data[$i]['HoukokuFL' . "_" . $H_YE ."_" . $H_ME] != 1){
										$HyojiFlg2 = 1;
									}
								}
								$HyojiFlg3 = 0;
								if($_SESSION["J_Naiyo"]==""){
									$HyojiFlg3 = 1;
									$NaiyoHyoji = $data[$i]['ManthNaiyo'];
								}else{
									if($_SESSION["J_Naiyo"]=="02"){
										if($data[$i]['TaikenFlg'] == 1){
											$HyojiFlg3 = 1;
											$NaiyoHyoji = $data[$i]['ManthNaiyoTaiken'];
										}else{
											$HyojiFlg3 = 0;
										}
									}
									if($_SESSION["J_Naiyo"]=="10"){
										if($data[$i]['EigyoFlg'] == 1){
											$HyojiFlg3 = 1;
											$NaiyoHyoji = $data[$i]['ManthNaiyoEigyo'];
										}else{
											$HyojiFlg3 = 0;
										}
									}
									if($_SESSION["J_Naiyo"]=="11"){
										if($data[$i]['OnlineFlg'] == 1){
											$HyojiFlg3 = 1;
											$NaiyoHyoji = $data[$i]['ManthNaiyoOnline'];
											$ShoninDay = GetShoninDay($data[$i]['TeacherID'],$data[$i]['StudentID'],$data[$i]['AtenaSeq'],$data[$i]['Seq'],$_SESSION["TodayY_Start"],$_SESSION["TodayM_Start"]);
											if($_SESSION["J_ShoninUmu1"]==1 && $_SESSION["J_ShoninUmu2"]==1){
												$HyojiFlg3 = 1;
											}elseif($_SESSION["J_ShoninUmu1"]==1 && $_SESSION["J_ShoninUmu2"]==0){
												if($ShoninDay != ""){
													$HyojiFlg3 = 1;
												}else{
													$HyojiFlg3 = 0;
												}
											}elseif($_SESSION["J_ShoninUmu1"]==0 && $_SESSION["J_ShoninUmu2"]==1){
												if($ShoninDay != ""){
													$HyojiFlg3 = 0;
												}else{
													$HyojiFlg3 = 1;
												}
											}else{
												$HyojiFlg3 = 1;
											}
										}else{
											$HyojiFlg3 = 0;
										}
									}
								}

								if($HyojiFlg == 1 && $HyojiFlg2 == 1 && $HyojiFlg3 == 1){
							?>
								<tr>								
									<?php
									$Year = $_SESSION["TodayY_Start"];
									for($m=0; $m<$_SESSION["MCount"]; $m++){
										$Month = $_SESSION["TodayM_Start"] + $m;
										if($Month > 12){
											$Month = $Month - 12;
										}
										//2017/02/10↓
										//if($Month==1){
										//	$Year = $Year+1;
										//}
									?>
										<td width="500" height="40" align="left" <?php if ($data[$i]['HyojiFlg'] == 0) {?>bgcolor="#c0c0c0"<?php } ?>>
											<?php echo $NaiyoHyoji ?>
										</td>
									<?php } ?>
								</tr>
							<?php } ?>
						<?php } ?>
					<?php } ?>
				</table>
			</TD>
		</TR>
	</table>
</form>
</CENTER>
<BR><BR><BR><BR>
</html>