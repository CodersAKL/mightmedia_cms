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
ob_start();
header("Cache-control: public");
header("Content-type: text/html; charset=utf-8");
include_once("priedai/conf.php");
$status = isset($_SERVER['REDIRECT_STATUS']) ? $_SERVER['REDIRECT_STATUS'] : 500;
switch ($status) {
	case 403:
		{
			$tipas = $lang['system']['nohacking'];
			break;
		}
	case 404:
		{
			$tipas = $lang['system']['nopage'];
			break;
		}
	default:
		{
			$tipas = $lang['system']['error'];
			break;
		}
}

header(' ', true, $status);

?>

<HTML>
<HEAD>
<TITLE><?php

echo $status . ' - ' . $tipas;

?></TITLE>
</HEAD>
<BODY>
<H1 style="color:red"><?php echo $tipas; ?></H1><hr />
<?php echo $lang['system']['contact_admin'].$conf['Pastas'];?>.
</BODY>
</HTML>
<?php
ob_end_flush();
?>