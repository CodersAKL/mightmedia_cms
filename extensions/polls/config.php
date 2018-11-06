<?php

// add pages to CMS pages dropdown
addAction('cmsPages', 'pollsPages');

function pollsPages($adminPages)
{
    $extensionDir = 'extensions/' . basename(__DIR__);

    $cmsPages = [
        [
            'name' => $extensionDir . '/puslapiai/blsavimo_archyvas.php',
            'type' => 'file'
        ],
    ];
    
    return array_merge($adminPages, $cmsPages);
}

// add blocks to CMS dropdown
addAction('cmsBlocks', 'pollsBlocks');

function pollsBlocks($adminBlocks)
{
    $extensionDir = 'extensions/' . basename(__DIR__);

    $cmsBlocks = [
        [
            'name' => $extensionDir . '/blokai/apklausa.php',
            'type' => 'file'
        ],
    ];
    
    return array_merge($adminBlocks, $cmsBlocks);
}

// add CMS pages
addAction('adminExtensionsMenu', 'pollsAdminMenu');

function pollsAdminMenu($adminExtensionsMenu)
{
    $extensionDir = 'extensions/' . basename(__DIR__);

    $adminPages = [
        'polls' =>  $extensionDir . '/dievai/pages/balsavimas.php'
    ];
    
    return array_merge($adminExtensionsMenu, $adminPages);
}

// add admin Menus
addAction('adminButtons', 'pollsAdminButtons');

function pollsAdminButtons($buttons)
{
    global $lang;

    $extensionDir = 'extensions/' . basename(__DIR__);

    $adminButtons = [
        'polls'     => [
            [
                'url' 	=> url( "?id,999;a,polls;v,1" ),
                'value'	=> $lang['admin']['poll_create'],
                'icon'	=> adminIcon('polls', 'create')
            ],
            [
                'url'	=> url( "?id,999;a,polls;v,2" ),
                'value'	=> $lang['admin']['poll_edit'],
                'icon'	=> adminIcon('polls', 'edit')
            ]
        ],
    ];
    
    return array_merge($buttons, $adminButtons);
}

// add admin Menus
addAction('adminMenuIcons', 'pollsUsersAdminIcons');

function pollsUsersAdminIcons($icons)
{
    $adminIcons['polls']  = 'show_chart';

    return array_merge($icons, $adminIcons);
}