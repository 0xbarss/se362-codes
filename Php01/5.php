<?php
	
echo "Test PHP".'<br><br>';
	
$fruits = ["Banana", "Apple", "Orange"];
// $fruits = array("Banana", "Apple", "Orange");

var_dump($fruits); 

echo '<pre>';
var_dump($fruits); 
echo '</pre>';
/*
echo $fruits[1].'<br>';

$fruits[1] = "Kiwi";

echo '<pre>';
var_dump($fruits); 
echo '</pre>';

var_dump(isset($fruits[2]));

$fruits[] = 'Peach';
echo '<pre>';
var_dump($fruits); 
echo '</pre>';

echo '<br>'.$fruits[3].'<br><br>';

echo count($fruits).'<br>';

array_push($fruits, 'Plum');
echo '<pre>';
var_dump($fruits); 
echo '</pre>';

array_pop($fruits);
echo '<pre>';
var_dump($fruits); 
echo '</pre>';

array_unshift($fruits, 'Foo');
echo '<pre>';
var_dump($fruits); 
echo '</pre>';

array_shift($fruits);
echo '<pre>';
var_dump($fruits); 
echo '</pre>';

$string = "Plum,Melon,Strawberry";
echo '<pre>';
var_dump(explode(",", $string));
echo '</pre>';

echo implode(" & ", $fruits).'<br>';

echo '<pre>';
var_dump(in_array('Banana', $fruits));
echo '</pre>';

echo '<pre>';
var_dump(array_search("Orange", $fruits));
echo '</pre>';

print_r($fruits);

$vegetables = ['Potato', 'Cucumber'];
echo '<pre>';
var_dump(array_merge($fruits, $vegetables));
echo '</pre>';

sort($fruits); //sort, rsort, usort
echo '<pre>';
print_r($fruits);
echo '</pre>';

$numbers = [1, 2, 3, 4, 5, 6, 7, 8];
$evens = array_filter($numbers, function($n){ 
    return $n % 2 === 0;
});
echo '<pre>';
var_dump($evens);
echo '</pre>';

$threes = array_filter($numbers, fn($n)=> $n % 3 === 0);
echo '<pre>';
var_dump($threes);
echo '</pre>';

$sqrs=array_map(fn($n)=>$n*$n,$numbers);
echo '<pre>';
var_dump($sqrs);
echo '</pre>';

$sum = array_reduce($numbers, fn($carry, $item) => $carry + $item);
echo $sum.'<br>';
echo array_reduce($numbers, fn($carry, $item) => $carry + $item);
echo '<br><br>';

$person = [
  'name' => 'Brad',
  'surname' => 'Pitt',
  'age' => 30,
  'hobbies' => ['Tennis', 'Video Games'],
];
echo $person['name'].' '.$person['surname'].'<br>'; 
//...please check to find a better or shorter way if there is any

$person['channel'] = 'Warner Bross';
echo '<pre>';
var_dump($person);
echo '</pre>';

echo '<pre>';
var_dump(isset($person['ages']));  
echo '</pre>';

echo '<pre>';
var_dump(array_keys($person));
echo '</pre>';

echo '<pre>';
var_dump(array_values($person));
echo '</pre>';

asort($person); // ksort, krsort, asort, arsort
echo '<pre>';
var_dump($person);
echo '</pre>';

$todoItems = [
  ['title' => 'Todo 1', 'completed' => true],
  ['title' => 'Todo 2', 'completed' => false],
  ['title' => 'Todo 3', 'completed' => true]
];

echo '<pre>';
var_dump($todoItems);
echo '</pre>';



*/

?>