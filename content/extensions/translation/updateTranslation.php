<?php
include_once ( "../../../config.php" );
include_once ( "../../../core/inc/inc.db_ready.php");
include_once ( "../../../core/boot.php" );
if (isset($_GET['group']) && isset($_GET['key']) && isset($_GET['newValue'])){
    if ($_GET['newValue'] !== 'null'){
        $group = escape( $_GET['group'] );
        $key = escape( $_GET['key'] );
        $newValue = escape( urldecode( $_GET['newValue'] ) );
        $updateTime = time();
        $kalba = escape( lang() );
        $sqlCheckCombination = "SELECT * FROM `" . LENTELES_PRIESAGA . "translations` WHERE `group`= " . $group . " and  `key` = " . $key ;
        $combinationResult = mysql_query1($sqlCheckCombination);
        if (count($combinationResult) == 0 ){
            $sql = "INSERT INTO  `" . LENTELES_PRIESAGA . "translations` (`group`, `key`, `translation`, `lang`, `last_update`, `status`) VALUES (" . $group . ", " . $key . ", " . $newValue . ", " .  $kalba  . ", " .  $updateTime . ", 0)";
        } else {
            $sql = "UPDATE `" . LENTELES_PRIESAGA . "translations` set `translation` = " . $newValue . " WHERE `group` = " . $group . " and `key` = " . $key . " and `lang`= " . $kalba;
        }
        if ($result = mysql_query1($sql)){
            unset($sql);
            return $newValue;
        }
    }

}
