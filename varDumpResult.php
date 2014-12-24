<html><head><title>var_dump Result</title></head>
<body>
<font face="Comic Sans MS" size="4">

<?php
use vardump\VarDumpParser;

include "functions.php";

if(!isset($_POST["dump"])){
	echo "Missing post field 'dump'";
}

$parser = new VarDumpParser($_POST["dump"]);

try{
	$var = $parser->readVar();
	echo "<p>Variable content:</p>";
	$var->presentInHtml();
}
catch(Exception $e){
	echo "Error parsing dump: <code>{$e->getMessage()}</code>";
}

?>

</font>
</body>
</html>
<?php

?>
