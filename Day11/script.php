<?php
include (__DIR__.'/../vendor/autoload.php');

$inputFileName = $argv[1];
$input = file_get_contents(__DIR__.'/'.$inputFileName);

$lines = explode("\n", $input);


// Expand the galaxy rows, then columns, and then combine back together
$galaxyRowsExpanded = [];
foreach ($lines as $line) {
    if (! str_contains($line, '#')) {
        $galaxyRowsExpanded[] = str_split($line);
    }
    $galaxyRowsExpanded[] = str_split($line);
}

$galaxyColumnsExpanded = [];
for ($i = 0; $i < count($galaxyRowsExpanded[0]); $i++) {
    $column = array_column($galaxyRowsExpanded, $i);
    if (! str_contains(implode('', $column), '#')) {
        $galaxyColumnsExpanded[] = $column;
    }
    $galaxyColumnsExpanded[] = $column;
}

$expandedGalaxy = [];
foreach ($galaxyColumnsExpanded as $colIndex => $column) {
    $expandedGalaxy[] = array_column($galaxyColumnsExpanded, $colIndex);
}


// Find the coordinates of each galaxy
$galaxies = [];
foreach ($expandedGalaxy as $rowIndex => $row) {
    foreach ($row as $colIndex => $cell) {
        if ($cell === '#') {
            $galaxies[] = [$rowIndex, $colIndex];
        }
    }
}

// Loop through each galaxy and find distances
$distances = [];
foreach ($galaxies as $galaxyIndex => $galaxy) {
    $galaxyRow = $galaxy[0];
    $galaxyCol = $galaxy[1];

    foreach ($galaxies as $otherGalaxyIndex => $otherGalaxy) {
        if ($otherGalaxyIndex === $galaxyIndex || isset($distances[$galaxyIndex.'-'.$otherGalaxyIndex]) || isset($distances[$otherGalaxyIndex.'-'.$galaxyIndex])) {
            continue;
        }
        $otherGalaxyRow = $otherGalaxy[0];
        $otherGalaxyCol = $otherGalaxy[1];

        $distances[$galaxyIndex.'-'.$otherGalaxyIndex] = abs($galaxyRow - $otherGalaxyRow) + abs($galaxyCol - $otherGalaxyCol);
    }
}

echo 'Part 1: '.array_sum($distances)."\n\n";


// We will use the original galaxy but keep track of expansion cols and rows instead of expanding it.
$expansionRows = [];
$expansionColumns = [];

$galaxy = [];
foreach ($lines as $rowIndex => $line) {
    if (! str_contains($line, '#')) {
        $expansionRows[] = $rowIndex;
    }
    $galaxy[] = str_split($line);
}

for ($i = 0; $i < count($galaxy[0]); $i++) {
    $column = implode('', array_column($galaxy, $i));

    if (! str_contains($column, '#')) {
        $expansionColumns[] = $i;
    }
}

// Set out expansion size here as a variable for testing with the sample data.
$expansionSize = 1000000;

// Find the coordinates of each galaxy
// A bit trickier here. We count the number of expansion rows and columns, subtract that against the current count, then add back the expanded size.
$galaxies = [];
foreach ($galaxy as $rowIndex => $row) {
    $expandedRows = array_filter($expansionRows, fn ($r) => $r < $rowIndex);
    foreach ($row as $colIndex => $cell) {
        $expandedCols = array_filter($expansionColumns, fn ($c) => $c < $colIndex);
        if ($cell === '#') {
            $galaxies[] = [($rowIndex-count($expandedRows)) + (count($expandedRows) * $expansionSize), ($colIndex-count($expandedCols)) + (count($expandedCols) * $expansionSize)];
        }
    }
}


// Loop through each galaxy and find distances
$distances = [];
foreach ($galaxies as $galaxyIndex => $galaxy) {
    $galaxyRow = $galaxy[0];
    $galaxyCol = $galaxy[1];

    foreach ($galaxies as $otherGalaxyIndex => $otherGalaxy) {
        if ($otherGalaxyIndex === $galaxyIndex || isset($distances[$galaxyIndex.'-'.$otherGalaxyIndex]) || isset($distances[$otherGalaxyIndex.'-'.$galaxyIndex])) {
            continue;
        }
        $otherGalaxyRow = $otherGalaxy[0];
        $otherGalaxyCol = $otherGalaxy[1];

        $distances[$galaxyIndex.'-'.$otherGalaxyIndex] = abs($galaxyRow - $otherGalaxyRow) + abs($galaxyCol - $otherGalaxyCol);
    }
}

echo 'Part 2: '.array_sum($distances)."\n";

