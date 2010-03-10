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
  //header('location: index.php'); exit;
}
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
	<link rel="stylesheet" type="text/css" media="all" href="stiliai/remontas/css/style.css" />
	
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

	
</head>
<body>
<div  id="login_container">
	<div  id="header">

		<div id="logo"><h1><a href="/">MightMedia TVS</a></h1></div>

	</div><!-- end header -->

	
	
		<div id="login" class="section">
			<div id="warning">
			<?php echo $conf['Maintenance']; ?>
			</div>
			© <a href="http://mightmedia.lt">MightMedia TVS</a>		
		</div>
	
	



</div><!-- end container -->

</body>
</html>







<?php ob_end_flush(); ?>


