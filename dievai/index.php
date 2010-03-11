<?php
ob_start();
header("Cache-control: public");
header("Content-type: text/html; charset=utf-8");
header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
if (!isset($_SESSION))
	session_start();

if (is_file('../priedai/conf.php') && filesize('../priedai/conf.php') > 1) {
	include_once ("../priedai/conf.php");
} elseif (is_file('../setup.php')) {
	header('location: ../setup.php');
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
<title>MightMedia TVS - Administravimas</title>
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="robots" content="index,follow" />
	<!--[if IE]><link rel="stylesheet" href="css/ie.css" type="text/css" media="screen, projection" /><![endif]-->
	<link rel="stylesheet" type="text/css" media="all" href="css/style.css" />
	<link rel="Stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.7.1.custom.css"  />
	<!--[if IE]>
		<style type="text/css">
		  .clearfix {
			zoom: 1;     /* triggers hasLayout */
			display: block;     /* resets display for IE/Win */
			}  /* Only IE can see inside the conditional comment
			and read this CSS rule. Don't ever use a normal HTML
			comment inside the CC or it will close prematurely. */
		</style>
	<![endif]-->
	<!-- JavaScript -->
	<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.7.1.custom.min.js"></script>
	<!--script type="text/javascript" src="js/custom.js"></script> -->
	 <!--[if IE]><script language="javascript" type="text/javascript" src="js/excanvas.pack.js"></script><![endif]-->
</head>
<body>
<div  id="login_container">
	<div  id="header">

		<div id="logo"><h1><a href="<?php echo adresas()?>../">MightMedia TVS</a></h1></div>

	</div><!-- end header -->

	<?php if (!isset($_SESSION['username'])){?>
	<form id="user_reg" name="user_reg" method="post" action="">
		<div id="login" class="section">
			<?php if (!empty($strError)):?>
				<div id="fail" class="info_div"><span class="ico_cancel"><?php echo $strError; ?></span></div>
			<?php endif ?>
			<form name="loginform" id="loginform" action="panel.html" method="post">

			<label><strong><?php echo $lang['user']['user'];?></strong></label><input type="text" name="vartotojas" id="user_login"  size="28" class="input"/>
			<br />
			<label><strong><?php echo $lang['user']['password']; ?></strong></label><input type="password" name="slaptazodis" id="user_pass"  size="28" class="input"/>
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

	<script type="text/javascript">
		$(':input[name="vartotojas"]').focus();
	</script>

</div><!-- end container -->

</body>
</html>
