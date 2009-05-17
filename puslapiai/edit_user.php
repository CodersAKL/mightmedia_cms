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
if (!defined("LEVEL") || !defined("OK") || !isset($_SESSION['username'])) {
	header("Location: ?");
	exit;
}
if (isset($url['m']) && isnum($url['m']) && $url['m'] > 0) {
	$mid = (int)$url['m'];
} else {
	$mid = 0;
}
if (isset($url['id']) && isnum($url['id']) && $url['id'] > 0) {
	$id = (int)$url['id'];
} else {
	$url['id'] = 0;
}
// ############ Apdorojomi duomenys kurie buvo pateikti is tam tikros redagavimo lenteles #####################
// ######### Slaptazodzio keitimas #############
if (isset($_POST['old_pass']) && count($_POST['old_pass']) > 0 && count($_POST['new_pass']) > 0 && count($_POST['new_pass2']) > 0) {
	$old_pass = koduoju($_POST['old_pass']);
	$sql = mysql_num_rows(mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "users` WHERE nick=" . escape($_SESSION['username']) . " AND pass=" . escape($old_pass) . ""));
	if ($sql != 0) {
		$new_pass = koduoju($_POST['new_pass']);
		$new_pass2 = koduoju($_POST['new_pass2']);
		if ($new_pass == $new_pass2) {
			mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "users` SET pass=" . escape($new_pass) . " WHERE nick=" . escape($_SESSION['username']) . "");
			msg("{$lang['system']['done']}", "{$lang['user']['edit_updated']}.");

		} else {
			klaida("{$lang['system']['error']}", "{$lang['user']['edit_badconfirm']}.");
		}
	} else {
		klaida("{$lang['system']['error']}", "{$lang['user']['edit_badpass']}.");
	}
	unset($old_pass, $sql, $new_pass, $new_pass2);
}
// ################# kontaktu keitimas ######################
if (isset($_POST['action']) && $_POST['action'] == 'contacts_change') {
	$icq = input($_POST['icq']);
	$msn = input($_POST['msn']);
	$skype = input($_POST['skype']);
	$yahoo = input($_POST['yahoo']);
	$aim = input($_POST['aim']);
	$url = input($_POST['url']);
	$email = input($_POST['email']);
	mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "users` SET icq=" . escape($icq) . ", msn=" . escape($msn) . ", skype=" . escape($skype) . ", yahoo=" . escape($yahoo) . ", aim=" . escape($aim) . ", url=" . escape($url) . ", email=" . escape($email) . " WHERE nick=" . escape($_SESSION['username']) . "");
	msg("{$lang['system']['done']}", "{$lang['user']['edit_updated']}");
	unset($icq, $msn, $skype, $yahoo, $aim, $url, $email);
}
// ################ Salies bei miesto nustatymai #############
if (isset($_POST['action']) && $_POST['action'] == 'country_change') {
	$miestas = input($_POST['miestas']);
	$salis = input($_POST['salis']);
	mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "users` SET salis=" . escape($salis) . ", miestas=" . escape($miestas) . " WHERE nick=" . escape($_SESSION['username']) . "");
	msg("{$lang['system']['done']}", "{$lang['user']['edit_updated']}");
}


