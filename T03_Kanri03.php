<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN"
	"http://www.w3.org/TR/html4/frameset.dtd">

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="Header.css">
	<title>教師管理画面</title>
	<style type="text/css">
		div {
			width: 1100px;
			height: 400px;
			margin-bottom: 1.5em;
			background-color: #ffffff;
			border: 1px #c0c0c0 solid;
			color: #000000;
		}
		div.Sessho1 {
		overflow-y: auto;
		}
	</style>
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

	if(isset($_POST['T03_Student_sel'])){
		$_SESSION["ShoriID"]="STDSEL";
	}

	if(isset($_POST['T03_Hizuke_sel'])){
		$_SESSION["ShoriID"]="HizukeSEL";
	}

	if(isset($_POST['T03_Hizuke_sel2'])){
		$_SESSION["ShoriID"]="HizukeSEL2";
	}

	if(isset($_POST['submitter'])){
		switch ($_POST['submitter']){
			case 'update':
				$_SESSION["ShoriID"]="INS";
				break;
			case 'T03_Shosai':
				$_SESSION["ShoriID"]="SHOSAI";
				break;
			case 'T03_Kensaku':
				$_SESSION["ShoriID"]="KENSAKU";
				break;
			case 'T03_Clear':
				$_SESSION["ShoriID"]="CLEAR";
				break;
		}
	}

	// 折衝選択処理
	for ($m = 0; $m < $_SESSION["T04_Sessho_DataCount"]; $m++){
		if(isset($_POST["T04_No_" . $m])){
			$_SESSION["T04_JyotaiKubun"]=$_POST['T04_Sessho_JyotaiKubun' . $m];
			$_SESSION["S03_StudentID"]=$_POST['T04_Sessho_StudentID' . $m];
			$_SESSION["S03_Seq"]=$_POST['T04_Sessho_AtenaSeq' . $m];
			$_SESSION["T03_TeacherID"]=$_POST['T04_Sessho_TeacherID' . $m];
			if($_POST['T04_Sessho_JyotaiKubun' . $m] == 1){
				$Location = "S03_index.php?MODE=UPD&RPID=K01_index&KEY1=" . $_POST['T04_Sessho_StudentID' . $m] . "&KUBUN=1" . "&SEQ=" . $_POST['T04_Sessho_AtenaSeq' . $m];
			}else{
				$Location = "T03_index.php?MODE=UPD&RPID=K01_index&KEY1=" . $_POST['T04_Sessho_TeacherID' . $m] . "&KUBUN=0";
			}
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
			$_SESSION["T03_kensaku_RPID"] = $_GET['RPID'];
		      	//print($_SESSION["T03_kensaku_RPID"]);
		}
		if(isset($_GET['MODE'])) {
			if($_GET['MODE'] != "Modoru"){
				$_SESSION["T03_kensaku_MODE"] = $_GET['MODE'];
				$_SESSION["ShoriID"]=$_GET['MODE'];
			      	//print($_SESSION["K_kensaku_MODE"] . "<BR>");
			}
		}

		if(isset($_GET['KUBUN'])) {
			$_SESSION["Kensaku_KUBUN"] = $_GET['KUBUN'];
		}
		if(isset($_GET['KEY1'])) {
			$_SESSION["Kensaku_KEY1"] = $_GET['KEY1'];
			if($_SESSION["Kensaku_KUBUN"]=="0"){
				$_SESSION["TeacherID"] = $_GET['KEY1'];
			}else{
				$_SESSION["StudentID"] = $_GET['KEY1'];
			}
		}
		if(isset($_GET['SEQ'])) {
			$_SESSION["T03_StudentSeq"] = $_GET['SEQ'];
		}

//print($_SESSION["Kensaku_KUBUN"] . "<BR>");

//print($_SESSION["ShoriID"] . "<BR>");
		switch ($_SESSION["ShoriID"]){
			case 'ALL':
				SessionClear();
				GetData();
				$CodeData6 = array();
				$CodeData6 = GetCodeData("折衝日数","","",1);
				$_SESSION["11CodeData"]=$CodeData6;
				break;
			case 'UPD':
				SessionClear();
				GetData();

				break;
			case 'INS':
				SaveShori();
				$EMSG = CheckShori();
				if($EMSG == ""){
					$EMSG = InsertShori();
					GetData();
				}
				break;
			case 'KAKUNIN':
				if(isset($_GET['MSG'])) {
					$TuikiMsg = $_GET['MSG'];
				}else{
					$TuikiMsg = "";
				}
				if(isset($_GET['INDEX'])) {
					$TuikiIdx = $_GET['INDEX'];
					$_SESSION["T04_Sessho_HikitugiKubun" .$TuikiIdx] = "0";
					if($TuikiMsg != ""){
						$Hikitugi = $_SESSION["T04_Sessho_Hikitugi" .$TuikiIdx] . "⇒" .$TuikiMsg;
						$_SESSION["T04_Sessho_Hikitugi" .$TuikiIdx] = $Hikitugi;
						//-----TS_Sesshouを更新する-----
						$EMSG = SesshoUpd($TuikiIdx);
						GetData();
					}
				}
				break;
			case 'STDSEL':
				SaveShori();
				GetData();
				break;
			case 'HizukeSEL':
				SaveShori();
				GetData();
				break;
			case 'HizukeSEL2':
				SaveShori();
				GetData();
				break;
			case 'SHOSAI':
				GetData();
				$_SESSION["T03_ShosaiFlg"]="1";
				break;
			case 'KENSAKU':
				SaveShori();
				$EMSG = CheckShori();
				if($EMSG == ""){
					GetData();
				}
				break;
			case 'CLEAR':
				SessionClear();
				GetData();
				break;

		}	
	}

