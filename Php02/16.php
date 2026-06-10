<?php
	session_start();
?>
<html>
<title>
Main
</title>
<head>
</head>
<body>
This is main page<br><br><br><br>
<a href="15.php">This link is for login page</a><br><br>
<?php
	echo $_SESSION["uname"]."<br>";
	echo $_SESSION["pword"]."<br>";	
?>