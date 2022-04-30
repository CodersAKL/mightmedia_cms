<?php



function createTable($array = [])
{
	global $mmdb;

	// $array = [
	// 	'name'	=>	'',
	// 	'columns'	=>	[
	// 		'name'	=>	[
	// 			'type'		=>	'text',
	// 			'null'		=>	false,
	// 			'default'	=>	null,
	// 			'increment'	=>	false,
	// 			'collation'	=>	'utf8mb4_general_ci',
	// 		]
	// 	],
	// 	'engine'	=>	'InnoDB', // MyISAM/InnoDB
	// 	'charset'	=>	'utf8mb4_general_ci',
	// 	'primary'	=>	'id', // ?

	// ];

	if(empty($array) && empty($array['columns'])) {
		return false;
	}

	// make a string
	$columns = [];

	foreach ($array['columns'] as $columnKey => $column) {
		$default		= ! empty($column['default']) && is_string($column['default']) ? 'DEFAULT ' . $column['default'] . '' : '';
		$null			= ! empty($column['null']) && $column['null'] ? 'NULL ' : 'NOT NULL ';
		$increment		= ! empty($column['increment']) && $column['increment'] ? 'AUTO_INCREMENT ' : '';

		$columns[]		= '`' . $columnKey . '` ' . $column['type'] . ' ' . $default . $null . $increment;
	}

	// indexes
	if(isset($array['primary']) && $array['primary']) {
		$columns[] = ' PRIMARY KEY (`' . $array['primary'] . '`)';
	}

	// check if table exists
	// if($mmdb->run(''))

	// create sql string
	$sqlCreate = 'CREATE TABLE IF NOT EXISTS `' . $array['name'] . '` (' . implode(', ', $columns) . ') ENGINE=' . $array['engine'] . ' DEFAULT CHARSET=' . $array['charset'] . ';';

	// create table
	$mmdb->run($sqlCreate);

	// check if table exists
	$sqlCheck = "SHOW TABLES LIKE '" . $array['name'] . "';";
	
	return $mmdb->run($sqlCheck);
}

function dbInsert(string $table, array $data, string $fieldGet = null)
{
	global $mmdb;

	$date = new DateTime();

	$defaultData = [
		'created' => $date->format(TIME_FORMAT),
		'updated' => $date->format(TIME_FORMAT),
	];

	$data = array_merge($defaultData, $data);

	if(! empty($fieldGet)) {
		return $mmdb->insertGet($table, $data, $fieldGet);
	}

	return $mmdb->insert($table, $data);
}

function dbUpdate($table, $data = [], $where = [])
{
	global $mmdb;

	$date = new DateTime();

	$defaultData = [
		'updated' => $date->format(TIME_FORMAT),
	];

	$data = array_merge($defaultData, $data);

	return $mmdb->update($table, $data, $where);
}

function dbDelete($table, $data = [])
{
	global $mmdb;

	return $mmdb->delete($table, $data);
}

/**
 * dbSelect
 *
 * @param  string $table
 * @param  mixed $where
 * @param  mixed $columns
 * @param  int $limit
 * @param  mixed $offset
 * @return mixed
 */
function dbSelect(string $table, array $where = null, array $columns = null, $limit = 0, $offset = null)
{
	global $mmdb;

	$string = '';
	$i 		= 0;

	if (! empty($where)) {
		
		foreach ($where as $column => $value) {
			$i++;

			if($i == 1) {
				$string .= " WHERE `" . $column . "` = '" . $value . "'";
			} else {
				$string .= " AND `" . $column . "` = '" . $value . "'";
			}
		}
	}

	if($limit) {
		if(! empty($offset)) {
			$string .= " LIMIT " . $offset . ", " . $limit;
		} else {
			$string .= " LIMIT " . $limit;
		}
	}

	$queryColumns = ! empty($columns) ? implode(', ', $columns) : '*';

	$query = 'SELECT ' . $queryColumns . ' FROM `' . $table . '`' . $string;

	try {
		return $mmdb->run($query);
	} catch (exception $e) {
		die('dbSelect: ' . $e);
	}
}

function dbRow(string $table, array $where, array $columns = null)
{

	try {
		return dbSelect($table, $where, $columns, 1)[0];
	} catch (exception $e) {
		die('dbRow: ' . $e);
	}
}

function dbCount(string $table, string $colum = '*', array $where = null)
{
	global $mmdb;

	$string = '';
	$i 		= 0;

	if (! empty($where)) {
		foreach ($where as $column => $value) {
			$i++;

			if($i = 1) {
				$string .= " WHERE `" . $column . "` = '" . $value . "'";
			} else {
				$string .= " AND `" . $column . "` = '" . $value . "'";
			}
		}
	}

	$queryColumn = ! empty($column) ? $column : '*';

	$query = 'SELECT COUNT(' . $queryColumn . ') FROM `' . $table . '`' . $string;

	try {
		return $mmdb->single($query);
	} catch (exception $e) {
		die('dbCount: ' . $e);
	}
}

// OLD-----
/**
 * Sutvarko SQL užklausą
 *
 * @param string $sql
 *
 * @return string escaped
 */
// TODO: can we get solution to escape strings?
if(! function_exists('escape')) {
	function escape( $sql ) {
		// global $prisijungimas_prie_mysql;
		// // Stripslashes
		// $sql = stripslashes($sql);
		
		// if ( !isNum( $sql ) || $sql[0] == '0' ) {
		// 	if ( !isNum( $sql ) ) {
		// 		$sql = "'" . @mysqli_real_escape_string( $prisijungimas_prie_mysql, $sql ) . "'";
		// 	}
		// }

		return $sql;
	}
}

/**
 * MySQL queries
 *
 * @param string $query
 *
 * @return array
 */
if(! function_exists('dbQuery')) {
	function dbQuery($query) {
		global $mmdb;

		// trigger_error('Deprecated function called. Use `dbQuery` instead.', E_USER_NOTICE);
		//add LOGS

		return $mmdb->run($query);
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