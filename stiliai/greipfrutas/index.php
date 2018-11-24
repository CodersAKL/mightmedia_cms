<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php header_info(); ?>
	<!-- favicon -->
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo adresas(); ?>/images/favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo adresas(); ?>/images/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo adresas(); ?>/images/favicon/favicon-16x16.png">
	<link rel="manifest" href="<?php echo adresas(); ?>/images/favicon/site.webmanifest">
	<link rel="mask-icon" href="<?php echo adresas(); ?>/images/favicon/safari-pinned-tab.svg" color="#db7300">
	<meta name="msapplication-TileColor" content="#ff440e">
	<meta name="theme-color" content="#ffffff">
</head>
<body>

<div id="plotis">

	<div id="kaire">
		<div class="skalpas">
			<a href="<?php echo adresas(); ?>" title="<?php echo adresas(); ?>">
				<div class="logo"></div>
			</a>

			<div class="sonas2"></div>
		</div>

		<?php include ( "priedai/kaires_blokai.php" ); ?>
	</div>

	<div id="kunas">
		<div id="meniu_juosta">
			<ul>
				<?php
				$limit = '8'; //Kiek nuorodu rodome
				$sql1  = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `parent` = 0 AND `show` = 'Y' AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC LIMIT {$limit}" );
				$text  = '';
				foreach ( $sql1 as $row1 ) {
					if ( teises( $row1['teises'], $_SESSION[SLAPTAS]['level'] ) ) {
						$text .= '<li><a href="' . url( '?id,' . (int)$row1['id'] ) . '">' . input( $row1['pavadinimas'] ) . '</a></li>';
					}
				}
				echo $text;
				?>
			</ul>
		</div>

		<div id="centras" <?php if ( $page == 'puslapiai/frm' ) {
			echo 'style="width: 675px;"';
		}?>>
			<?php
			if ( isset( $strError ) && !empty( $strError ) ) {
				klaida( "Klaida", $strError );
			}
			include ( "priedai/centro_blokai.php" );
			include ( $page . ".php" );
			?>
		</div>

		<?php if ( $page != 'puslapiai/frm' ) { ?>
		<div id="desine">
			<?php include ( "priedai/desines_blokai.php" ); ?>
		</div>
		<?php } ?>
		<div class="sonas"></div>
		<div id="kojos">
			<div class="tekstas"><?php copyright( $conf['Copyright'] );?></div>
			<a href="http://mightmedia.lt" target="_blank" title="Mightmedia">
				<div class="logo"></div>
			</a>
		</div>
	</div>
</div>
<div id="another" class="clear">
	<div class="lygiuojam">
		<div class="taisom"></div>
	</div>
</div>
</body>
</html>