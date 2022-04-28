<?php

// add admin pages page
function pagesRoutes()
{
	// get('/' . ADMIN_DIR . '/pages/$page', ADMIN_ROOT . 'sys/pages/page.php', false);
	$args = [
		'method'	=> 'get',
		'route'		=> '/' . ADMIN_DIR . '/pages/$page',
		'include'	=> ADMIN_ROOT . 'sys/pages/page.php',
		// 'callback'	=> 'page',
		// 'root'		=> false,
		'data'		=> [
			'test' => 'Test'
		],
		'header'	=> [
			'pageName' => 'Pages'
		],
	];

	newRoute('pages', $args);
	// TODO: post
}

// function page()
// {
// 	global $headerData;

// 	d($headerData);
// }

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