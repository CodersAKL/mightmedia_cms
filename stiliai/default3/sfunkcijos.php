<?php

function hide($pavadinimas, $tekstas, $hide = false, $label = false, $id = false) {
	lentele($pavadinimas, $tekstas);

}
function lentele_r($pavadinimas, $tekstas, $label = false) {
	lentele_l($pavadinimas, $tekstas);
}
function lentele_l($pavadinimas, $tekstas, $label = false) {
	echo "<div class=\"block\">
              <div class=\"title\">$pavadinimas</div>
              <div class=\"content\">$tekstas</div>
              <div class=\"foot\"></div>
          </div>";
}

function lentele($pavadinimas, $tekstas, $label = false) {
	echo "<h2>{$pavadinimas}</h2>".($label ? "<div style=\"font-size: 12px; vertical-align: middle; z-index: 22; margin:5px 0;\">{$label}</div>" : "")."
    <p>{$tekstas}</p><br />";
}


function klaida($pavadinimas,$tekstas,$label=false) {
	echo '<div style="background-color:#FFFFCC;padding:10px;border:1px solid #FFCC99">';
	lentele($pavadinimas,"<img src=\"images/icons/emblem-important.png\" align=\"left\" hspace=\"10\" alt=\"Klaida\"/>".$tekstas);
	echo '</div>';
}
function msg($pavadinimas,$tekstas,$label=false) {
	echo '<div style="background-color:#FFFFCC;padding:10px;border:1px solid #FFCC99">';
	lentele($pavadinimas,"<img src=\"images/icons/tick.png\" align=\"left\" hspace=\"10\" alt=\"Klaida\"/>".$tekstas);
	echo '</div>';
}
function ofunc() {
	echo "
		<table width=\"100%\" border=\"0\">
			<tr>
				<td><div class=\"title\">Masyvas</div></td>
				<td><div class=\"title\">Paaiškinimas</div></td>
			</tr>
			<tr>
		 ";
}
function cfunc() {
	echo "</table>";
}
function copyright($tekstas) {
	global $mysql_num;
	echo "$tekstas " . (defined('LEVEL') && LEVEL == 1 ? 'MySQL: ' . $mysql_num : '') . "";
}
function th_meniu($array, $start = '', $end = '') {
	$return = $start . "\n";
	foreach ($array as $key => $val) {
		if (is_array($val)) {
			$return .= "\t<li>$key\n";
			$return .= "\t<ul>\n\t";
			$return .= "\t" . th_meniu($val);
			$return .= "\t</ul></li>\n";
		} else {
			$return .= "\t<li><a href='$val'>$key</a></li>\n";
		}
	}
	return $return . "\n" . $end;
}
function bbk($forma) {
	$return = "
<button onclick=\"addText('$forma', '[b]', '[/b]'); return false;\" title=\"B\"><img src=\"images/icons/text_bold.png\"></button>
<button onclick=\"addText('$forma', '[i]', '[/i]'); return false;\" title=\"I\"><img src=\"images/icons/text_italic.png\"></button>
<button onclick=\"addText('$forma', '[u]', '[/u]'); return false;\" title=\"U\"><img src=\"images/icons/text_underline.png\"></button>
<button onclick=\"addText('$forma', '[url]', '[/url]'); return false;\" title=\"URL\"><img src=\"images/icons/link.png\"></button>
<button onclick=\"addText('$forma', '[big]', '[/big]'); return false;\" title=\"BIG\"><img src=\"images/icons/text_heading_1.png\"></button>
<button onclick=\"addText('$forma', '[sm]', '[/sm]'); return false;\" title=\"SM\"><img src=\"images/icons/text_heading_6.png\"></button>
<button onclick=\"addText('$forma', '[img]', '[/img]'); return false;\" title=\"IMG\"><img src=\"images/icons/picture.png\"></button>
<button onclick=\"addText('$forma', '[quote]', '[/quote]'); return false;\" title=\"QUOTE\"><img src=\"images/icons/comment.png\"></button> 
";
	if (LEVEL == 1) {
		$return .= "
<button onclick=\"addText('$forma', '[hide=Tik registruotiems]', '[/hide]'); return false;\" title=\"HIDE\"><img src=\"images/icons/shield.png\"></button>
";
	}
	return $return . "<br />";
}

