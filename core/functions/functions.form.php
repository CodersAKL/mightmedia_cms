<?php

function checkCSRF($token)
{
	// check if token is already seted  
	if (empty($token) || ! getSession('token') || ! getSession('token_expire')) {

		return [
			'error'		=> true,
			'message'	=> 'Token is not set!',
		];
	}
   
  // compare token with session token
//   todo: use hash_equals func to compare hash
//   if(hash_equals(crypt('$_SESSION['token'],'$2a$07$usesomesillystringforsalt$'), crypt($_POST['token'],'$2a$07$usesomesillystringforsalt$')) {
  if (getSession('token') == $token) {
	// token expired
	if (time() >= getSession('token_expire')) {

	  return [
			'error'		=> true,
			'message'	=> 'Token expired. Please reload form.',
		];
	// all good - forget sessions and continue
	} else {
		forgotSessions(
			[
				'token',
				'token_expire'
			]
		);
	  
		return [
			'error'		=> false,
			'message'	=> 'Token is good!',
		];
	}
  }

	// bad token
	return [
		'error'		=> true,
		'message'	=> 'Token is bad!',
	];
}

function checkCSRFreal($token)
{
	if(DEBUG) {
		return;
	}
	
	$check = checkCSRF($token);

	if($check['error']) {
		die($check['message']);
	}
}

function CSRFinput()
{
	return '<input type="hidden" name="_token" value="' . getSession('token') . '">';
}