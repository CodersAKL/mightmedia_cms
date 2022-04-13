<?php

/**
 * Tikrina ar kintamasis teigiamas skaičius
 * TODO: deprecate and remove
 *
 * @param int $value
 *
 * @return int 1 arba NULL
 */
if(! function_exists('isNum')) {
	function isNum( $value ) {

		// return @preg_match( "/^[0-9]+$/", $value ); //}

		return is_numeric($value);
	}
}

/**
 * Sutvarkom tekstą saugiam atvaizdavimui
 * šito reikia jei nori gražinti į input'ą informaciją.
 * dažnai tai būna su visokiais \\\'? ir pan
 *
 * @param string $s
 *
 * @return string formated
 */
if(! function_exists('input')) {
	function input( $s ) {

		$s = htmlspecialchars( $s, ENT_QUOTES, "UTF-8" );

		return $s;
	}
}

/**
 * Slaptažodžio kodavimas
 *
 * @param $pass
 *
 * @return string
 */
if(! function_exists('koduoju')) {
	function koduoju( $pass ) {

		return md5( sha1( md5( $pass ) ) );
	}
}

/**
 * Sutvarkom failo pavadinimą
 *
 * @param string $name
 *
 * @return string formated
 */
if(! function_exists('nice_name')) {
	function nice_name($name) {

		$name = ucfirst_utf8( $name );
		$name = basename( $name, '.php' );
		$name = str_replace( "_", " ", $name );

		return $name;
	}
}

/**
 * Pirma raidė didžioji (utf-8)
 *
 * @param string $str
 *
 * @return string
 */
if(! function_exists('ucfirst_utf8')) {
	function ucfirst_utf8($str) {

		if ( mb_check_encoding( $str, 'UTF-8' ) ) {
			$first = mb_substr( mb_strtoupper( $str, "utf-8" ), 0, 1, 'utf-8' );

			return $first . mb_substr( mb_strtolower( $str, "utf-8" ), 1, mb_strlen( $str ), 'utf-8' );
		} else {
			return $str;
		}
	}
}

/**
 * Sutrumpina stringa iki nurodyto ilgio (saugiai utf-8)
 *
 * @param string $str
 * @param int    $start ilgis
 *
 * @return string
 */
if(! function_exists('utf8_substr')) {
	function utf8_substr( $str, $start ) {

		preg_match_all( "/./u", $str, $ar );

		if ( func_num_args() >= 3 ) {
			$end = func_get_arg( 2 );

			return join( "", array_slice( $ar[0], $start, $end ) );
		} else {
			return join( "", array_slice( $ar[0], $start ) );
		}
	}
}
/**
 * Užkoduoja problematiškus simbolius
 *
 * @param      $text
 * @param bool $striptags
 *
 * @return mixed
 * @url http://blog.bitflux.ch/wiki/
 */
if(! function_exists('descript')) {
	function descript( $text, $striptags = TRUE ) {

		$search   = array( "40", "41", "58", "65", "66", "67", "68", "69", "70", "71", "72", "73", "74", "75", "76", "77", "78", "79", "80", "81", "82", "83", "84", "85", "86", "87", "88", "89", "90", "97", "98", "99", "100", "101", "102", "103", "104", "105", "106", "107", "108", "109", "110", "111", "112", "113", "114", "115", "116", "117", "118", "119", "120", "121", "122", "239" );
		$replace  = array( "(", ")", ":", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "" );
		$entities = count( $search );
		for ( $i = 0; $i < $entities; $i++ ) {
			$text = preg_replace( "#(&\#)(0*" . $search[$i] . "+);*#si", $replace[$i], $text );
		}
		$text = str_replace( chr( 32 ) . chr( 32 ), "&nbsp;", $text );
		$text = str_replace( chr( 9 ), "&nbsp; &nbsp; &nbsp; &nbsp;", $text );
		// the following is based on code from bitflux (http://blog.bitflux.ch/wiki/)
		// Kill hexadecimal characters completely
		$text = preg_replace( '#(&\#x)([0-9A-F]+);*#si', "", $text );
		// remove any attribute starting with "on" or xmlns
		$text = preg_replace( '#(<[^>]+[\\"\'\s])(onmouseover|onmousedown|onmouseup|onmouseout|onmousemove|onclick|ondblclick|onload|xmlns)[^>]*>#iU', ">", $text );
		// remove javascript: and vbscript: protocol
		$text = preg_replace( '#([a-z]*)=([\`\'\"]*)script:#iU', '$1=$2nojscript...', $text );
		$text = preg_replace( '#([a-z]*)=([\`\'\"]*)javascript:#iU', '$1=$2nojavascript...', $text );
		$text = preg_replace( '#([a-z]*)=([\'\"]*)vbscript:#iU', '$1=$2novbscript...', $text );
		//<span style="width: expression(alert('Ping!'));"></span> (only affects ie...)
		$text = preg_replace( '#(<[^>]+)style=([\`\'\"]*).*expression\([^>]*>#iU', "$1>", $text );
		$text = preg_replace( '#(<[^>]+)style=([\`\'\"]*).*behaviour\([^>]*>#iU', "$1>", $text );
		if ( $striptags ) {
			do {
				$thistext = $text;
				$text     = preg_replace( '#</*(applet|meta|xml|blink|link|style|script|iframe|frame|frameset|ilayer|layer|bgsound|title|base)[^>]*>#i', "", $text );
			} while ( $thistext != $text );
		}

		return $text;
	}
}

