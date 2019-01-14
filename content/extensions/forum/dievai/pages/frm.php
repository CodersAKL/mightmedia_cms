<?php

if ( !defined( "OK" ) || !ar_admin( basename( __file__ ) ) ) {
	redirect( 'location: http://' . $_SERVER["HTTP_HOST"] );
}

if(BUTTONS_BLOCK) {
	lentele( getLangText('admin', 'frm'), buttonsMenu(buttons('forum')));
}

if ( !isset( $_GET['f'] ) && !isset( $_POST['f_forumas'] ) ) {
	$_GET['f'] = 3;
	$url['f']  = 3;
}

//teisiu sarasas
$lygiai = array_keys( $conf['level'] );
foreach ( $lygiai as $key ) {
	$teises[$key] = $conf['level'][$key]['pavadinimas'];
}
$teises[0] = getLangText('admin', 'for_guests');

//Kategorijos trynimas (gali but problemu)
if (isset($_GET['d'])) {
	$f_id  = (int)$_GET['d'];

	$selectQuery = "SELECT `id` FROM `" . LENTELES_PRIESAGA . "d_temos`  WHERE `fid`=" . escape($f_id) . " AND `lang` = " . escape(lang());
	
	if ($catsIds = mysql_query1($selectQuery)) {
		foreach ($catsIds as $catsId) {
			$subCatQuery = "SELECT `id` FROM `" . LENTELES_PRIESAGA . "d_straipsniai` WHERE `tid`=" . escape($catsId['id']) . " AND `lang` = " . escape(lang());
			
			if ($subCatsIds = mysql_query1($subCatQuery)) {
				foreach ($subCatsIds as $subCatsId) {
					$messageDeleteQuery = "DELETE FROM `" . LENTELES_PRIESAGA . "d_zinute`  WHERE `sid`=" . escape($subCatsId['id']);
					mysql_query1($messageDeleteQuery);
				}
			}

			$subCatDeleteQuery = "DELETE FROM `" . LENTELES_PRIESAGA . "d_straipsniai`  WHERE `tid`=" . escape($catsId['id']);
			mysql_query1($subCatDeleteQuery);
		}
	}

	$categoryDelQuery = "DELETE FROM `" . LENTELES_PRIESAGA . "d_forumai`  WHERE `id`=" . escape($f_id);
	$topicDeleteQuery = "DELETE FROM `" . LENTELES_PRIESAGA . "d_temos`  WHERE `fid`=" . escape($f_id);

	if (mysql_query1($categoryDelQuery) && mysql_query1($topicDeleteQuery)) {
		redirect(
			url("?id," . $url['id'] . ";a," . $url['a']),
			"header",
			[
				'type'		=> 'success',
				'message' 	=> getLangText('admin', 'categorydeleted')
			]
		);

	} else {
		notifyMsg(
			[
				'type'		=> 'error',
				'message' 	=> input(mysqli_error($prisijungimas_prie_mysql))
			]
		);
	}
}
//subkategorijos trynimas
if ( isset( $_GET['t'] ) ) {
	$f_id = (int)$_GET['t'];
	//sita atlieka (istrina subkategorija)
	$deleteCatQuery = "DELETE FROM `" . LENTELES_PRIESAGA . "d_temos` WHERE `id`=" . escape($f_id);
	if(mysql_query1($deleteCatQuery)) {
		//turetu istrint zinutes
		$selectMessagesQuery = "SELECT `id` FROM `" . LENTELES_PRIESAGA . "d_straipsniai` WHERE `tid`=" . escape($f_id);
		if ($messages = mysql_query1($selectMessagesQuery)) {
			foreach ($messages as $message) {
				$deleteMessage = "DELETE FROM `" . LENTELES_PRIESAGA . "d_zinute` WHERE `sid`=" . escape($message['id']);
				mysql_query1($deleteMessage);
			}
		}
		//istina temas is kategorijos
		$topicDeleteQuery = "DELETE FROM `" . LENTELES_PRIESAGA . "d_straipsniai` WHERE `lang` = " . escape(lang()) . " AND `tid`='" . $f_id . "'";
		mysql_query1($topicDeleteQuery);

		redirect(
			url("?id," . $url['id'] . ";a," . $url['a']),
			"header",
			[
				'type'		=> 'success',
				'message' 	=> getLangText('admin', 'forum_deletesub')
			]
		);
	} else {
		notifyMsg(
			[
				'type'		=> 'error',
				'message' 	=> input(mysqli_error($prisijungimas_prie_mysql))
			]
		);
	}

	unset($f_id);
}

