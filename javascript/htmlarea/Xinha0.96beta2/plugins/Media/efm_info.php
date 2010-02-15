<?php
	include("../ExtendedFileManager/config.inc.php");
	if($IMConfig['allow_upload'] == true || 
	$IMConfig['allow_edit_image'] == true && $IMConfig['img_library'] == true){
		if($IMConfig['safe_mode'] == true){
			$IMConfig['allow_new_dir'] = false;
		}
		echo "dir=$IMConfig[images_dir]&url=$IMConfig[images_url]&newdir=$IMConfig[allow_new_dir]";
	}else{
		echo "null";
	}
?>