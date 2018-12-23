<?php

if(! function_exists('isExtensionInstalled')) {
    function isExtensionInstalled($name)
    {

		$sql = "SELECT EXISTS( SELECT * FROM `" . LENTELES_PRIESAGA . "extensions` WHERE `name` = " . escape($name) . ") AS 'status'";

		if ($result =  mysql_query1($sql)){
			return ($result[0]['status'] == 1) ? true : false;
        }
        
        return false;
	}

}

if(! function_exists('getExtensionStatus')) {
	function getExtensionStatus($name)
	{
		$sql = "SELECT `status` FROM `" . LENTELES_PRIESAGA . "extensions` WHERE `name` = " . escape($name);

		if ($result =  mysql_query1($sql)) {
			return ($result[0]['status'] == 1) ? true : false;
        }
        
        return false;
	}
}

if(! function_exists('getActiveExtensions')) {
    function getActiveExtensions()
    {

        $sql = "SELECT * FROM `" . LENTELES_PRIESAGA . "extensions` WHERE `status` = '1'";
        
		if ($result =  mysql_query1($sql)){
			return $result;
        }
        
        return false;
	}
}
