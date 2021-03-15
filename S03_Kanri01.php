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
		}
		if(isset($_GET['KEY1'])) {
			$_SESSION["S03_Kensaku_KEY1"] = $_GET['KEY1'];
		}
		if(isset($_GET['SEQ'])) {
			$_SESSION["S03_Kensaku_Seq"] = $_GET['SEQ'];
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
				$CodeData = GetCodeData("現状","","",1);
				$_SESSION["23CodeData"]=$CodeData;

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
				header("Location:S00_Atena01.php?MODE=UPD&RPID=S03_Kanri01&KEY1=" . $_SESSION["S03_Kensaku_KEY1"] . "&SEQ=" . $_SESSION["S03_Kensaku_Seq"]);
				break;
		}	
	}
//-----------------------------------------------------------
//	セッション情報クリア
//-----------------------------------------------------------
function SessionClear(){

	$_SESSION["S03_Koza_DataCount"]=1;

	$_SESSION["KozaFlg"]="1";
	$_SESSION["TorokuJyohoFlg"]="1";
	$_SESSION["KibouFlg"]="1";
	$_SESSION["TokkiFlg"]="1";

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
	$_SESSION["S03_Genjyo9"]="";
	$_SESSION["S03_Genjyo10"]="";
	$_SESSION["S03_Genjyo99"]="";
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
			if($_SESSION["S00_Genjyo10"] >= 1){
				$S00_Genjyo10 = $_SESSION["S00_Genjyo10"];
				$Gengyolen = strlen($_SESSION["S00_Genjyo10"]);
				for($i=0; $i<$Gengyolen; $i++ ){
					$m = $i + 10;
					$_SESSION["S00_Genjyo" . $m] = substr($S00_Genjyo10,$i,1);
				}
				$_SESSION["S00_GenjyoCount"] = $m;
			}else{
				$_SESSION["S00_GenjyoCount"] = $_SESSION["23CodeData"]["23DataCount"] - 1;
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
	 	// データベースの切断
		$mysqli->close();


}

?>

<script type="text/javascript" src="utility.js"></script>

<CENTER>
<body>

<form name="form1" method="post" action="S03_Kanri01.php">
	<table border="0" width="100%">
		<tr align="Right">
			<td align="right">
				<input type="hidden" id="submitter" name="submitter" value="" />
				<?php if($_SESSION["ShoriID"] == "VIEW"){ ?>
					<input type="button" id="modorushori" name="modorushori" onClick="sbmfnc(this,<?php echo $_SESSION["TourokuFlg"] ?>)" style="cursor: pointer" value="戻る" />
				<?php }else{ ?>
					<input type="button" id="updateshori" name="updateshori" onClick="this.form.target='_top';sbmfnc(this,<?php echo $_SESSION["TourokuFlg"] ?>)" style="cursor: pointer" value="生徒情報更新" <?php if($_SESSION["shikaku2"]==0){ ?>Disabled<?php } ?>/>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="<?php echo STUDENT_COLOR ?>">生　徒　情　報</td>
		</tr>
	</table>
<div id="tbl-bdr">
	<table>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">生徒ID</td>
			<td align="center" width="50"><?php echo $_SESSION["S03_StudentID"] ?>-<?php echo $_SESSION["S03_Seq"] ?></td>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">登録日</td>
			<td align="center" width="120"><?php if(is_null($_SESSION["S03_EntryDay"])){ }else{ echo date('Y年n月j日', strtotime($_SESSION["S03_EntryDay"])); } ?></td>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">コース</td>
			<td align="center" width="120" colspan="2"></td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">生徒名</td>
			<td align="left" colspan="4"><?php echo $_SESSION["S03_Name1"]?>　(<?php echo $_SESSION["S03_Name2"]?>)</td>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">生年月日</td>
			<td align="center" width="120"><?php if(is_null($_SESSION["S03_BirthDay"])){ }else{ echo date('Y年n月j日', strtotime($_SESSION["S03_BirthDay"])); } ?><?php echo $_SESSION["S03_Old2"]?>歳</td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">学校名</td>
			<td align="left" colspan="4"><?php echo $_SESSION["S03_SchoolName"]?>　
			<?php 
				for($dataidx=0; $dataidx < $_SESSION["13CodeData"]["13DataCount"]; $dataidx++){
					if($_SESSION["S03_gread"] == $_SESSION["13CodeData"]["13_Eda_" . $dataidx]){
						echo $_SESSION["13CodeData"]["13_CodeName2_" . $dataidx];
					}
				}
			?>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">年齢・性別</td>
			<td align="left"><?php echo $_SESSION["S03_old"]?>　
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
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">保護者①</td>
			<td align="left" colspan="4"><?php echo $_SESSION["S03_Hogosha1"]?>（<?php echo $_SESSION["S03_HogoshaKana1"]?>）</td>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">続柄</td>
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
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">保護者②</td>
			<td align="left" colspan="4"><?php echo $_SESSION["S03_Hogosha2"]?>（<?php echo $_SESSION["S03_HogoshaKana2"]?>）</td>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">続柄</td>
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

		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">住所</td>
			<td align="left" colspan="6"><?php echo $_SESSION["S03_Yubin1_1"]?>-<?php echo $_SESSION["S03_Yubin1_2"]?>　<?php echo $_SESSION["S03_Add_ken1"]?><?php echo $_SESSION["S03_Add_shi1"]?><?php echo $_SESSION["S03_Add_ku1"]?><?php echo $_SESSION["S03_Add_cho1"]?></td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">その他</td>
			<td align="left" colspan="6"><?php echo $_SESSION["S03_Yubin2_1"]?>-<?php echo $_SESSION["S03_Yubin2_2"]?>　<?php echo $_SESSION["S03_Add_ken2"]?><?php echo $_SESSION["S03_Add_shi2"]?><?php echo $_SESSION["S03_Add_ku2"]?><?php echo $_SESSION["S03_Add_cho2"]?></td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="2">交通</td>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">沿線</td>
			<td align="left" colspan="2"><?php echo $_SESSION["S03_Kotu_rosen"]?></td>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">駅・バス</td>
			<td align="left" colspan="2"><?php echo $_SESSION["S03_Kotu_Eki"]?>　<?php echo $_SESSION["S03_Kotu_Toho"]?>分</td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">車使用</td>
			<td align="left" colspan="2"><?php if($_SESSION["S03_CarTF"]==1){?>可能<?php }elseif(is_null($_SESSION["S03_CarTF"])){ ?><?php }else{ ?>不可<?php } ?></td>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">その他</td>
			<td align="left" colspan="2"><?php echo $_SESSION["S03_Kotu_Sonota"]?></td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">兄弟①</td>
			<td align="left" colspan="6">
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
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">兄弟②</td>
			<td align="left" colspan="6">
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
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" colspan="3">電話</td>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" colspan="4">メール</td>
		</tr>
		<?php for($idx=1; $idx <= 3; $idx++){ ?>
			<tr>
				<td align="left">
					<?php 
						for($dataidx=0; $dataidx < $_SESSION["14CodeData"]["14DataCount"]; $dataidx++){
							if($_SESSION["S03_Tel_Kubun" .$idx] == $_SESSION["14CodeData"]["14_Eda_" . $dataidx]){
								echo $_SESSION["14CodeData"]["14_CodeName1_" . $dataidx];
							}
						} 
					?>
				</td>
				<td align="left" colspan="2"><?php echo $_SESSION["S03_Tel" .$idx]?></td>
				<td align="left">
					<?php 
						for($dataidx=0; $dataidx < $_SESSION["14CodeData"]["14DataCount"]; $dataidx++){
							if($_SESSION["S03_Mail_Kubun" .$idx] == $_SESSION["14CodeData"]["14_Eda_" . $dataidx]){
								echo $_SESSION["14CodeData"]["14_CodeName1_" . $dataidx];
							}
						} 
					?>
				</td>
				<td align="left" colspan="3"><?php echo $_SESSION["S03_Mail" .$idx]?></td>
			</tr>
		<?php } ?>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" colspan="2">連絡可能時間</td>
			<td align="left" colspan="5">
				<?php for($dataidx=0; $dataidx < $_SESSION["15CodeData"]["15DataCount"]; $dataidx++){ ?>
					<input type="checkbox" name="S03_ContactTime" value="<?php echo $_SESSION["S03_ContactTime" .$dataidx]?>" <?php if($_SESSION["S03_ContactTime" .$dataidx] == 1){?> CHECKED <?php } ?> disabled><?php echo $_SESSION["15CodeData"]["15_CodeName1_" . $dataidx] ?>　
				<?php } ?>
			</td>
		</tr>

		<?php if($_SESSION["KozaFlg"] == "0"){ ?>
			<?php $Row = $_SESSION["S03_Koza_DataCount"]*3;?>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="<?php echo $Row?>"><input type="button" id="Koza" name="Koza" onClick="sbmfnc(this,'')" style="cursor: pointer" value="-口座情報" /></td>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">記号番号</td>
				<td align="left" colspan="2"><?php echo $_SESSION["S03_Koza_Kigou0"]?>-<?php echo $_SESSION["S03_Koza_Bango0"]?></td>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">開始日</td>
				<td align="left" colspan="2"><?php if(is_null($_SESSION["S03_Koza_Start0"]) || $_SESSION["S03_Koza_Start0"]==""){ }else{ echo date('Y年n月j日', strtotime($_SESSION["S03_Koza_Start0"])); } ?>　</td>
			</tr>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">名義人</td>
				<td align="left" colspan="2"><?php echo $_SESSION["S03_Koza_Meigi0"]?>　(<?php echo $_SESSION["S03_Koza_MeigiKana0"]?>)</td>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">終了日</td>
				<td align="left" colspan="2"><?php if(is_null($_SESSION["S03_Koza_End0"]) || $_SESSION["S03_Koza_End0"]==""){ }else{ echo date('Y年n月j日', strtotime($_SESSION["S03_Koza_End0"])); } ?>　</td>
			</tr>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">備考</td>
				<td align="left" colspan="5"><?php echo $_SESSION["S03_Koza_Biko0"]?>　(<?php echo $_SESSION["S03_Koza_Biko0"]?>)</td>
			</tr>
			<?php for($m=1; $m<$_SESSION["S03_Koza_DataCount"]; $m++){ ?>
				<tr>
					<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">記号番号</td>
					<td align="left" colspan="2"><?php echo $_SESSION["S03_Koza_Kigou" .$m]?>-<?php echo $_SESSION["S03_Koza_Bango" .$m]?></td>
					<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">開始日</td>
					<td align="left" colspan="2"><?php if(is_null($_SESSION["S03_Koza_Start" .$m]) || $_SESSION["S03_Koza_Start" .$m]==""){ }else{ echo date('Y年n月j日', strtotime($_SESSION["S03_Koza_Start" .$m])); } ?>　</td>
				</tr>
				<tr>
					<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">名義人</td>
					<td align="left" colspan="2"><?php echo $_SESSION["S03_Koza_Meigi" .$m]?>　(<?php echo $_SESSION["S03_Koza_MeigiKana" .$m]?>)</td>
					<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">終了日</td>
					<td align="left" colspan="2"><?php if(is_null($_SESSION["S03_Koza_End" .$m]) || $_SESSION["S03_Koza_End" .$m]==""){ }else{ echo date('Y年n月j日', strtotime($_SESSION["S03_Koza_End" .$m])); } ?>　</td>
				</tr>
				<tr>
					<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">備考</td>
					<td align="left" colspan="5"><?php echo $_SESSION["S03_Koza_Biko" .$m]?>　(<?php echo $_SESSION["S03_Koza_Biko" .$m]?>)</td>
				</tr>
			<?php } ?>
		<?php }else{ ?>
			<tr>
				<td align="left" colspan="7"><input type="button" id="Koza2" name="Koza2" onClick="sbmfnc(this,'')" style="cursor: pointer" value="+口座情報" /></td>
			</tr>
		<?php } ?>
		<?php if($_SESSION["TorokuJyohoFlg"] == "0"){ ?>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="6"> <input type="button" id="TorokuJyoho" name="TorokuJyoho" onClick="sbmfnc(this,'')" style="cursor: pointer" value="-登録情報" /></td>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">申込日</td>
				<td align="left" colspan="6"><?php echo $_SESSION["S03_TorokuDay"]?></td>
			</tr>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="3">現状</td>
				<td align="left" colspan="5">
					<?php $CodeCnt = ceil($_SESSION["S00_GenjyoCount"] / 2);?>
					<?php $CodeCnt2 = $CodeCnt + 1;?>
					<?php for($dataidx=0; $dataidx < $CodeCnt; $dataidx++){ ?>
						<?php $m = $dataidx + 1;?>
						<input type="checkbox" name="S03_Genjyo<?php echo $m ?>" value="<?php echo $_SESSION["23CodeData"]["23_Eda_" . $dataidx]?>" <?php if($_SESSION["S03_Genjyo" . $m] == 1){?> CHECKED <?php } ?> disabled><?php echo $_SESSION["23CodeData"]["23_CodeName1_" . $dataidx] ?>　
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td align="left" colspan="5">
					<?php for($dataidx=$CodeCnt; $dataidx < $_SESSION["S00_GenjyoCount"]; $dataidx++){ ?>
						<?php $m = $dataidx + 1;?>
						<input type="checkbox" name="S00_Genjyo<?php echo $m ?>" value="<?php echo $_SESSION["23CodeData"]["23_Eda_" . $dataidx]?>" <?php if($_SESSION["S00_Genjyo" . $m] == 1){?> CHECKED <?php } ?>><?php echo $_SESSION["23CodeData"]["23_CodeName1_" . $dataidx] ?>　
					<?php } ?>
					<input type="checkbox" name="S00_Genjyo99" value="<?php echo $_SESSION["23CodeData"]["23_Eda_" . $dataidx]?>" <?php if($_SESSION["S00_Genjyo99"] == 1){?> CHECKED <?php } ?>><?php echo $_SESSION["23CodeData"]["23_CodeName1_" . $dataidx] ?>　
				</td>
			</tr>
			<tr>
				<td align="left" colspan="5">
					<input type="checkbox" name="S03_Genjyo8" value="<?php echo $_SESSION["S03_Genjyo8"]?>" <?php if($_SESSION["S03_Genjyo8"] == 1){?> CHECKED <?php } ?> disabled>その他　
					（<?php echo $_SESSION["S03_Genjyo_Sonota"]?>）
				</td>
			</tr>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="2">相談</td>
				<td align="left" colspan="5"><?php echo $_SESSION["S03_Sonota_Naiyo"]?></td>
			</tr>
			<tr>
				<td align="left" colspan="5"><?php echo $_SESSION["S03_Soudan"]?></td>
			</tr>
		<?php }else{ ?>
			<tr>
				<td align="left" colspan="7"><input type="button" id="TorokuJyoho2" name="TorokuJyoho2" onClick="sbmfnc(this,'')" style="cursor: pointer" value="+登録情報" /></td>
			</tr>
		<?php } ?>
		<?php if($_SESSION["KibouFlg"] == "0"){ ?>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="6"> <input type="button" id="KibouJyoho" name="KibouJyoho" onClick="sbmfnc(this,'')" style="cursor: pointer" value="-希望情報" /></td>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">教科</td>
				<td align="left" colspan="5">
					<?php 
						for($m=1; $m<=6; $m++){
							$kyoka = "小学";
							if($_SESSION["S03_Sub1_" . $m] == 1){
								echo $kyoka . $_SESSION["05CodeData"]["05_CodeName1_" . $m] . "　";
							}
						}
						for($m=1; $m<=6; $m++){
							$kyoka = "中学";
							if($_SESSION["S03_Sub2_" . $m] == 1){
								echo $kyoka . $_SESSION["06CodeData"]["06_CodeName1_" . $m] . "　";
							}
						}
						for($m=1; $m<=20; $m++){
							$kyoka = "高校";
							if($_SESSION["S03_Sub3_" . $m] == 1){
								echo $kyoka . $_SESSION["07CodeData"]["07_CodeName1_" . $m] . "　";
							}
						}
					?>
					<BR>
					<?php echo $_SESSION["S03_Sub4_1"] ?>
				</td>
			</tr>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">曜日</td>
				<td align="left" colspan="5">
					<input type="checkbox" name="S03_Youbi1" value="<?php echo $_SESSION["S03_Youbi1"]?>" <?php if($_SESSION["S03_Youbi1"] == 1){?> CHECKED <?php } ?> disabled>月　
					<input type="checkbox" name="S03_Youbi2" value="<?php echo $_SESSION["S03_Youbi2"]?>" <?php if($_SESSION["S03_Youbi2"] == 1){?> CHECKED <?php } ?> disabled>火　
					<input type="checkbox" name="S03_Youbi3" value="<?php echo $_SESSION["S03_Youbi3"]?>" <?php if($_SESSION["S03_Youbi3"] == 1){?> CHECKED <?php } ?> disabled>水　
					<input type="checkbox" name="S03_Youbi4" value="<?php echo $_SESSION["S03_Youbi4"]?>" <?php if($_SESSION["S03_Youbi4"] == 1){?> CHECKED <?php } ?> disabled>木　
					<input type="checkbox" name="S03_Youbi5" value="<?php echo $_SESSION["S03_Youbi5"]?>" <?php if($_SESSION["S03_Youbi5"] == 1){?> CHECKED <?php } ?> disabled>金　
					<input type="checkbox" name="S03_Youbi6" value="<?php echo $_SESSION["S03_Youbi6"]?>" <?php if($_SESSION["S03_Youbi6"] == 1){?> CHECKED <?php } ?> disabled>土　
					<input type="checkbox" name="S03_Youbi7" value="<?php echo $_SESSION["S03_Youbi7"]?>" <?php if($_SESSION["S03_Youbi7"] == 1){?> CHECKED <?php } ?> disabled>日　
					<BR><?php echo $_SESSION["S03_Youbi_Sonota"]?>
				</td>
			</tr>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">時間</td>
				<td align="left" colspan="5">
					<?php echo $_SESSION["S03_ShidoTime"]?>
				</td>
			</tr>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">指導内容</td>
				<td align="left" colspan="5">
					<?php echo $_SESSION["S03_ShidoKibou"]?>
				</td>

			</tr>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="2">希望教師</td>
				<td align="left" colspan="5">
					<?php for($dataidx=0; $dataidx < $_SESSION["18CodeData"]["18DataCount"]; $dataidx++){ ?>
						<input type="checkbox" name="S03_KyoushiKibou1" value="<?php echo $_SESSION["S03_KyoushiKibou" . $dataidx]?>" <?php if($_SESSION["S03_KyoushiKibou" . $dataidx] == 1){?> CHECKED <?php } ?> disabled><?php echo $_SESSION["18CodeData"]["18_CodeName1_" . $dataidx] ?>　
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td align="left" colspan="5">
					<?php echo $_SESSION["S03_KyoushiKibouNaiyo"]?>
				</td>
			</tr>
		<?php }else{ ?>
			<tr>
				<td align="left" colspan="7"><input type="button" id="KibouJyoho2" name="KibouJyoho2" onClick="sbmfnc(this,'')" style="cursor: pointer" value="+希望情報" /></td>
			</tr>
		<?php } ?>
		<?php if($_SESSION["TokkiFlg"] == "0"){ ?>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="8"> <input type="button" id="TokkiJiko" name="TokkiJiko" onClick="sbmfnc(this,'')" style="cursor: pointer" value="-特記事項" /></td>
				<td align="left" colspan="6"><?php echo $_SESSION["S03_Notice1"]?></td>
			</tr>
			<tr>
				<td align="left" colspan="6"><?php echo $_SESSION["S03_Notice2"]?></td>
			</tr>
			<tr>
				<td align="left" colspan="6"><?php echo $_SESSION["S03_Notice3"]?></td>
			</tr>
			<tr>
				<td align="left" colspan="6"><?php echo $_SESSION["S03_Notice4"]?></td>
			</tr>
			<tr>
				<td align="left" colspan="6"><?php echo $_SESSION["S03_Notice5"]?></td>
			</tr>
			<tr>
				<td align="left" colspan="6"><?php echo $_SESSION["S03_notice1"]?></td>
			</tr>
			<tr>
				<td align="left" colspan="6"><?php echo $_SESSION["S03_notice2"]?></td>
			</tr>
			<tr>
				<td align="left" colspan="6"><?php echo $_SESSION["S03_notice3"]?></td>
			</tr>

		<?php }else{ ?>
			<tr>
				<td align="left" colspan="7"><input type="button" id="TokkiJiko2" name="TokkiJiko2" onClick="sbmfnc(this,'')" style="cursor: pointer" value="+特記事項" /></td>
			</tr>
		<?php } ?>
	</table>
</div>
</form>
</body>
</CENTER>
</html>