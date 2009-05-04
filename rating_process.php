<?php

ob_start();
session_start();
/*
Dynamic Star Rating Redux
Developed by Jordan Boesch
www.boedesign.com
Licensed under Creative Commons - http://creativecommons.org/licenses/by-nc-nd/2.5/ca/

Used CSS from komodomedia.com.
*/
header("Cache-Control: no-cache");
header("Pragma: nocache");

include ("priedai/conf.php");
// Cookie settings
$expire = time() + 99999999;
$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false; // make cookies work with localhost

// escape variables
function escape2($val) {

	$val = trim($val);

	if (get_magic_quotes_gpc()) {
		$val = stripslashes($val);
	}

	return mysql_real_escape_string($val);

}

// IF JAVASCRIPT IS ENABLED

if ($_POST) {
	$id = escape2($_POST['id']);
	$rating = (int)$_POST['rating'];
	$psl = $_SESSION['page'];
	if ($rating <= 5 && $rating >= 1) {

		if (@mysql_fetch_assoc(mysql_query1("SELECT id FROM " . LENTELES_PRIESAGA . "ratings WHERE IP = '" . $_SERVER['REMOTE_ADDR'] . "' AND rating_id = '$id' AND psl = '$psl'")) || isset($_COOKIE['has_voted_' . $id])) {

			echo 'already_voted';

		} else {


			setcookie('has_voted_' . $id, $id, $expire, '/', $domain, false);
			mysql_query1("INSERT INTO " . LENTELES_PRIESAGA . "ratings (rating_id,rating_num,IP,psl) VALUES ('$id','$rating','" . $_SERVER['REMOTE_ADDR'] . "','$psl')") or die(mysql_error());

			$total = 0;
			$rows = 0;

			$sel = mysql_query1("SELECT rating_num FROM " . LENTELES_PRIESAGA . "ratings WHERE rating_id = '$id' AND psl = '$psl'");
			while ($data = mysql_fetch_assoc($sel)) {

				$total = $total + $data['rating_num'];
				$rows++;
			}

			$perc = ($total / $rows) * 20;

			echo round($perc, 2);
			//echo round($perc/5)*5;

		}

	}

}

// IF JAVASCRIPT IS DISABLED

if ($_GET) {

	$id = escape2($_GET['id']);
	$rating = (int)$_GET['rating'];
	$psl = $_GET['psl'];
	// If you want people to be able to vote more than once, comment the entire if/else block block and uncomment the code below it.

	if ($rating <= 5 && $rating >= 1) {

		if (@mysql_fetch_assoc(mysql_query1("SELECT id FROM " . LENTELES_PRIESAGA . "ratings WHERE IP = '" . $_SERVER['REMOTE_ADDR'] . "' AND rating_id = '$id' AND psl = '$psl'")) || isset($_COOKIE['has_voted_' . $id])) {

			echo 'already_voted';

		} else {

			setcookie('has_voted_' . $id, $id, $expire, '/', $domain, false);
			mysql_query1("INSERT INTO " . LENTELES_PRIESAGA . "ratings (rating_id,rating_num,IP,psl) VALUES ('$id','$rating','" . $_SERVER['REMOTE_ADDR'] . "','$psl')") or die(mysql_error());

		}

		header("Location:" . $_SERVER['HTTP_REFERER'] . "");
		die;

	} else {

		echo 'You cannot rate this more than 5 or less than 1 <a href="' . $_SERVER['HTTP_REFERER'] . '">back</a>';

	}


}

?>
