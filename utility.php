<?php
//---------------------------------------------
//ログアウト処理
//---------------------------------------------
function LogoutShori()
{
	// セッション変数を全て解除する

	$_SESSION = array();
 
	// セッションを切断するにはセッションクッキーも削除する。
	// Note: セッション情報だけでなくセッションを破壊する。
	if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
		$params["path"], $params["domain"],
		$params["secure"], $params["httponly"]);
	}

	header("Location:index.php");
}
//---------------------------------------------
//ログアウト処理
//---------------------------------------------
function LogoutShori2()
{
	header("Location:index.php");
}

//---------------------------------------------
//戻る処理
//---------------------------------------------
function ModoruShori($arg1)
{
print ($arg1);
	header("Location:" .$arg1. ".php?MODE=Modoru");
}


//---------------------------------------------
// Nullをゼロに置き換える
//---------------------------------------------
function NullToZero($arg1)
{
	if (is_null($arg1)){
		return 0;
	} else {
		return $arg1;
	}
}

//---------------------------------------------
// Nullをゼロに置き換える
//---------------------------------------------
function NullToSpace($arg1)
{
	if (is_null($arg1)){
		return "　";
	} else {
		return $arg1;
	}
}

//---------------------------------------------
//　教師の宛名情報を取得する
//　$arg1:教師ID
//　戻り値
//　$db_Name1, $db_Name2
//---------------------------------------------
function GetTAtena($arg1)
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
	$userid = $mysqli->real_escape_string($arg1);

	// クエリの実行
	$query = "SELECT * FROM T_AtenaInfo WHERE  TeacherID = '" . $userid . "'";
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
//---------------------------------------------
//　教師の宛名情報を取得する
//　$arg1:教師ID
//　戻り値
//　$db_Name1, $db_Name2
//---------------------------------------------
function GetTeacherName($arg1)
{
$db_Name1="";
$db_Name2="";

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
	$userid = $mysqli->real_escape_string($arg1);

	// クエリの実行
	$query = "SELECT * FROM T_AtenaInfo WHERE  TeacherID = '" . $userid . "'";
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

	return $db_Name1;

//print ("GetTAtena-End");


}
//---------------------------------------------
//　教師の宛名情報を取得する
//　$arg1:教師ID
//　戻り値
//　$db_Name1, $db_Name2
//---------------------------------------------
function GetTeacherName2($arg1)
{
$db_Name1="";
$db_Name2="";

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
	$userid = $mysqli->real_escape_string($arg1);

	// クエリの実行
	$query = "SELECT * FROM T_AtenaInfo WHERE  TeacherID = '" . $userid . "'";
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

	return $db_Name2;

//print ("GetTAtena-End");


}
//-----------------------------------------------------------
//	生徒名取得
//-----------------------------------------------------------
Function GetStudentName($StdID){

$db_Name1="";
		// mysqlへの接続
		$mysqli = new mysqli(HOST, USER, PASS);
		if ($mysqli->connect_errno) {
			print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
			exit();
		   		}

		// データベースの選択
		$mysqli->select_db(DBNAME);
		$mysqli->set_charset("utf8");

			//------生徒名取得------
			$query2 = "SELECT * FROM S_AtenaInfo WHERE  StudentID = '" . $StdID . "'";
			$query2 = $query2 . " ORDER BY Seq Desc";
			
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

	 	// データベースの切断
		$mysqli->close();

			return $db_Name1;


}
//-----------------------------------------------------------
//	生徒名取得
//-----------------------------------------------------------
Function GetStudentName2($StdID){

$db_Name1="";
		// mysqlへの接続
		$mysqli = new mysqli(HOST, USER, PASS);
		if ($mysqli->connect_errno) {
			print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
			exit();
		   		}

		// データベースの選択
		$mysqli->select_db(DBNAME);
		$mysqli->set_charset("utf8");

			//------生徒名取得------
			$query2 = "SELECT * FROM S_AtenaInfo WHERE  StudentID = '" . $StdID . "'";
			$query2 = $query2 . " ORDER BY Seq Desc";
			
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

	 	// データベースの切断
		$mysqli->close();

			return $db_Name2;


}

