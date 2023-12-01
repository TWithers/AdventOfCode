<?php

$inputFileName = $argv[1];
$input = file_get_contents(__DIR__.'/'.$inputFileName);

$lines = explode("\n", $input);

$total = 0;
foreach($lines as $line) {
    $new = preg_replace('/[a-zA-Z]/', '', $line);
    $newInt = (int) substr($new,0,1).substr($new,-1,1);
    $total += $newInt;
}

echo 'Part 1 Total: '.$total."\n";

$total = 0;
foreach($lines as $line) {
    $newLine = '';
    $search = [
        "twenty" => 't20y',
        "thirty" => 't30y',
        "forty" => 'f40y',
        "fifty" => 'f50y',
        "sixty" => 's60y',
        "seventy" => 's70y',
        "eighty" => 'e80y',
        "ninety" => 'n90y',
        "ten" => 't10n',
        "eleven" => 'e11n',
        "twelve" => 't12e',
        "thirteen" => 't13n',
        "fourteen" => 'f14n',
        "fifteen" => 'f15n',
        "sixteen" => 's16n',
        "seventeen" => 's17n',
        "eighteen" => 'e18n',
        "nineteen" => 'n19n',
        "one" => 'o1e',
        "two" => 't2o',
        "three" => 't3e',
        "four" => 'f4r',
        "five" => 'f5e',
        "six" => 's6x',
        "seven" => 's7n',
        "eight" => 'e8t',
        "nine" => 'n9e',
        "zero" => 'z0o',
    ];
    $newLine=str_replace(array_keys($search), array_values($search), $line);
    $new = preg_replace('/[a-zA-Z]/', '', $newLine);
    $newInt = (int) substr($new,0,1).substr($new,-1,1);

    $total += $newInt;
}

echo 'Part 2 Total: '.$total."\n";