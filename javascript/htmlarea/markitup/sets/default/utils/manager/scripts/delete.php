<?php
  session_start();
 include_once('../../../../../../../../priedai/conf.php');
 include_once('../../../../../../../../priedai/prisijungimas.php');
 if(!isset($_SESSION['level']) || $_SESSION['level'] != 1)
  die('eik lauk..');
rename('../../../../../../../../siuntiniai/'.$_POST['file'], '../../../../../../../../sandeliukas/'.basename($_POST['file']));
?>