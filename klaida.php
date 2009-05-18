<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author$
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision$
 * @$Date$
 **/

$status = $_SERVER['REDIRECT_STATUS'];
switch ($status) {
	case 403:
		{
			$tipas = 'Programišiams - NE!';
			break;
		}
	case 404:
		{
			$tipas = 'Puslapis nerastas.';
			break;
		}
	default:
		{
			$tipas = 'Klaida.';
			break;
		}
}
header("Content-type: text/html; charset=utf-8");
header(' ', true, $status);

?>

<HTML>
<HEAD>
<TITLE><?php

echo $status . ' - ' . $tipas

?></TITLE>
</HEAD>
<BODY>
<H1 style="color:red"><?php

echo $tipas;

?></H1><hr>
Įvyko nesusipratimas? Susisiekite su puslapio administracija.
</BODY>
</HTML>