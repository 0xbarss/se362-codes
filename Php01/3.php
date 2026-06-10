<?php
	
echo "Test PHP".'<br><br>';

// 1. Declaring numbers
$a = 6;
$b = 4;
$c = 1.4;

echo $a.'<br>';
echo $b.'<br>';
echo $c.'<br><br>';

echo $a+$b.'<br>';
echo $a-$b.'<br>';
echo $a*$b.'<br>';
echo $a/$b.'<br>';
echo $a%$b.'<br>';
echo ($a-$b)/$c.'<br>';

echo '<br><br>';
//$a+=$b;
//echo $a.'<br>', $b.'<br>';
//$a-=$b;
//echo $a.'<br>', $b.'<br>';
//$a*=$b;
//echo $a.'<br>', $b.'<br>';
//$a/=$b;
//echo $a.'<br>', $b.'<br>';
//$a%=$b;
//echo $a.'<br>', $b.'<br>';

//echo $a++.'<br>';
//echo ++$a.'<br>';
//echo $a.'<br>';

//echo ++$a.'<br>';
//echo $a.'<br>';

//echo $a--.'<br>';
//echo $a.'<br>';

//echo --$a.'<br>';
//echo $a.'<br>';

//$yep=is_float(2.1);
//echo $yep.'<br>';
//$ye=is_integer(2);
//echo $ye.'<br>';
//$y=is_numeric ("178");
//echo $y.'<br>';

$strnm='15.34';
var_dump($strnm);
echo '<br>';
$number=(int)$strnm;
var_dump($number);
echo '<br><br>';

echo "abs(-20) ".abs(-20)."<br>";
echo 'pow(2,3) '.pow(2,3).'<br>';
echo 'sqrt(25) '.sqrt(25).'<br>';
echo "min(-20,-25) ".min(-20,-25)."<br>";
echo 'max(2,3) '.max(2,3).'<br>';
echo 'round(25.2) '.round(25.2).'<br>';
echo 'round(25.5) '.round(25.5).'<br>';
echo 'round(25.49) '.round(25.49).'<br>';
echo 'floor(25.9) '.floor(25.9).'<br>';
echo 'floor(25.01) '.floor(25.01).'<br>';
echo 'ceil(25.9) '.ceil(25.9).'<br>';
echo 'ceil(25.4) '.ceil(25.4).'<br>';

echo '<br><br>';

$sayi=1123456289.123456;
echo number_format ($sayi, 2,',','.').'<br>';

?>

