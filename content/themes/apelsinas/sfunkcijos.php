<?php
//Atvaizduojame remonto pranešimą
function maintenance($pavadinimas, $tekstas) {

	global $conf, $lang;

	?>
	<div class="card card-nav-tabs card-block">
		<div class="card-header card-header---bg">
			<h4 class="title">
				<?php echo $pavadinimas; ?>
			</h4>
		</div>
		<div class="card-body">
			<div class="card-text">
				<?php echo $tekstas; ?>
			</div>
		</div>
	</div>
	<?php
}

//Dešinės pozicijos blokai
//Jeigu neprireikia paliekame taip.
function lentele_r( $pavadinimas, $tekstas, $label = FALSE ) {

	lentele_l( $pavadinimas, $tekstas );
}

//Kairės pozicijos blokai
function lentele_l( $pavadinimas, $tekstas, $label = FALSE ) {
?>
	<div class="card card-nav-tabs card-block">
		<div class="card-header card-header---bg">
			<h4 class="title">
				<?php echo $pavadinimas; ?>
			</h4>
		</div>
		<div class="card-body">
			<div class="card-text">
				<?php echo $tekstas; ?>
			</div>
		</div>
	</div>
<?php
}

//Naujienų, straipsnių lentelė
function lentele_c( $pavadinimas, $tekstas, $n_nuoroda, $kom_kiekis = FALSE, $datai = FALSE, $autorius = FALSE) {

//Jei naudosim kalbystę ištraukiam $lang, jei ne ištrinam.
	global $lang, $page;
//Tvarkome skaitymo nuorodas
//$kom_kiekis - komentarų skaičius, $n_nuoroda - nuoroda skaitymui
	$data = date( 'Y-m-d', $datai );
//Naujienų
	if ( 'naujienos' == str_replace( 'content/pages/', '', $page ) ) {
		$skaitom = "{$lang['news']['read']} • {$lang['news']['comments']}({$kom_kiekis})";
//Straipsnių
	} else {
		$skaitom = "{$lang['article']['read']}({$kom_kiekis})";
	}
//Atvaizduojame
	?>

	<div class="section">
		<h2 class="title">
			<?php echo $pavadinimas; ?>
		</h2>
		<div class="description">
			<?php echo $tekstas; ?>
			<div class="btn btn-info btn-link">
				<?php echo $data; ?>
			</div>
			
			<div class="btn btn-link">
				<?php echo $autorius; ?>
			</div>
			<a href="<?php echo $n_nuoroda; ?>" class="btn btn-primary">
				<?php echo $skaitom; ?>
			</a>
		</div>	
	</div>
	<?php
}

//Centrinės pozicijos blokai
function lentele( $pavadinimas, $tekstas, $reitingai = FALSE ) {

	?>
	<div class="section">
		<h2 class="title">
			<?php echo $pavadinimas; ?>
		</h2>
		<div class="description">
			<?php echo $tekstas; ?>
		</div>	
	</div>
	<?php
}

//Atvaizduojame klaidos pranešimą
function klaida( $pavadinimas, $tekstas, $label = FALSE ) {
	?>
	<div class="alert alert-warning">
        <div class="container">
          <div class="alert-icon">
            <i class="material-icons">warning</i>
          </div>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="material-icons">clear</i></span>
          </button>
          <b><?php echo ucfirst($pavadinimas); ?></b> <?php echo $tekstas; ?>
        </div>
      </div>
	<?php
}

//Atvaizduojame įvykdymo pranešimą
function msg( $pavadinimas, $tekstas, $label = FALSE ) {
	?>
	<div class="alert alert-success">
        <div class="container">
          <div class="alert-icon">
            <i class="material-icons">success</i>
          </div>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="material-icons">clear</i></span>
          </button>
          <b><?php echo ucfirst($pavadinimas); ?></b> <?php echo $tekstas; ?>
        </div>
      </div>
	<?php
}

