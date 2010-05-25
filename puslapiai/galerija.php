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

include_once ("rating.php");

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
	$sqlas = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='galerija' AND `lang` = ".escape(lang())." ORDER BY `pavadinimas`", 86400);
	if ($sqlas && sizeof($sqlas) > 0) {
		foreach ($sqlas as $sql) {
			$path = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id`='" . $sql['id'] . "' AND `lang` = ".escape(lang())." ORDER BY `pavadinimas` LIMIT 1", 86400);
			//$path1 = explode(",", $path['path']);

			if ($path['path'] == $k) {
				$sqlkiek = kiek('galerija', "WHERE `categorija`=" . escape($sql['id']) . " AND `rodoma`='TAIP' AND `lang` = ".escape(lang()));
				$info[] = array(
					" " => "<a href='".url("?id," . $url['id'] . ";k," . $sql['id'] . "")."'><img src='images/naujienu_kat/" . $sql['pav'] . "' alt='Kategorija' border='0' /></a>",
					$lang['category']['about'] => "<h2><a href='".url("?id," . $url['id'] . ";k," . $sql['id'] . "")."'>" . $sql['pavadinimas'] . "</a></h2>" . $sql['aprasymas'] . "",
					$lang['category']['images'] => $sqlkiek
				);
			}
		}
		include_once ("priedai/class.php");
		$bla = new Table();
		if (isset($info)) {
			lentele($lang['system']['categories'], $bla->render($info), false);
		}
	}
}

//pabaiga
$visos = kiek('galerija', "WHERE `categorija`=" . escape($k) . " AND `rodoma` =  'TAIP' AND `lang` = ".escape(lang())."");
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
	AND `" . LENTELES_PRIESAGA . "galerija`.`lang` = ".escape(lang())."
  ORDER BY
  `" . LENTELES_PRIESAGA . "galerija`.`".$conf['galorder']."` ".$conf['galorder_type']."
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
  `" . LENTELES_PRIESAGA . "galerija`.`".$conf['galorder']."` ".$conf['galorder_type']."
  LIMIT  $p,$limit", 86400);

}


if ($visos > $limit) {
	lentele($lang['system']['pages'], puslapiai($p, $limit, $visos, 10));
}


if (empty($url['m'])) {
	$sqlas = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id`=" . escape($k) . " AND `kieno`='galerija' AND `lang` = ".escape(lang())." ORDER BY `pavadinimas` LIMIT 1");


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
			<div class=\"gallery img_left\" >
				<a rel=\"lightbox\" href=\"images/galerija/" . $row['file'] . "\" title=\"" . (!empty($row['pavadinimas'])?$row['pavadinimas'] . "<br>":'') . trimlink(strip_tags($row['apie']), 50) . "\">
					<img src=\"images/galerija/mini/" . $row['file'] . "\" alt=\"\" />
				</a>
				<div class='gallery_menu'>
					<a href=\"#\" title=\"{$lang['admin']['gallery_date']}: " . date('Y-m-d H:i:s ', $row['data']) . "\"><img src='images/icons/information.png' border='0' alt='info' /></a>
					<a href=\"".url("?id," . $conf['puslapiai']['galerija.php']['id'] . ";m," . $row['id'])."\" title=\"{$lang['admin']['gallery_comments']}\"><img src='images/icons/comment.png' alt='C' border='0' /></a>
					<a href=\"images/galerija/originalai/" . $row['file'] . "\" title=\"{$lang['download']['download']}\"><img src='images/icons/disk.png' border='0' alt='save' /></a>";
					$text .= "
				</div>
				<div class='gallery_title'>
					" . trimlink((!empty($row['pavadinimas'])?$row['pavadinimas']:''),10) . "
				</div>
			</div>
		";

			$foto = true;
		//<b>{$lang['admin']['gallery_author']}:</b> " . $autorius . "<br />
		}
		$text .= '</td>
	</tr>
</table>';

		if ($k > 0) {
			$name = mysql_query1("SELECT pavadinimas FROM " . LENTELES_PRIESAGA . "grupes WHERE id=  " . escape($k) . " AND `lang` = ".escape(lang())." LIMIT 1", 86400);
			$pav = input($name['pavadinimas']);
		} else {
			$pav = "--";
		}
		if (isset($foto)) {
			lentele($pav, $text);
		}
		unset($row, $text, $sql);
	} else {
		klaida($lang['system']['warning'], $lang['category']['cant']);
	}
}
//}else{ klaida("Dėmesio","Jums nesuteiktos teisės Matyti šią kategoriją."); }
if (!empty($url['m'])) {
	$sql = "SELECT
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
   `" . LENTELES_PRIESAGA . "galerija`.`id` =  " . escape($url['m']) . " AND `" . LENTELES_PRIESAGA . "galerija`.`rodoma` =  'TAIP'
	AND `" . LENTELES_PRIESAGA . "galerija`.`lang` = ".escape(lang())."
  ORDER BY
  `" . LENTELES_PRIESAGA . "galerija`.`data` DESC
  LIMIT 1";

	$row = mysql_query1($sql, 86400);
	if(empty($row['file'])) {
		$row = mysql_query1("SELECT
  `" . LENTELES_PRIESAGA . "galerija`.`pavadinimas`,
  `" . LENTELES_PRIESAGA . "galerija`.`id` AS `nid`,
  `" . LENTELES_PRIESAGA . "galerija`.`apie`,
  `" . LENTELES_PRIESAGA . "galerija`.`data`,
  `" . LENTELES_PRIESAGA . "users`.`nick` AS `Nick`,
  `" . LENTELES_PRIESAGA . "users`.`id` AS `nick_id`,
  `" . LENTELES_PRIESAGA . "users`.`levelis` AS `levelis`,
  `" . LENTELES_PRIESAGA . "galerija`.`file`
  FROM
  `" . LENTELES_PRIESAGA . "galerija`

  Inner Join `" . LENTELES_PRIESAGA . "users` ON `" . LENTELES_PRIESAGA . "galerija`.`autorius` = `" . LENTELES_PRIESAGA . "users`.`id`
  WHERE  
   `" . LENTELES_PRIESAGA . "galerija`.`id` =  " . escape($url['m']) . " AND `" . LENTELES_PRIESAGA . "galerija`.`rodoma` =  'TAIP'
    AND `" . LENTELES_PRIESAGA . "galerija`.`lang` = ".escape(lang())."
  ORDER BY
  `" . LENTELES_PRIESAGA . "galerija`.`data` DESC
  LIMIT 1", 86400);
		$row['teises']=0;
		$row['kid']=0;
	}

	if (!empty($row['file']) && isset($row['file'])) {
		if (defined('LEVEL') && teises($row['teises'], $_SESSION['level']) || ((isset($_SESSION['mod']) && is_array(unserialize($_SESSION['mod'])) && in_array('galerija.php', unserialize($_SESSION['mod']))))) {
        addtotitle($row['pavadinimas']);
			$nuoroda2 = mysql_query1("SELECT id FROM " . LENTELES_PRIESAGA . "galerija WHERE id > " . escape($url['m']) . " AND `categorija`=" . escape($row['kid']) . " AND `lang` = ".escape(lang())." order by id ASC LIMIT 1", 86400);
			$nuoroda = mysql_query1("SELECT id FROM " . LENTELES_PRIESAGA . "galerija WHERE id < " . escape($url['m']) . " AND categorija=" . escape($row['kid']) . " AND `lang` = ".escape(lang())." order by id DESC LIMIT 1", 86400);
			if (isset($row['Nick'])) {
				$autorius = user($row['Nick'], $row['nick_id'], $row['levelis']);
			} else {
				$autorius = $lang['system']['guest'];
			}

			//if ((int)$conf['galbalsuot'] == 1) {
				$balsavimas = rating_form($page,$row['nid']);
			/*} else {
				$balsavimas = '';
			}*/
			if (!empty($nuoroda2['id'])) {
				$text .= "<a href=\"".url("?id," . $url['id'] . ";m," . $nuoroda2['id']). "\" >< {$lang['admin']['gallery_prev']}</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			}
			if (!empty($nuoroda['id'])) {
				$text .= "<a href=\"".url("?id," . $url['id'] . ";m," . $nuoroda['id'] ). "\" >{$lang['admin']['gallery_next']} ></a>";
			}
			$text .= "
			<div id=\"gallery\" >
        <center>
          <a  rel=\"lightbox\" href=\"images/galerija/originalai/" . $row['file'] . "\" title=\"" . $row['pavadinimas'] . ": " . trimlink(strip_tags($row['apie']), 50) . "\">
            <img src=\"images/galerija/" . $row['file'] . "\" alt=\"\" />
          </a>
        </center>
      </div>
		<br />
		" . $balsavimas . "
		
		<b>{$lang['admin']['gallery_date']}:</b> " . date('Y-m-d H:i:s ', $row['data']) . "<br />\n";
			if (!empty($row['apie'])) { $text .= "<b>{$lang['admin']['gallery_about']}:</b> " . $row['apie'] . "<br />\n"; }
			$text .= "<b>{$lang['admin']['gallery_author']}:</b> " . $autorius . " <br />
		<center>
	";

			if (!empty($nuoroda2['id'])) {
				$text .= "<a href=\"".url("?id," . $url['id'] . ";m," . $nuoroda2['id']). "\" >< {$lang['admin']['gallery_prev']}</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			}
			if (!empty($nuoroda['id'])) {
				$text .= "<a href=\"".url("?id," . $url['id'] . ";m," . $nuoroda['id'] ). "\" >{$lang['admin']['gallery_next']} ></a>";
			}
			$text .= "</center>";

			lentele($row['pavadinimas'], $text);

			if ((int)$conf['galkom'] == 1) {
				include_once ("priedai/komentarai.php");
				komentarai($url['m']);
			}
		} else {
			klaida($lang['system']['warning'], $lang['admin']['gallery_cant']);
		}
	}
}
if (count($_GET) == 1) {
	if (kiek("galerija", "WHERE rodoma='TAIP' AND `lang` = ".escape(lang())."") == 0)
		klaida($lang['system']['warning'], $lang['system']['no_content']);
}
unset($text, $row, $sql);

?>
