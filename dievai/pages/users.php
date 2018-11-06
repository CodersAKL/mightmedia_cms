<?php
/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: FDisk & ire - zlotas - Aivaras Čenkus $
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision: 362 $
 * @$Date: 2009-11-24 16:56:25 +0200 (Tue, 24 Nov 2009) $
 * */
if ( !defined( "LEVEL" ) || LEVEL > 1 || !defined( "OK" ) ) {
	redirect( 'location: http://' . $_SERVER["HTTP_HOST"] );
}

if(BUTTONS_BLOCK) {
	lentele($lang['admin']['users'], buttonsMenu(buttons('users')));
}

if ( !isset( $_GET['v'] ) ) {
	$_GET['v'] = 1;
}
//Puslapiavimui
if ( isset( $url['p'] ) && isnum( $url['p'] ) && $url['p'] > 0 ) {
	$p = (int)$url['p'];
} else {
	$p = 0;
}

$limit = 15;

include_once ( ROOT . "priedai/kategorijos.php" );
kategorija( "vartotojai", TRUE );
//trinimas
if ( isset( $_POST['users_delete'] ) ) {
	foreach ( $_POST['userss_delete'] as $a=> $b ) {
		$trinti[] = escape( $b );
	}

	mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "users` WHERE `id` IN(" . implode( ', ', $trinti ) . ") AND `levelis` > 1" );
	
	redirect(
		url("?id," . $url['id'] . ";a," . $url['a']),
		"header",
		[
			'type'		=> 'success',
			'message' 	=> $lang['admin']['post_deleted']
		]
	);
}

if ( isset( $url['d'] ) && $url['d'] != "" && $url['d'] != 0 ) {
	$del = mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "users` WHERE id=" . escape( (int)$url['d'] ) . " AND `levelis` > 1" );
	redirect(
		url("?id," . $url['id'] . ";a," . $url['a']),
		"header",
		[
			'type'		=> 'success',
			'message' 	=> $lang['admin']['post_deleted']
		]
	);
}

