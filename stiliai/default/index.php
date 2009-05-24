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
<div class="pagr">
<div class="virsus"></div>
<div class="aplink">
<div class="kaire">
<div class="shadow">
<?php include ("priedai/kairespaneles.php"); ?>
</div>
<div class="shadow_bottom"></div>
</div>

<div class="center">


<?php

if (isset($strError) && !empty($strError)) {
	klaida("Klaida", $strError);
}
include ($page . ".php");

?>

</div>
<div class="desine">
<div class="shadow">
<?php include ("priedai/desinespaneles.php"); ?>
</div>
<div class="shadow_bottom"></div>
</div>


<div style="clear: both;"> </div>

</div>
</div>
<div  class="title">
<?php copyright( $conf['Copyright'] );?>
</div>
</div>
</div>
</body>
</html>