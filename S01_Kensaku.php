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
		 	ModoruShori($_SESSION["S01_Kensaku_RPID"]);
			exit;
		}
	}

	if(isset($_POST['K_StudentKen'])){
		$_SESSION["ShoriID"] = "KNSAKU_KEN";
		SaveShori();
		SelectShiKu();
	}
	if(isset($_POST['K_StudentShi'])){
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
			$_SESSION["S01_Kensaku_RPID"] = $_GET['RPID'];
		      	//print($_SESSION["K_kensaku_RPID"]);
		}
		if(isset($_GET['MODE'])) {
			if($_GET['MODE'] != "Modoru"){
				$_SESSION["S01_Kensaku_MODE"] = $_GET['MODE'];
				$_SESSION["ShoriID"]=$_GET['MODE'];
			      	//print($_SESSION["K_kensaku_MODE"] . "<BR>");
			}
		}

		switch ($_SESSION["ShoriID"]){
			case 'NEW':
				SessionClear();
				ShokiDataGet();
				$CodeData = array();
				$CodeData = GetCodeData("年代","","",1);
				$_SESSION["02CodeData"]=$CodeData;
				$CodeData2 = array();
				$CodeData2 = GetCodeData("状態生徒","","",1);
				$_SESSION["04CodeData"]=$CodeData2;
				$CodeData3 = array();
				$CodeData3 = GetCodeData("学年","","",1);
				$_SESSION["13CodeData"]=$CodeData3;

				break;

			case 'CLEAR':
				SessionClear();
				ShokiDataGet();
				break;

			case 'KENSAKU':

				$EMSG = K_CheckShori();
				if($EMSG == ""){
					SaveShori();

					// mysqlへの接続
					$mysqli = new mysqli(HOST, USER, PASS);
					if ($mysqli->connect_errno) {
						print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
						exit();
					   		}

					// データベースの選択
					$mysqli->select_db(DBNAME);
					$mysqli->set_charset("utf8");

					$query = "Select a.StudentID ,Count(*) AS CNT FROM S_AtenaInfo as a inner join";
					$query = $query . " S_KihonInfo as b on";
					$query = $query . " a.StudentID=b.StudentID";
					$query = $query . " and a.Seq=b.AtenaSeq";
					$query = $query . " inner join S_TourokuInfo as c on";
					$query = $query . " a.StudentID=c.StudentID";
					$query = $query . " and a.Seq=c.AtenaSeq";

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
//						$Old1 = mb_substr($_SESSION["K_StudentOld1"],0,2);
//						$Old2 = mb_substr($_SESSION["K_StudentOld1"],0,1);
//						$OldStart = mb_convert_kana($Old1, "n");
//						$OldEnd = mb_convert_kana($Old2, "n") . 9;
//						$start = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 1, date('Y') - $OldStart - 1));
//						$end = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 1, date('Y') - $OldEnd - 1));
//						if($query2 == ""){
//							$query2 = $query2 . " Where b.Birthday >= '" . $end . "' and b.Birthday <='" . $start . "'";
//						}else{
//							$query2 = $query2 . " And b.Birthday >= '" . $end . "' and b.Birthday <='" . $start . "'";
//						}

						if($query2 == ""){
							$query2 = $query2 . " Where a.gread = '" . $_SESSION["K_StudentOld1"] . "'";
						}else{
							$query2 = $query2 . " And a.gread = '" . $_SESSION["K_StudentOld1"] . "'";
						}

					}
					if($_SESSION["K_StudentOld2"]!=""){
//						$start = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 1, date('Y') - $_SESSION["K_StudentOld2"] - 1));
//						$end = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 1, date('Y') - $_SESSION["K_StudentOld2"]));
//						if($query2 == ""){
//							$query2 = $query2 . " Where b.Birthday >= '" . $start . "' and b.Birthday <='" . $end . "'";
//						}else{
//							$query2 = $query2 . " And b.Birthday >= '" . $start . "' and b.Birthday <='" . $end . "'";
//						}
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
						if($_SESSION["K_StudentNendoKoshin"]!=2){
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

					$query = $query . $query2;
print($query);
					$result = $mysqli->query($query);

					if (!$result) {
						print('クエリーが失敗しました。' . $mysqli->error);
						$mysqli->close();
						exit();
					}
					
					while ($row = $result->fetch_assoc()) {
						$_SESSION["db_Count"] = $row['CNT'];
						$_SESSION["StudentID"] = $row['StudentID'];
					}
					
					$_SESSION["K_KEY1"]=$_SESSION["StudentID"];

				 	// データベースの切断
					$mysqli->close();		

					if($_SESSION["db_Count"] == 0){
						$EMSG = "該当のデータがありません。";
					}else {
						header("Location:S02_Kensaku.php?MODE=KEN&RPID=S01_Kensaku&KEY1=" .$_SESSION["K_KEY1"]);
					}
				}

				break;
			case 'NEWSHORI':
				header("Location:S00_Atena01.php?MODE=NEW&RPID=S01_Kensaku");
				break;
			case 'INSERT':
				header("Location:S00_Atena02.php?MODE=NEW&RPID=S01_Kensaku");
				break;
		}	
	}
