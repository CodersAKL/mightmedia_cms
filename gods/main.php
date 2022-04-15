<?php

// require 'config/menu.php';

function adminPages()
{

	get('/' . ADMIN_DIR, ADMIN_ROOT . 'pages/dashboard.php', false);
	get('/' . ADMIN_DIR . '/pages', ADMIN_ROOT . 'pages/pages.php', false);
}

// var_dump(function_exists('addAction')); exit;

// addAction('routes', 'adminPages');


// require 'config/buttons.php';

require 'themes/material/form.class.php';
require 'themes/material/table.class.php';

//todo: make it safe
if(isset($_GET['a']) && $_GET['a'] === 'ajax') {
	require 'ajax.php';
	exit;
}

require 'themes/material/index.php';