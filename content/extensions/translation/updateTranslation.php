<?php
include_once ( "../../../config.php" );
include_once ( "../../../core/functions/functions.core.php" );
include_once ( "../../../core/functions/functions.db.php" );
var_dump($_GET);
if (isset($_GET['group']) && isset($_GET['key']) && isset($_GET['newValue'])){
    
    $newValue = $_GET['newValue'];
    if ( $newValue !== 'null'){
        $group =  $_GET['group'];
        $key =  $_GET['key'];
        $updateTime = time(); 
        $kalba = lang();
        
        $sql = "INSERT INTO  `" . LENTELES_PRIESAGA . "translations` (`group`, `key`, `value`, `lang`, `last_update`) VALUES (" . $group  . ", " .  $key . ", " .  $newValue  . ", " .  $kalba  . ", " .  $updateTime . ")";
        echo $sql;
        if ($result = mysql_query1($sql)){
            unset($sql);
            return $newValue;
        } else {
            echo 'klaida';
        }
    }

}
