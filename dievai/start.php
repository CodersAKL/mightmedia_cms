<?php
//tree
$data2 = '';
$res = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `lang`=" . escape(lang()) . " ORDER BY `place` ASC");
foreach ($res as $row) {
	if (teises($row['teises'], $_SESSION['level'])) {
		$data2[$row['parent']][] = $row;
	}
}
$tree = build_tree($data2);
//data
$stats = mysql_query1("SELECT
	(SELECT COUNT(*) as total FROM " . LENTELES_PRIESAGA . "kas_prisijunges WHERE timestamp BETWEEN UNIX_TIMESTAMP(NOW() - INTERVAL 1 DAY) AND UNIX_TIMESTAMP()) as siandien,
	(SELECT COUNT(*) as total FROM " . LENTELES_PRIESAGA . "kas_prisijunges WHERE timestamp BETWEEN UNIX_TIMESTAMP(NOW() - INTERVAL 2 DAY) AND UNIX_TIMESTAMP(NOW() - INTERVAL 1 DAY)) as vakar,
	(SELECT COUNT(*) as total FROM " . LENTELES_PRIESAGA . "kas_prisijunges WHERE timestamp BETWEEN UNIX_TIMESTAMP(NOW() - INTERVAL 3 DAY) AND UNIX_TIMESTAMP(NOW() - INTERVAL 2 DAY)) as uzvakar
	LIMIT 1");
$sql = mysql_query1("SELECT count(id) as svec,
	(SELECT count(id) FROM " . LENTELES_PRIESAGA . "kas_prisijunges WHERE `timestamp`>'" . $timeout . "' AND user!='Svecias') as users,
	(SELECT count(id) FROM " . LENTELES_PRIESAGA . "users) as useriai,
	(SELECT `nick` FROM " . LENTELES_PRIESAGA . "users order by id DESC LIMIT 1 ) as useris,
	(SELECT `id` FROM " . LENTELES_PRIESAGA . "users order by id DESC  LIMIT 1 ) as userid,
	(SELECT `levelis` FROM " . LENTELES_PRIESAGA . "users order by id DESC  LIMIT 1 ) as lvl
	FROM " . LENTELES_PRIESAGA . "kas_prisijunges WHERE `timestamp`>'" . $timeout . "' AND user='Svecias'
	LIMIT 1");

$progresas = procentai((!empty($stats['uzvakar']) ? $stats['uzvakar'] : 1), (!empty($stats['vakar']) ? $stats['vakar'] : 1));
$memberis = user($sql['useris'], $sql['userid'], $sql['lvl']);
//chart
?>
<script type="text/javascript">
	$(document).ready(function(){
		$('#test').append((new Date()).getTime());
		var d = [[<?php echo ((time() - 86400 * 2) * 1000); ?>,<?php echo $stats['uzvakar']; ?>],[<?php echo ((time() - 86400) * 1000); ?>,<?php echo $stats['vakar']; ?>],[<?php echo (time() * 1000); ?>,<?php echo $stats['siandien']; ?>]];
		$.plot($("#placeholder"), [d], {
			xaxis: {
				mode: "time"
			},
			grid: {
				color: "#666",
				borderWidth: 1
			}
		});
	});
</script>

<?php
//table
$text = <<<HTM
<div class="left">
<h2>{$lang['system']['tree']}</h2>
<ul id="treemenu">{$tree}</ul>
</div>

<div class="right">

<div class="leftui">
<h2>{$lang['system']['some_data']}</h2>
<ul>
<li>{$lang['online']['users']} {$lang['online']['usrs']}: <span class="number">{$sql['users']}</span></li>
<li>{$lang['online']['users']} {$lang['online']['guests']}: <span class="number">{$sql['svec']}</span></li>
<li>{$lang['online']['traffic_in']}: <span class="number">{$progresas}%</span></li>
<li>{$lang['online']['today']}: <span class="number">{$stats['siandien']}</span></li>
<li>{$lang['online']['registeredmembers']}: <span class="number">{$sql['useriai']}</span></li>
<li>{$lang['online']['lastregistered']}: <span class="number">{$memberis}</span></li>
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
  $('#txtUrl').val('http://mightmedia.lt/RSS');
  $('#txtCount').val('5');
  $('#chkDate').attr('checked','checked');
  $('#chkDesc').attr('checked','checked');
   $('#divRss').FeedEk({
   FeedUrl : 'http://mightmedia.lt/RSS',
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
lentele($lang['system']['control'], $text);
?>