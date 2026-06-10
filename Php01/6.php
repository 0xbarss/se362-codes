<?php
	
echo "Test PHP".'<br><br>';
	
$age = 18;
$salary = 600000;

if ($age < 22) {
echo 'You are a young man!!<br><br>';
}
/*
if ($age < 18) {
echo 'You are a young man!!<br><br>';
}
else {
echo 'You are not a young man!!<br><br>';
}

if ($age < 22 && $salary > 500000) {
echo 'You are young AND rich<br><br>';
}

if ($age < 22 || $salary > 500000) { 
echo 'You are young OR rich<br><br>';
}

if ($age < 22) {
echo 'You are a young man!!<br><br>';
} elseif ($age > 60) {
echo 'You are old<br><br>';
} else {
echo "You are not young, but not old also<br><br>";
}

if ($age < 22 && $salary >= 500000) {
echo 'You are a young man AND rich!!<br>';
} elseif ($age < 22 && $salary < 500000) {
echo "You are a young man, and not so rich<br>";
} elseif ($age > 60 && $salary >= 500000) {
echo 'You are old, but rich<br>';
} elseif ($age > 60 && $salary < 500000) {
echo 'You are old and NOT rich also<br>';
}

echo '<br>';
echo $age < 22 ? 'Young' : 'Old';
echo '<br><br>';

echo '<br>';
echo $age < 22 ? 'Young' : ($age < 30 ? 'Not young but not old':'Old');
echo '<br><br>';

echo $age < 22 ? 'Young' : ($age < 30 ? 'Not young but not old':'Old');
echo '<br><br>';

echo $age < 22 ? ($age < 16 ? 'Too young' : 'Young') : ($age < 60 ? 'Adult' : 'Old');
echo '<br><br>';

//$address='Izmir';
$myadd=isset($address)?$address:'Current Location';
echo $myadd.'<br><br>';


$person = [
'name' => 'John' 
];
if (!isset($person['name'])){
  $person['name'] = 'Anonymous';
}
echo $person['name'].'<br>';



$userRole = 'userd'; // admin, editor, user

switch ($userRole) {
    case 'admin':
        echo 'You can do anything<br>';
        break;
    case 'editor';
        echo 'You can edit content<br>';
        break;
    case 'user':
        echo 'You can view posts and comment<br>';
        break;
    default:
        echo 'Unknown role<br>';
}
*/
?>