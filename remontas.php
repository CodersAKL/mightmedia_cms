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
$page_pavadinimas = $lang['admin']['maintenance'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php header_info(); ?>
	</head>
	<body>
		<div style="width:45%; text-align:center; margin:auto;margin-top:20px;">
					<?php klaida($lang['admin']['maintenance'], $conf['Maintenance']); ?>
			
					<?php

					if ($conf['Palaikymas'] == 1 && !isset($_SESSION['id'])) {
						lentele($lang['user']['administration'],admin_login_form());
					} elseif (isset($_SESSION['id']) && $_SESSION['level'] > 1 && $_SESSION['level'] > 0 && $conf['Palaikymas'] == 1) {
						echo "<a href='".url("?id,Atsijungti")."'>{$lang['user']['logout']}</a>";
					} elseif (isset($_SESSION['id']) && $_SESSION['level'] == 1) {
						header('location: index.php'); exit;
					}
					if ($conf['Palaikymas'] == 0) {
						header('location: index.php'); exit;
					}

					?>
				<div class="title">
					<?php copyright($conf['Copyright']); unset($text); ?>
				</div>
		</div>
	</body>
</html>
<?php ob_end_flush(); ?>