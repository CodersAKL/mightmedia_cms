<?php
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
	        <button onclick="window.open('htmlarea/markitup/utils/manager/index.php?id={$key}','mywindow','menubar=1,resizable=1,width=820,height=500');return false;" >
        <img src="../images/icons/pictures__plus.png" /> {$lang['admin']['insert_image']}</button><br />
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
	        <button onclick="window.open('htmlarea/markitup/utils/manager/index.php?id={$id}','mywindow','menubar=1,resizable=1,width=820,height=500');return false;" >
        <img src="../images/icons/pictures__plus.png" /> {$lang['admin']['insert_image']}</button><br />
<textarea id="{$id}" name="{$id}">{$value}</textarea>
HTML;

		}
	} elseif ( $conf['Editor'] == 'textarea' ) {
		$return = '';
		if ( is_array( $id ) ) {
			foreach ( $id as $key => $val ) {
				$return .= <<<HTML

	<textarea id="{$key}" name="{$key}" >{$value[$key]}</textarea>
HTML;
			}
		} else {
			$return .= <<<HTML

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

function defaultHead() 
{
	?>
	<base href="<?php echo adresas(); ?>" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo input( strip_tags( $conf['Pavadinimas'] ) . ' - Admin' ); ?></title>
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="robots" content="index,follow" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="icon" href="images/favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
	<?php
}

function adminPages() 
{
	global $url, $admin_pages, $lang, $conf;

	if ( isset( $url['a'] ) && file_exists(dirname(__DIR__) . "/" . ( isset( $admin_pages[(int)$url['a']] ) ? $admin_pages[(int)$url['a']] : 'n/a' ) . '.php' ) && isset( $_SESSION[SLAPTAS]['username'] ) && $_SESSION[SLAPTAS]['level'] == 1 && defined( "OK" ) ) {
		if ( count( $_POST ) > 0 && $conf['keshas'] == 1 ) {
			msg( $lang['system']['warning'], $lang['system']['cache_info'] );
		}
		
		include_once("/" . $admin_pages[(int)$url['a']] . '.php' );

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

		include_once("/" . $page);
	} else {
		include_once("/start.php");
	}
}

function getAdminPages($page = null) 
{
	global $admin_pages;

	return ! empty($page) ? $admin_pages[$page] : $admin_pages;
}