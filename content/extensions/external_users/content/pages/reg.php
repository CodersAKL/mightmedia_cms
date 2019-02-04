<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author$
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision$
 * @$Date$
 **/

function registracijos_forma()
{
	global $vardas, $pass, $pass2, $email, $lang, $conf;
	
	include_once config('class', 'dir') . 'class.form.php';

	$bla   = new Form();
	$forma = array(
		"Form"                               		=> array( "action" => url( "?id," . $conf['pages'][basename( __file__ )]['id'] ), "method" => "post", "name" => "reg", "extra"=> "onSubmit=\"return checkMail('reg','email')\"" ),
		getLangText('reg', 'username') . ":"        => array( "type" => "text", "value" => ( isset( $vardas ) ? input( $vardas ) : "" ), "name" => "nick", "class" => "input", "extra"=> "title='" . getLangText('reg',  'username') . "'" ),
		getLangText('reg', 'password') . ":"        => array( "type" => "password", "value" => input( $pass ), "name" => "pass", "class" => "input" ),
		getLangText('reg', 'confirmpassword') . ":" => array( "type" => "password", "value" => input( $pass2 ), "name" => "pass2", "class" => "input" ),
		getLangText('reg', 'email') . ":"           => array( "type" => "text", "value" => ( isset( $email ) ? input( $email ) : "" ), "name" => "email", "extra"=> "title=\"" . getLangText('reg',  'email') . "\"" ),
		kodas()                              		=> array( "type"=> "text", "name"=> "kode", "class"=> "chapter" ),
		" \r\r"                              		=> array( "type" => "hidden", "name" => "action", "value" => "registracija" ),
		" \r"                                		=> array( "type" => "submit", "name" => "Submit_link", "value" => getLangText('reg', 'register'), "class" => "submit" )
	);

	return $bla->render( $forma );
}

if (! empty(getSession('username'))) {
	header( "Location: " . url( "?id,{$conf['pages'][$conf['pirminis'].'.php']['id']}" ) );
}
$error = '';
$sekme = FALSE;
if ( isset( $_POST['action'] ) && $_POST['action'] == 'registracija' ) {
	$vardas = input( $_POST['nick'] );
	$kode   = strip_tags( strtoupper( $_POST['kode'] ) );
	$pass   = $_POST['pass'];
	$pass2  = $_POST['pass2'];
	$email  = strip_tags( $_POST['email'] );
	$error  = "";
	$einfo  = count( mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "users` WHERE nick=" . escape( $vardas ) . "" ) );
	if ( $einfo != 0 ) {
		$error .= getLangText('reg', 'takenusername') . "<br />";
	}
	if ( strlen( $vardas ) < 4 ) {
		$error .= " " .getLangText('reg', 'usrtooshort') . "<br />";
	}
	if ( strlen( $vardas ) > 15 ) {
		$error .= " " . getLangText('reg', 'usrtoolong') . "<br />";
	}
	//šitaip neveikia kai kuriuose hostinguose
	//if (preg_match('/^[\p{L}0-9]+$/u', $vardas) == 0) {
	if ( preg_match( '/^[\p{L}0-9a-zA-Z]+$/u', $vardas ) == 0 ) {
		$error .= " " . getLangText('reg',  'only_letters_numbers') . "<br />";
	}
	if ( $pass != $pass2 ) {
		$error .= " " . getLangText('reg',  'badpass') . "<br />";
	}
	if ( strlen( $pass ) < 6 ) {
		$error .= " " . getLangText('reg',  'passtooshort') . "<br />";
	}
	if ( strlen( $pass ) > 15 ) {
		$error .= " " . getLangText('reg',  'passtoolong') . "<br />";
	}
	if ( !check_email( $email ) ) {
		$error .= " " . getLangText('reg',  'bademail') . "<br />";
	}

	if ( check_email( $email ) ) {
		$minfo = count( mysql_query1( "SELECT * FROM " . LENTELES_PRIESAGA . "users WHERE email=" . escape( $email ) . "" ) );
		if ( $minfo != 0 ) {
			$error .= " " . getLangText('reg',  'emailregistered') . "<br />";
		}
	}
	if (getSession('code') != $kode) {
		$error .= " " . getLangText('reg',  'wrongcode') . "<br>";
	}
	if ( strlen( $error ) == 0 ) {
		if ( mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "users` ( `id` , `nick` , `levelis` , `pass` , `email` , `reg_data` , `login_data` )
					VALUES (
					NULL , " . escape( $vardas ) . ", '2', " . escape( koduoju( $pass ) ) . " , " . escape( $email ) . ", '" . time() . "' , '" . time() . "'
					)" )
		) {
			msg( getLangText('system', 'done'), getLangText('reg', 'registered') );
			$sekme = TRUE;
		} else {
			klaida( getLangText('system', 'error'), "" . getLangText('system',  'systemerror') . "" . mysqli_error($prisijungimas_prie_mysql) );
		}
	} else {
		klaida( getLangText('reg', 'wronginfo'), $error );
	}
}
if ( $sekme == FALSE ) {
	$title = getLangText('reg', 'registration');
	$text  = registracijos_forma();
	lentele( $title, $text );
}
unset( $title, $text );

?>
<script language="JavaScript1.2">
	function checkMail(form, email) {
		var x = document.forms[form].email.value;
		var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if (filter.test(x)) {
			return true;
		}
		else {
			alert(<?= getLangText('reg', 'bademail'); ?>);
			return false;
		}
	}
</script>
