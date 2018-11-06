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
//patikrinam ar teisingai uzkrautas puslapis
if ( !defined( "OK" ) ) {
	header( 'location: ' . url( "?id,{$conf['puslapiai'][$conf['pirminis'].'.php']['id']}" ) );
	exit;
}

if ( isset( $url['c'] ) && isnum( $url['c'] ) && $url['c'] > 0 ) {
	$cid = (int)$url['c'];
} else {
	$cid = 0;
}
if ( isset( $url['v'] ) && isnum( $url['v'] ) && $url['v'] > 0 ) {
	$vid = (int)$url['v'];
} else {
	$vid = 0;
}
if ( isset( $url['r'] ) && isnum( $url['r'] ) && $url['r'] > 0 ) {
	$rid = (int)$url['r'];
} else {
	$rid = 0;
}
if ( isset( $url['k'] ) && isnum( $url['k'] ) && $url['k'] > 0 ) {
	$k = (int)$url['k'];
} else {
	$k = 0;
}
if ( isset( $url['p'] ) && isnum( $url['p'] ) && $url['p'] > 0 ) {
	$p = (int)$url['p'];
} else {
	$p = 0;
}
$subs  = 0;
$limit = $conf['News_limit'];
//$sqlkiek = '';
//kategorijos
if ( $vid == 0 ) {
	$sqlas = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='siuntiniai' AND `lang` = " . escape( lang() ) . " ORDER BY `pavadinimas`", 86400 );
	if ( $sqlas && sizeof( $sqlas ) > 0 ) {
		foreach ( $sqlas as $sql ) {
			//echo $sql['id'].'->'.$sql['path'].'<br />'
			//TODO: skaiciavimas is neriboto gylio.. dabar tik kategorija + sub kategorija skaiciuoja
			if ( $sql['path'] == $k ) {
				//$sqlkiek = kiek('siuntiniai', "WHERE ".($k == 0 ? "`categorija` =" . escape($sql['path']) . " OR" : "")." `categorija` =" . escape($sql['id']) . " AND `rodoma`='TAIP' AND `lang` = ".escape(lang())."");
				$kiek    = mysql_query1( "SELECT count(*) + (SELECT count(*) FROM `" . LENTELES_PRIESAGA . "siuntiniai` WHERE `categorija` IN (SELECT `id` FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `path`=" . escape( $sql['id'] ) . ")) as `kiek` FROM `" . LENTELES_PRIESAGA . "siuntiniai` WHERE `categorija`=" . escape( $sql['id'] ) . " AND `rodoma`='TAIP' AND `lang` = " . escape( lang() ) . " LIMIT 1" );
				$sqlkiek = $kiek['kiek'];
				$subs++;
				$info[] = array(
					$lang['system']['categories'] => "<a style=\"float: left;\" class=\"kat\" href='" . url( "?id," . $url['id'] . ";k," . $sql['id'] . "" ) . "'><img src='images/naujienu_kat/" . input( $sql['pav'] ) . "' alt=\"\"  border=\"0\" /></a><div><a href='" . url( "?id," . $url['id'] . ";k," . $sql['id'] . "" ) . "'><b>" . input( $sql['pavadinimas'] ) . "</b></a><span class=\"small_about\"style='font-size:9px;width:auto;display:block;'><div>" . input( $sql['aprasymas'] ) . "</div><div>{$lang['category']['downloads']}: {$sqlkiek}</div></span></div>" //,
				);
			}

		}
	}
	include_once ( "priedai/class.php" );
	$bla = new Table();
	if ( isset( $info ) ) {
		lentele( "{$lang['system']['categories']}", $bla->render( $info ), FALSE );
	}
}
//pabaiga
# Rodom siuntini
if ( $vid > 0 ) {
	$sql = mysql_query1( "SELECT
  `" . LENTELES_PRIESAGA . "grupes`.`pavadinimas` AS `Kategorija`,
  `" . LENTELES_PRIESAGA . "grupes`.`pav` AS `img`,
  `" . LENTELES_PRIESAGA . "grupes`.`id` AS `kid`,
  `" . LENTELES_PRIESAGA . "grupes`.`teises` AS `teises`,
  `" . LENTELES_PRIESAGA . "siuntiniai`.`pavadinimas`,
  `" . LENTELES_PRIESAGA . "siuntiniai`.`id`,
  `" . LENTELES_PRIESAGA . "siuntiniai`.`apie`,
  `" . LENTELES_PRIESAGA . "siuntiniai`.`data`,
  `" . LENTELES_PRIESAGA . "siuntiniai`.`autorius`,
  `" . LENTELES_PRIESAGA . "siuntiniai`.`file`
  FROM
  `" . LENTELES_PRIESAGA . "grupes`
  Inner Join `" . LENTELES_PRIESAGA . "siuntiniai` ON `" . LENTELES_PRIESAGA . "grupes`.`id` = `" . LENTELES_PRIESAGA . "siuntiniai`.`categorija`
  WHERE  
   `" . LENTELES_PRIESAGA . "siuntiniai`.`ID` = '$vid' AND
   `" . LENTELES_PRIESAGA . "siuntiniai`.`categorija` =   " . escape( $k ) . "
   AND
   `" . LENTELES_PRIESAGA . "siuntiniai`.`rodoma` =  'TAIP'
   AND `" . LENTELES_PRIESAGA . "siuntiniai`.`lang` = " . escape( lang() ) . "
  ORDER BY
  `" . LENTELES_PRIESAGA . "siuntiniai`.`data` DESC
  LIMIT 1", 86400 );
	if ( !isset( $sql['id'] ) ) {
		$sql = mysql_query1( "SELECT *, `ID` as id  FROM  `" . LENTELES_PRIESAGA . "siuntiniai` WHERE `ID` = '$vid' AND `rodoma` =  'TAIP' AND `lang` = " . escape( lang() ) . " LIMIT 1", 86400 );
	}
	if ( sizeof( $sql ) > 0 && isset( $sql['id'] ) ) {
		//$sql = mysql_fetch_assoc($sql);
		if ( !isset( $sql['teises'] ) || teises( $sql['teises'], $_SESSION[SLAPTAS]['level'] ) ) {
			$sql_autr = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "users` WHERE `id`= '" . $sql['autorius'] . "' LIMIT 1" );
			if ( isset( $sql_autr ['nick'] ) ) {
				$autorius = user( $sql_autr ['nick'], $sql_autr['id'], $sql_autr ['levelis'] );
			} else {
				$autorius = $lang['system']['guest'];
			}

			include_once ( "priedai/class.php" );
			$ble = new Table();
			addtotitle( $sql['pavadinimas'] );
			if ( isset( $sql['Kategorija'] ) ) {
				$info2[0][$lang['system']['category']]   = "<b>" . input( $sql['Kategorija'] ) . "</b><br /><div class='avataras'><img src='images/naujienu_kat/" . input( $sql['img'] ) . "' alt='" . input( $sql['Kategorija'] ) . "' /></div>";
				$ble->width[$lang['system']['category']] = '50px';
			}
			$info2[0][input( $sql['pavadinimas'] )] = "
			<div style='vertical-align: top'> <b>{$lang['download']['about']}:</b> " . $sql['apie'] . "<br />
			<b>{$lang['admin']['download_author']} :</b> {$autorius}<br /><b>{$lang['download']['date']}:</b> " . date( 'Y-m-d H:i:s ', $sql['data'] ) . "<br />
			<div class='line'></div>
			<!-- AddThis Button BEGIN -->
			<div class='addthis_toolbox addthis_default_style '><a href='http://www.addthis.com/bookmark.php?v=250&amp;pubid=xa-4e7a05051d3cf281' class='addthis_button_compact'>" . $lang['news']['share'] . "</a><script type='text/javascript' src='http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4e7a05051d3cf281'></script><a class='addthis_button_facebook_like' fb:like:layout='button_count'></a><a class='addthis_button_tweet'></a><a class='addthis_button_google_plusone' g:plusone:size='medium'></a></div><script type='text/javascript' src='http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4e7a03fc44b95268'></script>
			<!-- AddThis Button END -->
			<div class='line'></div>
			<a class='siust' href=\"siustis.php?d," . $sql['id'] . "\"><img src=\"images/icons/disk.png\" alt=\"" . input( $sql['file'] ) . "\" border=\"0\" />{$lang['download']['download']}</a></div>";

			lentele( "{$lang['download']['downloads']} >> " . ( isset( $sql['Kategorija'] ) ? input( $sql['Kategorija'] ) . " >> " : "" ) . input( $sql['pavadinimas'] ) . "", $ble->render( $info2 ) . "<a href=\"javascript: history.go(-1)\">{$lang['download']['back']}</a>" );

			include_once ( "priedai/komentarai.php" );
			komentarai( $vid );
		} else {
			klaida( $lang['system']['error'], $lang['download']['notallowed'] );
		}
	} else {
		klaida( $lang['system']['error'], $lang['system']['pagenotfounfd'] );
	}
} # rodom visus siuntinius
else {
	$viso = kiek( "siuntiniai", "WHERE `rodoma`='TAIP' AND `categorija` =  '$k' AND `lang` = " . escape( lang() ) . "" );
	if ( $k > 0 ) {
		$sql_s = mysql_query1( "
   SELECT
    `" . LENTELES_PRIESAGA . "grupes`.`pavadinimas` AS `Kategorija`,
    `" . LENTELES_PRIESAGA . "grupes`.`teises` AS `teises`,
    `" . LENTELES_PRIESAGA . "siuntiniai`.`pavadinimas`,
    `" . LENTELES_PRIESAGA . "siuntiniai`.`data`,
    `" . LENTELES_PRIESAGA . "siuntiniai`.`id`,
    `" . LENTELES_PRIESAGA . "users`.`nick` AS `Nick`,
    `" . LENTELES_PRIESAGA . "users`.`id` AS `nick_id`,
    `" . LENTELES_PRIESAGA . "users`.`levelis` AS `levelis`,
    `" . LENTELES_PRIESAGA . "siuntiniai`.`file`
    FROM
    `" . LENTELES_PRIESAGA . "grupes`
    Inner Join `" . LENTELES_PRIESAGA . "siuntiniai` ON `" . LENTELES_PRIESAGA . "grupes`.`id` = `" . LENTELES_PRIESAGA . "siuntiniai`.`categorija`
    Inner Join `" . LENTELES_PRIESAGA . "users` ON `" . LENTELES_PRIESAGA . "siuntiniai`.`autorius` = `" . LENTELES_PRIESAGA . "users`.`id`
    WHERE
  `" . LENTELES_PRIESAGA . "siuntiniai`.`categorija` =  '$k'
  AND
   `" . LENTELES_PRIESAGA . "siuntiniai`.`rodoma` =  'TAIP'
   AND `" . LENTELES_PRIESAGA . "siuntiniai`.`lang` = " . escape( lang() ) . "
    ORDER BY
    `" . LENTELES_PRIESAGA . "siuntiniai`.`data` DESC
    LIMIT {$p},{$limit}
  ", 86400 );
		if ( count( $sql_s ) == 0 && $subs == 0 ) {
			klaida( $lang['system']['warning'], $lang['system']['no_content'] . "<br /><a href=\"javascript: history.go(-1)\">{$lang['download']['back']}</a>" );
		}

	} else {
		$sql_s = mysql_query1( " SELECT
    `" . LENTELES_PRIESAGA . "siuntiniai`.`pavadinimas`,
    `" . LENTELES_PRIESAGA . "siuntiniai`.`data`,
    `" . LENTELES_PRIESAGA . "siuntiniai`.`id`,
    `" . LENTELES_PRIESAGA . "users`.`nick` AS `Nick`,
    `" . LENTELES_PRIESAGA . "users`.`id` AS `nick_id`,
    `" . LENTELES_PRIESAGA . "users`.`levelis` AS `levelis`,
    `" . LENTELES_PRIESAGA . "siuntiniai`.`file`
    FROM
    `" . LENTELES_PRIESAGA . "siuntiniai`
    Inner Join `" . LENTELES_PRIESAGA . "users` ON `" . LENTELES_PRIESAGA . "siuntiniai`.`autorius` = `" . LENTELES_PRIESAGA . "users`.`id`
    WHERE
  `" . LENTELES_PRIESAGA . "siuntiniai`.`categorija` =  '$k'
  AND
   `" . LENTELES_PRIESAGA . "siuntiniai`.`rodoma` =  'TAIP'
	AND `" . LENTELES_PRIESAGA . "siuntiniai`.`lang` = " . escape( lang() ) . "
    ORDER BY
    `" . LENTELES_PRIESAGA . "siuntiniai`.`data` DESC
    LIMIT {$p},{$limit}", 86400 );
	}
	if ( sizeof( $sql_s ) > 0 ) {
		include_once ( "priedai/class.php" );
		$bla  = new Table();
		$info = array();
		foreach ( $sql_s as $sql ) {
			if ( !isset( $sql['teises'] ) || teises( $sql['teises'], $_SESSION[SLAPTAS]['level'] ) ) {
				if ( isset( $sql['Nick'] ) ) {
					$autorius = user( $sql['Nick'], $sql['nick_id'], $sql['levelis'] );
				} else {
					$autorius = '';
				}
				$info[] = array( "{$lang['download']['title']}:" => "<a href=\"" . url( "v," . $sql['id'] . "" ) . "\">" . input( $sql['pavadinimas'] ) . "</a>",
				                 "{$lang['download']['date']}:" => date( 'Y-m-d H:i:s ', $sql['data'] ),
				                 //"{$lang['admin']['download_author']} :" => $autorius,
				                 "{$lang['download']['download']}:" => "<a href=\"siustis.php?d," . $sql['id'] . "\"><img src=\"images/icons/disk.png\" alt=\"" . $sql['file'] . "\" border=\"0\" /></a>" );

			} else {
				klaida( $lang['system']['error'], $lang['download']['notallowed'] );
			}
		}
		$name = mysql_query1( "SELECT `pavadinimas` FROM `" . LENTELES_PRIESAGA . "grupes` WHERE id= " . escape( $k ) . " LIMIT 1", 86400 );
		lentele( "{$lang['download']['downloads']} >> " . input( $name['pavadinimas'] ), $bla->render( $info ) . "<br /><a href=\"javascript: history.go(-1)\">{$lang['download']['back']}</a>" );
		if ( $viso > $limit ) {
			lentele( $lang['system']['pages'], puslapiai( $p, $limit, $viso, 10 ) );
		}
	}

	unset( $bla, $info, $sql, $sql_d, $vid );
}
if ( count( $_GET ) == 1 ) {
	if ( kiek( "siuntiniai", "WHERE `rodoma`='TAIP' AND `lang` = " . escape( lang() ) ) <= 0 ) {
		klaida( $lang['system']['warning'], $lang['system']['no_content'] . "<br /><a href=\"javascript: history.go(-1)\">{$lang['download']['back']}</a>" );
	}
}
