<?php

include "../functions.php";

$headers = apache_request_headers();

if(!isset($headers["Report"])){
	echo "400 Bad Requeset\r\nMissing header: Report";
	http_response_code(400);
	return;
}
$reportData = unserialize($headers["Report"]);
if(!is_array($reportData)){
	echo "400 Bad Request\r\nJSON decode error";
}
foreach(["repo", "plugin", "event", "exception", "sha"] as $attr){
	if(!isset($reportData[$attr])){
		http_response_code(400);
		echo "400 Bad Request\r\nMissing attribute: $attr";
		return;
	}
}
$repo = $reportData["repo"];
$plugin = $reportData["plugin"];
$event = $reportData["event"];
$ex = $reportData["exception"];
$sha = $reportData["sha"];

$reports = htdocs . "data/reports/$repo/";
@mkdir($reports, 0777, true);
if(!is_dir($reports)){
	http_response_code(500);
	echo "500 Internal Server Error\r\nUnable to create directory $reports";
}
for($id = 1; is_file($file = $reports . "$id.htm") or is_file($jsonFile = $reports . "$id.json"); $id++);

file_put_contents($jsonFile, json_encode($reportData, JSON_PRETTY_PRINT), LOCK_EX);

$os = fopen($file, "wt");
flock($os, LOCK_EX);
fwrite($os, <<<EOH
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>Exception report #$id</title>
</head><body>
EOH
);
fwrite($os, "<h1>$repo Exception Report #$id</h1>");
fwrite($os, "<h3>Plugin version:</h3>");
fwrite($os, "<p>" . $plugin["version"] . "</p>");
fwrite($os, "<h3>Event</h3>");
$eventString = "<p>";
htmlArrayToString($event, $eventString);
fwrite($os, $eventString . "</p>");
fwrite($os, "<h3>Exception</h3>");
fwrite($os, "<p><code>" . $ex["class"] . "</code>: " . $ex["message"] . "<br>");
fwrite($os, sprintf("Thrown at line %d of file %s</p>", $ex["line"], $ex["file"]));
fwrite($os, "<h3>Stack trace</h3><hr><pre>");
fwrite($os, $ex["trace"]);
fwrite($os, "</pre>");
fwrite($os, "<h3>Code:<hr><pre>");
fwrite($os, $ex["code"]);
fwrite($os, "</pre>");
fwrite($os, "<h3>Commit SHA: ");
if(is_string($sha)){
	fwrite($os, "<code>$sha</code>");
}
else{
	fwrite($os, "Unknown");
}
fwrite($os, "</h3>");
fwrite($os, "</body></html>");
fflush($os);
flock($os, LOCK_UN);
fclose($os);

http_response_code(201);
echo "201 Created";

function htmlArrayToString(array $array, &$output, $indent = 0){
	foreach($array as $key => $value){
		$output .= str_repeat("&nbsp;", $indent * 4);
		$key = ucfirst($key);
		$output .= "<b>$key</b>:";
		if(is_array($value)){
			htmlArrayToString($value, $output, $indent + 1);
		}
		else{
			$output .= " $value<br>";
		}
	}
}
