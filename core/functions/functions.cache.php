<?php

/**
 * Sandeliukui valyti
 *
 * @param string $query
 */
if(! function_exists('delete_cache')) {
	function delete_cache($key) {

		$fileName = realpath(dirname(__FILE__) . '/..') . '/content/cache/' . md5($key) . '.php';
		if (is_file($fileName)) {
			unlink($fileName);
		}
	}
}

if(! function_exists('cachePutData')) {
	function cachePutData($key, $data, $lifeTime = []) {
		$path = realpath(dirname(__FILE__) . '/..') . '/content/cache/';
		$fileName = md5($key) . '.php';
		$filePath = $path . $fileName;
		
		if (is_file($filePath)) {
			unlink($filePath);
		}

		$fh = fopen($filePath, 'wb') or die("Išvalyk <b>/content/cache</b> bylą");

		//insert data life time
		$data['lifetime'] = $lifeTime;

		if(! is_string($data)) {
			$data = json_encode($data, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
		}

		// Reikia užrakinti failą, kad du kartus neįrašytų
		if (flock($fh, LOCK_EX)) { // užrakinam
			fwrite($fh, $data);
			flock($fh, LOCK_UN); // atrakinam
		} else {
			echo "Negaliu užrakinti failo !";
		}

		// Baigiam failo įrašymą
		fclose($fh);
	}
}

if(! function_exists('cacheGetData')) {
	function cacheGetData($key, $array = true) {
		$path = realpath(dirname(__FILE__) . '/..') . '/content/cache/';
		$fileName = $path . md5($key) . '.php';
		
		if (is_file($fileName)) {
			// Užkraunam kešą
			$content 	= file_get_contents($fileName);
			$data 		= json_decode($content, $array);

			if(filemtime($fileName) > time() - $data['lifetime']) {
				unset($data['lifetime']); //we don't need this in data after check

				return $data;
			}
		}

		return false;
	}
}
