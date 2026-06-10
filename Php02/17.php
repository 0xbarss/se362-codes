<?php
	session_start();
?>
<html>
<title>Login</title>
<head>
</head>
<body>  
<form action="17.php" method="post">
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
<input type="submit" name="login" value="Sign In">
<input type="reset" name="clear" value="Reset">
</td>
</tr>
</table>
</form> 
</body>
</html>
<?php

	if(isset($_POST["login"])){ 	
			
			if(!empty($_POST["un"])&& !empty($_POST["pw"])){
			$_SESSION["uname"]=$_POST["un"];
			$_SESSION["pword"]=$_POST["pw"];
			//echo $_SESSION["uname"]."<br>";
			//echo $_SESSION["pword"]."<br>";	
			header("location: 18.php");
			}
			else{
			echo "Missing username/password";
			}
	
	}
?>