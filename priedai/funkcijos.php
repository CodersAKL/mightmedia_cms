<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author$
 * @copyright CodeRS Â©2008
 * @license GNU General Public License v2
 * @$Revision$
 * @$Date$
 **/

//patikrinimui ar puslapaia atveriami taip kaip reikia
if (basename($_SERVER['PHP_SELF']) == 'funkcijos.php') {
	ban($lang['system']['forhacking']);
}
define("OK", true);

if (preg_match('%/\*\*/|SERVER|http|SELECT|UNION|UPDATE|INSERT%i', $_SERVER['QUERY_STRING'])) {
	$ip = getip();
	$forwarded = (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : 'N/A');
	$remoteaddress = $_SERVER["REMOTE_ADDR"];
	ban($lang['system']['forhacking']);
}
//slaptaþodþio kodavimas
function koduoju($pass) {
	return md5(sha1(md5($pass)));
}
//meta tagai ir kita
function header_info() {
	global $conf, $page_pavadinimas;
	echo '
	<meta name="generator" content="notepad" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="description" content="' . input(strip_tags($conf['Pavadinimas']) . ' - ' . trimlink(strip_tags($conf['Apie']), 120)) . '" />
  <meta name="keywords" content="' . input(strip_tags($conf['Keywords'])) . '" />
  <meta name="author" content="' . input(strip_tags($conf['Copyright'])) . '" />
  <link rel="stylesheet" type="text/css" href="stiliai/' . input(strip_tags($conf['Stilius'])) . '/default.css" />
  <link rel="stylesheet" type="text/css" href="stiliai/system.css" />
  <link href="stiliai/rating_style.css" rel="stylesheet" type="text/css" media="all" />
  <link rel="shortcut icon" href="favicon.ico" />
  
  <title>' . input(strip_tags($conf['Pavadinimas']) . ' - ' . $page_pavadinimas) . '</title>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script> 
  <script type="text/javascript" src="javascript/jquery/jquery-ui-personalized-1.6rc6.min.js"></script> 
  <script type="text/javascript" src="javascript/jquery/jquery.tablesorter.js"></script> 
  <script language="javascript" type="text/javascript" src="javascript/pagrindinis.js"></script>  
  <script language="javascript" type="text/javascript" src="javascript/rating_update.js"></script>	
  <script type="text/javascript" src="javascript/jquery/tooltip.js"></script>
	<!--[if lt IE 7]>
	<script type="text/javascript" src="javascript/jquery/jquery.pngFix.pack.js"></script>
	<script type="text/javascript">$(document).ready(function(){$(document).pngFix();});</script>
	<script src="http://ie7-js.googlecode.com/svn/version/2.0(beta3)/IE7.js" type="text/javascript"></script>
	<![endif]-->
';
}
// Nupiesiam vartotojo avatara
function avatar($mail, $size = 80) {
	$result = '<img src="http://www.gravatar.com/avatar/' . md5(strtolower($mail)) . '?s=' . htmlentities($size . '&r=any&default=' . urlencode(adresas() . 'images/avatars/no_image.jpg') . '&time=' . time()) . '"  width="' . $size . '" alt="avataras" />';
	return $result;
}
function nice_name($name) {
	$name = ucfirst_utf8($name);
	$name = str_replace(".php", "", $name);
	$name = str_replace("_", " ", $name);
	return $name;
}
function ar_admin($failas) {
	global $_SESSION;
	if ((is_array(unserialize($_SESSION['mod'])) && in_array($failas, unserialize($_SESSION['mod']))) || $_SESSION['level'] == 1)
		return true;
	else
		return false;
}
function ucfirst_utf8($str) {
	if (mb_check_encoding($str, 'UTF-8')) {
		$first = mb_substr(mb_strtoupper($str, "utf-8"), 0, 1, 'utf-8');
		return $first . mb_substr(mb_strtolower($str, "utf-8"), 1, mb_strlen($str), 'utf-8');
	} else {
		return $str;
	}
}
function utf8_substr($str, $start) {
	preg_match_all("/./u", $str, $ar);

	if (func_num_args() >= 3) {
		$end = func_get_arg(2);
		return join("", array_slice($ar[0], $start, $end));
	} else {
		return join("", array_slice($ar[0], $start));
	}
}
// Svetaines adresui gauti
function adresas() {
	$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
	$protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) . $s;
	$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":" . $_SERVER["SERVER_PORT"]);
	//return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
	return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . '/';
}

/**
 * Patikrina ar puslapis egzistuoja ir grazina puslapio ID
 * @param string $puslapis
 */
function puslapis($puslapis, $extra = false) {
	global $conf;
	if (isset($conf['puslapiai'][$puslapis]['id']) && !empty($conf['puslapiai'][$puslapis]['id']) && is_file(dirname(__file__) . '/puslapiai/' . $puslapis)) {
		if ($extra && isset($conf['puslapiai'][$puslapis][$extra]))
			return $conf['puslapiai'][$puslapis][$extra]; //Jei reikalinga kita informacija apie puslapi - grazinam ja.
		else
			return (int)$conf['puslapiai'][$puslapis]['id'];
	} else
		return false;
}

/**
 * UÅ¾drausti IP ant serverio
 *
 * @param string $kodel
 */
function ban($kodel = 'XSS') {
	global $lang;

	global $_SERVER, $ip, $forwarded, $remoteaddress;
	$atidaryti = fopen(".htaccess", "a");
	fwrite($atidaryti, "# {$lang['system']['forhacking']} \ndeny from " . getip() . "\n");
	fclose($atidaryti);
	//@chmod(".htaccess", 0777);

	$forwarded = (isset($forwarded) ? $forwarded : 'N/A');
	$remoteaddress = (isset($remoteaddress) ? $remoteaddress : 'N/A');
	$ip = (isset($ip) ? $ip : getip());
	$referer = (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'N/A');

	$message = <<< HTML
	FROM:{$referer}
	REQ:{$_SERVER['REQUEST_METHOD']}
	FILE:{$_SERVER['SCRIPT_FILENAME']}
	QUERY:{$_SERVER['QUERY_STRING']}
	IP:{$ip} - Forwarded = {$forwarded} - Remoteaddress = {$remoteaddress}
HTML;


	die("<center><h1>{$lang['system']['nohacking']}!</h1><font color='red'><b>" . $kodel . " - {$lang['system']['forbidden']}<blink>!</blink></b></font><hr/></center>");
}

/// ASPAUGA - NETRINK - Pagal php-fusion

