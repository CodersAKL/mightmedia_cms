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
if ( !defined( "OK" ) ) {
	header( "location: ?" );
}
function komentarai( $id, $hide = FALSE ) {

	global $url, $page, $lang, $conf, $_GET;
	//tikrinam ar komentarai neišjungti
	if ( $conf['kmomentarai_sveciams'] != 3 ) {
		if ( isset( $url['id'] ) && $id > 0 ) {
			if ( isset( $_SESSION[SLAPTAS]['id'] ) || ( isset( $conf['kmomentarai_sveciams'] ) && $conf['kmomentarai_sveciams'] == 1 ) ) {
				include_once ( "priedai/class.php" );
				$bla  = new forma();
				$form = array(
					"Form"                     => array( "action" => "", "method" => "post", "name" => "n_kom" ),
					$lang['guestbook']['name'] => (
						!isset( $_SESSION[SLAPTAS]['username'] )
							? array(
								"type" => "text",
								"value" => (
									isset( $_COOKIE['komentatorius'] )
										? $_COOKIE['komentatorius']
										: ""
								),
								"name" => "name"
							)
							: array(
							"type" => "string",
							"value" => "<b>" . $_SESSION[SLAPTAS]['username'] . "</b>"
						)
					),
					"  \r\r\r\r\r"                               => array( "type" => "string", "value" => bbs( 'n_kom' ) ),
					$lang['guestbook']['message']                => array( "type" => "textarea", "value" => "", "class" => "input", "name" => "n_kom", "extra" => "rows=\"5\" cols=\"3\"" ),
					( !isset( $_SESSION[SLAPTAS]['id'] ) ? kodas() : "" ) => ( !isset( $_SESSION[SLAPTAS]['id'] ) ? array( "type" => "text", "value" => "", "name" => "code", "class" => "chapter" ) : "" ),
					" "                                          => array( "type" => "submit", "name" => "Naujas", "value" => $lang['comments']['send'] ),
					"  "                                         => array( "type" => "hidden", "value" => $id, "name" => "id" )
				);


				lentele( $lang['comments']['write'], $bla->form( $form ) );
			} else {
				lentele( $lang['comments']['write'], $lang['system']['pleaselogin'] );
			}

			$sql = mysql_query1( "SELECT k.*, u.email AS email, u.levelis AS levelis	FROM " . LENTELES_PRIESAGA . "kom AS k LEFT JOIN " . LENTELES_PRIESAGA . "users AS u ON k.nick_id = u.id WHERE k.kid = " . escape( (int)$id ) . " AND k.pid = " . escape( $page ) . " ORDER BY k.data DESC LIMIT 50", 3600 );

			$text = '';
			$tr   = '';
			$i    = 0;
			foreach ( $sql as $row ) {
				$i++;
				$tr = $i % 2 ? '2' : '';
				$text .= "<div class=\"tr{$tr}\"><em><a href=\"" . $_SERVER['REQUEST_URI'] . "#k:" . $row['id'] . "\" id=\"k:" . $row['id'] . "\"> <img src=\"images/icons/bullet_black.png\" alt=\"#\" class=\"middle\" border=\"0\" /> </a> ";
				if ( ar_admin( 'com' ) ) {
					$text .= "<a style=\"float: right;\" href='" . url( "dk," . $row['id'] . "" ) . "' onclick=\"return confirm('{$lang['system']['delete_confirm']}') \"><img height=\"15\" src='images/icons/cross.png' class='middle' alt='" . $lang['faq']['delete'] . "' border='0' title='" . $lang['admin']['delete'] . "' /></a> ";
				}
				if ( $row['nick_id'] == 0 ) {
					$duom = @unserialize( $row['nick'] );
					$nick = user( $duom[0], $row['nick_id'] ) . ( $_SESSION[SLAPTAS]['level'] == 1 ? " (" . $duom[1] . ")" : "" );
				} else {
					$nick = user( $row['nick'], $row['nick_id'] );
				}
				$text .= $nick;
				$text .= " (" . date( 'Y-m-d H:i:s', $row['data'] ) . ") " . naujas( $row['data'], $row['nick'] ) . "</em><br />
			    <div class=\"avataras\" align=\"left\">" . avatar( $row['email'], 40 ) . "</div>" . smile( bbchat( wrap( input( $row['zinute'] ), 80 ) ) ) . "</div>";
			}
			if ( !empty( $text ) ) {
				lentele( $lang['comments']['comments'], $text );
			}
		}


		//Irasom nauja komentara jei nurodytas puslapis, gal perdidele salyga bet saugumo sumetimais :)
		if ( isset( $_POST['n_kom'] ) && !empty( $_POST['n_kom'] ) && !empty( $_POST['Naujas'] ) && $_POST['Naujas'] == $lang['comments']['send'] && isset( $_POST['id'] ) && !empty( $_POST['id'] ) && ( isset( $_SESSION[SLAPTAS]['id'] ) || $conf['kmomentarai_sveciams'] == 1 ) ) {
			if ( ( isset( $_POST['code'] ) && strtoupper( $_POST['code'] ) == $_SESSION[SLAPTAS]['code'] ) || isset( $_SESSION[SLAPTAS]['id'] ) ) {
				if ( isset( $_SESSION[SLAPTAS]['id'] ) ) {
					mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "users` SET taskai=taskai+1 WHERE nick=" . escape( $_SESSION[SLAPTAS]['username'] ) . " AND `id` = " . escape( $_SESSION[SLAPTAS]['id'] ) . "" );
				} else if ( !isset( $_COOKIE['komentatorius'] ) || $_POST['name'] != $_COOKIE['komentatorius'] ) {
					setcookie( "komentatorius", input( $_POST['name'] ), time() + 60 * 60 * 24 * 30 );
				}

				$nick_id = ( isset( $_SESSION[SLAPTAS]['id'] ) ? $_SESSION[SLAPTAS]['id'] : 0 );
				$nick    = ( isset( $_SESSION[SLAPTAS]['username'] ) ? $_SESSION[SLAPTAS]['username'] : ( !empty( $_POST['name'] ) ? serialize( array( trimlink( strip_tags( $_POST['name'] ), 9 ), getip() ) ) : serialize( array( $lang['system']['guest'], getip() ) ) ) );
				mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "kom` (`kid`, `pid`, `zinute`, `nick`, `nick_id`, `data`) VALUES (" . escape( $_POST['id'] ) . ", " . escape( $page ) . ", " . escape( $_POST['n_kom'] ) . ", " . escape( $nick ) . ", " . escape( $nick_id ) . ", '" . time() . "')" );
				delete_cache( "SELECT k.*, u.email AS email, u.levelis AS levelis	FROM " . LENTELES_PRIESAGA . "kom AS k LEFT JOIN " . LENTELES_PRIESAGA . "users AS u ON k.nick_id = u.id WHERE k.kid = " . escape( (int)$_POST['id'] ) . " AND k.pid = " . escape( $page ) . " ORDER BY k.data DESC LIMIT 50" );
				//unset($_POST['Naujas']);
				header( "location: " . $_SERVER['HTTP_REFERER'] );
			} else {
				klaida( $lang['system']['error'], $lang['reg']['wrongcode'] );
			}
		}

		// Trinam komentara
		if ( isset( $url['dk'] ) && isset( $url['id'] ) && ar_admin( 'com' ) ) {
			$id  = (int)$url['dk'];
			$sql = mysql_query1( "SELECT nick, nick_id FROM `" . LENTELES_PRIESAGA . "kom` WHERE id=" . escape( $id ) . " LIMIT 1" );
			mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "users` SET taskai=taskai-1 WHERE nick=" . escape( $sql['nick'] ) . " AND `id` = " . escape( $sql['nick_id'] ) . "" );
			mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE id=" . escape( $id ) . " LIMIT 1" );
			delete_cache( "SELECT k.*, u.email AS email, u.levelis AS levelis	FROM " . LENTELES_PRIESAGA . "kom AS k LEFT JOIN " . LENTELES_PRIESAGA . "users AS u ON k.nick_id = u.id WHERE k.kid = " . escape( (int)$url['k'] ) . " AND k.pid = " . escape( $page ) . " ORDER BY k.data DESC LIMIT 50" );
			unset( $id );
			header( "location: " . $_SERVER['HTTP_REFERER'] . "" );
		}
	}
}

?>