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

//paziurim ar vartotojas neprisijunges, jei prisijunges tai jam nera cia ka veikti
if (isset($_SESSION['username'])) {
	header("Location: ?");
}

//nuskaitom saugos koda is nuorodos - jeigu toks egzistuoja patikrinam ar tinka ir vydom slaptazodzio atstatyma
if (isset($url['c']) && !empty($url['c']) && strlen($url['c']) == 11) {
	$kode = input(strip_tags($url['c']));
	$sql = mysql_query("SELECT `nick`,`email`,`slaptas` FROM `" . LENTELES_PRIESAGA . "users` WHERE slaptas=" . escape($kode) . " LIMIT 1");
	if (count($sql) < 1) {
		$error = "{$lang['pass']['wrongcode']}";
	} else {
		//$sql = mysql_fetch_assoc($sql);
		$slaptas = random_name();
		$nick = $sql['nick'];
		if (mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "users` SET `slaptas`= NULL, pass='" . koduoju($slaptas) . "' WHERE `nick`=" . escape($nick) . " LIMIT 1") or die(mysql_error())) {
			msg($lang['system']['done'], "{$lang['user']['hello']} <b>" . $nick . "</b>,<br/>{$lang['pass']['new']} <b>" . $slaptas . "</b><br/>");
		} else {
			klaida($lang['system']['systemerror'], "{$lang['system']['contactadmin']}.");
		}
	}
}
//priesingu atveju pranesam apie klaida
elseif (!empty($url['c'])) {
	klaida($lang['system']['sorry'], "{$lang['pass']['wrongcode']}.");
	redirect("?", "meta"); //peradresuojam i pagrindini psulapi
	$error = ''; //kad nerodytu formos
}

if (isset($_POST['action']) && $_POST['action'] == 'siusti') {
	$error = '';
	$kode = strip_tags(strtoupper($_POST['kode']));
	if ($kode != $_SESSION['code']) {
		$error = "{$lang['pass']['wrongcode']}<br />";
	} elseif ($_POST['email'] == $_POST['email1']) {
		$email = input(strip_tags($_POST['email']));
		$sql = mysql_query("SELECT `nick`,`email` FROM `" . LENTELES_PRIESAGA . "users` WHERE email=" . escape($email) . " LIMIT 1");
		if (count($sql) < 1) {
			$error .= " {$lang['pass']['wrongemail']}.<br />";
			mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "logai` (`action` ,`time` ,`ip`) VALUES (" . escape("{$lang['pass']['wrongemail']}({$lang['pass']['remain']}) : " . $email) . ", '" . time() . "', INET_ATON(" . escape(getip()) . "))");
		} else {
			//$sql = mysql_fetch_assoc($sql);
			$slaptas = random_name();
			$msg = "
{$lang['user']['hello']} <b>" . $sql['nick'] . "</b>,<br/>
{$lang['pass']['mail']}
 <a href='http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . "/?id," . $_GET['id'] . ";c," . $slaptas . "'>http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . "/?id," . $_GET['id'] . ";c," . $slaptas . "</a>
<hr>";
			ini_set("sendmail_from", $conf['Pastas']);
			mail($email, strip_tags($conf['Pastas']) . " {$lang['pass']['remain']}", $msg, "From: " . $conf['Pavadinimas'] . "<" . $conf['Pastas'] . ">\r\nContent-type: text/html; charset=utf-8");
			mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "users` SET `slaptas` = " . escape($slaptas) . " WHERE nick=" . escape($sql['nick']) . " LIMIT 1");
			mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "logai` (`action` ,`time` ,`ip`) VALUES (" . escape("{$lang['pass']['remain']}: Nick: " . $sql['nick'] . " Emailas: " . $sql['email']) . ", '" . time() . "', INET_ATON(" . escape(getip()) . "))");

			msg($lang['system']['done'], "{$lang['pass']['sent']}.");
			echo "<img src='priedai/human.php' style='display:none' />";
		}
	} else {
		$error .= "{$lang['pass']['notmatch']}<br />";
	}
}
if (isset($error)) {
	if (!empty($error) || $error != "") {
		klaida("{$lang['system']['sorry']}", $error);
	}
} elseif (!isset($_POST['action']) && !isset($url['c'])) {
	include_once ("priedai/class.php");

		$bla = new forma();
		$forma = array("Form" => array("action" => "", "method" => "post", "name" => "siusti","extra"=>"onSubmit=\"return checkMail('reg','email')\""), "{$lang['pass']['email']}:" => array("type" => "text", "value" =>$lang['pass']['email'], "name" => "email","extra"=>"onfocus=\"if (this.value=='{$lang['pass']['email']}   ') this.value=''\" onblur=\"if (this.value=='') this.value='{$lang['pass']['email']}   '; \""), 
		"{$lang['pass']['email2']}:" => array("type" => "text", "value" =>$lang['pass']['email'], "name" => "email1","extra"=>"onfocus=\"if (this.value=='{$lang['pass']['email']}   ') this.value=''\" onblur=\"if (this.value=='') this.value='{$lang['pass']['email']}   '; \""), kodas()=>array("type"=>"text","name"=>"kode", "style"=>"height:40px; text-align:center; text-transform:uppercase; font-weight:bold; vertical-align:middle"), " \r" => array("type" => "submit", "name" => "Submit_link", "value" => "{$lang['pass']['send']}")," \r\r" => array("type" => "hidden", "name" => "action", "value" => "siusti"));


	/*$text = "
    	<form name=\"siusti\" action=\"\" onSubmit=\"return checkMail('reg','email')\" method=\"post\">
    	    	<table border=0 width=100%>
    		<tr>
    			<td align=\"right\">{$lang['pass']['email']}:</td>
    			<td><input name=\"email\" id=\"email\" type=\"text\" value=\"{$lang['pass']['email']}\" onfocus=\"if (this.value=='{$lang['pass']['email']}   ') this.value=''\" onblur=\"if (this.value=='') this.value='{$lang['pass']['email']}   '; if (checkMail('reg','email')) ajax.update('tikrink.php?email='+this.value+'', 'result_email'); \" /> <span id=\"result_email\"></span></td>
    		</tr>
    		<tr>
    			<td align=\"right\">{$lang['pass']['email2']}:</td>
    			<td><input name=\"email1\" id=\"email1\" type=\"text\" value=\"{$lang['pass']['email']}   \" onfocus=\"if (this.value=='{$lang['pass']['email']}   ') this.value=''\" onblur=\"if (this.value=='') this.value='{$lang['pass']['email']}   '; if (checkMail('reg','email')) ajax.update('tikrink.php?email='+this.value+'', 'result_email'); \" /> <span id=\"result_email\"></span></td>
    		</tr>
    		<tr>
    			<td align=\"right\">" . kodas() . "</td>
    			<td><input name=\"kode\" id=\"kode\" style=\"height:40px; text-align:center; text-transform:uppercase; font-weight:bold; vertical-align:middle\" type=\"text\" onblur=\"ajax.update('tikrink.php?c='+this.value+'', 'result_kode'); \" /> <span id=\"result_kode\"></span></td>
    		</tr>
    		<tr>
    			<td></td>
    			<td><input type=\"submit\" value=\"{$lang['pass']['send']}\" /></td>
    		</tr>
    	</table>
    	<input type=\"hidden\" name=\"action\" value=\"siusti\" />
     	
    	</form>
    ";*/
	lentele($lang['pass']['remain'], $bla->form($forma));
	unset($text);
}

?>