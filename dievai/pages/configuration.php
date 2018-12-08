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
	lentele($lang['admin']['configuration'], buttonsMenu(buttons('configuration')));
}

if (isset($url['c'])) {
	if ($url['c'] == 'main') {
		if ( isset( $_POST ) && !empty( $_POST ) && isset( $_POST['Konfiguracija'] ) ) {
			setSettingsValue( $_POST['Copyright'], 'Copyright');
			setSettingsValue( input( strip_tags( $_POST['Pastas'] ) ), 'Pastas');
			setSettingsValue( (int)$_POST['News_limit'] , 'News_limit');
			setSettingsValue( input( strip_tags( $_POST['Stilius'] ) ) , 'Stilius');
			setSettingsValue( basename( $_POST['pirminis'], '.php' ) , 'pirminis');
			setSettingsValue( basename( $_POST['kalba'] ) , 'kalba');
			setSettingsValue( (int)$_POST['koment'] , 'kmomentarai_sveciams');
			setSettingsValue( $_POST['Editor'] , 'Editor');
			setSettingsValue( (int)$_POST['galbalsuot'] , 'galbalsuot');
			setSettingsValue( (int)$_POST['hyphenator'] , 'hyphenator');
			setSettingsValue( $_POST['googleanalytics'] , 'googleanalytics');
			
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
				"selected" 	=> (getSettingsValue('pirminis') . '.php'), 
				"name" 		=> "pirminis"
			],

			$lang['admin']['copyright']       => [
				"type" 	=> "text", 
				"value" => input(getSettingsValue('Copyright')), 
				"name" 	=> "Copyright"
			],

			$lang['admin']['email']           => [
				"type" 	=> "text", 
				"value" => input(getSettingsValue('Pastas')), 
				"name" 	=> "Pastas"
			],

			$lang['admin']['comm_guests']     => [
				"type" 		=> "select", 
				"value" 	=> [
					"1" => "{$lang['admin']['yes']}", 
					"0" => "{$lang['admin']['no']}", 
					"3"	=> "{$lang['admin']['comments_off']}"
				],
				"selected" 	=> input(@getSettingsValue('kmomentarai_sveciams')),
				"name" 		=> "koment"
			],

			$lang['admin']['gallery_rate']    => [
				"type"  	=> "switch",
				"value" 	=> '1',
				"name"  	=> "galbalsuot",
				'form_line'	=> 'form-not-line',
				'checked'	=> (input(getSettingsValue('galbalsuot')) == 1 ? true : false)
			],

			$lang['admin']['newsperpage']     => [
				"type" 	=> "text", 
				"value" => input(getSettingsValue('News_limit')), 
				"name" 	=> "News_limit", 
				'extra' => "onkeyup=\"javascript:this.value=this.value.replace(/[^0-9]/g, '');\""
			],
		//                     "{$lang['admin']['cache']}:"           => array( "type" => "select", "value" => array( "1" => "{$lang['admin']['yes']}", "0" => "{$lang['admin']['no']}" ), "selected" => input( $conf['keshas'] ), "name" => "keshas"),
			$lang['admin']['theme']           => [
				"type" 		=> "select", 
				"value" 	=> $stiliai, 
				"selected" 	=> input(getSettingsValue('Stilius')), 
				"name" 		=> "Stilius"
			],

			$lang['admin']['lang']            => [
				"type" 		=> "select", 
				"value" 	=> $kalba, 
				"selected" 	=> input(getSettingsValue('kalba')), 
				"name" 		=> "kalba"
			],

			$lang['admin']['editor']          => [
				"type" 		=> "select", 
				"value" 	=> $editors, 
				"selected" 	=> input(getSettingsValue('Editor')), 
				"name" 		=> "Editor"
			],

			$lang['admin']['use_hyphenator']  => [
				"type"  	=> "switch",
				"value" 	=> '1',
				"name"  	=> "hyphenator",
				'form_line'	=> 'form-not-line',
				'checked'	=> (input(getSettingsValue('hyphenator')) == 1 ? true : false)
			],
			$lang['admin']['ga']  => [
				"type"  	=> "textarea",
				"value" 	=> input(getSettingsValue('googleanalytics')),
				"name"  	=> "googleanalytics"
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
			
			setSettingsValue( (int)$_POST['Palaikymas'] , 'Palaikymas');
			setSettingsValue( $_POST['Maintenance'] , 'Maintenance');

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
				'checked'	=> (input(getSettingsValue('Palaikymas')) == 1 ? true : false)
			],
			$lang['admin']['maintenancetext'] => [
				"type" => "string", 
				"value" => editor( 'jquery', 'mini', 'Maintenance', getSettingsValue('Maintenance'))
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

			setSettingsValue( $_POST['Apie'] , 'Apie');
			setSettingsValue( input( strip_tags( $_POST['keywords'] ) ) , 'keywords');
			setSettingsValue( input( strip_tags( $_POST['Pavadinimas'] ) ) , 'Pavadinimas');
			setSettingsValue( $_POST['F_urls'] , 'F_urls');

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
				"value" => input( getSettingsValue('Pavadinimas')), 
				"name" 	=> "Pavadinimas"
			],

			$lang['admin']['about']		=> [
				"type" 	=> "textarea", 
				"name" 	=> "Apie", 
				"value" => getSettingsValue('Apie')
			],

			$lang['admin']['keywords']	=> [
				"type" 	=> "text", 
				"value" => input( getSettingsValue('Keywords') ), 
				"name" 	=> "keywords"
			],
			
			"Friendly url:"				=> [
				"type"		=> "select", 
				"value"		=>	[
					'/'=> '/', 
					';'=> ';', 
					'0'=> $lang['admin']['off']
				], 
				"selected"	=> getSettingsValue('F_urls'), 
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