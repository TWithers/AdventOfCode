<?php
include (__DIR__.'/../vendor/autoload.php');

$inputFileName = $argv[1];
$input = file_get_contents(__DIR__.'/'.$inputFileName);

const EW = '-';
const NS = '|';
const NW = 'J';
const SE = 'F';
const SW = '7';
const NE = 'L';
const NOTHING = '.';

// For debugging purposes
function printGrid($grid): void
{
    $prettyMap = [
        EW => '═',
        NS => '║',
        NW => '╝',
        SE => '╔',
        SW => '╗',
        NE => '╚',
        NOTHING => '•',

    ];

    foreach ($grid as $row) {
        echo str_replace(array_keys($prettyMap), array_values($prettyMap),implode('', $row))."\n";
    }
}

$grid = array_map(fn ($row) => str_split($row), explode("\n", $input));

array_unshift($grid, array_fill(0, count($grid[0]), '.'));
$grid[] = array_fill(0, count($grid[0]), '.');
foreach ($grid as &$r) {
    array_unshift($r, '.');
    $r[] = '.';
}

//printGrid($grid);

// First we find the starting point of the loop, then we replace it with the correct piece.

$starting = [0, 0];
foreach ($grid as $rowIndex => $row) {
    foreach ($row as $colIndex => $col) {
        if ($col === 'S') {
            $starting = [$rowIndex, $colIndex];

            if (in_array($row[$colIndex - 1], [EW, NE, SE])) {
                if (in_array($grid[$rowIndex-1][$colIndex], [SW, NS, SE])) {
                    $symbol = NW;
                } elseif (in_array($row[$colIndex + 1], [EW, NW, SW])) {
                    $symbol = EW;
                } else {
                    $symbol = SW;
                }
            } else {
                if (in_array($grid[$rowIndex-1][$colIndex], [SW, NS, SE])) {
                    if (in_array($row[$colIndex + 1], [EW, NW, SW])) {
                        $symbol = NE;
                    } else {
                        $symbol = NS;
                    }
                } else {
                    $symbol = SE;
                }
            }

            $grid[$rowIndex][$colIndex] = $symbol;
        }
    }
}

//printGrid($grid);


// For part one we just need to walk around the loop until we get back to the start. Then we divide our steps in half.
// We need to keep a heading so we know which way to turn inside the pipe.
$position = $starting;
$steps = [];
$heading = null;
do {
    $current = $grid[$position[0]][$position[1]];
    switch ($current) {
        case EW:
            if ($heading === 'E') {
                $position[1]++;
                $heading = 'E';
            } else {
                $position[1]--;
                $heading = 'W';
            }
            break;
        case NS:
            if ($heading === 'N') {
                $position[0]--;
                $heading = 'N';
            } else {
                $position[0]++;
                $heading = 'S';
            }
            break;
        case NW:
            if ($heading === 'E') {
                $position[0]--;
                $heading = 'N';
            } else {
                $position[1]--;
                $heading = 'W';
            }
            break;
        case NE:
            if ($heading === 'W') {
                $position[0]--;
                $heading = 'N';
            } else {
                $position[1]++;
                $heading = 'E';
            }
            break;
        case SW:
            if ($heading === 'E') {
                $position[0]++;
                $heading = 'S';
            } else {
                $position[1]--;
                $heading = 'W';
            }
            break;
        case SE:
            if ($heading === 'W') {
                $position[0]++;
                $heading = 'S';
            } else {
                $position[1]++;
                $heading = 'E';
            }
            break;
    }
    $steps[] = $position;
} while (implode(",",$position) !== implode(",",$starting));

echo "Part 1: ".(count($steps)/2)."\n";


// For part two we need to figure out the inside and outside of the loop.
// Since the unused loop parts can be considered "in the loop", lets swap them out which the period symbol.
// We initialize a new grid with only period tiles, and then we walk around the loop again, using the path from before,
// and we transpose the symbols from the original grid to the new grid.
//
$partTwoGrid = array_map(fn ($row) => array_fill(0, count($row), '.'), $grid);
$partTwoGrid[$starting[0]][$starting[1]] = $symbol;
foreach ($steps as $step) {
    $partTwoGrid[$step[0]][$step[1]] = $grid[$step[0]][$step[1]];
}

//printGrid($partTwoGrid);

// Now, we need to find the outer edge of the loop. It will always be a SE symbol (F) as the first character on the highest row.
// We can assume the that the west and north sides of the F are outside, and the southeast is inside.
foreach ($partTwoGrid as $rowIndex => $row) {
    if (array_search(SE, $row) === false) {
        continue;
    } else {
        $starting = [$rowIndex, array_search(SE, $row)];
        break;
    }
}
$newSteps = [];

