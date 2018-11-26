<?php
// Diegimo pabaiga
if (! empty($_POST) && isset($_POST['finish'])) {
    $zone    = (isset($_SESSION['time_zone']) ? $_SESSION['time_zone'] : 'Europe/Vilnius');
    $configFile = file_get_contents(ROOT . 'priedai/conf.example.php');
    // Unikalus kodas, naudojamas svetainės identifikacijai.
    $secret = md5(uniqid(rand(), true));
    var_dump(ROOT . 'priedai/conf.example.php');
    
    $configFile = str_replace('{{zone}}', $zone, $configFile);
    $configFile = str_replace('{{host}}', $_SESSION['mysql']['host'], $configFile);
    $configFile = str_replace('{{user}}', $_SESSION['mysql']['user'], $configFile);
    $configFile = str_replace('{{pass}}', $_SESSION['mysql']['pass'], $configFile);
    $configFile = str_replace('{{db}}', $_SESSION['mysql']['db'], $configFile);
    $configFile = str_replace('{{prefix}}', $_SESSION['mysql']['prefix'], $configFile);
    $configFile = str_replace('{{secret}}', $secret, $configFile);
    $configFile = str_replace('{{email}}', $_SESSION['admin']['email'], $configFile);
  
    $newConfig = ROOT . 'priedai/conf.php';
    if (! $newConfigFile = fopen($newConfig, 'w')) {
		die($lang['setup']['cant_open'] . " (" . $newConfig . ")");
	}
	if (fwrite($newConfigFile, $configFile) === false) {
		die($lang['setup']['cant_write'] . " (" . $newConfig . ")");
    }
    
	fclose($newConfigFile);
    unset($newConfigFile);
  

	// @chmod("index.php", 0777 );
	// unlink( "index.php" );

	// header( "Location: ".  ROOT . "index.php" );
}