//Atvaizduojame Copyright
function copyright( $tekstas ) {

	global $mysql_num;
	echo $tekstas . ' ' . ( defined( 'LEVEL' ) && LEVEL == 1 ? 'MySQL: ' . $mysql_num : '' );
}

//Meniu bloko SUB MENIU funkcija
function th_meniu( $array, $start = '', $end = '' ) {

	$return = $start . "\n";
	foreach ( $array as $key => $val ) {
		if ( is_array( $val ) ) {
			$return .= "\t<li>$key\n";
			$return .= "\t<ul>\n\t";
			$return .= "\t" . th_meniu( $val );
			$return .= "\t</ul></li>\n";
		} else {
			$return .= "\t<li><a href='$val'>$key</a></li>\n";
		}
	}
	return $return . "\n" . $end;
}

//BB Kodai
function bbk($forma) {
	$return = "
<button onclick=\"addText('$forma', '[b]', '[/b]'); return false;\" title=\"B\"><img src=\"core/assets/images/icons/text_bold.png\"></button>
<button onclick=\"addText('$forma', '[i]', '[/i]'); return false;\" title=\"I\"><img src=\"core/assets/images/icons/text_italic.png\"></button>
<button onclick=\"addText('$forma', '[u]', '[/u]'); return false;\" title=\"U\"><img src=\"core/assets/images/icons/text_underline.png\"></button>
<button onclick=\"addText('$forma', '[url]', '[/url]'); return false;\" title=\"URL\"><img src=\"core/assets/images/icons/link.png\"></button>
<button onclick=\"addText('$forma', '[big]', '[/big]'); return false;\" title=\"BIG\"><img src=\"core/assets/images/icons/text_heading_1.png\"></button>
<button onclick=\"addText('$forma', '[sm]', '[/sm]'); return false;\" title=\"SM\"><img src=\"core/assets/images/icons/text_heading_6.png\"></button>
<button onclick=\"addText('$forma', '[img]', '[/img]'); return false;\" title=\"IMG\"><img src=\"core/assets/images/icons/picture.png\"></button>
<button onclick=\"addText('$forma', '[quote]', '[/quote]'); return false;\" title=\"QUOTE\"><img src=\"core/assets/images/icons/comment.png\"></button> <button style='padding-top:6px;' onclick=\"addText('$forma', '[php]', '[/php]'); return false;\" title=\"KODAS\"><b>KODAS</b></button> 
";

if ($_SESSION[SLAPTAS]['level'] == 1) {
$return .= "
<button onclick=\"addText('$forma', '[hide=Tik registruotiems]', '[/hide]'); return false;\" title=\"HIDE\"><img src=\"core/assets/images/icons/shield.png\"></button>
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
		//$data = str_replace($smile,"<img src='core/assets/images/smiles/$image' alt='".$smile."' class='middle' ".(($bb)?"onclick=\"addText('".$bb."','".$smile."',' ');\" style='cursor: pointer;'":"")." />",$data);
		$data = str_replace($smile, "<img src='content/themes/{$conf['Stilius']}/smilies/$image' alt='" . $smile . "' class='middle' onclick=\"addText('" . $bb . "','" . $smile . "',' ');\" style='cursor: pointer;' />", $data);
	}
	return $data;
}

