<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: FDisk $
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision: 356 $
 * @$Date: 2009-11-11 00:08:55 +0200 (Wed, 11 Nov 2009) $
 * */
//if (!defined("LEVEL") || LEVEL > 1 || !defined("OK")) {
if (!defined("OK") || !ar_admin(basename(__file__))) {
    redirect('location: http://' . $_SERVER["HTTP_HOST"]);
}
if (!isset($_GET['v'])) { $_GET['v'] = 1; $url['v'] = 1;}
$buttons = "<div id=\"admin_menu\" class=\"btns\"><a href=\"" . url("?id,{$_GET['id']};a,{$_GET['a']};v,1") . "\"class=\"btn\"><span><img src=\"" . ROOT . "images/icons/heart__plus.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['poll_create']}</span></a>  <a href=\"" . url("?id,{$_GET['id']};a,{$_GET['a']};v,2") . "\" class=\"btn\"><span><img src=\"" . ROOT . "images/icons/heart__pencil.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['poll_edit']}</span></a></div>";
lentele($lang['admin']['poll'], $buttons);
include_once(ROOT.'priedai/class.php');
//delete poll
if(isset($_GET['t'])) {
  mysql_query1("DELETE FROM  `".LENTELES_PRIESAGA."poll_questions` WHERE `id`=".escape($_GET['t'])." LIMIT 1");
  mysql_query1("DELETE FROM  `".LENTELES_PRIESAGA."poll_answers` WHERE `question_id`=".escape($_GET['t'])."");
  mysql_query1("DELETE FROM  `".LENTELES_PRIESAGA."poll_votes` WHERE `question_id`=".escape($_GET['t'])."");
  header("Location: " . url("?id," . $_GET['id'] . ";a," . $_GET['a'] . ";v,2"));

}

