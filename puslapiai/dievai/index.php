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

if ($_SERVER['PHP_SELF'] == 'index.php') {
    die($lang['system']['notadmin']);
}
include_once ("priedai/prisijungimas.php");
if (!isset($_SESSION['username'])) {
    admin_login_form();
}
// Jei lanktytojas neprisijungęs arba, jei nėra administratorius
elseif (!defined("LEVEL") || LEVEL > 1 || !defined("OK") || !isset($_SESSION['username'])) {
    redirect("?home");
}

if (isset($url['a']) && isnum($url['a']) && $url['a'] > 0) {
    $aid = (int)$url['a'];
} else {
    $aid = 0;
}
if (isset($url['id']) && isnum($url['id']) && $url['id'] > 0) {
    $id = (int)$url['id'];
} else {
    $id = 0;
}

// index.php -> pokalbiai.php
$puslapis = "pokalbiai.php";

if (isset($_SESSION['username']) && $_SESSION['level'] == 1 && defined("OK")) {
    $text = "<table border=\"0\">
	<tr>
		<td >
<div>";
    $text .= "<div class=\"blokas\"><center><a href=\"?id," . $url['id'] . ";a,1\"><img src=\"images/admin/conf.png\" alt=\"{$lang['admin']['conf']}\" />{$lang['admin']['conf']}</a></center></div>";
    if (isset($conf['puslapiai']['naujienos.php']['id'])) {
        $text .= "<div class=\"blokas\"><center><a href=\"?id," . $url['id'] . ";a,2\"><img src=\"images/admin/naujienos.png\" alt=\"{$lang['admin']['news']}\" />{$lang['admin']['news']}</a></center></div>";
    }
    if (isset($conf['puslapiai']['frm.php']['id'])) {
        $text .= "<div class=\"blokas\"><center><a href=\"?id," . $url['id'] . ";a,3\"><img src=\"images/admin/forumas.png\" alt=\"{$lang['admin']['forum']}\" />{$lang['admin']['forum']}</a></center></div>";
    }
    $text .= "<div class=\"blokas\"><center><a href=\"?id," . $url['id'] . ";a,5\"><img src=\"images/admin/balsavimas.png\" alt=\"{$lang['admin']['poll']}\" />{$lang['admin']['poll']}</a></center></div>";
    $text .= "<div class=\"blokas\"><center><a href=\"?id," . $url['id'] . ";a,6\"><img src=\"images/admin/users.png\" alt=\"{$lang['admin']['users']}\" />{$lang['admin']['users']}</a></center></div>";
    if (isset($conf['puslapiai']['nuorodos.php']['id'])) {
        $text .= "<div class=\"blokas\"><center><a href=\"?id," . $url['id'] . ";a,7\"><img src=\"images/admin/nuorodos.png\" alt=\"{$lang['admin']['links']}\" />{$lang['admin']['links']}</a></center></div>";
    }
    $text .= "<div class=\"blokas\"><center><a href=\"?id," . $url['id'] . ";a,8\"><img src=\"images/admin/folder.png\" alt=\"{$lang['admin']['files']}\" />{$lang['admin']['files']}</a></center></div>";
    $text .= "<div class=\"blokas\"><center><a href=\"?id," . $url['id'] . ";a,9\"><img src=\"images/admin/panel.png\" alt=\"{$lang['admin']['blocks']}\" />{$lang['admin']['blocks']}</a></center></div>";
    //$text .="<div class=\"blokas\"><center><a href=\"?id,".$url['id'].";a,10\"><img src=\"images/admin/plugins.gif\" alt=\"{$lang['admin']['Comments']}\" /> {$lang['admin']['Comments']}</a></center></div>";
    $text .= "<div class=\"blokas\"><center><a href=\"?id," . $url['id'] . ";a,11\"><img src=\"images/admin/banai.png\" alt=\"{$lang['admin']['bans']}\" />{$lang['admin']['bans']}</a></center></div>";
    if (isset($conf['puslapiai']['pm.php']['id'])) {
        $text .= "<div class=\"blokas\"><center><a href=\"?id," . $url['id'] . ";a,20\"><img src=\"images/admin/laiskai.png\" alt=\"{$lang['admin']['messages']}\" />{$lang['admin']['messages']}</a></center></div>";
    }
    if (isset($conf['puslapiai']['straipsnis.php']['id'])) {
        $text .= "<div class=\"blokas\"><center><a href=\"?id," . $url['id'] . ";a,4\"><img src=\"images/admin/straipsniai.png\" alt=\"{$lang['admin']['Articles']}\" />{$lang['admin']['Articles']}</a></center></div>";
    }
    if (isset($conf['puslapiai']['siustis.php']['id'])) {
        $text .= "<div class=\"blokas\"><center><a href=\"?id," . $url['id'] . ";a,13\"><img src=\"images/admin/siuntiniai.png\" alt=\"{$lang['admin']['downloads']}\" />{$lang['admin']['downloads']}</a></center></div>";
    }
    $text .= "<div class=\"blokas\"><center><a href=\"?id," . $url['id'] . ";a,19\"><img src=\"images/admin/messagebox_warning.png\" alt=\"{$lang['admin']['logs']}\" />{$lang['admin']['logs']}</a></center></div>";
    $text .= "<div class=\"blokas\"><center><a href=\"?id," . $url['id'] . ";a,21\"><img src=\"images/admin/meniu.png\" alt=\"{$lang['admin']['pages']}\" />{$lang['admin']['pages']}</a></center></div>";
    if (isset($conf['puslapiai']['galerija.php']['id'])) {
        $text .= "<div class=\"blokas\"><center><a href=\"?id," . $url['id'] . ";a,22\"><img src=\"images/admin/galerija.png\" alt=\"{$lang['admin']['gallery']}\" />{$lang['admin']['gallery']}</a></center></div>";
    }
    $text .= "</div><br style=\"clear:left\"/></td>
	</tr>
</table>

";

    lentele($lang['user']['administration'], $text);
    unset($text, $arredaguoti);

    switch ($aid) {
        case 1:
            {
                $puslapis = "config.php";
                break;
            }
        case 2:
            {
                $puslapis = "naujienos.php";
                break;
            }
        case 3:
            {
                $puslapis = "forumas.php";
                break;
            }
        case 4:
            {
                $puslapis = "straipsniai.php";
                break;
            }
        case 5:
            {
                $puslapis = "balsavimas.php";
                break;
            }
        case 6:
            {
                $puslapis = "vartotojai.php";
                break;
            }
        case 7:
            {
                $puslapis = "nuorodos.php";
                break;
            }
        case 8:
            {
                $puslapis = "narsykle.php";
                break;
            }
        case 9:
            {
                $puslapis = "paneles.php";
                break;
            }
        case 11:
            {
                $puslapis = "banai.php";
                break;
            }
        case 13:
            {
                $puslapis = "siuntiniai.php";
                break;
            }
        case 19:
            {
                $puslapis = "logai.php";
                break;
            }
        case 20:
            {
                $puslapis = "laiskai.php";
                break;
            }
        case 21:
            {
                $puslapis = "meniu.php";
                break;
            }
        case 22:
            {
                $puslapis = "galerija.php";
                break;
            }
        default:
            {
                $puslapis = "pokalbiai.php";
                break;
            }
    }
	
	if($puslapis == 'pokalbiai.php'){
		lentele('MightMedia TVS Naujienos','<iframe src="http://code.assembla.com/mightmedia/subversion/node/blob/naujienos.html" width="100%" height="100" frameborder="0"></iframe>');
	}
	
    // Įkeliamas puslapis
    if (file_exists(dirname(__file__) . "/" . $puslapis)){
        include_once (dirname(__file__) . "/" . $puslapis);
    } else {
        klaida("{$lang['system']['error']}", "{$lang['system']['nopage']}");
    }
} elseif (isset($_POST['action']) && $_POST['action'] == 'prisijungimas'){
    klaida($lang['system']['warning'], $lang['system']['notadmin']);
}
?>