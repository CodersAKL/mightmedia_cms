<?php
/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: FDisk $
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision: 375 $
 * @$Date: 2010-02-07 16:15:41 +0200 (Sun, 07 Feb 2010) $
 * */
?>

<script type="text/javascript" src="<?php echo ROOT; ?>javascript/jquery/jquery.asmselect.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
		$("#test-list").sortable({
			'handle':'.handle',
			'axis':'y',
			'update':function () {
				var order = $('#test-list').sortable('serialize');
				$("#la").show("slow");
				$("#la").hide("slow");
				$.post("<?php echo url( '?id,' . $_GET['id'] . ';a,' . $_GET['a'] . ';' ); ?>", {order:order});
			}
		});


		$("select[multiple]").asmSelect({
			'addItemTarget':'bottom',
			'animate':true,
			'highlight':true,
			'removeLabel':'<?php echo $lang['system']['delete']; ?>',
			'highlightAddedLabel':'<?php echo $lang['admin']['added']; ?>: ',
			'highlightRemovedLabel':'<?php echo $lang['sb']['deleted']; ?>: ',
			'sortable':true
		});

	});
</script>

<?php
unset( $text );

if ( !defined( "LEVEL" ) || LEVEL > 1 || !defined( "OK" ) ) {
	redirect( 'location: http://' . $_SERVER["HTTP_HOST"] . '' );
}
if ( isset( $_POST['order'] ) ) {
	$case_place = '';
	$where      = '';
	/*
	 * Post pavyzdys: listItem[]=19&listItem[]=17&listItem[]=2&listItem[]=20&listItem[]=15&listItem[]=1&listItem[]=14&listItem[]=3&listItem[]=8&listItem[]=7&listItem[]=9&listItem[]=13
	 */
	parse_str( $_POST['order'], $array ); //paverciam i masyva

	foreach ( $array['listItem'] as $position => $item ):
		$case_place .= "WHEN " . (int)$item . " THEN '" . (int)$position . "' ";
		$where .= "$item,";
	endforeach;
	$where = rtrim( $where, ", " );
	$sqlas = "UPDATE `" . LENTELES_PRIESAGA . "page` SET `place`=  CASE id " . $case_place . " END WHERE id IN (" . $where . ")";

	echo $sqlas . " done";
	$result = mysql_query1( $sqlas );
	delete_cache( "SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
} else {
	$buttons = "
<div class=\"btns\">
	<a href=\"" . url( "?id," . $url['id'] . ";a,{$_GET['a']};n,1" ) . "\" class=\"btn\"><span><img src=\"" . ROOT . "images/icons/script__plus.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['page_select']}</span></a>
	<a href=\"" . url( "?id,{$_GET['id']};a,{$_GET['a']};n,2" ) . "\" class=\"btn\"><span><img src=\"" . ROOT . "images/icons/script__pencil.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['page_create']}</span></a>
	<a href=\"" . url( "?id,{$_GET['id']};a,{$_GET['a']};n,3" ) . "\" class=\"btn\"><span><img src=\"" . ROOT . "images/icons/script__pencil.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['page_tree']}</span></a>
</div>";
	echo $buttons;
	$parent     = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `parent`='0' AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
	$parents[0] = "";
	foreach ( $parent as $parent_row ) {
		$parents[$parent_row['id']] = $parent_row['pavadinimas'];
	}
	$lygiai = array_keys( $conf['level'] );


	foreach ( $lygiai as $key ) {
		$teises[$key] = $conf['level'][$key]['pavadinimas'];
	}
	$teises[0] = $lang['admin']['for_guests'];


	if ( isset( $_POST['Naujas_puslapis2'] ) && $_POST['Naujas_puslapis2'] == $lang['admin']['page_create'] ) {
		// Nurodote failo pavadinimą:
		//$failas = ROOT . "puslapiai/" . preg_replace( "/[^a-z0-9-]/", "_", strtolower( $_POST['pav'] ) ) . ".php";
		$failas = ROOT . "puslapiai/" . seo_url( basename( $_POST['pav'] ), '' ) . ".php";

		// Nurodote įrašą kuris bus faile kai jį sukurs:
		$tekstas = str_replace( array( '$', 'HTML' ), array( '&#36;', 'html' ), $_POST['Page'] );

		$irasas = '<?php
$text =
<<<HTML
' . $tekstas . '
HTML;
lentele($page_pavadinimas,$text);
?>';
		//Tikrinam ar nera tokio pacio failo
		if (file_exists($failas)) {
			klaida($lang['system']['error'], "{$lang['system']['file_exists']}.");
		} else {
			// Irasom faila
			$fp = fopen( $failas, "w+" );
			fwrite( $fp, $irasas );
			fclose( $fp );
			chmod( $failas, 0777 );
			// Rezultatas:
			//msg($lang['system']['done'], "{$lang['admin']['page_created']}.");
			redirect( url( "?id,{$_GET['id']};a,{$_GET['a']};n,1" ), "header" );
		}
	}

	if ( isset( $url['d'] ) && isnum( $url['d'] ) && $url['d'] > 0 ) {
		mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "page` WHERE `id`= " . escape( (int)$url['d'] ) . " LIMIT 1" );
		mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "page` SET `parent`='0' WHERE `parent`=" . escape( (int)$url['d'] ) . "" );
		delete_cache( "SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
		redirect( url( "?id," . $url['id'] . ";a," . $url['a'] ), "header" );
	} elseif ( isset( $url['n'] ) && $url['n'] == 1 ) {
		if ( isset( $_POST['Naujas_puslapis'] ) && $_POST['Naujas_puslapis'] == $lang['admin']['page_create'] ) {
			$psl    = input( $_POST['Page'] );
			$teises = serialize( ( isset( $_POST['Teises'] ) ? $_POST['Teises'] : 0 ) );
			$file   = input( $_POST['File'] );

			if ( empty( $psl ) || $psl == '' ) {
				$psl = basename( $file, ".php" );
			}
			$show = input( $_POST['Show'] );
			if ( strlen( $show ) > 1 ) {
				$align = 'Y';
			}
			$sql = "INSERT INTO `" . LENTELES_PRIESAGA . "page` (`pavadinimas`, `file`, `place`, `show`, `teises`,`parent`, `lang`) VALUES (" . escape( $psl ) . ", " . escape( $file ) . ", '0', " . escape( $show ) . ", " . escape( $teises ) . "," . escape( (int)$_POST['parent'] ) . ", " . escape( lang() ) . ")";
			mysql_query1( $sql );
			delete_cache( "SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
			redirect( url( "?id," . $url['id'] . ";a," . $url['a'] ), "header" );
		}
		$failai = getFiles( ROOT . 'puslapiai/' );


		foreach ( $failai as $file ) {
			if ( $file['type'] == 'file' ) {
				if ( $file['name'] !== 'style-switcher.php' && $file['name'] !== 'klaida.php' && !isset( $conf['puslapiai'][$file['name']]['id'] ) ) {
					$puslapiai[basename( $file['name'] )] = ( isset( $lang['pages'][$file['name']] ) ? $lang['pages'][$file['name']] : nice_name( basename( $file['name'], '.php' ) ) );
				}
			}
		}

		if ( !isset( $puslapiai ) || count( $puslapiai ) < 1 ) {
			klaida( $lang['system']['warning'], "<h3>{$lang['admin']['page_nounused']}</h3>" );
		} else {
			$info = " <img src=\"" . ROOT . "/images/icons/help.png\" title=\"{$lang['system']['about_allow_pg']}\">";
			$psl = array(
				"Form"                                        => array( "action" => "", "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "new_panel" ),
				$lang['admin']['page_name']                   => array(
					"type"  => "text",
					"value" => "{$lang['admin']['page_name']}",
					"name"  => "Page",
					"class" => "input" ),

				$lang['admin']['page_file'] . "<a id=\"linkas\" onclick=\"$('#failas').replaceWith('<input name=\'File\' value=\'http://\' class=\'input\' />');$('#linkas').empty();return fale;\">[{$lang['admin']['page_link']}]</a>"  => array(
					"type"  => "select",
					"value" => $puslapiai,
					"name"  => "File",
					"id"    => "failas" ),

				"Sub"                                         => array(
					"type"  => "select",
					"value" => $parents,
					"name"  => "parent" ),

				$lang['admin']['page_show']                   => array(
					"type"  => "select",
					"value" => array( "Y" => $lang['admin']['yes'],
					                  "N" => "{$lang['admin']['no']}" ),
					"name"  => "Show" ),

				$lang['admin']['page_showfor'] . $info        => array(
					"type"  => "select",
					"extra" => "multiple=multiple",
					"value" => $teises,
					"class" => "asmSelect",
					"style" => "width:100%",
					"name"  => "Teises[]",
					"id"    => "punktai" ),
				""                                            => array(
					"type" => "submit",
					"name" => "Naujas_puslapis",
					"value" => $lang['admin']['page_create'] )
			);
			
			$formClass = new Form($psl);
			lentele($lang['admin']['page_select'], $formClass->form());
		}
	} elseif ( isset( $url['n'] ) && $url['n'] == 2 ) {
		$psl = array(

			"Form"                               => array(
				"action"  => "",
				"method"  => "post",
				"enctype" => "",
				"id"      => "",
				"class"   => "",
				"name"    => "new_page2" ),

			"{$lang['admin']['page_filename']}:" => array(
				"type"  => "text",
				"value" => "{$lang['admin']['page_name']}",
				"name"  => "pav",
				"class" => "input" ),

			"{$lang['admin']['page_text']}:"     => array(
				"type"  => "string",
				"value" => editor( 'spaw', 'standartinis', array( 'Page' => 'Page' ), FALSE ),
				"name"  => "Page",
				"class" => "input",
				"rows"  => "8",
				"class" => "input" ),
			""                                   => array(
				"type"  => "submit",
				"name"  => "Naujas_puslapis2",
				"value" => $lang['admin']['page_create'] )
		);

		$formClass = new Form($psl);
		lentele($lang['admin']['page_create'], $formClass->form());
	} elseif ( isset( $url['n'] ) && $url['n'] == 3 ) {
		//tree
		$data2 = '';
		$res   = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `lang`=" . escape( lang() ) . " ORDER BY `place` ASC" );
		foreach ( $res as $row ) {
			if ( teises( $row['teises'], $_SESSION[SLAPTAS]['level'] ) ) {
				$data2[$row['parent']][] = $row;
			}
		}
		$tree = build_tree( $data2 );
		$text = '<ul id="treemenu">' . $tree . '</ul>';
		lentele( $lang['system']['tree'], $text );
	}
	//puslapiai redagavimas
	elseif ( isset( $url['r'] ) && isnum( $url['r'] ) && $url['r'] > 0 ) {
		if ( isset( $_POST['Redaguoti_psl'] ) && $_POST['Redaguoti_psl'] == $lang['admin']['edit'] ) {
			$psl    = input( $_POST['pslp'] );
			$teises = serialize( ( isset( $_POST['Teises'] ) ? $_POST['Teises'] : 0 ) );
			if ( empty( $psl ) || $psl == '' ) {
				$psl = $lang['admin']['page_text'];
			}
			$align = input( $_POST['Align'] );
			if ( strlen( $align ) > 1 ) {
				$align = 'L';
			}
			$show = input( $_POST['Show'] );
			if ( strlen( $show ) > 1 ) {
				$align = 'Y';
			}
			$sql = "UPDATE `" . LENTELES_PRIESAGA . "page` SET `pavadinimas`=" . escape( $psl ) . ", `show`=" . escape( $show ) . ",`teises`=" . escape( $teises ) . ",`parent`= " . escape( (int)$_POST['parent'] ) . "  WHERE `id`=" . escape( (int)$url['r'] );
			mysql_query1( $sql );
			delete_cache( "SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
			redirect( url( "?id," . $url['id'] . ";a," . $url['a'] ), "header" );
		} else {
			$sql      = "SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `id`=" . escape( (int)$url['r'] ) . " LIMIT 1";
			$sql      = mysql_query1( $sql );
			$selected = unserialize( $sql['teises'] );
			unset( $parents[$sql['id']] );
			$info = " <img src=\"" . ROOT . "/images/icons/help.png\" title=\"{$lang['system']['about_allow_pg']}\">";
			$psl = array(
				"Form"                                 => array(
					"action"  => "",
					"method"  => "post",
					"enctype" => "",
					"id"      => "",
					"class"   => "",
					"name"    => "new_psl" ),

				$lang['admin']['page_name']            => array(
					"type"  => "text",
					"value" => $sql['pavadinimas'],
					"name"  => "pslp",
					"class" => "input" ),

				$lang['admin']['page_show']            => array(
					"type"     => "select",
					"value"    => array( "Y" => $lang['admin']['yes'],
					                     "N" => $lang['admin']['no'] ),
					"selected" => input( $sql['show'] ),
					"name"     => "Show" ),

				"Sub"                                  => array(
					"type"     => "select",
					"value"    => $parents,
					"selected" => input( $sql['parent'] ),
					"name"     => "parent" ),

				$lang['admin']['page_showfor'] . $info => array(
					"type"  => "select",
					"extra" => "multiple=multiple",
					"value" => $teises,
					"class" => "asmSelect",
					"style" => "width:100%",
					"name"  => "Teises[]",
					"id"    => "punktai" ),

				""                                     => array(
					"type"  => "submit",
					"name"  => "Redaguoti_psl",
					"value" => $lang['admin']['edit'] )
			);


			if ( !empty( $selected ) ) {
				$psl[$lang['admin']['page_showfor'] . $info]['selected'] = $selected;
			}
			
			$formClass = new Form($psl);
			lentele($sql['pavadinimas'], $formClass->form());
		}
	}

	//Redaguojam puslapiai turini
	elseif ( isset( $url['e'] ) && isnum( $url['e'] ) && $url['e'] > 0 ) {
		$psl_id = (int)$url['e']; //puslapiai ID

		if ( isset( $_POST['Redaguoti_txt'] ) && $_POST['Redaguoti_txt'] == $lang['admin']['edit'] ) {
			$sql     = "SELECT `file`,`pavadinimas` FROM `" . LENTELES_PRIESAGA . "page` WHERE `id`=" . escape( $psl_id ) . " LIMIT 1";
			$sql     = mysql_query1( $sql );
			$tekstas = str_replace( array( '$', 'HTML' ), array( '&#36;', 'html' ), $_POST['Page'] );
			$irasas = '<?php
$text =
<<<HTML
' . stripslashes( $tekstas ) . '
HTML;
lentele($page_pavadinimas,$text);
?>';

			// Irasom faila
			$fp = fopen( ROOT . 'puslapiai/' . $sql['file'], "w+" );
			fwrite( $fp, $irasas );
			fclose( $fp );
			chmod( ROOT . 'puslapiai/' . $sql['file'], 0777 );
		} else {

			$sql = "SELECT `id`, `pavadinimas`, `file` FROM `" . LENTELES_PRIESAGA . "page` WHERE `id`=" . escape( $psl_id ) . " LIMIT 1";
			$sql = mysql_query1( $sql );
			//tikrinam failo struktura

			$lines      = file( ROOT . 'puslapiai/' . $sql['file'] );
			$resultatai = array();

			$zodiz = '$text =';
			for ( $i = 0; $i < count( $lines ); $i++ ) {
				$temp = trim( $lines[$i] );
				if ( substr_count( $temp, $zodiz ) > 0 ) {
					$resultatai[] = $temp;
					$nr           = ( $i + 1 );
				}
			}

			//tikrinimo pabaiga
			if ( isset( $nr ) && $nr == 2 ) {
				$page_pavadinimas = $sql['pavadinimas'];

				include ROOT . 'puslapiai/' . $sql['file'];

				$puslapio_txt = $text;

				$puslapis = array(
					"Form"                      => array(
						"action"  => "",
						"method"  => "post",
						"enctype" => "",
						"id"      => "",
						"class"   => "",
						"name"    => "psl_txt" ),
					$lang['admin']['page_text'] => array(
						"type"  => "string",
						"value" => editor( 'spaw', 'standartinis', array( 'Page' => 'Page' ), array( 'Page' => $puslapio_txt ) ),
						"name"  => "Turinys",
						"class" => "input",
						"rows"  => "10" ),

					""                          => array(
						"type"  => "submit",
						"name"  => "Redaguoti_txt",
						"value" => $lang['admin']['edit'] )
				);

				$formClass = new Form($puslapis);
				lentele($sql['pavadinimas'], $formClass->form());
			} else {
				klaida( $lang['system']['warning'], $lang['admin']['page_cantedit'] );
			}
		}
	} else {

		$li         = '';
		$recordSet1 = mysql_query1( "SELECT * from `" . LENTELES_PRIESAGA . "page` WHERE `show`= 'Y' AND `lang` = " . escape( lang() ) . " order by place" );
		$listArray1 = array();

		foreach ( $recordSet1 as $row ) {
			$data[$row['parent']][] = $row;
		}

		$li      = !empty( $data ) ? build_menu_admin( $data ) : '';
		$tekstas = '<div id="la" style="display:none"><b>' . $lang['system']['updated'] . '</b></div><fieldset><legend>' . $lang['admin']['page_navigation'] . '</legend><ul id="test-list">' . $li . '</ul>';
		$tekstas .= '</fieldset>';
		$sql25 = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `show`= 'N' AND `lang` = " . escape( lang() ) . " order by id" );
		$tekstas .= '<fieldset><legend>' . $lang['admin']['page_other'] . '</legend><ul>';

		if ( sizeof( $sql25 ) > 0 ) {
			foreach ( $sql25 as $sql2 ) {
				$tekstas .= '<li class="drag_block"><a href="' . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';d,' . $sql2['id'] ) . '" style="align:right" onClick="return confirm(\'' . $lang['system']['delete_confirm'] . '\')"><img src="' . ROOT . 'images/icons/cross.png" title="' . $lang['admin']['delete'] . '" align="right" /></a><a href="' . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';r,' . $sql2['id'] ) . '" style="align:right"><img src="' . ROOT . 'images/icons/wrench.png" title="' . $lang['admin']['edit'] . '" align="right" /></a><a href="' . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';e,' . $sql2['id'] ) . '" style="align:right"><img src="' . ROOT . 'images/icons/pencil.png" title="' . $lang['admin']['page_text'] . '" align="right" /></a>' . $sql2['pavadinimas'] . '</li>';
			}
		}

		$tekstas .= '</ul></fieldset>';
		lentele( $lang['admin']['meniu'], $tekstas );
	}
}

