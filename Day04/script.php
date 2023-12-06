<?php

$inputFileName = $argv[1];
$input = file_get_contents(__DIR__.'/'.$inputFileName);

$lines = explode("\n", $input);

$points = 0;

foreach ($lines as $row => $line) {
    preg_match_all('/Card([\d ]+): ([\d ]+) | ([\d ]+)/', $line, $matches);
    $winners = array_values(array_filter(explode(" ", $matches[2][0])));
    $cardNumbers = array_values(array_filter(explode(" ", $matches[3][1])));

    $foundMatches = 0;
    foreach($cardNumbers as $number) {
        if (in_array($number, $winners)) {
            $foundMatches++;
        }
    }

    if ($foundMatches === 0) {
        $cardPoints = 0;
    } else {
        $cardPoints = 2**($foundMatches-1);
    }

    $points += $cardPoints;
}

echo 'Part 1 Total: '.$points."\n";

/**
 * Part 2 can be done a similar way to part 1. We just need to keep track of the card we are on and how many winners there
 * have been previously for the cards.
 */

$cardCounts = [];
foreach ($lines as $row => $line) {
    preg_match_all('/Card([\d ]+): ([\d ]+) | ([\d ]+)/', $line, $matches);
    $cardNumber = (int) trim($matches[1][0]);

    if (! isset($cardCounts[$cardNumber])) {
        $cardCounts[$cardNumber] = 1;
    } else {
        $cardCounts[$cardNumber]++;
    }

    $winners = array_values(array_filter(explode(" ", $matches[2][0])));
    $cardNumbers = array_values(array_filter(explode(" ", $matches[3][1])));

    $intersect = array_intersect($winners, $cardNumbers);

    for ($a = 0; $a < count($intersect); $a++) {
        if (! isset($cardCounts[$cardNumber+$a+1])) {
            $cardCounts[$cardNumber+$a+1] = $cardCounts[$cardNumber];
        } else {
            $cardCounts[$cardNumber+$a+1] += $cardCounts[$cardNumber];
        }
    }
}

echo 'Part 2 Total: '.array_sum($cardCounts)."\n";



//echo 'Part 2 Total: '.array_sum($numbers)."\n";