<?php 
/** 
 * @Projektas: MightMedia TVS 
 * @Puslapis: www.coders.lt 
 * @$Modified: Aivaras Cenkus - m3dis.com - ire $ 
 * @$Author: p.dambrauskas $ 
 * @copyright CodeRS ©2008 
 * @license GNU General Public License v2 
 * @$Revision: 433 $ 
 * @$Date: 2010-03-13 16:35:43 +0200 (Sat, 13 Mar 2010) $ 
 **/ 

if(isset($_POST['chat_box']) && !empty($_POST['chat_box']) && !empty($_POST['chat_msg'])) { 
    if(!isset($_COOKIE['komentatorius']) || (isset($_POST['name']) && $_POST['name'] != $_COOKIE['komentatorius'])) { 
        setcookie("komentatorius",$_POST['name'],time() + 60 * 60 * 24 * 30); 
    } 
    $msg        = htmlspecialchars($_POST['chat_msg']); 
    $nick_id    = (isset($_SESSION['id']) ? $_SESSION['id'] : 0); 
    $nick        = (isset($_SESSION['username']) ? $_SESSION['username'] : (!empty($_POST['name']) ? $_POST['name'] : $lang['system']['guest'])); 

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
           '.((isset($conf['kmomentarai_sveciams']) && $conf['kmomentarai_sveciams'] == 1 && !isset($_SESSION['username'])) ? '<br /> <input type="text" name="name" class="submit" value="'.$name.'"/><br /> ' : '').' 
                <input name="chat_msg"  class="input" style="margin-bottom:5px; width:97%;"/> 
                <input type="submit" name="chat_box" class="submit" value="'.$lang['sb']['send'].'" style="margin-bottom:5px;"/>  
                </form> 
                '; 
    } else { 
        $chat_box = ''.$lang['system']['pleaselogin'].''; 
    } 
    $chat_box .= "<hr /><br />"; 
    $extrasas = '';
     // $avat = mysql_query1("SELECT * FROM `".LENTELES_PRIESAGA."users` WHERE id=".$row['niko_id'].""; 	
    if(isset($conf['kmomentarai_sveciams']) && $conf['kmomentarai_sveciams'] == 1) { 
        $chat = mysql_query1("SELECT * FROM `".LENTELES_PRIESAGA."chat_box` ORDER BY `time` DESC LIMIT ".escape((int) $conf['Chat_limit'])); 
    } else { 


        $chat = mysql_query1("SELECT 
			" . LENTELES_PRIESAGA . "users.nick,
	        " . LENTELES_PRIESAGA . "users.email,
	        " . LENTELES_PRIESAGA . "users.id,
            `".LENTELES_PRIESAGA."chat_box`.*
            FROM `".LENTELES_PRIESAGA."chat_box` 
            Inner Join `".LENTELES_PRIESAGA."users` 
            ON `".LENTELES_PRIESAGA."chat_box`.`niko_id` = `".LENTELES_PRIESAGA."users`.`id`
            ORDER BY `time` DESC LIMIT ".escape((int) $conf['Chat_limit']) 
        ); 
    } 
    $i = 0; 
    if(sizeof($chat) > 0) { 
        foreach($chat as $row) { 
		$extrasas = "";
            $i++; 
//if(isset($_SESSION['level']) && ($_SESSION['level'] == 1 || (isset($_SESSION['mod']) && ($_SESSION['mod']) > 1)) && puslapis('deze.php')) { 
	        if(ar_admin('com') && puslapis('deze.php')) {
$extrasas = " 
<a title='{$lang['admin']['delete']}' href='".url("?id,".$conf['puslapiai']['deze.php']['id'].";d,".$row['id'])."'><img height='12' src='images/icons/cross.png' alt='[d]' class='middle' border='0' /></a> 
<a title='{$lang['admin']['edit']}' href='".url("?id,".$conf['puslapiai']['deze.php']['id'].";r,".$row['id'])."'><img height='12' src='images/icons/pencil.png' alt='[r]' class='middle' border='0' /></a> 
"; 
            } else {
			$extrasas = " ";
			}
			
      if(is_int($i / 2)) { $tr = "2"; } else { $tr = "";  } 

      $chat_box .= '
	  <div class="tr'.$tr.'">
	  <b>'.user($row['nikas'],$row['niko_id']).'</b><span style="float:right;">'.$extrasas.' </span>
	  <table><tr><td valign="top" >' . avatar($row['email'], 35) .  '</td>
	  <td>'.smile(bbchat(wrap($row['msg'],18))).'</td></tr></table>
      </div> 
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
	$text = "".chatbox().""; 

?>