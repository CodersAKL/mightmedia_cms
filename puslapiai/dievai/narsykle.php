<style type="text/css">
<!--
UL.jqueryFileTree { font-family: Verdana, sans-serif; font-size: 11px; line-height: 18px; padding: 0px;	margin: 0px; }

UL.jqueryFileTree LI { list-style: none; padding: 0px; padding-left: 20px; margin: 0px; white-space: nowrap; }

UL.jqueryFileTree A { color: #333; text-decoration: none;display: block;padding: 0px 2px;}

UL.jqueryFileTree A:hover {	background: #BDF; }

/* Core Styles */
.jqueryFileTree LI.directory { background: url(images/admin/directory.png) left top no-repeat; }
.jqueryFileTree LI.expanded { background: url(images/admin/folder_open.png) left top no-repeat; }
.jqueryFileTree LI.file { background: url(images/admin/file.png) left top no-repeat; }
.jqueryFileTree LI.wait { background: url(images/admin/spinner.gif) left top no-repeat; }
/* File Extensions*/
.jqueryFileTree LI.ext_3gp { background: url(images/admin/film.png) left top no-repeat; }
.jqueryFileTree LI.ext_afp { background: url(images/admin/code.png) left top no-repeat; }
.jqueryFileTree LI.ext_afpa { background: url(images/admin/code.png) left top no-repeat; }
.jqueryFileTree LI.ext_asp { background: url(images/admin/code.png) left top no-repeat; }
.jqueryFileTree LI.ext_aspx { background: url(images/admin/code.png) left top no-repeat; }
.jqueryFileTree LI.ext_avi { background: url(images/admin/film.png) left top no-repeat; }
.jqueryFileTree LI.ext_bat { background: url(images/admin/application.png) left top no-repeat; }
.jqueryFileTree LI.ext_bmp { background: url(images/admin/picture.png) left top no-repeat; }
.jqueryFileTree LI.ext_c { background: url(images/admin/code.png) left top no-repeat; }
.jqueryFileTree LI.ext_cfm { background: url(images/admin/code.png) left top no-repeat; }
.jqueryFileTree LI.ext_cgi { background: url(images/admin/code.png) left top no-repeat; }
.jqueryFileTree LI.ext_com { background: url(images/admin/application.png) left top no-repeat; }
.jqueryFileTree LI.ext_cpp { background: url(images/admin/code.png) left top no-repeat; }
.jqueryFileTree LI.ext_css { background: url(images/admin/css.png) left top no-repeat; }
.jqueryFileTree LI.ext_doc { background: url(images/admin/doc.png) left top no-repeat; }
.jqueryFileTree LI.ext_exe { background: url(images/admin/application.png) left top no-repeat; }
.jqueryFileTree LI.ext_gif { background: url(images/admin/picture.png) left top no-repeat; }
.jqueryFileTree LI.ext_fla { background: url(images/admin/flash.png) left top no-repeat; }
.jqueryFileTree LI.ext_h { background: url(images/admin/code.png) left top no-repeat; }
.jqueryFileTree LI.ext_htm { background: url(images/admin/html.png) left top no-repeat; }
.jqueryFileTree LI.ext_html { background: url(images/admin/html.png) left top no-repeat; }
.jqueryFileTree LI.ext_jar { background: url(images/admin/java.png) left top no-repeat; }
.jqueryFileTree LI.ext_jpg { background: url(images/admin/picture.png) left top no-repeat; }
.jqueryFileTree LI.ext_jpeg { background: url(images/admin/picture.png) left top no-repeat; }
.jqueryFileTree LI.ext_js { background: url(images/admin/script.png) left top no-repeat; }
.jqueryFileTree LI.ext_lasso { background: url(images/admin/code.png) left top no-repeat; }
.jqueryFileTree LI.ext_log { background: url(images/admin/txt.png) left top no-repeat; }
.jqueryFileTree LI.ext_m4p { background: url(images/admin/music.png) left top no-repeat; }
.jqueryFileTree LI.ext_mov { background: url(images/admin/film.png) left top no-repeat; }
.jqueryFileTree LI.ext_mp3 { background: url(images/admin/music.png) left top no-repeat; }
.jqueryFileTree LI.ext_mp4 { background: url(images/admin/film.png) left top no-repeat; }
.jqueryFileTree LI.ext_mpg { background: url(images/admin/film.png) left top no-repeat; }
.jqueryFileTree LI.ext_mpeg { background: url(images/admin/film.png) left top no-repeat; }
.jqueryFileTree LI.ext_ogg { background: url(images/admin/music.png) left top no-repeat; }
.jqueryFileTree LI.ext_pcx { background: url(images/admin/picture.png) left top no-repeat; }
.jqueryFileTree LI.ext_pdf { background: url(images/admin/pdf.png) left top no-repeat; }
.jqueryFileTree LI.ext_php { background: url(images/admin/php.png) left top no-repeat; }
.jqueryFileTree LI.ext_png { background: url(images/admin/picture.png) left top no-repeat; }
.jqueryFileTree LI.ext_ppt { background: url(images/admin/ppt.png) left top no-repeat; }
.jqueryFileTree LI.ext_psd { background: url(images/admin/psd.png) left top no-repeat; }
.jqueryFileTree LI.ext_pl { background: url(images/admin/script.png) left top no-repeat; }
.jqueryFileTree LI.ext_py { background: url(images/admin/script.png) left top no-repeat; }
.jqueryFileTree LI.ext_rb { background: url(images/admin/ruby.png) left top no-repeat; }
.jqueryFileTree LI.ext_rbx { background: url(images/admin/ruby.png) left top no-repeat; }
.jqueryFileTree LI.ext_rhtml { background: url(images/admin/ruby.png) left top no-repeat; }
.jqueryFileTree LI.ext_rpm { background: url(images/admin/linux.png) left top no-repeat; }
.jqueryFileTree LI.ext_ruby { background: url(images/admin/ruby.png) left top no-repeat; }
.jqueryFileTree LI.ext_sql { background: url(images/admin/db.png) left top no-repeat; }
.jqueryFileTree LI.ext_swf { background: url(images/admin/flash.png) left top no-repeat; }
.jqueryFileTree LI.ext_tif { background: url(images/admin/picture.png) left top no-repeat; }
.jqueryFileTree LI.ext_tiff { background: url(images/admin/picture.png) left top no-repeat; }
.jqueryFileTree LI.ext_txt { background: url(images/admin/txt.png) left top no-repeat; }
.jqueryFileTree LI.ext_vb { background: url(images/admin/code.png) left top no-repeat; }
.jqueryFileTree LI.ext_wav { background: url(images/admin/music.png) left top no-repeat; }
.jqueryFileTree LI.ext_wmv { background: url(images/admin/film.png) left top no-repeat; }
.jqueryFileTree LI.ext_xls { background: url(images/admin/xls.png) left top no-repeat; }
.jqueryFileTree LI.ext_xml { background: url(images/admin/code.png) left top no-repeat; }
.jqueryFileTree LI.ext_zip { background: url(images/admin/zip.png) left top no-repeat; }
-->
</style>
<script type="text/javascript" src="javascript/jquery/jquery-latest.pack.js"></script>
<script type="text/javascript" src="javascript/jquery/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="javascript/jquery/jqueryFileTree.js"></script>
<script src="http://dev.jquery.com/view/tags/ui/latest/ui/ui.core.js"></script>
<script src="http://dev.jquery.com/view/tags/ui/latest/ui/ui.sortable.js"></script>

<script type="text/javascript">
	$(document).ready( function() {		
		$('#fileTree').fileTree({ root: '/', script: 'tikrink.php', folderEvent: 'click', expandSpeed: 1, collapseSpeed: 1 }, function(file) { 
			alert(file);
		});
		//$('.directory').sortable({});
	});
</script>

<div class="example">
	<h2><?php echo $lang['admin']['files'];?></h2>
	<div id="fileTree" class="demo"></div>
</div>

<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author$
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision$
 * @$Date$
 **/
/*
if (!defined("LEVEL") || LEVEL < 30 || !defined("OK")) { header('location: ?home'); }

// biski kintamuju

    $host = "?id,".$url['id'].";a,8"; // pagrindinis
    $denny = "toolbar_reklama.php|conf.php|localhost.php|mg2_settings.php|sms_reklama.php|_config-rating.php|.svn|sms_config.php";
	 $denny = explode("|", $denny);
    // knopkes
    $img_back="images/admin/back.gif";
    $img_folder="images/admin/folder.gif";
    $img_file="images/admin/file.gif";
    $img_home="images/admin/home.gif";
    $img_surce="images/icons/page_white_code_red.png";
	if (isset($url['d'])) { $d = urldecode($url['d']); } else { $d = './'; }


if (isset($url['v'])) {
	if (!in_array($url['v'], $denny) && is_file($d.$url['v'])) {
		$h = "<div style='width:100%;overflow:auto; height:300px;'><table bgcolor='#EEEEEE' width='100%'><tr><td width=30 valign='top'><code>";
		for ($i = 1; $i <= count(file($d.$url['v'])); $i++) $h .= $i.".<br>";
		$h .= "</code></td><td>";
		$h .= highlight_file($d.$url['v'], true);
		$h .= "</td></tr></table></div>";
		hide($url['v']." - failo turinys",$h);
		unset($h);
	}
	else { klaida("Draudziama!","Konfidenciali informacija!"); }
}

function s($arr, $str) {
   if (is_array($arr)) {
       $ilgis = strlen($str); $return = '';
       foreach ($arr as $key => $val) {
           $tmp = substr($val, 0, $ilgis);
           if ($str == $tmp) {
               $return .= $val."\n";
           }
       }
       return $return;
   }
   return false;
}

// Navigacija
$startdir = "./";
if(isset($d)) {
    $prev = $d;
    $folder = $d;   
} 
else { $folder = $startdir; $prev='';}
// END navigacija

$files = getFiles($folder);
$return = '';
foreach ($files as $file) {
    //if(strip_ext($file['name'])!='htaccess') {
    if($file['name']!='.htaccess' && $file['name'] != '.override') {	//Jeigu tai nera htaccess failas
        $image = $img_file;
        $extra = "<a href=\"".url('v,'.$file['name'].'')."\"><img src=\"$img_surce\" border=\"0\"/></a>";
        if($file['type']=='dir') {	//jeigu direktorija
            $image = $img_folder;
            $extra = "";
            $cmd='?id,'.$url['id'].';a,8;d,'.urldecode($prev).$file['name'].'/';
        }
        else $cmd=$prev.$file['name'];
        //$return .= "$extra <a href=\"$cmd\" title=\"".$file['type'].", ".$file['sizetext']."\"><img src=\"$image\" border=\"0\" /> ".$file['name']."</a> <br/>";
        //print_r($file);
        if ($file['type'] == 'file') { $return .= "$extra <a href=\"$cmd\" title=\"<b>".$file['type'].":".$file['name']."</b<br/><img src='".$cmd."'><br/>Dydis: ".$file['sizetext']."\"><img src=\"$image\" border=\"0\" /> ".$file['name']."</a> <br/>"; }
        else { $return .= "$extra <a href=\"$cmd\" title=\"".$file['type'].", ".$file['sizetext']."\"><img src=\"$image\" border=\"0\" /> ".$file['name']."</a> <br/>"; }
    }
}
if (isset($d)) {
	$folder = explode("/",urldecode($d));
	$link = ''; $dir = '';
	foreach ($folder as $fname) {
		$dir .= $fname."/";
		$link .= " <a href='?id,".$url['id'].";a,8;d,".$dir."'>$fname</a>";
	}
}
lentele("Failų naršyklė: <a href=\"?id,".$url['id'].";a,8\">Failai</a>: ".((!empty($link))?$link:'')."", $return);
*/
?>