<?php

// add pages to CMS pages dropdown
addAction('cmsPages', 'linksPages');

function linksPages($adminPages)
{
    $extensionDir = 'extensions/' . basename(__DIR__);

    $cmsPages = [
        [
            'name' => $extensionDir . '/puslapiai/nuorodos.php',
            'type' => 'file'
        ],
        [
            'name' => $extensionDir . '/puslapiai/pateikti_nuoroda.php',
            'type' => 'file'
        ],
    ];
    
    return array_merge($adminPages, $cmsPages);
}

// add CMS pages
addAction('adminExtensionsMenu', 'linksAdminMenu');

function linksAdminMenu($adminExtensionsMenu)
{
    $extensionDir = 'extensions/' . basename(__DIR__);

    $adminPages = [
        'links' =>  $extensionDir . '/dievai/pages/nuorodos.php'
    ];
    
    return array_merge($adminExtensionsMenu, $adminPages);
}

// add admin Menus
addAction('adminButtons', 'linksAdminButtons');

function linksAdminButtons($buttons)
{
    global $lang;

    $extensionDir = 'extensions/' . basename(__DIR__);

    $adminButtons = [
        'links'     => [
            [
                'url' 	=> url( "?id,999;a,links;v,1" ),
                'value'	=> $lang['admin']['links_unpublished'],
                'icon'	=> adminIcon('links', 'unpublished')
            ],
            [
                'url' 	=> url( "?id,999;a,links;v,5" ),
                'value'	=> $lang['admin']['links_create'],
                'icon'	=> adminIcon('links', 'create')
            ],
            [
                'url' 	=> url( "?id,999;a,links;v,4" ),
                'value'	=> $lang['admin']['links_edit'],
                'icon'	=> adminIcon('links', 'edit')
            ],
            [
                'url' 	=> url( "?id,999;a,links;v,2" ),
                'value'	=> $lang['system']['createcategory'],
                'icon'	=> adminIcon('links', 'create_category')
            ],
            [
                'url' 	=> url( "?id,999;a,links;v,3" ),
                'value'	=> $lang['system']['editcategory'],
                'icon'	=> adminIcon('links', 'edit_category')
            ]
        ],
    ];
    
    return array_merge($buttons, $adminButtons);
}

// add admin Menus
addAction('adminMenuIcons', 'linksUsersAdminIcons');

function linksUsersAdminIcons($icons)
{
    $adminIcons['links']  = 'link';

    return array_merge($icons, $adminIcons);
}