/*foreach ($_GET as $check_url) {
* if ((eregi("<[^>]*script*\"?[^>]*>", $check_url)) || (eregi("<[^>]*object*\"?[^>]*>", $check_url)) ||
* (eregi("<[^>]*iframe*\"?[^>]*>", $check_url)) || (eregi("<[^>]*applet*\"?[^>]*>", $check_url)) ||
* (eregi("<[^>]*meta*\"?[^>]*>", $check_url)) || (eregi("<[^>]*style*\"?[^>]*>", $check_url)) ||
* (eregi("<[^>]*form*\"?[^>]*>", $check_url)) || (eregi("\([^>]*\"?[^)]*\)", $check_url)) ||
* (eregi("\"", $check_url))) { ban('GET patikra'); }
* }
* unset($check_url);*/

//Tvarkom $_SERVER globalus. Pagal php-fusion
$_SERVER['PHP_SELF'] = cleanurl($_SERVER['PHP_SELF']);
$_SERVER['QUERY_STRING'] = isset($_SERVER['QUERY_STRING']) ? cleanurl($_SERVER['QUERY_STRING']) : "";
$_SERVER['REQUEST_URI'] = isset($_SERVER['REQUEST_URI']) ? cleanurl($_SERVER['REQUEST_URI']) : "";
$PHP_SELF = cleanurl($_SERVER['PHP_SELF']);

/**
 * Adreso apsauga pagal php-fusion
 *
 * @param unknown_type $url
 * @return unknown
 */
function cleanurl($url) {
	$bad_entities = array('"', "'", "<", ">", "(", ")", '\\');
	$safe_entities = array("", "", "", "", "", "", "");
	$url = str_replace($bad_entities, $safe_entities, $url);
	return $url;
}

/// ASPAUGA - END

//sutvarkom nuorodas
if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
	$_GET = url_arr(cleanurl($_SERVER['QUERY_STRING']));
	$url = $_GET;
} else {
	$url = array();
}


/**
 * VartotojÅ³ lygiai
 * @return array
 */
