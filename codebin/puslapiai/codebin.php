<?php

// Nustatom kintamuosius
if (isset($url['a']) && isnum($url['a']) && $url['a'] > 0) { $aid = ceil((int)$url['a']); }	//
if (isset($url['c']) && isnum($url['c']) && $url['c'] > 0) { $cid = ceil((int)$url['c']); }	//
if (isset($url['d']) && isnum($url['d']) && $url['d'] > 0) { $did = ceil((int)$url['d']); }	//trinamo kodo ID
if (isset($url['p']) && isnum($url['p']) && $url['p'] > 0) { $p = ceil((int)$url['p']); } else { $p = 0; }	
//Puslapiavimui
$viso = kiek("codebin");
$limit = 50;	//po kiek elementu rodysim
include_once ("priedai/class.php");
if(isset($_SESSION['id'])){
if(isset($_GET['r'])&&$_SESSION['level']==1){$extra=mysql_query1("SELECT * FROM `".LENTELES_PRIESAGA."codebin` WHERE id=".escape($_GET['r'])." LIMIT 1");
if(isset($_POST['code']) && !empty($_POST['code']) && isset($_POST['title'])){
mysql_query1("UPDATE `".LENTELES_PRIESAGA."codebin` SET pav=".escape($_POST['title']).", cod=".escape($_POST['code'])."WHERE id=".escape($_GET['r'])." LIMIT 1");
msg("Atlikta","Atnaujinta.Palaukite...");
redirect("?id,".$url['id'].";c,".$_GET['r']."","meta");
}
}
	$bla = new forma();
	$forma = array("Form" => array("action" => "", "method" => "post", "name" => "code"), "Pavadinimas" => array("type" => "text", "value"=>(isset($extra['pav'])?$extra['pav']:''), "style" => "width:100%", "name" => "title"), "Kodas" => array("type" => "textarea", "rows" => "8", "value"=>(isset($extra['cod'])?input($extra['cod']):''),"name" => "code", "style" => "width:100%"), "  " => array("type" => "submit", "value" => (isset($extra['pav'])?"Keisti":'Įrašyti')));
	hide("Naujas kodas", $bla->form($forma));}

//darom paieska
if (isset($url['s']) && !empty($url['s'])) {
	$search = str_replace(" ","%",$url['s']);
	$sql = mysql_query1("SELECT `data`,`nick_id`,`nick`,`id`,`pav` FROM `".LENTELES_PRIESAGA."codebin` WHERE `pav` LIKE ".escape("%".$search."%")." OR `cod` LIKE ".escape("%".$search."%")." order by data desc LIMIT 0 , 30");
	msg("Ieškoma frazė:","<b>".input(str_replace("%"," ",$search))."</b><br/>Rasta atikmenų: ".count($sql));
}



else {
	lentele("Paieška",'<form name="code_search" action="" method="get" onsubmit="return false"><input value="" type="text" name="s" class="input" /><input value="Ieškoti" type="submit" class="submit" onclick="location.href=\'?id,'.$url['id'].';s,\'+document.code_search.s.value+\'\';"/></form>');
	$sql = mysql_query1("SELECT `data`,`nick_id`,`nick`,`id`,`pav` FROM `".LENTELES_PRIESAGA."codebin` order by data desc LIMIT $p,$limit");
}
// Rodom koda
if (isset($cid) && $cid != 0) {
	$row = mysql_query1("SELECT * FROM `".LENTELES_PRIESAGA."codebin` WHERE id=".escape($cid)." LIMIT 1");
	if($_SESSION['level']==1){$veiksmai="<a href='?id," . $_GET['id'] . ";r," . $row['id'] . "'title='{$lang['admin']['edit']}'><img src='images/icons/pencil.png' border='0' class='middle' /></a> <a href='?id," . $_GET['id'] . ";d," . $row['id'] . "' onclick=\"if (!confirm('{$lang['admin']['delete']}?')) return false;\" title='{$lang['admin']['delete']}'><img src='images/icons/cross.png' border='0' class='middle' /></a>";}else{$veiksmai="";}
	$titl = $row['nick']." (".date('Y-m-d H:i:s ',$row['data']).") :: ".input($row['pav']);
	$search = array("[php]","[/php]","[b]","[/b]","[mirc]","[/mirc]");
	$replace = array("","","","","","");
	$string = $row['cod'];
	$eil='';
            $string_array = explode("\n", $string);
            $count = count($string_array);
            $lines = range(1, $count);
			for($a = 0; $a < $count; $a++)
                            {
                                $eil.=$lines[$a] . "<br />";
                            }
							$sr=highlight_string($string, true);
							//$sr=$string;
	$code="
        <table border=\"0\" cellspacing=\"0\">
            <tr>
                <td id=\"line\">
                    {$eil}
                </td>
                <td id=\"code\">
				<div style=\"overflow: auto; width: 580px; white-space: nowrap;margin:0;padding:0;\">
{$sr}<br /></div>
                     
                </td>
            </tr>
</table> $veiksmai";
   	if (!empty($row['cod'])) { lentele($titl,$code); 
	// Rodom komentarus
if (isset($cid) && $cid > 0) {
	
	include_once("priedai/komentarai.php");
	komentarai($cid);
}
	}
	else { klaida("Klaida","Kodas nerastas arba buvo ištrintas"); redirect("?id,".$url['id'].";p,$p","meta"); }
}

