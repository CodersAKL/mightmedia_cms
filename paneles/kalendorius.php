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
$redagavimas = "Ne";

function svente($array, $siandien = '', $return = '') {

	//Gauname šiandienos (mėnesis-diena)
	if (!$siandien) {
		$siandien = date('n-j');
	}

	//Tikriname ar švenčių masyve nurodyta diena egzistuoja
	if (array_key_exists($siandien, $array)) {
		foreach ($array[$siandien] as $key => $val) {
			if (empty($return)) {
				$return .= $val;
			} //Jei išvedam šventę pirmą kartą
			else {
				$return .= ", <br />" . $val;
			} //Išvedame daugiau nei vieną šventę, atskiriame kableliais
		}
	}
	return $return;
}

//class maxCalendar{
function showCalendar($year = 0, $month = 0) {
	global $lang;
	/*$sventes = array( //Valstybinės šventės
	"1-1" => array("Naujieji metai", "Lietuvos vėliavos diena"),
	"1-13" => array("Laisvės gynėjų diena"),
	"2-16" => array("Lietuvos valstybės atkūrimo diena"),
	"3-11" => array("Lietuvos nepriklausomybės atkūrimo diena"),
	"5-1" => array("Tarptautinė darbo diena"),
	"5-4" => array("Motinos diena"),
	"6-24" => array("Rasos diena", "Jonininės"), 
	"7-6" => array("Valstybės diena", "Lietuvos karaliaus Mindaugo karūnavimo diena"), 
	"8-15" => array("Žolinės"), 
	"11-1" => array("Visų šventųjų diena"), 
	"12-25" => array("Kalėdos"), 
	"12-26" => array("Kalėdos (antra diena)"), //Lietuvos Respublikos atmintinos dienos
	"8-23" => array("Juodojo kaspino diena", "Baltijos kelio diena"), 
	"8-31" => array("Laisvės diena"), 
	"9-1" => array("Mokslo ir žinių diena"), 
	"9-8" => array("Šilinė (Švč. Mergelės Marijos gimimo diena)", "Vytauto Didžiojo karūnavimo diena"), //Kitos šventės
	"3-18" => array("FDisk gimtadienis")
	);*/
	$sventes = array();

	//Ieskom kieno gimtadieniai
	//$sql = "SELECT SQL_CACHE `id`,`nick`,`gim_data`,DATE_FORMAT(`gim_data`,'%e') AS `diena` FROM `" . LENTELES_PRIESAGA . "users` WHERE DATE_FORMAT(`gim_data`,'%c')=DATE_FORMAT(NOW(),'%c')";
	$sql = "SELECT SQL_CACHE `id`, `nick`, `gim_data`, DATE_FORMAT(`gim_data`,'%e') as `diena` FROM `" . LENTELES_PRIESAGA . "users` WHERE DATE_FORMAT(`gim_data`,'%c')=MONTH(NOW())";

	$sql = mysql_query1($sql) or die(mysql_error());
	if (mysql_num_rows($sql) > 0) {
		while ($row = mysql_fetch_assoc($sql)) {
			if($row['diena'] >= date("j")){
			$sventes[date('n') . "-" . $row['diena']][] = "<b>" . $row['nick'] . "</b> {$lang['calendar']['birthday']}. " . (amzius($row['gim_data']) + 1) . "m.";
			}
		}
	}

	// Get today, reference day, first day and last day info
	if (($year == 0) || ($month == 0)) {
		$referenceDay = getdate();

	} else {
		$referenceDay = getdate(mktime(0, 0, 0, $month, 1, $year));
	}
	$firstDay = getdate(mktime(0, 0, 0, $referenceDay['mon'], 1, $referenceDay['year']));
	$lastDay = getdate(mktime(0, 0, 0, $referenceDay['mon'] + 1, 0, $referenceDay['year']));
	$today = getdate();
	$ieskom = array("December", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November");
	$keiciam = array($lang['calendar']['December'], $lang['calendar']['January'], $lang['calendar']['February'], $lang['calendar']['March'], $lang['calendar']['April'], $lang['calendar']['May'], $lang['calendar']['June'], $lang['calendar']['July'], $lang['calendar']['August'], $lang['calendar']['September'], $lang['calendar']['October'], $lang['calendar']['November']);
	$referenceDay['month'] = str_replace($ieskom, $keiciam, $referenceDay['month']);
	$month = $referenceDay['mon'];
	$year = $referenceDay['year'];
	// Create a table with the necessary header informations
	$return = '<div class="kalendorius"><table width="100%" >
	<tr><th colspan="7">' . $referenceDay['month'] . ' - ' . $referenceDay['year'] . '</th></tr>
	<tr class="days"><td>' . $lang['calendar']['Mon'] . '</td><td>' . $lang['calendar']['Tue'] . '</td><td>' . $lang['calendar']['Wed'] . '</td><td>' . $lang['calendar']['Thu'] . '</td><td>' . $lang['calendar']['Fri'] . '</td><td>' . $lang['calendar']['Sat'] . '</td><td>' . $lang['calendar']['Sun'] . '</td></tr>';


	// Display the first calendar row with correct positioning
	$return .= '<tr>';
	if ($firstDay['wday'] == 0)
		$firstDay['wday'] = 7;
	for ($i = 1; $i < $firstDay['wday']; $i++) {
		$return .= '<td>&nbsp;</td>';
	}
	$actday = 0;
	for ($i = $firstDay['wday']; $i <= 7; $i++) {
		$actday++;
		$svente = svente($sventes, "" . $today['mon'] . "-" . $actday . "");
		if (($actday == $today['mday']) /*&& ($today['mon'] == $month)*/ ) {
			$class = "  style='border:1px solid red'";
		} else {
			$class = '';
		}
		if (!empty($svente)) {
			$return .= "<td$class ><div style='color:red' title=\"<b>{$lang['calendar']['this']}</b><br/>" . $svente . "<br/>\">$actday</div></td>";
		} else {
			$return .= "<td$class>$actday</td>";
		}
	}


	$return .= '</tr>';

	//Get how many complete weeks are in the actual month
	$fullWeeks = floor(($lastDay['mday'] - $actday) / 7);

	for ($i = 0; $i < $fullWeeks; $i++) {
		$return .= '<tr>';
		for ($j = 0; $j < 7; $j++) {
			$actday++;
			$svente = svente($sventes, "" . $today['mon'] . "-" . $actday . "");
			if (($actday == $today['mday']) && ($today['mon'] == $month)) {
				$class = "  style='border:1px solid red'";
			} else {
				$class = '';
			}
			if (!empty($svente)) {
				$return .= "<td$class ><div style='color:red' title=\"<b>{$lang['calendar']['this']}</b><br/>" . $svente . "<br/>\">$actday</div></td>";
			} else {
				$return .= "<td$class>$actday</td>";
			}
		}

		$return .= '</tr>';
	}

	//Now display the rest of the month
	if ($actday < $lastDay['mday']) {
		$return .= '<tr>';

		for ($i = 0; $i < 7; $i++) {
			$actday++;
			$svente = svente($sventes, "" . $today['mon'] . "-" . $actday . "");

			if (($actday == $today['mday']) && ($today['mon'] == $month)) {
				$class = "  style='border:1px solid red'";
			} else {
				$class = '';
			}

			if ($actday <= $lastDay['mday']) {
				if (!empty($svente)) {
					$return .= "<td$class ><div style='color:red' title=\"<b>{$lang['calendar']['this']}</b><br/>" . $svente . "<br/>\">$actday</div></td>";
				} else {
					$return .= "<td$class>$actday</td>";
				}
			} else {
				$return .= '<td>&nbsp;</td>';
			}
		}


		$return .= '</tr>';
	}

	$return .= '</table></div>';
	return $return;
}
$text = showCalendar();

?>
    
