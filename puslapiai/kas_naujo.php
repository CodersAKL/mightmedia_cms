<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author$
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision$
 * @$Date$
 * */

require_once ("priedai/class.php");
$bla = new forma();
$dienos = array();
for ($i = 0; $i < 101; $i++)
	$dienos[$i] = $i;
$forma = array("Form" => array("action" => "", "method" => "post", "name" => "naujienos"), "Kelių dienų naujienas norėtumėte peržvelgti?:" => array("type" => "select", "value" => $dienos, "name" => "dienos", "class" => "input", "selected" => (isset($_POST['dienos']) ? (int) $_POST['dienos'] : 0)), " " => array("type" => "submit", "name" => "ziureti", "value" => "Žiūrėti"));
lentele('Kas naujo?', $bla->form($forma));
if (isset($_POST['dienos'])) {
	$time = time() - 24 * 3600 * (int) $_POST['dienos'];

	//forume
	if (isset($conf['puslapiai']['frm.php']['id'])) {
		$q = mysql_query1("SELECT `id`,`id` AS strid,`tid`,`tid` as `temosid`,`pav`,`autorius`,`last_data`,`last_nick`, (SELECT COUNT(*) FROM `" . LENTELES_PRIESAGA . "d_zinute` WHERE `tid`=`temosid` AND`sid`=strid ) AS viso	 FROM `" . LENTELES_PRIESAGA . "d_straipsniai` WHERE `last_data` >= " . escape($time) . " ORDER BY `last_data` DESC", 3600);
		if (sizeof($q) > 0) {
			$text = '';
			foreach ($q as $row) {
				$text .= "\t <a href='" . url("?id," . $conf['puslapiai']['frm.php']['id'] . ";t," . $row['id'] . ";s," . $row['tid'] . ";p," . ((int) ($row['viso'] / 15 - 0.1) * 15)) . "#end'>" . trimlink($row['pav'], 40) . "</a> (" . date('Y-m-d H:i:s', $row['last_data']) . " - " . $row['last_nick'] . ")<br />\n";
			}
			lentele($lang['new']['forum'], $text);
			unset($text, $row, $q);
		}
	}
	//naujienose
	if (isset($conf['puslapiai']['naujienos.php']['id'])) {
		$q = mysql_query1("SELECT `id`, `pavadinimas`,`data`,`autorius` FROM `" . LENTELES_PRIESAGA . "naujienos` WHERE `data` >= " . escape($time) . " AND `rodoma`='TAIP' ORDER BY `data` DESC", 3600);
		if (sizeof($q) > 0) {
			$text = '';
			foreach ($q as $row) {
				$text .= "<a href='" . url("?id," . $conf['puslapiai']['naujienos.php']['id'] . ";k," . $row['id']) . "'>" . trimlink($row['pavadinimas'], 40) . "</a> (" . date('Y-m-d H:i:s', $row['data']) . " - " . $row['autorius'] . ")<br />\n";
			}
			lentele($lang['new']['news'], $text);
			unset($text, $row, $q);
		}
	}
	//galerijoj
	if (isset($conf['puslapiai']['galerija.php']['id'])) {
		$q = mysql_query1("SELECT `ID`, `apie`, `pavadinimas`,`data`,`autorius` FROM `" . LENTELES_PRIESAGA . "galerija` WHERE `data`>=" . escape($time) . " AND `rodoma`='TAIP' ORDER BY `data` DESC", 3600);
		if (sizeof($q) > 0) {
			$text = '';
			foreach ($q as $row) {
				$text .= "<a href='" . url("?id," . $conf['puslapiai']['galerija.php']['id'] . ";m," . $row['ID']) . ";'>" . trimlink($row['pavadinimas'], 40) . "</a> (" . date('Y-m-d H:i:s', $row['data']) . ")<br />\n";
			}
			lentele($lang['new']['gallery'], $text);
			unset($text, $row, $q);
		}
	}
	//siuntiniuose
	if (isset($conf['puslapiai']['siustis.php']['id'])) {
		$q = mysql_query1("SELECT `ID`, `apie`, `pavadinimas`, `categorija`,`autorius`,`data` FROM `" . LENTELES_PRIESAGA . "siuntiniai` WHERE `data`>=" . escape($time) . " AND `rodoma`='TAIP' ORDER BY `data` DESC", 3600);
		if (sizeof($q) > 0) {
			$text = '';
			foreach ($q as $row) {
				$text .= "<a href='" . url("?id," . $conf['puslapiai']['siustis.php']['id'] . ";k," . $row['categorija'] . ";v," . $row['ID']) . "'>" . trimlink($row['pavadinimas'], 40) . "</a> (" . date('Y-m-d H:i:s', $row['data']) . ")<br />\n";
			}
			lentele($lang['new']['downloads'], $text);
			unset($text, $row, $q);
		}
	}
	//straipsniai
	if (isset($conf['puslapiai']['straipsnis.php']['id'])) {
		$q = mysql_query1("SELECT `id`, `t_text`, `pav`, `kat` FROM `" . LENTELES_PRIESAGA . "straipsniai` WHERE `date`>=" . escape($time) . " AND `rodoma`='TAIP'  ORDER BY `date` DESC", 3600);
		if (sizeof($q) > 0) {
			$text = '';
			foreach ($q as $row) {
				$text .= "<a href='" . url("?id," . $conf['puslapiai']['straipsnis.php']['id'] . ";m," . $row['id']) . "'>" . trimlink($row['pav'], 40) . "</a> (" . date('Y-m-d H:i:s', $row['data']) . " - " . $row['autorius'] . ")<br />\n";
			}
			lentele($lang['new']['articles'], $text);
			unset($text, $row, $q);
		}
	}
	$q = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "kom` WHERE `data`>=" . escape($time) . " ORDER BY `data`", 3600);
	if (sizeof($q) > 0) {
		$text = '';
		foreach ($q as $row) {
			if ($row['pid'] == 'puslapiai/naujienos' && isset($conf['puslapiai']['naujienos.php']['id'])) {
				$link = "k," . $row['kid'];
			} elseif ($row['pid'] == 'puslapiai/siustis' && isset($conf['puslapiai']['siustis.php']['id'])) {
				$linkas = mysql_query1("SELECT categorija FROM `" . LENTELES_PRIESAGA . "siuntiniai` WHERE `ID`='" . $row['kid'] . "'LIMIT 1");
				$link = "k," . $linkas['categorija'] . ";v," . $row['kid'] . "";
			} elseif ($row['pid'] == 'puslapiai/straipsnis' && isset($conf['puslapiai']['straipsnis.php']['id'])) {
				$link = "m," . $row['kid'];
			} elseif ($row['pid'] == 'puslapiai/galerija' && isset($conf['puslapiai']['galerija.php']['id'])) {
				$link = "m," . $row['kid'];
			} elseif ($row['pid'] == 'puslapiai/view_user' && isset($conf['puslapiai']['view_user.php']['id'])) {
				$link = "m," . $row['kid'];
			} elseif ($row['pid'] == 'puslapiai/blsavimo_archyvas' && isset($conf['puslapiai']['blsavimo_archyvas.php']['id'])) {
				$link = "m," . $row['kid'];
			}

			$file = str_replace('puslapiai/', '', $row['pid']);
			if (isset($conf['puslapiai'][$file . ".php"]['id'])) {
				if (strlen($row['nick']) > 15) {
					$ar = unserialize($row['nick']);
					$author = $ar[0];
				} else {
					$author = $row['nick'];
				}
				$text .= "<a href='" . url("?id," . $conf['puslapiai'][$file . '.php']['id'] . ";" . $link . "#" . $row['id']) . "' title=\"{$lang['new']['author']}: <b>" . $author . "</b><br/>{$lang['new']['date']}: <b>" . date('Y-m-d H:i:s ', $row['data']) . "</b><br/>\">" . trimlink($row['zinute'], 40) . "</a> (" . date('Y-m-d H:i:s', $row['data']) . " - " . $author . ")<br />\n";
			}
		}
		lentele($lang['new']['comments'], $text);
		unset($text, $row, $q);
	}
}
?>
