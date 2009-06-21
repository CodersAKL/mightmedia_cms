<?php 
header("content-type: application/xhtml+xml; charset=UTF-8"); 
require_once('priedai/conf.php');
if(isset($conf['puslapiai']['rss.php'])){?>

 <rss version="2.0">
 	<channel>
 		<title><?php echo htmlspecialchars($conf['Pavadinimas']);?></title>
 		<link><?php  echo adresas();?></link>
 		<description><?php echo $conf['Apie'];?></description>
 		<language>en-lt</language>
 		<copyright><?php echo htmlspecialchars($conf['Copyright']);?></copyright>
 		<managingEditor><?php echo htmlspecialchars($conf['Pastas']);?></managingEditor>
 		<webMaster><?php echo htmlspecialchars($conf['Pastas']);?></webMaster>
 		<pubDate><?php echo htmlspecialchars(date('Y-m-d H:i:s'));?></pubDate>
 		<lastBuildDate><?php echo htmlspecialchars(date('Y-m-d H:i:s'));?></lastBuildDate>
 		<category><?php echo htmlspecialchars($conf['Pavadinimas']);?></category>
 		<generator>Virtuosi Media RSS Generator</generator>
 		<docs>http://www.rssboard.org/rss-specification</docs>
 		<ttl>50</ttl>
 		
 
 <?php
/*
 <image>
 			<url>http://www.virtuosimedia.com/images/logo_fancy.png</url>
 			<title>Virtuosi Media</title>
 			<link>http://www.virtuosimedia.com</link>
 			<height>95</height>
 			<width>133</width>
 			<description>A web development resource center</description>
 		</image>*/

 $result = mysql_query1("SELECT SQL_CACHE * FROM `" . LENTELES_PRIESAGA . "naujienos`	WHERE `rodoma`= 'TAIP'	ORDER BY `data` DESC LIMIT 50");
 
 //Iterate over the rows to create each item
 //<image>'.adresas()."images/naujienu_kat/".$kategorija['pav'].'</image>
 foreach($result as $row) {
 $kategorija = mysql_query1("SELECT  SQL_CACHE * FROM `" . LENTELES_PRIESAGA . "grupes` where `id`=".escape($row['kategorija'])." LIMIT 1");
 if((isset($kategorija['teises']) && teises($kategorija['teises'], 0))||!isset($kategorija['teises'])){
 echo '
 <item>
 		<title>'.$row['pavadinimas'].'</title>
 		<link>'.adresas().'?id,'.$conf['puslapiai']['naujienos.php']['id'].';k,'.$row['id'].'</link>
 		<description>'.htmlspecialchars($row['naujiena']).'</description>
 		<author>'.$row['autorius'].'</author>
 		'.(isset($kategorija['pavadinimas'])?'<category>'.$kategorija['pavadinimas'].'</category>':'').'
 		<pubDate>'.date('Y-m-d H:i:s ', $row['data']).'</pubDate>
 		<source url="'.adresas().'">'.$conf['Pavadinimas'].' RSS</source>
</item>
';
 }
} 
 ?>
 
 	</channel>
 </rss>
 <?php }else header("Loacation:index.php");?>