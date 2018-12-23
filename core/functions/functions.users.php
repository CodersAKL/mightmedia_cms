<?php

/**
 * Grąžina vartotojo avatarą
 *
 * @param $mail string emeilas
 * @param $size int
 *
 * @return string formated html
 */
if(! function_exists('avatar')) {
	function avatar($mail, $size = 80) {
		global $conf;
		if ( file_exists( ROOT . 'content/uploads/avatars/' . md5( $mail ) . '.jpeg' ) ) {
			$result = '<img src="' . ROOT . 'content/uploads/avatars/' . md5( $mail ) . '.jpeg?' . time() . '" width="' . $size . '" height="' . $size . '" alt="avataras" />';
		} else {
			$avatardir = (
			file_exists( ROOT . 'stiliai/' . $conf['Stilius'] . '/no_avatar.png' )
				? adresas() . 'stiliai/' . $conf['Stilius'] . '/no_avatar.png'
				: adresas() . 'content/uploads/avatars/no_avatar.png'
			);
			$avatarUrl = 'https://www.gravatar.com/avatar/' . md5(strtolower($mail)) . '?s=' . $size . '&r=g&d=' . $avatardir . '&time=' . time();
			$result    = '<img src="' . $avatarUrl . '"  width="' . $size . '" alt="avatar" />';
		}

		return $result;
	}
}

/**
 * Patikrina ar vartotojas turi "admin" teises.
 * grąžina true arba false
 *
 * @param  string $failas
 *
 * @global array  $_SESSION
 * @return bool <type>
 */
if(! function_exists('ar_admin')) {
	function ar_admin($failas) {

		global $_SESSION;

		if ( ( is_array( unserialize( $_SESSION[SLAPTAS]['mod'] ) ) && in_array( $failas, unserialize( $_SESSION[SLAPTAS]['mod'] ) ) ) || $_SESSION[SLAPTAS]['level'] == 1 ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}

/**
 * Gražina true arba false (nustatom vartotojo teises)
 *
 * @param array $mas serialize
 * @param int   $lvl
 *
 * @return true/false
 */
if(! function_exists('teises')) {
	function teises($mas, $lvl) {

		if ( !empty( $mas ) && !is_array( $mas ) ) {
			$mas = @unserialize( $mas );
		}
		if ( $lvl == 1 || ( is_array( $mas ) && in_array( $lvl, $mas ) ) || empty( $mas ) ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}

if(! function_exists('getUserMail')) {
	function getUserMail($id) {
		$sql = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "users` WHERE `id`=" . escape($id) . " LIMIT 1", 86400 );
	
		return $sql;
	}
}

/**
 * Vartotojui atvaizduoti
 *
 * @param string $user    nickas
 * @param int    $id
 * @param int    $level   levelis
 * @param bool   $extra
 *
 * @return string
 */
if(! function_exists('user')) {
	function user( $user, $id = 0, $level = 0, $extra = FALSE ) {

		global $lang, $conf;
		if ( $user == '' || $user == $lang['system']['guest'] ) {

			return $lang['system']['guest'];
		} else {
			if ( isset( $conf['pages']['pm.php'] ) && $id != 0 && isset( $_SESSION[SLAPTAS]['id'] ) && $id != $_SESSION[SLAPTAS]['id'] ) {
				$pm = "<a href=\"" . url( "?id," . $conf['pages']['pm.php']['id'] . ";n,1;u," . str_replace( "=", "", base64_encode( $user ) ) ) . "\"><img src=\"" . ROOT . "core/assets/images/pm/mail.png\"  style=\"vertical-align:middle\" alt=\"pm\" border=\"0\" /></a>";
			} else {
				$pm = '';
			}
			if ( isset( $conf['level'][$level]['pav'] ) ) {
				$img = '<img src="' . ROOT . 'core/assets/images/icons/' . $conf['level'][$level]['pav'] . '" border="0" class="middle" alt="" /> ';
			} else {
				$img = '';
			}
			if ( isset( $conf['pages']['view_user.php']['id'] ) && $id != 0 ) {
				return $img . '<a href="' . url( '?id,' . $conf['pages']['view_user.php']['id'] . ';' . $user ) . '" title="' . input( $user ) . " " . $extra . '">' . trimlink( $user, 10 ) . '</a> ' . $pm;
			} else {
				return '<div style="display:inline;" title="' . input( $user ) . '" "' . $extra . '">' . $img . ' ' . trimlink( $user, 10 ) . ' ' . $pm . '</div>';
			}
		}
	}
}

if(! function_exists('get_user_os')) {
	function get_user_os() {

		global $global_info, $HTTP_USER_AGENT, $HTTP_SERVER_VARS;
		if ( !empty( $global_info['user_os'] ) ) {
			return $global_info['user_os'];
		}
		if ( !empty( $HTTP_SERVER_VARS['HTTP_USER_AGENT'] ) ) {
			$HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];
		} elseif ( getenv( "HTTP_USER_AGENT" ) ) {
			$HTTP_USER_AGENT = getenv( "HTTP_USER_AGENT" );
		} elseif ( empty( $HTTP_USER_AGENT ) ) {
			$HTTP_USER_AGENT = "";
		}
		if ( strpos( $HTTP_USER_AGENT, 'Windows' ) ) {
			$global_info['user_os'] = "WIN";
		} elseif ( strpos( $HTTP_USER_AGENT, 'Macintosh' ) ) {
			$global_info['user_os'] = "MAC";
		} elseif ( strpos( $HTTP_USER_AGENT, "Linux" ) ) {
			$global_info['user_os'] = "Linux";
		} else {
			$global_info['user_os'] = "OTHER";
		}

		return $global_info['user_os'];
	}
}
