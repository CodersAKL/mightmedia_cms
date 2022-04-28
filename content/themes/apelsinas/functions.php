<?php
// die('aaa');
function createMyPostType()
{
	$params  = [
		'name'			=>	'custom',
		'label'			=>	'Custom',
		'label_list'	=>	'Custom list',
		'icon'			=>	'favorite_border',
		'fields'		=>	[
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