//-----------------------------------------------------------
//	セッション情報クリア
//-----------------------------------------------------------
function SessionClear(){


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

	$_SESSION["T03_SesshoDay"]=$_SESSION["Today"];
	$_SESSION["T03_Jyotai"]="";
	$_SESSION["T03_JyotaiKubun"]=$_SESSION["Kensaku_KUBUN"];
	$_SESSION["T03_YakusokuDay"]="";
	$_SESSION["T03_TAite"]="";
	$_SESSION["T03_SAite"]="";
	$_SESSION["T03_Student"]="";
	$_SESSION["T03_Teacher"]="";
	if($_SESSION["Kensaku_KUBUN"] == "0"){
		$_SESSION["T03_Student_sel"]="";
	}else{
		$_SESSION["T03_Teacher_sel"]="";
	}
	if($_SESSION["Kensaku_KUBUN"] == "2"){
		$_SESSION["T03_Hizuke_sel"]="01";
	}else{
		$_SESSION["T03_Hizuke_sel"]="";
	}
	$_SESSION["T03_Hizuke_sel2"]="";
	$_SESSION["T03_THouho"]="";
	$_SESSION["T03_SHouho"]="";
	$_SESSION["T03_SesshoNaiyo"]="";
	$_SESSION["T03_Hikitugi"]="";
	$_SESSION["T03_Tanto"]=$_SESSION["LoginTeacherID"];
	$_SESSION["T03_ShosaiFlg"] = 1;
	$_SESSION["T03_ken_Jyotai"] = "";
	$_SESSION["T03_ken_Jyotai2"] = "";
    $_SESSION["T03_ken_TeacherID"] = "";
	$_SESSION["T03_ken_StudentID"] = "";
	$_SESSION["T03_ken_YakusokuDay1"] = "";
	$_SESSION["T03_ken_YakusokuDay2"] = "";

}
//-----------------------------------------------------------
//	セーブ処理
//-----------------------------------------------------------
function SaveShori(){

	if($_SESSION["Kensaku_KUBUN"]=="0" || $_SESSION["Kensaku_KUBUN"]=="1"){
		if($_SESSION["Kensaku_KUBUN"]=="0"){
			if($_POST['T03_Student'] == ""){
				$_SESSION["T03_Student"] = "0";
				$_SESSION["T03_StudentSeq"] = "0";
			}else{
				//画面から取得分割する
				$student = explode(" ", $_POST['T03_Student']);
//print($student[0] . "<BR>");
//print($student[1] . "<BR>");
				$_SESSION["T03_Student"] = $student[0];
				$_SESSION["T03_Seq"] = $student[1];
			}
			$_SESSION["T03_Teacher"] = $_SESSION["TeacherID"];
		}else{
			if($_POST['T03_Teacher'] == ""){
				$_SESSION["T03_Teacher"] = "0";
			}else{
				$_SESSION["T03_Teacher"] = $_POST['T03_Teacher'];
			}
			$_SESSION["T03_Student"] = $_SESSION["StudentID"];
			$_SESSION["T03_Seq"] = $_SESSION["T03_StudentSeq"];//URLパラから取得
		}
		$_SESSION["T03_SesshoDay"] = $_POST['T03_SesshoDay'];
		$_SESSION["T03_Jyotai"] = $_POST['T03_Jyotai'];
//		$_SESSION["T03_JyotaiKubun"] = "";
		$_SESSION["T03_YakusokuDay"] = $_POST['T03_YakusokuDay'];
		$_SESSION["T03_TAite"] = $_POST['T03_TAite'];
		$_SESSION["T03_SAite"] = $_POST['T03_SAite'];
		$_SESSION["T03_THouho"] = $_POST['T03_THouho'];
		$_SESSION["T03_SHouho"] = $_POST['T03_SHouho'];
		$_SESSION["T03_SesshoNaiyo"] = $_POST['T03_SesshoNaiyo'];
		$_SESSION["T03_Hikitugi"] = $_POST['T03_Hikitugi'];
		$_SESSION["T03_Tanto"] = $_POST['T03_Tanto'];
		if($_SESSION["Kensaku_KUBUN"] == "0"){
			$_SESSION["T03_Student_sel"] = $_POST['T03_Student_sel'];
		}else{
			$_SESSION["T03_Teacher_sel"] = $_POST['T03_Teacher_sel'];
		}
	}else{
		$_SESSION["T03_Hizuke_sel"]= $_POST['T03_Hizuke_sel'];
		$_SESSION["T03_Hizuke_sel2"]= $_POST['T03_Hizuke_sel2'];
		$_SESSION["T03_ken_Jyotai"] = $_POST['T03_ken_Jyotai'];
		$_SESSION["T03_ken_Jyotai2"] = $_POST['T03_ken_Jyotai2'];
        $_SESSION["T03_ken_TeacherID"] = $_POST['T03_ken_TeacherID'];
		$_SESSION["T03_ken_StudentID"] = $_POST['T03_ken_StudentID'];
		$_SESSION["T03_ken_YakusokuDay1"] = $_POST['T03_ken_YakusokuDay1'];
		$_SESSION["T03_ken_YakusokuDay2"] = $_POST['T03_ken_YakusokuDay2'];
	}
}
//-----------------------------------------------------------
//	データ取得
//-----------------------------------------------------------
Function GetData(){
$Hizuke = "";
$query4Flg = "0";
		// mysqlへの接続
		$mysqli = new mysqli(HOST, USER, PASS);
		if ($mysqli->connect_errno) {
			print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
			exit();
		   		}

		// データベースの選択
		$mysqli->select_db(DBNAME);
		$mysqli->set_charset("utf8");

		//------------------------折衝情報取得------------------------------
		$query4 = "Select * ";
		$query4 = $query4 . " FROM TS_SeshoInfo ";
//		if($_SESSION["Kensaku_KEY1"] != ""){
//			$query4 = $query4 . " Where TeacherID='" . $_SESSION["Kensaku_KEY1"] . "'";
//			$query4Flg = "1";
//		}
		if($_SESSION["Kensaku_KUBUN"] == "0"){
			$query4 = $query4 . " WHERE TeacherID='" . $_SESSION["TeacherID"] . "'";
			if($_SESSION["T03_Student_sel"] !=""){
				$query4 = $query4 . " AND StudentID='" . $_SESSION["T03_Student_sel"] . "'";
			}
		}elseif($_SESSION["Kensaku_KUBUN"] == "1"){
			$query4 = $query4 . " WHERE StudentID='" . $_SESSION["StudentID"] . "'";
			//$query4 = $query4 . " AND AtenaSeq='" . $_SESSION["T03_StudentSeq"] . "'";//urlパラ
			if($_SESSION["T03_Teacher_sel"] !=""){
				$query4 = $query4 . " AND TeacherID='" . $_SESSION["T03_Teacher_sel"] . "'";
			}
		}elseif($_SESSION["Kensaku_KUBUN"] == "2"){
			if($_SESSION["T03_Hizuke_sel"] != "08"){
				switch ($_SESSION["T03_Hizuke_sel"]){
					case '01':	//１週間
						$Hizuke = date("Y/m/d", strtotime("-1 week" ));
						break;
					case '02':	//２週間
						$Hizuke = date("Y/m/d", strtotime("-2 week" ));
						break;
					case '03':	//３週間
						$Hizuke = date("Y/m/d", strtotime("-3 week" ));
						break;
					case '04':	//１ヶ月
						$Hizuke = date("Y/m/d", strtotime("-1 month" ));
						break;
					case '05':	//２ヶ月
						$Hizuke = date("Y/m/d", strtotime("-2 month" ));
						break;
					case '06':	//３ヶ月
						$Hizuke = date("Y/m/d", strtotime("-3 month" ));
						break;
					case '07':	//６ヶ月
						$Hizuke = date("Y/m/d", strtotime("-6 month" ));
						break;
				}
				if($query4Flg == "1"){
					$query4 = $query4 . " AND SesshoDay >= '" . $Hizuke . "'";
				}else{
					$query4 = $query4 . " WHERE SesshoDay >= '" . $Hizuke . "'";
					$query4Flg = "1";
				}
			}
			if($_SESSION["T03_Hizuke_sel2"] != ""){
				if($query4Flg == "1"){
					$query4 = $query4 . " AND HikitugiKubun = '" . $_SESSION["T03_Hizuke_sel2"] . "'";
				}else{
					$query4 = $query4 . " WHERE HikitugiKubun = '" . $_SESSION["T03_Hizuke_sel2"] . "'";
					$query4Flg = "1";
				}			
			}
			if($_SESSION["T03_ken_Jyotai"] != ""){
				if($query4Flg == "1"){
					$query4 = $query4 . " AND Jyotai = '" . $_SESSION["T03_ken_Jyotai"] . "' AND JyotaiKubun='0'";
				}else{
					$query4 = $query4 . " WHERE Jyotai = '" . $_SESSION["T03_ken_Jyotai"] . "' AND JyotaiKubun='0'";
					$query4Flg = "1";
				}			
			}
			if($_SESSION["T03_ken_Jyotai2"] != ""){
				if($query4Flg == "1"){
					$query4 = $query4 . " AND Jyotai = '" . $_SESSION["T03_ken_Jyotai2"] . "' AND JyotaiKubun='1'";
				}else{
					$query4 = $query4 . " WHERE Jyotai = '" . $_SESSION["T03_ken_Jyotai2"] . "' AND JyotaiKubun='1'";
					$query4Flg = "1";
				}			
			}
            if($_SESSION["T03_ken_TeacherID"] != ""){
				if($query4Flg == "1"){
					$query4 = $query4 . " AND TeacherID = '" . $_SESSION["T03_ken_TeacherID"] . "'";
				}else{
					$query4 = $query4 . " WHERE TeacherID = '" . $_SESSION["T03_ken_TeacherID"] . "'";
					$query4Flg = "1";
				}			
			}
			if($_SESSION["T03_ken_StudentID"] != ""){
				if($query4Flg == "1"){
					$query4 = $query4 . " AND StudentID = '" . $_SESSION["T03_ken_StudentID"] . "'";
				}else{
					$query4 = $query4 . " WHERE StudentID = '" . $_SESSION["T03_ken_StudentID"] . "'";
					$query4Flg = "1";
				}			
			}
			if($_SESSION["T03_ken_YakusokuDay1"] != "" && $_SESSION["T03_ken_YakusokuDay2"] != ""){
				if($query4Flg == "1"){
					$query4 = $query4 . " AND YakusokuDay >= '" . $_SESSION["T03_ken_YakusokuDay1"] . "'";
					$query4 = $query4 . " AND YakusokuDay <= '" . $_SESSION["T03_ken_YakusokuDay2"] . "'";
				}else{
					$query4 = $query4 . " WHERE YakusokuDay >= '" . $_SESSION["T03_ken_YakusokuDay1"] . "'";
					$query4 = $query4 . " AND YakusokuDay <= '" . $_SESSION["T03_ken_YakusokuDay2"] . "'";
					$query4Flg = "1";
				}
			}elseif($_SESSION["T03_ken_YakusokuDay1"] != "" && $_SESSION["T03_ken_YakusokuDay2"] == ""){
				if($query4Flg == "1"){
					$query4 = $query4 . " AND YakusokuDay >= '" .$_SESSION["T03_ken_YakusokuDay1"] . "'";
				}else{
					$query4 = $query4 . " WHERE YakusokuDay >= '" . $_SESSION["T03_ken_YakusokuDay1"] . "'";
					$query4Flg = "1";
				}
			}elseif($_SESSION["T03_ken_YakusokuDay1"] == "" && $_SESSION["T03_ken_YakusokuDay2"] != ""){
				if($query4Flg == "1"){
					$query4 = $query4 . " AND YakusokuDay <= '" .$_SESSION["T03_ken_YakusokuDay2"] . "'";
				}else{
					$query4 = $query4 . " WHERE YakusokuDay <= '" . $_SESSION["T03_ken_YakusokuDay2"] . "'";
					$query4Flg = "1";
				}
			}
		}

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
		// クエリの実行　Kensaku_KUBUN：0⇒教師情報　1⇒生徒情報
		if($_SESSION["Kensaku_KUBUN"]=="0"){
			$query = "SELECT * FROM T_TantoShosai WHERE  TeacherID = '" . $_SESSION["TeacherID"] . "' ORDER BY StudentID ASC,StartDay DESC ";
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

				$StudentID = $data[$i]['StudentID'];
				$Seq = $data[$i]['AtenaSeq'];
				$db_Name1 = "";
				$db_Name2 = "";
				$db_Gread = "";

				//------生徒名取得------
				$query2 = "SELECT * FROM S_AtenaInfo WHERE  StudentID = '" . $StudentID . "' AND Seq = " . $Seq ;
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
				$_SESSION["T03_HyojiFlg" .$i] = $data[$i]['HyojiFlg'];

				$i++;
			}
			$_SESSION["T03_Student_DateCount"] = count($data);	//データ件数
		}elseif($_SESSION["Kensaku_KUBUN"]=="1"){
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
		}
	 	// データベースの切断
		$mysqli->close();

}

