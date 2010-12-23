<?php
include_once('priedai/conf.php');
class rating {
	public $average = 0;
	public $votes;
	public $status;
	public $page;
	public $id;//private $path;
	function __construct($page, $id) {
		$this->page = $page;
		$this->id = $id;
		$sel = mysql_query1("SELECT `rating_num` FROM `" . LENTELES_PRIESAGA . "ratings` WHERE `rating_id` = ".escape($this->id)." AND `psl` = ".escape($this->page),360);
		if (count(mysql_query1("SELECT `id` FROM `" . LENTELES_PRIESAGA . "ratings` WHERE `IP` = " . escape(getip()) . " AND `rating_id` = ".escape($id)." AND `psl` = ".escape($page))) == 0)
			$this->status = true;
		else
			$this->status = false;

		if (sizeof($sel) > 0) {
			$total = 0;
			$rows = 0;
			foreach ($sel as $data) {
				$total = $total + $data['rating_num'];
				$rows++;
			}
			$this->average = round((($total*20)/$rows), 0);
			$this->votes=$rows;
		}
		else {
			$this->votes=0;
			$this->average=0;
		}
	}
	function set_score($rating, $ip, $page, $id) {
		if ($this->status) {
			mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "ratings` (`rating_id`,`rating_num`,`IP`,`psl`) VALUES (".escape($id).",".escape($rating).",".escape(getip()) . ",".escape($page).")") or die(mysql_error());
			$this->votes++;
			$this->status = '<img src="images/icons/tick_circle.png" alt="yes" />';
		}
		else {
			$this->status = '<img src="images/icons/cross_circle.png" alt="no" />';
		}
		$sel = mysql_query1("SELECT `rating_num` FROM `" . LENTELES_PRIESAGA . "ratings` WHERE `rating_id` = ".escape($id)." AND `psl` = ".escape($page),360);
		if (sizeof($sel) > 0) {
			$total = 0;
			$rows = 0;
			foreach ($sel as $data) {
				$total = $total + $data['rating_num'];
				$rows++;
			}
			$this->average = round((($total*20)/$rows), 0);
		}
	}
}
function rating_form($page, $id, $allow=true) {
	global $lang, $conf;
	if ($conf['galbalsuot'] == 1) {
    $ip = getip();
    if (!isset($page) && isset($_GET['page'])&&!isset($id) && isset($_GET['id'])) {
      $page = $_GET['page'];
      $id = $_GET['id'];
    }
    $return = '';
    $rating = new rating($page, $id);
    $status = "<div class='score' id='score_{$id}'>
          <a class='score1' href='?score=1&amp;page={$page}&amp;user={$ip}&amp;id={$id}'>1</a>
          <a class='score2' href='?score=2&amp;page={$page}&amp;user={$ip}&amp;id={$id}'>2</a>
          <a class='score3' href='?score=3&amp;page={$page}&amp;user={$ip}&amp;id={$id}'>3</a>
          <a class='score4' href='?score=4&amp;page={$page}&amp;user={$ip}&amp;id={$id}'>4</a>
          <a class='score5' href='?score=5&amp;page={$page}&amp;user={$ip}&amp;id={$id}'>5</a>
        </div>
       ";
    if (isset($_GET['score']) && $allow==true) {
      $score = $_GET['score'];
      if (is_numeric($score) && $score <= 5 && $score >= 1 && ($page == $_GET['page']) && isset($_GET["user"]) && $ip == $_GET["user"]) {
        $page = $_GET['page'];
        $rating->set_score($score, $ip, $page, $id);
        $status = $rating->status;
      }
    }
    if ($allow == false || !$rating->status) {
      $status = '<img src="images/icons/cross_circle.png" alt="no" />';
    }
    if (!isset($_GET['update'])) {
      $return .= '<div class="rating_wrapper">';
    }
    $return .='<div class="sp_rating" id="sp_rating_'.$id.'">
      <div class="rating"></div>
      <div class="base"><div class="average" style="width:'.$rating->average.'%">'.$rating->average.'</div></div>
      <div class="votes">'.$rating->votes.' '.$lang['poll']['votes'].'</div>
      <div class="status">
      <div class="score_this" id="score_this_'.$id.'" onclick="$(this).slideUp(); return false">(<a href="#">'.$lang['poll']['vote'].'</a>)</div>
       '.$status.'
      </div>
    </div><script type="text/javascript">init_rating(\''.$id.'\')</script>';
    if (!isset($_GET['update'])) {
      $return .= '</div>';
    }
	} else
      $return = '';
	return $return;
}
if (isset($_GET['reload']) && isset($_GET['page']) && isset($_GET['id'])) {
	echo rating_form($_GET['page'], $_GET['id']);
}
?>