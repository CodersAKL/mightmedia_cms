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

if (!defined("OK")) {
	header("Location: " . adresas());
	exit;
}
if (isset($url['w']) && isnum($url['w']) && $url['w'] > 0) {
	$link = (int)$url['w'];
} else {
	$link = 0;
}
if (isset($url['p']) && isnum($url['p']) && $url['p'] > 0) {
	$p = escape(ceil((int)$url['p']));
} else {
	$p = 0;
}
if (isset($url['k']) && isnum($url['k']) && $url['k'] > 0) {
	$k = escape(ceil((int)$url['k']));
} else {
	$k = 0;
}
//Kintamieji
$text = '';
$extra = '';
$p = 0;

//Jei lankytojas paspaudžia ant nuorodos
if (isset($link) && strlen($link) > 0 && $link > 0) {
	mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "nuorodos` SET click=click+1 WHERE `id`=" . escape($link) . " LIMIT 1");
	$link = mysql_query1("SELECT `url` FROM `" . LENTELES_PRIESAGA . "nuorodos` WHERE `id`=" . escape($link) . " LIMIT 1", 86400);
	redirect($link['url']);
}

//kategorijos
$sqlas = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='nuorodos'  ORDER BY `pavadinimas`");
if (sizeof($sqlas) > 0) {
	foreach ($sqlas as $sql) {
		$path = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id`='" . $sql['id'] . "' ORDER BY `pavadinimas` LIMIT 1");
		$path1 = explode(",", $path['path']);

		if ($path1[(count($path1) - 1)] == $k) {
			$sqlkiek = kiek('nuorodos', "WHERE `cat`=" . escape($sql['id']) . " AND `active`='TAIP'");
			$info[] = array(
				" " => "<img src='images/naujienu_kat/" . $sql['pav'] . "' alt='Kategorija' border='0' />",
				"{$lang['category']['about']}" => "<h2><a href='?id," . $url['id'] . ";k," . $sql['id'] . "'>" . $sql['pavadinimas'] . "</a></h2>" . $sql['aprasymas'] . "<br>",
				"{$lang['category']['links']}" => $sqlkiek
			);
		}
	}
}
include_once ("priedai/class.php");
$bla = new Table();
if (isset($info)) {
	lentele("{$lang['system']['categories']}", $bla->render($info), false);
}
//pabaiga

if ($k >= 0) {
	$teis = mysql_query1("SELECT teises FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id`='" . $k . "' LIMIT 1", 86400);
	if (teises($teis['teises'], $_SESSION['level'])) {
		$q = mysql_query1("SELECT `" . LENTELES_PRIESAGA . "nuorodos`.`id`,
		`" . LENTELES_PRIESAGA . "nuorodos`.`url`,
		`" . LENTELES_PRIESAGA . "nuorodos`.`pavadinimas`,
		`" . LENTELES_PRIESAGA . "nuorodos`.`click`,
		`" . LENTELES_PRIESAGA . "nuorodos`.`date`,
		`" . LENTELES_PRIESAGA . "nuorodos`.`apie`,
		`" . LENTELES_PRIESAGA . "users`.`nick`
FROM `" . LENTELES_PRIESAGA . "nuorodos` 
Left Join `" . LENTELES_PRIESAGA . "users` ON `" . LENTELES_PRIESAGA . "nuorodos`.`nick` = `" . LENTELES_PRIESAGA . "users`.`id` WHERE `" . LENTELES_PRIESAGA . "nuorodos`.`cat`='" . $k . "' AND `" . LENTELES_PRIESAGA . "nuorodos`.`active`='TAIP'
ORDER BY `" . LENTELES_PRIESAGA . "nuorodos`.`click` DESC", 86400);
		if (count($q) > 0) {
			include_once ("priedai/class.php");

			$bla = new Table();
			$info = array();

			foreach ($q as $sql) {
				$extra = '';
				include_once ("priedai/class.php");
				include_once ("rating.php");

				$info[] = array(
					"{$lang['admin']['link']}:" => '' . $extra . ' <a href="?id,' . $url['id'] . ';k,' . $url['k'] . ';w,' . $sql['id'] . '" title="<center><b>' . $sql['url'] . '</b><br /><img src=\'http://enimages2.websnapr.com/?size=s&url=' . $sql['url'] . '\' /></center><br />' . $lang['admin']['links_author'] . ': <b>' . $sql['nick'] . '</b><br />' . $lang['admin']['links_date'] . ': <b>' . date('Y-m-d H:i:s ', $sql['date']) . '</b><br />' . $lang['admin']['links_clicks'] . ': <b>' . $sql['click'] . '</b>" target="_blank" rel="nofollow">' . $sql['pavadinimas'] . '</a>',
					"{$lang['admin']['links_about']}:" => $sql['apie'],
					"{$lang['admin']['links_rate']}:" => rating_form($page,$sql['id'])				);


			}
			lentele($lang['admin']['links_links'], $bla->render($info));
		}
	}
}

if (isset($_SESSION['username']) && !empty($_SESSION['username']) && defined("LEVEL") && LEVEL > 0) {
	if (isset($_POST['Submit_link']) && !empty($_POST['Submit_link']) && $_POST['Submit_link'] == $lang['admin']['links_create']) {

	// Nustatom kintamuosius
		$url = strip_tags($_POST['url']);
		$apie = strip_tags($_POST['apie']);
		$pavadinimas = strip_tags($_POST['name']);
		$cat = strip_tags($_POST['kat']);

		// Patikrinam
		//$pattern = "#^(http:\/\/|https:\/\/|www\.)(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(:(\d+))?(\/)*$#i";
		$pattern = "#([a-z]+?)://([a-z0-9\-\.,\?!%\*_\#:;~\\&$@\/=\+]+)#si";
		if (!preg_match($pattern, $url)) {
			klaida($lang['system']['error'], "{$lang['admin']['links_bad']}");

		} else {

			$result = mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "nuorodos` (`cat` , `url` ,`pavadinimas` , `nick` , `date` , `apie` ) VALUES (" . escape($cat) . ", " . escape($url) . ", " . escape($pavadinimas) . ", " . escape($_SESSION['id']) . ", '" . time() . "', " . escape($apie) . ");");
			if ($result) {
				msg($lang['system']['done'], "{$lang['admin']['links_sent']}.");
			} else {
				klaida($lang['system']['error'], "{$lang['admin']['links_allfields']}");
			}
		}
	}
	$sql = mysql_query1("SELECT * FROM  `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='nuorodos' AND `path`=0 ORDER BY `id` DESC");
	if (sizeof($sql) > 0) {
		foreach ($sql as $row) {

			$sql2 = mysql_query1("SELECT * FROM  `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='straipsniai' AND path!=0 and `path` like '" . $row['id'] . "%' ORDER BY `id` ASC");

			$subcat = '';
			if (sizeof($sql2) > 0) {
				foreach ($sql2 as $row) {
					$subcat .= "->" . $path['pavadinimas'];
					$kategorijos[$row['id']] = $row['pavadinimas'];
					$kategorijos[$path['id']] = $row['pavadinimas'] . $subcat;
				}
			} else {
				$kategorijos[$row['id']] = $row['pavadinimas'];
			}
		}
	} else {
		$kategorijos[0] = "{$lang['system']['nocategories']}";
		$nocat = 1;
	}
	if (!isset($nocat)) {
		include_once ("priedai/class.php");

		$bla = new forma();
		$nuorodos = array("Form" => array("action" => "", "method" => "post", "name" => "Submit_link"), "{$lang['system']['category']}:" => array("type" => "select", "value" => $kategorijos, "name" => "kat"), "{$lang['admin']['links_title']}:" => array("type" => "text", "value" => "", "name" => "name"), "Url:" => array("type" => "text", "value" => "http://", "name" => "url"), "{$lang['admin']['links_about']}:" => array("type" => "textarea", "value" => "", "name" => "apie"), " " => array("type" => "submit", "name" => "Submit_link", "value" => "{$lang['admin']['links_create']}"));

		hide("{$lang['admin']['links_create']}", $bla->form($nuorodos), true);
	}
}
unset($bla, $nuorodos, $row, $sql, $cat, $url, $pavadinimas, $apie, $info, $q, $sql, $text, $link, $sqlas);
if (count($_GET) == 1) {
	if (kiek("nuorodos", "WHERE active='TAIP'") == 0)
		klaida($lang['system']['warning'], $lang['system']['no_content']);
}
/**
 * Gražina informaciją apie nurodyta puslapį
 * Title, meta tagus ir pan.
 *
 * @param http $url
 * @return array
 */
function svetaines_info($url = false) {
	if (!$url) {
		return false;
	}

	if ($fp = @fopen($url, 'r')) {
		$content = "";
		while (!feof($fp)) {
			$buffer = trim(fgets($fp, 4096));
			$content .= $buffer;
		}

		$start = '<title>';
		$end = '<\/title>';
		preg_match("/$start(.*)$end/s", $content, $match);
		$title = $match[1];

		$metatagarray = get_meta_tags($url);
		$metatagarray['title'] = $title;
		$metatagarray['response'] = $http_response_header;
	} else {
		$metatagarray['response'] = $http_response_header;
		unset($http_response_header);
	}
	if ($metatagarray['response'][0] == 'HTTP/1.0 301 Moved Permanently') {
		$new_url = strstr($metatagarray['response'][1], ' ');
		svetaines_info($new_url);
	}
	if ($metatagarray['response'][7] == 'HTTP/1.0 302 Moved Temporarily') {
		$new_url = strstr($metatagarray['response'][12], ' ');
		svetaines_info($new_url);
	}
	return $metatagarray;
}

?>