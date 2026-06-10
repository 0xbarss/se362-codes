<?php
	
echo "Test PHP".'<br><br>';

echo time() . '<br><br>';

echo date('H:i:s d-m-Y') . '<br><br>';

echo "Today is " . date("Y/m/d") . "<br>";
echo "Today is " . date("Y.m.d") . "<br>";
echo "Today is " . date("y-m-d") . "<br>";
echo "Today is " . date("l") . "<br><br>";

//date_default_timezone_set("Europe/Berlin");
echo "The time is " . date("h:i:sa");
echo '<br><br>';

echo date('Y-m-d H:i:s', time() - 60 * 60 * 24) . '<br><br>';

echo date('F j Y, H:i:s') . '<br><br>';

echo strtotime('now') . "<br><br>";
echo strtotime('-1 day') . "<br><br>";
echo strtotime('+3 week') . "<br><br>";

$dateString = '2020-05-01 14:30:09'; // Declare date
$parsedDate = date_parse($dateString); // Parse date
echo '<pre>';
var_dump($parsedDate);
echo '</pre>';

$dateString = 'May 4 2020 12:45:35';
$parsedDate = date_parse_from_format('F j Y H:i:s', $dateString);
echo '<pre>';
var_dump($parsedDate);
echo '</pre>';




?>