//bbcodo varijantas 1
function bb2html($Input) {
	$Bbcode = array('/\[b\](.+?)\[\/b\]/i', '/\[i\](.+?)\[\/i\]/i', '/\[quote\](.+?)\[\/quote\]/i', '/\[url=(.+?)\](.+?)\[\/url\]/i');
	$Html = array('<strong>$1</strong>', '<em>$1</em>', '<blockquote>$1</blockquote>', '<a href="$1">$2</a>');
	return preg_replace_callback($Bbcode, function($matches) {
		return str_replace('$1', $Html[$matches[1]]);
	}, $Input);
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
	return preg_replace_callback($patterns, function($matches) {
		return $replaces[$matches[1]];
	}, $text);
}
function bbchat($str) {

	//$str=htmlspecialchars(trim($str));
	$chatMessage = descript($str);
	// paryskinam teksta
	$chatMessage = preg_replace_callback("#\[b\](.*?)\[/b\]#si", function($m) {
		return "<b>" . $m[1] . "</b>";
	}, $chatMessage);
	// Paverciam teksta
	$chatMessage = preg_replace_callback("#\[i\](.*?)\[/i\]#si", function($m) {
		return "<i>" . $m[1] . "</i>";
	}, $chatMessage);
	
	// Pabraukiam teksta
	$str = preg_replace_callback("#\[u\](.*?)\[/u\]#si", function($m) {
		return "<u>" . $m[1] . "</u>";
	}, $str);
	// Mazas sriftas
	$str = preg_replace_callback("#\[sm\](.*?)\[/sm\]#si", function($m) {
		return "<small>" . $m[1] . "</small>";
	}, $str);
	// Specialus simboliai
	$chatMessage = str_replace('&amp;plusmn;', '&plusmn;', $chatMessage);
	$chatMessage = str_replace('&amp;trade;', '&trade;', $chatMessage);
	$chatMessage = str_replace('&amp;bull;', '&bull;', $chatMessage);
	$chatMessage = str_replace('&amp;deg;', '&deg;', $chatMessage);
	$chatMessage = str_replace('&amp;copy;', '&copy;', $chatMessage);
	$chatMessage = str_replace('&amp;reg;', '&reg;', $chatMessage);
	$chatMessage = str_replace('&amp;hellip;', '&hellip;', $chatMessage);
	$chatMessage = str_replace('&amp;#8230;', '&hellip;', $chatMessage);
	// Konvertuojam naujas eilutes i <br/>
	$chatMessage = nl2br($chatMessage);
	//padarom grazias kabutes
	$chatMessage = preg_replace_callback("#\&quot;(.+?)\&quot;#si", function($m) {
		return "<q>" . $m[1] . "</q>";
	}, $chatMessage);

	// js
	$chatMessage = preg_replace_callback("#\<(.*?)javascript(.*?)\>#si", "bbcode_js", $chatMessage);

	return $chatMessage;
}

