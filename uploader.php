<?php
session_start();
include_once ("priedai/conf.php");

ini_set('upload_max_filesize', '20M');
$fileSize = ini_get('upload_max_filesize');
$fileSizeb = (int)$fileSize * 1024 * 1024;
$sessionID = session_id();
$sessionName = session_name();


if (isset($_GET[$sessionName])) { $_COOKIE[$sessionName] = $_GET[$sessionName]; }




$page_pavadinimas = "Grotuvo administravimas";


if (isset($_SESSION['username']) && $_SESSION['level'] == 1) {
    if (isset($_GET['music'])) {
        if (isset($_POST['del'])) {
            unlink("siuntiniai/media/" . basename($_POST['del'])) or die("neina :/");
        }

        if (isset($_POST['dir'])) {
            $denny = "toolbar_reklama.php|conf.php|localhost.php|mg2_settings.php|sms_reklama.php|_config-rating.php|.svn|sms_config.php|.htaccess|sql.sql";
            $denny = explode("|", $denny);

            if (file_exists('siuntiniai/media/')) {
                $files = scandir('siuntiniai/media/');
                natcasesort($files);
                if (count($files) > 2) {

                    echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";

                    // All files
                    foreach ($files as $file) {
                        if (file_exists('siuntiniai/media/' . $file) && $file != '.' && $file != '..' &&
                            !is_dir('siuntiniai/media/' . $file) && !in_array($file, $denny)) {
                            $ext = preg_replace('/^.*\./', '', $file);
                            if (strtolower($ext) == 'mp3') {
                            	echo "<li id=\"$file\" class=\"file ext_$ext\">" . htmlentities($file) .
                                "<a style=\"display:inline;\" href=\"#\" onclick=\" $(this).parent('li').remove();$.post('uploader.php?music=true',{ del: '$file'});\" rel=\"" . htmlentities('siuntiniai/media/' . $file) . "\"><img src=\"images/icons/cross.png\"/></a></li>";
                            }
                        }
                    } //$(this).parent('#$file').remove();
                    echo "</ul>";
                }
            }
        }
    }
    elseif (!empty($_FILES)&&strrchr($_FILES['Filedata']['name'],'.')==".mp3") {
		$tempFile = $_FILES['Filedata']['tmp_name'];
		$targetPath = 'siuntiniai/media/';
		//$file = preg_replace('/^[\p{L}0-9]+$/u', '', $_FILES['Filedata']['name']);
		$file = preg_replace("/[^a-z0-9-]/", "-", strtolower(basename($_FILES['Filedata']['name'],'.mp3')));
		$targetFile =  str_replace('//','/',$targetPath) . $file . '.mp3';
		
		move_uploaded_file($tempFile,$targetFile);
		chmod($targetFile,0777);

		exit($file.'.mp3');
	} else {
        echo "<head>" . header_info() . "
<script type=\"text/javascript\" src=\"flashmp3player/jquery.uploadify-v1.6.2/jquery.uploadify (Source).js\"></script>
 <script type=\"text/javascript\" src=\"javascript/jquery/jquery.easing.1.3.js\"></script>
<script type=\"text/javascript\" src=\"javascript/jquery/jqueryFileTree.js\"></script>
<script src=\"http://dev.jquery.com/view/tags/ui/latest/ui/ui.core.js\"></script>
<script src=\"http://dev.jquery.com/view/tags/ui/latest/ui/ui.sortable.js\"></script>
 <link href=\"flashmp3player/jquery.uploadify-v1.6.2/uploadify.css\" rel=\"stylesheet\" type=\"text/css\" media=\"all\">
<style type=\"text/css\">
UL.jqueryFileTree { font-family: Verdana, sans-serif; font-size: 11px; line-height: 18px; padding: 0px;	margin: 0px; }

UL.jqueryFileTree LI { list-style: none; padding: 0px; padding-left: 20px; margin: 0px; white-space: nowrap; }

UL.jqueryFileTree A { color: #333; text-decoration: none;display: block;padding: 0px 2px;}

UL.jqueryFileTree A:hover {	background: #BDF; }
.filename { color:black; }

/* Core Styles */
.jqueryFileTree LI.directory { background: url(images/admin/directory.png) left top no-repeat; }
.jqueryFileTree LI.expanded { background: url(images/admin/folder_open.png) left top no-repeat; }
.jqueryFileTree LI.file { background: url(images/admin/file.png) left top no-repeat; }
.jqueryFileTree LI.wait { background: url(images/admin/spinner.gif) left top no-repeat; }
/* File Extensions*/
.jqueryFileTree LI.ext_mp3 { background: url(images/admin/music.png) left top no-repeat; }
</style>
<script type=\"text/javascript\">
	$(document).ready( function() {
    	//$('#mp').fileTree({ root: '{$_SERVER['PHP_SELF']}',script: '?music=true'});
});

</script>
</head>
<body>";

        lentele("Grojaraštis", "<div class=\"example\"><div id=\"mp\" class=\"demo\"></div></div>");

        echo <<<HTML
<script type="text/javascript">
$(document).ready(function() {
		$('#fileInput').fileUpload ({
		'uploader'  : 'flashmp3player/jquery.uploadify-v1.6.2/uploader.swf',
		'script'    : '{$_SERVER['PHP_SELF']}',
		'cancelImg' : 'flashmp3player/jquery.uploadify-v1.6.2/cancel.png',
		'fileExt'    :'*.mp3;',
		'auto'      : true,
		'sizeLimit'	: '{$fileSizeb}',
		'multi'      : true,
		'folder'    : '/siuntiniai/media',
		'scriptData': {'{$sessionName}': '{$sessionID}'},
		'post_params': {'{$sessionName}': '{$sessionID}'},
		'wmode'	: 'transparent',
		'onAllComplete'	: \$('#mp').fileTree({ root: '{$_SERVER['PHP_SELF']}',script: '?music=true'}),
		'onComplete'	: function (evt, queueID, fileObj, response, data) { $('.jqueryFileTree').append('<li id="'+response+'" class="file ext_mp3">'+response+'<a style="display:inline;" href="#" onclick="\$(this).parent(\'li\').remove();\$.post(\'uploader.php?music=true\',{ del: \''+response+'\'});" rel="'+response+'"><img src="images/icons/cross.png"/></a></li>'); }
	});
});
</script>
HTML;
        lentele("Dainų įkėlimas - maksimalus failo dydis: $fileSize", "<input type=\"file\" name=\"fileInput\" id=\"fileInput\" />");
    }
} else {
	include_once ("priedai/prisijungimas.php");
   echo admin_login_form();
}
?>
</body>
