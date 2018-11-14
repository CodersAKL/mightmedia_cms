<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: p.dambrauskas $
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision: 366 $
 * @$Date: 2009-12-03 20:46:01 +0200 (Thu, 03 Dec 2009) $
 **/

if ( !defined( "OK" ) || !ar_admin( basename( __file__ ) ) ) {
	redirect( 'location: http://' . $_SERVER["HTTP_HOST"] );
}
// take what we need from extension config
global $limitedext, $mini_img, $big_img, $galleryExtensionDir;
//Puslapiavimui
if ( isset( $url['p'] ) && isnum( $url['p'] ) && $url['p'] > 0 ) {
	$p = (int)$url['p'];
} else {
	$p = 0;
}
$limit = 15;
//
if ( count( $_GET ) < 3 ) {
	$_GET['v'] = 1;
}

if ( empty( $url['s'] ) ) {
	$url['s'] = 0;
}
if ( empty( $url['v'] ) ) {
	$url['v'] = 0;
}

if(BUTTONS_BLOCK) {
	lentele($lang['admin']['galerija'], buttonsMenu($buttons['gallery']));
}

unset( $buttons, $extra, $text );
include_once ( ROOT . "priedai/kategorijos.php" );
kategorija( "galerija", TRUE );
//kategorijos
$kategorijos    = cat( 'galerija', 0 );
$kategorijos[0] = "--";
//foto aktyvavimas
if (isset( $_GET['priimti'] )) {
	$activateQuery = "UPDATE `" . LENTELES_PRIESAGA . "galerija` SET rodoma='TAIP' WHERE `id`=" . escape( $_GET['priimti'] ) . ";";

	if (mysql_query1($activateQuery)) {
		redirect(
			url("?id," . $url['id'] . ";a," . $url['a'] . ";v,8"),
			"header",
			[
				'type'		=> 'success',
				'message' 	=> $lang['admin']['gallery_activated']
			]
		);
	} else {
		notifyMsg(
			[
				'type'		=> 'error',
				'message' 	=> mysqli_error($prisijungimas_prie_mysql)
			]
		);
	}
}
//foto salinimas
if ( ( ( isset( $_POST['action'] ) && $_POST['action'] == $lang['admin']['delete'] && isset( $_POST['edit_new'] ) && $_POST['edit_new'] > 0 ) ) || isset( $url['t'] ) ) {
	if ( isset( $url['t'] ) ) {
		$trinti = (int)$url['t'];
	} elseif ( isset( $_POST['edit_new'] ) ) {
		$trinti = (int)$_POST['edit_new'];
	}

	//delete all images from server
	$fileQuery = "SELECT `file` FROM `" . LENTELES_PRIESAGA . "galerija` WHERE `ID` = " . escape( $trinti ) . " LIMIT 1";
		
	if($sql = mysql_query1($fileQuery)) {
		if ( isset( $sql['file'] ) && !empty( $sql['file'] ) ) {
			@unlink( ROOT . "images/galerija/" . $sql['file'] );
			@unlink( ROOT . "images/galerija/mini/" . $sql['file'] );
			@unlink( ROOT . "images/galerija/originalai/" . $sql['file'] );
		}
	}

	//delete images posts from DB
	$deleteQuery = "DELETE FROM `" . LENTELES_PRIESAGA . "galerija` WHERE id=" . escape( $trinti ) . " LIMIT 1";

	if (mysql_query1($deleteQuery)) {
		$commentsQuery = "DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE pid='puslapiai/galerija' AND kid=" . escape( $trinti );
		
		mysql_query1($commentsQuery);
		
		redirect(
			url("?id," . $url['id'] . ";a," . $url['a'] . ";v,8"),
			"header",
			[
				'type'		=> 'success',
				'message' 	=> $lang['admin']['gallery_deleted']
			]
		);
		
	} else {
		notifyMsg(
			[
				'type'		=> 'error',
				'message' 	=> mysqli_error($prisijungimas_prie_mysql)
			]
		);
	}

//foto redagavimas
} elseif ( ( ( isset( $_POST['edit_new'] ) && isNum( $_POST['edit_new'] ) && $_POST['edit_new'] > 0 ) ) || isset( $url['h'] ) ) {
	if ( isset( $url['h'] ) ) {
		$redaguoti = (int)$url['h'];
	} elseif ( isset( $_POST['edit_new'] ) ) {
		$redaguoti = (int)$_POST['edit_new'];
	}

	$extra = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "galerija` WHERE `id`=" . escape( $redaguoti ) . " LIMIT 1" );

} elseif ( isset( $_POST['action'] ) && $_POST['action'] == $lang['admin']['edit'] ) {

	$apie        = strip_tags( $_POST['Aprasymas'] );
	$pavadinimas = strip_tags( $_POST['Pavadinimas'] );
	$kategorija  = (int)$_POST['cat'];
	$id          = ceil( (int)$_POST['news_id'] );
	$komentaras  = (isset($_POST['kom']) && $_POST['kom'] === '1' ? 'taip' : 'ne');
	$rodoma     = (isset($_POST['rodoma']) && $_POST['rodoma'] === '1' ? 'TAIP' : 'NE');

	$updateQuery = "UPDATE `" . LENTELES_PRIESAGA . "galerija` SET
	`pavadinimas` = " . escape( $pavadinimas ) . ",
	`kom` = " . escape( $komentaras ) . ",
	`rodoma` = " . escape( $rodoma ) . ",
	`categorija` = " . escape( $kategorija ) . ",
	`apie` = " . escape( $apie ) . "
	WHERE `id`=" . escape( $id ) . ";";

	if (mysql_query1($updateQuery)) {
		redirect(
			url("?id," . $url['id'] . ";a," . $url['a']),
			"header",
			[
				'type'		=> 'success',
				'message' 	=> $lang['admin']['gallery_updated']
			]
		);
	} else {
		notifyMsg(
			[
				'type'		=> 'error',
				'message' 	=> mysqli_error($prisijungimas_prie_mysql)
			]
		);
	}

} elseif ( isset( $_POST['action'] ) && $_POST['action'] == $lang['admin']['gallery_add'] ) {
	//image upload
	if (isset($_FILES['failas']['name'])) {
		$file_type = $_FILES['failas']['type'];
		$file_name = $_FILES['failas']['name'];
		$file_size = $_FILES['failas']['size'];
		$file_tmp  = $_FILES['failas']['tmp_name'];
		
		if ($file_size <= MFDYDIS) {
			//Patikrinam ar failas įkeltas sėkmingai
			if (! is_uploaded_file($file_tmp)) {
				notifyMsg(
					[
						'type'		=> 'error',
						'message' 	=> $lang['admin']['gallery_nofile']
					]
				);
			} else {
				//gaunamm failo galunę
				$ext = strrchr($file_name, '.');
				$ext = strtolower($ext);

				//Tikrinam ar tinkamas failas
				if (! in_array($ext, $limitedext)) {
					notifyMsg(
						[
							'type'		=> 'error',
							'message' 	=> $lang['admin']['gallery_notimg']
						]
					);
				}
				
				//Image class helper
				require_once ROOT . $galleryExtensionDir . '/Image-master/vendor/autoload.php';
				
				//create a random file name
				$rand_pre  = basename($file_name, $ext) . '_' . random();
				$rand_name = $rand_pre . time();

				if ($file_size) {
					//save original
					\Gregwar\Image\Image::open($file_tmp)
					->fixOrientation()
					->save($big_img . "originalai/" . $rand_name . $ext);

					chmod($big_img . "originalai/" . $rand_name . $ext, 0755);
					
					//make big thumbnail
					\Gregwar\Image\Image::open($file_tmp)
					->fixOrientation()
					->zoomCrop($conf['fotodyd'], $conf['fotodyd'])
					->save($big_img . $rand_name . $ext);

					chmod($big_img . $rand_name . $ext, 0755);

					//make thumbnail
					\Gregwar\Image\Image::open($file_tmp)
					->fixOrientation()
					->zoomCrop($conf['minidyd'], $conf['minidyd'])
					->save($mini_img . '/' .$rand_name . $ext);

					chmod($mini_img . '/' .$rand_name . $ext, 0755);

					$komentaras  = (isset($_POST['kom']) && $_POST['kom'] === '1' ? 'taip' : 'ne');
					$rodymas     = (isset($_POST['rodoma']) && $_POST['rodoma'] === '1' ? 'TAIP' : 'NE');
					
					$insertQuery = "INSERT INTO `" . LENTELES_PRIESAGA . "galerija` (`pavadinimas`,`file`,`apie`,`autorius`,`data`,`categorija`,`rodoma`,`kom`, `lang`) VALUES (" . escape( $_POST['Pavadinimas'] ) . "," . escape( $rand_name . $ext ) . "," . escape($_POST['Aprasymas']) . "," . escape( $_SESSION[SLAPTAS]['id'] ) . ",'" . time() . "'," . escape( $_POST['cat'] ) . "," . escape( $rodymas ) . "," . escape( $komentaras ) . ", " . escape( lang() ) . ")";

					if (mysql_query1($insertQuery)) {

						redirect(
							url("?id," . $url['id'] . ";a," . $url['a']),
							"header",
							[
								'type'		=> 'success',
								'message' 	=> $lang['admin']['gallery_added']
							]
						);
					} else {
						notifyMsg(
							[
								'type'		=> 'error',
								'message' 	=> mysqli_error($prisijungimas_prie_mysql)
							]
						);
					}

					unset( $_FILES['failas'], $filename, $_POST['action'] );
				}
			}
		} else {
			notifyMsg(
				[
					'type'		=> 'error',
					'message' 	=> $file_name . ' ' . $lang['admin']['download_toobig']
				]
			);
		}

	} else {
		notifyMsg(
			[
				'type'		=> 'error',
				'message' 	=> $lang['admin']['download_badfile']
			]
		);
	}
}

