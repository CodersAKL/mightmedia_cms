<?php
session_start();
require_once('../../../../../../priedai/conf.php');
require_once('../../../../../../priedai/funkcijos.php');

if (!isset($_SESSION[SLAPTAS]['level']) || $_SESSION[SLAPTAS]['level'] != 1 || empty($_POST['file']) || basename($_POST['file']) == '.htaccess' ) {
	if ( basename($_POST['file']) == '.htaccess' ) {
		ban( getip(), $lang['system']['nohacking'] );
	} else {
		die('eik lauk..');
	}
}
rename(ROOTAS.'siuntiniai/' . $_POST['file'], ROOTAS.'sandeliukas/' . basename($_POST['file']));