//-----------------------------------------------------------
//	折衝情報更新
//-----------------------------------------------------------
function InsertShori(){

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

	$query = "Select * from TS_SeshoInfo";
	$query = $query . " Where TeacherID = '" . $_SESSION["T03_Teacher"] . "'";
	$query = $query . " And StudentID = '" . $_SESSION["T03_Student"] . "'";
	$query = $query . " And AtenaSeq = '" . $_SESSION["T03_Seq"] . "'";
	$query = $query . " And SesshoDay = '" . $_SESSION["T03_SesshoDay"] . "'";
	$query = $query . " Order by SesshoSeq Desc Limit 1";

//print($query . "<BR>");

	$result = $mysqli->query($query);

	if (!$result) {
		$ErrMSG = "クエリーが失敗しました。（シーケンス取得）" . $mysqli->error;
		$ErrFlg = 1;
	}

	if ($ErrFlg == 0){
		$Cnt=0;
		while ($row = $result->fetch_assoc()) {
			$SesshoSeq = $row['SesshoSeq'];
			$Cnt++;
		}
		if($Cnt == 0){
			$SesshoSeq = 0;
		}else{
			$SesshoSeq = $SesshoSeq + 1;
		}
//print($SesshoSeq . "<BR>");

		$query2 = "Select Count(*) as Cnt2 from TS_SeshoInfo ";
		$query2 = $query2 . " Where TeacherID = '" . $_SESSION["T03_Teacher"] . "'";
		$query2 = $query2 . " And StudentID = '" . $_SESSION["T03_Student"] . "'";
		$query2 = $query2 . " And AtenaSeq = '" . $_SESSION["T03_Seq"] . "'";
		$query2 = $query2 . " And SesshoDay = '" . $_SESSION["T03_SesshoDay"] . "'";
		$query2 = $query2 . " And SesshoSeq = '" . $SesshoSeq . "'";

//print($query2 . "<BR>");

		$result = $mysqli->query($query2);

		if (!$result) {
			$ErrMSG = "クエリーが失敗しました。（重複データ）" . $mysqli->error;
			$ErrFlg = 1;
		}

		if ($ErrFlg == 0){
			while ($row = $result->fetch_assoc()) {
				$Cnt2 = $row['Cnt2'];
			}
//print($Cnt2 . "<BR>");

			if ($Cnt2 == 0){
				$query3 = "INSERT INTO TS_SeshoInfo ";
				$query3 = $query3 . "values(";
				$query3 = $query3 . "'" . $_SESSION["T03_Teacher"] . "'";
				$query3 = $query3 . ",'" . $_SESSION["T03_Student"] . "'";
				$query3 = $query3 . ",'" . $_SESSION["T03_Seq"] . "'";
				$query3 = $query3 . ",'" . $_SESSION["T03_SesshoDay"] . "'";
				$query3 = $query3 . ",'" . $SesshoSeq . "'";
				$query3 = $query3 . ",'" . $_SESSION["T03_Jyotai"] . "'";
				$query3 = $query3 . ",'" . $_SESSION["T03_JyotaiKubun"] . "'";
				if($_SESSION["T03_YakusokuDay"] != ""){
					$query3 = $query3 . ",'" . $_SESSION["T03_YakusokuDay"] . "'";
				}else{
					$query3 = $query3 . ",NULL";
				}
				$query3 = $query3 . ",NULL";
				$query3 = $query3 . ",'" . $_SESSION["T03_Tanto"] . "'";
				$query3 = $query3 . ",'" . $_SESSION["T03_THouho"] . "'";
				$query3 = $query3 . ",'" . $_SESSION["T03_SHouho"] . "'";
				$query3 = $query3 . ",'" . $_SESSION["T03_TAite"] . "'";
				$query3 = $query3 . ",'" . $_SESSION["T03_SAite"] . "'";
				$query3 = $query3 . ",'" . $_SESSION["T03_SesshoNaiyo"] . "'";
				$query3 = $query3 . ",'" . $_SESSION["T03_Hikitugi"] . "'";
				if($_SESSION["T03_Hikitugi"]==""){
					$query3 = $query3 . ",'0')";
				}else{
					$query3 = $query3 . ",'1')";
				}
//print($query3);
				$result = $mysqli->query($query3);

				if (!$result) {
					$ErrMSG = "クエリーが失敗しました。（折衝情報登録）" . $mysqli->error;
					$ErrFlg = 1;
				}

				if($ErrFlg == 0){

					// コミット
					$mysqli->query("commit");

					$RtnMSG = "登録しました。";

					$mysqli->close();

					return $RtnMSG;

				}else{
					$mysqli->query("rollback");

					$mysqli->close();

					return $ErrMSG;
				}
			}else{
				$ErrMSG="（例外）重複データがあります。";
				$mysqli->query("rollback");
				$mysqli->close();
			}
		}else{
			$mysqli->query("rollback");

			$mysqli->close();

			return $ErrMSG;
		}

	}else{
		$mysqli->query("rollback");

		$mysqli->close();

		return $ErrMSG;
	}
}

