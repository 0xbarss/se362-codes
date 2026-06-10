<html>
<title>Sanitize</title>
<head>
</head>
<body>  
<form action="10.php" method="post">
<table border=1 bgcolor=yellow>
<tr>
<td><label>Username: </label></td>
<td><input type="text" name="un" value=""></td>
</tr>
<tr>
<td><label>Password: </label></td>
<td><input type="password" name="pw" value=""></td>
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
		$uname=$_POST["un"];
		$pword=$_POST["pw"];
		echo "Hello $uname <br>";
		echo "Your password is $pword";		
	}
?>

<script>alert("Virus")</script>