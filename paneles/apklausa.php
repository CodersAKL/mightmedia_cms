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

unset($title);


$sql = mysql_query1("SELECT * ,autorius ,(SELECT `nick` FROM `" . LENTELES_PRIESAGA . "users` WHERE id=autorius LIMIT 1)AS nick FROM `" . LENTELES_PRIESAGA . "balsavimas` WHERE ijungtas='TAIP' ORDER BY `laikas` DESC LIMIT 1");
//$sql = mysql_fetch_assoc($sql);


if (isset($sql['klausimas'])) {
	if (isset($_SESSION['id'])) {
		$narys = $_SESSION['id'];
		$userid = $_SESSION['id'] . ";";
	} else {
		$userid = "";
		$narys = $_SERVER['REMOTE_ADDR'];
	}

	$ipasai = explode(";", $sql['ips']);
	$nariai = explode(";", $sql['nariai']);
	$ats = array();
	$atsa = array();
	$ats[1] = explode(";", $sql['pirmas']);
	$ats[2] = explode(";", $sql['antras']);
	$ats[3] = explode(";", $sql['trecias']);
	$ats[4] = explode(";", $sql['ketvirtas']);
	$ats[5] = explode(";", $sql['penktas']);

	$viso = ((int)$ats[1][1] + (int)$ats[2][1] + (int)$ats[3][1] + (int)$ats[4][1] + (int)$ats[5][1]);

	if ($viso != 0) {
		for ($i = 1; $i <= 5; $i++) {
	if (!empty($ats[$i][0])) {
				$atsa[$i] = "<br />" . $ats[$i][0] . " [" . $ats[$i][1] . "] <br />";
				$img = round((int)(100 / $viso * $ats[$i][1]));
                $atsa[$i] .= '
         <div style="width:'.$img.'%;background:url(images/balsavimas/center.png) top left repeat-x; height:10px">
         
			<div style="float:right;height:8px; width:1px; border-right:1px solid black;margin:1px -1px"></div>
			<div style="float:left;height:8px; width:1px; border-right:1px solid black;margin:1px -2px"></div>

		</div>
';
			} else {
				$atsa[$i] = '';
			}
		}

		$rezultatai = '<blockquote><div align="left"><center><b>' . (isset($sql['klausimas']) ? $sql['klausimas'] : "N/A") . '</b></center>' . $atsa[1] . $atsa[2] . $atsa[3] . $atsa[4] . $atsa[5] . '</div>';
	} else {
		$rezultatai = "";
	}


	if (!in_array($_SERVER['REMOTE_ADDR'], $ipasai) && !in_array($narys, $nariai)) {
		$text = '<blockquote><center><b>' . $sql['klausimas'] . '</b></center><br/>
			<form name="vote" action="" method="post">';
		for ($i = 1; $i <= 5; $i++) {
			if (!empty($ats[$i][0])) {
				$text .= '<label name="balsas"><input name="balsas" value="' . $ats[$i][0] . '" type="radio">' . $ats[$i][0] . '</label><br>';
			}
		}

		//visu balsavimas
		if (($sql['info'] == 'vis') || ($sql['info'] == 'nar' && isset($_SESSION['username']))) {
			if (isset($_POST['balsas']) && $_POST['vote'] == $lang['poll']['vote']) {
				if ($_POST['balsas'] == $ats[1][0]) {
					$stulp = 'pirmas';
					$atsakymas = $_POST['balsas'] . ";" . ($ats[1][1] + 1);
				}
				if ($_POST['balsas'] == $ats[2][0]) {
					$stulp = 'antras';
					$atsakymas = $_POST['balsas'] . ";" . ($ats[2][1] + 1);
				}
				if ($_POST['balsas'] == $ats[3][0]) {
					$stulp = 'trecias';
					$atsakymas = $_POST['balsas'] . ";" . ($ats[3][1] + 1);
				}
				if ($_POST['balsas'] == $ats[4][0]) {
					$stulp = 'ketvirtas';
					$atsakymas = $_POST['balsas'] . ";" . ($ats[4][1] + 1);
				}
				if ($_POST['balsas'] == $ats[5][0]) {
					$stulp = 'penktas';
					$atsakymas = $_POST['balsas'] . ";" . ($ats[5][1] + 1);
				}

				$result2 = mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "balsavimas` SET $stulp = " . escape($atsakymas) . ", ips=" . escape($sql['ips'] . $_SERVER['REMOTE_ADDR'] . ";") . ", nariai='" . $sql['nariai'] . $userid . "' WHERE `id`=" . escape($sql['id']));
				header("Location: " . $_SERVER['PHP_SELF'] . "");
			}

			$text .= '</br><input name="vote" type="submit" value="' . $lang['poll']['vote'] . '"></form>';
		}
		if ($sql['info'] == 'nar' && !isset($_SESSION['username'])) {
			$text .= '</br>' . $lang['poll']['cant'] . '.</form>';
		}


	} else {
		$text = $rezultatai;
	}

	$text .= '<br/> ' . $lang['poll']['votes'] . ': ' . $viso . '';
	$text .= '<br>	' . $lang['poll']['author'] . ': ' . user($sql['nick'], $sql['autorius']) . '';
	$text .= '</blockquote>';
} else {
	$text = '<blockquote><b>' . $lang['poll']['no'] . '.</b><br/>';
}
if (isset($conf['puslapiai']['blsavimo_archyvas.php'])) {
	$text .= '<a href=?id,' . $conf['puslapiai']['blsavimo_archyvas.php']['id'] . '>' . $lang['poll']['archive'] . '</a></blockquote>';
}
unset($rezultatai, $atsakymas, $ipsai, $nariai, $narys, $atsakymas, $ats, $atsa, $sql);

?>