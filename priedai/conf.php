<?php
if (basename($_SERVER['PHP_SELF']) == 'conf.php') { die("Tiesioginis kreipimàsis á failà draudþiamas"); }
define('SETUP',true);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'On');	//Klaidu pranesimai On/Off
date_default_timezone_set('Europe/Vilnius');//Lithuanian time zone
$host = "localhost";	//mysql sererio adresas
$user = "root";	//db vartotojas
$pass = "";	//slaptazodis
$db = "mightmedia";	//Duomenu baze
define("LENTELES_PRIESAGA", "mm_");	//Lenteliu pavadinimu priesaga
$slaptas = "e6b48c64ece6c171e24ed0a50bde9352";	//Sausainiams ir kitai informacijai

//Admin paneles vartotojas ir slaptazodis
$admin_name="Admin";	//useris
$admin_email="aivaras.cenkus@gmail.com";	//e-pastas

//Versiju tikrinimas
$update_url = "http://www.assembla.com/code/mightmedia/subversion/node/blob/naujienos.json?jsoncallback=?";

// DB Prisijungimas
$prisijungimas_prie_mysql = mysql_connect($host, $user, $pass) or die("<center><h1>Klaida 1</h1><br/>Svetainë laikinai neveikia. <h4>Praðome uþsukti vëliau</h4></center>");
mysql_select_db($db,$prisijungimas_prie_mysql) or die("<center><h1>Klaida 2</h1><br/>Svetainë neidiegta. <h4>Praðome uþsukti vëliau</h4></center>");
mysql_query("SET NAMES 'utf8'",$prisijungimas_prie_mysql);
$sql = mysql_query("SELECT * FROM `".LENTELES_PRIESAGA."nustatymai`",$prisijungimas_prie_mysql);
$conf = array();
if(mysql_num_rows($sql) > 1) while($row = mysql_fetch_assoc($sql)) $conf[$row['key']] = $row['val'];
unset($row,$sql,$user,$host,$pass,$db);
//kalba
$lang = array();
if (isset($conf['kalba'])) {
    require_once (realpath(dirname(__file__)) . '/../lang/' . (empty($_SESSION['lang'])?basename($conf['kalba'],'.php'):$_SESSION['lang']). '.php');
} else {
    require_once (realpath(dirname(__file__)) . '/../lang/lt.php');
}
//Jeigu nepavyko nuskaityti nustatymø
if (!isset($conf) || empty($conf)) die("<center><h1>Klaida 3</h1><br/>Svetainë laikinai neveikia. <h4>Praðome uþsukti vëliau</h4></center>");

// Inkludinam tai ko mums reikia
require_once(realpath(dirname(__file__))."/funkcijos.php");
?>