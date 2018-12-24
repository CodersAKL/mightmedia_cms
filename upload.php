<?php
ob_start();
session_start();

/**
 * BOOT
 */
include_once 'core/boot.php';

include_once 'core/inc/inc.auth.php';


if (! empty(getSession('level')) && getSession('level') > 0 && isset( $_FILES['userfile']['type'] ) ) {


	//$allowed_types = array('image/jpg', 'image/jpeg');

	//if(in_array($_FILES['userfile']['type'], $allowed_types))

	//{

	function create_th( $files ) {

		global $_POST;
		//$big_img = "tmp/"; //Kur bus saugomi didesni paveiksliukai
		$mini_img = "content/uploads/avatars"; //Kur bus saugomos miniatiuros

		$img_thumb_width = 250; //Mini paveiksliuku dydis

		//Sara�as leid�iamu failu
		$limitedext = array( ".jpg", ".JPG", ".jpeg", ".JPEG", ".png", ".PNG", ".gif", ".GIF" );

		$file_type = $files['type'];
		$file_name = $files['name'];
		$file_size = $files['size'];
		$file_tmp  = $files['tmp_name'];

		//Patikrinam ar failas ikeltas sekmingai
		if ( !is_uploaded_file( $file_tmp ) ) {
			throw new Exception( "File: <b>{$file_tmp}</b> was not uploaded" );
		} else {
			//gaunamm failo galune
			$ext = strrchr( $file_name, '.' );
			$ext = strtolower( $ext );

			//Tikrinam ar tinkamas failas
			if ( !in_array( $ext, $limitedext ) ) {
				throw new Exception( "File: <b>{$file_tmp}</b> is wrong type: <b>{$file_type}</b>" );
			}

			//create a random file name
			//$rand_name = basename($file_tmp,'.tmp');
			$rand_name = md5( $_POST['email'] );
			//the new width variable
			if ( $file_size ) {
				if ( $file_type == "image/pjpeg" || $file_type == "image/jpeg" ) {
					$img = imagecreatefromjpeg( $file_tmp );
				} elseif ( $file_type == "image/x-png" || $file_type == "image/png" ) {
					$img = imagecreatefrompng( $file_tmp );
				} elseif ( $file_type == "image/gif" ) {
					$img = imagecreatefromgif( $file_tmp );
				} elseif ( $file_type == "image/bmp" ) {
					$img = imagecreatefrombmp( $file_tmp );
				}
				//list the width and height and keep the height ratio.
				$width  = imageSX( $img );
				$height = imageSY( $img );

				// Build the thumbnail
				$target_width  = 100;
				$target_height = 100;
				$target_ratio  = $target_width / $target_height;

				$img_ratio = $width / $height;

				//calculate the image ratio
				$imgratio = $width / $height;

				if ( $target_ratio > $img_ratio ) {
					$new_height = $target_height;
					$new_width  = $img_ratio * $target_height;
				} else {
					$new_height = $target_width / $img_ratio;
					$new_width  = $target_width;
				}

				if ( $new_height > $target_height ) {
					$new_height = $target_height;
				}
				if ( $new_width > $target_width ) {
					$new_height = $target_width;
				}


				$new_img = ImageCreateTrueColor( $target_width, $target_height );
				if ( !@imagefilledrectangle( $new_img, 0, 0, $target_width - 1, $target_height - 1, 0 ) ) { // Fill the image black
					throw new Exception( "Wrong GD version" );
				}

				if ( !@imagecopyresampled( $new_img, $img, ( $target_width - $new_width ) / 2, ( $target_height - $new_height ) / 2, 0, 0, $new_width, $new_height, $width, $height ) ) {
					throw new Exception( "Wrong GD version" );
				}

				imagejpeg( $new_img, $mini_img . "/" . $rand_name . '.jpeg', 95 );

				chmod( $mini_img . "/" . $rand_name . '.jpeg', 0777 );
				ImageDestroy( $img );
				ImageDestroy( $new_img );

				//move_uploaded_file($file_tmp, $big_img . "/" . $rand_name . $ext);
				//chmod($big_img . "/" . $rand_name . $ext, 0777);
				return $mini_img . "/" . $rand_name . $ext;
			}
		}
	}

	create_th( $_FILES['userfile'] );
}



//}
// echo $_POST['email'];
?>