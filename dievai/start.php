<?php
//tree
$data2 = [];
$res   = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `lang`=" . escape( lang() ) . " ORDER BY `place` ASC" );
foreach ( $res as $row ) {
	if ( teises( $row['teises'], $_SESSION[SLAPTAS]['level'] ) ) {
		$data2[$row['parent']][] = $row;
	}
}
$tree = build_tree( $data2 );
//data
$stats = mysql_query1( "SELECT
	(SELECT COUNT(*) as total FROM " . LENTELES_PRIESAGA . "kas_prisijunges WHERE timestamp BETWEEN UNIX_TIMESTAMP(NOW() - INTERVAL 1 DAY) AND UNIX_TIMESTAMP()) as siandien,
	(SELECT COUNT(*) as total FROM " . LENTELES_PRIESAGA . "kas_prisijunges WHERE timestamp BETWEEN UNIX_TIMESTAMP(NOW() - INTERVAL 2 DAY) AND UNIX_TIMESTAMP(NOW() - INTERVAL 1 DAY)) as vakar,
	(SELECT COUNT(*) as total FROM " . LENTELES_PRIESAGA . "kas_prisijunges WHERE timestamp BETWEEN UNIX_TIMESTAMP(NOW() - INTERVAL 3 DAY) AND UNIX_TIMESTAMP(NOW() - INTERVAL 2 DAY)) as uzvakar
	LIMIT 1" );
//chart
$uzvakar  = ( ( time() - 86400 * 2 ) * 1000 );
$vakar    = ( ( time() - 86400 ) * 1000 );
$siandien = time() * 1000;
/*$uzvakar = mktime(0, 0, 0, date("m"), date("d")-2, date("y"));
$vakar = mktime(0, 0, 0, date("m"), date("d")-1, date("y"));
$siandien  = mktime(0, 0, 0, date("m")  , date("d"), date("Y")); */
?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
	google.load("visualization", "1", {packages:["corechart"]});
	google.setOnLoadCallback(drawChart);
	function drawChart() {
		var data = google.visualization.arrayToDataTable([
			['', '<?php echo "{$lang['system']['visits']}";  ?>'],
			[<?php echo $uzvakar;  ?>, <?php echo $stats['uzvakar']; ?>],
			[<?php echo $vakar;  ?>,  <?php echo $stats['vakar']; ?>],
			[<?php echo$siandien;  ?>, <?php echo $stats['siandien']; ?>]
		]);

		var options = {
			chartArea:{ width:'80%', height:'80%'},
			title:'',
			isStacked:true,
			colors:['#FF7910'],
			legend:{position:'none'},
			vAxis:{gridlines:{count:2}, textPosition:'in', title:" "},
			hAxis:{title:" "}
		};

		var chart = new google.visualization.AreaChart(document.getElementById('placeholder'));
		chart.draw(data, options);
	}
</script>
<?php
//table
$text = "
<div class='left'>
<h2>{$lang['system']['tree']}</h2>
<ul id='treemenu'>{$tree}</ul>
</div>
<div class='right'>
<div class='leftui'>
<h2 style='height:30px;'>{$lang['system']['some_data']}</h2>
<ul> 
";
$sqli = mysql_query1( "SELECT count(id) as svec,
(SELECT count(id) FROM " . LENTELES_PRIESAGA . "kas_prisijunges WHERE `timestamp`>'" . $timeout . "' AND user!='Svečias') as users, 
(SELECT count(id) FROM " . LENTELES_PRIESAGA . "users) as useriai, 
(SELECT `nick` FROM " . LENTELES_PRIESAGA . "users order by id DESC LIMIT 1 ) as useris,
(SELECT `id` FROM " . LENTELES_PRIESAGA . "users order by id DESC  LIMIT 1 ) as userid,
(SELECT `levelis` FROM " . LENTELES_PRIESAGA . "users order by id DESC  LIMIT 1 ) as lvl
 FROM " . LENTELES_PRIESAGA . "kas_prisijunges WHERE `timestamp`>'" . $timeout . "' AND user='Svečias'" );
foreach ( $sqli as $sql ) {
	$progresas = procentai( ( !empty( $stats['uzvakar'] ) ? $stats['uzvakar'] : 1 ), ( !empty( $stats['vakar'] ) ? $stats['vakar'] : 1 ) );
	$memberis  = user( $sql['useris'], $sql['userid'], $sql['lvl'] );
	$text .= "
<li>{$lang['online']['users']} {$lang['online']['usrs']}: <span class='number'>" . (int)$sql['users'] . "</span></li>
<li>{$lang['online']['users']} {$lang['online']['guests']}: <span class='number'>" . (int)$sql['svec'] . "</span></li>
<li>{$lang['online']['traffic_in']}: <span class='number'>{$progresas}%</span></li>
<li>{$lang['online']['today']}: <span class='number'>{$stats['siandien']}</span></li>
<li>{$lang['online']['registeredmembers']}: <span class='number'>" . (int)$sql['useriai'] . "</span></li>
<li>{$lang['online']['lastregistered']}: <span class='number'>{$memberis}</span></li>
";
}
$text .= <<<HTM
</ul>
</div>

<div class="leftui">
<h2>{$lang['system']['visits']}</h2>
<div id="chart">
<div id="placeholder" ></div>
<!-- CHART -->
<br />
</div>
</div>

<div class="leftui" style="height: 400px;width:420px">
<h2>Mightmedia {$lang['news']['news']}</h2>
<script type="text/javascript" src="js/FeedEk.js"></script>
<script type="text/javascript" >
$(document).ready(function(){
  $('#txtUrl').val('http://mightmedia.lt/rss.php');
  $('#txtCount').val('5');
  $('#chkDate').attr('checked','checked');
  $('#chkDesc').attr('checked','checked');
   $('#divRss').FeedEk({
   FeedUrl : 'http://mightmedia.lt/rss.php',
   MaxCount : 10,
   ShowDesc : false,
   ShowPubDate: true
  });
});
</script>
<div id="divRss"></div>
<br/><br/>
</div>

</div>
<div style="clear:both;"></div>
HTM;
lentele( $lang['system']['control'], $text );
?>
