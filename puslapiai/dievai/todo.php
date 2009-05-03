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

if (!defined("LEVEL") || LEVEL < 30 || !defined("OK")) { die('Uždrausta'); }

/**
 * Administravimas
 */

//Jei adminas bando trinti. $_GET['d'] == ID
if (isset($_GET['d']) && isnum($_GET['d']) && $_GET['d'] > 0) {
	$q = "DELETE FROM `".LENTELES_PRIESAGA."todo` WHERE `id` = ".escape((int)$_GET['d']);
	mysql_query1($q) or die(klaida("klaida","ajajaj"));
	redirect("?id,".(int)$_GET['id'].";a,".(int)$_GET['a']."","header");
}
//kitu atveju jei bandoma sukurti arba redaguoti
elseif (isset($_GET['n']) || isset($_GET['e'])) { ?>
	<script language="JavaScript">
	// Paleidziame simple HTML editoriu
	tinyMCE.init({
		mode : "exact",
		elements : "Aprasymas",
		theme : "simple",
		apply_source_formatting : true
	});
	</script>
	<?php
	$pavadinimas = '';
	$aprasymas = '';
	$atliktas = '';

	//Jeigu adminas nori redaguoti
	// url['e'] pasakome koki ID redaguosime
	if (isset($_GET['e']) && isnum($_GET['e']) && $_GET['e'] > 0) {
		$value = "Keisti"; $id = ceil((int)$_GET['e']); if ($id < 0) { $id = 0; }
		$sql = mysql_fetch_assoc(mysql_query1("SELECT * FROM `".LENTELES_PRIESAGA."todo` WHERE `id` = ".escape($id)." LIMIT 1")) or klaida('SQL klaida','Tik nesakysiu kokia :)');
		$pavadinimas = $sql['pavadinimas'];
		$aprasymas = $sql['aprasymas'];
		$atliktas = (int)$sql['atliktas'];
	}

	//Jei adminas bando rasyti nauja TODO
	elseif (isset($_GET['n']) && $_GET['n'] == 1) {
		$value = "Įrašyti";
	}

	$todo = array(
	"Form"=>array("action"=>"","method"=>"post","enctype"=>"","id"=>"","class"=>"","name"=>"todo"),
	"Pavadinimas:"=>array("type"=>"text","value"=>input($pavadinimas),"name"=>"Pavadinimas","style"=>"width:400px"),
	"Aprašymas:"=>array("type"=>"textarea","value"=>input($aprasymas),"name"=>"Aprasymas","id"=>"aprasymas","class"=>"input","rows"=>"10","style"=>"width:90%"),
	"Atlikta %:"=>array("type"=>"text","value"=>input((int)$atliktas),"name"=>"Atliktas","style"=>"width:200px"),
	""=>array("type"=>"hidden","value"=>input($id),"name"=>"id","id"=>"id"),
	""=>array("type"=>"submit","name"=>"todo","value"=>$value)
	);

	include_once("priedai/class.php");
	$bla = new forma();
	lentele("TODO rašymas/koregavimas",$bla->form($todo));
}
elseif (isset($_GET['v']) && $_GET['v'] > 0) {
	$id = ceil((int)$_GET['v']); if ($id < 0) { $id = 0; }
	$sql = mysql_fetch_assoc(mysql_query1("SELECT * FROM `".LENTELES_PRIESAGA."todo` WHERE `id` = ".escape($id)." LIMIT 1")) or klaida('SQL klaida','Tik nesakysiu kokia :)');
	$pavadinimas = $sql['pavadinimas'];
	$aprasymas = $sql['aprasymas'];
	$atliktas = (int)$sql['atliktas'];
	$pic = rodom_todo($atliktas);
	$buttons = "
	<button onclick=\"window.location='".url('n,1')."'\">Sukurti naują užduotį</button> | 
	<button onclick=\"window.location='".url('e,'.$id)."'\">Redaguoti</button>
	<button onclick=\"if (confirm('Ar tikrai norite ištrinti?') window.location='".url('d,'.$id)."'; else return false;\">Trinti</button>";
	echo <<<HTML
	<table width="100%">
	<tr class="tr">
		<th class="th" colspan=2>{$pavadinimas}</th>
	</tr>
	<tr class="sarasas">
		<td>Atliktas:</td>
		<td width="100%">{$pic}</td>
	</tr>
	<tr class="sarasas">
		<td style="background-color: rgb(255, 255, 204);" colspan=2>{$aprasymas}</td>
	</tr>
	<tr class="sarasas">
		<td colspan=2>{$buttons}</td>
	</tr>
</table>
HTML;
	echo "<hr/>";
	include_once("priedai/komentarai.php");
	komentarai($id);

	//lentele('TODO - '.$pavadinimas.' Atliktas:'.$atliktas,"<button onclick=\"window.location='?id,".(int)$_GET['id'].";a,".(int)$_GET['a']."'\">Atgal į sarašą</button><br />".$aprasymas.'<br />'.rodom_todo($atliktas));
}
else {
	//Visais kitais atvejais atvaizduojame TODO sarašą
	include_once("priedai/class.php");
	$bla = new forma();
	$sql = mysql_query1("SELECT id, pavadinimas, atliktas FROM `".LENTELES_PRIESAGA."todo` order by atliktas"); $i=0;
	if (mysql_num_rows($sql) > 0) {
		while($row = mysql_fetch_assoc($sql)) {
			$i++;
			$info1[] = array(
			//"Nr:"=>$row['id'],
			"Pavadinimas"=> "<a href=\"".url('v,'.$row['id'])."\" title=\"".$row['pavadinimas']."\">".$row['pavadinimas']."</a>",
			"atlikta" => rodom_todo($row['atliktas']),
			""=>"<a href=\"".url("e,".$row['id'])."\"><img src=\"images/icons/tag_blue_edit.png\" alt=\"edit\" border=\"0\" /></a> <a href=\"".url("d,".$row['id'])."\" onclick=\"return confirm('Ar tikrai norite ištrinti?')\"><img src=\"images/icons/tag_blue_delete.png\" alt=\"delete\" border=\"0\" /></a>"
			);
		}
		//nupiesiam moderatoriu lentele
		$bla = new Table();
		lentele("TODO - darbų sarašas. Viso:".$i,"<button onclick=\"window.location='".url('n,1')."'\">Sukurti naują užduotį</button><hr />".$bla->render($info1));
		unset($info1,$i);
	}
}

