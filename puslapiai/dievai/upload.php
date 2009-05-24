	<script language="JavaScript">
	// Notice: The simple theme does not use all options some of them are limited to the advanced theme
	tinyMCE.init({
		mode : "textareas",
		elements : "Apie",
		theme : "simple",
		mode : "exact"
		//content_css : "stilius/stilius.css",
		//apply_source_formatting : true,
	});

	</script>
	<script type="text/javascript" src="javascript/swfupload/swfupload.js"></script>
	<script type="text/javascript" src="javascript/swfupload/swfupload.graceful_degradation.js"></script>
	<script type="text/javascript" src="javascript/swfupload/handlers.js"></script>
	<script type="text/javascript">
		var swf_upload_control;

        //window.onload = function () {
            swf_upload_control = new SWFUpload({
				// Backend settings
				upload_url: "../../upload.php",	// Relative to the SWF file, you can use an absolute URL as well.
				file_post_name: "resume_file",

				// Flash file settings
				file_size_limit : "10240",	// 10 MB
				file_types : "*.jpg;*.gif;*.png;*.bmp;*.zip;*.rar;*.doc;*.mrc;*.pdf",	// or you could use something like: "*.doc;*.wpd;*.pdf",
				file_types_description : "Paveiksliukai",
				file_upload_limit : "0", // Even though I only want one file I want the user to be able to try again if an upload fails
				file_queue_limit : "1", // this isn't needed because the upload_limit will automatically place a queue limit

				// Event handler settings
				swfupload_loaded_handler : myShowUI,
				
				//file_dialog_start_handler : fileDialogStart,		// I don't need to override this handler
				file_queued_handler : fileQueued,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				
				//upload_start_handler : uploadStart,	// I could do some client/JavaScript validation here, but I don't need to.
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,

				// Flash Settings
				flash_url : "javascript/swfupload/swfupload_f9.swf",	// Relative to this file

				// UI settings
				swfupload_element_id : "flashUI",		// setting for the graceful degradation plugin
				degraded_element_id : "degradedUI",

				custom_settings : {
					progress_target : "fsUploadProgress",
					upload_successful : false
				},
				
				// Debug settings
				debug: true
			});

        //}

        function myShowUI() {
         var btnSubmit = document.getElementById("btnSubmit");
			var txtLastName = document.getElementById("lastname");
			var txtFirstName = document.getElementById("firstname");
			var txtEducation = document.getElementById("education");
			var txtReferences = document.getElementById("references");
			
			btnSubmit.onclick = doSubmit;
			btnSubmit.disabled = true;
			
			txtLastName.onchange = validateForm;
			txtFirstName.onchange = validateForm;
			txtEducation.onchange = validateForm;
			txtReferences.onchange = validateForm;
			
			
            SWFUpload.swfUploadLoaded.apply(this);  // Let SWFUpload finish loading the UI.
			validateForm();
        }
		
		function validateForm() {
			var txtLastName = document.getElementById("lastname");
			var txtFirstName = document.getElementById("firstname");
			var txtEducation = document.getElementById("education");
			var txtFileName = document.getElementById("txtFileName");
			var txtReferences = document.getElementById("references");
			
			var is_valid = true;
			if (txtLastName.value === "") is_valid = false;
			if (txtFirstName.value === "") is_valid = false;
			if (txtEducation.value === "") is_valid = false;
			if (txtFileName.value === "") is_valid = false;
			if (txtReferences.value === "") is_valid = false;
			
			document.getElementById("btnSubmit").disabled = !is_valid;
		
		}
		
		function fileBrowse() {
			var txtFileName = document.getElementById("txtFileName");
			txtFileName.value = "";

			this.cancelUpload();
			this.selectFile();
		}
		
		
        // Called by the submit button to start the upload
		function doSubmit(e) {
			e = e || window.event;
			if (e.stopPropagation) e.stopPropagation();
			e.cancelBubble = true;
			
			try {
				swf_upload_control.startUpload();
			} catch (ex) {

            }
            return false;
	    }

		 // Called by the queue complete handler to submit the form
	    function uploadDone() {
			try {
				document.forms[0].submit();
			} catch (ex) {
				alert("Error submitting form");
			}
	    }
	</script>
