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


//Sarašas kur ieškoti
$kur = array();
if ( isset( $conf['pages']['naujienos.php']['id'] ) ) {
	$kur['naujienos'] = getLangText('search', 'news');
}
if ( isset( $conf['pages']['straipsnis.php']['id'] ) ) {
	$kur['str'] = getLangText('search', 'articles');
}
if ( isset( $conf['pages']['siustis.php']['id'] ) ) {
	$kur['siunt'] = getLangText('search', 'downloads');
}
if ( isset( $conf['pages']['frm.php']['id'] ) ) {
	$kur['frmt'] = getLangText('search', 'forum_topics');
}
if ( isset( $conf['pages']['frm.php']['id'] ) ) {
	$kur['frm'] = getLangText('search', 'forum_messages');
}
if ( isset( $conf['pages']['galerija.php']['id'] ) ) {
	$kur['galerija'] = getLangText('search', 'images');
}
if ( isset( $conf['pages']['reg.php']['id'] ) ) {
	$kur['memb'] = getLangText('search', 'members');
}
$kur['kom']  = getLangText('search', 'comments');
$kur['page'] = getLangText('search', 'pages');
//$kur['vis'] = getLangText('search', 'everything');
$box = "";
foreach ( $kur as $name => $check ) {
	$box .= "<label><input type=\"checkbox\" name=\"$name\" value=\"$name\" " . ( ( isset( $_POST[$name] ) && !empty( $_POST[$name] ) ) || ( isset( $_POST['vis'] ) && !empty( $_POST['vis'] ) ) ? 'checked="yes"' : '' ) . "/> $check</label><br /> ";
}
$box .= "<label><input type='checkbox' name='vis' onclick='checkedAll(\"search\");'/> " . getLangText('search',  'everything') . "</label>";
//Paieškos forma
$search = array(
	"Form"                      => array( "action" => url( "?id," . $conf['pages'][basename( __file__ )]['id'] ), "method" => "post", "enctype" => "", "id" => "search", "name" => "search" ),
	getLangText('search', 'for')		=> array( "type" => "text", "class" => "form-control", "value" => ( isset( $_POST['s'] ) ? input( $_POST['s'] ) : '' ), "name" => "s" ),
	" "							=> array( "type" => "string", "value" => $box ),
	""                          => array( "type" => "submit", "class" => "btn btn-primary", "name" => "subsearch", "value" => getLangText('search', 'search') )
);

//Nupiešiam paieškos formą
include_once config('class', 'dir') . 'class.form.php';