function bbcode( $str ) {

	global $lang;
	// Problemos su \ slashais
	$str = input( $str );
	$str = preg_replace_callback ( "#\[code\](.*?)\[\/code\]#si", function(){ return base64encode('<textarea name=\"code\" class=\"code\">\\1</textarea>');}, $str );
	$str = preg_replace_callback ( "#\[php\](.*?)\[\/php\]#si", function(){ return base64encode('<textarea name=\"code\" class=\"php\" rows=\"15\" cols=\"100\">\\1</textarea>');}, $str );
	$str = preg_replace_callback ( "#\[mirc\](.*?)\[\/mirc\]#si", function(){ return base64encode('<textarea name=\"code\" class=\"mirc\" rows=\"15\" cols=\"100\">\\1</textarea>');}, $str );
	$str = preg_replace_callback ( "#\[html\](.*?)\[\/html\]#si", function(){ return base64encode('<textarea name=\"code\" class=\"html\" rows=\"15\" cols=\"100\">\\1</textarea>');}, $str );
	$str = preg_replace_callback ( "#\[css\](.*?)\[\/css\]#si", function(){ return base64encode('<textarea name=\"code\" class=\"css\" rows=\"15\" cols=\"100\">\\1</textarea>');}, $str );
	$str = preg_replace_callback ( "#\[js\](.*?)\[\/js\]#si", function(){ return base64encode('<textarea name=\"code\" class=\"js\" rows=\"15\" cols=\"100\">\\1</textarea>');}, $str );
	$str = preg_replace_callback ( "#\[sql\](.*?)\[\/sql\]#si", function(){ return base64encode('<textarea name=\"code\" class=\"sql\" rows=\"15\" cols=\"100\">\\1</textarea>');}, $str );

	// Atverciam linka naujame lange
	$str = preg_replace_callback("#\[url\](.*?)?(.*?)\[/url\]#si", function($m) {
		return '<a target="_blank" href="' . $m[1] . '' . $m[2] . '">' . $m[1] . '' . $m[2] . '</a>';
	}, $str);
	$str = preg_replace_callback("#\[url=(.*?)?(.*?)\](.*?)\[/url\]#si", function($m) {
		return '<a target="_blank" href="' . $m[2] . '">' . $m[3] . '</a>';
	}, $str);

	// Atverciam linka tame paciame lange
	$str = preg_replace_callback("#\[url\](.*?)?(.*?)\[/url\]#si", function($m) {
		return '<a href="' . $m[1] . '' . $m[2] . '">' . $m[1] . '' . $m[2] . '</a>';
	}, $str);
	$str = preg_replace_callback("#\[url=(.*?)?(.*?)\](.*?)\[/url\]#si", function($m) {
		return '<a href="' . $m[2] . '">' . $m[3] . '</a>';
	}, $str);

	// Automatiskai konvertuojam nuorodas
	$str = preg_replace_callback( "#([\n ])([a-z]+?)://([a-z0-9\-\.,\?!%\*_\#:;~\\&$@\/=\+]+)#si", "bbcode_autolink", $str );
	$str = preg_replace_callback("#([\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+)#i", function($m) {
		return $m[1] . '<a href="javascript:mailto:mail(\'' . $m[2] . ', ' . $m[3] . '\');">' . $m[3] . '</a>';
	}, $str);

	// Slepti tekstui - rodomas tik registruotiems vartotojams
	$str = preg_replace_callback("#\[hide=\"?(.*?)\"?\](.*?)\[/hide]#si", function($m) {
		return 'ukryj';
	}, $str);

	// Paryskinam teksta
	$str = preg_replace_callback("#\[b\](.*?)\[/b\]#si", function($m) {
		return '<b>' . $m[1] . '</b>';
	}, $str);

	// Paverciam teksta
	$str = preg_replace_callback("#\[i\](.*?)\[/i\]#si", function($m) {
		return '<i>' . $m[1] . '</i>';
	}, $str);

	// Pabraukiam teksta
	$str = preg_replace_callback("#\[u\](.*?)\[/u\]#si", function($m) {
		return '<u>' . $m[1] . '</u>';
	}, $str);

	// Mazas tekstas
	$str = preg_replace_callback("#\[sm\](.*?)\[/sm\]#si", function($m) {
		return '<small>' . $m[1] . '</small>';
	}, $str);

	// Didelis tekstas
	//todo: dont use <big> tag at all
	$str = preg_replace_callback("#\[big\](.*?)\[/big\]#si", function($m) {
		return '<big>' . $m[1] . '</big>';
	}, $str);

	// Centruojam
	$str = preg_replace_callback("/\[center\](.*?)\[\/center\]/si", function($m) {
		return '<div style="text-align:center;">' . $m[1] . '</div>';
	}, $str);

	// Kaire
	$str = preg_replace_callback("/\[left\](.*?)\[\/left\]/si", function($m) {
		return '<div style="text-align:left;">' . $m[1] . '</div>';
	}, $str);

	// Desine
	$str = preg_replace_callback("/\[right\](.*?)\[\/right\]/si", function($m) {
		return '<div style="text-align:right;">' . $m[1] . '</div>';
	}, $str);

	// Lygiuojam
	$str = preg_replace_callback("/\[justify\](.*?)\[\/justify\]/si", function($m) {
		return '<div style="text-align:justify;">' . $m[1] . '</div>';
	}, $str);

	// Spalvojam teksta
	$str = preg_replace_callback("#\[color=(http://)?(.*?)\](.*?)\[/color\]#si", function($m) {
		return '<span style="color: ' . $m[2] . ';">' . $m[3] . '</span>';
	}, $str);

	// Rodom paveiksliuka
	$str = preg_replace_callback("/\[img\](http:\/\/[^\s'\"<>]+(\.gif|\.jpeg|\.jpg|\.png))\[\/img\]/", function($m) {
		return '<a title=" '. $lang['admin']['preview'] .' " href="' . $m[1] . '">
			<img class="forum_img" src="' . $m[1] . '" alt="pc" onerror="this.src=\'core/assets/images/icons/nopic.png\';this.style.border=\'1px dashed red\';this.style.margin=\'5px\';this.style.padding=\'5px\'">
		</a>';
	}, $str);
	$str = preg_replace_callback( "/\[img\](http:\/\/[^\s'\"<>]+(\.GIF|\.JPEG|\.JPG|\.PNG))\[\/img\]/", function($m) {
		return "<a title=\"{$lang['admin']['preview']}\" href=\"\\1\"><img border=0 class=\"forum_img\" src='" . $m[1] . "' alt=\"pic\" onerror=\"this.src='core/assets/images/icons/nopic.png';this.style.border='1px dashed red';this.style.margin='5px';this.style.padding='5px'\"></a>";
	}, $str);

	// [img=http://www/image.gif]
	$str = preg_replace_callback("/\[img=(http:\/\/[^\s'\"<>]+(\.gif|\.jpg|\.png))\]/", function($m) {
		return '<img src="' . $m[1] . '">';
	}, $str);
	
	$str = preg_replace_callback("/\[img=(http:\/\/[^\s'\"<>]+(\.GIF|\.JPG|\.PNG))\]/", function($m) {
		return '<img src="' . $m[1] . '">';
	}, $str);

	// Cituojam
	if (preg_match( '#\[quote\](.*?)\[/quote]#si', $str) ) {
		$str = preg_replace_callback( '#\[quote=(&quot;|"|\'|)(.*)\\1\]#sU', function($m) {
			return '<div class="cytat"><u><b>'. str_replace('[', '&#91;', $m['2']) .' rašė:</b></u><br/>';
		}, $str);
		$str = str_replace( '[quote]', "<p class=\"cytat\"><u><b>Citata:</b></u><br/>", $str );
		$str = str_replace( '[/quote]', '</p>', $str );
	}

	// Cituojam autoriu
	$str = preg_replace_callback("#\[quote=(http://)?(.*?)\](.*?)\[/quote]#si", function($m) {
		return '<p class="cytat" <u><b>' . $m[2] . 'rašė:</b></u><br/>' . $m[3] . '</p>';
	}, $str);

	// Sarašas
	$str = preg_replace_callback("#\[list\](.*?)\[/list\]#si", function($m) {
		return '<ul>' . $m[1] . '</ul>';
	}, $str);
	$str = preg_replace_callback("#\[list=(http://)?(.*?)\](.*?)\[/list\]#si", function($m) {
		return '<ol type="' . $m[2] . '">' . $m[3] . '</ol>';
	}, $str);
	$str = preg_replace_callback("#\[\*\](.*?)\\s#si", function($m) {
		return '<lo>' . $m[1] . '</li>';
	}, $str);

	// Atvaizduojam emailą
	$str = preg_replace_callback("#\[email\]([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+)\[/email\]#i", function($m) {
		return '<a href="mailto:' . $m[1] . '@' . $m[2] . '>' . $m[1] . '@' . $m[2] . '</a>';
	}, $str);

	// HTML formavimas
	$str = nl2br( $str );
	$str = str_replace( "[br]", "<br/>", $str );
	$str = smile( $str );

	// Iskvieciam kodu atvaizdavima
	$str = preg_replace_callback( "#\<base64\>(.*?)\</base64\>#si", "base64decode", $str );

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
function bbcode_js( $str ) {

	if ( !eregi( '<a href=\"javascript:mailto:mail\(\'', $str[0] ) ) {
		return str_replace( 'javascript', 'java_script', $str[0] );
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
	return ' <a href="' . $str[2] . '://' . $str[3] . '" target="_blank" class="link" rel="nofollow"> ' . $str[2] . '://' . $lnk . ' </a>';
}

// Jei zmogus perkele teksta i kita eilute tai taip ir atvaizduojam
function lauzymas($txt) {
	//return str_replace("\r\n", "<br>", $txt);
}