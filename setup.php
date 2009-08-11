<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author$
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision$
 * @$Date$
 * @Apie: setup.php - TVS diegimo įrankis
 **/

header("Content-type: text/html; charset=utf-8");

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'Off');


if (!isset($_SESSION))
	session_start();
ob_start();

//slaptaþodþio kodavimas
function koduoju($pass) {
	return md5(sha1(md5($pass)));
}

// Sarašas failų kurių teisės turi suteikti svetainei įrašymo galimybę
$chmod_files[0] = "priedai/conf.php";
$chmod_files[] = "setup.php";
$chmod_files[] = ".htaccess";
$chmod_files[] = "siuntiniai/failai";
$chmod_files[] = "siuntiniai/images";
$chmod_files[] = "siuntiniai/media";
$chmod_files[] = "galerija";
$chmod_files[] = "galerija/originalai";
$chmod_files[] = "sandeliukas";
$chmod_files[] = "galerija/mini";
$chmod_files[] = "puslapiai";
$chmod_files[] = "paneles";
$chmod_files[] = "images/avatars";
// Unikalus kodas, naudojamas svetainės identifikacijai.
$slaptas = md5(uniqid(rand(), true));

// Sugeneruojam atsitiktinį duomenų bazės prieždėlį
function random($return = '') {
	$simboliai = "abcdefghijkmnopqrstuvwxyz0123456789";
	for ($i = 1; $i < 3; ++$i) {
		$num = rand() % 33;
		$return .= substr($simboliai, $num, 1);
	}
	return $return . '_';
}

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
		header("Location: setup.php?step=" . $_SESSION['step']);
	}
}

// Duomenų bazės prisijungimo tikrinimo ir lentelių sukūrimo dalis
if (isset($_POST['next_msyql'])) {
	$host = strip_tags($_POST['host']);
	$user = strip_tags($_POST['user']);
	$pass = strip_tags($_POST['pass']);
	$db = strip_tags($_POST['db']);
	$prefix = (isset($_POST['prefix']) ? strip_tags($_POST['prefix']) : random());

	$_SESSION['mysql']['host'] = $host;
	$_SESSION['mysql']['user'] = $user;
	$_SESSION['mysql']['pass'] = $pass;
	$_SESSION['mysql']['db'] = $db;
	$_SESSION['mysql']['prefix'] = $prefix;

	/**
	 * Reikalinga papildomai patikrinti (TODO:)
	 * 1. Ar tinkamas hostas
	 * 2. Ar useris ir pass veikia
	 * 3. Reikalinga funkcija kuri nuskaitytų sql.sql failą tiek local tiek remote. TUri būti universalus ir veikti ant SAFE_MODE režimo
	 */

	$mysql_con = mysql_connect($host, $user, $pass);
	mysql_select_db($db);
	if (!$mysql_con) {
		$mysql_info = '<b>Klaida:</b> ' . mysql_error($mysql_con) . '<br/><b>Klaidos NR: </b>' . mysql_errno($mysql_con);
	}
	if (mysql_errno($mysql_con) == 1049) {
		$next_mysql = '<input name="next_msyql" type="submit" value="Bandyti sukurti duombazę">';
		mysql_connect($host, $user, $pass);
		mysql_query("CREATE DATABASE `$db` DEFAULT CHARACTER SET utf8 COLLATE utf8_lithuanian_ci");
		mysql_select_db($db);
	} else {
		$mysql_info = '<strong>Prisijungimas prie MySQL serverio pavyko.</strong><br />';

		// Sukuriamos visos MySQL leneteles is SVN Trunk
		if (!file_exists('sql.sql')) {
			$sql = file_get_contents('http://code.assembla.com/mightmedia/subversion/node/blob/v1/sql.sql');
		} else {
			$sql = file_get_contents('sql.sql');
		}

		// Paruošiam užklausas
		$sql = str_replace("CREATE TABLE IF NOT EXISTS `", "CREATE TABLE IF NOT EXISTS `" . $prefix, $sql);
		$sql = str_replace("CREATE TABLE `", "CREATE TABLE IF NOT EXISTS `" . $prefix, $sql);
		$sql = str_replace("INSERT INTO `", "INSERT INTO `" . $prefix, $sql);
		$sql = str_replace("UPDATE `", "UPDATE `" . $prefix, $sql);

		// Prisijungiam prie duombazės
		mysql_connect($host, $user, $pass);
		mysql_select_db($db);
		mysql_query("SET NAMES utf8");

		// Atliekam SQL apvalymą
		$match = '';
		preg_match_all("/(?:CREATE|UPDATE|INSERT).*?;[\r\n]/s", $sql, $match);

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
			$mysql_info = 'Lentelės sėkmingai sukurtos. Galite tęsti instaliaciją.';
			$next_mysql = '<center><input type="reset" value="Toliau >>" onClick="Go(\'4\');"></center>';
		} else {
			$next_mysql = '<center><input type="reset" value="Bandyti dar kartą" onClick="Go(\'3\');"></center>';
		}

	}
} else {
	$next_mysql = '<input name="next_msyql" type="submit" value="Sukurti lenteles">';
}

