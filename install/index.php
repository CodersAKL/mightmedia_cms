<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: p.dambrauskas $
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision: 819 $
 * @$Date: 2012-06-23 12:19:17 +0300 (Št, 23 Bir 2012) $
 * @Apie: index.php - TVS diegimo įrankis
 **/

ob_start();
header( "Content-type: text/html; charset=utf-8" );
session_start();
@ini_set( 'error_reporting', E_ALL );
@ini_set( 'display_errors', 'On' );
$root     = '';
$out_page = TRUE;
$inc      = "priedai/conf.php";
while ( !file_exists( $root . $inc ) && strlen( $root ) < 70 ) {
	$root = "../" . $root;
}
if ( !defined( 'ROOT' ) ) {
	//_ROOT = $root;
	define( 'ROOT', '../' );
} else {
	define( 'ROOT', $root );
}

if ( isset( $_SESSION['language'] ) ) {
	include_once( ROOT . "lang/" . $_SESSION['language'] );
	//echo $lang['system']['warning'];
} else {
	include_once( ROOT . "lang/lt.php" );
}
//slaptaþodþio kodavimas
function koduoju( $pass ) {
	return md5( sha1( md5( $pass ) ) );
}

// Aplankalo - papkės - folderio - direktorijos trinimui.
//Neveikia...
function trinam_direktorija($direktorija) {
	$d = dir($direktorija);
	while($entry = $d->read()) {
			unlink($entry);
		}
	$d->close();
	rmdir($direktorija);
}

// Sarašas failų kurių teisės turi suteikti svetainei įrašymo galimybę
$chmod_files[0] = ROOT . "priedai/conf.php";
$chmod_files[]  = ROOT . "install/index.php";
$chmod_files[]  = ROOT . ".htaccess";
$chmod_files[]  = ROOT . "siuntiniai/failai";
$chmod_files[]  = ROOT . "siuntiniai/images";
$chmod_files[]  = ROOT . "siuntiniai/media";
$chmod_files[]  = ROOT . "sandeliukas";
$chmod_files[]  = ROOT . "puslapiai";
$chmod_files[]  = ROOT . "blokai";
$chmod_files[]  = ROOT . "images/avatars";
$chmod_files[]  = ROOT . "images/nuorodu";
$chmod_files[]  = ROOT . "images/galerija";
$chmod_files[]  = ROOT . "images/galerija/originalai";
$chmod_files[]  = ROOT . "images/galerija/mini";
// Unikalus kodas, naudojamas svetainės identifikacijai.
$slaptas = md5( uniqid( rand(), TRUE ) );
//Laiko zonos
/**
 * Timezone List
 * Array compiled from timezone_identifiers_list() that can be used on
 * systems that do not support the function but a list of timezones is
 * needed anyway
 **/

