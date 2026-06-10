<?php 
	include("a.php");
	$mobile="122333";
	$sql="INSERT INTO users (uname,password,name,surname,email,mobile) VALUES ('hanzo','toto','cuti','totor','cutingo',$mobile)";
	try{
		mysqli_query($dbtc,$sql);
		echo "User is registered";
	}
	catch(mysqli_sql_exception){
		echo "User is already registered";
	}	
	mysqli_close($dbtc);
?>
