<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author$
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision$
 * @$Date$
 * */
//FDISK, nenaudok session_destroy(); ir session_unset(); šiam faile, nes jie tuo pačiu ir forumo sausainius išvalo
//Auto Atjungimas nuo sistemos (neveikė)
//svecio lygis = 0
$kelias = explode( '/', adresas() );
//print_r($kelias);
define( "PATH", ( !empty( $kelias[sizeof( $kelias ) - 2] ) ? "/{$kelias[sizeof($kelias)-2]}/" : "/" ) );
define( "DOM", $kelias[2] );
//echo PATH;
if ( !isset( $_SESSION[SLAPTAS]['level'] ) ) {
	$_SESSION[SLAPTAS]['level'] = 0;
	$_SESSION[SLAPTAS]['mod']   = serialize( array() );
}

function login( $array ) {

	$_SESSION[SLAPTAS]['username'] = $array['nick'];
	$_SESSION[SLAPTAS]['password'] = $array['pass'];
	$_SESSION[SLAPTAS]['id']       = (int)$array['id'];
	$_SESSION[SLAPTAS]['lankesi']  = $array['login_before'];
	$_SESSION[SLAPTAS]['level']    = $array['levelis'];
	$_SESSION[SLAPTAS]['mod']      = $array['mod'];
}

function logout() {

	unset( $_SESSION[SLAPTAS]['username'], $_SESSION[SLAPTAS]['password'], $_SESSION[SLAPTAS]['id'], $_SESSION[SLAPTAS]['level'], $_SESSION[SLAPTAS]['mod'] ); // Isvalom sesija
	$_SESSION[SLAPTAS]['level'] = 0;
	$_SESSION[SLAPTAS]['mod']   = serialize( array() );
	setcookie( "user", "", time() - 3600, PATH, DOM ); // Sunaikinam sesija
}

//tikrinam sesija
if ( isset( $_SESSION[SLAPTAS]['username'] ) && isset( $_SESSION[SLAPTAS]['password'] ) ) {
	$linformacija = mysql_query1( "SELECT `id`, `levelis`,`pass`,`nick`,`login_data`,`login_before`,(SELECT `mod` FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id`=levelis) as `mod` FROM `" . LENTELES_PRIESAGA . "users` WHERE `nick`=" . escape( $_SESSION[SLAPTAS]['username'] ) . " AND `pass`=" . escape( $_SESSION[SLAPTAS]['password'] ) . " LIMIT 1" );
	if ( !empty( $linformacija['levelis'] ) ) {
		login( $linformacija );
	} else {
		logout();
	}
//jeigu yra sausainis bandom jungtis naudojant ji
} elseif ( isset( $_COOKIE[SLAPTAS]['user'] ) && !empty( $_COOKIE[SLAPTAS]['user'] ) ) {
	$user_id = explode( ".", $_COOKIE[SLAPTAS]['user'], 2 );
	if ( isnum( $user_id['0'] ) ) {
		$user_pass = $user_id['1'];
		$user_id   = $user_id['0'];
	}
	$linformacija2 = mysql_query1( "SELECT `id`, `levelis`,`pass`,`nick`,`login_data`,`login_before`, (SELECT `mod` FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id`=levelis) as `mod` FROM `" . LENTELES_PRIESAGA . "users` WHERE `id`=" . escape( (int)$user_id ) . " LIMIT 1" );
	if ( !empty( $linformacija2['levelis'] ) && isset( $user_pass ) && koduoju( $slaptas . getip() . $linformacija2['pass'] ) === $user_pass ) {
		$result = mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "users` SET `login_before`=login_data, `login_data` = '" . time() . "', `ip` = INET_ATON(" . escape( getip() ) . ") WHERE `id` ='" . escape( $user_id ) . "' LIMIT 1" );
		login( $linformacija2 );
	} else {
		mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "logai` (`action` ,`time` ,`ip`) VALUES (" . escape( "{$lang['user']['cookie']}: UserID: " . $user_id . " Pass: " . $user_pass ) . ", '" . time() . "', INET_ATON(" . escape( getip() ) . "))" );
		$strError = $lang['user']['cookie'];
		logout();
	}
}
//jeigu jungiasi per html forma
if ( isset( $_POST['action'] ) && $_POST['action'] == 'prisijungimas' ) {

	//Jeigu prisijungimo bandymai nevirsyjo limito
	if ( !isset( $_SESSION[SLAPTAS]['login_error'] ) || $_SESSION[SLAPTAS]['login_error'] <= 4 ) {
		$strUsername   = $_POST['vartotojas']; // Vartotojo vardas
		$strPassword   = koduoju( $_POST['slaptazodis'] ); // Slaptazodis
		$linformacija3 = mysql_query1( "SELECT `id`,`levelis`,`pass`,`nick`,`login_data`,`login_before`,(SELECT `mod` FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id`=levelis) as `mod` FROM `" . LENTELES_PRIESAGA . "users` WHERE hex(nick)=hex(" . escape( $strUsername ) . ") AND password(pass)=password('" . $strPassword . "') LIMIT 1" );
		if ( !empty( $linformacija3 ) && $strPassword === $linformacija3['pass'] ) {
			login( $linformacija3 );
			mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "users` SET `login_before`=login_data, `login_data` = '" . time() . "', `ip` = INET_ATON(" . escape( getip() ) . ") WHERE `id` ='" . $linformacija3['id'] . "' LIMIT 1" );
			if ( isset( $_POST['Prisiminti'] ) && $_POST['Prisiminti'] == 'on' ) {
				setcookie( "user", $_SESSION[SLAPTAS]['id'] . "." . koduoju( $slaptas . getip() . $_SESSION[SLAPTAS]['password'] ), time() + 60 * 60 * 24 * 30, PATH, DOM );
			}
			header( "Location: " . ( isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : adresas() ) );
		} else {
			mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "logai` (`action` ,`time` ,`ip`) VALUES (" . escape( "{$lang['user']['wrong']}: User: " . $strUsername . " Pass: " . str_repeat( '*', strlen( $_POST['slaptazodis'] ) ) ) . ",'" . time() . "',INET_ATON(" . escape( getip() ) . "));" );
			$strError = $lang['user']['wrong'];
			// + bandymas
			isset( $_SESSION[SLAPTAS]['login_error'] ) ? $_SESSION[SLAPTAS]['login_error']++ : $_SESSION[SLAPTAS]['login_error'] = 1;
			//laukimo laikas
			$_SESSION[SLAPTAS]['timeout_idle'] = time() + ini_get( 'session.cache_expire' );
		}
		unset( $linfo, $strUsername, $strPassword );
	} else {
		$strError = "{$lang['user']['cantlogin']}<span id='sekundes'>" . ( $_SESSION[SLAPTAS]['timeout_idle'] - time() ) . "</span></b><script>startCount();</script>s. ";
		//jeigu baigesi laikas
		if ( $_SESSION[SLAPTAS]['timeout_idle'] - time() <= 0 ) {
			unset( $_SESSION[SLAPTAS]['timeout_idle'], $_SESSION[SLAPTAS]['login_error'] );
		}
	}
}
//jei paspaude atsijungti
if ( isset( $_GET['id'] ) && !empty( $_GET['id'] ) && $_GET['id'] == $lang['user']['logout'] ) {
	logout();
	setcookie( "PHPSESSID", "", time() - 3600, PATH, DOM );
	header( "HTTP/1.0 401 Unauthorized" );
	header( "Location: " . ( isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : adresas() ) );
}
//print_r($_SESSION);
?> 