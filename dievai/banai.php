<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: p.dambrauskas $
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision: 284 $
 * @$Date: 2009-08-11 12:23:50 +0300 (Tue, 11 Aug 2009) $
 **/


if ( !defined( "OK" ) || !ar_admin( basename( __file__ ) ) ) {
	redirect( 'location: http://' . $_SERVER["HTTP_HOST"] );
}
//Puslapiavimui
//if (isset($url['p']) && isnum($url['p']) && $url['p'] > 0) $p = (int)$url['p']; else $p = 0;
//$limit = 15;
//
unset( $resultatai, $i, $temp, $lines );
if ( !isset( $_GET['b'] ) ) {
	$_GET['b'] = 1;
}

unset( $buttons, $extra, $text );

if ( isset( $_GET['d'] ) ) {

	$lines = file( ROOT . '.htaccess' );
	$zodiz = $_GET['d'];
	for ( $i = 0; $i < count( $lines ); $i++ ) {
		$pos = strpos( $lines[$i], $zodiz );
		if ( $pos ) {
			$trint = $i;
		}
	}

	delLineFromFile( ROOT . '.htaccess', $trint + 1 );
	delLineFromFile( ROOT . '.htaccess', $trint );
	//msg("ka trint?",( $trint)."ir". ($trint+1)."?");
	msg( $lang['system']['done'], "IP {$lang['admin']['unbaned']}." );
	redirect( $_SERVER['HTTP_REFERER'], 'meta' );
}
//ip baninimas
if ( isset( $_GET['b'] ) && $_GET['b'] == 1 ) {
	$title = "IP {$lang['admin']['bans']}"; //Atvaizdavimo pavadinimas
	//$viso = kiek("ban_portai");	//suskaiciuojam kiek isviso irasu
	$forma = array( "Form"                     => array( "action" => "", "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "port" ),
	                "IP (xx.xxx.xxx.xx):"      => array( "type" => "text", "value" => "" . input( ( isset( $url['ip'] ) ) ? $url['ip'] : '' ) . "", "name" => "ip", ),
		//"Veiksmas:"=>array("type"=>"select","value"=>array("1"=>"Baninti","0"=>"Peradresuoti"),"name"=>"veiksmas"),
	                "{$lang['admin']['why']}:" => array( "type" => "text", "value" => "", "name" => "priezastis" ),
	                " "                        => array( "type" => "submit", "name" => "Portai", "value" => "{$lang['admin']['save']}" ) );
	if ( isset( $_POST['ip'] ) && isset( $_POST['priezastis'] ) ) {
		if ( preg_match( "/^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])" . "(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}$/", $_POST['ip'] ) ) {
			$sql = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "users` WHERE ip =INET_ATON(" . escape( $_POST['ip'] ) . ") AND levelis='1'" );
			if ( count( $sql ) == 0 ) {
				ban( $_POST['ip'], $_POST['priezastis'] );
				msg( $lang['system']['done'], "IP {$_POST['ip']} {$lang['admin']['banned']}." );
				redirect( $_SERVER['HTTP_REFERER'], 'meta' );
			} else {
				klaida( $lang['system']['warning'], "{$lang['admin']['notallowed']}." );
			}
		} else {
			klaida( $lang['system']['warning'], "{$lang['admin']['badip']}." );
		}
	}
}
//Atvaizduojam info ir formas
if ( isset( $forma ) && isset( $title ) ) {
	include_once ( ROOT . 'priedai/class.php' );
	$bla = new forma();
	lentele( $title, $bla->form( $forma ) );
}

/**
 * Banu valdymas
 * Nuskaitom htaccess faila ir gaunam visu banu sarasa
 */

function htaccess_bans() {

	return;
}

/**
 * Gaunam komentara
 *
 */
function htaccess_all() {

	foreach ( $htaccess as $key => $val ) {
		if ( !empty( $val ) ) {
			echo comment_htaccess( $val );
		}
	}

}

/**
 * Nuskaitom visa htaccess
 *
 * @return str
 */
function read_htaccess() {

	return file_get_contents( ROOT . '.htaccess' );
}

/**
 * Gaunam komentarą jei toks yra
 *
 * @param unknown_type $str
 */
function comment_htaccess( $str ) {

	if ( preg_match( '/#.*?$/sim', $str, $regs ) ) {
		return $regs[0];
	} else {
		return "N/A";
	}
}

/**
 * Grazina ip adresus kurie buvo uzdrausti
 *
 * @param unknown_type $str
 *
 * @return unknown
 */
function deny_htaccess( $str ) {

	preg_match_all( '/^(#.*?$).*?([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})/sim', $str, $result, PREG_PATTERN_ORDER );
	foreach ( $result[1] as $key => $val ) {
		$return[$result[2][$key]] = $result[1][$key];
	}
	return @$return;
}

//echo read_htaccess();
$IPS = deny_htaccess( read_htaccess() );
//$viso = count ($IPS);
//print_r($IPS);
if ( count( $IPS ) > 0 ) {
	foreach ( $IPS as $key => $val ) {

		$info[] = array(
			'IP'                     => $key,
			$lang['admin']['why']    => trimlink( $val, 150 ),
			$lang['admin']['action'] => "<a href=\"" . url( "?id,{$_GET['id']};a,{$_GET['a']};d,{$key}" ) . "\" title='{$lang['admin']['delete']}' onClick=\"return confirm('" . $lang['system']['delete_confirm'] . "')\"><img src=\"" . ROOT . "images/icons/cross.png\" alt=\"delete\" border=\"0\"></a> " );
	}
}
$title = $lang['admin']['bans'];
//nupiesiam lenteles/sarasus
if ( isset( $title ) && isset( $info ) ) {
	include_once ( ROOT . 'priedai/class.php' );
	$bla = new Table();
	lentele( $title . " - " . count( $info ), $bla->render( $info ) );

}
//unset($_POST['ip'],$_POST['priezastis']);
?>