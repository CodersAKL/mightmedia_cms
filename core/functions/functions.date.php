<?php


/**
 * Grąžina amžių, nurodžius datą
 *
 * @param string $data
 *
 * @return int
 */
if(! function_exists('amzius')) {
	function amzius( $data ) {

		if ( !isset( $data ) || $data == '' || $data == '0000-00-00' ) {
			return "- ";
		} else {
			$data   = explode( "-", $data );
			$amzius = time() - mktime( 0, 0, 0, $data['1'], $data['2'], $data['0'] );
			$amzius = date( "Y", $amzius ) - 1970;

			return $amzius;
		}
	}
}

//bano galiojimo laikas. Rodo data iki kada +30 dienu
//echo "Galioja iki: ".galioja('12', '19', '2007');
//grazina: Galioja iki: 2008-01-17
if(! function_exists('galioja')) {
	function galioja( $menuo, $diena, $metai, $kiek_galioja = 30 ) {

		$nuo  = (int)( mktime( 0, 0, 0, $menuo, $diena, $metai ) - time( void ) / 86400 );
		$liko = $nuo + ( $kiek_galioja * 24 * 60 * 60 );

		return date( 'Y-m-d', $liko );
	}
}

if(! function_exists('liko')) {
	function liko( $diena, $menuo, $metai ) {

		$until      = mktime( 0, 0, 0, $menuo, $diena, $metai );
		$now        = time();
		$difference = $until - $now;
		$days       = floor( $difference / 86400 );
		$difference = $difference - ( $days * 86400 );
		$hours      = floor( $difference / 3600 );
		$difference = $difference - ( $hours * 3600 );
		$minutes    = floor( $difference / 60 );
		$difference = $difference - ( $minutes * 60 );
		$seconds    = $difference;

		return (int)$days + 1;
	}
}

/**
 * Grąžina paveiksliuką "new" jei elementas naujas
 *
 * @param      $data
 * @param null $nick
 *
 * @return string
 */
if(! function_exists('naujas')) {
	function naujas( $data, $nick = NULL ) {

		global $lang;
		if ( isset( $_SESSION[SLAPTAS]['lankesi'] ) ) {
			return ( ( $data > $_SESSION[SLAPTAS]['lankesi'] ) ? '<img src="' . ROOT . 'images/icons/new.png" onload="$(this).fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);" alt="New" border="0" style="vertical-align: middle;" title="' . $lang['system']['new'] . '" />' : '' );
		} else {
			return '';
		}
	}
}
/**
 * Gražina išsireiškimą nusakantį įvykio laiką
 *
 * @param $ts string
 *
 * @return string
 */
if(! function_exists('kada')) {
	function kada( $ts ) {

		global $lang;
		if ( $ts == '' || $ts == "0000-00-00 00:00:00" ) {
			return '';
		}
		$mins  = floor( ( strtotime( date( "Y-m-d H:i:s" ) ) - strtotime( $ts ) ) / 60 );
		$hours = floor( $mins / 60 );
		$mins -= $hours * 60;
		$days = floor( $hours / 24 );
		$hours -= $days * 24;
		$weeks = floor( $days / 7 );
		$days -= $weeks * 7;
		$month = floor( $weeks / 4 );
		$days -= $month * 4;
		$year = floor( $month / 12 );
		$days -= $year * 12;
		if ( $year ) {
			return ( $year > 1 ? sprintf( $lang['system']['years'], $year ) : sprintf( $lang['system']['year'], $year ) );
		}
		if ( $month ) {
			return ( $month > 1 ? sprintf( $lang['system']['months'], $month ) : sprintf( $lang['system']['month'], $month ) );
		}
		if ( $weeks ) {
			return ( $weeks > 1 ? sprintf( $lang['system']['weeks'], $weeks ) : sprintf( $lang['system']['week'], $weeks ) );
		}
		if ( $days ) {
			return ( $days > 1 ? sprintf( $lang['system']['days'], $days ) : sprintf( $lang['system']['day'], $days ) );
		}
		if ( $hours ) {
			return ( $hours > 1 ? sprintf( $lang['system']['hours'], $hours ) : sprintf( $lang['system']['hour'], $hours ) );
		}
		if ( $mins ) {
			return ( $mins > 1 ? sprintf( $lang['system']['minutes'], $mins ) : sprintf( $lang['system']['minute'], $mins ) );
		}

		//return "&lt; 1 {$lang['system']['minute']} {$lang['system']['ago']}";
		return sprintf( $lang['system']['minute'], '&lt; 1' );
	}
}

/**
 * Sulietuvinimas mėnesio
 * echo menesis(12); //Gruodis
 *
 * @param INT $men
 *
 * @return string
 */
if(! function_exists('menesis')) {
	function menesis( $men ) {

		if ( is_int( $men ) ) {
			$ieskom = array( "12", "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11" );
		} else {
			$ieskom = array(
				"December",
				"January",
				"February",
				"March",
				"April",
				"May",
				"June",
				"July",
				"August",
				"September",
				"October",
				"November"
			);
		}
		$keiciam = array(
			"Gruodis",
			"Sausis",
			"Vasaris",
			"Kovas",
			"Balandis",
			"Gegužė",
			"Birželis",
			"Liepa",
			"Rugpjūtis",
			"Rugsėjis",
			"Spalis",
			"Lapkritis"
		);

		return str_replace( $ieskom, $keiciam, $men );
	}
}
