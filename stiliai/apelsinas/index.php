<!DOCTYPE html>
<html lang="en">

<head>
  <?php header_info(); ?>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">  <!-- CSS Files -->
  <link href="stiliai/<?php echo $conf['Stilius']; ?>/assets/css/material-kit.css?v=2.0.5" rel="stylesheet" />
</head>

<body class="landing-page sidebar-collapse">
	<nav class="navbar navbar-transparent navbar-color-on-scroll fixed-top navbar-expand-lg" color-on-scroll="100" id="sectionsNav">
		<div class="container">
		<div class="navbar-translate">
			<a class="navbar-brand" href="<?php echo adresas(); ?>">
				<?php echo $conf['Pavadinimas']; ?>
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
					$menuSql  	= mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `parent` = 0 AND `show` = 'Y' AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC LIMIT {$limit}" );
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
					<?php if (teises($menuItem['teises'], $_SESSION[SLAPTAS]['level'])) { ?>
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
				<a href="http://mightmedia.lt" target="_blank" class="btn btn-danger btn-raised btn-lg">
					<i class="fas fa-play"></i> Mightmedia svetainė
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
			?>
			<div class="row">
				<div class="col-md-8">
					<div class="main main-raised">
						<?php
							include "priedai/centro_blokai.php";
							include $page . ".php";
						?>
					</div>
				
				</div>
				<div class="col-md-4">
					<div class="">
						<?php include "priedai/kaires_blokai.php"; ?>
						<?php include "priedai/desines_blokai.php"; ?>
					</div>
				</div>
			</div>

		</div>
	</main>
  	
  <footer class="footer footer-default">
	<div class="container">
	  <nav class="float-left">
		<ul>
		  <li>
			<a href="https://www.creative-tim.com">
			  Creative Tim
			</a>
		  </li>
		  <li>
			<a href="https://creative-tim.com/presentation">
			  About Us
			</a>
		  </li>
		  <li>
			<a href="http://blog.creative-tim.com">
			  Blog
			</a>
		  </li>
		  <li>
			<a href="https://www.creative-tim.com/license">
			  Licenses
			</a>
		  </li>
		</ul>
	  </nav>
	  <div class="copyright float-right">
		&copy;
		<script>
		  document.write(new Date().getFullYear())
		</script>, made with <i class="material-icons">favorite</i> by
		<a href="https://www.creative-tim.com" target="_blank">Creative Tim</a> for a better web.
	  </div>
	</div>
  </footer>
  <!--   Core JS Files   -->
  <script src="stiliai/<?php echo $conf['Stilius']; ?>/assets/js/core/jquery.min.js" type="text/javascript"></script>
  <script src="stiliai/<?php echo $conf['Stilius']; ?>/assets/js/core/popper.min.js" type="text/javascript"></script>
  <script src="stiliai/<?php echo $conf['Stilius']; ?>/assets/js/core/bootstrap-material-design.min.js" type="text/javascript"></script>
  <script src="stiliai/<?php echo $conf['Stilius']; ?>/assets/js/plugins/moment.min.js"></script>
  <!--	Plugin for the Datepicker, full documentation here: https://github.com/Eonasdan/bootstrap-datetimepicker -->
  <script src="stiliai/<?php echo $conf['Stilius']; ?>/assets/js/plugins/bootstrap-datetimepicker.js" type="text/javascript"></script>
  <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
  <script src="stiliai/<?php echo $conf['Stilius']; ?>/assets/js/plugins/nouislider.min.js" type="text/javascript"></script>
  <!-- Control Center for Material Kit: parallax effects, scripts for the example pages etc -->
  <script src="stiliai/<?php echo $conf['Stilius']; ?>/assets/js/material-kit.js?v=2.0.5" type="text/javascript"></script>
</body>

</html>