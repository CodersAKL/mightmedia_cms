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
if ( !defined( "OK" ) || !ar_admin( basename( __file__ ) ) ) {
	redirect( 'location: http://' . $_SERVER["HTTP_HOST"] );
}
if ( !isset( $_GET['v'] ) ) {
	$_GET['v'] = 1;
	$url['v']  = 1;
}
//Puslapiavimui
if ( isset( $url['p'] ) && isnum( $url['p'] ) && $url['p'] > 0 ) {
	$p = (int)$url['p'];
} else {
	$p = 0;
}
$limit = 15;

if(BUTTONS_BLOCK) {
	lentele($lang['admin']['poll'], buttonsMenu(buttons('polls')));
}

//delete poll
if ( isset( $_GET['t'] ) ) {
	mysql_query1( "DELETE FROM  `" . LENTELES_PRIESAGA . "poll_questions` WHERE `id`=" . escape( $_GET['t'] ) . " LIMIT 1" );
	mysql_query1( "DELETE FROM  `" . LENTELES_PRIESAGA . "poll_answers` WHERE `question_id`=" . escape( $_GET['t'] ) . "" );
	mysql_query1( "DELETE FROM  `" . LENTELES_PRIESAGA . "poll_votes` WHERE `question_id`=" . escape( $_GET['t'] ) . "" );
	header( "Location: " . url( "?id," . $_GET['id'] . ";a," . $_GET['a'] . ";v,2" ) );
}

//poll creation
if ( $_GET['v'] == 1 ) {
	if ( isset( $_POST['question'] ) ) {
		mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "poll_questions` (`question`, `radio`, `shown`, `only_guests`, `author_id`, `author_name`, `lang`) VALUES (" . escape( $_POST['question'] ) . ", " . escape( (int)$_POST['type'] ) . ", " . escape( (int)$_POST['shown'] ) . ", " . escape( (int)$_POST['only_guests'] ) . ", " . escape( $_SESSION[SLAPTAS]['id'] ) . "," . escape( $_SESSION[SLAPTAS]['username'] ) . ", " . escape( lang() ) . ")" );
		$qid = mysql_query1( "SELECT `id` FROM `" . LENTELES_PRIESAGA . "poll_questions` WHERE `lang` = " . escape( lang() ) . " ORDER BY `id` DESC LIMIT 1", 3600 );
		foreach ( $_POST['answers'] as $ans ) {
			//echo $ans.'</br>';
			mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "poll_answers` (`question_id`, `answer`, `lang`) VALUES (" . escape( $qid['id'] ) . ", " . escape( $ans ) . "," . escape( lang() ) . ")" );
		}
		msg( $lang['system']['done'], $lang['admin']['poll_created'] );
		redirect( url( "?id," . $_GET['id'] . ";a," . $_GET['a'] ), "meta" );
	}
	echo <<<HTML
  <script type="text/javascript">
