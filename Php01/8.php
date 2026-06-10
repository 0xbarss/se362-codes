<?php
	
echo "Test PHP".'<br><br>';

/*
function hello()
{
    echo 'Hello I am TheCodeholic<br><br>';
}

hello();
hello();

function sum($a,$b)
{
    echo ($a*$b) . '<br><br>'; 
}
sum(4,6);
sum(5,8);
*/

function sum(...$nums)
{
    $sum = 0;
    foreach ($nums as $num) $sum += $num;
    return $sum;
}
echo sum(1, 2, 3, 4, 5, 6);


?>