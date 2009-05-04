<?php
if (!defined("OK") || !ar_admin(basename(__file__)))
{
    header('location: ?');
    exit();
}
if (isset($url['p']) && isnum($url['p']) && $url['p'] > 0)
{
    $p = escape(ceil((int)$url['p']));
}
else
{
    $p = 0;
}
$limit = 50;
$viso = kiek("private_msg");

//Nustatom pagal ka rusiuosim
if (isset($url['o']) && !empty($url['o']))
{
    switch ($url['o'])
    {
        case "{$lang['admin']['pm_sender']}":
            {
                $order = "`from`";
                break;
            }
        case "#":
            {
                $order = "`read`";
                break;
            }
        case "{$lang['admin']['pm_reciever']}":
            {
                $order = "`to`";
                break;
            }
        case "{$lang['admin']['pm_subject']}":
            {
                $order = "`title`";
                break;
            }
        case "{$lang['admin']['pm_date']}":
            {
                $order = "`date`";
                break;
            }
        default:
            {
                $order = "`id`";
                break;
            }
    }
}
//nustatom mazejancia ar didejancia tvarka
if (isset($url['w']) && !empty($url['w']))
{
    switch ($url['w'])
    {
        case "d":
            {
                $order .= " DESC";
                break;
            }
        case "a":
            {
                $order .= " ASC";
                break;
            }
        default:
            {
                $order .= " ASC";
                break;
            }
    }
}
else
{
    $order = "`id` DESC";
}

// Trinam laiska
if (isset($url['d']) && isnum($url['d']))
{
    if ($url['d'] == "0" && isset($_POST['to']) && !empty($_POST['to']) && $_POST['del_all'] == $lang['admin']['delete'])
    {
        $sql = mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "private_msg` WHERE `to`=" . escape($_POST['to']) . "") or die(mysql_error());
        //$i = mysql_affected_rows();
        if ($sql)
        {
            msg($lang['system']['done'], "<b>" . input($_POST['to']) . "</b> {$lang['admin']['pm_msgsdeleted']}.");
            redirect("?id,999;a," . $_GET['a'] . "", "meta");
        }
        else
        {
            klaida($lang['system']['error'], $lang['admin']['pm_deleteerror']);
        }
    }
    if ($url['d'] == "0" && isset($_POST['from']) && !empty($_POST['from']) && $_POST['del_all'] == $lang['admin']['delete'])
    {
        $sql = mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "private_msg` WHERE `from`=" . escape($_POST['from']) . "") or die(mysql_error());
        //$i = mysql_affected_rows();
        if ($sql)
        {
            msg($lang['system']['done'], "<b>" . input($_POST['from']) . "</b> {$lang['admin']['pm_msgsdeleted']}.");
            redirect("?id,999;a," . $_GET['a'] . "", "meta");
        }
        else
        {
            klaida($lang['system']['error'], $lang['admin']['pm_deleteerror']);
        }
    }
    if (!empty($url['d']) && $url['d'] > 0)
    {
        $sql = mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "private_msg` WHERE id=" . escape((int)$url['d'])) or die(mysql_error());
        if ($sql)
        {
            msg($lang['system']['done'], "{$lang['admin']['pm_deleted']}.");
            redirect("?id,999;a," . $_GET['a'] . "", "meta");
        }
        else
        {
            klaida($lang['system']['error'], $lang['admin']['pm_deleteerror']);
        }
    }
    //header("Location: ".url('d,0'));
}


//perziureti laiska
if (isset($url['v']))
{
    if (!empty($url['v']) && (int)$url['v'] > 0)
    {
        $sql = mysql_fetch_assoc(mysql_query1("SELECT `msg`, `from`,`to`, `title` FROM `" . LENTELES_PRIESAGA . "private_msg` WHERE `id`=" . escape((int)$url['v']) . " LIMIT 1"));
        if (count($sql) > 0)
        {
            $laiskas = "
				<b>{$lang['admin']['pm_sender']}:</b>  " . $sql['from'] . "<br><b>{$lang['admin']['pm_reciever']}:</b> " . $sql['to'] . "<br> <b>{$lang['admin']['pm_subject']}:</b> " . (isset($sql['title']) && !empty($sql['title']) ? input(trimlink($sql['title'], 40)) : $lang['admin']['pm_nosubject']) . "<br><br><b>{$lang['admin']['pm_message']}:</b><br>" . bbcode(wrap($sql['msg'], 40)) .
                "<br><br>
				<form name=\"replay_pm\" action='' method=\"post\">
					 <input type=\"button\" value=\"{$lang['admin']['delete']}\" onclick=\"location.href='" . url("d," . $url['v'] . ";v,0") . "'\"/>
				</form>
				";
            lentele($lang['admin']['pm_message'], $laiskas);
        }
        else
        {
            klaida($lang['system']['error'], $lang['admin']['pm_nomessage']);
        }
    }
}


//paruosiam klase lenteliu paisymui
include_once ("priedai/class.php");

