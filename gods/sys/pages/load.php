<?php

// add admin pages page
function pagesRoutes()
{

	$pages = dbSelect(
		'pages'
	);

	newRoute(
		'pages.list', 
		[
			'method'	=> 'get',
			'route'		=> '/' . ADMIN_DIR . '/pages/list',
			'include'	=> ADMIN_ROOT . 'sys/pages/list.php',
			'data'		=> [
				'pages' => $pages
			],
			'header'	=> [
				'pageName' => 'Pages'
			],
		]
	);

	newRoute(
		'pages.create', 
		[
			'method'	=> 'get',
			'route'		=> '/' . ADMIN_DIR . '/pages/create',
			'include'	=> ADMIN_ROOT . 'sys/pages/create.php',
			'data'		=> [
				'page' => 'create'
			],
			'header'	=> [
				'pageName' => 'Pages'
			],
		]
	);

	newRoute(
		'pages.createPost', 
		[
			'method'	=> 'post',
			'route'		=> '/' . ADMIN_DIR . '/pages/create',
			'callback'	=> 'pageCreate',
		]
	);
	
}

function pageCreate()
{
	global $headerData;

	unset($_POST['_token']);

	if(! isset($_POST['active']) || empty($_POST['active'])) {
		$_POST['active'] = 0;
	}

	$_POST['slug'] = slug($_POST['title']);

	$_POST['description'] = decodeSafeData($_POST['description']);

	if($page = dbInsert('pages', $_POST)) {
		
		return redirect(
			'pages.createPost', 
			[
				'type' 		=> 'success',
				'message' 	=> 'success',
			]
		);
	}

	return redirect(
		'pages.createPost', 
		[
			'type' 		=> 'error',
			'message' 	=> 'error',
		]
	);
	
}

addAction('adminRoutes', 'pagesRoutes');
// addAction('boot', 'pagesRoutes');

function pagesMenu($data)
{
	$route = '/' . ADMIN_DIR . '/pages';
// check if exists
	$data['pages'] = [
		'url' 	=> $route,
		'title' => 'Pages',
		'icon' 	=> 'description',
			'sub'	=> [
				[
					'url' 	=> $route . '/list',
					'title' => 'Pages list',
				],
				[
					'url' 	=> $route . '/create',
					'title' => 'Create new',
				]
			]
	];
	
	return $data;
}

addAction('adminMenu', 'pagesMenu', 1);

// disdplay users list

// edit user

// create user

// if we need
// require_once 'functions.php';