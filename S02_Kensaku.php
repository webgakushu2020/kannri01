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
		 	ModoruShori($_SESSION["S02_kensaku_RPID"]);
			exit;
		}
	}

	if(isset($_POST['submitter'])){
		if($_POST['submitter']=="NendUpd"){
			$_SESSION["ShoriID"]="NendUpd";
			if($_SESSION["K_StudentOld1"]==""){
				$_SESSION["EMSG"]="学年更新する場合は学年を指定して検索しなおしてください。";
			}else{
				$_SESSION["ShoriID"]="NendUpd";
				$_SESSION["UpdFlg"]=1;
			}
		}
	}
	if(isset($_POST['submitter'])){
		if($_POST['submitter']=="OldUpd"){
			$_SESSION["ShoriID"]="OldUpd";
		}
	}
	if(isset($_POST['submitter'])){
		if($_POST['submitter']=="GakunenUpd"){
			$_SESSION["ShoriID"]="GakunenUpd";
		}
	}
	if(isset($_POST['submitter'])){
		if($_POST['submitter']=="EndUpd"){
			$_SESSION["ShoriID"]="EndUpd";
		}
	}
	if(isset($_POST['submitter'])){
		if($_POST['submitter']=="Kanryo"){
			$_SESSION["ShoriID"]="Kanryo";
		}
	}

	// 生徒選択処理
//	if($_SESSION["S02_DataCount"] == 1){
//		$Location = "S03_StudentInfo.php?MODE=UPD&RPID=S02_Kensaku&KEY1=" . $_SESSION["S02_StudentID0"] . "&KUBUN=1" . "&SEQ=" .$_SESSION["S02_Seq0"];
//	 	header("Location: {$Location}");
//		exit;
//	}else{
		for ($m = 0; $m < $_SESSION["S02_DataCount"]; $m++){
			if(isset($_POST["No_" . $m])){
				$_SESSION["T03_StudentID"]=$_POST['S02_StudentID' . $m];
				$Location = "S03_index.php?MODE=UPD&RPID=S02_Kensaku&KEY1=" . $_POST['S02_StudentID' . $m] . "&KUBUN=1" . "&SEQ=" .$_POST['S02_Seq' . $m];
			 	header("Location: {$Location}");
				exit;
			}
		}
