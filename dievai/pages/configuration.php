<?php

if ( !defined( "OK" ) ) {
	redirect( 'location: http://' . $_SERVER["HTTP_HOST"] );
}

if(BUTTONS_BLOCK) {
	lentele(getLangText('admin', 'configuration'), buttonsMenu(buttons('configuration')));
}

if (isset($url['c'])) {
	if ($url['c'] == 'main') {
		if ( isset( $_POST ) && !empty( $_POST ) && isset( $_POST['Konfiguracija'] ) ) {
			$req = array();
			$req[] = [
				'val' 		=> $_POST['Copyright'],
				'key' 		=> 'Copyright',
				'options' 	=> null
			];
			$req[] = [
				'val' 		=> input( strip_tags( $_POST['Pastas'] ) ),
				'key' 		=> 'Pastas',
				'options' 	=> null
			];
			$req[] = [
				'val' 		=> (int)$_POST['News_limit'],
				'key' 		=> 'News_limit',
				'options' 	=> null
			];
			$req[] = [
				'val' 		=> input( strip_tags( $_POST['Stilius'] ) ),
				'key' 		=> 'Stilius',
				'options' 	=> null
			];
			$req[] = [
				'val' 		=> basename( $_POST['pirminis'], '.php' ),
				'key' 		=> 'pirminis',
				'options' 	=> null
			];
			$req[] = [
				'val' 		=> basename( $_POST['kalba'] ),
				'key' 		=> 'kalba',
				'options' 	=> null
			];
			$req[] = [
				'val' 		=> (int)$_POST['koment'],
				'key' 		=> 'kmomentarai_sveciams',
				'options' 	=> null
			];
			$req[] = [
				'val' 		=> $_POST['Editor'],
				'key' 		=> 'Editor',
				'options' 	=> null
			];
			$req[] = [
				'val' 		=> (int)$_POST['galbalsuot'],
				'key' 		=> 'galbalsuot',
				'options' 	=> null
			];
			$req[] = [
				'val' 		=> (int)$_POST['hyphenator'],
				'key' 		=> 'hyphenator',
				'options' 	=> null
			];
			$req[] = [
				'val' 		=> $_POST['googleanalytics'],
				'key' 		=> 'googleanalytics',
				'options' 	=> null
			];

			foreach ($req as $row) {
				setSettingsValue( $row['val'], $row['key'], $row['options'] );
			}

			delete_cache( "SELECT id, reg_data, gim_data, login_data, nick, vardas, levelis, pavarde FROM `" . LENTELES_PRIESAGA . "users` WHERE levelis=1 OR levelis=2" );
			
			redirect(
				url("?id," . $url['id'] . ";a," . $url['a'] . ";c," . $url['c']),
				"header",
				[
					'type'		=> 'success',
					'message' 	=> getLangText('admin', 'configuration_updated')
				]
			);	
		}
		
		$stiliai             = getDirs(ROOT . 'content/themes/', 'maintenance');
		$editors             = getDirs('htmlarea/');
		$editors['textarea'] = 'textarea';
		$kalbos              = getFiles( ROOT . 'content/lang/' );

		foreach ($kalbos as $file) {
			if ($file['type'] == 'file') {
				$kalba[basename($file['name'])] = basename($file['name'], '.php');
			}
		}
		
		if ( isset( $conf['pages'] ) && count( $conf['pages'] ) > 0 ) {
			$pages = array_keys( $conf['pages'] );
			foreach ( $pages as $key ) {
				$psl[$key] = ( !empty( getLangText('pages', $key) ) ? getLangText('pages', $key) : nice_name( basename( $key, '.php' ) ) );
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

			getLangText('admin', 'homepage')        => [
				"type" 		=> "select", 
				"value" 	=> $psl, 
				"selected" 	=> (getSettingsValue('pirminis') . '.php'), 
				"name" 		=> "pirminis"
			],

			getLangText('admin', 'copyright')       => [
				"type" 	=> "text", 
				"value" => input(getSettingsValue('Copyright')), 
				"name" 	=> "Copyright"
			],

			getLangText('admin', 'email')           => [
				"type" 	=> "text", 
				"value" => input(getSettingsValue('Pastas')), 
				"name" 	=> "Pastas"
			],

			getLangText('admin', 'comm_guests')     => [
				"type" 		=> "select", 
				"value" 	=> [
					"1" => getLangText('admin', 'yes'), 
					"0" => getLangText('admin', 'no'), 
					"3"	=> getLangText('admin', 'comments_off')
				],
				"selected" 	=> input(@getSettingsValue('kmomentarai_sveciams')),
				"name" 		=> "koment"
			],

			getLangText('admin', 'gallery_rate')    => [
				"type"  	=> "switch",
				"value" 	=> '1',
				"name"  	=> "galbalsuot",
				'form_line'	=> 'form-not-line',
				'checked'	=> (input(getSettingsValue('galbalsuot')) == 1 ? true : false)
			],

			getLangText('admin', 'newsperpage')     => [
				"type" 	=> "text", 
				"value" => input(getSettingsValue('News_limit')), 
				"name" 	=> "News_limit", 
				'extra' => "onkeyup=\"javascript:this.value=this.value.replace(/[^0-9]/g, '');\""
			],
		//                     getLangText('admin', 'cache') . ":"           => array( "type" => "select", "value" => array( "1" => getLangText('admin', 'yes'), "0" => getLangText('admin', 'no')), "selected" => input( $conf['keshas'] ), "name" => "keshas"),
			getLangText('admin', 'theme')           => [
				"type" 		=> "select", 
				"value" 	=> $stiliai, 
				"selected" 	=> input(getSettingsValue('Stilius')), 
				"name" 		=> "Stilius"
			],

			getLangText('admin', 'lang')            => [
				"type" 		=> "select", 
				"value" 	=> $kalba, 
				"selected" 	=> input(getSettingsValue('kalba')), 
				"name" 		=> "kalba"
			],

			getLangText('admin', 'editor')          => [
				"type" 		=> "select", 
				"value" 	=> $editors, 
				"selected" 	=> input(getSettingsValue('Editor')), 
				"name" 		=> "Editor"
			],

			getLangText('admin', 'use_hyphenator')  => [
				"type"  	=> "switch",
				"value" 	=> '1',
				"name"  	=> "hyphenator",
				'form_line'	=> 'form-not-line',
				'checked'	=> (input(getSettingsValue('hyphenator')) == 1 ? true : false)
			],
			getLangText('admin', 'ga')  => [
				"type"  	=> "textarea",
				"value" 	=> input(getSettingsValue('googleanalytics')),
				"name"  	=> "googleanalytics"
			],
			
			""                                     => [
				"type" 		=> "submit", 
				"name" 		=> "Konfiguracija", 
				"value" 	=> getLangText('admin', 'save'), 
				'form_line'	=> 'form-not-line',
			]
		];
		
		$formClass = new Form($settings);
		lentele(getLangText('admin', 'configuration_main'), $formClass->form());
		
	} else if($url['c'] == 'maintenance') {

		if ( isset( $_POST ) && !empty( $_POST ) && isset( $_POST['Konfiguracija'] ) ) {

			$req = array();
			$req[] = [
				'val' 		=> (int)$_POST['Palaikymas'],
				'key' 		=> 'Palaikymas',
				'options' 	=> null
			];
			$req[] = [
				'val' 		=> $_POST['Maintenance'],
				'key' 		=> 'Maintenance',
				'options' 	=> null
			];
			foreach ($req as $row) {
				setSettingsValue( $row['val'], $row['key'], $row['options'] );
			}

			delete_cache( "SELECT id, reg_data, gim_data, login_data, nick, vardas, levelis, pavarde FROM `" . LENTELES_PRIESAGA . "users` WHERE levelis=1 OR levelis=2" );
			
			redirect(
				url("?id," . $url['id'] . ";a," . $url['a'] . ";c," . $url['c']),
				"header",
				[
					'type'		=> 'success',
					'message' 	=> getLangText('admin', 'configuration_updated')
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
			getLangText('admin', 'maintenance')     => [
				"type"  	=> "switch",
				"value" 	=> '1',
				"name"  	=> "Palaikymas",
				'form_line'	=> 'form-not-line',
				'checked'	=> (input(getSettingsValue('Palaikymas')) == 1 ? true : false)
			],
			getLangText('admin', 'maintenancetext') => [
				"type" => "string", 
				"value" => editor( 'jquery', 'mini', 'Maintenance', getSettingsValue('Maintenance'))
			],
			""                                     => [
				"type" 		=> "submit", 
				"name" 		=> "Konfiguracija", 
				"value" 	=> getLangText('admin', 'save'), 
				'form_line'	=> 'form-not-line',
			]
		];

		$formClass = new Form($settings);
		lentele(getLangText('admin', 'configuration_maintenance'), $formClass->form());

	} else if($url['c'] == 'seo') {
		if ( isset( $_POST ) && !empty( $_POST ) && isset( $_POST['Konfiguracija'] ) ) {

			$req = array();
			$req[] = [
				'val' 		=> $_POST['Apie'],
				'key' 		=> 'Apie',
				'options' 	=> null
			];
			$req[] = [
				'val' 		=> input( strip_tags( $_POST['keywords'] ) ),
				'key' 		=> 'keywords',
				'options' 	=> null
			];
			$req[] = [
				'val' 		=> input( strip_tags( $_POST['Pavadinimas'] ) ),
				'key' 		=> 'Pavadinimas',
				'options' 	=> null
			];
			$req[] = [
				'val' 		=> $_POST['F_urls'],
				'key' 		=> 'F_urls',
				'options' 	=> null
			];
			foreach ($req as $row) {
				setSettingsValue( $row['val'], $row['key'], $row['options'] );
			}
			delete_cache( "SELECT id, reg_data, gim_data, login_data, nick, vardas, levelis, pavarde FROM `" . LENTELES_PRIESAGA . "users` WHERE levelis=1 OR levelis=2" );
			
			redirect(
				url("?id," . $url['id'] . ";a," . $url['a'] . ";c," . $url['c']),
				"header",
				[
					'type'		=> 'success',
					'message' 	=> getLangText('admin', 'configuration_updated')
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

			getLangText('admin', 'sitename')	=> [
				"type" 	=> "text", 
				"value" => input( getSettingsValue('Pavadinimas')), 
				"name" 	=> "Pavadinimas"
			],

			getLangText('admin', 'about')		=> [
				"type" 	=> "textarea", 
				"name" 	=> "Apie", 
				"value" => getSettingsValue('Apie')
			],

			getLangText('admin', 'keywords')	=> [
				"type" 	=> "text", 
				"value" => input( getSettingsValue('Keywords') ), 
				"name" 	=> "keywords"
			],
			
			"Friendly url:"				=> [
				"type"		=> "select", 
				"value"		=>	[
					'/'=> '/', 
					';'=> ';', 
					'0'=> getLangText('admin', 'off')
				], 
				"selected"	=> getSettingsValue('F_urls'), 
				"name"		=> "F_urls"
			],

			""                                     => [
				"type" 		=> "submit", 
				"name" 		=> "Konfiguracija", 
				"value" 	=> getLangText('admin', 'save'), 
				'form_line'	=> 'form-not-line',
			]
		];

		$formClass = new Form($settings);
		lentele(getLangText('admin', 'configuration_seo'), $formClass->form());
	} else if($url['c'] == 'extensions') {
		if ( isset( $_POST ) && !empty( $_POST ) && isset( $_POST['saveExtensionsSettings']) && (isset($_POST['extension'])) ) {
			
			$extensionsSettings = $_POST['extension'];
			foreach ($extensionsSettings as $extension => $settings) {
				$status = (isset($settings) && $settings == '1') ? 1 : 0;
				if (isExtensionInstalled($extension)){
					$extensionsRequest = "UPDATE `" . LENTELES_PRIESAGA . "extensions` SET `status`= " . escape($status) . " WHERE `name` = " . escape($extension);
				} else if ($status == 1 ) {
					$extensionsRequest = "INSERT INTO `" . LENTELES_PRIESAGA . "extensions` (`name`, `status`, `options`) VALUES (" . escape($extension) . ", " . escape($status) . ", '')";
				}
				if (isset($extensionsRequest)){
					mysql_query1($extensionsRequest);
				}
			}
			unset($settings);
			
			delete_cache( "SELECT id, reg_data, gim_data, login_data, nick, vardas, levelis, pavarde FROM `" . LENTELES_PRIESAGA . "users` WHERE levelis=1 OR levelis=2" );
			redirect(
				url("?id," . $url['id'] . ";a," . $url['a'] . ";c," . $url['c']),
				"header",
				[
					'type'		=> 'success',
					'message' 	=> getLangText('admin', 'configuration_updated')
				]
			);
		}
		$settings = array();
		$settings["Form"]   = [
			"action" 	=> "", 
			"method" 	=> "post", 
			"enctype" 	=> "", 
			"id" 		=> "", 
			"class" 	=> "", 
			"name" 		=> "reg"
		];

			$extPath = ROOT . 'content/extensions/';
			$extensions = getDirs($extPath);
			
			if(! empty($extensions)) {
				foreach ($extensions as $extension) {
					$fileExt = $extPath . $extension . '/config.php';
					if(file_exists($fileExt)) {
						$settings[$extension] = [ 	
							"type"  	=> "switch",
							"value" 	=> '1',
							"name"  	=> "extension[" . $extension . "]",
							'form_line'	=> 'form-not-line',
							'checked'	=> getExtensionStatus($extension)
						];
					}
				}
			}

			$settings[""] = [ 
				"type" 		=> "submit", 
				"name" 		=> "saveExtensionsSettings", 
				"value" 	=> getLangText('admin', 'save'), 
				'form_line'	=> 'form-not-line',
			];
		$formClass = new Form($settings);
		lentele(getLangText('admin', 'configuration_extensions'), $formClass->form());
	} else if($url['c'] == 'translation') {
		if ( isset( $_POST ) && !empty( $_POST ) && isset( $_POST['saveTranslationOptions']))  {
			$req = array();
			
			$req[] = [
				'val' 		=> (str_replace(',','.', $_POST['ip'])),
				'key' 		=> 'translator',
				'options' 	=> null
			];
			
			$req[] = [
				'val' 		=> $_POST['status'],
				'key' 		=> 'translation_status',
				'options' 	=> null
			];
			
			foreach ($req as $row) {
				setSettingsValue( $row['val'], $row['key'], $row['options'] );
			}
			
			delete_cache( "SELECT id, reg_data, gim_data, login_data, nick, vardas, levelis, pavarde FROM `" . LENTELES_PRIESAGA . "users` WHERE levelis=1 OR levelis=2" );
			
			redirect(
				url("?id," . $url['id'] . ";a," . $url['a'] . ";c," . $url['c']),
				"header",
				[
					'type'		=> 'success',
					'message' 	=> getLangText('admin', 'configuration_updated')
				]
			);
			
		}
		$settings = [];
		$settings["Form"]   = [
			"action" 	=> "", 
			"method" 	=> "post", 
			"enctype" 	=> "", 
			"id" 		=> "", 
			"class" 	=> "", 
			"name" 		=> "reg"
		];
		$settings[getLangText('admin', 'configuration_translations_status')]= [
			"type"  	=> "switch",
			"value" 	=> '1',
			"name"  	=> "status",
			'form_line'	=> 'form-not-line',
			'checked'	=> (input(getSettingsValue('translation_status')) == 1 ? true : false)
		];
		$settings["IP (xx.xxx.xxx.xx)"] =  [
			"type" 	=> "text",
			"value" => input( (getSettingsValue('translator') ) ? getSettingsValue('translator') : '' ),
			"name" 	=> "ip"
		];			
		
		$settings[""] = [ 
			"type" 		=> "submit", 
			"name" 		=> "saveTranslationOptions", 
			"value" 	=> getLangText('admin', 'save'), 
			'form_line'	=> 'form-not-line',
		];
		$formClass = new Form($settings);
		lentele(getLangText('admin', 'configuration_translations'), $formClass->form());
	

		$path = ROOT . 'content/extensions/translation/missingtranslations.json';
		if(file_exists($path)) {
			$missingTranslationsFileContent = file_get_contents($path);
			$missingTranslations = json_decode($missingTranslationsFileContent, true);
		}

		if (!empty($missingTranslations)){
			$table = '<table class="table">';

			foreach($missingTranslations[lang()] as $group => $key){
				$keyCount = count((array)$key) + 1;
				$table .= '<tr>
						<td rowspan = "' . $keyCount . '">' . $group . '</td><td></td><td></td></tr>';
				foreach($key as $keyText => $groupKeyValue){
					$table .= '<tr><td>' .  $keyText . '</td><td>';
					$table .= !empty(getLangText($group, $keyText)) ? getLangText($group, $keyText) : $groupKeyValue;
					$table .= '</td></tr>';
				}

			}
			$table .= '</table>';
		}

		if ( !empty( $table ) ) {
			lentele( getLangText('configuration', 'missingText'), $table );
		}
	}
	

}