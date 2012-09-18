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
if ( !isset( $_SESSION[SLAPTAS]['username'] ) || empty( $_SESSION[SLAPTAS]['username'] ) ) {
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
	if ( puslapis( 'reg.php' ) ) {
		$text .= "<a href=\"" . url( "?id," . $conf['puslapiai']['reg.php']['id'] ) . "\">{$lang['user']['registration']}</a> ";
	}
	if ( puslapis( 'slaptazodzio_priminimas.php' ) ) {
		$text .= "<a href=\"" . url( "?id," . $conf['puslapiai']['slaptazodzio_priminimas.php']['id'] ) . "\">{$lang['user']['pass_forget']}</a>";
		$title = $lang['user']['for_members'];
	}
} else {
	//$user = $_SESSION['username'];
	$text = "<ul>";
	//profilio nuoroda
	if ( isset( $conf['puslapiai']['view_user.php']['id'] ) ) {
		if ( !$conf['F_urls'] == 0 ) {
			$bruksniukxs = $conf['F_urls'];
		} else {
			$bruksniukxs = ";";
		}

		$text .= "<li><a href=\"" . url( "?id," . $conf['puslapiai']['view_user.php']['id'] ) . $bruksniukxs . $_SESSION[SLAPTAS]['username'] . "\"><img src=\"images/icons/user-white.png\" alt=\"@\" style=\"vertical-align: middle;\" border=\"0\" /> {$lang['user']['profile']}</a></li>";
	}
	//profilio redagavimo nuoroda
	if ( isset( $conf['puslapiai']['edit_user.php']['id'] ) ) {
		$text .= "<li><a href=\"" . url( "?id," . $conf['puslapiai']['edit_user.php']['id'] ) . "\"><img src=\"images/icons/user_edit.png\" alt=\"@\" style=\"vertical-align: middle;\" border=\"0\" /> {$lang['user']['edit_profile']}</a></li>";
	}
	//Pm nuoroda
	if ( puslapis( 'pm.php' ) ) {
		$pm = kiek( 'private_msg', "WHERE `to`=" . escape( $_SESSION[SLAPTAS]['username'] ) . " AND `read`='NO'", 'total' );

		if ( $pm != 0 ) {
			$img = "<img src='images/icons/email_error.gif' alt='new' border='0' style=\"vertical-align: middle;\"/>";
		} else {
			$img = "<img src='images/icons/email.png' alt='@' border='0' style=\"vertical-align: middle;\" />";
		}
		$text .= "<li><a href=\"" . url( "?id," . $conf['puslapiai']['pm.php']['id'] . ";a,1" ) . "\">$img {$lang['user']['messages']} ({$pm})</a></li>";
	}
	//moderatoriaus puslapio nuoroda
	if ( puslapis( 'moderatorius.php' ) && !empty( $_SESSION[SLAPTAS]['mod'] ) ) {
		$text .= "
			<li><a href=\"" . url( "?id,{$conf['puslapiai']['moderatorius.php']['id']}" ) . "\"><img src=\"images/icons/book__pencil.png\" alt=\"@\" style=\"vertical-align: middle;\" border=\"0\" /> {$lang['system']['mod']}</a></li>";
	}

	//atsijungimo nuoroda
	$text .= "
			<li><a href=\"{$lang['user']['logout']}\"><img src=\"images/icons/key_go.png\" alt=\"@\" style=\"vertical-align: middle;\" border=\"0\" /> {$lang['user']['logout']}</a></li>
		</ul>
	";
	$title = sprintf( $lang['user']['hello'], $_SESSION[SLAPTAS]['username'] );
}
?>