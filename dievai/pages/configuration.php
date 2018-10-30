<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: p.dambrauskas $
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision: 360 $
 * @$Date: 2009-11-20 17:27:27 +0200 (Fri, 20 Nov 2009) $
 **/

if ( !defined( "OK" ) ) {
	redirect( 'location: http://' . $_SERVER["HTTP_HOST"] );
}

if(BUTTONS_BLOCK) {
	lentele($lang['admin']['configuration'], buttonsMenu($buttons['configuration']));
}

if (isset($url['c'])) {
	if ($url['c'] == 'main') {
		if ( isset( $_POST ) && !empty( $_POST ) && isset( $_POST['Konfiguracija'] ) ) {
			$q   = array();
			$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape( $_POST['Copyright'] ) . ",'Copyright')  ON DUPLICATE KEY UPDATE `val`=" . escape( $_POST['Copyright'] );
			$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape( input( strip_tags( $_POST['Pastas'] ) ) ) . ",'Pastas')  ON DUPLICATE KEY UPDATE `val`=" . escape( input( strip_tags( $_POST['Pastas'] ) ) );
			$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape( (int)$_POST['News_limit'] ) . ",'News_limit')  ON DUPLICATE KEY UPDATE `val`=" . escape( (int)$_POST['News_limit'] );
			$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape( input( strip_tags( $_POST['Stilius'] ) ) ) . ",'Stilius')  ON DUPLICATE KEY UPDATE `val`=" . escape( input( strip_tags( $_POST['Stilius'] ) ) );
			$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape( basename( $_POST['pirminis'], '.php' ) ) . ",'pirminis')  ON DUPLICATE KEY UPDATE `val`=" . escape( basename( $_POST['pirminis'], '.php' ) );
			$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape( basename( $_POST['kalba'] ) ) . ",'kalba')  ON DUPLICATE KEY UPDATE `val`=" . escape( basename( $_POST['kalba'] ) );
			$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape( (int)$_POST['koment'] ) . ",'kmomentarai_sveciams')  ON DUPLICATE KEY UPDATE `val`=" . escape( (int)$_POST['koment'] );
			$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape( $_POST['Editor'] ) . ",'Editor')  ON DUPLICATE KEY UPDATE `val`=" . escape( $_POST['Editor'] );
			$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape( (int)$_POST['galbalsuot'] ) . ",'galbalsuot')  ON DUPLICATE KEY UPDATE `val`=" . escape( (int)$_POST['galbalsuot'] );
			$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape( (int)$_POST['hyphenator'] ) . ",'hyphenator')  ON DUPLICATE KEY UPDATE `val`=" . escape( (int)$_POST['hyphenator'] );
		
			foreach ($q as $sql) {
				mysql_query1($sql);
			}
			
			delete_cache( "SELECT id, reg_data, gim_data, login_data, nick, vardas, levelis, pavarde FROM `" . LENTELES_PRIESAGA . "users` WHERE levelis=1 OR levelis=2" );
			
			redirect(
				url("?id," . $url['id'] . ";a," . $url['a'] . ";c," . $url['c']),
				"header",
				[
					'type'		=> 'success',
					'message' 	=> $lang['admin']['configuration_updated']
				]
			);
		}
		
		$stiliai             = getDirs(ROOT . 'stiliai/', 'remontas');
		$editors             = getDirs('htmlarea/');
		$editors['textarea'] = 'textarea';
		$kalbos              = getFiles( ROOT . 'lang/' );

		foreach ($kalbos as $file) {
			if ($file['type'] == 'file') {
				$kalba[basename($file['name'])] = basename($file['name'], '.php');
			}
		}
		
		if ( isset( $conf['puslapiai'] ) && count( $conf['puslapiai'] ) > 0 ) {
			$puslapiai = array_keys( $conf['puslapiai'] );
			foreach ( $puslapiai as $key ) {
				$psl[$key] = ( isset( $lang['pages'][$key] ) ? $lang['pages'][$key] : nice_name( basename( $key, '.php' ) ) );
			}
		} else {
			$psl[] = '';
		}
		
		$settings = [ 
			"Form" => [
				"action" => "", 
				"method" => "post", 
				"enctype" => "", 
				"id" => "", 
				"class" => "", 
				"name" => "reg"
			],

			$lang['admin']['homepage']        => [
				"type" 		=> "select", 
				"value" 	=> $psl, 
				"selected" 	=> (isset( $conf['pirminis'] ) ? $conf['pirminis'] . '.php' : ''), 
				"name" 		=> "pirminis"
			],

			$lang['admin']['copyright']       => [
				"type" 	=> "text", 
				"value" => input($conf['Copyright']), 
				"name" 	=> "Copyright"
			],

			$lang['admin']['email']           => [
				"type" 	=> "text", 
				"value" => input($conf['Pastas']), 
				"name" 	=> "Pastas"
			],

			$lang['admin']['comm_guests']     => [
				"type" 		=> "select", 
				"value" 	=> [
					"1" => "{$lang['admin']['yes']}", 
					"0" => "{$lang['admin']['no']}", 
					"3"	=> "{$lang['admin']['comments_off']}"
				],
				"selected" 	=> input(@$conf['kmomentarai_sveciams']),
				"name" 		=> "koment"
			],

			$lang['admin']['gallery_rate']    => [
				"type"  	=> "switch",
				"value" 	=> '1',
				"name"  	=> "galbalsuot",
				'form_line'	=> 'form-not-line',
				'checked'	=> (input($conf['galbalsuot']) == 1 ? true : false)
			],

			$lang['admin']['newsperpage']     => [
				"type" 	=> "text", 
				"value" => input($conf['News_limit']), 
				"name" 	=> "News_limit", 
				'extra' => "onkeyup=\"javascript:this.value=this.value.replace(/[^0-9]/g, '');\""
			],
		//                     "{$lang['admin']['cache']}:"           => array( "type" => "select", "value" => array( "1" => "{$lang['admin']['yes']}", "0" => "{$lang['admin']['no']}" ), "selected" => input( $conf['keshas'] ), "name" => "keshas"),
			$lang['admin']['theme']           => [
				"type" 		=> "select", 
				"value" 	=> $stiliai, 
				"selected" 	=> input($conf['Stilius']), 
				"name" 		=> "Stilius"
			],

			$lang['admin']['lang']            => [
				"type" 		=> "select", 
				"value" 	=> $kalba, 
				"selected" 	=> input($conf['kalba']), 
				"name" 		=> "kalba"
			],

			$lang['admin']['editor']          => [
				"type" 		=> "select", 
				"value" 	=> $editors, 
				"selected" 	=> input($conf['Editor']), 
				"name" 		=> "Editor"
			],

			$lang['admin']['use_hyphenator']  => [
				"type"  	=> "switch",
				"value" 	=> '1',
				"name"  	=> "hyphenator",
				'form_line'	=> 'form-not-line',
				'checked'	=> (input($conf['hyphenator']) == 1 ? true : false)
			],
			
			""                                     => [
				"type" 		=> "submit", 
				"name" 		=> "Konfiguracija", 
				"value" 	=> $lang['admin']['save'], 
				'form_line'	=> 'form-not-line',
			]
		];
		
		$formClass = new Form($settings);
		lentele($lang['admin']['configuration_main'], $formClass->form());
		
	} else if($url['c'] == 'maintenance') {

		if ( isset( $_POST ) && !empty( $_POST ) && isset( $_POST['Konfiguracija'] ) ) {
			$q = [];
			$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape( (int)$_POST['Palaikymas'] ) . ",'Palaikymas')  ON DUPLICATE KEY UPDATE `val`=" . escape( (int)$_POST['Palaikymas'] );
			$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape( $_POST['Maintenance'] ) . ",'Maintenance')  ON DUPLICATE KEY UPDATE `val`=" . escape( $_POST['Maintenance'] );
			
			foreach ($q as $sql) {
				mysql_query1($sql);
			}

			delete_cache( "SELECT id, reg_data, gim_data, login_data, nick, vardas, levelis, pavarde FROM `" . LENTELES_PRIESAGA . "users` WHERE levelis=1 OR levelis=2" );
			
			redirect(
				url("?id," . $url['id'] . ";a," . $url['a'] . ";c," . $url['c']),
				"header",
				[
					'type'		=> 'success',
					'message' 	=> $lang['admin']['configuration_updated']
				]
			);
		}

		$settings = [ 
			"Form" => [
				"action" => "", 
				"method" => "post", 
				"enctype" => "", 
				"id" => "", 
				"class" => "", 
				"name" => "reg"
			],
			$lang['admin']['maintenance']     => [
				"type"  	=> "switch",
				"value" 	=> '1',
				"name"  	=> "Palaikymas",
				'form_line'	=> 'form-not-line',
				'checked'	=> (input($conf['Palaikymas']) == 1 ? true : false)
			],
			$lang['admin']['maintenancetext'] => [
				"type" => "string", 
				"value" => editor( 'jquery', 'mini', 'Maintenance', isset( $conf['Maintenance'] ) ? $conf['Maintenance'] : '' )
			],
			""                                     => [
				"type" 		=> "submit", 
				"name" 		=> "Konfiguracija", 
				"value" 	=> $lang['admin']['save'], 
				'form_line'	=> 'form-not-line',
			]
		];

		$formClass = new Form($settings);
		lentele($lang['admin']['configuration_maintenance'], $formClass->form());

	} else if($url['c'] == 'seo') {
		if ( isset( $_POST ) && !empty( $_POST ) && isset( $_POST['Konfiguracija'] ) ) {
			$q = [];
			$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape( $_POST['Apie'] ) . ",'Apie')  ON DUPLICATE KEY UPDATE `val`=" . escape( $_POST['Apie'] ) . "";
			$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape( input( strip_tags( $_POST['keywords'] ) ) ) . ",'keywords')  ON DUPLICATE KEY UPDATE `val`=" . escape( input( strip_tags( $_POST['keywords'] ) ) ) . "";
			$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape( input( strip_tags( $_POST['Pavadinimas'] ) ) ) . ",'Pavadinimas')  ON DUPLICATE KEY UPDATE `val`=" . escape( input( strip_tags( $_POST['Pavadinimas'] ) ) );
			$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape( $_POST['F_urls'] ) . ",'F_urls')  ON DUPLICATE KEY UPDATE `val`=" . escape( $_POST['F_urls'] );

			foreach ($q as $sql) {
				mysql_query1($sql);
			}

			delete_cache( "SELECT id, reg_data, gim_data, login_data, nick, vardas, levelis, pavarde FROM `" . LENTELES_PRIESAGA . "users` WHERE levelis=1 OR levelis=2" );
			
			redirect(
				url("?id," . $url['id'] . ";a," . $url['a'] . ";c," . $url['c']),
				"header",
				[
					'type'		=> 'success',
					'message' 	=> $lang['admin']['configuration_updated']
				]
			);
		}
		$settings = [ 
			"Form" => [
				"action" 	=> "", 
				"method" 	=> "post", 
				"enctype" 	=> "", 
				"id" 		=> "", 
				"class" 	=> "", 
				"name" 		=> "reg"
			],

			$lang['admin']['sitename']	=> [
				"type" 	=> "text", 
				"value" => input( $conf['Pavadinimas'] ), 
				"name" 	=> "Pavadinimas"
			],

			$lang['admin']['about']		=> [
				"type" 	=> "textarea", 
				"name" 	=> "Apie", 
				"value" => ( isset( $conf['Apie'] ) ? $conf['Apie'] : '' )
			],

			$lang['admin']['keywords']	=> [
				"type" 	=> "text", 
				"value" => input( $conf['Keywords'] ), 
				"name" 	=> "keywords"
			],
			
			"Friendly url:"				=> [
				"type"		=> "select", 
				"value"		=>	[
					'/'=> '/', 
					';'=> ';', 
					'0'=> $lang['admin']['off']
				], 
				"selected"	=> $conf['F_urls'], 
				"name"		=> "F_urls"
			],

			""                                     => [
				"type" 		=> "submit", 
				"name" 		=> "Konfiguracija", 
				"value" 	=> $lang['admin']['save'], 
				'form_line'	=> 'form-not-line',
			]
		];

		$formClass = new Form($settings);
		lentele($lang['admin']['configuration_seo'], $formClass->form());
	}

}