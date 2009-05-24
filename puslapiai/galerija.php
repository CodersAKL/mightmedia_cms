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

include_once ("priedai/rating_functions.php");

$text = "";
$limit = $conf['fotoperpsl'];

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
//kategorijos
if (!isset($url['m'])) {
	$sqlas = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='galerija'  ORDER BY `pavadinimas`");
	if ($sqlas && sizeof($sqlas) > 0) {
		foreach ($sqlas as $sql) {
			$path = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id`='" . $sql['id'] . "' ORDER BY `pavadinimas` LIMIT 1", 86400);
			$path1 = explode(",", $path['path']);

			if ($path1[(count($path1) - 1)] == $k) {
				$sqlkiek = kiek('galerija', "WHERE `categorija`=" . escape($sql['id']) . " AND `rodoma`='TAIP'");
				$info[] = array(" " => "<a href='?id," . $url['id'] . ";k," . $sql['id'] . "'><img src='images/naujienu_kat/" . $sql['pav'] . "' alt='Kategorija' border='0' /></a>", "{$lang['category']['about']}" => "<h2><a href='?id," . $url['id'] . ";k," . $sql['id'] . "'>" . $sql['pavadinimas'] . "</a></h2>" . $sql['aprasymas'] . "", "{$lang['category']['images']}" => $sqlkiek, );
			}
		}
		include_once ("priedai/class.php");
		$bla = new Table();
		if (isset($info)) {
			lentele("{$lang['system']['categories']}", $bla->render($info), false);
		}
	}
}

//pabaiga
$visos = kiek('galerija', "WHERE `categorija`=" . escape($k) . " AND `rodoma` =  'TAIP'");
if ($k > 0) {

	$sql = mysql_query1("SELECT
  `" . LENTELES_PRIESAGA . "grupes`.`pavadinimas` AS `Kategorija`,
    `" . LENTELES_PRIESAGA . "grupes`.`pav` AS `img`,
        `" . LENTELES_PRIESAGA . "grupes`.`teises` AS `teises`,
  `" . LENTELES_PRIESAGA . "galerija`.`pavadinimas`,
  `" . LENTELES_PRIESAGA . "galerija`.`id`,
  `" . LENTELES_PRIESAGA . "galerija`.`apie`,
  `" . LENTELES_PRIESAGA . "galerija`.`data`,
  `" . LENTELES_PRIESAGA . "users`.`nick` AS `Nick`,
  `" . LENTELES_PRIESAGA . "galerija`.`file`
  FROM
  `" . LENTELES_PRIESAGA . "grupes`
  Inner Join `" . LENTELES_PRIESAGA . "galerija` ON `" . LENTELES_PRIESAGA . "grupes`.`id` = `" . LENTELES_PRIESAGA . "galerija`.`categorija`
  Inner Join `" . LENTELES_PRIESAGA . "users` ON `" . LENTELES_PRIESAGA . "galerija`.`autorius` = `" . LENTELES_PRIESAGA . "users`.`id`
  WHERE  
   `" . LENTELES_PRIESAGA . "galerija`.`categorija` = " . escape($k) . " AND `" . LENTELES_PRIESAGA . "galerija`.`rodoma` =  'TAIP'
  ORDER BY
  `" . LENTELES_PRIESAGA . "galerija`.`id` DESC
  LIMIT  $p,$limit", 86400);
} else {
	$sql = mysql_query1("SELECT
  `" . LENTELES_PRIESAGA . "galerija`.`pavadinimas`,
  `" . LENTELES_PRIESAGA . "galerija`.`id`,
  `" . LENTELES_PRIESAGA . "galerija`.`apie`,
  `" . LENTELES_PRIESAGA . "galerija`.`data`,
  `" . LENTELES_PRIESAGA . "users`.`nick` AS `Nick`,
  `" . LENTELES_PRIESAGA . "galerija`.`file`
  FROM
  `" . LENTELES_PRIESAGA . "galerija`
  
  Inner Join `" . LENTELES_PRIESAGA . "users` ON `" . LENTELES_PRIESAGA . "galerija`.`autorius` = `" . LENTELES_PRIESAGA . "users`.`id`
  WHERE  
   `" . LENTELES_PRIESAGA . "galerija`.`categorija` =  " . escape($k) . " AND `" . LENTELES_PRIESAGA . "galerija`.`rodoma` =  'TAIP'
  ORDER BY
  `" . LENTELES_PRIESAGA . "galerija`.`id` DESC
  LIMIT  $p,$limit", 86400);

}


if ($visos > $limit) {
	lentele($lang['system']['pages'], puslapiai($p, $limit, $visos, 10));
}


if (empty($url['m'])) {
	$sqlas = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id`=" . escape($k) . " AND `kieno`='naujienos' ORDER BY `pavadinimas` LIMIT 1");


	if (defined('LEVEL') && teises($sqlas['teises'], $_SESSION['level'])) {
		$text .= "
			
    
			<table border=\"0\">
	<tr>
		<td >
";

		foreach ($sql as $row) {

			if (isset($row['Nick'])) {
				$autorius = $row['Nick'];
			} else {
				$autorius = $lang['system']['guest'];
			}

			$text .= "
				
				<div id=\"gallery\" class=\"img_left\" >
			<a rel=\"lightbox\" href=\"galerija/originalai/" . $row['file'] . "\"  title=\"" . $row['pavadinimas'] . ": " . trimlink(strip_tags($row['apie']), 50) . "\">
				<img src=\"galerija/mini/" . $row['file'] . "\" alt=\"\" />
			</a><br />
      <a href=\"#\" title=\"{$lang['admin']['gallery_date']}: " . date('Y-m-d H:i:s ', $row['data']) . "\">
        <img src='images/icons/information.png' border='0' alt='info' />
      </a>
      <a href=\"?id," . $conf['puslapiai']['galerija.php']['id'] . ";m," . $row['id'] . "\" title=\"{$lang['admin']['gallery_comments']}\">
        <img src='images/icons/comment.png' alt='C' border='0' />
      </a>
			<a href=\"galerija/originalai/" . $row['file'] . "\" title=\"{$lang['download']['download']}\">
        <img src='images/icons/disk.png' border='0' alt='save' />
      </a>";
			$text .= (defined('LEVEL') && LEVEL == 1 ? "
      <a href=\"#\" onclick=\"if (confirm('{$lang['system']['delete_confirm']}')) { $.get('?id,999;a,{$admin_pagesid['galerija']};t," . $row['id'] . "'); $(this).parent('.img_left').remove(); return false } else { return false }\" title=\"{$lang['system']['delete']}\">
        <img src='images/icons/cross.png' alt='X' border='0' />
      </a>
      " : "") . "
		</div>
		";

			$foto = true;
			//<b>{$lang['admin']['gallery_author']}:</b> " . $autorius . "<br />
		}
		$text .= '</td>
	</tr>
</table>';

		if ($k > 0) {
			$name = mysql_query1("SELECT pavadinimas FROM " . LENTELES_PRIESAGA . "grupes WHERE id=  " . escape($k) . " LIMIT 1", 86400);
			$pav = input($name['pavadinimas']);
		} else {
			$pav = "--";
		}
		if (isset($foto)) {
			lentele($pav, $text);
		}
		unset($row, $text, $sql);
	} else {
		klaida($lang['system']['warning'], "{$lang['category']['cant']}.");
	}
}
//}else{ klaida("Dėmesio","Jums nesuteiktos teisės Matyti šią kategoriją."); }
if (!empty($url['m'])) {
	$sql = mysql_query1("SELECT
  `" . LENTELES_PRIESAGA . "grupes`.`pavadinimas` AS `Kategorija`,
    `" . LENTELES_PRIESAGA . "grupes`.`pav` AS `img`,
        `" . LENTELES_PRIESAGA . "grupes`.`teises` AS `teises`,
   `" . LENTELES_PRIESAGA . "grupes`.`id` AS `kid`,
  `" . LENTELES_PRIESAGA . "galerija`.`pavadinimas`,
  `" . LENTELES_PRIESAGA . "galerija`.`id` AS `nid`,
  `" . LENTELES_PRIESAGA . "galerija`.`apie`,
  `" . LENTELES_PRIESAGA . "galerija`.`data`,
  `" . LENTELES_PRIESAGA . "users`.`nick` AS `Nick`,
  `" . LENTELES_PRIESAGA . "users`.`id` AS `nick_id`,
  `" . LENTELES_PRIESAGA . "users`.`levelis` AS `levelis`,
  `" . LENTELES_PRIESAGA . "galerija`.`file`
  FROM
  `" . LENTELES_PRIESAGA . "grupes`
  Inner Join `" . LENTELES_PRIESAGA . "galerija` ON `" . LENTELES_PRIESAGA . "grupes`.`id` = `" . LENTELES_PRIESAGA . "galerija`.`categorija`
  Inner Join `" . LENTELES_PRIESAGA . "users` ON `" . LENTELES_PRIESAGA . "galerija`.`autorius` = `" . LENTELES_PRIESAGA . "users`.`id`
  WHERE  
   `" . LENTELES_PRIESAGA . "galerija`.`id` =  '" . $url['m'] . "' AND `" . LENTELES_PRIESAGA . "galerija`.`rodoma` =  'TAIP'
  ORDER BY
  `" . LENTELES_PRIESAGA . "galerija`.`data` DESC
  LIMIT  1", 86400);

	$row = $sql;
	if (!empty($row['file']) && isset($row['file'])) {
		if (defined('LEVEL') && teises($row['teises'], $_SESSION['level']) || ((isset($_SESSION['mod']) && is_array(unserialize($_SESSION['mod'])) && in_array('galerija.php', unserialize($_SESSION['mod']))))) {

			$nuoroda = mysql_query1("SELECT id
FROM " . LENTELES_PRIESAGA . "galerija WHERE id > '" . $url['m'] . "' AND `categorija`='" . $row['kid'] . "'order by id ASC LIMIT 1", 86400);
			$nuoroda2 = mysql_query1("SELECT id FROM " . LENTELES_PRIESAGA . "galerija WHERE id < '" . $url['m'] . "' AND categorija='" . $row['kid'] . "' order by id DESC LIMIT 1", 86400);
			if (isset($row['Nick'])) {
				$autorius = user($row['Nick'], $row['nick_id'], $row['levelis']);
			} else {
				$autorius = $lang['system']['guest'];
			}

			if ((int)$conf['galbalsuot'] == 1) {
				$balsavimas = pullRating($row['nid'], false, true, true);
			} else {
				$balsavimas = '';
			}
			$text .= "
			<div id=\"gallery\" class=\"img_left\" >
        <center>
          <a  rel=\"lightbox\" href=\"galerija/originalai/" . $row['file'] . "\" title=\"" . $row['pavadinimas'] . ": " . trimlink(strip_tags($row['apie']), 50) . "\">
            <img src=\"galerija/" . $row['file'] . "\" alt=\"\" />
          </a>
        </center>
      </div>
		<br />
		" . $balsavimas . "
		
		<b>{$lang['admin']['gallery_date']}:</b> " . date('Y-m-d H:i:s ', $row['data']) . "<br />
		<b>{$lang['admin']['gallery_about']}:</b> " . $row['apie'] . "<br />
		<b>{$lang['admin']['gallery_author']}:</b> " . $autorius . " <br />
		<center>
		<h1>";

			if (!empty($nuoroda2['id'])) {
				$text .= "<a href=\"?id," . $url['id'] . ";m," . $nuoroda2['id'] . "\" >< {$lang['admin']['gallery_prev']}</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			}
			if (!empty($nuoroda['id'])) {
				$text .= "<a href=\"?id," . $url['id'] . ";m," . $nuoroda['id'] . "\" >{$lang['admin']['gallery_next']} ></a></h1>";
			}
			$text .= "</center>";

			lentele($row['pavadinimas'], $text);

			if ((int)$conf['galkom'] == 1) {
				include_once ("priedai/komentarai.php");
				komentarai($url['m']);
			}
		} else {
			klaida("{$lang['system']['warning']}", "{$lang['admin']['gallery_cant']}.");
		}
	}
}
if (count($_GET) == 1) {
	if (kiek("galerija", "WHERE rodoma='TAIP'") == 0)
		klaida($lang['system']['warning'], $lang['system']['no_content']);
}
unset($text, $row, $sql);

?>