function bbs($forma) {
	return smile("
		:-)
		;-)
		:-]
		:-D
		:-o
		:(
		B-)
		:-|
		:P
		|)
		:-/
		", $forma) . "<br/>";
}

//SYPSENOS
function smile($data, $bb = false) {
  global $conf; 
	$smilies = array(':)' => 'square_smile.png', ':]' => 'square_smug.png', ':-)' => 'square_smile.png', ';-)' => 'square_wink.png', ';)' => 'square_wink.png', ':-]' => 'square_smug.png', ':-D' => 'square_biggrin.png', ':D' => 'square_biggrin.png', ':o' => 'square_eek.png', ':-o' => 'square_eek.png', ':O' => 'square_eek.png', ':(' => 'square_frown.png', 'B-)' => 'square_cool.png', 'B)' => 'square_cool.png', '%-)' => 'square_cool.png', ':-|' => 'square_unsure.png', ':|' => 'square_unsure.png', ':P' => 'square_tongue.png', ':p' => 'square_tongue.png', '|)' => 'square_mad.png', '0_o' => 'square_eek.png', 'o_0' => 'square_eek.png', ':-/'=>'square_confused.png');
	foreach ($smilies as $smile => $image) {
		//$data = str_replace($smile,"<img src='images/smiles/$image' alt='".$smile."' class='middle' ".(($bb)?"onclick=\"addText('".$bb."','".$smile."',' ');\" style='cursor: pointer;'":"")." />",$data);
		$data = str_replace($smile, "<img src='stiliai/{$conf['Stilius']}/smilies/$image' alt='" . $smile . "' class='middle' onclick=\"addText('" . $bb . "','" . $smile . "',' ');\" style='cursor: pointer;' />", $data);
	}
	return $data;
}

//bbcodo varijantas 1
function bb2html($Input) {
	$Bbcode = array('/\[b\](.+?)\[\/b\]/i', '/\[i\](.+?)\[\/i\]/i', '/\[quote\](.+?)\[\/quote\]/i', '/\[url=(.+?)\](.+?)\[\/url\]/i');
	$Html = array('<strong>$1</strong>', '<em>$1</em>', '<blockquote>$1</blockquote>', '<a href="$1">$2</a>');
	return preg_replace($Bbcode, $Html, $Input);
}


// bbcode

function nl2br2($text) {
	return str_replace('<br />', '', $text);
}
function htmlspecialchars2($text) {
	static $patterns, $replaces;
	if (!$patterns) {
		$patterns = array('#&lt;#', '#&gt;#', '#&amp;#', '#&quot;#');
		$replaces = array('<', '>', '&', '"');
	}
	return preg_replace($patterns, $replaces, $text);
}
function bbchat($str) {

	//$str=htmlspecialchars(trim($str));
	$str = descript($str);
	// paryskinam teksta
	$str = preg_replace("#\[b\](.*?)\[/b\]#si", "<b>\\1</b>", $str);
	// Paverciam teksta
	$str = preg_replace("#\[i\](.*?)\[/i\]#si", "<i>\\1</i>", $str);
	// Pabraukiam teksta
	$str = preg_replace("#\[u\](.*?)\[/u\]#si", "<u>\\1</u>", $str);
	// Mazas sriftas
	$str = preg_replace("#\[sm\](.*?)\[/sm\]#si", "<small>\\1</small>", $str);
	// Specialus simboliai
	$str = str_replace('&amp;plusmn;', '&plusmn;', $str);
	$str = str_replace('&amp;trade;', '&trade;', $str);
	$str = str_replace('&amp;bull;', '&bull;', $str);
	$str = str_replace('&amp;deg;', '&deg;', $str);
	$str = str_replace('&amp;copy;', '&copy;', $str);
	$str = str_replace('&amp;reg;', '&reg;', $str);
	$str = str_replace('&amp;hellip;', '&hellip;', $str);
	$str = str_replace('&amp;#8230;', '&hellip;', $str);
	// Konvertuojam naujas eilutes i <br/>
	$str = nl2br($str);
	//padarom grazias kabutes
	$str = preg_replace("#\&quot;(.+?)\&quot;#si", "<q>\\1</q>", $str);
	//$str = preg_replace("/\"(.+?)\"/i", "<q>\\1</q>", $str);

	// js
	$str = preg_replace_callback("#\<(.*?)javascript(.*?)\>#si", "bbcode_js", $str);

	return $str;
}

