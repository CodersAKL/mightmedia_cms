<?php

/**
 * @Projektas : MightMedia TVS
 * @Puslapis  : www.coders.lt
 * @$Author$
 * @copyright CodeRS ©2008
 * @license   GNU General Public License v2
 * @$Revision$
 * @$Date$
 **/
ob_start();
session_start();
$out_page = TRUE;
include_once ( dirname( __file__ ) . "/priedai/conf.php" );
include_once ( dirname( __file__ ) . "/priedai/prisijungimas.php" );
include_once ( dirname( __file__ ) . "/stiliai/" . $conf['Stilius'] . "/sfunkcijos.php" );
if ( $conf['Palaikymas'] == 0 ) {
	header( 'location: index.php' );
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><?php header_info(); ?></head>
<body>
<?php remontas( $lang['admin']['maintenance'], $conf['Maintenance'] ) ?>
</body>
</html>
<?php ob_end_flush(); ?>