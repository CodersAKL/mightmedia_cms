<?php

/**
 * HOOKS
 */
require_once (config('class', 'dir') . 'class.hooks.php');

if(! function_exists('doAction')) {
	function doAction($tag, $value)
	{
		$hooks = Hooks::getInstance();

		return $hooks->do_action($tag, $value);
	}
}

if(! function_exists('addAction')) {
	function addAction($tag, $callback)
	{
		$hooks = Hooks::getInstance();

		return $hooks->add_action($tag, $callback);
	}
}

if(! function_exists('applyFilters')) {
	function applyFilters($tag, $value)
	{
		$hooks = Hooks::getInstance();

		return $hooks->apply_filters($tag, $value);
	}
}