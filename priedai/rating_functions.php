<?php

//include("includes/rating_config.php");
/*
Dynamic Star Rating Redux
Developed by Jordan Boesch
www.boedesign.com
Licensed under Creative Commons - http://creativecommons.org/licenses/by-nc-nd/2.5/ca/

Used CSS from komodomedia.com.
*/

$_SESSION['page'] = $page;
function getRating($id) {
	global $page;
	$total = 0;
	$rows = 0;

	$sel = mysql_query1("SELECT rating_num FROM " . LENTELES_PRIESAGA . "ratings WHERE rating_id = '$id'AND psl = '$page'");
	if (mysql_num_rows($sel) > 0) {

		while ($data = mysql_fetch_assoc($sel)) {

			$total = $total + $data['rating_num'];
			$rows++;
		}

		$perc = ($total / $rows) * 20;

		//$newPerc = round($perc/5)*5;
		//return $newPerc.'%';

		$newPerc = round($perc, 2);
		return $newPerc . '%';

	} else {

		return '0%';

	}
}

function outOfFive($id) {
	global $page;
	$total = 0;
	$rows = 0;

	$sel = mysql_query1("SELECT rating_num FROM " . LENTELES_PRIESAGA . "ratings WHERE rating_id = '$id' AND psl = '$page'");
	if (mysql_num_rows($sel) > 0) {

		while ($data = mysql_fetch_assoc($sel)) {

			$total = $total + $data['rating_num'];
			$rows++;
		}

		$perc = ($total / $rows);

		return round($perc, 2);
		//return round(($perc*2), 0)/2; // 3.5

	} else {

		return '0';

	}


}

function getVotes($id) {
	global $page;
	$sel = mysql_query1("SELECT rating_num FROM " . LENTELES_PRIESAGA . "ratings WHERE rating_id = '$id' AND psl = '$page'");
	$rows = mysql_num_rows($sel);
	if ($rows == 0) {
		$votes = '0 Balsų';
	} else
		if ($rows == 1) {
			$votes = '1 Balsas';
		} else {
			$votes = $rows . ' Balsai';
		}
		return $votes;

}

