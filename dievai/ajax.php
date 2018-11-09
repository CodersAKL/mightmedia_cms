<?php
if ( !defined( "LEVEL" ) || LEVEL > 1 || !defined( "OK" ) ) {
	redirect( 'location: http://' . $_SERVER["HTTP_HOST"] );
}

if(! empty($_POST) && ! empty($_POST['action'])) {
    if(! empty($_POST['action_functions'])){
        $funcFile = 'functions/functions.' . input($_POST['action_functions']) . '.php';
        if(is_file($funcFile)) {
            unset($_POST['action_functions']);
            
            include $funcFile;
        }
    }

    if(function_exists($_POST['action'])) {
        $data = $_POST;
        unset($data['action']);
        $func = call_user_func($_POST['action'], $data);
        if(is_string($func)) {
            echo $func;
        } else {
            echo json_encode($func, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }
        
    } else {
        die('0');
    }
} else {
    die('0');
}