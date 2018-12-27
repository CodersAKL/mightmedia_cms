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
	lentele(getLangText('admin', 'galerija'), buttonsMenu($buttons['gallery']));
}

unset( $buttons, $extra, $text );
include_once config('functions', 'dir') . 'functions.categories.php';
category( "galerija", TRUE );
//categories
$categories    = cat( 'galerija', 0 );
$categories[0] = "--";
//foto aktyvavimas
if (isset( $_GET['priimti'] )) {
	$activateQuery = "UPDATE `" . LENTELES_PRIESAGA . "galerija` SET rodoma='TAIP' WHERE `id`=" . escape( $_GET['priimti'] ) . ";";

	if (mysql_query1($activateQuery)) {
		redirect(
			url("?id," . $url['id'] . ";a," . $url['a'] . ";v,8"),
			"header",
			[
				'type'		=> 'success',
				'message' 	=> getLangText('admin', 'gallery_activated')
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
//foto salinimas
if ( ( ( isset( $_POST['action'] ) && $_POST['action'] == getLangText('admin', 'delete') && isset( $_POST['edit_new'] ) && $_POST['edit_new'] > 0 ) ) || isset( $url['t'] ) ) {
	if ( isset( $url['t'] ) ) {
		$trinti = (int)$url['t'];
	} elseif ( isset( $_POST['edit_new'] ) ) {
		$trinti = (int)$_POST['edit_new'];
	}

	//delete all images from server
	$fileQuery = "SELECT `file` FROM `" . LENTELES_PRIESAGA . "galerija` WHERE `ID` = " . escape( $trinti ) . " LIMIT 1";
		
	if($sql = mysql_query1($fileQuery)) {
		if ( isset( $sql['file'] ) && !empty( $sql['file'] ) ) {
			unlink( ROOT . "content/uploads/gallery/" . $sql['file'] );
			unlink( ROOT . "content/uploads/gallery/thumbs/" . $sql['file'] );
			unlink( ROOT . "content/uploads/gallery/originals/" . $sql['file'] );
		}
	}

	//delete images posts from DB
	$deleteQuery = "DELETE FROM `" . LENTELES_PRIESAGA . "galerija` WHERE id=" . escape( $trinti ) . " LIMIT 1";

	if (mysql_query1($deleteQuery)) {
		$commentsQuery = "DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE pid='content/pages/galerija' AND kid=" . escape( $trinti );
		
		mysql_query1($commentsQuery);
		
		redirect(
			url("?id," . $url['id'] . ";a," . $url['a'] . ";v,8"),
			"header",
			[
				'type'		=> 'success',
				'message' 	=> getLangText('admin', 'gallery_deleted')
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

//foto redagavimas
} elseif ( ( ( isset( $_POST['edit_new'] ) && isNum( $_POST['edit_new'] ) && $_POST['edit_new'] > 0 ) ) || isset( $url['h'] ) ) {
	if ( isset( $url['h'] ) ) {
		$redaguoti = (int)$url['h'];
	} elseif ( isset( $_POST['edit_new'] ) ) {
		$redaguoti = (int)$_POST['edit_new'];
	}

	$extra = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "galerija` WHERE `id`=" . escape( $redaguoti ) . " LIMIT 1" );

} elseif ( isset( $_POST['action'] ) && $_POST['action'] == getLangText('admin', 'edit') ) {

	$apie        = strip_tags( $_POST['Aprasymas'] );
	$pavadinimas = strip_tags( $_POST['Pavadinimas'] );
	$category  = (int)$_POST['cat'];
	$id          = ceil( (int)$_POST['news_id'] );
	$komentaras  = (isset($_POST['kom']) && $_POST['kom'] === '1' ? 'taip' : 'ne');
	$rodoma     = (isset($_POST['rodoma']) && $_POST['rodoma'] === '1' ? 'TAIP' : 'NE');

	$updateQuery = "UPDATE `" . LENTELES_PRIESAGA . "galerija` SET
	`pavadinimas` = " . escape( $pavadinimas ) . ",
	`kom` = " . escape( $komentaras ) . ",
	`rodoma` = " . escape( $rodoma ) . ",
	`categorija` = " . escape( $category ) . ",
	`apie` = " . escape( $apie ) . "
	WHERE `id`=" . escape( $id ) . ";";

	if (mysql_query1($updateQuery)) {
		redirect(
			url("?id," . $url['id'] . ";a," . $url['a']),
			"header",
			[
				'type'		=> 'success',
				'message' 	=> getLangText('admin', 'gallery_updated')
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

} elseif ( isset( $_POST['action'] ) && $_POST['action'] == getLangText('admin', 'gallery_add') ) {
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
						'message' 	=> getLangText('admin', 'gallery_nofile')
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
							'message' 	=> getLangText('admin', 'gallery_notimg')
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
					->save($big_img . "originals/" . $rand_name . $ext);

					chmod($big_img . "originals/" . $rand_name . $ext, 0755);
					
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
					
					$insertQuery = "INSERT INTO `" . LENTELES_PRIESAGA . "galerija` (`pavadinimas`,`file`,`apie`,`autorius`,`data`,`categorija`,`rodoma`,`kom`, `lang`) VALUES (" . escape( $_POST['Pavadinimas'] ) . "," . escape( $rand_name . $ext ) . "," . escape($_POST['Aprasymas']) . "," . escape(getSession('id')) . ",'" . time() . "'," . escape( $_POST['cat'] ) . "," . escape( $rodymas ) . "," . escape( $komentaras ) . ", " . escape( lang() ) . ")";

					if (mysql_query1($insertQuery)) {

						redirect(
							url("?id," . $url['id'] . ";a," . $url['a']),
							"header",
							[
								'type'		=> 'success',
								'message' 	=> getLangText('admin', 'gallery_added')
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

					unset( $_FILES['failas'], $filename, $_POST['action'] );
				}
			}
		} else {
			notifyMsg(
				[
					'type'		=> 'error',
					'message' 	=> $file_name . ' ' . getLangText('admin', 'download_toobig')
				]
			);
		}

	} else {
		notifyMsg(
			[
				'type'		=> 'error',
				'message' 	=> getLangText('admin', 'download_badfile')
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
						<?php echo getLangText('gallery', 'photoalbums'); ?>
					</h2>
				</div>
				<div class="body">
					<div class="row">
					<?php foreach ($categories as $id => $category) { ?>
						<div class="col-sm-6 col-md-3">
							<div class="thumbnail">
								<!-- <img src="<?php //echo  ROOT . 'content/uploads/gallery/' . $row2['file']; ?>"> -->
								<div class="caption">
									<h3>
										<a href="<?php echo url( '?id,' . $_GET['id'] . ';a,' . $_GET['a'] . ';v,8;k,' . $id ); ?>">
											<?php
												if($category == '--') {
													echo 'Uncategorized';
												} else {
													echo str_replace('-', '',  $category);
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
						<?php echo getLangText('admin', 'gallery_edit'); ?>
					</h2>
				</div>
				<div class="body">
					<div class="row">
						<?php foreach ( $sql2 as $row2 ) { ?>
							<div class="col-sm-6 col-md-3">
								<div class="thumbnail">
									<img src="<?php echo  ROOT . 'content/uploads/gallery/' . $row2['file']; ?>">
									<div class="caption">
										<h3>
											<?php echo $row2['pavadinimas']; ?>
										</h3>
										<p>
											<span>
												<?php echo getLangText('admin', 'gallery_date'); ?>:  <?php echo date( 'Y-m-d H:i:s ', $row2['data'] ); ?>
											</span>
											<a href="<?php echo url( "?id," . $url['id'] . ";a," . $url['a'] . ";t," . $row2['ID'] ); ?>"
											onclick="return confirm('<?php echo getLangText('system', 'delete_confirm'); ?>');">
												<img src="<?php echo ROOT . 'core/assets/images/icons/cross.png'; ?>">
											</a>
											<a href="<?php echo url( "?id," . $url['id'] . ";a," . $url['a'] . ";h," . $row2['ID'] ); ?>" 
											title="<?php echo getLangText('admin', 'edit'); ?>">
												<img src="<?php echo ROOT . 'core/assets/images/icons/picture_edit.png'; ?>">
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
					'message' 	=> getLangText('system', 'no_items')
				]
			);
		}

		if ($viso > $limit) {
			lentele(getLangText('system', 'pages'), pages($p, $limit, $viso, 10));
		}

	} elseif ( $_GET['v'] == 1 || isset( $url['h'] ) ) {

		$editOrCreate = (isset( $extra ) ) ? getLangText('admin', 'edit') : getLangText('admin', 'gallery_add');

		$photoForm  = [
			"Form"				=> [
				"enctype" 	=> "multipart/form-data", 
				"action" 	=> url( "?id," . $url['id'] . ";a," . $url['a'] ), 
				"method" 	=> "post",
				"name" 		=> "action"
			]
		];

		if(! isset($extra)) {
			$photoForm[getLangText('admin', 'gallery_file')] = [
				"name" => "failas", 
				"type" => 'file'
			];
		}

		$photoForm += [
			getLangText('admin', 'gallery_title')		=> [
				"type" => "text", 
				"value" => (isset($extra['pavadinimas'])) ? input($extra['pavadinimas']) : '', 
				"name" => "Pavadinimas"
			],

			getLangText('gallery', 'photoalbum')		=> [
				"type" 		=> "select", 
				"value" 	=> $categories, 
				"name" 		=> "cat", 
				"selected" 	=> (isset($extra['categorija']) ? input($extra['categorija']) : '0')
			],
		
			getLangText('admin', 'gallery_about') 	=> [
				"type" 	=> "string", 
				"name" 	=> "Aprasymas", 
				"rows" 	=> "3", 
				"value" => editor('jquery', 'standartinis','Aprasymas', (isset($extra['apie'])) ? input($extra['apie']) : '')
			],

			getLangText('admin', 'article_comments') 	=> [
				'type'		=> 'switch',
				'value'		=> 1,
				'name'		=> 'kom',
				'id'		=> 'kom',
				'form_line'	=> 'form-not-line',
				'checked' 	=> (! empty($extra['kom']) && $extra['kom'] == 'taip' ? true : false),
			],
	
			getLangText('admin', 'article_shown') 	=> [
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
		$title = ((isset($extra)) ? getLangText('admin', 'edit') : getLangText('admin', 'gallery_add'));
		$content = '<a name="edit"></a>' . ( ( isset( $extra['file'] ) ) ? '<center><img src="' . ROOT . 'content/uploads/gallery/' . input( $extra['file'] ) . '"></center>' : '' );

		lentele($title, $content . $formClass->form());

		//gallery settings
	} elseif ( $_GET['v'] == 6 ) {
		if (! empty($_POST) && isset($_POST['Konfiguracija'])) {
			
			$req = array();
			$req[] = [
				'val' 		=> (int)$_POST['fotodyd'],
				'key' 		=> 'fotodyd',
				'options' 	=> ['LIMIT'=>1]
			];
			$req[] = [
				'val' 		=> (int)$_POST['minidyd'],
				'key' 		=> 'minidyd',
				'options' 	=> ['LIMIT'=>1]
			];
			$req[] = [
				'val' 		=> (int)$_POST['fotoperpsl'],
				'key' 		=> 'fotoperpsl',
				'options' 	=> ['LIMIT'=>1]
			];
			$req[] = [
				'val' 		=> (int)$_POST['galkom'],
				'key' 		=> 'galkom',
				'options' 	=> ['LIMIT'=>1]
			];
			$req[] = [
				'val' 		=> $_POST['order'],
				'key' 		=> 'galorder',
				'options' 	=> null
			];
			$req[] = [
				'val' 		=> $_POST['order_type'],
				'key' 		=> 'galorder_type',
				'options' 	=> null
			];
			
			foreach ($req as $row) {
				setSettingsValue( $row['val'], $row['key'], $row['options'] );
			}


			redirect(
				url( '?id,' . $_GET['id'] . ';a,' . $url['a'] . ';v,6'),
				"header",
				[
					'type'		=> 'success',
					'message' 	=> getLangText('admin', 'configuration_updated')
				]
			);
		}

		$settings = [
			"Form"										=> [
				"action" 	=> "", 
				"method" 	=> "post", 
				"name" 		=> "reg"
			],

			getLangText('admin', 'gallery_maxwidth')        	=> [
				"type" 	=> "text", 
				"value" => input($conf['fotodyd']), 
				"name" 	=> "fotodyd"
			],

			getLangText('admin', 'gallery_minwidth')        	=> [
				"type" 	=> "text", 
				"value" => input($conf['minidyd']), 
				"name" 	=> "minidyd"
			],
			
			getLangText('admin', 'gallery_images_per_page')	=>[
				"type" 	=> "text", 
				"value" => input( (int)$conf['fotoperpsl'] ), 
				"name" 	=> "fotoperpsl"
			],

			getLangText('admin', 'gallery_order')           	=> [
				"type" 		=> "select", 
				"selected" 	=> (isset($conf['galorder']) ? $conf['galorder'] : ''), 
				"value" 	=> [ 
					'data' 			=> getLangText('admin', 'gallery_date'), 
					'pavadinimas'	=> getLangText('admin', 'gallery_title'), 
					'autorius' 		=> getLangText('admin', 'gallery_author') 
				], 
				"name"		 => "order"
			],

			getLangText('admin', 'gallery_order_type')      	=> [
				"type" 		=> "select", 
				"selected" 	=> (isset($conf['galorder_type']) ? $conf['galorder_type'] : ''),
				"value" 	=> [ 
					'DESC'	=> getLangText('admin', 'gallery_from_biggest'), 
					'ASC' 	=> getLangText('admin', 'gallery_from_smallest')
				], 
				"name" 		=> "order_type"
			],

			getLangText('admin', 'gallery_comments') 	=> [
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
				"value" 	=> getLangText('admin', 'save')
			]
		];

		$formClass = new Form($settings);
		lentele(getLangText('admin', 'gallery_conf'), $formClass->form());

	} elseif ( $_GET['v'] == 7 ) {

		$viso = kiek( 'galerija', "WHERE `rodoma` = 'NE' AND `lang` = " . escape( lang() ) . " AND `categorija`=" . escape( ( isset( $_GET['k'] ) ? $_GET['k'] : 0 ) ) . "" );
		$sqlQuery = "SELECT * FROM  `" . LENTELES_PRIESAGA . "galerija` WHERE `rodoma` = 'NE' AND `lang` = " . escape( lang() ) . " ORDER BY `" . $conf['galorder'] . "` " . $conf['galorder_type'] . " LIMIT {$p},{$limit}";
		if ($unpublishedPhotos = mysql_query1($sqlQuery)) {
			?>
			<div class="card">
				<div class="header">
					<h2>
						<?php echo getLangText('admin', 'gallery_unpublished'); ?>
					</h2>
				</div>
				<div class="body">
					<div class="row">
						<?php foreach ($unpublishedPhotos as $unpublishedPhoto) { ?>
							<div class="col-sm-6 col-md-3">
								<div class="thumbnail">
									<img src="<?php echo  ROOT . 'content/uploads/gallery/' . $unpublishedPhoto['file']; ?>">
									<div class="caption">
										<h3>
											<?php echo $unpublishedPhoto['pavadinimas']; ?>
										</h3>
										<p>
											<span>
												<?php echo getLangText('admin', 'gallery_date'); ?>:  <?php echo date( 'Y-m-d H:i:s ', $unpublishedPhoto['data'] ); ?>
											</span>
											<a href="<?php echo url( "?id," . $url['id'] . ";a," . $url['a'] . ";t," . $unpublishedPhoto['ID'] ); ?>"
											onclick="return confirm('<?php echo getLangText('system', 'delete_confirm'); ?>');">
												<img src="<?php echo ROOT . 'core/assets/images/icons/cross.png'; ?>">
											</a>
											<a href="<?php echo url( "?id," . $url['id'] . ";a," . $url['a'] . ";h," . $unpublishedPhoto['ID'] ); ?>" 
											title="<?php echo getLangText('admin', 'edit'); ?>">
												<img src="<?php echo ROOT . 'core/assets/images/icons/picture_edit.png'; ?>">
											</a>
											<a href="<?php echo url( "?id," . $url['id'] . ";a," . $url['a'] . ";priimti," . $unpublishedPhoto['ID']); ?>" title="<?php echo getLangText('admin', 'acept'); ?>">
												<img src="<?php echo ROOT . 'core/assets/images/icons/tick_circle.png'; ?>">
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
				lentele( getLangText('system', 'pages'), pages( $p, $limit, $viso, 10 ) );
			}
		} else {
			notifyMsg(
				[
					'type'		=> 'error',
					'message' 	=> getLangText('system', 'no_items')
				]
			);
		}

	}

}

unset( $sql, $extra, $row );