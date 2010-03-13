<?php
if(isset($_POST['msg'])){
include('../../priedai/conf.php');
include('../../stiliai/'.$conf['Stilius'].'/sfunkcijos.php');
echo "<p>".bbcode($_POST['msg'])."</p>";
}
?>