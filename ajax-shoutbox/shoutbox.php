<?php
ob_start();
session_start();
include_once("priedai/conf.php");
include_once("priedai/prisijungimas.php");

function getContent($num){
	$res = mysql_query1("SELECT SQL_CACHE `" . LENTELES_PRIESAGA . "chat_box`.*,`" . LENTELES_PRIESAGA . "users`.`nick`,`" . LENTELES_PRIESAGA . "users`.`levelis`,`" . LENTELES_PRIESAGA . "users`.`email`
FROM `" . LENTELES_PRIESAGA . "chat_box` Inner Join `" . LENTELES_PRIESAGA . "users` ON `" . LENTELES_PRIESAGA . "chat_box`.`niko_id` = `" . LENTELES_PRIESAGA . "users`.`id`
ORDER BY `time` DESC LIMIT ".$num."");
		return $res;
}
function insertMessage($nick, $msg, $nick_id=0){

			$res=mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "chat_box` (`nikas`, `msg`, `time`, `niko_id`) VALUES (" . escape($nick) . ", " . escape($msg) . ", NOW(), " . escape($nick_id) . ");");

		return $res;
}

/******************************
	MANAGE REQUESTS
/******************************/
if(!$_POST['action']){
	//We are redirecting people to our shoutbox page if they try to enter in our shoutbox.php
	header ("Location: index.php"); 
}
else{
	//$link = connect(HOST, USER, PASSWORD);
	switch($_POST['action']){
		case "update":
			$res = getContent(8);
			$result='';
			$i=0;
			
			foreach($res as $row){
			$i++;
			if (isset($_SESSION['level']) && ($_SESSION['level'] == 1 || (isset($_SESSION['mod']) && strlen($_SESSION['mod']) > 1)) && isset($conf['puslapiai']['deze.php']['id'])) {
				$extras = "
        <a title='{$lang['admin']['delete']}' href='?id," . $conf['puslapiai']['deze.php']['id'] . ";d," . $row['id'] . "'><img src='images/icons/control_delete_small.png' alt='[d]' class='middle' border='0' /></a>
        <a title='{$lang['admin']['edit']}' href='?id," . $conf['puslapiai']['deze.php']['id'] . ";r," . $row['id'] . "'><img src='images/icons/brightness_small_low.png' alt='[r]' class='middle' border='0' /></a>
        
      ";
			}else $extras='';
			$tr=(is_int($i / 2)?'tr2':'tr');
				$result .= 	'<div class="'.$tr.'">' . user($row['nick'], $row['niko_id'], $row['levelis']).$extras.'<br />
			' . smile(bbchat($row['msg'])) . '<br /></div>';
			}
			echo $result;
			break;
		case "insert":
			echo insertMessage($_SESSION['username'], $_POST['message'],$_SESSION['id']);
			break;
	}
	
}


?>