if (isset($_POST) && !empty($_POST) && isset($_POST['todo'])) {
	//apsauga nuo kenksmingo kodo
	include_once('priedai/safe_html.php');
	// nurodome masyva leidziamu elementu DUK
	// - tagai kurie uzdaromi atskirai (<p></p>) pazymeti kaip 1
	// - tagai kuriuos uzdaryti nebutina (<hr>) zymimi kaip 0
	$tags= array ( "p"=>1, "br"=>0, "a"=>1, "img"=>0,
	"li"=>1, "ol"=>1, "ul"=>1,
	"b"=>1, "i"=>1, "em"=>1, "strong"=>1,
	"del"=>1, "ins"=>1, "u"=>1, "code"=>1, "pre"=>1,
	"blockquote"=>1, "hr"=>0, "span"=>1, "font"=>1,"h1"=>1,"h2"=>1,"h3"=>1,
	"table"=>1, "tr"=>1, "td"=>1, "th"=>1,"tbody"=>1, "div"=>1
	);

	$pavadinimas = safe_html($_POST['Pavadinimas'], $tags );
	$aprasymas = safe_html($_POST['Aprasymas'], $tags );
	$atliktas = ceil((int)$_POST['Atliktas']);
	$id = ceil((int)$url['e']);

	//jeigu rašom nauja
	if ($_POST['todo'] == 'Įrašyti') {
		$q = "INSERT INTO `".LENTELES_PRIESAGA."todo` (`pavadinimas`,`aprasymas`,`atliktas`) VALUES (
		".escape($pavadinimas).",
		".escape($aprasymas).",
		".escape($atliktas).");";
		mysql_query1($q) or die (mysql_error());
		redirect("?id,".(int)$_GET['id'].";a,".(int)$_GET['a']."","header");
	}

	//jeigu redaguojam
	elseif ($_POST['todo'] == 'Keisti') {
		$q = "UPDATE `".LENTELES_PRIESAGA."todo` SET
		`pavadinimas` = ".escape($pavadinimas).",
		`aprasymas` = ".escape($aprasymas).",
		`atliktas` = ".escape($atliktas)." WHERE `id`=".$id." LIMIT 1 ;";
		mysql_query1($q) or die (mysql_error());
		redirect("?id,".(int)$_GET['id'].";a,".(int)$_GET['a']."","header");
	}

	//jeigu kažkas netaip
	else {
		klaida("Kas per velnias?","Tai jau čia kažką netaip padarei. Mėgink dar kartą");
	}
}

/*
* Procentai isreiksti grafiskai
*/
function rodom_todo($dabar,$max=100) {

	//paveiksleliu nustatymai
	$bg_pic = "http://img340.imageshack.us/img340/2239/loadbarbgnc2.gif"; //fono paveiksliukas (neaktyvi zona)
	$nulis_pic = "http://img231.imageshack.us/img231/8220/nosounddh2.gif"; //rodomoas paveiksliukas jei rezultatas lygus 0% (pvz jei nera klausytoju)
	$mazas_pic = "http://img403.imageshack.us/img403/8008/loadbarreduh0.gif"; //uzsidengiantis paveiksliukas (zalia)
	$vidutinis_pic = "http://img230.imageshack.us/img230/3815/loadbaryellowue2.gif"; //Kai vidutine procentu israiska (geltona)
	$didelis_pic = "http://img340.imageshack.us/img340/456/loadbargreenyk1.gif"; //Kai arti arba lygu 100 procentu (raudona)

	//pagrindiniai veiksmai
	$procentai = (int)round((100 * $dabar) / $max);
	$width = $procentai; //paveiksliuko plotis procentaliai
	if ($procentai <= 1) {$pic = $nulis_pic; $width = "100";}
	elseif ($procentai <= 40) { $pic = $mazas_pic; }
	elseif ($procentai <= 80) { $pic = $vidutinis_pic; }
	elseif ($procentai <= 100) { $pic = $didelis_pic; }
	else { $pic = $didelis_pic; $width = "100"; } //jei daugiau nei 100%

	//atvaziduojam lentele su rezultatais
	return "<table border='0' width='100%' title='header=[Informacija] body=[<br/>Užduotis atlikta:<b>".$dabar."%</b><br/><br/>] fade=[on]'><tr><td style='padding: 0px; background-image: url(".$bg_pic."); background-repeat: repeat-x'><img height=15 width='".$width."%' src='$pic' alt='$dabar'></td></tr></table>";
}

?>