<?php

$buttons = [
    'news'      => [
        [
            'url' 	=> url( "?id,999;a,news;v,6" ),
            'value'	=> getLangText('admin','news_unpublished'),
            'icon'	=> adminIcon('news', 'unpublished')
        ],
        [
            'url' 	=> url( "?id,999;a,news;v,1" ),
            'value'	=> getLangText('admin','news_create'),
            'icon'	=> adminIcon('news', 'create')
        ],
        [
            'url' 	=> url( "?id,999;a,news;v,4" ),
            'value'	=> getLangText('admin','news_edit'),
            'icon'	=> adminIcon('news', 'edit')
        ],
        [
            'url' 	=> url( "?id,999;a,news;v,2" ),
            'value'	=> getLangText('system','createcategory'),
            'icon'	=> adminIcon('news', 'create_category')
        ],
        [
            'url' 	=> url( "?id,999;a,news;v,3" ),
            'value'	=> getLangText('system','editcategory'),
            'icon'	=> adminIcon('news', 'create_category')
        ]
    ],
    'blocks'    => [
        [
            'url' 	=> url( "?id,999;a,blocks;" ),
            'value'	=> getLangText('admin','blocks'),
            'icon'	=> adminIcon('blocks', 'select')
        ],
        [
            'url' 	=> url( "?id,999;a,blocks;n,1" ),
            'value'	=> getLangText('admin','panel_select'),
            'icon'	=> adminIcon('blocks', 'select')
        ],
        [
            'url' 	=> url( "?id,999;a,blocks;n,2" ),
            'value'	=> getLangText('admin','panel_create'),
            'icon'	=> adminIcon('blocks', 'create')
        ]
    ],
    'users'     => [
        [
            'url' 	=> url( "?id,999;a,users;v,1" ),
            'value'	=> getLangText('admin','user_list'),
            'icon'	=> adminIcon('users', 'list')
        ],
        [
            'url' 	=> url( "?id,999;a,users;v,4" ),
            'value'	=> getLangText('admin','user_find'),
            'icon'	=> adminIcon('users', 'find')
        ],
        [
            'url' 	=> url( "?id,999;a,users;v,2" ),
            'value'	=> getLangText('system','createcategory'),
            'icon'	=> adminIcon('users', 'create_category')
        ],
        [
            'url' 	=> url( "?id,999;a,users;v,3" ),
            'value'	=> getLangText('system','editcategory'),
            'icon'	=> adminIcon('users', 'create_category')
        ]
    ],
    'pages'     => [
        [
            'url' 	=> url( "?id,999;a,pages;" ),
            'value'	=> getLangText('admin','meniu'),
            'icon'	=> adminIcon('pages', 'select')
        ],
        [
            'url' 	=> url( "?id,999;a,pages;n,1" ),
            'value'	=> getLangText('admin','page_select'),
            'icon'	=> adminIcon('pages', 'select')
        ],
        [
            'url' 	=> url( "?id,999;a,pages;n,2" ),
            'value'	=> getLangText('admin','page_create'),
            'icon'	=> adminIcon('pages', 'create')
        ],
        [
            'url' 	=> url( "?id,999;a,pages;n,3" ),
            'value'	=> getLangText('admin','page_tree'),
            'icon'	=> adminIcon('pages', 'edit')
        ]
    ],
    'configuration' => [
        [
            'url' 	=> url("?id,999;a,configuration;c,main"),
            'value'	=> getLangText('admin','configuration_main'),
            'icon'	=> adminIcon('configuration', 'main')
        ],
        [
            'url' 	=> url("?id,999;a,configuration;c,seo"),
            'value'	=> getLangText('admin','configuration_seo'),
            'icon'	=> adminIcon('configuration', 'seo')
        ],
        [
            'url' 	=> url("?id,999;a,configuration;c,maintenance"),
            'value'	=> getLangText('admin','configuration_maintenance'),
            'icon'	=> adminIcon('configuration', 'maintenance')
        ],
        [
            'url' 	=> url("?id,999;a,configuration;c,extensions"),
            'value'	=> getLangText('admin','configuration_extensions'),
            'icon'	=> adminIcon('configuration', 'extensions')
        ],
        [
            'url' 	=> url("?id,999;a,configuration;c,translation"),
            'value'	=> getLangText('admin','configuration_translations'),
            'icon'	=> adminIcon('configuration', 'translation')
        ]
    ],
    'pageAssembler' => [
        [
            'url' 	=> url("?id,999;a,pageAssembler;c,settings"),
            'value'	=> getLangText('pageAssembler','new_page'),
            'icon'	=> adminIcon('blocks', 'main')
        ],
        [
            'url' 	=> url("?id,999;a,pageAssembler;c,list"),
            'value'	=> getLangText('pageAssembler','pageassembler_list'),
            'icon'	=> adminIcon('blocks', 'list')
        ]
    ]
];
