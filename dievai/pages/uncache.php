<?php
foreach ( glob( ROOT . "sandeliukas/*.php" ) as $filename ) {
	unlink( $filename );
}
//echo 'TODO: PADARYT kad valytų iš tikro, reik folderio tuštinimo funkcijos.';
msg( $lang['system']['done'], $lang['admin']['uncached'] );