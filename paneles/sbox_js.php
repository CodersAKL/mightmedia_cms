<?php
$text='<script type="text/javascript" src="javascript/jquery/shoutbox.js"></script>';

	 if(isset($_SESSION['username'])){ 
	$text.='<div id="form">
		
			<input class="text" id="message" type="text"  style="width:98%;" /><br />
			<input class="text" id="send" type="submit" style="width:98%" value="Rėkti! / Naujinti" /> 
			
	</div><hr />';
	
	 }
$text.='	<div id="containerer">
		<span class="clear"></span>
		<div class="contenter">
					<div id="loading" align="center"><img align="center" src="images/galerija/lightbox-ico-loading.gif" alt="Kraunasi..." /></div>
			<ul>
			<ul>
		</div>
	</div>';
if (isset($conf['puslapiai']['deze.php']['id'])) {
		$text .= "<a href='?id," . $conf['puslapiai']['deze.php']['id'] . "' >{$lang['sb']['archive']}</a>";
	}