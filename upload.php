<?php

	if (isset($_FILES["resume_file"]) && is_uploaded_file($_FILES["resume_file"]["tmp_name"]) && $_FILES["resume_file"]["error"] == 0) {
		@move_uploaded_file($_FILES["resume_file"]["tmp_name"],"siuntiniai/".$_FILES["resume_file"]["name"].".tmp");
		echo rand(1000000, 9999999);	// Create a pretend file id, this might have come from a database.
	} else {
		echo ' '; // I have to return something or SWFUpload won't fire uploadSuccess
	}
	
?>