<?php
function svente($array, $siandien = '', $return = '') {

//Gauname ðiandienos (mënesis-diena)
	if (!$siandien) {
		$siandien = date('n-j');
	}

	//Tikriname ar ðvenèiø masyve nurodyta diena egzistuoja
	if (array_key_exists($siandien, $array)) {
		foreach ($array[$siandien] as $key => $val) {
			if (empty($return)) {	//Jei iðvedam ðventæ pirmà kartà
				$return .= $val;
			}
			else {
				$return .= ", <br />" . $val;
			}	//Iðvedame daugiau nei vienà ðventæ, atskiriame kableliais
		}
	}
	return $return;
}
function gimtadienis() {
	$gimtadieniai=array();
	$sql = "SELECT id,nick,gim_data,DAY(gim_data) AS diena FROM `" . LENTELES_PRIESAGA . "users` WHERE MONTH(gim_data)=MONTH(NOW())";
	$sql = mysql_query1($sql);
	foreach ($sql as $row) {
		$gimtadieniai[date('n') . "-" . $row['diena']][] = user($row['nick'],$row['id'],'',"sukako " . (amzius($row['gim_data'])) . "m.");
	}

	if(isset($gimtadieniai[date('n-j')])) {
		return implode(", ",$gimtadieniai[date('n-j')]);
	}

}

$sventes = array(
	 //Valstybines ðventes
	 "1-1" => array("Naujieji metai", "Lietuvos veliavos diena"),
	 "1-13" => array("Laisves gyneju diena"),
	 "2-16" => array("Lietuvos valstybes atkurimo diena"),
	 "3-11" => array("Lietuvos nepriklausomybes atkurimo diena"),
	 "5-1" => array("Tarptautine darbo diena"),
	 "5-4" => array("Motinos diena"),
	 "6-24" => array("Rasos diena", "Joninines"),
	 "7-6" => array("Valstybes diena", "Lietuvos karaliaus Mindaugo karunavimo diena"),
	 "8-15" => array("Þolines"),
	 "11-1" => array("Visu ðventuju diena"),
	 "12-25" => array("Kaledos"),
	 "12-26" => array("Kaledos (antra diena)"),
	 //Lietuvos Respublikos atmintinos dienos
	 "8-23" => array("Juodojo kaspino diena", "Baltijos kelio diena"),
	 "8-31" => array("Laisves diena"),
	 "9-1" => array("Mokslo ir þiniu diena"),
	 "9-8" => array("Ðiline (Ðvc. Mergeles Marijos gimimo diena)", "Vytauto Didþiojo karunavimo diena")
);

$ieskom = array(
	 "December",
	 "January",
	 "February",
	 "March",
	 "April",
	 "May",
	 "June",
	 "July",
	 "August",
	 "September",
	 "October",
	 "November"
);
$keiciam = array(
	 $lang['calendar']['December'],
	 $lang['calendar']['January'],
	 $lang['calendar']['February'],
	 $lang['calendar']['March'],
	 $lang['calendar']['April'],
	 $lang['calendar']['May'],
	 $lang['calendar']['June'],
	 $lang['calendar']['July'],
	 $lang['calendar']['August'],
	 $lang['calendar']['September'],
	 $lang['calendar']['October'],
	 $lang['calendar']['November']
);
$days_en = array(
	 'Monday',
	 'Tuesday',
	 'Wednesday',
	 'Thursday',
	 'Friday',
	 'Saturday',
	 'Sunday');

$dienos= array(
	 $lang['calendar']['Monday'],
	 $lang['calendar']['Tuesday'],
	 $lang['calendar']['Wednesday'],
	 $lang['calendar']['Thursday'],
	 $lang['calendar']['Friday'],
	 $lang['calendar']['Saturday'],
	 $lang['calendar']['Sunday']
);

$text='<center>
<b> '.date('Y').'</b><br />
<b> '.str_replace($ieskom,$keiciam,date('F')).'</b><br />
<span style="font-family: Arial; font-style: normal; font-variant: normal; font-weight: bold; font-size: 65px; line-height: normal; font-size-adjust: none; font-stretch: normal; -x-system-font: none;">'.date('d').'</span><br />
<b>'.str_replace($days_en ,$dienos,date('l')).'</b><br />
<b>'.date('W').' savaitë</b><br />
';
$svent=svente($sventes);
$gim=gimtadienis();
if (!empty($svent)) {
	$text.="<i><b>".$svent."</b></i>";
}
if(!empty($gim)) {
	$text.="<br /><b>{$lang['calendar']['today']}:</b><br />".$gim;
}
$text.="</center>";
?>