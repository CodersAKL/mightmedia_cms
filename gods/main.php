<?php

// sys files
$loadSysFilesArray = [
	'custom', // ?
	'users',
	'media',
];

foreach ($loadSysFilesArray as $loadSysFile) {
    require_once ADMIN_ROOT . 'sys/' . $loadSysFile . '/load.php';
}

routeAjax('/' . ADMIN_DIR . '/ajax/$action');

require 'themes/material/form.class.php'; // todo: remove?
require 'themes/material/table.class.php'; // todo: remove?

//todo: make it safe
if(isset($_GET['a']) && $_GET['a'] === 'ajax') {
	require 'ajax.php';
	exit;
}

require 'themes/material/index.php';