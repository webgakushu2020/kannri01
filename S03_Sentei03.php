<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN"
	"http://www.w3.org/TR/html4/frameset.dtd">

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="Header.css">
	<title>選定画面</title>
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

	if(isset($_POST['S03_Sentei_TeacherKen'])){
		$_SESSION["ShoriID"] = "KNSAKU_KEN";
		SaveShori();
		SelectShiKu();
	}

//print("submitter:" . $_POST['submitter']);
	if(isset($_POST['submitter'])){
		$Sel = substr($_POST['submitter'], 0, 3);
		$SelNo = substr($_POST['submitter'], 4);
//print($Sel . "<BR>");
//print($SelNo . "<BR>");

		if($Sel == "Sel"){
			$_SESSION["ShoriID"]="SEL";
		}else{
			switch ($_POST['submitter']){
				case 'modoru':
				 	ModoruShori($_SESSION["S03_Sentei_kensaku_RPID"]);
					break;
				case 'kensaku':
				 	$_SESSION["ShoriID"]="KENSAKU";
					break;
				case 'clear':
				 	$_SESSION["ShoriID"]="CLEAR";
					break;
				case 'Comp':
				 	$_SESSION["ShoriID"]="COMP";
					break;
			}
		}
	}

	// 検索処理
//	if(isset($_POST['kensaku'])){
//	 	$_SESSION["ShoriID"]="KENSAKU";
//	}

	// クリア
//	if(isset($_POST['clear'])){
//	 	$_SESSION["ShoriID"]="CLEAR";
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
		$_SESSION["S03_Sentei_Kensaku_Sel"] = 0;

		//URLパラメータ
		if(isset($_GET['RPID'])) {
			$_SESSION["S03_Sentei_kensaku_RPID"] = $_GET['RPID'];
		      	//print($_SESSION["S03_Sentei_kensaku_RPID"]);
		}
		if(isset($_GET['MODE'])) {
			if($_GET['MODE'] != "Modoru"){
				$_SESSION["S03_Sentei_MODE"] = $_GET['MODE'];
				$_SESSION["ShoriID"]=$_GET['MODE'];
			      	//print($_SESSION["S03_Sentei_kensaku_MODE"] . "<BR>");
			}
		}
		if(isset($_GET['KEY1'])) {
			$_SESSION["S03_Sentei_Kensaku_KEY1"] = $_GET['KEY1'];
		}
		if(isset($_GET['SEQ'])) {
			$_SESSION["S03_Sentei_Kensaku_Seq"] = $_GET['SEQ'];
		}
		if(isset($_GET['SEL'])) {
			$_SESSION["S03_Sentei_Kensaku_Sel"] = $_GET['SEL'];
		}

//print($_SESSION["S03_Sentei_Kensaku_Seq"] . "<br>");

//print($_SESSION["ShoriID"] . "<br>");
		switch ($_SESSION["ShoriID"]){
			case 'UPD':
				//選択ボタン押下後
				if($_SESSION["S03_Sentei_Kensaku_Sel"] == 0){
					SessionClear();
				}
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

				break;
			case 'CLEAR':
				SessionClear();
				break;

			case 'KENSAKU':
				SaveShori();
				$EMSG = K_CheckShori();
				if($EMSG == ""){
					$EMSG = GetData();
				}

				break;

			case 'COMP':
				SaveShori();
				SaveShori2();

				if($_SESSION["S03_Sentei_CheckCnt"] == 0){
					$EMSG = "比較する教師にチェックしてください。";
				}else {
					header("Location:S04_Sentei03.php?MODE=NEW&RPID=S03_Sentei03");
				}
				
				break;
			case 'SEL':
				//S_SenteiInfoにデータ格納後画面再読み込み
				$EMSG = UpDateShori($SelNo);
				if($EMSG == ""){
					$no_login_url = "S03_index.php?MODE=SEL&RPID=S02_Kensaku&KEY1=" . $_SESSION["S03_Sentei_Kensaku_KEY1"] . "&KUBUN=1&Seq=" . $_SESSION["S03_Sentei_Kensaku_Seq"];
					header("Location: {$no_login_url}");
				}
				break;

		}	
	}
