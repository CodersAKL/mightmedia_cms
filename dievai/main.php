<?php
ob_start();
header("Cache-control: public");
header("Content-type: text/html; charset=utf-8");
header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
if (!isset($_SESSION))
	session_start();
define('LEVEL', $_SESSION['level']);
/* detect root */
$out_page = true;
$inc="priedai/conf.php";
$root = '';
while(!file_exists($root.$inc) && strlen($root)<70 ) {
	$root="../".$root;
}

#check if the file actually exists or if we crashed out.
if (!file_exists($root.$inc)) {
	die ("Kritine klaida.". $root.$inc);
}
if (is_file($root.'priedai/conf.php') && filesize($root.'priedai/conf.php') > 1) {

	if (!defined('ROOT')) {
		//_ROOT = $root;
		define('ROOT','../');
	} else {
		define('ROOT', $root);
	}

	include_once ($root.'priedai/conf.php');
	include_once ($root.'priedai/header.php');

	//Stiliaus funkcijos
	require_once("sfunkcijos.php");
	//Inkludinam tai ko mums reikia
	require_once($root.'priedai/funkcijos.php');

} elseif (is_file($root.'setup.php')) {
	header('location: '.$root.'setup.php');
	exit();
} else {
	die(klaida('Sistemos klaida / System error', 'Atsiprašome svetaine neidiegta. Truksta sisteminiu failu. / CMS is not installed.'));
}
//kalbos
$kalbos = getFiles(ROOT.'lang/');
$language = '<ul class="sf-menu" id="lang"><li><a href=""><img src="'.ROOT.'images/icons/flags/'.lang().'.png" alt="'.lang().'"/></a><ul>';
//echo lang();
foreach ($kalbos as $file) {
 	if ($file['type'] == 'file' && basename($file['name'],'.php') != lang()) {
		$language .= '<li><a href="'.url('?id,999;lang,'.basename($file['name'],'.php')).'"><img src="'.ROOT.'images/icons/flags/'.basename($file['name'],'.php').'.png" alt="'.basename($file['name'],'.php').'" class="language flag '.basename($file['name'],'.php').'" /></a></li>';
	}
}
$language .= '</ul></li></ul>';
if (!empty($_GET['lang'])) {
	$_SESSION['lang'] = basename($_GET['lang'],'.php');
	redirect(url("?id," . $_GET['id']));
}
if (!empty($_SESSION['lang']) && is_file(ROOT.'lang/'.basename($_SESSION['lang']).'.php')) {
	require(ROOT.'lang/'.basename($_SESSION['lang'],'.php').'.php');
}
if (empty($_SESSION['username']) || $_SESSION['level']!=1) {
	redirect(ROOT.'index.php');
}
if(isset($_GET['do'])) {
	unset($_SESSION['username'],$_SESSION['level'],$_SESSION['password']);
	redirect(ROOT.'index.php');
}
$glob = glob('*.php');
$admin_tools = "";
foreach($glob as $id => $file) {
	$file = basename($file,'.php');
	$image = (is_file("images/icons/{$file}.png")?"images/icons/{$file}.png":'images/icons/module.png');
	$admin_pages[$id] = $file;
	$admin_pagesid[$file] = $id;
	if ((isset($conf['puslapiai'][$file.'.php']['id']) || in_array($file, array('config','meniu','logai','paneles','vartotojai','komentarai','banai','balsavimas'))) && !in_array($file, array('index','pokalbiai', 'main', 'search', 'antivirus'))) {

		$admin_tools .= "<li ".(isset($_GET['a']) && $_GET['a'] == $id ?'class="active"':'')."><a href=\"".url("?id,999;a,$id")."\"><img src=\"{$image}\" alt=\"\" />".(isset($lang['admin'][$file])?$lang['admin'][$file]:$file)."</a>".(isset($_GET['a']) && $_GET['a'] == $id ? '<ul><div id="veiksmai"></div><script type="text/javascript">
		//sub punktai
$(document).ready(function() {
$(\'.btns a\').each(function(id,obj){
$("div#veiksmai").append(\'<li><a href="\'+obj.href+\'">\'+$(this).text()+\'</a></li>\');

});

});</script></ul>':"")."</li>";

	}
}

//medzio darymo f-ja
function build_tree($data, $id=0, $active_class='active') {
	global $admin_pagesid, $lang;
	if(!empty($data)) {
		$re="";
		foreach ($data[$id] as $row) {
			if (isset($data[$row['id']])) {
				$re.= "<li><a href=\"".url('?id,'.$row['id'])."\" >".$row['pavadinimas']."</a><a href=\"".url('?id,999;a,' . $admin_pagesid['meniu'] . ';d,' . $row['id'] ). "\" style=\"align:right\" onClick=\"return confirm(\'" . $lang['admin']['delete'] . "?\')\"><img src=\"".ROOT."images/icons/cross.png\" title=\"" . $lang['admin']['delete'] . "\" align=\"right\" /></a>
<a href=\"".url('?id,999;a,' . $admin_pagesid['meniu'] . ';r,' . $row['id'] ). "\" style=\"align:right\"><img src=\"".ROOT."images/icons/wrench.png\" title=\"" . $lang['admin']['edit'] . "\" align=\"right\" /></a>
<a href=\"".url('?id,999;a,' . $admin_pagesid['meniu'] . ';e,' . $row['id'] ). "\" style=\"align:right\"><img src=\"".ROOT."images/icons/pencil.png\" title=\"" . $lang['admin']['page_text'] . "\" align=\"right\" /></a><ul>";
				$re.=build_tree($data, $row['id'],$active_class);
				$re.= "</ul></li>";
			} else $re.= "<li><a href=\"".url('?id,'.$row['id'])."\" >".$row['pavadinimas']."</a>
<a href=\"".url('?id,999;a,' . $admin_pagesid['meniu'] . ';d,' . $row['id'] ). "\" style=\"align:right\" onClick=\"return confirm(\'" . $lang['admin']['delete'] . "?\')\"><img src=\"".ROOT."images/icons/cross.png\" title=\"" . $lang['admin']['delete'] . "\" align=\"right\" /></a>
<a href=\"".url('?id,999;a,' . $admin_pagesid['meniu'] . ';r,' . $row['id'] ). "\" style=\"align:right\"><img src=\"".ROOT."images/icons/wrench.png\" title=\"" . $lang['admin']['edit'] . "\" align=\"right\" /></a>
<a href=\"".url('?id,999;a,' . $admin_pagesid['meniu'] . ';e,' . $row['id'] ). "\" style=\"align:right\"><img src=\"".ROOT."images/icons/pencil.png\" title=\"" . $lang['admin']['page_text'] . "\" align=\"right\" /></a>
</li>";
		}
		return $re;
	}
}

function editor($tipas = 'rte', $dydis = 'standartinis', $id = false, $value = '') {
	global $conf, $lang;
	if (!$id) {
		$id = md5(uniqid());
	}

	if (is_array($id)) {
		foreach ($id as $key => $val) {
			$arr[$val] = "'$key'";
		}
		$areos = implode($arr, ",");
	} else {
		$areos = "'$id'";
	}
	$root = ROOT;
	if($conf['Editor'] == 'markitup'){
    $return = <<<HTML
<script type="text/javascript" src="htmlarea/markitup/jquery.markitup.js"></script>
<script type="text/javascript" src="htmlarea/markitup/sets/default/set.js"></script>
<link rel="stylesheet" type="text/css" href="htmlarea/markitup/skins/markitup/style.css" />
<link rel="stylesheet" type="text/css" href="htmlarea/markitup/sets/default/style.css" />

HTML;

    if (is_array($id)) {
      foreach ($id as $key => $val) {
        $return .= <<<HTML
	<script type="text/javascript">
	$(document).ready(function(){
		$('#{$key}').markItUp(mySettings);
	});
	</script>
<textarea id="{$key}" name="{$key}" style="min-height:320px;">{$value[$key]}</textarea>
HTML;
      }
    } else {
      $return .= <<<HTML
	<script type="text/javascript">
	$(document).ready(function()	{
		$('#{$id}').markItUp(mySettings);
	});
	</script>
<textarea id="{$id}" name="{$id}" style="min-height:320px;">{$value}</textarea>
HTML;

    }
	} elseif($conf['Editor'] == 'textarea') {
      $return = '';
     if (is_array($id)) {
      foreach ($id as $key => $val) {
        $return .= <<<HTML
        <button onclick="window.open('htmlarea/markitup/utils/manager/index.php?id={$key}','mywindow','menubar=1,resizable=1,width=820,height=500');
" ><img src="../images/icons/pictures__plus.png" /> {$lang['admin']['insert_image']}</button>
	<textarea id="{$key}" name="{$key}" style="min-height:200px; width: 100%;">{$value[$key]}</textarea>
HTML;
      }
    } else {
      $return .= <<<HTML
      <button onclick="window.open('htmlarea/markitup/utils/manager/index.php?id={$key}','mywindow','menubar=1,resizable=1,width=820,height=500');
" ><img src="../images/icons/pictures__plus.png" /> {$lang['admin']['insert_image']}</button>
<textarea id="{$id}" name="{$id}" style="min-height:200px; width: 100%;">{$value}</textarea>
HTML;

    }
	} elseif($conf['Editor'] == 'tiny_mce') {
      $dir = adresas();
      $return = <<<HTML
      <!-- Load TinyMCE -->
<script type="text/javascript" src="{$dir}htmlarea/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('textarea.tinymce').tinymce({
			// Location of TinyMCE script
			script_url : '{$dir}htmlarea/tiny_mce/tiny_mce.js',

			// General options
			theme : "advanced",
			plugins : "pagebreak,style,layer,table,advimage,advlink,emotions,insertdatetime,preview,media,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,advlist",

			// Theme options
			theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen,pagebreak",
			
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,

			// Example content CSS (should be your site CSS)
			//content_css : "css/content.css",

			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			external_image_list_url : "lists/image_list.js",
			media_external_list_url : "lists/media_list.js"//,
      //remove_script_host : false,

			// Replace values for the template plugin
			
		});
	});
