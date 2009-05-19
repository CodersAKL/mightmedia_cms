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
//kai kuoriuose hostinguose susimala nario id su puslapio id, todel:
unset($id);
//Auto Atjungimas nuo sistemos (neveikė)
if (!isset($_SESSION['level'])) {
	$_SESSION['level'] = 0;
	define("LEVEL", 0);
} else {
	define("LEVEL", $_SESSION['level']);
}
if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
	$linformacija = mysql_query1("SELECT `id`, `levelis`,`pass`,`nick`,`login_data`,`login_before`,(SELECT `mod` FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `teises`=levelis)as `mod` FROM `" . LENTELES_PRIESAGA . "users` WHERE `nick`=" . escape($_SESSION['username']) . " AND `pass`=" . escape($_SESSION['password']) . " LIMIT 1");
	if (!empty($linformacija['levelis'])) {
		$_SESSION['username'] = $linformacija['nick'];
		$_SESSION['password'] = $linformacija['pass'];
		$_SESSION['id'] = (int)$linformacija['id'];
		//$_SESSION['lankesi'] = $linformacija['login_before'];
		$_SESSION['level'] = $linformacija['levelis'];
		$_SESSION['mod'] = $linformacija['mod'];
	} else {
		unset($_SESSION); // Isvalom sesija
		session_unset();
		session_destroy();
		$_SESSION['level'] = 0;
		setcookie("user", "", time() - 3600); // Sunaikinam sesija
	}
	unset($linfo);
} elseif (isset($_COOKIE['user']) && !empty($_COOKIE['user'])) {
	$user_id = explode(".", $_COOKIE['user'], 2);
	if (isnum($user_id['0'])) {
		$user_pass = $user_id['1'];
		$user_id = $user_id['0'];

	}
	$linformacija2 = mysql_query1("SELECT `levelis`,`pass`,`nick`,`login_data`,`login_before`,(SELECT `mod` FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `teises`=levelis)as `mod` FROM `" . LENTELES_PRIESAGA . "users` WHERE `id`=" . escape((int)$user_id) . " LIMIT 1");
	if (!empty($linformacija2['levelis']) && $linformacija2['levelis'] > 0 && koduoju($slaptas . getip() . $linformacija2['pass']) === $user_pass) {

		$result = mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "users` SET `login_before`=login_data, `login_data` = '" . time() . "', `ip` = INET_ATON(" . escape(getip()) . ") WHERE `id` ='" . escape($user_id) . "' LIMIT 1") or die(mysql_error());
		$_SESSION['username'] = $linformacija2['nick'];
		$_SESSION['password'] = $linformacija2['pass'];
		$_SESSION['id'] = (int)$user_id;
		$_SESSION['lankesi'] = $linformacija2['login_before'];
		$_SESSION['level'] = $linformacija2['levelis'];
		$_SESSION['mod'] = $linformacija2['mod'];
	} else {
		mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "logai` (`action` ,`time` ,`ip`) VALUES (" . escape("{$lang['user']['cookie']}: UserID: " . $user_idas . " Pass: " . $user_pass) . ", '" . time() . "', INET_ATON(" . escape(getip()) . "))");
		$strError = $lang['user']['cookie'];

		unset($_SESSION['username'], $_SESSION['password'], $_SESSION['id'], $_SESSION['lankesi'], $_SESSION['mod']); // Isvalom sesija
		session_unset();
		session_destroy();
		setcookie("user", "", time() - 3600); // Sunaikinam sesija
	}
	unset($linfo);
}

