<?php

$inputFileName = $argv[1];
$input = file_get_contents(__DIR__.'/'.$inputFileName);

$sections = explode("\n\n", $input);

$seedSection = array_shift($sections);
$seeds = explode(" ", explode(": ", $seedSection)[1]);
$locations = [];

foreach ($seeds as $seed) {
    $current = $seed;
    foreach($sections as $section) {
        $lines = explode("\n", $section);
        array_shift($lines);

        foreach ($lines as $line) {
            list($destination, $source, $range) = explode(" ", $line);
            $destination = (int) $destination;
            $source = (int) $source;
            $range = (int) $range;

            if ($current < $source + $range && $current >= $source) {
                $current = $destination + ($current - $source);
                $found = true;
                break;
            }
        }
    }
    $locations[] = $current;
}

sort($locations);
echo "Part 1: " . array_values($locations)[0] . "\n";


/**
 * Part 2 can't be solved the same as part one.
 *
 * If we were to turn the ranges of seeds into array elements, we
 * would quickly run out of memory.
 *
 * We could attempt to brute force it backwards, meaning start with a location of 0 and see if any seeds are in range.
 * This works for the sample set quickly... and it would work for the normal input if the value was low enough.
 * Unfortunately the answer is in the 100s of millions. PHP takes about 30 seconds to brute force about a million attempts
 * so doing it this way doesn't work.
 *
 * The best solution is to work with ranges. Each seed, if it is in a range, makes a specific sized step.
 * 52 50 48 would mean that seeds between 50 and 97 would increment their values by 2. So rather than following
 * the ranges through a map, we just increment or decrement accordingly. Where it gets tricky is the ranges overlap.
 * Seeds 10 20 means seeds range from 10 to 29.
 * If a section map had a line with 25 5 9, then seeds 10-14 would increment by 20, but seeds 15-29 wouldn't.
 * So to solve this, you need to split the range apart into 2 ranges, one that increments, and another that stays the same.
 * The caveat is that the range that stays the same might be affected by future lines in the section map, so those need to
 * be checked as well.
 *
 */
$ranges = [];
for ($a = 0; $a < count($seeds); $a+=2) {
    $ranges[] = [(int)$seeds[$a], (int)$seeds[$a] + (int)$seeds[$a + 1] - 1];
}

foreach ($sections as $section) {
    $lines = explode("\n", $section);
    array_shift($lines);

    for ($a = 0; $a < count($ranges); $a++) {
        foreach ($lines as $line) {
            list($destination, $source, $range) = explode(" ", $line);
            $destination = (int)$destination;
            $source = (int)$source;
            $range = (int)$range;

            if ($source <= $ranges[$a][0] && $ranges[$a][1] <= $source + $range - 1) {
                //Range starts and ends within source range
                $ranges[$a][0] += $destination - $source;
                $ranges[$a][1] += $destination - $source;
                break;
            } elseif ($ranges[$a][0] <= $source && $source + $range - 1 <= $ranges[$a][1]) {
                //Range starts and ends within destination range
                //spit into 3 ranges
                $ranges[] = [$ranges[$a][0], $source - 1];
                $ranges[] = [$source + $range, $ranges[$a][1]];
                $inRange = [$source, $source + $range - 1];
                $inRange[0] += $destination - $source;
                $inRange[1] += $destination - $source;
                $ranges[$a] = $inRange;
                break;
            } elseif ($ranges[$a][0] <= $source && $ranges[$a][1] >= $source && $ranges[$a][1] <= $source + $range - 1) {
                //Range starts outside destination range and ends inside
                //split into 2 ranges
                $ranges[] = [$ranges[$a][0], $source - 1];
                $inRange = [$source, $ranges[$a][1]];
                $inRange[0] += $destination - $source;
                $inRange[1] += $destination - $source;
                $ranges[$a] = $inRange;
                break;
            } elseif ($source <= $ranges[$a][0] && $ranges[$a][0] <= $source + $range -1 && $source + $range -1 <= $ranges[$a][1]) {
                //Range starts inside destination range and ends outside
                //split into 2 ranges
                $ranges[] = [$source + $range, $ranges[$a][1]];
                $inRange = [$ranges[$a][0], $source + $range - 1];
                $inRange[0] += $destination - $source;
                $inRange[1] += $destination - $source;
                $ranges[$a] = $inRange;
                break;
            }
        }
    }
    $ranges = array_values($ranges);
}
var_dump($ranges);
$unsorted = array_map(function($a) { return $a[0];}, $ranges);
sort($unsorted);
echo "Part 2: " . array_values($unsorted)[0] . "\n";