//---------------------------------------------
//　お知らせ情報を取得する
//　引数：なし
//　戻り値
//　$db_Naiyo1
//　$db_Naiyo2
//　$db_Naiyo3
//　$db_Naiyo4
//　$db_Naiyo5
//---------------------------------------------
function GetInfo()
{

//print ("GetInfo<br>");
$db_Naiyo1 = "";
$db_Naiyo2 = "";
$db_Naiyo3 = "";
$db_Naiyo4 = "";
$db_Naiyo5 = "";

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
	$dt = new DateTime();
	$dt->setTimeZone(new DateTimeZone('Asia/Tokyo'));
	$Today = $dt->format('Y/m/d');

	// クエリの実行
	$query = "SELECT * FROM K_Info Order by TypeName";
	$result = $mysqli->query($query);

	if (!$result) {
		print('クエリーが失敗しました。' . $mysqli->error);
		$mysqli->close();
		exit();
	}

	while ($row = $result->fetch_assoc()) {
		$db_Naiyo1 = $row['Naiyo1'];
		$db_Naiyo2 = $row['Naiyo2'];
		$db_Naiyo3 = $row['Naiyo3'];
		$db_Naiyo4 = $row['Naiyo4'];
		$db_Naiyo5 = $row['Naiyo5'];
	}

 	// データベースの切断
	$mysqli->close();
//print ("データベースの切断<br>");

	return array ($db_Naiyo1, $db_Naiyo2, $db_Naiyo3, $db_Naiyo4, $db_Naiyo5);
}
//-----------------------------------------------------------
//	コード取得
//-----------------------------------------------------------
Function GetCodeData($pCode,$pWhere,$pOrder,$pKubun){


	// mysqlへの接続
	$mysqli = new mysqli(HOST, USER, PASS);
	if ($mysqli->connect_errno) {
		print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
		exit();
	}

	// データベースの選択
	$mysqli->select_db(DBNAME);
	$mysqli->set_charset("utf8");

	// クエリの実行
	$query = "SELECT A.CodeNo As No,A.CodeName,B.* FROM K_CodeName as A inner join K_Code as B";
	$query = $query . " on A.CodeNo = B.CodeNo";
	$query = $query . " where A.CodeName='" . $pCode . "'";
	if($pWhere!=""){
		$query = $query . " AND " . $pWhere;
	}
	if($pOrder!=""){
		$query = $query . " Order by " . $pOrder;
	}else{
		$query = $query . " Order by SortNo Asc";
	}
//print($query);
	$result = $mysqli->query($query);

	if (!$result) {
		print('クエリーが失敗しました。' . $mysqli->error);
		$mysqli->close();
		exit();
	}

	$db_data = array();
	$data = array();
	$i = 0;
	$data["CountFlg"]="0";

	while($arr_item = $result->fetch_assoc()){

		//レコード内の各フィールド名と値を順次参照
		foreach($arr_item as $key => $value){
			//フィールド名と値を表示
			$db_data[$i][$key] = $value;
		}
		
		$CodeNo=$db_data[$i]['No'];
		$data[$CodeNo . "_CodeNo_" .$i]=$db_data[$i]['No'];
		$data[$CodeNo . "_CodeName_" .$i]=$db_data[$i]['CodeName'];
		$data[$CodeNo . "_Eda_" .$i]=$db_data[$i]['Eda'];
		$data[$CodeNo . "_CodeName1_" .$i]=$db_data[$i]['CodeName1'];
		$data[$CodeNo . "_CodeName2_" .$i]=$db_data[$i]['CodeName2'];
		$data[$CodeNo . "_SortNo_" .$i]=$db_data[$i]['SortNo'];

		$i++;
	}
	if($i==0){
		$data["CountFlg"]="1";
	}else{
		$data[$CodeNo . "DataCount"] = $i;
	}
 	// データベースの切断
	$mysqli->close();

	return $data;

}
//-----------------------------------------------------------
//	担当者取得
//-----------------------------------------------------------
Function GetKanriTnato($pWhere,$pOrder){

	// mysqlへの接続
	$mysqli = new mysqli(HOST, USER, PASS);
	if ($mysqli->connect_errno) {
		print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
		exit();
	}

	// データベースの選択
	$mysqli->select_db(DBNAME);
	$mysqli->set_charset("utf8");

	// クエリの実行
	$query = "SELECT A.TeacherID AS TeacherID,B.Name1 as Name1,B.Name2 as Name2,A.Shikaku as Shikaku FROM K_LoginInfo as A inner join T_AtenaInfo as B";
	$query = $query . " on A.TeacherID = B.TeacherID";
	$query = $query . " where A.Shikaku='1'";
	if($pWhere!=""){
		$query = $query . " AND " . $pWhere;
	}
	if($pOrder!=""){
		$query = $query . " Order by " . $pOrder;
	}

	$result = $mysqli->query($query);

	if (!$result) {
		print('クエリーが失敗しました。' . $mysqli->error);
		$mysqli->close();
		exit();
	}

	$db_data = array();
	$i = 0;
	while($arr_item = $result->fetch_assoc()){

		//レコード内の各フィールド名と値を順次参照
		foreach($arr_item as $key => $value){
			//フィールド名と値を表示
			$db_data[$i][$key] = $value;
		}

		$data["TeacherID_" . $i]=$db_data[$i]['TeacherID'];
		$data["Name1_" . $i]=$db_data[$i]['Name1'];
		$data["Name2_" . $i]=$db_data[$i]['Name2'];
		$data["Shikaku_" . $i]=$db_data[$i]['Shikaku'];
		
		$i++;
	}

	if($i==0){
		$data["TeacherID_0"]="";
		$data["Name1_0"]="";
		$data["Name2_0"]="";
		$data["Shikaku_0"]="";
	}else{
		$data["TantoDataCount"] = $i;
	}

 	// データベースの切断
	$mysqli->close();

	return $data;

}
//----------------------------------------------------
//曜日の取得
//----------------------------------------------------
function GetYoubi($argY,$argM,$argD){

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
//都道府県の取得
//----------------------------------------------------
function GetTodofuken($pKen,$pCode){

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
	if(pKen != ""){
		$query = $query . " Where Todofuken ='" . $pKen . "'";
	}else if(pCode != ""){
		$query = $query . " Where Code ='" . $pCode . "'";
	}

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

		$Code = $data[$i]['Code'];
		$ChiikiCode = $data[$i]['ChiikiCode'];
		$Todofuken = $data[$i]['Todofuken'];

		$i++;
	}

 	// データベースの切断
	$mysqli->close();

	return array ($Code, $ChiikiCode, $Todofuken);
}
//-----------------------------------------------------------
//	教師IDの範囲を取得する
//-----------------------------------------------------------
function GetIdNoHenshu($P_Name)
{
	switch ($P_Name){
		case 'あ':
			$Def =1000;
			$Na1 =1000;
			$Na2 =1999;
			break;
		case 'い':
			$Def =2000;
			$Na1 =2000;
			$Na2 =2999;
			break;
		case 'う':
			$Def =3000;
			$Na1 =3000;
			$Na2 =3999;
			break;
		case 'え':
			$Def =4000;
			$Na1 =4000;
			$Na2 =4999;
			break;
		case 'お':
			$Def =5000;
			$Na1 =5000;
			$Na2 =5999;
			break;
		case 'か':
		case 'が':
			$Def =6000;
			$Na1 =6000;
			$Na2 =6999;
			break;
		case 'き':
		case 'ぎ':
			$Def =7000;
			$Na1 =7000;
			$Na2 =7999;
			break;
		case 'く':
		case 'ぐ':
			$Def =8000;
			$Na1 =8000;
			$Na2 =8999;
			break;
		case 'け':
		case 'げ':
			$Def =9000;
			$Na1 =9000;
			$Na2 =9999;
			break;
		case 'こ':
		case 'ご':
			$Def =10000;
			$Na1 =10000;
			$Na2 =10999;
			break;
		case 'さ':
		case 'ざ':
			$Def =11000;
			$Na1 =11000;
			$Na2 =11999;
			break;
		case 'し':
		case 'じ':
			$Def =12000;
			$Na1 =12000;
			$Na2 =12999;
			break;
		case 'す':
		case 'ず':
			$Def =13000;
			$Na1 =13000;
			$Na2 =13999;
			break;
		case 'せ':
		case 'ぜ':
			$Def =14000;
			$Na1 =14000;
			$Na2 =14999;
			break;
		case 'そ':
		case 'ぞ':
			$Def =15000;
			$Na1 =15000;
			$Na2 =15999;
			break;
		case 'た':
		case 'だ':
			$Def =16000;
			$Na1 =16000;
			$Na2 =16999;
			break;
		case 'ち':
		case 'ぢ':
			$Def =17000;
			$Na1 =17000;
			$Na2 =17999;
			break;
		case 'つ':
		case 'づ':
			$Def =18000;
			$Na1 =18000;
			$Na2 =18999;
			break;
		case 'て':
		case 'で':
			$Def =19000;
			$Na1 =19000;
			$Na2 =19999;
			break;
		case 'と':
		case 'ど':
			$Def =20000;
			$Na1 =20000;
			$Na2 =20999;
			break;
		case 'な':
			$Def =21000;
			$Na1 =21000;
			$Na2 =21999;
			break;
		case 'に':
			$Def =22000;
			$Na1 =22000;
			$Na2 =22999;
			break;
		case 'ぬ':
			$Def =23000;
			$Na1 =23000;
			$Na2 =23999;
			break;
		case 'ね':
			$Def =24000;
			$Na1 =24000;
			$Na2 =24999;
			break;
		case 'の':
			$Def =25000;
			$Na1 =25000;
			$Na2 =25999;
			break;
		case 'は':
		case 'ば':
		case 'ぱ':
			$Def =26000;
			$Na1 =26000;
			$Na2 =26999;
			break;
		case 'ひ':
		case 'び':
		case 'ぴ':
			$Def =27000;
			$Na1 =27000;
			$Na2 =27999;
			break;
		case 'ふ':
		case 'ぶ':
		case 'ぷ':
			$Def =28000;
			$Na1 =28000;
			$Na2 =28999;
			break;
		case 'へ':
		case 'べ':
		case 'ぺ':
			$Def =29000;
			$Na1 =29000;
			$Na2 =29000;
			break;
		case 'ほ':
		case 'ぼ':
		case 'ぽ':
			$Def =30000;
			$Na1 =30000;
			$Na2 =30999;
			break;
		case 'ま':
			$Def =31000;
			$Na1 =31000;
			$Na2 =31999;
			break;
		case 'み':
			$Def =32000;
			$Na1 =32000;
			$Na2 =32999;
			break;
		case 'む':
			$Def =33000;
			$Na1 =33000;
			$Na2 =33999;
			break;
		case 'め':
			$Def =34000;
			$Na1 =34000;
			$Na2 =34999;
			break;
		case 'も':
			$Def =35000;
			$Na1 =35000;
			$Na2 =35999;
			break;
		case 'や':
			$Def =36000;
			$Na1 =36000;
			$Na2 =36999;
			break;
		case 'ゆ':
			$Def =37000;
			$Na1 =37000;
			$Na2 =37999;
			break;
		case 'よ':
			$Def =38000;
			$Na1 =38000;
			$Na2 =38999;
			break;
		case 'わ':
			$Def =39000;
			$Na1 =39000;
			$Na2 =39999;
			break;
		case 'ら':
			$Def =40000;
			$Na1 =40000;
			$Na2 =40999;
			break;
		case 'り':
			$Def =41000;
			$Na1 =41000;
			$Na2 =41999;
			break;
		case 'る':
			$Def =42000;
			$Na1 =42000;
			$Na2 =42999;
			break;
		case 'れ':
			$Def =43000;
			$Na1 =43000;
			$Na2 =43999;
			break;
		case 'ろ':
			$Def =44000;
			$Na1 =44000;
			$Na2 =44999;
			break;
		default:
			$Def =0;
			$Na1 =0;
			$Na2 =0;
			break;
	}

	return array ($Na1,$Na2,$Def);  
}
//-----------------------------------------------------------
//	生徒IDの範囲を取得する
//-----------------------------------------------------------
function GetIdNoHenshu2($P_Name)
{
	switch ($P_Name){
		case 'あ':
			$Def =1001000;
			$Na1 =1001000;
			$Na2 =1001999;
			break;
		case 'い':
			$Def =1002000;
			$Na1 =1002000;
			$Na2 =1002999;
			break;
		case 'う':
			$Def =1003000;
			$Na1 =1003000;
			$Na2 =1003999;
			break;
		case 'え':
			$Def =1004000;
			$Na1 =1004000;
			$Na2 =1004999;
			break;
		case 'お':
			$Def =1005000;
			$Na1 =1005000;
			$Na2 =1005999;
			break;
		case 'か':
		case 'が':
			$Def =1011000;
			$Na1 =1011000;
			$Na2 =1011999;
			break;
		case 'き':
		case 'ぎ':
			$Def =1012000;
			$Na1 =1012000;
			$Na2 =1012999;
			break;
		case 'く':
		case 'ぐ':
			$Def =1013000;
			$Na1 =1013000;
			$Na2 =1013999;
			break;
		case 'け':
		case 'げ':
			$Def =1014000;
			$Na1 =1014000;
			$Na2 =1014999;
			break;
		case 'こ':
		case 'ご':
			$Def =1015000;
			$Na1 =1015000;
			$Na2 =1015999;
			break;
		case 'さ':
		case 'ざ':
			$Def =1021000;
			$Na1 =1021000;
			$Na2 =1021999;
			break;
		case 'し':
		case 'じ':
			$Def =1022000;
			$Na1 =1022000;
			$Na2 =1022999;
			break;
		case 'す':
		case 'ず':
			$Def =1023000;
			$Na1 =1023000;
			$Na2 =1023999;
			break;
		case 'せ':
		case 'ぜ':
			$Def =1024000;
			$Na1 =1024000;
			$Na2 =1024999;
			break;
		case 'そ':
		case 'ぞ':
			$Def =1025000;
			$Na1 =1025000;
			$Na2 =1025999;
			break;
		case 'た':
		case 'だ':
			$Def =1031000;
			$Na1 =1031000;
			$Na2 =1031999;
			break;
		case 'ち':
		case 'ぢ':
			$Def =1032000;
			$Na1 =1032000;
			$Na2 =1032999;
			break;
		case 'つ':
		case 'づ':
			$Def =1033000;
			$Na1 =1033000;
			$Na2 =1033999;
			break;
		case 'て':
		case 'で':
			$Def =1034000;
			$Na1 =1034000;
			$Na2 =1034999;
			break;
		case 'と':
		case 'ど':
			$Def =1035000;
			$Na1 =1035000;
			$Na2 =1035999;
			break;
		case 'な':
			$Def =1041000;
			$Na1 =1041000;
			$Na2 =1041999;
			break;
		case 'に':
			$Def =1042000;
			$Na1 =1042000;
			$Na2 =1042999;
			break;
		case 'ぬ':
			$Def =1043000;
			$Na1 =1043000;
			$Na2 =1043999;
			break;
		case 'ね':
			$Def =1044000;
			$Na1 =1044000;
			$Na2 =1044999;
			break;
		case 'の':
			$Def =1045000;
			$Na1 =1045000;
			$Na2 =1045999;
			break;
		case 'は':
		case 'ば':
		case 'ぱ':
			$Def =1051000;
			$Na1 =1051000;
			$Na2 =1051999;
			break;
		case 'ひ':
		case 'び':
		case 'ぴ':
			$Def =1052000;
			$Na1 =1052000;
			$Na2 =1052999;
			break;
		case 'ふ':
		case 'ぶ':
		case 'ぷ':
			$Def =1053000;
			$Na1 =1053000;
			$Na2 =1053999;
			break;
		case 'へ':
		case 'べ':
		case 'ぺ':
			$Def =1054000;
			$Na1 =1054000;
			$Na2 =1054999;
			break;
		case 'ほ':
		case 'ぼ':
		case 'ぽ':
			$Def =1055000;
			$Na1 =1055000;
			$Na2 =1055999;
			break;
		case 'ま':
			$Def =1061000;
			$Na1 =1061000;
			$Na2 =1061999;
			break;
		case 'み':
			$Def =1062000;
			$Na1 =1062000;
			$Na2 =1062999;
			break;
		case 'む':
			$Def =1063000;
			$Na1 =1063000;
			$Na2 =1063999;
			break;
		case 'め':
			$Def =1064000;
			$Na1 =1064000;
			$Na2 =1064999;
			break;
		case 'も':
			$Def =1065000;
			$Na1 =1065000;
			$Na2 =1065999;
			break;
		case 'や':
			$Def =1071000;
			$Na1 =1071000;
			$Na2 =1071999;
			break;
		case 'ゆ':
			$Def =1072000;
			$Na1 =1072000;
			$Na2 =1072999;
			break;
		case 'よ':
			$Def =1073000;
			$Na1 =1073000;
			$Na2 =1073999;
			break;
		case 'ら':
			$Def =1081000;
			$Na1 =1081000;
			$Na2 =1081999;
			break;
		case 'り':
			$Def =1082000;
			$Na1 =1082000;
			$Na2 =1082999;
			break;
		case 'る':
			$Def =1083000;
			$Na1 =1083000;
			$Na2 =1083999;
			break;
		case 'れ':
			$Def =1084000;
			$Na1 =1084000;
			$Na2 =1084999;
			break;
		case 'ろ':
			$Def =1085000;
			$Na1 =1085000;
			$Na2 =1085999;
			break;
		case 'わ':
			$Def =1091000;
			$Na1 =1091000;
			$Na2 =1091999;
			break;
		default:
			$Def =0;
			$Na1 =0;
			$Na2 =0;
			break;
	}

	return array ($Na1,$Na2,$Def);  
}
//------------------------------------
//  ファイルダウンロード
//------------------------------------
function fileDownload($TID){
        //履歴書
        $dir = FILE_DIR . "/" . $TID . "/*";
        $m=0;
        foreach (glob($dir) as $filename) {
            $_SESSION["FileName_" .$m] = $filename;
            $m++;
        }
        $_SESSION["FileNameCnt"] = $m;
        $_SESSION["FileDir"] = FILE_DIR . "/" . $TID . "/";
        
        //顔写真
        $dir2 = IMG_DIR . "/" . $TID . "/*";
        $n=0;

        $_SESSION["ImageNameDisp_0"] = "";
        foreach (glob($dir2) as $filename) {
            $_SESSION["ImageName_" .$n] = $filename;
            $_SESSION["ImageNameDisp_" .$n] = substr($filename, 2);
            $n++;
        }
        $_SESSION["ImageNameCnt"] = $n;
        $_SESSION["ImageDir"] = IMG_DIR . "/" . $TID . "/";
        
        //その他書類
        $dir3 = FILE_DIR2 . "/" . $TID . "/*";
        $m=0;
        foreach (glob($dir3) as $filename) {
            $_SESSION["FileName_sonota_" .$m] = $filename;
            $m++;
        }
        $_SESSION["FileNameCnt_sonota"] = $m;
        $_SESSION["FileDir_sonota"] = FILE_DIR2 . "/" . $TID . "/";
        
        //その他画像
        $dir4 = IMG_DIR2 . "/" . $TID . "/*";
        $n=0;
        foreach (glob($dir4) as $filename) {
            $_SESSION["ImageName_sonota_" .$n] = $filename;
            $n++;
        }
        $_SESSION["ImageNameCnt_sonota"] = $n;
        $_SESSION["ImageDir_sonota"] = IMG_DIR2 . "/" . $TID . "/";
}
function fileDownload_ichiran($Cnt,$TID){
    for ($i = 0; $i< $Cnt; $i++) {
        $dir = FILE_DIR . "/" . $_SESSION[$TID . $i] . "/*";
        $m=0;
        foreach (glob($dir) as $filename) {
            $_SESSION["FileName_" . $i . "_" .$m] = $filename;
            $m++;
        }
        $_SESSION["FileNameCnt_" . $i] = $m;
        $_SESSION["FileDir_" . $i] = FILE_DIR . "/" . $_SESSION[$TID . $i] . "/";

        $dir2 = IMG_DIR . "/" . $_SESSION[$TID . $i] . "/*";
        $n=0;
        foreach (glob($dir2) as $filename) {
            $_SESSION["ImageName_" . $i . "_" .$n] = $filename;
            $n++;
        }
        $_SESSION["ImageNameCnt_" . $i] = $n;
        $_SESSION["ImageDir_" . $i] = IMG_DIR . "/" . $_SESSION[$TID . $i] . "/";
        
        $dir3 = FILE_DIR2 . "/" . $_SESSION[$TID . $i] . "/*";
        $m=0;
        foreach (glob($dir3) as $filename) {
            $_SESSION["FileName_sonota_" . $i . "_" .$m] = $filename;
            $m++;
        }
        $_SESSION["FileNameCnt_sonota_" . $i] = $m;
        $_SESSION["FileDir_sonota_" . $i] = FILE_DIR2 . "/" . $_SESSION[$TID . $i] . "/";

        $dir4 = IMG_DIR2 . "/" . $_SESSION[$TID . $i] . "/*";
        $n=0;
        foreach (glob($dir4) as $filename) {
            $_SESSION["ImageName_sonota_" . $i . "_" .$n] = $filename;
            $n++;
        }
        $_SESSION["ImageNameCnt_sonota_" . $i] = $n;
        $_SESSION["ImageDir_sonota_" . $i] = IMG_DIR2 . "/" . $_SESSION[$TID . $i] . "/";
        
    }
}
?>