// Administratoriaus sukūrimo dalis
if (!empty($_POST['acc_create'])) {
	$user = htmlspecialchars($_POST['user']);
	$pass = (!empty($_POST['pass']) ? koduoju($_POST['pass']) : "");
	$pass2 = (!empty($_POST['pass2']) ? koduoju($_POST['pass2']) : "");
	$email = htmlspecialchars($_POST['email']);
	$_SESSION['admin']['email'] = $email;
	if ($pass != $pass2) {
		$admin_info = 'Nesutampa slaptažodžiai';
	} else {
		if (!empty($user) && !empty($pass) && !empty($pass2) && !empty($email)) {
			mysql_connect($_SESSION['mysql']['host'], $_SESSION['mysql']['user'], $_SESSION['mysql']['pass']);
			mysql_query("SET NAMES utf8");
			mysql_select_db($_SESSION['mysql']['db']);
			mysql_query("UPDATE `" . $_SESSION['mysql']['prefix'] . "users` SET `nick`='" . $user . "', `pass`='" . $pass . "', `email`='" . $email . "', `reg_data`='" . time() . "', `ip`=INET_ATON('" . $_SERVER['REMOTE_ADDR'] . "') WHERE `nick`='Admin'") or die(mysql_error());
			mysql_query("INSERT INTO `" . $_SESSION['mysql']['prefix'] . "private_msg` (`id`, `from`, `to`, `title`, `msg`, `read`, `date`) VALUES (2, 'CodeRS', '" . $user . "', 'Administracija praneša!', 'Labadiena. Sveikiname sėkmingai įdiegus MightMedia TVS. Ačiū, kad naudojatės [b]CodeRS[/b] produktu.', 'NO', '" . time() . "');") or die(mysql_error());
			mysql_query("INSERT INTO `" . $_SESSION['mysql']['prefix'] . "nustatymai` (`key`, `val`) VALUES ('Pastas', '".$email."');") or die(mysql_error());
			header("Location: setup.php?step=5");
		} else {
			$admin_info = 'Prašome užpildyti visus laukus';
		}
	}
}

// Diegimo pabaiga
if (!empty($_POST['finish'])) {
	$content = <<< HTML
<?php
if (basename(\$_SERVER['PHP_SELF']) == 'conf.php') { die("Tiesioginis kreipimąsis į failą draudžiamas"); }
define('SETUP',true);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'Off');	//Klaidu pranesimai On/Off
\$host = "{$_SESSION['mysql']['host']}";	//mysql sererio adresas
\$user = "{$_SESSION['mysql']['user']}";	//db vartotojas
\$pass = "{$_SESSION['mysql']['pass']}";	//slaptazodis
\$db = "{$_SESSION['mysql']['db']}";	//Duomenu baze
define("LENTELES_PRIESAGA", "{$_SESSION['mysql']['prefix']}");	//Lenteliu pavadinimu priesaga
\$slaptas = "{$slaptas}";	//Sausainiams ir kitai informacijai

