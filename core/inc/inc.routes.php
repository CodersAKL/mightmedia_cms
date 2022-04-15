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



function createPostType($params = [])
{
	$defaultParams  = [
		'name'  	=>	'pages',
		'fields'    =>	[
			'title'     => true,
			'editor'    => true,
			'media'     => [
				'attachments'   => false,
				'photo'         => false,
				'gallery'       => false,
			],
			// add defaults params
		],
	];

	$params = array_merge($defaultParams, $params);

	// make array query and create table
	$newQuery = [
		'name'	=>	$params['name'],
		'columns'	=>	[
			'id'	=>	[
				'type'		=>	'bigint(20)',
				'null'		=>	false,
				'default'	=>	null,
				'increment'	=>	true,
				'collation'	=>	'utf8mb4_general_ci',
			],
		],
		'engine'	=>	'InnoDB',
		'charset'	=>	'utf8mb4',
		'primary'	=>	'id'
	];

	foreach ($params['fields'] as $paramKey => $param) {
		// skip if param turned off
		if(! $param) {
			continue;
		}

		$newQuery[$paramKey] = [];
		// individually check
		switch ($paramKey) {
			case 'title':
				$newQuery[$paramKey] += [
					'type'		=>	'varchar(255)',
				];

				break;

			case 'excerpt':
				$newQuery[$paramKey] += [
					'type'		=>	'text',
				];

				break;

			case 'content':
				$newQuery[$paramKey] += [
					'type'		=>	'longtext',
				];

				break;

			case 'media': // TODO: foreign key to media relations table 
				$newQuery[$paramKey] += [
					'type'		=>	'bigint(20)',
				];

				break;
			
			default:
				$newQuery[$paramKey] += [
					'type'		=>	'varchar(255)',
				];
				break;
		}

		// defaul settings
		$newQuery[$paramKey] = [
			'null'		=>	true,
			'default'	=>	null,
			'increment'	=>	false,
			'collation'	=>	'utf8mb4_general_ci',
		];
	}


}




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



