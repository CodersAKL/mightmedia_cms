<?php

/**
 * HOOKS
 */
require_once (config('class', 'dir') . 'class.hooks.php');

$mmHooks = new Hooks();

function doAction($tag)
{
	global $mmHooks;
	// $hooks = new Hooks();

	return $mmHooks->do_action($tag);
}

function addAction($tag, $callback, $priority = 50)
{
	global $mmHooks;
	// $hooks = new Hooks();

	return $mmHooks->add_action($tag, $callback, $priority);
}

function addFilter($tag, $callback, $priority = 50, $include_path = null, $enabled = true)
{
	global $mmHooks;
	// $hooks = new Hooks();

	return $mmHooks->add_filter($tag, $callback, $priority, $include_path, $enabled);
}

function applyFilters($tag, $value = [])
{
	global $mmHooks;
	// $hooks = new Hooks();

	return $mmHooks->apply_filters($tag, $value);
}