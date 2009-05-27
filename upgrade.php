<?php

/**
 * @Projektas: MightMedia TVS
 * @license GNU General Public License v2
 * @$Revision: 121 $
 * @$Date: 2009-05-23 17:22:13 +0300 (Št, 23 Geg 2009) $
 * @Apie: upgrade.php - TVS atnaujinimo įrankis
 **/

header("Content-type: text/html; charset=utf-8");
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'Off');

if (!isset($_SESSION))
	session_start();
ob_start();
include_once("priedai/conf.php");
include_once("priedai/prisijungimas.php");
if(isset($_SESSION['id'])&&$_SESSION['id']==1){
// Sarašas failų kurių teisės turi suteikti svetainei įrašymo galimybę
$chmod_files[0] = "siuntiniai/media";
$chmod_files[] = "sandeliukas";




// Diegimo stadijų registravimas
if (!isset($_GET['step']) || empty($_GET['step'])) {
	$_SESSION['step'] = 1;
	$step = 1;
} else {
	if ($_GET['step'] != 0 && $_GET['step'] > 1) {
		$step = (int)$_GET['step'];
		if ($_SESSION['step'] == ($step - 1)) {
			$_SESSION['step'] = $step;
		}
	} else {
		header("Location: upgrade.php?step=" . $_SESSION['step']);
	}
}

// Duomenų bazės prisijungimo tikrinimo ir lentelių sukūrimo dalis
if (isset($_POST['next_msyql'])) {
			// Sukuriamos visos MySQL leneteles is SVN Trunk
		if (!file_exists('sql-upgrade.sql')) {
			$sql = file_get_contents('http://code.assembla.com/mightmedia/subversion/node/blob/v1/sql-upgrade.sql');
		} else {
			$sql = file_get_contents('sql-upgrade.sql');
		}

		// Paruošiam užklausas
		$sql = str_replace("CREATE TABLE IF NOT EXISTS `", "CREATE TABLE IF NOT EXISTS `" . LENTELES_PRIESAGA, $sql);
		$sql = str_replace("CREATE TABLE `", "CREATE TABLE IF NOT EXISTS `" . LENTELES_PRIESAGA, $sql);
		$sql = str_replace("INSERT INTO `", "INSERT INTO `" . LENTELES_PRIESAGA, $sql);
		$sql = str_replace("ALTER TABLE `", "ALTER TABLE `" . LENTELES_PRIESAGA, $sql);
		$sql = str_replace("UPDATE `", "UPDATE `" . LENTELES_PRIESAGA, $sql);

		// Prisijungiam prie duombazės
		mysql_query("SET NAMES utf8");

		// Atliekam SQL apvalymą
		$match = '';
		preg_match_all("/(?:CREATE|UPDATE|INSERT|ALTER).*?;[\r\n]/s", $sql, $match);

		$mysql_info = "<ol>";
		$mysql_error = 0;
		foreach ($match[0] as $key => $val) {
			if (!empty($val)) {
				$query = mysql_query($val);
				if (!$query) {
					$mysql_info .= "<li><b>Klaida:" . mysql_errno() . "</b> " . mysql_error() . "<hr><b>Užklausa:</b><br/>" . $val . "</li><hr>";
					$mysql_error++;
				}
			}
		}
		$mysql_info .= "</ol>";

		if ($mysql_error == 0) {
			$mysql_info = 'Lentelės sėkmingai atnaujintos. Galite tęsti atnaujinimą.';
			$next_mysql = '<center><input type="reset" value="Toliau >>" onClick="Go(\'3\');"></center>';
		} else {
			$next_mysql = '<center><input type="reset" value="Bandyti dar kartą" onClick="Go(\'2\');"></center>';
		}

	}
if(!isset($next_mysql)){
	$next_mysql = '<input name="next_msyql" type="submit" value="Atnaujinti lenteles">';
}

// Administratoriaus sukūrimo dalis


// Diegimo pabaiga
if (!empty($_POST['finish'])) {

	unlink('upgrade.php');
	//}
	header("Location: index.php");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="favicon.ico" />
<meta name="resource-type" content="document" />
<meta name="distribution" content="global" />
<meta name="author" content="CodeRS - MightMedia TVS scripts www.coders.lt" />
<meta name="copyright" content="copyright (c) by CodeRS www.coders.lt" />
<meta name="rating" content="general" />
<meta name="generator" content="notepad" />
<script src="javascript/jquery/jquery-1.3.1.min.js" type="text/javascript" ></script>

<script src="javascript/jquery/tooltip.js" type="text/javascript" ></script>
<title>MightMedia TVS atnaujinimas</title>
<link href="stiliai/default/default.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body>
<center>
<table border="0" cellpadding="2" cellspacing="5" width="80%">
<tbody>
<tr>
        <td width="25%" valign="top">
                <div class="title" title="Įdiegimo stadijos. Viską atlikite su įpatingu atidumu.">Įdiegimo stadijos</div>
                <div class="vidus">
                <ul>
<?php

$menu_pavad = array(1 => "Failų tikrinimas", 2 => "Duomenų bazės atnaujinimas",  3 => "Pabaiga");
foreach ($menu_pavad as $key => $value) {
	if ($key <= $step)
		echo "\t\t\t<li><img src=\"images/icons/tick_circle.png\" style=\"vertical-align: middle;\" /><font color=\"green\"><b>" . $value . "</b></font></li>";
	else
		echo "\t\t\t<li><img src=\"images/icons/cross_circle.png\" style=\"vertical-align: middle;\" /><b>" . $value . "</b></li>";
}

?>
                </ul>
                <hr />
                Produktas: <a href="http://www.mightmedia.lt/" target="_blank">MightMedia TVS</a><br />
        </td>
        <td valign="top">
        <div class="title">MightMedia TVS atnaujinimas</div>
        <div class="vidus">

<?php
// HTML DALIS - Failų tikrinimas
if ($step == 1) {

?>
        <h2>Failų tikrinimas</h2>
                        Žemiau surašyti failai kurie bus reikalingi įdiegiant šią sistemą. Jei sistema surado klaidų prašome jas ištaisyti ir spausti atnaujinti. Kitu atveju jums nebus leidžiama tęsti įdiegimo. <br /><br />
        <h2>Legenda</h2>
                        <img src="images/icons/tick.png" /> Jei prie failo nustatyta ši ikonėlė vadinasi failas yra tinkamai nustatytas.<br />
                        <img src="images/icons/cross.png" /> Jei rasite šią ikonėlę prie nurodyto failo tuomet reikia jį sutvarkyti.<br /><br />
                        <strong>Priminimas:</strong> Sutvarkyti failus, t.y. jums reikia atlikti <strong>chmod</strong>. Visur kur matote įkonėlę <img src="images/icons/cross.png" /> būtina nurodyti <strong>chmod      777</strong> FTP serveryje. <br /><br />
                        <table border="0">
                        <tr>
                                <td class="title" valign="top" width="10%">Failas</td>
                                <td class="title" valign="top" width="5%">Būsena</td>
                                <td class="title" valign="top" width="35%">Klaidos aprašymas</td>
                        </tr>
<?php

	$kartot = count($chmod_files) - 1;
	for ($i = 0; $i <= $kartot; $i++) {
		$teises = substr(sprintf('%o', fileperms($chmod_files[$i])), -4);
		if ($teises != 777 && $teises != 666 && !is_writable($chmod_files[$i])) {
			$file_error = 'Y';
		}
		echo "
                        <tr>
                                <td>" . $chmod_files[$i] . "</td>
                                <td>" . (($teises == 777) || ($teises == 666) || is_writable($chmod_files[$i]) ? "<img src=\"images/icons/tick.png\" />" : "<img src=\"images/icons/cross.png\" />") . "</td>
                                <td>" . (($teises == 777) || ($teises == 666) || is_writable($chmod_files[$i]) ? "" : "Būtina nurodyti chmod 777 failui <strong>" . $chmod_files[$i] . "</strong> kadangi esamas chmod yra <strong>" . $teises . "</strong>") . "</td>
                        </tr>";
	}
	echo "\t\t\t</table>\n<br /><br />\n";

	if (isset($file_error) && $file_error == 'Y')
		echo '<center><input type="reset" value="Atnaujinti" onClick="JavaScript:location.reload(true);"> <input type="reset" value="Jeigu esate isitikines, kad viskas gerai" onClick="Go(\'2\');"><center>';
	else
		echo '<center><input type="reset" value="Toliau >>" onClick="Go(\'2\');"></center>';

}
//END


// HTML DALIS - MySQL duomenų bazės nustatymai
if ($step == 2) {

?>
        <h2>MySQL Duomenų bazės atnaujinimas</h2>

                Norėdami atnaujinti lenteles, spauskite mygtuką, esantį žemiau.
                <form name="mysql" method="post">
                <table border="0" width="100%">
                
                <tr>
                        <td>
                                <form name="mysql" action="?step=2" method="post">
                                                     
                        </td>
                </tr>
                </table>
                <br />
                <center>
                <p id="mysql_response"><?php

	echo $next_mysql;

?></p>
                </center>
                <?php

	if (isset($mysql_info)) {

?>
                <br />
                <table border="0" width="50%">
                <tr>
                        <td class="title">Informacija</td>
                </tr>
                <tr>
                        <td><div id="info"><?php

		echo $mysql_info;

?></div></td>
                </tr>
                </table>
                <?php

	}

?>
                </form>
<?php

}
//END


// HTML DALIS - TVS administratoriaus sukūrimas


// HTML DALIS - Pabaiga
if ($step == 3) {

?>
                <h2>Pabaiga</h2>
                                Sveikiname įdiegus MightMedia TVS (Turinio Valdymo Sistemą).<br />
                                Spauskite "Pabaigti" galutinai užbaigti instaliaciją. Bus ištrintas <b>upgrade.php</b> failas. Dėl visa ko - patikrinkite prisijungę prie FTP serverio.<br /><br />
                                <form name="finish_install" method="post">
                                <center><input name="finish" type="submit" value="Pabaigti" /></center>
                                </form>
<?php

}
//END


?>
                </div>
        </td>
</tr>
</tbody>
</table>
</center>
<script type="text/javascript">

function Go(id) {
        document.location.href = "upgrade.php?step="+id;
}

</script>
</body>
</html>
<?php }else{?><head><title>MightMedia TVS atnaujinimas</title>
<link href="stiliai/default/default.css" rel="stylesheet" type="text/css" media="all" /></head><body><center><div class="title">Tik instaliuotojui</div><div class="vidus"><?php admin_login_form();?></div></center></body><?php }?>