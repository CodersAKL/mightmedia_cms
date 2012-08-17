<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: projektas $
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision: 965 $
 * @$Date: 2008-06-16 11:46:49 +0300 (Pr, 16 Bir 2008) $
 **/

session_start();
include_once ("priedai/conf.php");
include_once ("priedai/prisijungimas.php");

// Tikrinam su ajax ar nėra naujų laiškų.
// @TODO floodo apsauga
if (isset($_GET) && !empty($_GET)) {

	if (isset($_GET['pm']) && !empty($_GET['pm']) && isset($_SESSION['username']) && !empty($_SESSION['username'])) {
    $msg = mysql_fetch_assoc(mysql_query1(
    "SELECT SQL_CACHE ".LENTELES_PRIESAGA."private_msg.id as `msg_id`, `from`, `title`, `msg`, `" . LENTELES_PRIESAGA . "users`.`nick`, `" . LENTELES_PRIESAGA . "users`.`email` as email  
    FROM `".LENTELES_PRIESAGA."private_msg` Inner Join `" . LENTELES_PRIESAGA . "users` ON `" . LENTELES_PRIESAGA . "private_msg`.`from` = `" . LENTELES_PRIESAGA . "users`.`nick`
    WHERE `to`=".escape($_SESSION['username'])." AND `read`='NO' order by date desc limit 1"));

    if ($msg) {
    echo '
      var cur_title = new String(document.title);
      document.title = "(Nauja žinutė) - "+cur_title;
      $(\'<div><table><tr><th width="70" height="70" align="center" vlign="top">'.trimlink(input(strip_tags($msg['from'])),30).'<br />'.avatar($msg['email'],50).'</th><td><b>'.trimlink(input(strip_tags($msg['title'])),50).'</b>:<br />'.trimlink(input(strip_tags(preg_replace("/[\n\r]/","",trim($msg['msg'])))),100).' <br /><a href="?id,'.$conf['puslapiai']['pm.php']['id'].';v,'.$msg['msg_id'].'">Skaityti laišką...</a></td></tr></table></div>\').toaster({position: \'br\', closable: true, sticky: false, timeout: 15}).click(function(){alert(\'bandom redirectinti iki laiško\');});
    ';
    }
	}

}

?>