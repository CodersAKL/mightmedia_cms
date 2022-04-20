<?php

/**
 * HOOKS
 */
require_once (config('class', 'dir') . 'class.hooks.php');

function doAction($tag, $arg = '')
{
	$hooks = Hooks::getInstance();

	return $hooks->do_action($tag, $arg);
}

function addAction($tag, $callback, $data = null)
{
	$hooks = Hooks::getInstance();

	return $hooks->add_action($tag, $callback, $data);
}

function applyFilters($tag, $value)
{
	$hooks = Hooks::getInstance();

	return $hooks->apply_filters($tag, $value);
}