##################### Prisijungimas prie sistemos ########################
if (isset($_POST['action']) && $_POST['action'] == 'prisijungimas') {

	//Jeigu prisijungimo bandymai nevirsyjo limito
	if (!isset($_SESSION['login_error']) || $_SESSION['login_error'] <= $conf['Bandymai']) {

		$strUsername = input($_POST['vartotojas']); // Vartotojo vardas
		$strPassword = koduoju($_POST['slaptazodis']); // Slaptazodis
	$linformacija3 = mysql_query1("SELECT `id`,`levelis`,`pass`,`nick`,`login_data`,`login_before`,(SELECT `mod` FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `teises`=`levelis`)as `mod` FROM `" . LENTELES_PRIESAGA . "users` WHERE hex(nick)=hex(" . escape($strUsername) . ") AND password(pass)=password('" . $strPassword . "') LIMIT 1");
		$linformacija3 = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "users` WHERE nick=" . escape($strUsername) . " AND pass='" . $strPassword . "' limit 1");
		//print_r($linformacija3);
		//echo $linformacija3['nick'];
		//print_r($conf['level']);

		if (!empty($linformacija3) && $strPassword === $linformacija3['pass']) {
			$_SESSION['username'] = input($linformacija3['nick']);
			$_SESSION['password'] = $strPassword;
			$_SESSION['id'] = $linformacija3['id'];
			$_SESSION['lankesi'] = $linformacija3['login_before'];
			$_SESSION['level'] = $linformacija3['levelis'];
			$_SESSION['mod'] = $linformacija3['mod'];
			//	define("LEVEL", $linfo['levelis']);
			mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "users` SET `login_before`=login_data, `login_data` = '" . time() . "', `ip` = INET_ATON(" . escape(getip()) . ") WHERE `id` ='" . $linformacija3['id'] . "' LIMIT 1");
			if (isset($_POST['Prisiminti']) && $_POST['Prisiminti'] == 'on') {
				setcookie("user", $_SESSION['id'] . "." . koduoju($slaptas . getip() . $_SESSION['password']), time() + 60 * 60 * 24 * 30);
			}
			header("Location: " . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : adresas()));

		} else {
			mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "logai` (`action` ,`time` ,`ip`) VALUES (" . escape("{$lang['user']['wrong']}: User: " . $strUsername . " Pass: " . $_POST['slaptazodis']) . ",'" . time() . "',INET_ATON(" . escape($_SERVER['REMOTE_ADDR']) . "));");
			$strError = $lang['user']['wrong'];
			isset($_SESSION['login_error']) ? $_SESSION['login_error']++ : $_SESSION['login_error'] = 1;
		}
		unset($linfo, $strUsername, $strPassword);

	} else {
		$strError = "{$lang['user']['cantlogin']}<span id='sekundes'>" . ini_get('session.cache_expire') . "</span></b><script>startCount();</script>s. ";
	}
}

if (isset($_GET['id']) && !empty($_GET['id']) && preg_match('/[^\d]/simx', $_GET['id'])) {
	unset($_SESSION);
	session_unset();
	session_destroy();
	$_SESSION['level'] = 0;
	setcookie("user", "", time() - 3600);
	setcookie("PHPSESSID", "", time() - 3600);
	header("HTTP/1.0 401 Unauthorized");
	header("Location: " . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : adresas()));
}

function admin_login_form($strError = false) {
	global $lang;
	$text = "
      <center>" . ((isset($strError)) ? $strError : 'Administratoriam') . "
          <form id=\"user_reg\" name=\"user_reg\" method=\"post\" action=\"\">
          <label for=\"vartotojas\">{$lang['user']['user']}:</label>
          <br />
          <input name=\"vartotojas\" type=\"text\"  value=\"\" maxlength=\"50\" />
          <br />
          <label for=\"slaptazodis\">{$lang['user']['password']}:</label>
          <br />
          <input name=\"slaptazodis\" type=\"password\" value=\"\" maxlength=\"50\" />
          <br />
          <input type=\"submit\" name=\"Submit\" value=\"{$lang['user']['login']}\" />
          <input type=\"hidden\" name=\"action\" value=\"prisijungimas\" />
        </form>
      </center>
    ";
	echo $text;
	unset($text);
	exit();
}

?> 