<?php

function blocksOrder($data) 
{
	global $lang;

	if (isset($data['order'])) {
		$array = json_decode($data['order'], true);
		$case_place = '';
		$where = '';
		foreach ($array as $position => $item) {
			$case_place .= "WHEN " . (int)$item['id'] . " THEN '" . (int)$position . "' ";
	
			$where .= $item['id'] . ",";
		}
		$where = rtrim($where, ", ");
		$sqlas = "UPDATE `" . LENTELES_PRIESAGA . "panel` SET `place`= (CASE id " . $case_place . " END) WHERE id IN (" . $where . ")";

		if($result = mysql_query1($sqlas)) {
			delete_cache( "SELECT * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='R' AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
			delete_cache( "SELECT * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='L' AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
			delete_cache( "SELECT * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='C' AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );

			return $lang['system']['updated'];
		}
	}

	return null;
}

function blockContent($data)
{
    global $url, $lang;

    $content = '<a href="' . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';d,' . $data['id'] ) . '" style="align:right" onClick="return confirm(\'' . $lang['admin']['delete'] . '?\')"><img src="' . ROOT . 'images/icons/cross.png" title="' . $lang['admin']['delete'] . '" align="right" /></a>
    <a href="' . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';r,' . $data['id'] ) . '" style="align:right"><img src="' . ROOT . 'images/icons/wrench.png" title="' . $lang['admin']['edit'] . '" align="right" /></a>
    <a href="' . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';e,' . $data['id'] ) . '" style="align:right"><img src="' . ROOT . 'images/icons/pencil.png" title="' . $lang['admin']['panel_text'] . '" align="right" /></a>
    ' . $data['panel'];

    return $content;
}