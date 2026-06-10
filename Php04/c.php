<?php 
	include("a.php");
	$sql="SELECT * FROM users WHERE name='cuti'";
	$result=mysqli_query($dbtc,$sql);
	if(mysqli_num_rows($result)>0){
		$row=mysqli_fetch_assoc($result);
		echo $row["uname"]."<br>";
	}	
	else{
		echo "No results found";
	}
	mysqli_close($dbtc);
?>
