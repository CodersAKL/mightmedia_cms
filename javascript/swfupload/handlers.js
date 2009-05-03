
function fileQueueError(fileObj, error_code, message)  {
	try {
		// Handle this error separately because we don't want to create a FileProgress element for it.
		switch(error_code) {
			case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:
				alert("Jūs bandote įkelti perdaug failų.\n" + (message == 0 ? "Pasiekėt įkėlimo limitą." : "Jūs galite parinkti " + (message > 1 ? "daugiau kaip " + message + " failus." : "vieną failą.")));
				return;
				break;
			case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
				alert("Parinktas failas perdidelis.");
				this.debug("Error Code: File too big, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
				return;
				break;
			case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
				alert("Parinktas failas yra tuščias.  Prašome parinkti kitą.");
				this.debug("Error Code: Zero byte file, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
				return;
				break;
			case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
				alert("Parinktas failas nėra leistinų saraše.");
				this.debug("Error Code: Invalid File Type, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
				return;
				break;
			default:
				alert("Nusiuntimo klaida. Prašome pabandyti vėliau.");
				this.debug("Error Code: " + error_code + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
				return;
				break;
		}
	} catch (e) {}
}

function fileQueued(fileObj) {
	try {
		var txtFileName = document.getElementById("txtFileName");
		txtFileName.value = fileObj.name;
	} catch (e) { }

}
function fileDialogComplete(num_files_selected) {
	validateForm();
}

function uploadProgress(fileObj, bytesLoaded, bytesTotal) {

	try {
		var percent = Math.ceil((bytesLoaded / bytesTotal) * 100)

		fileObj.id = "singlefile";	// This makes it so FileProgress only makes a single UI element, instead of one for each file
		var progress = new FileProgress(fileObj, this.customSettings.progress_target);
		progress.SetProgress(percent);
		progress.SetStatus("Išsiunčiame...");
	} catch (e) { }
}

function uploadSuccess(fileObj, server_data) {
	try {
		fileObj.id = "singlefile";	// This makes it so FileProgress only makes a single UI element, instead of one for each file
		var progress = new FileProgress(fileObj, this.customSettings.progress_target);
		progress.SetComplete();
		progress.SetStatus("Atlikta.");
		progress.ToggleCancel(false);
		
		if (server_data === " ") {
			this.customSettings.upload_successful = false;
		} else {
			this.customSettings.upload_successful = true;
			document.getElementById("hidFileID").value = server_data;
		}
		
	} catch (e) { }
}

function uploadComplete(fileObj) {
	try {
		if (this.customSettings.upload_successful) {
			document.getElementById("btnBrowse").disabled = "true";
			uploadDone();
		} else {
			fileObj.id = "singlefile";	// This makes it so FileProgress only makes a single UI element, instead of one for each file
			var progress = new FileProgress(fileObj, this.customSettings.progress_target);
			progress.SetError();
			progress.SetStatus("Failas atmestas");
			progress.ToggleCancel(false);
			
			var txtFileName = document.getElementById("txtFileName");
			txtFileName.value = "";
			validateForm();

			alert("Klaida siunčiant failą.\nServeris atmetė failą.");
		}
	} catch (e) {  }
}

function uploadError(fileObj, error_code, message) {
	try {
		var txtFileName = document.getElementById("txtFileName");
		txtFileName.value = "";
		validateForm();
		
		// Handle this error separately because we don't want to create a FileProgress element for it.
		switch(error_code) {
			case SWFUpload.UPLOAD_ERROR.MISSING_UPLOAD_URL:
				alert("Nustatymų klaida. Jūs negalėsite pratęsti failo išsiuntimo.");
				this.debug("Error Code: No backend file, File name: " + file.name + ", Message: " + message);
				return;
				break;
			case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
				alert("Jūs galite įkelti tik vieną failą.");
				this.debug("Error Code: Upload Limit Exceeded, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
				return;
				break;
			case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
			case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
				break;
			default:
				alert("Išsiuntimo klaida. Prašome pabandyti vėliau.");
				this.debug("Error Code: " + error_code + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
				return;
				break;
		}

		fileObj.id = "singlefile";	// This makes it so FileProgress only makes a single UI element, instead of one for each file
		var progress = new FileProgress(fileObj, this.customSettings.progress_target);
		progress.SetError();
		progress.ToggleCancel(false);

		switch(error_code) {
			case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
				progress.SetStatus("Išsiuntimo klaida");
				this.debug("Error Code: HTTP Error, File name: " + file.name + ", Message: " + message);
				break;
			case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
				progress.SetStatus("Išsiuntimas nepavyko.");
				this.debug("Error Code: Upload Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
				break;
			case SWFUpload.UPLOAD_ERROR.IO_ERROR:
				progress.SetStatus("Serverio (IO) klaida");
				this.debug("Error Code: IO Error, File name: " + file.name + ", Message: " + message);
				break;
			case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
				progress.SetStatus("Saugumo klaida");
				this.debug("Error Code: Security Error, File name: " + file.name + ", Message: " + message);
				break;
			case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
				progress.SetStatus("Išsiuntimas atšauktas");
				this.debug("Error Code: Upload Cancelled, File name: " + file.name + ", Message: " + message);
				break;
			case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
				progress.SetStatus("Išsiuntimas sustabdytas");
				this.debug("Error Code: Upload Stopped, File name: " + file.name + ", Message: " + message);
				break;
		}
	} catch (e) {}
}


/* ********************************************************
 *  Utility for displaying the file upload information
 *  This is not part of SWFUpload, just part of the demo
 * ******************************************************** */
function FileProgress(fileObj, target_id) {
		this.file_progress_id = fileObj.id;

		this.fileProgressElement = document.getElementById(this.file_progress_id);
		if (!this.fileProgressElement) {
			this.fileProgressElement = document.createElement("div");
			this.fileProgressElement.className = "progressContainer";
			this.fileProgressElement.id = this.file_progress_id;

			var progressCancel = document.createElement("a");
			progressCancel.className = "progressCancel";
			progressCancel.href = "#";
			progressCancel.style.visibility = "hidden";
			progressCancel.appendChild(document.createTextNode(" "));

			var progressText = document.createElement("div");
			progressText.className = "progressName";
			progressText.appendChild(document.createTextNode(fileObj.name));

			var progressBar = document.createElement("div");
			progressBar.className = "progressBarInProgress";

			var progressStatus = document.createElement("div");
			progressStatus.className = "progressBarStatus";
			progressStatus.innerHTML = "&nbsp;";

			this.fileProgressElement.appendChild(progressCancel);
			this.fileProgressElement.appendChild(progressText);
			this.fileProgressElement.appendChild(progressStatus);
			this.fileProgressElement.appendChild(progressBar);

			document.getElementById(target_id).appendChild(this.fileProgressElement);

		}

}
FileProgress.prototype.SetStart = function() {
		this.fileProgressElement.className = "progressContainer";
		this.fileProgressElement.childNodes[3].className = "progressBarInProgress";
		this.fileProgressElement.childNodes[3].style.width = "";
}

FileProgress.prototype.SetProgress = function(percentage) {
		this.fileProgressElement.className = "progressContainer green";
		this.fileProgressElement.childNodes[3].className = "progressBarInProgress";
		this.fileProgressElement.childNodes[3].style.width = percentage + "%";
}
FileProgress.prototype.SetComplete = function() {
		this.fileProgressElement.className = "progressContainer blue";
		this.fileProgressElement.childNodes[3].className = "progressBarComplete";
		this.fileProgressElement.childNodes[3].style.width = "";


}
FileProgress.prototype.SetError = function() {
		this.fileProgressElement.className = "progressContainer red";
		this.fileProgressElement.childNodes[3].className = "progressBarError";
		this.fileProgressElement.childNodes[3].style.width = "";
}
FileProgress.prototype.SetCancelled = function() {
		this.fileProgressElement.className = "progressContainer";
		this.fileProgressElement.childNodes[3].className = "progressBarError";
		this.fileProgressElement.childNodes[3].style.width = "";
}
FileProgress.prototype.SetStatus = function(status) {
		this.fileProgressElement.childNodes[2].innerHTML = status;
}

FileProgress.prototype.ToggleCancel = function(show, upload_obj) {
		this.fileProgressElement.childNodes[0].style.visibility = show ? "visible" : "hidden";
		if (upload_obj) {
			var file_id = this.file_progress_id;
			this.fileProgressElement.childNodes[0].onclick = function() { upload_obj.cancelUpload(file_id); return false; };
		}
}