//sub category create/edit
if (isset($url['r']) && isset($url['f']) || isset($url['f']) && (int)$url['f'] == 3 && ! isset($url['r'])) {
	//Subkategorijos kūrimas
	if (isset($_POST['action'])) {
		$category	= (int)$_POST['category'];
		$name      	= input($_POST['name']);
		$about		= input($_POST['about']);
		$rules		= serialize((isset($_POST['rules']) ? $_POST['rules'] : 0));

		// $f_forumas      = (int)$_POST['category'];
		
		// $edit_tema      = input( $_POST['temos_pav'] );
		// $edit_aprasymas = input( $_POST['temos_apr'] );
		// creation
		if ($_POST['action'] == getLangText('admin', 'forum_createsub') ) {
			$insertQuery = "INSERT INTO `" . LENTELES_PRIESAGA . "d_temos` (`fid`, `pav`, `aprasymas`, `lang`, `teises`) VALUES (" . escape($category) . ", " . escape($name) . ", " . escape($about) . ", " . escape(lang()) . ", " . escape($rules) . ")";
	
			if (mysql_query1($insertQuery)) {
				redirect(
					url("?id," . $url['id'] . ";a," . $url['a']),
					"header",
					[
						'type'		=> 'success',
						'message' 	=> getLangText('admin', 'forum_createdsub')
					]
				);
	
			} else {
				notifyMsg(
					[
						'type'		=> 'error',
						'message' 	=> input(mysqli_error($prisijungimas_prie_mysql))
					]
				);
			}
		}
		//sub category edit
		if ($_POST['action'] == getLangText('admin', 'forum_editsub') ) {
			$id         = (int)$_POST['sub_cat'];

			$updateQuery = "UPDATE `" . LENTELES_PRIESAGA . "d_temos` SET 
			`pav`=" . escape($name) . ", 
			`aprasymas`=" . escape($about) . ", 
			`teises`=" . escape($rules) . " 
			WHERE `fid`='" . $category . "' AND `id`=" . escape($id);

			if (mysql_query1($updateQuery)) {
				redirect(
					url("?id," . $url['id'] . ";a," . $url['a']),
					"header",
					[
						'type'		=> 'success',
						'message' 	=> getLangText('admin', 'forum_updatedsub')
					]
				);
			} else {
				notifyMsg(
					[
						'type'		=> 'error',
						'message' 	=> input(mysqli_error($prisijungimas_prie_mysql))
					]
				);
			}
		
		}
	}

	if(isset($url['r']) && isset($url['f'])) {
		$f_id       	= (int)$url['f'];
		$f_temos_id 	= (int)$url['r'];
		
		$categoryQuery 	= "SELECT `pav` FROM `" . LENTELES_PRIESAGA . "d_forumai` WHERE `id`='" . $f_id . "' limit 1";
		$category     	= mysql_query1($categoryQuery);
		$f_forumas  	= $category['pav'];
		
		$subCategoryQuery = "SELECT * FROM `" . LENTELES_PRIESAGA . "d_temos` WHERE `id`='" . $f_temos_id . "' limit 1";
		$extra = mysql_query1($subCategoryQuery);
		var_dump($extra);
	} else {
		$sql = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "d_forumai` WHERE `lang` = " . escape(lang()) . " ORDER BY `place` ASC" );
		foreach ($sql as $row) {
			$categories[$row['id']] = $row['pav'];
		}
	}

	$editOrCreate 	= (isset($extra) ? getLangText('admin', 'forum_editsub') : getLangText('admin', 'forum_createsub'));

	$forumForm  = [
		"Form"	=> [
			"action" 	=> '', 
			"method" 	=> "post", 
			"name" 		=> "port"
		],
	]; 

	if(isset($extra)) {
		$forumForm += [
			getLangText('admin', 'forum_category') => [
				"type" 	=> "text",
				"extra"	=> 'readonly', 
				"value" => $f_forumas
			],
			'category' => [
				'type' 	=> 'hidden', 
				'name'	=> 'category',
				'value'	=> $f_id
			],
			'sub_cat' => [
				'type' 	=> 'hidden', 
				'name'	=> 'sub_cat',
				'value'	=> $extra['id']
			],
		];
	} else {
		$forumForm[getLangText('admin', 'forum_category')] = [
			"type" 		=> "select", 
			"value" 	=> $categories, 
			"name"		=> "category"
		];
	}

	$forumForm  += [
		getLangText('admin', 'forum_subcategory')=> [
			"type"	=> "text", 
			"value"	=> (! empty($extra['pav']) ? $extra['pav'] : ''), 
			"name"	=> "name"
		],

		getLangText('admin', 'forum_subabout') => [
			"type" 	=> "text", 
			"value" => (! empty($extra['aprasymas']) ? $extra['aprasymas'] : ''), 
			"name" 	=> "about"
		], 

		getLangText('system', 'showfor') => [
			"type" 		=> "select", 
			"extra" 	=> "multiple", 
			"value" 	=> $teises, 
			"name" 		=> "rules[]", 
			"id" 		=> "punktai", 
			"selected" 	=> (! empty($extra['teises']) ? unserialize($extra['teises']) : '')
		],

		"" => [
			"type" 		=> "submit", 
			"name" 		=> "action", 
			'form_line'	=> 'form-not-line',
			"value" 	=> $editOrCreate
		]
	];
	
	$formClass = new Form($forumForm);
	lentele($editOrCreate, $formClass->render());
}

