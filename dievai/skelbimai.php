<?php

/**
 * @author fdisk
 * @copyright 2009
 */

 
$buttons="
<div id=\"admin_menu\" class=\"btns\">
	<a class=\"btn\" href=\"?id,{$_GET['id']};a,{$_GET['a']};v,1\"><span><img src=\"images/icons/admin_block.png\" alt=\"\" class=\"middle\"/>Nustatymai</span></a>
	<a class=\"btn\" href=\"?id,{$_GET['id']};a,{$_GET['a']};v,2\"><span><img src=\"images/icons/emblem-important.png\" alt=\"\" class=\"middle\"/>Laukiantys patvirtinimo</span></a>
	<a class=\"btn\" href=\"?id,{$_GET['id']};a,{$_GET['a']};v,3\"><span><img src=\"images/icons/tick_circle.png\" alt=\"\" class=\"middle\"/>Rodomi skelbimai</span></a>
	<a class=\"btn\" href=\"?id,{$_GET['id']};a,{$_GET['a']};v,4\"><span><img src=\"images/icons/sticky_note__pencil.png\" alt=\"\" class=\"middle\"/>Naujas skelbimas</span></a>
</div>";

lentele('Skelbimų administravimas', $buttons);
 
class skelbimas {

	var $user_id;
	var $message;
	var $debug;

	/* Tikrinam */
	function skelbimas() {
		global $_SESSION;
		$this->user_id = $_SESSION['id'];
	}

