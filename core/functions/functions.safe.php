<?php

/**
 * safeData
 *
 * @param  mixed $data
 * @return void
 */
function safeData(array $data)
{
	foreach ($data as $kData => $vData) {
		if(is_array($vData)) {
			$data[$kData] = safeData($vData);
		} else {
			$data[$kData] = htmlspecialchars($vData, ENT_QUOTES, 'UTF-8');
		}
	}
	
	return $data;
}

/**
 * decodeSafeData
 *
 * @param  mixed $str
 * @return void
 */
function decodeSafeData(string $str)
{
	return htmlspecialchars_decode($str, ENT_QUOTES);
}