<?php

addAction('adminStyles', 'addDropZoneStyle');

addAction('adminScripts', 'addDropZoneScript');

addAction('ajaxMediaUpload', 'mediaUpload');

function mediaUpload($data)
{
	// TODO: move upload functions to seperate file
	require_once (config('class', 'dir') . 'class.uploader.php');

	$uploadDir = 'content/uploads/' . date('Y') . '/' . date('m') . '/';

	// If upload dir doesn't exists - creat it and set permissions
	if (! file_exists(ROOT . $uploadDir) && ! is_dir(ROOT . $uploadDir)) {
		mkdir(ROOT . $uploadDir, 0755, true);       
	}

	$uploader = new Uploader();

	$upload = $uploader->upload(
		$data['file'], 
		[
			'limit' => 10, //Maximum Limit of files. {null, Number}
			'maxSize' => 10, //Maximum Size of files {null, Number(in MB's)}
			'extensions' => null, //Whitelist for file extension. {null, Array(ex: array('jpg', 'png'))}
			'required' => false, //Minimum one file is required for upload {Boolean}
			'uploadDir' => ROOT . $uploadDir, //Upload directory {String}
			'title' => '{{file_name}}', //New file name {null, String, Array} *please read documentation in README.md
			'removeFiles' => false, //Enable file exclusion {Boolean(extra for jQuery.filer), String($_POST field name containing json data with file names)}
			'replace' => false, //Replace the file if it already exists {Boolean}
			'perms' => null, //Uploaded file permisions {null, Number}
			'onCheck' => null, //A callback function name to be called by checking a file for errors (must return an array) | ($file) | Callback
			'onError' => null, //A callback function name to be called if an error occured (must return an array) | ($errors, $file) | Callback
			'onSuccess' => null, //A callback function name to be called if all files were successfully uploaded | ($files, $metas) | Callback
			'onUpload' => null, //A callback function name to be called if all files were successfully uploaded (must return an array) | ($file) | Callback
			'onComplete' => null, //A callback function name to be called when upload is complete | ($file) | Callback
			'onRemove' => 'onFilesRemoveCallback' //A callback function name to be called by removing files (must return an array) | ($removed_files) | Callback
		]
	);

	// insert file data to DB
	if($upload['isComplete']){
		$files = $upload['data']['metas'];

		foreach ($files as $vFiley) {

			// if image add thumbnails
			
			$queryData = [
				'name' 		=> $vFiley['name'],
				'type' 		=> getFileType($vFiley['type'][0]),
				'mime' 		=> implode('/', $vFiley['type']),
				'extension' => $vFiley['extension'],
				'path'		=> $uploadDir,
			];

			dbInsert('media', $queryData);
		}
	}


	var_dump($data); exit;
}

function getFileType($type)
{
	// $parts = explode('/', $mime);

	if($type == 'image') {
		return 'image';
	}

	return 'file';
}

function mediaList()
{
	$columns = [
		'id',
		'name',
		'path',
		'type',
	];

	$files = dbSelect('media', null, $columns);

	return $files;
}