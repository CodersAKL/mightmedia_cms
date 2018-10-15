<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<?php defaultHead(); ?>
	<link rel="stylesheet" href="css/default.css" />
	<?php 
		if ( !empty( $_COOKIE['style'] ) ) {
			$style = $_COOKIE['style'];
		} else {
			$style = 'diena';
		}
	?>
	<link id="stylesheet" type="text/css" href="css/<?php echo $style ?>.css" rel="stylesheet" />
	<link rel="stylesheet" href="css/superfish.css" />
	<link rel="stylesheet" href="css/jquery.treeview.css" />
	<!--[if IE]>
	<link type='text/css' rel='stylesheet' href='css/defaultie.css' media="screen" />
	<![endif]-->
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
	<script src="js/jquery.treeview.js" type="text/javascript"></script>
	<script type="text/javascript" src="<?php echo ROOT; ?>javascript/jquery/jquery.tablesorter.js"></script>
	<script type="text/javascript" src="<?php echo ROOT; ?>javascript/jquery/tooltip.js"></script>
	<script type="text/javascript" src="<?php echo ROOT; ?>javascript/pagrindinis.js"></script>
	<script type="text/javascript">
		$(document).ready(function () {
			// first example
			$("#treemenu").treeview({
				persist:"location",
				collapsed:true,
				unique:true
			});
			$('ul.sf-menu').superfish();
		});
	</script>
	<script type="text/javascript" src="js/jquery-ui-1.7.1.custom.min.js"></script>
	<script type="text/javascript" src="js/superfish.js"></script>
	<script type="text/javascript" src="js/excanvas.pack.js"></script>
	<script type="text/javascript" src="js/jquery.flot.pack.js"></script>
	<script src="js/jquery.cookie.js" type="text/javascript"></script>
</head>
<body>