function bbcode($str) {
	// Problemos su \ slashais
	//$str=str_replace("\\","&#92;",$str);
	//$str = preg_replace('&','#&amp;#',$str);
	$str = input($str);
	$str = preg_replace("#\[php\](.*?)\[\/php\]#sie", "base64encode('<textarea name=\"code\" class=\"php\" rows=\"15\" cols=\"100\">\\1</textarea>')", $str);
	$str = preg_replace("#\[mirc\](.*?)\[\/mirc\]#sie", "base64encode('<textarea name=\"code\" class=\"mirc\" rows=\"15\" cols=\"100\">\\1</textarea>')", $str);
	$str = preg_replace("#\[html\](.*?)\[\/html\]#sie", "base64encode('<textarea name=\"code\" class=\"html\" rows=\"15\" cols=\"100\">\\1</textarea>')", $str);
	$str = preg_replace("#\[css\](.*?)\[\/css\]#sie", "base64encode('<textarea name=\"code\" class=\"css\" rows=\"15\" cols=\"100\">\\1</textarea>')", $str);
	$str = preg_replace("#\[js\](.*?)\[\/js\]#sie", "base64encode('<textarea name=\"code\" class=\"js\" rows=\"15\" cols=\"100\">\\1</textarea>')", $str);
	$str = preg_replace("#\[sql\](.*?)\[\/sql\]#sie", "base64encode('<textarea name=\"code\" class=\"sql\" rows=\"15\" cols=\"100\">\\1</textarea>')", $str);

	// Atverciam linka naujame lange
	$str = preg_replace("#\[url\](.*?)?(.*?)\[/url\]#si", "<A HREF=\"\\1\\2\" TARGET=\"_blank\" class=\"link\">\\1\\2</A>", $str);
	$str = preg_replace("#\[url=(.*?)?(.*?)\](.*?)\[/url\]#si", "<A HREF=\"\\2\" TARGET=\"_blank\" class=\"link\">\\3</A>", $str);

	// Atverciam linka tame paciame lange
	$str = preg_replace("#\[url2\](.*?)?(.*?)\[/url2\]#si", "<A HREF=\"\\1\\2\">\\1\\2</A>", $str);
	$str = preg_replace("#\[url2=(.*?)?(.*?)\](.*?)\[/url2\]#si", "<A HREF=\"\\2\">\\3</A>", $str);

	// Automatiskai konvertuojam nuorodas
	$str = preg_replace_callback("#([\n ])([a-z]+?)://([a-z0-9\-\.,\?!%\*_\#:;~\\&$@\/=\+]+)#si", "bbcode_autolink", $str);
	//$str = preg_replace("#([\n ])www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[a-z0-9\-\.,\?!%\*_\#:;~\\&$@\/=\+]*)?)#i", " <a href=\"http://www.\\2.\\3\\4\" target=\"_blank\">www.\\2.\\3\\4</a>", $str);
	$str = preg_replace("#([\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+)#i", "\\1<a href=\"javascript:mailto:mail('\\2','\\3');\">\\2_(at)_\\3</a>", $str);

	// Slepti tekstui - rodomas tik registruotiems vartotojams
	$str = preg_replace_callback("#\[hide=\"?(.*?)\"?\](.*?)\[/hide]#si", 'ukryj', $str);

	// Paryskinam teksta
	$str = preg_replace("#\[b\](.*?)\[/b\]#si", "<b>\\1</b>", $str);

	// Paverciam teksta
	$str = preg_replace("#\[i\](.*?)\[/i\]#si", "<i>\\1</i>", $str);

	// Pabraukiam teksta
	$str = preg_replace("#\[u\](.*?)\[/u\]#si", "<u>\\1</u>", $str);

	// Mazas tekstas
	$str = preg_replace("#\[sm\](.*?)\[/sm\]#si", "<small>\\1</small>", $str);

	// Didelis tekstas
	$str = preg_replace("#\[big\](.*?)\[/big\]#si", "<big>\\1</big>", $str);

	// Centruojam
	$str = preg_replace("/\[center\](.*?)\[\/center\]/si", "<center>\\1</center>", $str);

	// Spalvojam teksta
	$str = preg_replace("#\[color=(http://)?(.*?)\](.*?)\[/color\]#si", "<span style=\"color:\\2\">\\3</span>", $str);

	// Rodom paveiksliuka
	$str = preg_replace("/\[img\](http:\/\/[^\s'\"<>]+(\.gif|\.jpg|\.png))\[\/img\]/", "<img border=0 src=\"\\1\" alt=\"pic\" onError=\"this.src='images/icons/nopic.png';this.style.border='1px dashed red';this.style.margin='5px';this.style.padding='5px'\">", $str);
	$str = preg_replace("/\[img\](http:\/\/[^\s'\"<>]+(\.GIF|\.JPG|\.PNG))\[\/img\]/", "<img border=0 src=\"\\1\" alt=\"pic\" onError=\"this.src='images/icons/nopic.png';this.style.border='1px dashed red';this.style.margin='5px';this.style.padding='5px'\">", $str);

	// [img=http://www/image.gif]
	$str = preg_replace("/\[img=(http:\/\/[^\s'\"<>]+(\.gif|\.jpg|\.png))\]/", "<img border=0 src=\"\\1\">", $str);
	$str = preg_replace("/\[img=(http:\/\/[^\s'\"<>]+(\.GIF|\.JPG|\.PNG))\]/", "<img border=0 src=\"\\1\">", $str);

	// Cituojam
		if (preg_match('#\[quote\](.*?)\[/quote]#si', $str)) {
		$str = preg_replace('#\[quote=(&quot;|"|\'|)(.*)\\1\]#seU', '"<blockquote><u><b>".str_replace(\'[\', \'&#91;\', \'$2\')." rašė:</b></u><br/>"', $str);
		$str = str_replace('[quote]', "<blockquote><u><b>Citata:</b></u><br/>", $str);
		$str = str_replace('[/quote]', '</blockquote>', $str);
	}

	// Cituojam autoriu
	$str = preg_replace("#\[quote=(http://)?(.*?)\](.*?)\[/quote]#si", "<blockquote><u><b>\\2 rašė:</b></u><br/>\\3</blockquote>", $str);

	// Sarašas
	$str = preg_replace("#\[list\](.*?)\[/list\]#si", "<ul>\\1</ul>", $str);
	$str = preg_replace("#\[list=(http://)?(.*?)\](.*?)\[/list\]#si", "<ol type=\"\\2\">\\3</ol>", $str);
	$str = preg_replace("#\[\*\](.*?)\\s#si", "<li>\\1</li>", $str);

	// Atvaizduojam emailą
	$str = preg_replace("#\[email\]([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+)\[/email\]#i", "<a href=\"mailto:\\1@\\2\">\\1@\\2</a>", $str);

	// HTML formavimas
	$str = nl2br($str);
	$str = str_replace("[br]", "<br/>", $str);
	$str = smile($str);

	// Iskvieciam kodu atvaizdavima
	$str = preg_replace_callback("#\<base64\>(.*?)\</base64\>#si", "base64decode", $str);

	return $str;
}

