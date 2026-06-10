<?php 
	include("a.php");
	$sql="update users set uname='arty' where uid='14'";
	$result=mysqli_query($dbtc,$sql);
	mysqli_close($dbtc);
?>
