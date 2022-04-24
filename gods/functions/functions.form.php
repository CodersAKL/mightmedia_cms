<?php
// drop zone
function addDropZoneStyle()
{
	echo '
	<!-- Dropzone Css -->
	<link href="' . adminUrl() . 'themes/material/plugins/dropzone/dropzone.css" rel="stylesheet">
	';
}

function addDropZoneScript()
{
	echo '
	<!-- Dropzone Plugin Js -->
	<script src="' . adminUrl() . 'themes/material/plugins/dropzone/dropzone.js"></script>
	<script>
		//$(function () {
		//Dropzone
		Dropzone.options.frmFileUpload = {
			paramName: "file",
			maxFilesize: 10,
			uploadMultiple: true
			// maxFilesize
			// acceptedFiles
		};
		//});
	</script>
	';
}
// editor
function editor($id = false, $value = '') {

	if (! $id) {
		$id = md5(uniqid());
	}

	if (getOption('site_editor') == 'tinymce') {

		addAction('adminScripts', 'tinymceScripts');

		return '<textarea id="' . $id . '" name="' . $id . '" class="tinymce">' . $value . '</textarea>';
		
	} elseif (getOption('site_editor') == 'ckeditor' ) {

		addAction('adminScripts', 'ckeScripts');

		return '<textarea id="' . $id . '" name="' . $id . '" class="ckeditor">' . $value . '</textarea>';
	}
	
	return '<textarea id="' . $id . '" name="' . $id . '" rows="1" class="form-control no-resize auto-growth">' . $value . '</textarea>';
}


// HOOKS action

function tinymceScripts() {
	echo '
	<!-- Load TinyMCE -->
	<script src="' . adminUrl() . 'htmlarea/tinymce/tinymce.js" type="text/javascript"></script>
	<script type="text/javascript">
		//TinyMCE
		tinymce.init({
			selector: "textarea.tinymce",
			theme: "modern",
			height: 300,
			plugins: [
				\'advlist autolink lists link image charmap print preview hr anchor pagebreak\',
				\'searchreplace wordcount visualblocks visualchars code fullscreen\',
				\'insertdatetime media nonbreaking save table contextmenu directionality\',
				\'emoticons template paste textcolor colorpicker textpattern imagetools\'
			],
			toolbar1: \'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image\',
			toolbar2: \'print preview media | forecolor backcolor emoticons\',
			image_advtab: true,
			// images_upload_url: \'postAcceptor.php\', - images local upload
		});

	</script>
	<!-- /TinyMCE -->';
}
function ckeScripts() {
	echo '
	<!-- Load CKE Editor -->
	<script src="' . adminUrl() . 'htmlarea/ckeditor/ckeditor.js" type="text/javascript"></script>
	<script type="text/javascript">
		//CKEditor
		CKEDITOR.replaceClass = \'ckeditor\';
		CKEDITOR.config.height = 300;
		CKEDITOR.config.extraPlugins = \'uploadimage\';
	</script>';
}