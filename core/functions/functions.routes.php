<?php

function get($route, $pathToInclude, bool $root = true)
{
	if($_SERVER['REQUEST_METHOD'] == 'GET'){
		route($route, $pathToInclude, $root);
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

// todo: add group option
function route($route, $pathToInclude, bool $root = true)
{

	if($root) {
		$root = ROOT;
	} else {
		$root = '';
	}

	$file = $root . $pathToInclude;

	if($route == '/404'){
		include_once includePage($file);
	}
	
	$requestUrl			= filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
	$requestUrl			= rtrim($requestUrl, '/');
	$requestUrl			= strtok($requestUrl, '?');
	$routeParts			= explode('/', $route);
	$requestUrlParts	= explode('/', $requestUrl);

	array_shift($routeParts);
	array_shift($requestUrlParts);

	if( $routeParts[0] == '' && count($requestUrlParts) == 0 ){
		include_once includePage($file);
	}

	if(count($routeParts) != count($requestUrlParts)){
		
		return;
	}
	
	$routeParam = [];

	for($i = 0; $i < count($routeParts); $i++){
		$routePart = $routeParts[$i];
	  
		if(preg_match("/^[$]/", $routePart)) {
			$routePart = ltrim($routePart, '$');
			// set params
			array_push($routeParam, $requestUrlParts[$i]);
			// create var
			${$routePart} = $requestUrlParts[$i];
			// set route data
			$routePart = $requestUrlParts[$i];
			

		} else if($routeParts[$i] != $requestUrlParts[$i]){

			return;
		} 
	}

	include_once includePage($file);
}

function includePage($file)
{
	if(file_exists($file)) {
		return $file;
		// exit;
	} else {
		die('File: <strong>' . $file . '</strong> not found!');
	}
}

function out($text)
{
	echo htmlspecialchars($text);
}