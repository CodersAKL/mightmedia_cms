<?php
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

		global $mmdb;

		// trigger_error('Deprecated function called. Use `dbQuery` instead.', E_USER_NOTICE);
		//add LOGS
		try {
			return $mmdb->run($query);
		} catch (exception $e) {
			d($e);
		}

		
	}
}
