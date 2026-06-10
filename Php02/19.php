<?php
	$password="dolako1980";
	$hash=password_hash($password, PASSWORD_DEFAULT);
	echo $hash."<br>";
	if(password_verify("solako1980",$hash)){
		echo "Success!";
	}
	else {
		echo "Fail!";
	}
?>