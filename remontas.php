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
session_start();
include_once (dirname(__file__) . "/priedai/conf.php");
include_once (dirname(__file__) . "/priedai/prisijungimas.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Description" content="<?php

echo input(strip_tags($conf['Pavadinimas']) . ' - ' . trimlink(strip_tags($conf['Apie']), 120));

?>" />
<title><?php

echo input(strip_tags($conf['Pavadinimas']));

?></title>
<link rel="SHORTCUT ICON" href="favicon.ico" /> 
<link href="stiliai/<?php

echo $conf['Stilius'];

?>/default.css" rel="stylesheet" type="text/css" />
<link href="stiliai/system.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="javascript/swfobject.js"></script>
<script type="text/javascript" src="javascript/scriptaculous/lib/prototype.js"></script>
<script type="text/javascript" src="javascript/scriptaculous/src/scriptaculous.js"></script>
<script language="javascript" src="javascript/pagrindinis.js" type="text/javascript"></script>
</head>
<body>
<table width="400px" align="center" class="main">
<tr class="center_header"><td>
<?php

klaida($lang['admin']['maintenance'], $conf['Maintenance']);

?>
</td>
</tr>
<tr>
<td class="center_middle">
<?php

if ($conf['Palaikymas'] == 1 && !isset($_SESSION['id'])) {
	admin_login_form();
} elseif (isset($_SESSION['id']) && $_SESSION['level'] > 1 && $_SESSION['level'] > 0) {
	echo "<a href='?id,Atsijungti'>{$lang['user']['logout']}</a>";
} elseif (isset($_SESSION['id']) && $_SESSION['level'] == 1) {
	header('location: index.php');
}

?>
</td></tr>
<tr>
<td class="center_footer">
<?php

copyright($conf['Copyright']);
unset($text);

?>
</td>
</tr>
</table>
</body>
</html>
<?php

ob_end_flush();

?>