<?php
//Jeigu "action" tipas yra 1 - Siuntinio idejimas
if ($_GET['a'] == 1) {
require_once("priedai/class.php");
$bla = new forma();

$forma = array(
	"Form"=>array("action"=>"","method"=>"post","enctype"=>"multipart/form-data","id"=>"form1","class"=>"","name"=>"siuntiniai"),
	"Siuntinio pavadinimas:"=>array("type"=>"text","value"=>input($conf['Pavadinimas']),"name"=>"Pavadinimas","class"=>"input"),
	"Trumpai apie svetainę:"=>array("type"=>"textarea","id"=>"Apie","value"=>input($conf['Apie']),"name"=>"Apie","class"=>"input","rows"=>"8","class"=>"input"),
	"Raktiniai žodžiai: (skirkite žodžius kableliais)"=>array("type"=>"textarea","value"=>input($conf['Keywords']),"name"=>"Keywords","rows"=>"3","class"=>"input","class"=>"input"),
	"Ar rodyti sugeneravimo laiką:"=>array("type"=>"select","value"=>array("1"=>"Taip","0"=>"Ne"),"selected"=>input($conf['Render']),"name"=>"Render"),
	"Copyright Tekstas:"=>array("type"=>"text","value"=>input($conf['Copyright']),"name"=>"Copyright","class"=>"input"),
	"Svetaines e-paštas:"=>array("type"=>"text","value"=>input($conf['Pastas']),"name"=>"Pastas","class"=>"input"),
	"Leisti registruotis:"=>array("type"=>"select","value"=>array("1"=>"Taip","0"=>"Ne"),"selected"=>input($conf['Registracija']),"name"=>"Registracija"),
	"Svetaine remontuojama?:"=>array("type"=>"select","value"=>array("1"=>"Taip","0"=>"Ne"),"selected"=>input($conf['Palaikymas']),"name"=>"Palaikymas"),
	"Uždarytos svetaines tekstas:"=>array("type"=>"textarea","id"=>"Maintenance","value"=>input($conf['Maintenance']),"name"=>"Maintenance","rows"=>"8","class"=>"input","class"=>"input"),
	"Kiek rodyti ChatBox pranešimu?:"=>array("type"=>"select","value"=>array("5"=>"5","10"=>"10","15"=>"15","20"=>"20","25"=>"25","30"=>"30","35"=>"35","40"=>"40"),"selected"=>input($conf['Chat_limit']),"name"=>"Chat_limit"),
	"Kiek rodyti naujienu?:"=>array("type"=>"select","value"=>array("5"=>"5","10"=>"10","15"=>"15","20"=>"20","25"=>"25","30"=>"30","35"=>"35","40"=>"40"),"selected"=>input($conf['News_limit']),"name"=>"News_limit"),
	"Svetainės stilius:"=>array("type"=>"select","value"=>$stiliai,"selected"=>input($conf['Stilius']),"name"=>"Stilius"),
	""=>array("type"=>"submit","name"=>"Konfiguracija","value"=>"Saugoti")
);

}
?>
	<form id="form1" action="" enctype="multipart/form-data" method="post">
		<div class="content">
			<div>This demo shows how SWFUpload might be combined with an HTML form.  It also demonstrates graceful degradation (using the graceful degradation plugin).
			This demo also demonstrates the use of the server_data parameter.  This demo requires Flash Player 9+</div>
			<fieldset >
				<legend>Submit your Application</legend>
				<table style="vertical-align:top;">
					<tr>
						<td>
							Last Name:
						</td>
						<td>
							<input name="lastname" id="lastname" type="text" style="width: 200px" />
						</td>
					</tr>
					<tr>
						<td>
							First Name:
						</td>
						<td>
							<input name="firstname" id="firstname" type="text" style="width: 200px" />
						</td>
					</tr>
					<tr>
						<td>
							Education:
						</td>
						<td>
							<textarea name="education"  id="education" cols="0" rows="0" style="width: 400px; height: 100px;"></textarea>
						</td>
					</tr>
					<tr>
						<td>
							Resume:
						</td>
						<td>

							<div id="flashUI" style="display: none;">
								<!-- The UI only gets displayed if SWFUpload loads properly -->
								<div>
									<input type="text" id="txtFileName" disabled="true" style="border: solid 1px; background-color: #FFFFFF;" /><input id="btnBrowse" type="button" value="Browse..." onclick="fileBrowse.apply(swf_upload_control)" /> (10 MB max)
								</div>
								<div class="flash" id="fsUploadProgress">
									<!-- This is where the file progress gets shown.  SWFUpload doesn't update the UI directly.
										The Handlers (in handlers.js) process the upload events and make the UI updates -->
								</div>
								<input type="hidden" name="hidFileID" id="hidFileID" value="" /><!-- This is where the file ID is stored after SWFUpload uploads the file and gets the ID back from upload.php -->
							</div>
							<div id="degradedUI">
								<!-- This is the standard UI.  This UI is shown by default but when SWFUpload loads it will be
								hidden and the "flashUI" will be shown -->
								<input type="file" name="resume_degraded" id="resume_degraded" /> (10 MB max)<br/>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							References:
						</td>
						<td>
							<textarea name="references" id="references" cols="0" rows="0" style="width: 400px; height: 100px;"></textarea>
						</td>
					</tr>
				</table>
				<br />
				<input type="submit" value="Submit Application" id="btnSubmit" />
			</fieldset>
		</div>
	</form>