$timezone = array(
	'Africa/Abidjan',
	'Africa/Accra',
	'Africa/Addis_Ababa',
	'Africa/Algiers',
	'Africa/Asmara',
	'Africa/Bamako',
	'Africa/Bangui',
	'Africa/Banjul',
	'Africa/Bissau',
	'Africa/Blantyre',
	'Africa/Brazzaville',
	'Africa/Bujumbura',
	'Africa/Cairo',
	'Africa/Casablanca',
	'Africa/Ceuta',
	'Africa/Conakry',
	'Africa/Dakar',
	'Africa/Dar_es_Salaam',
	'Africa/Djibouti',
	'Africa/Douala',
	'Africa/El_Aaiun',
	'Africa/Freetown',
	'Africa/Gaborone',
	'Africa/Harare',
	'Africa/Johannesburg',
	'Africa/Kampala',
	'Africa/Khartoum',
	'Africa/Kigali',
	'Africa/Kinshasa',
	'Africa/Lagos',
	'Africa/Libreville',
	'Africa/Lome',
	'Africa/Luanda',
	'Africa/Lubumbashi',
	'Africa/Lusaka',
	'Africa/Malabo',
	'Africa/Maputo',
	'Africa/Maseru',
	'Africa/Mbabane',
	'Africa/Mogadishu',
	'Africa/Monrovia',
	'Africa/Nairobi',
	'Africa/Ndjamena',
	'Africa/Niamey',
	'Africa/Nouakchott',
	'Africa/Ouagadougou',
	'Africa/Porto-Novo',
	'Africa/Sao_Tome',
	'Africa/Tripoli',
	'Africa/Tunis',
	'Africa/Windhoek',
	'America/Adak',
	'America/Anchorage',
	'America/Anguilla',
	'America/Antigua',
	'America/Araguaina',
	'America/Argentina/Buenos_Aires',
	'America/Argentina/Catamarca',
	'America/Argentina/Cordoba',
	'America/Argentina/Jujuy',
	'America/Argentina/La_Rioja',
	'America/Argentina/Mendoza',
	'America/Argentina/Rio_Gallegos',
	'America/Argentina/Salta',
	'America/Argentina/San_Juan',
	'America/Argentina/San_Luis',
	'America/Argentina/Tucuman',
	'America/Argentina/Ushuaia',
	'America/Aruba',
	'America/Asuncion',
	'America/Atikokan',
	'America/Bahia',
	'America/Barbados',
	'America/Belem',
	'America/Belize',
	'America/Blanc-Sablon',
	'America/Boa_Vista',
	'America/Bogota',
	'America/Boise',
	'America/Cambridge_Bay',
	'America/Campo_Grande',
	'America/Cancun',
	'America/Caracas',
	'America/Cayenne',
	'America/Cayman',
	'America/Chicago',
	'America/Chihuahua',
	'America/Costa_Rica',
	'America/Cuiaba',
	'America/Curacao',
	'America/Danmarkshavn',
	'America/Dawson',
	'America/Dawson_Creek',
	'America/Denver',
	'America/Detroit',
	'America/Dominica',
	'America/Edmonton',
	'America/Eirunepe',
	'America/El_Salvador',
	'America/Fortaleza',
	'America/Glace_Bay',
	'America/Godthab',
	'America/Goose_Bay',
	'America/Grand_Turk',
	'America/Grenada',
	'America/Guadeloupe',
	'America/Guatemala',
	'America/Guayaquil',
	'America/Guyana',
	'America/Halifax',
	'America/Havana',
	'America/Hermosillo',
	'America/Indiana/Indianapolis',
	'America/Indiana/Knox',
	'America/Indiana/Marengo',
	'America/Indiana/Petersburg',
	'America/Indiana/Tell_City',
	'America/Indiana/Vevay',
	'America/Indiana/Vincennes',
	'America/Indiana/Winamac',
	'America/Inuvik',
	'America/Iqaluit',
	'America/Jamaica',
	'America/Juneau',
	'America/Kentucky/Louisville',
	'America/Kentucky/Monticello',
	'America/La_Paz',
	'America/Lima',
	'America/Los_Angeles',
	'America/Maceio',
	'America/Managua',
	'America/Manaus',
	'America/Marigot',
	'America/Martinique',
	'America/Mazatlan',
	'America/Menominee',
	'America/Merida',
	'America/Mexico_City',
	'America/Miquelon',
	'America/Moncton',
	'America/Monterrey',
	'America/Montevideo',
	'America/Montreal',
	'America/Montserrat',
	'America/Nassau',
	'America/New_York',
	'America/Nipigon',
	'America/Nome',
	'America/Noronha',
	'America/North_Dakota/Center',
	'America/North_Dakota/New_Salem',
	'America/Panama',
	'America/Pangnirtung',
	'America/Paramaribo',
	'America/Phoenix',
	'America/Port-au-Prince',
	'America/Port_of_Spain',
	'America/Porto_Velho',
	'America/Puerto_Rico',
	'America/Rainy_River',
	'America/Rankin_Inlet',
	'America/Recife',
	'America/Regina',
	'America/Resolute',
	'America/Rio_Branco',
	'America/Santarem',
	'America/Santiago',
	'America/Santo_Domingo',
	'America/Sao_Paulo',
	'America/Scoresbysund',
	'America/Shiprock',
	'America/St_Barthelemy',
	'America/St_Johns',
	'America/St_Kitts',
	'America/St_Lucia',
	'America/St_Thomas',
	'America/St_Vincent',
	'America/Swift_Current',
	'America/Tegucigalpa',
	'America/Thule',
	'America/Thunder_Bay',
	'America/Tijuana',
	'America/Toronto',
	'America/Tortola',
	'America/Vancouver',
	'America/Whitehorse',
	'America/Winnipeg',
	'America/Yakutat',
	'America/Yellowknife',
	'Antarctica/Casey',
	'Antarctica/Davis',
	'Antarctica/DumontDUrville',
	'Antarctica/Mawson',
	'Antarctica/McMurdo',
	'Antarctica/Palmer',
	'Antarctica/Rothera',
	'Antarctica/South_Pole',
	'Antarctica/Syowa',
	'Antarctica/Vostok',
	'Arctic/Longyearbyen',
	'Asia/Aden',
	'Asia/Almaty',
	'Asia/Amman',
	'Asia/Anadyr',
	'Asia/Aqtau',
	'Asia/Aqtobe',
	'Asia/Ashgabat',
	'Asia/Baghdad',
	'Asia/Bahrain',
	'Asia/Baku',
	'Asia/Bangkok',
	'Asia/Beirut',
	'Asia/Bishkek',
	'Asia/Brunei',
	'Asia/Choibalsan',
	'Asia/Chongqing',
	'Asia/Colombo',
	'Asia/Damascus',
	'Asia/Dhaka',
	'Asia/Dili',
	'Asia/Dubai',
	'Asia/Dushanbe',
	'Asia/Gaza',
	'Asia/Harbin',
	'Asia/Ho_Chi_Minh',
	'Asia/Hong_Kong',
	'Asia/Hovd',
	'Asia/Irkutsk',
	'Asia/Jakarta',
	'Asia/Jayapura',
	'Asia/Jerusalem',
	'Asia/Kabul',
	'Asia/Kamchatka',
	'Asia/Karachi',
	'Asia/Kashgar',
	'Asia/Kathmandu',
	'Asia/Kolkata',
	'Asia/Krasnoyarsk',
	'Asia/Kuala_Lumpur',
	'Asia/Kuching',
	'Asia/Kuwait',
	'Asia/Macau',
	'Asia/Magadan',
	'Asia/Makassar',
	'Asia/Manila',
	'Asia/Muscat',
	'Asia/Nicosia',
	'Asia/Novosibirsk',
	'Asia/Omsk',
	'Asia/Oral',
	'Asia/Phnom_Penh',
	'Asia/Pontianak',
	'Asia/Pyongyang',
	'Asia/Qatar',
	'Asia/Qyzylorda',
	'Asia/Rangoon',
	'Asia/Riyadh',
	'Asia/Sakhalin',
	'Asia/Samarkand',
	'Asia/Seoul',
	'Asia/Shanghai',
	'Asia/Singapore',
	'Asia/Taipei',
	'Asia/Tashkent',
	'Asia/Tbilisi',
	'Asia/Tehran',
	'Asia/Thimphu',
	'Asia/Tokyo',
	'Asia/Ulaanbaatar',
	'Asia/Urumqi',
	'Asia/Vientiane',
	'Asia/Vladivostok',
	'Asia/Yakutsk',
	'Asia/Yekaterinburg',
	'Asia/Yerevan',
	'Atlantic/Azores',
	'Atlantic/Bermuda',
	'Atlantic/Canary',
	'Atlantic/Cape_Verde',
	'Atlantic/Faroe',
	'Atlantic/Madeira',
	'Atlantic/Reykjavik',
	'Atlantic/South_Georgia',
	'Atlantic/St_Helena',
	'Atlantic/Stanley',
	'Australia/Adelaide',
	'Australia/Brisbane',
	'Australia/Broken_Hill',
	'Australia/Currie',
	'Australia/Darwin',
	'Australia/Eucla',
	'Australia/Hobart',
	'Australia/Lindeman',
	'Australia/Lord_Howe',
	'Australia/Melbourne',
	'Australia/Perth',
	'Australia/Sydney',
	'Europe/Amsterdam',
	'Europe/Andorra',
	'Europe/Athens',
	'Europe/Belgrade',
	'Europe/Berlin',
	'Europe/Bratislava',
	'Europe/Brussels',
	'Europe/Bucharest',
	'Europe/Budapest',
	'Europe/Chisinau',
	'Europe/Copenhagen',
	'Europe/Dublin',
	'Europe/Gibraltar',
	'Europe/Guernsey',
	'Europe/Helsinki',
	'Europe/Isle_of_Man',
	'Europe/Istanbul',
	'Europe/Jersey',
	'Europe/Kaliningrad',
	'Europe/Kiev',
	'Europe/Lisbon',
	'Europe/Ljubljana',
	'Europe/London',
	'Europe/Luxembourg',
	'Europe/Madrid',
	'Europe/Malta',
	'Europe/Mariehamn',
	'Europe/Minsk',
	'Europe/Monaco',
	'Europe/Moscow',
	'Europe/Oslo',
	'Europe/Paris',
	'Europe/Podgorica',
	'Europe/Prague',
	'Europe/Riga',
	'Europe/Rome',
	'Europe/Samara',
	'Europe/San_Marino',
	'Europe/Sarajevo',
	'Europe/Simferopol',
	'Europe/Skopje',
	'Europe/Sofia',
	'Europe/Stockholm',
	'Europe/Tallinn',
	'Europe/Tirane',
	'Europe/Uzhgorod',
	'Europe/Vaduz',
	'Europe/Vatican',
	'Europe/Vienna',
	'Europe/Vilnius',
	'Europe/Volgograd',
	'Europe/Warsaw',
	'Europe/Zagreb',
	'Europe/Zaporozhye',
	'Europe/Zurich',
	'Indian/Antananarivo',
	'Indian/Chagos',
	'Indian/Christmas',
	'Indian/Cocos',
	'Indian/Comoro',
	'Indian/Kerguelen',
	'Indian/Mahe',
	'Indian/Maldives',
	'Indian/Mauritius',
	'Indian/Mayotte',
	'Indian/Reunion',
	'Pacific/Apia',
	'Pacific/Auckland',
	'Pacific/Chatham',
	'Pacific/Easter',
	'Pacific/Efate',
	'Pacific/Enderbury',
	'Pacific/Fakaofo',
	'Pacific/Fiji',
	'Pacific/Funafuti',
	'Pacific/Galapagos',
	'Pacific/Gambier',
	'Pacific/Guadalcanal',
	'Pacific/Guam',
	'Pacific/Honolulu',
	'Pacific/Johnston',
	'Pacific/Kiritimati',
	'Pacific/Kosrae',
	'Pacific/Kwajalein',
	'Pacific/Majuro',
	'Pacific/Marquesas',
	'Pacific/Midway',
	'Pacific/Nauru',
	'Pacific/Niue',
	'Pacific/Norfolk',
	'Pacific/Noumea',
	'Pacific/Pago_Pago',
	'Pacific/Palau',
	'Pacific/Pitcairn',
	'Pacific/Ponape',
	'Pacific/Port_Moresby',
	'Pacific/Rarotonga',
	'Pacific/Saipan',
	'Pacific/Tahiti',
	'Pacific/Tarawa',
	'Pacific/Tongatapu',
	'Pacific/Truk',
	'Pacific/Wake',
	'Pacific/Wallis',
	'UTC' );

