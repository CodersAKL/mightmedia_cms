<?php


/**
 * Title gairės papildymui
 *
 * @param $add string
 */
if(! function_exists('addtotitle')) {
    function addtotitle($add) {
    ?>
        <script type="text/javascript" data-append-script>
            var cur_title = new String(document.title);
            document.title = cur_title + " - <?php echo $add; ?>";
        
            var code = document.querySelector('[data-append-script]');
            if(code) {
                var script = code.cloneNode(true);
                document.body.appendChild(script);
                code.parentNode.removeChild(code);
                script.removeAttribute('data-append-script');
            }
        </script>
    <?php
    }
}

/**
 * Patikrina ar puslapis egzistuoja ir ar vartotojas turi teise ji matyti bei grazinam puslapio ID
 *
 * @param string $puslapis
 * @param bool   $extra
 *
 * @return bool|int
 */
if(! function_exists('puslapis')) {
	function puslapis($puslapis, $extra = false ) {

		global $conf;
		$teises = @unserialize( $conf['pages'][$puslapis]['teises'] );
	
		//todo: optimize after v2
		$isFile 	= is_file($puslapis) || is_file(dirname( __FILE__ ) . '/../content/pages/' . $puslapis);
		$pageName 	= basename($puslapis);

		if (isset($conf['pages'][$pageName]['id']) && ! empty( $conf['pages'][$pageName]['id']) && $isFile) {

			if ( $_SESSION[SLAPTAS]['level'] == 1 || ( is_array( $teises ) && in_array( $_SESSION[SLAPTAS]['level'], $teises ) ) || empty( $teises ) ) {

				if ($extra && isset($conf['pages'][$pageName][$extra]) ) {
					return $conf['pages'][$pageName][$extra];
				} else { //Jei reikalinga kita informacija apie puslapi - grazinam ja.
					return (int)$conf['pages'][$pageName]['id'];
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}

/**
 * Puslapiavimui generuoti
 *
 * @param string   $start puslapis
 * @param int      $count limitas
 * @param int      $total viso
 * @param int      $range ruožas
 *
 * @return string
 */
if(! function_exists('pages')) {
	function pages( $start, $count, $total, $range = 0 ) {

		$res    = "";
		$pg_cnt = ceil( $total / $count );

		if ( $pg_cnt > 1 ) {
			$idx_back = $start - $count;
			$idx_next = $start + $count;
			$cur_page = ceil( ( $start + 1 ) / $count );
			$res .= "";
			$res .= "<div class=\"puslapiavimas\">\n";

			if ( $idx_back >= 0 ) {

				if ( $cur_page > ( $range + 1 ) ) {
					$res .= "<a href='" . url( "p,0" ) . "'><span class=\"nuoroda\">««</span></a>\n";
				}
				$res .= "<a href='" . url( "p,{$idx_back}" ) . "'><span class=\"nuoroda\">«</span></a>\n";
			}
			$idx_fst = max( $cur_page - $range, 1 );
			$idx_lst = min( $cur_page + $range, $pg_cnt );

			if ( $range == 0 ) {
				$idx_fst = 1;
				$idx_lst = $pg_cnt;
			}
			for ( $i = $idx_fst; $i <= $idx_lst; $i++ ) {
				$offset_page = ( $i - 1 ) * $count;

				if ( $i == $cur_page ) {
					$res .= "<span class=\"paspaustas\">{$i}</span>\n";
				} else {
					$res .= "<a href='" . url( "p,{$offset_page}" ) . "'><span class=\"nuoroda\">{$i}</span></a>\n";
				}
			}
			if ( $idx_next < $total ) {
				$res .= "<a href='" . url( "p,{$idx_next}" ) . "'><span class=\"nuoroda\">»</span></a>\n";

				if ( $cur_page < ( $pg_cnt - $range ) ) {
					$res .= "<a href='" . url( "p," . ( $pg_cnt - 1 ) * $count . "" ) . "'><span class=\"nuoroda\">»»</span></a>\n";
				}
			}
			$res .= "</div>\n";
		}

		return $res;
	}
}

if(! function_exists('build_menu')) {
	function build_menu( $data, $id = 0, $active_class = 'active' ) {

		if ( !empty( $data ) ) {
			$re = "";
			foreach ( $data[$id] as $row ) {
				if ( isset( $data[$row['id']] ) ) {
					$re .= "\n\t\t<li " . ( ( isset( $_GET['id'] ) && $_GET['id'] == $row['id'] ) ? 'class="' . $active_class . '"' : '' ) . "><a href=\"" . url( "?id,{$row['id']}" ) . "\">" . $row['pavadinimas'] . "</a>\n<ul>\n\t";
					$re .= build_menu( $data, $row['id'], $active_class );
					$re .= "\t</ul>\n\t</li>";
				} else {
					$re .= "\n\t\t<li " . ( ( isset( $_GET['id'] ) && $_GET['id'] == $row['id'] ) ? 'class="' . $active_class . '"' : '' ) . "><a href=\"" . url( "?id,{$row['id']}" ) . "\">" . $row['pavadinimas'] . "" . ( isset( $row['extra'] ) ? $row['extra'] : '' ) . "</a></li>";
				}
			}

			return $re;
		} else {
			return FALSE;
		}
	}
}

if(! function_exists('site_tree')) {
	function site_tree( $data, $id = 0, $active_class = 'active' ) {

		global $lang;
		
		if ( !empty( $data ) ) {
			$re = "";
			foreach ( $data[$id] as $row ) {
				if ( isset( $data[$row['id']] ) ) {
					if ( teises( $row['teises'], $_SESSION[SLAPTAS]['level'] ) ) {
						$re .= "<li><a href=\"" . url( '?id,' . $row['id'] ) . "\" >" . $row['pavadinimas'] . "</a><ul>";
						$re .= site_tree( $data, $row['id'], $active_class );
						$re .= "</ul></li>";
					}
				} else if ( teises( $row['teises'], $_SESSION[SLAPTAS]['level'] ) ) {
					$re .= "<li><a href=\"" . url( '?id,' . $row['id'] ) . "\" >" . $row['pavadinimas'] . "</a></li>";
				}
			}

			return $re;
		}
	}
}