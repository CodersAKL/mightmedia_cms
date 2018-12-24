<?php

/**
 * Adresa verčiam į masyvą
 *
 * @param string $params
 *
 * @return array
 */
if(! function_exists('url_arr')) {
	function url_arr($params) {

		global $conf;

		$str2 = array();
		
		if ( !isset( $params ) ) {
			$params = $_SERVER['QUERY_STRING'];
		}

		if ( strrchr( $params, '&' ) ) {
			$params = explode( "&", $params );
		} //Jeigu tai paprastas GET
		else {
			$params = explode( ( ( empty( $conf['F_urls'] ) || $conf['F_urls'] == '0' ) ? ';' : $conf['F_urls'] ), $params );
		}

		if ( isset( $params ) && is_array( $params ) && count( $params ) > 0 ) {
			foreach ( $params as $key => $value ) {
				if ( strrchr( $value, '=' ) ) {
					$str1 = explode( "=", $value );
				} else {
					$str1 = explode( ",", $value );
				}
				if ( isset( $str1[1] ) ) {
					if ( preg_match( '%/\*\*/|SERVER|SELECT|UNION|DELETE|UPDATE|INSERT%i', $str1[1] ) ) {
						echo "BAN";
						ban();
					}
					$str2[$str1[0]] = $str1[1];
				}
			}
		}

		return $str2;
	}
}


/**
 * Svetainės adresui gauti
 *
 * @return string
 */
if(! function_exists('adresas')) {
	function adresas() {
		//TODO: problems with admin CP
		// if(defined('MAIN_URL')) {
		// 	return MAIN_URL;
		// }
		
		if ( isset( $_SERVER['HTTP_HOST'] ) ) {
			$adresas = isset( $_SERVER['HTTPS'] ) && strtolower( $_SERVER['HTTPS'] ) !== 'off' ? 'https' : 'http';
			$adresas .= '://' . $_SERVER['HTTP_HOST'];
			$adresas .= str_replace( basename( $_SERVER['SCRIPT_NAME'] ), '', $_SERVER['SCRIPT_NAME'] );
		} else {
			$adresas = 'http://localhost/';
		}

		return $adresas;
	}
}

/**
 * Adreso apsauga
 *
 * @param string $url
 *
 * @return string
 */
if(! function_exists('cleanurl')) {
	function cleanurl($url) {

		$bad_entities  = array( '"', "'", "<", ">", "(", ")", '\\' );
		$safe_entities = array( "", "", "", "", "", "", "" );
		$url           = str_replace( $bad_entities, $safe_entities, $url );

		return $url;
	}
}

/**
 * "Friendly urls" apdorojimas
 *
 * @param $str
 *
 * @return string
 */
if(! function_exists('url')) {
	function url( $str ) {

		global $conf;

		if ( substr( $str, 0, 1 ) == '?' ) {
			$linkai    = explode( ';', $str );
			$start     = explode( ',', $linkai[0] );
			$linkai[0] = '';
			if ( !empty( $conf['F_urls'] ) && $conf['F_urls'] != '0' ) {

				// Žodinis linkas
				$url_title = !empty( $conf['titles'][$start[1]] ) ? $conf['titles'][$start[1]] : '';

				// Išmetam tarpus
				$url_title = str_replace( ' ', '_', $url_title );

				// Atskiriam atskirus getus pasirinktu simboliu
				$return = adresas() . ROOT . $url_title . implode( ( $conf['F_urls'] != '0' ? $conf['F_urls'] : ';' ), $linkai );
			} else {

				$return = adresas() . ( substr( $str, 4, 3 ) == '999' && ( empty( $conf['F_urls'] ) || $conf['F_urls'] == '0' ) ? 'main.php' : ( substr( $str, 0, 1 ) != '?' ? '' : ROOT ) ) . $str;
			}
		} else {

			$g = '?';
			foreach ( $_GET as $k => $v ) {

				$g .= "{$k},{$v};";
			}
			$return = url( $g . $str );
		}

		return $return;
	}
}

/**
 * Seo url TODO
 */
if(! function_exists('seo_url')) {
	function seo_url( $url, $id ) {

		// Sušveplinam
		$url = iconv( 'UTF-8', 'US-ASCII//TRANSLIT', $url );
		// Nuimam tarpus pradžioje bei pabaigoje
		$url = trim( $url );
		// Neaiškius simbolius pakeičiam brūkšniukais
		$url = preg_replace( '/[^A-z0-9-]/', '_', $url );
		// Išvalom besikartojančius brūkšniukus
		$url = preg_replace( '/-+/', "-", $url );
		// Verčiam viską į mažasias raides
		$url = strtolower( $url );

		return $url . $id;
	}
}
/**
 * Naršyklių peradresavimas
 *
 * @param string $location
 * @param string $type
 * @param array $sessions | null
 */
if(! function_exists('redirect')) {
	function redirect($location, $type = "header", $sessions = null) {

		if(! empty($sessions)) {
			setSession('redirect', $sessions);
		}

		if ( $type == "header" ) {
			header( "Location: " . $location );
			exit;
		} elseif ( $type == "meta" ) {
			echo "<meta http-equiv='Refresh' content='1;url=$location'>";
		} else {
			echo "<script type='text/javascript'>document.location.href='" . $location . "'</script>\n";
		}
	}
}

//sutvarko url iki linko
if(! function_exists('linkas')) {
	function linkas( $str ) {
		$str = strtolower( strip_tags( $str ) );

		//return preg_replace_callback("#([\n ])([a-z]+?)://([a-z0-9\-\.,\?!%\*_\#:;~\\&$@\/=\+]+)#si", "linkai", $str);
		return preg_replace( "`((http)+(s)?:(//)|(www\.))((\w|\.|\-|_)+)(/)?(\S+)?`i", "<a href=\"\\0\" title=\"\\0\" target=\"_blank\" class=\"link\" >\\5\\6</a>", $str );
	}
}

/**
 * Nuorodų tikrinimas
 *
 * @example if (checkUrl('http://delfi.lt')) echo 'ok'; else echo 'no';
 *
 * @param string $url
 *
 * @return true/false
 */
if(! function_exists('checkUrl')) {
	function checkUrl( $url ) {

		if ( $data = @get_headers( $url ) ) {
			preg_match( '/^HTTP\/1\.[01] (\d\d\d)/', implode( '', $data ), $matches );
			if ( $matches[1] == 200 ) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
}
