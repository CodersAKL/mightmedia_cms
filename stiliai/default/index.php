<html>
<head>
<?php

header_info();

?>

</head>

<body>
<noscript>
 <?php klaida("Klaida", "Prašome įjunkite javascript palaikymą"); ?>
 <meta http-equiv="refresh" content="0;url=javascript.html">
</noscript>
<div class="main">
  <table width="970" border="0" align="center" cellpadding="2" cellspacing="5">
    <tr>
      <td height="123" colspan="3">
	    <h4 style="display:none"><?php echo $conf['Pavadinimas']; ?></h4>
       <table width="100%" border="0" cellspacing="0" cellpadding="0">
         <tr>
           <td>
            <div id="mygtukaii">
                <?php

$sql1 = mysql_query1("SELECT SQL_CACHE * FROM `" . LENTELES_PRIESAGA . "page` ORDER BY `place` ASC LIMIT 12");
$text = '';
foreach ($sql1 as $row1) {
	if ($row1['show'] == "Y" && puslapis($row1['file'])) {
		$text .= '<a href="?id,' . (int)$row1['id'] . '">' . input($row1['pavadinimas']) . '</a>';
	}
}
echo $text;

?>
            </div>
	        </td>
          </tr>
         <tr>
           <th scope="col" style="background-image:url(stiliai/<?php echo urlencode($conf['Stilius']); ?>/images/hdr_bg.jpg)"><a href="?"><img src="stiliai/<?php echo $conf['Stilius']; ?>/images/hdr_left.jpg" border="0" align="left" /></a><img src="stiliai/<?php

echo $conf['Stilius'];

?>/images/bug.gif" /></th>
         </tr>
         <tr>
           <td class="title" scope="col">
            <marquee  behavior='scroll' scrollamount='1' scrolldelay='1' onmouseover='this.stop()' onmouseout='this.start()'>
             <?php echo input(strip_tags($conf['Apie'])); ?>
            </marquee>
          </td>
         </tr>
       </table>
      </td>
    </tr>
    
    <tr>
      <td width="20%" valign="top">
		<?php include ("priedai/kairespaneles.php"); ?>
        <p><img src="stiliai/<?php echo urlencode($conf['Stilius']); ?>/images/tarpine.jpg" width="200" height="0" /></p>
      </td>
      <td width="60%" valign="top">
        <div class="title"><?php if (isset($conf['puslapiai']['online.php'])): ?>Šiuo metu svetainėje: <a href="?id,<?php echo $conf['puslapiai']['online.php']['id']; ?>"><?php echo (int)$online; ?></a><?php endif; ?></div>
        <div class="vidus">
		<?php

if (isset($strError) && !empty($strError)) {
	klaida("Klaida", $strError);
}
include ($page . ".php");

?>
		</div>
      </td>
	  <td width="20%" valign="top">
		<?php include ("priedai/desinespaneles.php"); ?>
	    <p><img src="stiliai/<?php echo urlencode($conf['Stilius']); ?>/images/tarpine.jpg" width="200" height="0" /></p>
      </td>
    </tr>
  </table>
<?php

$m2 = explode(" ", microtime());
$etime = $m2[1] + $m2[0];
$ttime = ($etime - $stime);
$ttime = number_format($ttime, 7);
if ($conf['Render'] == 1) {
	$ttime = "Sugeneruotas per: " . apvalinti($ttime, 2) . "s.";
} else {
	$ttime = '';
}
copyright("$ttime\n" . $conf['Copyright'] . "");
if (defined("LEVEL") && LEVEL == 1) {
	copyright("Mysql uzklausu: $mysql_num");
}
unset($extra, $meniu, $text, $ttime);
$mysql_num;
?></div>
</body>
</html>