function build_menu_admin( $data, $id = 0 ) {

	global $url, $lang;
	$re = "";
	foreach ( $data[$id] as $row ) {
		$admin = '
				<a href="' . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';d,' . $row['id'] ) . '" style="align:right" onClick="return confirm(\'' . $lang['system']['delete_confirm'] . '\')"><img src="' . ROOT . 'images/icons/cross.png" title="' . $lang['admin']['delete'] . '" align="right" /></a>
				<a href="' . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';r,' . $row['id'] ) . '" style="align:right"><img src="' . ROOT . 'images/icons/wrench.png" title="' . $lang['admin']['edit'] . '" align="right" /></a>
				<a href="' . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';e,' . $row['id'] ) . '" style="align:right"><img src="' . ROOT . 'images/icons/pencil.png" title="' . $lang['admin']['page_text'] . '" align="right" /></a>
				<img src="' . ROOT . 'images/icons/arrow_inout.png" alt="move" width="16" height="16" class="handle" style="' . ( $row['parent'] != 0 ? "padding-left:20px" : "" ) . '" />';
		if ( isset( $data[$row['id']] ) ) {
			$re .= "\n\t\t<li id=\"listItem_" . $row['id'] . "\" class=\"drag_block\">" . $admin . "<a href=\"" . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';r,' . $row['id'] ) . "\">" . $row['pavadinimas'] . "</a>\n<ul id=\"test-list-" . $row['id'] . "\">\n\t";
			$re .= build_menu_admin( $data, $row['id'] );
			$re .= "\t</ul>\n\t</li>
			<script type=\"text/javascript\">
			$(\"#test-list-" . $row['id'] . "\").sortable({
			'handle' : '.handle',
			'axis': 'y',
			'update' : function () {
				var order = $('#test-list-" . $row['id'] . "').sortable('serialize');
				$(\"#la\").show(\"slow\");
				$(\"#la\").hide(\"slow\");
				$.post(\"" . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';ajax,1' ) . "\",{order:order});
			}
		});
			</script>
			";
		} else {
			$re .= "\n\t\t<li id=\"listItem_" . $row['id'] . "\" class=\"drag_block\">" . $admin . "<a href=\"" . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';r,' . $row['id'] ) . "\">" . $row['pavadinimas'] . "</a></li>";
		}
	}
	return $re;
}

?>