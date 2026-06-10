<?php
	
	echo "Test PHP".'<br><br>';
	
	$uni="IEU";	
	$abc1= "Hello $uni"; 
	$abc2 = 'Welcome to PHP $uni'; 
	echo $abc1 . '<br>';
	echo $abc2 . '<br>';
	echo 'Welcome '.'to PHP'.'Izmir'.'<br>';
	
	$abc3 = '  Welcome to Economy  ';
	echo '1 - '.strlen($abc3).'<br>';
	echo '2 - '.trim($abc3).'<br>';
	echo '3 - '.str_word_count($abc3).'<br>';
	echo '4 - '.ltrim($abc3).'<br>';
	echo '5 - '.rtrim($abc3).'<br>';
	echo "6 - " . strrev($abc3) . '<br>';
	echo "7 - " . strtoupper($abc3) . '<br>';
	echo "8 - " . strtolower($abc3) . '<br>';
	echo "9 - " . ucfirst('welcome') . '<br>';
	echo "10 - " . lcfirst('WELCOME') . '<br>';
	echo "11 - " . ucwords('welcome to economy') . '<br>';
	echo "12 - " . strpos($abc3, 'Economy') . '<br>';
	echo "13 - " . strpos($abc3, 'economy') . '<br>';
	echo "14 - " . stripos($abc3, 'economy') . '<br>';
	echo "15 - " . substr($abc3, 3,5) . '<br>';
	echo "16 - " . str_replace('Se362', 'IEU', 'Welcome to Se362') . '<br>';
	echo "17 - " . str_ireplace('economy', 'IEU', $abc3) . '<br>';
	
	echo '<br>';

	$innum=100;
	$innum2=123456;
	echo str_pad($innum, 5, '0', STR_PAD_RIGHT).'<br>';
	echo str_pad($innum2, 9, '9', STR_PAD_LEFT).'<br>';
	echo str_repeat($innum2, 3).'<br>';
	echo str_repeat('hello ', 3).'<br>';

	echo '<br>';

	$longText = "
	Hello, my name is Ayselin
	I am 35,
	I love my daughter
  	";
 	echo $longText . '<br>';
  	echo nl2br($longText) . '<br>';
	
 	echo '<br>';

	$longText = "
	Hello, my name is <b>Ayselin</b>
	I am <b>35</b>,
	I love my daughter
	";	
	echo "1 - " .$longText . '<br>';
	echo "2 - " .nl2br($longText) . '<br>';
	echo "3 - " . htmlentities($longText) . '<br>';
	echo "4 - " . nl2br(htmlentities($longText)) . '<br>';
	echo "5 - " . html_entity_decode(htmlentities($longText)) . '<br>';
	echo "6 - " . htmlspecialchars($longText) . '<br>';

https://www.php.net/manual/tr/ref.strings.php


?>