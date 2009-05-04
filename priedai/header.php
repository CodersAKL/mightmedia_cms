<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author$
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision$
 * @$Date$
 **/

/*
* Siame faile vykdomos komandos pries uzsikraunant puslapiui
* pvz "vartotojai online" sistema
*/

if (!defined("OK")) {
	header("Location: ?");
	exit;
}

if ($_SERVER['QUERY_STRING'] === 'versija') {
	exit(versija());
}
$timeoutseconds = 300; // Timeout sekundemis

if (isset($url['id'])) {
	$failas = $url['id'];
} //kuriame faile yra
else {
	$failas = 'home';
}

if (isset($_SERVER['HTTP_REFERER'])) {
	$ref = $_SERVER['HTTP_REFERER'];
} //is kur atejo
else {
	$ref = 'Direct Link';
}

$ip = getip(); //funkcija IP gauti

$timestamp = time(); //timestampas
$timeout = $timestamp - $timeoutseconds; //taimout aritmetika :)
$db_expire = $timestamp - 259200; //3 dienos

if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
	$username = $_SESSION['username'];
	$uzerid = $_SESSION['id'];
} else {
	$username = $lang['system']['guest'];
	$uzerid = '0';
}

//uzsetinam unikalu sausaini
if (!isset($_COOKIE["uid"])) {
	$uid = random_name();
	setcookie("uid", $uid, $timestamp + 259200); //3 dienos
} else {
	$uid = $_COOKIE["uid"];
}

if (!check_name($uid)) {
	echo "unsetinau";
	setcookie("uid", "", time() - 3600);
	unset($uid);
}

//jei sausainis yra (bus lengviau nuo botu)
if (isset($uid)) {

	//Jei tai ne botas atnaujinam
	if (!botas($_SERVER['HTTP_USER_AGENT'])) {
		$q = kiek("kas_prisijunges", "WHERE `uid` = " . escape($uid) . "");
		//paskutinio prisijungimo atnaujinimas

		//Jei jau toks useris buvo
		if ($q > 0) {
			$q = "UPDATE `" . LENTELES_PRIESAGA . "kas_prisijunges` SET 
			id=" . $uzerid . ",
			ip=" . escape($ip) . ",
			clicks = clicks+1,
			ref=" . escape($ref) . ",
			file=" . escape(htmlspecialchars($_SERVER['QUERY_STRING'])) . ",
			agent=" . escape(htmlspecialchars($_SERVER['HTTP_USER_AGENT'])) . ",
			user=" . escape($username) . ",
			timestamp=" . escape($timestamp) . "
			WHERE uid=" . escape($uid) . ";";
		} else {
			$q = "INSERT INTO `" . LENTELES_PRIESAGA . "kas_prisijunges` (`id`,`uid`,`timestamp`,`ip`,`file`,`user`,`agent`,`ref`,`clicks`) VALUES (
				" . escape($uzerid) . ",
				" . escape($uid) . ",
				" . escape($timestamp) . ",
				" . escape($ip) . ",
				" . escape(htmlspecialchars($_SERVER['QUERY_STRING'])) . ",
				" . escape($username) . ",
				" . escape(htmlspecialchars($_SERVER['HTTP_USER_AGENT'])) . ",
				" . escape($ref) . ",
				'1');
			";
		}
		mysql_query1($q);
	}
}

$q = mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "kas_prisijunges` WHERE timestamp<" . escape($db_expire) . "");
$q = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "kas_prisijunges` WHERE timestamp>" . escape($timeout) . "");

