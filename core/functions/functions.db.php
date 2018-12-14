<?php

/**
 * Sutvarko SQL užklausą
 *
 * @param string $sql
 *
 * @return string escaped
 */
if(! function_exists('escape')) {
	function escape( $sql ) {
		global $prisijungimas_prie_mysql;
		// Stripslashes
		if ( get_magic_quotes_gpc() ) {
			$sql = stripslashes( $sql );
		}
		// Jei ne skaičius
		if ( !isNum( $sql ) || $sql[0] == '0' ) {
			if ( !isNum( $sql ) ) {
				$sql = "'" . @mysqli_real_escape_string( $prisijungimas_prie_mysql, $sql ) . "'";
			}
		}

		return $sql;
	}
}

/**
 * MySQL užklausoms
 *
 * @param string $query
 * @param int    $lifetime
 *
 * @return array
 */
if(! function_exists('mysql_query1')) {
	function mysql_query1( $query, $lifetime = 0 ) {

		global $mysql_num, $prisijungimas_prie_mysql, $conf;

		// Sugeneruojam kešo pavadinimą
		$keshas = realpath( dirname( __file__ ) . '/..' ) . '/sandeliukas/' . md5( $query ) . '.php'; //kešo failas
		$return = array();

		if ( !empty( $conf['keshas'] ) && $lifetime > 0 && !in_array( strtolower( substr( $query, 0, 6 ) ), array(
			'delete',
			'insert',
			'update'
		) )
		) {

			// Tikrinam ar kešavimas įjungtas ir ar kešas egzistuoja
			if ( is_file( $keshas ) && filemtime( $keshas ) > $_SERVER['REQUEST_TIME'] - $lifetime ) {
				// Užkraunam kešą
				include ( $keshas );
			} else {

				// Įrašom į kešo failą
				$mysql_num++;

				$sql = mysqli_query( $prisijungimas_prie_mysql, $query ); // or die(mysqli_error($prisijungimas_prie_mysql));
				if ( mysqli_error($prisijungimas_prie_mysql) ) {
					mysqli_query( $prisijungimas_prie_mysql, "INSERT INTO `" . LENTELES_PRIESAGA . "logai` (`action` ,`time` ,`ip`) VALUES (" . escape( "MySql error:  " . mysqli_error($prisijungimas_prie_mysql) . " query: " . $query ) . ",'" . time() . "', '" . escape( getip() ) . "');" );
				}
				// Jeigu užklausoje nurodyta, kad reikia tik vieno įrašo tai nesudarom masyvo.
				if ( substr( strtolower( $query ), -7 ) == 'limit 1' ) {
					$return = mysqli_fetch_assoc( $sql );
				} else {
					while ( $row = mysqli_fetch_assoc( $sql ) ) {
						$return[] = $row;
					}
				}

				$fh = fopen( $keshas, 'wb' ) or die( "Išvalyk <b>/sandeliukas</b> bylą" );

				// Reikia užrakinti failą, kad du kartus neįrašytų
				if ( flock( $fh, LOCK_EX ) ) { // užrakinam
					fwrite( $fh, '<?php $return = ' . var_export( $return, TRUE ) . '; ?>' );
					flock( $fh, LOCK_UN ); // atrakinam
				} else {
					echo "Negaliu užrakinti failo !";
				}

				// Baigiam failo įrašymą
				fclose( $fh );
			}

			return $return;
		} else {
			$mysql_num++;

			$sql = mysqli_query( $prisijungimas_prie_mysql, $query ); // or die(mysqli_error($prisijungimas_prie_mysql));
			if ( mysqli_error($prisijungimas_prie_mysql) ) {
				mysqli_query( $prisijungimas_prie_mysql, "INSERT INTO `" . LENTELES_PRIESAGA . "logai` (`action` ,`time` ,`ip`) VALUES (" . escape( "MySql error:  " . mysqli_error($prisijungimas_prie_mysql) . " query: " . $query ) . ",'" . time() . "', '" . escape( getip() ) . "');" );
			}
			if ( in_array( strtolower( substr( $query, 0, 6 ) ), array( 'delete', 'insert', 'update' ) ) ) {
				if ( in_array( strtolower( substr( $query, 0, 6 ) ), array( 'insert' ) ) ) {
					$return = mysqli_insert_id( $prisijungimas_prie_mysql );
				} else {
					$return = mysqli_affected_rows( $prisijungimas_prie_mysql );
				}
			} else {
				if ( substr( strtolower( $query ), -7 ) == 'limit 1' ) {
					$return = mysqli_fetch_assoc( $sql );
				} else {
					while ( $row = mysqli_fetch_assoc( $sql ) ) {
						$return[] = $row;
					}
				}
			}

		}

		return $return;
	}
}

/**
 * Suskaičiuojam kiek nurodytoje lentelėje yra įrašų
 *
 * @param string $table
 * @param string $where
 * @param string $as
 *
 * @return int
 */
if(! function_exists('kiek')) {
	function kiek( $table, $where = '', $as = "viso" ) {

		$viso = mysql_query1( "SELECT count(*) AS `$as` FROM `" . LENTELES_PRIESAGA . $table . "` " . $where . " limit 1", 60 );

		return ( isset( $viso[$as] ) && $viso[$as] > 0 ? (int)$viso[$as] : (int)0 );
	}
}

//Insert SQL - supaprastina duomenų Ä¯terpimą, paduodam lentlÄ—s pavadinimą ir kitu argumentu asociatyvų masyvą
if(! function_exists('insert')) {
	function insert( $table, $array ) {

		return 'INSERT INTO `' . LENTELES_PRIESAGA . $table . '` (' . implode( ', ', array_keys( $array ) ) . ') VALUES (' . implode( ', ', array_map( 'escape', $array ) ) . ')';
	}
}