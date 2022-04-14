<?php

function createPassword($string)
{
	return password_hash($string, PASSWORD_DEFAULT);;
}

function checkPassword($string, $hash)
{
	return password_verify($string, $hash); 
}

function createUser($email, $password)
{
	$return = dbInsert(
		'users', 
		[
			'email'		=>	$email,
			'password'	=>	createPassword($password),
		],
		'id'
	);

	return $return;
}

function loginUser($email, $password)
{
	$columns = [
		'email',
		'password',
	];

	$where = [
		'email'	=> $email
	];

	$user = dbSelect('users', $where, $columns);

	var_dump($user); exit;

	if(! checkPassword($password, $user['hash'])) {
		return false;
	}

	$data = [
		'user_id'	=> $user['id'],
		'email' 	=> $user['email'],
	];

	// set user data to session
	setSessions($data);

}

function logoutUser() 
{
	$data = [
		'user_id',
		'email',
	];

	// forgot user data from sessions
	forgotSessions($data);
}
/** 
 * Improves the application's security over HTTP(S) by setting specific headers 
 * 
 * SOURCE IDEA: https://github.com/delight-im/PHP-Auth/blob/master/src/Auth.php
 * */

function enhanceHttpSecurity()
{
	// remove exposure of PHP version (at least where possible)
	\header_remove('X-Powered-By');

	// if the user is signed in
	if (isLoggedIn()) {
		// prevent clickjacking
		\header('X-Frame-Options: sameorigin');
		// prevent content sniffing (MIME sniffing)
		\header('X-Content-Type-Options: nosniff');

		// disable caching of potentially sensitive data
		\header('Cache-Control: no-store, no-cache, must-revalidate', true);
		\header('Expires: Thu, 19 Nov 1981 00:00:00 GMT', true);
		\header('Pragma: no-cache', true);
	}
}

function isLoggedIn()
{

}

function login($array)
{
	$data = [
		'username' 	=> $array['nick'],
		'password' 	=> $array['pass'],
		'id' 		=> (int)$array['id'],
		'lankesi' 	=> $array['login_before'],
		'level' 	=> $array['levelis'],
		'mod' 		=> $array['mod'],
	];

	setSessions($data);
}

function logout()
{
	forgotSessions(
		[
			'username',
			'password',
			'id',
			'level',
			'mod'
		]
	);

	setSessions(
		[
			'level' => 0,
			'mod' 	=> serialize([]),
		]
	);

	setcookie("user", "", time() - 3600, PATH, DOM);
}