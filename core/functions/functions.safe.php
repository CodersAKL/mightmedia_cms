<?php

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