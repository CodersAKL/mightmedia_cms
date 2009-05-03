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

if (!defined("OK") || !ar_admin(basename(__file__)))
{
    header('location: ?');
    exit();
}
ini_set("memory_limit", "50M");
if (isset($url['p']) && isnum($url['p']) && $url['p'] > 0)
{
    $p = escape(ceil((int)$url['p']));
}
else
{
    $p = 0;
}
/*$buttons = <<<HTML
<table><tr><td>
<button onclick="location.href='?id,{$_GET['id']};a,{$_GET['a']};v,6'" class="blokas"><img src="images/icons/picture_key.png" border="0"><br>Nustatymai</button>
<button onclick="location.href='?id,{$_GET['id']};a,{$_GET['a']};v,7'" class="blokas"><img src="images/icons/picture_error.png" border="0"><br>Nerodomos nuotraukos</button>
<button onclick="location.href='?id,{$_GET['id']};a,{$_GET['a']};v,1'" class="blokas"><img src="images/icons/picture_add.png" border="0"><br>Pridėti nuotrauką</button>
<button onclick="location.href='?id,{$_GET['id']};a,{$_GET['a']};v,8'" class="blokas"><img src="images/icons/picture_edit.png" border="0"><br>Redaguoti nuotrauką</button>
<button onclick="location.href='?id,{$_GET['id']};a,{$_GET['a']};v,2'" class="blokas"><img src="images/icons/folder_add.png" border="0"><br>Sukurti kategoriją</button>
<button onclick="location.href='?id,{$_GET['id']};a,{$_GET['a']};v,3'" class="blokas"><img src="images/icons/folder_edit.png" border="0"><br>Redaguoti kategoriją</button>
</td></tr></table>
HTML;*/
$buttons = <<< HTML
<button onclick="location.href='?id,{$_GET['id']};a,{$_GET['a']};v,6'">{$lang['admin']['gallery_conf']}</button>
<button onclick="location.href='?id,{$_GET['id']};a,{$_GET['a']};v,7'">{$lang['admin']['gallery_unpublished']}</button>
<button onclick="location.href='?id,{$_GET['id']};a,{$_GET['a']};v,1'">{$lang['admin']['gallery_add']}</button>
<button onclick="location.href='?id,{$_GET['id']};a,{$_GET['a']};v,8'">{$lang['admin']['gallery_edit']}</button>
<button onclick="location.href='?id,{$_GET['id']};a,{$_GET['a']};v,2'">{$lang['system']['createcategory']}</button>
<button onclick="location.href='?id,{$_GET['id']};a,{$_GET['a']};v,3'">{$lang['system']['editcategory']}</button>
<button onclick="location.href='?id,{$_GET['id']};a,{$_GET['a']};v,5'">{$lang['system']['createsubcategory']}</button>

HTML;

if (empty($url['s']))
{
    $url['s'] = 0;
}
if (empty($url['v']))
{
    $url['v'] = 0;
}

lentele($lang['admin']['gallery'], $buttons);

