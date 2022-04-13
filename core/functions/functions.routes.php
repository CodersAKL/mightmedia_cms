<?php

function get($route, $pathToInclude)
{
	if($_SERVER['REQUEST_METHOD'] == 'GET'){
		route($route, $pathToInclude);
	}  
}

function post($route, $pathToInclude)
{
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		route($route, $pathToInclude);
	}    
}

function put($route, $pathToInclude)
{
	if($_SERVER['REQUEST_METHOD'] == 'PUT'){
		route($route, $pathToInclude);
	}    
}

function patch($route, $pathToInclude)
{
	if($_SERVER['REQUEST_METHOD'] == 'PATCH'){
		route($route, $pathToInclude);
	}    
}

function delete($route, $pathToInclude)
{
	if($_SERVER['REQUEST_METHOD'] == 'DELETE'){
		route($route, $pathToInclude);
	}    
}

function any($route, $pathToInclude)
{
	route($route, $pathToInclude);
}

function route($route, $pathToInclude)
{
	// $ROOT = $_SERVER['DOCUMENT_ROOT'];
	$ROOT = $_SERVER['DOCUMENT_ROOT'];

	if($route == "/404"){
		include_once("$ROOT/$pathToInclude");
		exit();
	}

	$requestUrl        = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
	$requestUrl        = rtrim($requestUrl, '/');
	$requestUrl        = strtok($requestUrl, '?');
	$routeParts        = explode('/', $route);
	$requestUrlParts  = explode('/', $requestUrl);

	array_shift($routeParts);
	array_shift($requestUrlParts);

	if( $routeParts[0] == '' && count($requestUrlParts) == 0 ){
		include_once("$ROOT/$pathToInclude");

		exit;
	}

	if(count($routeParts) != count($requestUrlParts)){
		return;
	}
	
	$parameters = [];

	for($i = 0; $i < count($routeParts); $i++){
		$route_part = $routeParts[$i];
	  
		if(preg_match("/^[$]/", $route_part)){
			$route_part = ltrim($route_part, '$');
			array_push($parameters, $requestUrlParts[$i]);
			$$route_part=$requestUrlParts[$i];

		} else if( $routeParts[$i] != $requestUrlParts[$i] ){
			return;
		} 
	}

	include_once("$ROOT/$pathToInclude");

	exit;
}

function out($text)
{
	echo htmlspecialchars($text);
}

// TODO: move to the new seperate file

function setCsrf()
{
	if(! isset($_SESSION["csrf"]) ){
		$_SESSION["csrf"] = bin2hex(random_bytes(50));
	}

	echo '<input type="hidden" name="csrf" value="'.$_SESSION["csrf"].'">';
}

function isCsrfValid()
{
	if(! isset($_SESSION['csrf']) || ! isset($_POST['csrf'])){
		return false;
	}

	if( $_SESSION['csrf'] != $_POST['csrf']){
		return false;
	}

	return true;
}