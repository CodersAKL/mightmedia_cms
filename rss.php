<?php 
header("content-type: application/xml; charset=UTF-8"); 
require_once('priedai/conf.php');
if(isset($conf['puslapiai']['rss.php'])){
	echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
 <rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom">
 	<channel>
 		<title><?php echo htmlspecialchars($conf['Pavadinimas']);?></title>
 		<link><?php  echo adresas();?></link>
 		<description><![CDATA[ <?php echo $conf['Apie'];?> ]]></description>
 		<language>en-lt</language>
 		<copyright><![CDATA[ <?php echo strip_tags($conf['Copyright']);?> ]]></copyright>
 		<managingEditor><![CDATA[ <?php echo htmlspecialchars($conf['Pastas']).' ('.$admin_name.')';?> ]]></managingEditor>
 		<webMaster><![CDATA[ <?php echo htmlspecialchars($conf['Pastas']).' ('.$admin_name.')';?> ]]></webMaster>
 		<pubDate><?php echo date("D, d M Y H:i:s O");?></pubDate>
 		<atom:link href="<?php echo adresas();?>/rss.php" rel="self" type="application/rss+xml" />
 		<lastBuildDate><?php echo date("D, d M Y H:i:s O");?></lastBuildDate>
 		<category><![CDATA[ <?php echo htmlspecialchars($conf['Pavadinimas']);?> ]]></category>
 		<generator>MightMedia TVS</generator>
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
 $kategorija = mysql_query1("SELECT  SQL_CACHE * FROM `" . LENTELES_PRIESAGA . "grupes` where `id`=".escape($row['kategorija'])." LIMIT 1",60);
 $nickas = mysql_query1("SELECT `email` FROM `" . LENTELES_PRIESAGA . "users` WHERE `nick` = ".escape($row['autorius'])." LIMIT 1",60);
 if((isset($kategorija['teises']) && teises($kategorija['teises'], 0))||!isset($kategorija['teises'])){
 echo '
 <item>
 		<title><![CDATA['.$row['pavadinimas'].']]></title>
 		<link>'.adresas().'?id,'.$conf['puslapiai']['naujienos.php']['id'].';k,'.$row['id'].'</link>
 		<description><![CDATA[ '.$row['naujiena'].' <br />'.$row['daugiau'].' ]]></description>
 		<author><![CDATA['.$nickas['email'].' ('.$row['autorius'].')]]></author>
 		'.(isset($kategorija['pavadinimas'])?'<category>'.$kategorija['pavadinimas'].'</category>':'').'
 		<pubDate>'.date('D, d M Y H:i:s O', $row['data']).'</pubDate>
 		<source url="'.adresas().'">'.$conf['Pavadinimas'].' RSS</source>
 		<guid>'.adresas().'?id,'.$conf['puslapiai']['naujienos.php']['id'].';k,'.$row['id'].'</guid>
</item>
';
 }
} 
 ?>
 
 	</channel>
 </rss>
 <?php }else header("Loacation:index.php");?>