if ( isset( $_POST['action'] ) && $_POST['action'] == $lang['admin']['save'] && $_POST['id'] > 0 ) {
	$info = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "users` WHERE id='" . $_POST['id'] . "'" . ( $_SESSION[SLAPTAS]['id'] == 1 ? '' : 'AND `levelis` > 1' ) . " LIMIT 1" );

	if ( !empty( $_POST['tsk'] ) ) {
		$tsk = (int)$_POST['tsk'];
	} else {
		$tsk = $info['taskai'];
	}
	if ( !empty( $_POST['lvl'] ) /* && $_POST['lvl'] < 31*/ ) {
		$lvl = (int)$_POST['lvl'];
	} else {
		$lvl = $info['levelis'];
	}
	if ( !empty( $_POST['slapt'] ) ) {
		$slapt = koduoju( $_POST['slapt'] );
	} else {
		$slapt = $info['pass'];
	}
	if ( !empty( $_POST['email'] ) ) {
		$mail = $_POST['email'];
	} else {
		$mail = $info['email'];
	}

	$resut = mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "users` SET `taskai`=" . escape( $tsk ) . " , `levelis`=" . escape( $lvl ) . " , `pass`=" . escape( $slapt ) . " , `email`=" . escape( $mail ) . " WHERE `id`=" . escape( (int)$_POST['id'] ) . " " . ( $_SESSION[SLAPTAS]['id'] == 1 ? '' : 'AND `levelis` > 1' ) );
	if ( $resut ) {
		redirect(
			url("?id," . $url['id'] . ";a," . $url['a']),
			"header",
			[
				'type'		=> 'success',
				'message' 	=> $lang['admin']['user_updated']
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
	
	unset( $result, $info );
}

//Jei redaguojam
if ( isset( $url['r'] ) && $url['r'] != "" && $url['r'] != 0 ) {

	$info = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "users` WHERE id='" . $url['r'] . "'" . ( $_SESSION[SLAPTAS]['id'] == 1 ? '' : ' AND `levelis` > 1' ) . " LIMIT 1" );
	if ( $info ) {
		$lygiai2 = array_keys( $conf['level'] );
		
		foreach ( $lygiai2 as $key ) {
			$lygiai[$key] = $conf['level'][$key]['pavadinimas'];
		}

		$infoIcon = infoIcon($lang['system']['warning'] . ' ' . $lang['admin']['user_passinfo']);

		$userEditForm = [
			'Form'											=> [
				'action' 	=> url( "?id,{$_GET['id']};a,{$_GET['a']}" ),
				"method" 	=> "post",
				"name" 		=> "reg",
				'extra' 	=> "onSubmit=\"return checkMail('change_contacts','email')\""
			],

			$lang['admin']['user_points']					=> [
				'type' 	=> 'text',
				'name' 	=> 'tsk',
				'extra' => "onkeyup=\"javascript:this.value=this.value.replace(/[^0-9]/g, '');\"",
				'value' => ( isset( $info['taskai'] ) ? input( $info['taskai'] ) : "" ),
			],

			$lang['admin']['user_level']					=> [
				"type" 		=> "select",
				"value" 	=> $lygiai,
				"name" 		=> "lvl",
				"selected" 	=> ( isset( $info['levelis'] ) ? (int)$info['levelis'] : '' )
			],

			$lang['admin']['user_pass'] . ' ' . $infoIcon	=> [
				'type' => 'password',
				'name' => 'slapt'
			],

			$lang['admin']['user_email']   					=> [
				'type' 	=> 'text',
				'value' => ( isset( $info['email'] ) ? input( $info['email'] ) : "" )
			],

			$url['r']										=> [
				'type' 	=> 'hidden',
				"value" =>  $url['r']
			],

			""                             					=> [
				'type' 		=> 'submit',
				'name' 		=> 'action',
				'form_line'	=> 'form-not-line',
				"value" 	=> $lang['admin']['save']
			]
		];
		
		$formClass = new Form($userEditForm);
		$title= '<strong>' . input( $info['nick'] ) . '</strong>';
		
		lentele($title, $formClass->form() . "<br /><small>*{$lang['admin']['user_canteditadmin']}</small>", $lang['admin']['user_details']);
		
		unset( $info, $userEditForm );
	} else {
		notifyMsg(
			[
				'type'		=> 'error',
				'message' 	=> $lang['admin']['user_canteditadmin']
			]
		);
	}
}

if ( isset( $_GET['v'] ) && $_GET['v'] == 1 ) {
	//Sarašas visų lygių
	$lygiai = array_keys( $conf['level'] );
	$grupe  = "";
	//Užsukam ciklą tiek kartų kiek yra lygių
	foreach ( $lygiai as $key ) {
		$grupe .= "<img src='" . ROOT . "images/icons/" . $conf['level'][$key]['pav'] . "'> <a href='" . url( "?id," . $_GET['id'] . ";a," . $_GET['a'] . ";v," . $_GET['v'] . ";k," . $key ) . "'>" . $conf['level'][$key]['pavadinimas'] . "</a><br>";
	}

	lentele( $lang['admin']['user_groups'], $grupe );

	if ( isset( $_GET['k'] ) ) {//vartotoju sarasas pagal esamą levelį
	
		//FILTER - query
		$sqlQuery = "SELECT * FROM `" . LENTELES_PRIESAGA . "users` WHERE levelis=" . escape( (int)$_GET['k'] ) . " " . ( isset( $_POST['nick'] ) ? "AND (`nick` LIKE " . escape( "%" . $_POST['nick'] . "%" ) . " " . ( !empty( $_POST['ip'] ) ? " AND `ip` LIKE " . escape( sprintf( "%u", ip2long( $_POST['ip'] ) ) ) . "" : "" ) . "" . ( !empty( $_POST['email'] ) ? " AND `email` LIKE " . escape( "%" . $_POST['email'] . "%" ) . "" : "" ) . ")" : "" ) . " ORDER BY id DESC LIMIT {$p},{$limit}";
		
		if ($sql = mysql_query1($sqlQuery)) {

			//FILTER - begin
			$formData = [
				'nick'	=> $lang['admin']['user_name'],
				'ip '	=> 'IP ' . infoIcon($lang['system']['warning'] . ' ' . $lang['admin']['user_ip_filter']),
				'email'	=> $lang['admin']['user_email'],
			];
			
			$info2[] = tableFilter($formData, $_POST, '#usersch');

			//FILTER - end
			$i    = 0;
			$viso = kiek( "users", "WHERE levelis=" . escape( $_GET['k'] ) );

			foreach ( $sql as $row2 ) {
				$i++;
				$info2[] = array(
					"#"                           => '<input type="checkbox" value="' . $row2['id'] . '" name="users_delete[]" class="filled-in" id="users-delete-' . $row2['id'] . '"><label for="users-delete-' . $row2['id'] . '"></label>',
				     $lang['admin']['user_name']  => user( $row2['nick'], $row2['id'], $row2['levelis'] ),
				     "IP"                         => $row2['ip'],
				     $lang['admin']['user_email'] => "" . $row2['email'] . "",
				     $lang['admin']['action']     => "<a href='" . url( "?id," . $_GET['id'] . ";a," . $_GET['a'] . ";r," . $row2['id'] ) . "'title='{$lang['admin']['edit']}'><img src='" . ROOT . "images/icons/pencil.png' border='0' class='middle' /></a> <a href='" . url( "d," . $row2['id'] ) . "' onClick=\"return confirm('" . $lang['system']['delete_confirm'] . "')\" title='{$lang['admin']['delete']}'><img src='" . ROOT . "images/icons/cross.png' border='0' class='middle' /></a><a href='" . url( "?id," . $_GET['id'] . ";a," . getAdminPagesbyId('bans') . ";b,1;ip," . $row2['ip'] ) . "' title='{$lang['admin']['badip']}'><img src='" . ROOT . "images/icons/delete.png' border='0' class='middle' /></a>"
				);
			}

			$title = $conf['level'][$_GET['k']]['pavadinimas'] . " ({$viso})";
			$tableClass = new Table($info2);
			$content = "<form id=\"usersch\" method=\"post\">" . $tableClass->render() . '<button type="submit" class="btn bg-red waves-effect">' . $lang['system']['delete'] . '</button></form>';
			lentele($title, $content);
			// if list is bigger than limit, then we show list with pagination
			if ($viso > $limit) {
				lentele( $lang['system']['pages'], puslapiai( $p, $limit, $viso, 10 ) );
			}

			unset($info2, $i);
		}
	}
}
if ( isset( $_GET['v'] ) && $_GET['v'] == 4 ) {
	$text = "<form name='rasti' method='post' id='rasti' action=''>{$lang['admin']['user_name']}: <input type='text' name='vardas'> <input name='rasti' type='submit' value='{$lang['admin']['user_find']}'></form>";
	
	lentele($lang['admin']['user_find'], $text);
	
	if ( isset( $_POST['rasti'] ) && isset( $_POST['vardas'] ) ) {
		$resultas = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "users` WHERE nick LIKE " . escape( "%" . $_POST['vardas'] . "%" ) . "LIMIT 0,100" );
		if (! empty($resultas)) {
			foreach ( $resultas as $row2 ) {
				$info3[] = array(
					$lang['admin']['user_name']      => user( $row2['nick'], $row2['id'], $row2['levelis'] ),
					"IP"                             => $row2['ip'],
					$lang['admin']['user_email']	 => "" . $row2['email'] . "",
					" "                              => "<a href='" . url( "?id," . $_GET['id'] . ";a," . $_GET['a'] . ";r," . $row2['id'] ) . "' title='{$lang['admin']['edit']}'><img src='" . ROOT . "images/icons/pencil.png' border='0' class='middle' /></a> <a href='" . url( "d," . $row2['id'] ) . "' onClick=\"return confirm('" . $lang['admin']['delete'] . "?')\" title='{$lang['admin']['delete']}'><img src='" . ROOT . "images/icons/cross.png' border='0' class='middle' /></a><a href='" . url( "?id," . $_GET['id'] . ";a," . getAdminPagesbyId('bans') . ";b,1;ip," . $row2['ip'] ) . "' title='{$lang['admin']['badip']}'><img src='" . ROOT . "images/icons/delete.png' border='0' class='middle' /></a>"
				);
			}

			$tableClass = new Table($info3);
			lentele($lang['admin']['user_list'], $tableClass->render());
		} else {
			notifyMsg(
				[
					'type'		=> 'error',
					'message' 	=> $lang['admin']['user_notfound']
				]
			);
		}
	}
}