if ( isset( $_GET['v'] ) ) {
	//foto kategoriju saraso rodymas
	if ( $_GET['v'] == 8 ) {
		?>
			<div class="card">
				<div class="header">
					<h2>
						<?php echo $lang['gallery']['photoalbums']; ?>
					</h2>
				</div>
				<div class="body">
					<div class="row">
					<?php foreach ($kategorijos as $id => $kategorija) { ?>
						<div class="col-sm-6 col-md-3">
							<div class="thumbnail">
								<!-- <img src="<?php //echo  ROOT . 'images/galerija/' . $row2['file']; ?>"> -->
								<div class="caption">
									<h3>
										<a href="<?php echo url( '?id,' . $_GET['id'] . ';a,' . $_GET['a'] . ';v,8;k,' . $id ); ?>">
											<?php
												if($kategorija == '--') {
													echo 'Uncategorized';
												} else {
													echo str_replace('-', '',  $kategorija);
												}
											?>
										</a>
									</h3>
								</div>
							</div>
						</div>
					<?php } ?>
					</div>
				</div>
			</div>
		<?php
		$viso = kiek( 'galerija', "WHERE `rodoma` = 'TAIP' AND `lang` = " . escape( lang() ) . " AND `categorija`=" . escape( ( isset( $_GET['k'] ) ? $_GET['k'] : 0 ) ) . "" );
		$photosQuery = "SELECT * FROM  `" . LENTELES_PRIESAGA . "galerija` WHERE `rodoma` = 'TAIP' AND `lang` = " . escape( lang() ) . " AND `categorija`=" . escape( ( isset( $_GET['k'] ) ? $_GET['k'] : 0 ) ) . " ORDER BY `" . $conf['galorder'] . "` " . $conf['galorder_type'] . " LIMIT {$p},{$limit}";
		$sql2 = mysql_query1($photosQuery);
//foto pagal kategorijas rodymas
?>

		<?php if (! empty($sql2)) { ?>
			<div class="card">
				<div class="header">
					<h2>
						<?php echo $lang['admin']['gallery_edit']; ?>
					</h2>
				</div>
				<div class="body">
					<div class="row">
						<?php foreach ( $sql2 as $row2 ) { ?>
							<div class="col-sm-6 col-md-3">
								<div class="thumbnail">
									<img src="<?php echo  ROOT . 'images/galerija/' . $row2['file']; ?>">
									<div class="caption">
										<h3>
											<?php echo $row2['pavadinimas']; ?>
										</h3>
										<p>
											<span>
												<?php echo $lang['admin']['gallery_date']; ?>:  <?php echo date( 'Y-m-d H:i:s ', $row2['data'] ); ?>
											</span>
											<a href="<?php echo url( "?id," . $url['id'] . ";a," . $url['a'] . ";t," . $row2['ID'] ); ?>"
											onclick="return confirm('<?php echo $lang['system']['delete_confirm']; ?>');">
												<img src="<?php echo ROOT . 'images/icons/cross.png'; ?>">
											</a>
											<a href="<?php echo url( "?id," . $url['id'] . ";a," . $url['a'] . ";h," . $row2['ID'] ); ?>" 
											title="<?php echo $lang['admin']['edit']; ?>">
												<img src="<?php echo ROOT . 'images/icons/picture_edit.png'; ?>">
											</a>
										</p>
									</div>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		<?php } else { ?>
		<?php
			notifyMsg(
				[
					'type'		=> 'error',
					'message' 	=> $lang['system']['no_items']
				]
			);
		}

		if ($viso > $limit) {
			lentele($lang['system']['pages'], puslapiai($p, $limit, $viso, 10));
		}

	} elseif ( $_GET['v'] == 1 || isset( $url['h'] ) ) {

		$editOrCreate = (isset( $extra ) ) ? $lang['admin']['edit'] : $lang['admin']['gallery_add'];

		$photoForm  = [
			"Form"				=> [
				"enctype" 	=> "multipart/form-data", 
				"action" 	=> url( "?id," . $url['id'] . ";a," . $url['a'] ), 
				"method" 	=> "post",
				"name" 		=> "action"
			]
		];

		if(! isset($extra)) {
			$photoForm[$lang['admin']['gallery_file']] = [
				"name" => "failas", 
				"type" => 'file'
			];
		}

		$photoForm += [
			$lang['admin']['gallery_title']		=> [
				"type" => "text", 
				"value" => (isset($extra['pavadinimas'])) ? input($extra['pavadinimas']) : '', 
				"name" => "Pavadinimas"
			],

			$lang['gallery']['photoalbum']		=> [
				"type" 		=> "select", 
				"value" 	=> $kategorijos, 
				"name" 		=> "cat", 
				"selected" 	=> (isset($extra['categorija']) ? input($extra['categorija']) : '0')
			],
		
			$lang['admin']['gallery_about'] 	=> [
				"type" 	=> "string", 
				"name" 	=> "Aprasymas", 
				"rows" 	=> "3", 
				"value" => editor('jquery', 'standartinis','Aprasymas', (isset($extra['apie'])) ? input($extra['apie']) : '')
			],

			$lang['admin']['article_comments'] 	=> [
				'type'		=> 'switch',
				'value'		=> 1,
				'name'		=> 'kom',
				'id'		=> 'kom',
				'form_line'	=> 'form-not-line',
				'checked' 	=> (! empty($extra['kom']) && $extra['kom'] == 'taip' ? true : false),
			],
	
			$lang['admin']['article_shown'] 	=> [
				'type'		=> 'switch',
				'value'		=> 1,
				'name'		=> 'rodoma',
				'id'		=> 'rodoma',
				'form_line'	=> 'form-not-line',
				'checked' 	=> (! empty($extra['rodoma']) && $extra['rodoma'] == 'TAIP' ? true : false),
			],

			'' 									=> [
				"type" 		=> "submit", 
				"name" 		=> "action", 
				'form_line'	=> 'form-not-line',
				"value" 	=> $editOrCreate
			]
		];

		if (isset($extra)) {
			$photoForm['news_id'] = [
				"type" => "hidden", 
				"name" => "news_id", 
				"value" => input($extra['ID'])
			];
		}

		$formClass = new Form($photoForm);
		$title = ((isset($extra)) ? $lang['admin']['edit'] : $lang['admin']['gallery_add']);
		$content = '<a name="edit"></a>' . ( ( isset( $extra['file'] ) ) ? '<center><img src="' . ROOT . 'images/galerija/' . input( $extra['file'] ) . '"></center>' : '' );

		lentele($title, $content . $formClass->form());

		//gallery settings
	} elseif ( $_GET['v'] == 6 ) {
		if (! empty($_POST) && isset($_POST['Konfiguracija'])) {
			$q   = [];
			$q[] = "UPDATE `" . LENTELES_PRIESAGA . "nustatymai` SET `val` = " . escape( (int)$_POST['fotodyd'] ) . " WHERE `key` = 'fotodyd' LIMIT 1 ; ";
			$q[] = "UPDATE `" . LENTELES_PRIESAGA . "nustatymai` SET `val` = " . escape( (int)$_POST['minidyd'] ) . " WHERE `key` = 'minidyd' LIMIT 1 ; ";
			$q[] = "UPDATE `" . LENTELES_PRIESAGA . "nustatymai` SET `val` = " . escape( (int)$_POST['fotoperpsl'] ) . " WHERE `key` = 'fotoperpsl' LIMIT 1 ; ";
			$q[] = "UPDATE `" . LENTELES_PRIESAGA . "nustatymai` SET `val` = " . escape( (int)$_POST['galkom'] ) . " WHERE `key` = 'galkom' LIMIT 1 ; ";
			$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape( $_POST['order'] ) . ",'galorder')  ON DUPLICATE KEY UPDATE `val`=" . escape( $_POST['order'] );
			$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape( $_POST['order_type'] ) . ",'galorder_type')  ON DUPLICATE KEY UPDATE `val`=" . escape( $_POST['order_type'] );
			
			foreach ($q as $sql) {
				mysql_query1($sql);
			
			}

			redirect(
				url( '?id,' . $_GET['id'] . ';a,' . $url['a'] . ';v,6'),
				"header",
				[
					'type'		=> 'success',
					'message' 	=> $lang['admin']['configuration_updated']
				]
			);
		}

		$settings = [
			"Form"										=> [
				"action" 	=> "", 
				"method" 	=> "post", 
				"name" 		=> "reg"
			],

			$lang['admin']['gallery_maxwidth']        	=> [
				"type" 	=> "text", 
				"value" => input($conf['fotodyd']), 
				"name" 	=> "fotodyd"
			],

			$lang['admin']['gallery_minwidth']        	=> [
				"type" 	=> "text", 
				"value" => input($conf['minidyd']), 
				"name" 	=> "minidyd"
			],
			
			$lang['admin']['gallery_images_per_page']	=>[
				"type" 	=> "text", 
				"value" => input( (int)$conf['fotoperpsl'] ), 
				"name" 	=> "fotoperpsl"
			],

			$lang['admin']['gallery_order']           	=> [
				"type" 		=> "select", 
				"selected" 	=> (isset($conf['galorder']) ? $conf['galorder'] : ''), 
				"value" 	=> [ 
					'data' 			=> $lang['admin']['gallery_date'], 
					'pavadinimas'	=> $lang['admin']['gallery_title'], 
					'autorius' 		=> $lang['admin']['gallery_author'] 
				], 
				"name"		 => "order"
			],

			$lang['admin']['gallery_order_type']      	=> [
				"type" 		=> "select", 
				"selected" 	=> (isset($conf['galorder_type']) ? $conf['galorder_type'] : ''),
				"value" 	=> [ 
					'DESC'	=> $lang['admin']['gallery_from_biggest'], 
					'ASC' 	=> $lang['admin']['gallery_from_smallest']
				], 
				"name" 		=> "order_type"
			],

			$lang['admin']['gallery_comments'] 	=> [
				'type'		=> 'switch',
				'value'		=> 1,
				'name'		=> 'galkom',
				'id'		=> 'galkom',
				'form_line'	=> 'form-not-line',
				'checked' 	=> (! empty($conf['galkom']) && $conf['galkom'] == '1' ? true : false),
			],

			""											=> [
				"type" 		=> "submit", 
				"name" 		=> "Konfiguracija", 
				'form_line'	=> 'form-not-line',
				"value" 	=> $lang['admin']['save']
			]
		];

		$formClass = new Form($settings);
		lentele($lang['admin']['gallery_conf'], $formClass->form());

	} elseif ( $_GET['v'] == 7 ) {

		$viso = kiek( 'galerija', "WHERE `rodoma` = 'NE' AND `lang` = " . escape( lang() ) . " AND `categorija`=" . escape( ( isset( $_GET['k'] ) ? $_GET['k'] : 0 ) ) . "" );
		$sqlQuery = "SELECT * FROM  `" . LENTELES_PRIESAGA . "galerija` WHERE `rodoma` = 'NE' AND `lang` = " . escape( lang() ) . " ORDER BY `" . $conf['galorder'] . "` " . $conf['galorder_type'] . " LIMIT {$p},{$limit}";
		if ($unpublishedPhotos = mysql_query1($sqlQuery)) {
			?>
			<div class="card">
				<div class="header">
					<h2>
						<?php echo $lang['admin']['gallery_unpublished']; ?>
					</h2>
				</div>
				<div class="body">
					<div class="row">
						<?php foreach ($unpublishedPhotos as $unpublishedPhoto) { ?>
							<div class="col-sm-6 col-md-3">
								<div class="thumbnail">
									<img src="<?php echo  ROOT . 'images/galerija/' . $unpublishedPhoto['file']; ?>">
									<div class="caption">
										<h3>
											<?php echo $unpublishedPhoto['pavadinimas']; ?>
										</h3>
										<p>
											<span>
												<?php echo $lang['admin']['gallery_date']; ?>:  <?php echo date( 'Y-m-d H:i:s ', $unpublishedPhoto['data'] ); ?>
											</span>
											<a href="<?php echo url( "?id," . $url['id'] . ";a," . $url['a'] . ";t," . $unpublishedPhoto['ID'] ); ?>"
											onclick="return confirm('<?php echo $lang['system']['delete_confirm']; ?>');">
												<img src="<?php echo ROOT . 'images/icons/cross.png'; ?>">
											</a>
											<a href="<?php echo url( "?id," . $url['id'] . ";a," . $url['a'] . ";h," . $unpublishedPhoto['ID'] ); ?>" 
											title="<?php echo $lang['admin']['edit']; ?>">
												<img src="<?php echo ROOT . 'images/icons/picture_edit.png'; ?>">
											</a>
											<a href="<?php echo url( "?id," . $url['id'] . ";a," . $url['a'] . ";priimti," . $unpublishedPhoto['ID']); ?>" title="<?php echo $lang['admin']['acept']; ?>">
												<img src="<?php echo ROOT . 'images/icons/tick_circle.png'; ?>">
											</a>
										</p>
									</div>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php
			// if list is bigger than limit, then we show list with pagination
			if ( $viso > $limit ) {
				lentele( $lang['system']['pages'], puslapiai( $p, $limit, $viso, 10 ) );
			}
		} else {
			notifyMsg(
				[
					'type'		=> 'error',
					'message' 	=> $lang['system']['no_items']
				]
			);
		}

	}

}

unset( $sql, $extra, $row );