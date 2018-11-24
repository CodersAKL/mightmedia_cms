<?php
session_start();

@ini_set( 'error_reporting', E_ALL );
@ini_set( 'display_errors', 'On' );

if (isset($_SESSION['language'])) {
	include_once(ROOT . "lang/" . $_SESSION['language']);
} else {
	include_once(ROOT . "lang/lt.php");
}
/**
 * Svetainės adresui gauti
 *
 * @return string
 */
if(! function_exists('adresas')) {
	function adresas() {
		if ( isset( $_SERVER['HTTP_HOST'] ) ) {
			$adresas = isset( $_SERVER['HTTPS'] ) && strtolower( $_SERVER['HTTPS'] ) !== 'off' ? 'https' : 'http';
			$adresas .= '://' . $_SERVER['HTTP_HOST'];
			$adresas .= str_replace( basename( $_SERVER['SCRIPT_NAME'] ), '', $_SERVER['SCRIPT_NAME'] );
		} else {
			$adresas = 'http://localhost/';
		}

		return $adresas;
	}
}

function stepClass($currentStep, $key)
{
    if ($currentStep < $key) {
        $return = 'disabled';
    } elseif($currentStep == $key) {
        $return = 'active';
    } else {
		$return = 'list-group-item-success';
	}

    return $return;
}

// Sarašas failų kurių teisės turi suteikti svetainei įrašymo galimybę
$chmod_files[0] = ROOT . "priedai/conf.php";
$chmod_files[]  = ROOT . "install/index.php";
$chmod_files[]  = ROOT . ".htaccess";
$chmod_files[]  = ROOT . "siuntiniai/failai";
$chmod_files[]  = ROOT . "siuntiniai/images";
$chmod_files[]  = ROOT . "siuntiniai/media";
$chmod_files[]  = ROOT . "sandeliukas";
$chmod_files[]  = ROOT . "puslapiai";
$chmod_files[]  = ROOT . "blokai";
$chmod_files[]  = ROOT . "images/avatars";
$chmod_files[]  = ROOT . "images/nuorodu";
$chmod_files[]  = ROOT . "images/galerija";
$chmod_files[]  = ROOT . "images/galerija/originalai";
$chmod_files[]  = ROOT . "images/galerija/mini";