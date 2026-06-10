<?php 
	include("a.php");
	$sql="SELECT * FROM users";
	$result=mysqli_query($dbtc,$sql);
	if(mysqli_num_rows($result)>0){
		while($row=mysqli_fetch_assoc($result)){
		echo $row["uid"]."<br>";
		echo $row["uname"]."<br>";
		echo $row["password"]."<br>";
		echo $row["name"]."<br>";
		echo $row["surname"]."<br>";
		echo $row["email"]."<br>";
		echo $row["mobile"]."<br>";
		echo $row["regdate"]."<br><br>";		
		};
	}	
	else{
		echo "No results found";
	}
	mysqli_close($dbtc);
?>
