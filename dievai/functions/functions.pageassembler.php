<?php

//Funkcija sukuria unikalu bloku id
	function unikalusId(){
		return  md5(uniqid(rand(0, 1000000), true));
		
	}

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
					$sql1 = "CREATE TABLE `" . LENTELES_PRIESAGA . "pa_page_settings` (
						page_id INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
						title TEXT,
						lang TEXT,
						meta_title TEXT,
						meta_desc TEXT,
						meta_keywords TEXT,
						friendly_url TEXT,
						status_id INT(2)
						) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;";
					break;
			}
			$result = mysqli_query( $prisijungimas_prie_mysql, $sql1 ); 
			$result = (1 == $result) ? true : false;
		}
		return $result;
	}

	function checkBlockListStatus(){
		// var_dump(__DIR__);
		// var_dump(EXTENSIONS_ROOT);
		// 
		// $path = ROOT . 'page_assembler/block_list.json';
		// // $path = siteUrl() . 'extensions/page_assembler/block_list.json';
		// $getJson = file_get_contents($path, true);

		$getJson = '{
			"content":{
			   "form_blocks":{
				  "registration_form":"..\/extensions\/page_assembler\/form_blocks\/registration_form\/config.json"
			   },
			   "list_blocks":{
				  "12-col-list":"..\/extensions\/page_assembler\/list_blocks\/12-col-list\/config.json"
			   },
			   "team_blocks":{
				  "team_block":"..\/extensions\/page_assembler\/team_blocks\/team_block\/config.json"
			   },
			   "text_blocks":{
				  "2-col-text-1-img":"..\/extensions\/page_assembler\/text_blocks\/2-col-text-1-img\/config.json",
				  "4-col-text-spans":"..\/extensions\/page_assembler\/text_blocks\/4-col-text-spans\/config.json"
			   }
			},
			"generated":1546448899
		 }';

		

		if ($blockList = json_decode($getJson)){
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

	function generateNewBlockList($path = '../extensions/page_assembler/'){
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
?>

