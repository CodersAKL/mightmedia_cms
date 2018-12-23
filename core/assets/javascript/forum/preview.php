<?php
if ( isset( $_POST['msg'] ) ) {
	include( '../../config.php' );
	include( '../../content/themes/' . $conf['Stilius'] . '/sfunkcijos.php' );
	echo "<p>" . bbcode( $_POST['msg'] ) . "</p>";
}