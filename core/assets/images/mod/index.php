<?
function show_files($dir,$type) {
	//$dir = "./images/gif";                                     //direktorija pvz: "gallerija"
	//==============[Pradzia]===============
	// Autorius: FDisk
	// Svetaine: www.mrcbug.com
	// Emailas: projektas[eta]gmail[taskas]com
	// Data: 2005.08.29
	// Apie: Sudeda failu esanc(iu; nurodytoje direktorijoje informacija; i; masyva; $failai
	$failai = array();                              //"$failai" kintamaji verciam i masyva
	if (is_dir($dir)) { $d = opendir($dir); }       //jeigu nurodyta direktorija egzistuoja
	else {                                          //kitu atveju metam klaidos pranesima
		user_error("<b><font color=red>Tokio folderio nera</font></b>");
		exit;
	}
	while ($failas = readdir($d)) {                 //Paleidziame cikla
		if (is_file($dir.'/'.$failas)) {            //Jeigu tai failas tesiam veiksma
			$a = explode(".",basename($failas));      //suskaldom failo pavadinima pagal taskus
			$ext = $a[count($a) - 1];                 //nustatom failo tipa
			$vardas = urlencode($a[0]);               //patvarkom failo pavadinima
			if ($ext == "$type") {
				$failai[] = array(                        //talpiname informacija i ARRAY - masyva
				'failas' => $dir.'/'.$failas,
				'laikas' => filemtime($dir.'/'.$failas),
				'dydis' => filesize($dir.'/'.$failas),
				'vardas' => $vardas,
				'tipas' => $ext
				);
			}
		}
		else {
			$a = basename($failas);
			$vardas = urlencode($a);
				$failai[] = array(                        //talpiname informacija i ARRAY - masyva
				'failas' => $dir.'/'.$failas,
				'laikas' => filemtime($dir.'/'.$failas),
				'dydis' => filesize($dir.'/'.$failas),
				'vardas' => $vardas
				);
		}
	}
	$kiek = count($failai);                        //suzinau kiek yra irasu MASYVE $failai
	if (isset($kiek)) {                                   //jeigu masyvas netuscias
		foreach ($failai as $key => $row) {
			$laikas[$key] = $row['laikas'];
		}
		array_multisort($laikas,SORT_DESC,$failai); //surusiuojam failus pagal sukurimo data
		unset($laikas,$kiek,$dir,$key,$row);        //nereikalingus kintamuosius istrinam
		return $failai;
	}
}
$failai = show_files(".","png");
$content = '';
if (isset($failai)) {
	foreach ($failai as $key => $row) {
		if (isset($row['tipas'])) { $content .= "<img src='$row[failas]' title='$row[vardas]'>\n"; }
		elseif ($row['vardas'] == "..") { $content .= "<a href='".$row['failas']."'><img src='http://www.mrcbug.com/icons/back.gif' border=0></a><br/>\n"; }
		elseif ($row['vardas'] != ".") { $content .= "<a href='".$row['failas']."'><img src='http://www.mrcbug.com/icons/dir.gif' border=0> ".$row['vardas']."</a><br/>\n"; }
	}
}
else { $content = "Tusècia"; }
echo $content;
//echo blokas("Naujausi failai",$content);

?>
