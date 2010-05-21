<?php
if(!isset($_GET['m'])){
  $limit=20;
  $viso=kiek("poll_questions", "WHERE `shown`='1' AND `lang` = ".escape(lang())."");
  $p=(isset($_GET['p'])?$_GET['p']:0);
  $quests = mysql_query1("SELECT *, `id` as `qid`, (SELECT count(id) FROM `".LENTELES_PRIESAGA."poll_votes` WHERE `question_id`=`qid`) as `votes` FROM `".LENTELES_PRIESAGA."poll_questions` WHERE `shown`='1' AND `lang` = ".escape(lang())." ORDER BY `id` DESC LIMIT $p , $limit");
  $text = '<ul>';
  foreach ($quests as $row){
    $text .= "<li><a href =\"".url("?id,{$_GET['id']};m,{$row['id']}")."\">".input($row['question'])."</a> ({$row['votes']})</li>";
  }
  $text .= '</ul>';
  lentele($lang['poll']['archive'], $text);
  if ($viso > $limit) {
    lentele($lang['system']['pages'], puslapiai($p, $limit, $viso, 10));
  }
} else {
    $quest = mysql_query1("SELECT * FROM `".LENTELES_PRIESAGA."poll_questions` WHERE `shown`='1' AND `lang` = ".escape(lang())." AND `id`=".escape($_GET['m'])." ORDER BY `id` DESC LIMIT 1");
    if(isset($quest['question'])){
        $answers = mysql_query1("SELECT * FROM  `".LENTELES_PRIESAGA."poll_answers` WHERE `question_id`=".escape($quest['id'])." ORDER BY `id` ASC");
        $votes = mysql_query1("SELECT * FROM  `".LENTELES_PRIESAGA."poll_votes` WHERE `question_id`=".escape($quest['id'])."");
        $viso = 0;
        $voted = array();
        $text = '';
        foreach($votes as $vote){
          if(!isset($voted[$vote['answer_id']]))
            $voted[$vote['answer_id']] = 1;
          else
            $voted[$vote['answer_id']]++;
          $viso++;
        }
        foreach ($answers as $row) {
          $voted[$row['id']] = (isset($voted[$row['id']]) ? $voted[$row['id']] : 0);
          $text .= input($row['answer'])." (".$voted[$row['id']].")<br />   
        <div style=\"width:".round((int)(100 / $viso * $voted[$row['id']]))."%;background:url(images/balsavimas/center.png) top left repeat-x; height:10px\">
             
          <div style=\"float:right;height:8px; width:1px; border-right:1px solid black;margin:1px -1px\"></div>
          <div style=\"float:left;height:8px; width:1px; border-right:1px solid black;margin:1px -2px\"></div>

        </div><br />";
        }
        $text .= '<br />	' . $lang['poll']['author'] . ': ' . user($quest['author_name'], $quest['author_id']) . '';
        lentele(input($quest['question']), $text);
        include("priedai/komentarai.php");
        komentarai($url['m'],true);
    } else {
        klaida($lang['system']['error'], "{$lang['system']['pagenotfounfd']}.");
    }
}
/*
$limit=6;
$viso=kiek("balsavimas");
$p=(isset($_GET['p'])?$_GET['p']:0);
$sqlas = mysql_query1("SELECT * ,autorius ,(SELECT `nick` FROM `" . LENTELES_PRIESAGA . "users` WHERE id=autorius LIMIT 1)AS nick FROM `" . LENTELES_PRIESAGA . "balsavimas` WHERE ijungtas='TAIP' AND `lang` = ".escape(lang())." ORDER BY `laikas` DESC LIMIT $p , $limit");
//$sql = mysql_fetch_assoc($sql);
$text="";
foreach($sqlas as $sql){
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


		for ($i = 1; $i <= 5; $i++) {
	if (!empty($ats[$i][0])) {
				$atsa[$i] = "<br />" . $ats[$i][0] . " [" . (!empty($ats[$i][1])?$ats[$i][1]:0) . "] <br />";
				$img = @round((int)(100 / $viso * $ats[$i][1]));
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
	



	$text .= $rezultatai.'<br/> ' . $lang['poll']['votes'] . ': ' . $viso . '';
	$text .= '<br>	' . $lang['poll']['author'] . ': ' . user($sql['nick'], $sql['autorius']) . '';
	$text .= '</blockquote><hr></hr>';
} else {
	$text .= '<blockquote><b>' . $lang['poll']['no'] . '.</b><br/>';
}}
lentele($lang['poll']['archive'], $text);
if ($viso > $limit) {
	lentele($lang['system']['pages'], puslapiai($p, $limit, $viso, 10));
}
unset($rezultatai, $atsakymas, $ipsai, $nariai, $narys, $atsakymas, $ats, $atsa, $sql);
*/
?>