<?php
if(isset($_POST['msg'])){
include('../../priedai/conf.php');
echo "<p>".bbcode($_POST['msg'])."</p>";
}
?>