</script>
<!-- /TinyMCE -->
HTML;
	 if (is_array($id)) {
      foreach ($id as $key => $val) {
        $return .= <<<HTML
        <button onclick="window.open('htmlarea/markitup/utils/manager/index.php?id={$key}','mywindow','menubar=1,resizable=1,width=820,height=500');return false;
" ><img src="../images/icons/pictures__plus.png" /> {$lang['admin']['insert_image']}</button>
	<textarea id="{$key}" name="{$key}" style="min-height:200px; width: 100%;" class="tinymce">{$value[$key]}</textarea>
HTML;
      }
    } else {
      $return .= <<<HTML
      <button onclick="window.open('htmlarea/markitup/utils/manager/index.php?id={$id}','mywindow','menubar=1,resizable=1,width=820,height=500');
"><img src="../images/icons/pictures__plus.png" /> {$lang['admin']['insert_image']}</button>
<textarea id="{$id}" name="{$id}" style="min-height:200px; width: 100%;" class="tinymce">{$value}</textarea>
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
		<title><?php echo input(strip_tags($conf['Pavadinimas']) . ' - Admin')?></title>
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<meta name="robots" content="index,follow" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link type='text/css' rel='stylesheet' href='css/default.css' />
    <link rel="stylesheet" type="text/css" href="css/superfish.css" media="screen" />
		<link rel="stylesheet" href="css/jquery.treeview.css" />
		<link rel="stylesheet" href="../stiliai/system.css" />
		<link type="text/css" media="screen" rel="stylesheet" href="../stiliai/colorbox.css" />
    <!--[if IE]>
    <link type="text/css" media="screen" rel="stylesheet" href="../stiliai/colorbox-ie.css" title="example" />
    <![endif]-->

    <script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.7.1.custom.min.js"></script>
    <script type="text/javascript" src="js/superfish.js"></script>
    <script type="text/javascript" src="../javascript/jquery/jquery.colorbox.js"></script>
		<script type="text/javascript" src="js/excanvas.pack.js"></script>
		<script type="text/javascript" src="js/jquery.flot.pack.js"></script>
		<!--script type="text/javascript" src="js/custom.js"></script-->
		<script src="js/jquery.cookie.js" type="text/javascript"></script>
		<script src="js/jquery.treeview.js" type="text/javascript"></script>
		<!--script src="js/jquery.scrollTo.js" type="text/javascript"></script-->
		<script type="text/javascript" src="<?php echo ROOT; ?>javascript/jquery/jquery.tablesorter.js"></script>
		<script type="text/javascript" src="<?php echo ROOT; ?>javascript/jquery/tooltip.js"></script>
		<script type="text/javascript" src="<?php echo ROOT; ?>javascript/pagrindinis.js"></script>
		
		<script type="text/javascript">
			$(document).ready(function(){

				// first example
				$("#treemenu").treeview({
					persist: "location",
					collapsed: true,
					unique: true
				});
				$('ul.sf-menu').superfish();
         //Examples of how to assign the ColorBox event to elements.
        $(".gallery a[rel='lightbox']").colorbox({transition:"fade"});

        //Example of preserving a JavaScript event for inline calls.
        $("#click").click(function(){
        $('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
        });
        $("#inline").colorbox({width:"50%", inline:true, href:"#inline_example1", title:"hello"});
        // find all the input elements with title attributes and make them with a hint
			});
			
		</script>
  </head>
  <body>
	  <div id="admin_root">
		  <div id="admin_main">

			  <div id="admin_header">

				  <!--div id="select_color">
					  <div class="color c1">&nbsp;</div>
					  <div class="color c2">&nbsp;</div>
					  <div class="color c3">&nbsp;</div>
					  <div class="color c4">&nbsp;</div>
				  </div--><div style="text-align: right;color: #666;"><?php echo $lang['admin']['user_lastvisit']; ?>: <b><?php echo date('H:i:s'); ?></b></div>
				  <a href="#" id="admin_logo"><img src="images/mm_logo.png" alt="MightMedia TVS" /></a>

				  <div id="controls">
					  <div id="admin_user" class="down"><a href="<?php echo url('?id,999;do,logout');?>" title="<?php echo $lang['user']['logout']; ?>"><img src="images/icons/logout.png" alt="off" /></a><?php echo $_SESSION['username']; ?></div>
					<div id="admin_lang" class="down"><?php echo $language;?></div>
				  </div>
				  
			  </div>

			  <div id="admin_hmenu">
				  <ul class="sf-menu">
					  <li ><a href="<?php echo adresas().'../';?>"><?php echo $lang['system']['tree']; ?></a>
							<ul>
									<?php
									$data1 = '';
									$res = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `show`='Y' AND `lang`=".escape(lang())." ORDER BY `place` ASC");
									foreach ($res as $row) {
										if(teises($row['teises'],$_SESSION['level'])) {
											$data1[$row['parent']][] = $row;
										}
									}
									echo build_menu($data1);

									?>
							</ul>
						</li>
				  </ul>
			  </div>
		  </div>
      <div id="content">
      <div id="top">
      <div class="search">
            <form method="post" action="<?php echo url('?id,999;m,4');?>">
						<input name="vis" value="vis" type="hidden" />
						<input type="text" name="s"  value="" />
					</form>
          </div>  <div class="msg" id="version_check">
            <img src="images/icons/lightbulb.png" alt="" />...
          </div>
       </div>
<div style="clear: both;"></div>
        <div id="left">          
          <div class="buttons">
            <button onclick="window.location='<?php echo url('?id,999');?>'"><img src="images/icons/home.png" alt="" /></button>
            <button title="<?php echo $lang['admin']['antivirus']; ?>" onclick="window.location='<?php echo url('?id,999;m,3');?>'"><img src="images/icons/product-1.png" alt="" /></button>
            <?php if(!empty($conf['keshas'])):?>
				<button title="<?php echo $lang['admin']['uncache']; ?>" onclick="window.location='<?php echo url('?id,999;m,1');?>'"><img src="images/icons/publish.png" alt="" /></button>
			<?php endif?>
            <button title="<?php echo $lang['admin']['admin_chat']; ?>" onclick="window.location='<?php echo url('?id,999;m,2');?>'"><img src="images/icons/finished-work.png" alt="" /></button>
          </div>
          <div class="nav">
            <ul>
              <?php echo $admin_tools;?>
            </ul>
          </div>
        </div>
                

        <div id="right">
          				<script type="text/javascript">
										//$.getJSON('<?php echo $update_url; ?>');
										/*function versija(data) {
											$('#version_check').html(
											'<img src="images/icons/lightbulb.png" alt="" /><b>'+data.title+'</b> '+
												'' + data.version + ' - ' +
												'' + data.about + ' '+
												(data.log?'<span onclick="$(\'#version_check_more\').toggle(\'fast\')" style="cursor:pointer" class="number">▼</span><br />':'') +
												'<div id="version_check_more" style="display:none"></div>'+
												(data.url?'<span class="number" style="display:inline;"><a href="' + data.url + '" target="_blank">' + data.title + ' v' + data.version + '</a></span>':'')
										);
											if (data.log) {
												$(data.log).each(function(json,info){
													$('#version_check_more').append('<li>'+info+'</li>');
												});
												$('#version_check_more').wrapInner('<ol>');
											}
											if (data.menu.<?php echo lang(); ?>) {
												$(data.menu.<?php echo lang(); ?>).each(function(json,menu){
													$('#admin_hmenu ul').append('<li>'+ (typeof menu == 'object'?'<a href="">'+data.title+'</a>'+arr2html(menu):menu)+'</li>');
												});
											} else {
												$(data.menu.en).each(function(json,menu){
													$('#admin_hmenu ul').append('<li>'+ (typeof menu == 'object'?arr2html(menu):menu)+'</li>');
												});
											}
										}
										function arr2html(arr) {
											var html='';
											if(typeof arr == 'object') {
												html+='<ul>';
												for(var i in arr) {
													html+='<li>';
													html+=typeof arr[i] == 'object'?(i+arr2html(arr[i])):arr[i];
													html+='</li>';
												}
												html+='</ul>';
											}
											return html;
										}*/


									</script>
          <div id="container"> 
            <div class="where"><img src="images/bullet.png" alt="" /> <a href="<?php echo url('?id,999');?>">Admin</a> > <a href="<?php echo url('?id,999'.(isset($_GET['a'])?';a,'.$_GET['a']:''));?>"><?php echo (isset($_GET['a'])?$lang['admin'][$admin_pages[$_GET['a']]]:$lang['admin']['homepage']); ?></a> </div>
            

	<?php if (isset($url['a']) && file_exists(dirname(__file__) . "/" . $admin_pages[(int)$url['a']].'.php') && isset($_SESSION['username']) && $_SESSION['level'] == 1 && defined("OK")) {
		include_once (dirname(__file__) . "/" . $admin_pages[(int)$url['a']].'.php');
	} elseif (isset($_GET['m'])) {
		switch ($_GET['m']){
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
		include_once (dirname(__file__) . "/". $page);
	}
    else
      include_once (dirname(__file__) . "/start.php");
	?>
        </div>
        <div style="clear: both;"></div>
      </div>
      <div id="footer">
         <div class="c">©</div>
         <div class="text">
          <div class="copy">
            <div class="links"><a href="http://mightmedia.lt">MightMedia</a> | <a href="http://mightmedia.lt/Kontaktai"><?php echo $lang['pages']['kontaktas.php'];?></a> | <a href="http://www.gnu.org/licenses/gpl.html">GNU</a></div>MightMedia TVS - tai viena pirmuju Lietuvoje atviro kodo turinio valdymo sistema, sukurta CodeRS komandos.</div>
            <div class="images"><img src="images/mysql.png" alt="" /><img src="images/php.png" alt="" /><img src="images/gnu.png" alt="" /></div>
         </div>
          </div>
      </div>
	  </div>
	   <script type="text/javascript">
         $.getJSON('<?php echo $update_url; ?>');
         function versija(data) {
          

            $('#version_check').html('<img src="images/icons/lightbulb.png" alt="" /><b>'+data.title+'</b> '+'' + data.version + ' - ' +'' + data.about + ' '+(data.log?'<span id="news" title="'+data.log+'">[info]</span>':'')+(data.url?' <span class="number" style="display:inline;"><a href="' + data.url + '" target="_blank">' + data.title + ' v' + data.version + '</a></span>':''));
         
         }
    </script>
  </body>
</html>
