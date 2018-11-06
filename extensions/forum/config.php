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