// Sugeneruojam atsitiktinį duomenų bazės prieždėlį
function random( $return = '' ) {

	$simboliai = "abcdefghijkmnopqrstuvwxyz0123456789";
	for ( $i = 1; $i < 3; ++$i ) {
		$num = rand() % 33;
		$return .= substr( $simboliai, $num, 1 );
	}
	return $return . '_';
}

//adresas
function adresas() {

	if ( isset( $_SERVER['HTTP_HOST'] ) ) {
		$adresas = isset( $_SERVER['HTTPS'] ) && strtolower( $_SERVER['HTTPS'] ) !== 'off' ? 'https' : 'http';
		$adresas .= '://' . $_SERVER['HTTP_HOST'];
		$adresas .= str_replace( basename( $_SERVER['SCRIPT_NAME'] ), '', $_SERVER['SCRIPT_NAME'] );
	} else {
		$adresas = 'http://localhost/';
	}

	return $adresas;
}

// Diegimo stadijų registravimas
if ( !isset( $_GET['step'] ) || empty( $_GET['step'] ) ) {
	$_SESSION['step'] = 0;
	$step             = 0;
} else {
	if ( $_GET['step'] != 0 ) {
		$step = (int)$_GET['step'];
		if ( $_SESSION['step'] == ( $step - 1 ) ) {
			$_SESSION['step'] = $step;
		}
	} else {
		header( "Location: index.php?step=" . $_SESSION['step'] );
	}
}
if ( isset( $_POST['language'] ) ) {
	$_SESSION['language'] = $_POST['language'];
	header( "Location: index.php?step=1" );
}

