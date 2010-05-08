<?php
if (basename($_SERVER['PHP_SELF']) == 'conf.php') { die("Tiesioginis kreipimąsis į failą draudžiamas"); }
define('SETUP',true);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'On');	//Klaidu pranesimai On/Off
$host = "localhost";	//mysql sererio adresas
$user = "root";	//db vartotojas
$pass = "root";	//slaptazodis
$db = "mightmedia";	//Duomenu baze
define("LENTELES_PRIESAGA", "jz_");	//Lenteliu pavadinimu priesaga
$slaptas = "e639911efb7bbf73b9c14641a53743c8";	//Sausainiams ir kitai informacijai

//Admin paneles vartotojas ir slaptazodis
$admin_name="Admin";	//useris
$admin_pass="admin";	//slaptazodis
$admin_email="admin@admin.lt";	//e-pastas

$update_url = "http://www.assembla.com/code/mightmedia/subversion/node/blob/naujienos.json?jsoncallback=?";

// DB Prisijungimas
$prisijungimas_prie_mysql = mysql_connect($host, $user, $pass) or die("<center><h1>Klaida 1</h1><br/>Svetainė laikinai neveikia. <h4>Prašome užsukti vėliau</h4></center>");
mysql_select_db($db,$prisijungimas_prie_mysql) or die("<center><h1>Klaida 2</h1><br/>Svetainė neidiegta. <h4>Prašome užsukti vėliau</h4></center>");
mysql_query("SET NAMES 'utf8'",$prisijungimas_prie_mysql);
$sql = mysql_query("SELECT * FROM `".LENTELES_PRIESAGA."nustatymai`",$prisijungimas_prie_mysql);
if(mysql_num_rows($sql) > 1) while($row = mysql_fetch_assoc($sql)) $conf[$row['key']] = $row['val'];
unset($row,$sql,$user,$host,$pass,$db);
//kalba
if (isset($conf['kalba'])) {
    require_once (realpath(dirname(__file__)) . '/../lang/' . (empty($_SESSION['lang'])?basename($conf['kalba'],'.php'):$_SESSION['lang']). '.php');
} else {
    require_once (realpath(dirname(__file__)) . '/../lang/lt.php');
}
//Jeigu nepavyko nuskaityti nustatymų
if (!isset($conf) || empty($conf)) die("<center><h1>Klaida 3</h1><br/>Svetainė laikinai neveikia. <h4>Prašome užsukti vėliau</h4></center>");

// Inkludinam tai ko mums reikia
require_once(realpath(dirname(__file__))."/funkcijos.php");
?>