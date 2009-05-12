<?php
// Uploadify v1.6.2
// Copyright (C) 2009 by Ronnie Garcia
// Co-developed by Travis Nickels
session_start();
$page_pavadinimas="Grotuvo administravimas";
include_once("priedai/conf.php");
include_once("priedai/prisijungimas.php");

if(isset($_SESSION['username'])&&$_SESSION['level']==1){
if (!empty($_FILES)&&$_FILES['Filedata']['tmp_name']=="audio/mp3") {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $_GET['folder'] . '/';
	$targetFile =  str_replace('//','/',$targetPath) . $_FILES['Filedata']['name'];
	
	// Uncomment the following line if you want to make the directory if it doesn't exist
	// mkdir(str_replace('//','/',$targetPath), 0755, true);
	
	move_uploaded_file($tempFile,$targetFile);
echo "1";
}
else echo "0";
}
else echo "0";
?>