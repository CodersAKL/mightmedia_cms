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

function siteUrl($urlString = null, $trimSlash = false)
{
	$url = getOption('site_url');

	if($trimSlash) {
		return rtrim($url, '/\\');
	}

	return ! empty($urlString) ? $urlString : '' . $url;
}

function adminMakeUrl($name = '')
{
	return '/' . ADMIN_DIR . '/' . $name;
}

function adminUrl($part = '')
{
	
	return siteUrl(adminMakeUrl($part), false);
}

/**
 * redirect
 *
 * @param  string $routeName
 * @param  array $data
 * @param  string $type
 * 
 */
function redirect(string $routeName, array $data = [], string $type = 'header') 
{
	setFlashMessages($routeName, $data['type'], $data['message']);

	$url = siteUrl(getRouteUrl($routeName), true);

	if($type == 'header') {
		header('Location: ' . $url);
		
		exit;
	}
	// TODO: add meta and script redirect
}