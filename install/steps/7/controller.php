<?php
/**
 * Adress security
 *
 * @param string $url
 *
 * @return string
 */
if(! function_exists('cleanurl')) {
	function cleanurl($url) {

		$bad_entities  = ['"', "'", "<", ">", "(", ")", '\\'];
		$safe_entities = ["", "", "", "", "", "", ""];
		$url           = str_replace($bad_entities, $safe_entities, $url);

		return $url;
	}
}

/**
 * Adress cleaning
 *
 * @param string $rawUrl
 *
 * @return string
 */
if(! function_exists('urlClean')) {
	function urlClean($rawUrl) {
		$url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
		$urlPart = explode('://', $rawUrl);
		$url .= '://' . cleanurl($urlPart[1]);
		$lastSymbol = substr($url, -1);

		if($lastSymbol !== '/') {
			$url .= '/';	
		}

		return $url;
	}
}

if (!empty($_POST) && isset($_POST['main_url'])) {
	//set main Url to session
	$_SESSION['main_url'] = urlClean($_POST['main_url']);

	//finish setup
	$zone    = (isset($_SESSION['time_zone']) ? $_SESSION['time_zone'] : 'Europe/Vilnius');
    $configFile = file_get_contents(ROOT . 'priedai/conf.example.php');
    // Unique code to CMS sessions and identifications
    $secret = md5(uniqid(rand(), true));
    
    $configFile = str_replace('{{zone}}', $zone, $configFile);
    $configFile = str_replace('{{host}}', $_SESSION['mysql']['host'], $configFile);
    $configFile = str_replace('{{user}}', $_SESSION['mysql']['user'], $configFile);
    $configFile = str_replace('{{pass}}', $_SESSION['mysql']['pass'], $configFile);
    $configFile = str_replace('{{db}}', $_SESSION['mysql']['db'], $configFile);
    $configFile = str_replace('{{prefix}}', $_SESSION['mysql']['prefix'], $configFile);
    $configFile = str_replace('{{secret}}', $secret, $configFile);
    $configFile = str_replace('{{main_url}}', $_SESSION['main_url'], $configFile);
    $configFile = str_replace('{{email}}', $_SESSION['admin']['email'], $configFile);
  
    $newConfig = ROOT . 'priedai/conf.php';
    if (! $newConfigFile = fopen($newConfig, 'w')) {
		die($lang['setup']['cant_open'] . " (" . $newConfig . ")");
	}
	if (fwrite($newConfigFile, $configFile) === false) {
		die($lang['setup']['cant_write'] . " (" . $newConfig . ")");
    }
    
	fclose($newConfigFile);

	header("Location: index.php?step=7");
}