function pullRating($id, $show5 = false, $showPerc = false, $showVotes = false, $static = null) {
	global $page;
	// Check if they have already voted...
	$text = '';

	$sel = mysql_query1("SELECT id FROM " . LENTELES_PRIESAGA . "ratings WHERE IP = '" . $_SERVER['REMOTE_ADDR'] . "' AND rating_id = '$id' AND psl = '$page'");
	if (mysql_num_rows($sel) > 0 || $static == 'novote' || isset($_COOKIE['has_voted_' . $id])) {


		if ($show5 || $showPerc || $showVotes) {

			$text .= '<div class="rated_text">';

		}

		if ($show5) {
			$text .= 'Reitingas <span id="outOfFive_' . $id . '" class="out5Class">' . outOfFive($id) . '</span>/5';
		}
		if ($showPerc) {
			$text .= ' (<span id="percentage_' . $id . '" class="percentClass">' . getRating($id) . '</span>)';
		}
		if ($showVotes) {
			$text .= ' (<span id="showvotes_' . $id . '" class="votesClass">' . getVotes($id) . '</span>)';
		}

		if ($show5 || $showPerc || $showVotes) {

			$text .= '</div>';

		}


		return $text . '
			<ul class="star-rating2" id="rater_' . $id . '">
				<li class="current-rating" style="width:' . getRating($id) . ';" id="ul_' . $id . '"></li>
				<li><a onclick="return false;" href="#" title="1 žvaigždė iš 5" class="one-star" >1</a></li>
				<li><a onclick="return false;" href="#" title="2 žvaigždės iš 5" class="two-stars">2</a></li>
				<li><a onclick="return false;" href="#" title="3 žvaigždės iš 5" class="three-stars">3</a></li>
				<li><a onclick="return false;" href="#" title="4 žvaigždės iš 5" class="four-stars">4</a></li>
				<li><a onclick="return false;" href="#" title="5 žvaigždės iš 5" class="five-stars">5</a></li>
			</ul>
			<div id="loading_' . $id . '"></div>';


	} else {

		if ($show5 || $showPerc || $showVotes) {

			$text .= '<div class="rated_text">';

		}
		if ($show5) {
			$show5bool = 'true';
			$text .= 'Reitingas <span id="outOfFive_' . $id . '" class="out5Class">' . outOfFive($id) . '</span>/5';
		} else {
			$show5bool = 'false';
		}
		if ($showPerc) {
			$showPercbool = 'true';
			$text .= ' (<span id="percentage_' . $id . '" class="percentClass">' . getRating($id) . '</span>)';
		} else {
			$showPercbool = 'false';
		}
		if ($showVotes) {
			$showVotesbool = 'true';
			$text .= ' (<span id="showvotes_' . $id . '" class="votesClass">' . getVotes($id) . '</span>)';
		} else {
			$showVotesbool = 'false';
		}

		if ($show5 || $showPerc || $showVotes) {

			$text .= '</div>';

		}

		return $text . '
			<ul class="star-rating" id="rater_' . $id . '">
				<li class="current-rating" style="width:' . getRating($id) . ';" id="ul_' . $id . '"></li>
				<li><a onclick="rate(\'1\',\'' . $id . '\',' . $show5bool . ',' . $showPercbool . ',' . $showVotesbool . '); return false;" href="rating_process.php?id=' . $id . '&rating=1&psl=' . $page . '" title="1 žvaigždė iš 5" class="one-star" >1</a></li>
				<li><a onclick="rate(\'2\',\'' . $id . '\',' . $show5bool . ',' . $showPercbool . ',' . $showVotesbool . '); return false;" href="rating_process.php?id=' . $id . '&rating=2&psl=' . $page . '" title="2 žvaigždės iš 5" class="two-stars">2</a></li>
				<li><a onclick="rate(' . $page . ',\'3\',\'' . $id . '\',' . $show5bool . ',' . $showPercbool . ',' . $showVotesbool . '); return false;" href="rating_process.php?id=' . $id . '&rating=3&psl=' . $page . '" title="3 žvaigždės iš 5" class="three-stars">3</a></li>
				<li><a onclick="rate(\'4\',\'' . $id . '\',' . $show5bool . ',' . $showPercbool . ',' . $showVotesbool . '); return false;" href="rating_process.php?id=' . $id . '&rating=4&psl=' . $page . '" title="4 žvaigždės iš 5" class="four-stars">4</a></li>
				<li><a onclick="rate(\'5\',\'' . $id . '\',' . $show5bool . ',' . $showPercbool . ',' . $showVotesbool . '); return false;" href="rating_process.php?id=' . $id . '&rating=5&psl=' . $page . '" title="5 žvaigždės iš 5" class="five-stars">5</a></li>
			</ul>
			<div id="loading_' . $id . '"></div>';

	}
}

// Added in version 1.5
function getTopRated($limit, $table, $idfield, $namefield) {
	global $page;
	$result = '';

	$sql = "SELECT " . LENTELES_PRIESAGA . "ratings.rating_id," . $table . "." . $namefield . " as thenamefield,ROUND(AVG(" . LENTELES_PRIESAGA . "ratings.rating_num),2) as rating 
			FROM " . LENTELES_PRIESAGA . "ratings," . $table . " WHERE " . $table . "." . $idfield . " = " . LENTELES_PRIESAGA . "ratings.rating_id GROUP BY rating_id 
			ORDER BY rating DESC LIMIT " . $limit . "";

	$sel = mysql_query1($sql);

	$result .= '<ul class="topRatedList">' . "\n";

	while ($data = @mysql_fetch_assoc($sel)) {
		$result .= '<li>' . $data['thenamefield'] . ' (' . $data['rating'] . ')</li>' . "\n";
	}

	$result .= '</ul>' . "\n";

	return $result;

}

?>