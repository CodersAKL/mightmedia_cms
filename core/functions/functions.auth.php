<?php

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