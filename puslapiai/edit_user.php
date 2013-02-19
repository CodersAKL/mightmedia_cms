<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author$
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision$
 * @$Date$
 * */

if ( !defined( "OK" ) || !isset( $_SESSION[SLAPTAS]['username'] ) ) {
	header( "Location: " . url( "?id,{$conf['puslapiai'][$conf['pirminis'] . '.php']['id']}" ) );
	exit;
}
include_once ( "priedai/class.php" );
$mid = isset( $url['m'] ) ? $url['m'] : 0;
$id  = $url['id'];
// ############ Apdorojomi duomenys kurie buvo pateikti is tam tikros redagavimo lenteles #####################
// ######### Slaptazodzio keitimas #############
if ( isset( $_POST['old_pass'] ) && count( $_POST['old_pass'] ) > 0 && count( $_POST['new_pass'] ) > 0 && count( $_POST['new_pass2'] ) > 0 ) {
	$old_pass = koduoju( $_POST['old_pass'] );
	$user     = mysql_query1( "SELECT `nick` FROM `" . LENTELES_PRIESAGA . "users` WHERE `nick`=" . escape( $_SESSION[SLAPTAS]['username'] ) . " AND pass=" . escape( $old_pass ) . " LIMIT 1" );
	//ar teisingas senas slaptzodis
	if ( isset( $user['nick'] ) ) {
		$new_pass  = koduoju( $_POST['new_pass'] );
		$new_pass2 = koduoju( $_POST['new_pass2'] );
		//ar sutampa ivesti nauji slaptazodziai
		if ( $new_pass == $new_pass2 ) {
			mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "users` SET pass=" . escape( $new_pass ) . " WHERE nick=" . escape( $_SESSION[SLAPTAS]['username'] ) );
			msg( $lang['system']['done'], $lang['user']['edit_updated'] );
		} else {
			klaida( $lang['system']['error'], $lang['user']['edit_badconfirm'] );
		}
	} else {
		klaida( $lang['system']['error'], $lang['user']['edit_badpass'] );
	}
	unset( $old_pass, $user, $new_pass, $new_pass2 );
}
// ################# kontaktu keitimas ######################
if ( isset( $_POST['action'] ) && $_POST['action'] == 'contacts_change' ) {
	if ( !empty( $_POST['email'] ) && check_email( $_POST['email'] ) ) {
		$icq   = $_POST['icq'];
		$msn   = $_POST['msn'];
		$skype = $_POST['skype'];
		$yahoo = $_POST['yahoo'];
		$aim   = $_POST['aim'];
		$url   = parse_url( $_POST['url'] );
		$url   = ( !empty( $url['scheme'] ) ? $url['scheme'] : 'http' ) . '://' . ( empty( $url['host'] ) ? $url['path'] : $url['host'] . $url['path'] ); //Paruošiam ir sutvarkom įvestą adresą. Išimam visokius argumentus iš nuorodos.
		$email = $_POST['email'];
		$ep    = mysql_query1( "SELECT `email` FROM `" . LENTELES_PRIESAGA . "users` WHERE email=" . escape( $email ) . " LIMIT 1" );
		$sql   = mysql_query1( "SELECT `email` FROM `" . LENTELES_PRIESAGA . "users` WHERE nick=" . escape( $_SESSION[SLAPTAS]['username'] ) . " LIMIT 1" );
		if ( !isset( $ep['email'] ) || ( isset( $ep['email'] ) && $ep['email'] == $sql['email'] ) ) {
			if ( file_exists( 'images/avatars/' . md5( $sql['email'] ) . '.jpeg' ) ) {
				rename( 'images/avatars/' . md5( $sql['email'] ) . '.jpeg', 'images/avatars/' . md5( $email ) . '.jpeg' );
			}

			mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "users` SET icq=" . escape( $icq ) . ", msn=" . escape( $msn ) . ", skype=" . escape( $skype ) . ", yahoo=" . escape( $yahoo ) . ", aim=" . escape( $aim ) . ", url=" . escape( $url ) . ", email=" . escape( $email ) . " WHERE nick=" . escape( $_SESSION[SLAPTAS]['username'] ) . "" );
			msg( $lang['system']['done'], $lang['user']['edit_updated'] );
		} else {
			klaida( $lang['system']['error'], $lang['reg']['emailregistered'] );
		}
		unset( $icq, $msn, $skype, $yahoo, $aim, $url, $email );
	} else {
		klaida( $lang['system']['error'], $lang['reg']['bademail'] );
	}
}
// ################ Salies bei miesto nustatymai #############
if ( isset( $_POST['action'] ) && $_POST['action'] == 'country_change' ) {
	$miestas = $_POST['miestas'];
	$salis   = $_POST['salis'];
	mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "users` SET salis=" . escape( $salis ) . ", miestas=" . escape( $miestas ) . " WHERE nick=" . escape( $_SESSION[SLAPTAS]['username'] ) . " LIMIT 1" );
	msg( $lang['system']['done'], $lang['user']['edit_updated'] );
}


