<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: p.dambrauskas $
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision: 775 $
 * @$Date: 2010-12-23 14:56:07 +0200 (Kt, 23 Grd 2010) $
 **/

####################### Atvaizduojam panele ###########################
if (empty(getSession('username'))) {
	$text = <<< HTML
	<center>
		<form id="user_reg" name="user_reg" method="post" action="">
			<label>{$lang['user']['user']}:<br />
			<script type="text/javascript">document.write(unescape(unescape('%3C')+'input name="vartotojas" class="input" id="vartotojas" type="text" value="" maxlength="50" /'+unescape('%3E')));</script></label><br />
			<label for="slaptazodis">{$lang['user']['password']}:</label><br/>
			<input name="slaptazodis" class="input" id="slaptazodis" type="password" value="" maxlength="50" /><br/>
			<label>{$lang['user']['login_remember']}:<input type="checkbox" class="checkbox" style="background-color:transparent;border:0" name="Prisiminti" id="Prisiminti"/></label><br /> 
			<button class="btn btn-primary" type="submit" name="Submit">{$lang['user']['login']}</button>
			<input type="hidden" name="action" value="prisijungimas" />
		</form>
	</center>
HTML;
	if ( puslapis( 'reg.php' ) ) {
		$text .= "<a href=\"" . url( "?id," . $conf['pages']['reg.php']['id'] ) . "\">{$lang['user']['registration']}</a> ";
	}
	if ( puslapis( 'slaptazodzio_priminimas.php' ) ) {
		$text .= "<a href=\"" . url( "?id," . $conf['pages']['slaptazodzio_priminimas.php']['id'] ) . "\">{$lang['user']['pass_forget']}</a>";
		$title = $lang['user']['for_members'];
	}
} else {
	//$user = $_SESSION['username'];
	$text = "<ul>";
	//profilio nuoroda
	if ( isset( $conf['pages']['view_user.php']['id'] ) ) {
		if ( !$conf['F_urls'] == 0 ) {
			$bruksniukxs = $conf['F_urls'];
		} else {
			$bruksniukxs = ";";
		}

		$text .= "<li><a href=\"" . url( "?id," . $conf['pages']['view_user.php']['id'] ) . $bruksniukxs . getSession('username') . "\"><img src=\"core/assets/images/icons/user-white.png\" alt=\"@\" style=\"vertical-align: middle;\" border=\"0\" /> {$lang['user']['profile']}</a></li>";
	}
	//profilio redagavimo nuoroda
	if ( isset( $conf['pages']['edit_user.php']['id'] ) ) {
		$text .= "<li><a href=\"" . url( "?id," . $conf['pages']['edit_user.php']['id'] ) . "\"><img src=\"core/assets/images/icons/user_edit.png\" alt=\"@\" style=\"vertical-align: middle;\" border=\"0\" /> {$lang['user']['edit_profile']}</a></li>";
	}
	//Pm nuoroda
	if ( puslapis( 'pm.php' ) ) {
		$pm = kiek( 'private_msg', "WHERE `to`=" . escape(getSession('username')) . " AND `read`='NO'", 'total' );

		if ( $pm != 0 ) {
			$img = "<img src='core/assets/images/icons/email_error.gif' alt='new' border='0' style=\"vertical-align: middle;\"/>";
		} else {
			$img = "<img src='core/assets/images/icons/email.png' alt='@' border='0' style=\"vertical-align: middle;\" />";
		}
		$text .= "<li><a href=\"" . url( "?id," . $conf['pages']['pm.php']['id'] . ";a,1" ) . "\">$img {$lang['user']['messages']} ({$pm})</a></li>";
	}
	//moderatoriaus puslapio nuoroda
	if ( puslapis( 'moderatorius.php' ) && !empty(getSession('mod')) ) {
		$text .= "
			<li><a href=\"" . url( "?id,{$conf['pages']['moderatorius.php']['id']}" ) . "\"><img src=\"core/assets/images/icons/book__pencil.png\" alt=\"@\" style=\"vertical-align: middle;\" border=\"0\" /> {$lang['system']['mod']}</a></li>";
	}

	//atsijungimo nuoroda
	$text .= "
			<li><a href=\"{$lang['user']['logout']}\"><img src=\"core/assets/images/icons/key_go.png\" alt=\"@\" style=\"vertical-align: middle;\" border=\"0\" /> {$lang['user']['logout']}</a></li>
		</ul>
	";
	$title = sprintf($lang['user']['hello'], getSession('username'));
}