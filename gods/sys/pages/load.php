<?php

// add admin pages page
function pagesRoutes()
{

	// $pages = dbSelect(
	// 	'pages'
	// );

	$query = [
		'table' => 'pages'
	];
	
	// pagination
	newRoute(
		'pages.list.pagination', 
		[
			'method'	=> 'get',
			'route'		=> '/' . ADMIN_DIR . '/pages/list/page/$page',
			'include'	=> ADMIN_ROOT . 'sys/pages/list.php',
			'query'		=> $query + [
				'pagination' => true
			],
			// 'data'		=> [
			// 	'pages' => $pages
			// ],
			'header'	=> [
				'pageName' => 'Pages'
			],
		]
	);
	// list
	newRoute(
		'pages.list', 
		[
			'method'	=> 'get',
			'route'		=> '/' . ADMIN_DIR . '/pages/list',
			'include'	=> ADMIN_ROOT . 'sys/pages/list.php',
			'query'		=> $query + [
				'pagination' => true
			],
			// 'data'		=> [
			// 	'pages' => $pages
			// ],
			'header'	=> [
				'pageName' => 'Pages'
			],
		]
	);

	// edit
	newRoute(
		'pages.edit', 
		[
			'method'	=> 'get',
			'route'		=> '/' . ADMIN_DIR . '/pages/edit/$id-$slug',
			'include'	=> ADMIN_ROOT . 'sys/pages/edit.php',
			'query'		=> $query + [
				'where' => [
					'id' 	=> 'param.id',
					// 'slug' 	=> 'param.slug'
				],
				'row' => true
			],
			// 'data'		=> [
			// 	'pages' => $pages
			// ],
			'header'	=> [
				'pageName' => 'Pages'
			],
		]
	);


	newRoute(
		'pages.edit.post', 
		[
			'method'	=> 'post',
			'route'		=> '/' . ADMIN_DIR . '/pages/edit/$id-$slug',
			'callback'	=> 'pageEdit',
			'query'		=> $query + [
				'where' => [
					'id' 	=> 'param.id',
					// 'slug' 	=> 'param.slug'
				],
				'row' => true
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
		'pages.create.post', 
		[
			'method'	=> 'post',
			'route'		=> '/' . ADMIN_DIR . '/pages/create',
			'callback'	=> 'pageCreate',
		]
	);
	
}

function pageEdit($data)
{
	unset($_POST['_token']);

	if(! isset($_POST['active']) || empty($_POST['active'])) {
		$_POST['active'] = 0;
	} else {
		$_POST['active'] = 1;
	}

	$_POST['slug'] = makePostSlug($_POST['title'], 'pages', $_POST['slug'], $data['slug']);

	$_POST['description'] = decodeSafeData($_POST['description']);

	$where = [
		'id' => $data['id']
	];

	if(dbUpdate('pages', $_POST, $where)) {

		return redirect(
			'pages.edit', 
			[
				'type' 		=> 'success',
				'message' 	=> 'success',
			],
			[
				'id' 	=> $data['id'], 
				'slug' 	=> $_POST['slug']
			]
		);
	}

	return redirect(
		'pages.edit', 
		[
			'type' 		=> 'error',
			'message' 	=> 'error',
		],
		[
			'id' 	=> $data['id'], 
			'slug' 	=> $_POST['slug']
		]
	);
	
}


function pageCreate()
{
	global $headerData;

	unset($_POST['_token']);

	if(! isset($_POST['active']) || empty($_POST['active'])) {
		$_POST['active'] = 0;
	} else {
		$_POST['active'] = 1;
	}

	$_POST['slug'] = makePostSlug($_POST['title'], 'pages');

	$_POST['description'] = decodeSafeData($_POST['description']);

	if($page = dbInsert('pages', $_POST)) {
		
		return redirect(
			'pages.create.post', 
			[
				'type' 		=> 'success',
				'message' 	=> 'success',
			]
		);
	}

	return redirect(
		'pages.create.post', 
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