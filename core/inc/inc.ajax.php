<?php
$data = [];

if(! empty($_FILES)) {
	array_push($data, $_FILES);
}

if(! empty($_POST)) {
	array_push($data, $_POST);
}

// TODO: fix this hardcode
if(isset($data[0])) {
	$data = $data[0];
}

if(function_exists($routePart)) {

	$func = doAction($action, $data);

	if(is_string($func)) {
		echo $func;
	} else {
		echo json_encode($func, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	}
	
} else {
	die('0');
}