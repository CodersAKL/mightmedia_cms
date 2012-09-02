<?php
ob_start();
header("Cache-control: public");
header("Content-type: text/html; charset=utf-8");
header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
if (!isset($_SESSION))
	session_start();

if (is_file('../priedai/conf.php') && filesize('../priedai/conf.php') > 1) {
	include_once ("../priedai/conf.php");
} elseif (is_file('../install/index.php')) {
	header('location: ../install/index.php');
	exit();
} else {
	die(klaida('Sistemos klaida / System error', 'Atsiprašome svetaine neįdiegta. Trūksta sisteminių failų. / CMS is not installed.'));
}
include_once ("../priedai/prisijungimas.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo input(strip_tags($conf['Pavadinimas'])); ?> - Admin</title>
	<meta name="description" content="<?php echo input(strip_tags($conf['Pavadinimas']) . ' - ' . trimlink(strip_tags($conf['Apie']), 120)); ?>" />
	<meta name="keywords" content="<?php echo input(strip_tags($conf['Keywords']));?>" />
	<meta name="author" content="<?php echo input(strip_tags($conf['Copyright']));?>" />
	<link rel="stylesheet" type="text/css" media="all" href="css/default.css" />
</head>
<body>
<div id="admin_main">
			  <div id="admin_header" style="height: 15px;">
<div style="text-align: right;color: #666;"><b><?php echo date('H:i:s'); ?></b></div>
			  </div>

		<div id="admin_hmenu" style="font-weight:bold; font-size:25px; color: #FFF; padding: 10px;margin-bottom: 100px;"><?php echo input(strip_tags($conf['Pavadinimas']));?></div>
		<div id="container" style="border: 0;width:50%; margin:auto;">
			<h2>Admin</h2>
        <p style="border-top: 1px solid #7F7F7F;">

	<?php if (!isset($_SESSION['username'])){?>
	<form id="user_reg" name="user_reg" method="post" action="">
		<div id="login" class="section">
			<?php if (!empty($strError)):?>
				<div id="fail" class="info_div"><span class="ico_cancel"><?php echo $strError; ?></span></div>
			<?php endif ?>
			<form name="loginform" id="loginform" action="panel.html" method="post">

			<label><strong><?php echo $lang['user']['user'];?></strong></label><br /><input type="text" name="vartotojas" id="user_login"  size="28" class="input"/>
			<br />
			<label><strong><?php echo $lang['user']['password']; ?></strong></label><br /><input type="password" name="slaptazodis" id="user_pass"  size="28" class="input"/>
			<br />
			<!--<strong>Remember Me</strong><input type="checkbox" id="remember" class="input noborder" />-->

			<br />
			<input type="hidden" name="action" value="prisijungimas" />
			<input id="save" class="loginbutton" type="submit" class="submit" value="<?php echo $lang['user']['login']; ?>" />

			</form>
		
		</div>
	</form>
	<?php }elseif (isset($_SESSION['level']) && $_SESSION['level']==1)
              redirect('main.php');
  ?>

	
</p>
<span style="text-align: right;position:absolute;bottom:0;right:0; padding: 5px;">&copy; <a href="http://mightmedia.lt" style="color: #666;" target="_blank">MightMedia TVS</a></span>
		</div>
</div>

</body>
</html>
