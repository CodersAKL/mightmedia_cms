<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: p.dambrauskas $
 * @copyright CodeRS �2008
 * @license GNU General Public License v2
 * @$Revision: 492 $
 * @$Date: 2010-04-01 13:22:10 +0300 (Kt, 01 Bal 2010) $
 **/


//Sarasas kur ieskoti
$kur = array();
if ( isset( $conf['puslapiai']['naujienos.php']['id'] ) ) {
	$kur['naujienos'] = $lang['search']['news'];
}
if ( isset( $conf['puslapiai']['straipsnis.php']['id'] ) ) {
	$kur['str'] = $lang['search']['articles'];
}
if ( isset( $conf['puslapiai']['siustis.php']['id'] ) ) {
	$kur['siunt'] = $lang['search']['downloads'];
}
if ( isset( $conf['puslapiai']['frm.php']['id'] ) ) {
	$kur['frmt'] = $lang['search']['forum_topics'];
}
if ( isset( $conf['puslapiai']['frm.php']['id'] ) ) {
	$kur['frm'] = $lang['search']['forum_messages'];
}
if ( isset( $conf['puslapiai']['galerija.php']['id'] ) ) {
	$kur['galerija'] = $lang['search']['images'];
}
if ( isset( $conf['puslapiai']['reg.php']['id'] ) ) {
	$kur['memb'] = $lang['search']['members'];
}
$kur['kom']  = $lang['search']['comments'];
$kur['page'] = $lang['search']['pages'];
//$kur['vis'] = $lang['search']['everything'];
$box = "";
foreach ( $kur as $name => $check ) {
	$box .= "<label><input type=\"checkbox\" name=\"$name\" value=\"$name\" " . ( ( isset( $_POST[$name] ) && !empty( $_POST[$name] ) ) || ( isset( $_POST['vis'] ) && !empty( $_POST['vis'] ) ) ? 'checked="yes"' : '' ) . "/> $check</label><br /> ";
}
$box .= "<label><input type='checkbox' name='vis' onclick='checkedAll(\"search\");'/> {$lang['search']['everything']}</label>";
//Paieskos forma
$search = array(
	"Form"                      => array( "action" => url( "?id,999;m,4" ), "method" => "post", "enctype" => "", "id" => "search", "name" => "search" ),
	" "                         => array( "type" => "text", "value" => ( isset( $_POST['s'] ) ? input( $_POST['s'] ) : '' ), "name" => "s", "class"=> "input", "extra"=> "title='{$lang['search']['for']}'" ),
	"{$lang['search']['for']}:" => array( "type" => "string", "value" => $box ),
	""                          => array( "type" => "submit", "class" => "submit", "name" => "subsearch", "value" => $lang['search']['search'] )
);

//Nupiesiam paieskos forma
$formClass = new Form($search);	
lentele($lang['admin']['pm_deletefrom'], $formClass->form());

