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
define('ROOTAS', dirname(realpath(__file__)) . '/../');
if (preg_match('%/\*\*/|SERVER|SELECT|UNION|DELETE|UPDATE|INSERT%i', $_SERVER['QUERY_STRING']) || (isset($_GET['id']) && preg_match('%/\*\*/|SERVER|SELECT|UNION|DELETE|UPDATE|INSERT%i', $_GET['id']))) {
	$ip = getip();
	$forwarded = (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : 'N/A');
	$remoteaddress = $_SERVER["REMOTE_ADDR"];
	ban();
}
if (!empty($_POST) && isset($_SESSION['level']) && $_SESSION['level'] != 1) {
	include_once (ROOTAS . 'priedai/safe_html.php');
	foreach ($_POST as $key => $value) {
		if (!is_array($value))
			$post[$key] = safe_html($value);
		else
			$post[$key] = $value;
	}
	unset($_POST);
	$_POST = $post;
}


//slaptaþodþio kodavimas
function koduoju($pass) {
	return md5(sha1(md5($pass)));
}
//meta tagai ir kita
function header_info() {
	global $conf, $page_pavadinimas;
	echo '
	<base href="'.adresas().'"></base>
	<meta name="generator" content="MightMedia TVS" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="description" content="' . input(strip_tags($conf['Pavadinimas']) . ' - ' . trimlink(strip_tags($conf['Apie']), 120)) . '" />
  <meta name="keywords" content="' . input(strip_tags($conf['Keywords'])) . '" />
  <meta name="author" content="' . input(strip_tags($conf['Copyright'])) . '" />
  <link rel="stylesheet" type="text/css" href="stiliai/system.css" />
  <link rel="stylesheet" type="text/css" href="stiliai/rating.css" />
  <link rel="stylesheet" type="text/css" href="stiliai/' . input(strip_tags($conf['Stilius'])) . '/default.css" />
  <link rel="shortcut icon" href="stiliai/' . input(strip_tags($conf['Stilius'])) . '/favicon.ico" type="image/x-icon">
  <link rel="icon" href="stiliai/' . input(strip_tags($conf['Stilius'])) . '/favicon.ico" type="image/x-icon">

  ' . (isset($conf['puslapiai']['rss.php']) ? '<link rel="alternate" type="application/rss+xml" title="' . input(strip_tags($conf['Pavadinimas'])) . '" href="rss.php" />' : '') . '
  <link type="text/css" media="screen" rel="stylesheet" href="stiliai/colorbox.css" />
  <!--[if IE]>
  <link type="text/css" media="screen" rel="stylesheet" href="stiliai/colorbox-ie.css" title="example" />
  <![endif]-->
  <title>' . input(strip_tags($conf['Pavadinimas']) . ' - ' . $page_pavadinimas) . '</title>
  <script type="text/javascript" src="javascript/jquery/jquery-1.3.2.min.js"></script> 
  <script type="text/javascript" src="javascript/jquery/jquery-ui-personalized-1.6rc6.min.js"></script> 
  <script type="text/javascript" src="javascript/pagrindinis.js"></script>  
  <script type="text/javascript" src="javascript/jquery/rating.js"></script>	
  <script type="text/javascript" src="javascript/jquery/tooltip.js"></script>
  <script type="text/javascript" src="javascript/jquery/jquery.colorbox.js"></script>
  <script type="text/javascript" src="javascript/jquery/jquery.hint.js"></script>
  <script type="text/javascript" src="javascript/jquery/break.js"></script> 
  <script type="text/javascript">
  $(document).ready(function(){
  $(\'.tr\').breakly(20);
  $(\'.tr2\').breakly(20);
  $(\'.td\').breakly(20);
  $(\'.td2\').breakly(20);
  $(\'.th\').breakly(20);
	//Examples of how to assign the ColorBox event to elements.
	$(".gallery a[rel=\'lightbox\']").colorbox({transition:"fade"});

	//Example of preserving a JavaScript event for inline calls.
	$("#click").click(function(){
			 $(\'#click\').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
	});
	$("#inline").colorbox({width:"50%", inline:true, href:"#inline_example1", title:"hello"});

	// find all the input elements with title attributes and make them with a hint
	$(\'input[title!=""]\').hint(\'inactive\');
});
</script>
<!--[if lt IE 7]>
<script type="text/javascript" src="javascript/jquery/jquery.pngFix.pack.js"></script>
<script type="text/javascript">$(document).ready(function(){$(document).pngFix();});</script>
<script src="http://ie7-js.googlecode.com/svn/version/2.0(beta3)/IE7.js" type="text/javascript"></script>
<![endif]-->
';
//<script type="text/javascript" src="javascript/jquery/jquery.tablesorter.js"></script> 
}

function addtotitle($add) {
	//$add = input($add);
	echo <<<HTML
		<script type="text/javascript">
		var cur_title = new String(document.title);
      document.title = cur_title+" - {$add}";
    </script>
HTML;
}

/**
 * Gražina vartotojo avatarą
 * @param emeilas $mail
 * @param dydis px $size
 * @return formated html
 */
function avatar($mail, $size = 80) {
	if(file_exists(ROOT.'images/avatars/'.md5($mail).'.jpeg')) {
		$result='<img src="'.ROOT.'images/avatars/'.md5($mail).'.jpeg?'.time().'" width="' . $size . '" height="' . $size . '" alt="avataras" />';
	}else {
		$result = '<img src="http://www.gravatar.com/avatar/' . md5(strtolower($mail)) . '?s=' . htmlentities($size . '&r=any&default=' . urlencode(adresas() .ROOT.'images/avatars/no_image.jpg') . '&time=' . time()) . '"  width="' . $size . '" alt="avataras" />';
	}
	return $result;
}

/**
 * Sutvarkom failo pavadinimą
 * @param string $name
 * @return formated string
 */
function nice_name($name) {
	$name = ucfirst_utf8($name);
	$name = basename($name,'.php');
	$name = str_replace("_", " ", $name);
	return $name;
}

/**
 *
 * @global <type> $_SESSION
 * @param <type> $failas
 * @return <type>
 */
function ar_admin($failas) {
	global $_SESSION;
	if ((is_array(unserialize($_SESSION['mod'])) && in_array($failas, unserialize($_SESSION['mod']))) || $_SESSION['level'] == 1)
		return true;
	else
		return false;
}

/**
 * Pirma raidė didžioji (utf-8)
 * @param string $str
 * @return string
 */
function ucfirst_utf8($str) {
	if (mb_check_encoding($str, 'UTF-8')) {
		$first = mb_substr(mb_strtoupper($str, "utf-8"), 0, 1, 'utf-8');
		return $first . mb_substr(mb_strtolower($str, "utf-8"), 1, mb_strlen($str), 'utf-8');
	} else {
		return $str;
	}
}

/**
 * Sutrumpina stringa iki nurodyto ilgio (saugiai utf-8)
 * @param string $str
 * @param ilgis $start
 * @return string
 */
function utf8_substr($str, $start) {
	preg_match_all("/./u", $str, $ar);

	if (func_num_args() >= 3) {
		$end = func_get_arg(2);
		return join("", array_slice($ar[0], $start, $end));
	} else {
		return join("", array_slice($ar[0], $start));
	}
}

/**
 * Svetainės adresui gauti
 * @return string
 */
function adresas() {
	return "http://" . $_SERVER["HTTP_HOST"].preg_replace("/[^\/]*$/", "", $_SERVER["PHP_SELF"]);
}

/**
 * Patikrina ar puslapis egzistuoja ir ar vartotojas turi teise ji matyti bei grazinam puslapio ID
 * @param string $puslapis
 */
function puslapis($puslapis, $extra = false) {
	global $conf;
	$teises = @unserialize($conf['puslapiai'][$puslapis]['teises']);

	if (isset($conf['puslapiai'][$puslapis]['id']) && !empty($conf['puslapiai'][$puslapis]['id']) && is_file(dirname(__file__) . '/../puslapiai/' . $puslapis)) {

		if (LEVEL == 1 || (is_array($teises) && in_array(LEVEL, $teises)) || empty($teises)) {

			if ($extra && isset($conf['puslapiai'][$puslapis][$extra]))
				return $conf['puslapiai'][$puslapis][$extra]; //Jei reikalinga kita informacija apie puslapi - grazinam ja.
			else
				return (int)$conf['puslapiai'][$puslapis]['id'];
		} else {
			return false;
		}

	} else
		return false;
}

/**
 * Gražina true arba false (nustatom vartotojo teises)
 * @param serialize array $mas
 * @param int $lvl
 * @return true/false
 */
function teises($mas, $lvl) {
	if (!is_array($mas))
		$mas = @unserialize($mas);
	if ($lvl == 1 || (is_array($mas) && in_array($lvl, $mas)) || empty($mas))
		return true;
	else
		return false;
}

/**
 * Uždrausti IP ant serverio
 *
 * @param string $kodel
 */
function ban($ipas = '', $kodel = '') {
	global $lang, $_SERVER, $ip, $forwarded, $remoteaddress;
	if (empty($kodel))
		$kodel = $lang['system']['forhacking'].' - '.input(str_replace("\n","",$_SERVER['QUERY_STRING']));
	if (empty($ipas))
		$ipas = getip();
	$atidaryti = fopen(".htaccess", "a");
	fwrite($atidaryti, '# '.$kodel." \nSetEnvIf Remote_Addr \"^{$ipas}$\" draudziam\n");
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

	if ($kodel == $lang['system']['forhacking']) {
		die("<center><h1>{$lang['system']['nohacking']}!</h1><font color='red'><b>" . $kodel . " - {$lang['system']['forbidden']}<blink>!</blink></b></font><hr/></center>");
	}
}


/**
 * Nurodytai eilutei iš failo trinti
 * @global kalba $lang
 * @param failas $fileName
 * @param eilutė $lineNum
 */
function delLineFromFile($fileName, $lineNum) {
	global $lang;
	// check the file exists
	if (!is_writable($fileName)) {
		// print an error
		klaida($lang['system']['error'], $lang['system']['error']);
		// exit the function
		exit;
	} else {
		// read the file into an array
		$arr = file($fileName);
	}

	// the line to delete is the line number minus 1, because arrays begin at zero
	$lineToDelete = $lineNum - 1;

	// check if the line to delete is greater than the length of the file
	if ($lineToDelete > sizeof($arr)) {
		// print an error
		klaida($lang['system']['error'], "{$lang['system']['error']} <b>[$lineNum]</b>.");
		// exit the function
		exit;
	}

	//remove the line
	unset($arr["$lineToDelete"]);

	// open the file for reading
	if (!$fp = fopen($fileName, 'w+')) {
		// print an error
		klaida($lang['system']['error'], "{$lang['system']['error']} ($fileName)");
		// exit the function
		exit;
	}

	// if $fp is valid
	if ($fp) {
		// write the array to the file
		foreach ($arr as $line) {
			fwrite($fp, $line);
		}

		// close the file
		fclose($fp);
	}

//msg($lang['system']['done'],"IP {$lang['admin']['unbaned']}.");
}

//Tvarkom $_SERVER globalus.
$_SERVER['PHP_SELF'] = cleanurl($_SERVER['PHP_SELF']);
$_SERVER['QUERY_STRING'] = isset($_SERVER['QUERY_STRING']) ? cleanurl($_SERVER['QUERY_STRING']) : "";
$_SERVER['REQUEST_URI'] = isset($_SERVER['REQUEST_URI']) ? cleanurl($_SERVER['REQUEST_URI']) : "";
$PHP_SELF = cleanurl($_SERVER['PHP_SELF']);

/**
 * Adreso apsauga
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


/**
 * Vartotojų lygiai
 * @return array
 */
unset($sql, $row);
$sql = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno` = 'vartotojai' AND `lang`=".escape(lang())." ORDER BY `id` DESC");

if (sizeof($sql) > 0) {
	foreach ($sql as $row) {

		$levels[(int)$row['teises']] = array('pavadinimas' => $row['pavadinimas'], 'aprasymas' => $row['aprasymas'], 'pav' => $row['pav']);

	}
}
$levels[1] = array('pavadinimas' => $lang['system']['admin'], 'aprasymas' => $lang['system']['admin'], 'pav' => 'admin.png');
$levels[2] = array('pavadinimas' => $lang['system']['user'], 'aprasymas' => $lang['system']['user'], 'pav' => 'user.png');

$conf['level'] = $levels;
unset($levels, $sql, $row);


/**
 * Gaunam visus puslapius ir sukisam i masyva
 */
$sql = mysql_query1("SELECT SQL_CACHE * FROM `" . LENTELES_PRIESAGA . "page` WHERE `lang`=".escape(lang())." ORDER BY `place` ASC", 120);
foreach ($sql as $row) {
	$conf['puslapiai'][$row['file']] = array('id' => $row['id'], 'pavadinimas' => $row['pavadinimas'], 'file' => $row['file'], 'place' => (int)$row['place'], 'show' => $row['show'], 'teises' => $row['teises']);
	$conf['titles'][$row['id']] =(isset($lang['pages'][$row['file']])?$lang['pages'][$row['file']]:nice_name($row['file']));
	$conf['titles_id'][strtolower(str_replace(' ', '_',(isset($lang['pages'][$row['file']])?$lang['pages'][$row['file']]:nice_name($row['file']))))] = $row['id'];
}
//nieko geresnio nesugalvojau
$dir = explode('/', dirname($_SERVER['PHP_SELF']));
$conf['titles']['999'] = $dir[count($dir)-1].'/admin';
$conf['titles_id']['admin'] = 999;
//sutvarkom nuorodas
if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
	$_GET = url_arr(cleanurl($_SERVER['QUERY_STRING']));
	if(isset($_GET['id'])) {
		$element = strtolower($_GET['id']);
		$_GET['id'] = ((isset($conf['titles_id'][$element]) && $conf['F_urls'] != '0')?$conf['titles_id'][$element]:$_GET['id']);
		//echo $_GET['id'];
	}
	$url = $_GET;
} else {
	$url = array();
}
//print_r($_GET);
function url_arr($params) {
	global $conf;
	$str2 = array();
	if (!isset($params))
		$params = $_SERVER['QUERY_STRING'];

	if (strrchr($params, '&'))
		$params = explode("&", $params); //Jeigu tai paprastas GET
	else
		$params = explode(($conf['F_urls'] == '0'?';':$conf['F_urls']), $params);

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

/////////////////////////////////////////////////////////
//////// URL APDOROJIMUI
////////////////////////////////////////////////////////


function url($str) {
	global $conf;

	if(substr($str,0,1) == '?') {
		$linkai = explode(';',$str);
		$start = explode(',', $linkai[0]);
		$linkai[0] = '';
		if($conf['F_urls'] != '0') {
			$return = ROOT.str_replace(' ', '_', $conf['titles'][$start[1]]).implode(($conf['F_urls'] != '0'?$conf['F_urls']:';'), $linkai);
		} else {
			$return = (basename($_SERVER['SCRIPT_NAME']) != 'index.php'?basename($_SERVER['SCRIPT_NAME']):'').$str;
		}
	} else {
		$return = ($conf['F_urls'] != '0'?'':'?').str_replace('id=', '', $_SERVER['QUERY_STRING']).($conf['F_urls'] != '0'?$conf['F_urls']:';').$str;
	}
	return adresas().$return;
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
	if ($user == '' || $user == $lang['system']['guest']) {
		$user = $lang['system']['guest'];
		return $lang['system']['guest'];
	} else {
		if (isset($conf['puslapiai']['view_user.php']['id'])) {
			//Jeigu galiam ziuret vartotojo profili tada nickas paspaudziamas
			if ($level > 0 && $id > 0) {

				return (isset($conf['level'][$level]['pav']) ? '<img src="'.ROOT.'images/icons/' . $conf['level'][$level]['pav'] . '" border="0" class="middle" alt="" /> ' : '') . ' <a href="'.url('?id,' . $conf['puslapiai']['view_user.php']['id'] . ';' . $user). '" title="' . input($user) . " " . $extra . '">' . trimlink($user, 10) . '</a> ' . (isset($_SESSION['username']) && $user != $_SESSION['username'] && isset($conf['puslapiai']['pm.php']) ? "<a href=\"".url("?id," . $conf['puslapiai']['pm.php']['id'] . ";n,1;u," . str_replace("=", "", base64_encode($user))) . "\"><img src=\"".ROOT."images/pm/mail.png\"  style=\"vertical-align:middle\" alt=\"pm\" border=\"0\" /></a>" : "");
			} elseif ($id == 0 && $level != 0) {
				return '<div style="display:inline;" title="' . input($user) . " " . $extra . '">' . (isset($conf['level'][$level]['pav']) ? '<img src="'.ROOT.'images/icons/' . $conf['level'][$level]['pav'] . '" border="0" class="middle" alt="" /> ' : '') . trimlink($user, 10) . (isset($_SESSION['username']) && $user != $_SESSION['username'] && isset($conf['puslapiai']['pm.php']) ? "<a href=\"".url("?id," . $conf['puslapiai']['pm.php']['id'] . ";n,1;u," . str_replace("=", "", base64_encode($user)) ). "\"><img src=\"".ROOT."images/pm/mail.png\"  style=\"vertical-align:middle\" alt=\"pm\" border=\"0\" /></a>" : "") . '</div>';
			} elseif ($level == 0 && $id != 0) {
				return '<a href="'.url('?id,' . $conf['puslapiai']['view_user.php']['id'] . ';' . $user ). '" title="' . input($user) . " " . $extra . '">' . trimlink($user, 10) . '</a> ' . (isset($_SESSION['username']) && $user != $_SESSION['username'] && isset($conf['puslapiai']['pm.php']) ? "<a href=\"".url("?id," . $conf['puslapiai']['pm.php']['id'] . ";n,1;u," . str_replace("=", "", base64_encode($user)))  . "\"><img src=\"".ROOT."images/pm/mail.png\"  style=\"vertical-align:middle\" alt=\"pm\" border=\"0\" /></a>" : "");
			} else {
				return '<div style="display:inline;" title="' . input($user) . " " . $extra . '">' . trimlink($user, 10) . (isset($_SESSION['username']) && $user != $_SESSION['username'] && isset($conf['puslapiai']['pm.php']) ? "<a href=\"".url("?id," . $conf['puslapiai']['pm.php']['id'] . ";n,1;u," . str_replace("=", "", base64_encode($user))) . "\"><img src=\"".ROOT."images/pm/mail.png\"  style=\"vertical-align:middle\" alt=\"pm\" border=\"0\" /></a>" : "") . '</div>';
			}

		} else {
			//Kitu atveju nickas nepaspaudziamas
			if ($level == 0 || $id == 0) {
				return '<div style="display:inline;" title="' . input($user) . " " . $extra . '"><u>' . $user . '</u></div>';
			} else {
				return (isset($conf['level'][$level]['pav']) ? '<img src="'.ROOT.'images/icons/' . $conf['level'][$level]['pav'] . '" border="0" class="middle" alt="" /> ' : '') . ' <a href="#" onclick="return false" title="' . input($user) . " " . $extra . '">' . trimlink($user, 10) . '</a> ' . (isset($_SESSION['username']) && $user != $_SESSION['username'] && isset($conf['puslapiai']['pm.php']) ? "<a href=\"".url("?id," . $conf['puslapiai']['pm.php']['id'] . ";n,1;u," . str_replace("=", "", base64_encode($user))) . "\"><img src=\"".ROOT."images/pm/mail.png\" alt=\"pm\" style=\"vertical-align:middle\" border=\"0\" /></a>" : "");
			}

		}
	}
}


/**
 * MySQL užklausoms
 *
 * @param sql string $query
 * @return resource
 */
function mysql_query1($query, $lifetime = 0) {
	global $mysql_num, $prisijungimas_prie_mysql, $conf;

	//Sugeneruojam kesho pavadinima
	$keshas = realpath(dirname(__file__) . '/..') . '/sandeliukas/' . md5($query) . '.php'; //kesho failas
	$return = array();

	if ($conf['keshas'] && $lifetime > 0 && !in_array(strtolower(substr($query, 0, 6)), array('delete', 'insert', 'update'))) {

		//Tikrinam ar keshavimas ijungtas ir ar keshas egzistuoja
		if (is_file($keshas) && filemtime($keshas) > $_SERVER['REQUEST_TIME'] - $lifetime) {
			//uzkraunam kesha
			include ($keshas);

		} else {
			//Irasom i kesh faila
			$mysql_num++;

			$sql = mysql_query($query, $prisijungimas_prie_mysql) or die(mysql_error());

			//Jeigu uzklausoje nurodyta kad reikia tik vieno iraso tai nesudarom masyvo.
			if (substr(strtolower($query), -7) == 'limit 1') {
				$return = mysql_fetch_assoc($sql);
			} else {
				while ($row = mysql_fetch_assoc($sql)) {
					$return[] = $row;
				}
			}

			$fh = fopen($keshas, 'wb') or die("negaliu nuskaityti kesho");

			//Reikia uzrakinti faila kad du kartus neirasytu
			if (flock($fh, LOCK_EX)) { // urakinam
				fwrite($fh, '<?php $return = ' . var_export($return, true) . '; ?>');
				flock($fh, LOCK_UN); // release the lock
			} else {
				echo "Negaliu užrakinti failo !";
			}
			fclose($fh); //baigiam failo irasyma
			$return = $return;
		}
		return $return;
	} else {
		$mysql_num++;

		$sql = mysql_query($query, $prisijungimas_prie_mysql) or die(mysql_error());

		if (in_array(strtolower(substr($query, 0, 6)), array('delete', 'insert', 'update'))) {
			$return = true;
		} else {
			if (substr(strtolower($query), -7) == 'limit 1') {
				$return = mysql_fetch_assoc($sql);
			} else {
				while ($row = mysql_fetch_assoc($sql)) {
					$return[] = $row;
				}
			}
		}
	}
	return $return;
}

/**
 * Sandeliukui valyti
 * @param <type> $query
 */
function delete_cache($query) {
	$filename = realpath(dirname(__file__) . '/..') . '/sandeliukas/' . md5($query) . '.php';
	if (is_file($filename)) {
		unlink($filename);
	}

}

/**
 * Nuskaitom turinį iš adreso
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
 * Gaunam informaciją iš XML
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
 * Suskaičiuojam kiek nurodytoje lentelėje yra įrašų
 *
 * @param string $table
 * @param string $where
 * @param string $as
 * @return int
 */
function kiek($table, $where = '', $as = "viso") {
	//$viso = mysql_query1("SELECT count(*) AS `$as` FROM `" . LENTELES_PRIESAGA . $table . "` " . $where . " limit 1", 60);
	//return (isset($viso[$as]) && $viso[$as] > 0 ? (int)$viso[$as] : (int)0);
	$i = 0;
	$sql = mysql_query1("SELECT *  FROM `" . LENTELES_PRIESAGA . $table . "` " . $where . "", 60);
	if(sizeof($sql > 0)) {
		foreach($sql as $row) {
			if(isset($sql['teises'])) {
				if(teises($sql['teises'],$_SESSION['level']))
					$i++;
			} else {
				$i++;
			}
		}
	}
	return $i;
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
		$res .= "<div class=\"pg_links\"><center>\n";
		if ($idx_back >= 0) {
			if ($cur_page > ($range + 1))
				$res .= "<a href='" . url("p,0") . "'>[<u>««</u>]</a>\n";
			$res .= "<a href='" . url("p,$idx_back") . "'>[<u>«</u>]</a>\n";
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
				$res .= "<b>[<u><b>$i</b></u>]</b>\n";
			} else {
				$res .= "<a href='" . url("p,$offset_page") . "'>[<u>$i</u>]</a>\n";
			}
		}
		if ($idx_next < $total) {
			$res .= "<a href='" . url("p,$idx_next") . "'>[<u>»</u>]</a>\n";
			if ($cur_page < ($pg_cnt - $range)) {
				$res .= "<a href='" . url("p," . ($pg_cnt - 1) * $count . "") . "'>[<u>»»</u>]</a>\n";
			}
		}
		$res .= "</center></div>\n";
	}
	return $res;
}

/**
 * Tikrina ar kintamasis teigiamas skaičius
 *
 * @param Skaičius $value
 * @return 1 arba NULL
 */
function isNum($value) {
//	if(is_string($value)){
	return @preg_match("/^[0-9]+$/", $value); //}
//else {return false;}
}

/**
 * Grąžina lankytojo IP
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
 * Sugeneruojam atsitiktinė frazė
 *
 * @param frazės ilgis $i
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
 * Sutvarko SQL užklausa
 *
 * @param string $sql
 * @return escaped string
 */
function escape($sql) {
// Stripslashes
	if (get_magic_quotes_gpc()) {
   		$sql = stripslashes($sql);
	}
	//Jei ne skaičius
	if (!isnum($sql) || $sql[0] == '0') {
		if (!isnum($sql)) {
			$sql = "'" . @mysql_real_escape_string($sql) . "'";
		}
	}
	return $sql;
}

/**
 * Sutvarkom stringų saugiam atvaizdavimui
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


/**
 * Seo url TODO
 */
function seo_url($url,$id) {
	//sušveplinam
	$url = iconv('UTF-8', 'US-ASCII//TRANSLIT', $url);
	//neaiškius simbolius pakeičiam brūkšniukais
	$url = preg_replace('/[^A-z0-9-]/', '-', $url);
	//išvalom besikartojančius brūkšniukus
	$url = preg_replace('/-+/', "-", $url);
	//verčiam viską į mažasias raides
	$url = strtolower($url);
	return $url.'_'.$id.'.html';
} 
/////////////////////////////////////////////////////////////
///////// URL PABAIGA
/////////////////////////////////////////////////////////////

/**
 * Naršyklių peradresavimas
 *
 * @param adresas $location
 * @param header/meta/script $type
 */
function redirect($location, $type = "header") {
	if ($type == "header") {
		header("Location: " .$location);
		exit;
	} elseif ($type == "meta") {
		echo "<meta http-equiv='Refresh' content='1;url=$location'>";
	} else {
		echo "<script type='text/javascript'>document.location.href='" .$location . "'</script>\n";
	}
}

/**
 * Grąžina amžių, nurodžius datą
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
 * Užkoduoja problematiškus simbolius
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
 * Sulaužo žodį jei jis per ilgas
 * laužo net jei žodis turi tarpus
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
 * Sulaužo per ilgus žodžius
 * tik jei jis yra be tarpų
 *
 * @param tekstas $string
 * @param ilgis $width
 * @param simbolis $break
 * @return string
 */
function wrap($string, $width, $break = "\n") {
	//Jei tvs be javascript naudosi, atkomentuok
	//$string = preg_replace('/([^\s]{' . $width . '})/i', "$1$break", $string);
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

/**
 * Suapavalinimas
 * @param skaicius $sk
 * @param kiek po kableliu apvalinti $kiek
 * @return skaičių
 */
function apvalinti($sk, $kiek = 2) {
	if ($kiek < 0) {
		$kiek = 0;
	}
	$mult = pow(10, $kiek);
	return ceil($sk * $mult) / $mult;
}

/**
 * Gražina paveiksliuką "new" jei elementas naujas
 * @param data $data
 * @param apsilankymas $nick
 * @return formated string
 */
function naujas($data, $nick = null) {
	if (isset($_SESSION['lankesi'])) {
		return (($data > $_SESSION['lankesi']) ? '<img src="'.ROOT.'images/icons/new.png" onload="$(this).fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
" alt="New" border="0" style="vertical-align: middle;" />' : '');
	} else {
		return '';
	}
}

/**
 * Gražina išsireiškimą nusakantį įvykio laiką
 * @global kalba $lang
 * @param laikas $ts
 * @return išsireiškimą
 */
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
		return ($year > 1 ? sprintf($lang['system']['years'],$year) : sprintf($lang['system']['year'],$year));
	if ($month)
		return ($month > 1 ? sprintf($lang['system']['months'],$month) : sprintf($lang['system']['month'],$month));
	if ($weeks)
		return ($weeks > 1 ? sprintf($lang['system']['weeks'],$weeks) : sprintf($lang['system']['week'],$weeks));
	if ($days)
		return ($days > 1 ? sprintf($lang['system']['days'],$days) : sprintf($lang['system']['day'],$days));
	if ($hours)
		return ($hours > 1 ? sprintf($lang['system']['hours'],$hours) : sprintf($lang['system']['hour'],$hours));
	if ($mins)
		return ($mins > 1 ? sprintf($lang['system']['minutes'],$mins) : sprintf($lang['system']['minute'],$mins));
	//return "&lt; 1 {$lang['system']['minute']} {$lang['system']['ago']}";
	return sprintf($lang['system']['minute'],'&lt; 1');
}

/**
 * Verčiam baitus į žmonių kalbą
 * @param dydis baitais $size
 * @param po kableliu $digits
 * @return formated string
 */
function baitai($size, $digits = 2) {
	$kb = 1024;
	$mb = 1024 * $kb;
	$gb = 1024 * $mb;
	$tb = 1024 * $gb;
	if ($size == 0) {
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
	if (strlen(strip_tags($text)) > $length)
		$text = utf8_substr($text, 0, ($length - 3)) . "...";
	$text = str_replace($dec, $enc, $text);
	return $text;
}

//Paskaiciuojam procentus
function procentai($reikia, $yra, $zenklas = false) {
	$return = (int)round((100 * $yra) / $reikia);
	if ($return > 100 && $zenklas) {
		$return = "<img src='".ROOT."images/icons/accept.png' class='middle' alt='100%' title='100%' borders='0' />";
	} elseif ($return > 0 && $zenklas) {
		$return = "<img src='".ROOT."images/icons/cross.png' class='middle' alt='" . $return . "%' title='" . $reikia . "/" . $yra . " - " . $return . "%' borders='0' />";
	}
	return $return;
}

//Insert SQL - supaprastina duomenų Ä¯terpimą, paduodam lentlÄ—s pavadinimą ir kitu argumentu asociatyvų masyvą
function insert($table, $array) {
	return 'INSERT INTO `' . LENTELES_PRIESAGA . $table . '` (' . implode(', ', array_keys($array)) . ') VALUES (' . implode(', ', array_map('escape', $array)) . ')';
}

function pic($off_site, $size = false, $url = 'images/nuorodu/', $sub = 'url') {
	$pic_name = md5($off_site);
	$pic_name = $url . $sub . "_" . $pic_name . ".png";
	if (!file_exists($pic_name) || (time() - filemtime($pic_name)) > 86400) { //9 valandos senumo
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

function pic1($off_site, $size = false, $url = 'images/nuorodu/', $sub = 'url') {
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
		imagealphablending($src, true);

		if (empty($src)) {
			return false;
		}
		if ($size) {
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
		}
		$file = $url . $sub . "_" . md5($off_site) . ".png";

		// determine image type and send it to the browser
		imagesavealpha($src, true);
		@imagepng((!$img?$src:$img), $file);
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
	$keiciam = array("Gruodis", "Sausis", "Vasaris", "Kovas", "Balandis", "Gegužė", "Birželis", "Liepa", "Rugpjūtis", "Rugsėjis", "Spalis", "Lapkritis");
	return str_replace($ieskom, $keiciam, $men);
}

// grąžina failus iš nurodytos direktorijos ir sukiša Ä¯ masyvą
function getFiles($path, $denny = '.htaccess|index.php|index.html|index.htm|index.php3|conf.php') {
	global $lang;
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

//Grazina direktorijų sarašą
function getDirs($dir, $skip = '') {
	if ($handle = opendir($dir)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && $file != ".svn" && is_dir($dir . $file) && (is_array($skip)?!in_array($file, $skip):true) && $skip  != $file) {
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
//emailo validumas
function check_email($email) {
	return preg_match("/^([_a-zA-Z0-9-+]+)(\.[_a-zA-Z0-9-]+)*@([a-zA-Z0-9-]+)(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,6})$/" , $email) ? true : false;
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
	if (strpos($HTTP_USER_AGENT, 'Windows')) {
		$global_info['user_os'] = "WIN";
	} elseif (strpos($HTTP_USER_AGENT, 'Macintosh')) {
		$global_info['user_os'] = "MAC";
	} elseif (strpos($HTTP_USER_AGENT, "Linux")) {
		$global_info['user_os'] = "Linux";
	} else {
		$global_info['user_os'] = "OTHER";
	}
	return $global_info['user_os'];
}
/*
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
	if (preg_match("MSIE ([0-9].[0-9]{1,2})", $HTTP_USER_AGENT, $regs)) {
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
*/
/**
 * Gražina patvirtinimo kodą
 *
 * @return HTML
 */
function kodas() {
	global $lang;
	$return = <<<HTML
	<script type="text/javascript">
	var \$human = document.createElement('img');
	\$human.setAttribute('src','priedai/human.php');
	\$human.setAttribute('style','cursor: pointer');
	\$human.setAttribute('title','{$lang['system']['refresh']}');
	\$human.setAttribute('onclick','this.src="priedai/human.php?"+Math.random();');
	\$human.setAttribute('alt','code');
	\$human.setAttribute('id','captcha');

	$('#captcha_content').html(\$human);
	</script>
HTML;
	return "<p id='captcha_content'></p>$return";
}

/** Gražina versijos numerį */
function versija($failas = false) {
	if (!$failas) {
		$svnid = '$Rev$';
		$scid = utf8_substr($svnid, 6);
		return apvalinti((intval(utf8_substr($scid, 0, strlen($scid) - 2)) / 5000) + '1.26', 2);
	} else {
		//Nuskaityti faila ir paimti su regexp versijos numeri
		return '$Rev$';
	}
}


// compress HTML BLOGAS DUOMENŲ SUSPAUDIMO BŪDAS
//ob_start('sendcompressedcontent');
/**
 * Editorius skirtas vaizdžiai redaguoti html
 *
 * @example echo editorius('tiny_mce','mini');
 * @example echo editorius('spaw','standartinis',array('Glaustai'=>'Glaustai','Placiau'=>'Plačiau'),array('Glaustai'=>'Naujiena glaustai','Placiau'=>'Naujiena plačiau'));
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

	if (is_array($id)) {
		foreach ($id as $key => $val) {
			$arr[$val] = "'$key'";
		}
		$areos = implode($arr, ",");
	} else {
		$areos = "'$id'";
	}
	$root = ROOT;
	$return = <<<HTML
<script type="text/javascript" src="{$root}javascript/htmlarea/markitup/jquery.markitup.js"></script>
<!-- markItUp! toolbar settings -->
<script type="text/javascript" src="{$root}javascript/htmlarea/markitup/sets/default/set.js"></script>
<!-- markItUp! skin -->
<link rel="stylesheet" type="text/css" href="{$root}javascript/htmlarea/markitup/skins/markitup/style.css" />
<!--  markItUp! toolbar skin -->
<link rel="stylesheet" type="text/css" href="{$root}javascript/htmlarea/markitup/sets/default/style.css" />
	
HTML;

	if (is_array($id)) {
		foreach ($id as $key => $val) {


			$return .= <<< HTML
			<script type="text/javascript">
$(document).ready(function()	{
		$('#{$key}').markItUp(mySettings);});
	</script>
<textarea id="{$key}" name="{$key}" style="min-height:320px;">{$value[$key]}</textarea>
HTML;
		}
	} else {
		$return .= <<< HTML
		<script type="text/javascript">
$(document).ready(function()	{
		$('#{$id}').markItUp(mySettings);});
	</script>
<textarea id="{$id}" name="{$id}" style="min-height:320px;">{$value}</textarea>
HTML;

	}


	return $return;
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
function build_menu($data, $id=0, $active_class='active') {
  if(!empty($data)){
    $re="";
    foreach ($data[$id] as $row) {
      if (isset($data[$row['id']])) {
        $re.= "\n\t\t<li ".((isset($_GET['id']) && $_GET['id'] == $row['id'])?'class="'.$active_class.'"':'')."><a href=\"".url("?id,{$row['id']}")."\">".$row['pavadinimas']."</a>\n<ul>\n\t";
        $re.=build_menu($data, $row['id'],$active_class);
        $re.= "\t</ul>\n\t</li>";
      } else $re.= "\n\t\t<li ".((isset($_GET['id']) && $_GET['id'] == $row['id'])?'class="'.$active_class.'"':'')."><a href=\"".url("?id,{$row['id']}")."\">".$row['pavadinimas']."".(isset($row['extra'])?$row['extra']:'')."</a></li>";
    }
    return $re;
  }
}
/**
 * Nuorodų tikrinimas
 *
 * @example if (checkUrl('http://delfi.lt')) echo 'ok'; else echo 'no';
 * @param string $url
 * @return true/false
 */
function checkUrl($url) {
	if ($data = @get_headers($url)) {
		preg_match('/^HTTP\/1\.[01] (\d\d\d)/', implode('',$data), $matches);
		if ($matches[1] == 200) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

/**
 * Gražina kalbą
 * @global array $conf
 * @return string
 */
function lang() {
	if (empty($_SESSION['lang'])) {
		global $conf;
		$_SESSION['lang'] = basename($conf['kalba'],'.php');
	}
	return $_SESSION['lang'];
}
?>
