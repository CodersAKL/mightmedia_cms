<?php

require_once (config('class', 'dir') . 'class.routes.php');

function get($route, $pathToInclude, bool $root = true, $viewData = [])
{
	if($_SERVER['REQUEST_METHOD'] == 'GET'){
		route($route, $pathToInclude, $root, $viewData);
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

function routeAjax($route)
{
	if($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'PUT'){

		$routeCheck 		= routeCheck($route);
		$routeParts 		= $routeCheck['routeParts'];
		$requestUrlParts	= $routeCheck['requestUrlParts'];

		if(count($routeParts) != count($requestUrlParts)){
			
			return false;
		}
		
		$routeParam = [];

		for($i = 0; $i < count($routeParts); $i++){
			$routePart = $routeParts[$i];
		
			if(preg_match("/^[$]/", $routePart)) {
				$routePart = ltrim($routePart, '$');
				// set params
				array_push($routeParam, $requestUrlParts[$i]);
				// set route data
				$routePart = $requestUrlParts[$i];
				

			} else if($routeParts[$i] != $requestUrlParts[$i]){

				return false;
			} 
		}

		$action = 'ajax' . ucfirst($routePart);

		include ROOT . 'core/inc/inc.ajax.php';

		exit;
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

function routeCheck($route)
{
	$requestUrl			= filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
	$requestUrl			= rtrim($requestUrl, '/');
	$requestUrl			= strtok($requestUrl, '?');
	$routeParts			= explode('/', $route);
	$requestUrlParts	= explode('/', $requestUrl);

	array_shift($routeParts);
	array_shift($requestUrlParts);

	return [
		'routeParts' 		=> $routeParts,
		'requestUrlParts' 	=> $requestUrlParts,
	];

}

function newRoute($name, $args)
{
	$params[$name] = $args;

	$routes = new Routes($params);

	$routes->route($name);
}

function getHeaderData()
{
	return Routes::getHeaderData();
}

// todo: add group option
function route($route, $pathToInclude, bool $root = true, $viewData = [])
{
	$nArr = explode('/', $route);
	$name = end($nArr);

	$params[$name] =[
		'method'	=> 'get',
		'route'		=> $route,
		'include'	=> $pathToInclude,
		'callback'	=> null,
		'root'		=> $root,
		'data'		=> $viewData,
		'header'	=> null,
	];

	$routes = new Routes($params);

	$routes->route($name);

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

function getRoute($type, $action)
{
	return $type . '/' . $action;
}