unset($sql, $row);
$sql = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno` = 'vartotojai' AND `path`=0 ORDER BY `id` DESC");
//$levels = '';
if (mysql_num_rows($sql) > 0) {
	while ($row = mysql_fetch_assoc($sql)) {
		$sql2 = mysql_query1("SELECT * FROM  `" . LENTELES_PRIESAGA . "grupes` WHERE path!=0 and `path` like'" . $row['id'] . "%' ORDER BY `id` ASC");
		if (mysql_num_rows($sql2) > 0) {
			$subcat = '';
			while ($path = mysql_fetch_assoc($sql2)) {

				$subcat .= "->" . $path['pavadinimas'];
				$levels[$row['teises']] = array('pavadinimas' => $row['pavadinimas'], 'aprasymas' => $row['aprasymas'], 'pav' => $row['pav']);
				$levels[$path['teises']] = array('pavadinimas' => $row['pavadinimas'] . $subcat, 'aprasymas' => $row['aprasymas'], 'pav' => $row['pav']);


			}
		} else {
			$levels[$row['teises']] = array('pavadinimas' => $row['pavadinimas'], 'aprasymas' => $row['aprasymas'], 'pav' => $row['pav']);

		}


	}
}
$levels[1] = array('pavadinimas' => $lang['system']['admin'], 'aprasymas' => $lang['system']['admin'], 'pav' => 'admin.png');
//$levels[2] = array('pavadinimas' => $lang['system']['mod'], 'aprasymas' => $lang['system']['mod'], 'pav' => 'mod.png');
$levels[2] = array('pavadinimas' => $lang['system']['user'], 'aprasymas' => $lang['system']['user'], 'pav' => 'user.png');

$conf['level'] = $levels;
unset($levels, $sql, $row);

/**
 * Gaunam visus puslapius ir sukisam i masyva
 */
$sql = mysql_query1("SELECT SQL_CACHE * FROM `" . LENTELES_PRIESAGA . "page` ORDER BY `place` ASC");
while ($row = mysql_fetch_assoc($sql)) {
	$conf['puslapiai'][$row['file']] = array('id' => $row['id'], 'pavadinimas' => $row['pavadinimas'], 'file' => $row['file'], 'place' => (int)$row['place'], 'show' => $row['show']);
}

/**
 * Vartotojui atvaizduoti
 *
 * @param nickas $user
 * @param levelis $level
 * @return string
 */
function user($user, $id = 0, $level = 0, $extra = false) {
	//kadangi vëjai gaunas jeigu nikas su utf-8 simboliais, tai pm sistema pakeiciu
	global $lang, $conf;
	if ($user == '') {
		$user = $lang['system']['guest'];
	}
	if (isset($conf['puslapiai']['view_user.php']['id'])) {
		//Jeigu galiam ziuret vartotojo profili tada nickas paspaudziamas
		if ($level > 0 && $id > 0) {
			return (isset($conf['level'][$level]['pav']) ? '<img src="images/icons/' . $conf['level'][$level]['pav'] . '" border="0" class="middle" alt="" /> ' : '') . ' <a href="?id,' . $conf['puslapiai']['view_user.php']['id'] . ';m,' . (int)$id . '" title="' . input($user) . '<br />' . $extra . '">' . trimlink($user, 10) . '</a> ' . (isset($_SESSION['username']) && $user != $_SESSION['username'] && isset($conf['puslapiai']['pm.php']) ? "<a href=\"?id," . $conf['puslapiai']['pm.php']['id'] . ";n,1;u," . str_replace("=", "", base64_encode($user)) . "\"><img src=\"images/pm/mail.png\"  style=\"vertical-align:middle\" alt=\"pm\" border=\"0\" /></a>" : "");
		}
		if ($level == 0 || $id == 0) {
			return '<a href="?id,' . $conf['puslapiai']['view_user.php']['id'] . ';m,' . (int)$id . '" title="' . input($user) . '<br />' . $extra . '">' . trimlink($user, 10) . '</a> ' . (isset($_SESSION['username']) && $user != $_SESSION['username'] && isset($conf['puslapiai']['pm.php']) ? "<a href=\"?id," . $conf['puslapiai']['pm.php']['id'] . ";n,1;u," . str_replace("=", "", base64_encode($user)) . "\"><img src=\"images/pm/mail.png\" alt=\"pm\" border=\"0\" style=\"vertical-align:middle\" class=\"middle\" /></a>" : "");
		}

	} else {
		//Kitu atveju nickas nepaspaudziamas
		if ($level == 0 || $id == 0) {
			return '<a href="#" onclick="return false" title="' . input($user) . '<br /> ' . $extra . '">' . $user . '</a>';
		} else {
			return (isset($conf['level'][$level]['pav']) ? '<img src="images/icons/' . $conf['level'][$level]['pav'] . '" border="0" class="middle" /> ' : '') . ' <a href="#" onclick="return false" title="' . input($user) . '<br/>' . $extra . '">' . trimlink($user, 10) . '</a> ' . (isset($_SESSION['username']) && $user != $_SESSION['username'] && isset($conf['puslapiai']['pm.php']) ? "<a href=\"?id," . $conf['puslapiai']['pm.php']['id'] . ";n,1;u," . str_replace("=", "", base64_encode($user)) . "\"><img src=\"images/pm/mail.png\" alt=\"pm\" style=\"vertical-align:middle\" border=\"0\" /></a>" : "");
		}

	}

}


/**
 * MySQL uÅ¾klausoms
 *
 * @param sql string $query
 * @return resource
 */
function mysql_query1($query) {
	global $mysql_num, $prisijungimas_prie_mysql;
	//if (defined("LEVEL") && LEVEL > 20) {
	$mysql_num++;
	//}
	return mysql_query($query, $prisijungimas_prie_mysql);
}

/**
 * Nuskaitom turinÄ¯ iÅ¡ adreso
 *
 * @param string $url
 * @return string
 */
function http_get($url) {
	$request = fopen($url, "rb");
	$result = "";
	while (!feof($request)) {
		$result .= fread($request, 8192);
	}
	fclose($request);
	return $result;
}

/**
 * Gaunam informacijÄ… iÅ¡ XML
 *
 * @param string $xml
 * @param string $tag
 * @return string
 */
function get_tag_contents($xml, $tag) {
	$result = "";
	$s_tag = "<$tag>";
	$s_offs = strpos($xml, $s_tag);

	// If we found a starting offset, then look for the end-tag.
	if ($s_offs) {
		$e_tag = "</$tag>";
		$e_offs = strpos($xml, $e_tag, $s_offs);

		// If we have both tags, then dig out the contents.
		if ($e_offs) {
			$result = substr($xml, $s_offs + strlen($s_tag), $e_offs - $s_offs - strlen($e_tag) + 1);
		}
	}
	return $result;
}

/**
 * SuskaiÄiuojam kiek nurodytoje lentelÄ—je yra Ä¯raÅ¡Å³
 *
 * @param string $table
 * @param string $where
 * @param string $as
 * @return int
 */
function kiek($table, $where = '', $as = "viso") {
	$viso = mysql_fetch_assoc(mysql_query1("SELECT count(id) AS $as FROM `" . LENTELES_PRIESAGA . $table . "` " . $where));
	return (isset($viso[$as]) && $viso[$as] > 0 ? (int)$viso[$as] : (int)0);
}

/**
 * Puslapiavimui generuoti
 *
 * @param puslapis $start
 * @param limit $count
 * @param viso $total
 * @param po kiek $range
 * @return unknown
 */
function puslapiai($start, $count, $total, $range = 0) {
	$res = "";
	$pg_cnt = ceil($total / $count);
	if ($pg_cnt > 1) {
		$idx_back = $start - $count;
		$idx_next = $start + $count;
		$cur_page = ceil(($start + 1) / $count);
		$res .= "";
		$res .= "<center>\n";
		if ($idx_back >= 0) {
			if ($cur_page > ($range + 1))
				$res .= "<a href='" . url("p,0") . "'>[<u>Â«Â«</u>]</a>\n";
			$res .= "<a href='" . url("p,$idx_back") . "'>[<u>Â«</u>]</a>\n";
		}
		$idx_fst = max($cur_page - $range, 1);
		$idx_lst = min($cur_page + $range, $pg_cnt);
		if ($range == 0) {
			$idx_fst = 1;
			$idx_lst = $pg_cnt;
		}
		for ($i = $idx_fst; $i <= $idx_lst; $i++) {
			$offset_page = ($i - 1) * $count;
			if ($i == $cur_page) {
				$res .= "<font color='red'>[<u><b>$i</b></u>]</font>\n";
			} else {
				$res .= "<a href='" . url("p,$offset_page") . "'>[<u>$i</u>]</a>\n";
			}
		}
		if ($idx_next < $total) {
			$res .= "<a href='" . url("p,$idx_next") . "'>[<u>Â»</u>]</a>\n";
			if ($cur_page < ($pg_cnt - $range)) {
				$res .= "<a href='" . url("p," . ($pg_cnt - 1) * $count . "") . "'>[<u>Â»Â»</u>]</a>\n";
			}
		}
		$res .= "</center>\n";
	}
	return $res;
}

/**
 * Tikrina ar kintamasis teigiamas skaiÄius
 *
 * @param SkaiÄius $value
 * @return 1 arba NULL
 */
function isNum($value) {
	return preg_match("/^[0-9]+$/", $value);
}

/**
 * GraÅ¾ina lankytojo IP
 *
 * @return string
 */
function getip() {
	if (getenv('HTTP_X_FORWARDED_FOR')) {
		$ip2 = '';
		$ip3 = '';
		$ip = $_SERVER['REMOTE_ADDR'];
		if (preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", getenv('HTTP_X_FORWARDED_FOR'), $ip3)) {
			$ip2 = array('/^0\./', '/^127\.0\.0\.1/', '/^192\.168\..*/', '/^172\.16\..*/', '/^10..*/', '/^224..*/', '/^240..*/');
			$ip = preg_replace($ip2, $ip, $ip3[1]);
		}
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	if ($ip == "") {
		$ip = "x.x.x.x";
	}
	return $ip;
}

/**
 * Sugeneruojam atsitiktinÄ™ frazÄ™
 *
 * @param frazÄ—s ilgis $i
 * @return string
 */
function random_name($i = 10) {
	$chars = "abcdefghijkmnopqrstuvwxyz023456789";
	srand((double)microtime() * 1000000);
	$name = '';
	while ($i >= 0) {
		$num = rand() % 33;
		$tmp = substr($chars, $num, 1);
		$name = $name . $tmp;
		$i--;
	}
	return $name;
}

/**
 * Sutvarko SQL uÅ¾klausÄ…
 *
 * @param string $sql
 * @return escaped string
 */
function escape($sql) {
	// Stripslashes
	if (get_magic_quotes_gpc()) {
		$sql = stripslashes($sql);
	}
	//Jei ne skaiÄius
	if (!isnum($sql) || $sql[0] == '0') {
		if (!isnum($sql)) {
			$sql = "'" . mysql_real_escape_string($sql) . "'";
		}
	}
	return $sql;
}

/**
 * Sutvarkom stringÄ… saugiam atvaizdavimui
 * sito reikia jei nori grainti i inputa informacija.
 * daznai tai buna su visokiais \\\'? ir pan
 *
 * @param string $s
 * @return formated string
 */
function input($s) {
	$s = htmlspecialchars($s, ENT_QUOTES, "UTF-8");
	return $s;
}

/////////////////////////////////////////////////////////
//////// URL APDOROJIMUI
////////////////////////////////////////////////////////

/**
 * IÅ¡ QUERY_STRING padarom masyvÄ…
 *
 * @param QUERY_STRING $params
 * @return array
 */
function url_arr($params) {
	$str2 = array();
	if (!isset($params))
		$params = $_SERVER['QUERY_STRING'];

	if (strrchr($params, '&'))
		$params = explode("&", $params); //Jeigu tai paprastas GET
	else
		$params = explode(";", $params); //Kitu atveju tai TVS ";," tipo GET

	if (isset($params) && is_array($params) && count($params) > 0) {
		foreach ($params as $key => $value) {
			if (strrchr($value, '='))
				$str1 = explode("=", $value);
			else
				$str1 = explode(",", $value);
			if (isset($str1[1]))
				$str2[$str1[0]] = $str1[1];
		}
	}
	return $str2;
}

/**
 * IÅ¡ masyvo padarom Ä¯ QUERY_STRING
 * dekui: "sliekas_kanibalas" uz patobulinima
 *
 * @param array $params
 * @param string $str
 * @return string
 */
function arr_url($params, $str = '') {
	$strs = array();
	foreach ($params as $key => $value) {
		if (!empty($value))
			$strs[] = $key . ',' . rawurlencode($value);
	}
	$str .= implode(";", $strs);
	return ($str);
}

/**
 * Papildo nuorodÄ… naujais kintamaisiais
 * Esant reikalui atnaujina esamus
 *
 * @param string $str
 * @param string $link
 * @return formated string
 */
function url($str, $link = '') {
	if (!empty($_SERVER['QUERY_STRING'])) {
		$url = url_arr($_SERVER['QUERY_STRING']);
	} else {
		$url = array();
	}
	if (!is_array($str)) {
		$str = url_arr($str);
	}
	return $link . "?" . arr_url(array_merge($url, $str));
}
/////////////////////////////////////////////////////////////
///////// URL PABAIGA
/////////////////////////////////////////////////////////////

/**
 * NarÅ¡yklÄ—s peradresavimas
 *
 * @param adresas $location
 * @param header/meta/script $type
 */
function redirect($location, $type = "header") {
	if ($type == "header") {
		header("Location: " . $location);
		exit;
	}
	if ($type == "meta") {
		echo "<meta http-equiv='Refresh' content='1;url=$location'>";
	} else {
		echo "<script type='text/javascript'>document.location.href='" . $location . "'</script>\n";
	}
}

/**
 * GraÅ¾ina amÅ¾iÅ³, nurodÅ¾ius datÄ…
 *
 * @param 0000-00-00 $data
 * @return int
 */
function amzius($data) {
	if (!isset($data) || $data == '' || $data == '0000-00-00') {
		return "- ";
	} else {
		$data = explode("-", $data);
		$amzius = time() - mktime(0, 0, 0, $data['1'], $data['2'], $data['0']);
		$amzius = date("Y", $amzius) - 1970;
		return $amzius;
	}
}

/**
 * UÅ¾koduoja problematiÅ¡kus simbolius
 *
 * @param tekstas/string $text
 * @param true/false $striptags
 * @return string
 */
function descript($text, $striptags = true) {
	// Convert problematic ascii characters to their true values
	$search = array("40", "41", "58", "65", "66", "67", "68", "69", "70", "71", "72", "73", "74", "75", "76", "77", "78", "79", "80", "81", "82", "83", "84", "85", "86", "87", "88", "89", "90", "97", "98", "99", "100", "101", "102", "103", "104", "105", "106", "107", "108", "109", "110", "111", "112", "113", "114", "115", "116", "117", "118", "119", "120", "121", "122", "239");
	$replace = array("(", ")", ":", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "");
	$entities = count($search);
	for ($i = 0; $i < $entities; $i++)
		$text = preg_replace("#(&\#)(0*" . $search[$i] . "+);*#si", $replace[$i], $text);
	$text = str_replace(chr(32) . chr(32), "&nbsp;", $text);
	$text = str_replace(chr(9), "&nbsp; &nbsp; &nbsp; &nbsp;", $text);
	// the following is based on code from bitflux (http://blog.bitflux.ch/wiki/)
	// Kill hexadecimal characters completely
	$text = preg_replace('#(&\#x)([0-9A-F]+);*#si', "", $text);
	// remove any attribute starting with "on" or xmlns
	$text = preg_replace('#(<[^>]+[\\"\'\s])(onmouseover|onmousedown|onmouseup|onmouseout|onmousemove|onclick|ondblclick|onload|xmlns)[^>]*>#iU', ">", $text);
	// remove javascript: and vbscript: protocol
	$text = preg_replace('#([a-z]*)=([\`\'\"]*)script:#iU', '$1=$2nojscript...', $text);
	$text = preg_replace('#([a-z]*)=([\`\'\"]*)javascript:#iU', '$1=$2nojavascript...', $text);
	$text = preg_replace('#([a-z]*)=([\'\"]*)vbscript:#iU', '$1=$2novbscript...', $text);
	//<span style="width: expression(alert('Ping!'));"></span> (only affects ie...)
	$text = preg_replace('#(<[^>]+)style=([\`\'\"]*).*expression\([^>]*>#iU', "$1>", $text);
	$text = preg_replace('#(<[^>]+)style=([\`\'\"]*).*behaviour\([^>]*>#iU', "$1>", $text);
	if ($striptags) {
		do {
			$thistext = $text;
			$text = preg_replace('#</*(applet|meta|xml|blink|link|style|script|iframe|frame|frameset|ilayer|layer|bgsound|title|base)[^>]*>#i', "", $text);
		} while ($thistext != $text);
	}
	return $text;
}

/**
 * Patikrina ar tai tikrai paveiksliukas
 *
 * @param adresas $img
 * @return string
 */
function isImage1($img) {
	//$img = $matches[1].str_replace(array("?","&","="),"",$matches[3]).$matches[4];
	//$img = $matches[1].$matches[3].$matches[4];
	if (@getimagesize($img)) {
		$res = "<img src='" . $img . "' style='border:0px;'>";
	} else {
		$res = "[img]" . $img . "[/img]";
	}
	return $res;
}

/**
 * SulauÅ¾o Å¾odÄ¯ jei jis per ilgas
 * lauÅ¾o net jei Å¾odis turi tarpus
 *
 * @param tekstas $text
 * @param ilgis $chars
 * @return string
 */
function wrap1($text, $chars = 25) {
	$text = wordwrap($text, $chars, "<br />\n", 1);
	return $text;
}

/**
 * SulauÅ¾o per ilgus Å¾odÅ¾ius
 * tik jei jis yra be tarpÅ³
 *
 * @param tekstas $string
 * @param ilgis $width
 * @param simbolis $break
 * @return string
 */
function wrap($string, $width, $break = "\n") {
	$string = preg_replace('/([^\s]{' . $width . '})/i', "$1$break", $string);
	return $string;
}

//tikrinam ar kintamasis sveikas skaicius ar normalus zodis
function tikrinam($txt) {
	return (preg_match("/^[0-9a-zA-Z]+$/", $txt));
}

//bano galiojimo laikas. Rodo data iki kada +30 dienu
//echo "Galioja iki: ".galioja('12', '19', '2007');
//grazina: Galioja iki: 2008-01-17
function galioja($menuo, $diena, $metai, $kiek_galioja = 30) {
	$nuo = (int)(mktime(0, 0, 0, $menuo, $diena, $metai) - time(void) / 86400);
	$liko = $nuo + ($kiek_galioja * 24 * 60 * 60);
	return date('Y-m-d', $liko);
}

function liko($diena, $menuo, $metai) {
	$until = mktime(0, 0, 0, $menuo, $diena, $metai);
	$now = time();
	$difference = $until - $now;
	$days = floor($difference / 86400);
	$difference = $difference - ($days * 86400);
	$hours = floor($difference / 3600);
	$difference = $difference - ($hours * 3600);
	$minutes = floor($difference / 60);
	$difference = $difference - ($minutes * 60);
	$seconds = $difference;
	return (int)$days + 1;
}

//sutvarko url iki linko
function linkas($str) {
	$str = strtolower(strip_tags($str));
	//return preg_replace_callback("#([\n ])([a-z]+?)://([a-z0-9\-\.,\?!%\*_\#:;~\\&$@\/=\+]+)#si", "linkai", $str);
	return preg_replace("`((http)+(s)?:(//)|(www\.))((\w|\.|\-|_)+)(/)?(\S+)?`i", "<a href=\"http://\\5\\6\" title=\"\\0\" target=\"_blank\" class=\"link\" >\\5\\6</a>", $str);
}

// apvalinti:
// suapvalina nurodyta skaiÄiu
// (iÅ¡esmÄ—s veikia kaip ceil() tik leidÅ¾ia nurodyti deÅ¡imtainÄ™)
function apvalinti($sk, $kiek = 2) {
	if ($kiek < 0) {
		$kiek = 0;
	}
	$mult = pow(10, $kiek);
	return ceil($sk * $mult) / $mult;
}

function naujas($data, $nick = null) {
	if (isset($_SESSION['lankesi'])) {
		return (($data > $_SESSION['lankesi']) ? '<img src="images/icons/new.png" onload="new Effect.Pulsate(this)" alt="New" border="0" style="vertical-align: middle;" />' : '');
	} else {
		return '';
	}
}

//Pries kiek laiko
function kada($ts) {
	global $lang;
	if ($ts == '' || $ts == "0000-00-00 00:00:00") {
		return '';
	}
	$mins = floor((strtotime(date("Y-m-d H:i:s")) - strtotime($ts)) / 60);
	$hours = floor($mins / 60);
	$mins -= $hours * 60;
	$days = floor($hours / 24);
	$hours -= $days * 24;
	$weeks = floor($days / 7);
	$days -= $weeks * 7;
	$month = floor($weeks / 4);
	$days -= $month * 4;
	$year = floor($month / 12);
	$days -= $year * 12;
	if ($year)
		return $year . " " . ($year > 1 ? "{$lang['system']['years']}" : "{$lang['system']['year']}") . " " . $lang['system']['ago'];
	if ($month)
		return $month . " " . ($month > 1 ? "{$lang['system']['months']}" : "{$lang['system']['month']}") . " " . $lang['system']['ago'];
	if ($weeks)
		return $weeks . " " . ($weeks > 1 ? "{$lang['system']['weeks']}" : "{$lang['system']['week']}") . " " . $lang['system']['ago'];
	if ($days)
		return $days . " " . ($days > 1 ? "{$lang['system']['days']}" : "{$lang['system']['day']}") . " " . $lang['system']['ago'];
	if ($hours)
		return $hours . " " . ($hours > 1 ? "{$lang['system']['hours']}" : "{$lang['system']['hour']}") . " " . $lang['system']['ago'];
	if ($mins)
		return $mins . " " . ($mins > 1 ? "{$lang['system']['minutes']}" : "{$lang['system']['minute']}") . " " . $lang['system']['ago'];
	return "&lt; 1 {$lang['system']['minute']} {$lang['system']['ago']}";
}

//vercia baitus i zmoniu kalba
function baitai($size, $digits = 2, $dir = false) {
	$kb = 1024;
	$mb = 1024 * $kb;
	$gb = 1024 * $mb;
	$tb = 1024 * $gb;
	if (($size == 0) && ($dir)) {
		return " Nulis";
	} elseif ($size < $kb) {
		return $size . " Baitai";
	} elseif ($size < $mb) {
		return round($size / $kb, $digits) . " Kb";
	} elseif ($size < $gb) {
		return round($size / $mb, $digits) . " Mb";
	} elseif ($size < $tb) {
		return round($size / $gb, $digits) . " Gb";
	} else {
		return round($size / $tb, $digits) . " Tb";
	}
}

// Trim a line of text to a preferred length
function trimlink($text, $length) {
	$dec = array("\"", "'", "\\", '\"', "\'", "<", ">");
	$enc = array("&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;");
	$text = str_replace($enc, $dec, $text);
	if (strlen($text) > $length)
		$text = utf8_substr($text, 0, ($length - 3)) . "...";
	$text = str_replace($dec, $enc, $text);
	return $text;
}

//Paskaiciuojam procentus
function procentai($reikia, $yra, $zenklas = false) {
	$return = (int)round((100 * $yra) / $reikia);
	if ($return > 100 && $zenklas) {
		$return = "<img src='images/icons/accept.png' class='middle' alt='100%' title='100%' borders='0' />";
	} elseif ($return > 0 && $zenklas) {
		$return = "<img src='images/icons/cross.png' class='middle' alt='" . $return . "%' title='" . $reikia . "/" . $yra . " - " . $return . "%' borders='0' />";
	}
	return $return;
}

//Insert SQL - supaprastina duomenÅ³ Ä¯terpimÄ…, paduodam lentlÄ—s pavadinimÄ… ir kitu argumentu asociatyvÅ³ masyvÄ…
function insert($table, $array) {
	return 'INSERT INTO `' . LENTELES_PRIESAGA . $table . '` (' . implode(', ', array_keys($array)) . ') VALUES (' . implode(', ', array_map('escape', $array)) . ')';
}

//echo "<img src='".pic('http://img95.imageshack.us/img95/6290/web1160860226jy1.jpg')."'>";
function pic($off_site, $size = 150, $url = 'images/nuorodu/', $sub = 'url') {
	$pic_name = md5($off_site);
	$pic_name = $url . $sub . "_" . $pic_name . ".jpg";
	if (!file_exists($pic_name) || (time() - filemtime($pic_name)) > 32400) { //9 valandos senumo
		clearstatcache();
		@unlink($pic_name);
		if (pic1($off_site, $size, $url, $sub)) {
			return $pic_name;
		} else {
			clearstatcache();
			return $url . "noimage.jpg";
		}
	} else {
		clearstatcache();
		return $pic_name;
	}
}

function pic1($off_site, $size = 150, $url = 'images/nuorodu/', $sub = 'url') {
	$fp = @fopen($off_site, 'rb');
	$buf = '';
	if ($fp) {
		stream_set_blocking($fp, true);
		stream_set_timeout($fp, 2);
		while (!feof($fp)) {
			$buf .= fgets($fp, 4096);
		}

		$data = $buf;

		//set new height
		$src = @imagecreatefromstring($data);
		if (empty($src)) {
			return false;
		}
		$width = @imagesx($src);
		$height = @imagesy($src);
		$aspect_ratio = $width / $height;

		//start resizing
		if ($width <= $size) {
			$new_w = $width;
			$new_h = $height;
		} else {
			$new_w = $size;
			$new_h = @abs($new_w / $aspect_ratio);
		}

		$img = @imagecreatetruecolor($new_w, $new_h);

		//output image
		@imagecopyresampled($img, $src, 0, 0, 0, 0, $new_w, $new_h, $width, $height);

		$file = $url . $sub . "_" . md5($off_site) . ".jpg";

		// determine image type and send it to the browser
		@imagejpeg($img, $file, 90);
		@imagedestroy($img);
		unset($buf);
		sleep(2);
	}
}

/**
 * Sulietuvinimas mėnesio
 * echo menesis(12); //Gruodis
 *
 * @param INT $men
 * @return string
 */
function menesis($men) {
	if (is_int($men)) {
		$ieskom = array(12, 01, 02, 03, 04, 05, 06, 07, 08, 09, 10, 11);
	} else {
		$ieskom = array("December", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November");
	}
	$keiciam = array("Gruodis", "Sausis", "Vasaris", "Kovas", "Balandis", "GeguÅ¾Ä—", "BirÅ¾elis", "Liepa", "RugpjÅ«tis", "RugsÄ—jis", "Spalis", "Lapkritis");
	return str_replace($ieskom, $keiciam, $men);
}

// grÄ…Å¾ina failus iÅ¡ nurodytos direktorijos ir sukiÅ¡a Ä¯ masyvÄ…
function getFiles($path, $denny = '.htaccess|index.php|index.html|index.htm|index.php3|conf.php') {
	$denny = explode('|', $denny);
	$path = urldecode($path);
	$files = array();
	$fileNames = array();
	$i = 0;
	//print_r(basename($path));
	//&& !in_array(basename($path), $denny)
	if (is_dir($path)) {
		if ($dh = opendir($path)) {
			while (($file = readdir($dh)) !== false) {
				if (!in_array($file, $denny)) {
					if (($file == ".") || ($file == ".."))
						continue;
					$fullpath = $path . "/" . $file;
					//$fkey = strtolower($file);
					$fkey = $file;
					while (array_key_exists($fkey, $fileNames))
						$fkey .= " ";
					$a = stat($fullpath);
					$files[$fkey]['size'] = $a['size'];
					if ($a['size'] == 0)
						$files[$fkey]['sizetext'] = "-";
					else
						if ($a['size'] > 1024 && $a['size'] <= 1024 * 1024)
							$files[$fkey]['sizetext'] = (ceil($a['size'] / 1024 * 100) / 100) . " K"; //patvarkom failo dydziu atvaizdavima
						else
							if ($a['size'] > 1024 * 1024)
								$files[$fkey]['sizetext'] = (ceil($a['size'] / (1024 * 1024) * 100) / 100) . " Mb";
							else
								$files[$fkey]['sizetext'] = $a['size'] . " bytes";
					$files[$fkey]['name'] = $file;
					$e = strip_ext($file); // $e failo pletinys - pvz: .gif
					$files[$fkey]['type'] = filetype($fullpath); // failo tipas, dir, file ir pan
					$k = $e . $file; // kad butu lengvau rusiuoti;
					$fileNames[$i++] = $k;
				}
			}
			closedir($dh);
		} else
			die(klaida($lang['system']['error'], "{$lang['system']['cantread']}:  $path"));
	} else
		die(klaida($lang['system']['error'], "{$lang['system']['notdir']}:  $path"));
	sort($fileNames, SORT_STRING); // surusiuojam
	$sortedFiles = array();
	$i = 0;
	foreach ($fileNames as $f) {
		$f = utf8_substr($f, 4, strlen($f) - 4); //sutvarko failo pletinius
		if ($files[$f]['name'] != '')
			$sortedFiles[$i++] = $files[$f];
	}
	return $sortedFiles;
}

//Grazina direktorijÅ³ saraÅ¡Ä…
function getDirs($dir) {
	if ($handle = opendir($dir)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && $file != ".svn" && is_dir($dir . $file)) {
				$return[$file] = $file;
			}
		}
		closedir($handle);
	}
	return $return;
}

