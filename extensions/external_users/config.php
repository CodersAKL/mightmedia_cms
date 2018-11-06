<?php

// add pages to CMS pages dropdown
addAction('cmsPages', 'externalUsersPages');

function externalUsersPages($adminPages)
{
    $extensionDir = 'extensions/' . basename(__DIR__);

    $cmsPages = [
        [
            'name' => $extensionDir . '/puslapiai/edit_user.php',
            'type' => 'file'
        ],
        [
            'name' => $extensionDir . '/puslapiai/nariai.php',
            'type' => 'file'
        ],
        [
            'name' => $extensionDir . '/puslapiai/online.php',
            'type' => 'file'
        ],
        [
            'name' => $extensionDir . '/puslapiai/pm.php',
            'type' => 'file'
        ],
        [
            'name' => $extensionDir . '/puslapiai/reg.php',
            'type' => 'file'
        ],
        [
            'name' => $extensionDir . '/puslapiai/slaptazodzio_priminimas.php',
            'type' => 'file'
        ],
        [
            'name' => $extensionDir . '/puslapiai/view_user.php',
            'type' => 'file'
        ],
    ];
    
    return array_merge($adminPages, $cmsPages);
}

// add blocks to CMS dropdown
addAction('cmsBlocks', 'externalUsersBlocks');

function externalUsersBlocks($adminBlocks)
{
    $extensionDir = 'extensions/' . basename(__DIR__);

    $cmsBlocks = [
        [
            'name' => $extensionDir . '/blokai/prisijunge.php',
            'type' => 'file'
        ],
        [
            'name' => $extensionDir . '/blokai/vartotojas.php',
            'type' => 'file'
        ],
        [
            'name' => $extensionDir . '/blokai/vartotoju_top.php',
            'type' => 'file'
        ],
    ];
    
    return array_merge($adminBlocks, $cmsBlocks);
}

// add CMS pages
addAction('adminExtensionsMenu', 'externalUsersAdminMenu');

function externalUsersAdminMenu($adminExtensionsMenu)
{
    $extensionDir = 'extensions/' . basename(__DIR__);

    $adminPages = [
        'pm' =>  $extensionDir . '/dievai/pages/pm.php'
    ];
    
    return array_merge($adminExtensionsMenu, $adminPages);
}

// add admin Menus
addAction('adminMenuIcons', 'externalUsersAdminIcons');

function externalUsersAdminIcons($icons)
{
    $adminIcons['pm']  = 'message';

    return array_merge($icons, $adminIcons);
}