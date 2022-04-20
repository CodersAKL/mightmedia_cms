<?php

require_once config('class', 'dir') . 'class.postType.php';

$params  = [
		'name'  	=>	'pages',
		'label'  	=>	'Pages',
		'label_list'  	=>	'Custom list',
		'icon'  	=>	'favorite_border',
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

createPostType($params);

function createPostType(array $params = [])
{
	$posType = new PostType;

	$posType->setArgs($params);
	$posType->createPostType();

}

function getPostTypeParams()
{
	
}


