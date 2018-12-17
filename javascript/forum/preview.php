<?php
if ( isset( $_POST['msg'] ) ) {
	include( '../../config.php' );
	include( '../../stiliai/' . $conf['Stilius'] . '/sfunkcijos.php' );
	echo "<p>" . bbcode( $_POST['msg'] ) . "</p>";
}