// ################ Pagrindiniu nustatymu keitimas ###################
if (isset($_POST['action']) && $_POST['action'] == 'default_change') {
	$vardas = input($_POST['vardas']);
	$pavarde = input($_POST['pavarde']);
	$metai = (int)$_POST['metai'];
	$menesis = (int)$_POST['menesis'];
	$diena = (int)$_POST['diena'];
	$parasas = input($_POST['parasas']);
	$gimimas = $metai . "-" . $menesis . "-" . $diena;
	mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "users` SET vardas='" . $vardas . "', pavarde='" . $pavarde . "', parasas='" . $parasas . "', gim_data='" . $gimimas . "' WHERE nick='" . $_SESSION['username'] . "'");
	msg("{$lang['system']['done']}", "{$lang['user']['edit_updated']}");
}
// ################ Siulomi punktai redagavimui MENIU ##########################
$text = "
 <table width=100% border=0>
	<tr>
		<td>
			<div class=\"blokas\"><center><a href='?id," . $id . ";m,1'><img src=\"images/user/user-auth.png\" alt=\"slaptazodis\" />{$lang['user']['edit_pass']}</a></center></div>
			<div class=\"blokas\"><center><a href='?id," . $id . ";m,2'><img src=\"images/user/user-contact.png\" alt=\"kontaktai\" />{$lang['user']['edit_contacts']}</a></center></div>
			<div class=\"blokas\"><center><a href='?id," . $id . ";m,3'><img src=\"images/user/user-place.png\" alt=\"vietove\" />{$lang['user']['edit_locality']}</a></center></div>
<div class=\"blokas\"><center><a href='?id," . $id . ";m,4'><img src=\"images/user/user-avatar.png\" alt=\"avataras\" />{$lang['user']['edit_avatar']}</a></center></div>
			<div class=\"blokas\"><center><a href='?id," . $id . ";m,5'><img src=\"images/user/user-settings.png\" alt=\"nustatymai\" />{$lang['user']['edit_signature']}</a></center></div>
			
		</td>
	</tr>
</table>
";

