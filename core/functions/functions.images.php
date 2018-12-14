<?php

/**
 * Patikrina ar tai tikrai paveiksliukas
 *
 * @param string $img
 *
 * @return string
 */
if(! function_exists('isImage1')) {
	function isImage1( $img ) {

		//$img = $matches[1].str_replace(array("?","&","="),"",$matches[3]).$matches[4];
		//$img = $matches[1].$matches[3].$matches[4];
		if ( @getimagesize( $img ) ) {
			$res = "<img src='" . $img . "' style='border:0px;'>";
		} else {
			$res = "[img]" . $img . "[/img]";
		}

		return $res;
	}
}

if(! function_exists('pic')) {
	function pic( $off_site, $size = FALSE, $url = 'images/nuorodu/', $sub = 'url' ) {

		$pic_name = md5( $off_site );
		$pic_name = $url . $sub . "_" . $pic_name . ".png";
		if ( !file_exists( $pic_name ) || ( time() - filemtime( $pic_name ) ) > 86400 ) { //9 valandos senumo
			clearstatcache();
			@unlink( $pic_name );
			if ( pic1( $off_site, $size, $url, $sub ) ) {
				return $pic_name;
			} else {
				clearstatcache();

				return $url . "noimage.jpg";
			}
		} else {
			clearstatcache();

			return $pic_name;
		}
	}
}

if(! function_exists('pic1')) {
	function pic1( $off_site, $size = FALSE, $url = 'images/nuorodu/', $sub = 'url' ) {

		$fp  = @fopen( $off_site, 'rb' );
		$buf = '';
		if ( $fp ) {
			stream_set_blocking( $fp, TRUE );
			stream_set_timeout( $fp, 2 );
			while ( !feof( $fp ) ) {
				$buf .= fgets( $fp, 4096 );
			}

			$data = $buf;

			//set new height
			$src = @imagecreatefromstring( $data );
			imagealphablending( $src, TRUE );

			if ( empty( $src ) ) {
				return FALSE;
			}
			if ( $size ) {
				$width        = @imagesx( $src );
				$height       = @imagesy( $src );
				$aspect_ratio = $width / $height;

				//start resizing
				if ( $width <= $size ) {
					$new_w = $width;
					$new_h = $height;
				} else {
					$new_w = $size;
					$new_h = @abs( $new_w / $aspect_ratio );
				}

				$img = @imagecreatetruecolor( $new_w, $new_h );

				//output image
				@imagecopyresampled( $img, $src, 0, 0, 0, 0, $new_w, $new_h, $width, $height );
			}
			$file = $url . $sub . "_" . md5( $off_site ) . ".png";

			// determine image type and send it to the browser
			imagesavealpha( $src, TRUE );
			@imagepng( ( !$img ? $src : $img ), $file );
			@imagedestroy( $img );
			unset( $buf );
			sleep( 2 );
		}
	}
}