unset($buttons, $extra, $text);
include_once ("priedai/kategorijos.php");
kategorija("galerija", true);
$sql = mysql_query1("SELECT * FROM  `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='galerija' AND `path`=0 ORDER BY `id` DESC") or die(mysql_error());
if (mysql_num_rows($sql) > 0)
{
    while ($row = mysql_fetch_assoc($sql))
    {

        $sql2 = mysql_query1("SELECT * FROM  `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='galerija' AND path!=0 and `path` like '" . $row['id'] . "%' ORDER BY `id` ASC");
        if (mysql_num_rows($sql2) > 0)
        {
            $subcat = '';
            while ($path = mysql_fetch_assoc($sql2))
            {

                $subcat .= "->" . $path['pavadinimas'];
                $kategorijos[$row['id']] = $row['pavadinimas'];
                $kategorijos[$path['id']] = $row['pavadinimas'] . $subcat;


            }
        }
        else
        {
            $kategorijos[$row['id']] = $row['pavadinimas'];
        }


    }
}
else
{
    $kategorijos[] = "{$lang['system']['nocategories']}";

}
if (isset($_GET['p']))
{
    $result = mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "galerija` SET rodoma='TAIP' 
			WHERE `id`=" . escape($_GET['p']) . ";
			");
    if ($result)
    {
        msg($lang['system']['done'], "{$lang['admin']['gallery_activated']}.");
    }
    else
    {
        klaida("{$lang['system']['error']}", " <br><b>" . mysql_error() . "</b>");
    }
}
if (((isset($_POST['action']) && $_POST['action'] == $lang['admin']['delete'] && LEVEL == 1 && isset($_POST['edit_new']) && $_POST['edit_new'] > 0)) || isset($url['t']) && LEVEL == 1)
{
    if (isset($url['t']))
    {
        $trinti = (int)$url['t'];
    } elseif (isset($_POST['edit_new']))
    {
        $trinti = (int)$_POST['edit_new'];
    }
    $sql = mysql_query1("SELECT `file` FROM `" . LENTELES_PRIESAGA . "galerija` WHERE `ID` = " . escape($trinti) . " LIMIT 1");
    $row = mysql_fetch_assoc($sql);
    if (isset($row['file']) && !empty($row['file']))
    {
        @unlink("galerija/" . $row['file']);
        @unlink("galerija/mini/" . $row['file']);
        @unlink("galerija/originalai/" . $row['file']);
    }
    mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "galerija` WHERE id=" . escape($trinti) . " LIMIT 1");


    if (mysql_affected_rows() > 0)
    {
        msg("{$lang['system']['done']}", "{$lang['admin']['gallery_deleted']}");
    }


    else
    {
        klaida("{$lang['system']['error']}", " <br><b>" . mysql_error() . "</b>");
    }

    mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE pid='puslapiai/galerija' AND kid=" . escape($trinti) . "");
    //redirect("?id,".$_GET['id'].";a,".$_GET['a'],"header");
}

