<?php

// add pages to CMS pages dropdown
addAction('cmsPages', 'forumPages');

function forumPages($adminPages)
{
    $extensionDir = 'extensions/' . basename(__DIR__);

    $cmsPages = [
        [
            'name' => $extensionDir . '/puslapiai/frm.php',
            'type' => 'file'
        ],
    ];
    
    return array_merge($adminPages, $cmsPages);
}

// add CMS pages
addAction('adminExtensionsMenu', 'forumAdminMenu');

function forumAdminMenu($adminExtensionsMenu)
{
    $extensionDir = 'extensions/' . basename(__DIR__);

    $adminPages = [
        'forum' =>  $extensionDir . '/dievai/pages/frm.php'
    ];
    
    return array_merge($adminExtensionsMenu, $adminPages);
}

// add admin Menus
addAction('adminButtons', 'forumAdminButtons');

function forumAdminButtons($buttons)
{
    global $lang;

    $extensionDir = 'extensions/' . basename(__DIR__);

    $adminButtons = [
        'forum'     => [
            [
                'url' 	=> url( "?id,999;a,forum;f,1" ),
                'value'	=> $lang['system']['createcategory'],
                'icon'	=> adminIcon('forum', 'create_category')
            ],
            [
                'url' 	=> url( "?id,999;a,forum;f,2" ),
                'value'	=> $lang['system']['editcategory'],
                'icon'	=> adminIcon('forum', 'edit_category')
            ],
            [
                'url' 	=> url( "?id,999;a,forum;f,3" ),
                'value'	=> $lang['admin']['forum_createsub'],
                'icon'	=> adminIcon('forum', 'create_sub_category')
            ],
            [
                'url' 	=> url( "?id,999;a,forum;f,4" ),
                'value'	=> $lang['admin']['forum_editsub'],
                'icon'	=> adminIcon('forum', 'edit_sub_category')
            ]
        ],    
    ];
    
    return array_merge($buttons, $adminButtons);
}

// add admin Menus
addAction('adminMenuIcons', 'forumUsersAdminIcons');

function forumUsersAdminIcons($icons)
{
    $adminIcons['forum']  = 'mode_comment';

    return array_merge($icons, $adminIcons);
}

//functions
function buldForumMenu($data)
{
	global $lang;
	
	$liPage = '<ol class="dd-list">';

	foreach ($data as $id => $item) {
        $actions = '<span style="float:right;" class="clearfix">
        <a href="' . $item['edit'] . '" data-toggle="tooltip" title="' . $lang['admin']['edit'] . '">
        <img src="' . ROOT . 'images/icons/wrench.png">
        </a>
        <a href="' . $item['delete'] . '" data-toggle="tooltip" title="' . $lang['admin']['delete'] . '" onclick="return confirm(\'' . $lang['admin']['delete'] . '?\')">
        <img src="' . ROOT . 'images/icons/cross.png">
        </a>
        </span>';
		$content =	'';
        $content .= $actions;
        $content .= $item['title'];

        $liPage .= dragItem($id, $content);
		
	}

	$liPage .= '</ol>';

	return $liPage;
}

function forumCatsOrder($data)
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
		$sqlas = "UPDATE `" . LENTELES_PRIESAGA . "d_forumai` SET `place`= (CASE id " . $case_place . " END) WHERE id IN (" . $where . ")";

		if($result = mysql_query1($sqlas)) {

			return $lang['system']['updated'];
		}
	}

	return null;
}

function forumSubCatsOrder($data)
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
		$sqlas = "UPDATE `" . LENTELES_PRIESAGA . "d_temos` SET `place`= (CASE id " . $case_place . " END) WHERE id IN (" . $where . ")";

		if($result = mysql_query1($sqlas)) {

			return $lang['system']['updated'];
		}
	}

	return null;
}