	/* Instaliuojam */
	function install() {
		$sql = "
		CREATE TABLE `" . LENTELES_PRIESAGA . "skelbimai` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `user_id` int(10) unsigned DEFAULT NULL,
		  `name` varchar(128) COLLATE utf8_lithuanian_ci DEFAULT NULL,
		  `email` tinytext COLLATE utf8_lithuanian_ci,
		  `tel` varchar(12) COLLATE utf8_lithuanian_ci DEFAULT NULL,
		  `text` text COLLATE utf8_lithuanian_ci COMMENT 'Skelbimo tekstas',
		  `approved` enum('no','yes') COLLATE utf8_lithuanian_ci DEFAULT 'no',
		  `date` datetime DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci
		";
		mysql_query1($sql);
		$q = array();
		$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`key`,`val`) VALUES ('skelbimai_kiek','10') ON DUPLICATE KEY UPDATE val=''";
		$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`key`,`val`) VALUES ('skelbimai_sms_nr','1679') ON DUPLICATE KEY UPDATE val=''";
		$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`key`,`val`) VALUES ('skelbimai_pass','') ON DUPLICATE KEY UPDATE val=''";
		$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`key`,`val`) VALUES ('skelbimai_sms_raktazodis','mrc') ON DUPLICATE KEY UPDATE val=''";
		$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`key`,`val`) VALUES ('skelbimai_sms_kaina','1Lt') ON DUPLICATE KEY UPDATE val=''";
		$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`key`,`val`) VALUES ('skelbimai_text','Skelbimo tekste negalima rašyti savo ar kitų žmonių kontaktinių duomenų. Visus skelbimus administratorius peržiuri ir rankiniu būdu patvirtina. Todėl jūsų skelbimas iškarto nepasirodys čia. Skelbimo patvirtinimas gali užtrukti neilgiau kaip dvi paros.') ON DUPLICATE KEY UPDATE val=''";
		foreach ($q as $sql) {
			mysql_query1($sql);
		}

		/* Ivykdom mysql uzklausa */
	}

	/* Tikrinam */
	function validate($string, $what = 'phone') {
		switch ($what) {
			case 'phone':
				{
					if (preg_match('/((\+{0,1}[[:digit:]]{3}[[:space:]]{0,1}|8{1})[[:space:]]{0,1}[[:digit:]]{8})/i', $string)) {
						return true;
					} else {
						return false;
					}
					break;
				}

			case 'message':
				{
					/* Patikrinam ar nera html'o */
					if (preg_match('%</?[a-z][a-z0-9]*[^<>]*>%i', $string)) {
						$this->debug .= 'Skelbimo tekste HTML neleistinas.';
						return false;
						# Successful match
					} else {
						# Match attempt failed
						if ($this->validate($string, 'phone') || $this->validate($string, 'email')) {
							$this->debug .= 'Prašome skelbimo tekste nerašyti savo kontaktinių duomenų.';
							return false;
						} else {
							return true;
						}
					}
					break;
				}
			case 'email':
				{
					//PHP5 filter_var('bob@exam.ple.com', FILTER_VALIDATE_EMAIL)
					if (preg_match('/([[:alnum:]_[:digit:].-]+)@{1}(([[:alnum:]_[:digit:]-]{1,67})|([[:alnum:]_[:digit:]-]+\.[[:alnum:]_[:digit:]-]{1,67}))\.(([a-zA-Z[:digit:]]{2,4})(\.[a-zA-Z[:digit:]]{2})?)/i', $string)) {
						# Successful match
						return true;
					} else {
						# Match attempt failed
						return false;
					}
					break;
				}
			case 'name':
				{
					if (preg_match('/^[a-zA-ZąčęėįšųūžĄČĘĖĮŠŲŪŽ]+(([\',. -][a-zA-Z ąčęėįšųūžĄČĘĖĮŠŲŪŽ])?[a-zA-ZąčęėįšųūžĄČĘĖĮŠŲŪŽ]*)$/i', $string)) {
						# Successful match
						return true;
					} else {
						# Match attempt failed
						return false;
					}
					break;
				}
			default:
		}
	}


	function add($msg, $name, $phone) {
		global $conf, $lang;
		if (!$this->validate($msg, 'message'))
			$this->debug .= 'Jūsų skelbimo tekstas neatitinka reikalavimų.';

		elseif (!$this->validate($name, 'name'))
			$this->debug .= 'Klaidingai užrašytas vardas.';

		elseif (!$this->validate($phone, 'phone'))
			$this->debug .= 'Klaidingai nurodytas telefono numeris';

		else {
			/* Send email */
			$title = strip_tags('Naujas skelbimas - '.$name);
			$from = strip_tags($name);
			$email = strip_tags($_POST['email']);
			$phone = strip_tags($phone);
			$message = strip_tags($msg);
		
			/* Insert data */
			mysql_query1(insert('skelbimai',array(
					'user_id'	=>	$_SESSION['id'],
					'name'		=>	$from,
					'email'		=>	$email,
					'tel'		=>	$phone,
					'text'		=>	$message,
					'approved'	=>	'yes',
					'date'		=>	date('Y-m-d H:i:s')
				)
			));
	
			msg($lang['system']['done'],'Jūsų skelbimas sėkmingai išsaugotas ir rodomas.');
			redirect("?id," . (int)$_GET['id'] .';a,'.(int)$_GET['a'], "meta");
		}
		
	}
	function display($num = false, $approved = false) {
		global $lang;
		if (!empty($this->debug))
			klaida('Klaida', $this->debug);
			
		if ($num) {
			
			$sql = "SELECT
					" . LENTELES_PRIESAGA . "skelbimai.*,
					" . LENTELES_PRIESAGA . "users.nick,
					" . LENTELES_PRIESAGA . "users.levelis
					FROM
					" . LENTELES_PRIESAGA . "skelbimai
					Inner Join " . LENTELES_PRIESAGA . "users ON " . LENTELES_PRIESAGA . "skelbimai.user_id = " . LENTELES_PRIESAGA . "users.id
					WHERE " . LENTELES_PRIESAGA . "skelbimai.id = " . escape($num) . "
					ORDER BY `date` DESC
					LIMIT 1";

			$query = mysql_query1($sql,360);
			
			lentele('Autorius',user($query['nick'],$query['user_id'],$query['levelis']).'<br />Email.:<strong>'.$query['email'].'</strong><br />Tel.:<strong>'.$query['tel'].'</strong><br />Data:<strong>'.$query['date'].'</strong>');
			lentele($query['date'],bbcode($query['text']));
			echo '<br /><input type="submit" value="Atgal" onclick="history.go(-1)" name="kontaktas" class="submit"/>';
		}
		else {
			$sql = "SELECT
					" . LENTELES_PRIESAGA . "skelbimai.*,
					" . LENTELES_PRIESAGA . "users.nick,
					" . LENTELES_PRIESAGA . "users.levelis
					FROM
					" . LENTELES_PRIESAGA . "skelbimai
					Inner Join " . LENTELES_PRIESAGA . "users ON " . LENTELES_PRIESAGA . "skelbimai.user_id = " . LENTELES_PRIESAGA . "users.id
					WHERE `approved` = ".escape(($approved?'yes':'no'))."
					ORDER BY `date` DESC
					LIMIT 0, 100";
			//$query = mysql_query1("SELECT `id`,`title`,`text`,`date`,`approved` FROM `" . LENTELES_PRIESAGA . "skelbimai` WHERE `approved` = ".escape(($approved?'yes':'no'))." ORDER BY `date` DESC LIMIT 0, 100",360);
			$query = mysql_query1($sql,360);
			foreach($query as $sql) {
				$info[] = array(
					"Autorius" => user($sql['nick'],$sql['user_id'],$sql['levelis']),
					"Skelbimas" => naujas(strtotime($sql['date']),$_SESSION['id']).'<a href="?id,'.$_GET['id'].';a,'.(int)$_GET['a'].';s,'.(int)$sql['id'].'" title="'.wrap1(strip_tags(bbcode($sql['text'])),50).'">'.trimlink(strip_tags(bbcode($sql['text'])),50).'</a>', 
					"Data" => kada($sql['date']),
					'Busena' => '<a href="?id,'.$_GET['id'].';a,'.(int)$_GET['a'].';b,'.($approved?'1':'2').';i,'.(int)$sql['id'].'"><img src="images/icons/status_'.($sql['approved'] == 'yes'?'online':'offline').'.png" /></a><a href="?id,'.$_GET['id'].';a,'.(int)$_GET['a'].';d,1;i,'.(int)$sql['id'].'" onclick="return confirm(\'Ar tikrai norite ištrinti šį skelbimą?\')"><img src="images/icons/cross.png" /></a>'
				);
			}
	
			include_once ("priedai/class.php");
			$bla = new Table();
			if (!empty($info)) {
				echo $bla->render($info);
			} else {
				klaida('Klaida','Skelbimų lenta tuščia.');
			}
		}
	}

	function form() {
		global $lang;
		$extra_info = '<font color="red">*</font> pažymėtus laukelius užpildyti būtina. Skelbimas bus įdėtas tik administratoriui patvirtinus, todėl prieš išsiųsdami įsitikinkite ar skelbimo tekstas įvestas teisingai ir tvarkingai.';
		include_once ("priedai/class.php");
		$bla = new forma();
		$form = array(
			"Form" => array("action" => "?id,".$_GET['id'].";a,".$_GET['a'], "method" => "post", "name" => "skelbimas"), 
			"{$lang['contact']['name']}:" => array("type" => "text", "class" => "input", "value" => (!empty($_POST['vardas']) ? input($_POST['vardas']) : input($_SESSION['username'])), "name" => "vardas", "class" => "input"), 
			"{$lang['contact']['email']}:" => array("type" => "text", "class" => "input", "value" => (!empty($_POST['email']) ? input($_POST['email']) : ''), "name" => "email", "class" => "input"), 
			"<font color='red'>*</font> Tel. Nr.:" => array("type" => "text", "class" => "input", "value" => (!empty($_POST['phone']) ? input($_POST['phone']) : ''), "name" => "phone", "class" => "input", "extra" => "title='+370'"), 
			"  " => array("type" => "string", "value" => bbk('skelbimas')),
			"<font color='red'>*</font> {$lang['admin']['news_text']}:" => array("type" => "textarea", "value" => (isset($_POST['skelbimas']) && !empty($_POST['skelbimas']) ? input($_POST['skelbimas']) : ''), "name" => "skelbimas", "extra" => "rows=5", "class" => "input"), 
			" " => array("type" => "submit", "name" => "kontaktas", "value" => "{$lang['contact']['submit']}")
		);
		if (!empty($this->debug))
			klaida('Klaida', $this->debug);
		lentele($lang['forum']['newpost'], $bla->form($form) . $extra_info);

	}
}