//Naujienos redagavimas
elseif (((isset($_POST['edit_new']) && isNum($_POST['edit_new']) && $_POST['edit_new'] > 0)) || isset($url['h']))
{
    if (isset($url['h']))
    {
        $redaguoti = (int)$url['h'];
    } elseif (isset($_POST['edit_new']))
    {
        $redaguoti = (int)$_POST['edit_new'];
    }

    $extra = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "galerija` WHERE `id`=" . escape($redaguoti) . " LIMIT 1");
    $extra = mysql_fetch_assoc($extra);
} elseif (isset($_POST['action']) && $_POST['action'] == $lang['admin']['edit'])
{
    //apsauga nuo kenksmingo kodo

    $apie = strip_tags($_POST['Aprasymas']);
    //$naujiena = safe_html($_POST['naujiena'], $tags );

    //$placiau = safe_html($_POST['placiau'], $tags );
    $pavadinimas = strip_tags($_POST['Pavadinimas']);
    $kategorija = (int)$_POST['cat'];
    //$foto = strip_tags($_POST['Pav']);
    $id = ceil((int)$_POST['news_id']);
    $komentaras = (isset($_POST['kom']) && $_POST['kom'] == 'TAIP' ? 'TAIP' : 'NE');


    $result = mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "galerija` SET
			`pavadinimas` = " . escape($pavadinimas) . ",
			`categorija` = " . escape($kategorija) . ",
			`apie` = " . escape($apie) . "
			WHERE `id`=" . escape($id) . ";
			");
    if ($result)
    {
        msg("{$lang['system']['done']}", "{$lang['admin']['gallery_updated']}");
    }
    else
    {
        klaida("{$lang['system']['error']}", " <br><b>" . mysql_error() . "</b>");
    }

} elseif (isset($_POST['action']) && $_POST['action'] == $lang['admin']['gallery_add'])
{
    function random($return = '')
    {
        $simboliai = "abcdefghijkmnopqrstuvwxyz0123456789";
        for ($i = 1; $i < 3; ++$i)
        {
            $num = rand() % 33;
            $return .= substr($simboliai, $num, 1);
        }
        return $return . '_';
    }
    if (isset($_FILES['failas']['name']))
    {
        //make sure this directory is writable!
        $path_big = "galerija/";
        $path_thumbs = "galerija/mini";
        //the new width of the resized image, in pixels.
        $img_thumb_width = $conf['minidyd']; //
        $extlimit = "yes"; //Limit allowed extensions? (no for all extensions allowed)
        //List of allowed extensions if extlimit = yes
        $limitedext = array(".gif", ".jpg", ".png", ".jpeg", ".bmp");
        //the image -> variables
        $file_type = $_FILES['failas']['type'];
        $file_name = $_FILES['failas']['name'];
        $file_size = $_FILES['failas']['size'];
        $file_tmp = $_FILES['failas']['tmp_name'];
        //check if you have selected a file.

        if (!is_uploaded_file($file_tmp))
        {
            klaida("{$lang['system']['warning']}", "{$lang['admin']['gallery_nofile']}.");
        }
        else
        {
            //check the file's extension
            $ext = strrchr($file_name, '.');
            $ext = strtolower($ext);
            //uh-oh! the file extension is not allowed!
            if (($extlimit == "yes") && (!in_array($ext, $limitedext)))
            {
                klaida("{$lang['system']['warning']}", "{$lang['admin']['gallery_notimg']}");
            }
            //so, whats the file's extension?
            $getExt = explode('.', $file_name);
            $file_ext = $getExt[count($getExt) - 1];
            //create a random file name
            $rand_pre = random();
            $rand_name = $rand_pre . time();
            //$rand_name= rand(0,999999999);
            //the new width variable
            $ThumbWidth = $img_thumb_width;
            if ($file_size)
            {
                if ($file_type == "image/pjpeg" || $file_type == "image/jpeg")
                {
                    $new_img = imagecreatefromjpeg($file_tmp);
                } elseif ($file_type == "image/x-png" || $file_type == "image/png")
                {
                    $new_img = imagecreatefrompng($file_tmp);
                } elseif ($file_type == "image/gif")
                {
                    $new_img = imagecreatefromgif($file_tmp);
                }
                //list the width and height and keep the height ratio.
                list($width, $height) = getimagesize($file_tmp);
                //calculate the image ratio
                $imgratio = $width / $height;
                if ($width > $ThumbWidth)
                {
                    if ($imgratio > 1)
                    {
                        $newwidth = $ThumbWidth;
                        $newheight = $ThumbWidth / $imgratio;
                    }
                    else
                    {
                        $newheight = $ThumbWidth;
                        $newwidth = $ThumbWidth * $imgratio;
                    }
                }
                else
                {
                    $newwidth = $width;
                    $newheight = $height;
                }
                //function for resize image.
                if (function_exists('imagecreatetruecolor'))
                {
                    $resized_img = imagecreatetruecolor($newwidth, $newheight);
                }
                else
                {
                    klaida($lang['system']['error'], 'GD v2+' . $lang['system']['error']);
                }


                //the resizing is going on here!

                imagecopyresampled($resized_img, $new_img, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                //imagecopyresampled($resized_img, $new_img, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

                //finally, save the image

                ImageJpeg($resized_img, "$path_thumbs/$rand_name.$file_ext", 95);
                ImageDestroy($resized_img);
                ImageDestroy($new_img);

            }

            if ($file_size)
            {
                if ($file_type == "image/pjpeg" || $file_type == "image/jpeg")
                {
                    $new_img = imagecreatefromjpeg($file_tmp);
                } elseif ($file_type == "image/x-png" || $file_type == "image/png")
                {
                    $new_img = imagecreatefrompng($file_tmp);
                } elseif ($file_type == "image/gif")
                {
                    $new_img = imagecreatefromgif($file_tmp);
                } elseif ($file_type == "image/bmp")
                {
                    $new_img = imagecreatefrombmp($file_tmp);
                }
                $bigsize = $conf['fotodyd'];
                list($width, $height) = getimagesize($file_tmp);
                //calculate the image ratio
                $imgratio = $width / $height;
                if ($width > $bigsize)
                {
                    if ($imgratio > 1)
                    {
                        $newwidth = $bigsize;
                        $newheight = $bigsize / $imgratio;
                    }
                    else
                    {
                        $newheight = $bigsize;
                        $newwidth = $bigsize * $imgratio;
                    }
                }
                else
                {
                    $newwidth = $width;
                    $newheight = $height;
                }
                $resized_imgbig = imagecreatetruecolor($newwidth, $newheight);
                imagecopyresampled($resized_imgbig, $new_img, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

                //finally, save the image

                ImageJpeg($resized_imgbig, "$path_big/$rand_name.$file_ext", 95);
                ImageDestroy($resized_imgbig);
                ImageDestroy($new_img);

                move_uploaded_file($file_tmp, "$path_big/originalai/$rand_name.$file_ext");

                $result = mysql_query1("
INSERT INTO `" . LENTELES_PRIESAGA . "galerija` (`pavadinimas`,`file`,`apie`,`autorius`,`data`,`categorija`,`rodoma`)
VALUES (
" . escape($_POST['Pavadinimas']) . ",
" . escape($rand_name . "." . $file_ext) . ",
" . escape(strip_tags($_POST['Aprasymas'])) . ",
" . escape($_SESSION['id']) . ",
'" . time() . "',
" . escape($_POST['cat']) . ",
'TAIP'
)");

                if ($result)
                {
                    msg($lang['system']['done'], "{$lang['admin']['gallery_added']}");
                }
                else
                {
                    klaida("{$lang['system']['error']}", " <br><b>" . mysql_error() . "</b>");
                }
                unset($_FILES['failas'], $filename, $_POST['action']);
                redirect("?id," . $_GET['id'] . ";a," . $_GET['a'] . "", "meta");

            }
        }
    }

    /**
     * Funkcija dirbanti su BMP paveiksliukais
     * @author - nežinomas
     *
     * @param resource $filename
     * @return resource
     */
    function ImageCreateFromBMP($filename)
    {
        if (!$f1 = fopen($filename, "rb"))
            return false;
        $FILE = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($f1, 14));
        if ($FILE['file_type'] != 19778)
            return false;

        $BMP = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel' . '/Vcompression/Vsize_bitmap/Vhoriz_resolution' . '/Vvert_resolution/Vcolors_used/Vcolors_important', fread($f1, 40));
        $BMP['colors'] = pow(2, $BMP['bits_per_pixel']);
        if ($BMP['size_bitmap'] == 0)
            $BMP['size_bitmap'] = $FILE['file_size'] - $FILE['bitmap_offset'];
        $BMP['bytes_per_pixel'] = $BMP['bits_per_pixel'] / 8;
        $BMP['bytes_per_pixel2'] = ceil($BMP['bytes_per_pixel']);
        $BMP['decal'] = ($BMP['width'] * $BMP['bytes_per_pixel'] / 4);
        $BMP['decal'] -= floor($BMP['width'] * $BMP['bytes_per_pixel'] / 4);
        $BMP['decal'] = 4 - (4 * $BMP['decal']);
        if ($BMP['decal'] == 4)
            $BMP['decal'] = 0;

        $PALETTE = array();
        if ($BMP['colors'] < 16777216)
        {
            $PALETTE = unpack('V' . $BMP['colors'], fread($f1, $BMP['colors'] * 4));
        }

        $IMG = fread($f1, $BMP['size_bitmap']);
        $VIDE = chr(0);

        $res = imagecreatetruecolor($BMP['width'], $BMP['height']);
        $P = 0;
        $Y = $BMP['height'] - 1;
        while ($Y >= 0)
        {
            $X = 0;
            while ($X < $BMP['width'])
            {
                if ($BMP['bits_per_pixel'] == 24)
                    $COLOR = unpack("V", substr($IMG, $P, 3) . $VIDE);
                elseif ($BMP['bits_per_pixel'] == 16)
                {
                    $COLOR = unpack("n", substr($IMG, $P, 2));
                    $COLOR[1] = $PALETTE[$COLOR[1] + 1];
                } elseif ($BMP['bits_per_pixel'] == 8)
                {
                    $COLOR = unpack("n", $VIDE . substr($IMG, $P, 1));
                    $COLOR[1] = $PALETTE[$COLOR[1] + 1];
                } elseif ($BMP['bits_per_pixel'] == 4)
                {
                    $COLOR = unpack("n", $VIDE . substr($IMG, floor($P), 1));
                    if (($P * 2) % 2 == 0)
                        $COLOR[1] = ($COLOR[1] >> 4);
                    else
                        $COLOR[1] = ($COLOR[1] & 0x0F);
                    $COLOR[1] = $PALETTE[$COLOR[1] + 1];
                } elseif ($BMP['bits_per_pixel'] == 1)
                {
                    $COLOR = unpack("n", $VIDE . substr($IMG, floor($P), 1));
                    if (($P * 8) % 8 == 0)
                        $COLOR[1] = $COLOR[1] >> 7;
                    elseif (($P * 8) % 8 == 1)
                        $COLOR[1] = ($COLOR[1] & 0x40) >> 6;
                    elseif (($P * 8) % 8 == 2)
                        $COLOR[1] = ($COLOR[1] & 0x20) >> 5;
                    elseif (($P * 8) % 8 == 3)
                        $COLOR[1] = ($COLOR[1] & 0x10) >> 4;
                    elseif (($P * 8) % 8 == 4)
                        $COLOR[1] = ($COLOR[1] & 0x8) >> 3;
                    elseif (($P * 8) % 8 == 5)
                        $COLOR[1] = ($COLOR[1] & 0x4) >> 2;
                    elseif (($P * 8) % 8 == 6)
                        $COLOR[1] = ($COLOR[1] & 0x2) >> 1;
                    elseif (($P * 8) % 8 == 7)
                        $COLOR[1] = ($COLOR[1] & 0x1);
                    $COLOR[1] = $PALETTE[$COLOR[1] + 1];
                }
                else
                    return false;
                imagesetpixel($res, $X, $Y, $COLOR[1]);
                $X++;
                $P += $BMP['bytes_per_pixel'];
            }
            $Y--;
            $P += $BMP['decal'];
        }


        fclose($f1);

        return $res;
    }
}


if (isset($_GET['v']))
{
    include_once ("priedai/class.php");
    $bla = new forma();
    if ($_GET['v'] == 8)
    {
        $limit = 10;
        $sql2 = mysql_query1("SELECT * FROM  `" . LENTELES_PRIESAGA . "galerija` LIMIT $p,$limit");
        if (mysql_num_rows($sql2) > 0)
        {
            $text = "
			
    <script type=\"text/javascript\" src=\"javascript/jquery/jquery.lightbox-0.5.js\"></script>
	<link rel=\"stylesheet\" type=\"text/css\" href=\"stiliai/jquery.lightbox-0.5.css\" media=\"screen\" />
   
    <!-- / fim dos arquivos utilizados pelo jQuery lightBox plugin -->
    
    <!-- Ativando o jQuery lightBox plugin -->
    <script type=\"text/javascript\">
    $(function() {
        $('#gallery a[rel^=lightbox]').lightBox({fixedNavigation:true,txtOf: '" . $lang['user']['pm_of'] . "',txtImage: ''
});

    });
    </script>
			<table border=\"0\">
	<tr>
		<td >
";
            while ($row2 = mysql_fetch_assoc($sql2))
            {
                if (isset($row['Nick']))
                {
                    $autorius = $row2['Nick'];
                }
                else
                {
                    $autorius = $lang['system']['guest'];
                }
                $text .= "
				
				<div id=\"gallery\" class=\"img_left\" >
			<a rel=\"lightbox[" . $row2['ID'] . "]\" href=\"galerija/" . $row2['file'] . "\"  title=\"" . $row2['pavadinimas'] . ": " . $row2['apie'] . "\">
				<img src=\"galerija/mini/" . $row2['file'] . "\" alt=\"\" />
			</a><br>
<a href=\"?id," . $conf['puslapiai']['galerija.php']['id'] . ";m," . $row2['ID'] . "\" title=\"{$lang['admin']['gallery_comments']}\"><img src='images/icons/comment.png' alt='C' border='0'></a>
					<a href=\"?id," . $url['id'] . ";a," . $url['a'] . ";t," . $row2['ID'] . "\" onclick=\"if (confirm('{$lang['system']['delete_confirm']}')) { $.get('?id," . $url['id'] . ";a," . $url['a'] . ";t," . $row2['ID'] . "'); $(this).parent('.img_left').remove(); return false } else { return false }\" title=\"{$lang['admin']['delete']}\"><img src='images/icons/cross.png'  border='0'></a>
						<a href=\"?id," . $url['id'] . ";a," . $url['a'] . ";h," . $row2['ID'] . "\" title=\"{$lang['admin']['edit']}\"><img src='images/icons/picture_edit.png'  border='0'></a>
			<a href=\"galerija/originalai/" . $row2['file'] . "\" title=\"{$lang['download']['download']}\"><img src='images/icons/disk.png' border='0'></a>
		</div>";
            }
            /*$siuntiniaii[$row2['id']] = $row2['pavadinimas'];
            }}else{$siuntiniaii[]=$lang['admin']['gallery_noimages'];}
            $redagavimas = array(
            "Form"=>array("action"=>"?id,{$_GET['id']};a,{$_GET['a']};v,1","method"=>"post","name"=>"reg"),
            "{$lang['admin']['gallery_images']}:"=>array("type"=>"select","value"=>$siuntiniaii,"name"=>"edit_new"),
            "{$lang['admin']['edit']}:"=>array("type"=>"submit","name"=>"action","value"=>"{$lang['admin']['edit']}"),
            "{$lang['admin']['delete']}:"=>array("type"=>"submit","name"=>"action","value"=>"{$lang['admin']['delete']}")
            );
            lentele($lang['admin']['gallery_edit'],$bla->form($redagavimas));*/
            $text .= '</td>
	</tr>
</table>';
            lentele($lang['admin']['gallery_edit'], $text);
            $visos = kiek('galerija');


            if ($visos > $limit)
            {
                lentele($lang['system']['pages'], puslapiai($p, $limit, $visos, 10));
            }
        }
    } elseif ($_GET['v'] == 1 || isset($url['h']))
    {

        if (mysql_num_rows($sql) > 0)
        {
            $forma = array("Form" => array("enctype" => "multipart/form-data", "action" => "?id," . $_GET['id'] . ";a," . $_GET['a'] . "", "method" => "post", "name" => "action"), (!isset($extra)) ? "{$lang['admin']['gallery_file']}:" : "" => array("name" => "failas", "type" => (!isset($extra)) ? "file" : "hidden", "value" => "", "style" => "width:100%"), "{$lang['admin']['gallery_title']}:" =>
                array("type" => "text", "value" => (isset($extra['pavadinimas'])) ? input($extra['pavadinimas']) : '', "name" => "Pavadinimas", "style" => "width:100%"), "{$lang['system']['category']}:" => array("type" => "select", "value" => $kategorijos, "name" => "cat", "class" => "input", "style" => "width:100%", "selected" => (isset($extra['categorija']) ? input($extra['categorija']) : '')),
                "{$lang['admin']['gallery_about']}:" => array("type" => "textarea", "name" => "Aprasymas", "style" => "width:100%", "rows" => "3", "class" => "input", "value" => (isset($extra['apie'])) ? input($extra['apie']) : ''), //"Paveiksliukas:"=>array("type"=>"text","value"=>(isset($extra['foto']))?input($extra['foto']):'http://',"name"=>"Pav","style"=>"width:100%"),
                (isset($extra)) ? $lang['admin']['edit'] : $lang['admin']['gallery_add'] => array("type" => "submit", "name" => "action", "value" => (isset($extra)) ? $lang['admin']['edit'] : $lang['admin']['gallery_add']), );
            if (isset($extra))
            {
                $forma[''] = array("type" => "hidden", "name" => "news_id", "value" => (isset($extra) ? input($extra['ID']) : ''));
            }
            lentele(((isset($extra)) ? $lang['admin']['edit'] : $lang['admin']['gallery_add']), ((isset($extra['file'])) ? '<center><img src="galerija/' . input($extra['file']) . '"></center>' : '') . $bla->form($forma));
        }
        else
        {
            klaida($lang['system']['warning'], "{$lang['system']['nocategories']}");
        }
    } elseif ($_GET['v'] == 6)
    {
        if (isset($_POST) && !empty($_POST) && isset($_POST['Konfiguracija']))
        {
            $q = array();
            $q[] = "UPDATE `" . LENTELES_PRIESAGA . "nustatymai` SET `val` = " . escape((int)$_POST['fotodyd']) . " WHERE `key` = 'fotodyd' LIMIT 1 ; ";
            $q[] = "UPDATE `" . LENTELES_PRIESAGA . "nustatymai` SET `val` = " . escape((int)$_POST['minidyd']) . " WHERE `key` = 'minidyd' LIMIT 1 ; ";
            $q[] = "UPDATE `" . LENTELES_PRIESAGA . "nustatymai` SET `val` = " . escape((int)$_POST['galbalsuot']) . " WHERE `key` = 'galbalsuot' LIMIT 1 ; ";
            $q[] = "UPDATE `" . LENTELES_PRIESAGA . "nustatymai` SET `val` = " . escape((int)$_POST['fotoperpsl']) . " WHERE `key` = 'fotoperpsl' LIMIT 1 ; ";
            $q[] = "UPDATE `" . LENTELES_PRIESAGA . "nustatymai` SET `val` = " . escape((int)$_POST['galkom']) . " WHERE `key` = 'galkom' LIMIT 1 ; ";
            foreach ($q as $sql)
            {
                mysql_query1($sql) or die(mysql_error());
            }
            redirect('?id,999;a,' . $url['a'] . ';v,6');
        }
        $nustatymai = array("Form" => array("action" => "", "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "reg"), "{$lang['admin']['gallery_maxwidth']}:" => array("type" => "text", "value" => input($conf['fotodyd']), "name" => "fotodyd", "style" => "width:100%"), "{$lang['admin']['gallery_minwidth']}:" => array("type" => "text", "value" => input($conf['minidyd']),
            "name" => "minidyd", "style" => "width:100%"), "{$lang['admin']['gallery_rate']}:" => array("type" => "select", "value" => array("1" => "{$lang['admin']['yes']}", "0" => "{$lang['admin']['no']}"), "selected" => input($conf['galbalsuot']), "name" => "galbalsuot"), "{$lang['admin']['gallery_comments']}:" => array("type" => "select", "value" => array("1" => "{$lang['admin']['yes']}",
            "0" => "{$lang['admin']['yes']}"), "selected" => input($conf['galkom']), "name" => "galkom"), "{$lang['admin']['gallery_images_per_page']}:" => array("type" => "select", "value" => array("5" => "5", "10" => "10", "15" => "15", "20" => "20", "25" => "25", "30" => "30", "35" => "35", "40" => "40"), "selected" => input($conf['fotoperpsl']), "name" => "fotoperpsl"), "" => array("type" =>
            "submit", "name" => "Konfiguracija", "value" => "{$lang['admin']['save']}"));

        include_once ("priedai/class.php");
        $bla = new forma();
        lentele($lang['admin']['gallery_conf'], $bla->form($nustatymai));

    } elseif ($_GET['v'] == 7)
    {

        $q = mysql_query1("SELECT
  `" . LENTELES_PRIESAGA . "galerija`.`pavadinimas`,
  `" . LENTELES_PRIESAGA . "galerija`.`id` ,
  `" . LENTELES_PRIESAGA . "galerija`.`apie`,
  `" . LENTELES_PRIESAGA . "galerija`.`data`,
  `" . LENTELES_PRIESAGA . "users`.`nick` AS `Nick`,
  `" . LENTELES_PRIESAGA . "galerija`.`file`
  FROM
  `" . LENTELES_PRIESAGA . "galerija`
  
  Inner Join `" . LENTELES_PRIESAGA . "users` ON `" . LENTELES_PRIESAGA . "galerija`.`autorius` = `" . LENTELES_PRIESAGA . "users`.`id`
  WHERE  
   `" . LENTELES_PRIESAGA . "galerija`.`rodoma` =  'NE' 
  ORDER BY
  `" . LENTELES_PRIESAGA . "galerija`.`data` DESC
  ");
        if ($q)
        {

            include_once ("priedai/class.php");
            $bla = new Table();
            $info = array();
            while ($row = mysql_fetch_assoc($q))
            {
                //$sql2 = mysql_fetch_assoc(mysql_query1("SELECT nick FROM `".LENTELES_PRIESAGA."users` WHERE id='".$sql['autorius']."'"));
                if (isset($row['Nick']))
                {
                    $autorius = $row['Nick'];
                }
                else
                {
                    $autorius = $lang['system']['guest'];
                }

                $info[] = array( //"ID"=> $row['ID'],
                    "{$lang['admin']['gallery_image']}:" => "<a href='?id,{$_GET['id']};a,{$_GET['a']}' title='<img src=galerija/" . $row['file'] . "><br><b>{$lang['admin']['gallery_author']}:</b> " . $autorius . "<br>
		<b>{$lang['admin']['gallery_date']}:</b> " . date('Y-m-d H:i:s ', $row['data']) . "<br>
		<b>{$lang['admin']['gallery_about']}:</b> " . $row['apie'] . "'>" . $row['pavadinimas'] . " ...</a>", "{$lang['admin']['action']}:" => "<a href='?id,{$_GET['id']};a,{$_GET['a']};p," . $row['id'] . "'title='{{$lang['admin']['acept']}}'><img src='images/icons/icon_accept.gif' border='0'></a> <a href='?id,{$_GET['id']};a,{$_GET['a']};t," . $row['id'] . "' title='{$lang['admin']['delete']}'><img src='images/icons/cross.png' border='0'></a> <a href='?id,{$_GET['id']};a,{$_GET['a']};h," .
                    $row['id'] . "' title='{$lang['admin']['edit']}'><img src='images/icons/picture_edit.png' border='0'></a>");

            }
            lentele($lang['admin']['gallery_unpublished'], $bla->render($info));

        }
    }
}


unset($sql, $extra, $row);
//unset($_POST);
?>