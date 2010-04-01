<?php

  session_start();
 include_once('../../../../../../../../priedai/conf.php');
 include_once('../../../../../../../../priedai/prisijungimas.php');
 if(!isset($_SESSION['level']) || $_SESSION['level'] != 1)
  die('eik lauk..');

$file = '../../../../../../../../siuntiniai/'.$_POST['file'];
$path_parts = pathinfo($file);
/*
echo $path_parts['dirname'], "\n";
echo $path_parts['basename'], "\n";
echo $path_parts['extension'], "\n";
echo $path_parts['filename'], "\n"; // since PHP 5.2.0
*/

//Istraukiam sutrumpinta direktorijos kelia
$safe_dir = strstr($path_parts['dirname'],'siuntiniai');
if (strstr($safe_dir,'../')) {
	die('Banas uz hack');
}
$file_name = $path_parts['basename'];

$file_preview = '';
if (in_array($path_parts['extension'],array('jpg','png','gif','jpeg')))
	$file_preview = '<img src="'.$file.'" style="max-width:350px" >';

echo <<<HTML
<center>
<h1>$file_name</h1>
<input type="text" readonly="readonly" value="siuntiniai/{$_POST['file']}" style="width:350px;border:1px solid grey;margin-bottom:5px" onfocus="$(this).select()" /><br />
{$file_preview}<br /><b><a href="javascript:dialog_close('siuntiniai/{$_POST['file']}');">{$lang['admin']['insert']}</a></b>
</center>

HTML;

?>