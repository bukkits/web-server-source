<?php
include "../../functions.php";
?>
<html>
<head>
	<title>Plugin Builds</title>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="js/indexScroll.js"></script>
</head>
<body bgcolor="#f0ffff"><font face="Comic Sans MS">
<h1><a name="pagetop">Plugin Builds</a></h1>
<p>
	<b>About branches/pull requests</b>: Plugins are developed at the <code>master</code> branch. If you see branches other than <code>master</code>, they usually contain code that is unstable but adds new features to the plugin. Once they are stable or finished, they will be merged into the <code>master</code> branch. Therefore, you are discouraged to use non-<code>master</code>-branch builds.<br>
	Builds of pull requests are built from code modified by other people on GitHub (or from other branches). They may be dangerous. Look into that pull request's link for details. You are encouraged to use <a href="?branches=master"><code>master</code> branch builds only</a>.<br>
	Some repos may not have <code>master</code> as their default branch. Non-default branches will be in red, so only builds of the default branch will be available.
</p>
<?php
$projects = [];
foreach(scandir(".") as $owner){
	if(trim($owner, ".") !== "" and is_dir($owner)){
		foreach(scandir($owner) as $project){
			if(trim($project, ".") !== "" and is_dir("$owner/$project")){
				if(strpos("$owner/$project", "_vti_") === false){
					$projects[] = "$owner/$project";
				}
			}
		}
	}
}
echo "<ul>";
foreach($projects as $project){
	$p = str_replace("/", "-", $project);
	echo "<li><a href='#top-$project' onclick='javascript:scrollTo(\"$p\");'>$project</a></li>";
}
echo "</ul>";
if(isset($_GET["branches"]) and $_GET["branches"] !== "*"){
	$allowedBranches = explode(",", $_GET["branches"]);
}
foreach($projects as $fullName){
	$defaultBranch = json_decode(utils_getURL("https://api.github.com/repos/$fullName"), true)["default_branch"];
	echo "<br><hr>";
	echo "<div align='right'><a href='#pagetop' onclick='javascript:scrollToTop();'>
			<font size='2'>Back to top</font></a></div>";
	$id = "top-" . str_replace("/", "-", $fullName);
	echo "<h2><a name='top-$fullName' id='$id' href='https://github.com/$fullName'
			target='_blank'>$fullName</a></h2>";
	echo "<p>Default branch: <code>$defaultBranch</code></p>";
	echo "<table border='1' width='1000'>";
	echo "<tr>";
	echo "<th><font color='#FF4000'>Branch</font> / <font color='#44DF00'>Pull Request</font></th>";
	echo "<th>Commit SHA</th>";
	echo "<th>Built at</th>";
	echo "<th>Download GZIP archive</th>";
	echo "</tr>";
	$files = [];
	foreach(scandir($fullName) as $branch){
		if(trim($branch, ".") === ""){
			continue;
		}
		foreach(scandir("$fullName/$branch") as $file){
			if(substr($file, -5) === ".phar" and is_file($path = "$fullName/$branch/$file")){
				$files[filemtime($path)] = ["path" => $path, "commit" => substr(substr($file, 7), 0, -5), "branch" => $branch];
			}
		}
	}
	krsort($files, SORT_NUMERIC);
	$g = true;
	foreach($files as $time => $file){
		$branch = $file["branch"];
		$b_ = $branch;
		if($isPr = (substr($branch, 0, 1) === "#")){
			$b_ = "pr";
		}
		if(isset($allowedBranches) and !in_array($b_, $allowedBranches)){
			continue;
		}
		$g = !$g;
		$bg = $g ? "bgcolor='#cffccf'":"";
		echo "<tr $bg>";
		$path = $file["path"];
		$commit = $file["commit"];
		$date = date("M j, Y \\a\\t H:i:s \\U\\T\\C", $time);
		echo "<td align='center'>";
		if($isPr){
			$url = "https://github.com/$fullName/pull/" . substr($branch, 1);
			echo "<a href='$url' target='_blank'><font color='#44DF00'>$branch</font></a>";
		}
		elseif($branch !== $defaultBranch){
			echo "<a href='https://github.com/$fullName/tree/$branch' target='_blank'><font color='#FF4000'>$branch</font></a>";
		}
		else{
			echo $branch;
		}
		echo "</td>";
		echo "<td align='center'>";
		echo "<a href='$path'>Download $commit</a>";
		if(filesize($path) < 1024){
			echo "<br><font color='#f04020'>Warning: this file seems to be too small. It might be a corrupted file!</font>";
		}
		echo "</td>";
		echo "<td align='center'><a href='https://github.com/$fullName/tree/$commit' target='_blank'>$date</a></td>";
		echo "<td align='center'><a href='$path.gz'>Download</a></td>";
		echo "</tr>";
	}
	echo "</table>";
}
?>
</font>
<hr>
<form method="GET">
	<p align="justify">
		<input type="text" value="*" name="branches"><br>
		Only show these branches. Separate branches by a comma (,) without spaces.<br>
		For pull request filters, type <code>pr</code>.<br>
		<input type="submit" value="Filter">
	</p>
</form>
<hr>
<footer>
	<p align='center'>Page generated at <i><?php echo date("M j, Y \\a\\t H:i:s \\U\\T\\C"); ?></i><br>
		This file is now open-source on <a href="https://github.com/PEMapModder/web-server-source" target="_blank">GitHub</a>.</p>
</footer>
</body>
</html>