$i = 0;
//Atliekam paieska
//print_r($_POST);
if ( isset( $_POST['s'] ) ) {
	if ( strlen( str_replace( array( " ", "\r", "\n", "<", ">", "\"", "'", "." ), "", $_POST['s'] ) ) >= 3 ) {
		if ( ( isset( $_POST['naujienos'] ) || isset( $_POST['vis'] ) ) && isset( $conf['puslapiai']['naujienos.php']['id'] ) ) {
			$sqlas3 = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "naujienos` WHERE `pavadinimas` LIKE " . escape( "%" . $_POST['s'] . "%" ) . " AND `rodoma`='TAIP' OR `naujiena` LIKE " . escape( "%" . $_POST['s'] . "%" ) . " AND `rodoma`='TAIP' AND `lang` = " . escape( lang() ) . " LIMIT 0,100" );

			$text = "";
			if ( sizeof( $sqlas3 ) > 0 ) {
				foreach ( $sqlas3 as $row3 ) {
					$i++;
					$text .= "<a href='" . url( "?id," . $conf['puslapiai']['naujienos.php']['id'] . ";k," . $row3['id'] ) . "'>" . trimlink( input( $row3['pavadinimas'] ), 40 ) . "...</a><br />";
				}
				lentele( $lang['search']['news'], $text );
			}
		}
		if ( ( isset( $_POST['frmt'] ) || isset( $_POST['vis'] ) ) && isset( $conf['puslapiai']['frm.php']['id'] ) ) {
			$sqlas4 = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "d_straipsniai` WHERE `pav` LIKE " . escape( "%" . $_POST['s'] . "%" ) . " AND `lang` = " . escape( lang() ) . " LIMIT 0,100" );

			$text = "";
			if ( sizeof( $sqlas4 ) > 0 ) {
				foreach ( $sqlas4 as $row4 ) {
					$i++;
					$text .= "<a href='" . url( "?id," . $conf['puslapiai']['frm.php']['id'] . ";t," . $row4['id'] . ";s," . $row4['tid'] ) . "'>" . trimlink( input( $row4['pav'] ), 40 ) . "...</a><br />";
				}
				lentele( $lang['search']['forum_topics'], $text );
			}
		}
		if ( ( isset( $_POST['frm'] ) || isset( $_POST['vis'] ) ) && isset( $conf['puslapiai']['frm.php']['id'] ) ) {
			$sqlas5 = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "d_zinute` WHERE `zinute` LIKE " . escape( "%" . $_POST['s'] . "%" ) . " LIMIT 0,100" );

			$text = "";
			if ( sizeof( $sqlas5 ) > 0 ) {
				foreach ( $sqlas5 as $row5 ) {
					$i++;
					$text .= "<a href='" . url( "?id," . $conf['puslapiai']['frm.php']['id'] . ";t," . $row5['sid'] . ";s," . $row5['tid'] ) . "'>" . trimlink( input( $row5['zinute'] ), 40 ) . "...</a><br />";
				}
				lentele( $lang['search']['forum_messages'], $text );
			}

		}
		if ( ( isset( $_POST['str'] ) || isset( $_POST['vis'] ) ) && isset( $conf['puslapiai']['straipsnis.php']['id'] ) ) {
			$sqlas6 = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "straipsniai` WHERE `t_text` LIKE " . escape( "%" . $_POST['s'] . "%" ) . " AND `rodoma`='TAIP' or `f_text` LIKE " . escape( "%" . $_POST['s'] . "%" ) . " AND `rodoma`='TAIP' or `pav` LIKE " . escape( "%" . $_POST['s'] . "%" ) . " AND `rodoma`='TAIP' AND `lang` = " . escape( lang() ) . " LIMIT 0,100" );

			$text = "";
			if ( sizeof( $sqlas6 ) > 0 ) {
				foreach ( $sqlas6 as $row6 ) {
					$i++;
					$text .= "<a href='" . url( "?id," . $conf['puslapiai']['straipsnis.php']['id'] . ";k," . $row6['kat'] . ";m," . $row6['id'] ) . "'>" . trimlink( input( $row6['pav'] ), 40 ) . "...</a><br />";
				}
				lentele( $lang['search']['articles'], $text );
			}

		}
		if ( ( isset( $_POST['siunt'] ) || isset( $_POST['vis'] ) ) && isset( $conf['puslapiai']['siustis.php']['id'] ) ) {
			$sqlas7 = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "siuntiniai` WHERE  `pavadinimas` LIKE " . escape( "%" . $_POST['s'] . "%" ) . " AND `rodoma`='TAIP' or `apie` LIKE " . escape( "%" . $_POST['s'] . "%" ) . " AND `rodoma`='TAIP' AND `lang` = " . escape( lang() ) . " LIMIT 0,100" );

			$text = "";
			if ( sizeof( $sqlas7 ) > 0 ) {
				foreach ( $sqlas7 as $row7 ) {
					$i++;
					$text .= "<a href='" . url( "?id," . $conf['puslapiai']['siustis.php']['id'] . ";k," . $row7['categorija'] . ";v," . $row7['ID'] ) . "'>" . trimlink( input( $row7['pavadinimas'] ), 40 ) . "...</a><br />";
				}
				lentele( $lang['search']['downloads'], $text );
			}

		}
		if ( ( isset( $_POST['galerija'] ) || isset( $_POST['vis'] ) ) && isset( $conf['puslapiai']['galerija.php']['id'] ) ) {
			$sqlas7 = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "galerija` WHERE `pavadinimas` LIKE " . escape( "%" . $_POST['s'] . "%" ) . " AND `rodoma`='TAIP' or `apie` LIKE " . escape( "%" . $_POST['s'] . "%" ) . " AND `rodoma`='TAIP' AND `lang` = " . escape( lang() ) . " LIMIT 0,100" );

			$text = "";
			if ( sizeof( $sqlas7 ) > 0 ) {
				foreach ( $sqlas7 as $row7 ) {
					$i++;
					$text .= "<a href='" . url( "?id," . $conf['puslapiai']['galerija.php']['id'] . ";m," . $row7['ID'] ) . "'>" . trimlink( input( $row7['pavadinimas'] ), 40 ) . "...</a><br />";
				}
				lentele( $lang['search']['images'], $text );
			}

		}
		if ( ( isset( $_POST['memb'] ) || isset( $_POST['vis'] ) ) && isset( $conf['puslapiai']['reg.php']['id'] ) ) {
			$sqlas9 = mysql_query1( "SELECT id,nick,levelis FROM `" . LENTELES_PRIESAGA . "users` WHERE `nick` LIKE " . escape( "%" . $_POST['s'] . "%" ) . "" );

			$text = "";
			if ( sizeof( $sqlas9 ) > 0 ) {
				foreach ( $sqlas9 as $row9 ) {
					$i++;

					$text .= user( $row9['nick'], $row9['id'], $row9['levelis'] ) . "<br />";
				}
				lentele( $lang['search']['members'], $text );
			}

		}
		if ( isset( $_POST['page'] ) || isset( $_POST['vis'] ) ) {
			$sqlas10 = mysql_query1( "SELECT id,pavadinimas FROM `" . LENTELES_PRIESAGA . "page` WHERE `pavadinimas` LIKE " . escape( "%" . $_POST['s'] . "%" ) . " AND `lang` = " . escape( lang() ) );

			$text = "";
			if ( sizeof( $sqlas10 ) > 0 ) {
				foreach ( $sqlas10 as $row10 ) {
					$i++;

					$text .= "<a href=\"" . url( "?id,{$row10['id']}" ) . "\">{$row10['pavadinimas']}</a><br />";
				}
				lentele( $lang['search']['pages'], $text );
			}

		}

		if ( isset( $_POST['kom'] ) || isset( $_POST['vis'] ) ) {
			$sqlas2 = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "kom` WHERE `zinute` LIKE " . escape( "%" . $_POST['s'] . "%" ) . " LIMIT 0,100" );

			$text = "";
			if ( sizeof( $sqlas2 ) > 0 ) {
				foreach ( $sqlas2 as $row2 ) {
					if ( $row2['pid'] == 'puslapiai/naujienos' && isset( $conf['puslapiai']['naujienos.php']['id'] ) ) {
						$link = "k," . $row2['kid'];
					} elseif ( $row2['pid'] == 'puslapiai/view_user' && isset( $conf['puslapiai']['view_user.php']['id'] ) ) {
						$link = "m," . $row2['kid'];
					} elseif ( $row2['pid'] == 'puslapiai/galerija' && isset( $conf['puslapiai']['view_user.php']['id'] ) ) {
						$link = "m," . $row2['kid'];
					} elseif ( $row2['pid'] == 'puslapiai/straipsnis' && isset( $conf['puslapiai']['straipsnis.php']['id'] ) ) {
						$link = "m," . $row2['kid'] . "";
					} elseif ( $row2['pid'] == 'puslapiai/siustis' && isset( $conf['puslapiai']['siustis.php']['id'] ) ) {

						$linkas = mysqli_fetch_assoc( $prisijungimas_prie_mysql, mysql_query1( "SELECT categorija FROM `" . LENTELES_PRIESAGA . "siuntiniai` WHERE `ID`='" . $row2['kid'] . "' AND `lang` = " . escape( lang() ) . "LIMIT 1" ) );
						$link   = "k," . $linkas['categorija'] . "v," . $row2['kid'] . "";
					} else {
						$link = "";
					}


					$i++;
					$file = str_replace( 'puslapiai/', '', $row2['pid'] );
					if ( isset( $conf['puslapiai']['' . $file . '.php']['id'] ) ) {
						$text .= "<a href=" . url( "?id," . $conf['puslapiai']['' . $file . '.php']['id'] . ";" . $link . "#" . $row2['id'] ) . ">" . substr( input( $row2['zinute'] ), 0, 200 ) . "...</a><br />";
					}
				}
				lentele( $lang['search']['comments'], $text );
			}
		}
		if ( $i > 0 ) {
			//$kiek = mysql_num_rows($sqlas);
			//msg($lang['system']['done'],"<b>".input(str_replace("%"," ",$_POST['s']))."</b><br/>Rasta atikmenu: ".$i);
			//msg($lang['search']['results'], $text);
		} else {
			klaida( $lang['system']['sorry'], "<b>" . input( str_replace( "%", " ", $_POST['s'] ) ) . "</b> {$lang['search']['notfound']}" );
		}
	} else {
		klaida( $lang['system']['warning'], $lang['search']['short'] );
	}
}

unset( $kur, $ka, $link, $link2, $link3, $text, $row, $search, $kuriam, $iskur, $iskurdar, $sqlas, $forma );
