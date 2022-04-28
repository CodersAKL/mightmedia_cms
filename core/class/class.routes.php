<?php

class Routes {
	public $routes = [];

	public $headerData = [];

	private $routeParts = [];

	private $requestUrlParts = [];

	private $name;

	private $vars = [];

	public $methods = [
		'get',
		'post',
		'put',
		'patch',
		'delete'
	];
	
	public function __construct() {

		

		$route = [
			'gods' => [
				'method'	=> 'get',
				'route'		=> '',
				'include'	=> null,
				'callback'	=> '',
				'root'		=> false,
				'data'		=> null,
				'header'	=> null,
			]
		];

		
	}

	public function newRoute($name, $params)
	{
		// d($name);
		// $this->name = $name;
		$this->setRoutes($name, $params);		
		$this->route($name);
	}

	public function setRoutes($name, $params)
	{
		// foreach ($params as $key => $value) {
		// 	// check if route with same name exists
		// 	if(! empty($this->routes[$key])) {
		// 		throw new Exception('Error: route with name ' . $key . ' already exists!');
		// 	// if not - set the new route
		// 	} else {
		// 		// $this->routes[$key] = $value;
		// 		$this->setRoute($key, $value);
		// 	}
		// }

		$this->setRoute($name, $params);
	}

	private function checkMethod($method)
	{
		return $_SERVER['REQUEST_METHOD'] == strtoupper($method);
	}

	public function routeAjax($route)
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

	public function any($route, $pathToInclude)
	{
		$this->route($route, $pathToInclude);
	}

	private function routeCheck($route)
	{
		$requestUrl			= filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
		$requestUrl			= rtrim($requestUrl, '/');
		$requestUrl			= strtok($requestUrl, '?');
		$routeParts			= explode('/', $route);
		$requestUrlParts	= explode('/', $requestUrl);

		array_shift($routeParts);
		array_shift($requestUrlParts);

		$this->routeParts 		= $routeParts;
		$this->requestUrlParts 	= $requestUrlParts;
	}

	public function loadData()
	{
		// loadRoute
		$data = $this->getRoute($this->name);
		// set header data
		// if(isset($data['header']) && ! empty($data['header'])) {
		// 	$this->headerData[$this->name] = $data['header'];

		// 	// addAction('headerData', [$this, 'setHeaderData']);
		
		// 	foreach ($data['header'] as $kHeaderData => $vHeaderData) {
		// 		// create var
		// 		${$kHeaderData} = $vHeaderData;
		// 	}
		// }

		return isset($data['header']) && ! empty($data['header']) ? $data['header'] : [];
	}

	public function loadPages() 
	{
		$data = $this->getRoute($this->name);

		if(isset($data['data']) && ! empty($data['data'])) {
			// set variables
			$vars = array_merge($data['data'], $this->vars);

			foreach ($vars as $kViewData => $VviewData) {
				// create var
				${$kViewData} = $VviewData;
				
			}
		}

		if(isset($data['include']) && ! empty($data['include'])) {
			// file_exists()
			include_once $this->includePage($data['include']);
		} else {
			// is_callable
			call_user_func($data['callback']);
		}
	}

	private function loadPage($name)
	{
		$this->name = $name;
		addAction('loadPages', [$this, 'loadPages']);
	}

	// todo: add group option
	public function route($name)
	{
				// 'method'	=> 'get',
				// 'route'		=> 'get',
				// 'include'	=> null,
				// 'callback'	=> '',
				// 'root'		=> false,
				// 'data'		=> null,
				// 'header'	=> null,

		$data = $this->getRoute($name);

		if(! $this->checkMethod($data['method'])){
			return;
		}

		//
		

		addFilter('loadRoute', [$this, 'loadData']);
		//

		if($name == '404'){
			$this->loadPage($name);
			// todo: exit or smth
		}
		// check the route
		$this->routeCheck($data['route']);

		if($this->routeParts[0] == '' && count($this->requestUrlParts) == 0){

			$this->loadPage($name);
			
		}

		if(count($this->routeParts) != count($this->requestUrlParts)){
			
			return;
		}
		
		$routeParam = [];

		for($i = 0; $i < count($this->routeParts); $i++){
			$routePart = $this->routeParts[$i];
		
			if(preg_match("/^[$]/", $routePart)) {
				$routePart = ltrim($routePart, '$');
				// set params
				array_push($routeParam, $this->requestUrlParts[$i]);
				// // create var
				// ${$routePart} = $this->requestUrlParts[$i];
				// // set route data
				// $routePart = $this->requestUrlParts[$i];

				$this->vars += [
					'routeParam'	=> $routeParam,
					'routePart'		=> $this->requestUrlParts[$i],
					$routePart		=> $this->requestUrlParts[$i],
				];

				// array_push($this->vars, $routeVars);
				

			} else if($this->routeParts[$i] != $this->requestUrlParts[$i]){

				return;
			} 
		}

		$this->loadPage($name);
	}

	private function includePage($file)
	{
		if(file_exists($file)) {
			return $file;
			// exit;
		} else {
			die('File: <strong>' . $file . '</strong> not found!');
		}
	}

	public function out($text)
	{
		echo htmlspecialchars($text);
	}

	public function getRoute($name)
	{
		return $this->routes[$name];
	}

	public function setHeaderData($headerData)
	{
		$headerData += $this->headerData;

		return $headerData;
	}

	public static function getHeaderData()
	{

		return self::$headerData;
	}

	public function getRouteUrl($name)
	{
		$data = $this->getRoute($name);

		return $data['route'];
	}

	public function setRoute($name, $data)
	{
		if(! in_array($data['method'], $this->methods)) {
			throw new Exception('Error: route method doesn\'t exists: ' . $data['method']);

			return;
		}

		$this->routes[$name] = $data;
	}
}