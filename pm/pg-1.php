<?php
include "../functions.php";
if(!isset($_POST["class"])){
	header("Location: pg.php?notice=" . urlencode("<font color='#FF2020'>Fatal: Required post field(s) <code>class</code> missing!</font>"));
	return;
}
switch($_POST["class"]){
	case "init":
		if(!isset($_POST["name"], $_POST["version"], $_POST["api"])){
			header("Location: pg.php?notice=" . urlencode("<font color='#FF2020'>Fatal: Required post field(s) <code>name</code>, <code>version</code> or <code>api</code> missing</font>"));
			return;
		}
		if(preg_match_all('#^[A-Za-z0-9+]+$#', $_POST["name"]) === 0){
			header("Location: pg.php?notice=" . urlencode("<font color='#FF2020'>Fatal: Plugin name should only consist of <code>A</code>-<code>Z</code>, <code>a</code>-<code>z</code>, <code>0</code>-<code>9</code> or <code>_</code>.</font>"));
			return;
		}
		if(!isset($_POST["author"])){
			$authors = [];
		}
		else{
			$authors = explode(",", $_POST["author"]);
		}
		session_start();
		$_SESSION["manifest"] = [
			"name" => $_POST["name"],
			"author" => $_POST["author"],
			"version" => $_POST["version"],
			"api" => $_POST["api"],
			"authors" => $authors,
			"permission" => [
				strtolower($_POST["name"]) => [
					"description" => $_POST["name"] . " main permission node",
					"default" => "op",
					"children" => []
				]
			]
		];
		$_SESSION["namespace"] = randomClass(16);
		$_SESSION["manifest"]["main"] = $_SESSION["namespace"] . "\\MainClass";
		$_SESSION["commands"] = [];
		/*
		 * example: [
		 *   "name" => "name",
		 *   "desc" => "desc msg",
		 *   "usage" => "usage msg",
		 *   "perm" => "perm node name",
		 *   "action" => "function name"
		 * ]
		 */
		$_SESSION["events"] = [];
		$_SESSION["functions"] = [];
		break;
}
?>
<html>
<head>
	<title>Plugin Generator</title>
</head>
<body>
<h1>Add elements to your plugin</h1>
<?php
if(count($_SESSION["commands"]) > 0){
	echo "<table border='1'>";
	echo "<tr>";
	echo "<th>Name</th>";
	echo "<th>Description</th>";
	echo "<th>Usage message</th>";
	echo "</tr>";
	foreach($_SESSION["commands"] as $cmd){
		echo "<tr><td>";
		echo htmlspecialchars($cmd["name"]);
		echo "</td><td>";
		echo htmlspecialchars($cmd["desc"]);
		echo "</td><td>";
		echo htmlspecialchars($cmd["usage"]);
		echo "</td></tr>";
	}
	echo "</table>";
}
?>
<button onclick='window.location.href = "pgAddCommand.php";'>Add command</button>
</body>
<footer>
	<a href="resetSession.php?redirect=pg.php">Reset all</a>
</footer>
</html>
