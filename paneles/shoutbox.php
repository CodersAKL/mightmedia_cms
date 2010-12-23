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

if(isset($_POST['chat_box']) && !empty($_POST['chat_box']) && !empty($_POST['chat_msg'])) {
	if(!isset($_COOKIE['komentatorius']) || (isset($_POST['name']) && $_POST['name'] != $_COOKIE['komentatorius'])) {
		setcookie("komentatorius",$_POST['name'],time() + 60 * 60 * 24 * 30);
	}
	$msg		= htmlspecialchars($_POST['chat_msg']);
	$nick_id	= (isset($_SESSION['id']) ? $_SESSION['id'] : 0);
	$nick		= (isset($_SESSION['username']) ? $_SESSION['username'] : (!empty($_POST['name']) ? $_POST['name'] : $lang['system']['guest']));

	mysql_query1("INSERT INTO `".LENTELES_PRIESAGA."chat_box`
		(`nikas`, `msg`, `time`, `niko_id`)
		VALUES (".escape($nick).", ".escape($msg).", NOW(), ".escape($nick_id).");"
	);
	mysql_query1("DELETE FROM `".LENTELES_PRIESAGA."chat_box` WHERE time < (NOW() - INTERVAL 31 DAY)");
	redirect($_SERVER['HTTP_REFERER'],"header");
}
function chatbox() {
	global $conf,$lang;
	$extra = '';
	$name = (isset($_COOKIE['komentatorius']) ? $_COOKIE['komentatorius'] : $lang['system']['guest']);
	if((isset($_SESSION['username']) && !empty($_SESSION['username'])) || $conf['kmomentarai_sveciams'] == 1) {
		$chat_box = '<form name="chat_box" action="" method="post">
		            '.((isset($conf['kmomentarai_sveciams']) && $conf['kmomentarai_sveciams'] == 1 && !isset($_SESSION['username'])) ? '<input type="text" name="name" class="submit" value="'.$name.'"/>' : '').'
                <textarea name="chat_msg" rows="3" cols="10" class="input"></textarea>
                <br />
                <input type="submit" name="chat_box" class="submit" value="'.$lang['sb']['send'].'" />
                </form>
                ';
	} else {
		$chat_box = '<textarea name="chat" rows="3" cols="10" class="input" disabled="disabled">'.$lang['system']['pleaselogin'].'</textarea>
                <br />
                <input type="submit" class="submit" name="chat_box" value="'.$lang['sb']['send'].'" disabled="disabled" />
        ';
	}
	$chat_box .= "<hr />";
	$extras = '';
	if(isset($conf['kmomentarai_sveciams']) && $conf['kmomentarai_sveciams'] == 1) {
		$chat = mysql_query1("SELECT * FROM `".LENTELES_PRIESAGA."chat_box` ORDER BY `time` DESC LIMIT ".escape((int) $conf['Chat_limit']));
	} else {
		$chat = mysql_query1("SELECT SQL_CACHE
			`".LENTELES_PRIESAGA."chat_box`.*,
			`".LENTELES_PRIESAGA."users`.`nick`,
			`".LENTELES_PRIESAGA."users`.`levelis`
			FROM `".LENTELES_PRIESAGA."chat_box`
			Inner Join `".LENTELES_PRIESAGA."users`
			ON `".LENTELES_PRIESAGA."chat_box`.`niko_id` = `".LENTELES_PRIESAGA."users`.`id`
			ORDER BY `time` DESC LIMIT ".escape((int) $conf['Chat_limit'])
		);
	}
	$i = 0;

	if(sizeof($chat) > 0) {
		foreach($chat as $row) {
			$i++;
			if(ar_admin('com')) {
				$extras = "
        <a title='{$lang['admin']['delete']}' href='".url("?id,".$conf['puslapiai']['deze.php']['id'].";d,".$row['id'])."' onclick=\"return confirm('{$lang['system']['delete_confirm']}')\"><img src='images/icons/cross_small.png' alt='[d]' class='middle' border='0' /></a>
        <a title='{$lang['admin']['edit']}' href='".url("?id,".$conf['puslapiai']['deze.php']['id'].";r,".$row['id'])."'><img src='images/icons/pencil_small.png' alt='[r]' class='middle' border='0' /></a>

      ";
			}
			if(is_int($i / 2)) {
				$tr = "2";
			} else {
				$tr = "";
			}
			$chat_box .= '<div class="tr'.$tr.'"><b>
      '.user($row['nikas'],$row['niko_id']).$extras.'</b> <br />
        '.smile(bbchat(wrap($row['msg'],18))).'<br /></div>
      ';
		}
	} else {
		$chat_box .= '';
	}

	if(puslapis('deze.php')) {
		$chat_box .= "<a href='".url("?id,".$conf['puslapiai']['deze.php']['id'])."' >{$lang['sb']['archive']}</a>";
	}
	return $chat_box;
}

$text = chatbox();

?>
