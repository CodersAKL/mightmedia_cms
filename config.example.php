<?php
if (basename($_SERVER['PHP_SELF']) == 'config.php') { 
    die('Direct file access is prohibited');
}

define('DEBUG', false);
define('TIME_ZONE', '{{zone}}');
/**
 * Database connection config
 */
define('DB_HOSTNAME', '{{host}}');
define('DB_USERNAME', '{{user}}');
define('DB_PASSWORD', '{{pass}}');
define('DB_DATABASE', '{{db}}');

define('LENTELES_PRIESAGA', '{{prefix}}');
define('SECRET', '{{secret}}');
define('MAIN_URL', '{{main_url}}');
