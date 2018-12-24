<?php

// add pages to CMS pages dropdown
addAction('cmsPages', 'downloadsPages');

function downloadsPages($adminPages)
{
    $extensionDir = 'content/extensions/' . basename(__DIR__);

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
    $extensionDir = 'content/extensions/' . basename(__DIR__);

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

    $extensionDir = 'content/extensions/' . basename(__DIR__);

    $adminButtons = [
        'downloads' => [
            [
                'url' 	=> url( "?id,999;a,downloads;v,6" ),
                'value'	=> getLangText('admin', 'download_unpublished'),
                'icon'	=> adminIcon('downloads', 'unpublished')
            ],
            [
                'url' 	=> url( "?id,999;a,downloads;v,1" ),
                'value'	=> getLangText('admin', 'download_Create'),
                'icon'	=> adminIcon('downloads', 'create')
            ],
            [
                'url' 	=> url( "?id,999;a,downloads;v,7" ),
                'value'	=> getLangText('admin', 'download_edit'),
                'icon'	=> adminIcon('downloads', 'edit')
            ],
            [
                'url' 	=> url( "?id,999;a,downloads;v,2" ),
                'value'	=> getLangText('system', 'createcategory'),
                'icon'	=> adminIcon('downloads', 'create_category')
            ],
            [
                'url' 	=> url( "?id,999;a,downloads;v,3" ),
                'value'	=> getLangText('system', 'editcategory'),
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

//functions
function upload( $file, $file_types_array = ["BMP", "JPG", "PNG", "PSD", "ZIP", "RAR", "GIF"], $upload_dir = "../siuntiniai" ) {

    global $lang;
    
    if ( $_FILES["$file"]["name"] != "" ) {
        $origfilename = $_FILES["$file"]["name"];
        $filename     = explode( ".", $_FILES["$file"]["name"] );
        $filenameext  = strtolower( $filename[count( $filename ) - 1] );
        unset( $filename[count( $filename ) - 1] );
        $filename       = implode( ".", $filename );
        $filename       = substr( $filename, 0, 60 ) . "." . $filenameext;
        $file_ext_allow = FALSE;
        for ( $x = 0; $x < count( $file_types_array ); $x++ ) {
            if ( $filenameext == $file_types_array[$x] ) {
                $file_ext_allow = TRUE;
            }
        } // for
        if ( $file_ext_allow ) {
            if ( $_FILES["$file"]["size"] < MFDYDIS ) {
                $ieskom   = array( "?", "&", "=", " ", "+", "-", "#" );
                $keiciam  = array( "", "", "", "_", "", "", "" );
                $filename = str_replace( $ieskom, $keiciam, $filename );
                if ( is_file( $upload_dir . $filename ) ) {
                    $filename = time() . "_" . $filename;
                }
                move_uploaded_file( $_FILES["$file"]["tmp_name"], $upload_dir . $filename );
                chmod( $upload_dir . $filename, 0777 );
                if ( file_exists( $upload_dir . $filename ) ) {
                    $result = mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "siuntiniai` (`pavadinimas`,`file`,`apie`,`autorius`,`data`,`categorija`,`rodoma`) VALUES (" . escape( $_POST['Pavadinimas'] ) . "," . escape( $filename ) . ", " . escape( $_POST['Aprasymas'] ) . "," . escape( $_SESSION[SLAPTAS]['id'] ) . ", '" . time() . "', " . escape( $_POST['cat'] ) . ", 'TAIP')" );

                    if ( $result ) {
                        msg( getLangText('system', 'done'), getLangText('admin', 'download_created') );
                    } else {
                        klaida( getLangText('system', 'error'), getLangText('system', 'error') );
                    }
                } else {
                    klaida( getLangText('system', 'error'), '<font color="#FF0000">' . $filename . '</font>' );
                }
            } else {
                klaida( getLangText('system', 'error'), '<font color="#FF0000">' . $filename . '</font> ' . getLangText('admin', 'download_toobig') . '' );
            }
        } else {
            klaida( getLangText('system', 'error'), '<font color="#FF0000">' . $filename . '</font> ' . getLangText('admin', 'download_badfile') . '' );
        }
    }
}