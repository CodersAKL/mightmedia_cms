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

$sql1 = mysql_query1("SELECT SQL_CACHE * FROM `" . LENTELES_PRIESAGA . "page` ORDER BY `place` ASC LIMIT 12");
$text = '';
foreach ($sql1 as $row1) {
	if ($row1['show'] == "Y" && (int)$_SESSION['level'] >= (int)$row1['teises']) {
		$text .= '<li><a href="?id,' . (int)$row1['id'] . '">' . input($row1['pavadinimas']) . '</a></li>';
	}
}
echo $text;

?></ul>
            </div>
<div class="header_bar"><a href="?" title="<?php adresas(); ?>"><img src="stiliai/<?php echo $conf['Stilius']; ?>/images/hdr_left.jpg" alt=""/></a></div>

<div class="title">
             <?php echo input(strip_tags($conf['Apie'])); ?>
            </div>
</div>

<div class="kaire">

<?php include ("priedai/kairespaneles.php"); ?>
</div>

<div class="center">
<div class="title"><?php if (isset($conf['puslapiai']['online.php'])): ?>Šiuo metu svetaineje: <a href="?id,<?php echo $conf['puslapiai']['online.php']['id']; ?>"><?php echo (int)$online; ?></a><?php endif; ?></div>
<div class="vidus">
<?php

if (isset($strError) && !empty($strError)) {
	klaida("Klaida", $strError);
}
include ($page . ".php");

?>
</div>
</div>
<div class="desine">

<?php include ("priedai/desinespaneles.php"); ?>
</div>

<div style="clear: both;"> </div>

</div>

<div id="footer">
<?php copyright( $conf['Copyright'] );?>
</div>

</div>
</body>
</html>