// Duomenų bazės prisijungimo tikrinimo ir lentelių sukūrimo dalis
if ( isset( $_POST['next_msyql'] ) ) {
	$host   = strip_tags( $_POST['host'] );
	$user   = strip_tags( $_POST['user'] );
	$pass   = strip_tags( $_POST['pass'] );
	$db     = strip_tags( $_POST['db'] );
	$prefix = ( isset( $_POST['prefix'] ) ? strip_tags( $_POST['prefix'] ) : random() );

	$_SESSION['mysql']['host']   = $host;
	$_SESSION['mysql']['user']   = $user;
	$_SESSION['mysql']['pass']   = $pass;
	$_SESSION['mysql']['db']     = $db;
	$_SESSION['mysql']['prefix'] = $prefix;

	/**
	 * Reikalinga papildomai patikrinti (TODO:)
	 * 1. Ar tinkamas hostas
	 * 2. Ar useris ir pass veikia
	 * 3. Reikalinga funkcija kuri nuskaitytų sql.sql failą tiek local tiek remote. TUri būti universalus ir veikti ant SAFE_MODE režimo
	 */

	$mysql_con = mysqli_connect( $host, $user, $pass );
	mysqli_select_db( $mysql_con, $db );
	if ( !$mysql_con ) {
		$mysql_info = '<b>' . $lang['system']['error'] . '</b> ' . mysqli_error( $mysql_con ) . '<br/><b> #</b>' . mysqli_errno( $mysql_con );
	}
	if ( mysqli_errno( $mysql_con ) == 1049 ) {
		$next_mysql = '<input name="next_msyql" type="submit" value="' . $lang['setup']['crete_db'] . '" />';
		$mysql_con2 = mysqli_connect( $host, $user, $pass );
		mysqli_query( $mysql_con2, "CREATE DATABASE `$db` DEFAULT CHARACTER SET utf8 COLLATE utf8_lithuanian_ci" );
		mysqli_select_db( $mysql_con2, $db );
	} else {
		$mysql_info = '<strong>' . $lang['setup']['mysql_connected'] . '</strong><br />';

		// Sukuriamos visos MySQL leneteles is SVN Trunk
		if ( !file_exists( 'sql.sql' ) ) {
			$sql = file_get_contents( 'http://code.assembla.com/mightmedia/subversion/node/blob/v1/sql' . ( $_SESSION['language'] == 'en.php' ? '(en.php)' : '' ) . '.sql' );
		} else {
			$sql = file_get_contents( 'sql' . ( $_SESSION['language'] == 'en.php' ? '(en.php)' : '' ) . '.sql' );
		}

		// Paruošiam užklausas
		$sql = str_replace( "CREATE TABLE IF NOT EXISTS `", "CREATE TABLE IF NOT EXISTS `" . $prefix, $sql );
		$sql = str_replace( "CREATE TABLE `", "CREATE TABLE IF NOT EXISTS `" . $prefix, $sql );
		$sql = str_replace( "INSERT INTO `", "INSERT INTO `" . $prefix, $sql );
		$sql = str_replace( "UPDATE `", "UPDATE `" . $prefix, $sql );

		// Prisijungiam prie duombazės
		$mysql_con3 = mysqli_connect( $host, $user, $pass );
		mysqli_select_db( $mysql_con3, $db );
		mysqli_query( $mysql_con3, "SET NAMES utf8" );

		// Atliekam SQL apvalymą
		$match = '';
		preg_match_all( "/(?:CREATE|UPDATE|INSERT).*?;[\r\n]/s", $sql, $match );

		$mysql_info  = "<ol>";
		$mysql_error = 0;
		foreach ( $match[0] as $key => $val ) {
			if ( !empty( $val ) ) {
				$query = mysqli_query( $mysql_con3, $val );
				if ( !$query ) {
					$mysql_info .= "<li><b>{$lang['system']['error']} " . mysqli_errno($mysql_con3) . "</b> " . mysqli_error($mysql_con3) . "<hr><b>{$lang['setup']['query']}:</b><br/>" . $val . "</li><hr>";
					$mysql_error++;
				}
			}
		}
		$mysql_info .= "</ol>";

		if ( $mysql_error == 0 ) {
			$mysql_info = $lang['setup']['mysql_created'];
			$next_mysql = '<center><input type="reset" value="' . $lang['setup']['next'] . ' >>" onClick="Go(\'4\');"></center>';
		} else {
			$next_mysql = '<center><input type="reset" value="' . $lang['setup']['try_again'] . '" onClick="Go(\'3\');"></center>';
		}

	}
} else {
	$next_mysql = '<input name="next_msyql" type="submit" value="' . $lang['setup']['create_tables'] . '">';
}
if ( isset( $_POST['time_zone'] ) ) {
	$_SESSION['time_zone'] = $_POST['time_zone'];
	header( "Location: index.php?step=7" );
}
// Administratoriaus sukūrimo dalis
if ( !empty( $_POST['acc_create'] ) ) {
	$user                       = htmlspecialchars( $_POST['user'] );
	$pass                       = ( !empty( $_POST['pass'] ) ? koduoju( $_POST['pass'] ) : "" );
	$pass2                      = ( !empty( $_POST['pass2'] ) ? koduoju( $_POST['pass2'] ) : "" );
	$email                      = htmlspecialchars( $_POST['email'] );
	$_SESSION['admin']['email'] = $email;
	if ( $pass != $pass2 ) {
		$admin_info = $lang['user']['edit_badconfirm'];
	} else {
		if ( !empty( $user ) && !empty( $pass ) && !empty( $pass2 ) && !empty( $email ) ) {
			$mysql_con4 = mysqli_connect( $_SESSION['mysql']['host'], $_SESSION['mysql']['user'], $_SESSION['mysql']['pass'] );
			mysqli_query( $mysql_con4, "SET NAMES utf8" );
			mysqli_select_db( $mysql_con4, $_SESSION['mysql']['db'] );
			mysqli_query( $mysql_con4, "UPDATE `" . $_SESSION['mysql']['prefix'] . "users` SET `nick`='" . $user . "', `pass`='" . $pass . "', `email`='" . $email . "', `reg_data`='" . time() . "', `ip`='" . $_SERVER['REMOTE_ADDR'] . "' WHERE `nick`='Admin'" ) or die( mysqli_error($mysql_con4) );
			
			mysqli_query( $mysql_con4, "INSERT INTO `" . $_SESSION['mysql']['prefix'] . "nustatymai` (`key`, `val`) VALUES ('Pastas', '" . $email . "');" ) or die( mysqli_error($mysql_con4) );
			mysqli_query( $mysql_con4, "INSERT INTO `" . $_SESSION['mysql']['prefix'] . "nustatymai` (`key`, `val`) VALUES ('kalba', '" . $_SESSION['language'] . "');" ) or die( mysqli_error($mysql_con4) );
			header( "Location: index.php?step=5" );
		} else {
			$admin_info = $lang['admin']['news_required'];
		}
	}
}

