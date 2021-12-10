<?php

/**
 * Nuskaitom turinį iš adreso
 *
 * @param string $url
 *
 * @return string
 */
if(! function_exists('http_get')) {
	function http_get($url) {

		$request = fopen( $url, "rb" );
		$result  = "";
		while ( !feof( $request ) ) {
			$result .= fread( $request, 8192 );
		}
		fclose( $request );

		return $result;
	}
}

if(! function_exists('postRemote')) {
	function postRemote($url, $data)
	{
		//open connection
		$ch = curl_init();

		$curlConfig = [
			CURLOPT_URL            => $url,
			CURLOPT_POST           => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS     => $data,
			// not secure stuff
			// todo: remove it or change 
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0
		];

		curl_setopt_array($ch, $curlConfig);

		if (! $result = curl_exec($ch)) {
			echo curl_error($ch);
		}
		//close connection
		curl_close($ch);

		return $result;
	}
}