<?php
// add pages to CMS pages dropdown
addAction('cmsPages', 'articlesPages');

function articlesPages($adminPages)
{
    $extensionDir = 'extensions/' . basename(__DIR__);

    $cmsPages = [
        [
            'name' => $extensionDir . '/puslapiai/pateikti_straipsni.php',
            'type' => 'file'
        ],
        [
            'name' =>  $extensionDir . '/puslapiai/straipsnis.php',
            'type' => 'file'
        ]
    ];
    
    return array_merge($adminPages, $cmsPages);
}

// add CMS pages
addAction('adminExtensionsMenu', 'articlesAdminMenu');

function articlesAdminMenu($adminExtensionsMenu)
{
    $extensionDir = 'extensions/' . basename(__DIR__);

    $adminPages = [
        'articles' =>  $extensionDir . '/dievai/pages/straipsnis.php'
    ];
    
    return array_merge($adminExtensionsMenu, $adminPages);
}

// add admin Menus
addAction('adminButtons', 'articlesAdminButtons');

function articlesAdminButtons($buttons)
{
    global $lang;

    $extensionDir = 'extensions/' . basename(__DIR__);

    $adminButtons = [
        'articles'  => [
            [
                'url' 	=> url( "?id,999;a,articles;v,6" ),
                'value'	=> $lang['admin']['article_unpublished'],
                'icon'	=> adminIcon('articles', 'unpublished')
            ],
            [
                'url' 	=> url( "?id,999;a,articles;v,7" ),
                'value'	=> $lang['admin']['article_create'],
                'icon'	=> adminIcon('articles', 'create')
            ],
            [
                'url' 	=> url( "?id,999;a,articles;v,4" ),
                'value'	=> $lang['admin']['article_edit'],
                'icon'	=> adminIcon('articles', 'edit')
            ],
            [
                'url' 	=> url( "?id,999;a,articles;v,2" ),
                'value'	=> $lang['system']['createcategory'],
                'icon'	=> adminIcon('articles', 'create_category')
            ],
            [
                'url' 	=> url( "?id,999;a,articles;v,3" ),
                'value'	=> $lang['system']['editcategory'],
                'icon'	=> adminIcon('articles', 'edit_category')
            ]
        ],
    ];
    
    return array_merge($buttons, $adminButtons);
}

// add admin Menus
addAction('adminMenuIcons', 'articlesAdminIcons');

function articlesAdminIcons($icons)
{
    $adminIcons['articles']  = 'insert_drive_file';

    return array_merge($icons, $adminIcons);
}