/**
 * Sulaužo žodį jei jis per ilgas
 * laužo net jei žodis turi tarpus
 *
 * @param string  $text tekstas
 * @param int     $chars ilgis
 *
 * @return string
 */
if(! function_exists('wrap1')) {
	function wrap1( $text, $chars = 25 ) {

		$text = wordwrap( $text, $chars, "<br />\n", 1 );

		return $text;
	}
}

/**
 * Sulaužo per ilgus žodžius
 * tik jei jis yra be tarpų
 *
 * @param string  $string tekstas
 * @param int     $width ilgis
 * @param string  $break simbolis
 *
 * @return string
 */
if(! function_exists('wrap')) {
	function wrap( $string, $width, $break = "\n" ) {

		//Jei tvs be javascript naudosi, atkomentuok
		//$string = preg_replace('/([^\s]{' . $width . '})/i', "$1$break", $string);
		return $string;
	}
}

//tikrinam ar kintamasis sveikas skaicius ar normalus zodis
if(! function_exists('tikrinam')) {
	function tikrinam( $txt ) {

		return ( preg_match( "/^[0-9a-zA-Z]+$/", $txt ) );
	}
}

/**
 * Trim a line of text to a preferred length
 *
 * @param $text
 * @param $length
 *
 * @return mixed
 */
if(! function_exists('trimlink')) {
	function trimlink( $text, $length ) {

		$dec  = array( "\"", "'", "\\", '\"', "\'", "<", ">" );
		$enc  = array( "&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;" );
		$text = str_replace( $enc, $dec, $text );
		if ( strlen( strip_tags( $text ) ) > $length ) {
			$text = utf8_substr( $text, 0, ( $length - 3 ) ) . "...";
		}
		$text = str_replace( $dec, $enc, $text );

		return $text;
	}
}

/**
 * El pašto validacija
 *
 * @param $email
 *
 * @return bool
 */
if(! function_exists('check_email')) {
	function check_email( $email ) {

		return preg_match( "/^([_a-zA-Z0-9-+]+)(\.[_a-zA-Z0-9-]+)*@([a-zA-Z0-9-]+)(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,6})$/", $email ) ? TRUE : FALSE;
	}
}

if(! function_exists('random')) {
	function random( $return = '' ) {

		$simboliai = "abcdefghijkmnopqrstuvwxyz0123456789";
		for ( $i = 1; $i < 3; ++$i ) {
			$num = rand() % 33;
			$return .= substr( $simboliai, $num, 1 );
		}

		return $return . '_';
	}
}

/**
 * Atsitiktinės frazės generatorius
 *
 * @param int $i
 *
 * @return string
 */
if(! function_exists('random_name')) {
	function random_name( $i = 10 ) {

		$chars = "abcdefghijkmnopqrstuvwxyz023456789ABCDEFGHJKLMNOPQRSTUVWXYZ";
		srand( (double)microtime() * 1000000 );
		$name = '';

		while ( $i > 0 ) {
			$num  = rand() % strlen($chars);
			$tmp  = substr( $chars, $num, 1 );
			$name = $name . $tmp;
			$i--;
		}

		return $name;
	}
}
