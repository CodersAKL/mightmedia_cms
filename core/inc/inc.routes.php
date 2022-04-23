<?php

//
// $pages = [
// 	'name'  	=>	'pages',
// 	'fields'    =>	[
// 		'title',
// 		'editor',
// 		'media'     => [
// 			'attachments'   => true,
// 			'photo'         => true,
// 			'gallery'       => true,
// 		],
// 	],
// ];







// ///
// $pagesQuery = [
// 	'name'	=>	'pages',
// 	'columns'	=>	[
// 		'id'	=>	[
// 			'type'		=>	'bigint(20)',
// 			'null'		=>	false,
// 			'default'	=>	null,
// 			'increment'	=>	true,
// 			'collation'	=>	'utf8mb4_general_ci',
// 		],
// 		'title'	=>	[
// 			'type'		=>	'varchar(255)',
// 			'null'		=>	false,
// 			'default'	=>	null,
// 			'increment'	=>	false,
// 			'collation'	=>	'utf8mb4_general_ci',
// 		],
// 		'slug'	=>	[
// 			'type'		=>	'varchar(255)',
// 			'null'		=>	false,
// 			'default'	=>	null,
// 			'increment'	=>	false,
// 			'collation'	=>	'utf8mb4_general_ci',
// 		],
// 	],
// 	'engine'	=>	'InnoDB', // MyISAM/InnoDB
// 	'charset'	=>	'utf8mb4',
// 	'primary'	=>	'id', // ?

// ];

// var_dump(createTable($pagesQuery));

// get('/$var', 'content/pages/main.php');

// register hook
doAction('routes');

// any('/404','content/pages/404.php'); //TODO

//ajax
routeAjax('/ajax/$action'); // ?????



