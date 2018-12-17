<?php
if (basename($_SERVER['PHP_SELF']) == 'config.php') { die("Tiesioginis kreipimąsis į failą draudžiamas"); }
define('SETUP',true);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'On');	//Klaidu pranesimai On/Off
date_default_timezone_set('Europe/Vilnius');//Lithuanian time zone
$host = "localhost";	//mysql sererio adresas
$user = "root";	//db vartotojas
$pass = "";	//slaptazodis
$db = "mm";	//Duomenu baze
define("LENTELES_PRIESAGA", "wc_");	//Lenteliu pavadinimu priesaga
$slaptas = "e3765f8c5cf232731f3bef11604a8463";	//Sausainiams ir kitai informacijai
define('SLAPTAS', $slaptas);

//Admin paneles vartotojas ir slaptazodis
$admin_name="Admin";	//useris
$admin_email="aivaras.cenkus@gmail.com";	//e-pastas

//Versiju tikrinimas
$update_url = "http://www.assembla.com/code/mightmedia/subversion/node/blob/naujienos.json?jsoncallback=?";

// DB Prisijungimas
$prisijungimas_prie_mysql = mysqli_connect($host, $user, $pass, $db) or die("<center><h1>Klaida 1</h1><br/>Svetainė laikinai neveikia. <h4>Prašome užsukti vėliau</h4></center>");
mysqli_query($prisijungimas_prie_mysql,"SET NAMES 'utf8'");
$sql = mysqli_query($prisijungimas_prie_mysql,"SELECT * FROM `".LENTELES_PRIESAGA."nustatymai`");
$conf = array();
if(mysqli_num_rows($sql) > 1) while($row = mysqli_fetch_assoc($sql)) $conf[$row['key']] = $row['val'];
unset($row,$sql,$user,$host,$pass,$db);
//kalba
$lang = array();
if (isset($conf['kalba'])) {
    require_once (realpath(dirname(__file__)) . '/lang/' . (empty($_SESSION[SLAPTAS]['lang'])?basename($conf['kalba'],'.php'):$_SESSION[SLAPTAS]['lang']). '.php');
} else {
    require_once (realpath(dirname(__file__)) . '/lang/lt.php');
}
//Jeigu nepavyko nuskaityti nustatymų
if (!isset($conf) || empty($conf)) die("<center><h1>Klaida 3</h1><br/>Svetainė laikinai neveikia. <h4>Prašome užsukti vėliau</h4></center>");
