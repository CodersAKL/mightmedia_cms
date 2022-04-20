<?php

// add admin user page
function userRoutes()
{
	get('/' . ADMIN_DIR . '/users', ADMIN_ROOT . 'sys/users/page.php', false);
}

addAction('adminRoutes', 'userRoutes');

function userMenu($data)
{
// check if exists
	$data['users'] = [
		'url' 	=> '/' . ADMIN_DIR . '/users',
		'title' => 'Users',
	];
	
	return $data;
}

addAction('adminMenu', 'userMenu');

// disdplay users list

// edit user

// create user

// if we need
// require_once 'functions.php';