foreach ($steps as $stepIndex => $step) {
    if ($step[0] === $starting[0] && $step[1] === $starting[1]) {
        $newSteps = array_merge(array_slice($steps, $stepIndex), array_slice($steps, 0, $stepIndex));
        break;
    }
}

// Now we walk around the loop again, but this time we need to keep track of the inside and outside of the loop.
// Knowing the inside of the loop as we walk, we can check for empty spaces and mark them as inside (I).
$inside = 'E';
$heading = 'N';
foreach ($newSteps as $step) {
    switch($partTwoGrid[$step[0]][$step[1]]) {
        case SE:
            if (
                ($heading === 'W' && in_array($inside, ['SE', 'S', 'SW'])) ||
                ($heading === 'N' && in_array($inside, ['E', 'NE', 'SE']))
            ) {
                if ($partTwoGrid[$step[0] + 1][$step[1] + 1] === NOTHING) {
                    $partTwoGrid[$step[0] + 1][$step[1] + 1] = 'I';
                }
                $inside = 'SE';
            } else {
                if ($partTwoGrid[$step[0] - 1][$step[1] - 1] === NOTHING) {
                    $partTwoGrid[$step[0] - 1][$step[1] - 1] = 'I';
                }
                if ($partTwoGrid[$step[0] - 1][$step[1]] === NOTHING) {
                    $partTwoGrid[$step[0] - 1][$step[1]] = 'I';
                }
                if ($partTwoGrid[$step[0] - 1][$step[1] + 1] === NOTHING) {
                    $partTwoGrid[$step[0] - 1][$step[1] + 1] = 'I';
                }
                if ($partTwoGrid[$step[0]][$step[1] - 1] === NOTHING) {
                    $partTwoGrid[$step[0]][$step[1] - 1] = 'I';
                }
                if ($partTwoGrid[$step[0] + 1][$step[1] - 1] === NOTHING) {
                    $partTwoGrid[$step[0] + 1][$step[1] - 1] = 'I';
                }
                $inside = 'NW';
            }
            $heading = $heading === 'W' ? 'S' : 'E';
            break;
        case SW:
            if (
                ($heading === 'E' && in_array($inside, ['SE', 'S', 'SW'])) ||
                ($heading === 'N' && in_array($inside, ['W', 'NW', 'SW']))
            ) {
                if ($partTwoGrid[$step[0] + 1][$step[1] - 1] === NOTHING) {
                    $partTwoGrid[$step[0] + 1][$step[1] - 1] = 'I';
                }
                $inside = 'SW';
            } else {
                if ($partTwoGrid[$step[0] - 1][$step[1] - 1] === NOTHING) {
                    $partTwoGrid[$step[0] - 1][$step[1] - 1] = 'I';
                }
                if ($partTwoGrid[$step[0] - 1][$step[1]] === NOTHING) {
                    $partTwoGrid[$step[0] - 1][$step[1]] = 'I';
                }
                if ($partTwoGrid[$step[0] - 1][$step[1] + 1] === NOTHING) {
                    $partTwoGrid[$step[0] - 1][$step[1] + 1] = 'I';
                }
                if ($partTwoGrid[$step[0]][$step[1] + 1] === NOTHING) {
                    $partTwoGrid[$step[0]][$step[1] + 1] = 'I';
                }
                if ($partTwoGrid[$step[0] + 1][$step[1] + 1] === NOTHING) {
                    $partTwoGrid[$step[0] + 1][$step[1] + 1] = 'I';
                }
                $inside = 'NE';
            }
            $heading = $heading === 'E' ? 'S' : 'W';
            break;
        case NE:
            if (
                ($heading === 'W' && in_array($inside, ['NW', 'N', 'NE'])) ||
                ($heading === 'S' && in_array($inside, ['E', 'NE', 'SE']))
            ) {
                if ($partTwoGrid[$step[0] - 1][$step[1] + 1] === NOTHING) {
                    $partTwoGrid[$step[0] - 1][$step[1] + 1] = 'I';
                }
                $inside = 'NE';
            } else {
                if ($partTwoGrid[$step[0] - 1][$step[1] - 1] === NOTHING) {
                    $partTwoGrid[$step[0] - 1][$step[1] - 1] = 'I';
                }
                if ($partTwoGrid[$step[0]][$step[1] - 1] === NOTHING) {
                    $partTwoGrid[$step[0]][$step[1] - 1] = 'I';
                }
                if ($partTwoGrid[$step[0] + 1][$step[1] - 1] === NOTHING) {
                    $partTwoGrid[$step[0] + 1][$step[1] - 1] = 'I';
                }
                if ($partTwoGrid[$step[0] + 1][$step[1]] === NOTHING) {
                    $partTwoGrid[$step[0] + 1][$step[1]] = 'I';
                }
                if ($partTwoGrid[$step[0] + 1][$step[1] + 1] === NOTHING) {
                    $partTwoGrid[$step[0] + 1][$step[1] + 1] = 'I';
                }
                $inside = 'SW';
            }
            $heading = $heading === 'W' ? 'N' : 'E';
            break;
        case NW:
            if (
                ($heading === 'E' && in_array($inside, ['NW', 'N', 'NE'])) ||
                ($heading === 'S' && in_array($inside, ['W', 'NW', 'SW']))
            ) {
                if ($partTwoGrid[$step[0] - 1][$step[1] - 1] === NOTHING) {
                    $partTwoGrid[$step[0] - 1][$step[1] - 1] = 'I';
                }
                $inside = 'NW';
            } else {
                if ($partTwoGrid[$step[0] - 1][$step[1] + 1] === NOTHING) {
                    $partTwoGrid[$step[0] - 1][$step[1] + 1] = 'I';
                }
                if ($partTwoGrid[$step[0]][$step[1] + 1] === NOTHING) {
                    $partTwoGrid[$step[0]][$step[1] + 1] = 'I';
                }
                if ($partTwoGrid[$step[0] + 1][$step[1] - 1] === NOTHING) {
                    $partTwoGrid[$step[0] + 1][$step[1] - 1] = 'I';
                }
                if ($partTwoGrid[$step[0] + 1][$step[1]] === NOTHING) {
                    $partTwoGrid[$step[0] + 1][$step[1]] = 'I';
                }
                if ($partTwoGrid[$step[0] + 1][$step[1] + 1] === NOTHING) {
                    $partTwoGrid[$step[0] + 1][$step[1] + 1] = 'I';
                }
                $inside = 'SE';
            }
            $heading = $heading === 'E' ? 'N' : 'W';
            break;
        case NS:
            if (in_array($inside, ['E', 'NE', 'SE'])) {
                if ($partTwoGrid[$step[0] - 1][$step[1] + 1] === NOTHING) {
                    $partTwoGrid[$step[0] - 1][$step[1] + 1] = 'I';
                }
                if ($partTwoGrid[$step[0]][$step[1] + 1] === NOTHING) {
                    $partTwoGrid[$step[0]][$step[1] + 1] = 'I';
                }
                if ($partTwoGrid[$step[0] + 1][$step[1] + 1] === NOTHING) {
                    $partTwoGrid[$step[0] + 1][$step[1] + 1] = 'I';
                }

                $inside = 'E';
            } else {
                if ($partTwoGrid[$step[0] - 1][$step[1] - 1] === NOTHING) {
                    $partTwoGrid[$step[0] - 1][$step[1] - 1] = 'I';
                }
                if ($partTwoGrid[$step[0]][$step[1] - 1] === NOTHING) {
                    $partTwoGrid[$step[0]][$step[1] - 1] = 'I';
                }
                if ($partTwoGrid[$step[0] + 1][$step[1] - 1] === NOTHING) {
                    $partTwoGrid[$step[0] + 1][$step[1] - 1] = 'I';
                }
                $inside = 'W';
            }
            break;
        case EW:
            if (in_array($inside, ['N', 'NE', 'NW'])) {
                if ($partTwoGrid[$step[0] - 1][$step[1] - 1] === NOTHING) {
                    $partTwoGrid[$step[0] - 1][$step[1] - 1] = 'I';
                }
                if ($partTwoGrid[$step[0] - 1][$step[1]] === NOTHING) {
                    $partTwoGrid[$step[0] - 1][$step[1]] = 'I';
                }
                if ($partTwoGrid[$step[0] - 1][$step[1] + 1] === NOTHING) {
                    $partTwoGrid[$step[0] - 1][$step[1] + 1] = 'I';
                }

                $inside = 'N';
            } else {
                if ($partTwoGrid[$step[0] + 1][$step[1] - 1] === NOTHING) {
                    $partTwoGrid[$step[0] + 1][$step[1] - 1] = 'I';
                }
                if ($partTwoGrid[$step[0] + 1][$step[1]] === NOTHING) {
                    $partTwoGrid[$step[0] + 1][$step[1]] = 'I';
                }
                if ($partTwoGrid[$step[0] + 1][$step[1] + 1] === NOTHING) {
                    $partTwoGrid[$step[0] + 1][$step[1] + 1] = 'I';
                }
                $inside = 'S';
            }
            break;
    }
}

// Now we need to replace any patterns that are "I....I" with "IIIIII" since any empty spaces between two I's are inside the loop.
foreach ($partTwoGrid as $rowIndex => $row) {
    $replaced = preg_replace_callback('/I(\.+)I/', fn ($matches) => str_replace('.', 'I',$matches[0]), implode('', $row));
    $partTwoGrid[$rowIndex] = str_split($replaced);
}

//Finally, we go row by row and count the I's.
$total = 0;
foreach ($partTwoGrid as $row) {
    $total += substr_count(implode('', $row), 'I');
}

echo "Part 2: $total\n";





