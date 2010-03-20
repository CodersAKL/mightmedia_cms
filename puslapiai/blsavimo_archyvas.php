<?php
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

?>