//Administravimo direktorijos keitimas
if ( !empty( $_POST['admin_dir'] ) ) {
	if ( is_dir( ROOT."dievai" ) ) //Pervadink "dievai" direktoriją į sunkiau nuspėjamą
	{
		header( "Location: index.php?step=5" );
	} else {
		header( "Location: index.php?step=6" );
	}
}

// Diegimo pabaiga
if ( !empty( $_POST['finish'] ) ) {
	$zone    = ( isset( $_SESSION['time_zone'] ) ? $_SESSION['time_zone'] : 'Europe/Vilnius' );
	$content = <<< HTML
<?php
if (basename(\$_SERVER['PHP_SELF']) == 'conf.php') { die("Tiesioginis kreipimąsis į failą draudžiamas"); }
define('SETUP',true);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'Off');	//Klaidu pranesimai On/Off
date_default_timezone_set('{$zone}');//Lithuanian time zone
\$host = "{$_SESSION['mysql']['host']}";	//mysql sererio adresas
\$user = "{$_SESSION['mysql']['user']}";	//db vartotojas
\$pass = "{$_SESSION['mysql']['pass']}";	//slaptazodis
\$db = "{$_SESSION['mysql']['db']}";	//Duomenu baze
define("LENTELES_PRIESAGA", "{$_SESSION['mysql']['prefix']}");	//Lenteliu pavadinimu priesaga
\$slaptas = "{$slaptas}";	//Sausainiams ir kitai informacijai
define('SLAPTAS', \$slaptas);

//Admin paneles vartotojas ir slaptazodis
\$admin_name="Admin";	//useris
\$admin_email="{$_SESSION['admin']['email']}";	//e-pastas

//Versiju tikrinimas
\$update_url = "http://www.assembla.com/code/mightmedia/subversion/node/blob/naujienos.json?jsoncallback=?";

