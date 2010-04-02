<?php
 session_start();
 require_once('../../../../../../../priedai/conf.php');
 require_once('../../../../../../../priedai/prisijungimas.php');

 if(!isset($_SESSION['level']) || $_SESSION['level'] != 1)
  die('eik lauk..');
 
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $conf['Pavadinimas'];?></title>
<link href="css/default.css" rel="stylesheet" type="text/css" />
<link href="css/uploadify.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/jqueryFileTree.css" media="screen" />
<link rel="stylesheet" type="text/css" href="css/styles.css" />
<script type="text/javascript" src="scripts/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="scripts/jquery-ui-1.7.2.custom.min.js"></script>
<script type="text/javascript" src="scripts/swfobject.js"></script>
<script type="text/javascript" src="scripts/jquery.uploadify.v2.1.0.min.js"></script>
<script type="text/javascript" src="jquery.easing.1.3.js"></script>
<script type="text/javascript" src="jqueryFileTree.js"></script>

<script type="text/javascript">
$(document).ready(function() {
	
  $('#JQueryFTD_Demo').fileTree({
    root: '',
    script: 'connectors/jqueryFileTree.php',
    expandSpeed: 1000,
    collapseSpeed: 1000,
    multiFolder: false
  }, function(file) {
		$.post('scripts/info.php',{'file':file},function(data){
			$('#fileQueue').html(data);
		});
  });
  
	$("#uploadify").uploadify({
		'uploader'       : 'scripts/uploadify.swf',
		'script'         : 'scripts/uploadify.php',
		//'checkScript'    : 'scripts/check.php',
		'sizeLimit': "2097152",  //2MB
		'fileExt'     : "*.exe;*.bat;*.cmd;*.htm;*.html;*.php;*.css;*.sql;*.db;*.doc;*.txt;*.mpg4;*.avi;*.mov;*.mkv;*.swf;*.java;*.jnlp;*.mp3;*.wav;*.pdf;*.gif;*.jpg;*.png;*.bmp;*.ppt;*.psd;*.xls;*.zip;*.rar;*.7z",
		'scriptData'     : {"<?php echo session_name(); ?>": "<?php echo session_id(); ?>","fileext":$(this).fileExt},
		'fileDesc' : "All Files",
		'cancelImg'      : 'cancel.png',
		'folder'         : '/siuntiniai',
		'queueID'        : 'fileQueue',
		'auto'           : true,
		'multi'          : true,
		'debug'          : true,
		'buttonText'     : 'Naujas failas',
		//'buttonImg'      : 'scripts/button61x22.png',
		//'rollover'       : true,
		//'width'          : 61,
		//'height'         : 22,
		'onAllComplete'  : function(event,data) {$(filetree).parent().parent().find('UL').slideUp({ duration: o.collapseSpeed, easing: o.collapseEasing }); }
		
	});
	
	$('.dragbox')
	.each(function(){
		$(this).hover(function(){
			$(this).find('h2').addClass('collapse');
		}, function(){
			$(this).find('h2').removeClass('collapse');
		})
		.find('h2').hover(function(){
			$(this).find('.configure').css('visibility', 'visible');
		}, function(){
			$(this).find('.configure').css('visibility', 'hidden');
		})
		.click(function(){
			$(this).siblings('.dragbox-content').toggle();
		})
		.end()
		.find('.configure').css('visibility', 'hidden');
	});
	$('.column').sortable({
		connectWith: '.column',
		handle: 'h2',
		cursor: 'move',
		placeholder: 'placeholder',
		forcePlaceholderSize: true,
		opacity: 0.4,
		stop: function(event, ui){
			$(ui.item).find('h2').click();
			var sortorder='';
			$('.column').each(function(){
				var itemorder=$(this).sortable('toArray');
				var columnId=$(this).attr('id');
				sortorder+=columnId+'='+itemorder.toString()+'&';
			});
			//alert('SortOrder: '+sortorder);
			//Pass sortorder variable to server using ajax to save state
		}
	})
	//.disableSelection();

});
</script>
<script language="javascript">
function dialog_close(cual) {
	var o = window.opener.document.getElementById('<?php echo strip_tags($_GET['id']); ?>');
	//alert(o.value);
	o.value = o.value+'<img src="'+cual+'" alt="" />';
	self.close();	
}
</script>
</head>

<body>
	<div class="column" id="column1">
		<div class="dragbox" id="item1" >
			<h2><?php echo $lang['admin']['file_list']; ?></h2>
			<div class="dragbox-content" >
				<div id="JQueryFTD_Demo" class="fileTree"></div>
			</div>
		</div>
	</div>
	<div class="column" id="column2" >
		<div class="dragbox" id="item1" >
			<h2><?php echo $lang['admin']['preview']; ?></h2>
			<div class="dragbox-content" >
				<div id="fileQueue"></div>
				<input type="file" name="uploadify" id="uploadify" />
			</div>
		</div>
	</div>
</body>
</html>
