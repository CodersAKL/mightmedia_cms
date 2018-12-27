<?php
foreach ( glob( ROOT . "content/cache/*.php" ) as $filename ) {
	unlink( $filename );
}
//echo 'TODO: PADARYT kad valytų iš tikro, reik folderio tuštinimo funkcijos.';
msg( getLangText('system', 'done'), getLangText('admin', 'uncached') );