//-----------------------------------------------------------
//	セッション情報クリア
//-----------------------------------------------------------
function SessionClear(){

	$_SESSION["TourokuFlg"]=1;
	$_SESSION["S_KihonInfo_DataCount"]=0;
	$_SESSION["S_KihonInfo_DataCount2"]=0;
	$_SESSION["K_ToDofuken_DataCount"]=0;

	for($dataidx=0; $dataidx<100; $dataidx++){
		$_SESSION["S_KihonInfo_AddData_" .$dataidx] = "";
		$_SESSION["S_KihonInfo_AddData2_" .$dataidx] = "";
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
	$_SESSION["K_StudentGakunenKoshin"] = "2";
	$_SESSION["K_StudentNendoKoshin"] = "2";
	$_SESSION["K_StudentStartDay1"] = "";
	$_SESSION["K_StudentStartDay2"] = "";
	$_SESSION["K_ShokaiDay"] = "";
	$_SESSION["K_Jyotai"] = "";

	$_SESSION["K_StudentStartDay1_COLER"] = "";
	$_SESSION["K_StudentStartDay2_COLER"] = "";

	$CodeData = array();
	$CodeData = GetCodeData("現状","","",1);
	$_SESSION["23CodeData"]=$CodeData;

	for($i=0; $i < $_SESSION["23CodeData"]["23DataCount"] - 1; $i++){
		$m = $i + 1;
		$_SESSION["K_Genjyo" . $m] = "";
	}
	$_SESSION["K_Genjyo99"] = "";
	$_SESSION["K_GenjyoHantei"] = "";

}
//-----------------------------------------------------------
//	セーブ処理
//-----------------------------------------------------------
Function SaveShori(){
$K_Genjyo10 = "";
	$_SESSION["K_StudentID"] = $_POST['K_StudentID'];
	$_SESSION["K_StudentName"] = $_POST['K_StudentName'];
	$_SESSION["K_StudentKana"] = $_POST['K_StudentKana'];
	$_SESSION["K_StudentKen"] = $_POST['K_StudentKen'];
	$_SESSION["K_StudentShi"] = $_POST['K_StudentShi'];
	$_SESSION["K_StudentKu"] = $_POST['K_StudentKu'];
	$_SESSION["K_StudentAdd"] = $_POST['K_StudentAdd'];
	$_SESSION["K_StudentTel"] = $_POST['K_StudentTel'];
	$_SESSION["K_StudentOld1"] = $_POST['K_StudentOld1'];
	$_SESSION["K_StudentOld2"] = $_POST['K_StudentOld2'];
	if(isset($_POST['K_StudentSei'])){
		$_SESSION["K_StudentSei"] = $_POST['K_StudentSei'];
	}
	if(isset($_POST['K_StudentNendoKoshin'])){
		$_SESSION["K_StudentNendoKoshin"] = $_POST['K_StudentNendoKoshin'];
	}
	if(isset($_POST['K_StudentGakunenKoshin'])){
		$_SESSION["K_StudentGakunenKoshin"] = $_POST['K_StudentGakunenKoshin'];
	}
	$_SESSION["K_StudentStartDay1"] = $_POST['K_StudentStartDay1'];
	$_SESSION["K_StudentStartDay2"] = $_POST['K_StudentStartDay2'];

	for($i=0; $i < $_SESSION["23CodeData"]["23DataCount"] - 1; $i++){
		$m = $i + 1;
		if(isset($_POST['K_Genjyo' . $m])){
			$_SESSION["K_Genjyo" . $m]=1;
		}else{
			$_SESSION["K_Genjyo" . $m]=0;
		}
	}
	for($i=9; $i < $_SESSION["23CodeData"]["23DataCount"] - 1; $i++){
		$m = $i + 1;
		if(isset($_POST['K_Genjyo' . $m])){
			$K_Genjyo10 = $K_Genjyo10 . "1";
		}else{
			$K_Genjyo10 = $K_Genjyo10 . "0";
		}
	}
	$_SESSION["K_Genjyo10Data"]=$K_Genjyo10;

	if(isset($_POST['K_Genjyo99'])){
		$_SESSION["K_Genjyo99"]=1;
	}else{
		$_SESSION["K_Genjyo99"]=0;
	}
	if(isset($_POST['K_GenjyoHantei'])){
		$_SESSION["K_GenjyoHantei"] = $_POST['K_GenjyoHantei'];
	}

}
//-----------------------------------------------------------
//	チェック処理
//-----------------------------------------------------------
function K_CheckShori(){
$ErrMsg = "";
$Background="background-color: #F5A9F2";

	$_SESSION["K_StudentStartDay1_COLER"] = "";
	$_SESSION["K_StudentStartDay2_COLER"] = "";

	if($_SESSION["K_StudentStartDay1"] != ""){
		if (strptime($_SESSION["K_StudentStartDay1"], '%Y-%m-%d')) {
		}else{
			$ErrMsg = "登録日が不正です。";
			$_SESSION["K_StudentStartDay1_COLER"] = $Background;
		}
	}
	if($_SESSION["K_StudentStartDay2"] != ""){
		if (strptime($_SESSION["K_StudentStartDay2"], '%Y-%m-%d')) {
		}else{
			$ErrMsg = "登録日が不正です。";
			$_SESSION["K_StudentStartDay2_COLER"] = $Background;
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

	$query = "SELECT Add_shi1 As AddData FROM S_KihonInfo ";
	$query = $query . " Where Add_Ken_Code1='" . $_SESSION["K_StudentKen"] . "'";
	$query = $query . " Group by Add_shi1";
	$query = $query . " Order by Add_shi1";

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

		$_SESSION["S_KihonInfo_AddData_" .$i]=$data[$i]['AddData'];

		$i++;
	}
	$_SESSION["S_KihonInfo_DataCount"] = $i;

	$query = "SELECT Add_ku1 As AddData FROM S_KihonInfo ";
	$query = $query . " Where Add_Ken_Code1='" . $_SESSION["K_StudentKen"] . "'";
	$query = $query . " Group by Add_ku1 ";
	$query = $query . " Order by Add_ku1";

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

		$_SESSION["S_KihonInfo_AddData2_" .$i]=$data[$i]['AddData'];

		$i++;
	}
	$_SESSION["S_KihonInfo_DataCount2"] = $i;

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

	$query = "SELECT Add_shi1 As AddData FROM S_KihonInfo ";
	$query = $query . " Where Add_Ken_Code1='" . $_SESSION["K_StudentKen"] . "'";
	$query = $query . " Group by Add_shi1";
	$query = $query . " Order by Add_shi1";

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

		$_SESSION["S_KihonInfo_AddData_" .$i]=$data[$i]['AddData'];

		$i++;
	}
	$_SESSION["S_KihonInfo_DataCount"] = $i;

	$query = "SELECT Add_ku1 As AddData FROM S_KihonInfo ";
	$query = $query . " Where Add_Ken_Code1='" . $_SESSION["K_StudentKen"] . "'";
	$query = $query . " And Add_shi1='" . $_SESSION["K_StudentShi"] . "'";
	$query = $query . " Group by Add_ku1 ";
	$query = $query . " Order by Add_ku1";

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

		$_SESSION["S_KihonInfo_AddData2_" .$i]=$data[$i]['AddData'];

		$i++;
	}
	$_SESSION["S_KihonInfo_DataCount2"] = $i;

 	// データベースの切断
	$mysqli->close();

}
?>

