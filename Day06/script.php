<?php

$inputFileName = $argv[1];
$input = file_get_contents(__DIR__.'/'.$inputFileName);

$lines = explode("\n", $input);

$timeString = preg_replace('/(\s+)/', ' ', $lines[0]);
$distanceString = preg_replace('/(\s+)/', ' ', $lines[1]);

$times = explode(" ", $timeString);
$distances = explode(" ", $distanceString);

array_shift($times);
array_shift($distances);

$winWays = 1;
foreach ($times as $raceIndex => $time) {
    $winCount = 0;
    $start = floor($time/2);
    for ($a = $start; $a > 0; $a--) {
        $result = $a * ($time - $a);
        if ($result > $distances[$raceIndex]) {
            $winCount+=2;
        } else {
            break;
        }
    }
    if ($winCount > 0 && $time % 2 === 0) {
        $winCount-=1;
    }
    $winWays *= $winCount;
}

echo 'Part 1: '.$winWays."\n";


$time = (int) preg_replace('/(\s+)/', '', str_replace('Time:', '', $lines[0]));
$distance = (int) preg_replace('/(\s+)/', '', str_replace('Distance:', '', $lines[1]));

$winCount = 0;
$start = floor($time/2);
for ($a = $start; $a > 0; $a--) {
    $result = $a * ($time - $a);
    if ($result > $distance) {
        $winCount+=2;
    } else {
        break;
    }
}
if ($winCount > 0 && $time % 2 === 0) {
    $winCount-=1;
}

echo 'Part 2: '.$winCount."\n";