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

include_once ( "rating.php" );

$text  = "";
$limit = $conf['fotoperpsl'];

if ( isset( $url['p'] ) && isnum( $url['p'] ) && $url['p'] > 0 ) {
	$p = escape( ceil( (int)$url['p'] ) );
} else {
	$p = 0;
}
if ( isset( $url['k'] ) && isnum( $url['k'] ) && $url['k'] > 0 ) {
	$k = escape( ceil( (int)$url['k'] ) );
} else {
	$k = 0;
}
$subs = 0;
//kategorijos
if ( !isset( $url['m'] ) ) {
	$sqlas = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='galerija' AND `lang` = " . escape( lang() ) . " ORDER BY `pavadinimas`", 86400 );
	if ( $sqlas && sizeof( $sqlas ) > 0 ) {
		$info = "";
		foreach ( $sqlas as $sql ) {
			//TODO: skaiciavimas is neriboto gylio.. dabar tik kategorija + sub kategorija skaiciuoja
			if ( $sql['path'] == $k ) {
				// $sqlkiek = kiek('galerija', "WHERE ".($k != 0 ? "`categorija` =" . escape($sql['path']) . " OR" : "")." `categorija` =" . escape($sql['id']) . " AND `rodoma`='TAIP' AND `lang` = ".escape(lang())."");
				$kiek    = mysql_query1( "SELECT count(*) + (SELECT count(*) FROM `" . LENTELES_PRIESAGA . "galerija` WHERE `categorija` IN (SELECT `id` FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `path`=" . escape( $sql['id'] ) . ")) as `kiek` FROM `" . LENTELES_PRIESAGA . "galerija` WHERE `categorija`=" . escape( $sql['id'] ) . " AND `rodoma`='TAIP' AND `lang` = " . escape( lang() ) . " LIMIT 1" );
				$paskut  = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "galerija` WHERE `categorija`=" . escape( $sql['id'] ) . " AND `rodoma`='TAIP' AND `lang` = " . escape( lang() ) . " ORDER BY `data` LIMIT 1" );
				$sqlkiek = $kiek['kiek'];
				$subs++;
				if ( isset( $paskut['file'] ) ) {
					$fotke = "images/galerija/mini/" . input( $paskut['file'] ) . "";
				} else {
					$fotke = "images/naujienu_kat/camera_48.png";
				}

				$info .= "<div class='albumas'>
	                <a href='" . url( "?id," . $url['id'] . ";k," . $sql['id'] . "" ) . "' title='" . input( $sql['pavadinimas'] ) . "'>	" . trimlink( $sql['pavadinimas'], 20 ) . "</a>
	                <a href='" . url( "?id," . $url['id'] . ";k," . $sql['id'] . "" ) . "' title='" . input( $sql['aprasymas'] ) . "'>	<div class='foto' style='background-image :url({$fotke});background-repeat: no-repeat;background-position: center center;'>	</div></a>
		            <small>{$lang['gallery']['photoalbum_img']}: {$sqlkiek} </small>
                </div>";
			}
		}
		$info .= "<div class='clear'></div>";
		//if (isset($info)) {
		lentele( $page_pavadinimas, $info );
		//	}


	}
}

