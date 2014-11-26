<?php
/**
 * @Projektas : MightMedia TVS
 * @Puslapis  : www.coders.lt
 * @$Author: FDisk $
 * @copyright CodeRS Â©2008
 * @license   GNU General Public License v2
 * @$Revision: 917 $
 * @$Date: 2012-09-05 22:59:15 +0300 (Wed, 05 Sep 2012) $
 * */

include_once( 'priedai/conf.php' );
class rating
{

	public $average = 0;
	public $votes;
	public $status;
	public $page;
	public $id;

	function __construct( $page, $id ) {

		$this->page = $page;
		$this->id   = $id;
		$sel        = mysql_query1( "SELECT `rating_num` FROM `" . LENTELES_PRIESAGA . "ratings` WHERE `rating_id` = " . escape( $this->id ) . " AND `psl` = " . escape( $this->page ), 360 );
		if ( count( mysql_query1( "SELECT `id` FROM `" . LENTELES_PRIESAGA . "ratings` WHERE `IP` = " . escape( getip() ) . " AND `rating_id` = " . escape( $id ) . " AND `psl` = " . escape( $page ) ) ) == 0 ) {
			$this->status = TRUE;
		} else {
			$this->status = FALSE;
		}

		if ( sizeof( $sel ) > 0 ) {
			$total = 0;
			$rows  = 0;
			foreach ( $sel as $data ) {
				$total = $total + $data['rating_num'];
				$rows++;
			}
			$this->average = round( ( ( $total * 20 ) / $rows ), 0 );
			$this->votes   = $rows;
		} else {
			$this->votes   = 0;
			$this->average = 0;
		}
	}

	function set_score( $rating, $ip, $page, $id ) {

		if ( $this->status ) {
			mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "ratings` (`rating_id`,`rating_num`,`IP`,`psl`) VALUES (" . escape( $id ) . "," . escape( $rating ) . "," . escape( getip() ) . "," . escape( $page ) . ")" ) or die( mysqli_error($prisijungimas_prie_mysql) );
			delete_cache( "SELECT `rating_num` FROM `" . LENTELES_PRIESAGA . "ratings` WHERE `rating_id` = " . escape( $id ) . " AND `psl` = " . escape( $page ) );
			$this->status = '';
		} else {
			$this->status = '';
		}
		$sel = mysql_query1( "SELECT `rating_num` FROM `" . LENTELES_PRIESAGA . "ratings` WHERE `rating_id` = " . escape( $id ) . " AND `psl` = " . escape( $page ), 360 );
		if ( sizeof( $sel ) > 0 ) {
			$total = 0;
			$rows  = 0;
			foreach ( $sel as $data ) {
				$total = $total + $data['rating_num'];
				$rows++;
			}
			$this->average = round( ( ( $total * 20 ) / $rows ), 0 );
		}
	}
}

function rating_form( $page, $id, $allow = TRUE ) {

	global $lang, $conf;
	if ( $conf['galbalsuot'] == 1 ) {
		$ip = getip();
		if ( !isset( $page ) && isset( $_GET['page'] ) && !isset( $id ) && isset( $_GET['id'] ) ) {
			$page = $_GET['page'];
			$id   = $_GET['id'];
		}
		$return = '';
		$rating = new rating( $page, $id );
		$status = "
          <a title='1' class='score1' href='?score=1&amp;page={$page}&amp;user={$ip}&amp;id={$id}'>1</a>
          <a title='2' class='score2' href='?score=2&amp;page={$page}&amp;user={$ip}&amp;id={$id}'>2</a>
          <a title='3' class='score3' href='?score=3&amp;page={$page}&amp;user={$ip}&amp;id={$id}'>3</a>
          <a title='4' class='score4' href='?score=4&amp;page={$page}&amp;user={$ip}&amp;id={$id}'>4</a>
          <a title='5' class='score5' href='?score=5&amp;page={$page}&amp;user={$ip}&amp;id={$id}'>5</a>
       ";
		if ( isset( $_GET['score'] ) && $allow == TRUE ) {
			$score = $_GET['score'];
			if ( is_numeric( $score ) && $score <= 5 && $score >= 1 && ( $page == $_GET['page'] ) && isset( $_GET["user"] ) && $ip == $_GET["user"] ) {
				$page = $_GET['page'];
				$rating->set_score( $score, $ip, $page, $id );
				$status = $rating->status;
			}
		}
		if ( $allow == FALSE || !$rating->status ) {
			$status = '';
		}
		if ( !isset( $_GET['update'] ) ) {
			$return .= '<div class="rating_wrapper">';
		}

		$return .= '<div class="sp_rating" id="sp_rating_' . $id . '">
      <div class="rating"></div>
		  <div class="base">
			  <div class="status">
				  <div class="score" id="score_' . $id . '">
				  ' . $status . '
				  <div class="average" title="' . $rating->votes . ' ' . $lang['poll']['votes'] . '" style="width:' . $rating->average . '%">' . $rating->average . '</div>
			  </div>
		  </div>
	  </div>

    </div><script type="text/javascript">init_rating(\'' . $id . '\')</script>';
		if ( !isset( $_GET['update'] ) ) {
			$return .= '</div>';
		}
	} else {
		$return = '';
	}

	return $return;
}

if ( isset( $_GET['reload'] ) && isset( $_GET['page'] ) && isset( $_GET['id'] ) ) {
	echo rating_form( $_GET['page'], $_GET['id'] );
}
?>