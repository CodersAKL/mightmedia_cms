<?php
$directory = stripslashes($_GET['dir']) . '/';
if(isset($_GET['edit'])){
	if(isset($_GET['name'])){
		if(file_exists($directory.'media_playlists')){
			$directory = $directory.'media_playlists/';
		}
		$File = $directory . $_GET['name'] . '.xml';
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		echo '<html>
		<head>';
		echo '<link href="../../popups/popup.css" type="text/css" rel="stylesheet" />
		<link rel="stylesheet" type="text/css" href="'.$_GET['base'].'skins/'.$_GET['skin'].'/skin.css" />';
		echo "</head>
		<body class='dialog' style='height: 100%;'>
		<table cellpadding=\"7\" border=\"0\" class='dialog' width=\"100%\">";
		if(file_exists($File)){
			if($_GET['edit'] != 'edit'){
				$xml_file = file_get_contents($File);
				$remove_items = split('<item>',$xml_file);
				foreach($remove_items as $name => $value){
					if($name != 0){
						$_GET['edit'] = urldecode($_GET['edit']);
						if(!eregi("$_GET[edit]",$value)){
							$new_xml.= "<item>".$value; 	
						}
					}else{
						$new_xml = $value.$new_xml;
					}
				}
				if(!eregi("</channel>",$new_xml)){
					$new_xml.= "</channel>
					</rss>";
				}
			$Handle = fopen($File, 'w') or die("can't open file");
			fwrite($Handle, $new_xml);
			fclose($Handle);
			}
			$xml_file = file_get_contents($File);
			$xml_items = split("<item>",$xml_file);
			$count=0;
			foreach($xml_items as $field => $value){
				if($field != 0){
					$title = split("title>",$value);
					$image = split("<media:thumbnail url=\"",$value);
					$file = split("<media:content url=\"",$value);
					$file_pos = strpos($file[1],'"');
					$file = substr_replace($file[1],'',$file_pos);
					echo "<form action=\"$_SERVER[PHP_SELF]\" method=\"GET\">\n
					<tr><td>Item <strong>".eregi_replace('</','',$title[1])."</strong>:</td>";
					echo "<td>".eregi_replace("$_GET[url]/",'',$file)."</td>";
					if($image[1] != ''){
						$img_pos = strpos($image[1],'"');
						$image = substr_replace($image[1],'',$img_pos);
						echo '<td><img src="'.$image.'" height="30" style="vertical-align: middle; border: 1px solid black;" \></td>';
					}
					echo "<td><button onclick=\"this.form.submit();\">Remove</button>\n
					<input type=\"hidden\" name=\"edit\" value=\"".eregi_replace('</','',$title[1])."\">\n";
					foreach($_GET as $name => $value){
						if($name != 'edit'){
							echo "<input type=\"hidden\" name=\"$name\" value=\"$value\">\n";
						}
					}
					echo "</td></tr>\n
					</form>";
				}
			}
		}
		echo "</table>
		</body>
		<html>";
	}
}else{
	if(isset($_GET['content'])){
		if(!file_exists($directory.'media_playlists')){
			if($_GET['newdir'] == 1){
				$directory = $directory.'media_playlists/';
				mkdir ($directory, 777);
			}
		}else{
			$directory = $directory.'media_playlists/';
		}
		$_GET['name'] = trim(stripslashes(strip_tags($_GET['name'])));
		$File = $directory . $_GET['name'] . '.xml';
		if(!file_exists($File)){
			$content = "<rss version=\"2.0\" xmlns:media=\"http://search.yahoo.com/mrss\">
			<channel>
			<title>$_GET[name]</title>
			<link>http://cyber.law.harvard.edu/rss/rss.html</link>\n";
			$w_method = 'x';
		}else{
			$content = eregi_replace('</channel>','',file_get_contents($File));
			$content = eregi_replace('</rss>','',$content);
			$w_method = 'w';
		}
		if(eregi("mp3",substr($_GET['content'],-3))){$type = 'type="audio/mpeg"';}
		else if(eregi("flv",substr($_GET['content'],-3))){$type = 'type="video/x-flv"';}
		else if(eregi("gif",substr($_GET['content'],-3))){$type = 'type="image/gif" duration="10"';}
		else if(eregi("jpg",substr($_GET['content'],-3))){$type = 'type="image/jpeg" duration="10"';}
		else if(eregi("png",substr($_GET['content'],-3))){$type = 'type="image/png" duration="10"';}
			
			$content.="<item>
				<title>$_GET[title]_ID_".createRandom(2)."</title>
				<link>$_GET[link]</link>
				<media:content url=\"$_GET[content]\" $type />
				<media:thumbnail url=\"$_GET[thumb]\" />
			</item>
		</channel>
		</rss>";

		$Handle = fopen($File, $w_method) or die("can't open file - check your folder permissions");
		fwrite($Handle, $content) or die("can't write file - perhaps your playlist title contains invalid characters or this folder has insufficient permissions");
		fclose($Handle);

		echo file_get_contents($File); 
	}else if(isset($_GET['name'])){
		if(file_exists($directory.'media_playlists')){
			$directory = $directory.'media_playlists/';
		}
		$File = $directory . $_GET['name'] . '.xml';
		if(file_exists($File)){
			echo file_get_contents($File); 
		}
	}
}
function createRandom($length) {
	$alphaPool .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$alphaPool .= "abcdefghijklmnopqrstuvwxyz";
	$alphaPool .= "0123456789";
	$randomName = ""; 
		for($i = 0; $i < $length; $i++) {    
			$randomName .= substr($alphaPool,(rand()%(strlen($alphaPool))),1); 
		}  
	return($randomName); 
}
?>