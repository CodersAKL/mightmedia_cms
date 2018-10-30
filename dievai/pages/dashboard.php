<?php
//data
$stats = mysql_query1("SELECT
	(SELECT COUNT(*) as total FROM " . LENTELES_PRIESAGA . "kas_prisijunges WHERE timestamp BETWEEN UNIX_TIMESTAMP(NOW() - INTERVAL 1 DAY) AND UNIX_TIMESTAMP()) as siandien,
	(SELECT COUNT(*) as total FROM " . LENTELES_PRIESAGA . "kas_prisijunges WHERE timestamp BETWEEN UNIX_TIMESTAMP(NOW() - INTERVAL 2 DAY) AND UNIX_TIMESTAMP(NOW() - INTERVAL 1 DAY)) as vakar,
	(SELECT COUNT(*) as total FROM " . LENTELES_PRIESAGA . "kas_prisijunges WHERE timestamp BETWEEN UNIX_TIMESTAMP(NOW() - INTERVAL 3 DAY) AND UNIX_TIMESTAMP(NOW() - INTERVAL 2 DAY)) as uzvakar
	LIMIT 1");

$sqli = mysql_query1( "SELECT count(id) as svec,
(SELECT count(id) FROM " . LENTELES_PRIESAGA . "kas_prisijunges WHERE `timestamp`>'" . $timeout . "' AND user!='Svečias') as users, 
(SELECT count(id) FROM " . LENTELES_PRIESAGA . "users) as useriai, 
(SELECT `nick` FROM " . LENTELES_PRIESAGA . "users order by id DESC LIMIT 1 ) as useris,
(SELECT `id` FROM " . LENTELES_PRIESAGA . "users order by id DESC  LIMIT 1 ) as userid,
(SELECT `levelis` FROM " . LENTELES_PRIESAGA . "users order by id DESC  LIMIT 1 ) as lvl
FROM " . LENTELES_PRIESAGA . "kas_prisijunges WHERE `timestamp`>'" . $timeout . "' AND user='Svečias'" );

$sql = $sqli[0];

$progresas = procentai( ( !empty( $stats['uzvakar'] ) ? $stats['uzvakar'] : 1 ), ( !empty( $stats['vakar'] ) ? $stats['vakar'] : 1 ) );
//tree
$data2 = [];
$res   = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `lang`=" . escape( lang() ) . " ORDER BY `place` ASC" );
foreach($res as $row) {
	if (teises($row['teises'], $_SESSION[SLAPTAS]['level'])) {
		$data2[$row['parent']][] = $row;
	}
}
?>

<div class="row clearfix">
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<?php infoBox('green', 'perm_identity', $lang['online']['users'] , (int)$sql['svec']); ?>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<?php infoBox('orange', 'face', $lang['online']['registeredmembers'], (int)$sql['useriai']); ?>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<?php infoBox('blue', 'person_add', $lang['online']['today'], $stats['siandien']); ?>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<?php infoBox('red', 'devices', $lang['online']['traffic_in'], $progresas . '%'); ?>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
		<?php dashTree($lang['system']['tree'], build_tree($data2)); ?>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
		<div class="card">
			<div class="header bg-cyan">
				<h2>
					Mightmedia <?php echo $lang['news']['news']; ?>
				</h2>
			</div>
			<div class="body">
				<div class="list-group">
				<?php
					$feedUrl = 'https://mightmedia.lt/rss.php';
					$rssContent = getFeedArray($feedUrl);
					$i = 0;
				?>
				<?php foreach($rssContent->item as $key => $new) { ?>
					<?php
						++$i;
						$date = strtotime($new->pubDate[0]);
						$date = date('Y-m-d', $date);
					?>
					<a href="<?php echo $new->link->__toString() ; ?>" class="list-group-item">
						<?php echo $new->title->__toString(); ?>
						<span class="badge<?php echo ($i == 1 ? ' bg-green' : ''); ?>"><?php echo $date; ?></span>
					</a>
					<?php
						if ($i > 5) {
							break;
						}
					?>
				<?php } ?>
				</div>
			</div>
		</div>
		<?php dashStats($lang['system']['visits'], $stats); ?>
	</div>
</div>