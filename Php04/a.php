<?php 
	$dbt="localhost";
	$dbtu="root";
	$dbtp="";
	$dbtn="deneme";
	$dbtc="";
	try{
	$dbtc=mysqli_connect($dbt,$dbtu,$dbtp,$dbtn);
	}
	catch(mysqli_sql_exception){
		echo "Could not connect!";//header("location:www.w3schools.com");
	}	
	if($dbtc){
		echo "Connection is established!<br>";
	}
?>
