<?php


$applicationBaseDir = $_SERVER['CONTEXT_PREFIX'];

if(strlen($applicationBaseDir) == 0 || substr($applicationBaseDir, -1) !== "/")
	$applicationBaseDir .= '/';

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