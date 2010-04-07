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
if(isset($_SESSION['language'])){
include_once('lang/'.$_SESSION['language']);
//echo $lang['system']['warning'];
}
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
$chmod_files[] = "sandeliukas";
$chmod_files[] = "puslapiai";
$chmod_files[] = "paneles";
$chmod_files[] = "images/avatars";
$chmod_files[] = "images/nuorodu";
$chmod_files[] = "images/galerija";
$chmod_files[] = "images/galerija/originalai";
$chmod_files[] = "images/galerija/mini";
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
	$_SESSION['step'] = 0;
	$step = 0;
} else {
	if ($_GET['step'] != 0) {
		$step = (int)$_GET['step'];
		if ($_SESSION['step'] == ($step - 1)) {
			$_SESSION['step'] = $step;
		}
	} else {
		header("Location: setup.php?step=" . $_SESSION['step']);
	}
}
if (isset($_POST['language'])) {
	$_SESSION['language']=$_POST['language'];
	header("Location: setup.php?step=1");
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
		$mysql_info = '<b>'.$lang['system']['error'].'</b> ' . mysql_error($mysql_con) . '<br/><b> #</b>' . mysql_errno($mysql_con);
	}
	if (mysql_errno($mysql_con) == 1049) {
		$next_mysql = '<input name="next_msyql" type="submit" value="'.$lang['setup']['crete_db'].'" />';
		mysql_connect($host, $user, $pass);
		mysql_query("CREATE DATABASE `$db` DEFAULT CHARACTER SET utf8 COLLATE utf8_lithuanian_ci");
		mysql_select_db($db);
	} else {
		$mysql_info = '<strong>'.$lang['setup']['mysql_connected'].'</strong><br />';

		// Sukuriamos visos MySQL leneteles is SVN Trunk
		if (!file_exists('sql.sql')) {
			$sql = file_get_contents('http://code.assembla.com/mightmedia/subversion/node/blob/v1/sql'.($_SESSION['language']=='en.php'?'(en.php)':'').'.sql');
		} else {
			$sql = file_get_contents('sql'.($_SESSION['language']=='en.php'?'(en.php)':'').'.sql');
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
					$mysql_info .= "<li><b>{$lang['system']['error']} " . mysql_errno() . "</b> " . mysql_error() . "<hr><b>{$lang['setup']['query']}:</b><br/>" . $val . "</li><hr>";
					$mysql_error++;
				}
			}
		}
		$mysql_info .= "</ol>";

		if ($mysql_error == 0) {
			$mysql_info = $lang['setup']['mysql_created'];
			$next_mysql = '<center><input type="reset" value="'.$lang['setup']['next'].' >>" onClick="Go(\'4\');"></center>';
		} else {
			$next_mysql = '<center><input type="reset" value="'.$lang['setup']['try_again'].'" onClick="Go(\'3\');"></center>';
		}

	}
} else {
	$next_mysql = '<input name="next_msyql" type="submit" value="'.$lang['setup']['create_tables'].'">';
}

// Administratoriaus sukūrimo dalis
if (!empty($_POST['acc_create'])) {
	$user = htmlspecialchars($_POST['user']);
	$pass = (!empty($_POST['pass']) ? koduoju($_POST['pass']) : "");
	$pass2 = (!empty($_POST['pass2']) ? koduoju($_POST['pass2']) : "");
	$email = htmlspecialchars($_POST['email']);
	$_SESSION['admin']['email'] = $email;
	if ($pass != $pass2) {
		$admin_info = $lang['user']['edit_badconfirm'];
	} else {
		if (!empty($user) && !empty($pass) && !empty($pass2) && !empty($email)) {
			mysql_connect($_SESSION['mysql']['host'], $_SESSION['mysql']['user'], $_SESSION['mysql']['pass']);
			mysql_query("SET NAMES utf8");
			mysql_select_db($_SESSION['mysql']['db']);
			mysql_query("UPDATE `" . $_SESSION['mysql']['prefix'] . "users` SET `nick`='" . $user . "', `pass`='" . $pass . "', `email`='" . $email . "', `reg_data`='" . time() . "', `ip`=INET_ATON('" . $_SERVER['REMOTE_ADDR'] . "') WHERE `nick`='Admin'") or die(mysql_error());
			//mysql_query("INSERT INTO `" . $_SESSION['mysql']['prefix'] . "private_msg` (`id`, `from`, `to`, `title`, `msg`, `read`, `date`) VALUES (2, 'CodeRS', '" . $user . "', 'Administracija praneša!', 'Labadiena. Sveikiname sėkmingai įdiegus MightMedia TVS. Ačiū, kad naudojatės [b]CodeRS[/b] produktu.', 'NO', '" . time() . "');") or die(mysql_error());
			mysql_query("INSERT INTO `" . $_SESSION['mysql']['prefix'] . "nustatymai` (`key`, `val`) VALUES ('Pastas', '".$email."');") or die(mysql_error());
			mysql_query("INSERT INTO `" . $_SESSION['mysql']['prefix'] . "nustatymai` (`key`, `val`) VALUES ('kalba', '".$_SESSION['language']."');") or die(mysql_error());
			header("Location: setup.php?step=5");
		} else {
			$admin_info = $lang['admin']['news_required'];
		}
	}
}

