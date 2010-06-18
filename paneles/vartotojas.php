<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author$
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision$
 * @$Date$
 **/

####################### Atvaizduojam panele ###########################
if (!defined("LEVEL") || !isset($_SESSION['username']) || empty($_SESSION['username'])) {
	$text = <<< HTML
	<center>
		<form id="user_reg" name="user_reg" method="post" action="">
			<label>{$lang['user']['user']}:<br />
			<script type="text/javascript">document.write(unescape(unescape('%3C')+'input name="vartotojas" class="input" id="vartotojas" type="text" value="" maxlength="50" /'+unescape('%3E')));</script></label><br />
			<label for="slaptazodis">{$lang['user']['password']}:</label><br/>
			<input name="slaptazodis" class="input" id="slaptazodis" type="password" value="" maxlength="50" /><br/>
			<label>{$lang['user']['login_remember']}:<input type="checkbox" class="checkbox" style="background-color:transparent;border:0" name="Prisiminti" id="Prisiminti"/></label><br /> <input type="submit" class="submit" name="Submit" value="{$lang['user']['login']}" />
			<input type="hidden" name="action" value="prisijungimas" />
		</form>
	</center>
HTML;
		if (isset($conf['puslapiai']['reg.php']['id'])) {
			$text .= "<a href=\"".url("?id," . $conf['puslapiai']['reg.php']['id'] ). "\">{$lang['user']['registration']}</a> ";
		}
	if (isset($conf['puslapiai']['slaptazodzio_priminimas.php']['id'])) {
		$text .= "<a href=\"".url("?id," . $conf['puslapiai']['slaptazodzio_priminimas.php']['id'] ). "\">{$lang['user']['pass_forget']}</a>";
		$title = $lang['user']['for_members'];
	}
} else {
	$user = $_SESSION['username'];
	$text = "<ul>";
	//profilio redagavimo nuoroda
	if (isset($conf['puslapiai']['edit_user.php']['id'])) {
		$text .= "<li><a href=\"".url("?id," . $conf['puslapiai']['edit_user.php']['id']) . "\"><img src=\"images/icons/user_edit.png\" alt=\"@\" style=\"vertical-align: middle;\" border=\"0\" /> {$lang['user']['edit_profile']}</a></li>";
	}
	//Pm nuoroda
	if (isset($conf['puslapiai']['pm.php']['id'])) {
		$pm = kiek('private_msg', "WHERE `to`=" . escape($_SESSION['username']) . " AND `read`='NO'", 'total');

		if ($pm != 0) {
			$img = "<img src='images/icons/email_error.gif' alt='new' border='0' style=\"vertical-align: middle;\"/>";
		} else {
			$img = "<img src='images/icons/email.png' alt='@' border='0' style=\"vertical-align: middle;\" />";
		}
		$text .= "<li><a href=\"".url("?id," . $conf['puslapiai']['pm.php']['id'] . ";a,1")."\">$img {$lang['user']['messages']} ({$pm})</a></li>";
	}
  //moderatoriaus puslapio nuoroda
	if (isset($conf['puslapiai']['moderatorius.php']['id']) && isset($_SESSION['mod']) && !empty($_SESSION['mod'])) {
		$text .= "
			<li><a href=\"".url("?id,{$conf['puslapiai']['moderatorius.php']['id']}")."\"><img src=\"images/icons/book__pencil.png\" alt=\"@\" style=\"vertical-align: middle;\" border=\"0\" /> {$lang['system']['mod']}</a></li>";
	}
	//į admin pultą
	/*if (isset($_SESSION['level']) && $_SESSION['level'] == 1) {
		$text .= "
			<li><a href=\"dievai\"><img src=\"images/icons/admin_block.png\" alt=\"@\" style=\"vertical-align: middle;\" border=\"0\" /> {$lang['user']['administration']}</a></li>
			";
			
	}*/
	//atsijungimo nuoroda
	$text .= "
			<li><a href=\"{$lang['user']['logout']}\"><img src=\"images/icons/key_go.png\" alt=\"@\" style=\"vertical-align: middle;\" border=\"0\" /> {$lang['user']['logout']}</a></li>
		</ul>
	";
	$title = sprintf($lang['user']['hello'], $_SESSION['username']);
}
?>