$bla = new Form();
lentele(getLangText('search', 'search'), $bla->form($search));
$i = 0;
//Atliekam paiešką
//print_r($_POST);
if ( isset( $_POST['s'] ) ) {
	if ( strlen( str_replace( array( " ", "\r", "\n", "<", ">", "\"", "'", "." ), "", $_POST['s'] ) ) >= 3 ) {
		if ( ( isset( $_POST['naujienos'] ) || isset( $_POST['vis'] ) ) && isset( $conf['pages']['naujienos.php']['id'] ) ) {
			$sqlas3 = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "naujienos` WHERE `pavadinimas` LIKE " . escape( "%" . $_POST['s'] . "%" ) . " AND `rodoma`='TAIP' OR `naujiena` LIKE " . escape( "%" . $_POST['s'] . "%" ) . " AND `rodoma`='TAIP' AND `lang` = " . escape( lang() ) . " LIMIT 0,100", 120 );

			$text = "";
			if ( sizeof( $sqlas3 ) > 0 ) {
				foreach ( $sqlas3 as $row3 ) {
					$i++;
					$text .= "<a href='" . url( "?id," . $conf['pages']['naujienos.php']['id'] . ";k," . $row3['id'] ) . "'>" . trimlink( input( $row3['pavadinimas'] ), 40 ) . "...</a><br />";
				}
				lentele( getLangText('search', 'news'), $text );
			}
		}
		if ( ( isset( $_POST['frmt'] ) || isset( $_POST['vis'] ) ) && isset( $conf['pages']['frm.php']['id'] ) ) {
			$sqlas4 = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "d_straipsniai` WHERE `pav` LIKE " . escape( "%" . $_POST['s'] . "%" ) . " AND `lang` = " . escape( lang() ) . " LIMIT 0,100", 120 );

			$text = "";
			if ( sizeof( $sqlas4 ) > 0 ) {
				foreach ( $sqlas4 as $row4 ) {
					$i++;
					$text .= "<a href='" . url( "?id," . $conf['pages']['frm.php']['id'] . ";t," . $row4['id'] . ";s," . $row4['tid'] ) . "'>" . trimlink( input( $row4['pav'] ), 40 ) . "...</a><br />";
				}
				lentele( getLangText('search', 'forum_topics'), $text );
			}
		}
		if ( ( isset( $_POST['frm'] ) || isset( $_POST['vis'] ) ) && isset( $conf['pages']['frm.php']['id'] ) ) {
			$sqlas5 = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "d_zinute` WHERE `zinute` LIKE " . escape( "%" . $_POST['s'] . "%" ) . " LIMIT 0,100" );

			$text = "";
			if ( sizeof( $sqlas5 ) > 0 ) {
				foreach ( $sqlas5 as $row5 ) {
					$i++;
					$text .= "<a href='" . url( "?id," . $conf['pages']['frm.php']['id'] . ";t," . $row5['sid'] . ";s," . $row5['tid'] ) . "'>" . trimlink( input( $row5['zinute'] ), 40 ) . "...</a><br />";
				}
				lentele( getLangText('search', 'forum_messages'), $text );
			}

		}
		if ( ( isset( $_POST['str'] ) || isset( $_POST['vis'] ) ) && isset( $conf['pages']['straipsnis.php']['id'] ) ) {
			$sqlas6 = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "straipsniai` WHERE `t_text` LIKE " . escape( "%" . $_POST['s'] . "%" ) . " AND `rodoma`='TAIP' or `f_text` LIKE " . escape( "%" . $_POST['s'] . "%" ) . " AND `rodoma`='TAIP' or `pav` LIKE " . escape( "%" . $_POST['s'] . "%" ) . " AND `rodoma`='TAIP' AND `lang` = " . escape( lang() ) . " LIMIT 0,100", 120 );

			$text = "";
			if ( sizeof( $sqlas6 ) > 0 ) {
				foreach ( $sqlas6 as $row6 ) {
					$i++;
					$text .= "<a href='" . url( "?id," . $conf['pages']['straipsnis.php']['id'] . ";k," . $row6['kat'] . ";m," . $row6['id'] ) . "'>" . trimlink( input( $row6['pav'] ), 40 ) . "...</a><br />";
				}
				lentele( getLangText('search', 'articles'), $text );
			}

		}
		if ( ( isset( $_POST['siunt'] ) || isset( $_POST['vis'] ) ) && isset( $conf['pages']['siustis.php']['id'] ) ) {
			$sqlas7 = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "siuntiniai` WHERE  `pavadinimas` LIKE " . escape( "%" . $_POST['s'] . "%" ) . " AND `rodoma`='TAIP' or `apie` LIKE " . escape( "%" . $_POST['s'] . "%" ) . " AND `rodoma`='TAIP' AND `lang` = " . escape( lang() ) . " LIMIT 0,100", 120 );

			$text = "";
			if ( sizeof( $sqlas7 ) > 0 ) {
				foreach ( $sqlas7 as $row7 ) {
					$i++;
					$text .= "<a href='" . url( "?id," . $conf['pages']['siustis.php']['id'] . ";k," . $row7['categorija'] . ";v," . $row7['ID'] ) . "'>" . trimlink( input( $row7['pavadinimas'] ), 40 ) . "...</a><br />";
				}
				lentele( getLangText('search', 'downloads'), $text );
			}

		}
		if ( ( isset( $_POST['galerija'] ) || isset( $_POST['vis'] ) ) && isset( $conf['pages']['galerija.php']['id'] ) ) {
			$sqlas7 = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "galerija` WHERE `pavadinimas` LIKE " . escape( "%" . $_POST['s'] . "%" ) . " AND `rodoma`='TAIP' or `apie` LIKE " . escape( "%" . $_POST['s'] . "%" ) . " AND `rodoma`='TAIP' AND `lang` = " . escape( lang() ) . " LIMIT 0,100", 120 );

			$text = "";
			if ( sizeof( $sqlas7 ) > 0 ) {
				foreach ( $sqlas7 as $row7 ) {
					$i++;
					$text .= "<a href='" . url( "?id," . $conf['pages']['galerija.php']['id'] . ";m," . $row7['ID'] ) . "'>" . trimlink( input( $row7['pavadinimas'] ), 40 ) . "...</a><br />";
				}
				lentele( getLangText('search', 'images'), $text );
			}

		}
		if ( ( isset( $_POST['memb'] ) || isset( $_POST['vis'] ) ) && isset( $conf['pages']['reg.php']['id'] ) ) {
			$sqlas9 = mysql_query1( "SELECT id,nick,levelis FROM `" . LENTELES_PRIESAGA . "users` WHERE `nick` LIKE " . escape( "%" . $_POST['s'] . "%" ) . "", 120 );

			$text = "";
			if ( sizeof( $sqlas9 ) > 0 ) {
				foreach ( $sqlas9 as $row9 ) {
					$i++;

					$text .= user( $row9['nick'], $row9['id'], $row9['levelis'] ) . "<br />";
				}
				lentele( getLangText('search', 'members'), $text );
			}

		}
		if ( isset( $_POST['page'] ) || isset( $_POST['vis'] ) ) {
			$sqlas10 = mysql_query1( "SELECT id,pavadinimas FROM `" . LENTELES_PRIESAGA . "page` WHERE `pavadinimas` LIKE " . escape( "%" . $_POST['s'] . "%" ) . " AND `lang` = " . escape( lang() ), 120 );

			$text = "";
			if ( sizeof( $sqlas10 ) > 0 ) {
				foreach ( $sqlas10 as $row10 ) {
					$i++;

					$text .= "<a href=\"" . url( "?id,{$row10['id']}" ) . "\">{$row10['pavadinimas']}</a><br />";
				}
				lentele( getLangText('search', 'pages'), $text );
			}

		}

		if ( isset( $_POST['kom'] ) || isset( $_POST['vis'] ) ) {
			$sqlas2 = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "kom` WHERE `zinute` LIKE " . escape( "%" . $_POST['s'] . "%" ) . " LIMIT 0,100", 120 );

			$text = "";
			if ( sizeof( $sqlas2 ) > 0 ) {
				foreach ( $sqlas2 as $row2 ) {
					if ( $row2['pid'] == 'content/pages/naujienos' && isset( $conf['pages']['naujienos.php']['id'] ) ) {
						$link = "k," . $row2['kid'];
					} elseif ( $row2['pid'] == 'content/pages/view_user' && isset( $conf['pages']['view_user.php']['id'] ) ) {
						$link = "m," . $row2['kid'];
					} elseif ( $row2['pid'] == 'content/pages/galerija' && isset( $conf['pages']['view_user.php']['id'] ) ) {
						$link = "m," . $row2['kid'];
					} elseif ( $row2['pid'] == 'content/pages/straipsnis' && isset( $conf['pages']['straipsnis.php']['id'] ) ) {
						$link = "m," . $row2['kid'] . "";
					} elseif ( $row2['pid'] == 'content/pages/siustis' && isset( $conf['pages']['siustis.php']['id'] ) ) {

						$linkas = mysql_fetch_assoc( mysql_query1( "SELECT categorija FROM `" . LENTELES_PRIESAGA . "siuntiniai` WHERE `ID`='" . $row2['kid'] . "' AND `lang` = " . escape( lang() ) . "LIMIT 1" ) );
						$link   = "k," . $linkas['categorija'] . "v," . $row2['kid'] . "";
					} else {
						$link = "";
					}


					$i++;
					$file = str_replace( 'content/pages/', '', $row2['pid'] );
					if ( isset( $conf['pages']['' . $file . '.php']['id'] ) ) {
						$text .= "<a href=" . url( "?id," . $conf['pages']['' . $file . '.php']['id'] . ";" . $link . "#" . $row2['id'] ) . ">" . substr( input( $row2['zinute'] ), 0, 200 ) . "...</a><br />";
					}
				}
				lentele( getLangText('search', 'comments'), $text );
			}
		}
		if ( $i > 0 ) {
			//$kiek = mysql_num_rows($sqlas);
			//msg(getLangText('system', 'done'),"<b>".input(str_replace("%"," ",$_POST['s']))."</b><br/>Rasta atikmenų: ".$i);
			//msg(getLangText('search', 'results'), $text);
		} else {
			klaida( getLangText('system', 'sorry'), "<b>" . input( str_replace( "%", " ", $_POST['s'] ) ) . "</b> " . getLangText('search',  'notfound') . "" );
		}
	} else {
		klaida( getLangText('system', 'warning'), getLangText('search', 'short') );
	}
}

unset( $kur, $ka, $link, $link2, $link3, $text, $row, $search, $kuriam, $iskur, $iskurdar, $sqlas, $bla, $forma );