//laisku saras
unset($info);
$info = array();
$sql = mysql_query1("
			SELECT SUBSTRING(`msg`,1,50) AS `msg`,
			(SELECT `id` AS `nick_id` FROM `" . LENTELES_PRIESAGA . "users` WHERE `nick`= `" . LENTELES_PRIESAGA . "private_msg`.`from`) AS `from_id`,
			(SELECT `id` AS `nick_id` FROM `" . LENTELES_PRIESAGA . "users` WHERE `nick`= `" . LENTELES_PRIESAGA . "private_msg`.`to`) AS `to_id`,
			`" . LENTELES_PRIESAGA . "private_msg`.`id`, `" . LENTELES_PRIESAGA . "private_msg`.`from` AS `from_nick`, `" . LENTELES_PRIESAGA . "private_msg`.`to` AS `to_nick`, `" . LENTELES_PRIESAGA . "private_msg`.`title`, `" . LENTELES_PRIESAGA . "private_msg`.`read`, `" . LENTELES_PRIESAGA . "private_msg`.`date`
			FROM `" . LENTELES_PRIESAGA . "private_msg` ORDER BY $order LIMIT " . escape($p) . "," . $limit);
if ($sql)
{
    while ($row = mysql_fetch_assoc($sql))
    {
        if ($row['read'] == "NO")
        {
            $extra = "<img src='images/pm/pm_new.png' />";
        }
        else
        {
            $extra = "<img src='images/pm/pm_read.png' />";
        }

        $info[] = array("#" => $extra, "{$lang['admin']['pm_sender']}" => user($row['from_nick'], $row['from_id']), "{$lang['admin']['pm_reciever']}" => user($row['to_nick'], $row['to_id']), "{$lang['admin']['pm_subject'] }" => "<a href=\"?id,999;a," . $_GET['a'] . ";v," . $row['id'] . "\" title=\"<b>Laiško ištrauka:</b> " . input(trim(strip_tags(str_replace(array('[', ']'), '', $row['msg'])))) .
            "...\" style=\"display:block\">" . (isset($row['title']) && !empty($row['title']) ? trimlink(input($row['title']), 10) : 'Be temos') . "</a>", //"Pavadinimas"=>"<a href=\"?id,46;a,20;v,".$row['id']."\" title=\"header=[Laiško ištrauka:] body=[".input(bbcode($sql['msg']))."...] fade=[on]\">".input($row['title'])."</a>",
            "{$lang['admin']['pm_date']}" => date('Y-m-d H:i:s ', $row['date']), "{$lang['admin']['action']}" => "<button onclick=\"if (confirm('{$lang['admin']['delete']}?')) window.location='" . url("d," . $row['id'] . "") . "'; else return false;\">X</button>");
    }
}
//nupiesiam laisku lentele
$bla = new Table();
if ($viso > $limit)
{
    lentele($lang['system']['pages'], puslapiai($p, $limit, $viso, 10));
}
lentele("{$lang['admin']['pm_messages']}", $bla->render($info));
if ($viso > $limit)
{
    lentele($lang['system']['pages'], puslapiai($p, $limit, $viso, 10));
}
unset($info, $row, $viso, $limit, $p);

//laisku trinimas "kam siustu laisku"
$sql = mysql_query1("SELECT count(*) AS 'viso', `to` AS 'nick' FROM `" . LENTELES_PRIESAGA . "private_msg` GROUP BY `to` ORDER BY `to`");
if (mysql_num_rows($sql) > 0)
{
    while ($row = mysql_fetch_assoc($sql))
    {
        $select[$row['nick']] = $row['nick'] . " - " . $row['viso'];
    }
    $nustatymai = array("Form" => array("action" => "?id," . $_GET['id'] . ";a," . $_GET['a'] . ";d,0", "method" => "post", "name" => "reg"), "{$lang['admin']['pm_deleteto']}:" => array("type" => "select", "value" => $select, "selected" => $_SESSION['username'], "name" => "to"), "" => array("type" => "submit", "name" => "del_all", "value" => $lang['admin']['delete']));
    $bla = new forma();
    lentele($lang['admin']['pm_deleteto'], $bla->form($nustatymai));
    unset($nustatymai, $select, $sql);
}

//laisku tinimas "nuo ko gautu"
$sql = mysql_query1("SELECT count(*) AS 'viso', `from` AS 'nick' FROM `" . LENTELES_PRIESAGA . "private_msg` GROUP BY `from` ORDER BY `from`");
if (mysql_num_rows($sql) > 0)
{
    while ($row = mysql_fetch_assoc($sql))
    {
        $select[$row['nick']] = $row['nick'] . " - " . $row['viso'];
    }
    $nustatymai = array("Form" => array("action" => "?id," . $_GET['id'] . ";a," . $_GET['a'] . ";d,0", "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "reg"), "{$lang['admin']['pm_deletefrom']}:" => array("type" => "select", "value" => $select, "selected" => $_SESSION['username'], "name" => "from"), "" => array("type" => "submit", "name" => "del_all",
        "value" => $lang['admin']['delete']));
    $bla = new forma();
    lentele($lang['admin']['pm_deletefrom'], $bla->form($nustatymai));
    unset($nustatymai, $select, $sql);
}
unset($text);
//unset($_POST);
?>