//Admin paneles vartotojas ir slaptazodis
\$admin_name="Admin";	//useris
\$admin_pass="admin";	//slaptazodis
\$admin_email="{$_SESSION['admin']['email']}";	//e-pastas

// DB Prisijungimas
\$prisijungimas_prie_mysql = mysql_connect(\$host, \$user, \$pass) or die("<center><h1>Klaida 1</h1><br/>Svetainė laikinai neveikia. <h4>Prašome užsukti vėliau</h4></center>");
mysql_select_db(\$db,\$prisijungimas_prie_mysql) or die("<center><h1>Klaida 2</h1><br/>Svetainė neidiegta. <h4>Prašome užsukti vėliau</h4></center>");
mysql_query("SET NAMES 'utf8'",\$prisijungimas_prie_mysql);
\$sql = mysql_query("SELECT * FROM `".LENTELES_PRIESAGA."nustatymai`",\$prisijungimas_prie_mysql);
if(mysql_num_rows(\$sql) > 1) while(\$row = mysql_fetch_assoc(\$sql)) \$conf[\$row['key']] = \$row['val'];
unset(\$row,\$sql,\$user,\$host,\$pass,\$db);
//kalba
if (isset(\$conf['kalba'])) {
    require_once (realpath(dirname(__file__)) . '/../lang/' . \$conf['kalba'] . '');
} else {
    require_once (realpath(dirname(__file__)) . '/../lang/lt.php');
}
//Jeigu nepavyko nuskaityti nustatymų
if (!isset(\$conf) || empty(\$conf)) die("<center><h1>Klaida 3</h1><br/>Svetainė laikinai neveikia. <h4>Prašome užsukti vėliau</h4></center>");

