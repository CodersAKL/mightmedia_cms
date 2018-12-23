<?php


if ( is_file( '../../priedai/conf.php' ) && filesize( '../../priedai/conf.php' ) > 1 ) {
    include_once ( "../../priedai/conf.php" );
    include_once ( "../../priedai/funkcijos.php" );
}
if (isset($_GET['group']) && isset($_GET['key']) && isset($_GET['newValue'])){
    if ($_GET['newValue'] !== 'null'){
        $group = escape( $_GET['group'] );
        $key = escape( $_GET['key'] );
        $newValue = escape( urldecode( $_GET['newValue'] ) );
        $updateTime = time();
        $kalba = lang();
        $sql = "INSERT INTO  `" . LENTELES_PRIESAGA . "translations` (`group`, `key`, `value`, `lang`, `last_update`) VALUES (" . $group . ", " . $key . ", " . $newValue . ", " . escape( $kalba ) . ", " .  $updateTime . ")";
        if ($result = mysql_query1($sql)){
            unset($sql);
            return $newValue;
        }
    }

}
