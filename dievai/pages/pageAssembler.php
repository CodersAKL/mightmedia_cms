<?php

include_once 'functions/functions.pageassembler.php';
echo '<link rel="stylesheet" href="css/page-assembler.css">';


if (!defined("OK")) {
    redirect('location: http://' . $_SERVER["HTTP_HOST"]);
}

if (BUTTONS_BLOCK) {
    lentele(getLangText('pageAssembler','pageassembler'), buttonsMenu(buttons('pageAssembler')));
}

if (isset($url['c'])) {
    if ($url['c'] == 'edit') {
        if (isset($_SESSION['page-assembler-pageId']) && !isset($_GET['pageId'])) {
            $pageId = $_SESSION['page-assembler-pageId'];
        } elseif (isset($_GET['pageId'])) {
            $pageId = $_GET['pageId'];
            $_SESSION['page-assembler-pageId'] = $_GET['pageId'];
        }
        if (isset($pageId) && pageAssemblerDBexist('pa_data')) {
            $settings = [
                "Form" => [
                    "action" 	=> "",
                    "method" 	=> "post",
                    "enctype" 	=> "",
                    "id" 		=> "",
                    "class" 	=> "",
                    "name" 		=> "reg"
                ]
            ];
        
            if (isset($_GET['insertBlock'])) {
                $blockName = $_GET['insertBlock'];
                $blockType = $_GET['blockType'];
                $extensionPrefix = "../content/extensions";
                $blockList = $extensionPrefix . '/pageassembler/block_list.json';
                $blockPath = json_decode(file_get_contents($blockList))->content->{$blockType}->{$blockName};
                $blockJSON = file_get_contents($blockPath, true);
                $json = json_decode($blockJSON, true);
                $content = $json['content'];

                foreach ($content as $key => $element) {
                    //echo "<pre>"; print_r($element); echo "</pre>";
                    if ($element['type'] == 'span') {
                        $settings[$element['name']] = [
                            'type'      =>      'string',
                            'value'     =>      editor('spaw', 'standartinis', $element['name'], $element['value']),
                            'name'      =>      $element['name'],
                            
                        ];
                    } else {
                        $settings[$element['name']] = [
                            'type'      =>      $element['type'],
                            'value'     =>      $element['value'],
                            'name'      =>      $element['name']
                            
                        ];
                    }
                }
                $settings[""] = [
                    "type" 		=> "submit",
                    "name" 		=> "addblock",
                    "value" 	=> getLangText('admin','save'),
                    'form_line'	=> 'form-not-line',
                ];
            
           
                $formClass = new Form($settings);
                lentele(getLangText('pageAssembler','new_page'), $formClass->form());

            }

            if (isset($_POST['addblock'])) {
                $content = $json['content'];
                $i=0;
                array_pop($_POST);
                foreach ($_POST as $row) {
                    $content[$i]['value'] = $row;
                    $i++;
                }
                $contentToDb = json_encode($content);
                $sql = "INSERT INTO `" . LENTELES_PRIESAGA . "pa_data` (page_id,type,lang, content) VALUES (" . escape($pageId) . ", " . escape($blockPath) . ", " . escape(lang()) . ", " .escape($contentToDb) . ")";
                mysql_query1($sql);
                unset($sql);
                $_SESSION['page-assembler-pageId'] = null;
                redirect(
                    url("?id," . $url['id'] . ";a," . $url['a'] .";c," . $url['c'] .";pageId," . $url['pageId']),
                    "header",
                    [
                        'type'		=> 'success',
                        'message' 	=> getLangText('pageAssembler','blockAdded')
                    ]
                );
            }

            

            $sql = "SELECT * FROM `" . LENTELES_PRIESAGA . "pa_data` WHERE page_id = " . escape($pageId) . " ORDER BY ID ASC";
            $pageContent = mysql_query1($sql);
            unset($sql);
            if ($pageContent) {
                $extensionPrefix = "../content/extensions";
                echo '<div id="page-builder-zone">';
                if (count($pageContent) > 0) {
                    foreach ($pageContent as $block => $value) {
                        $blockPath = $pageContent[$block]['type'];
                        $blockJSON =  $pageContent[$block]['content'];
                        $content = json_decode($blockJSON, true);
                        $localBlockConfig = json_decode(file_get_contents($blockPath, true), true);
                        $content['orderID'] = $pageContent[$block]['order_id'];
                        $content['parentId'] = $pageContent[$block]['parent_id'];
                        $backEndHtmlFile = $localBlockConfig['configurations']['backEndHtmlFile'];
                        include $extensionPrefix . $backEndHtmlFile;
                    }
                }
            }
        } ?>
        </div>
        <div class="card">
            <div class="header">
                <h2>Add New Block</h2>
            </div>
            <div class="body clearfix">
                <?php
                            checkBlockListStatus();
                $block_list_json = file_get_contents('../content/extensions/pageassembler/block_list.json');
                        
                $block_list = json_decode($block_list_json, true);
                $categoriesCount  = 0; ?>

                <!-- Nav tabs -->
                <ul class="nav nav-tabs tab-nav-right" role="tablist">
                    <?php foreach ($block_list['content'] as $key => $category) {
                    $categoriesCount ++; ?>
                    <li role="presentation" <?php echo($categoriesCount === 1 ? ' class="active"' : ''); ?>>
                        <a href="#<?php echo $key; ?>" data-toggle="tab">
                            <?php echo ucfirst($key.' blocks'); ?>
                        </a>
                    </li>
                    <?php
                } ?>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <?php $pathArray = explode('/', $_SERVER['REQUEST_URI']); ?>
                    <?php for ($i = 0; $i < (sizeof($pathArray)-1); $i++):?>
                    <?php $out[] = $pathArray[$i] ?>
                    <?php endfor ?>
                    <?php $realPath = implode('/', $out); ?>
                    <?php $categoriesCount = 0; ?>
                    <?php foreach ($block_list['content'] as $key => $category) {
                    $categoriesCount ++; ?>
                    <?php $categoryName = $key; ?>
                    <div role="tabpanel" class="tab-pane fade <?php echo($categoriesCount === 1 ? ' active in' : ''); ?>"
                        id="<?php echo $key ?>">
                        <b><?php echo ucfirst($key); ?></b>
                        <p>
                            <?php foreach ($category as $key => $block) {
                        ?>
                            <li>
                                <?php echo '<a tabindex="-1" href="' . $realPath . '/admin;a,' . $url['a'] . ';c,' . $url['c'] . ';pageId,' . $url['pageId'] . ';insertBlock,' . $key . ';blockType,' . $categoryName . '">' ?>
                                <?php echo ucfirst($key.' block') ?>
                                </a>
                            </li>
                            <?php
                    } ?>
                        </p>
                    </div>
                    <?php
                } ?>
                </div>
            </div>
        </div>

        <script>
            function CssFileItraukimas() {
                var link = document.createElement("link");
                src = "../dievai/css/Test.css"; //pakeisti css faila i reikiama
                link.href = src;
                link.type = "text/css";
                link.rel = "stylesheet";
                link.media = "screen,print";

                document.getElementsByTagName("head")[0].appendChild(link);
            }
        </script>
        <script type="text/javascript" src="js/page-assembler.js"></script>
        <script src="js/blocks.js"></script>
        <?php
    }
    

    if ($url['c'] == 'list') {

        // Page Path
        $pathArray = explode('/', $_SERVER['REQUEST_URI']);
        for ($i = 0; $i < (sizeof($pathArray)-1); $i++) {
            $out[] = $pathArray[$i];
        }
        $realPath = implode('/', $out);
                
        echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">';
            echo '<div class="card">';
                echo '<div class="header">';
                    echo '<h2>' . getLangText('pageAssembler','pageassembler_list') . '</h2>';
                echo '</div>';
                echo '<div class="body clearfix">';
                    echo '<div>';
                        $selectSql = mysql_query1("SELECT *FROM `" . LENTELES_PRIESAGA . "page`");
                        echo '<ul>';
                        foreach ($selectSql as $irasas) {
                            $content = '
                                <a href="' . url('?id,' . $url['id'] . ';a,' . $url['a'] . ';d,' . $irasas['id']) . '" style="align:right" onClick="return confirm(\'' . getLangText('admin','delete') . '?\')"><img src="' . ROOT . 'core/assets/images/icons/cross.png" title="' . getLangText('admin','delete') . '" align="right" /></a>
                                <a href="' . url('?id,' . $url['id'] . ';a,' . $url['a'] . ';c,settings;pageId,' . $irasas['id']) . '" style="align:right"><img src="' . ROOT . 'core/assets/images/icons/wrench.png" title="' . getLangText('pageAssembler','pageassembler_settings') . '" align="right" /></a>
                                <a href="' . url('?id,' . $url['id'] . ';a,' . $url['a'] . ';c,edit;pageId,' . $irasas['id']) . '" style="align:right"><img src="' . ROOT . 'core/assets/images/icons/pencil.png" title="' . getLangText('admin','edit') . '" align="right" /></a>
                                <a href="'. MAIN_URL . $irasas['id'] . '" target="_blank">' . $irasas['pavadinimas'] . '</a>';
                            echo '<li>' . $content . '</li>';
                        }
                        echo '</ul>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        echo '</div>';

        if (isset($_POST['delete'])) {
            $page_id =  $irasas['page_id'];
            $irasoTrinimas = mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "page` WHERE id = $page_id");
        }
    }
    if ($url['c'] == 'settings') {
        if (isset($_POST) && !empty($_POST) && isset($_POST['Konfiguracija'])) {
            $pageId = isset($_POST['page_id']) ? $_POST['page_id'] : null;
            $title =  escape($_POST['Pavadinimas']);
            $langText = escape(lang());
            $metaTitle = escape($_POST['metaPavadinimas']);
            $metaDescription =  escape($_POST['metaAprasymas']);
            $metaKeywords = escape($_POST['metaKeywords']);
            $friendlyUrl = escape($_POST['fUrl']);
            $statusID = (int)$_POST['rodymas'];
            
            if (isset($_POST['page_id'])){
                $sqlCheckPageIDstatus = "SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE id = " . escape($pageId);
                $result = mysql_query1($sqlCheckPageIDstatus);
            } else {
                $result = null;
            }
            
            if (count($result) == 0) {
                $sqlPageSettings =
                    "INSERT INTO `" . LENTELES_PRIESAGA . "page` (`pavadinimas`, `metatitle`, `metadesc`, `metakeywords`, `show`, `url`, `lang`, `builder`)
                     VALUES (" . $title . "," . $metaTitle . "," . $metaDescription . "," . $metaKeywords . "," . $statusID . "," . $friendlyUrl . "," . $langText . ", 'assembler')";
            } else {
                $placeId = mysql_query1("SELECT MAX( place ) AS 'max' FROM `" . LENTELES_PRIESAGA . "page`");
                $placeId = $placeId[0]['max']+1;
                $sqlPageSettings ="UPDATE `" . LENTELES_PRIESAGA . "page` SET `pavadinimas` = $title, `file` = $pageId, `metatitle` = $metaTitle, `metadesc` = $metaDescription, 
                 `metakeywords` = $metaKeywords, `show` = $statusID, `url` = $friendlyUrl , `lang` = $langText, `place` = $placeId WHERE `id` = $pageId";
            }
            $result = mysql_query1($sqlPageSettings);
           
            if ($result) {
                if (!isset($_POST['page_id'])) {
                    redirect(
                        url("?id," . $url['id'] . ";a," . $url['a'] . ";c,list"),
                        "header",
                        [
                            'type'		=> 'success',
                            'message' 	=> getLangText('pageAssembler','page_settings_saved')
                        ]
                    );
                    
                } else {
                    $pageId = $prisijungimas_prie_mysql->insert_id;                  
                    redirect(
                        url("?id," . $url['id'] . ";a," . $url['a'] . ";c,edit;pageId," . $pageId),
                        "header",
                        [
                            'type'		=> 'success',
                            'message' 	=> getLangText('pageAssembler','page_settings_saved')
                        ]
                    );
                    
                }
            }
        }
        if (isset($_GET['pageId'])) {
            $pageId = $_GET['pageId'];
            $sqlPageSettings = "SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE id = " . escape($pageId);
            $pageSettings = mysql_query1($sqlPageSettings);
            $pageSettings = $pageSettings[0];
        } else {
            $pageSettings = null;
        }
        
        $settings = [
            "Form" => [
                "action"    => "",
                "method"    => "post",
                "name"      => "reg"
            ],
               "page_Id" => [
                "type" 	=> "hidden",
                "name" 	=> "page_id",
                "value" =>  input($pageSettings['id'])
            ],
            getLangText('admin','title')  => [
                "type"  => "text",
                "value" => input($pageSettings['pavadinimas']),
                "name"  => "Pavadinimas"
            ],
            getLangText('admin','page_metatitle') => [
                "type"  => "text",
                "value" => input($pageSettings['metatitle']),
                "name"  => "metaPavadinimas"
            ],
            getLangText('admin','page_metadesc') => [
                "type"  => "text",
                "value" => input($pageSettings['metadesc']),
                "name"  => "metaAprasymas"
            ],
            getLangText('admin','page_metakeywords')  => [
                "type"  => "text",
                "value" => input($pageSettings['metakeywords']),
                "name"  => "metaKeywords"
            ],
            getLangText('admin','pageStatus') => [
                'type'		=> 'switch',
                "value" 	=> '1',
                'name'		=> 'rodymas',
                'form_line'	=> 'form-not-line',
                'checked' 	=> (input($pageSettings['show']) == 1 ? true : false),
            ],
            getLangText('pageAssembler','page_url') => [
                "type"      => "text",
                "value"     =>  $pageSettings['url'],
                "name"      => "fUrl"
            ],
            ""              => [
                "type"      => "submit",
                "name"      => "Konfiguracija",
                "value"     => getLangText('admin','save'),
                'form_line' => 'form-not-line',
            ]
        ];
        $formClass = new Form($settings);
        if (isset($_GET['pageId'])) {
            lentele(getLangText('pageAssembler','pageassembler_settings') , $formClass->render());
        } else {
            lentele(getLangText('pageAssembler','new_page'), $formClass->render());
        }
    } ?>
<div class='modal-insert-place'></div>

<script src="js/menuclick.js" async></script>
<script src="js/class.insertblock.js" async></script>

<?php
}
