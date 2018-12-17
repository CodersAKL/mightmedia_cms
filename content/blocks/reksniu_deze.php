<?php
unset( $i );
/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: zlotas $
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision: 828 $
 * @$Date: 2012-08-17 10:15:31 +0300 (Pn, 17 Rgp 2012) $
 **/

if ( isset( $_POST['chat_box'] ) && !empty( $_POST['chat_box'] ) && !empty( $_POST['chat_msg'] ) ) {
	if ( !isset( $_COOKIE['komentatorius'] ) || ( isset( $_POST['name'] ) && $_POST['name'] != $_COOKIE['komentatorius'] ) ) {
		setcookie( "komentatorius", $_POST['name'], time() + 60 * 60 * 24 * 30 );
	}
	$msg     = htmlspecialchars( $_POST['chat_msg'] );
	$nick_id = ( isset( $_SESSION[SLAPTAS]['id'] ) ? $_SESSION[SLAPTAS]['id'] : 0 );
	$nick    = ( isset( $_SESSION[SLAPTAS]['username'] ) ? $_SESSION[SLAPTAS]['username'] : ( !empty( $_POST['name'] ) ? $_POST['name'] : $lang['system']['guest'] ) );

	mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "chat_box`
		(`nikas`, `msg`, `time`, `niko_id`)
		VALUES (" . escape( $nick ) . ", " . escape( $msg ) . ", NOW(), " . escape( $nick_id ) . ");"
	);
	mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "chat_box` WHERE time < (NOW() - INTERVAL 31 DAY)" );
	redirect( $_SERVER['HTTP_REFERER'], "header" );
}
$extra    = '';
$vardas   = ( isset( $_COOKIE['komentatorius'] ) ? $_COOKIE['komentatorius'] : $lang['system']['guest'] );
$sveciams = ( isset( $conf['kmomentarai_sveciams'] ) && $conf['kmomentarai_sveciams'] == 1 );
if ( ( isset( $_SESSION[SLAPTAS]['username'] ) && !empty( $_SESSION[SLAPTAS]['username'] ) ) || $sveciams ) {
	$chat_box = "<form name=\"chat_box\" action=\"\" method=\"post\">
	               " . ( $sveciams && !isset( $_SESSION[SLAPTAS]['username'] ) ? '<input type="text" name="name" class="submit" value="' . $vardas . '"/>' : '' ) . "
                   <textarea onkeypress=\"return imposeMaxLength(event, this, 300);\" name=\"chat_msg\" rows=\"3\" cols=\"10\" class=\"input\" style=\"margin-bottom:5px;\"></textarea>
                     <script>
		               function imposeMaxLength(Event, Object, MaxLen){
                         return (Object.value.length <= MaxLen)||(Event.keyCode == 8 ||Event.keyCode==46||(Event.keyCode>=35&&Event.keyCode<=40))
                       }
		            </script>
                   <input type=\"submit\" name=\"chat_box\" class=\"submit\" value=\"{$lang['sb']['send']}\" />
                </form>";
} else {
	$chat_box = $lang['system']['pleaselogin'];
}
$chat_box .= "<div class='line'></div>";
$extras = "";
//usklausa irasam, priklausomai nuo to, ar sveciams galima rasyt.
if ( isset( $conf['kmomentarai_sveciams'] ) && $conf['kmomentarai_sveciams'] == 1 ) {
	$chat = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "chat_box` ORDER BY `time` DESC LIMIT " . escape( (int)$conf['Chat_limit'] ) );
} else {
	$chat = mysql_query1( "SELECT
		`" . LENTELES_PRIESAGA . "chat_box`.*,
		`" . LENTELES_PRIESAGA . "users`.`nick`,
		`" . LENTELES_PRIESAGA . "users`.`levelis`
		FROM `" . LENTELES_PRIESAGA . "chat_box`
		Inner Join `" . LENTELES_PRIESAGA . "users`
		ON `" . LENTELES_PRIESAGA . "chat_box`.`niko_id` = `" . LENTELES_PRIESAGA . "users`.`id`
		ORDER BY `time` DESC LIMIT " . escape( (int)$conf['Chat_limit'] )
	);
}
$i = 0;
//irasu sarasas
if ( sizeof( $chat ) > 0 ) {
	foreach ( $chat as $row ) {
		$i++;
		$tr = $i % 2 ? '2' : '';
		if ( ar_admin( 'com' ) && puslapis( 'deze.php' ) ) {
			$extras = "<span style='display: none;' id='irankiai{$i}'>
			<a style=\"float: right;\" title=\"{$lang['admin']['delete']}\" href=\"" . url( "?id,{$conf['puslapiai']['deze.php']['id']};d,{$row['id']}" ) . "\" onclick=\"return confirm('{$lang['system']['delete_confirm']}')\"><img height=\"12\" src=\"images/icons/cross.png\" alt=\"[d]\" class=\"middle\" border=\"0\" /></a>
			<a style=\"float: right;\" title=\"{$lang['admin']['edit']}\" href=\"" . url( "?id,{$conf['puslapiai']['deze.php']['id']};r,{$row['id']}" ) . "\"><img height=\"12\" src=\"images/icons/pencil.png\" alt=\"[r]\" class=\"middle\" border=\"0\" /></a>
			</span>
			";
		}
		$chat_box .= "<div class=\"tr{$tr}\" onmouseover=\"document.getElementById('irankiai{$i}').style.display = '';\" onMouseOut=\"document.getElementById('irankiai{$i}').style.display = 'none';\"><b>" . user( $row['nikas'], $row['niko_id'] ) . "</b><font style='font-size:9px;'><em>(" . $row['time'] . ")</em></font>{$extras}
		<br />" . smile( bbchat( wrap( $row['msg'], 18 ) ) ) . "<br />
		
		</div>";
	}
} else {
	$chat_box .= "";
}
//jei archyvo psl ijungtas, rodom nuoroda
if ( puslapis( 'deze.php' ) ) {
	$chat_box .= "<a href=\"" . url( "?id,{$conf['puslapiai']['deze.php']['id']}" ) . "\" >{$lang['sb']['archive']}</a>";
}


$text = $chat_box;
unset( $i );
?>