//-----------------------------------------------------------
//	折衝情報更新（追記更新）
//-----------------------------------------------------------
function SesshoUpd($id){
		// mysqlへの接続
		$mysqli = new mysqli(HOST, USER, PASS);
		if ($mysqli->connect_errno) {
			print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
			exit();
		   		}

		// データベースの選択
		$mysqli->select_db(DBNAME);
		$mysqli->set_charset("utf8");

		//------------------------折衝情報取得------------------------------
		$query = "Update TS_SeshoInfo Set";
		$query = $query . " HikitugiKubun='0',";
		$query = $query . " Hikitugi='" . $_SESSION["T04_Sessho_Hikitugi" .$id] . "'";
		$query = $query . " Where TeacherID='" . $_SESSION["T04_Sessho_TeacherID" .$id] . "'";
		$query = $query . " And StudentID='" . $_SESSION["T04_Sessho_StudentID" .$id] . "'";
		$query = $query . " And AtenaSeq='" . $_SESSION["T04_Sessho_AtenaSeq" .$id] . "'";
		$query = $query . " And SesshoDay='" . $_SESSION["T04_Sessho_SesshoDay" .$id] . "'";
		$query = $query . " And SesshoSeq='" . $_SESSION["T04_Sessho_SesshoSeq" .$id] . "'";

		$query = $mysqli->query($query);

	 	// データベースの切断
		$mysqli->close();

}
//-----------------------------------------------------------
//	チェック処理
//-----------------------------------------------------------
function CheckShori(){


	if($_SESSION["Kensaku_KUBUN"]=="2"){
		if($_SESSION["T03_ken_YakusokuDay1"] != ""){
			if (strptime($_SESSION["T03_ken_YakusokuDay1"], '%Y-%m-%d')) {
			}else{
				$ErrMsg = "約束日が不正です。";
//				$_SESSION["K_TeacherStartDay1_COLER"] = $Background;
			}
		}
		if($_SESSION["T03_ken_YakusokuDay2"] != ""){
			if (strptime($_SESSION["T03_ken_YakusokuDay2"], '%Y-%m-%d')) {
			}else{
				$ErrMsg = "約束日が不正です。";
//				$_SESSION["K_TeacherStartDay2_COLER"] = $Background;
			}
		}
        if($_SESSION["T03_ken_Jyotai"] != "" and $_SESSION["T03_ken_Jyotai2"] != ""){
				$ErrMsg = "教師状態・生徒状態どちらか一つを選択してください。";
        }
		return $ErrMsg;
	}else{
		if($_SESSION['T03_SesshoDay'] == ""){
			$MSG = "日付が入力されていません。";
			return $MSG;
		}
		if($_SESSION['T03_Jyotai'] == ""){
			$MSG = "状態が選択されていません。";
			return $MSG;
		}
		if($_SESSION['T03_Tanto'] == ""){
			$MSG = "担当が選択されていません。";
			return $MSG;
		}
	}


}
?>
<script type="text/javascript" src="utility.js"></script>
<script type="text/javascript">
<!--

function disp(p){

	// 入力ダイアログを表示 ＋ 入力内容を user に代入
	var tuiki=prompt("確認済みとします。\n追記がある場合は入力してください。", "");

	if(tuiki==null){
        	/* [キャンセル]ボタンが押下された場合 */
        	alert("キャンセルボタンが押されました");
	}else{
        	/* 入力値を表示 */
        	location.href = "T03_Kanri03.php?MODE=KAKUNIN&MSG=" + tuiki + "&INDEX=" + p;
	}

}

