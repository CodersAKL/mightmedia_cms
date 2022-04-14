<?php
ob_start();
header( "Cache-control: public" );
header( "Content-type: text/html; charset=utf-8" );
header( 'P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"' );

if (! isset($_SESSION)) {
	session_start();
}

/**
 * BOOT
 */
include_once ROOT . 'core/boot.php';


include_once ROOT . 'core/inc/inc.auth.php';

require 'themes/material/config.php';
require 'themes/material/functions.php';

include 'functions/functions.core.php';
include 'themes/material/login.php';