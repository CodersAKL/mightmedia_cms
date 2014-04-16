<?php
if (basename($_SERVER['PHP_SELF']) == 'conf.php') { die("Tiesioginis kreipimąsis į failą draudžiamas"); }
define('SETUP',true);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'Off');	//Klaidu pranesimai On/Off
date_default_timezone_set('Europe/Vilnius');//Lithuanian time zone
$host = "localhost";	//mysql sererio adresas
$user = "root";	//db vartotojas
$pass = "usbw";	//slaptazodis
$db = "mightmedia";	//Duomenu baze
define("LENTELES_PRIESAGA", "xq_");	//Lenteliu pavadinimu priesaga
$slaptas = "acf4ba681a90aeeb7322f21afbb79b25";	//Sausainiams ir kitai informacijai
define('SLAPTAS', $slaptas);

//Admin paneles vartotojas ir slaptazodis
$admin_name="Admin";	//useris
$admin_email="projektas@gmail.com";	//e-pastas

//Versiju tikrinimas
$update_url = "http://www.assembla.com/code/mightmedia/subversion/node/blob/naujienos.json?jsoncallback=?";

// DB Prisijungimas
$prisijungimas_prie_mysql = mysql_connect($host, $user, $pass) or die("<center><h1>Klaida 1</h1><br/>Svetainė laikinai neveikia. <h4>Prašome užsukti vėliau</h4></center>");
mysql_select_db($db,$prisijungimas_prie_mysql) or die("<center><h1>Klaida 2</h1><br/>Svetainė neidiegta. <h4>Prašome užsukti vėliau</h4></center>");
mysql_query("SET NAMES 'utf8'",$prisijungimas_prie_mysql);
$sql = mysql_query("SELECT * FROM `".LENTELES_PRIESAGA."nustatymai`",$prisijungimas_prie_mysql);
$conf = array();
if(mysql_num_rows($sql) > 1) while($row = mysql_fetch_assoc($sql)) $conf[$row['key']] = $row['val'];
unset($row,$sql,$user,$host,$pass,$db);
//kalba
$lang = array();
if (isset($conf['kalba'])) {
    require_once (realpath(dirname(__file__)) . '/../lang/' . (empty($_SESSION[SLAPTAS]['lang'])?basename($conf['kalba'],'.php'):$_SESSION[SLAPTAS]['lang']). '.php');
} else {
    require_once (realpath(dirname(__file__)) . '/../lang/lt.php');
}
//Jeigu nepavyko nuskaityti nustatymų
if (!isset($conf) || empty($conf)) die("<center><h1>Klaida 3</h1><br/>Svetainė laikinai neveikia. <h4>Prašome užsukti vėliau</h4></center>");

// Inkludinam tai ko mums reikia
require_once(realpath(dirname(__file__))."/funkcijos.php");
?>