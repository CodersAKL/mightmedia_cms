<?php
//todo: delete or fix;
if (is_file( 'conf.php' ) && filesize( 'conf.php' ) > 10) {
	include_once 'conf.php';
} else {
	die();
}

session_start();
//SUGENERUOJA PATVIRTINIMO PAVEIKSLIUKA
header( "Content-type: image/png" ); //nurodome narsyklei kad cia PNG paveiksliukas
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', time() ) . ' GMT' ); //paveiksliuko galiojimo laikas - padarom kad galiotu iki dabar, todel jo nedes i podeli

$font = dirname( realpath( __file__ ) ) . '/human_free.ttf'; // kelias iki srifto. pvz: arial.ttf
$im   = @imagecreate( 70, 40 ) //paveiksliuko dydis plotis/aukstis taskais
	or die( "Sistemos klaida. Nepalaiko GD" );

$bg = imagecolorallocate( $im, 204, 204, 204 ); //fono spalva
ImageColorTransparent( $im, $bg ); //fono spalva padarom permatoma

$fg = ImageColorAllocate( $im, 170, 34, 17 ); //Raidziu spalva rgb(170, 34, 17)

$x                = '5';
$code             = '';
$code             = strtoupper(random_name( 5 )); //sugeneruojam atsitiktini koda
$_SESSION[SLAPTAS]['code'] = $code;


ImageTTFText( $im, 19, 0, $x, 30, $fg, $font, $code );


for ( $i = 0; $i < 16; $i++ ) {
	$color1 = imagecolorallocate( $im, rand( 0, 255 ), rand( 0, 255 ), rand( 0, 255 ) );
	imageline( $im, rand( 0, 70 ), rand( 0, 40 ), rand( 0, 70 ), rand( 0, 40 ), $color1 );
}

imagepng( $im ); //atvaizduojam paveiksliuka
imagedestroy( $im ); //isvalom atminti
unset( $font, $im, $bg, $fg, $x, $rcode, $i );