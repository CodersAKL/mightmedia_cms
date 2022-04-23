<?php

addAction('adminStyles', 'addDropZoneStyle');

addAction('adminScripts', 'addDropZoneScript');

addAction('ajaxMediaUpload', 'mediaUpload');

function mediaUpload($data)
{
	require_once (config('class', 'dir') . 'class.uploader.php');

	$uploader = new Uploader();

	$upload = $uploader->upload(
		$data['file'], 
		[
			'limit' => 10, //Maximum Limit of files. {null, Number}
			'maxSize' => 10, //Maximum Size of files {null, Number(in MB's)}
			'extensions' => null, //Whitelist for file extension. {null, Array(ex: array('jpg', 'png'))}
			'required' => false, //Minimum one file is required for upload {Boolean}
			'uploadDir' => ROOT . 'content/uploads/', //Upload directory {String}
			'title' => '{{file_name}}', //New file name {null, String, Array} *please read documentation in README.md
			'removeFiles' => true, //Enable file exclusion {Boolean(extra for jQuery.filer), String($_POST field name containing json data with file names)}
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
		$files = $upload['data'];
		var_dump($files);
		// extension
		// name
		// type' => string 'image/jpeg' << from $data
	}
	if($upload['onCheck']){
		
		var_dump($upload);
		// extension
		// name
		// type' => string 'image/jpeg' << from $data
	}


	var_dump($data); exit;
	//   'name' => string 'IMG_0345.jpeg' (length=13)
    //       'type' => string 'image/jpeg' (length=10)
    //       'tmp_name' => string '/Applications/MAMP/tmp/php/php4odbnu' (length=36)
    //       'error' => int 0
    //       'size' => int 6938239
}