// grazina failo pletini
function strip_ext($name, $ext = '') {
	$ext = utf8_substr($name, strlen($ext) - 4, 4);
	if (strpos($ext, '.') === false) { // jeigu tai folderis
		return "    "; // grazinam biski tarpu kad rusiavimas butu ciki, susirusiuoja - folderiai virsuje
	}
	return $ext; // jei tai failas grazinam jo pletini
}

function admin_login() {
	global $_SERVER, $admin_name, $admin_pass, $lang;
	if (@$_SERVER['PHP_AUTH_USER'] != $admin_name || @$_SERVER['PHP_AUTH_PW'] != $admin_pass) {
		header("WWW-Authenticate: Basic realm='AdminAccess'");
		header("HTTP/1.0 401 Unauthorized");
		header("status: 401 Unauthorized");
		mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "logai` (`action` ,`time` ,`ip`) VALUES (" . escape("ADMIN pultas - Klaida loginantis: User: " . (isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : "N/A") . " Pass: " . (isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : "N/A")) . ",NOW(),INET_ATON(" . escape($_SERVER['REMOTE_ADDR']) . "));");
		die(klaida("{$lang['system']['forbidden']}!", "{$lang['system']['notadmin']}"));
	} else {
		unset($admin_name, $admin_pass);
		return true;
	}
}