//	}

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
			$_SESSION["S02_kensaku_RPID"] = $_GET['RPID'];
		      	//print($_SESSION["S02_kensaku_RPID"]);
		}
		if(isset($_GET['MODE'])) {
			if($_GET['MODE'] != "Modoru"){
				$_SESSION["S02_kensaku_MODE"] = $_GET['MODE'];
				$_SESSION["ShoriID"]=$_GET['MODE'];
			      	//print($_SESSION["K_kensaku_MODE"] . "<BR>");
			}
		}
		if(isset($_GET['KEY1'])) {
			$_SESSION["Kensaku_KEY1"] = $_GET['KEY1'];
		}

		switch ($_SESSION["ShoriID"]){
			case 'KEN':
				$CodeData = array();
				$CodeData = GetCodeData("学年","","",1);
				$_SESSION["13CodeData"]=$CodeData;
				$_SESSION["EMSG"]="";
				$_SESSION["UpdFlg"]="0";
				$_SESSION["OldNo"]=0;
				//SessionClear();
				GetData();

				break;
			case 'OldUpd':
				$EMSG = CheckShori_old();
				if($EMSG==""){
					for($i=0; $i<$_SESSION["S02_DataCount"]; $i++){
						if($_SESSION["GakunenCbx_" .$i] == "1"){
							$EMSG = UpdShori_old($_POST['S02_StudentID' . $i],$_POST['S02_Seq' . $i]);
							$_SESSION["EMSG"]=$EMSG;
							if($EMSG=="更新しました。"){
								$_SESSION["S02_updateflg" .$i] = 1;
							}else{
								exit;
							}
						}
					}
				}
				break;
			case 'GakunenUpd':
				$EMSG = CheckShori_Gakunen();
				$_SESSION["EMSG"]=$EMSG;
				break;
			case 'EndUpd':
				for($i=0; $i<$_SESSION["S02_DataCount"]; $i++){
					if($_SESSION["GakunenCbx_" .$i] == "1"){
						$EMSG = Update_Gakunen($_POST['S02_StudentID' . $i],$_POST['S02_Seq' . $i]);
						$_SESSION["EMSG"]=$EMSG;
						if($EMSG=="更新しました。"){
							$_SESSION["S02_greadflg" .$i] = 1;
							$_SESSION["UpdFlg"]="1";
						}else{
							exit;
						}
					}
				}
				break;
			case 'Kanryo':
				$EMSG = CheckShori_Kanryo();

				$_SESSION["EMSG"]=$EMSG;
				if($EMSG==""){
					for($i=0; $i<$_SESSION["S02_DataCount"]; $i++){
						if($_SESSION["NendoUpdCbx_" .$i] == "1"){
							$EMSG = UpdShori_Kanryo($_POST['S02_StudentID' . $i],$_POST['S02_Seq' . $i]);
							$_SESSION["EMSG"]=$EMSG;
							if($EMSG=="更新しました。"){
								$_SESSION["S02_updateflg" .$i] = 1;
							}else{
								exit;
							}
						}
					}
				}

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

	$_SESSION["K_StudentID"] = "";
	$_SESSION["K_StudentName"] = "";
	$_SESSION["K_StudentKana"] = "";
	$_SESSION["K_StudentKen"] = "";
	$_SESSION["K_StudentShi"] = "";
	$_SESSION["K_StudentKu"] = "";
	$_SESSION["K_StudentAdd"] = "";
	$_SESSION["K_StudentTel"] = "";
	$_SESSION["K_StudentOld1"] = "";
	$_SESSION["K_StudentOld2"] = "";
	$_SESSION["K_StudentSei"] = "";
	$_SESSION["K_StudentStartDay1"] = "";
	$_SESSION["K_StudentStartDay2"] = "";
	$_SESSION["K_ShokaiDay"] = "";
	$_SESSION["K_Jyotai"] = "";

	$CodeData = array();
	$CodeData = GetCodeData("現状","","",1);
	$_SESSION["23CodeData"]=$CodeData;
	
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

		$query = "Select a.StudentID , a.Seq ,a.Name1, a.old, a.gread, a.oldflg, a.greadflg, a.updateflg,";
		$query = $query . " b.BirthDay, b.BirthDay,b.Seibetu, b.Tel1, b.Tel2, b.Tel3,b.Tel_Kubun1, b.Tel_Kubun2, b.Tel_Kubun3, ";
		$query = $query . " b.Add_Ken_Code1, b.Add_ken1, b.Add_shi1, b.Add_ku1, b.Add_cho1, ";
		$query = $query . " a.EntryTime, b.Mail1,b.Mail2,b.Mail3,b.EntryDay, ";
		$query = $query . " c.Genjyo1,c.Genjyo2,c.Genjyo3,c.Genjyo4,c.Genjyo5,c.Genjyo6,c.Genjyo7,c.Genjyo8,c.Genjyo9,c.Genjyo10,c.Genjyo99 ";
		$query = $query . " FROM S_AtenaInfo as a inner join";
		$query = $query . " S_KihonInfo as b on";
		$query = $query . " a.StudentID=b.StudentID";
		$query = $query . " and a.Seq=b.AtenaSeq";
		$query = $query . " inner join S_TourokuInfo as c on";
		$query = $query . " a.StudentID=c.StudentID";
		$query = $query . " and a.Seq=c.AtenaSeq";

//		if($_SESSION["Kensaku_KEY1"]!=""){
//			if($query2 == ""){
//				$query2 = $query2 . " Where a.StudentID=" . $_SESSION["Kensaku_KEY1"];
//			}else{
//				$query2 = $query2 . " And a.StudentID=" . $_SESSION["Kensaku_KEY1"];
//			}
//		}

		if($_SESSION["K_StudentID"]!=""){
			if($query2 = ""){
				$query2 = $query2 . " Where a.StudentID=" . $_SESSION["K_StudentID"];
			}else{
				$query2 = $query2 . " And a.StudentID=" . $_SESSION["K_StudentID"];
			}
		}
		if($_SESSION["K_StudentName"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where a.Name1 like '%" . $_SESSION["K_StudentName"] . "%'";
			}else{
				$query2 = $query2 . " And a.Name1 like '%" . $_SESSION["K_StudentName"] . "%'";
			}
		}
		if($_SESSION["K_StudentKana"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where a.Name2 like '%" . $_SESSION["K_StudentKana"] . "%'";
			}else{
				$query2 = $query2 . " And a.Name2 like '%" . $_SESSION["K_StudentKana"] . "%'";
			}
		}
		if($_SESSION["K_StudentKen"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where b.Add_Ken_Code1 ='" . $_SESSION["K_StudentKen"] . "'";
			}else{
				$query2 = $query2 . " And b.Add_Ken_Code1 ='" . $_SESSION["K_StudentKen"] . "'";
			}
		}
		if($_SESSION["K_StudentShi"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where b.Add_shi1 like '%" . $_SESSION["K_StudentShi"] . "%'";
			}else{
				$query2 = $query2 . " And b.Add_shi1 like '%" . $_SESSION["K_StudentShi"] . "%'";
			}
		}
		if($_SESSION["K_StudentKu"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where b.Add_ku1 like '%" . $_SESSION["K_StudentKu"] . "%'";
			}else{
				$query2 = $query2 . " And b.Add_ku1 like '%" . $_SESSION["K_StudentKu"] . "%'";
			}
		}
		if($_SESSION["K_StudentAdd"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where b.Add_cho1 like '%" . $_SESSION["K_StudentAdd"] . "%'";
			}else{
				$query2 = $query2 . " And b.Add_cho1 like '%" . $_SESSION["K_StudentAdd"] . "%'";
			}
		}
		if($_SESSION["K_StudentTel"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where (b.Tel1 ='" . $_SESSION["K_StudentTel"] . "'";
				$query2 = $query2 . " OR b.Tel2 ='" . $_SESSION["K_StudentTel"] . "'";
				$query2 = $query2 . " OR b.Tel3 ='" . $_SESSION["K_StudentTel"] . "')";
			}else{
				$query2 = $query2 . " And (b.Tel1 ='" . $_SESSION["K_StudentTel"] . "'";
				$query2 = $query2 . " OR b.Tel2 ='" . $_SESSION["K_StudentTel"] . "'";
				$query2 = $query2 . " OR b.Tel3 ='" . $_SESSION["K_StudentTel"] . "')";
			}
		}
		if($_SESSION["K_StudentOld1"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where a.gread = '" . $_SESSION["K_StudentOld1"] . "'";
			}else{
				$query2 = $query2 . " And a.gread = '" . $_SESSION["K_StudentOld1"] . "'";
			}

		}
		if($_SESSION["K_StudentOld2"]!=""){
//			$start = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 1, date('Y') - $_SESSION["K_StudentOld2"] - 1));
//			$end = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 1, date('Y') - $_SESSION["K_StudentOld2"]));
//			if($query2 == ""){
//				$query2 = $query2 . " Where b.Birthday >= '" . $start . "' and b.Birthday <='" . $end . "'";
//			}else{
//				$query2 = $query2 . " And b.Birthday >= '" . $start . "' and b.Birthday <='" . $end . "'";
//			}
			if($query2 == ""){
				$query2 = $query2 . " Where a.old = '" . $_SESSION["K_StudentOld2"] . "'";
			}else{
				$query2 = $query2 . " And a.old = '" . $_SESSION["K_StudentOld2"] . "'";
			}

		}
		if($_SESSION["K_StudentSei"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where b.Seibetu = '" . $_SESSION["K_StudentSei"] . "'";
			}else{
				$query2 = $query2 . " And b.Seibetu = '" . $_SESSION["K_StudentSei"] . "'";
			}
		}
		if($_SESSION["K_StudentStartDay1"]!="" && $_SESSION["K_StudentStartDay2"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where b.EntryDay >= '" . $_SESSION["K_StudentStartDay1"] . "' and b.EntryDay <= '" . $_SESSION["K_StudentStartDay2"] . "'";
			}else{
				$query2 = $query2 . " And b.EntryDay >= '" . $_SESSION["K_StudentStartDay1"] . "' and b.EntryDay <= '" . $_SESSION["K_StudentStartDay2"] . "'";
			}
		}elseif($_SESSION["K_StudentStartDay1"]!="" && $_SESSION["K_StudentStartDay2"]==""){
			if($query2 == ""){
				$query2 = $query2 . " Where b.EntryDay >= '" . $_SESSION["K_StudentStartDay1"] . "'";
			}else{
				$query2 = $query2 . " And b.EntryDay >= '" . $_SESSION["K_StudentStartDay1"] . "'";
			}
		}elseif($_SESSION["K_StudentStartDay1"]=="" && $_SESSION["K_StudentStartDay2"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where b.EntryDay <= '" . $_SESSION["K_StudentStartDay2"] . "'";
			}else{
				$query2 = $query2 . " And b.EntryDay <= '" . $_SESSION["K_StudentStartDay2"] . "'";
			}
		}
		if($_SESSION["K_StudentGakunenKoshin"]!=""){
			if($_SESSION["K_StudentGakunenKoshin"]!=2){
				if($query2 == ""){
					$query2 = $query2 . " Where a.greadflg = '" . $_SESSION["K_StudentGakunenKoshin"] . "'";
				}else{
					$query2 = $query2 . " And a.greadflg = '" . $_SESSION["K_StudentGakunenKoshin"] . "'";
				}
			}
		}
		if($_SESSION["K_StudentNendoKoshin"]!=""){
			if($_SESSION["K_StudentNendoKoshin"]!="2"){
				if($query2 == ""){
					$query2 = $query2 . " Where a.updateflg = '" . $_SESSION["K_StudentNendoKoshin"] . "'";
				}else{
					$query2 = $query2 . " And a.updateflg = '" . $_SESSION["K_StudentNendoKoshin"] . "'";
				}
			}
		}
		if($_SESSION["K_GenjyoHantei"]==1 || $_SESSION["K_GenjyoHantei"]==""){
			if($_SESSION["K_Genjyo1"]==1){
				if($query2 == ""){
					$query2 = $query2 . " Where c.Genjyo1 = '" . $_SESSION["K_Genjyo1"] . "'";
				}else{
					$query2 = $query2 . " And c.Genjyo1 = '" . $_SESSION["K_Genjyo1"] . "'";
				}
			}
			if($_SESSION["K_Genjyo2"]==1){
				if($query2 == ""){
					$query2 = $query2 . " Where c.Genjyo2 = '" . $_SESSION["K_Genjyo2"] . "'";
				}else{
					$query2 = $query2 . " And c.Genjyo2 = '" . $_SESSION["K_Genjyo2"] . "'";
				}
			}
			if($_SESSION["K_Genjyo3"]==1){
				if($query2 == ""){
					$query2 = $query2 . " Where c.Genjyo3 = '" . $_SESSION["K_Genjyo3"] . "'";
				}else{
					$query2 = $query2 . " And c.Genjyo3 = '" . $_SESSION["K_Genjyo3"] . "'";
				}
			}
			if($_SESSION["K_Genjyo4"]==1){
				if($query2 == ""){
					$query2 = $query2 . " Where c.Genjyo4 = '" . $_SESSION["K_Genjyo4"] . "'";
				}else{
					$query2 = $query2 . " And c.Genjyo4 = '" . $_SESSION["K_Genjyo4"] . "'";
				}
			}
			if($_SESSION["K_Genjyo5"]==1){
				if($query2 == ""){
					$query2 = $query2 . " Where c.Genjyo5 = '" . $_SESSION["K_Genjyo5"] . "'";
				}else{
					$query2 = $query2 . " And c.Genjyo5 = '" . $_SESSION["K_Genjyo5"] . "'";
				}
			}
			if($_SESSION["K_Genjyo6"]==1){
				if($query2 == ""){
					$query2 = $query2 . " Where c.Genjyo6 = '" . $_SESSION["K_Genjyo6"] . "'";
				}else{
					$query2 = $query2 . " And c.Genjyo6 = '" . $_SESSION["K_Genjyo6"] . "'";
				}
			}
			if($_SESSION["K_Genjyo7"]==1){
				if($query2 == ""){
					$query2 = $query2 . " Where c.Genjyo7 = '" . $_SESSION["K_Genjyo7"] . "'";
				}else{
					$query2 = $query2 . " And c.Genjyo7 = '" . $_SESSION["K_Genjyo7"] . "'";
				}
			}
			if($_SESSION["K_Genjyo8"]==1){
				if($query2 == ""){
					$query2 = $query2 . " Where c.Genjyo8 = '" . $_SESSION["K_Genjyo8"] . "'";
				}else{
					$query2 = $query2 . " And c.Genjyo8 = '" . $_SESSION["K_Genjyo8"] . "'";
				}
			}
			if($_SESSION["K_Genjyo9"]==1){
				if($query2 == ""){
					$query2 = $query2 . " Where c.Genjyo9 = '" . $_SESSION["K_Genjyo9"] . "'";
				}else{
					$query2 = $query2 . " And c.Genjyo9 = '" . $_SESSION["K_Genjyo9"] . "'";
				}
			}
			if($_SESSION["K_Genjyo10Data"]>0){
				if($query2 == ""){
					$query2 = $query2 . " Where c.Genjyo10 > 0";
				}else{
					$query2 = $query2 . " And c.Genjyo10 > 0";
				}
			}
			if($_SESSION["K_Genjyo99"]==1){
				if($query2 == ""){
					$query2 = $query2 . " Where c.Genjyo99 = '" . $_SESSION["K_Genjyo99"] . "'";
				}else{
					$query2 = $query2 . " And c.Genjyo99 = '" . $_SESSION["K_Genjyo99"] . "'";
				}
			}
		}elseif($_SESSION["K_GenjyoHantei"]==2){
			if($_SESSION["K_Genjyo1"]==1){
				if($query2 == ""){
					$query2 = $query2 . " Where (c.Genjyo1 = '" . $_SESSION["K_Genjyo1"] . "'";
				}else{
					$query2 = $query2 . " And (c.Genjyo1 = '" . $_SESSION["K_Genjyo1"] . "'";
				}
				$GenjyoHanteiFlg=1;
			}
			if($_SESSION["K_Genjyo2"]==1){
				if($query2 == ""){
					$query2 = $query2 . " Where (c.Genjyo2 = '" . $_SESSION["K_Genjyo2"] . "'";
				}else{
					if($GenjyoHanteiFlg==1){
						$query2 = $query2 . " or c.Genjyo2 = '" . $_SESSION["K_Genjyo2"] . "'";
					}else{
						$query2 = $query2 . " And (c.Genjyo2 = '" . $_SESSION["K_Genjyo2"] . "'";
					}
				}
				$GenjyoHanteiFlg=1;
			}
			if($_SESSION["K_Genjyo3"]==1){
				if($query2 == ""){
					$query2 = $query2 . " Where (c.Genjyo3 = '" . $_SESSION["K_Genjyo3"] . "'";
				}else{
					if($GenjyoHanteiFlg==1){
						$query2 = $query2 . " or c.Genjyo3 = '" . $_SESSION["K_Genjyo3"] . "'";
					}else{
						$query2 = $query2 . " And (c.Genjyo3 = '" . $_SESSION["K_Genjyo3"] . "'";
					}
				}
				$GenjyoHanteiFlg=1;
			}
			if($_SESSION["K_Genjyo4"]==1){
				if($query2 == ""){
					$query2 = $query2 . " Where (c.Genjyo4 = '" . $_SESSION["K_Genjyo4"] . "'";
				}else{
					if($GenjyoHanteiFlg==1){
						$query2 = $query2 . " or c.Genjyo4 = '" . $_SESSION["K_Genjyo4"] . "'";
					}else{
						$query2 = $query2 . " And (c.Genjyo4 = '" . $_SESSION["K_Genjyo4"] . "'";
					}
				}
				$GenjyoHanteiFlg=1;
			}
			if($_SESSION["K_Genjyo5"]==1){
				if($query2 == ""){
					$query2 = $query2 . " Where (c.Genjyo5 = '" . $_SESSION["K_Genjyo5"] . "'";
				}else{
					if($GenjyoHanteiFlg==1){
						$query2 = $query2 . " or c.Genjyo5 = '" . $_SESSION["K_Genjyo5"] . "'";
					}else{
						$query2 = $query2 . " And (c.Genjyo5 = '" . $_SESSION["K_Genjyo5"] . "'";
					}
				}
				$GenjyoHanteiFlg=1;
			}
			if($_SESSION["K_Genjyo6"]==1){
				if($query2 == ""){
					$query2 = $query2 . " Where (c.Genjyo6 = '" . $_SESSION["K_Genjyo6"] . "'";
				}else{
					if($GenjyoHanteiFlg==1){
						$query2 = $query2 . " or c.Genjyo6 = '" . $_SESSION["K_Genjyo6"] . "'";
					}else{
						$query2 = $query2 . " And (c.Genjyo6 = '" . $_SESSION["K_Genjyo6"] . "'";
					}
				}
				$GenjyoHanteiFlg=1;
			}
			if($_SESSION["K_Genjyo7"]==1){
				if($query2 == ""){
					$query2 = $query2 . " Where (c.Genjyo7 = '" . $_SESSION["K_Genjyo7"] . "'";
				}else{
					if($GenjyoHanteiFlg==1){
						$query2 = $query2 . " or c.Genjyo7 = '" . $_SESSION["K_Genjyo7"] . "'";
					}else{
						$query2 = $query2 . " And (c.Genjyo7 = '" . $_SESSION["K_Genjyo7"] . "'";
					}
				}
				$GenjyoHanteiFlg=1;
			}
			if($_SESSION["K_Genjyo8"]==1){
				if($query2 == ""){
					$query2 = $query2 . " Where (c.Genjyo8 = '" . $_SESSION["K_Genjyo8"] . "'";
				}else{
					if($GenjyoHanteiFlg==1){
						$query2 = $query2 . " or c.Genjyo8 = '" . $_SESSION["K_Genjyo8"] . "'";
					}else{
						$query2 = $query2 . " And (c.Genjyo8 = '" . $_SESSION["K_Genjyo8"] . "'";
					}
				}
				$GenjyoHanteiFlg=1;
			}
			if($_SESSION["K_Genjyo9"]==1){
				if($query2 == ""){
					$query2 = $query2 . " Where (c.Genjyo9 = '" . $_SESSION["K_Genjyo9"] . "'";
				}else{
					if($GenjyoHanteiFlg==1){
						$query2 = $query2 . " or c.Genjyo9 = '" . $_SESSION["K_Genjyo9"] . "'";
					}else{
						$query2 = $query2 . " And (c.Genjyo9 = '" . $_SESSION["K_Genjyo9"] . "'";
					}
				}
				$GenjyoHanteiFlg=1;
			}
			if($_SESSION["K_Genjyo10Data"]>0){
				if($query2 == ""){
					$query2 = $query2 . " Where (c.Genjyo10 > 0";
				}else{
					if($GenjyoHanteiFlg==1){
						$query2 = $query2 . " or c.Genjyo10 > 0";
					}else{
						$query2 = $query2 . " And (c.Genjyo10 > 0";
					}
				}
				$GenjyoHanteiFlg=1;
			}
			if($_SESSION["K_Genjyo99"]==1){
				if($query2 == ""){
					$query2 = $query2 . " Where (c.Genjyo99 = '" . $_SESSION["K_Genjyo99"] . "'";
				}else{
					if($GenjyoHanteiFlg==1){
						$query2 = $query2 . " or c.Genjyo99 = '" . $_SESSION["K_Genjyo99"] . "'";
					}else{
						$query2 = $query2 . " And (c.Genjyo99 = '" . $_SESSION["K_Genjyo99"] . "'";
					}
				}
			}
			if($GenjyoHanteiFlg==1){
				$query2 = $query2 . ")";
			}
		}

		$query2 = $query2 . " Order by a.StudentID , a.Seq";


		$query = $query . $query2;
//print($query);
		$result = $mysqli->query($query);

		if (!$result) {
			print('クエリーが失敗しました。' . $mysqli->error);
			$mysqli->close();
			exit();
		}
		
		$data = array();
		$i = 0;
		$m = 0;
		while($arr_item = $result->fetch_assoc()){
			$GenjyoData = "";

			//レコード内の各フィールド名と値を順次参照
			foreach($arr_item as $key => $value){
				//フィールド名と値を表示
				$data[$i][$key] = $value;
			}

			$_SESSION["S02_StudentID" .$i]=$data[$i]['StudentID'];
			$_SESSION["S02_Seq" .$i]=$data[$i]['Seq'];
			$_SESSION["S02_Name1" .$i]=$data[$i]['Name1'];
			$_SESSION["S02_gread" .$i]=$data[$i]['gread'];
			$_SESSION["S02_oldflg" .$i]=$data[$i]['oldflg'];
			$_SESSION["S02_greadflg" .$i]=$data[$i]['greadflg'];
			$_SESSION["S02_updateflg" .$i]=$data[$i]['updateflg'];
			if($_SESSION["S02_greadflg" .$i]=="0"){
				$_SESSION["GakunenCbx_" .$i]=1;
			}else{
				$_SESSION["GakunenCbx_" .$i]=0;
			}
//			if($_SESSION["S02_updateflg" .$i]=="0"){
//				$_SESSION["NendoUpdCbx_" .$i]=1;
//			}else{
				$_SESSION["NendoUpdCbx_" .$i]=0;
//			}
//			$_SESSION["NendoUpdCbx_" .$i]=0;
			$_SESSION["S02_BirthDay" .$i]=$data[$i]['BirthDay'];
			$_SESSION["S02_Seibetu" .$i]=$data[$i]['Seibetu'];
			$_SESSION["S02_Tel1" .$i]=$data[$i]['Tel1'];
			$_SESSION["S02_Tel2" .$i]=$data[$i]['Tel2'];
			$_SESSION["S02_Tel3" .$i]=$data[$i]['Tel3'];
			$_SESSION["S02_Tel_Kubun1" .$i]=$data[$i]['Tel_Kubun1'];
			$_SESSION["S02_Tel_Kubun2" .$i]=$data[$i]['Tel_Kubun2'];
			$_SESSION["S02_Tel_Kubun3" .$i]=$data[$i]['Tel_Kubun3'];
			$_SESSION["S02_Add_Ken_Code1" .$i]=$data[$i]['Add_Ken_Code1'];
			$_SESSION["S02_Add_ken1" .$i]=$data[$i]['Add_ken1'];
			$_SESSION["S02_Add_shi1" .$i]=$data[$i]['Add_shi1'];
			$_SESSION["S02_Add_ku1" .$i]=$data[$i]['Add_ku1'];
			$_SESSION["S02_Add_cho1" .$i]=$data[$i]['Add_cho1'];
			$_SESSION["S02_EntryTime" .$i]=$data[$i]['EntryTime'];
			$_SESSION["S02_EntryDay" .$i]=$data[$i]['EntryDay'];
			$_SESSION["S02_Mail1" .$i]=$data[$i]['Mail1'];
			$_SESSION["S02_Mail2" .$i]=$data[$i]['Mail2'];
			$_SESSION["S02_Mail3" .$i]=$data[$i]['Mail3'];
			$_SESSION["S02_old" .$i] = $data[$i]['old'];
//print("23CodeData" . $_SESSION["23CodeData"]["23DataCount"] . "<BR>");
			for($m=0; $m < 10; $m++){
				$n = $m + 1;
				$_SESSION["S02_Genjyo" . $n . "_" .$i] = $data[$i]['Genjyo' . $n];
			}
			$_SESSION["S02_Genjyo99_" .$i] = $data[$i]['Genjyo99'];

			for($m=0; $m < 9; $m++){
				$n = $m + 1;
				if($_SESSION["S02_Genjyo" . $n . "_" .$i] == 1){
					if($_SESSION["23CodeData"]["23_Eda_" . $m] != 99){
						$GenjyoData = $GenjyoData. $_SESSION["23CodeData"]["23_CodeName1_" . $m] ."<BR>";
					}
				}
				//print($GenjyoData);
			}
				//print("----<BR>");
//print("S02_Genjyo10=" . $_SESSION["S02_Genjyo10_" .$i] . "<BR>");
			if($_SESSION["S02_Genjyo10_" .$i] >= 1){
				$S00_Genjyo10 = $_SESSION["S02_Genjyo10_" .$i];
//print($S00_Genjyo10 . "<BR>");
				$Gengyolen = strlen($_SESSION["S02_Genjyo10_" .$i]);
//print($Gengyolen . "<BR>");
				for($m=0; $m<$Gengyolen; $m++ ){
					$n = $m + 10;
					$_SESSION["S02_Genjyo" . $n . "_" . $i] = substr($S00_Genjyo10,$m,1);
//print(substr($S00_Genjyo10,$m,1). "<BR>");
//print($_SESSION["S02_Genjyo" . $n . "_" . $i] . "<BR>");
					if($_SESSION["S02_Genjyo" . $n . "_" . $i]==1){
						$n2 = $n -1;
						$GenjyoData = $GenjyoData. $_SESSION["23CodeData"]["23_CodeName1_" . $n2] ."<BR>";
					}
					//print("S02_Genjyo" . $m . "=" . _SESSION["S02_Genjyo" . $n . "_" . $i] . "<BR>");
				}
				//print($GenjyoData);
			}
				//print("----<BR>");

			if($_SESSION["S02_Genjyo99_" .$i] == 1){
				$n = $_SESSION["23CodeData"]["23DataCount"] - 1;
				$GenjyoData = $GenjyoData. $_SESSION["23CodeData"]["23_CodeName1_" . $n] ."<BR>";
				//print($GenjyoData);
			}
				//print("============<BR>");

			$_SESSION["S02_GenjyoData" .$i] = $GenjyoData;

			$query4 = "Select A.*,B.Name1,B.Name2 from T_TantoShosai as A inner join T_AtenaInfo as B on A.TeacherID=B.TeacherID ";
			$query4 = $query4 . " Where A.StudentID=" . $_SESSION["S02_StudentID" .$i];
			$query4 = $query4 . " And A.AtenaSeq=" . $_SESSION["S02_Seq" .$i];
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
				$_SESSION["S02_KeiyakuFlg" . $i] = "契約有";
			}else{
				if($KeiyakuCnt > 0){
					$_SESSION["S02_KeiyakuFlg" . $i] = "実績有";
				}
			}
			$_SESSION["S02_TeacherData" . $i] = $StudentData;

			$_SESSION["S02_KeiyakuDataCount"] = $m;

			$i++;
		}
		$_SESSION["S02_DataCount"] = $i;

	 	// データベースの切断
		$mysqli->close();

}
//-----------------------------------------------------------
//	チェック処理
//-----------------------------------------------------------
Function CheckShori_Gakunen(){
$CbxFlg=0;
$OldNo=0;

	for($i=0; $i<$_SESSION["S02_DataCount"]; $i++){
		if(isset($_POST['GakunenCbx_' . $i])){
			$_SESSION["GakunenCbx_" . $i]=1;
		}else{
			$_SESSION["GakunenCbx_" . $i]=0;
		}
//		$_SESSION["GakunenCbx_" .$i] = $_POST['GakunenCbx_' . $i];
		if($_SESSION["GakunenCbx_" .$i] == "1"){
			if($CbxFlg == 0){
				$Gread = $_SESSION["S02_gread" .$i];
				$Gread2 = $Gread + 1;
				$_SESSION["Gread2"] = $Gread2;
				for($dataidx=0; $dataidx < $_SESSION["13CodeData"]["13DataCount"]; $dataidx++){
					if($Gread == $_SESSION["13CodeData"]["13_Eda_" . $dataidx]){
						$GreadName = $_SESSION["13CodeData"]["13_CodeName2_" . $dataidx];
						$GreadNameNo = $_SESSION["13CodeData"]["13_Eda_" . $dataidx];						
					}
					if($Gread2 == $_SESSION["13CodeData"]["13_Eda_" . $dataidx]){
						$GreadName2 = $_SESSION["13CodeData"]["13_CodeName2_" . $dataidx];
						$GreadNameNo2 = $_SESSION["13CodeData"]["13_Eda_" . $dataidx];
					}
				}
			}
			$CbxFlg++;
		}
	}
	if($CbxFlg == 0){
		$ErrMsg = "変更するデータが選択されていません。チェックしてください。";
	}else{
		if($GreadName2==""){
			$ErrMsg = "変更する学年がありません。確認してください。";
		}else{
			switch ($GreadNameNo2){
				case '03':
					$OldNo=7;
					break;
				case '04':
					$OldNo=8;
					break;
				case '05':
					$OldNo=9;
					break;
				case '06':
					$OldNo=10;
					break;
				case '07':
					$OldNo=11;
					break;
				case '08':
					$OldNo=12;
					break;
				case '09':
					$OldNo=13;
					break;
				case '10':
					$OldNo=14;
					break;
				case '11':
					$OldNo=15;
					break;
				case '12':
					$OldNo=16;
					break;
				case '13':
					$OldNo=17;
					break;
				case '14':
					$OldNo=18;
					break;
				default:
					$OldNo=0;
					break;					
			}
			$_SESSION["OldNo"]=$OldNo;
			$ErrMsg = "学年を【" . $GreadName . "】から【" . $GreadName2 . "】へ更新します。<BR>";
			if($OldNo==0){
				$ErrMsg = $ErrMsg . "年齢は更新しません。<BR>";
			}else{
				$ErrMsg = $ErrMsg . "年齢を" . $OldNo . "歳へ更新します。<BR>";
			}
			$ErrMsg = $ErrMsg . "更新ボタンを押してください。";
			$_SESSION["UpdFlg"]="2";
		}
	}
	
	return $ErrMsg;
}
//-----------------------------------------------------------
//	チェック処理
//-----------------------------------------------------------
Function CheckShori_Kanryo(){
$CbxFlg = 0;
$ErrMsg="";

	for($i=0; $i<$_SESSION["S02_DataCount"]; $i++){
		if(isset($_POST['NendoUpdCbx_' . $i])){
			$_SESSION["NendoUpdCbx_" . $i]=1;
		}else{
			$_SESSION["NendoUpdCbx_" . $i]=0;
		}
//		if($_SESSION["NendoUpdCbx_" .$i] == "1"){
			$CbxFlg++;
//		}
//		if($CbxFlg==1){
//			break;
//		}
	}
	if($CbxFlg == 0){
		$ErrMsg = "完了するデータが選択されていません。チェックしてください。";
	}
	return $ErrMsg;
}

