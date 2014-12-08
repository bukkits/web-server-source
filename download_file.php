<?php

http_response_code(403);
return;
/** @noinspection PhpUnreachableStatementInspection */

if(!isset($_GET["file"])){
	http_response_code(400);
}

$file = realpath($_GET["file"]);
$allowed = realpath(htdocs . "data/");
if(!is_file($file)){
	http_response_code(404);
	return;
}
if(substr($file, 0, strlen($allowed)) !== $allowed){
	http_response_code(403);
	return;
}

header("Content-Type", "application/octet-stream");

echo file_get_contents($file);
