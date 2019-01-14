<?php

/**
 * @Projektas : MightMedia TVS
 * @Puslapis  : www.coders.lt
 * @$Author: zlotas $
 * @copyright CodeRS ©2008
 * @license   GNU General Public License v2
 * @$Revision: 945 $
 * @$Date: 2012-09-07 01:00:25 +0300 (Pn, 07 Rgs 2012) $
 **/

if ( !defined( "OK" ) || !ar_admin( basename( __file__ ) ) ) {
	redirect( 'location: http://' . $_SERVER["HTTP_HOST"] );
}
unset( $text, $extra );
if ( count( $_GET ) < 3 ) {
	$_GET['v'] = 7;
}
//Puslapiavimui
if ( isset( $url['p'] ) && isnum( $url['p'] ) && $url['p'] > 0 ) {
	$p = (int)$url['p'];
} else {
	$p = 0;
}
$limit = 15;
//

if(BUTTONS_BLOCK) {
	lentele(getLangText('admin', 'faq'), buttonsMenu(buttons('faq')));
}

if ( empty( $_GET['v'] ) ) {
	$_GET['v'] = 0;
}

//trinimas
if (isset($_POST['faq_delete'])) {
	foreach ($_POST['faq_delete'] as $a => $b) {
		$delete[] = escape( $b );
	}

	$sqlDeleteFew = "DELETE FROM `" . LENTELES_PRIESAGA . "duk` WHERE `id` IN(" . implode(', ', $delete) . ")";
	
	if(mysql_query1($sqlDeleteFew)) {
		redirect(
			url("?id," . $url['id'] . ";a," . $url['a'] . ';v,4'),
			"header",
			[
				'type'		=> 'success',
				'message' 	=> getLangText('admin', 'posts_deleted')
			]
		);
	} else {
		redirect(
			url("?id," . $url['id'] . ";a," . $url['a'] . ';v,4'),
			"header",
			[
				'type'		=> 'error',
				'message' 	=> getLangText('system', 'error')
			]
		);
	}
}

if ( isset( $_GET['t'] ) ) {
	$delId = (int)$url['t'];
	$deleteQuery = "DELETE FROM `" . LENTELES_PRIESAGA . "duk` WHERE id=" . escape($delId) . " LIMIT 1";

	if (mysql_query1($deleteQuery)) {
		redirect(
			url("?id," . $url['id'] . ";a," . $url['a']),
			"header",
			[
				'type'		=> 'success',
				'message' 	=> getLangText('admin', 'faq_deleted')
			]
		);
	} else {
		notifyMsg(
			[
				'type'		=> 'error',
				'message' 	=>  mysqli_error($prisijungimas_prie_mysql)
			]
		);
	}

} elseif ( ((isset($_POST['edit_new']) && isNum($_POST['edit_new']) && $_POST['edit_new'] > 0)) || isset( $url['h'] ) ) {
	if (isset($url['h'])) {
		$editId = (int)$url['h'];
	} elseif (isset($_POST['edit_new'])) {
		$editId = (int)$_POST['edit_new'];
	}

	$selectQuery = "SELECT * FROM `" . LENTELES_PRIESAGA . "duk` WHERE `id` = " . escape($editId) . " LIMIT 1";
	$faqItem = mysql_query1($selectQuery);


} elseif ( isset( $_POST['action'] ) && isset( $_POST['Klausimas'] ) && $_POST['action'] == getLangText('admin', 'edit') ) {
	$klausimas = $_POST['Klausimas'];
	$atsakymas = $_POST['Atsakymas'];
	$order     = (int)$_POST['Order'];
	$id        = ceil( (int)$_POST['eid'] );

	$updateQuery = "UPDATE `" . LENTELES_PRIESAGA . "duk` SET
	`atsakymas` = " . escape($atsakymas) . ",
	`klausimas` = " . escape($klausimas) . ",
	`order` = " . escape($order) . " WHERE `id`=" . $id . ";";

	if (mysql_query1($updateQuery)) {
		redirect(
			url("?id," . $url['id'] . ";a," . $url['a']),
			"header",
			[
				'type'		=> 'success',
				'message' 	=> getLangText('admin', 'faq_updated')
			]
		);
	} else {
		notifyMsg(
			[
				'type'		=> 'error',
				'message' 	=>  mysqli_error($prisijungimas_prie_mysql)
			]
		);
	}
} elseif ( isset( $_POST['action'] ) && $_POST['action'] == getLangText('faq', 'new') ) {
	$question 	= $_POST['Klausimas'];
	$answer 	= $_POST['Atsakymas'];
	$order     	= (int)$_POST['Order'];

	$insertQuery = "INSERT INTO `" . LENTELES_PRIESAGA . "duk` (`klausimas`,`atsakymas`,`order`,`lang`) VALUES (
		" . escape($question) . ",
		" . escape($answer) . ",
		" . escape($order) . ",
		" . escape(lang()) . ");";

	if (mysql_query1($insertQuery)) {
		redirect(
			url("?id," . $url['id'] . ";a," . $url['a']),
			"header",
			[
				'type'		=> 'success',
				'message' 	=> getLangText('admin', 'faq_created')
			]
		);
	} else {
		notifyMsg(
			[
				'type'		=> 'error',
				'message' 	=> mysqli_error($prisijungimas_prie_mysql)
			]
		);
	}
}

