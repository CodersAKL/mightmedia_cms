<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php header_info(); ?>
	</head>
	<body>
		<div id="wrap">
			<div id="contentt">
				<div id="header">
					<div id="juosta">
						<ul>
							<?php

							$sql1 = mysql_query1("SELECT SQL_CACHE * FROM `" . LENTELES_PRIESAGA . "page` WHERE `parent` = 0 AND `show` = 'Y' AND `lang` = ".escape(lang())." ORDER BY `place` ASC LIMIT 12");
							$text = '';
							foreach ($sql1 as $row1) {
								if (teises($row1['teises'], $_SESSION['level'])) {
									$text .= '<li><a href="'.url('?id,' . (int)$row1['id']) . '">' . input($row1['pavadinimas']) . '</a></li>';
								}
							}
							echo $text;

							?></ul>
					</div>
					<div class="header_bar"><a href="?" title="<?php adresas(); ?>"><img src="stiliai/<?php echo $conf['Stilius']; ?>/images/hdr_left.jpg" alt=""/></a></div>

					<div class="title">
						<?php echo trimlink(input(strip_tags($conf['Apie'])),120); ?>
					</div>
				</div>
				<div class="pagr">
					<div class="virsus"></div>
					<div class="aplink">

						<?php
						$cur_page = explode('/',$page);
						$desinespaneles = mysql_query1("SELECT SQL_CACHE * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='R' AND `lang` = ".escape(lang())." ORDER BY `place` ASC", 120);
						$kairespaneles = mysql_query1("SELECT SQL_CACHE * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='L' AND `lang` = ".escape(lang())." ORDER BY `place` ASC", 120);
						if (sizeof($kairespaneles) && $cur_page[1] != 'frm'): ?>

						<div class="kaire">
							<div class="shadow">
									<?php include ("priedai/kairespaneles.php"); ?>
							</div>
							<div class="shadow_bottom"></div>
						</div>
						<?php endif; ?>

						<div class="center" style="width:<?php echo (490 + (!sizeof($kairespaneles) || $cur_page[1] == 'frm'?200:0) + (!sizeof($desinespaneles) || $cur_page[1] == 'frm'?200:0)); ?>px">

							<?php

							if (isset($strError) && !empty($strError)) {
								klaida("Klaida", $strError);
							}
							include ($page . ".php");

							?>

						</div>
						<?php if (sizeof($desinespaneles) && $cur_page[1] != 'frm'): ?>
						<div class="desine">
							<div class="shadow">
									<?php include ("priedai/desinespaneles.php"); ?>
							</div>
							<div class="shadow_bottom"></div>
						</div>
						<?php endif; ?>

						<div style="clear: both;"> </div>

					</div>
				</div>
				<div class="title">
					<?php copyright( $conf['Copyright'] );?>
				</div>
			</div>
		</div>
	</body>
</html>