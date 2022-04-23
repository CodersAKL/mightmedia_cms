<?php

require_once config('class', 'dir') . 'class.postType.php';

function createPostType(array $params = [])
{
	$posType = new PostType;

	$posType->setArgs($params);
	$posType->createPostType();

}

function getPostTypeParams($name = null)
{
	$posType = new PostType;

	return $posType->getPostType($name);
}


