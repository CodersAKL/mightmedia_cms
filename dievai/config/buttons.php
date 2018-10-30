<?php

$buttons = [
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
            'url' 	=> url("?id,999;a,gallery;v,9"),
            'value'	=> $lang['admin']['gallery_group_add'],
            'icon'	=> adminIcon('gallery', 'group_add'),
            'view'	=> $_SESSION[SLAPTAS]['level'] == 1
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
    'news'      => [
        [
            'url' 	=> url( "?id,999;a,news;v,6" ),
            'value'	=> $lang['admin']['news_unpublished'],
            'icon'	=> adminIcon('news', 'unpublished')
        ],
        [
            'url' 	=> url( "?id,999;a,news;v,1" ),
            'value'	=> $lang['admin']['news_create'],
            'icon'	=> adminIcon('news', 'create')
        ],
        [
            'url' 	=> url( "?id,999;a,news;v,4" ),
            'value'	=> $lang['admin']['news_edit'],
            'icon'	=> adminIcon('news', 'edit')
        ],
        [
            'url' 	=> url( "?id,999;a,news;v,2" ),
            'value'	=> $lang['system']['createcategory'],
            'icon'	=> adminIcon('news', 'create_category')
        ],
        [
            'url' 	=> url( "?id,999;a,news;v,3" ),
            'value'	=> $lang['system']['editcategory'],
            'icon'	=> adminIcon('news', 'create_category')
        ]
    ],
    'blocks'    => [
        [
            'url' 	=> url( "?id,999;a,blocks;" ),
            'value'	=> $lang['admin']['blocks'],
            'icon'	=> adminIcon('blocks', 'select')
        ],
        [
            'url' 	=> url( "?id,999;a,blocks;n,1" ),
            'value'	=> $lang['admin']['panel_select'],
            'icon'	=> adminIcon('blocks', 'select')
        ],
        [
            'url' 	=> url( "?id,999;a,blocks;n,2" ),
            'value'	=> $lang['admin']['panel_create'],
            'icon'	=> adminIcon('blocks', 'create')
        ]
    ],
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
    'users'     => [
        [
            'url' 	=> url( "?id,999;a,users;v,1" ),
            'value'	=> $lang['admin']['user_list'],
            'icon'	=> adminIcon('users', 'list')
        ],
        [
            'url' 	=> url( "?id,999;a,users;v,4" ),
            'value'	=> $lang['admin']['user_find'],
            'icon'	=> adminIcon('users', 'find')
        ],
        [
            'url' 	=> url( "?id,999;a,users;v,2" ),
            'value'	=> $lang['system']['createcategory'],
            'icon'	=> adminIcon('users', 'create_category')
        ],
        [
            'url' 	=> url( "?id,999;a,users;v,3" ),
            'value'	=> $lang['system']['editcategory'],
            'icon'	=> adminIcon('users', 'create_category')
        ]
    ],
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
    'pages'     => [
        [
            'url' 	=> url( "?id,999;a,pages;" ),
            'value'	=> $lang['admin']['meniu'],
            'icon'	=> adminIcon('pages', 'select')
        ],
        [
            'url' 	=> url( "?id,999;a,pages;n,1" ),
            'value'	=> $lang['admin']['page_select'],
            'icon'	=> adminIcon('pages', 'select')
        ],
        [
            'url' 	=> url( "?id,999;a,pages;n,2" ),
            'value'	=> $lang['admin']['page_create'],
            'icon'	=> adminIcon('pages', 'create')
        ],
        [
            'url' 	=> url( "?id,999;a,pages;n,3" ),
            'value'	=> $lang['admin']['page_tree'],
            'icon'	=> adminIcon('pages', 'edit')
        ]
    ],
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
    'configuration' => [
        [
            'url' 	=> url("?id,999;a,configuration;c,main"),
            'value'	=> $lang['admin']['configuration_main'],
            'icon'	=> adminIcon('configuration', 'main')
        ],
        [
            'url' 	=> url("?id,999;a,configuration;c,seo"),
            'value'	=> $lang['admin']['configuration_seo'],
            'icon'	=> adminIcon('configuration', 'seo')
        ],
        [
            'url' 	=> url("?id,999;a,configuration;c,maintenance"),
            'value'	=> $lang['admin']['configuration_maintenance'],
            'icon'	=> adminIcon('configuration', 'maintenance')
        ]
    ]
];