function get_user_os() {
	global $global_info, $HTTP_USER_AGENT, $HTTP_SERVER_VARS;
	if (!empty($global_info['user_os'])) {
		return $global_info['user_os'];
	}
	if (!empty($HTTP_SERVER_VARS['HTTP_USER_AGENT'])) {
		$HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];
	} elseif (getenv("HTTP_USER_AGENT")) {
		$HTTP_USER_AGENT = getenv("HTTP_USER_AGENT");
	} elseif (empty($HTTP_USER_AGENT)) {
		$HTTP_USER_AGENT = "";
	}
	if (eregi("Win", $HTTP_USER_AGENT)) {
		$global_info['user_os'] = "WIN";
	} elseif (eregi("Mac", $HTTP_USER_AGENT)) {
		$global_info['user_os'] = "MAC";
	} elseif (eregi("UNIX", $HTTP_USER_AGENT)) {
		$global_info['user_os'] = "Linux";
	} else {
		$global_info['user_os'] = "OTHER";
	}
	return $global_info['user_os'];
}
function get_browser_info() {
	global $global_info, $HTTP_USER_AGENT, $HTTP_SERVER_VARS;
	if (!empty($global_info['browser_agent'])) {
		return $global_info['browser_agent'];
	}
	if (!empty($HTTP_SERVER_VARS['HTTP_USER_AGENT'])) {
		$HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];
	} elseif (getenv("HTTP_USER_AGENT")) {
		$HTTP_USER_AGENT = getenv("HTTP_USER_AGENT");
	} elseif (empty($HTTP_USER_AGENT)) {
		$HTTP_USER_AGENT = "";
	}
	if (eregi("MSIE ([0-9].[0-9]{1,2})", $HTTP_USER_AGENT, $regs)) {
		$global_info['browser_agent'] = "MSIE";
		$global_info['browser_version'] = $regs[1];
	} elseif (eregi("Mozilla/([0-9].[0-9]{1,2})", $HTTP_USER_AGENT, $regs)) {
		$global_info['browser_agent'] = "MOZILLA";
		$global_info['browser_version'] = $regs[1];
	} elseif (eregi("Opera(/| )([0-9].[0-9]{1,2})", $HTTP_USER_AGENT, $regs)) {
		$global_info['browser_agent'] = "OPERA";
		$global_info['browser_version'] = $regs[2];
	} else {
		$global_info['browser_agent'] = "OTHER";
		$global_info['browser_version'] = 0;
	}
	return $global_info['browser_agent'];
}

