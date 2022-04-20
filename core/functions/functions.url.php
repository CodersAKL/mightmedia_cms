<?php
/**
 * Adress security
 *
 * @param string $url
 *
 * @return string
 */

function cleanUrl($url) {

	$bad_entities  = ['"', "'", "<", ">", "(", ")", '\\'];
	$safe_entities = ["", "", "", "", "", "", ""];
	$url           = str_replace($bad_entities, $safe_entities, $url);

	return $url;
}

/**
 * Adress cleaning
 *
 * @param string $rawUrl
 *
 * @return string
 */

function urlClean($rawUrl) {
	$url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
	$urlPart = explode('://', $rawUrl);
	$url .= '://' . cleanUrl($urlPart[1]);
	$lastSymbol = substr($url, -1);

	if($lastSymbol !== '/') {
		$url .= '/';	
	}

	return $url;
}

function siteUrl($trimSlash = false)
{
	$url = getOption('site_url');

	if($trimSlash) {
		return rtrim($url, '/\\');
	}

	return $url;
}

function adminMakeUrl($name = '')
{
	return '/' . ADMIN_DIR . '/' . $name;
}

function adminUrl($part = '')
{
	
	return siteUrl(true) . adminMakeUrl($part);
}