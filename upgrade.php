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
ini_set('display_errors', 'On');
session_start();
ob_start();
include_once("priedai/conf.php");
include_once("priedai/prisijungimas.php");
if(isset($_SESSION['id']) && $_SESSION['id'] == 1){
  //į kokią versiją atnaujinam
  $versija = 1.4;
  // Sarašas failų kurių teisės turi suteikti svetainei įrašymo galimybę
  $chmod_files[0] = "siuntiniai/media";
  $chmod_files[] = "sandeliukas";
  $chmod_files[] = "images/avatars";
  //ką trinam
  $delete_files[] = "puslapiai/dievai/";
  $delete_files[] = "javascript/htmlarea/Xinha0.96beta2/";
  $delete_files[] = "javascript/forum/perview.php";

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
        $sql = file_get_contents('http://code.assembla.com/mightmedia/subversion/node/blob/v1/sql-upgrade-1.3to1.4.sql');
      } else {
        $sql = file_get_contents('sql-upgrade-1.3to1.4.sql');
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
        $next_mysql = '<center><input type="reset" value="Toliau >>" onClick="Go(\'3\');" class="submit"></center>';
      } else {
        $next_mysql = '<center><input type="reset" value="Bandyti dar kartą" onClick="Go(\'2\');" class="submit"></center>';
      }

    }
  if(!isset($next_mysql)){
    $next_mysql = '<input name="next_msyql" type="submit" value="Atnaujinti lenteles" class="submit">';
  }

  // Administratoriaus sukūrimo dalis


  // Diegimo pabaiga
  if (!empty($_POST['finish'])) {

    unlink('upgrade.php');
    //}
    header("Location: index.php");
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="resource-type" content="document" />
<meta name="distribution" content="global" />
<meta name="author" content="CodeRS - MightMedia TVS scripts www.coders.lt" />
<meta name="copyright" content="copyright (c) by CodeRS www.coders.lt" />
<meta name="rating" content="general" />
<meta name="generator" content="notepad" />
<script src="javascript/jquery/jquery-1.3.1.min.js" type="text/javascript" ></script>
<script src="javascript/jquery/tooltip.js" type="text/javascript" ></script>
<title>MightMedia TVS atnaujinimas</title>
<link rel="stylesheet" type="text/css" media="all" href="stiliai/remontas/css/default.css" />
</head>
<body>
<div id="admin_main">
			  <div id="admin_header" style="height: 15px;">
<div style="text-align: right;color: #666;"><b><?php echo date('H:i:s'); ?></b></div>
			  </div>

		<div id="admin_hmenu" style="font-weight:bold; font-size:25px; color: #666; padding: 10px; margin-bottom: 10px;"><?php echo input(strip_tags($conf['Pavadinimas']));?></div>
<?php if(isset($_SESSION['id'])&&$_SESSION['id']==1) {?>
<center>
<?php if(versija() != $versija) echo "<div class=\"msg\" style=\"width: 80%;\"><b>Nepakeisti senosios versijos failai!</b><br /> <small>Naudodamiesi FTP naršykle, atnaujinkite senos <b>".versija()."</b> versijos failus į naująją <b>$versija</b> versiją.</div>"; ?>
<table border="0" cellpadding="2" cellspacing="5" width="80%" id="container">
<tbody>
<tr>
        <td width="30%" valign="top" class="left">
                <h1 title="Įdiegimo stadijos. Viską atlikite su įpatingu atidumu.">Įdiegimo stadijos</h1>
                <div class="vidus">
                <ul>
<?php

$menu_pavad = array(1 => "Failų tikrinimas", 2 => "Duomenų bazės atnaujinimas", 3 => "conf.php keitimas",  4 => "Pabaiga");
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
        <td valign="top" class="right">
        <h1>MightMedia TVS atnaujinimas</h1>
        <div class="vidus">

<?php
// HTML DALIS - Failų tikrinimas
if ($step == 1) {

?>
        <h2>Failų tikrinimas</h2>
        
                   
        
                                Žemiau surašyti failai, kurių keitimas bus reikalingas atnaujinant šią sistemą. Jei sistema surado klaidų prašome jas ištaisyti ir spausti atnaujinti. Kitu atveju jums nebus leidžiama tęsti įdiegimo. <br /><br />
        <h2>Legenda</h2>
                        <img src="images/icons/tick.png" /> Jei prie failo nustatyta ši ikonėlė vadinasi failas yra paruoštas sistemai.<br />
                        <img src="images/icons/cross.png" /> Jei rasite šią ikonėlę prie nurodyto failo tuomet reikia atlikti užduotį, aprašytą „Klaidos aprašymas“ stulpelyje.<br /><br />
                        
                        <table border="0" class="table">
                        <tr>
                                <th class="th" valign="top" width="10%">Failas</th>
                                <th class="th" valign="top" width="5%">Būsena</th>
                                <th class="th" valign="top" width="35%">Klaidos aprašymas</th>
                        </tr>
<?php

	$kartot = count($chmod_files) - 1;
	for ($i = 0; $i <= $kartot; $i++) {
		$teises = substr(sprintf('%o', fileperms($chmod_files[$i])), -4);
		if ($teises != 777 && $teises != 666 && !is_writable($chmod_files[$i])) {
			$file_error = 'Y';
		}
		echo "
                        <tr class=\"tr\">
                                <td>" . $chmod_files[$i] . "</td>
                                <td>" . (($teises == 777) || ($teises == 666) || is_writable($chmod_files[$i]) ? "<img src=\"images/icons/tick.png\" />" : "<img src=\"images/icons/cross.png\" />") . "</td>
                                <td>" . (($teises == 777) || ($teises == 666) || is_writable($chmod_files[$i]) ? "-" : "Būtina nurodyti chmod 777 failui <strong>" . $chmod_files[$i] . "</strong> kadangi esamas chmod yra <strong>" . $teises . "</strong>") . "</td>
                        </tr>";
	}
	foreach ($delete_files as $file) {
		if (file_exists($file)) {
			$file_error = 'Y';
		}
		echo "
                        <tr class=\"tr\">
                                <td>" . $file . "</td>
                                <td>" . (!file_exists($file) ? "<img src=\"images/icons/tick.png\" />" : "<img src=\"images/icons/cross.png\" />") . "</td>
                                <td>" . (!file_exists($file) ? "-" : "Būtina ištrinti <strong>" . $file . "</strong> ") . "</td>
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
                        <td class="title"><b>Informacija</b></td>
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
if ($step == 3) {
?>
<h2>conf.php failo keitimas</h2>
Atlikite žemiau nurodytus pakeitimus <i>priedai/conf.php</i> faile.
<h2>Legenda</h2>
                        <img src="images/icons/tick.png" /> Jei prie užduoties matote šią ikoną, ji atlikta teisingai.<br />
                        <img src="images/icons/cross.png" /> Jei prie užduoties matote šią ikoną, ji atlikta neteisingai.<br /><hr /><br />
                        
         <div class="tr"><img src="<?php echo (isset($update_url) ? 'images/icons/tick.png' : 'images/icons/cross.png');  ?>" /> priedai/conf.php faile, prieš <input type="text" value='$prisijungimas_prie_mysql = mysql_connect($host, $user, $pass)' /> įklijuokite šį kodą <input type="text" value='$update_url = "http://www.assembla.com/code/mightmedia/subversion/node/blob/naujienos.json?jsoncallback=?";' /></div> <div class="tr">
        <img src="<?php echo (!function_exists('lentele') ? 'images/icons/tick.png' : 'images/icons/cross.png');  ?>" /> Failo apačioje ištrinkite kodą <input type="text" value='require_once(realpath(dirname(__file__))."/../stiliai/".$conf[&#039;Stilius&#039;]."/sfunkcijos.php");' />
     </div>    <div class="tr">Atlikę šias užduotis spauskite „Atnaujinti“ ir įsitikinlite, kadužduotys atliktos teisingai (žr. į ikonas šalia užduoties)</div>
<center><input type="reset" value="Atnaujinti" onClick="JavaScript:location.reload(true);"> <input type="reset" value="Toliau >>" onClick="Go('3');"><center>
<?php
}

// HTML DALIS - Pabaiga
if ($step == 4) {

?>
                <h2>Pabaiga</h2>
                                Sveikiname įdiegus MightMedia TVS (Turinio Valdymo Sistemą).<br />
                                Spauskite "Pabaigti" galutinai užbaigti instaliaciją. Bus ištrintas <b>upgrade.php</b> failas. Dėl visa ko - patikrinkite prisijungę prie FTP serverio.<br /><br />
                                <form name="finish_install" method="post">
                                <center><input name="finish" type="submit" value="Pabaigti" class="submit" /></center>
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

</script><?php } else {?>
<center id="container"><h1>Tik tinklalapio savininkui</h1><div class="vidus"><?php echo admin_login_form();?></div></center>
<?php } ?>
<span style="text-align: right;position:absolute;bottom:0;right:0; padding: 5px;">&copy; <a href="http://mightmedia.lt" style="color: #666;" target="_blank">MightMedia TVS</a></span>
</body>
</html>
<?php
ob_end_flush();
?>