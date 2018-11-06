<?php

// add pages to CMS pages dropdown
addAction('cmsPages', 'downloadsPages');

function downloadsPages($adminPages)
{
    $extensionDir = 'extensions/' . basename(__DIR__);

    $cmsPages = [
        [
            'name' => $extensionDir . '/puslapiai/siustis.php',
            'type' => 'file'
        ],
        [
            'name' => $extensionDir . '/puslapiai/pateikti_siuntini_url.php',
            'type' => 'file'
        ],
        [
            'name' => $extensionDir . '/puslapiai/pateikti_siuntini.php',
            'type' => 'file'
        ],
    ];
    
    return array_merge($adminPages, $cmsPages);
}

// add CMS pages
addAction('adminExtensionsMenu', 'downloadsAdminMenu');

function downloadsAdminMenu($adminExtensionsMenu)
{
    $extensionDir = 'extensions/' . basename(__DIR__);

    $adminPages = [
        'downloads' =>  $extensionDir . '/dievai/pages/siustis.php'
    ];
    
    return array_merge($adminExtensionsMenu, $adminPages);
}

// add admin Menus
addAction('adminButtons', 'downloadsAdminButtons');

function downloadsAdminButtons($buttons)
{
    global $lang;

    $extensionDir = 'extensions/' . basename(__DIR__);

    $adminButtons = [
        'downloads' => [
            [
                'url' 	=> url( "?id,999;a,downloads;v,6" ),
                'value'	=> $lang['admin']['download_unpublished'],
                'icon'	=> adminIcon('downloads', 'unpublished')
            ],
            [
                'url' 	=> url( "?id,999;a,downloads;v,1" ),
                'value'	=> $lang['admin']['download_Create'],
                'icon'	=> adminIcon('downloads', 'create')
            ],
            [
                'url' 	=> url( "?id,999;a,downloads;v,7" ),
                'value'	=> $lang['admin']['download_edit'],
                'icon'	=> adminIcon('downloads', 'edit')
            ],
            [
                'url' 	=> url( "?id,999;a,downloads;v,2" ),
                'value'	=> $lang['system']['createcategory'],
                'icon'	=> adminIcon('downloads', 'create_category')
            ],
            [
                'url' 	=> url( "?id,999;a,downloads;v,3" ),
                'value'	=> $lang['system']['editcategory'],
                'icon'	=> adminIcon('downloads', 'edit_category')
            ]
        ],
    ];
    
    return array_merge($buttons, $adminButtons);
}

// add admin Menus
addAction('adminMenuIcons', 'downloadsAdminIcons');

function downloadsAdminIcons($icons)
{
    $adminIcons['downloads']  = 'file_download';

    return array_merge($icons, $adminIcons);
}