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
if ( isset( $url['p'] ) && isnum( $url['p'] ) && $url['p'] > 0 ) {
	$p = escape( ceil( (int)$url['p'] ) );
} else {
	$p = 0;
}

include_once ( "priedai/class.php" );
$limit = 30;
$viso  = kiek( "users" );
//vartotojų sarašas
$sql = mysql_query1( "SELECT id, INET_NTOA(ip) AS ip, reg_data, gim_data, login_data, nick, vardas, pavarde, email, levelis FROM `" . LENTELES_PRIESAGA . "users` LIMIT $p, $limit", 86400 );
$i   = 0;

if ( sizeof( $sql ) > 0 ) {
	foreach ( $sql as $row ) {
		if ( isset( $conf['level'][$row['levelis']]['pavadinimas'] ) ) {
			$grupe = $conf['level'][$row['levelis']]['pavadinimas'];
		} else {
			$grupe = '-';
		}
		$i++;
		$info[] = array( "{$lang['ulist']['username']}" => user( $row['nick'], $row['id'], $row['levelis'] ), "{$lang['ulist']['group']}" => $grupe );
		if ( $_SESSION[SLAPTAS]['level'] == 1 ) {
			$info[( $i - 1 )][$lang['ulist']['email']] = preg_replace( "#([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+)#i", "<a href=\"javascript:mailto:mail('\\1','\\2');\">\\1_(at)_\\2</a>", input( $row['email'] ) );
		}
	}
	//nupiesiam adminu lentele
	$bla = new Table();
	lentele( "{$lang['ulist']['list']} - $viso", $bla->render( $info ), FALSE );
	if ( $viso > $limit ) {
		lentele( $lang['system']['pages'], puslapiai( $p, $limit, $viso, 10 ) );
	}

}
unset( $info, $bla, $i, $sql );

?>