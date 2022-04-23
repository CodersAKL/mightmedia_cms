<?php
// die('aaa');
function createMyPostType()
{
	$params  = [
		'name'  	=>	'pages',
		'label'  	=>	'Pages',
		'label_list'  	=>	'Custom list',
		'icon'  	=>	'favorite_border',
		'fields'    =>	[
			'title'     => true,
			'editor'    => true,
			'excerpt'	=> true,
			'media'     => [
				'attachments'   => false,
				'photo'         => false,
				'gallery'       => false,
			],
			// add defaults params
		],
	];

	createPostType($params);
}

addAction('boot', 'createMyPostType');