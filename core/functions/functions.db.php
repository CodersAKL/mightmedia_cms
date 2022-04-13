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
		$sql = stripslashes($sql);
		
		if ( !isNum( $sql ) || $sql[0] == '0' ) {
			if ( !isNum( $sql ) ) {
				$sql = "'" . @mysqli_real_escape_string( $prisijungimas_prie_mysql, $sql ) . "'";
			}
		}

		return $sql;
	}
}

/**
 * MySQL queries
 *
 * @param string $query
 * @param int    $lifetime
 *
 * @return array
 */
if(! function_exists('dbQuery')) {
	function dbQuery($query, $lifetime = 0) {

		global $mysql_num, $prisijungimas_prie_mysql, $conf;

		$return = [];

		$mysql_num++;

		$sql = mysqli_query($prisijungimas_prie_mysql, $query);

		if (mysqli_error($prisijungimas_prie_mysql)) {
			mysqli_query($prisijungimas_prie_mysql, "INSERT INTO `" . LENTELES_PRIESAGA . "logai` (`action` ,`time` ,`ip`) VALUES (" . escape( "MySql error:  " . mysqli_error($prisijungimas_prie_mysql) . " query: " . $query ) . ",'" . time() . "', '" . escape( getip() ) . "');" );
		}
		
		if (in_array(strtolower(substr($query, 0, 6)), ['delete', 'insert', 'update'])) {
			if (in_array(strtolower(substr($query, 0, 6)), ['insert'])) {
				$return = mysqli_insert_id($prisijungimas_prie_mysql);
			} else {
				$return = mysqli_affected_rows($prisijungimas_prie_mysql);
			}
		} else {
			if (substr(strtolower($query), -7) == 'limit 1') {
				$return = mysqli_fetch_assoc($sql);
			} else {
				while ($row = mysqli_fetch_assoc($sql)) {
					$return[] = $row;
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