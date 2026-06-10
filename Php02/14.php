<?php
	setcookie("fruit","melon",time()+(86400*2),"/");
	setcookie("desert","keskul",time()+(86400*2),"/");
	setcookie("drink","cay",time()+(86400*3),"/");
	setcookie("meal","kuru and pilav",time()+(86400*2),"/");
	setcookie("side","yogurt",time()-0,"/");
	foreach($_COOKIE as $key=>$value){
		echo "$key=$value <br>";
	}
	if (isset($_COOKIE["meal"])){
		echo "Buy some {$_COOKIE["meal"]}!!!";
	}
	else {
		echo "No info is given";
	}
?>