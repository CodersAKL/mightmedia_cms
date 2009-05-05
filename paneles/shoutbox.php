<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author$
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision$
 * @$Date$
 **/
if (isset($_POST['chat_box']) && !empty($_POST['chat_box']) && !empty($_POST['chat_msg']) && isset($_SESSION['username'])) {
	$msg = htmlspecialchars($_POST['chat_msg']);
	$nick = $_SESSION['username'];
	$nick_id = $_SESSION['id'];
	mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "chat_box` (`nikas`, `msg`, `time`, `niko_id`) VALUES (" . escape($nick) . ", " . escape($msg) . ", NOW(), " . escape($nick_id) . ");");
	mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "chat_box` WHERE time < (".time()." - INTERVAL 31 DAY)");
}
function chatbox() {
	global $conf, $lang;
	$extra = '';
	if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
		$chat_box = '<blockquote><form name="chat_box" action="" method="post">
                <textarea name="chat_msg" rows="3" cols="10" style="width:95%"></textarea>
                <br />
                <input type="submit" name="chat_box" value="' . $lang['sb']['send'] . '" />
                </form>
                ';
	} else {
		$chat_box = '<blockquote><textarea name="chat" rows="3" cols="10" style="width:95%" disabled="disabled">' . $lang['system']['pleaselogin'] . '</textarea>
                <br />
                <input type="submit" name="chat_box" value="' . $lang['sb']['send'] . '" disabled="disabled" />
        ';
	}
	$chat_box .= "<hr />";
	$extras = '';
	$chat = mysql_query1("SELECT SQL_CACHE `" . LENTELES_PRIESAGA . "chat_box`.*,`" . LENTELES_PRIESAGA . "users`.`nick`,`" . LENTELES_PRIESAGA . "users`.`levelis`
FROM `" . LENTELES_PRIESAGA . "chat_box` Inner Join `" . LENTELES_PRIESAGA . "users` ON `" . LENTELES_PRIESAGA . "chat_box`.`niko_id` = `" . LENTELES_PRIESAGA . "users`.`id`
ORDER BY `time` DESC LIMIT " . escape((int)$conf['Chat_limit']));
$i=0;
	while ($row = mysql_fetch_assoc($chat)) {
	$i++;
		if ($_SESSION['level']==1||(isset($_SESSION['mod'])&& strlen($_SESSION['mod'])>1)&& isset($conf['puslapiai']['deze.php']['id'])) {
			$extras = "
			<a title='{$lang['admin']['delete']}' href='?id," . $conf['puslapiai']['deze.php']['id'] . ";d," . $row['id'] . "'><img src='images/icons/control_delete_small.png' alt='[d]' class='middle' border='0' /></a>
			<a title='{$lang['admin']['edit']}' href='?id," . $conf['puslapiai']['deze.php']['id'] . ";r," . $row['id'] . "'><img src='images/icons/brightness_small_low.png' alt='[r]' class='middle' border='0' /></a>
			
		";
		}
		if(is_int($i/2))$tr="2"; else $tr="";
		$chat_box .= '<div class="tr'.$tr.'">	
		' . user($row['nick'], $row['niko_id'], $row['levelis']) . $extras . ' <br />
			' . wrap(smile(bbchat($row['msg'])), 18) . '<br /></div>
		';
	}

	//if (isset($_SESSION['username']) && isset($conf['puslapiai']['deze.php']['id'])) {
		$chat_box .= "<a href='?id," . $conf['puslapiai']['deze.php']['id'] . "' >{$lang['sb']['archive']}</a>";
	//}
	return $chat_box . '</blockquote>';
}

$text = chatbox();

?>
