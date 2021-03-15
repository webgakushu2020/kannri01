<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN"
	"http://www.w3.org/TR/html4/frameset.dtd">

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="Header.css">
	<title>教師管理画面</title>
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

    if(isset($_POST['submitter'])){
		if($_POST['submitter']=="deleteimg"){
            $_SESSION["FileName"]=$_SESSION["ImageNameDisp_0"];
		 	$_SESSION["ShoriID"]="DEL02";
		}
	}
    	// ファイル削除ボタン
    if(isset($_POST['submitter'])){
        $SubmName = substr($_POST['submitter'],0,10);
        $SubmNo = substr($_POST['submitter'],10,1);
        $DataNo = substr($_POST['submitter'],-1);
        if($SubmName=="deletefile"){
            switch ($SubmNo){
                case '1':
                    for ($m = 0; $m < $_SESSION["FileNameCnt"]; $m++){
                        if($DataNo == $m){
                            $_SESSION["FileName"]=$_SESSION["FileName_" . $m];
                            $_SESSION["ShoriID"]="DEL01";
                            break;
                        }
                    }
                    break;
                case '3':
                    for ($m = 0; $m < $_SESSION["FileNameCnt_sonota"]; $m++){
                        if($DataNo == $m){
                            $_SESSION["FileName"]=$_SESSION["FileName_sonota_" . $m];
                            $_SESSION["ShoriID"]="DEL03";
                            break;
                        }
                    }
                    break;
                case '4':
                    for ($m = 0; $m < $_SESSION["ImageNameCnt_sonota"]; $m++){
                        if($DataNo == $m){
                            $_SESSION["FileName"]=$_SESSION["ImageName_sonota_" . $m];
                            $_SESSION["ShoriID"]="DEL04";
                            break;
                        }
                    }
                    break;
            }
        }
    }

	if(isset($_POST['submitter'])){
		switch ($_POST['submitter']){
			case 'kyoka':
				$_SESSION["ShoriID"]="KYOKA";
				break;
			case 'kyoka2':
				$_SESSION["ShoriID"]="KYOKA2";
				break;
			case 'shikaku':
				$_SESSION["ShoriID"]="SHIKAKU";
				break;
			case 'shikaku2':
				$_SESSION["ShoriID"]="SHIKAKU2";
				break;
			case 'keiken':
				$_SESSION["ShoriID"]="KEIKEN";
				break;
			case 'keiken2':
				$_SESSION["ShoriID"]="KEIKEN2";
				break;
			case 'sonota':
				$_SESSION["ShoriID"]="SONOTA";
				break;
			case 'sonota2':
				$_SESSION["ShoriID"]="SONOTA2";
				break;
			case 'sonota_2':
				$_SESSION["ShoriID"]="SONOTA_2";
				break;
			case 'sonota2_2':
				$_SESSION["ShoriID"]="SONOTA2_2";
				break;
			case 'Koza':
				$_SESSION["ShoriID"]="KOZA";
				break;
			case 'Koza2':
				$_SESSION["ShoriID"]="KOZA2";
				break;
			case 'updateshori':
				$_SESSION["ShoriID"]="UPDATESHORI";
				break;
			case 'fileupload':
				$_SESSION["ShoriID"]="FILEUP";
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
		if(isset($_GET['KEY1'])) {
			$_SESSION["Kensaku_KEY1"] = $_GET['KEY1'];
		}
		switch ($_SESSION["ShoriID"]){
			case 'UPD':
				SessionClear();
				GetData();
                fileDownload($_SESSION["T03_TeacherID"]);
				break;
			case 'KYOKA':
				$_SESSION["KyokaFlg"]="1";
				break;
			case 'KYOKA2':
				$_SESSION["KyokaFlg"]="0";
				break;
			case 'SHIKAKU':
				$_SESSION["ShikakuFlg"]="1";
				break;
			case 'SHIKAKU2':
				$_SESSION["ShikakuFlg"]="0";
				break;
			case 'KEIKEN':
				$_SESSION["KeikenFlg"]="1";
				break;
			case 'KEIKEN2':
				$_SESSION["KeikenFlg"]="0";
				break;
			case 'SONOTA':
				$_SESSION["SonotaFlg"]="1";
				break;
			case 'SONOTA2':
				$_SESSION["SonotaFlg"]="0";
				break;
			case 'SONOTA_2':
				$_SESSION["SonotaFlg2"]="1";
				break;
			case 'SONOTA2_2':
				$_SESSION["SonotaFlg2"]="0";
				break;
			case 'KOZA':
				$_SESSION["KozaFlg"]="1";
				break;
			case 'KOZA2':
				$_SESSION["KozaFlg"]="0";
				break;
			case 'UPDATESHORI':
				header("Location:T00_Atena01.php?MODE=UPD&RPID=T03_Kanri01&KEY1=" . $_SESSION["Kensaku_KEY1"]);
				break;
            case 'FILEUP':
                $_SESSION["T03_FileType"]=$_POST['T03_FileType'];
                if($_SESSION["T03_FileType"]==""){
                    $EMSG="ファイル種類を選択してください。";
                }
                if($EMSG==""){
                    $EMSG = Uploadshori();
                }
                fileDownload($_SESSION["T03_TeacherID"]);
                $_SESSION["T03_FileType"]="";
				break;
            case 'DEL01':
            case 'DEL02':
            case 'DEL03':
            case 'DEL04':
				$EMSG=FileDelete($_SESSION["ShoriID"]);
                 if($EMSG!="ファイル削除に失敗しました"){
                   fileDownload($_SESSION["T03_TeacherID"]);
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

	$_SESSION["T03_Koza_DataCount"] = 1;

	$_SESSION["KyokaFlg"]="1";
	$_SESSION["ShikakuFlg"]="1";
	$_SESSION["KeikenFlg"]="1";
	$_SESSION["SonotaFlg"]="1";
	$_SESSION["SonotaFlg2"]="1";
	$_SESSION["KozaFlg"]="1";

	$_SESSION["T03_TeacherID"]="";
	$_SESSION["T03_EntryDay"]="";
	$_SESSION["T03_BirthDay"]="";
	$_SESSION["T03_Name1"]="";
	$_SESSION["T03_Name2"]="";
	$_SESSION["T03_Old"]= "";
	$_SESSION["T03_Seibetu"]="";

	$_SESSION["T03_Yubin1"]="";
	$_SESSION["T03_Yubin2"]="";
	$_SESSION["T03_Add_ken"]="";
	$_SESSION["T03_Add_Ken_Code"]="";
	$_SESSION["T03_Add_ken"]="";
	$_SESSION["T03_Add_shi"]="";
	$_SESSION["T03_Add_ku"]="";
	$_SESSION["T03_Add_cho"]="";

	$_SESSION["T03_Tel1"]="";
	$_SESSION["T03_Tel2"]="";
	$_SESSION["T03_Tel3"]="";
	$_SESSION["T03_Mail1"]="";
	$_SESSION["T03_Mail2"]="";

	$_SESSION["T03_Uni1"]="";
	$_SESSION["T03_Dept1"]="";
	$_SESSION["T03_Gradu1"]="";

	$_SESSION["T03_Ensen1"]="";
	$_SESSION["T03_Ensen2"]="";
	$_SESSION["T03_Ensen3"]="";

	$_SESSION["T03_License1"]="";
	$_SESSION["T03_License2"]="";
	$_SESSION["T03_License3"]="";

	$_SESSION["T03_Exp_Kyou"]="";
	$_SESSION["T03_Exp_Juken"]="";
	$_SESSION["T03_Gra_Hight"]="";
	$_SESSION["T03_Gra_Junior"]="";

	$_SESSION["T03_Other1"]="";
	$_SESSION["T03_Other2"]="";
	$_SESSION["T03_Other3"]="";
	$_SESSION["T03_Other4"]="";
	$_SESSION["T03_Other5"]="";

	for($m=1; $m<=10; $m++){
		$_SESSION["T03_Sub1_" . $m]="";
	}
	for($m=1; $m<=10; $m++){
		$_SESSION["T03_Sub2_" . $m]="";
	}
	for($m=1; $m<=25; $m++){
		$_SESSION["T03_Sub3_" . $m]="";
	}
	for($m=1; $m<=5; $m++){
		$_SESSION["T03_Sub4_" . $m]="";
	}
	for($m=1; $m<=5; $m++){
		$_SESSION["T03_Notice_" . $m]="";
	}

	$_SESSION["DateFlg"]=0;

	$CodeData = array();
	$CodeData = GetCodeData("評価","","",1);
	$_SESSION["28CodeData"]=$CodeData;
	$CodeData = array();
	$CodeData = GetCodeData("ファイルアップロード","","",1);
	$_SESSION["32CodeData"]=$CodeData;

    $_SESSION["T03_FileType"]="";
    $_SESSION["FileName"]="";
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
		$query = $query . " FROM T_AtenaInfo as a inner join";
		$query = $query . " T_KihonInfo as b on";
		$query = $query . " a.TeacherID=b.TeacherID";
		$query = $query . " inner join T_ShosaiInfo as c on";
		$query = $query . " a.TeacherID=c.TeacherID";

		if($_SESSION["Kensaku_KEY1"]!=""){
			if($query2 == ""){
				$query2 = $query2 . " Where a.TeacherID=" . $_SESSION["Kensaku_KEY1"];
			}else{
				$query2 = $query2 . " And a.TeacherID=" . $_SESSION["Kensaku_KEY1"];
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
		while($arr_item = $result->fetch_assoc()){

			//レコード内の各フィールド名と値を順次参照
			foreach($arr_item as $key => $value){
				//フィールド名と値を表示
				$_SESSION["T03_" . $key] = $value;
			}
			
			if(is_null($_SESSION['T03_BirthDay'])){
				$_SESSION["T03_Old"]="";
			}else{
				$_SESSION["T03_Old"]=floor ((date('Ymd') - date('Ymd', strtotime($_SESSION['T03_BirthDay'])))/10000);
			}
		}

		//------------------------口座情報------------------------------
		$_SESSION["T03_Koza_TeacherID0"]="";
		$_SESSION["T03_Koza_KozaSeq0"]="";
		$_SESSION["T03_Koza_Start0"]="";
		$_SESSION["T03_Koza_End0"]="";
		$_SESSION["T03_Koza_Kigou0"]="";
		$_SESSION["T03_Koza_Bango0"]="";
		$_SESSION["T03_Koza_Meigi0"]="";
		$_SESSION["T03_Koza_MeigiKana0"]="";
		$_SESSION["T03_Koza_Biko0"]="";

		$query3 = "Select * ";
		$query3 = $query3 . " FROM T_KozaInfo ";
		$query3 = $query3 . " Where TeacherID='" . $_SESSION["Kensaku_KEY1"] . "'";
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
			$_SESSION["T03_Koza_TeacherID" .$i]="";
			$_SESSION["T03_Koza_KozaSeq" .$i]="";
			$_SESSION["T03_Koza_Start" .$i]="";
			$_SESSION["T03_Koza_End" .$i]="";
			$_SESSION["T03_Koza_Kigou" .$i]="";
			$_SESSION["T03_Koza_Bango" .$i]="";
			$_SESSION["T03_Koza_Meigi" .$i]="";
			$_SESSION["T03_Koza_MeigiKana" .$i]="";
			$_SESSION["T03_Koza_Biko" .$i]="";

			$_SESSION["T03_Koza_TeacherID" .$i]=$data2[$i]['TeacherID'];
			$_SESSION["T03_Koza_KozaSeq" .$i]=$data2[$i]['KozaSeq'];
			$_SESSION["T03_Koza_Start" .$i]=$data2[$i]['Start'];
			$_SESSION["T03_Koza_End" .$i]=$data2[$i]['End'];
			$_SESSION["T03_Koza_Kigou" .$i]=$data2[$i]['Kigou'];
			$_SESSION["T03_Koza_Bango" .$i]=$data2[$i]['Bango'];
			$_SESSION["T03_Koza_Meigi" .$i]=$data2[$i]['Meigi'];
			$_SESSION["T03_Koza_MeigiKana" .$i]=$data2[$i]['MeigiKana'];
			$_SESSION["T03_Koza_Biko" .$i]=$data2[$i]['Biko'];

			$i++;
		}
		if($i > 0){
			$_SESSION["T03_Koza_DataCount"] = $i;
		}

	 	// データベースの切断
		$mysqli->close();


}
function Uploadshori(){
        //アップロード種類
        $CodeData = array();
        $CodeName_Where = "Eda = '" . $_SESSION["T03_FileType"] . "'";
        $CodeData = GetCodeData("ファイルアップロード",$CodeName_Where,"",1);
        $_SESSION["32CodeName"]=$CodeData;
        $File_name = $_SESSION["32CodeName"]["32_CodeName2_0"];
    
        //tempファイル名
        $tmp_file = @$_FILES['attachment_file']['tmp_name'];
        //ファイル名
        $tmp_file_name = @$_FILES['attachment_file']['name'];
        //拡張子を分割
        @list($file_name,$file_type) = explode(".",$tmp_file_name);
        //ファイル名の生成
        $copy_file = $File_name . $_SESSION["T03_TeacherID"] . "_" . date("Ymd-His") . $file_type;

        //ディレクトリ指定
/*        if($_SESSION["T03_FileType"] == "01"){
            $updir = FILE_DIR;
        }else{
            $updir = IMG_DIR;
        }
*/
		switch ($_SESSION["T03_FileType"]){
			case '01':
                $updir = FILE_DIR;
                break;
			case '02':
                $updir = IMG_DIR;
                break;
			case '03':
                $updir = FILE_DIR2;
                break;
			case '04':
                $updir = IMG_DIR2;
                break;
        }
    
        //フォルダ作成
        // 親ディレクトリへのパス
        $path = $updir . "/";

        // 作成するディレクトリ名
        $dir_name = $_SESSION["T03_TeacherID"];

        // 親ディレクトリが書き込み可能か、および同じ名前のディレクトリが存在しないか確認
        if( is_writable($path) && !file_exists($path.$dir_name)) {
            // ディレクトリを作成
            if(mkdir($path.$dir_name) ) {
                $msg = 'ディレクトリを作成しました。';
            } else {
                $msg = 'ディレクトリの作成に失敗しました。';
            }
        } else {
            $msg = '親ディレクトリが書き込みを許可していないか、すでに同名のディレクトリがあり作成できませんでした。';
        }
    
        $updir2 = $path.$dir_name;
        if (is_uploaded_file($_FILES["attachment_file"]["tmp_name"])) {
            if (move_uploaded_file($tmp_file,"$updir2/$copy_file")) {
//                chmod("upfiles/" . $_FILES["attachment_file"]["name"], 0644);
                $msg = $_FILES["attachment_file"]["name"] . "をアップロードしました。";
            } else {
                $msg =  "ファイルをアップロード出来ませんでした。";
            }
        } else {
            $msg =  "ファイルが選択されていません。";
        }
    return $msg;
}
//------------------------------------------------
//      ファイル削除
//------------------------------------------------
function FileDelete($fl){
    $flShurui = substr($fl, -2);

    switch ($flShurui){
        case '01':   //履歴書
            $dir = FILE_DIR;
            break;
        case '02':   //顔写真
            $dir = IMG_DIR;
            break;
        case '03':   //その他書類
            $dir = FILE_DIR2;
            break;
        case '04':   //その他画像
            $dir = IMG_DIR2;
            break;
    }

    if(unlink($_SESSION["FileName"])){
      $Msg = $_SESSION["FileName"] . "の削除に成功しました。";
    }else{
      $Msg = "ファイル削除に失敗しました";
    }
    return $Msg;
}
?>

<script type="text/javascript" src="utility.js"></script>

<CENTER>
<body onload="">

<form name="form1" method="post" action="T03_Kanri01.php" enctype="multipart/form-data">
    <table border="0" width="100%">
		<font size="5" color="#ff0000"><?php echo $EMSG ?></font>
	</table>
    <div id="tbl-bdr">
        <table>
            <tr>
                <td align="left" colspan="2" width="580">
                    <input type="hidden" id="submitter" name="submitter" value="" />
                    ファイル取り込み：<input type="file" name="attachment_file">
                    <select name="T03_FileType" class="selecttype" >
                        <option value="" <?php if($_SESSION["T03_FileType"] == ""){ ?> SELECTED <?php } ?>></option>
                        <?php for($dataidx=0; $dataidx < $_SESSION["32CodeData"]["32DataCount"]; $dataidx++){ ?>
                            <option value="<?php echo $_SESSION["32CodeData"]["32_Eda_" . $dataidx] ?>" <?php if($_SESSION["32CodeData"]["32_Eda_" . $dataidx] == $_SESSION["T03_FileType"]){ ?> SELECTED <?php } ?>><?php echo $_SESSION["32CodeData"]["32_CodeName1_" . $dataidx] ?></option>
                        <?php } ?>
                    </select>
                    <input type="button" id="fileupload" name="fileupload" onClick="sbmfnc(this,'')" style="cursor: pointer" value="取込" <?php if($_SESSION["shikaku2"]==0){ ?>Disabled<?php } ?>/>
                </td>
                <td rowspan="4" width="110" height="120" id="td_img">
                    <img src="<?php echo $_SESSION["ImageNameDisp_0"] ?>" width="110" height="120" alt="顔写真">
                    <?php if($_SESSION["ImageNameDisp_0"]!=""){?>
                        <input type="button" id="deleteimg" name="deleteimg" onClick="sbmfnc(this,'')" style="cursor: pointer" value="削除" <?php if($_SESSION["shikaku2"]==0){ ?>Disabled<?php } ?>/>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td align="left" width="100">履歴書</td>
                <td>
                    <?php for ($h = 0; $h< $_SESSION["FileNameCnt"]; $h++) { ?>
                        <a href="<?php echo $_SESSION["FileName_" . $h] ?>" target="_blank" style="cursor: pointer;float:left;"><?php echo $_SESSION["FileName_" . $h] ?></a>
                        <input type="button" id="deletefile1_<?php echo $h?>" name="deletefile1_<?php echo $h?>" onClick="sbmfnc(this,'')" style="cursor: pointer;float:right;" value="削除" <?php if($_SESSION["shikaku2"]==0){ ?>Disabled<?php } ?>/><BR>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td align="left">その他書類</td>
                <td>
                    <?php for ($h = 0; $h< $_SESSION["FileNameCnt_sonota"]; $h++) { ?>
                        <a href="<?php echo $_SESSION["FileName_sonota_" . $h] ?>" target="_blank" style="cursor: pointer;float:left;"><?php echo $_SESSION["FileName_sonota_" . $h] ?></a>
                        <input type="button" id="deletefile3_<?php echo $h?>" name="deletefile3_<?php echo $h?>" onClick="sbmfnc(this,'')" style="cursor: pointer;float:right;" value="削除" <?php if($_SESSION["shikaku2"]==0){ ?>Disabled<?php } ?>/><BR>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td align="left">その他画像</td>
                <td>
                    <?php for ($h = 0; $h< $_SESSION["ImageNameCnt_sonota"]; $h++) { ?>
                        <a href="<?php echo $_SESSION["ImageName_sonota_" . $h] ?>" target="_blank" style="cursor: pointer;float:left;"><?php echo $_SESSION["ImageName_sonota_" . $h] ?></a>
                        <input type="button" id="deletefile4_<?php echo $h?>" name="deletefile4_<?php echo $h?>" onClick="sbmfnc(this,'')" style="cursor: pointer;float:right;" value="削除" <?php if($_SESSION["shikaku2"]==0){ ?>Disabled<?php } ?>/><BR>
                    <?php } ?>
                </td>
            </tr>
        </table>
    </div>
    <BR>
    <table border="0" width="100%">
        <tr>
			<td align="center">
				<input type="button" id="updateshori" name="updateshori" onClick="this.form.target='_top';sbmfnc(this,<?php echo $_SESSION["TourokuFlg"] ?>)" style="cursor: pointer" value="教師情報更新" <?php if($_SESSION["shikaku2"]==0){ ?>Disabled<?php } ?>/>
			</td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="<?php echo TEACHR_COLOR ?>">　　教　師　情　報　　
			</td>
		</tr>
	</table>
<div id="tbl-bdr">
	<table>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">教師ID</td>
			<td align="center" width="50"><?php echo $_SESSION["T03_TeacherID"] ?></td>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">登録日</td>
			<td align="center" width="150"><?php if(is_null($_SESSION["T03_EntryDay"])){ }else{ echo date('Y年n月j日', strtotime($_SESSION["T03_EntryDay"])); } ?></td>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">生年月日</td>
			<td align="center" width="150"><?php if(is_null($_SESSION["T03_BirthDay"])){ }else{ echo date('Y年n月j日', strtotime($_SESSION["T03_BirthDay"])); } ?></td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">教師名</td>
			<td align="left" colspan="3">
				<?php echo $_SESSION["T03_Name1"]?>　(<?php echo $_SESSION["T03_Name2"]?>)　　
				<?php for($dataidx=0; $dataidx < $_SESSION["28CodeData"]["28DataCount"]; $dataidx++){ ?>
					<?php if($_SESSION["28CodeData"]["28_Eda_" . $dataidx] == $_SESSION["T03_Hyoka"]){ ?> 
						<?php if($_SESSION["T03_Hyoka"]==88 ||$_SESSION["T03_Hyoka"]==99){?><font color="red"><?php } ?><?php echo $_SESSION["28CodeData"]["28_CodeName2_" . $dataidx] ?><?php if($_SESSION["T03_Hyoka"]==88 ||$_SESSION["T03_Hyoka"]==99){?></font><?php } ?>
					<?php } ?>
				<?php } ?>
			</td>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">年齢・性別</td>
			<td align="left"><?php echo $_SESSION["T03_Old"]?>歳　<?php if($_SESSION["T03_Seibetu"]==1){?>男<?php }else{?>女<?php } ?></td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">住所</td>
			<td align="left" colspan="5"><?php echo $_SESSION["T03_Yubin1"]?>-<?php echo $_SESSION["T03_Yubin2"]?>　<?php echo $_SESSION["T03_Add_ken"]?><?php echo $_SESSION["T03_Add_shi"]?><?php echo $_SESSION["T03_Add_ku"]?><?php echo $_SESSION["T03_Add_cho"]?></td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">個人情報<BR>変更</td>
			<td align="left" colspan="5"><?php echo $_SESSION["T03_Notice1"]?></td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">メール1</td>
			<td align="left" colspan="3"><?php echo $_SESSION["T03_Mail1"]?></td>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">電話1</td>
			<td align="left" colspan="3"><?php echo $_SESSION["T03_Tel1"]?></td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">メール2</td>
			<td align="left" colspan="3"><?php echo $_SESSION["T03_Mail2"]?></td>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">電話2</td>
			<td align="left" colspan="3"><?php echo $_SESSION["T03_Tel2"]?></td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">メール3</td>
			<td align="left" colspan="3"><?php echo $_SESSION["T03_Mail3"]?></td>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">電話3</td>
			<td align="left" colspan="3"><?php echo $_SESSION["T03_Tel3"]?></td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">卒業大学</td>
			<td align="left" colspan="5">
				<?php echo $_SESSION["T03_Uni1"]?>　<?php echo $_SESSION["T03_Dept1"]?>　<?php if(is_null($_SESSION["T03_Gradu1"])){ ?>　<?php }else{ ?><?php echo $_SESSION["T03_Gradu1"]?>年卒<?php } ?><BR>
				<?php echo $_SESSION["T03_Uni2"]?>　<?php echo $_SESSION["T03_Dept2"]?>　<?php if(is_null($_SESSION["T03_Gradu2"])){ ?>　<?php }else{ ?><?php echo $_SESSION["T03_Gradu2"]?>年卒<?php } ?><BR>
			</td>
		</tr>
		<tr>
			<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">沿線</td>
			<td align="left" colspan="5">
				<?php echo $_SESSION["T03_Ensen1"]?><BR>
				<?php echo $_SESSION["T03_Ensen2"]?><BR>
				【車使用】<?php if($_SESSION["T03_Ensen3"]==1){?>　可<?php } ?>
			</td>
		</tr>
		<?php if($_SESSION["KyokaFlg"] == "0"){ ?>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="7">
					<input type="button" id="kyoka" name="kyoka" onClick="sbmfnc(this,'')" style="cursor: pointer" value="-教科" />
				</td>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">小学生</td>
				<td align="left" colspan="4">
					<input type="checkbox" name="T03_Sub1_1" value="<?php echo $_SESSION["T03_Sub1_1"]?>" <?php if($_SESSION["T03_Sub1_1"] == 1){?> CHECKED <?php } ?> disabled>国
					<input type="checkbox" name="T03_Sub1_2" value="<?php echo $_SESSION["T03_Sub1_2"]?>" <?php if($_SESSION["T03_Sub1_2"] == 1){?> CHECKED <?php } ?> disabled>算
					<input type="checkbox" name="T03_Sub1_3" value="<?php echo $_SESSION["T03_Sub1_3"]?>" <?php if($_SESSION["T03_Sub1_3"] == 1){?> CHECKED <?php } ?> disabled>理
					<input type="checkbox" name="T03_Sub1_4" value="<?php echo $_SESSION["T03_Sub1_4"]?>" <?php if($_SESSION["T03_Sub1_4"] == 1){?> CHECKED <?php } ?> disabled>社
					<input type="checkbox" name="T03_Sub1_5" value="<?php echo $_SESSION["T03_Sub1_5"]?>" <?php if($_SESSION["T03_Sub1_5"] == 1){?> CHECKED <?php } ?> disabled>英
					<input type="checkbox" name="T03_Sub1_6" value="<?php echo $_SESSION["T03_Sub1_6"]?>" <?php if($_SESSION["T03_Sub1_6"] == 1){?> CHECKED <?php } ?> disabled>私立受験
				</td>
			</tr>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">中学生</td>
				<td align="left" colspan="4">
					<input type="checkbox" name="T03_Sub2_1" value="<?php echo $_SESSION["T03_Sub2_1"]?>" <?php if($_SESSION["T03_Sub2_1"] == 1){?> CHECKED <?php } ?> disabled>国
					<input type="checkbox" name="T03_Sub2_2" value="<?php echo $_SESSION["T03_Sub2_2"]?>" <?php if($_SESSION["T03_Sub2_2"] == 1){?> CHECKED <?php } ?> disabled>算
					<input type="checkbox" name="T03_Sub2_3" value="<?php echo $_SESSION["T03_Sub2_3"]?>" <?php if($_SESSION["T03_Sub2_3"] == 1){?> CHECKED <?php } ?> disabled>理
					<input type="checkbox" name="T03_Sub2_4" value="<?php echo $_SESSION["T03_Sub2_4"]?>" <?php if($_SESSION["T03_Sub2_4"] == 1){?> CHECKED <?php } ?> disabled>社
					<input type="checkbox" name="T03_Sub2_5" value="<?php echo $_SESSION["T03_Sub2_5"]?>" <?php if($_SESSION["T03_Sub2_5"] == 1){?> CHECKED <?php } ?> disabled>英
					<input type="checkbox" name="T03_Sub2_6" value="<?php echo $_SESSION["T03_Sub2_6"]?>" <?php if($_SESSION["T03_Sub2_6"] == 1){?> CHECKED <?php } ?> disabled>高校受験
				</td>
			</tr>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0"rowspan="4">高校生</td>
				<td align="left" colspan="4" height="30">
					<input type="checkbox" name="T03_Sub3_1" value="<?php echo $_SESSION["T03_Sub3_1"]?>" <?php if($_SESSION["T03_Sub3_1"] == 1){?> CHECKED <?php } ?> disabled>現文
					<input type="checkbox" name="T03_Sub3_2" value="<?php echo $_SESSION["T03_Sub3_2"]?>" <?php if($_SESSION["T03_Sub3_2"] == 1){?> CHECKED <?php } ?> disabled>古文
					<input type="checkbox" name="T03_Sub3_3" value="<?php echo $_SESSION["T03_Sub3_3"]?>" <?php if($_SESSION["T03_Sub3_3"] == 1){?> CHECKED <?php } ?> disabled>漢文
					<input type="checkbox" name="T03_Sub3_4" value="<?php echo $_SESSION["T03_Sub3_4"]?>" <?php if($_SESSION["T03_Sub3_4"] == 1){?> CHECKED <?php } ?> disabled>小論文
					<input type="checkbox" name="T03_Sub3_5" value="<?php echo $_SESSION["T03_Sub3_5"]?>" <?php if($_SESSION["T03_Sub3_5"] == 1){?> CHECKED <?php } ?> disabled>英語
					<input type="checkbox" name="T03_Sub3_6" value="<?php echo $_SESSION["T03_Sub3_6"]?>" <?php if($_SESSION["T03_Sub3_6"] == 1){?> CHECKED <?php } ?> disabled>大学受験

				</td>
			</tr>
			<tr>
				<td align="left" colspan="4" height="30">
					<input type="checkbox" name="T03_Sub3_7" value="<?php echo $_SESSION["T03_Sub3_7"]?>" <?php if($_SESSION["T03_Sub3_7"] == 1){?> CHECKED <?php } ?> disabled>数Ⅰ
					<input type="checkbox" name="T03_Sub3_8" value="<?php echo $_SESSION["T03_Sub3_8"]?>" <?php if($_SESSION["T03_Sub3_8"] == 1){?> CHECKED <?php } ?> disabled>数Ａ
					<input type="checkbox" name="T03_Sub3_9" value="<?php echo $_SESSION["T03_Sub3_9"]?>" <?php if($_SESSION["T03_Sub3_9"] == 1){?> CHECKED <?php } ?> disabled>数Ⅱ
					<input type="checkbox" name="T03_Sub3_10" value="<?php echo $_SESSION["T03_Sub3_10"]?>" <?php if($_SESSION["T03_Sub3_10"] == 1){?> CHECKED <?php } ?> disabled>数Ｂ
					<input type="checkbox" name="T03_Sub3_11" value="<?php echo $_SESSION["T03_Sub3_11"]?>" <?php if($_SESSION["T03_Sub3_11"] == 1){?> CHECKED <?php } ?> disabled>数Ⅲ
					<input type="checkbox" name="T03_Sub3_12" value="<?php echo $_SESSION["T03_Sub3_12"]?>" <?php if($_SESSION["T03_Sub3_12"] == 1){?> CHECKED <?php } ?> disabled>数Ｃ
				</td>
			</tr>
			<tr>
				<td align="left" colspan="4" height="30">
					<input type="checkbox" name="T03_Sub3_13" value="<?php echo $_SESSION["T03_Sub3_13"]?>" <?php if($_SESSION["T03_Sub3_13"] == 1){?> CHECKED <?php } ?> disabled>物理
					<input type="checkbox" name="T03_Sub3_14" value="<?php echo $_SESSION["T03_Sub3_14"]?>" <?php if($_SESSION["T03_Sub3_14"] == 1){?> CHECKED <?php } ?> disabled>化学
					<input type="checkbox" name="T03_Sub3_15" value="<?php echo $_SESSION["T03_Sub3_15"]?>" <?php if($_SESSION["T03_Sub3_15"] == 1){?> CHECKED <?php } ?> disabled>生物
					<input type="checkbox" name="T03_Sub3_16" value="<?php echo $_SESSION["T03_Sub3_16"]?>" <?php if($_SESSION["T03_Sub3_16"] == 1){?> CHECKED <?php } ?> disabled>地学
				</td>
			</tr>
			<tr>
				<td align="left" colspan="4" height="30">
					<input type="checkbox" name="T03_Sub3_17" value="<?php echo $_SESSION["T03_Sub3_17"]?>" <?php if($_SESSION["T03_Sub3_17"] == 1){?> CHECKED <?php } ?> disabled>日本史
					<input type="checkbox" name="T03_Sub3_18" value="<?php echo $_SESSION["T03_Sub3_18"]?>" <?php if($_SESSION["T03_Sub3_18"] == 1){?> CHECKED <?php } ?> disabled>世界史
					<input type="checkbox" name="T03_Sub3_19" value="<?php echo $_SESSION["T03_Sub3_19"]?>" <?php if($_SESSION["T03_Sub3_19"] == 1){?> CHECKED <?php } ?> disabled>政経
					<input type="checkbox" name="T03_Sub3_20" value="<?php echo $_SESSION["T03_Sub3_20"]?>" <?php if($_SESSION["T03_Sub3_20"] == 1){?> CHECKED <?php } ?> disabled>地理
				</td>
			</tr>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">その他</td>
				<td align="left" colspan="4">
					<?php echo $_SESSION["T03_Sub4_1"]?>
				</td>
			</tr>
		<?php }else{ ?>
			<tr>
				<td align="left" colspan="6"><input type="button" id="kyoka2" name="kyoka2" onClick="sbmfnc(this,'')" style="cursor: pointer" value="+教科" /></td>
			</tr>
		<?php } ?>

		<?php if($_SESSION["ShikakuFlg"] == "0"){ ?>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="3"><input type="button" id="shikaku" name="shikaku" onClick="sbmfnc(this,'')" style="cursor: pointer" value="-資格" /></td>
				<td align="left" colspan="5" height="30"><?php echo $_SESSION["T03_License1"]?>　</td>
			</tr>
			<tr>
				<td align="left" colspan="5" height="30"><?php echo $_SESSION["T03_License2"]?>　</td>
			</tr>
			<tr>
				<td align="left" colspan="5" height="30"><?php echo $_SESSION["T03_License3"]?>　</td>
			</tr>
		<?php }else{ ?>
			<tr>
				<td align="left" colspan="6"><input type="button" id="shikaku2" name="shikaku2" onClick="sbmfnc(this,'')" style="cursor: pointer" value="+資格" /></td>
			</tr>
		<?php } ?>

		<?php if($_SESSION["KeikenFlg"] == "0"){ ?>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="2"><input type="button" id="keiken" name="keiken" onClick="sbmfnc(this,'')" style="cursor: pointer" value="-経験" /></td>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">出身中学</td>
				<td align="left" colspan="2"><?php echo $_SESSION["T03_Gra_Hight"]?>　</td>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">教職課程</td>
				<td align="left"><?php if($_SESSION["T03_Exp_Kyou"] == "1"){ ?>有<?php }elseif($_SESSION["T03_Exp_Kyou"] == "0"){ ?>無<?php } ?></td>
			</tr>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">出身高校</td>
				<td align="left" colspan="2"><?php echo $_SESSION["T03_Gra_Junior"]?></td>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">中学受験</td>
				<td align="left"><?php if($_SESSION["T03_Exp_Juken"] == "1"){ ?>有<?php }elseif($_SESSION["T03_Exp_Juken"] == "0"){ ?>無<?php } ?></td>
			</tr>
		<?php }else{ ?>
			<tr>
				<td align="left" colspan="6"><input type="button" id="keiken2" name="keiken2" onClick="sbmfnc(this,'')" style="cursor: pointer" value="+経験" /></td>
			</tr>
		<?php } ?>

		<?php if($_SESSION["SonotaFlg"] == "0"){ ?>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="5"><input type="button" id="sonota" name="sonota" onClick="sbmfnc(this,'')" style="cursor: pointer" value="-その他" /></td>
				<td align="left" colspan="5" height="30"><?php echo $_SESSION["T03_Other1"]?>　</td>
			</tr>
			<tr>
				<td align="left" colspan="5" height="30"><?php echo $_SESSION["T03_Other2"]?>　</td>
			</tr>
			<tr>
				<td align="left" colspan="5" height="30"><?php echo $_SESSION["T03_Other3"]?>　</td>
			</tr>
			<tr>
				<td align="left" colspan="5" height="30"><?php echo $_SESSION["T03_Other4"]?>　</td>
			</tr>
			<tr>
				<td align="left" colspan="5" height="30">【紹介文】<?php echo $_SESSION["T03_Other5"]?>　</td>
			</tr>
		<?php }else{ ?>
			<tr>
				<td align="left" colspan="6"><input type="button" id="sonota2" name="sonota2" onClick="sbmfnc(this,'')" style="cursor: pointer" value="+その他" /></td>
			</tr>
		<?php } ?>
		<?php if($_SESSION["SonotaFlg2"] == "0"){ ?>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="4"><input type="button" id="sonota_2" name="sonota_2" onClick="sbmfnc(this,'')" style="cursor: pointer" value="-その他2" /></td>
				<td align="left" colspan="5" height="30"><?php echo $_SESSION["T03_Notice2"]?>　</td>
			</tr>
			<tr>
				<td align="left" colspan="5" height="30"><?php echo $_SESSION["T03_Notice3"]?>　</td>
			</tr>
			<tr>
				<td align="left" colspan="5" height="30"><?php echo $_SESSION["T03_Notice4"]?>　</td>
			</tr>
			<tr>
				<td align="left" colspan="5" height="30"><?php echo $_SESSION["T03_Notice5"]?>　</td>
			</tr>
		<?php }else{ ?>
			<tr>
				<td align="left" colspan="6"><input type="button" id="sonota2_2" name="sonota2_2" onClick="sbmfnc(this,'')" style="cursor: pointer" value="+その他2" /></td>
			</tr>
		<?php } ?>
		<?php if($_SESSION["KozaFlg"] == "0"){ ?>
			<?php $Row = $_SESSION["T03_Koza_DataCount"]*3;?>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0" rowspan="<?php echo $Row?>"><input type="button" id="Koza" name="Koza" onClick="sbmfnc(this,'')" style="cursor: pointer" value="-口座情報" /></td>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">記号番号</td>
				<td align="left" colspan="2"><?php echo $_SESSION["T03_Koza_Kigou0"]?>-<?php echo $_SESSION["T03_Koza_Bango0"]?></td>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">開始日</td>
				<td align="left"><?php if(is_null($_SESSION["T03_Koza_Start0"]) || ($_SESSION["T03_Koza_Start0"] == "")){ }else{ echo date('Y年n月j日', strtotime($_SESSION["T03_Koza_Start0"])); } ?>　</td>
			</tr>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">名義人</td>
				<td align="left" colspan="2"><?php echo $_SESSION["T03_Koza_Meigi0"]?>　(<?php echo $_SESSION["T03_Koza_MeigiKana0"]?>)</td>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">終了日</td>
				<td align="left"><?php if(is_null($_SESSION["T03_Koza_End0"]) || ($_SESSION["T03_Koza_End0"] == "")){ }else{ echo date('Y年n月j日', strtotime($_SESSION["T03_Koza_End0"])); } ?>　</td>
			</tr>
			<tr>
				<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">備考</td>
				<td align="left" colspan="4"><?php echo $_SESSION["T03_Koza_Biko0"]?></td>
			</tr>
			<?php for($m=1; $m<$_SESSION["T03_Koza_DataCount"]; $m++){ ?>
				<tr>
					<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">記号番号</td>
					<td align="left" colspan="2"><?php echo $_SESSION["T03_Koza_Kigou" .$m]?>-<?php echo $_SESSION["T03_Koza_Bango" .$m]?></td>
					<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">開始日</td>
					<td align="left"><?php if(is_null($_SESSION["T03_Koza_Start" .$m])){ }else{ echo date('Y年n月j日', strtotime($_SESSION["T03_Koza_Start" .$m])); } ?>　</td>
				</tr>
				<tr>
					<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">名義人</td>
					<td align="left" colspan="2"><?php echo $_SESSION["T03_Koza_Meigi" .$m]?>　(<?php echo $_SESSION["T03_Koza_MeigiKana" .$m]?>)</td>
					<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">終了日</td>
					<td align="left"><?php if(is_null($_SESSION["T03_Koza_End" .$m])){ }else{ echo date('Y年n月j日', strtotime($_SESSION["T03_Koza_End" .$m])); } ?>　</td>
				</tr>
				<tr>
					<td id="midashi_Kanri" align="center" bgcolor="#c0c0c0">名義人</td>
					<td align="left" colspan="4"><?php echo $_SESSION["T03_Koza_Biko" .$m]?></td>
				</tr>
			<?php } ?>
		<?php }else{ ?>
			<tr>
				<td align="left" colspan="6"><input type="button" id="Koza2" name="Koza2" onClick="sbmfnc(this,'')" style="cursor: pointer" value="+口座情報" /></td>
			</tr>
		<?php } ?>
	</table>
</div>
</form>
</body>
</CENTER>
</html>