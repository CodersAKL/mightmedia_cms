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
$out_page = true;
include_once (dirname(__file__) . "/priedai/conf.php");
include_once (dirname(__file__) . "/priedai/prisijungimas.php");
$page_pavadinimas = $lang['admin']['maintenance'];
if ($conf['Palaikymas'] == 0) {
 // header('location: index.php'); exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo input(strip_tags($conf['Pavadinimas'])); ?> - <?php echo $page_pavadinimas; ?></title>
	<meta name="description" content="<?php echo input(strip_tags($conf['Pavadinimas']) . ' - ' . trimlink(strip_tags($conf['Apie']), 120)); ?>" />
	<meta name="keywords" content="<?php echo input(strip_tags($conf['Keywords']));?>" />
	<meta name="author" content="<?php echo input(strip_tags($conf['Copyright']));?>" />
	<link rel="stylesheet" type="text/css" media="all" href="stiliai/remontas/css/default.css" />
	
</head>
<body>
<div id="admin_main">
			  <div id="admin_header" style="height: 15px;">
<div style="text-align: right;color: #666;"><b><?php echo date('H:i:s'); ?></b></div>
			  </div>

		<div id="admin_hmenu" style="font-weight:bold; font-size:25px; color: #666; padding: 10px; margin-bottom: 100px;"><?php echo input(strip_tags($conf['Pavadinimas']));?></div>
		<div id="container" style="border: 0;width:50%; margin:auto;">
			<h2>
			<?php echo $lang['admin']['maintenance']; ?>
			</h2>
			<p style="border-top: 1px solid #7F7F7F;">
			<?php echo $conf['Maintenance']; ?></p>
			<span style="text-align: right;position:absolute;bottom:0;right:0; padding: 5px;">&copy; <a href="http://mightmedia.lt" style="color: #666;" target="_blank">MightMedia TVS</a></span>
		</div>
</div>
</body>
</html><?php ob_end_flush(); ?>