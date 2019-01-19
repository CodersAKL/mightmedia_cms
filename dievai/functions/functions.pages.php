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
		
			return getLangText('system', 'updated');
		}
	}

	return null;
}

function build_menu_admin($data, $id = 0) {

	global $url, $lang;
	
	$liPage = '<ol class="dd-list">';

	foreach ($data[$id] as $row) {
		if ($row['builder'] == 'cms') {
			$pageEditUrl = url('?id,' . $url['id'] . ';a,' . $url['a'] . ';e,' . $row['id']);
			$pageDeleteUrl = url('?id,' . $url['id'] . ';a,' . $url['a'] . ';d,' . $row['id']);
			$pageSettingsUrl = url('?id,' . $url['id'] . ';a,' . $url['a'] . ';r,' . $row['id']);
		} else {
			$pageEditUrl = url('?id,' . $url['id'] . ';a,pageAssembler;c,edit;pageId,' . $row['id']);
			$pageSettingsUrl = url('?id,' . $url['id'] . ';a,pageAssembler;c,settings;pageId,' . $row['id']);
			unset($pageDeleteUrl);
		}
		$actions = '';
		if ( isset($pageEditUrl) ){
			$actions .= '<a href="' . $pageEditUrl . '" style="align:right"><img src="' . ROOT . 'core/assets/images/icons/pencil.png" title="' . getLangText('admin', 'page_text') . '" align="right" /></a>';
		}
        if (isset($pageDeleteUrl)) {
            $actions .= '<a href="' . $pageDeleteUrl . '" style="align:right" onClick="return confirm(\'' . getLangText('system', 'delete_confirm') . '\')"><img src="' . ROOT . 'core/assets/images/icons/cross.png" title="' . getLangText('admin', 'delete') . '" align="right" /></a>';
        }
        if (isset($pageSettingsUrl)) {
            $actions .= '<a href="' . $pageSettingsUrl . '" style="align:right"><img src="' . ROOT . 'core/assets/images/icons/wrench.png" title="' . getLangText('admin', 'edit') . '" align="right" /></a>';
        }
		
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
