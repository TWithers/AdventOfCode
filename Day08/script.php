<?php
include (__DIR__.'/../vendor/autoload.php');

$inputFileName = $argv[1];
$input = file_get_contents(__DIR__.'/'.$inputFileName);

$lines = explode("\n", $input);

$directions = str_split(str_replace(['L','R'],[0,1],array_shift($lines)));

array_shift($lines);

$map = [];

foreach ($lines as $line) {
    preg_match('/(\w+) = \((\w+), (\w+)\)/', $line, $matches);
    $map[$matches[1]] = [$matches[2], $matches[3]];
}

$stepCount = 0;
$key = 0;
$current = 'AAA';
while ($current !== 'ZZZ') {
    $current = $map[$current][$directions[$key]];
    $stepCount++;
    $key++;
    if ($key === count($directions)) {
        $key = 0;
    }
}
echo 'Part 1: '.$stepCount."\n";

/**
 * For part 2, there are two ways to solve this.
 * 1: You walk every path simultaneously until all paths end with Z
 * 2: Walk each path once, then find the Lowest Common Multiple of all the step counts
 *
 * Because the path counts are crazy high, we need to go with option 2.
 */
$nodes = [];
foreach ($map as $key => $value) {
    if (str_ends_with($key, 'A')) {
        $nodes[] = $key;
    }
}

$counts = [];
foreach ($nodes as $node) {
    $stepCount = 0;
    $key = 0;
    $current = $node;
    while (!str_ends_with($current, 'Z')) {
        $current = $map[$current][$directions[$key]];
        $stepCount++;
        $key++;
        if ($key === count($directions)) {
            $key = 0;
        }
    }
    $counts[] = $stepCount;
}

// Division method to find LCM
// Use a list of prime numbers, start low and work up.
// if any number is evenly divisible, store the quotient and the prime number, and start over
// One all numbers are at 1, multiply all the prime numbers together to get the LCM
$original = \GuzzleHttp\Psr7\Utils::streamFor(fopen('https://raw.githubusercontent.com/koorukuroo/Prime-Number-List/master/primes.csv', 'r'));
$stream = new \GuzzleHttp\Psr7\CachingStream($original);
$getNextPrime = function () use (&$stream) {
    $output = '';
    do {
        $c =$stream->read(1);
        $output.= $c;
    } while ($c !== "\n");

    return (int)explode(',', $output)[1];
};

$primes = [];
while (array_sum($counts) !== count($counts)) {
    $prime = $getNextPrime();

    $isDivisible = false;
    foreach ($counts as $index => $count) {
        if ($count % $prime === 0) {
            $isDivisible = true;
            $counts[$index] = $count / $prime;
        }
    }

    if ($isDivisible) {
        $primes[] = $prime;
        $stream->rewind();
    }
}

$stepCount = $primes[0];
for ($i = 1; $i < count($primes); $i++) {
    $stepCount = $stepCount * $primes[$i];
}

echo 'Part 2: '.$stepCount."\n";