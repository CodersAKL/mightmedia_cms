<?php

// todo: replace and rename

function adminPages()
{
	get('/' . ADMIN_DIR, ADMIN_ROOT . 'pages/dashboard.php', false);
	doAction('adminRoutes');
}

// var_dump(function_exists('addAction')); exit;

addAction('routes', 'adminPages');

// sys files
$loadSysFilesArray = [
	'users',
];

foreach ($loadSysFilesArray as $loadSysFile) {
    require_once ADMIN_ROOT . 'sys/' . $loadSysFile . '/load.php';
}

// require 'config/buttons.php';

require 'themes/material/form.class.php';
require 'themes/material/table.class.php';

//todo: make it safe
if(isset($_GET['a']) && $_GET['a'] === 'ajax') {
	require 'ajax.php';
	exit;
}

require 'themes/material/index.php';