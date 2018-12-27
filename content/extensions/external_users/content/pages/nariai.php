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


$limit = 30;
$viso  = kiek( "users" );
//vartotojų sarašas
$sql = mysql_query1( "SELECT id, ip, reg_data, gim_data, login_data, nick, vardas, pavarde, email, levelis FROM `" . LENTELES_PRIESAGA . "users` LIMIT $p, $limit", 86400 );
$i   = 0;

if ( sizeof( $sql ) > 0 ) {
	foreach ( $sql as $row ) {
		if ( isset( $conf['level'][$row['levelis']]['pavadinimas'] ) ) {
			$grupe = $conf['level'][$row['levelis']]['pavadinimas'];
		} else {
			$grupe = '-';
		}
		$i++;
		$info[] = array( getLangText('ulist',  'username') => user( $row['nick'], $row['id'], $row['levelis'] ), getLangText('ulist',  'group') => $grupe );
		if (getSession('level') == 1) {
			$info[( $i - 1 )][getLangText('ulist', 'email')] = preg_replace( "#([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+)#i", "<a href=\"javascript:mailto:mail('\\1','\\2');\">\\1_(at)_\\2</a>", input( $row['email'] ) );
		}
	}
	//nupiesiam adminu lentele
	include_once config('class', 'dir') . 'class.table.php';
	$bla = new Table();
	lentele(getLangText('ulist', 'list') . " - " . $viso . ", " . $bla->render( $info ), FALSE );
	if ( $viso > $limit ) {
		lentele( getLangText('system', 'pages'), pages( $p, $limit, $viso, 10 ) );
	}

}
unset( $info, $bla, $i, $sql );