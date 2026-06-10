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
This is main page<br><br>
<form action="18.php" method="post">
<input type="submit" name="lo" value="Sign Out">
</form>
<?php
	echo $_SESSION["uname"]."<br>";
	echo $_SESSION["pword"]."<br>";	
	if(isset($_POST["lo"])){
		session_destroy();
		header("location: 17.php");
		//echo $_SESSION["uname"]."<br>";
		//echo $_SESSION["pword"]."<br>";
	}
?>