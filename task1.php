<?php

function fibonacci($n)
{
	if ($n < 3) {
		return 1;
	} else {
		return fibonacci($n - 1) + fibonacci($n - 2);
	}
}
$fib = [];
for ($n = 1; $n <= 36; $n++) {
	$fib[] = fibonacci($n);
}


$arr = [];
$sum = 0;

for ($j = 0; $j < 6; $j++) {
	for ($i = 0; $i < 6; $i++) {

		$arr[$i][$j] = $fib[$k];
		$k++;
	}
}

echo "<pre>";
var_dump($arr);
echo "<pre>";
echo "<br>";

$b = 5;
for ($a = 0; $a < 6; $a++) {
	$sum += $arr[$b][$a];
	$b--;
}

echo $sum;

