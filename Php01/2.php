<?php
	$name="Cem";
	$age=20;
	$isMale=true;
	$height=1.85;
	$salary=null;
	echo $name.'<br>';
	echo $age.'<br>';
	echo $isMale.'<br>';
	echo $salary.'<br>';
	echo $height.'<br><br>';

	echo gettype($name).'<br>';
	echo gettype($age).'<br>';
	echo gettype($isMale).'<br>';
	echo gettype($salary).'<br>';
	echo gettype($height).'<br><br>';

	var_dump($name, $age, $isMale, $salary, $height);

	echo '<br><br>';

	$name=true;
	echo gettype($name).'<br>';

	echo '<br><br>';

	echo is_bool($isMale).'<br>';
	echo is_int($age).'<br>';
	var_dump(is_int($age));
	echo '<br><br>';

	echo isset($namec).'<br>';
	var_dump(isset($name));
	var_dump(isset($age));
	var_dump(isset($salary));
	echo '<br><br>';

	define('PI', 3.1452);
	echo PI.'<br>';
	var_dump(defined('PI'));
	echo '<br>'.defined('PI');
	echo '<br><br>';

	echo SORT_ASC.'<br>';
	echo PHP_INT_MAX.'<br>';


?>

