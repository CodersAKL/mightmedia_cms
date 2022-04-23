<?php

// add media user page
function mediaRoutes()
{
	get('/' . ADMIN_DIR . '/media', ADMIN_ROOT . 'sys/media/page.php', false);
}

addAction('adminRoutes', 'mediaRoutes');

function mediaMenu($data)
{
// check if exists
	$data['media'] = [
		'url' 	=> '/' . ADMIN_DIR . '/media',
		'title' => 'Media',
	];
	
	return $data;
}

addAction('adminMenu', 'mediaMenu');