$bla = new skelbimas;

if (!empty($_POST) && !empty($_POST['kontaktas'])) {
		$bla->add($_POST['skelbimas'], $_POST['vardas'], $_POST['phone']);
		 if (!empty($bla->debug)) {
		 	$bla->form();
		 }
	  
} elseif(!empty($_POST['nustatymai'])) {
	$q = array();
	$q[] = "UPDATE `" . LENTELES_PRIESAGA . "nustatymai` SET `val` = " . escape($_POST['kiek']) . " WHERE `key` = 'skelbimai_kiek' LIMIT 1 ; ";
	$q[] = "UPDATE `" . LENTELES_PRIESAGA . "nustatymai` SET `val` = " . escape($_POST['pass']) . " WHERE `key` = 'skelbimai_pass' LIMIT 1 ; ";
	$q[] = "UPDATE `" . LENTELES_PRIESAGA . "nustatymai` SET `val` = " . escape($_POST['raktazodis']) . " WHERE `key` = 'skelbimai_sms_raktazodis' LIMIT 1 ; ";
	$q[] = "UPDATE `" . LENTELES_PRIESAGA . "nustatymai` SET `val` = " . escape($_POST['sms']) . " WHERE `key` = 'skelbimai_sms_nr' LIMIT 1 ; ";
	$q[] = "UPDATE `" . LENTELES_PRIESAGA . "nustatymai` SET `val` = " . escape($_POST['kaina']) . " WHERE `key` = 'skelbimai_sms_kaina' LIMIT 1 ; ";
	$q[] = "UPDATE `" . LENTELES_PRIESAGA . "nustatymai` SET `val` = " . escape($_POST['text']) . " WHERE `key` = 'skelbimai_text' LIMIT 1 ; ";
	foreach ($q as $sql) {
		mysql_query1($sql);
	}

} elseif (!empty($_GET['s']) && $_GET['s'] == 'n') {
	$bla->form();
	
} elseif (!empty($_GET['s']) && isnum($_GET['s']) &&  $_GET['s'] > 0) {
	$bla->display((int)$_GET['s']);
	
} elseif(!empty($_GET['v'])) {
	if ($_GET['v'] == 1) {
		$result = mysql_query1("show tables like '" . LENTELES_PRIESAGA . "skelbimai'");
		if (empty($result)) {
			$bla->install();
			msg('Įdiegimas','Dėmesio. Skelbimų lenta buvo sėkmingai įdiegta');
		} else {
			include_once ("priedai/class.php");
			$bla = new forma();
			$form = array(
				"Form" => array("action" => "?id,".$_GET['id'].";a,".$_GET['a'], "method" => "post", "name" => "text"), 
				"Kiek skelbimų rodyti:" => array("type" => "text", "class" => "input", "value" => (!empty($conf['skelbimai_kiek']) ? input($conf['skelbimai_kiek']) : '10'), "name" => "kiek", "class" => "input"), 
				"<font color='red'>*</font> Mokejimai.lt slaptazodis:" => array("type" => "password", "class" => "input", "name" => "pass", "class" => "input"), 
				"<font color='red'>*</font> SMS raktažodis:" => array("type" => "text", "class" => "input", "value" => (!empty($conf['skelbimai_sms_raktazodis']) ? input($conf['skelbimai_sms_raktazodis']) : 'mrc'), "name" => "raktazodis", "class" => "input"), 
				"<font color='red'>*</font> SMS nr.:" => array("type" => "text", "class" => "input", "value" => (!empty($conf['skelbimai_sms_nr']) ? input($conf['skelbimai_sms_nr']) : '1679'), "name" => "sms", "class" => "input"), 
				"<font color='red'>*</font> SMS kaina:" => array("type" => "text", "class" => "input", "value" => (!empty($conf['skelbimai_sms_kaina']) ? input($conf['skelbimai_sms_kaina']) : '1Lt'), "name" => "kaina", "class" => "input"), 
				"  " => array("type" => "string", "value" => bbk('text')),
				"<font color='red'>*</font> Informacinis tekstas <br />rodomas skelbimų puslapyje:" => array("type" => "textarea", "value" => (!empty($conf['skelbimai_text']) ? input($conf['skelbimai_text']) : 'Skelbimo tekste negalima rašyti savo ar kitų žmonių kontaktinių duomenų. Visus skelbimus administratorius peržiuri ir rankiniu būdu patvirtina. Todėl jūsų skelbimas iškarto nepasirodys čia. Skelbimo patvirtinimas gali užtrukti neilgiau kaip dvi paros.'), "name" => "text", "extra" => "rows=5", "class" => "input"), 
				" " => array("type" => "submit", "name" => "nustatymai", "value" => "{$lang['contact']['submit']}")
			);
			
			lentele('Nustatymai',$bla->form($form));
		}
	} elseif ($_GET['v'] == 2) {
		/* rodom nepatvirtintus */
		$bla->display(false,false);
	} elseif($_GET['v'] == 3) {
		/* Rodom patvirtintus */
		$bla->display(false,true);
	} elseif ($_GET['v'] == 4) {
		/* Naujo skelbimo forma */
		$bla->form();
	}
	
} elseif (!empty($_GET['b'])) {
	if ($_GET['b'] == 2 && isnum($_GET['i']) && $_GET['i'] > 0) {
		mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "skelbimai` SET `approved`='yes' WHERE (`id`=".escape((int)$_GET['i']).") LIMIT 1");
		$bla->display(false,false);
	} elseif ($_GET['b'] == 1 && isnum($_GET['i']) && $_GET['i'] > 0) {
		mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "skelbimai` SET `approved`='no' WHERE (`id`=".escape((int)$_GET['i']).") LIMIT 1");
		$bla->display(false,true);
	}
} else {
	$bla->display();
}

?>

