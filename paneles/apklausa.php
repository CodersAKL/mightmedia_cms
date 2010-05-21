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
$quest = mysql_query1("SELECT * FROM `".LENTELES_PRIESAGA."poll_questions` WHERE `shown`='1' AND `lang` = ".escape(lang())." ORDER BY `id` DESC LIMIT 1");
if(isset($quest['question'])){
  $answers = mysql_query1("SELECT * FROM  `".LENTELES_PRIESAGA."poll_answers` WHERE `question_id`=".escape($quest['id'])." ORDER BY `id` ASC");
  $votes = mysql_query1("SELECT * FROM  `".LENTELES_PRIESAGA."poll_votes` WHERE `question_id`=".escape($quest['id'])."");
  $ip = getip();
  $show_rezults = false;
  $viso = 0;
  $voted = array();
  foreach($votes as $vote){
    if(!isset($voted[$vote['answer_id']]))
      $voted[$vote['answer_id']] = 1;
    else
      $voted[$vote['answer_id']]++;
    if ($ip == $vote['ip'])
      $show_rezults = true;
    $viso++;
  }

  if(!$show_rezults){
    if(isset($_POST['answer']) && ($quest['radio'] == 0 || ($quest['radio'] == 1 && isset($_SESSION['username'])))){
      if($quest['radio'] == 1)
        mysql_query1("INSERT INTO `".LENTELES_PRIESAGA."poll_votes` (`ip`, `question_id`, `answer_id`) VALUES (".escape($ip).", ".escape($quest['id']).", ".escape($_POST['answer'][0]).")");
      else
        foreach($_POST['answer'] as $answer)
          mysql_query1("INSERT INTO `".LENTELES_PRIESAGA."poll_votes` (`ip`, `question_id`, `answer_id`) VALUES (".escape($ip).", ".escape($quest['id']).", ".escape($answer).")");
        header("LOCATION: ".$_SERVER['HTTP_REFERER']);
    }
    $text = '<b style="text-align: center;">'.input($quest['question']).'</b><form method="post">';
    foreach ($answers as $row) {
      $text .= "<label><input type=\"".($quest['radio'] == 1 ? 'radio' : 'checkbox')."\" name=\"answer[]\" class=\"middle\" value=\"{$row['id']}\" /> ".input($row['answer'])."</label><br />";
    }
    if ($quest['radio'] == 0 || ($quest['radio'] == 1 && isset($_SESSION['username'])))
      $text .= '<div style="text-align: center;"><input name="vote" type="submit" value="' . $lang['poll']['vote'] . '" /></div>';
    $text .= '</form>';
  } else{
    $text = '<b style="text-align: center;">'.input($quest['question']).'</b><br />';
    foreach ($answers as $row) {
      $voted[$row['id']] = (isset($voted[$row['id']]) ? $voted[$row['id']] : 0);
      $text .= input($row['answer'])." (".$voted[$row['id']].")<br />   
    <div style=\"width:".round((int)(100 / $viso * $voted[$row['id']]))."%;background:url(images/balsavimas/center.png) top left repeat-x; height:10px\">
         
			<div style=\"float:right;height:8px; width:1px; border-right:1px solid black;margin:1px -1px\"></div>
			<div style=\"float:left;height:8px; width:1px; border-right:1px solid black;margin:1px -2px\"></div>

		</div><br />";
    }
    $text .= '<br />	' . $lang['poll']['author'] . ': ' . user($quest['author_name'], $quest['author_id']) . '';
    if (puslapis('blsavimo_archyvas.php')) 
        $text .= '<a href='.url('?id,' . $conf['puslapiai']['blsavimo_archyvas.php']['id'] ). '>' . $lang['poll']['archive'] . '</a>';
  }
} else 
  $text = '<b>' . $lang['poll']['no'] . '.</b><br />';
//if(!in_array(getip(), $votes[]))

/*
unset($title);


$sql = mysql_query1("SELECT * ,autorius ,(SELECT `nick` FROM `" . LENTELES_PRIESAGA . "users` WHERE id=autorius LIMIT 1)AS nick FROM `" . LENTELES_PRIESAGA . "balsavimas` WHERE ijungtas='TAIP' AND `lang` = ".escape(lang())." ORDER BY `laikas` DESC LIMIT 1");
//$sql = mysql_fetch_assoc($sql);


if (isset($sql['klausimas'])) {
	if (isset($_SESSION['id'])) {
		$narys = $_SESSION['id'];
		$userid = $_SESSION['id'] . ";";
	} else {
		$userid = "";
		$narys = getip();
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

		$rezultatai = '<div align="left"><center><b>' . (isset($sql['klausimas']) ? $sql['klausimas'] : "N/A") . '</b></center>' . $atsa[1] . $atsa[2] . $atsa[3] . $atsa[4] . $atsa[5] . '</div>';
	} else {
		$rezultatai = "";
	}


	if (!in_array(getip(), $ipasai) && !in_array($narys, $nariai)) {
		$text = '<center><b>' . $sql['klausimas'] . '</b></center><br/>
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

				$result2 = mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "balsavimas` SET $stulp = " . escape($atsakymas) . ", ips=" . escape($sql['ips'] .getip(). ";") . ", nariai='" . $sql['nariai'] . $userid . "' WHERE `id`=" . escape($sql['id']));
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

	$text .= '<br /> ' . $lang['poll']['votes'] . ': ' . $viso . '';
	$text .= '<br />	' . $lang['poll']['author'] . ': ' . user($sql['nick'], $sql['autorius']) . '';
	$text .= '';
} else {
	$text = '<b>' . $lang['poll']['no'] . '.</b><br />';
}
if (isset($conf['puslapiai']['blsavimo_archyvas.php'])) {
	$text .= '<a href='.url('?id,' . $conf['puslapiai']['blsavimo_archyvas.php']['id'] ). '>' . $lang['poll']['archive'] . '</a>';
}
unset($rezultatai, $atsakymas, $ipsai, $nariai, $narys, $atsakymas, $ats, $atsa, $sql);
*/
?>