//-----------------------------------------------------------
//	学年更新
//-----------------------------------------------------------
Function Update_Gakunen($id,$seq){
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

	//学年更新
	$query = "UPDATE S_AtenaInfo SET ";
	$query = $query . " gread = '" . str_pad($_SESSION["Gread2"], 2, 0, STR_PAD_LEFT) . "'";
	$query = $query . " ,greadflg = '1'";
	$query = $query . " WHERE  StudentID = '" . $id . "'";
	$query = $query . " AND  Seq = '" . $seq . "'";
	$query = $query . " AND  greadflg = 0";

	$result = $mysqli->query($query);

	//print($query);

	if (!$result) {
		$ErrMSG = "クエリーが失敗しました。（S_AtenaInfoエラー）" . $mysqli->error;
		$ErrFlg = 1;
	}

	if($ErrFlg == 0){
		//年齢更新
		if($_SESSION["OldNo"]!=0){
			$query = "UPDATE S_AtenaInfo SET ";
			$query = $query . " old = '" . $_SESSION["OldNo"] . "'";
			$query = $query . " ,oldflg = '1'";
			$query = $query . " WHERE  StudentID = '" . $id . "'";
			$query = $query . " AND  Seq = '" . $seq . "'";
			$query = $query . " AND  oldflg = 0";

			$result = $mysqli->query($query);

			//print($query);

			if (!$result) {
				$ErrMSG = "クエリーが失敗しました。（S_AtenaInfoエラー）" . $mysqli->error;
				$ErrFlg = 1;
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
//	完了処理
//-----------------------------------------------------------
Function UpdShori_Kanryo($id,$seq){
//print("UpdShori_Kanryo" . "<BR>");

$ErrMSG="";

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

	//学年更新
	$query = "UPDATE S_AtenaInfo SET ";
	$query = $query . " updateflg = '1'";
	$query = $query . " WHERE  StudentID = '" . $id . "'";
	$query = $query . " AND  Seq = '" . $seq . "'";
	$query = $query . " AND  updateflg = 0";

	$result = $mysqli->query($query);

	//print($query);

	if (!$result) {
		$ErrMSG = "クエリーが失敗しました。（UpdShori_Kanryoエラー）" . $mysqli->error;
		$ErrFlg = 1;
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

?>

<script type="text/javascript" src="utility.js"></script>

<CENTER>
<body>

<form name="form1" method="post" action="S02_Kensaku.php">
	<div id="header0" class="item">
		<BR>
		<table border="0" width="100%"  bgcolor="<?php echo HEADER_COLOR ?>">
			<tr align="center">
				<td align="center">
					<h2>生徒検索画面</h2>
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
				<input type="button" id="print" name="print" style="cursor: pointer" value="印刷画面" />
				<input type="button" id="NendUpd" name="NendUpd" onClick="sbmfnc(this,<?php echo $_SESSION["TourokuFlg"] ?>)" style="cursor: pointer" value="年度更新" />
			</td>
		</tr>
	</table>
	<BR>
	<table border="0" width="100%">
		<font size="5" color="#ff0000"><?php echo $_SESSION["EMSG"] ?></font>
	</table>
	<BR><BR>
	<table border="0">
		<tr><td>
		<table border="1">
			<tr>
				<td width="10" height="30" align="center" bgcolor="#c0c0c0">No</td>
				<td width="50" align="center" bgcolor="#c0c0c0">生徒ID</td>
				<td width="50" align="center" bgcolor="#c0c0c0">枝番</td>
				<td width="100" align="center" bgcolor="#c0c0c0">生徒名</td>
				<?//php if($_SESSION["UpdFlg"]=="1"){ ?>
<!--
					<td width="100" align="center" bgcolor="#c0c0c0">
						<input type="button" id="OldUpd" name="OldUpd" onClick="sbmfnc(this,<?php echo $_SESSION["TourokuFlg"] ?>)" style="cursor: pointer" value="年齢更新" />
					</td>
-->
				<?//php }else{ ?>
				<td width="50" align="center" bgcolor="#c0c0c0">年齢</td>
					<?//php } ?>
				<td width="50" align="center" bgcolor="#c0c0c0">性別</td>
				<?php if($_SESSION["UpdFlg"]=="1"){ ?>
					<td width="100" align="center" bgcolor="#c0c0c0">
						<input type="button" id="GakunenUpd" name="GakunenUpd" onClick="sbmfnc(this,<?php echo $_SESSION["TourokuFlg"] ?>)" style="cursor: pointer" value="学年更新" />
					</td>
				<?php }elseif($_SESSION["UpdFlg"]=="2"){ ?>
					<td width="100" align="center" bgcolor="#FF0000">
						<input type="button" id="EndUpd" name="EndUpd" onClick="sbmfnc(this,<?php echo $_SESSION["TourokuFlg"] ?>)" style="cursor: pointer" value="更新" />
					</td>
				<?php }else{ ?>
					<td width="100" align="center" bgcolor="#c0c0c0">学年</td>
				<?php } ?>
				<td width="200" align="center" bgcolor="#c0c0c0">電話番号</td>
				<td width="400" align="center" bgcolor="#c0c0c0">住所</td>
				<td width="150" align="center" bgcolor="#c0c0c0">登録日</td>
				<?php if($_SESSION["UpdFlg"]=="1"){ ?>
						<td width="50" align="center" bgcolor="#c0c0c0">
								<input type="button" id="Kanryo" name="Kanryo" onClick="sbmfnc(this,<?php echo $_SESSION["TourokuFlg"] ?>)" style="cursor: pointer" value="完了" />
						</td>
				<?php } ?>
				<td width="200" align="center" bgcolor="#c0c0c0">契約</td>
				<td width="200" align="center" bgcolor="#c0c0c0">現状</td>

			</tr>
			<?php for($i=0; $i<$_SESSION["S02_DataCount"]; $i++){ ?>
				<tr <?php if($_SESSION["S02_updateflg" .$i] == "1"){ ?>bgcolor="#F6CEF5"<?php }?>>
					<td height="30" align="center"><input type="submit" name="No_<?php echo $i ?>" size="10" value="<?php echo $i+1 ?>"></td>
					<td align="center"><BR><?php echo $_SESSION["S02_StudentID" .$i] ?><BR><BR></td>
					<td align="center"><?php echo $_SESSION["S02_Seq" .$i] ?></td>
					<td align="center"><?php echo $_SESSION["S02_Name1" .$i] ?></td>
					<td align="center" <?php if($_SESSION["S02_oldflg" .$i] == "1"){ ?>bgcolor="#FFFF00"<?php }?>>
						<?php if($_SESSION["S02_old" .$i]==""){ }else{ echo $_SESSION["S02_old" .$i]; } ?>
					</td>
					<td align="center"><?php if($_SESSION["S02_Seibetu" .$i]=="1"){?>男<?php }elseif($_SESSION["S02_Seibetu" .$i]=="2"){?>女<?php }else{ ?><?php } ?></td>
					<td align="center" <?php if($_SESSION["S02_greadflg" .$i] == "1"){ ?>bgcolor="#FFFF00"<?php }?>>
						<?php if($_SESSION["UpdFlg"]=="1" || $_SESSION["UpdFlg"]=="2"){ ?>
							<input type="checkbox" name="GakunenCbx_<?php echo $i ?>" value="<?php echo $_SESSION["GakunenCbx_" .$i]?>" <?php if($_SESSION["GakunenCbx_" .$i] == "1"){?> CHECKED <?php } ?>>
						<?php } ?>
						<?php 
							for($dataidx=0; $dataidx < $_SESSION["13CodeData"]["13DataCount"]; $dataidx++){
								if($_SESSION["S02_gread" .$i] == $_SESSION["13CodeData"]["13_Eda_" . $dataidx]){
									echo $_SESSION["13CodeData"]["13_CodeName2_" . $dataidx];
								}
							}
						?>
					</td>
					<td align="center">
						<?php 
							$telkubun=0;
							for($dataidx=0; $dataidx < $_SESSION["14CodeData"]["14DataCount"]; $dataidx++){
								if($_SESSION["S02_Tel_Kubun1" .$i] == $_SESSION["14CodeData"]["14_Eda_" . $dataidx]){
									echo $_SESSION["14CodeData"]["14_CodeName2_" . $dataidx];
									$telkubun=1;
								}
							} 
						?>
						<?php if($telkubun==1){ ?>⇒<?php }?>
						<?php echo $_SESSION["S02_Tel1" .$i]  ?>
						<BR>
						<?php 
							$telkubun=0;
							for($dataidx=0; $dataidx < $_SESSION["14CodeData"]["14DataCount"]; $dataidx++){
								if($_SESSION["S02_Tel_Kubun2" .$i] == $_SESSION["14CodeData"]["14_Eda_" . $dataidx]){
									echo $_SESSION["14CodeData"]["14_CodeName2_" . $dataidx];
									$telkubun=1;
								}
							} 
						?>
						<?php if($telkubun==1){ ?>⇒<?php }?>
						<?php echo $_SESSION["S02_Tel2" .$i]  ?>
						<BR>
						<?php 
							$telkubun=0;
							for($dataidx=0; $dataidx < $_SESSION["14CodeData"]["14DataCount"]; $dataidx++){
								if($_SESSION["S02_Tel_Kubun3" .$i] == $_SESSION["14CodeData"]["14_Eda_" . $dataidx]){
									echo $_SESSION["14CodeData"]["14_CodeName2_" . $dataidx];
									$telkubun=1;
								}
							} 
						?>
						<?php if($telkubun==1){ ?>⇒<?php }?>
						<?php echo $_SESSION["S02_Tel3" .$i]  ?>
						<BR>
					</td>
					<td align="left"><?php echo $_SESSION["S02_Add_ken1" .$i] .$_SESSION["S02_Add_shi1" .$i] .$_SESSION["S02_Add_ku1" .$i] .$_SESSION["S02_Add_cho1" .$i]?></td>
					<td align="center"><?php if(is_null($_SESSION["S02_EntryDay" .$i])){ }else{ echo date('Y年n月j日', strtotime($_SESSION["S02_EntryDay" .$i])); } ?></td>
					<?php if($_SESSION["UpdFlg"]=="1"){ ?>
							<td align="center" <?php if($_SESSION["S02_updateflg" .$i] == "1"){ ?>bgcolor="#F6CEF5"<?php }?>>
								<input type="checkbox" name="NendoUpdCbx_<?php echo $i ?>" value="<?php echo $_SESSION["NendoUpdCbx_" .$i]?>" <?php if($_SESSION["NendoUpdCbx_" .$i] == "1"){?> CHECKED <?php } ?>>
							</td>
					<?php } ?>
					<td align="left"><?php echo $_SESSION["S02_TeacherData" .$i] ?></td>
					<td align="left"><?php echo $_SESSION["S02_GenjyoData" .$i] ?></td>

					<input type="hidden" name="S02_StudentID<?php echo $i ?>" value="<?php echo $_SESSION["S02_StudentID" .$i]; ?>">
					<input type="hidden" name="S02_Seq<?php echo $i ?>" value="<?php echo $_SESSION["S02_Seq" .$i]; ?>">

				</tr>
			<?php } ?>
		</table>
		</td><td>
		<table border="1">
			<tr>
				<td width="300" height="30" align="center" bgcolor="#c0c0c0">mailアドレス</td>
			</tr>
			<?php for($i=0; $i<$_SESSION["S02_DataCount"]; $i++){ ?>
				<tr>
					<td height="30" align="left">
						<?php echo $_SESSION["S02_Mail1" .$i] ?><BR>
						<?php echo $_SESSION["S02_Mail2" .$i] ?><BR>
						<?php echo $_SESSION["S02_Mail3" .$i] ?><BR>
					</td>
				</tr>
			<?php } ?>
		</table>
		</td></tr>
	</table>

</form>
</body>
</CENTER>
</html>