// -->
</script>
<CENTER>
<body>
<form name="form1" method="post" action="T03_Kanri03.php">
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
	<?php if($_SESSION["Kensaku_KUBUN"]!="2"){ ?>
		<table border="0">
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#00FF00" colspan=8>折衝情報入力</td>
			</tr>
			<tr>
				<td id="midashi_Kanri" width="150" align="center" bgcolor="#c0c0c0">日付</td>
				<td align="left"><input class="inputtype" type="text" size="10" maxlength="10" name="T03_SesshoDay" style="ime-mode: disabled;" value="<?php echo $_SESSION["T03_SesshoDay"] ?>"></td>
				<td id="midashi_Kanri" width="150" align="center" bgcolor="#c0c0c0">状態</td>
				<?php if($_SESSION["Kensaku_KUBUN"]=="0"){?>
					<td align="left">
						<select name="T03_Jyotai" class="selecttype">
							<option value="" <?php if($_SESSION["T03_Jyotai"] == ""){ ?> SELECTED <?php } ?>></option>
							<?php for($dataidx=0; $dataidx < $_SESSION["03CodeData"]["03DataCount"]; $dataidx++){ ?>
								<option value="<?php echo $_SESSION["03CodeData"]["03_Eda_" . $dataidx] ?>" <?php if($_SESSION["03CodeData"]["03_Eda_" . $dataidx] == $_SESSION["T03_Jyotai"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["03CodeData"]["03_CodeName1_" . $dataidx] ?></option>
							<?php } ?>
						</select>
					</td>
				<?php }else{ ?>
					<td align="left">
						<select name="T03_Jyotai" class="selecttype">
							<option value="" <?php if($_SESSION["T03_Jyotai"] == ""){ ?> SELECTED <?php } ?>></option>
							<?php for($dataidx=0; $dataidx < $_SESSION["04CodeData"]["04DataCount"]; $dataidx++){ ?>
								<option value="<?php echo $_SESSION["04CodeData"]["04_Eda_" . $dataidx] ?>" <?php if($_SESSION["04CodeData"]["04_Eda_" . $dataidx] == $_SESSION["T03_Jyotai"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["04CodeData"]["04_CodeName1_" . $dataidx] ?></option>
							<?php } ?>
						</select>
					</td>
				<?php } ?>
				<td id="midashi_Kanri" width="150" align="center" bgcolor="#c0c0c0">約束日</td>
				<td align="left"><input class="inputtype" type="text" size="10" maxlength="10" name="T03_YakusokuDay" style="ime-mode: disabled;" value="<?php echo $_SESSION["T03_YakusokuDay"] ?>"></td>
				<td id="midashi_Kanri" width="150" align="center" bgcolor="#c0c0c0">担当</td>
				<td align="left">
					<select name="T03_Tanto" class="selecttype">
						<option value="" <?php if($_SESSION["T03_Tanto"] == ""){ ?> SELECTED <?php } ?>></option>
						<?php for($dataidx=0; $dataidx < $_SESSION["Tanto"]["TantoDataCount"]; $dataidx++){ ?>
							<option value="<?php echo $_SESSION["Tanto"]["TeacherID_" . $dataidx] ?>" <?php if($_SESSION["Tanto"]["TeacherID_" . $dataidx] == $_SESSION["T03_Tanto"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["Tanto"]["Name1_" . $dataidx] ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td id="midashi_Kanri" width="150" align="center" bgcolor="<?php echo TEACHR_COLOR ?>">教師</td>
				<?php if($_SESSION["Kensaku_KUBUN"]=="0"){ ?>
					<td align="center" width="50"><?php echo GetTeacherName($_SESSION["TeacherID"])?></td>
				<?php }else{ ?>
					<td align="left">
						<select name="T03_Teacher" class="selecttype">
							<option value="" <?php if($_SESSION["T03_Teacher"] == ""){ ?> SELECTED <?php } ?>></option>
							<?php for($dataidx=0; $dataidx < $_SESSION["T03_Teacher_DateCount"]; $dataidx++){ ?>
								<option value="<?php echo $_SESSION["T03_TeacherID" .$dataidx] ?>" <?php if($_SESSION["T03_TeacherID" . $dataidx] == $_SESSION["T03_Teacher"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["T03_TeacherName" . $dataidx] ?></option>
							<?php } ?>
						</select>
					</td>
				<?php } ?>
				<td id="midashi_Kanri" width="150" align="center" bgcolor="<?php echo TEACHR_COLOR ?>">連絡方法</td>
				<td align="left">
					<select name="T03_THouho" class="selecttype">
						<option value="" <?php if($_SESSION["T03_THouho"] == ""){ ?> SELECTED <?php } ?>></option>
						<?php for($dataidx=0; $dataidx < $_SESSION["09CodeData"]["09DataCount"]; $dataidx++){ ?>
							<option value="<?php echo $_SESSION["09CodeData"]["09_Eda_" . $dataidx] ?>" <?php if($_SESSION["09CodeData"]["09_Eda_" . $dataidx] == $_SESSION["T03_THouho"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["09CodeData"]["09_CodeName1_" . $dataidx] ?></option>
						<?php } ?>
					</select>
				</td>
				<td id="midashi_Kanri" width="150" align="center" bgcolor="<?php echo TEACHR_COLOR ?>">折衝相手</td>
				<td align="left">
					<select name="T03_TAite" class="selecttype">
						<option value="" <?php if($_SESSION["T03_TAite"] == ""){ ?> SELECTED <?php } ?>></option>
						<?php for($dataidx=0; $dataidx < $_SESSION["10CodeData"]["10DataCount"]; $dataidx++){ ?>
							<option value="<?php echo $_SESSION["10CodeData"]["10_Eda_" . $dataidx] ?>" <?php if($_SESSION["10CodeData"]["10_Eda_" . $dataidx] == $_SESSION["T03_TAite"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["10CodeData"]["10_CodeName1_" . $dataidx] ?></option>
						<?php } ?>
					</select>
				</td>
				<td id="midashi_Kanri" width="150" align="center"></td>
				<td align="center" width="50">　</td>
			</tr>
			<tr>
				<td id="midashi_Kanri" width="150" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">生徒</td>
				<?php if($_SESSION["Kensaku_KUBUN"]=="0"){ ?>
					<td align="left">
						<select name="T03_Student" class="selecttype">
								<option value="" <?php if($_SESSION["T03_Student"] == ""){ ?> SELECTED <?php } ?>></option>
								<?php for($dataidx=0; $dataidx < $_SESSION["T03_Student_DateCount"]; $dataidx++){ ?>
									<?php if($_SESSION["T03_HyojiFlg" .$dataidx] == 1) {?>
										<option value="<?php echo $_SESSION["T03_StudentID" .$dataidx] ?>" <?php if($_SESSION["T03_StudentID" . $dataidx] == $_SESSION["T03_Student"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["T03_StudentName" . $dataidx] ?></option>
									<?php } ?>
								<?php } ?>
						</select>
					</td>
				<?php }else{?>
					<td align="center" width="50"><?php echo GetStudentName($_SESSION["StudentID"])?></td>
				<?php }?>
				<td id="midashi_Kanri" width="150" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">連絡方法</td>
				<td align="left">
					<select name="T03_SHouho" class="selecttype">
						<option value="" <?php if($_SESSION["T03_SHouho"] == ""){ ?> SELECTED <?php } ?>></option>
						<?php for($dataidx=0; $dataidx < $_SESSION["09CodeData"]["09DataCount"]; $dataidx++){ ?>
							<option value="<?php echo $_SESSION["09CodeData"]["09_Eda_" . $dataidx] ?>" <?php if($_SESSION["09CodeData"]["09_Eda_" . $dataidx] == $_SESSION["T03_SHouho"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["09CodeData"]["09_CodeName1_" . $dataidx] ?></option>
						<?php } ?>
					</select>
				</td>
				<td id="midashi_Kanri" width="150" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">折衝相手</td>
				<td align="left">
					<select name="T03_SAite" class="selecttype">
						<option value="" <?php if($_SESSION["T03_SAite"] == ""){ ?> SELECTED <?php } ?>></option>
						<?php for($dataidx=0; $dataidx < $_SESSION["10CodeData"]["10DataCount"]; $dataidx++){ ?>
							<option value="<?php echo $_SESSION["10CodeData"]["10_Eda_" . $dataidx] ?>" <?php if($_SESSION["10CodeData"]["10_Eda_" . $dataidx] == $_SESSION["T03_SAite"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["10CodeData"]["10_CodeName1_" . $dataidx] ?></option>
						<?php } ?>
					</select>
				</td>
				<td id="midashi_Kanri" width="150" align="center" ></td>
				<td align="center" width="50">　</td>
			</tr>
			<tr>
				<td id="midashi_Kanri" width="150" align="center" bgcolor="#c0c0c0">折衝内容</td>
				<td colspan=8><textarea name="T03_SesshoNaiyo" cols="120" rows="7"><?php echo $_SESSION["T03_SesshoNaiyo"] ?></textarea></td>
			</tr>
			<tr>
				<td id="midashi_Kanri" width="150" align="center" bgcolor="#c0c0c0">引継事項</td>
				<td colspan=8><textarea name="T03_Hikitugi" cols="120" rows="3"><?php echo $_SESSION["T03_Hikitugi"] ?></textarea></td>
			</tr>

		</table>
		<table border="0" width="100%">
			<tr>
				<td width="100" align="center">
					<input type="button" id="update" name="update" onClick="sbmfnc(this,'');" style="cursor: pointer" value="登録" />
				</td>
			</tr>
		</table>
		<BR>
		<BR>
	<?php } ?>

	<?php if($_SESSION["Kensaku_KUBUN"]=="0"){ ?>
		<table border="0">
			<TR align="center">
				<td id="midashi_Kanri" width="100" align="center" bgcolor="#c0c0c0">生徒選択</td>
				<td align="left">
					<select name="T03_Student_sel" onchange="this.form.submit()" class="selecttype">
						<option value="" <?php if($_SESSION["T03_Student_sel"] == ""){ ?> SELECTED <?php } ?>></option>
						<?php for($dataidx=0; $dataidx < $_SESSION["T03_Student_DateCount"]; $dataidx++){ ?>
							<option value="<?php echo $_SESSION["T03_StudentID" .$dataidx] ?>" <?php if($_SESSION["T03_StudentID" . $dataidx] == $_SESSION["T03_Student_sel"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["T03_StudentName" . $dataidx] ?></option>
						<?php } ?>
					</select>
				</td>
			</TR>
		</table>
	<?php }elseif($_SESSION["Kensaku_KUBUN"]=="1"){ ?>
		<table border="0">
			<TR align="center">
				<td id="midashi_Kanri" width="100" align="center" bgcolor="#c0c0c0">教師選択</td>
				<td >
					<select name="T03_Teacher_sel" onchange="this.form.submit()" class="selecttype">
						<option value="" <?php if($_SESSION["T03_Teacher_sel"] == ""){ ?> SELECTED <?php } ?>></option>
						<?php for($dataidx=0; $dataidx < $_SESSION["T03_Teacher_DateCount"]; $dataidx++){ ?>
							<option value="<?php echo $_SESSION["T03_TeacherID" .$dataidx] ?>" <?php if($_SESSION["T03_TeacherID" . $dataidx] == $_SESSION["T03_Teacher_sel"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["T03_TeacherName" . $dataidx] ?></option>
						<?php } ?>
					</select>
				</td>
			</TR>
		</table>
	<?php }elseif($_SESSION["Kensaku_KUBUN"]=="2"){ ?>
		<table border="0">
			<TR align="center">
				<td id="midashi_Kanri" width="100" align="center" bgcolor="#c0c0c0">日付選択</td>
				<td >
					<select name="T03_Hizuke_sel" onchange="this.form.submit()" class="selecttype">
						<?php for($dataidx=0; $dataidx < $_SESSION["11CodeData"]["11DataCount"]; $dataidx++){ ?>
							<option value="<?php echo $_SESSION["11CodeData"]["11_Eda_" . $dataidx] ?>" <?php if($_SESSION["11CodeData"]["11_Eda_" . $dataidx] == $_SESSION["T03_Hizuke_sel"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["11CodeData"]["11_CodeName1_" . $dataidx] ?></option>
						<?php } ?>
					</select>
				</td>
				<td >
					<select name="T03_Hizuke_sel2" onchange="this.form.submit()" class="selecttype">
						<option value="" <?php if($_SESSION["T03_Hizuke_sel2"] == ""){ ?> SELECTED <?php } ?>></option>
						<option value="1" <?php if($_SESSION["T03_Hizuke_sel2"] == "1"){ ?> SELECTED <?php } ?>>引継事項あり</option>
						<option value="0" <?php if($_SESSION["T03_Hizuke_sel2"] == "0"){ ?> SELECTED <?php } ?>>引継事項なし</option>
					</select>
				</td>
				<?php if($_SESSION["T03_ShosaiFlg"] == 0){ ?>
					<td width="30" align="center" rowspan="2">
						<input type="button" id="T03_Shosai" name="T03_Shosai" onClick="sbmfnc(this,'');" style="cursor: pointer" value="詳細検索" />
					</td>
				<?php } ?>
			</TR>
		</table>
		<table border="0">
			<?php if($_SESSION["T03_ShosaiFlg"] == 1){ ?>
				<TR align="center">
					<td id="midashi_Kanri" width="100" align="center" bgcolor="#c0c0c0">教師状態</td>
					<td align="left">
						<select name="T03_ken_Jyotai" class="selecttype">
							<option value="" <?php if($_SESSION["T03_ken_Jyotai"] == ""){ ?> SELECTED <?php } ?>></option>
							<?php for($dataidx=0; $dataidx < $_SESSION["03CodeData"]["03DataCount"]; $dataidx++){ ?>
								<option value="<?php echo $_SESSION["03CodeData"]["03_Eda_" . $dataidx] ?>" <?php if($_SESSION["03CodeData"]["03_Eda_" . $dataidx] == $_SESSION["T03_ken_Jyotai"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["03CodeData"]["03_CodeName1_" . $dataidx] ?></option>
							<?php } ?>
						</select>
					</td>
					<td id="midashi_Kanri" width="100" align="center" bgcolor="#c0c0c0">生徒状態</td>
					<td align="left">
						<select name="T03_ken_Jyotai2" class="selecttype">
							<option value="" <?php if($_SESSION["T03_ken_Jyotai2"] == ""){ ?> SELECTED <?php } ?>></option>
							<?php for($dataidx=0; $dataidx < $_SESSION["04CodeData"]["04DataCount"]; $dataidx++){ ?>
								<option value="<?php echo $_SESSION["04CodeData"]["04_Eda_" . $dataidx] ?>" <?php if($_SESSION["04CodeData"]["04_Eda_" . $dataidx] == $_SESSION["T04_ken_Jyotai"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["04CodeData"]["04_CodeName1_" . $dataidx] ?></option>
							<?php } ?>
						</select>
					</td>
                </TR>
                <TR align="center">
					<td id="midashi_Kanri" width="100" align="center" bgcolor="#c0c0c0">生徒ID</td>
					<td align="left"><input class="inputtype" type="text" size="20" maxlength="10" name="T03_ken_StudentID" style="ime-mode: disabled;" value="<?php echo $_SESSION["T03_ken_StudentID"] ?>"></td>
					<td id="midashi_Kanri" width="100" align="center" bgcolor="#c0c0c0">教師ID</td>
					<td align="left"><input class="inputtype" type="text" size="20" maxlength="10" name="T03_ken_TeacherID" style="ime-mode: disabled;" value="<?php echo $_SESSION["T03_ken_TeacherID"] ?>"></td>
				</TR>
				<TR align="center">
					<td id="midashi_Kanri" width="100" align="center" bgcolor="#c0c0c0">約束日</td>
					<td align="left"><input class="inputtype" type="text" size="20" maxlength="10" name="T03_ken_YakusokuDay1" style="ime-mode: disabled;" value="<?php echo $_SESSION["T03_ken_YakusokuDay1"] ?>"></td>
					<td >～</td>
					<td align="left"><input class="inputtype" type="text" size="20" maxlength="10" name="T03_ken_YakusokuDay2" style="ime-mode: disabled;" value="<?php echo $_SESSION["T03_ken_YakusokuDay2"] ?>"></td>
					<td >
						<input type="button" id="T03_Kensaku" name="T03_Kensaku" onClick="sbmfnc(this,'');" style="cursor: pointer" value="検索" />
					</td>
					<td >
						<input type="button" id="T03_Clear" name="T03_Clear" onClick="sbmfnc(this,'');" style="cursor: pointer" value="クリア" />
					</td>
				</TR>
			<?php } ?>
		</table>
	<?php } ?>

<div id="tbl-bdr">
<?php if($_SESSION["Kensaku_KUBUN"]!="2"){ ?>
<div class="Sessho1">
<?php } ?>

	<table border="0">
		<tr>
			<?php if($_SESSION["Kensaku_KUBUN"]=="0"){ ?>
				<td id="midashi_Kanri" width="100" align="center" bgcolor="#c0c0c0" rowspan="2">日付</td>
				<td id="midashi_Kanri" width="300" align="center" bgcolor="#c0c0c0" rowspan="2">状態</td>
				<td id="midashi_Kanri" width="150" align="center" bgcolor="<?php echo TEACHR_COLOR ?>">方法</td>
				<td id="midashi_Kanri" width="150" align="center" bgcolor="<?php echo TEACHR_COLOR ?>">相手</td>
				<td id="midashi_Kanri" width="200" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">生徒</td>
				<td id="midashi_Kanri" width="100" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">方法</td>
				<td id="midashi_Kanri" width="100" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">相手</td>
				<td id="midashi_Kanri" width="100" align="center" bgcolor="#c0c0c0" rowspan="2">担当</td>
			<?php }elseif($_SESSION["Kensaku_KUBUN"]=="1"){ ?>
				<td id="midashi_Kanri" width="100" align="center" bgcolor="#c0c0c0" rowspan="2">日付</td>
				<td id="midashi_Kanri" width="300" align="center" bgcolor="#c0c0c0" rowspan="2">状態</td>
				<td id="midashi_Kanri" width="100" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">方法</td>
				<td id="midashi_Kanri" width="100" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">相手</td>
				<td id="midashi_Kanri" width="200" align="center" bgcolor="<?php echo TEACHR_COLOR ?>">教師</td>
				<td id="midashi_Kanri" width="150" align="center" bgcolor="<?php echo TEACHR_COLOR ?>">方法</td>
				<td id="midashi_Kanri" width="150" align="center" bgcolor="<?php echo TEACHR_COLOR ?>">相手</td>
				<td id="midashi_Kanri" width="100" align="center" bgcolor="#c0c0c0" rowspan="2">担当</td>
			<?php }elseif($_SESSION["Kensaku_KUBUN"]=="2"){ ?>
				<td  width="30" align="center" bgcolor="#c0c0c0" rowspan="2">ＮＯ</td>
				<td  width="150" align="center" bgcolor="#c0c0c0" rowspan="2">日付</td>
				<td  width="200" align="center" bgcolor="#c0c0c0" rowspan="2">状態</td>
				<td  width="200" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">生徒</td>
				<td  width="100" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">方法</td>
				<td  width="100" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">相手</td>
				<td  width="200" align="center" bgcolor="<?php echo TEACHR_COLOR ?>">教師</td>
				<td  width="150" align="center" bgcolor="<?php echo TEACHR_COLOR ?>">方法</td>
				<td  width="150" align="center" bgcolor="<?php echo TEACHR_COLOR ?>">相手</td>
				<td  width="100" align="center" bgcolor="#c0c0c0" rowspan="2">担当</td>
			<?php } ?>
		</tr>
		<tr>
			<?php if($_SESSION["Kensaku_KUBUN"]=="2"){ ?>
				<td id="midashi_Kanri" width="450" align="center" bgcolor="#c0c0c0" colspan="6">内容</td>
			<?php }else{ ?>
				<td id="midashi_Kanri" width="450" align="center" bgcolor="#c0c0c0" colspan="5">内容</td>
			<?php } ?>
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
				<?php if($_SESSION["Kensaku_KUBUN"]=="0"){ ?>
					<td width="100" align="center" rowspan="2"><?php echo $_SESSION["T04_Sessho_SesshoDay" .$i] ?>(<?php echo $Youbi?>)</td>
					<td width="200" align="left" rowspan="2">
						<B><?php if($_SESSION["T04_Sessho_JyotaiKubun" .$i]=="0"){ ?> <?php echo $_SESSION["03CodeName"]["03_CodeName1_0"] ?> <?php }else{ ?> <?php echo $_SESSION["03CodeName"]["03_CodeName1_0"] ?> <?php } ?></B><BR>
						<font color="#0000FF"><?php echo $_SESSION["T04_Sessho_YakusokuDay" .$i] ?></font>
					</td>
					<td width="100" align="center"><?php echo $_SESSION["09TCodeName"]["09_CodeName1_0"] ?></td>
					<td width="100" align="center"><?php echo $_SESSION["10TCodeName"]["10_CodeName1_0"] ?></td>
					<td width="200" align="left"><?php if($_SESSION["T04_Sessho_StudentID" .$i]!=0){ ?><?php echo GetStudentName($_SESSION["T04_Sessho_StudentID" .$i]) ?><?php } ?></td>
					<td width="100" align="center" ><?php echo $_SESSION["09SCodeName"]["09_CodeName1_0"] ?></td>
					<td width="100" align="center" ><?php echo $_SESSION["10SCodeName"]["10_CodeName1_0"] ?></td>
					<td width="100" align="center" rowspan="2"><?php echo $_SESSION["TantoName"]["Name1_0"] ?></td>
				<?php }elseif($_SESSION["Kensaku_KUBUN"]=="1"){ ?>
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
				<?php }elseif($_SESSION["Kensaku_KUBUN"]=="2"){ ?>
					<td width="30" align="center" rowspan="2"><input type="submit" name="T04_No_<?php echo $i ?>" style="cursor: pointer" value="<?php echo $no ?>" /></td>
					<td width="100" align="center" rowspan="2"><?php echo $_SESSION["T04_Sessho_SesshoDay" .$i] ?>(<?php echo $Youbi?>)</td>
					<td width="200" align="left" rowspan="2">
						<B><?php if($_SESSION["T04_Sessho_JyotaiKubun" .$i]=="0"){ ?> <?php echo $_SESSION["03CodeName"]["03_CodeName1_0"] ?> <?php }else{ ?> <?php echo $_SESSION["04CodeName"]["04_CodeName1_0"] ?> <?php } ?></B><BR>
						<font color="#0000FF"><?php echo $_SESSION["T04_Sessho_YakusokuDay" .$i] ?></font>
					</td>
					<td width="200" align="left" <?php if($_SESSION["T04_Sessho_JyotaiKubun" .$i]=="1"){ ?> bgcolor="#00FF00"<?php } ?>><?php if($_SESSION["T04_Sessho_StudentID" .$i]!=0){ ?><?php echo $_SESSION["T04_Sessho_StudentID" .$i] ?>-<?php echo $_SESSION["T04_Sessho_AtenaSeq" .$i] ?><?php echo GetStudentName($_SESSION["T04_Sessho_StudentID" .$i]) ?><?php } ?></td>
					<td width="100" align="center" <?php if($_SESSION["T04_Sessho_JyotaiKubun" .$i]=="1"){ ?> bgcolor="#00FF00"<?php } ?>><?php echo $_SESSION["09SCodeName"]["09_CodeName1_0"] ?></td>
					<td width="100" align="center" <?php if($_SESSION["T04_Sessho_JyotaiKubun" .$i]=="1"){ ?> bgcolor="#00FF00"<?php } ?>><?php echo $_SESSION["10SCodeName"]["10_CodeName1_0"] ?></td>
					<td width="200" align="left" <?php if($_SESSION["T04_Sessho_JyotaiKubun" .$i]=="0"){ ?> bgcolor="#AFEEEE"<?php } ?>><?php if($_SESSION["T04_Sessho_TeacherID" .$i]!=0){ ?><?php echo $_SESSION["T04_Sessho_TeacherID" .$i] ?><?php echo GetTeacherName($_SESSION["T04_Sessho_TeacherID" .$i]) ?><?php } ?></td>
					<td width="100" align="center" <?php if($_SESSION["T04_Sessho_JyotaiKubun" .$i]=="0"){ ?> bgcolor="#AFEEEE"<?php } ?>><?php echo $_SESSION["09TCodeName"]["09_CodeName1_0"] ?></td>
					<td width="100" align="center" <?php if($_SESSION["T04_Sessho_JyotaiKubun" .$i]=="0"){ ?> bgcolor="#AFEEEE"<?php } ?>><?php echo $_SESSION["10TCodeName"]["10_CodeName1_0"] ?></td>
					<td width="100" align="center" rowspan="2"><?php echo $_SESSION["TantoName"]["Name1_0"] ?></td>
					<input type="hidden" name="T04_Sessho_JyotaiKubun<?php echo $i ?>" value="<?php echo $_SESSION["T04_Sessho_JyotaiKubun" .$i]; ?>">
					<input type="hidden" name="T04_Sessho_StudentID<?php echo $i ?>" value="<?php echo $_SESSION["T04_Sessho_StudentID" .$i]; ?>">
					<input type="hidden" name="T04_Sessho_AtenaSeq<?php echo $i ?>" value="<?php echo $_SESSION["T04_Sessho_AtenaSeq" .$i]; ?>">
					<input type="hidden" name="T04_Sessho_TeacherID<?php echo $i ?>" value="<?php echo $_SESSION["T04_Sessho_TeacherID" .$i]; ?>">

				<?php } ?>
			</tr>
			<tr>
				<?php if($_SESSION["Kensaku_KUBUN"]=="2"){ ?>
					<td id="midashi_Ichiran" width="450" align="left" colspan="6">
				<?php }else{ ?>
					<td id="midashi_Ichiran" width="450" align="left" colspan="5">
				<?php } ?>
					<B><?php echo $_SESSION["T04_Sessho_Naiyo" .$i] ?></B>
					<BR>
					<B><?php if($_SESSION["T04_Sessho_HikitugiKubun" .$i]=="1"){ ?><a href="#" onClick="disp(<?php echo $i?>);return false"><font color="#FF0000"><?php }else{ ?><font color="#0000FF"><?php } ?><?php echo $_SESSION["T04_Sessho_Hikitugi" .$i]?><?php if($_SESSION["T04_Sessho_HikitugiKubun" .$i]=="1"){ ?></font></a><?php }else{ ?></font><?php } ?></B>
				</td>
			</tr>
			<?php $no++; ?>
		<?php } ?>
	</table>
<?php if($_SESSION["Kensaku_KUBUN"]!="2"){ ?>
</div>
<?php } ?>
</div>
<BR><BR>
</form>
</body>
</CENTER>
</html>