if ( $_GET['v'] == 4 ) {
	$viso 	= kiek( "duk", " WHERE`lang` = " . escape(lang()));
	$selectQuery = "SELECT * FROM `" . LENTELES_PRIESAGA . "duk` 
	WHERE `lang` = " . escape(lang()) . " " . (! empty($_POST['order'] ) ? " AND `order` = " . escape($_POST['order']) . ' ' : "" ) . (! empty($_POST['klausimas'] ) ? " AND `klausimas` LIKE " . escape("%" . $_POST['klausimas'] . "%") . ' ' : "" ) . (! empty($_POST['atsakymas'] ) ? " AND `klausimas` LIKE " . escape("%" . $_POST['atsakymas'] . "%") . ' ' : "" ) . "
	ORDER by `order` ASC LIMIT {$p},{$limit}";

	if ($questions = mysql_query1($selectQuery)) {
		//FILTRAVIMAS
		$formData = [
			'order'			=> getLangText('faq', 'order'),
			'klausimas'		=> getLangText('faq', 'question'),
			'atsakymas'		=> getLangText('faq', 'answer'),
		];

		$info[] = tableFilter($formData, $_POST, '#faq');
		//FILTRAVIMAS - END
		foreach ($questions as $question) {

			$info[] = [  
				"#"							=> '<input type="checkbox" value="' . $question['id'] . '" name="faq_delete[]" class="filled-in" id="faq-delete-' . $question['id'] . '"><label for="faq-delete-' . $question['id'] . '"></label>',
				getLangText('faq', 'order')		=> $question['order'],
				getLangText('faq', 'question')	=> trimlink($question['klausimas'], 55),
				getLangText('faq', 'answer')		=> trimlink(strip_tags($question['atsakymas']), 55),
				getLangText('admin', 'action')	=> "<a href='" . url("?id,{$url['id']};a,{$url['a']};t," . $question['id']) . "' title = '" . getLangText('admin',  'delete') . "'><img src='" . ROOT . "images/icons/cross.png'></a> 
				<a href='" . url( "?id,{$url['id']};a,{$url['a']};h," . $question['id'] ) . "' title = '" . getLangText('admin',  'edit') . "'><img src='" . ROOT . "images/icons/pencil.png'></a>"
			];
		}
		
		$tableClass = new Table($info);
		$content = '<form id="faq" method="post">' . $tableClass->render() . '<button type="submit" class="btn bg-red waves-effect">' . getLangText('system', 'delete') . '</button></form>';
		lentele(getLangText('faq', 'questions'), $content);
		
		unset($info);
		// if list is bigger than limit, then we show list with pagination
		if ( $viso > $limit ) {
			lentele( getLangText('system', 'pages'), pages( $p, $limit, $viso, 10 ) );
		}
	} else {
		notifyMsg(
			[
				'type'		=> 'error',
				'message' 	=> getLangText('system', 'no_items')
			]
		);
	}

} elseif ($_GET['v'] == 7 || isset( $url['h'] ) ) {
	$faqForm = [
		"Form"						=> [
			"action"  => url("?id," . $url['id'] . ";a," . $url['a']),
			"method"  => "post",
			"name"    => "reg"
		],

		getLangText('faq', 'question')	=> [
			"type"  => "text",
			"value" => input((isset($faqItem)) ? $faqItem['klausimas'] : ''),
			"name"  => "Klausimas"
		],

		getLangText('faq', 'answer')		=> [
			"type"  => "string",
			"value" => editor('jquery', 'mini', 'Atsakymas', (isset($faqItem ) ? $faqItem['atsakymas'] : ''))
		],

		getLangText('faq', 'order')		=> [
			"type"  => "text",
			"value" => (isset($faqItem ) ? (int)$faqItem['order'] : ''),
			"name"  => "Order"
		],

		"id"						=> [
			"type"  => "hidden",
			"value" => (isset($faqItem['id']) ? input($faqItem['id']) : ''),
			"name"  => "eid",
			"id"    => "id"
		],

		""							=> [
			"type"  	=> "submit",
			"name"  	=> "action",
			'form_line'	=> 'form-not-line',
			"value" 	=> (isset($faqItem)) ? getLangText('admin', 'edit') : getLangText('faq', 'new')
		]
	];

	// Verčiam msayvą į formą
	$formClass = new Form($faqForm);
	lentele(getLangText('faq', 'edit'), $formClass->render());
}