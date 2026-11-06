<?php
$text4 = file_get_contents('bab4.txt');
$text5 = file_get_contents('bab5.txt');

preg_match_all('/(4\.\d.*?)\n/i', $text4, $matches4);
preg_match_all('/(5\.\d.*?)\n/i', $text5, $matches5);

echo "Bab 4 Headings:\n";
print_r(array_unique($matches4[1]));

echo "\nBab 5 Headings:\n";
print_r(array_unique($matches5[1]));
