<?php
$galleryExtensionDir = 'extensions/' . basename(__DIR__);
$big_img  = ROOT . "images/galerija/"; //Kur bus saugomi didesni paveiksliukai
$mini_img = ROOT . "images/galerija/mini"; //Kur bus saugomos miniatiuros
//Sarašas leidžiamų failų
$limitedext = [
    ".jpg", 
    ".JPG", 
    ".jpeg", 
    ".JPEG", 
    ".png", 
    ".PNG", 
    ".gif", 
    ".GIF"
];

// add pages to CMS pages dropdown
addAction('cmsPages', 'galleryPages');

function galleryPages($adminPages)
{
    $extensionDir = 'extensions/' . basename(__DIR__);

    $cmsPages = [
        [
            'name' => $extensionDir . '/puslapiai/pateikti_foto.php',
            'type' => 'file'
        ],
        [
            'name' =>  $extensionDir . '/puslapiai/galerija.php',
            'type' => 'file'
        ]
    ];
    
    return array_merge($adminPages, $cmsPages);
}

// add CMS pages
addAction('adminExtensionsMenu', 'galleryAdminMenu');

function galleryAdminMenu($adminExtensionsMenu)
{
    $extensionDir = 'extensions/' . basename(__DIR__);

    $adminPages = [
        'gallery' =>  $extensionDir . '/dievai/pages/galerija.php'
    ];
    
    return array_merge($adminExtensionsMenu, $adminPages);
}

// add admin Menus
addAction('adminButtons', 'galleryAdminButtons');

function galleryAdminButtons($buttons)
{
    global $lang;

    $extensionDir = 'extensions/' . basename(__DIR__);

    $adminButtons = [
        'gallery'   => [
            [
                'url' 	=> url("?id,999;a,gallery;v,6"),
                'value'	=> $lang['admin']['gallery_conf'],
                'icon'	=> adminIcon('gallery', 'config')
            ],
            [
                'url' 	=> url("?id,999;a,gallery;v,7"),
                'value'	=> $lang['admin']['gallery_unpublished'],
                'icon'	=> adminIcon('gallery', 'unpublished')
            ],
            [
                'url' 	=> url("?id,999;a,gallery;v,1"),
                'value'	=> $lang['admin']['gallery_add'],
                'icon'	=> adminIcon('gallery', 'add')
            ],
            [
                'url' 	=> url("?id,999;a,gallery;v,8"),
                'value'	=> $lang['admin']['gallery_edit'],
                'icon'	=> adminIcon('gallery', 'edit')
            ],
            [
                'url' 	=> url("?id,999;a,gallery;v,2"),
                'value'	=> $lang['admin']['gallery_photoalbum_cr'],
                'icon'	=> adminIcon('gallery', 'album_create')
            ],
            [
                'url' 	=> url("?id,999;a,gallery;v,3"),
                'value'	=> $lang['admin']['gallery_photoalbum_ed'],
                'icon'	=> adminIcon('gallery', 'album_create')
            ],
        ],
    ];
    
    return array_merge($buttons, $adminButtons);
}

// add admin Menus
addAction('adminMenuIcons', 'galleryUsersAdminIcons');

function galleryUsersAdminIcons($icons)
{
    $adminIcons['gallery']  = 'collections';

    return array_merge($icons, $adminIcons);
}
//remove gallery photo album

function galleryAlbumRemove($data)
{
    var_dump($data);
}