//-----------------------------------------------------------
//	セッション情報クリア
//-----------------------------------------------------------
function SessionClear(){

	$_SESSION["TourokuFlg"]=1;
	$_SESSION["K_S03_Sentei_DataCount"]=0;

	$_SESSION["S03_Sentei_TeacherID"] = "";
	$_SESSION["S03_Sentei_TeacherName"] = "";
	$_SESSION["S03_Sentei_TeacherKana"] = "";
	$_SESSION["S03_Sentei_TeacherKen"] = "";
	$_SESSION["S03_Sentei_TeacherShi"] = "";
	$_SESSION["S03_Sentei_TeacherKu"] = "";
	$_SESSION["S03_Sentei_TeacherAdd"] = "";
	$_SESSION["S03_Sentei_TeacherTel"] = "";
	$_SESSION["S03_Sentei_TeacherOld1"] = "";
	$_SESSION["S03_Sentei_TeacherOld2"] = "";
	$_SESSION["S03_Sentei_TeacherSei"] = "";
	$_SESSION["S03_Sentei_TeacherStartDay1"] = "";
	$_SESSION["S03_Sentei_TeacherStartDay2"] = "";
	$_SESSION["S03_Sentei_ShokaiDay"] = "";
	$_SESSION["S03_Sentei_Jyotai"] = "";
	$_SESSION["S03_Sentei_kyouka1-1"] = "0";
	$_SESSION["S03_Sentei_kyouka1-2"] = "0";
	$_SESSION["S03_Sentei_kyouka1-3"] = "0";
	$_SESSION["S03_Sentei_kyouka1-4"] = "0";
	$_SESSION["S03_Sentei_kyouka1-5"] = "0";
	$_SESSION["S03_Sentei_kyouka1-6"] = "0";
	$_SESSION["S03_Sentei_kyouka2-1"] = "0";
	$_SESSION["S03_Sentei_kyouka2-2"] = "0";
	$_SESSION["S03_Sentei_kyouka2-3"] = "0";
	$_SESSION["S03_Sentei_kyouka2-4"] = "0";
	$_SESSION["S03_Sentei_kyouka2-5"] = "0";
	$_SESSION["S03_Sentei_kyouka2-6"] = "0";
	$_SESSION["S03_Sentei_kyouka3-1"] = "0";
	$_SESSION["S03_Sentei_kyouka3-2"] = "0";
	$_SESSION["S03_Sentei_kyouka3-3"] = "0";
	$_SESSION["S03_Sentei_kyouka3-4"] = "0";
	$_SESSION["S03_Sentei_kyouka3-5"] = "0";
	$_SESSION["S03_Sentei_kyouka3-6"] = "0";
	$_SESSION["S03_Sentei_kyouka3-7"] = "0";
	$_SESSION["S03_Sentei_kyouka3-8"] = "0";
	$_SESSION["S03_Sentei_kyouka3-9"] = "0";
	$_SESSION["S03_Sentei_kyouka3-10"] = "0";
	$_SESSION["S03_Sentei_kyouka3-11"] = "0";
	$_SESSION["S03_Sentei_kyouka3-12"] = "0";
	$_SESSION["S03_Sentei_kyouka3-13"] = "0";
	$_SESSION["S03_Sentei_kyouka3-14"] = "0";
	$_SESSION["S03_Sentei_kyouka3-15"] = "0";
	$_SESSION["S03_Sentei_kyouka3-16"] = "0";
	$_SESSION["S03_Sentei_kyouka3-17"] = "0";
	$_SESSION["S03_Sentei_kyouka3-18"] = "0";
	$_SESSION["S03_Sentei_kyouka3-19"] = "0";
	$_SESSION["S03_Sentei_kyouka3-20"] = "0";
	$_SESSION["kyouka_sonota"] = "";

	$_SESSION["S03_Sentei_TeacherStartDay1_COLER"] = "";
	$_SESSION["S03_Sentei_TeacherStartDay2_COLER"] = "";

	$CodeData = array();
	$CodeData = GetCodeData("評価","","",1);
	$_SESSION["28CodeData"]=$CodeData;

}
//-----------------------------------------------------------
//	セーブ処理
//-----------------------------------------------------------
Function SaveShori(){

	$_SESSION["S03_Sentei_TeacherID"] = $_POST['S03_Sentei_TeacherID'];
	$_SESSION["S03_Sentei_TeacherName"] = $_POST['S03_Sentei_TeacherName'];
	$_SESSION["S03_Sentei_TeacherKana"] = $_POST['S03_Sentei_TeacherKana'];
	$_SESSION["S03_Sentei_TeacherKen"] = $_POST['S03_Sentei_TeacherKen'];
	$_SESSION["S03_Sentei_TeacherShi"] = $_POST['S03_Sentei_TeacherShi'];
	$_SESSION["S03_Sentei_TeacherKu"] = $_POST['S03_Sentei_TeacherKu'];
	$_SESSION["S03_Sentei_TeacherAdd"] = $_POST['S03_Sentei_TeacherAdd'];
	$_SESSION["S03_Sentei_TeacherTel"] = $_POST['S03_Sentei_TeacherTel'];
	$_SESSION["S03_Sentei_TeacherOld1"] = $_POST['S03_Sentei_TeacherOld1'];
	$_SESSION["S03_Sentei_TeacherOld2"] = $_POST['S03_Sentei_TeacherOld2'];
	if(isset($_POST['S03_Sentei_TeacherSei'])){
		$_SESSION["S03_Sentei_TeacherSei"] = $_POST['S03_Sentei_TeacherSei'];
	}
	$_SESSION["S03_Sentei_TeacherStartDay1"] = $_POST['S03_Sentei_TeacherStartDay1'];
	$_SESSION["S03_Sentei_TeacherStartDay2"] = $_POST['S03_Sentei_TeacherStartDay2'];

	for($m=1; $m<=6; $m++){
//print($_SESSION["S03_Sentei_kyouka1-" . $m] . "<BR>");
		if(isset($_POST['S03_Sentei_kyouka1-' . $m])){

			$_SESSION["S03_Sentei_kyouka1-" . $m]=1;
		}else{
			$_SESSION["S03_Sentei_kyouka1-" . $m]=0;
		}
	}
	for($m=1; $m<=6; $m++){
		if(isset($_POST['S03_Sentei_kyouka2-' . $m])){
			$_SESSION["S03_Sentei_kyouka2-" . $m]=1;
		}else{
			$_SESSION["S03_Sentei_kyouka2-" . $m]=0;
		}
	}
	for($m=1; $m<=20; $m++){
		if(isset($_POST['S03_Sentei_kyouka3-' . $m])){
			$_SESSION["S03_Sentei_kyouka3-" . $m]=1;
		}else{
			$_SESSION["S03_Sentei_kyouka3-" . $m]=0;
		}
	}
	if(isset($_POST['kyouka_sonota'])){
		$_SESSION["kyouka_sonota"] = $_POST['kyouka_sonota'];
	}

}
//-----------------------------------------------------------
//	セーブ処理
//-----------------------------------------------------------
Function SaveShori2(){
$CHKCNT = 0;
$ID = 0;
//print("K_S03_Sentei_DataCount=" . $_SESSION["K_S03_Sentei_DataCount"]);

	for($i=0; $i<$_SESSION["K_S03_Sentei_DataCount"]; $i++){
		$_SESSION["S03_Sentei_No_" . $i] = $_POST['S03_Sentei_No_' . $i];
		if(isset($_SESSION['S03_Sentei_No_' . $i])){
			$_SESSION["S03_Sel_TeacherID_" . $ID] = $_POST['K_S03_Sentei_TeacherID' . $i];
			$CHKCNT++;
			$ID++;
		}
	}
	$_SESSION["S03_Sentei_CheckCnt"] = $CHKCNT;

}
//-----------------------------------------------------------
//	チェック処理
//-----------------------------------------------------------
function K_CheckShori(){
$ErrMsg = "";
$Background="background-color: #F5A9F2";

	$_SESSION["S03_Sentei_TeacherStartDay1_COLER"] = "";
	$_SESSION["S03_Sentei_TeacherStartDay2_COLER"] = "";

	//入力有無
//	if($_SESSION["S03_Sentei_TeacherID"]=="" && $_SESSION["S03_Sentei_TeacherName"]=="" && $_SESSION["S03_Sentei_TeacherKana"]=="" && $_SESSION["S03_Sentei_TeacherKen"]=="" && $_SESSION["S03_Sentei_TeacherShi"]=="" && $_SESSION["S03_Sentei_TeacherKu"]=="" && $_SESSION["S03_Sentei_TeacherAdd"]=="" && $_SESSION["S03_Sentei_TeacherTel"]=="" && $_SESSION["S03_Sentei_TeacherOld1"]=="" && $_SESSION["S03_Sentei_TeacherOld2"]=="" && $_SESSION["S03_Sentei_TeacherSei"]=="" && $_SESSION["S03_Sentei_TeacherStartDay1"]=="" && $_SESSION["S03_Sentei_TeacherStartDay2"]=="" ){
//		$ErrMsg = "検索条件を入力してください。";
//	}
	if($_SESSION["S03_Sentei_TeacherStartDay1"] != ""){
		if (strptime($_SESSION["S03_Sentei_TeacherStartDay1"], '%Y-%m-%d')) {
		}else{
			$ErrMsg = "登録日が不正です。";
			$_SESSION["S03_Sentei_TeacherStartDay1_COLER"] = $Background;
		}
	}
	if($_SESSION["S03_Sentei_TeacherStartDay2"] != ""){
		if (strptime($_SESSION["S03_Sentei_TeacherStartDay2"], '%Y-%m-%d')) {
		}else{
			$ErrMsg = "登録日が不正です。";
			$_SESSION["S03_Sentei_TeacherStartDay2_COLER"] = $Background;
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

		$_SESSION["S03_Sentei_ToDofuken_Code_" .$i]=$data[$i]['Code'];
		$_SESSION["S03_Sentei_ToDofuken_ChiikiCode_" .$i]=$data[$i]['ChiikiCode'];
		$_SESSION["S03_Sentei_ToDofuken_Todofuken_" .$i]=$data[$i]['Todofuken'];

		$i++;
	}
	$_SESSION["S03_Sentei_ToDofuken_DataCount"] = $i;

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
	$query = $query . " Where Add_Ken_Code='" . $_SESSION["S03_Sentei_TeacherKen"] . "'";
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
	$query = $query . " Where Add_Ken_Code='" . $_SESSION["S03_Sentei_TeacherKen"] . "'";
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

		$query = "Select a.TeacherID , a.Name1, a.Hyoka, ";
		$query = $query . " b.BirthDay, b.Seibetu, b.Tel1, b.Tel2, b.Tel3, ";
		$query = $query . " b.Add_Ken_Code, b.Add_ken, b.Add_shi, b.Add_ku, b.Add_cho, ";
		$query = $query . " a.EntryTime, b.Mail1 ,b.Uni1 , b.Dept1 ,b.Uni2, b.Dept2, b.Ensen1,b.Ensen2,b.Ensen3 FROM T_AtenaInfo as a inner join";
		$query = $query . " T_KihonInfo as b on";
		$query = $query . " a.TeacherID=b.TeacherID";
		$query = $query . " inner join T_ShosaiInfo as c on";
		$query = $query . " a.TeacherID=c.TeacherID";

		if($_SESSION["S03_Sentei_TeacherID"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where a.TeacherID=" . $_SESSION["S03_Sentei_TeacherID"];
			}else{
				$query2 = $query2 . " And a.TeacherID=" . $_SESSION["S03_Sentei_TeacherID"];
			}
		}
		if($_SESSION["S03_Sentei_TeacherName"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where a.Name1 like '%" . $_SESSION["S03_Sentei_TeacherName"] . "%'";
			}else{
				$query2 = $query2 . " And a.Name1 like '%" . $_SESSION["S03_Sentei_TeacherName"] . "%'";
			}
		}
		if($_SESSION["S03_Sentei_TeacherKana"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where a.Name2 like '%" . $_SESSION["S03_Sentei_TeacherKana"] . "%'";
			}else{
				$query2 = $query2 . " And a.Name2 like '%" . $_SESSION["S03_Sentei_TeacherKana"] . "%'";
			}
		}
		if($_SESSION["S03_Sentei_TeacherKen"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where b.Add_Ken_Code ='" . $_SESSION["S03_Sentei_TeacherKen"] . "'";
			}else{
				$query2 = $query2 . " And b.Add_Ken_Code ='" . $_SESSION["S03_Sentei_TeacherKen"] . "'";
			}
		}
		if($_SESSION["S03_Sentei_TeacherShi"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where b.Add_shi like '%" . $_SESSION["S03_Sentei_TeacherShi"] . "%'";
			}else{
				$query2 = $query2 . " And b.Add_shi like '%" . $_SESSION["S03_Sentei_TeacherShi"] . "%'";
			}
		}
		if($_SESSION["S03_Sentei_TeacherKu"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where b.Add_ku like '%" . $_SESSION["S03_Sentei_TeacherKu"] . "%'";
			}else{
				$query2 = $query2 . " And b.Add_ku like '%" . $_SESSION["S03_Sentei_TeacherKu"] . "%'";
			}
		}
		if($_SESSION["S03_Sentei_TeacherAdd"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where b.Add_cho like '%" . $_SESSION["S03_Sentei_TeacherAdd"] . "%'";
			}else{
				$query2 = $query2 . " And b.Add_cho like '%" . $_SESSION["S03_Sentei_TeacherAdd"] . "%'";
			}
		}
		if($_SESSION["S03_Sentei_TeacherTel"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where (b.Tel1 ='" . $_SESSION["S03_Sentei_TeacherTel"] . "'";
				$query2 = $query2 . " OR b.Tel2 ='" . $_SESSION["S03_Sentei_TeacherTel"] . "'";
				$query2 = $query2 . " OR b.Tel3 ='" . $_SESSION["S03_Sentei_TeacherTel"] . "')";
			}else{
				$query2 = $query2 . " And (b.Tel1 ='" . $_SESSION["S03_Sentei_TeacherTel"] . "'";
				$query2 = $query2 . " OR b.Tel2 ='" . $_SESSION["S03_Sentei_TeacherTel"] . "'";
				$query2 = $query2 . " OR b.Tel3 ='" . $_SESSION["S03_Sentei_TeacherTel"] . "')";
			}
		}
		if($_SESSION["S03_Sentei_TeacherOld1"]!=""){
			$Old1 = mb_substr($_SESSION["S03_Sentei_TeacherOld1"],0,2);
			$Old2 = mb_substr($_SESSION["S03_Sentei_TeacherOld1"],0,1);
			$OldStart = mb_convert_kana($Old1, "n");
			$OldEnd = mb_convert_kana($Old2, "n") . 9;
			$start = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 1, date('Y') - $OldStart - 1));
			$end = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 1, date('Y') - $OldEnd - 1));
			if($query2 == ""){
				$query2 = $query2 . " Where b.Birthday >= '" . $end . "' and b.Birthday <='" . $start . "'";
			}else{
				$query2 = $query2 . " And b.Birthday >= '" . $end . "' and b.Birthday <='" . $start . "'";
			}
		}
		if($_SESSION["S03_Sentei_TeacherOld2"]!=""){
			$start = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 1, date('Y') - $_SESSION["S03_Sentei_TeacherOld2"] - 1));
			$end = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 1, date('Y') - $_SESSION["S03_Sentei_TeacherOld2"]));
			if($query2 == ""){
				$query2 = $query2 . " Where b.Birthday >= '" . $start . "' and b.Birthday <='" . $end . "'";
			}else{
				$query2 = $query2 . " And b.Birthday >= '" . $start . "' and b.Birthday <='" . $end . "'";
			}
		}
		if($_SESSION["S03_Sentei_TeacherSei"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where b.Seibetu = '" . $_SESSION["S03_Sentei_TeacherSei"] . "'";
			}else{
				$query2 = $query2 . " And b.Seibetu = '" . $_SESSION["S03_Sentei_TeacherSei"] . "'";
			}
		}
		if($_SESSION["S03_Sentei_TeacherStartDay1"]!="" && $_SESSION["S03_Sentei_TeacherStartDay2"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where b.EntryDay >= '" . $_SESSION["S03_Sentei_TeacherStartDay1"] . "' and b.EntryDay <= '" . $_SESSION["S03_Sentei_TeacherStartDay2"] . "'";
			}else{
				$query2 = $query2 . " And b.EntryDay >= '" . $_SESSION["S03_Sentei_TeacherStartDay1"] . "' and b.EntryDay <= '" . $_SESSION["S03_Sentei_TeacherStartDay2"] . "'";
			}
		}elseif($_SESSION["S03_Sentei_TeacherStartDay1"]!="" && $_SESSION["S03_Sentei_TeacherStartDay2"]==""){
			if($query2 == ""){
				$query2 = $query2 . " Where b.EntryDay >= '" . $_SESSION["S03_Sentei_TeacherStartDay1"] . "'";
			}else{
				$query2 = $query2 . " And b.EntryDay >= '" . $_SESSION["S03_Sentei_TeacherStartDay1"] . "'";
			}
		}elseif($_SESSION["S03_Sentei_TeacherStartDay1"]=="" && $_SESSION["S03_Sentei_TeacherStartDay2"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where b.EntryDay <= '" . $_SESSION["S03_Sentei_TeacherStartDay2"] . "'";
			}else{
				$query2 = $query2 . " And b.EntryDay <= '" . $_SESSION["S03_Sentei_TeacherStartDay2"] . "'";
			}
		}
		for($m=1; $m<=6; $m++){
			if($_SESSION["S03_Sentei_kyouka1-" . $m]==1){
				if($query2 == ""){
					$query2 = $query2 . " Where c.Sub1_" . $m . "= '1'";
				}else{
					$query2 = $query2 . " And c.Sub1_" . $m . "= '1'";
				}
			}
		}
		for($m=1; $m<=6; $m++){
			if($_SESSION["S03_Sentei_kyouka2-" . $m]==1){
				if($query2 == ""){
					$query2 = $query2 . " Where c.Sub2_" . $m . "= '1'";
				}else{
					$query2 = $query2 . " And c.Sub2_" . $m . "= '1'";
				}
			}
		}
		for($m=1; $m<=20; $m++){
			if($_SESSION["S03_Sentei_kyouka3-" . $m]==1){
				if($query2 == ""){
					$query2 = $query2 . " Where c.Sub3_" . $m . "= '1'";
				}else{
					$query2 = $query2 . " And c.Sub3_" . $m . "= '1'";
				}
			}
		}
		if($_SESSION["kyouka_sonota"] !=""){
			if($query2 == ""){
				$query2 = $query2 . " Where c.Sub4_1 like '%" . $_SESSION["kyouka_sonota"] . "%'";
			}else{
				$query2 = $query2 . " And c.Sub4_1 like '%" . $_SESSION["kyouka_sonota"] . "%'";
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
		
		$data = array();
		$i = 0;
		while($arr_item = $result->fetch_assoc()){

			//レコード内の各フィールド名と値を順次参照
			foreach($arr_item as $key => $value){
				//フィールド名と値を表示
				$data[$i][$key] = $value;
				$_SESSION["K_S03_Sentei_" . $key . $i] = $data[$i][$key];
			}
			//S_SenteiInfo
			$query3 = "Select Count(*) as CNT from S_SenteiInfo";
			$query3 = $query3 . " Where TeacherID = '" . $_SESSION["K_S03_Sentei_TeacherID" . $i] . "'";
			$query3 = $query3 . " And StudentID = '" . $_SESSION["S03_Sentei_Kensaku_KEY1"] . "'";
			$query3 = $query3 . " And AtenaSeq = '" . $_SESSION["S03_Sentei_Kensaku_Seq"] . "'";
			$query3 = $query3 . " And JyotaiFlg<>'9' AND  JyotaiFlg<>'8' AND  JyotaiFlg<>'2'"; //２：契約変更　８：契約終了　９：契約解除

			$result3 = $mysqli->query($query3);
//print($query3);

			if (!$result3) {
				print('クエリーが失敗しました。' . $mysqli->error);
				$mysqli->close();
				exit();
			}

			while ($row = $result3->fetch_assoc()) {
				$db_CNT = $row['CNT'];
			}
			
			if($db_CNT == 0){
				$_SESSION["K_S03_Sentei_ZUMI" . $i] = 0; //選定可　２：契約変更　８：契約終了　９：契約解除
			}else{
				$_SESSION["K_S03_Sentei_ZUMI" . $i] = 1; //選定不可　０：選定　１：契約
			}

			$i++;
		}
		$_SESSION["K_S03_Sentei_DataCount"] = $i;

	 	// データベースの切断
		$mysqli->close();

}
//-----------------------------------------------------------
//	S_SenteiInfo
//-----------------------------------------------------------
Function UpDateShori($pNo){
	
	// mysqlへの接続
	$mysqli = new mysqli(HOST, USER, PASS);
	if ($mysqli->connect_errno) {
		print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
		exit();
	}

	// データベースの選択
	$mysqli->select_db(DBNAME);
	$mysqli->set_charset("utf8");
	
	//契約終了分　２,８,９の最大SEQを取得
	$_SESSION["S03_Sentei03_CNT"] = 0;
	$query = "Select * from S_SenteiInfo ";
	$query = $query . " where TeacherID='" . $_SESSION["K_S03_Sentei_TeacherID" .$pNo] . "'";
	$query = $query . " And StudentID='" . $_SESSION["S03_Sentei_Kensaku_KEY1"] . "'";
	$query = $query . " And AtenaSeq=" . $_SESSION["S03_Sentei_Kensaku_Seq"];
	$query = $query . " Order by Seq Desc LIMIT 1";
	
	//print($query ."<BR>");
	$result = $mysqli->query($query);

	if (!$result) {
		$ErrMSG = "クエリーが失敗しました。" . $mysqli->error;
		$ErrFlg = 1;
	}
	while ($row = $result->fetch_assoc()) {
		//フィールド名と値を表示
		$_SESSION["S03_Sentei03_Seq"] = $row['Seq'];
		$_SESSION["S03_Sentei03_CNT"] = 1;
	}

//print($_SESSION["S03_Sentei03_Seq"] . "<BR>");
	
	if($_SESSION["S03_Sentei03_CNT"]==0){
		$SeqCnt = 0;
	}else{
		$SeqCnt = $_SESSION["S03_Sentei03_Seq"] + 1;
	}
//print($SeqCnt . "<BR>");

	$query = "INSERT INTO S_SenteiInfo ";
	$query = $query . "values(";
	$query = $query . "'" . $_SESSION["K_S03_Sentei_TeacherID" .$pNo] . "'";
	$query = $query . ",'" . $_SESSION["S03_Sentei_Kensaku_KEY1"] . "'";
	$query = $query . "," . $_SESSION["S03_Sentei_Kensaku_Seq"];
	$query = $query . ",'" . $SeqCnt . "'";
	$query = $query . ",'0'";
	$query = $query . ",null";
	$query = $query . ",null";
	$query = $query . ",null";
	$query = $query . ",null";
	$query = $query . ",null";
	$query = $query . ",null";
	$query = $query . ",null";
	$query = $query . ",null";
	$query = $query . ",null";
	$query = $query . ",null)";

	//print($query ."<BR>");

	$result = $mysqli->query($query);

	if (!$result) {
		$ErrMSG = "クエリーが失敗しました。（S_SenteiInfoエラー）" . $mysqli->error;
	}

	$mysqli->close();

	$_SESSION["K_S03_Sentei_ZUMI" .$pNo] = 1;
		
	return $ErrMSG;

}
?>

<script type="text/javascript" src="utility.js"></script>

<CENTER>
<body onload="document.form1.S03_Sentei_TeacherID.focus();">

<form name="form1" method="post" action="S03_Sentei03.php">
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
	<table border="0">
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#00FF00" colspan=8>選定</td>
		</tr>
		<tr>
			<td id="midashi" align="center" bgcolor="#c0c0c0">教師ID</td>
			<td align="left"><input class="inputtype" type="text" size="20" maxlength="10" name="S03_Sentei_TeacherID" style="ime-mode: disabled;" value="<?php echo $_SESSION["S03_Sentei_TeacherID"] ?>"></td>
			<td id="midashi" align="center" bgcolor="#c0c0c0">教師名</td>
			<td align="left"><input class="inputtype" type="text" size="30" maxlength="20" name="S03_Sentei_TeacherName" style="ime-mode: active;" value="<?php echo $_SESSION["S03_Sentei_TeacherName"] ?>"></td>
			<td id="midashi" align="center" bgcolor="#c0c0c0">教師カナ</td>
			<td align="left"><input class="inputtype" type="text" size="30" maxlength="20" name="S03_Sentei_TeacherKana" style="ime-mode: active;" value="<?php echo $_SESSION["S03_Sentei_TeacherKana"] ?>"></td>
		</tr>
		<tr>
			<td id="midashi" align="center" bgcolor="#c0c0c0">都道府県</td>
			<td align="left">
				<select name="S03_Sentei_TeacherKen" class="selecttype" onchange="window.onbeforeunload = null;this.form.submit()">
					<option value="" <?php if($_SESSION["S03_Sentei_TeacherKen"] == ""){ ?> SELECTED <?php } ?>>　</option>
					<?php for($dataidx=0; $dataidx < $_SESSION["S03_Sentei_ToDofuken_DataCount"]; $dataidx++){ ?>
						<option value="<?php echo $_SESSION["S03_Sentei_ToDofuken_Code_" .$dataidx]?>" <?php if($_SESSION["S03_Sentei_ToDofuken_Code_" .$dataidx] == $_SESSION["S03_Sentei_TeacherKen"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["S03_Sentei_ToDofuken_Todofuken_" .$dataidx]?></option>
					<?php } ?>
				</select>
			</td>
			<td id="midashi" align="center" bgcolor="#c0c0c0">市区町村</td>
			<td align="left" colspan=3>
				<select name="S03_Sentei_TeacherShi" class="selecttype2">
					<option value="" <?php if($_SESSION["S03_Sentei_TeacherShi"] ==""){ ?> SELECTED <?php } ?>>　</option>
					<?php for($dataidx=0; $dataidx < $_SESSION["T_KihonInfo_DataCount"]; $dataidx++){ ?>
						<option value="<?php echo $_SESSION["T_KihonInfo_AddData_" .$dataidx]?>" <?php if($_SESSION["T_KihonInfo_AddData_" .$dataidx] ==$_SESSION["S03_Sentei_TeacherShi"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["T_KihonInfo_AddData_" .$dataidx]?></option>
					<?php } ?>
				</select>
				<select name="S03_Sentei_TeacherKu" class="selecttype2">
					<option value="" <?php if($_SESSION["S03_Sentei_TeacherKu"] ==""){ ?> SELECTED <?php } ?>>　</option>
					<?php for($dataidx=0; $dataidx < $_SESSION["T_KihonInfo_DataCount2"]; $dataidx++){ ?>
						<option value="<?php echo $_SESSION["T_KihonInfo_AddData2_" .$dataidx]?>" <?php if($_SESSION["T_KihonInfo_AddData2_" .$dataidx] ==$_SESSION["S03_Sentei_TeacherKu"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["T_KihonInfo_AddData2_" .$dataidx]?></option>
					<?php } ?>
				</select>
				<input class="inputtype" type="text" size="50" maxlength="80" name="S03_Sentei_TeacherAdd" style="ime-mode: active;" value="<?php echo $_SESSION["S03_Sentei_TeacherAdd"] ?>">
			</td>
		</tr>
		<tr>
			<td id="midashi" align="center" bgcolor="#c0c0c0">電話番号</td>
			<td align="left"><input class="inputtype" type="text" size="20" maxlength="20" name="S03_Sentei_TeacherTel" style="ime-mode: active;" value="<?php echo $_SESSION["S03_Sentei_TeacherTel"] ?>"></td>
			<td id="midashi" align="center" bgcolor="#c0c0c0">年齢</td>
			<td align="left">
				<select name="S03_Sentei_TeacherOld1" class="selecttype2">
					<option value="" <?php if($_SESSION["S03_Sentei_TeacherOld1"] == ""){ ?> SELECTED <?php } ?>></option>
					<?php for($dataidx=0; $dataidx < $_SESSION["02CodeData"]["02DataCount"]; $dataidx++){ ?>
						<option value="<?php echo $_SESSION["02CodeData"]["02_CodeName2_" . $dataidx] ?>" <?php if($_SESSION["02CodeData"]["02_CodeNo_" . $dataidx] == $_SESSION["S03_Sentei_TeacherOld1"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["02CodeData"]["02_CodeName2_" . $dataidx] ?></option>
					<?php } ?>
				</select>
				<select name="S03_Sentei_TeacherOld2" class="selecttype2">
					<option value="" <?php if($_SESSION["S03_Sentei_TeacherOld2"] == ""){ ?> SELECTED <?php } ?>></option>
					<?php for($dataidx=18; $dataidx < 60; $dataidx++){ ?>
						<option value="<?php echo $dataidx ?>" <?php if($_SESSION["S03_Sentei_TeacherOld2"] == $dataidx){ ?> SELECTED <?php } ?>><?php echo $dataidx ?></option>
					<?php } ?>
				</select>
			</td>
			<td id="midashi" width="80" align="center" bgcolor="#c0c0c0">性別</td>
			<td align="left">
				<input type="radio" name="S03_Sentei_TeacherSei" value="1" <?php if($_SESSION["S03_Sentei_TeacherSei"]==1){?> checked <?php } ?>>男
				<input type="radio" name="S03_Sentei_TeacherSei" value="2" <?php if($_SESSION["S03_Sentei_TeacherSei"]==2){?> checked <?php } ?>>女
			</td>
		</tr>
		<tr>
			<td id="midashi" width="80" align="center" bgcolor="#c0c0c0">登録日</td>
			<td width="600" align="left" colspan=6>
				<input type="text" size="20" class="inputtype" maxlength="10" name="S03_Sentei_TeacherStartDay1" style="ime-mode: active;<?php echo $_SESSION["S03_Sentei_TeacherStartDay1_COLER"] ?>" value="<?php echo $_SESSION["S03_Sentei_TeacherStartDay1"] ?>">
				～
				<input type="text" size="20" class="inputtype" maxlength="10" name="S03_Sentei_TeacherStartDay2" style="ime-mode: active;<?php echo $_SESSION["S03_Sentei_TeacherStartDay2_COLER"] ?>" value="<?php echo $_SESSION["S03_Sentei_TeacherStartDay2"] ?>">
			</td>
		</tr>
<!--
		<tr>
			<td id="midashi" width="80" align="center" bgcolor="#c0c0c0">初回日</td>
			<td width="600" align="left" colspan=6>
				<input type="text" size="20" class="inputtype" maxlength="10" name="S03_Sentei_ShokaiDay" style="ime-mode: active;" value="<?php echo $_SESSION["S03_Sentei_ShokaiDay"] ?>">
			</td>
		</tr>
		<tr>
			<td id="midashi" width="80" align="center" bgcolor="#c0c0c0">状態</td>
			<td width="600" align="left" colspan=6>
				<select name="S03_Sentei_Jyotai" class="selecttype2">
					<option value="" <?php if($_SESSION["S03_Sentei_Jyotai"] == ""){ ?> SELECTED <?php } ?>></option>
					<?php for($dataidx=0; $dataidx < $_SESSION["03CodeData"]["03DataCount"]; $dataidx++){ ?>
						<option value="<?php echo $_SESSION["03CodeData"]["03_CodeNo_" . $dataidx] ?>" <?php if($_SESSION["03CodeData"]["03_CodeNo_" . $dataidx] == $_SESSION["S03_Sentei_Jyotai"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["03CodeData"]["03_CodeName1_" . $dataidx] ?></option>
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
					<input type="checkbox" name="S03_Sentei_kyouka1-<?php echo $idx?>" value="<?php echo $_SESSION["S03_Sentei_kyouka1-" .$idx] ?>" <?php if($_SESSION["S03_Sentei_kyouka1-" .$idx]=="1"){?> CHECKED <?php } ?>><?php echo $_SESSION["05CodeData"]["05_CodeName2_" . $i] ?>
				<?php } ?>
				<BR>【中学生】
				<?php for($i=0; $i < $_SESSION["06CodeData"]["06DataCount"]; $i++){ ?>
					<?php $idx = $i + 1?>
					<input type="checkbox" name="S03_Sentei_kyouka2-<?php echo $idx?>" value="<?php echo $_SESSION["S03_Sentei_kyouka2-" .$idx] ?>" <?php if($_SESSION["S03_Sentei_kyouka2-" .$idx]=="1"){?> CHECKED <?php } ?>><?php echo $_SESSION["06CodeData"]["06_CodeName2_" . $i] ?>
				<?php } ?>
				<BR>【高校生】<BR>
				　　　　　　
				<?php for($i=0; $i < 6; $i++){ ?>
					<?php $idx = $i + 1?>
					<input type="checkbox" name="S03_Sentei_kyouka3-<?php echo $idx?>" value="<?php echo $_SESSION["S03_Sentei_kyouka3-" .$idx] ?>" <?php if($_SESSION["S03_Sentei_kyouka3-" .$idx]=="1"){?> CHECKED <?php } ?>><?php echo $_SESSION["07CodeData"]["07_CodeName2_" . $i] ?>
				<?php } ?>
				<BR>
				　　　　　　
				<?php for($i=6; $i < 12; $i++){ ?>
					<?php $idx = $i + 1?>
					<input type="checkbox" name="S03_Sentei_kyouka3-<?php echo $idx?>" value="<?php echo $_SESSION["S03_Sentei_kyouka3-" .$idx] ?>" <?php if($_SESSION["S03_Sentei_kyouka3-" .$idx]=="1"){?> CHECKED <?php } ?>><?php echo $_SESSION["07CodeData"]["07_CodeName2_" . $i] ?>
				<?php } ?>
				<BR>
				　　　　　　
				<?php for($i=12; $i < 16; $i++){ ?>
					<?php $idx = $i + 1?>
					<input type="checkbox" name="S03_Sentei_kyouka3-<?php echo $idx?>" value="<?php echo $_SESSION["S03_Sentei_kyouka3-" .$idx] ?>" <?php if($_SESSION["S03_Sentei_kyouka3-" .$idx]=="1"){?> CHECKED <?php } ?>><?php echo $_SESSION["07CodeData"]["07_CodeName2_" . $i] ?>
				<?php } ?>
				<BR>
				　　　　　　
				<?php for($i=16; $i < $_SESSION["07CodeData"]["07DataCount"]; $i++){ ?>
					<?php $idx = $i + 1?>
					<input type="checkbox" name="S03_Sentei_kyouka3-<?php echo $idx?>" value="<?php echo $_SESSION["S03_Sentei_kyouka3-" .$idx] ?>" <?php if($_SESSION["S03_Sentei_kyouka3-" .$idx]=="1"){?> CHECKED <?php } ?>><?php echo $_SESSION["07CodeData"]["07_CodeName2_" . $i] ?>
				<?php } ?>
				　　　　　　
				<BR>【その他】<input class="inputtype" type="text" size="30" maxlength="20" name="kyouka_sonota" style="ime-mode: active;" value="<?php echo $_SESSION["kyouka_sonota"] ?>">
			</td>
		</tr>
	</table>
	<table border="0">
		<tr>
			<td><input id="kensaku" type="button" name="kensaku" onClick="sbmfnc(this,<?php echo $_SESSION["TourokuFlg"] ?>)"style="cursor: pointer" value="検索" /></td>
			<td><input id="clear" type="button" name="clear" onClick="sbmfnc(this,<?php echo $_SESSION["TourokuFlg"] ?>)"style="cursor: pointer" value="最初から" /></td>
<!--			<td><input id="hoji" type="button" name="hoji" onClick="sbmfnc(this,<?php echo $_SESSION["TourokuFlg"] ?>)"style="cursor: pointer" value="検索条件の復元" /></td>
-->
		</tr>
	</table>
	<BR><BR>
	<table border="0">
		<tr align="right">
			<td><input id="Comp" type="button" name="Comp" onClick="sbmfnc(this,<?php echo $_SESSION["TourokuFlg"] ?>)" style="cursor: pointer" value="詳細比較" /></td>
		</tr>
	</table>
	<div id="tbl-bdr">
		<table>
			<tr>
				<td width="10" height="30" align="center" bgcolor="#c0c0c0">No</td>
				<td width="50" align="center" bgcolor="#c0c0c0">教師ID</td>
				<td width="100" align="center" bgcolor="#c0c0c0">教師名</td>
				<td width="50" align="center" bgcolor="#c0c0c0">年齢</td>
				<td width="50" align="center" bgcolor="#c0c0c0">性別</td>
				<td width="400" align="center" bgcolor="#c0c0c0">住所</td>
				<td width="150" align="center" bgcolor="#c0c0c0">出身大学</td>
				<td width="150" align="center" bgcolor="#c0c0c0">沿線・駅</td>
				<td width="20" align="center" bgcolor="#c0c0c0">選択</td>

			</tr>
			<?php for($i=0; $i<$_SESSION["K_S03_Sentei_DataCount"]; $i++){ ?>
				<tr>
					<td height="30" align="center">
						<input type="checkbox" name="S03_Sentei_No_<?php echo $i?>" value="<?php echo $i+1 ?>">
					</td>
					<td align="center" <?php if($_SESSION["K_S03_Sentei_ZUMI" . $i]==1){?> bgcolor="#F6CEF5" <?php } ?>><?php echo $_SESSION["K_S03_Sentei_TeacherID" .$i] ?></td>
					<td align="center" <?php if($_SESSION["K_S03_Sentei_ZUMI" . $i]==1){?> bgcolor="#F6CEF5" <?php } ?>>
						<?php echo $_SESSION["K_S03_Sentei_Name1" .$i] ?><BR>
						<?php for($dataidx=0; $dataidx < $_SESSION["28CodeData"]["28DataCount"]; $dataidx++){ ?>
							<?php if($_SESSION["28CodeData"]["28_Eda_" . $dataidx] == $_SESSION["K_S03_Sentei_Hyoka" .$i]){ ?> 
								<?php if($_SESSION["K_S03_Sentei_Hyoka" .$i]==88 ||$_SESSION["K_S03_Sentei_Hyoka" .$i]==99){?><font color="red"><?php } ?>【<?php echo $_SESSION["28CodeData"]["28_CodeName1_" . $dataidx] ?>】<?php if($_SESSION["K_S03_Sentei_Hyoka" .$i]==88 ||$_SESSION["K_S03_Sentei_Hyoka" .$i]==99){?></font><?php } ?>
							<?php } ?>
						<?php } ?>
					</td>
					<td align="center" <?php if($_SESSION["K_S03_Sentei_ZUMI" . $i]==1){?> bgcolor="#F6CEF5" <?php } ?>><?php echo floor ((date('Ymd') - date('Ymd', strtotime($_SESSION["K_S03_Sentei_BirthDay" .$i])))/10000); ?></td>
					<td align="center" <?php if($_SESSION["K_S03_Sentei_ZUMI" . $i]==1){?> bgcolor="#F6CEF5" <?php } ?>><?php if($_SESSION["K_S03_Sentei_Seibetu" .$i]==1){?>男<?php }else{?>女<?php } ?></td>
					<td align="left" <?php if($_SESSION["K_S03_Sentei_ZUMI" . $i]==1){?> bgcolor="#F6CEF5" <?php } ?>><?php echo $_SESSION["K_S03_Sentei_Add_ken" .$i] .$_SESSION["K_S03_Sentei_Add_shi" .$i] .$_SESSION["K_S03_Sentei_Add_ku" .$i] .$_SESSION["K_S03_Sentei_Add_cho" .$i]?></td>
					<td align="center" <?php if($_SESSION["K_S03_Sentei_ZUMI" . $i]==1){?> bgcolor="#F6CEF5" <?php } ?>><?php echo $_SESSION["K_S03_Sentei_Uni1" .$i] ?><BR><?php echo $_SESSION["K_S03_Sentei_Dept1" .$i] ?></td>
					<td align="center" <?php if($_SESSION["K_S03_Sentei_ZUMI" . $i]==1){?> bgcolor="#F6CEF5" <?php } ?>><?php echo $_SESSION["K_S03_Sentei_Ensen1" .$i] ?><BR><?php echo $_SESSION["K_S03_Sentei_Ensen2" .$i] ?><BR><?php echo $_SESSION["K_S03_Sentei_Ensen3" .$i] ?></td>
					<td height="30" align="center" <?php if($_SESSION["K_S03_Sentei_ZUMI" . $i]==1){?> bgcolor="#F6CEF5" <?php } ?>><input id="Sel_<?php echo $i ?>" type="button" name="Sel_<?php echo $i ?>" <?php if($_SESSION["K_S03_Sentei_ZUMI" . $i]==1){?> DISABLED <?php } ?> onClick="this.form.target='_top';sbmfnc(this,<?php echo $_SESSION["TourokuFlg"] ?>);" style="cursor: pointer" value="選択" /></td>
					<input type="hidden" name="K_S03_Sentei_TeacherID<?php echo $i ?>" value="<?php echo $_SESSION["K_S03_Sentei_TeacherID" .$i]; ?>">
				</tr>
			<?php } ?>
		</table>
	</div>
<BR><BR><BR>
</form>
</body>
</CENTER>
</html>