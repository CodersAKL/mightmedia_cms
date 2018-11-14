<?php

include 'config/head.php';

require 'themes/material/config.php';

require 'config/menu.php';

include 'functions/functions.core.php';
require 'themes/material/functions.php';
require 'config/buttons.php';

require 'themes/material/form.class.php';
require 'themes/material/table.class.php';

//todo: make it safe
if(isset($_GET['a']) && $_GET['a'] === 'ajax') {
	require 'ajax.php';
	exit;
}

require 'themes/material/index.php';
