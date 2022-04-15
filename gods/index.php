<?php
/**
 * BOOT
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/boot.php';

define('ADMIN_ROOT', __DIR__ . DS);
define('ADMIN_DIR', basename(dirname(__FILE__)));


include_once ROOT . 'core/inc/inc.auth.php';

require 'themes/material/config.php';
require 'themes/material/functions.php';

include 'functions/functions.core.php';

// add admin login check
if (! empty(getSession('user')) && getSession('user')['level'] == 1 ) {
    include 'main.php';
} else {
	// do login
	if(isset($_POST['action']) && $_POST['action'] == 'admin-login') {
		loginUser($_POST['email'], $_POST['password']);
	}

	include 'themes/material/login.php';
}