// ################ Pagrindiniu nustatymu keitimas ###################
if ( isset( $_POST['action'] ) && $_POST['action'] == 'default_change' ) {
	$vardas  = $_POST['vardas'];
	$pavarde = $_POST['pavarde'];
	$gimimas = date( 'Y-m-d', strtotime( $_POST['gimimas'] ) );
	$parasas = $_POST['parasas'];
	mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "users` SET vardas=" . escape( $vardas ) . ", pavarde=" . escape( $pavarde ) . ", parasas=" . escape( $parasas ) . ", gim_data=" . escape( $gimimas ) . " WHERE nick=" . escape( $_SESSION[SLAPTAS]['username'] ) . "" );
	msg( $lang['system']['done'], $lang['user']['edit_updated'] );
}
// ################ Siulomi punktai redagavimui MENIU ##########################
$text = "
 <table width=100% border=0>
	<tr>
		<td>
			<div class=\"blokas\"><center><a href='" . url( "?id," . $id . ";m,1" ) . "'><img src=\"images/user/user-auth.png\" alt=\"slaptazodis\" />{$lang['user']['edit_pass']}</a></center></div>
			<div class=\"blokas\"><center><a href='" . url( "?id," . $id . ";m,2" ) . "'><img src=\"images/user/user-contact.png\" alt=\"kontaktai\" />{$lang['user']['edit_contacts']}</a></center></div>
			<div class=\"blokas\"><center><a href='" . url( "?id," . $id . ";m,3" ) . "'><img src=\"images/user/user-place.png\" alt=\"vietove\" />{$lang['user']['edit_locality']}</a></center></div>
<div class=\"blokas\"><center><a href='" . url( "?id," . $id . ";m,4" ) . "'><img src=\"images/user/user-avatar.png\" alt=\"avataras\" />{$lang['user']['edit_avatar']}</a></center></div>
			<div class=\"blokas\"><center><a href='" . url( "?id," . $id . ";m,5" ) . "'><img src=\"images/user/user-settings.png\" alt=\"nustatymai\" />{$lang['user']['edit_mainsettings']}</a></center></div>
			
		</td>
	</tr>
</table>
";
lentele( $lang['user']['edit_settings'], $text );
// ######################### Jei pasirinktas vienas is pasiulytu MENIU ####################

