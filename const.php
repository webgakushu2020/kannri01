<?php
//サーバ情報
define("HOST", "localhost");
define("USER", "webgaku");
define("PASS", "webgaku");
define("DBNAME", "webgaku_system01");
define("DBNAME2", "webgaku_system01");

//ディレクトリ情報
define( "FILE_DIR", "./upfiles");
define( "IMG_DIR", "./upimages");
define( "FILE_DIR2", "./upfiles_sonota");
define( "IMG_DIR2", "./upimages_sonota");

//
define("CON_HOUR_START", 8);
define("CON_HOUR_END", 23);
define("CON_HOUR_UNIT", 15);
define("CON_JISSEKI_HOUR_START", 0);
define("CON_JISSEKI_HOUR_END", 20);

define("HEADER_COLOR", "#00ffff");
define("TEACHR_COLOR", "#00ffff");
define("STUDENT_COLOR", "#FFFF77");
define("KITEI_COLOR", "#FFAD90");
define("JISSEKI_COLOR", "#93FFAB");

//教師状態区分
define("T_JYOTAI_TOROKU", "01");
define("T_JYOTAI_TOROKUKAKUNIN", "02");
define("T_JYOTAI_MENSETU", "03");
define("T_JYOTAI_SENTEI_SHOKAI", "04");
define("T_JYOTAI_SENTEI_HEIJI", "05");
define("T_JYOTAI_SENTEI_TAIKEN", "06");
define("T_JYOTAI_KEKKA_KEIYAKU", "07");
define("T_JYOTAI_KEKKA_FUSEIRITU_KATEI", "08");
define("T_JYOTAI_KEKKA_FUSEIRITU_KYOSHI", "09");
define("T_JYOTAI_KEIYAKU_SYURYO_KATEI", "10");
define("T_JYOTAI_KEIYAKU_SYURYO_KYOSHI", "11");
define("T_JYOTAI_SOUDAN_KATEI", "12");
define("T_JYOTAI_SOUDAN_KYOSHI", "13");
define("T_JYOTAI_KEIYAKUHENKOU", "14");

//生徒状態区分
define("S_JYOTAI_TOROKU", "01");
define("S_JYOTAI_TOROKUKAKUNIN", "02");
define("S_JYOTAI_MENSETU", "03");
define("S_JYOTAI_SENTEI_SHOKAI", "04");
define("S_JYOTAI_SENTEI_HEIJI", "05");
define("S_JYOTAI_SENTEI_TAIKEN", "06");
define("S_JYOTAI_KEKKA_KEIYAKU", "07");
define("S_JYOTAI_KEKKA_FUSEIRITU_KATEI", "08");
define("S_JYOTAI_KEKKA_FUSEIRITU_KYOSHI", "09");
define("S_JYOTAI_KEIYAKU_SYURYO_KATEI", "10");
define("S_JYOTAI_KEIYAKU_SYURYO_KYOSHI", "11");
define("S_JYOTAI_SOUDAN_KATEI", "12");
define("S_JYOTAI_SOUDAN_KYOSHI", "13");
define("S_JYOTAI_KEIYAKUHENKOU", "14");

?>