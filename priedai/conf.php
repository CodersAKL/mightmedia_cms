<?php
if (basename($_SERVER['PHP_SELF']) == 'conf.php') { die("Tiesioginis kreipimąsis į failą draudžiamas"); }
define('SETUP',true);
date_default_timezone_set('Europe/Vilnius');
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'Off');       //Klaidu pranesimai On/Off
$host = "localhost";                                //mysql sererio adresas
$user = "root";                                //db vartotojas
$pass = "";                                //slaptazodis
$db = "mightmedia";                                    //Duomenu baze
define("LENTELES_PRIESAGA", "bla_");  //Lenteliu pavadinimu priesaga
$slaptas = "7488425850e9fd4e41e01c3ac1c9a99c";               //Sausainiams ir kitai informacijai

//Admin paneles vartotojas ir slaptazodis
$admin_name="Admin"; //useris
$admin_pass="admin"; //slaptazodis
$admin_email="fdisk1@narod.ru";                  //e-pastas

// DB Prisijungimas
$prisijungimas_prie_mysql = mysql_connect($host, $user, $pass) or die("<center><h1>Klaida 1</h1><br/>Svetainė laikinai neveikia. <h4>Prašome užsukti vėliau</h4></center>");
mysql_select_db($db,$prisijungimas_prie_mysql) or die("<center><h1>Klaida 2</h1><br/>Svetainė neidiegta. <h4>Prašome užsukti vėliau</h4></center>");
mysql_query("SET NAMES 'utf8'",$prisijungimas_prie_mysql);
$sql = mysql_query("SELECT * FROM `".LENTELES_PRIESAGA."nustatymai`",$prisijungimas_prie_mysql);
if(mysql_num_rows($sql) > 1) while($row = mysql_fetch_assoc($sql)) $conf[$row['key']] = $row['val'];
unset($row,$sql,$user,$host,$pass,$db);
//kalba
if (isset($conf['kalba'])) {
    include_once ('lang/' . $conf['kalba'] . '');
} else {
    include_once ('lang/lt.php');
}
//Jeigu nepavyko nuskaityti nustatymų
if (!isset($conf) || empty($conf)) die("<center><h1>Klaida 3</h1><br/>Svetainė laikinai neveikia. <h4>Prašome užsukti vėliau</h4></center>");

//Stiliaus funkcijos
include_once(realpath(dirname(__file__))."/../stiliai/".$conf['Stilius']."/sfunkcijos.php");
// Inkludinam tai ko mums reikia
include_once("funkcijos.php");
?>