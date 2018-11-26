<?php

if (! empty($_POST) && isset($_POST['acc_create'])) {
	$user                       = htmlspecialchars( $_POST['user'] );
	$pass                       = ( !empty( $_POST['pass'] ) ? koduoju( $_POST['pass'] ) : "" );
	$pass2                      = ( !empty( $_POST['pass2'] ) ? koduoju( $_POST['pass2'] ) : "" );
    $email                      = htmlspecialchars( $_POST['email'] );
    
	$_SESSION['admin']['email'] = $email;
	if ($pass != $pass2) {
        $error = [
            'type'      => 'error',
            'message'   => $lang['user']['edit_badconfirm']
        ];
	} else {
		if (! empty($user) && ! empty($pass) && ! empty($pass2) && !empty($email) ) {
			$mysql_con4 = mysqli_connect( $_SESSION['mysql']['host'], $_SESSION['mysql']['user'], $_SESSION['mysql']['pass'] );
			mysqli_query( $mysql_con4, "SET NAMES utf8mb4" );
			mysqli_select_db( $mysql_con4, $_SESSION['mysql']['db'] );
			mysqli_query( $mysql_con4, "UPDATE `" . $_SESSION['mysql']['prefix'] . "users` SET `nick`='" . $user . "', `pass`='" . $pass . "', `email`='" . $email . "', `reg_data`='" . time() . "', `ip`='" . $_SERVER['REMOTE_ADDR'] . "' WHERE `nick`='Admin'" ) or die( mysqli_error($mysql_con4) );
			
			mysqli_query( $mysql_con4, "INSERT INTO `" . $_SESSION['mysql']['prefix'] . "nustatymai` (`key`, `val`) VALUES ('Pastas', '" . $email . "');" ) or die( mysqli_error($mysql_con4) );
			mysqli_query( $mysql_con4, "INSERT INTO `" . $_SESSION['mysql']['prefix'] . "nustatymai` (`key`, `val`) VALUES ('kalba', '" . $_SESSION['language'] . "');" ) or die( mysqli_error($mysql_con4) );
			header( "Location: index.php?step=5" );
		} else {
            $error = [
                'type'      => 'error',
                'message'   => $lang['admin']['news_required']
            ];
		}
	}
}