// DB Prisijungimas
\$prisijungimas_prie_mysql = mysqli_connect(\$host, \$user, \$pass, \$db) or die("<center><h1>Klaida 1</h1><br/>Svetainė laikinai neveikia. <h4>Prašome užsukti vėliau</h4></center>");
mysqli_query(\$prisijungimas_prie_mysql,"SET NAMES 'utf8'");
\$sql = mysqli_query(\$prisijungimas_prie_mysql,"SELECT * FROM `".LENTELES_PRIESAGA."nustatymai`");
\$conf = array();
if(mysqli_num_rows(\$sql) > 1) while(\$row = mysqli_fetch_assoc(\$sql)) \$conf[\$row['key']] = \$row['val'];
unset(\$row,\$sql,\$user,\$host,\$pass,\$db);
//kalba
\$lang = array();
if (isset(\$conf['kalba'])) {
    require_once (realpath(dirname(__file__)) . '/../lang/' . (empty(\$_SESSION[SLAPTAS]['lang'])?basename(\$conf['kalba'],'.php'):\$_SESSION[SLAPTAS]['lang']). '.php');
} else {
    require_once (realpath(dirname(__file__)) . '/../lang/lt.php');
}
//Jeigu nepavyko nuskaityti nustatymų
if (!isset(\$conf) || empty(\$conf)) die("<center><h1>Klaida 3</h1><br/>Svetainė laikinai neveikia. <h4>Prašome užsukti vėliau</h4></center>");

// Inkludinam tai ko mums reikia
require_once(realpath(dirname(__file__))."/funkcijos.php");
?>
HTML;
	//if (is_writable($chmod_files[0])) {
	if ( !$handle = fopen( $chmod_files[0], 'w' ) ) {
		die( "{$lang['setup']['cant_open']} (" . $chmod_files[0] . ")" );
	}
	if ( fwrite( $handle, $content ) === FALSE ) {
		die( "{$lang['setup']['cant_write']} (" . $chmod_files[0] . ")" );
	}
	fclose( $handle );
	unset( $handle );
	//htaccess
	$htaccess = "
ErrorDocument 404 " . adresas() . "/klaida.php
ErrorDocument 400 " . adresas() . "/klaida.php
ErrorDocument 401 " . adresas() . "/klaida.php
ErrorDocument 403 " . adresas() . "/klaida.php
ErrorDocument 405 " . adresas() . "/klaida.php
ErrorDocument 406 " . adresas() . "/klaida.php
ErrorDocument 409 " . adresas() . "/klaida.php
ErrorDocument 413 " . adresas() . "/klaida.php
ErrorDocument 414 " . adresas() . "/klaida.php
ErrorDocument 500 " . adresas() . "/klaida.php
ErrorDocument 501 " . adresas() . "/klaida.php
";
	if ( !$handle = fopen( $chmod_files[2], 'a' ) ) {
		die( "{$lang['setup']['cant_open']} (" . $chmod_files[2] . ")" );
	}
	if ( fwrite( $handle, $htaccess ) === FALSE ) {
		die( "{$lang['setup']['cant_write']} (" . $chmod_files[2] . ")" );
	}
	fclose( $handle );
	@chmod("index.php", 0777 );

	unlink( "index.php" );
	//}
	header( "Location: ".  ROOT . "index.php" );
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="resource-type" content="document" />
	<meta name="distribution" content="global" />
	<meta name="author" content="CodeRS - MightMedia TVS" />
	<meta name="copyright" content="copyright (c) by CodeRS www.coders.lt" />
	<meta name="rating" content="general" />
	<meta name="generator" content="notepad" />
	<link rel="shortcut icon" href="<?php echo ROOT; ?>images/favicon.ico" type="image/x-icon" />
	<link rel="icon" href="<?php echo ROOT; ?>images/favicon.ico" type="image/x-icon" />
	<script src="<?php echo ROOT; ?>javascript/jquery/jquery-1.3.2.min.js" type="text/javascript"></script>
	<script src="<?php echo ROOT; ?>javascript/jquery/tooltip.js" type="text/javascript"></script>
	<title>MightMedia TVS/CMS</title>
	<link rel="stylesheet" type="text/css" media="all" href="default.css" />
</head>
<body>
<div id="plotis">
<?php
$menu_pavad = array( 1 => $lang['setup']['liceanse'], 2 => $lang['setup']['file_check'], 3 => $lang['setup']['database'], 4 => $lang['setup']['admin'], 5 => $lang['setup']['admin_dir'], 6 => $lang['setup']['time_zone'], 7 => $lang['setup']['end'] );
$text       = '';
foreach ( $menu_pavad as $key => $value ) {
	if ( $key <= $step ) {
		$text .= "\t\t\t<li><img src=\"" . ROOT . "images/icons/tick_circle.png\" style=\"vertical-align: middle;\" /><font color=\"green\"><b>" . $value . "</b></font></li>";
	} else {
		$text .= "\t\t\t<li><img src=\"" . ROOT . "images/icons/cross_circle.png\" style=\"vertical-align: middle;\" /><b>" . $value . "</b></li>";
	}
}
?>
<div id="kaire">
	<div class="skalpas"><a href="<?php echo adresas(); ?>" title="<?php echo adresas(); ?>">
		<div class="logo"></div>
	</a></div>
	<div class='pavadinimas'><?php echo $lang['setup']['steps']; ?></div>
	<div class='vidus'>
		<div class='text'>
			<ul><?php echo $text; ?></ul>
		</div>
	</div>
