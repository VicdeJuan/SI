<?php

$applicationBaseDir = $_SERVER['CONTEXT_PREFIX'];

function asAbsolutePath($path) 
{
	return $_SERVER['DOCUMENT_ROOT'].$path;
}

function asAbsoluteUrl($url)
{
	global $applicationBaseDir;
	return $applicationBaseDir.$url;
}

?>