<script type="text/javascript" src="utility.js"></script>

<CENTER>
<body onload="document.form1.K_StudentID.focus();">

<form name="form1" method="post" action="S01_Kensaku.php">
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
				<input type="button" id="newshori" name="newshori" onClick="sbmfnc(this,<?php echo $_SESSION["TourokuFlg"] ?>)" style="cursor: pointer" value="新規登録" <?php if($_SESSION["shikaku2"]==0){ ?>Disabled<?php } ?>/>
			</td>
		</tr>
	</table>
	<BR>
	<table border="0" width="100%">
		<font size="5" color="#ff0000"><?php echo $EMSG ?></font>
	</table>
	<table border="0">
		<tr>
			<td id="midashi" align="center" bgcolor="#c0c0c0">生徒ID</td>
			<td align="left"><input class="inputtype" type="text" size="20" maxlength="10" name="K_StudentID" style="ime-mode: disabled;" value="<?php echo $_SESSION["K_StudentID"] ?>"></td>
			<td id="midashi" align="center" bgcolor="#c0c0c0">生徒名</td>
			<td align="left"><input class="inputtype" type="text" size="30" maxlength="20" name="K_StudentName" style="ime-mode: active;" value="<?php echo $_SESSION["K_StudentName"] ?>"></td>
			<td id="midashi" align="center" bgcolor="#c0c0c0">生徒カナ</td>
			<td align="left"><input class="inputtype" type="text" size="30" maxlength="20" name="K_StudentKana" style="ime-mode: active;" value="<?php echo $_SESSION["K_StudentKana"] ?>"></td>
		</tr>
		<tr>
			<td id="midashi" align="center" bgcolor="#c0c0c0">都道府県</td>
			<td align="left">
				<select name="K_StudentKen" class="selecttype" onchange="window.onbeforeunload = null;this.form.submit()">
					<option value="" <?php if($_SESSION["K_StudentKen"] == ""){ ?> SELECTED <?php } ?>>　</option>
					<?php for($dataidx=0; $dataidx < $_SESSION["K_ToDofuken_DataCount"]; $dataidx++){ ?>
						<option value="<?php echo $_SESSION["K_ToDofuken_Code_" .$dataidx]?>" <?php if($_SESSION["K_ToDofuken_Code_" .$dataidx] == $_SESSION["K_StudentKen"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["K_ToDofuken_Todofuken_" .$dataidx]?></option>
					<?php } ?>
				</select>
			</td>
			<td id="midashi" align="center" bgcolor="#c0c0c0">市区町村</td>
			<td align="left" colspan=3>
				<select name="K_StudentShi" class="selecttype2" onchange="window.onbeforeunload = null;this.form.submit()">
					<option value="" <?php if($_SESSION["K_StudentShi"] ==""){ ?> SELECTED <?php } ?>>　</option>
					<?php for($dataidx=0; $dataidx < $_SESSION["S_KihonInfo_DataCount"]; $dataidx++){ ?>
						<option value="<?php echo $_SESSION["S_KihonInfo_AddData_" .$dataidx]?>" <?php if($_SESSION["S_KihonInfo_AddData_" .$dataidx] ==$_SESSION["K_StudentShi"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["S_KihonInfo_AddData_" .$dataidx]?></option>
					<?php } ?>
				</select>
				<select name="K_StudentKu" class="selecttype2">
					<option value="" <?php if($_SESSION["K_StudentKu"] ==""){ ?> SELECTED <?php } ?>>　</option>
					<?php for($dataidx=0; $dataidx < $_SESSION["S_KihonInfo_DataCount2"]; $dataidx++){ ?>
						<option value="<?php echo $_SESSION["S_KihonInfo_AddData2_" .$dataidx]?>" <?php if($_SESSION["S_KihonInfo_AddData2_" .$dataidx] ==$_SESSION["K_StudentKu"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["S_KihonInfo_AddData2_" .$dataidx]?></option>
					<?php } ?>
				</select>
				<input class="inputtype" type="text" size="50" maxlength="80" name="K_StudentAdd" style="ime-mode: active;" value="<?php echo $_SESSION["K_StudentAdd"] ?>">
			</td>
		</tr>
		<tr>
			<td id="midashi" align="center" bgcolor="#c0c0c0">電話番号</td>
			<td align="left"><input class="inputtype" type="text" size="20" maxlength="20" name="K_StudentTel" style="ime-mode: active;" value="<?php echo $_SESSION["K_StudentTel"] ?>"></td>
			<td id="midashi" align="center" bgcolor="#c0c0c0">学年・年齢</td>
			<td align="left">
				<select name="K_StudentOld1" class="selecttype2">
					<option value="" <?php if($_SESSION["K_StudentOld1"] == ""){ ?> SELECTED <?php } ?>></option>
					<?php for($dataidx=0; $dataidx < $_SESSION["13CodeData"]["13DataCount"]; $dataidx++){ ?>
						<option value="<?php echo $_SESSION["13CodeData"]["13_Eda_" . $dataidx] ?>" <?php if($_SESSION["13CodeData"]["13_Eda_" . $dataidx] == $_SESSION["K_StudentOld1"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["13CodeData"]["13_CodeName2_" . $dataidx] ?></option>
					<?php } ?>
				</select>
				<select name="K_StudentOld2" class="selecttype2">
					<option value="" <?php if($_SESSION["K_StudentOld2"] == ""){ ?> SELECTED <?php } ?>></option>
					<?php for($dataidx=1; $dataidx < 60; $dataidx++){ ?>
						<option value="<?php echo $dataidx ?>" <?php if($_SESSION["K_StudentOld2"] == $dataidx){ ?> SELECTED <?php } ?>><?php echo $dataidx ?></option>
					<?php } ?>
				</select>
			</td>
			<td id="midashi" width="80" align="center" bgcolor="#c0c0c0">性別</td>
			<td align="left">
				<input type="radio" name="K_StudentSei" value="1" <?php if($_SESSION["K_StudentSei"]==1){?> checked <?php } ?>>男
				<input type="radio" name="K_StudentSei" value="2" <?php if($_SESSION["K_StudentSei"]==2){?> checked <?php } ?>>女
			</td>
		</tr>
		<tr>
			<td id="midashi" width="80" align="center" bgcolor="#c0c0c0">登録日</td>
			<td width="600" align="left" colspan=6>
				<input type="text" size="20" class="inputtype" maxlength="10" name="K_StudentStartDay1" style="ime-mode: active;<?php echo $_SESSION["K_StudentStartDay1_COLER"] ?>" value="<?php echo $_SESSION["K_StudentStartDay1"] ?>">
				～
				<input type="text" size="20" class="inputtype" maxlength="10" name="K_StudentStartDay2" style="ime-mode: active;<?php echo $_SESSION["K_StudentStartDay2_COLER"] ?>" value="<?php echo $_SESSION["K_StudentStartDay2"] ?>">
			</td>
		</tr>
		<tr>
			<td id="midashi" width="80" align="center" bgcolor="#c0c0c0">学年更新</td>
			<td align="left">
				<input type="radio" name="K_StudentGakunenKoshin" value="2" <?php if($_SESSION["K_StudentGakunenKoshin"]==2){?> checked <?php } ?>>全件
				<input type="radio" name="K_StudentGakunenKoshin" value="1" <?php if($_SESSION["K_StudentGakunenKoshin"]==1){?> checked <?php } ?>>済
				<input type="radio" name="K_StudentGakunenKoshin" value="0" <?php if($_SESSION["K_StudentGakunenKoshin"]==0){?> checked <?php } ?>>未
			</td>
		</tr>
		<tr>
			<td id="midashi" width="80" align="center" bgcolor="#c0c0c0">年度更新</td>
			<td align="left">
				<input type="radio" name="K_StudentNendoKoshin" value="2" <?php if($_SESSION["K_StudentNendoKoshin"]==2){?> checked <?php } ?>>全件
				<input type="radio" name="K_StudentNendoKoshin" value="1" <?php if($_SESSION["K_StudentNendoKoshin"]==1){?> checked <?php } ?>>済
				<input type="radio" name="K_StudentNendoKoshin" value="0" <?php if($_SESSION["K_StudentNendoKoshin"]==0){?> checked <?php } ?>>未
			</td>
		</tr>
		<tr>
			<td id="midashi" width="80" align="center" bgcolor="#c0c0c0">現状</td>
			<td align="left" colspan="5">
				<input type="radio" name="K_GenjyoHantei" value="1" <?php if($_SESSION["K_GenjyoHantei"]==1){?> checked <?php } ?>>ＡＮＤ
				<input type="radio" name="K_GenjyoHantei" value="2" <?php if($_SESSION["K_GenjyoHantei"]==2){?> checked <?php } ?>>ＯＲ
				　　
				<?php $CodeCnt = $_SESSION["23CodeData"]["23DataCount"];?>
				<?php for($dataidx=0; $dataidx < $CodeCnt; $dataidx++){ ?>
					<?php $m = $dataidx + 1;?>
					<input type="checkbox" name="K_Genjyo<?php echo $m ?>" value="<?php echo $_SESSION["23CodeData"]["23_Eda_" . $dataidx]?>" <?php if($_SESSION["K_Genjyo" . $m] == 1){?> CHECKED <?php } ?>><?php echo $_SESSION["23CodeData"]["23_CodeName1_" . $dataidx] ?>　
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
		</tr>
	</table>
	<BR><BR>

</form>
</body>
</CENTER>
</html>