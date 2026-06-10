<?php
	
echo "Test PHP".'<br><br>';

$counter = 0; // When counter is 10??
while ($counter < 10) {
    echo "Printing counter: $counter<br>";
    //if ($counter > 5) break;
    $counter++;
}

$counter = 0; // When counter is 10?
do {
  echo "Printing counter: $counter<br>";
    $counter++;
} while ($counter < 10);

for ($i = 0; $i < 10; $i++) {
  echo "Printing counter: $i<br>";
}

$fruits = ["Banana", "Apple", "Orange"];
foreach ($fruits as $i => $fruit) {
    echo $i . ' ' . $fruit . '<br>';
}


$person = [
  'name' => 'Brad',
  'surname' => 'Traversy',
  'age' => 30,
  'hobbies' => 'Tennis, Games',
];
foreach ($person as $key => $value) {
  if ($key === 'hobbies') {
  break;
  }
  echo $key . ' ' . $value . '<br>';
}

?>