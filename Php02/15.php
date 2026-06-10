<?php
	session_start();
?>
<html>
<title>
SE362
</title>
<head>
</head>
<body>
<br>
This is login page<br><br><br><br>
<a href="16.php">This link is for main page</a><br><br>
</body>
</html>
<?php
	$_SESSION["uname"]="izincir";
	$_SESSION["pword"]="tonton123";
	echo $_SESSION["uname"]."<br>";
	echo $_SESSION["pword"]."<br>";	
?>