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

	private function checkSymbol($routeParts)
	{
		foreach($routeParts as $k => $v) {
			if(preg_match("/-/", $v)) {
				$moreRouteParts = explode('-', $v);

				// $routeParts += $moreRouteParts;
				foreach($moreRouteParts as $kM => $vM) {
					array_push($routeParts, $vM);
				}
				
				unset($routeParts[$k]);
			}
		}

		array_shift($routeParts);

		return $routeParts;
	}

	public function loadData()
	{
		// loadRoute

		$data = $this->getRoute($this->name);

		return isset($data['header']) && ! empty($data['header']) ? $data['header'] : [];
	}

	public function loadPages() 
	{
		
		$data = $this->getRoute($this->name);

		if(isset($data['query']) && ! empty($data['query'])) {
			

			if(isset($data['query']['where'])) {

				foreach ($data['query']['where'] as $kWhere => $vWhere) {
					if(preg_match("/^param/", $vWhere)) {
						$where = ltrim($vWhere, '.param');
						$data['query']['where'][$kWhere] = $this->vars[$where];
					}
				}

			}
		
			if(isset($data['query']['pagination']) && $data['query']['pagination']) {
				// load data from DB
				$page = isset($this->vars['page']) ? (int)$this->vars['page'] : 1;

				// TODO: add to config default pagination
				$limit = isset($data['query']['limit']) ? $data['query']['limit'] : 2;

				$offset = $limit * ($page - 1);

				$totalRows = dbCount(
					$data['query']['table'],
					'id'
				);
		
				$totalPages = ceil($totalRows / $limit);
			} else {
				$limit = isset($data['query']['row']) && $data['query']['row'] ? 1 : 0;
				$offset = null;
			}

			$funcName = isset($data['query']['row']) && $data['query']['row'] ? 'dbRow' : 'dbSelect';
		
			$query = $funcName(
				$data['query']['table'],
				isset($data['query']['where']) ? $data['query']['where'] : null,
				isset($data['query']['columns']) ? $data['query']['columns'] : null,
				$limit,
				$offset
			);

			if(isset($data['query']['returnVar']) && ! empty($data['query']['returnVar'])) {
				${$data['query']['returnVar']} = $query;
			} else {
				${$data['query']['table']} = $query;
			}
			// foreach ($vars as $kViewData => $VviewData) {
			// 	// create var
			// 	${$kViewData} = $VviewData;
				
			// }
		}

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


	private function loadPageData($name)
	{
		// $this->name = $name;
		addFilter('loadRoute', [$this, 'loadData']);
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
// $this->name = $name;
		$data = $this->getRoute($name);

		if(! $this->checkMethod($data['method'])){
			return;
		}

		//
		$this->loadPageData($name);
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
		
			if(preg_match("/^[$]/", $this->routeParts[$i])) {
				// set params
				$routePart = ltrim($this->routeParts[$i], '$');

				if(preg_match("/-/", $this->routeParts[$i])) {
					$moreRouteParts = explode('-', $this->routeParts[$i]);
					$moreRequestParts = explode('-', $this->requestUrlParts[$i], count($moreRouteParts));

					for ($iM=0; $iM < count($moreRouteParts); $iM++) { 
						$vM = $moreRequestParts[$iM];
						array_push($routeParam, $vM);

						$this->routeParts[] = $vM;
						$this->requestUrlParts[] = $vM;

						$key = ltrim($moreRouteParts[$iM], '$');

						$this->vars[$key] = $vM;
					}

				} else {
					array_push($routeParam, $this->requestUrlParts[$i]);
					
					$this->vars += [
						$routePart		=> $this->requestUrlParts[$i],
					];

				}

				

				$this->vars += [
					'routeParam'	=> $routeParam,
					'routePart'		=> $this->requestUrlParts[$i],
				];

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

	public function getRoute($name, $params = [])
	{
		
		$route = $this->routes[$name];

		if(! empty($params)) {
			$routeParts	= explode('/', $route['route']);
			$routeParts	= $this->checkSymbol($routeParts);

			for($i = 0; $i < count($routeParts); $i++){
				$routePart = $routeParts[$i];
			
				if(preg_match("/^[$]/", $routePart)) {
					$routePart = ltrim($routePart, '$');

					if(isset($params[$routePart])) {
						$route['route'] = str_replace('$' . $routePart, $params[$routePart], $route['route']);
					}
				}
			}
		}

		return $route;
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

	public function getRouteUrl($name, $params = [])
	{
		$data = $this->getRoute($name, $params);

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