//Stiliaus funkcijos
require_once(realpath(dirname(__file__))."/../stiliai/".\$conf['Stilius']."/sfunkcijos.php");
// Inkludinam tai ko mums reikia
require_once(realpath(dirname(__file__))."/funkcijos.php");
?>
HTML;
	//if (is_writable($chmod_files[0])) {
	if (!$handle = fopen($chmod_files[0], 'w')) {
		die("Nepavyko atverti failo (" . $chmod_files[0] . ")");
	}
	if (fwrite($handle, $content) === false) {
		die("Nepavyko nieko įrašyti į failą (" . $chmod_files[0] . ")");
	}
	fclose($handle);
	unlink('setup.php');
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
		<title>MightMedia TVS įdiegimas</title>
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

									$menu_pavad = array(1 => "Licensija", 2 => "Failų tikrinimas", 3 => "Duomenų bazės nustatymai", 4 => "Administratoriaus sukūrimas", 5 => "Pabaiga");
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
							<div class="title">MightMedia TVS įdiegimas</div>
							<div class="vidus">
								<?php

								// HTML DALIS - Licensija
								if ($step == 1) {

									?>
								<center>
									<form name="setup">
										<h2>Licensija</h2>
										<textarea name="copy" rows=15 cols=100 wrap="on" readonly="readonly"><?php include ('Skaityk.txt'); ?></textarea><br />
										<label><input name="agree_check" type="checkbox" value="ON" /> Su pateikta informacija sutinku ir jos laikysiuos.</label><br /><br />
										<input name="agree" type="reset" value="Toliau >>" onClick="Check();" />
									</form>
								</center>
								<?php

								}
								//END


								// HTML DALIS - Failų tikrinimas
								if ($step == 2) {

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
											echo '<center><input type="reset" value="Atnaujinti" onClick="JavaScript:location.reload(true);"> <input type="reset" value="Jeigu esate isitikines, kad viskas gerai" onClick="Go(\'3\');"><center>';
										else
											echo '<center><input type="reset" value="Toliau >>" onClick="Go(\'3\');"></center>';

									}
									//END


									// HTML DALIS - MySQL duomenų bazės nustatymai
									if ($step == 3) {

										?>
									<h2>MySQL Duomenų bazės nustatymai</h2>
									Žemiau pateiktuose laukeliuose suveskite savo MySQL serverio prisijungimus. Prisijungimai yra reikalingi norint sukurti MightMedia TVS sistemos lenteles nurodytoje duomenų bazėje. <br /><br />
									Suvedę visus reikiamus duomenis spauskite <strong>"Sukurti lenteles"</strong>. Jei prisijungimas sėkmingai pavyko tuomet išvysite papildomą mygtuką <b>"Toliau"</b>.<br /><br />
									Atlikę visus veiksmus išvysite sekantį mygtuką pereiti į kitą Meniu punktą. Jei bent vienas iš žingsnių nepavyks jums bus draudžiama tęsti įdiegimą.
									<form name="mysql" method="post">
										<table border="0" width="100%">
											<tr>
												<td class="title">MySQL prisijungimo duomenys</td>
											</tr>
											<tr>
												<td>
													<form name="mysql" action="?step=3" method="post">
														<table border="0" width="80%">
															<tr>
																<td>Serverio adresas:</td>
																<td><input name="host" type="text" value="<?php echo (isset($_SESSION['mysql']['host']) ? $_SESSION['mysql']['host'] : 'localhost'); ?>" /><br /></td>
															</tr>
															<tr>
																<td>Prisijungimo vartotojas:</td>
																<td><input name="user" type="text" value="<?php echo (isset($_SESSION['mysql']['user']) ? $_SESSION['mysql']['user'] : 'root'); ?>" /></td>
															</tr>
															<td>Slaptažodis:</td>
															<td><input name="pass" type="password" value="<?php

																	echo (isset($_SESSION['mysql']['pass']) ? $_SESSION['mysql']['pass'] : '');

																			  ?>"></td>
															</tr>
															<tr>
																<td>Duomenų bazė:</td>
																<td><input name="db" type="text" value="<?php

																		echo (isset($_SESSION['mysql']['db']) ? $_SESSION['mysql']['db'] : 'mightmedia');

																				  ?>"></td>
															</tr>
															<tr>
																<td>Duomenų bazės lentelių pavadinimų priesaga:</td>
																<td><input name="prefix" type="text" value="<?php

																		echo (isset($_SESSION['mysql']['prefix']) ? $_SESSION['mysql']['prefix'] : random());

																				  ?>"></td>
															</tr>
														</table>
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
									if ($step == 4) {

										?>
									<h2>Administratoriaus sukūrimas</h2>
									Sukurkite pagrindinį administratorių kuris administruos MightMedia
									TVS.
									<br />
									<br />
									<br />
									<span style="color: red"><?php

											echo (isset($admin_info) ? $admin_info : '');

											?></span>
									<br />
									<form name="admin_form" method="post">
										<table border="0" width="70%">
											<tr>
												<td width="50%">Slapyvardis:</td>
												<td><input name="user" type="text" value="<?php

														echo (isset($user) ? $user : '');

																  ?>"></td>
											</tr>
											<tr>
												<td>Slaptažodis:</td>
												<td><input name="pass" type="password" value=""></td>
											</tr>
											<tr>
												<td>Pakartokite slaptažodį:</td>
												<td><input name="pass2" type="password" value=""></td>
											</tr>
											<tr>
												<td>El. Paštas:</td>
												<td><input name="email" type="text" value="<?php

														echo (isset($email) ? $email : '');

																  ?>"></td>
											</tr>
										</table>
										<br />
										<center><input name="acc_create" type="submit" value="Tęsti >>"></center>
									</form>
									<?php

									}
									//END


									// HTML DALIS - Pabaiga
									if ($step == 5) {

										?>
									<h2>Pabaiga</h2>
									Sveikiname įdiegus MightMedia TVS (Turinio Valdymo Sistemą).<br />
									Spauskite "Pabaigti" galutinai užbaigti instaliaciją. Bus ištrintas <b>setup.php</b> failas. Dėl visa ko - patikrinkite prisijungę prie FTP serverio.<br /><br />
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
			function Check() {
				if (document.setup.agree_check.checked == true) {
					Go(2);
				} else {
					alert('Prašome sutikti su licensija');
				}
			}
			function Go(id) {
				document.location.href = "setup.php?step="+id;
			}

		</script>
	</body>
</html>