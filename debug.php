<pre><?php

$ip = $_SERVER["REMOTE_ADDR"];
if($ip !== "::1" and substr($ip, 0, 10) !== "192.168.1."){
	http_response_code(403);
	echo "403 Forbidden\r\nfor IP $ip";
	return;
}

$lines =$_GET["lines"];
$contents = explode("\r\n", file_get_contents("../logs/error.log"));
$cnt = count($contents);
$lines = min($cnt, $lines);
for($i = 1; $i <= $lines; $i++){
	echo $contents[$cnt - $i];
	echo "<br>";
}
?>
</pre>
