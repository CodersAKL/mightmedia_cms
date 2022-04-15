<?php

require_once config('functions', 'dir') . 'functions.auth.php';
$kelias = explode( '/', siteUrl() );
define( "PATH", ( !empty( $kelias[sizeof( $kelias ) - 2] ) ? "/{$kelias[sizeof($kelias)-2]}/" : "/" ) );
define( "DOM", $kelias[2] );

if (empty(getSession('level'))) {
	setSessions(
		[
			'level' => 0,
			'mod' 	=> serialize([]),
		]
	);
}

//tikrinam sesija
// if (! empty(getSession('username')) && ! empty(getSession('password'))) {
// 	$userQuery = "SELECT `id`, `levelis`,`pass`,`nick`,`login_data`,`login_before`,(SELECT `mod` FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id`=levelis) as `mod` FROM `" . LENTELES_PRIESAGA . "users` WHERE `nick`=" . escape(getSession('username')) . " AND `pass`=" . escape(getSession('password')) . " LIMIT 1";
// 	$linformacija = mysql_query1($userQuery);
// 	if ( !empty( $linformacija['levelis'] ) ) {
// 		login( $linformacija );
// 	} else {
// 		logout();
// 	}
// }

//jeigu jungiasi per html forma
// if ( isset( $_POST['action'] ) && $_POST['action'] == 'prisijungimas' ) {

// 	//Jeigu prisijungimo bandymai nevirsyjo limito
// 	if (empty(getSession('login_error')) || getSession('login_error') <= 4 ) {
// 		$strUsername   = $_POST['vartotojas']; // Vartotojo vardas
// 		$strPassword   = koduoju( $_POST['slaptazodis'] ); // Slaptazodis
// 		$linformacija3 = mysql_query1( "SELECT `id`,`levelis`,`pass`,`nick`,`login_data`,`login_before`,(SELECT `mod` FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id`=levelis) as `mod` FROM `" . LENTELES_PRIESAGA . "users` WHERE `nick`=" . escape($strUsername) . " AND `pass`='" . $strPassword . "' LIMIT 1" );
// 		if ( !empty( $linformacija3 ) && $strPassword === $linformacija3['pass'] ) {
// 			login( $linformacija3 );
// 			mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "users` SET `login_before`=login_data, `login_data` = '" . time() . "', `ip` = '" . escape( getip() ) . "' WHERE `id` ='" . $linformacija3['id'] . "' LIMIT 1" );
// 			if ( isset( $_POST['Prisiminti'] ) && $_POST['Prisiminti'] == 'on' ) {
// 				setcookie( "user", getSession('id') . "." . koduoju( SECRET . getip() . getSession('password')), time() + 60 * 60 * 24 * 30, PATH, DOM );
// 			}
// 			header( "Location: " . ( isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : siteUrl() ) );
// 		} else {
// 			mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "logai` (`action` ,`time` ,`ip`) VALUES (" . escape( getLangText('user', 'wrong') . ": User: " . $strUsername . " Pass: " . str_repeat( '*', strlen( $_POST['slaptazodis'] ) ) ) . ",'" . time() . "', '" . escape( getip() ) . "');" );
// 			$strError = getLangText('user', 'wrong');
// 			// + bandymas
// 			$loginError = getSession('login_error');
// 			! empty($loginError) ? $loginError++ : $loginError = 1;
// 			//laukimo laikas
// 			setSession('timeout_idle', time() + ini_get('session.cache_expire'));
// 		}
// 		unset( $linfo, $strUsername, $strPassword );
// 	} else {
// 		$strError = getLangText('user', 'cantlogin') . ' ' . (getSession('timeout_idle') - time() ) . 's.';
// 		//jeigu baigesi laikas
// 		if (getSession('timeout_idle') - time() <= 0) {
// 			forgotSessions(
// 				[
// 					'timeout_idle',
// 					'login_error'
// 				]
// 			);
// 		}
// 	}
// }
//jei paspaude atsijungti
// if ( isset( $_GET['id'] ) && !empty( $_GET['id'] ) && $_GET['id'] == getLangText('user', 'logout') ) {
// 	logout();
// 	setcookie( "PHPSESSID", "", time() - 3600, PATH, DOM );
// 	header( "HTTP/1.0 401 Unauthorized" );
// 	header( "Location: " . ( isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : siteUrl() ) );
// }
