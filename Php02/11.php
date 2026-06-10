<html>
<title>Sanitize</title>
<head>
</head>
<body>  
<form action="11.php" method="post">
<table border=1 bgcolor=yellow>
<tr>
<td><label>Username: </label></td>
<td><input type="text" name="un" value=""></td>
</tr>
<tr>
<td><label>Age: </label></td>
<td><input type="text" name="age" value=""></td>
</tr>
<tr>
<td><label>Email: </label></td>
<td><input type="text" name="email" value="" required></td>
</tr>
<tr>
<td colspan=2 align=center>
<input type="submit" name="login" value="Submit">
<input type="reset" name="clear" value="Reset">
</td>
</tr>
</table>
</form> 
</body>
</html>
<?php
	if(isset($_POST["login"])){
		$uname=filter_input(INPUT_POST,"un",
		FILTER_SANITIZE_SPECIAL_CHARS);
		$ages=filter_input(INPUT_POST,"age",
		FILTER_SANITIZE_NUMBER_INT);
		$emails=filter_input(INPUT_POST,"email",
		FILTER_SANITIZE_EMAIL);
		echo "Hello $uname!<br>";
		echo "You are $ages old.<br>";
		echo "Your email is: $emails<br>";
	}
?>