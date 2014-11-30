<?php

$applicationBaseDir = $_SERVER['CONTEXT_PREFIX'];

if (!isset($applicationBaseDir))
{
	if(preg_match("@/home/(\\w+)/public_html.*@", $_SERVER['SCRIPT_FILENAME'], $matches) === 1)
		$applicationBaseDir = "/~{$matches[1]}/";
}

if(!isset($applicationBaseDir) || strlen($applicationBaseDir) == 0 || substr($applicationBaseDir, -1) !== "/")
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

function getTableRowCount($pdo, $table)
{
	$sql = "SELECT count(*) FROM ".$table.";";
	$result = $pdo->prepare($sql);
	$result->execute();
	$rows = $result->fetchAll();
	return $rows[0][0];
}

?>