/**
 * GraÅ¾ina patvirtinimo kodÄ…
 *
 * @return HTML
 */
function kodas() {
	global $lang;
	return "<img src=\"priedai/human.php\" style=\"cursor: pointer;\" onclick='this.src=\"priedai/human.php?\"+Math.random()' id=\"captcha\" alt=\"code\" title=\"{$lang['system']['refresh']}\" />";
}

/** GraÅ¾ina versijos numerÄ¯ */
function versija($failas = false) {
	if (!$failas) {
		$svnid = '$Rev$';
		$scid = utf8_substr($svnid, 6);
		return apvalinti(intval(utf8_substr($scid, 0, strlen($scid) - 2))/1000,2);
	} else {
		//Nuskaityti faila ir paimti su regexp versijos numeri
		return '$Rev$';
	}
}

/**
 * Debuginimui
 */
function debug() {
	global $lang;
	$array = debug_backtrace();
	klaida($lang['system']['debuger'], '<pre>' . print_r(array_values($array), true) . '</pre>');
}
function sendcompressedcontent($content) {
	header("Content-Encoding: gzip");
	return gzencode($content, 9);
}

// compress HTML BLOGAS DUOMENŲ SUSPAUDIMO BŪDAS
//ob_start('sendcompressedcontent');
/**
 * Editorius skirtas vaizdÅ¾iai redaguoti html
 *
 * @example echo editorius('tiny_mce','mini');
 * @example echo editorius('spaw','standartinis',array('Glaustai'=>'Glaustai','Placiau'=>'PlaÄiau'),array('Glaustai'=>'Naujiena glaustai','Placiau'=>'Naujiena plaÄiau'));
 * @param string $tipas
 * @param string $dydis
 * @param string $id
 * @param string $value
 * @return string
 */