</div>
<div id="kunas">
<div id="meniu_juosta">MightMedia CMS setup / MightMedia TVS įdiegimas</div>
<div id="centras">
<?php if ( $step == 0 ) { ?>
<form name="lang" method="post" action="">
	<div class='pavadinimas'>Language / Kalba</div>
	<div class='vidus'>
		<div class='text'>
			Select language / Pasirinkite kalbą:<br />
			<select name="language">
				<option value="lt.php">Lietuvių</option>
				<option value="en.php">English</option>
			</select><br />
			<input style="margin-top:5px;" name="go" type="submit" value="<?php echo $lang['setup']['next'];?>" />
</form>
	<?php } if ( $step == 1 ) { ?>
<form name="setup" action="">
	<div class='pavadinimas'><?php echo $lang['setup']['liceanse'];?></div>
	<div class='vidus'>
		<div class='text'>
			<textarea name="copy" rows=15 cols=90 wrap="on" readonly="readonly"><?php include ( 'license.txt' ); ?></textarea><br />
			<label><input name="agree_check" type="checkbox" value="ON" /> <?php echo $lang['setup']['agree']; ?>
			</label><br /><br />
			<input class="submit" name="agree" type="reset" value="<?php echo $lang['setup']['next'];?> >>" onClick="Check();" />
</form>
	<?php } if ( $step == 2 ) { ?>
<div class='pavadinimas'><?php echo $lang['setup']['file_check'];?></div>
<div class='vidus'>
<div class='text'>
<?php echo $lang['setup']['file_check_info1']; ?><br /><br />
	<h2><?php echo $lang['setup']['file_check_legend'];?></h2>
	<img src="<?php echo ROOT; ?>images/icons/tick.png" alt="" /> <?php echo $lang['setup']['file_check_info2']; ?>
	<br />
	<img src="<?php echo ROOT; ?>images/icons/cross.png" alt="" /> <?php echo $lang['setup']['file_check_info3']; ?>
	<br /><br />
	<strong><?php echo $lang['setup']['note'];?>:</strong>
	<?php echo $lang['setup']['file_check_info3']; ?>
	<table border="0" class="table">
		<tr>
			<th class="th" valign="top" width="10%"><?php echo $lang['setup']['file'];?></th>
			<th class="th" valign="top" width="5%"><?php echo $lang['setup']['point'];?></th>
			<th class="th" valign="top" width="35%"><?php echo $lang['setup']['about_error'];?></th>
		</tr>
		<?php
		$kartot = count( $chmod_files ) - 1;
		for ( $i = 0; $i <= $kartot; $i++ ) {
			$teises = substr( sprintf( '%o', fileperms( $chmod_files[$i] ) ), -4 );
			if ( $teises != 777 && $teises != 666 && !is_writable( $chmod_files[$i] ) ) {
				$file_error = 'Y';
			}
			echo "
<tr class=\"tr\">
<td>" . $chmod_files[$i] . "</td>
<td>" . ( ( $teises == 777 ) || ( $teises == 666 ) || is_writable( $chmod_files[$i] ) ? "<img src=\"" . ROOT . "images/icons/tick.png\" />" : "<img src=\"" . ROOT . "images/icons/cross.png\" />" ) . "</td>
<td>" . ( ( $teises == 777 ) || ( $teises == 666 ) || is_writable( $chmod_files[$i] ) ? "" : "{$lang['setup']['chmod_777']} <strong>" . $chmod_files[$i] . "</strong> {$lang['setup']['chmod_777_2']} <strong>" . $teises . "</strong>" ) . "</td>
</tr>";
		}
		?>
	</table>
	<br /><br />
	<?php
	if ( isset( $file_error ) && $file_error == 'Y' ) {
		echo '<center><input class="submit" type="reset" value="' . $lang['setup']['reload'] . '" onClick="JavaScript:location.reload(true);"> <input class="submit" type="reset" value="' . $lang['setup']['if_you_think_ok'] . '" onClick="Go(\'3\');"><center>';
	} else {
		echo '<center><input type="reset" class="submit" value="' . $lang['setup']['next'] . '" onClick="Go(\'3\');"></center>';
	}
}
//END
// HTML DALIS - MySQL duomenų bazės nustatymai
if ( $step == 3 ) {
	?>
	<div class='pavadinimas'><?php echo $lang['setup']['database'];?></div>
<div class='vidus'>
<div class='text'>
<?php echo $lang['setup']['mysql_info']; ?>
	<form name="mysql" method="post" action="?step=3">
		<table border="0" width="100%">
			<tr>
				<td><h2><?php echo $lang['setup']['mysql_connect'];?></h2></td>
			</tr>
			<tr>
				<td>
					<table border="0" width="80%">
						<tr>
							<td><?php echo $lang['setup']['mysql_host'];?>:</td>
							<td>
								<input name="host" type="text" value="<?php echo ( isset( $_SESSION['mysql']['host'] ) ? $_SESSION['mysql']['host'] : 'localhost' ); ?>" /><br />
							</td>
						</tr>
						<tr>
							<td><?php echo $lang['setup']['mysql_user'];?>:</td>
							<td>
								<input name="user" type="text" value="<?php echo ( isset( $_SESSION['mysql']['user'] ) ? $_SESSION['mysql']['user'] : 'root' ); ?>" />
							</td>
						</tr>
						<tr>
							<td><?php echo $lang['setup']['mysql_pass'];?>:</td>
							<td>
								<input name="pass" type="password" value="<?php echo ( isset( $_SESSION['mysql']['pass'] ) ? $_SESSION['mysql']['pass'] : '' ); ?>" />
							</td>
						</tr>
						<tr>
							<td><?php echo $lang['setup']['mysql_db'];?>:</td>
							<td>
								<input name="db" type="text" value="<?php echo ( isset( $_SESSION['mysql']['db'] ) ? $_SESSION['mysql']['db'] : 'mightmedia' ); ?>" />
							</td>
						</tr>
						<tr>
							<td><?php echo $lang['setup']['mysql_prfx'];?>:</td>
							<td>
								<input name="prefix" type="text" value="<?php echo ( isset( $_SESSION['mysql']['prefix'] ) ? $_SESSION['mysql']['prefix'] : random() ); ?>" />
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<br />
		<center>
			<p id="mysql_response"><?php echo $next_mysql; ?></p>
		</center>
		<?php if ( isset( $mysql_info ) ): ?>
		<br />
		<table border="0" width="50%">
			<tr>
				<td><h2><?php echo $lang['user']['user_info'];?></h2></td>
			</tr>
			<tr>
				<td>
					<div id="info"><?php echo $mysql_info; ?></div>
				</td>
			</tr>
		</table>
		<?php endif ?>
	</form>
	<?php
}
//END
// HTML DALIS - TVS administratoriaus sukūrimas
if ( $step == 4 ) {
	?>
	<div class='pavadinimas'><?php echo $lang['setup']['admin'];?></div>
<div class='vidus'>
<div class='text'>
<?php echo $lang['setup']['admin_info']; ?>
	<br />
	<span style="color: red"><?php echo ( isset( $admin_info ) ? $admin_info : '' ); ?></span>
	<br />
	<form name="admin_form" method="post" action="">
		<table border="0" width="70%">
			<tr>
				<td width="50%"><?php echo $lang['reg']['username'];?>:</td>
				<td><input name="user" type="text" value="<?php echo ( isset( $user ) ? $user : '' ); ?>" /></td>
			</tr>
			<tr>
				<td><?php echo $lang['reg']['password'];?>:</td>
				<td><input name="pass" type="password" value="" /></td>
			</tr>
			<tr>
				<td><?php echo $lang['reg']['confirmpassword'];?>:</td>
				<td><input name="pass2" type="password" value="" /></td>
			</tr>
			<tr>
				<td><?php echo $lang['reg']['email'];?>:</td>
				<td><input name="email" type="text" value="<?php echo ( isset( $email ) ? $email : '' ); ?>" /></td>
			</tr>
		</table>
		<br />
		<center><input class="submit" name="acc_create" type="submit" value="<?php echo $lang['setup']['next'];?>" />
		</center>
	</form>
	<?php
}
//END
if ( $step == 5 ) {
	?>
	<div class='pavadinimas'><?php echo $lang['setup']['admin_dir'];?></div>
<div class='vidus'>
<div class='text'>
<?php echo $lang['setup']['admin_dir_info']; ?>
	<br /><br />
	<form name="admin_dir" method="post" action="">
		<center><input name="admin_dir" type="submit" value="<?php echo $lang['setup']['next'];?>" /></center>
	</form>
	<?php
}
if ( $step == 6 ) {
	?>
	<div class='pavadinimas'><?php echo $lang['setup']['time_zone'];?></div>
<div class='vidus'>
<div class='text'>
<?php echo $lang['setup']['time_zone_info']; ?>
	<br /><br />
	<form name="tz" method="post" action="">
		<center>
			<select name="time_zone">
				<?php foreach ( $timezone as $tz )
				echo '<option ' . ( $tz == 'Europe/Vilnius' ? 'selected' : '' ) . ' value="' . $tz . '">' . $tz;
				?>
			</select>
			<input name="tzone" type="submit" value="<?php echo $lang['setup']['next'];?>" />
		</center>
	</form>
	<?php
}
// HTML DALIS - Pabaiga
if ( $step == 7 ) {
	?>
	<div class='pavadinimas'><?php echo $lang['setup']['end'];?></div>
<div class='vidus'>
<div class='text'>
<?php echo $lang['setup']['end_info']; ?>
	<form name="finish_install" method="post" action="">
		<center><input class="submit" name="finish" type="submit" value="<?php echo $lang['setup']['end'];?>" /></center>
	</form>
	<?php
}
//END
?>
</div>
</div>
</div>
	<div class="sonas"></div>
	<div id="kojos">
		<div class="tekstas">MightMedia CMS / MightMedia TVS</div>
		<a href="http://mightmedia.lt" target="_blank" title="Mightmedia">
			<div class="logo"></div>
		</a>
	</div>
</div>
</div>
	<div id="another" class="clear">
		<div class="lygiuojam">
			<div class="taisom"></div>
		</div>
	</div>
	<script type="text/javascript">
		function Check() {
			if (document.setup.agree_check.checked == true) {
				Go(2);
			} else {
				alert('<?php echo $lang['setup']['agree_please'];?>');
			}
		}
		function Go(id) {
			document.location.href = "index.php?step=" + id;
		}

	</script>

</body>
</html>
<?php ob_end_flush(); ?>
