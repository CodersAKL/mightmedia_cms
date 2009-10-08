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
		<?php header_info(); ?>
	</head>
	<body>
		<table width="400px" align="center" class="main">
			<tr class="center_header"><td>
					<?php klaida($lang['admin']['maintenance'], $conf['Maintenance']); ?>
				</td>
			</tr>
			<tr>
				<td class="center_middle">
					<?php

					if ($conf['Palaikymas'] == 1 && !isset($_SESSION['id'])) {
						admin_login_form();
					} elseif (isset($_SESSION['id']) && $_SESSION['level'] > 1 && $_SESSION['level'] > 0 && $conf['Palaikymas'] == 1) {
						echo "<a href='?id,Atsijungti'>{$lang['user']['logout']}</a>";
					} elseif (isset($_SESSION['id']) && $_SESSION['level'] == 1) {
						header('location: index.php'); exit;
					}
					if ($conf['Palaikymas'] == 0) {
						header('location: index.php'); exit;
					}

					?>
				</td></tr>
			<tr>
				<td class="center_footer">
					<?php copyright($conf['Copyright']); unset($text); ?>
				</td>
			</tr>
		</table>
	</body>
</html>
<?php ob_end_flush(); ?>