// ######################### Jei pasirinktas vienas is pasiulytu MENIU ####################
if (isset($mid) && isnum($mid)) {
	// Pakeisti slaptazodi
	if ($mid == 1) {
		include_once ("priedai/class.php");
		$form = array("Form" => array("action" => "", "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "change_password"), "{$lang['user']['edit_pass']}:" => array("type" => "password", "value" => "", "name" => "old_pass", "style" => "width:200px"), "{$lang['user']['edit_newpass']}:" => array("type" => "password", "value" => "", "name" => "new_pass", "style" => "width:200px"), "{$lang['user']['edit_confirmnewpass']}:" => array("type" => "password", "value" => "", "name" => "new_pass2", "style" => "width:200px"), "" => array("type" => "hidden", "name" => "action", "value" => "pass_change"), "" => array("type" => "submit", "name" => "action", "value" => "{$lang['user']['edit_update']}"));
		$bla = new forma();
		$text .= $bla->form($form, "{$lang['user']['edit_pass']}");
	}
	// Pakeisti kontaktinius duomenis
	if ($mid == 2) {
		$info = mysql_fetch_assoc(mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "users` WHERE nick=" . escape($_SESSION['username']) . ""));
		$text .= "
				<fieldset>
					<legend>{$lang['user']['edit_contacts']}</legend>
					<form name='change_contacts' action='' method='post' onSubmit=\"return checkMail('change_contacts','email')\">
					<table border=0 width=100%>
						<tr>
							<td align='right' width='15%'>ICQ:</td>
							<td><input name=\"icq\" type=\"text\" value=" . input($info['icq']) . "></td>
						</tr>
						<tr>
							<td align='right'>MSN:</td>
							<td><input name=\"msn\" type=\"text\" value=" . input($info['msn']) . "></td>
						</tr>
						<tr>
							<td align='right'>Skype:</td>
							<td><input name=\"skype\" type=\"text\" value=" . input($info['skype']) . "></td>
						</tr>
						<tr>
							<td align='right'>Yahoo:</td>
							<td><input name=\"yahoo\" type=\"text\" value=" . input($info['yahoo']) . "></td>
						</tr>
						<tr>
							<td align='right'>AIM:</td>
							<td><input name=\"aim\" type=\"text\" value=" . input($info['aim']) . "></td>
						</tr>
						<tr>
							<td align='right'>{$lang['user']['edit_web']}:</td>
							<td><input name=\"url\" type=\"text\" value=" . input($info['url']) . "></td>
						</tr>
						<tr>
							<td align='right'>{$lang['user']['edit_email']}:</td>
							<td><input name=\"email\" type=\"text\" value=" . input($info['email']) . "></td>
						</tr>
						<tr>
							<td colspan=2>
								<input type=\"submit\" value=\"{$lang['user']['edit_update']}\">
								<input type=\"hidden\" name=\"action\" value=\"contacts_change\" />
							</td>
						</tr>
					</table>
					</form>
				</fieldset>
			";
		unset($info);
	}
	// Pakeisti sali, miesta
	if ($mid == 3) {
		$info = mysql_fetch_assoc(mysql_query1("SELECT salis, miestas FROM `" . LENTELES_PRIESAGA . "users` WHERE nick='" . $_SESSION['username'] . "'"));
		$text .= "
				<fieldset>
					<legend>{$lang['user']['edit_locality']}</legend>
					<form name='change_country' action='' method='post'>
					<table border=0 width=100%>
						<tr>
							<td align='right' width='5%'>{$lang['user']['edit_country']}:</td>
							<td>
								<select size=\"1\" name=\"salis\">
		";
		$sql = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "salis`");
		while ($row = mysql_fetch_assoc($sql)) {
			$text .= "<option value='" . $row['iso'] . "' ";
			if ($row['iso'] == $info['salis']) {
				$text .= "selected";
			}
			$text .= ">" . $row['printable_name'] . "</option>\n";
		}
		$text .= "		</select>
  					</td>
  				</tr>
  				<tr>
  					<td align='right'>{$lang['user']['edit_city']}:</td>
  					<td><input name=\"miestas\" type=\"text\" value=" . $info['miestas'] . ">
  				</tr>
  				<tr>
					<td colspan=2>
						<input type=\"submit\" value=\"{$lang['user']['edit_update']}\">
						<input type=\"hidden\" name=\"action\" value=\"country_change\" />
					</td>
				</tr>
  			</table>
  			</form>
  			</fieldset>
  		";
		unset($info, $sql, $row);
	}

	// Avataro keitimas
	//Žaidime mano šito nereikės

	if ($mid == 4) {
		$sql = mysql_fetch_assoc(mysql_query1("SELECT `email` FROM `" . LENTELES_PRIESAGA . "users` WHERE `nick`='" . $_SESSION['username'] . "'"));

		$text .= "<fieldset>
  			<legend>{$lang['user']['edit_avatar']}</legend><center><img src='http://www.gravatar.com/avatar.php?gravatar_id=" . md5($sql['email']) . "&amp;default=" . urlencode('images/avatars/no_image.jpg') . "&amp;size=60'></img><br/>
{$lang['user']['edit_avatarcontent']} <b>" . $sql['email'] . "</b> .</center></fieldset>
		";
	}
	// Pagrindiniai nustatymai
	if ($mid == 5) {
		$sql = mysql_fetch_assoc(mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "users` WHERE nick='" . $_SESSION['username'] . "'"));
		$data = explode("-", $sql['gim_data']);
		$text .= "
  			<fieldset>
  			<legend>{$lang['user']['edit_mainsettings']}</legend>
  			<form name=\"pagr_nustatymai\" action=\"\" method=\"post\">
  			<table border=0 width=100%>
  				<tr>
  					<td align='right' width='15%'>{$lang['user']['edit_name']}:</td>
  					<td><input name=\"vardas\" type=\"text\" value=" . input($sql['vardas']) . "></td>
  				</tr>
  				<tr>
  					<td align='right'>{$lang['user']['edit_secondname']}:</td>
  					<td><input name='pavarde' type=\"text\" value=" . input($sql['pavarde']) . "></td>
  				</tr>
  				<tr>
  					<td align='right'>{$lang['user']['edit_dateOfbirth']}:</td>
  					<td>
  					<select size=\"1\" name=\"diena\">";
		$a = 1;
		while ($a < 31) {
			$text .= "<option value=" . $a . " ";
			if (isset($data[2]) && $a == $data[2]) {
				$text .= "selected";
			}
			$text .= " >$a</option>\n";
			$a++;
		}
		unset($a);
		$text .= "
  					</select>
  					<select size=\"1\" name=\"menesis\">
  						<option value=\"1\" ";
		if (isset($data[1]) && $data[1] == 1) {
			$text .= "selected";
		}
		$text .= ">{$lang['calendar']['January']}</option>
  						<option value=\"2\" ";
		if (isset($data[1]) && $data[1] == 2) {
			$text .= "selected";
		}
		$text .= ">{$lang['calendar']['February']}</option>
  						<option value=\"3\" ";
		if (isset($data[1]) && $data[1] == 3) {
			$text .= "selected";
		}
		$text .= ">{$lang['calendar']['March']}</option>
  						<option value=\"4\" ";
		if (isset($data[1]) && $data[1] == 4) {
			$text .= "selected";
		}
		$text .= ">{$lang['calendar']['April']}</option>
  						<option value=\"5\" ";
		if (isset($data[1]) && $data[1] == 5) {
			$text .= "selected";
		}
		$text .= ">{$lang['calendar']['May']}</option>
  						<option value=\"6\" ";
		if (isset($data[1]) && $data[1] == 6) {
			$text .= "selected";
		}
		$text .= ">{$lang['calendar']['June']}</option>
  						<option value=\"7\" ";
		if (isset($data[1]) && $data[1] == 7) {
			$text .= "selected";
		}
		$text .= ">{$lang['calendar']['July']}</option>
  						<option value=\"8\" ";
		if (isset($data[1]) && $data[1] == 8) {
			$text .= "selected";
		}
		$text .= ">{$lang['calendar']['August']}</option>
  						<option value=\"9\" ";
		if (isset($data[1]) && $data[1] == 9) {
			$text .= "selected";
		}
		$text .= ">{$lang['calendar']['September']}</option>
  						<option value=\"10\" ";
		if (isset($data[1]) && $data[1] == 10) {
			$text .= "selected";
		}
		$text .= ">{$lang['calendar']['October']}</option>
  						<option value=\"11\" ";
		if (isset($data[1]) && $data[1] == 11) {
			$text .= "selected";
		}
		$text .= ">{$lang['calendar']['November']}</option>
  						<option value=\"12\" ";
		if (isset($data[1]) && $data[1] == 12) {
			$text .= "selected";
		}
		$text .= ">{$lang['calendar']['December']}</option>
					</select>
					<select size=\"1\" name=\"metai\">";
		$a = date("Y") - 80;
		$viso = date("Y") - 7;
		while ($a < $viso) {
			$text .= "<option value=" . $a . " ";
			if ($data[0] == $a) {
				$text .= "selected";
			}
			$text .= ">$a</option>\n";
			$a++;
		}
		unset($viso, $a);
		$text .= "</select></td>
				</tr>
				<tr>
					<td valign='top' align='right'>{$lang['user']['edit_signature']}:</td>
					<td><textarea name=\"parasas\" rows=5 cols=30 wrap=\"on\">" . input($sql['parasas']) . "</textarea></td>
				</tr>
			</table>
			<input type=\"submit\" value=\"{$lang['user']['edit_update']}\">
			<input type=\"hidden\" name=\"action\" value=\"default_change\" />
			</form>
			</fieldset>";
	}
}
// ############## VARTOTOJO Informacija ##############
else {
	include "puslapiai/view_user.php";
	$text .= "</td></tr></table>";
}
lentele("{$lang['user']['edit_settings']}", $text);

?>
<script language="JavaScript1.2">
function checkMail(form,email) {
	var x = document.forms[form].email.value;
	var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (filter.test(x)) { return true; }
	else { alert('<?php

echo $lang['user']['edit_bademail'];

?>'); return false; }
}
</script>
