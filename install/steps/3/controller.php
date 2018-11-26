<?php
// Duomenų bazės prisijungimo tikrinimo ir lentelių sukūrimo dalis
if ( isset( $_POST['next_msyql'] ) ) {
	$host   = strip_tags( $_POST['host'] );
	$user   = strip_tags( $_POST['user'] );
	$pass   = strip_tags( $_POST['pass'] );
	$db     = strip_tags( $_POST['db'] );
	$prefix = ( isset( $_POST['prefix'] ) ? strip_tags( $_POST['prefix'] ) : random() );

	$_SESSION['mysql']['host']   = $host;
	$_SESSION['mysql']['user']   = $user;
	$_SESSION['mysql']['pass']   = $pass;
	$_SESSION['mysql']['db']     = $db;
	$_SESSION['mysql']['prefix'] = $prefix;

	/**
	 * Reikalinga papildomai patikrinti (TODO:)
	 * 1. Ar tinkamas hostas
	 * 2. Ar useris ir pass veikia
	 * 3. Reikalinga funkcija kuri nuskaitytų sql.sql failą tiek local tiek remote. TUri būti universalus ir veikti ant SAFE_MODE režimo
	 */  
	
	if (! $mysql_con = mysqli_connect($host, $user, $pass)) {
        $mysql_info = '<b>' . $lang['system']['error'] . '</b> ' . mysqli_connect_error() . '<br/>
        <b> #</b>' . mysqli_connect_errno();

        $next_mysql = [
            'name'  => 'next_msyql',
            'value' => $lang['setup']['try_again'],
            'type'  => 'submit'
        ];

        return;
    }
    
    mysqli_select_db($mysql_con, $db);

	if (mysqli_errno($mysql_con) == 1049) {
        $next_mysql = [
            'name'  => 'next_msyql',
            'value' => $lang['setup']['crete_db'],
            'type'  => 'submit'
        ];
        
        $mysql_con2 = mysqli_connect( $host, $user, $pass );
		mysqli_query( $mysql_con2, "CREATE DATABASE `$db` DEFAULT CHARACTER SET utf8 COLLATE utf8_lithuanian_ci" );
		mysqli_select_db( $mysql_con2, $db );
	} else {
		$mysql_info = '<strong>' . $lang['setup']['mysql_connected'] . '</strong><br />';

		if (is_file(ROOT . 'install/sql.sql')) {
            $suffix = ($_SESSION['language'] == 'en.php' ? '(en.php)' : '');
			$sql    = file_get_contents( 'sql' . $suffix . '.sql' );
		} else {
            die('No SQL file: ' . ROOT . 'install/sql.sql');
        }

		// Paruošiam užklausas
		$sql = str_replace( "CREATE TABLE IF NOT EXISTS `", "CREATE TABLE IF NOT EXISTS `" . $prefix, $sql );
		$sql = str_replace( "CREATE TABLE `", "CREATE TABLE IF NOT EXISTS `" . $prefix, $sql );
		$sql = str_replace( "INSERT INTO `", "INSERT INTO `" . $prefix, $sql );
		$sql = str_replace( "UPDATE `", "UPDATE `" . $prefix, $sql );
		$sql = str_replace( "ALTER TABLE `", "ALTER TABLE `" . $prefix, $sql );

		// Prisijungiam prie duombazės
		$mysql_con3 = mysqli_connect( $host, $user, $pass );
		mysqli_select_db( $mysql_con3, $db );
		mysqli_query( $mysql_con3, "SET NAMES utf8mb4" );

		// Atliekam SQL apvalymą
		$match = '';
		preg_match_all( "/(?:CREATE|UPDATE|INSERT|ALTER).*?;[\r\n]/s", $sql, $match );

		$mysql_info  = "<ol>";
		$mysql_error = 0;
		foreach ( $match[0] as $key => $val ) {
			if ( !empty( $val ) ) {
				$query = mysqli_query( $mysql_con3, $val );
				if ( !$query ) {
					$mysql_info .= "<li><b>" . $lang['system']['error'] . " " . mysqli_errno($mysql_con3) . "</b> " . mysqli_error($mysql_con3) . "<hr><b>{$lang['setup']['query']}:</b><br/>" . $val . "</li><hr>";
					$mysql_error++;
				}
			}
		}
		$mysql_info .= "</ol>";

		if ( $mysql_error == 0 ) {
            $mysql_info = $lang['setup']['mysql_created'];
            $next_mysql = [
                'value' => $lang['setup']['next'],
                'type'  => 'reset',
                'go'    => 4
            ];
		} else {
            $next_mysql = [
                'value' => $lang['setup']['try_again'],
                'type'  => 'reset',
                'go'    => 3
            ];
		}

	}
} else {
    $next_mysql = [
        'name'  => 'next_msyql',
        'value' => $lang['setup']['create_tables'],
        'type'  => 'submit'
    ];
}