// Pakeisti slaptazodi
if ( $mid == 1 ) {
	$form_array = array( "Form" => array( "action" => "", "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "change_password" ), "{$lang['user']['edit_pass']}:" => array( "type" => "password", "value" => "", "name" => "old_pass" ), "{$lang['user']['edit_newpass']}:" => array( "type" => "password", "value" => "", "name" => "new_pass" ), "{$lang['user']['edit_confirmnewpass']}:" => array( "type" => "password", "value" => "", "name" => "new_pass2" ), "" => array( "type" => "hidden", "name" => "action", "value" => "pass_change" ), "" => array( "type" => "submit", "value" => "{$lang['user']['edit_update']}" ) );
	$form       = new forma();
	lentele( $lang['user']['edit_pass'], $form->form( $form_array ) );
} // Pakeisti kontaktinius duomenis
elseif ( $mid == 2 ) {
	$info = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "users` WHERE `nick`=" . escape( $_SESSION[SLAPTAS]['username'] ) . "LIMIT 1" );

	$form_array = array(
		"Form"                           => array( "action" => url( "?id," . $conf['puslapiai'][basename( __file__ )]['id'] . ";m," . $_GET['m'] ), "method" => "post", "enctype" => "", "id" => "", "extra" => "onSubmit=\"return checkMail('change_contacts','email')\"", "name" => "change_contacts" ),
		"ICQ:"                           => array( "type" => "text", "value" => input( $info['icq'] ), "name" => "icq", "class" => "input" ),
		"MSN:"                           => array( "type" => "text", "value" => input( $info['msn'] ), "name" => "msn", "class" => "input" ),
		"Skype:"                         => array( "type" => "text", "value" => input( $info['skype'] ), "name" => "skype", "class" => "input" ),
		"Yahoo:"                         => array( "type" => "text", "value" => input( $info['yahoo'] ), "name" => "yahoo", "class" => "input" ),
		"AIM:"                           => array( "type" => "text", "value" => input( $info['aim'] ), "name" => "aim", "class" => "input" ),
		"{$lang['user']['edit_web']}:"   => array( "type" => "text", "value" => input( $info['url'] ), "name" => "url", "class" => "input" ),
		"{$lang['user']['edit_email']}:" => array( "type" => "text", "value" => input( $info['email'] ), "name" => "email", "class" => "input" ),
		"\r\r\r"                         => array( "type" => "hidden", "name" => "action", "value" => "contacts_change" ),
		""                               => array( "type" => "submit", "value" => $lang['user']['edit_update'] )
	);
	$form       = new forma();
	lentele( $lang['user']['edit_contacts'], $form->form( $form_array ) );
}
// Pakeisti sali, miesta
elseif ( $mid == 3 ) {
	$info = mysql_query1( "SELECT `salis`, `miestas` FROM `" . LENTELES_PRIESAGA . "users` WHERE `nick`=" . escape( $_SESSION[SLAPTAS]['username'] ) . " LIMIT 1" );

	$sql   = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "salis`" );
	$salis = array();
	foreach ( $sql as $row ) {
		$salis[$row['iso']] = $row['printable_name'];
	}

	$form_array = array( "Form" => array( "action" => url( "?id," . $conf['puslapiai'][basename( __file__ )]['id'] . ";m," . $_GET['m'] ), "method" => "post", "name" => "change_country" ), "{$lang['user']['edit_country']}:" => array( "type" => "select", "value" => $salis, "name" => "salis", "selected" => input( $info['salis'] ) ), "{$lang['user']['edit_city']}:" => array( "type" => "text", "value" => input( $info['miestas'] ), "name" => "miestas" ), " \r " => array( "type" => "hidden", "name" => "action", "value" => "country_change" ), "" => array( "type" => "submit", "value" => $lang['user']['edit_update'] ) );

	$form = new forma();
	lentele( $lang['user']['edit_locality'], $form->form( $form_array ) );
}

