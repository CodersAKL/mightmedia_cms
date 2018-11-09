<?php


function pagesOrder($data)
{
	global $lang;

	$case_place = '';
	$where      = '';

	if (isset($data['order'])) {
		$array = json_decode($data['order'], true);

		foreach ($array as $position => $item) {
			$case_place .= "WHEN " . (int)$item['id'] . " THEN '" . (int)$position . "' ";
			$where .= $item['id'] . ",";
			
			if(! empty($item['children'])) {
				foreach ($item['children'] as $childrenPosition => $childrenItem) {
					$case_place .= "WHEN " . (int)$childrenItem['id'] . " THEN '" . (int)$childrenPosition . "' ";
					$where .= $childrenItem['id'] . ",";
				}
			}
		}

		$where = rtrim($where, ", ");

		$sqlas = "UPDATE `" . LENTELES_PRIESAGA . "page` SET `place`= (CASE id " . $case_place . " END) WHERE id IN (" . $where . ")";

		if($result = mysql_query1($sqlas)) {
			delete_cache( "SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
		
			return $lang['system']['updated'];
		}
	}

	return null;
}

function build_menu_admin($data, $id = 0) {

	global $url, $lang;
	
	$liPage = '<ol class="dd-list">';

	foreach ($data[$id] as $row) {
		$actions = '<a href="' . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';d,' . $row['id'] ) . '" style="align:right" onClick="return confirm(\'' . $lang['system']['delete_confirm'] . '\')"><img src="' . ROOT . 'images/icons/cross.png" title="' . $lang['admin']['delete'] . '" align="right" /></a>
		<a href="' . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';r,' . $row['id'] ) . '" style="align:right"><img src="' . ROOT . 'images/icons/wrench.png" title="' . $lang['admin']['edit'] . '" align="right" /></a>
		<a href="' . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';e,' . $row['id'] ) . '" style="align:right"><img src="' . ROOT . 'images/icons/pencil.png" title="' . $lang['admin']['page_text'] . '" align="right" /></a>';
		$content =	'';

		if (isset($data[$row['id']])) {
			$content .= $actions;
			$content .= '<a href="' . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';r,' . $row['id'] ) . '">';
			$content .= $row['pavadinimas'];
			$content .= '</a>';

			$subMenu = build_menu_admin($data, $row['id']);

			$liPage .= dragItem($row['id'], $content, $subMenu);

		} else {
			$content .= $actions;
			$content .= '<a href="' . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';r,' . $row['id'] ) . '">';
			$content .= $row['pavadinimas'];
			$content .= '</a>';

			$liPage .= dragItem($row['id'], $content);
		}
	}

	$liPage .= '</ol>';

	return $liPage;
}
