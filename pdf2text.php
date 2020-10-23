<?php

$pdf = 'files' . DIRECTORY_SEPARATOR . 'pdfs' . DIRECTORY_SEPARATOR . 'iktisat_yüksek_lisans.pdf';
require_once 'app/libraries/Pdf2text/Pdf2text.php';

$pdf2text = new \Pdf2text\Pdf2text($pdf);
$output = $pdf2text->decode();

echo $output;