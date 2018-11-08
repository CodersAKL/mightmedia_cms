<?php

if ( is_file( '../priedai/conf.php' ) && filesize( '../priedai/conf.php' ) > 1 ) {
	include_once ( "../priedai/conf.php" );
} elseif ( is_file( '../install/index.php' ) ) {
	header( 'location: ../install/index.php' );
	exit;
} else {
	die( klaida( 'Sistemos klaida / System error', 'Atsiprašome svetaine neįdiegta. Trūksta sisteminių failų. / CMS is not installed.' ) );
}
// example:
// $deleteFiles = [
//     '../upgrade/is_zip',
//     '../puslapiai/is_zip.php'
// ];
// //files delete

// foreach($deleteFiles as $deleteFile) {
//     if(is_file($deleteFile)) {
//         unlink($deleteFile);
//     } else if(is_dir($deleteFile)) {
//         rmdir($deleteFile);
//     }
// }

$updates[] = '4. Atnaujinta';