if ( isset( $url['f'] ) ) {
	// Category creation
	if ((int)$url['f'] == 1) {
		// Paspaustas kazkoks mygtukas
		if (isset($_POST['action']) && $_POST['action'] == getLangText('system', 'createcategory')) {
			$sqlInsert = "INSERT INTO `" . LENTELES_PRIESAGA . "d_forumai` (`pav`, `lang`) VALUES (" . escape(input($_POST['f_pav'])) . ", " . escape(lang()) . ")";

			if (mysql_query1($sqlInsert)) {
				redirect(
					url("?id," . $url['id'] . ";a," . $url['a']),
					"header",
					[
						'type'		=> 'success',
						'message' 	=> getLangText('system', 'categorycreated')
					]
				);
			} else {
				notifyMsg(
					[
						'type'		=> 'error',
						'message' 	=> input(mysqli_error($prisijungimas_prie_mysql))
					]
				);
			}
		}

		$categoryForm = [
			"Form" 								=> [
				"action" 	=> '', 
				"method" 	=> "post", 
				"name" 		=> "port"
			],

			getLangText('admin', 'forum_category') 	=> [
				"type" => "text", 
				"name" => "f_pav"
			],

			"" 									=> [
				"type" 		=> "submit", 
				"name" 		=> "action", 
				'form_line'	=> 'form-not-line',
				"value" 	=> getLangText('system', 'createcategory')
			]
		];
		
		$formClass = new Form($categoryForm);
		lentele(getLangText('system', 'createcategory'), $formClass->render());
	}
	//Categories list/edit
	if ( (int)$url['f'] == 2 && !isset( $_GET['r'] ) ) {
		//Kategorijos redagavimas
		if ( isset( $_POST['keisti'] ) && $_POST['keisti'] == getLangText('admin', 'edit') ) {
			$f_id           = (int)$_POST['category'];
			$f_pav_keitimas = input( $_POST['name'] );
			$updateQuery 	= "UPDATE `" . LENTELES_PRIESAGA . "d_forumai` SET `pav`=" . escape($f_pav_keitimas) . " WHERE `id`=" . escape($f_id);

			if (mysql_query1($updateQuery)) {
				redirect(
					url("?id," . $url['id'] . ";a," . $url['a']),
					"header",
					[
						'type'		=> 'success',
						'message' 	=> getLangText('system', 'categoryupdated')
					]
				);
			} else {
				notifyMsg(
					[
						'type'		=> 'error',
						'message' 	=> input(mysqli_error($prisijungimas_prie_mysql))
					]
				);
			}
		}

		$catsQuery = "SELECT * FROM `" . LENTELES_PRIESAGA . "d_forumai` WHERE `lang` = " . escape(lang()) . " ORDER BY `place` ASC";
		if ($categories = mysql_query1($catsQuery)) {
			foreach ($categories as $category) {
				$catsSelect[$category['id']] = $category['pav'];
				$cats[$category['id']] = [
					'title'	=> $category['pav'],
					'edit'		=> url('?id,' . $url['id'] . ';a,' . $url['a'] . ';f,' . $category['id']),
					'delete'	=> url('?id,' . $url['id'] . ';a,' . $url['a'] . ';d,' . $category['id']),
				];
			}

			$categoryForm = [
				"Form" 								=> [
					"action" => '', 
					"method" => "post", 
				], 

				getLangText('admin', 'forum_category') 	=> [
					"type" 	=> "select", 
					"name" 	=> "category", 
					"value"	=> $catsSelect
				], 

				getLangText('admin', 'forum_cangeto') 	=> [
					"type" => "text", 
					"name" => "name"
				],

				"" 									=> [
					"type" 		=> "submit", 
					"name" 		=> "keisti", 
					'form_line'	=> 'form-not-line',
					"value"	 	=> getLangText('admin', 'edit')
				]
			];
			
			$formClass = new Form($categoryForm);
			lentele(getLangText('system', 'editcategory'), $formClass->render());
			
			$li      	= ! empty($cats) ? buldForumMenu($cats) : '';
			$pageMenu 	= '<div class="dd nestable-with-handle">' . $li . '</div>';

			lentele(getLangText('admin', 'forum_order'), $pageMenu);
			?>
			<script type="text/javascript">
				//nestable
				$('.dd').nestable({
					maxDepth: 1
				});
				$('.dd').on('change', function () {
					var $this = $(this);
					var serializedData = JSON.stringify($($this).nestable('serialize')),
						data = {
							action: 'forumCatsOrder',
							order: serializedData
						};

					$.post("<?php echo url( "?id,999;a,ajax;" ); ?>", data, function(response) {
						if(response) {
							showNotification('success', response);
						}
					});
				});
			</script>
			<?php
		} else {
			notifyMsg(
				[
					'type'		=> 'error',
					'message' 	=> getLangText('system', 'nocategories')
				]
			);
		}
	}
	
	//Sub category edit form to select parent cat
	if ( (int)$url['f'] == 4 ) {
		//Sub cat edit by selected parent category
		if (isset($_POST['subedit']) && $_POST['subedit'] == getLangText('admin', 'forum_select') ) {

			$f_id = (int)$_POST['f_forumas'];
			$catSelectQuery = "SELECT * FROM `" . LENTELES_PRIESAGA . "d_temos` WHERE `lang` = " . escape(lang()) . " AND `fid`='" . $f_id . "' ORDER by place";

			if ($categories = mysql_query1($catSelectQuery)) {
				$tema = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "d_forumai` where `id`=" . escape( (int)$_POST['f_forumas'] ) . "  ORDER BY `place` ASC limit 1" );

				foreach ($categories as $category) {
					$cats[$category['id']] = [
						'title'		=> $category['pav'],
						'edit'		=> url('?id,' . $url['id'] . ';a,' . $url['a'] . ';r,' . $category['id'] . ';f,' . $tema['id']),
						'delete'	=> url('?id,' . $url['id'] . ';a,' . $url['a'] . ';t,' . $category['id']),
					];
				}

				$li      	= ! empty($cats) ? buldForumMenu($cats) : '';
				$content 	= '<div class="dd nestable-with-handle">' . $li . '</div>';

				lentele(getLangText('admin', 'forum_editsub'), $content);
				?>
				<script type="text/javascript">
					//nestable
					$('.dd').nestable({
						maxDepth: 1
					});
					$('.dd').on('change', function () {
						var $this = $(this);
						var serializedData = JSON.stringify($($this).nestable('serialize')),
							data = {
								action: 'forumSubCatsOrder',
								order: serializedData
							};

						$.post("<?php echo url( "?id,999;a,ajax;" ); ?>", data, function(response) {
							if(response) {
								showNotification('success', response);
							}
						});
					});
				</script>
				<?php
			}
		}

		$catSelectQuery = "SELECT * FROM `" . LENTELES_PRIESAGA . "d_forumai` WHERE `lang` = " . escape(lang()) . " ORDER BY `place` ASC";

		if ($categories = mysql_query1($catSelectQuery)) {
			foreach ($categories as $cat) {
				$forums[$cat['id']] = $cat['pav'];
			}

			$parentCatForm = [
				"Form" 								=> [
					"action" => '', 
					"method" => "post", 
				], 

				getLangText('admin', 'forum_subwhere')	=> [
					"type" 	=> "select", 
					"name" 	=> "f_forumas", 
					"value"	=> $forums
				],

				""									=> [
					"type" 		=> "submit", 
					"name" 		=> "subedit",
					'form_line'	=> 'form-not-line', 
					"value" 	=> getLangText('admin', 'forum_select')
				]
			];

			$formClass = new Form($parentCatForm);
			lentele(getLangText('admin', 'forum_editsub'), $formClass->render());
		} else {
			klaida( getLangText('system', 'warning'), getLangText('system', 'nocategories') );
		}
	}
}	