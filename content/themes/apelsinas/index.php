<!DOCTYPE html>
<html lang="<?php echo getseSsion('lang'); ?>">

<head>
	<?php header_info(); ?>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.0/css/brands.css" integrity="sha384-oT8S/zsbHtHRVSs2Weo00ensyC4I8kyMsMhqTD4XrWxyi8NHHxnS0Hy+QEtgeKUE" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.0/css/fontawesome.css" integrity="sha384-J4287OME5B0yzlP80TtfccOBwJJt6xiO2KS14V7z0mVCRwpz+71z7lwP4yoFbTnD" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
	<link href="content/themes/<?php echo $conf['Stilius']; ?>/assets/css/material-kit.css?v=2.0.5" rel="stylesheet" />
</head>

<body class="landing-page sidebar-collapse">
	<nav class="navbar navbar-transparent navbar-color-on-scroll fixed-top navbar-expand-lg" color-on-scroll="100" id="sectionsNav">
		<div class="container">
		<div class="navbar-translate">
			<a class="navbar-brand" href="<?php echo siteUrl(); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 91.3 97.3" class="navbar-brand--logo">
					<ellipse class="navbar-brand--logo-circle" cx="12.3" cy="57" rx="12.3" ry="12.3"></ellipse>
					<path class="navbar-brand--logo-first" d="M57 68.2c6.2-2.9 8.8-10.2 6-16.4L42.2 7.2C39.3 1 32-1.7 25.8 1.2c-6.2 2.9-8.8 10.2-6 16.4l20.8 44.6c2.9 6.2 10.3 8.9 16.4 6z"></path>
					<path class="navbar-brand--logo-second" d="M84.2 68.2c6.2-2.9 8.8-10.2 6-16.4L69.3 7.1C66.4 1 59.1-1.7 53 1.2c-6.2 2.9-8.8 10.2-6 16.4l20.8 44.6c2.9 6.2 10.2 8.8 16.4 6z"></path>
					<g>
						<path class="navbar-brand--logo-text" d="M2.5 89.4v4.5H0v-8.3h1.9l.4.9c.4-.8 1.2-1.1 2.3-1.1 1.1 0 1.9.4 2.4 1.2.5-.8 1.3-1.2 2.5-1.2 2.1 0 3.1 1.2 3.1 4v4.5h-2.5v-4.5c0-1.5-.3-2.1-1.1-2.2h-.1c-.9 0-1.3.7-1.3 2.2v4.5H5.1v-4.5c0-1.5-.4-2.2-1.3-2.2s-1.3.6-1.3 2.2zM17 83.1c0 .9-.4 1.3-1.4 1.3-1 0-1.4-.4-1.4-1.3s.4-1.3 1.4-1.3c1 .1 1.4.5 1.4 1.3zm-2.6 10.7v-8.3h2.5v8.3h-2.5zM21.9 94c-2.5 0-3.6-1.3-3.6-4.3s1.1-4.3 3.6-4.3c1.2 0 2.1.3 2.7 1l.3-.8h1.9V93c0 3-1.3 4.3-4.3 4.3-2.5 0-3.6-.8-3.9-2.8H21c.2.9.6 1.2 1.5 1.2 1.4 0 2-.7 2-2.4v-.2c-.6.7-1.5.9-2.6.9zm2.5-4.3c0-1.8-.5-2.5-1.8-2.5s-1.8.7-1.8 2.5.5 2.5 1.8 2.5 1.8-.7 1.8-2.5zM28.8 93.8V82.2h2.5V86c.5-.4 1.3-.6 2.2-.6 2.3 0 3.3 1.2 3.3 4v4.5h-2.5v-4.5c0-1.5-.4-2.2-1.5-2.2s-1.5.6-1.5 2.2v4.5-.1.1h-2.5v-.1zM42.7 92h1.4v1.8h-2.3c-1.6 0-2.6-1-2.6-2.5v-4.2h-1.3v-1.5h1.3v-2.1h2.5v2.1h2.4v1.5h-2.4v4c0 .5.4.9 1 .9zM48 89.4v4.5h-2.5v-8.3h1.9l.4.9c.4-.8 1.2-1.1 2.3-1.1 1.1 0 1.9.4 2.4 1.2.5-.8 1.3-1.2 2.5-1.2 2.1 0 3.1 1.2 3.1 4v4.5h-2.5v-4.5c0-1.5-.3-2.1-1.1-2.2h-.1c-.9 0-1.3.7-1.3 2.2v4.5h-2.5v-4.5c0-1.5-.4-2.2-1.3-2.2-.8 0-1.2.6-1.3 2.2zM63.7 92.5c.9 0 1.4-.4 1.6-1.2h2.3c-.4 1.9-1.5 2.8-3.9 2.8-3 0-4.3-1.3-4.3-4.3s1.3-4.3 4.3-4.3 4.3 1.3 4.3 4.4v.7h-6.1c.1 1.3.7 1.9 1.8 1.9zm-1.8-3.4h3.7c0-1.5-.5-2.2-1.8-2.2s-1.8.7-1.9 2.2zM68.9 89.7c0-3 1.1-4.3 3.6-4.3 1.1 0 1.9.2 2.5.7v-3.9h2.5v11.6h-1.9l-.3-.8c-.6.6-1.5.9-2.7.9-2.6.1-3.7-1.2-3.7-4.2zm6.1 0c0-1.8-.5-2.5-1.8-2.5s-1.8.8-1.8 2.5c0 1.8.5 2.5 1.8 2.5s1.8-.8 1.8-2.5zM82.1 83.1c0 .9-.4 1.3-1.4 1.3-1 0-1.4-.4-1.4-1.3s.4-1.3 1.4-1.3c1 .1 1.4.5 1.4 1.3zm-2.6 10.7v-8.3H82v8.3h-2.5zM86.5 94c-2.2 0-3.1-.8-3.1-2.6 0-2 1.2-2.8 4-2.8h1.4v-.4c0-1-.4-1.4-1.4-1.4-1 0-1.4.4-1.4 1.3h-2.3c0-1.9 1.1-2.7 3.8-2.7 2.6 0 3.8.9 3.8 3.1v5.4h-1.9l-.3-.7c-.5.5-1.4.8-2.6.8zm1.3-4.1h-.3c-1.1 0-1.6.4-1.6 1.3 0 .8.4 1.1 1.3 1.1 1.1 0 1.6-.5 1.6-1.6V90h-1z"></path>
					</g>
				</svg>
			</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" aria-expanded="false" aria-label="Toggle navigation">
				<span class="sr-only">Toggle navigation</span>
				<span class="navbar-toggler-icon"></span>
				<span class="navbar-toggler-icon"></span>
				<span class="navbar-toggler-icon"></span>
			</button>
		</div>
		<div class="collapse navbar-collapse">
			<ul class="navbar-nav ml-auto">
				<?php
					$limit 		= 8; //Kiek nuorodų rodome
					$menuSql  	= mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `parent` = 0 AND `show` = 1 AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC LIMIT {$limit}" );
					$menuCount 	= 0;
				?>
				<?php foreach ($menuSql as $menuItem) { $menuCount++; ?>
					<?php if($menuCount > 3 && $menuCount == 4) { ?>
						<li class="dropdown nav-item">
							<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
								<i class="material-icons">apps</i> Daugiau
							</a>
						<div class="dropdown-menu dropdown-with-icons">
					<?php } ?>
					<?php if (teises($menuItem['teises'], getSession('level'))) { ?>
						<?php if($menuCount <= 3) { ?>
							<li class="nav-item">
								<a href="<?php echo url('?id,' . (int)$menuItem['id']); ?>" class="nav-link">
									<?php echo input($menuItem['pavadinimas']); ?>
								</a>
							</li>
						<?php } else { ?>
							<a href="<?php echo url('?id,' . (int)$menuItem['id']); ?>" class="dropdown-item">
								<?php echo input($menuItem['pavadinimas']); ?>
							</a>
						<?php } ?>
					<?php } ?>
					<?php if($menuCount > 3 && $menuCount == count($menuSql)) { ?>
						</div>
						</li>
					<?php } ?>
				<?php } ?>
			<li class="nav-item">
				<a class="nav-link" rel="tooltip" title="Fork us on Github" data-placement="bottom" href="https://github.com/CodersAKL/mightmedia_cms" target="_blank">
					<i class="fab fa-github"></i>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" rel="tooltip" title="" data-placement="bottom" href="https://www.facebook.com/mightmedia/" target="_blank" data-original-title="Like us on Facebook">
				<i class="fab fa-facebook-square"></i>
				</a>
			</li>
			</ul>
		</div>
		</div>
	</nav>
	<div class="page-header header-filter" data-parallax="true">
		<div class="container">
		<div class="row">
			<div class="col-md-6">
				<h1 class="title">
					<?php echo $conf['Pavadinimas']; ?>
				</h1>
				<h4>
					<?php echo strip_tags($conf['Apie']); ?>
				</h4>
				<br>
				<a href="http://mightmedia.lt" target="_blank" class="btn btn---orange btn-raised btn-lg">
					<i class="material-icons">link</i> Mightmedia svetainė
				</a>
			</div>
		</div>
		</div>
	</div>
	<main class="main-wrapper">
		<div class="container">
			<?php
				if (isset($strError ) && ! empty($strError)) {
					klaida("Klaida", $strError);
				}
				
				if (empty($page_type) || (! empty($page_type) && $page_type == 'cms')){
					?>
					<div class="row">
						<div class="col-md-8">
							<div class="main main-raised">
								<?php
									include 'core/inc/inc.center_blocks.php';
									include $page . ".php";
								?>
							</div>
						
						</div>
						<div class="col-md-4">
							<div class="">
								<?php include 'core/inc/inc.left_blocks.php'; ?>
								<?php include 'core/inc/inc.right_blocks.php'; ?>
							</div>
						</div>
					</div>
					<?php
				} elseif ($page_type == 'assembler') {
					include $page . ".php";
				}
			?>
		</div>
	</main>
  	
  <footer class="footer footer-default">
	<div class="container">
		<?php
			$limit 		= 8; //Kiek nuorodų rodome
			$footerMenuSql  	= mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `parent` = 0 AND `show` = 1 AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC LIMIT {$limit}" );
		?>
	  <nav class="float-left">
		<ul>
			<?php foreach ($footerMenuSql as $footerMenuItem) {?>
				<li>
					<a href="<?php echo url('?id,' . (int)$footerMenuItem['id']); ?>">
						<?php echo input($footerMenuItem['pavadinimas']); ?>
					</a>
				</li>
			<?php } ?>
		</ul>
	  </nav>
	  <div class="copyright float-right">
		&copy; <?php echo date('Y'); ?>
		, svetainė iš <i class="material-icons">favorite</i> su
		<a href="http://mightmedia.lt" target="_blank">Mightmedia</a>.
	  </div>
	</div>
  </footer>
  <!--   Core JS Files   -->
  <script src="content/themes/<?php echo $conf['Stilius']; ?>/assets/js/core/jquery.min.js" type="text/javascript"></script>
  <script src="content/themes/<?php echo $conf['Stilius']; ?>/assets/js/core/popper.min.js" type="text/javascript"></script>
  <script src="content/themes/<?php echo $conf['Stilius']; ?>/assets/js/core/bootstrap-material-design.min.js" type="text/javascript"></script>
  <script src="content/themes/<?php echo $conf['Stilius']; ?>/assets/js/plugins/moment.min.js"></script>
  <!--	Plugin for the Datepicker, full documentation here: https://github.com/Eonasdan/bootstrap-datetimepicker -->
  <script src="content/themes/<?php echo $conf['Stilius']; ?>/assets/js/plugins/bootstrap-datetimepicker.js" type="text/javascript"></script>
  <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
  <script src="content/themes/<?php echo $conf['Stilius']; ?>/assets/js/plugins/nouislider.min.js" type="text/javascript"></script>
  <!-- Control Center for Material Kit: parallax effects, scripts for the example pages etc -->
  <script src="content/themes/<?php echo $conf['Stilius']; ?>/assets/js/material-kit.js?v=2.0.5" type="text/javascript"></script>
</body>

</html>