function editorius($tipas = 'rte', $dydis = 'standartinis', $id = false, $value = '') {
	global $conf;
	if (!$id) {
		$id = md5(uniqid());
	}

//$adr="http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/";
$adr="../../../";
$stilius=$adr.'stiliai/' . input(strip_tags($conf['Stilius'])) . '/default.css';
	/*   //Editorius - SPAW2
	if ($tipas === 'spaw' && is_file('javascript/htmlarea/spaw2/spaw.inc.php')) {
	if ($dydis === 'standartinis') {
	include ("javascript/htmlarea/spaw2/spaw.inc.php");
	if (is_array($id)) {
	$spaw = new SpawEditor(array_shift(array_keys($id)), (isset($value) ? $value[array_shift
	(array_keys($id))] : ''));
	} else
	$spaw = new SpawEditor($id);
	$new_dir = array('dir' => 'images/naujienos/', 'caption' => 'Paveiksliukai',
	'params' => array('allowed_filetypes' => array('images')));
	$spaw->setConfigValueElement('PG_SPAWFM_DIRECTORIES', sizeof($spaw->
	getConfigValue('PG_SPAWFM_DIRECTORIES')), $new_dir);
	$new_dir = array('dir' => 'siuntiniai/', 'caption' => 'Siuntiniai', 'params' =>
	array('allowed_filetypes' => array('documents', 'audio', 'video', 'archives',
	'flash', 'images')));
	$spaw->setConfigValueElement('PG_SPAWFM_DIRECTORIES', sizeof($spaw->
	getConfigValue('PG_SPAWFM_DIRECTORIES')), $new_dir);
	SpawConfig::setStaticConfigValue('default_height', '200px');
	SpawConfig::setStaticConfigItem('base_href', adresas(), SPAW_CFG_TRANSFER_JS);
	if (is_array($id)) {
	foreach ($id as $key => $val) {
	$spaw->addPage(new SpawEditorPage($key, $val, (isset($value[$key]) ? $value[$key] :
	'')));
	}
	}
	$spaw->setConfigValueElement('PG_SPAWFM_SETTINGS', 'allow_upload', true);
	$spaw->setConfigValueElement('PG_SPAWFM_SETTINGS', 'allow_modify', false);
	return $spaw->getHtml();
	} else {
	include ("javascript/htmlarea/spaw2/spaw.inc.php");
	SpawConfig::setStaticConfigItem('base_href', adresas(), SPAW_CFG_TRANSFER_JS);
	$new_dir = array('dir' => 'images/naujienos/', 'caption' => 'Paveiksliukai',
	'params' => array('allowed_filetypes' => array('images'), 'allow_upload' => true,
	'default_dir' => true, 'max_upload_filesize' => 1048576));
	$irankiai = array('format' => 'format_mini', 'edit' => 'edit', 'insert' =>
	'insert', );
	SpawConfig::setStaticConfigItem('toolbarset_mini', $irankiai);
	// $spaw = new SPAW_Wysiwyg($id /*name*/ //, $value /*value*/, 'lt' /*language*/,
	//   'mini' /*toolbar mode*/, '' /*theme*/, '100%' /*width*/, '150px' /*height*/ );
	/* $spaw->setConfigValueElement('PG_SPAWFM_SETTINGS', 'allow_upload', false);
	$spaw->setConfigValueElement('PG_SPAWFM_SETTINGS', 'allow_modify', false);
	$spaw->setConfigValueElement('PG_SPAWFM_DIRECTORIES', sizeof($spaw->
	getConfigValue('PG_SPAWFM_DIRECTORIES')), $new_dir);
	return $spaw->getHtml();
	}
	}

		elseif($tipas === 'jquery'){*/
		if($tipas=='dsrte'){
	require_once 'javascript/htmlarea/dsrte/lib/dsrte.php';

	// compress HTML
	$return = '<link rel="stylesheet" href="javascript/htmlarea/dsrte/lib/dsrte.css" type="text/css" />
       <script type="text/javascript"><!--
        // keyboard shortcut keys for current language
        var ctrlb="b",ctrli="i",ctrlu="u";
        //-->
    </script>';
	if (is_array($id)) {
		foreach ($id as $key => $val) {
			$dsrte = new dsRTE($key);
			$return .= $dsrte->getScripts() . $dsrte->getHTML(isset($value[$key]) ? $value[$key] : $val);
		}
	} else {
		$dsrte = new dsRTE($id);
		$return .= $dsrte->getScripts() . $dsrte->getHTML($value ? $value : "");
	}
	}else{
	$return=<<<HTML
	<link type="text/css" rel="stylesheet" href="javascript/htmlarea/jquery.rte1_2/jquery.rte.css" />
	<script type="text/javascript" src="javascript/htmlarea/jquery.rte1_2/jquery.rte.js"></script>
<script type="text/javascript" src="javascript/htmlarea/jquery.rte1_2/jquery.rte.tb.js"></script>
<script type="text/javascript" src="javascript/htmlarea/jquery.rte1_2/jquery.ocupload-1.1.4.js"></script>
HTML;
if (is_array($id)) {
		foreach ($id as $key => $val) {
			//$dsrte = new dsRTE($key);
			//$return .= $dsrte->getScripts() . $dsrte->getHTML(isset($value[$key]) ? $value[$key] : $val);
			$return.=<<<HTML
			<script type="text/javascript">
$(document).ready(function() {
      $('.{$key}').rte({
	            baseurl:'$adr', 
	            css: ['{$stilius}'],
				height: 150,
                controls_rte: rte_toolbar,
                controls_html: html_toolbar
        });     
 

       //arr is array of RTEm you can use api as you want.
 

});
 
</script>
<textarea name="{$key}" style="width:100%" class="{$key}" method="post" action="#">{$value[$key]}</textarea>
HTML;
		}
	} else {
	$return.=<<<HTML
			<script type="text/javascript">
$(document).ready(function() {
      $('.{$id}').rte({
	            baseurl:'$adr', 
                css: ['{$stilius}'],
                height: 150,
				frame_class: 'frameBody',
                controls_rte: rte_toolbar,
                controls_html: html_toolbar
        });     
 

       //arr is array of RTEm you can use api as you want.
 

});
 
</script>
<textarea name="{$id}"  style="width:100%" class="{$id}" method="post" action="#">{$value}</textarea>
HTML;
		//$dsrte = new dsRTE($id);
		//$return .= $dsrte->getScripts() . $dsrte->getHTML($value ? $value : "");
	}	
	
}
	
	return $return;
	/*}
	//Editorius - Tiny_MCE
	elseif (is_file('javascript/htmlarea/tiny_mce/tiny_mce.js')) {
	if ($dydis === 'standartinis') {
	$return = '<script language="javascript" type="text/javascript" src="javascript/htmlarea/tiny_mce/tiny_mce.js"></script><script language="JavaScript">tinyMCE.init({mode:"exact",language:"lt",elements:"' . (is_array
	($id) ? implode(',', array_keys($id)) : $id) .
	'",theme:"advanced",theme_advanced_resizing:true,theme_advanced_resize_horizontal:false,theme_advanced_path_location:"bottom",force_p_newlines:"false",height:"350",plugin_insertdate_dateFormat:"%Y-%m-%d",plugin_insertdate_timeFormat:"%H:%M:%S",plugins:"inlinepopups,emotions,advimage,media,table,insertdatetime,style",content_css:"stiliai/' .
	$conf['Stilius'] . '/default.css",theme_advanced_buttons1:"bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright, justifyfull,bullist,numlist,undo,redo,link,unlink",theme_advanced_buttons2_add:"separator,insertdate,separator,forecolor,backcolor,separator,emotions,media",theme_advanced_buttons3:"tablecontrols",theme_advanced_buttons4:"styleselect,fontsizeselect,formatselect",theme_advanced_toolbar_location:"top",theme_advanced_toolbar_align:"left",theme_advanced_statusbar_location:"bottom",invalid_elements:"script,object,applet,iframe",extended_valid_elements:"a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",theme_advanced_disable:"help",apply_source_formatting:true,tab_focus:":prev,:next",entity_encoding:"raw"});</script>';
	if (is_array($id)) {
	foreach ($id as $key => $val) {
	$return .= '<textarea rows="3" id="' . $key . '" name="' . $key .
	'" style="width:100%">' . (isset($value[$key]) ? $value[$key] : $val) .
	'</textarea>';
	}
	} else {
	$return .= '<textarea rows="3" id="' . $id . '" name="' . $id .
	'" style="width:100%">' . $value . '</textarea>';
	}
	return $return;
	} else {
	$return = '<script language="javascript" type="text/javascript" src="javascript/htmlarea/tiny_mce/tiny_mce.js"></script><script language="JavaScript">tinyMCE.init({mode : "exact",language : "lt",elements : "' . (is_array
	($id) ? implode(',', array_keys($id)) : $id) .
	'",theme : "simple",apply_source_formatting : true,tab_focus : ":prev,:next",entity_encoding: "raw"});</script>';

	if (is_array($id)) {
	foreach ($id as $key => $val) {
	$return .= '<textarea rows="3" id="' . $key . '" name="' . $value[$key] .
	'" style="width:100%">' . (isset($value[$key]) ? $value[$key] : $val) .
	'</textarea>';
	}
	} else {
	$return .= '<textarea rows="3" id="' . $id . '" name="' .$value[$key].
	'" style="width:100%">' . $value . '</textarea>';
	}
	return $return;
	}
	}*/
}

if (!function_exists('scandir')) {
	function scandir($directory, $sorting_order = 0) {
		$dh = opendir($directory);
		while (false !== ($filename = readdir($dh))) {
			$files[] = $filename;
		}
		if ($sorting_order == 0) {
			sort($files);
		} else {
			rsort($files);
		}
		return ($files);
	}
}

?>
