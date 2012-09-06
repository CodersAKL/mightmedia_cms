<?php

header( 'Content-type: text/xml' );
define( 'ROOT', '' );

require_once( 'priedai/conf.php' );

if ( isset( $conf['puslapiai']['galerija.php'] ) ) {

	$rss = new RssGallery;
	//$rss->display(adresas());
} else {
	echo $lang['admin']['gallery_noimages'];
}

class RssGallery
{

	function __construct() {

		echo $this->header();
		echo $this->GetGallery();
		echo $this->footer();
	}

	function header() {

		return '
<rss version="2.0"
             xmlns:media="http://search.yahoo.com/mrss/"
             xmlns:atom="http://www.w3.org/2005/Atom">
            <channel>
    <title>' . $conf['Pavadinimas'] . '</title>
    <link>' . adresas() . '</link>
    <description>' . strip_tags( $conf['Apie'] ) . '</description>
    <language>en-us</language>
    <lastBuildDate>' . date( "D, d M Y h:i:s" ) . ' EST</lastBuildDate>
    <atom:link href="' . adresas() . 'gallery.php" rel="self" type="application/rss+xml" />
';
	}

	function footer() {

		return '  </channel>
</rss>';
	}

	function GetGallery() {

		global $conf;
		$rssItems = '';
		$query    = "SELECT * FROM `" . LENTELES_PRIESAGA . "galerija` WHERE `rodoma` = 'TAIP' ORDER BY id DESC";
		$query    = mysql_query1( $query, 3600 );

		foreach ( $query as $row ) {
			$id        = $row['ID'];
			$title     = trimlink( $row["pavadinimas"], 50 );
			$text      = input( $row["apie"] );
			$img_id    = $row["file"];
			$timestamp = $row["data"];

			$rssItems .= "    <item>
      <title>" . $title . "</title>
      <link>" . url( "?id," . $conf['puslapiai']['galerija.php']['id'] . ";m," . $id ) . "</link>
      <media:thumbnail url=\"" . adresas() . "images/galerija/" . $img_id . " \"/>
      <media:content url=\"" . adresas() . "images/galerija/originalai/" . $img_id . " \"/>
      <guid isPermaLink=\"false\">" . $img_id . "</guid>
    </item>\r\n";
		}

		return $rssItems;
	}

}

?>

