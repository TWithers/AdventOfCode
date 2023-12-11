<?php
include (__DIR__.'/../vendor/autoload.php');

$inputFileName = $argv[1];
$input = file_get_contents(__DIR__.'/'.$inputFileName);

$lines = explode("\n", $input);

$total = 0;
$total2 = 0;
foreach ($lines as $line) {
    $inputArray = explode(" ", $line);
    $inputArray = array_map(function ($value) {
        return (int)$value;
    }, $inputArray);
    $total += part1Loop($inputArray);
    $total2 += part2Loop($inputArray);
}

echo "Part 1: $total\n";
echo "Part 2: $total2\n";

function array_every(array $arr, callable $predicate) {
    foreach ($arr as $e) {
        if (!call_user_func($predicate, $e)) {
            return false;
        }
    }

    return true;
}

function diffArray(array $array): array
{
    $newArray = [];
    for ($i = 1; $i < count($array); $i++) {
        $newArray[] = $array[$i] - $array[$i - 1];
    }

    return $newArray;
}

function part1Loop (array $array): int
{
    $originalArrayIndex = [];

    while (! array_every($array, fn ($i) => $i === 0)) {
        array_unshift($originalArrayIndex, $array);
        $array = diffArray($array);
    }

    $final = (int)array_slice($originalArrayIndex[0],-1)[0];

    for ($i = 1; $i < count($originalArrayIndex); $i++) {
        $final = (int)array_slice($originalArrayIndex[$i],-1)[0] + (int)array_slice($originalArrayIndex[$i-1], -1)[0];
        $originalArrayIndex[$i][] = $final;
    }

    return $final;

}

function part2Loop (array $array): int
{
    $originalArrayIndex = [];

    while (! array_every($array, fn ($i) => $i === 0)) {
        array_unshift($originalArrayIndex, $array);
        $array = diffArray($array);
    }

    $final = $originalArrayIndex[0][0];

    for ($i = 1; $i < count($originalArrayIndex); $i++) {
        $final = $originalArrayIndex[$i][0] - $originalArrayIndex[$i-1][0];
        array_unshift($originalArrayIndex[$i], $final);
    }

    return $final;
}

