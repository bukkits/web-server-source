<?php include "../functions.php"; ?>
<html>
<head>
	<title>Plugin Generator</title>
</head>
<body>
<h1>Plugin Generator</h1>
<form action="pg-1.php" method="post">
	<p><strong>Plugin information</strong></p>
	<table>
		<tr>
			<th align="right">Plugin Name</th>
			<td align="left"><input name="name" type="text"></td>
		</tr>
		<tr>
			<th align="right">Version</th>
			<td align="left"><input name="version" type="text" value="1.0.0"></td>
		</tr>
		<tr>
			<th align="right">Author</th>
			<td align="left"><input name="author" type="text" value=""></td>
			<td aligh="left"><em>Separate multiple authors by commas (<code>,</code>)</em></td>
		</tr>
	</table>
</form>
</body>
</html>