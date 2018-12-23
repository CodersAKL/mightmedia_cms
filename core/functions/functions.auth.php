<?php

function login($array)
{
	$_SESSION[SLAPTAS]['username'] = $array['nick'];
	$_SESSION[SLAPTAS]['password'] = $array['pass'];
	$_SESSION[SLAPTAS]['id']       = (int)$array['id'];
	$_SESSION[SLAPTAS]['lankesi']  = $array['login_before'];
	$_SESSION[SLAPTAS]['level']    = $array['levelis'];
	$_SESSION[SLAPTAS]['mod']      = $array['mod'];
}

function logout()
{
	unset( $_SESSION[SLAPTAS]['username'], $_SESSION[SLAPTAS]['password'], $_SESSION[SLAPTAS]['id'], $_SESSION[SLAPTAS]['level'], $_SESSION[SLAPTAS]['mod'] ); // Isvalom sesija
	$_SESSION[SLAPTAS]['level'] = 0;
	$_SESSION[SLAPTAS]['mod']   = serialize( array() );
	setcookie( "user", "", time() - 3600, PATH, DOM ); // Sunaikinam sesija
}