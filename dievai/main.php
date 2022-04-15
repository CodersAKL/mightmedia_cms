<?php

require 'config/menu.php';
require 'config/buttons.php';

require 'themes/material/form.class.php';
require 'themes/material/table.class.php';

//todo: make it safe
if(isset($_GET['a']) && $_GET['a'] === 'ajax') {
	require 'ajax.php';
	exit;
}

require 'themes/material/index.php';