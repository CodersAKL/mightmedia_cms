<?php
if ( !defined( "LEVEL" ) || LEVEL > 1 || !defined( "OK" ) ) {
	redirect( 'location: http://' . $_SERVER["HTTP_HOST"] );
}

if(! empty($_POST) && ! empty($_POST['action']) && function_exists($_POST['action'])) {
    $data = $_POST;
    unset($data['action']);
    echo call_user_func($_POST['action'], $data);
} else {
    die('0');
}