// Avataro keitimas
elseif ( $mid == 4 ) {
	$sql = mysql_query1( "SELECT `email` FROM `" . LENTELES_PRIESAGA . "users` WHERE `nick`=" . escape( $_SESSION[SLAPTAS]['username'] ) . " LIMIT 1" );
	if ( isset( $_GET['a'] ) && $_GET['a'] == 1 ) {
		@unlink( 'images/avatars/' . md5( $sql['email'] ) . '.jpeg' );
	}
	$avataras = avatar( $sql['email'] );
	$name     = md5( $sql['email'] );
	$gravatar = url( "?id,{$_GET['id']};m,{$_GET['m']};a,1" );
	$avatar   = <<<HTML
        <script type="text/javascript" src="javascript/jquery/jquery.uploader.js"></script>
        <script  type="text/javascript">
           $(document).ready(function(){
               var button = $('#button1'), interval;
               new AjaxUpload(button,{
                     action: 'upload.php',
                     name: 'userfile',
                     data: {
                        email : '{$sql['email']}'
                     },
                     onSubmit : function(file, ext){
                         $('#gravatar').hide();
                         if (! (ext && /^(jpg|jpeg|png|gif|bmp)$/.test(ext))){
                           alert('{$lang['admin']['download_badfile']}');
                           return false;
                         } else {
                            button.html('<img src="images/icons/Loading.gif" />{$lang['user']['edit_uploading']}...');
                            this.disable();
                         }
                     },
                     onComplete: function(file, response){
                        button.html('<img src="images/icons/picture__plus.png" alt="" class="middle"/>{$lang['user']['edit_upload']}');
                        this.enable();
                        $('#example1 .files').replaceWith('<div class="files"><img id="ikeltas_avataras" src="images/avatars/{$name}.jpeg?'+file+'" alt="" /></div>');
                     }
               });
           });
        </script>
        <div align="center">
         	<span id="example1" class="example">
		        <a class="btn" onclick="return false">
		           <span id="button1">
		              <img src="images/icons/picture__plus.png" alt="" class="middle"/> {$lang['user']['edit_upload']}
		            </span>
		        </a>
                <a class="btn" href="{$gravatar}">
                   <span>
                      <img src="images/icons/picture__plus.png" alt="" class="middle"/> {$lang['user']['edit_gravatar']}
                   </span>
                </a>
             	<p>{$lang['user']['edit_avatar']}:</p>
         		<div class="files">{$avataras}</div>
	    	</span>
        </div>
HTML;
	if ( isset( $_GET['a'] ) && $_GET['a'] == 1 ) {
		$avatar .= "<div align='center' id='gravatar'>{$lang['user']['edit_avatarcontent']} <b>" . input( $sql['email'] ) . "</b>.</div>";
	}

	lentele( $lang['user']['edit_avatar'], $avatar );
}
// Pagrindiniai nustatymai
elseif ( $mid == 5 ) {
	$sql = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "users` WHERE nick=" . escape( $_SESSION[SLAPTAS]['username'] ) . " LIMIT 1" );
	echo '<script src="javascript/jquery/jquery.maskedinput-1.2.2.js" type="text/javascript"></script>
          <script type="text/javascript">jQuery(function($){
             $("#date").mask("9999-99-99");
          });
          </script>';
	$form_array = array(
		"Form"                                 => array( "action" => url( "?id," . $conf['puslapiai'][basename( __file__ )]['id'] . ";m," . $_GET['m'] ), "method" => "post", "name" => "parasas" ),
		"{$lang['user']['edit_name']}:"        => array( "type" => "text", "value" => input( $sql['vardas'] ), "name" => "vardas", "class" => "input" ),
		"{$lang['user']['edit_secondname']}:"  => array( "type" => "text", "value" => input( $sql['pavarde'] ), "name" => "pavarde", "class" => "input" ),
		"{$lang['user']['edit_dateOfbirth']}:" => array( "type" => "text", "value" => input( $sql['gim_data'] ), "extra" => "title='0000-00-00' size='10' maxlength='10' style='width:inherit'", "class" => "input", "name" => "gimimas", "id" => "date" ),
		$lang['user']['edit_signature']        => array( "type" => "textarea", "class" => "input", "value" => input( $sql['parasas'] ), "name" => "parasas", "id" => "parasas" ),
		" "                                    => array( "type" => "string", "value" => bbk( 'parasas' ) ),
		" \r \n"                               => array( "type" => "hidden", "name" => "action", "value" => "default_change" ),
		""                                     => array( "type" => "submit", "value" => $lang['user']['edit_update'] )
	);

	$form = new forma();
	lentele( $lang['user']['edit_mainsettings'], $form->form( $form_array ) );
	unset( $data, $viso, $day, $month, $year );
}

unset( $text );
?>
<script type="text/javascript">
	function checkMail(form, email) {
		var x = document.forms[form].email.value;
		var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if (filter.test(x)) {
			return true;
		}
		else {
			alert('<?php echo $lang['user']['edit_bademail'];?>');
			return false;
		}
	}
</script>