// Atkoduojam koda
function base64decode($str) {
	return base64_decode($str[1]);
}

// Užkoduojam kodą
function base64encode($str) {
	return "<base64>" . base64_encode($str) . "</base64>";
}

// Teksto slepimui
function ukryj($match) {
	$id = uniqid('');
	$return = (defined("LEVEL")&& LEVEL>0) ? $match[2] : 'Tik registruotiems vartotojams';
	return '<font color="red">▲</font> <a href="#" onclick="flip(\'' . $id . '\'); return false;"><b>' . $match[1] . '</b> <font color="red">▼</font></a><div id="' . $id . '" class="ukryj" style="display: none;">' . $return . '</div>';
}

// Emailo apsauga
function bbcode_js($str) {
	if (!eregi('<a href=\"javascript:mailto:mail\(\'', $str[0])) {
		return str_replace('javascript', 'java_script', $str[0]);
	} else {
		return $str[0];
	}
}

// nuorodos kode
function bbcode_autolink($str) {
	$lnk = $str[3];
	if (strlen($lnk) > 30) {
		if (substr($lnk, 0, 3) == 'www') {
			$l = 9;
		} else {
			$l = 5;
		}
		$lnk = substr($lnk, 0, $l) . '...' . substr($lnk, strlen($lnk) - 8);
	}
	return ' <a href="' . $str[2] . '://' . $str[3] . '" target="_blank" class="link"> ' . $str[2] . '://' . $lnk . ' </a>';
}

// Jei zmogus perkele teksta i kita eilute tai taip ir atvaizduojam
function lauzymas($txt) {
	//return str_replace("\r\n", "<br>", $txt);
}

?>
