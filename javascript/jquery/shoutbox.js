/***************************/
//@Author: Adrian "yEnS" Mato Gondelle & Ivan Guardado Castro
//@website: www.yensdesign.com
//@email: yensamg@gmail.com
//@license: Feel free to use it, but keep this credits please!					
/***************************/
$(document).ready(function(){
	//global vars
	//var inputUser = $("#nick");
	var inputMessage = $("#message");
	var loading = $("#loading");
	var messageList = $(".contenter");
	
	//functions
	function updateShoutbox(){
		//just for the fade effect
		messageList.hide();
		loading.fadeIn();
		//rand=Math.random();
		//send the post to shoutbox.php
		$.ajax({
			type: "POST", url: "shoutbox.php", data: "action=update",
			complete: function(data){
				loading.fadeOut();
				messageList.html(data.responseText);
				messageList.fadeIn(2000);
			}
		});
	}
	//check if all fields are filled
	function checkForm(){
	//inputUser.attr("value") &&
		if(inputMessage.attr("value"))
			return true;
		else
			return false;
	}
	
	//Load for the first time the shoutbox data
	updateShoutbox();
	
	//on submit event
	$("#send").click(function(){
		if(checkForm()){
			//var nick = inputUser.attr("value");
			var message = inputMessage.attr("value");
			//we deactivate submit button while sending
			$("#send").attr({ disabled:true, value:"Siunčiama..." });
			$("#message").attr({ disabled:true, value:"" });
			$("#send").blur();
			//send the post to shoutbox.php
			$.ajax({
				type: "POST", url: "shoutbox.php", data: "action=insert&message=" + message,
				complete: function(data){
					messageList.html(data.responseText);
					updateShoutbox();
					//reactivate the send button
					$("#send").attr({ disabled:false, value:"Rėkti! / Naujinti" });
				  $("#message").attr({ disabled:false, value:"" });
				}
			 });
		}
		else //alert("Užpildykite laukelį!"); 
		updateShoutbox();
		//$("#send").attr({ disabled:false, value:"Rėkti! / Naujinti" });
		//we prevent the refresh of the page after submitting the form
		return false;
	});
		
});