<?php
session_start();
if(!isset($_SESSION["manifest"])){
	header("Location: pg.php");
	return;
}
?>
<html>
<head>
	<title>Plugin Generator | Add Command</title>
</head>
<body>
	<h1>Add Command</h1>
<form action="pgAddCommandListener.php" method="post">
	<input type="text" name="name">
	<input type="submit" value="Add">
</form>
</body>
</html>
