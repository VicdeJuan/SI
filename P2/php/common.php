<?php

$applicationBaseDir = $_SERVER['CONTEXT_PREFIX'];

function asAbsolutePath($path) 
{
	return $_SERVER['CONTEXT_DOCUMENT_ROOT'].$path;
}

function asAbsoluteUrl($url)
{
	global $applicationBaseDir;
	return $applicationBaseDir.$url;
}

?>