$(document).ready(function() { // when document has loaded
	var i = $('input').size() + 1; // check how many input exists on the document and add 1 for the add command to work
	$('a#add').click(function() { // when you click the add link
		$('<p><input type="text" name="answers[]" class="input" /></p>').animate({ opacity: "show" }, "fast", function(){(\$('#inputs input:last').focus())}).appendTo('#inputs'); // append (add) a new input to the document.
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
	$inputs = array( "Form"                               => array( "action" => "", "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "reg" ),
	                 "{$lang['admin']['poll_question']}:" => array( "type" => "text", "name" => "question"),
	                 "{$lang['admin']['poll_votecan']}:"  => array( "type" => "select", "name" => "only_guests", "value" => array( 0 => $lang['admin']['poll_all'], 1 => $lang['admin']['poll_membs'] )),
	                 "{$lang['admin']['poll_type']}:"     => array( "type" => "select", "name" => "type", "value" => array( 0 => 'checkbox', 1 => 'radio' )),
	                 "{$lang['admin']['poll_active']}:"   => array( "type" => "select", "name" => "shown", "value" => array( 1 => $lang['admin']['yes'], 0 => $lang['admin']['no'] )),
	                 "{$lang['admin']['poll_answers']}:"  => array( "type" => "string", "value" => "<a href=\"#\" onclick=\"return false;\" id=\"add\"><img src=\"" . ROOT . "images/icons/plus.png\" alt=\"[+]\" /></a> <a href=\"#\" onclick=\"return false;\" id=\"remove\"><img src=\"" . ROOT . "images/icons/minus.png\" alt=\"[-]\" /></a><div id=\"inputs\"><p><input type=\"text\" name=\"answers[]\" class=\"input\" /></p></div>"),
	                 " "                                  => array( "type" => "submit", "value" => $lang['admin']['poll_create'] )
	);

	$formClass = new Form($inputs);
	lentele($lang['admin']['poll_create'], $formClass->form());

} elseif ( $_GET['v'] == 2 ) {
	if ( isset( $_GET['e'] ) ) {
		if ( isset( $_POST['update'] ) ) {
			mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "poll_questions` SET `question`=" . escape( $_POST['question'] ) . ", `radio`=" . escape( (int)$_POST['type'] ) . ", `shown`=" . escape( (int)$_POST['shown'] ) . ", `only_guests`=" . escape( (int)$_POST['only_guests'] ) . " WHERE `id`=" . escape( $_GET['e'] ) . "" );
		}

		$quest  = mysql_query1( "SELECT * FROM  `" . LENTELES_PRIESAGA . "poll_questions` WHERE `id`=" . escape( $_GET['e'] ) . " LIMIT 1", 3600 );
		$inputs = array( 
			"Form"	=> array( "action" => "", "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "reg" ),
			"{$lang['admin']['poll_question']}:" => array( "type" => "text", "name" => "question", "value" => input( $quest['question'] )),
			"{$lang['admin']['poll_votecan']}:"  => array( "type" => "select", "selected" => input( $quest['only_guests'] ), "name" => "only_guests", "value" => array( 0 => $lang['admin']['poll_all'], 1 => $lang['admin']['poll_membs'] )),
			"{$lang['admin']['poll_type']}:"     => array( "type" => "select", "name" => "type", "value" => array( 0 => 'checkbox', 1 => 'radio' ), "selected" => input( $quest['radio'] ) ),
			"{$lang['admin']['poll_active']}:"   => array( "type" => "select", "name" => "shown", "value" => array( 1 => $lang['admin']['yes'], 0 => $lang['admin']['no'] ), "selected" => input( $quest['shown'] ) ),
			" "                                  => array( "type" => "submit", "name" => "update", "value" => $lang['admin']['edit'] )
		);

		$formClass = new Form($inputs);
		lentele($lang['admin']['poll_edit'], $formClass->form());
	}

	$viso  = kiek( "poll_questions", "WHERE `lang` = " . escape( lang() ) . "" );
	$quest = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "poll_questions` WHERE `lang` = " . escape( lang() ) . " ORDER BY `id` DESC LIMIT {$p},{$limit}", 3600 );
	foreach ( $quest as $row ) {
		$info[] = array(
			$lang['admin']['poll_active_q'] => ( $row['shown'] == 1 ? '<img src="' . ROOT . '/images/icons/status_online.png" alt="" />' : '<img src="' . ROOT . '/images/icons/status_offline.png" alt="" />' ),
			$lang['admin']['poll']          => input( $row['question'] ),
			$lang['system']['edit']         => " <a href='" . url( "?id,{$_GET['id']};a,{$_GET['a']};v,{$_GET['v']};e," . $row['id'] ) . "' title='{$lang['admin']['edit']}'><img src='" . ROOT . "images/icons/pencil.png' border='0'></a> <a href='" . url( "?id,{$_GET['id']};a,{$_GET['a']};t," . $row['id'] ) . "' title='{$lang['admin']['delete']}' onClick=\"return confirm('" . $lang['system']['delete_confirm'] . "')\"><img src='" . ROOT . "images/icons/cross.png' border='0'></a>" );
	}
	if (! empty($info)) {

		$tableClass   = new Table($info);

		lentele($lang['admin']['poll_edit'], $tableClass->render());
		// if list is bigger than limit, then we show list with pagination
		if ( $viso > $limit ) {
			lentele( $lang['system']['pages'], puslapiai( $p, $limit, $viso, 10 ) );
		}

	} else {
		lentele( $lang['admin']['poll_edit'], $lang['admin']['poll_no'] );
	}
}