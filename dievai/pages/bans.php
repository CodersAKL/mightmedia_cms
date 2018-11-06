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

unset( $resultatai, $i, $temp, $lines );

if ( !isset( $_GET['b'] ) ) {
	$_GET['b'] = 1;
}

unset($extra, $text);

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

	redirect(
		url("?id," . $url['id'] . ";a," . $url['a']),
		"header",
		[
			'type'		=> 'success',
			'message' 	=> 'IP ' . $lang['admin']['unbaned']
		]
	);
}
//ip baninimas
if ( isset( $_GET['b'] ) && $_GET['b'] == 1 ) {
	$title = 'IP ' . $lang['admin']['bans'];
	
	$form = [
		"Form"                     	=> [
			"action"	=> "",
			"method" 	=> "post", 
			"name" 		=> "port"
		],

	    "IP (xx.xxx.xxx.xx):"      	=> [
			"type" 	=> "text",
			"value" => input( ( isset( $url['ip'] ) ) ? $url['ip'] : '' ),
			"name" 	=> "ip"
		],

	    $lang['admin']['why'] 		=> [	
			"type" => "text",
			"name" => "priezastis"
		],

	    ""                        	=> [
			"type" 		=> "submit",
			"name" 		=> "Portai",
			'form_line'	=> 'form-not-line',
			"value" 	=> $lang['admin']['save']
		]
	];

	if ( isset( $_POST['ip'] ) && isset( $_POST['priezastis'] ) ) {
		if ( preg_match( "/^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])" . "(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}$/", $_POST['ip'] ) ) {
			$sql = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "users` WHERE ip = '" . escape( $_POST['ip'] ) . "' AND levelis='1'" );
			if ( count( $sql ) == 0 ) {
				ban( $_POST['ip'], $_POST['priezastis'] );

				redirect(
					url("?id," . $url['id'] . ";a," . $url['a']),
					"header",
					[
						'type'		=> 'success',
						'message' 	=> 'IP: ' . $_POST['ip'] . ' ' . $lang['admin']['banned']
					]
				);
			} else {
				notifyMsg(
					[
						'type'		=> 'error',
						'message' 	=> $lang['admin']['notallowed']
					]
				);
			}
		} else {
			notifyMsg(
				[
					'type'		=> 'error',
					'message' 	=> $lang['admin']['badip']
				]
			);
		}
	}
}
//Atvaizduojam info ir formas
if (isset($form) && isset($title)) {
	$formClass = new Form($form);
	lentele($title, $formClass->form());
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

$IPS = deny_htaccess( read_htaccess() );
if (! empty($IPS)) {
	foreach ( $IPS as $key => $val ) {

		$info[] = array(
			'IP'                     => $key,
			$lang['admin']['why']    => trimlink( $val, 150 ),
			$lang['admin']['action'] => "<a href=\"" . url( "?id,{$_GET['id']};a,{$_GET['a']};d,{$key}" ) . "\" title='{$lang['admin']['delete']}' onClick=\"return confirm('" . $lang['system']['delete_confirm'] . "')\"><img src=\"" . ROOT . "images/icons/cross.png\" alt=\"delete\" border=\"0\"></a> " );
	}
}
$title = $lang['admin']['bans'];
//nupiesiam lenteles/sarasus
if (! empty($title) && ! empty($info) ) {
	$tableClass = new Table($info);

	lentele( $title . " - " . count( $info ), $tableClass->render() );
}