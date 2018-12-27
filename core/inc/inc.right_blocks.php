<?php
//todo: remove

$sql_p = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='R' AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC", 120 );

foreach ( $sql_p as $row_p ) {
	if ( teises( $row_p['teises'], getSession('level')) ) {
		//todo: after v2 optimize it
		if(is_file($row_p['file'])) {
			$includeBlock = $row_p['file'];
		} elseif(is_file("content/blocks/" . basename($row_p['file']))) {
			$includeBlock = "content/blocks/" . basename($row_p['file']);
		} else {
			$includeBlock = null;
		}

		if (! empty($includeBlock)) {
			
			include_once $includeBlock;
			
			if ( !isset( $title ) ) {
				$title = $row_p['panel'];
			}
			if ( $row_p['show'] == 'Y' && isset( $text ) && !empty( $text ) && ! empty(getSession('level')) && teises( $row_p['teises'], getSession('level'))) {
				lentele_r( $title, $text );
				unset( $title, $text );
			} elseif ( isset( $text ) && !empty( $text ) && $row_p['show'] == 'N' && ! empty(getSession('level')) && teises( $row_p['teises'], getSession('level'))) {
				echo $text;
				unset( $text, $title );
			} else {
				unset( $text, $title );
			}
		} else {
			echo lentele_r( getLangText('system', 'error'), getLangText('system', 'nopanel') . ".", $row_p['file'] );
		}
	}
}
unset( $sql_p, $row_p );