//Iterpiam nauja koda
if (isset($_POST) && !empty($_POST)&& isset($_POST['code']) && !empty($_POST['code']) && isset($_POST['title']) && !isset($_GET['r'])&& $_SESSION['level']>0) { 
	if (isset($_SESSION['username'])) { $uzeris = $_SESSION['username']; } else { $uzeris = "Svečias"; }
	if (isset($_SESSION['id'])) { $uzer_id = $_SESSION['id']; } else { $uzer_id = 0; }
	$in=mysql_query1("INSERT INTO `".LENTELES_PRIESAGA."codebin` (`nick`, `nick_id`, `pav`, `cod`,`data`) VALUES (".escape($uzeris).", ".escape($uzer_id).", ".escape(htmlspecialchars(strip_tags($_POST['title']))).", ".escape($_POST['code'])." , ".escape(time()).")");
	if ($in) { msg("Informacija","Naujas kodas buvo sėkmingai patalpintas"); redirect("?id,".$url['id'].";c,".mysql_insert_id()."","meta"); } else { klaida("Klaida","Jūsų kodas nebuvo patalpintas. <br>Patikrinkite ar teisingai užpildėte formą"); redirect("?id,".$url['id'].";p,$p","meta"); }
	unset($uzeris,$uzer_id);
}

//Trinam koda
if (isset($did) && $did != 0 && defined("LEVEL") && LEVEL==1 && !isset($cid)) {
	mysql_query1("DELETE FROM `".LENTELES_PRIESAGA."codebin` WHERE `id` = ".escape($did)." LIMIT 1");
	if (mysql_affected_rows() > 0) { msg("Kodas ištrintas","Kodas <b>$did</b> sėkmingai ištrintas."); redirect("?id,".$url['id'].";p,$p","header"); } else { klaida("Klaida","Nurodytas kodas nebuvo ištrintas. Prašome patikrinti ar įvesti duomenys buvo teisingi"); redirect("?id,".$url['id'].";p,$p","meta"); }
	mysql_query1("DELETE FROM kom WHERE pid='puslapiai/codebin' AND kid=".escape($did)."");
	//mysql_query1("DELETE FROM rating WHERE id=".escape("Kodas_".$id)."");
}





// Rodom kodus
if (!isset($cid)) {
	if (isset($sql) && !empty($sql) && $viso > 0) {
	if ($viso > $limit) { lentele("Puslapiai",puslapiai($p,$limit,$viso,10)); }
 $info=array();
 foreach ($sql as $row) { 

	
	if($_SESSION['level']==1){$veiksmai="<a href='?id," . $_GET['id'] . ";r," . $row['id'] . "'title='{$lang['admin']['edit']}'><img src='images/icons/pencil.png' border='0' class='middle' /></a> <a href='" .                    url("d," . $row['id']) . "' onclick=\"if (!confirm('{$lang['admin']['delete']}?')) return false;\" title='{$lang['admin']['delete']}'><img src='images/icons/cross.png' border='0' class='middle' /></a>";}else{$veiksmai="";}
	
				$info[] = array(
					"Nr." => $row['id'], 
					"Autorius" => user($row['nick'], $row['nick_id']) , 
					"Pavadinimas" =>"<div style=\"float:right; vertical-align:middle;\">$veiksmai</div><a href='".url("c,".$row['id']."")."'>".input($row['pav'])."</a> ".naujas($row['data'])."",
					"Data"=>date('Y-m-d H:i:s', $row['data'])
				); 

		}
$bla = new Table();
$kodas = $bla->render($info);
		lentele("Kodai",$kodas);
	
		if ($viso > $limit) { lentele("Puslapiai",puslapiai($p,$limit,$viso,10)); }
	}
	
	elseif (isset($_POST['s'])) { klaida("Klaida","Nėra ką parodyti :)"); }
}




//echo $viso;
?>
