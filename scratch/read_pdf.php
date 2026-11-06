<?php
require 'vendor/autoload.php';

$parser = new \Smalot\PdfParser\Parser();
$pdf1 = $parser->parseFile('../Referensi/Bab 4 yang benar.pdf');
$text1 = $pdf1->getText();
file_put_contents('bab4.txt', $text1);

$pdf2 = $parser->parseFile('../Referensi/bab 5 lama.pdf');
$text2 = $pdf2->getText();
file_put_contents('bab5.txt', $text2);

echo "Done extracting PDFs.";