//Administravimo direktorijos keitimas
if (!empty($_POST['admin_dir'])) {
	if (is_dir('dievai'))	//Pervading "dievai" direktoriją į sunkiau nuspėjamą
		header("Location: setup.php?step=5");
	else
		header("Location: setup.php?step=6");
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

//Versiju tikrinimas
\$update_url = "http://www.assembla.com/code/mightmedia/subversion/node/blob/naujienos.json?jsoncallback=?";

// DB Prisijungimas
\$prisijungimas_prie_mysql = mysql_connect(\$host, \$user, \$pass) or die("<center><h1>Klaida 1</h1><br/>Svetainė laikinai neveikia. <h4>Prašome užsukti vėliau</h4></center>");
mysql_select_db(\$db,\$prisijungimas_prie_mysql) or die("<center><h1>Klaida 2</h1><br/>Svetainė neidiegta. <h4>Prašome užsukti vėliau</h4></center>");
mysql_query("SET NAMES 'utf8'",\$prisijungimas_prie_mysql);
\$sql = mysql_query("SELECT * FROM `".LENTELES_PRIESAGA."nustatymai`",\$prisijungimas_prie_mysql);
if(mysql_num_rows(\$sql) > 1) while(\$row = mysql_fetch_assoc(\$sql)) \$conf[\$row['key']] = \$row['val'];
unset(\$row,\$sql,\$user,\$host,\$pass,\$db);
//kalba
if (isset(\$conf['kalba'])) {
    require_once (realpath(dirname(__file__)) . '/../lang/' . (empty(\$_SESSION['lang'])?basename(\$conf['kalba'],'.php'):\$_SESSION['lang']). '.php');
} else {
    require_once (realpath(dirname(__file__)) . '/../lang/lt.php');
}
//Jeigu nepavyko nuskaityti nustatymų
if (!isset(\$conf) || empty(\$conf)) die("<center><h1>Klaida 3</h1><br/>Svetainė laikinai neveikia. <h4>Prašome užsukti vėliau</h4></center>");

// Inkludinam tai ko mums reikia
require_once(realpath(dirname(__file__))."/funkcijos.php");
?>
HTML;
	//if (is_writable($chmod_files[0])) {
	if (!$handle = fopen($chmod_files[0], 'w')) {
		die("{$lang['setup']['cant_open']} (" . $chmod_files[0] . ")");
	}
	if (fwrite($handle, $content) === false) {
		die("{$lang['setup']['cant_write']} (" . $chmod_files[0] . ")");
	}
	fclose($handle);
	@chmod('setup.php', 0777 );
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
		<meta name="author" content="CodeRS - MightMedia TVS" />
		<meta name="copyright" content="copyright (c) by CodeRS www.coders.lt" />
		<meta name="rating" content="general" />
		<meta name="generator" content="notepad" />
		<script src="javascript/jquery/jquery-1.3.1.min.js" type="text/javascript" ></script>

		<script src="javascript/jquery/tooltip.js" type="text/javascript" ></script>
		<title>MightMedia TVS/CMS</title>
		<link href="stiliai/default/default.css" rel="stylesheet" type="text/css" media="all" />
	</head>
	<body>
		<center>
			<table border="0" cellpadding="2" cellspacing="5" width="80%">
				<tbody>
						<tr>

						<?php if($step!=0) { ?>
						<td width="25%" valign="top">
						<div class="title"><?php echo $lang['setup']['steps']; ?></div>
							<div class="vidus">
								<ul>
									<?php

									$menu_pavad = array(1 => $lang['setup']['liceanse'], 2 => $lang['setup']['file_check'], 3 => $lang['setup']['database'], 4 => $lang['setup']['admin'], 5 => $lang['setup']['end']);
									foreach ($menu_pavad as $key => $value) {
										if ($key <= $step)
											echo "\t\t\t<li><img src=\"images/icons/tick_circle.png\" style=\"vertical-align: middle;\" /><font color=\"green\"><b>" . $value . "</b></font></li>";
										else
											echo "\t\t\t<li><img src=\"images/icons/cross_circle.png\" style=\"vertical-align: middle;\" /><b>" . $value . "</b></li>";
									}

									?>
								</ul>
								<hr />
								<?php echo $lang['setup']['product'];?>: <a href="http://www.mightmedia.lt/" target="_blank">MightMedia TVS</a><br /></div>
						</td>
						<?php } ?>

						<td valign="top">
							<div class="title">MightMedia TVS įdiegimas / MightMedia CMS setup</div>
							<div class="vidus">
								<?php if ($step == 0) { ?>
								<center>
									<form name="lang" method="post" action="">
										<h2>Language / Kalba</h2>
										Select language / Pasirinkite kalbą:<br />
										<select name="language">
											<option value="lt.php">Lietuvių</option>
											<option value="en.php">English</option>
										</select><br />
										<input name="go" type="submit" value=" >>" />
									</form>
								</center>
								<?php } if ($step == 1) { ?>
								<center>
									<form name="setup" action="">
										<h2><?php echo $lang['setup']['liceanse'];?></h2>
										<textarea name="copy" rows=15 cols=100 wrap="on" readonly="readonly"><?php include ('Skaityk.txt'); ?></textarea><br />
										<label><input name="agree_check" type="checkbox" value="ON" /> <?php echo $lang['setup']['agree']; ?></label><br /><br />
										<input name="agree" type="reset" value="<?php echo $lang['setup']['next'];?> >>" onClick="Check();" />
									</form>
								</center>
								<?php } if ($step == 2) { ?>

								<h2><?php echo $lang['setup']['file_check'];?></h2>
								<?php echo $lang['setup']['file_check_info1'];?><br /><br />
								<h2><?php echo $lang['setup']['file_check_legend'];?></h2>
								<img src="images/icons/tick.png" alt="" /> <?php echo $lang['setup']['file_check_info2'];?><br />
								<img src="images/icons/cross.png" alt="" /> <?php echo $lang['setup']['file_check_info3'];?><br /><br />
								<strong><?php echo $lang['setup']['note'];?>:</strong>
								<?php echo $lang['setup']['file_check_info3'];?>
								<table border="0">
									<tr>
										<td class="title" valign="top" width="10%"><?php echo $lang['setup']['file'];?></td>
										<td class="title" valign="top" width="5%"><?php echo $lang['setup']['point'];?></td>
										<td class="title" valign="top" width="35%"><?php echo $lang['setup']['about_error'];?></td>
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
											<td>" . (($teises == 777) || ($teises == 666) || is_writable($chmod_files[$i]) ? "" : "{$lang['setup']['chmod_777']} <strong>" . $chmod_files[$i] . "</strong> {$lang['setup']['chmod_777_2']} <strong>" . $teises . "</strong>") . "</td>
									</tr>";
										}
										?>
								</table>
								<br />
								<br />
								<?php

										if (isset($file_error) && $file_error == 'Y')
											echo '<center><input type="reset" value="'.$lang['setup']['reload'].'" onClick="JavaScript:location.reload(true);"> <input type="reset" value="'.$lang['setup']['if_you_think_ok'].'" onClick="Go(\'3\');"><center>';
										else
											echo '<center><input type="reset" value="'.$lang['setup']['next'].' >>" onClick="Go(\'3\');"></center>';

									}
									//END


									// HTML DALIS - MySQL duomenų bazės nustatymai
									if ($step == 3) { ?>

									<h2><?php echo $lang['setup']['database'];?></h2>
									<?php echo $lang['setup']['mysql_info'];?>
									<form name="mysql" method="post" action="?step=3">
										<table border="0" width="100%">
											<tr>
												<td class="title"><?php echo $lang['setup']['mysql_connect'];?></td>
											</tr>
											<tr>
												<td>
													<table border="0" width="80%">
															<tr>
																<td><?php echo $lang['setup']['mysql_host'];?>:</td>
																<td><input name="host" type="text" value="<?php echo (isset($_SESSION['mysql']['host']) ? $_SESSION['mysql']['host'] : 'localhost'); ?>" /><br /></td>
															</tr>
															<tr>
																<td><?php echo $lang['setup']['mysql_user'];?>:</td>
																<td><input name="user" type="text" value="<?php echo (isset($_SESSION['mysql']['user']) ? $_SESSION['mysql']['user'] : 'root'); ?>" /></td>
															</tr>
															<tr>
																<td><?php echo $lang['setup']['mysql_pass'];?>:</td>
																<td><input name="pass" type="password" value="<?php echo (isset($_SESSION['mysql']['pass']) ? $_SESSION['mysql']['pass'] : ''); ?>" /></td>
															</tr>
															<tr>
																<td><?php echo $lang['setup']['mysql_db'];?>:</td>
																<td><input name="db" type="text" value="<?php echo (isset($_SESSION['mysql']['db']) ? $_SESSION['mysql']['db'] : 'mightmedia'); ?>" /></td>
															</tr>
															<tr>
																<td><?php echo $lang['setup']['mysql_prfx'];?>:</td>
																<td><input name="prefix" type="text" value="<?php echo (isset($_SESSION['mysql']['prefix']) ? $_SESSION['mysql']['prefix'] : random()); ?>" /></td>
															</tr>
														</table>
												</td>
											</tr>
										</table>
										<br />
										<center>
											<p id="mysql_response"><?php echo $next_mysql; ?></p>
										</center>
											<?php if (isset($mysql_info)):?>
										<br />
										<table border="0" width="50%">
											<tr>
												<td class="title"><?php echo $lang['user']['user_info'];?></td>
											</tr>
											<tr>
												<td><div id="info"><?php echo $mysql_info; ?></div></td>
											</tr>
										</table>
											<?php endif ?>
									</form>
									<?php

									}
									//END


									// HTML DALIS - TVS administratoriaus sukūrimas
									if ($step == 4) { ?>
									<h2><?php echo $lang['setup']['admin'];?></h2>
									<?php echo $lang['setup']['admin_info'];?>
									<br />
									<br />
									<br />
									<span style="color: red"><?php echo (isset($admin_info) ? $admin_info : ''); ?></span>
									<br />
									<form name="admin_form" method="post" action="">
										<table border="0" width="70%">
											<tr>
												<td width="50%"><?php echo $lang['reg']['username'];?>:</td>
												<td><input name="user" type="text" value="<?php echo (isset($user) ? $user : ''); ?>" /></td>
											</tr>
											<tr>
												<td><?php echo $lang['reg']['password'];?>:</td>
												<td><input name="pass" type="password" value="" /></td>
											</tr>
											<tr>
												<td><?php echo $lang['reg']['confirmpassword'];?>:</td>
												<td><input name="pass2" type="password" value="" /></td>
											</tr>
											<tr>
												<td><?php echo $lang['reg']['email'];?>:</td>
												<td><input name="email" type="text" value="<?php echo (isset($email) ? $email : ''); ?>" /></td>
											</tr>
										</table>
										<br />
										<center><input name="acc_create" type="submit" value="<?php echo $lang['setup']['next'];?>" /></center>
									</form>
									<?php }
									//END


									if ($step == 5) { ?>
									<h2><?php echo $lang['setup']['admin_dir'];?></h2>
									<?php echo $lang['setup']['admin_dir_info'];?>
									<form name="admin_dir" method="post" action="">
										<center><input name="admin_dir" type="submit" value="<?php echo $lang['setup']['next'];?>" /></center>
									</form>
									<?php
									}

									// HTML DALIS - Pabaiga
									if ($step == 6) { ?>
									<h2><?php echo $lang['setup']['end'];?></h2>
									<?php echo $lang['setup']['end_info'];?>
									<form name="finish_install" method="post" action="">
										<center><input name="finish" type="submit" value="<?php echo $lang['setup']['end'];?>" /></center>
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
					alert('<?php echo $lang['setup']['agree_please'];?>');
				}
			}
			function Go(id) {
				document.location.href = "setup.php?step="+id;
			}

		</script>
	</body>
</html>