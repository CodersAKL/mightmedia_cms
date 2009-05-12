<?php
session_start();
$page_pavadinimas="Grotuvo administravimas";
include_once("priedai/conf.php");
include_once("priedai/prisijungimas.php");

if(isset($_SESSION['username'])&&$_SESSION['level']==1){
if(isset($_GET['music'])){
      if(isset($_POST['del'])){
    unlink("flashmp3player/mp3/".$_POST['del'])or die("neina :/");
   
    }
      
        if(isset($_POST['dir'])){   
           $denny = "toolbar_reklama.php|conf.php|localhost.php|mg2_settings.php|sms_reklama.php|_config-rating.php|.svn|sms_config.php|.htaccess|sql.sql";
    $denny = explode("|", $denny);

    $root = '.';
    $_POST['dir'] = urldecode($_POST['dir']);

    if (file_exists($root . $_POST['dir'])) {
        $files = scandir($root . $_POST['dir']);
        natcasesort($files);
        if (count($files) > 2) {

            echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";

            // All files
            foreach ($files as $file) {
                if (file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' &&
                    !is_dir($root . $_POST['dir'] . $file) && !in_array($file, $denny)) {
                    $ext = preg_replace('/^.*\./', '', $file);
                    echo "<li id=\"$file\" class=\"file ext_$ext\">" . htmlentities($file) . "<a style=\"display:inline;\" href=\"#\" onclick=\" $(this).parent('li').remove();$.post('uploader.php?music=true',{ del: '$file'});\" rel=\"" . htmlentities($_POST['dir'] .
                        $file) . "\"><img src=\"images/icons/cross.png\"/></a></li>";
                }
            }//$(this).parent('#$file').remove();
            echo "</ul>";
        }
    }}
}else{
echo "<head>".header_info()."
<script type=\"text/javascript\" src=\"flashmp3player/jquery.uploadify-v1.6.2/jquery.uploadify (Source).js\"></script>
 <script type=\"text/javascript\" src=\"javascript/jquery/jquery.easing.1.3.js\"></script>
<script type=\"text/javascript\" src=\"javascript/jquery/jqueryFileTree.js\"></script>
<script src=\"http://dev.jquery.com/view/tags/ui/latest/ui/ui.core.js\"></script>
<script src=\"http://dev.jquery.com/view/tags/ui/latest/ui/ui.sortable.js\"></script>
 <link href=\"flashmp3player/jquery.uploadify-v1.6.2/uploadify.css\" rel=\"stylesheet\" type=\"text/css\" media=\"all\">
<style type=\"text/css\">
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
.jqueryFileTree LI.ext_mp3 { background: url(images/admin/music.png) left top no-repeat; }
-->
</style>
<script type=\"text/javascript\">
	$(document).ready( function() {
    $('#mp').fileTree({ root: '/flashmp3player/mp3/',script: 'uploader.php?music=true'}
    );
});

</script>
</head>
<body>";

lentele("Grojaraštis","<div class=\"example\"><div id=\"mp\" class=\"demo\"></div></div>");

echo <<<HTML
<script type="text/javascript">
$(document).ready(function() {
$('#fileInput').fileUpload ({
'uploader'  : 'flashmp3player/jquery.uploadify-v1.6.2/uploader.swf',
'script'    : 'flashmp3player/jquery.uploadify-v1.6.2/upload.php',
'cancelImg' : 'flashmp3player/jquery.uploadify-v1.6.2/cancel.png',
'fileExt'    :'*.mp3;',
'auto'      : true,
'multi'      : true,
'folder'    : 'flashmp3player/mp3'
});
});
</script>
</body>
HTML;
lentele("Dainų įkėlimas","<input type=\"file\" name=\"fileInput\" id=\"fileInput\" />");
}}else{
echo admin_login_form();
}
?>