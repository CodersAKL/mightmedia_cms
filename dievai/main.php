<?php
ob_start();
header( "Cache-control: public" );
header( "Content-type: text/html; charset=utf-8" );
header( 'P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"' );
if ( !isset( $_SESSION ) ) {
	session_start();
}
define( 'LEVEL', $_SESSION['level'] );
/* detect root */
$out_page = TRUE;
$inc      = "priedai/conf.php";
$root     = '';
while ( !file_exists( $root . $inc ) && strlen( $root ) < 70 ) {
	$root = "../" . $root;
}

#check if the file actually exists or if we crashed out.
if ( !file_exists( $root . $inc ) ) {
	die( "Kritine klaida." . $root . $inc );
}
if ( is_file( $root . 'priedai/conf.php' ) && filesize( $root . 'priedai/conf.php' ) > 1 ) {

	if ( !defined( 'ROOT' ) ) {
		//_ROOT = $root;
		define( 'ROOT', '../' );
	} else {
		define( 'ROOT', $root );
	}

	include_once( $root . 'priedai/conf.php' );
	include_once( $root . 'priedai/header.php' );
	$base   = explode( '/', dirname( $_SERVER['PHP_SELF'] ) );
	$folder = $base[count( $base ) - 1];
	//echo $folder;
	if ( !isset( $conf['Admin_folder'] ) || $conf['Admin_folder'] != $folder ) {
		mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape( $folder ) . ",'Admin_folder')  ON DUPLICATE KEY UPDATE `val`=" . escape( $folder ) );
	}
	//Stiliaus funkcijos
	require_once( "sfunkcijos.php" );
	//Inkludinam tai ko mums reikia
	require_once( $root . 'priedai/funkcijos.php' );

} elseif ( is_file( $root . 'install/index.php' ) ) {
	header( 'location: ' . $root . 'install/index.php' );
	exit();
}
else {
	die( klaida( 'Sistemos klaida / System error', 'Atsiprašome svetaine neidiegta. Truksta sisteminiu failu. / CMS is not installed.' ) );
}
//kalbos
$kalbos   = getFiles( ROOT . 'lang/' );
$language = '<ul class="sf-menu" id="lang"><li><a href=""><img src="' . ROOT . 'images/icons/flags/' . lang() . '.png" alt="' . lang() . '"/></a><ul>';
//echo lang();
foreach ( $kalbos as $file ) {
	if ( $file['type'] == 'file' && basename( $file['name'], '.php' ) != lang() ) {
		$language .= '<li><a href="' . url( '?id,999;lang,' . basename( $file['name'], '.php' ) ) . '"><img src="' . ROOT . 'images/icons/flags/' . basename( $file['name'], '.php' ) . '.png" alt="' . basename( $file['name'], '.php' ) . '" class="language flag ' . basename( $file['name'], '.php' ) . '" /></a></li>';
	}
}
$language .= '</ul></li></ul>';
if ( !empty( $_GET['lang'] ) ) {
	$_SESSION['lang'] = basename( $_GET['lang'], '.php' );
	redirect( url( "?id," . $_GET['id'] ) );
}
if ( !empty( $_SESSION['lang'] ) && is_file( ROOT . 'lang/' . basename( $_SESSION['lang'] ) . '.php' ) ) {
	require( ROOT . 'lang/' . basename( $_SESSION['lang'], '.php' ) . '.php' );
}
if ( empty( $_SESSION['username'] ) || $_SESSION['level'] != 1 ) {
	redirect( ROOT . 'index.php' );
}
if ( isset( $_GET['do'] ) ) {
	unset( $_SESSION['username'], $_SESSION['level'], $_SESSION['password'], $_SESSION['id'] );
	redirect( ROOT . 'index.php' );
}
$glob        = glob( '*.php' );
$admin_tools = "";
foreach ( $glob as $id => $file ) {
	$file                 = basename( $file, '.php' );
	$image                = ( is_file( "images/icons/{$file}.png" ) ? "images/icons/{$file}.png" : 'images/icons/module.png' );
	$admin_pages[$id]     = $file;
	$admin_pagesid[$file] = $id;
	if ( ( isset( $conf['puslapiai'][$file . '.php']['id'] ) || in_array( $file, array( 'config', 'meniu', 'logai', 'paneles', 'vartotojai', 'komentarai', 'banai', 'balsavimas' ) ) ) && !in_array( $file, array( 'index', 'pokalbiai', 'main', 'search', 'antivirus' ) ) ) {

		$admin_tools .= "<li " . ( isset( $_GET['a'] ) && $_GET['a'] == $id ? 'class="active"' : '' ) . "><a href=\"" . url( "?id,999;a,$id" ) . "\"><img src=\"{$image}\" alt=\"\" />" . ( isset( $lang['admin'][$file] ) ? $lang['admin'][$file] : nice_name( $file ) ) . "</a>" . ( isset( $_GET['a'] ) && $_GET['a'] == $id ? '<ul><div id="veiksmai"></div><script type="text/javascript">
//sub punktai
$(document).ready(function() {
$(\'.btns a\').each(function(id,obj){
$("div#veiksmai").append(\'<li><a href="\'+obj.href+\'">\'+$(this).text()+\'</a></li>\');
});
});</script></ul>' : "" ) . "</li>";
	}
}

//medzio darymo f-ja
function build_tree( $data, $id = 0, $active_class = 'active' ) {

	global $admin_pagesid, $lang;
	if ( !empty( $data ) ) {
		$re = "";
		foreach ( $data[$id] as $row ) {
			if ( isset( $data[$row['id']] ) ) {
				$re .= "<li><a href=\"" . url( '?id,' . $row['id'] ) . "\" >" . $row['pavadinimas'] . "</a><span style=\"display: inline; width: 100px;margin:0; padding:0; height: 16px;\"><a href=\"" . url( '?id,999;a,' . $admin_pagesid['meniu'] . ';d,' . $row['id'] ) . "\"  onClick=\"return confirm(\'" . $lang['admin']['delete'] . "?\')\"><img src=\"" . ROOT . "images/icons/cross.png\" title=\"" . $lang['admin']['delete'] . "\"  /></a>
<a href=\"" . url( '?id,999;a,' . $admin_pagesid['meniu'] . ';r,' . $row['id'] ) . "\"><img src=\"" . ROOT . "images/icons/wrench.png\" title=\"" . $lang['admin']['edit'] . "\"/></a>
<a href=\"" . url( '?id,999;a,' . $admin_pagesid['meniu'] . ';e,' . $row['id'] ) . "\"><img src=\"" . ROOT . "images/icons/pencil.png\" title=\"" . $lang['admin']['page_text'] . "\" /></a></span><ul>";
				$re .= build_tree( $data, $row['id'], $active_class );
				$re .= "</ul></li>";
			} else {
				$re .= "<li><a href=\"" . url( '?id,' . $row['id'] ) . "\" >" . $row['pavadinimas'] . "</a><span style=\"display: inline; width: 100px; margin:0; padding:0; height: 16px;\">
<a href=\"" . url( '?id,999;a,' . $admin_pagesid['meniu'] . ';d,' . $row['id'] ) . "\" onClick=\"return confirm(\'" . $lang['admin']['delete'] . "?\')\"><img src=\"" . ROOT . "images/icons/cross.png\" title=\"" . $lang['admin']['delete'] . "\"/></a>
<a href=\"" . url( '?id,999;a,' . $admin_pagesid['meniu'] . ';r,' . $row['id'] ) . "\"><img src=\"" . ROOT . "images/icons/wrench.png\" title=\"" . $lang['admin']['edit'] . "\" /></a>
<a href=\"" . url( '?id,999;a,' . $admin_pagesid['meniu'] . ';e,' . $row['id'] ) . "\" ><img src=\"" . ROOT . "images/icons/pencil.png\" title=\"" . $lang['admin']['page_text'] . "\" /></a></span>
</li>";
			}
		}
		return $re;
	}
}

function editor( $tipas = 'jquery', $dydis = 'standartinis', $id = FALSE, $value = '' ) {

	global $conf, $lang;
	if ( !$id ) {
		$id = md5( uniqid() );
	}

	if ( is_array( $id ) ) {
		foreach ( $id as $key => $val ) {
			$arr[$val] = "'$key'";
		}
		$areos = implode( $arr, "," );
	} else {
		$areos = "'$id'";
	}
	$root = ROOT;
	if ( $conf['Editor'] == 'markitup' ) {
		$dir    = adresas();
		$return = <<<HTML
<script type="text/javascript" src="{$dir}htmlarea/markitup/jquery.markitup.js"></script>
<script type="text/javascript" src="{$dir}htmlarea/markitup/sets/default/set.js"></script>
<link rel="stylesheet" type="text/css" href="{$dir}htmlarea/markitup/skins/markitup/style.css" />
<link rel="stylesheet" type="text/css" href="{$dir}htmlarea/markitup/sets/default/style.css" />

HTML;

		if ( is_array( $id ) ) {
			foreach ( $id as $key => $val ) {
				$return .= <<<HTML
	<script type="text/javascript">
	$(document).ready(function(){
		$('#{$key}').markItUp(mySettings);
	});
	</script>
<textarea id="{$key}" name="{$key}">{$value[$key]}</textarea>
HTML;
			}
		} else {
			$return .= <<<HTML
	<script type="text/javascript">
	$(document).ready(function()	{
		$('#{$id}').markItUp(mySettings);
	});
	</script>
<textarea id="{$id}" name="{$id}">{$value}</textarea>
HTML;

		}
	} elseif ( $conf['Editor'] == 'textarea' ) {
		$return = '';
		if ( is_array( $id ) ) {
			foreach ( $id as $key => $val ) {
				$return .= <<<HTML
        <button onclick="window.open('htmlarea/markitup/utils/manager/index.php?id={$key}','mywindow','menubar=1,resizable=1,width=820,height=500');return false;" >
        <img src="../images/icons/pictures__plus.png" /> {$lang['admin']['insert_image']}</button><br />
	<textarea id="{$key}" name="{$key}" >{$value[$key]}</textarea>
HTML;
			}
		} else {
			$return .= <<<HTML
      <button onclick="window.open('htmlarea/markitup/utils/manager/index.php?id={$id}','mywindow','menubar=1,resizable=1,width=820,height=500'); return false;" >
      <img src="../images/icons/pictures__plus.png" /> {$lang['admin']['insert_image']}</button><br />
<textarea id="{$id}" name="{$id}" >{$value}</textarea>
HTML;

		}
	} elseif ( $conf['Editor'] == 'tiny_mce' ) {
		$dir    = adresas();
		$return = <<<HTML
      <!-- Load TinyMCE -->
<script src="{$dir}htmlarea/tiny_mce/tiny_mce.js" type="text/javascript"></script>
<script type="text/javascript">
		tinyMCE.init({
		// General options
		/*plugins : "paste",*/
		mode : "textareas",
		theme : "advanced",
		skin : "o2k7",
		skin_variant : "silver",
		plugins : "paste,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups,autosave",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "{$dir}htmlarea/tiny_mce/css/content.css",

		template_external_list_url : "{$dir}htmlarea/tiny_mce/template_list.js",
		external_link_list_url : "{$dir}htmlarea/tiny_mce/link_list.js",
		external_image_list_url : "{$dir}htmlarea/tiny_mce/image_list.js",
		media_external_list_url : "{$dir}htmlarea/tiny_mce/media_list.js"


	});

</script>
<!-- /TinyMCE -->
HTML;
		if ( is_array( $id ) ) {
			foreach ( $id as $key => $val ) {
				$return .= <<<HTML
        <button onclick="window.open('htmlarea/markitup/utils/manager/index.php?id={$key}','mywindow','menubar=1,resizable=1,width=820,height=500');return false;
" ><img src="../images/icons/pictures__plus.png" /> {$lang['admin']['insert_image']}</button><br />
	<textarea id="{$key}" name="{$key}" class="tinymce">{$value[$key]}</textarea>
HTML;
			}
		} else {
			$return .= <<<HTML
      <button onclick="window.open('htmlarea/markitup/utils/manager/index.php?id={$id}','mywindow','menubar=1,resizable=1,width=820,height=500');return false;
"><img src="../images/icons/pictures__plus.png" /> {$lang['admin']['insert_image']}</button><br />
<textarea id="{$id}" name="{$id}" class="tinymce">{$value}</textarea>
HTML;

		}
	} elseif ( $conf['Editor'] == 'nicedit' ) {
		$dir = adresas();

		$return = <<<HTML
<script type="text/javascript" src="{$dir}htmlarea/nicedit/nicEdit.js"></script>	
HTML;

		if ( is_array( $id ) ) {
			foreach ( $id as $key => $val ) {

				$return .= <<< HTML
<script type="text/javascript">
bkLib.onDomLoaded(function() {
	new nicEditor({fullPanel : true, iconsPath : '{$dir}htmlarea/nicedit/nicEditorIcons.gif', width: '100%'}).panelInstance('{$key}');
});
</script>
<textarea id="{$key}" name="{$key}">{$value[$key]}</textarea>
HTML;
			}
		} else {
			$return .= <<< HTML
<script type="text/javascript">
bkLib.onDomLoaded(function() {
	new nicEditor({fullPanel : true, iconsPath : '{$dir}htmlarea/nicedit/nicEditorIcons.gif', width: '100%'}).panelInstance('{$id}');
});
</script>

<textarea id="{$id}" name="{$id}">{$value}</textarea>
HTML;

		}

	} elseif ( $conf['Editor'] == 'ckeditor' ) {
		$dir = adresas();

		$return = <<<HTML
<script type="text/javascript" src="{$dir}htmlarea/ckeditor/ckeditor.js"></script>
HTML;

		if ( is_array( $id ) ) {
			foreach ( $id as $key => $val ) {

				$return .= <<< HTML
<textarea id="{$key}" name="{$key}" class="ckeditor">{$value[$key]}</textarea>
HTML;
			}
		} else {
			$return .= <<< HTML
<textarea id="{$id}" name="{$id}" class="ckeditor">{$value}</textarea>
HTML;

		}
	}

	return $return;
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<base href="<?php echo adresas(); ?>" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo input( strip_tags( $conf['Pavadinimas'] ) . ' - Admin' ); ?></title>
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="robots" content="index,follow" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="icon" href="images/favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" href="css/default.css" />
	<?php if ( !empty( $_COOKIE['style'] ) ) {
	$style = $_COOKIE['style'];
} else {
	$style = 'diena';
} ?>
	<link id="stylesheet" type="text/css" href="css/<?php echo $style ?>.css" rel="stylesheet" />
	<link rel="stylesheet" href="css/superfish.css" />
	<link rel="stylesheet" href="css/jquery.treeview.css" />
	<!--[if IE]>
	<link type='text/css' rel='stylesheet' href='css/defaultie.css' media="screen" />
	<![endif]-->
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.7.1.custom.min.js"></script>
	<script type="text/javascript" src="js/superfish.js"></script>
	<script type="text/javascript" src="js/excanvas.pack.js"></script>
	<script type="text/javascript" src="js/jquery.flot.pack.js"></script>
	<script src="js/jquery.cookie.js" type="text/javascript"></script>
	<script src="js/jquery.treeview.js" type="text/javascript"></script>
	<script type="text/javascript" src="<?php echo ROOT; ?>javascript/jquery/jquery.tablesorter.js"></script>
	<script type="text/javascript" src="<?php echo ROOT; ?>javascript/jquery/tooltip.js"></script>
	<script type="text/javascript" src="<?php echo ROOT; ?>javascript/pagrindinis.js"></script>
	<script type="text/javascript">
		$(document).ready(function () {
			// first example
			$("#treemenu").treeview({
				persist:"location",
				collapsed:true,
				unique:true
			});
			$('ul.sf-menu').superfish();
		});
	</script>
</head>
<body>
<div id="admin_root">
	<div id="content">

		<div id="left">

			<div class="fixed">

				<div id="virslogo"></div>
				<a href="<?php echo adresas(); ?>">
					<div id="admin_logo"></div>
				</a>

				<div class="search">
					<div class="sonas">
						<a href="style-switcher.php?style=diena">
							<div class="pirmas"></div>
						</a>
						<a href="style-switcher.php?style=naktis">
							<div class="antras"></div>
						</a>
					</div>
					<form method="post" action="<?php echo url( '?id,999;m,4' );?>">
						<input name="vis" value="vis" type="hidden" />
						<input type="text" name="s" value="" />
					</form>
					<div style="clear: both;"></div>
				</div>

			</div>

			<div class="nav">
				<ul>
					<li>
						<a href="<?php echo url( '?id,999' );?>"><img src="images/icons/home.png" alt="" /> <?php echo $lang['admin']['homepage']; ?>
						</a></li>
					<li>
						<a href="<?php echo url( '?id,999;m,3' );?>"><img src="images/icons/product-1.png" alt="" /> <?php echo $lang['admin']['antivirus']; ?>
						</a></li>
					<li>
						<a href="<?php echo url( '?id,999;m,2' );?>"><img src="images/icons/finished-work.png" alt="" /> <?php echo $lang['admin']['admin_chat']; ?>
						</a></li>
					<?php if ( !empty( $conf['keshas'] ) ) : ?>
					<li>
						<a href="<?php echo url( '?id,999;m,1' );?>"><img src="images/icons/publish.png" alt="" /><?php echo $lang['admin']['uncache']; ?>
						</a></li>
					<?php endif ?>
					<?php echo $admin_tools; ?>
				</ul>
			</div>

		</div>

		<div id="right">

			<div id="controls">
                <div class="admin_user down">
					<?php echo $lang['admin']['user_lastvisit']; ?>: <b><?php echo date( 'H:i:s' ); ?></b>
				</div>
                <div class="admin_user down">
					<a href="<?php echo url( '?id,999;do,logout' );?>" title="<?php echo $lang['user']['logout']; ?>">
						<img src="images/icons/logout.png" alt="off" />
						<?php echo $_SESSION['username']; ?>
					</a>
				</div>
				<div id="admin_lang" class="down"><?php echo $language; ?></div>
			</div>

			<div id="container">
				<div class="where">
					<img src="images/bullet.png" alt="" />
					<a href="<?php echo url( '?id,999' );?>">Admin</a> &raquo;
					<a href="<?php echo url( '?id,999' . ( isset( $_GET['a'] ) ? ';a,' . $_GET['a'] : '' ) );?>">
						<?php echo( isset( $_GET['a'] ) ? ( isset( $admin_pages[$_GET['a']] ) && isset( $lang['admin'][$admin_pages[$_GET['a']]] ) ? $lang['admin'][$admin_pages[$_GET['a']]] : $lang['admin']['homepage'] ) : $lang['admin']['homepage'] ); ?>
					</a>
				</div>
				<!--[if IE]>
				<?php klaida( '', 'Internet Explorer nėra gera naršyklė bei yra nepatogi, ji iškraipo dauguma dizaino funkcijų, siūlome naudoti: <a targer="_blank" href="https://www.google.com/chrome">Google Chrome</a>, <a targer="_blank" href="http://apple.com/safari">Safari</a>, <a targer="_blank" href="http://www.mozilla.org/firefox/">Mozilla Firefox</a>, <a targer="_blank" href="http://opera.com">Opera</a>' );?>
				<![endif]-->

				<div id="version_check"></div>
				<script type="text/javascript">
					$.getJSON('<?php echo $update_url; ?>');
					function versija(data) {
						if ( parseInt('<?php echo versija();?>') < parseInt( data.version ) ){
							$('#version_check').attr('class', 'msg');
							$('#version_check').html('<img src="images/icons/lightbulb.png" alt="" /><strong>' + data.title + '</strong> ' + '' + data.version + ' - ' + '' + data.about + ' ' + (data.log ? '<span id="news" title="' + data.log + '">[info]</span>' : '') + (data.url ? ' <span class="number" style="display:inline;"><a href="' + data.url + '" target="_blank">' + data.title + ' v' + data.version + '</a></span>' : ''));
						}
					}
				</script>

				<?php
				if ( isset( $url['a'] ) && file_exists( dirname( __file__ ) . "/" . ( isset( $admin_pages[(int)$url['a']] ) ? $admin_pages[(int)$url['a']] : 'n/a' ) . '.php' ) && isset( $_SESSION['username'] ) && $_SESSION['level'] == 1 && defined( "OK" ) ) {
					if ( count( $_POST ) > 0 && $conf['keshas'] == 1 ) {
						msg( $lang['system']['warning'], $lang['system']['cache_info'] );
					}
					include_once( dirname( __file__ ) . "/" . $admin_pages[(int)$url['a']] . '.php' );

				} elseif ( isset( $_GET['m'] ) ) {

					switch ( $_GET['m'] ) {
						case 1:
							$page = 'uncache.php';
							break;
						case 2:
							$page = 'pokalbiai.php';
							break;
						case 3:
							$page = 'antivirus.php';
							break;
						case 4:
							$page = 'search.php';
							break;
					}
					include_once( dirname( __file__ ) . "/" . $page );
				} else {
					include_once( dirname( __file__ ) . "/start.php" );
				}
				?>
			</div>
			<div style="clear: both;"></div>
			<div id="footer">

				<div class="copy">
					<div class="c">&copy;</div>
					<div class="links"><a target="_blank" href="http://mightmedia.lt">MightMedia</a> |
						<a target="_blank" href="http://mightmedia.lt/Kontaktai"><?php echo $lang['pages']['kontaktas.php']; ?></a> |
						<a target="_blank" href="http://www.gnu.org/licenses/gpl.html">GNU</a></div>
					MightMedia TVS - atviro kodo turinio valdymo sistema, sukurta CodeRS komandos.
				</div>

				<div class="images">
					<a target="_blank" href="http://www.mysql.com" target="_blank"><img src="images/mysql.png" alt="" /></a>
					<a target="_blank" href="http://php.net" target="_blank"><img src="images/php.png" alt="" /></a>
					<a target="_blank" href="http://www.gnu.org" target="_blank"><img src="images/gnu.png" alt="" /></a>
				</div>
			</div>

			<div style="clear: both;"></div>
		</div>
		<div style="height:20px;"></div>
	</div>
</div>

</body>
</html>