<?php
if (basename($_SERVER['PHP_SELF']) == 'conf.php') { die("Tiesioginis kreipimąsis į failą draudžiamas"); }
define('SETUP', true);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'Off');	//Klaidu pranesimai On/Off
date_default_timezone_set('{{zone}}');//Lithuanian time zone
$host   = '{{host}}';	//mysql sererio adresas
$user   = '{{user}}';	//db vartotojas
$pass   = '{{pass}}';	//slaptazodis
$db     = '{{db}}';	//Duomenu baze

define('LENTELES_PRIESAGA', '{{prefix}}');	//Lenteliu pavadinimu priesaga	
define('SLAPTAS', '{{secret}}'); //Sausainiams ir kitai informacijai
define('MAIN_URL', '{{main_url}}');

//Admin paneles vartotojas ir slaptazodis
$admin_name="Admin"; //useris
$admin_email='{{email}}'; //e-pastas

// DB Prisijungimas
$prisijungimas_prie_mysql = mysqli_connect($host, $user, $pass, $db) or die("<center><h1>Klaida 1</h1><br/>Svetainė laikinai neveikia. <h4>Prašome užsukti vėliau</h4></center>");
mysqli_query($prisijungimas_prie_mysql,"SET NAMES 'utf8mb4'");
$sql = mysqli_query($prisijungimas_prie_mysql,"SELECT * FROM `".LENTELES_PRIESAGA."nustatymai`");
$conf = [];
if(mysqli_num_rows($sql) > 1) while($row = mysqli_fetch_assoc($sql)) $conf[$row['key']] = $row['val'];
unset($row,$sql,$user,$host,$pass,$db);
//kalba
$lang = [];
if (isset($conf['kalba'])) {
    require_once (realpath(dirname(__file__)) . '/../lang/' . (empty($_SESSION[SLAPTAS]['lang'])?basename($conf['kalba'],'.php'):$_SESSION[SLAPTAS]['lang']). '.php');
} else {
    require_once (realpath(dirname(__file__)) . '/../lang/lt.php');
}
//Jeigu nepavyko nuskaityti nustatymų
if (!isset($conf) || empty($conf)) die("<center><h1>Klaida 3</h1><br/>Svetainė laikinai neveikia. <h4>Prašome užsukti vėliau</h4></center>");

// Inkludinam tai ko mums reikia
require_once(realpath(dirname(__file__))."/funkcijos.php");