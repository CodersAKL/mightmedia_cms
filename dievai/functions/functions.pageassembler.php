<?php

function pageAssemblerDBexist($dbName){
    global $mysql_num, $prisijungimas_prie_mysql, $conf;

    $request = "SELECT * FROM `" . LENTELES_PRIESAGA . $dbName . "` GROUP BY `id` ORDER BY `id` DESC";
    $sql = mysqli_query($prisijungimas_prie_mysql, $request ); 

    $result = false;
    if ($sql){
        $result = true;
    } else {
        switch ($dbName) {
            case 'pa_data':
                $sql1 = "CREATE TABLE `" . LENTELES_PRIESAGA . "pa_data` (
                    id INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                    parent_id INT(9),
                    order_id INT(9),
                    type TEXT,
                    lang TEXT,
                    content TEXT,
                    page_id INT(9),
                    style text
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;";
                break;
            case 'pa_page_settings':
                $sql1 = "CREATE TABLE `page` (
                    `id` int(11) NOT NULL,
                    `pavadinimas` varchar(255) DEFAULT NULL,
                    `lang` varchar(3) NOT NULL DEFAULT 'lt' COMMENT 'Language',
                    `file` varchar(255) DEFAULT NULL,
                    `place` int(11) DEFAULT NULL,
                    `show` enum('Y','N') NOT NULL DEFAULT 'Y',
                    `teises` varchar(255) NOT NULL DEFAULT 'N;',
                    `parent` int(150) NOT NULL DEFAULT '0',
                    `builder` text DEFAULT 'cms',
                    `metatitle` text DEFAULT NULL,
                    `metadesc` text DEFAULT NULL,
                    `metakeywords` text DEFAULT NULL,
                    `url` text DEFAULT NULL
                  ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;";
                break;
        }
        $result = mysqli_query( $prisijungimas_prie_mysql, $sql1 ); 
        $result = (1 == $result) ? true : false;
    }
    return $result;
}

function checkBlockListStatus(){
    $path = '../content/extensions/pageassembler/block_list.json';
    $blockList = json_decode(file_get_contents($path,true));

    if (array_key_exists('generated', $blockList)){
        $generated = $blockList->generated;
        if (time() > $generated){
            $tree['content'] = generateNewBlockList();
            $tree['generated'] = time();
            $blockList2 = fopen($path, "w+");
            fwrite($blockList2, json_encode($tree));
            fclose($blockList2);
        } else {
            return true;
        }
    } else {
        
        $tree['content'] = generateNewBlockList();
        $tree['generated'] = time();
        $_SESSION['NewBlockList'] = $tree;
        $blockList2 = fopen($path, "w+");
        fwrite($blockList2, json_encode($tree));
        fclose($blockList2);
    }	
}

function generateNewBlockList($path = '../content/extensions/pageassembler/'){
    $categories = getFiles($path);
    $curFolder = basename($path);
    if(is_array($categories)) {
        foreach ($categories as $files) {
            if ($files['type'] == 'dir'){
                $newPath  = $path . $files['name'] .'/';
                if(! empty(generateNewBlockList($newPath))) {
                    $newArray[$files['name']] = generateNewBlockList($newPath);
                }
            } else if($files['type'] == 'file' && $files['name'] == 'config.json') {
                $newArray = $path . $files['name'];
            }
        }
    }
    return empty($newArray) ? null : $newArray;
    
}