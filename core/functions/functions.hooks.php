<?php

/**
 * HOOKS
 */
require_once (config('class', 'dir') . 'class.hooks.php');

$mmHooks = Hooks::getInstance();

function doAction($tag, $arg = '')
{
	global $mmHooks;

	return $mmHooks->do_action($tag, $arg);
}

function addAction($tag, $callback)
{
	global $mmHooks;

	return $mmHooks->add_action($tag, $callback);
}

function applyFilters($tag, $value = [])
{
	global $mmHooks;

	return $mmHooks->apply_filters($tag, $value);
}