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

function registracijos_forma() {
	global $vardas, $pass, $pass2, $email, $lang;
	include_once ("priedai/class.php");

	$bla = new forma();
	$forma = array(
		 "Form" => array("action" => "", "method" => "post", "name" => "reg","extra"=>"onSubmit=\"return checkMail('reg','email')\"", "class" => "input"),
		 "{$lang['reg']['username']}:" => array("type" => "text", "value" => (isset($vardas) ? input($vardas) : ""), "name" => "nick", "class" => "input", "extra"=>"title='{$lang['reg']['username']}'"),
		 "{$lang['reg']['password']}:" => array("type" => "password", "value" => input($pass), "name" => "pass", "class" => "input"),
		 "{$lang['reg']['confirmpassword']}:" => array("type" => "password", "value" => input($pass2), "name" => "pass2", "class" => "input"),
		 "{$lang['reg']['email']}:" => array("type" => "text", "value" => (isset($email) ? input($email) : ""), "name" => "email","extra"=>"title=\"{$lang['reg']['email']}\""),
		 kodas()=>array("type"=>"text","name"=>"kode", "class"=>"chapter"),
		 " \r\r" => array("type" => "hidden", "name" => "action", "value" => "registracija"),
		 " \r" => array("type" => "submit", "name" => "Submit_link", "value" => "{$lang['reg']['register']}", "class" => "submit")
	);

	return $bla->form($forma);
}

// Tikrinamas E-Mail Adresas
function check_email($email) {
	if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
		return false;
	}
	$email_array = explode("@", $email);
	$local_array = explode(".", $email_array[0]);
	for ($i = 0; $i < sizeof($local_array); $i++) {
		if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
			return false;
		}
	}
	if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
		$domain_array = explode(".", $email_array[1]);
		if (sizeof($domain_array) < 2) {
			return false;
		}
		for ($i = 0; $i < sizeof($domain_array); $i++) {
			if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
				return false;
			}
		}
	}
	return true;
}


if (isset($_SESSION['username'])) {
	header("Location: ?");
}
$error = '';
$sekme = false;
if (isset($_POST['action']) && $_POST['action'] == 'registracija') {
//if (!preg_match("[^[a-zA-Z0-9_-]+$]", $user)) && !preg_match('/[^A-Za-z0-9]/', $_POST['nick'])
//$vardas = htmlentities($_POST['nick'], ENT_QUOTES, 'UTF-8');
	$vardas = input($_POST['nick']);
	$kode = strip_tags(strtoupper($_POST['kode']));
	$pass = $_POST['pass'];
	$pass2 = $_POST['pass2'];
	$email = strip_tags($_POST['email']);
	$error = "";
	$einfo = count(mysql_query1("SELECT * FROM " . LENTELES_PRIESAGA . "users WHERE nick=" . escape($vardas) . ""));
	if ($einfo != 0) {
		$error .= "<img src='images/icons/cross.png' alt='x' align='absmiddle' /> {$lang['reg']['takenusername']}<br />";
	}
	if (strlen($vardas) < 4) {
		$error .= "<img src='images/icons/cross.png' alt='x' align='absmiddle' /> {$lang['reg']['usrtooshort']}<br />";
	}
	if (strlen($vardas) > 15) {
		$error .= "<img src='images/icons/cross.png' alt='x' align='absmiddle' /> {$lang['reg']['usrtoolong']}<br />";
	}
	if (preg_match('/^[\p{L}0-9]+$/u', $vardas) == 0) {
		$error .= "<img src='images/icons/cross.png' alt='x' align='absmiddle' /> {$lang['reg']['only_letters_numbers']}<br />";
	}
	if ($pass != $pass2) {
		$error .= "<img src='images/icons/cross.png' alt='x' align='absmiddle' /> {$lang['reg']['badpass']}<br />";
	}
	if (strlen($pass) < 6) {
		$error .= "<img src='images/icons/cross.png' alt='x' align='absmiddle' /> {$lang['reg']['passtooshort']}<br />";
	}
	if (strlen($pass) > 15) {
		$error .= "<img src='images/icons/cross.png' alt='x' align='absmiddle' /> {$lang['reg']['passtoolong']}<br />";
	}
	if (!check_email($email)) {
		$error .= "<img src='images/icons/cross.png' alt='x' align='absmiddle' /> {$lang['reg']['bademail']}<br />";
	}

	if (check_email($email)) {
		$minfo = count(mysql_query1("SELECT * FROM " . LENTELES_PRIESAGA . "users WHERE email=" . escape($email) . ""));
		if ($minfo != 0) {
			$error .= "<img src='images/icons/cross.png' alt='x' align='absmiddle' /> {$lang['reg']['emailregistered']}<br />";
		}
	}
	if ($_SESSION['code'] != $kode) {
		$error .= "<img src='images/icons/cross.png' alt='x' align='absmiddle' /> {$lang['reg']['wrongcode']}<br>";
	}
	if (strlen($error) == 0) {
		if (mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "users` ( `id` , `nick` , `levelis` , `pass` , `email` , `reg_data` , `login_data` )
					VALUES (
					NULL , " . escape($vardas) . ", '2', " . escape(koduoju($pass)) . " , " . escape($email) . ", '" . time() . "' , '" . time() . "'
					)")) {
			msg("{$lang['system']['done']}", "{$lang['reg']['registered']}.");
			$sekme = true;
		} else {
			klaida("{$lang['system']['error']}", "{$lang['system']['systemerror']}" . mysql_error());
		}
	} else {
		klaida($lang['reg']['wronginfo'], $error);
	}
}
if ($sekme == false) {
	$title = "{$lang['reg']['registration']}";
	$text = registracijos_forma();
	lentele($title, $text);
}

//unset($email, $vardas, $error, $einfo, $pass, $pass, $reg_info);


?>
<script language="JavaScript1.2">
	function checkMail(form,email) {
		var x = document.forms[form].email.value;
		var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if (filter.test(x)) { return true; }
		else { alert('<?php echo $lang['reg']['bademail']; ?>'); return false; }
	}
</script>
