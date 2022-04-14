<?php

//todo: delete?

// OLD----
// // connection to DB
// $prisijungimas_prie_mysql = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die("<center><h1>Klaida 1</h1><br/>Svetainė laikinai neveikia. <h4>Prašome užsukti vėliau</h4></center>");
// mysqli_query($prisijungimas_prie_mysql, "SET NAMES 'utf8mb4'");
// // getting settings ready from DB
// $sql    = mysqli_query($prisijungimas_prie_mysql, "SELECT * FROM `".LENTELES_PRIESAGA."nustatymai`");
// $conf   = [];
// if(mysqli_num_rows($sql) > 1) {
//     while($row = mysqli_fetch_assoc($sql)) {
//         $conf[$row['key']] = $row['val'];
//     }
// }

// unset($row,$sql);


require_once ROOT . 'core/class/class.db.php';

$mmdb = DBFactory::fromArray(
    [
        'mysql:host=' . DB_HOSTNAME . ';dbname=' . DB_NAME,
        DB_USERNAME,
        DB_PASSWORD
    ]
);