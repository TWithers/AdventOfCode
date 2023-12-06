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

/**
 * Part two was not easy. I am sure there is a better way to do this. Essentially you could have strings like "eightwo"
 * which would match both eight and two. There were numbers like fourteen, which should only match 14 and not 4 and 14.
 *
 * The simplest solution I came up with was a str_replace in order of importance. In replacing, I would remove all the
 * middle characters of the number and replace it with the digit representation so that everything would work.
 *
 * The downside to this approach is if there were componund numbers like twentyone. Below would translate to t20yo1e.
 * This could be the correct logic, but maybe it should be t21e. I don't think this case came up, so it wasn't a problem.
 */

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