//pabaiga
$visos = kiek( 'galerija', "WHERE `categorija`=" . escape( $k ) . " AND `rodoma` =  'TAIP' AND `lang` = " . escape( lang() ) . "" );
if ( $k > 0 ) {

	$sql = mysql_query1( "SELECT
  `" . LENTELES_PRIESAGA . "grupes`.`pavadinimas` AS `Kategorija`,
    `" . LENTELES_PRIESAGA . "grupes`.`pav` AS `img`,
        `" . LENTELES_PRIESAGA . "grupes`.`teises` AS `teises`,
  `" . LENTELES_PRIESAGA . "galerija`.`pavadinimas`,
  `" . LENTELES_PRIESAGA . "galerija`.`id`,
  `" . LENTELES_PRIESAGA . "galerija`.`apie`,
  `" . LENTELES_PRIESAGA . "galerija`.`data`,
  `" . LENTELES_PRIESAGA . "users`.`nick` AS `Nick`,
  `" . LENTELES_PRIESAGA . "galerija`.`file`
  FROM
  `" . LENTELES_PRIESAGA . "grupes`
  Inner Join `" . LENTELES_PRIESAGA . "galerija` ON `" . LENTELES_PRIESAGA . "grupes`.`id` = `" . LENTELES_PRIESAGA . "galerija`.`categorija`
  Inner Join `" . LENTELES_PRIESAGA . "users` ON `" . LENTELES_PRIESAGA . "galerija`.`autorius` = `" . LENTELES_PRIESAGA . "users`.`id`
  WHERE
   `" . LENTELES_PRIESAGA . "galerija`.`categorija` = " . escape( $k ) . " AND `" . LENTELES_PRIESAGA . "galerija`.`rodoma` =  'TAIP'
	AND `" . LENTELES_PRIESAGA . "galerija`.`lang` = " . escape( lang() ) . "
  ORDER BY
  `" . LENTELES_PRIESAGA . "galerija`.`" . $conf['galorder'] . "` " . $conf['galorder_type'] . "
  LIMIT  $p,$limit", 86400 );
	if ( count( $sql ) == 0 && $subs == 0 ) {
		klaida( $lang['system']['warning'], $lang['system']['no_content'] );
	}

} else {
	$sql = mysql_query1( "SELECT
  `" . LENTELES_PRIESAGA . "galerija`.`pavadinimas`,
  `" . LENTELES_PRIESAGA . "galerija`.`id`,
  `" . LENTELES_PRIESAGA . "galerija`.`apie`,
  `" . LENTELES_PRIESAGA . "galerija`.`data`,
  `" . LENTELES_PRIESAGA . "users`.`nick` AS `Nick`,
  `" . LENTELES_PRIESAGA . "galerija`.`file`
  FROM
  `" . LENTELES_PRIESAGA . "galerija`

  Inner Join `" . LENTELES_PRIESAGA . "users` ON `" . LENTELES_PRIESAGA . "galerija`.`autorius` = `" . LENTELES_PRIESAGA . "users`.`id`
  WHERE
   `" . LENTELES_PRIESAGA . "galerija`.`categorija` =  " . escape( $k ) . " AND `" . LENTELES_PRIESAGA . "galerija`.`rodoma` =  'TAIP'
  ORDER BY
  `" . LENTELES_PRIESAGA . "galerija`.`" . $conf['galorder'] . "` " . $conf['galorder_type'] . "
  LIMIT  $p,$limit", 86400 );

}

if ( empty( $url['m'] ) ) {
	$sqlas = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id`=" . escape( $k ) . " AND `kieno`='galerija' AND `lang` = " . escape( lang() ) . " ORDER BY `pavadinimas` LIMIT 1" );


	if ( teises( $sqlas['teises'], $_SESSION[SLAPTAS]['level'] ) ) {
		$text .= "<table border=\"0\"><tr><td>";

		foreach ( $sql as $row ) {

			if ( isset( $row['Nick'] ) ) {
				$autorius = input( $row['Nick'] );
			} else {
				$autorius = $lang['system']['guest'];
			}

			$text .= "
			<div class=\"gallery img_left\" >
				<a href=\"" . url( "?id," . $conf['puslapiai']['galerija.php']['id'] . ";m," . $row['id'] ) . "\" title=\"" . ( !empty( $row['pavadinimas'] ) ? input( $row['pavadinimas'] ) . "<br />" : '' ) . trimlink( strip_tags( $row['apie'] ), 50 ) . "\">
					<img src=\"images/galerija/mini/" . input( $row['file'] ) . "\" alt=\"\" />
				</a>
				<div class='gallery_title'>
					" . trimlink( ( !empty( $row['pavadinimas'] ) ? input( $row['pavadinimas'] ) : '' ), 15 ) . "
				</div>
			</div>
		";

			$foto = TRUE;
		}
		$text .= '</td>
	</tr>
</table>';

		if ( $k > 0 ) {
			$name = mysql_query1( "SELECT `pavadinimas` FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id`=  " . escape( $k ) . " AND `lang` = " . escape( lang() ) . " LIMIT 1", 86400 );
			$pav  = input( $name['pavadinimas'] );
		} else {
			$pav = $page_pavadinimas;
		}
		if ( isset( $foto ) ) {
			lentele( $pav, ( $visos > $limit ? puslapiai( $p, $limit, $visos, 10 ) : '' ) . $text . ( $visos > $limit ? puslapiai( $p, $limit, $visos, 10 ) : '' ) );
		}
		unset( $row, $text, $sql );
	} else {
		klaida( $lang['system']['warning'], $lang['category']['cant'] );
	}
}
//}else{ klaida("Dėmesio","Jums nesuteiktos teisės Matyti šią kategoriją."); }
if ( !empty( $url['m'] ) ) {
	$sql = "SELECT
  `" . LENTELES_PRIESAGA . "grupes`.`pavadinimas` AS `Kategorija`,
  `" . LENTELES_PRIESAGA . "grupes`.`pav` AS `img`,
  `" . LENTELES_PRIESAGA . "grupes`.`teises` AS `teises`,
  `" . LENTELES_PRIESAGA . "grupes`.`id` AS `kid`,
  `" . LENTELES_PRIESAGA . "galerija`.`pavadinimas`,
  `" . LENTELES_PRIESAGA . "galerija`.`id` AS `nid`,
  `" . LENTELES_PRIESAGA . "galerija`.`apie`,
  `" . LENTELES_PRIESAGA . "galerija`.`data`,
  `" . LENTELES_PRIESAGA . "users`.`nick` AS `Nick`,
  `" . LENTELES_PRIESAGA . "users`.`id` AS `nick_id`,
  `" . LENTELES_PRIESAGA . "users`.`levelis` AS `levelis`,
  `" . LENTELES_PRIESAGA . "galerija`.`file`,
  `" . LENTELES_PRIESAGA . "galerija`.`kom`
  FROM
  `" . LENTELES_PRIESAGA . "grupes`
  Inner Join `" . LENTELES_PRIESAGA . "galerija` ON `" . LENTELES_PRIESAGA . "grupes`.`id` = `" . LENTELES_PRIESAGA . "galerija`.`categorija`
  Inner Join `" . LENTELES_PRIESAGA . "users` ON `" . LENTELES_PRIESAGA . "galerija`.`autorius` = `" . LENTELES_PRIESAGA . "users`.`id`
  WHERE
   `" . LENTELES_PRIESAGA . "galerija`.`id` =  " . escape( $url['m'] ) . " AND `" . LENTELES_PRIESAGA . "galerija`.`rodoma` =  'TAIP'
	AND `" . LENTELES_PRIESAGA . "galerija`.`lang` = " . escape( lang() ) . "
  ORDER BY
  `" . LENTELES_PRIESAGA . "galerija`.`data` DESC
  LIMIT 1";

	$row = mysql_query1( $sql, 86400 );
	if ( empty( $row['file'] ) ) {
		$row           = mysql_query1( "SELECT
		`" . LENTELES_PRIESAGA . "galerija`.`pavadinimas`,
		`" . LENTELES_PRIESAGA . "galerija`.`kom`,
		`" . LENTELES_PRIESAGA . "galerija`.`id` AS `nid`,
		`" . LENTELES_PRIESAGA . "galerija`.`apie`,
		`" . LENTELES_PRIESAGA . "galerija`.`data`,
		`" . LENTELES_PRIESAGA . "users`.`nick` AS `Nick`,
		`" . LENTELES_PRIESAGA . "users`.`id` AS `nick_id`,
		`" . LENTELES_PRIESAGA . "users`.`levelis` AS `levelis`,
		`" . LENTELES_PRIESAGA . "galerija`.`file`
		FROM
		`" . LENTELES_PRIESAGA . "galerija`

		Inner Join `" . LENTELES_PRIESAGA . "users` ON `" . LENTELES_PRIESAGA . "galerija`.`autorius` = `" . LENTELES_PRIESAGA . "users`.`id`
		WHERE
		`" . LENTELES_PRIESAGA . "galerija`.`id` =  " . escape( $url['m'] ) . " AND `" . LENTELES_PRIESAGA . "galerija`.`rodoma` =  'TAIP'
		AND `" . LENTELES_PRIESAGA . "galerija`.`lang` = " . escape( lang() ) . "
		ORDER BY
		`" . LENTELES_PRIESAGA . "galerija`.`data` DESC
		LIMIT 1", 86400 );
		$row['teises'] = 0;
		$row['kid']    = 0;
	}

	if ( !empty( $row['file'] ) && isset( $row['file'] ) ) {
		if ( teises( $row['teises'], $_SESSION[SLAPTAS]['level'] ) || ar_admin( 'galerija.php' ) ) {
			addtotitle( $row['pavadinimas'] );
			$nuoroda2 = mysql_query1(
				"SELECT `id` FROM `" . LENTELES_PRIESAGA . "galerija` WHERE `id` > " . escape( $url['m'] )
				. " AND `categorija`=" . escape( $row['kid'] ) . " AND `lang` = " . escape( lang() )
				. " ORDER BY `id` ASC LIMIT 1", 86400
			);
			$nuoroda  = mysql_query1(
				"SELECT `id` FROM `" . LENTELES_PRIESAGA . "galerija` WHERE `id` < " . escape( $url['m'] )
				. " AND categorija=" . escape( $row['kid'] ) . " AND `lang` = " . escape( lang() )
				. " ORDER BY `id` DESC LIMIT 1", 86400
			);
			if ( isset( $row['Nick'] ) ) {
				$autorius = user( $row['Nick'], $row['nick_id'], $row['levelis'] );
			}
			else {
				$autorius = $lang['system']['guest'];
			}

			$balsavimas = rating_form( $page, $row['nid'] );
			$text .= '<center>';
			if ( !empty( $nuoroda2['id'] ) ) {
				$text .= "<a href=\"" . url( "?id," . $url['id'] . ";m," . $nuoroda2['id'] )
					. "\" >< {$lang['admin']['gallery_prev']}</a>&nbsp;";
			}
			if ( !empty( $nuoroda['id'] ) ) {
				$text .= "<a href=\"" . url( "?id," . $url['id'] . ";m," . $nuoroda['id'] )
					. "\" >{$lang['admin']['gallery_next']} ></a>";
			}
			$text
				.= "</center>
			<div id=\"gallery\" >
	    <center>
	      <a class='fancybox-effects-d' href=\"images/galerija/originalai/" . input( $row['file'] ) . "\" title=\"sss"
				. input( $row['pavadinimas'] ) . ": " . trimlink( strip_tags( $row['apie'] ), 50 ) . "\">
	        <img src=\"images/galerija/" . input( $row['file'] ) . "\" alt=\"\" />
	      </a>
	    </center>
	  </div>
		<br />
		<div style='float:left;'><b>{$lang['system']['rate']}: </b></div>
		<div style='float:left;'>" . $balsavimas . "</div>
		<div style='clear:left;'></div>
		<b>{$lang['admin']['gallery_author']}:</b> " . $autorius . "<br />
		<b>{$lang['admin']['gallery_date']}:</b> " . date( 'Y-m-d H:i:s ', $row['data'] ) . "<br />\n";
			if ( !empty( $row['apie'] ) ) {
				$text .= "<b>{$lang['admin']['gallery_about']}:</b> " . input( $row['apie'] ) . "<br />\n";
			}
			$text .= "<center>";

			if ( !empty( $nuoroda2['id'] ) ) {
				$text .= "<a href=\"" . url( "?id," . $url['id'] . ";m," . $nuoroda2['id'] )
					. "\" >< {$lang['admin']['gallery_prev']}</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			}
			if ( !empty( $nuoroda['id'] ) ) {
				$text .= "<a href=\"" . url( "?id," . $url['id'] . ";m," . $nuoroda['id'] )
					. "\" >{$lang['admin']['gallery_next']} ></a>";
			}
			$text .= "</center>";

			lentele( $row['pavadinimas'], $text );

			if ( (int)$conf['galkom'] == 1 && $row['kom'] == 'taip' ) {
				include_once( "priedai/komentarai.php" );
				komentarai( $url['m'] );
			}
		}
		else {
			klaida( $lang['system']['warning'], $lang['admin']['gallery_cant'] );
		}
	}
}
if ( kiek( "galerija", "WHERE rodoma='TAIP' AND `lang` = " . escape( lang() ) . "" ) == 0 ) {
	klaida( $lang['system']['warning'], $lang['system']['no_content'] );
}

unset( $text, $row, $sql );