//poll creation
if($_GET['v'] == 1) {
  if(isset($_POST['question'])){
      mysql_query1("INSERT INTO `".LENTELES_PRIESAGA."poll_questions` (`question`, `radio`, `shown`, `only_guests`, `author_id`, `author_name`, `lang`) VALUES (".escape($_POST['question']).", ".escape((int)$_POST['type']).", ".escape((int)$_POST['shown']).", ".escape((int)$_POST['only_guests']).", ".escape($_SESSION['id']).",".escape($_SESSION['username']).", ".escape(lang()).")");
      $qid = mysql_query1("SELECT `id` FROM `".LENTELES_PRIESAGA."poll_questions` WHERE `lang` = ".escape(lang())." ORDER BY `id` DESC LIMIT 1");
      foreach ($_POST['answers'] as $ans) {
        //echo $ans.'</br>';
        mysql_query1("INSERT INTO `".LENTELES_PRIESAGA."poll_answers` (`question_id`, `answer`, `lang`) VALUES (".escape($qid['id']).", ".escape($ans).", `lang` = ".escape(lang()).")");
      }
       msg($lang['system']['done'], $lang['admin']['poll_created']);
       redirect(url("?id," . $_GET['id'] . ";a," . $_GET['a']), "meta");
  }
  echo <<<HTML
  <script type="text/javascript">
$(document).ready(function() { // when document has loaded
	var i = $('input').size() + 1; // check how many input exists on the document and add 1 for the add command to work
	$('a#add').click(function() { // when you click the add link
		$('<p><input type="text" name="answers[]" class="input" /></p>').animate({ opacity: "show" }, "slow").appendTo('#inputs'); // append (add) a new input to the document.
// if you have the input inside a form, change body to form in the appendTo
		i++; //after the click i will be i = 3 if you click again i will be i = 4
	});
	$('a#remove').click(function() { // similar to the previous, when you click remove link
	if(i > 1) { // if you have at least 1 input on the form
		$('#inputs input:last').animate({opacity:"hide"}, "slow").remove(); //remove the last input
		i--; //deduct 1 from i so if i = 3, after i--, i will be i = 2
	}
	});
	/*$('a.reset').click(function() {
	while(i > 2) { // while you have more than 1 input on the page
		$('#inputs input:last').remove(); // remove inputs
		i--;
	}
	});*/
});
</script>
HTML;
  $form = new forma();
  $inputs = array("Form" => array("action" => "", "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "reg"),
	"{$lang['admin']['poll_question']}:" => array("type" => "text", "name" => "question", "class" => "input"),
	"{$lang['admin']['poll_votecan']}:" => array("type" => "select", "name" => "only_guests", "value" => array(0 => $lang['admin']['poll_all'], 1 => $lang['admin']['poll_membs']), "class" => "input"),
	"{$lang['admin']['poll_type']}:" => array("type" => "select", "name" => "type", "value" => array(0 => 'checkbox', 1 => 'radio'), "class" => "input"),
		"{$lang['admin']['poll_active']}:" => array("type" => "select", "name" => "shown", "value" => array(0 => $lang['admin']['no'], 1 => $lang['admin']['yes']), "class" => "input"),
  "{$lang['admin']['poll_answers']}:" => array("type" => "string",  "value" => "<a href=\"#\" onclick=\"return false;\" id=\"add\"><img src=\"".ROOT."/images/icons/plus.png\" alt=\"[+]\" /></a> <a href=\"#\" onclick=\"return false;\" id=\"remove\"><img src=\"".ROOT."/images/icons/minus.png\" alt=\"[-]\" /></a><div id=\"inputs\"><p><input type=\"text\" name=\"answers[]\" class=\"input\" /></p></div>", "class" => "input"),
  " " => array("type" => "submit", "value" => $lang['admin']['poll_create'])
	); 
  lentele($lang['admin']['poll_create'], $form->form($inputs));
} elseif($_GET['v'] == 2){
      if(isset($_GET['e'])){
        if(isset($_POST['update']))
           mysql_query1("UPDATE `".LENTELES_PRIESAGA."poll_questions` SET `question`=".escape($_POST['question']).", `radio`=".escape((int)$_POST['type']).", `shown`=".escape((int)$_POST['shown']).", `only_guests`=".escape((int)$_POST['only_guests'])." WHERE `id`=".escape($_GET['e'])."");
          $quest = mysql_query1("SELECT * FROM  `".LENTELES_PRIESAGA."poll_questions` WHERE `id`=".escape($_GET['e'])." LIMIT 1");
          $form = new forma();
          $inputs = array("Form" => array("action" => "", "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "reg"),
          "{$lang['admin']['poll_question']}:" => array("type" => "text", "name" => "question", "value"=>input($quest['question']), "class" => "input"),
          "{$lang['admin']['poll_votecan']}:" => array("type" => "select", "selected"=> input($quest['only_guests']) , "name" => "only_guests", "value" => array(0 => $lang['admin']['poll_all'], 1 => $lang['admin']['poll_membs']), "class" => "input"),
          "{$lang['admin']['poll_type']}:" => array("type" => "select", "name" => "type", "value" => array(0 => 'checkbox', 1 => 'radio'), "class" => "input", "selected"=> input($quest['radio'])),
            "{$lang['admin']['poll_active']}:" => array("type" => "select", "name" => "shown", "value" => array(0 => $lang['admin']['no'], 1 => $lang['admin']['yes']), "class" => "input", "selected"=> input($quest['shown'])),
            " " => array("type" => "submit", "name"=>"update", "value" => $lang['admin']['edit'])
          ); 
          lentele($lang['admin']['poll_edit'], $form->form($inputs));
      }
    $tbl = new Table();
    $quest = mysql_query1("SELECT * FROM `".LENTELES_PRIESAGA."poll_questions` ORDER BY `id` DESC");
    foreach ($quest as $row) {
      $info[] = array("#"=>($row['shown'] == 1 ? '<img src="'.ROOT.'/images/icons/status_online.png" alt="" />': '<img src="'.ROOT.'/images/icons/status_offline.png" alt="" />'), "{$lang['admin']['poll']}:" => input($row['question']),
						"{$lang['admin']['action']}:" => " <a href='".url("?id,{$_GET['id']};a,{$_GET['a']};v,{$_GET['v']};e," . $row['id'] ). "' title='{$lang['admin']['edit']}'><img src='".ROOT."images/icons/pencil.png' border='0'></a> <a href='".url("?id,{$_GET['id']};a,{$_GET['a']};t," . $row['id'] ). "' title='{$lang['admin']['delete']}' onClick=\"return confirm('" . $lang['admin']['delete'] . "?')\"><img src='".ROOT."images/icons/cross.png' border='0'></a>");

			}
			if(isset($info)){
        echo '<style type="text/css" title="currentStyle">
			@import "'.ROOT.'javascript/table/css/demo_page.css";
			@import "'.ROOT.'javascript/table/css/demo_table.css";
			</style>
			<script type="text/javascript" language="javascript" src="'.ROOT.'javascript/table/js/jquery.dataTables.js"></script>
			<script type="text/javascript" charset="utf-8">
				$(document).ready(function() {
					$(\'#polls table\').dataTable( {
			  "bInfo": false,
			  "bProcessing": true,
						"aoColumns": [
              { "sWidth": "10px", "sType": "html" },
							{ "sWidth": "80%", "sType": "string" },
							{ "sWidth": "20px", "sType": "html", "bSortable": false}
						]
					} );
				} );
			</script>';
			
        lentele($lang['admin']['poll_edit'], '<div id="polls">'.$tbl->render($info).'</div>');
     } else 
        lentele($lang['admin']['poll_edit'], $lang['admin']['poll_no']);
  

  
}
  

//if (empty($url['v'])) {
//	$url['v'] = 0;

/*

$sql = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "balsavimas` WHERE `ijungtas`='TAIP' AND `lang` = " . escape(lang()) . "  ORDER BY `id` DESC LIMIT 1");
if (sizeof($sql) > 0) {
    if (!empty($sql['klausimas'])) {
        $info = $sql['klausimas'];
        $text = "<b>$info</b></br>";
    } else {
        $text = msg($lang['admin']['poll_no'], $lang['admin']['poll_no']);
    }
}

lentele($lang['admin']['poll_active'], $text);
unset($text, $a, $total, $info2, $info, $sql, $is);
//}
if (isset($url['v']) && (int) $url['v'] == 1) {
    $text = "
<form name='b_create' action='" . url("?id," . $_GET['id'] . ";a," . $_GET['a']) . "' method='post'>
	<table border=0>
		<tr>
			<td>{$lang['admin']['poll_question']}:</td>
			<td><input name='b_kl' type='text' size=50 value=''></td>
		</tr>
		<tr>
			<td>{$lang['admin']['poll_votecan']}:</td>
			<td>
				<select size='1' name='leid'>
					<option value='vis'>{$lang['admin']['poll_all']}</option>
					<option value='nar'>{$lang['admin']['poll_membs']}</option>
				</select>
			</td>
		</tr>
		<tr><td colspan=2>
		<input name='1' type='text' size=50 value=''><br/>
				<input name='2' type='text' size=50 value=''><br/>
						<input name='3' type='text' size=50 value=''><br/>
		<input name='4' type='text' size=50 value=''><br/>
		<input name='5' type='text' size=50 value=''><br/>
{$lang['admin']['poll_info']}.

		</td></tr>
		<tr>
			<td></td>
			<td>
				
			</td>
		</tr>
		</table>
		<input name='b_create' type='submit' value='{$lang['admin']['poll_create']}'><br>
</form>
	";
    lentele($lang['admin']['poll_create'], $text);
    unset($text);
}

if (isset($_POST['b_delete']) && $_POST['b_delete'] == $lang['admin']['delete']) {
    $result = mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "balsavimas` WHERE `id`= " . escape((int) $_POST['id']) . " LIMIT 1");
    header("Location: " . url("?id," . $_GET['id'] . ";a," . $_GET['a']));
}
if (isset($_POST['b_edit']) && $_POST['b_edit'] == $lang['admin']['edit']) {
    $result2 = mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "balsavimas` SET info='" . $_POST['leid'] . "', `ijungtas` = " . escape($_POST['ar']) . " WHERE `id`='" . $url['n'] . "' LIMIT 1 ;");
    header("Location: " . url("?id," . $_GET['id'] . ";a," . $_GET['a']));
}
if (isset($_POST['b_delete']) && $_POST['b_delete'] == $lang['admin']['edit']) {


    $edit = "
<form name='b_edit' action='" . url("?id," . $_GET['id'] . ";a," . $_GET['a'] . ";n," . $_POST['id']) . "' method='post'>
	Ar rodyti apklausą?
	<select size=1 name='ar'>
		<option name='ar' value='TAIP'>{$lang['admin']['yes']}</option>
		<option name='ar' value='NE'>{$lang['admin']['no']}</option>
	</select>
					
	</br>{$lang['admin']['poll_votecan']}:
	<select size=1 name='leid'>
		<option value='vis'>{$lang['admin']['poll_all']}</option>
		<option value='nar'>{$lang['admin']['poll_membs']}</option>
	</select>
	<input name='b_edit' type='submit' value='{$lang['admin']['edit']}'><br>
</form>";
    lentele($lang['admin']['poll_edit'], $edit);
}
if (isset($url['v']) &&(int) $url['v'] == 2) {
    $sql2 = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "balsavimas` WHERE `lang` = " . escape(lang()));
    if (sizeof($sql2) > 0) {
        $text = "
	<form name='b_delete' action='" . url("?id," . $_GET['id'] . ";a," . $_GET['a']) . "' method='post'>
		<select size='1' name='id'>
	";

        foreach ($sql2 as $row) {
            if (isset($row['klausimas'])) {
                $text .= "<option  value=" . $row['id'] . ">" . $row['klausimas'] . "</option>";
            }
        }

        $text .= "
		</select>
		<input name='b_delete' type='submit' onClick=\"return confirm('" . $lang['admin']['delete'] . "?')\" value='{$lang['admin']['delete']}'>
		<input name='b_delete' type='submit' value='{$lang['admin']['edit']}'>
	</form>
	";
        lentele($lang['admin']['poll_edit'], $text);
    }
    unset($sql, $row, $text, $info);
}


if (isset($_POST['b_create']) && $_POST['b_create'] == $lang['admin']['poll_create']) {
    $kl = $_POST['b_kl'];
    $ats1 = (isset($_POST[1]) && ! empty($_POST[1]) ? strip_tags($_POST[1]) . ';0' : ';0');
    $ats2 = (isset($_POST[2]) && ! empty($_POST[2]) ? strip_tags($_POST[2]) . ';0' : ';0');
    $ats3 = (isset($_POST[3]) && ! empty($_POST[3]) ? strip_tags($_POST[3]) . ';0' : ';0');
    $ats4 = (isset($_POST[4]) && ! empty($_POST[4]) ? strip_tags($_POST[4]) . ';0' : ';0');
    $ats5 = (isset($_POST[5]) && ! empty($_POST[5]) ? strip_tags($_POST[5]) . ';0' : ';0');

    $result = mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "balsavimas` (`info`, `autorius`, `laikas`, `klausimas`, `pirmas`, `antras`, `trecias`, `ketvirtas`,`penktas`, `lang`) VALUES ('" . $_POST['leid'] . "', '" . $_SESSION['id'] . "', '" . time() . "','" . $kl . "','" . $ats1 . "','" . $ats2 . "','" . $ats3 . "','" . $ats4 . "','" . $ats5 . "', " . escape(lang()) . ")");
    delete_cache("SELECT * ,autorius ,(SELECT `nick` FROM `" . LENTELES_PRIESAGA . "users` WHERE id=autorius LIMIT 1)AS nick FROM `" . LENTELES_PRIESAGA . "balsavimas` WHERE ijungtas='TAIP' ORDER BY `laikas` DESC LIMIT 1");
    if ($result) {
        msg($lang['system']['done'], $lang['admin']['poll_created']);
    }
    redirect(url("?id," . $_GET['id'] . ";a," . $_GET['a']), "meta");
}
unset($a, $ats1, $ats2, $ats3, $ats4, $ats5, $balsas, $sujungti);
//unset($_POST['b_create'], $_POST['b_delete']);*/
?>