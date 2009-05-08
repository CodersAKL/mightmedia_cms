<?php

if (!isset($_SESSION))
	session_start();

$rootas='../../../';
include_once($rootas.'priedai/conf.php');

include_once($rootas.'lang/lt.php');

include_once ($rootas."priedai/prisijungimas.php");
if (!isset($_SESSION['username'])) {
    admin_login_form();
}

//Extra login
if (!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER']!=$admin_name || $_SERVER['PHP_AUTH_PW']!=$admin_pass) {
	header("WWW-Authenticate: Basic realm=\"AdminAccess\"");
	header("HTTP/1.0 401 Unauthorized");
	die(klaida("Admin priėjimas uždraustas","Jūs mėginate patekti į tik administratoriams skirtą vietą. Betkokie mėginimai atspėti slaptažodį yra registruojami. <br/>p.s. Nekenčiu bomžų ir tai faktas"));
}

$dir = 'siuntiniai/images';
echo upload_process($dir);

function upload_process($dir) {
global $rootas;
	$file = current($_FILES); // we handle the only file in time
//if ($_FILES["file"]["type"] == "image/gif"|| $_FILES["file"]["type"] == "image/jpeg"|| $_FILES["file"]["type"] == "image/pjpeg"|| $_FILES["file"]["type"] == "image/png"){
	if($file['error'] == UPLOAD_ERR_OK) {
		if(@move_uploaded_file($file['tmp_name'], "".$rootas.$dir."/{$file['name']}"))
			$file['error']	= ''; //no errors, 0 - is our error code for 'moving error'
	}

	$arr = array(
		'error' => $file['error'], 
		'file' => "".$dir."/{$file['name']}",
		'tmpfile' => $file['tmp_name'], 
		'size' => $file['size']
	);

	if(function_exists('json_encode'))
		return json_encode($arr);
	
	$result = array();
	foreach($arr as $key => $val) {
		$val = (is_bool($val)) ? ($val ? 'true' : 'false') : $val;
		$result[] = "'{$key}':'{$val}'";
	}

	return '{' . implode(',', $result) . '}';
//}
}
?>