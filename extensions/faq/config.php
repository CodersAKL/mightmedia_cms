<?php

// add pages to CMS pages dropdown
addAction('cmsPages', 'faqPages');

function faqPages($adminPages)
{
    $extensionDir = 'extensions/' . basename(__DIR__);

    $cmsPages = [
        [
            'name' => $extensionDir . '/puslapiai/duk.php',
            'type' => 'file'
        ],
    ];
    
    return array_merge($adminPages, $cmsPages);
}

// add CMS pages
addAction('adminExtensionsMenu', 'faqAdminMenu');

function faqAdminMenu($adminExtensionsMenu)
{
    $extensionDir = 'extensions/' . basename(__DIR__);

    $adminPages = [
        'faq' =>  $extensionDir . '/dievai/pages/duk.php'
    ];
    
    return array_merge($adminExtensionsMenu, $adminPages);
}

// add admin Menus
addAction('adminButtons', 'faqAdminButtons');

function faqAdminButtons($buttons)
{
    global $lang;

    $extensionDir = 'extensions/' . basename(__DIR__);

    $adminButtons = [
        'faq'   => [
            [
                'url' 	=> url( "?id,999;a,faq;v,7" ),
                'value'	=> $lang['admin']['faq_new'],
                'icon'	=> adminIcon('faq', 'create')
            ],
            [
                'url' 	=> url( "?id,999;a,faq;v,4" ),
                'value'	=> $lang['admin']['faq_edit'],
                'icon'	=> adminIcon('faq', 'edit')
            ]
        ],
    ];
    
    return array_merge($buttons, $adminButtons);
}

// add admin Menus
addAction('adminMenuIcons', 'faqUsersAdminIcons');

function faqUsersAdminIcons($icons)
{
    $adminIcons['faq']  = 'question_answer';

    return array_merge($icons, $adminIcons);
}