$online = '';
$i = '';
$u = '';
while ($row = @mysql_fetch_assoc($q)) {
	$nekvepuoja = $timestamp - $row['timestamp'];
	if (time() - $nekvepuoja >= 1 * 60 * 60 && isset($_SESSION['id']) && $row['id'] == $_SESSION['id']) {
		mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "users` SET `login_before`=login_data, `login_data` = '" . time() . "', `ip` = INET_ATON(" . escape(getip()) . ") WHERE `id` ='" . $uzerid . "' LIMIT 1") or die(mysql_error());
	}
	if (!empty($row['id'])) {
		$i++;
	} else {
		$u++;
	}
}
if (isset($u) && $u == 1) {
	$online .= "$u {$lang['system']['guest']}\n";
} elseif (isset($u) && $u > 1) {
	$online .= "$u {$lang['online']['guests']}\n";
}
if (isset($i) && $i > 0 && isset($u) && $u > 0) {
	$online .= " ir ";
}
if (isset($i) && $i == 1) {
	$online .= "$i {$lang['system']['user']}\n";
} elseif (isset($i) && $i > 1) {
	$online .= "$i {$lang['online']['usrs']}\n";
}

//////////////////// ONLINE END /////////////////////
function botas($agentas) {
	if (preg_match("/\bGigabot\b/i", $agentas))
		return true;
	if (preg_match("/\bmsnbot\b/i", $agentas))
		return true;
	if (preg_match("/\bgoogle\b/i", $agentas))
		return true;
	if (preg_match("/\bIRLbot\b/i", $agentas))
		return true;
	if (preg_match("/\bHostTracker\b/i", $agentas))
		return true;
	if (preg_match("/\bEasyDL\b/i", $agentas))
		return true;
	if (preg_match("/\be-collector\b/i", $agentas))
		return true;
	if (preg_match("/\bEmailCollector\b/i", $agentas))
		return true;
	if (preg_match("/\bTelesoft\b/i", $agentas))
		return true;
	if (preg_match("/\bTwiceler\b/i", $agentas))
		return true;
	if (preg_match("/\bInternetSeer.com\b/i", $agentas))
		return true;
	if (preg_match("/\bMJ12bot\b/i", $agentas))
		return true;
	/*
	* if (preg_match("/^Mozilla/\d\.\d\s\(compatible;\sAdvanced\sEmail\sExtractor\sv\d\.\d+\)$/", $agentas)) return true;
	* if (preg_match("/\bCherryPicker\b/i", $agentas)) return true;
	* if (preg_match("/\bCrescent\b/i", $agentas)) return true;
	* if (preg_match("/^DA\s\d\.\d+$/", $agentas)) return true;
	* if (preg_match("/^Mozilla/\d\.\d\s\(compatible;\sMSIE\s\d\.\d;\sWindows\sNT;\sDigExt;\sDTS\sAgent$/", $agentas)) return true;
	* if (preg_match("/^EmailSiphon$/", $agentas)) return true;
	* if (preg_match("EmailWolf", $agentas)) return true;
	* if (preg_match("ExtractorPro", $agentas)) return true;
	* if (preg_match("Go!Zilla", $agentas)) return true;
	* if (preg_match("GetRight/\d.\d", $agentas)) return true;
	* if (preg_match("/^ia_archiver$/", $agentas)) return true;
	* if (preg_match("Indy\sLibrary", $agentas)) return true;
	* if (preg_match("larbin", $agentas)) return true;
	* if (preg_match("MSIECrawler", $agentas)) return true;
	* if (preg_match("Microsoft\sURL\sControl", $agentas)) return true;
	* if (preg_match("NEWT\sActiveX", $agentas)) return true;
	* if (preg_match("NICErsPRO", $agentas)) return true;
	* if (preg_match("RealDownload/\d\.\d\.\d\.\d", $agentas)) return true;
	* if (preg_match("Teleport", $agentas)) return true;
	* if (preg_match("UtilMind\sHTTPGet", $agentas)) return true;
	* if (preg_match("WebBandit", $agentas)) return true;
	* if (preg_match("webcollage/\d\.\d\d", $agentas)) return true;
	* if (preg_match("WebCopier\sv\d\.\d", $agentas)) return true;
	* if (preg_match("WebEMailExtrac", $agentas)) return true;
	* if (preg_match("WebZIP", $agentas)) return true;
	* if (preg_match("/^WGet/\d\.\d", $agentas)) return true;
	* if (preg_match("/^Zeus.+Webster", $agentas)) return true;
	* if (preg_match("/^Mozilla/3\.Mozilla/2\.01\s\(Win95;\sI\)$/", $agentas)) return true;
	* if (preg_match("/^Internet\sExplorer\s?\d?\.?\d?$/", $agentas)) return true;
	* if (preg_match("/^IE\s\d\.\d\sCompatible.*Browser$/", $agentas)) return true;
	* if (preg_match("/^Microsoft\sInternet\sExplorer/4\.40\.426\s\(Windows\s95\)$/", $agentas)) return true;
	* if (preg_match("/^SurveyBot/\d\.\d\s(<a\shref='http://www\.whois\.sc'>Whois\sSource</a>|\(Whois\sSource\))$/", $agentas)) return true;
	* if (preg_match("/^Mozilla/4\.0\s\(?hhjhj@yahoo\.com\)?$/", $agentas)) return true;
	* if (preg_match("/^MSIE", $agentas)) return true;
	* if (preg_match("/^Mozilla$/", $agentas)) return true;
	* if (preg_match("/^Mozilla(\\|/)\?\?$/", $agentas)) return true;
	* if (preg_match("/^Internet\sExplore\s?\d?\.?[a-z0-9]+$/", $agentas)) return true;
	* if (preg_match("/^IAArchiver-\d\.\d$/", $agentas)) return true;
	* if (preg_match("/^NPBot(-\d/\d\.\d)?(\s\(http://www\.nameprotect\.com/botinfo\.html\))?$/", $agentas)) return true;
	* if (preg_match("/^Webclipping\.com$/", $agentas)) return true;
	* if (preg_match("/^Mozilla/\d\.\d\s\(X11;\sLinux\si686;\sen-US;\srv:\d.\d[a-z0-9]*;\sOBJR\)$/", $agentas)) return true;
	* if (preg_match("/^Sqworm/\d\.\d\.\d\d-BETA\s\(beta_release;\s\d{8}-\d{3};\si\d{3}-pc-linux-gnu\)$/", $agentas)) return true;
	* if (preg_match("/^Lickity_Split/\d\.\d$/", $agentas)) return true;
	* if (preg_match("/^Production\sBot\s\d+B$/", $agentas)) return true;
	* if (preg_match("/^amzn_assoc$/", $agentas)) return true;
	* if (preg_match("/^Harvest", $agentas)) return true;
	* if (preg_match("/^Webdup/\d\.\d$/", $agentas)) return true;
	* if (preg_match("/^WebIndex/\d\.\d[a-z]$/", $agentas)) return true;
	* if (preg_match("(/^|\s)RPT-HTTPClient/\d\.\d-\d$/", $agentas)) return true;
	* if (preg_match("/^sitecheck\.internetseer\.com\s\(For\smore\sinfo\ssee:\shttp://sitecheck\.internetseer\.com\)$/", $agentas)) return true;
	* if (preg_match("/^vspider$/", $agentas)) return true;
	* if (preg_match("/^k2spider$/", $agentas)) return true;
	* if (preg_match("/^Mac\sFinder\s", $agentas)) return true;
	* if (preg_match("/^ICU\sv", $agentas)) return true;
	* if (preg_match("/^DART$/", $agentas)) return true;
	* if (preg_match("/^Mozilla/\d\.\d\s\(compatible;\sMSIE\s\d\.\d;\sWindows\sNT\s\d\.\d;\sQ\d{6};\s\.NET\sCLR\s\d\.\d\.\d{4}$/", $agentas)) return true;
	* if (preg_match("/^COMBOMANIA$/", $agentas)) return true;
	* if (preg_match("/^MyCrawler$/", $agentas)) return true;
	* if (preg_match("/^Mozilla/\d\.\d\s\(compatible;\sWin32;\sWinHttp\.WinHttpRequest\.\d\)$/", $agentas)) return true;
	* if (preg_match("/^WEP\sSearch\s\d+$/", $agentas)) return true;
	* if (preg_match("/^Mozilla/\d\.\d\s\(fantomBrowser\)$/", $agentas)) return true;
	* if (preg_match("/^TE$/", $agentas)) return true;
	* if (preg_match("/^WebStripper/\d\.\d\d$/", $agentas)) return true;
	* if (preg_match("/^OWR_Crawler\s\d\.\d$/", $agentas)) return true;
	* if (preg_match("/^WebMiner/\d\.\d\s\[en\]\s\(Win\d\d;\sI\)$/", $agentas)) return true;
	* if (preg_match("/^WebGather\s\d\.\d$/", $agentas)) return true;
	* if (preg_match("/^readwebpage$/", $agentas)) return true;
	* if (preg_match("/^InstantSSL\sBrowser:\slow\scost\sfully\svalidated\sSSL\s\+\sfree\strial$/", $agentas)) return true;
	* if (preg_match("/^Mozilla/\d\.\d\s\(compatible;\sHTTrack\s2\.0x;\sWindows\s.+\)$/", $agentas)) return true;
	* if (preg_match("/^Mozilla/4\.0\s\(compatible;\sPowermarks/\d\.\d;\sWindows\s.+\)$/", $agentas)) return true;
	* if (preg_match("/^Vivante\sLink\sChecker\s\(http://www\.vivante\.com\)$/", $agentas)) return true;
	* if (preg_match("/^Mozilla/\d\.\d\s\(compatible;\sWindows\sNT\s\d\.\d;\sABN\sAMRO$/", $agentas)) return true;
	* if (preg_match("/^Mozilla/\d\.\d\s\(compatible;\sIntelliseek;\shttp://www\.intelliseek\.com\)$/", $agentas)) return true;
	* if (preg_match("/^WebCopier\sSession\s\d$/", $agentas)) return true;
	* if (preg_match("/^Mozilla/\d\.\d\s\(compatible;\sMSIE\s\d\.\d\d;\sWindows\s\d\d$/", $agentas)) return true;
	* if (preg_match("/^Art-Online\.com\s\d\.\d\(Beta\)$/", $agentas)) return true;
	* if (preg_match("/^WebGo\s", $agentas)) return true;
	* if (preg_match("/^SuperBot/\d\.\d\s\(Win\d\d\)$/", $agentas)) return true;
	* if (preg_match("/^Download\sNinja\s\d\.\d$/", $agentas)) return true;
	* if (preg_match("/^Mozilla/\d\.\d\s\(compatible;\sMSIE\s\d\.\d;\sWindows\sNT\s\d\.\d;\s\.NET\sCLR\s\d\.\d\.\d{4}$/", $agentas)) return true;
	* if (preg_match("/^Expired\sDomain\sSleuth$/", $agentas)) return true;
	* if (preg_match("/^SHARP-TQ-GX\d\d$/", $agentas)) return true;
	* if (preg_match("/^HTTP/\d\.\d\sMozilla/\d\.\d\+\(compatible;\+MSIE\+\d\.\d;\+Windows\+NT\+\d\.\d\)$/", $agentas)) return true;
	* if (preg_match("/^.+/\d+\.\d+\s\(Version:\s\d+\sType:\d+\)$/", $agentas)) return true;
	* if (preg_match("/^Offline\sExplorer/\d+\.\d+$/", $agentas)) return true;
	*/
	else {
		return false;
	}
}

function check_name($name) {
	//return preg_match("/^w$/",$name);
	return true;
}
function curl_get_file_contents($URL) {
	if (function_exists('curl_init')) {
		$c = curl_init();
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_URL, $URL);
		$contents = curl_exec($c);
		curl_close($c);

		return $contents;
	} else
		return false;
}
function browser($browser) {
	$path = "/rpc/rpctxtsimple.php";
	$host = "http://user-agent-string.info";
	$access_key = "free";
	$ua = base64_encode($browser);
	$file = $host . $path . "?key=" . $access_key . "&ua=" . $ua;
	$data = curl_get_file_contents($file);
	if ($data) {
		return $data;
	} else {
		return 'curl error';
	}
}

?>