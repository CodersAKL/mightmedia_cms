<?php
$adminDir = basename(dirname(__DIR__));

$adminMenu = [
    'dashboard'     => $adminDir . '/pages/dashboard.php',
    'configuration' => $adminDir . '/pages/configuration.php', 
    'pages'         => $adminDir . '/pages/meniu.php',
    'blocks'        => $adminDir . '/pages/blocks.php',
    'news'          => $adminDir . '/pages/naujienos.php',
    'users'         => $adminDir . '/pages/users.php', 
    'bans'          => $adminDir . '/pages/bans.php',
    'logs'          => $adminDir . '/pages/logs.php', 
];

$adminExtensionsMenu = [
    // 'polls'     => 'balsavimas.php',
    // 'faq'       => 'duk.php',
    // 'comments'  => 'komentarai.php',
    // 'forum'     => 'frm.php',
    // 'gallery'   => 'galerija.php',
    // 'downloads' => 'siustis.php', 
    // 'articles'  => 'straipsnis.php',
    // 'pm'        => 'pm.php', 
    // 'chat'      => 'pokalbiai.php', 
];