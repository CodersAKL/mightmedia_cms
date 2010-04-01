<?php
//
// jQuery File Tree PHP Connector
//
// Version 1.01
//
// Cory S.N. LaViska
// A Beautiful Site (http://abeautifulsite.net/)
// 24 March 2008
//
// History:
//
// 1.01 - updated to work with foreign characters in directory/file names (12 April 2008)
// 1.00 - released (24 March 2008)
//
// Output a list of files for jQuery File Tree
//

  session_start();
 include_once('../../../../../../../../priedai/conf.php');
 include_once('../../../../../../../../priedai/prisijungimas.php');
 if(!isset($_SESSION['level']) || $_SESSION['level'] != 1)
  die('eik lauk..');
$_POST['dir'] = urldecode($_POST['dir']);
$root = '../../../../../../../../siuntiniai/';

$safe_dir = strstr($root . $_POST['dir'],'siuntiniai');
if (strstr($safe_dir,'../')) {
	die('Banas uz hack');
}


if( file_exists($root . $_POST['dir'])) {
	$files = scandir($root . $_POST['dir']);
	unset($files['.svn'],$files['.htaccess'],$files['conf.php']);
	natcasesort($files);
	
	if( count($files) > 2 ) { /* The 2 accounts for . and .. */
		echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
		// All dirs
		foreach( $files as $file ) {
			if( file_exists($root . $_POST['dir'] . $file) && is_dir($root . $_POST['dir'] . $file) && !in_array($file,array('.svn','.htaccess','conf.php','.','..'))) {
				echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file) . "/\">" . htmlentities($file) . "</a></li>";
			}
		}
		// All files
		foreach( $files as $file ) {
			if( file_exists($root . $_POST['dir'] . $file) && !is_dir($root . $_POST['dir'] . $file) && !in_array($file,array('.svn','.htaccess','conf.php','.','..')) ) {
				$ext = preg_replace('/^.*\./', '', $file);
				echo "<li class=\"file ext_$ext\"><img src=\"cancel.png\" class=\"del_file\" style=\"cursor:pointer\" align='right' rel='" . htmlentities($_POST['dir'] . $file) . "' onclick=\"if (confirm('Ar tikrai trinti?')) { $.post('scripts/delete.php',{'file':\$(this).attr('rel')});\$(this).parent().remove();}\" /><a href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file) . "\">" . htmlentities($file) . "</a></li>";
			}
		}
		echo "</ul>";	
	}
}

?>