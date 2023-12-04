<?php

$inputFileName = $argv[1];
$input = file_get_contents(__DIR__.'/'.$inputFileName);

$lines = explode("\n", $input);

$max = [
    'red' => 12,
    'green' => 13,
    'blue' => 14,
];

$total = 0;
foreach($lines as $line) {
    preg_match('/Game (\d+):/', $line, $gameNumber);

    foreach (array_keys($max) as $color) {
        preg_match_all('/(\d+) '.$color.'/', $line, $matches);
        if (max($matches[1]) > $max[$color]) {
            continue 2;
        }
    }

    $total += (int) $gameNumber[1];
}

echo 'Part 1 Total: '.$total."\n";


$total = 0;
foreach($lines as $line) {
    $max = [
        'red' => 0,
        'green' => 0,
        'blue' => 0,
    ];

    foreach (array_keys($max) as $color) {
        preg_match_all('/(\d+) '.$color.'/', $line, $matches);
        $max[$color] = max($matches[1]);
    }
    $sum = $max['red'] * $max['green'] * $max['blue'];
    $total += $sum;
}

echo 'Part 2 Total: '.$total."\n";
