<?php

    echo '<div class="main main-raised">';
    $sql = "SELECT * FROM `" . LENTELES_PRIESAGA . "pa_data` WHERE page_id = " . escape($pageId) . " ORDER BY ID ASC";
    $pageContent = mysql_query1($sql);
    unset($sql);
    if ($pageContent) {
        $extensionPrefix = "content/extensions";
        if (count($pageContent) > 0){
            foreach ($pageContent as $block => $value) {
                
                $blockPath =  $pageContent[$block]['type'];                 
                $blockJSON =  $pageContent[$block]['content'];  
                $blockPath = str_replace('../', '' ,$blockPath);                 
                $content = json_decode($blockJSON, true);
                $localBlockConfig = json_decode( file_get_contents($blockPath,true) , true);
                $content['orderID'] = $pageContent[$block]['order_id'];
                $content['parentId'] = $pageContent[$block]['parent_id'];
                $backEndHtmlFile = $localBlockConfig['configurations']['backEndHtmlFile'];
                include $extensionPrefix . $backEndHtmlFile;
            }  
        }                   
    }
    echo '</div>';