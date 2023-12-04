<?php

$inputFileName = $argv[1];
$input = file_get_contents(__DIR__.'/'.$inputFileName);

$lines = explode("\n", $input);

array_unshift($lines, str_repeat('.', strlen($lines[0])));
array_push($lines, str_repeat('.', strlen($lines[0])));
foreach ($lines as $row => $line) {
    $lines[$row] = '.'.$line.'.';
}

$total = 0;

$numbers = [];

foreach ($lines as $row => $line) {
    $characters = str_split($line);
    foreach ($characters as $col => $character) {
        if ($character !== '.' && ! is_numeric($character)) {
            if ($lines[$row-1][$col] === '.') {
                if (is_numeric($lines[$row-1][$col-1])) {
                    $number = [];
                    $start = $col - 1;
                    do {
                        $number[] = $lines[$row - 1][$start];
                        $start--;
                    } while (is_numeric($lines[$row - 1][$start]));
                    $numbers[] = (int)implode("", array_reverse($number));
                }

                if (is_numeric($lines[$row-1][$col+1])) {
                    $number = [];
                    $start = $col+1;
                    do {
                        $number[] = $lines[$row-1][$start];
                        $start++;
                    } while (is_numeric($lines[$row-1][$start]));
                    $numbers[] = (int) implode("", $number);
                }
            }

            if ($lines[$row + 1][$col] === '.') {
                if (is_numeric($lines[$row + 1][$col - 1])) {
                    $number = [];
                    $start = $col - 1;
                    do {
                        $number[] = $lines[$row + 1][$start];
                        $start--;
                    } while (is_numeric($lines[$row + 1][$start]));
                    $numbers[] = (int)implode("", array_reverse($number));
                }

                if (is_numeric($lines[$row + 1][$col+1])) {
                    $number = [];
                    $start = $col+1;
                    do {
                        $number[] = $lines[$row + 1][$start];
                        $start++;
                    } while (is_numeric($lines[$row + 1][$start]));
                    $numbers[] = (int) implode("", $number);
                }
            }

            if (is_numeric($lines[$row - 1][$col])) {
                $number = [];
                if (is_numeric($lines[$row - 1][$col - 1])) {
                    $start = $col - 1;
                    do {
                        $number[] = $lines[$row - 1][$start];
                        $start--;
                    } while (is_numeric($lines[$row - 1][$start]));
                    $number = array_reverse($number);
                }
                $number[] = $lines[$row - 1][$col];
                if (is_numeric($lines[$row - 1][$col + 1])) {
                    $start = $col + 1;
                    do {
                        $number[] = $lines[$row - 1][$start];
                        $start++;
                    } while (is_numeric($lines[$row - 1][$start]));
                }
                $numbers[] = (int) implode("", $number);
            }

            if (is_numeric($lines[$row + 1][$col])) {
                $number = [];
                if (is_numeric($lines[$row + 1][$col - 1])) {
                    $start = $col - 1;
                    do {
                        $number[] = $lines[$row + 1][$start];
                        $start--;
                    } while (is_numeric($lines[$row + 1][$start]));
                    $number = array_reverse($number);
                }
                $number[] = $lines[$row + 1][$col];
                if (is_numeric($lines[$row + 1][$col + 1])) {
                    $start = $col + 1;
                    do {
                        $number[] = $lines[$row + 1][$start];
                        $start++;
                    } while (is_numeric($lines[$row + 1][$start]));
                }
                $numbers[] = (int) implode("", $number);
            }

            if (is_numeric($lines[$row][$col-1])) {
                $number = [];
                $start = $col - 1;
                do {
                    $number[] = $lines[$row][$start];
                    $start--;
                } while (is_numeric($lines[$row][$start]));
                $numbers[] = implode("", array_reverse($number));
            }

            if (is_numeric($lines[$row][$col+1])) {
                $number = [];
                $start = $col + 1;
                do {
                    $number[] = $lines[$row][$start];
                    $start++;
                } while (is_numeric($lines[$row][$start]));
                $numbers[] = implode("", $number);
            }
        }
    }
}

echo 'Part 1 Total: '.array_sum($numbers)."\n";

$numbers = [];

foreach ($lines as $row => $line) {
    $characters = str_split($line);
    foreach ($characters as $col => $character) {
        if ($character === '*') {
            $gears = [];
            if ($lines[$row-1][$col] === '.') {
                if (is_numeric($lines[$row-1][$col-1])) {
                    $number = [];
                    $start = $col - 1;
                    do {
                        $number[] = $lines[$row - 1][$start];
                        $start--;
                    } while (is_numeric($lines[$row - 1][$start]));
                    $gears[] = (int)implode("", array_reverse($number));
                }

                if (is_numeric($lines[$row-1][$col+1])) {
                    $number = [];
                    $start = $col+1;
                    do {
                        $number[] = $lines[$row-1][$start];
                        $start++;
                    } while (is_numeric($lines[$row-1][$start]));
                    $gears[] = (int) implode("", $number);
                }
            }

            if ($lines[$row + 1][$col] === '.') {
                if (is_numeric($lines[$row + 1][$col - 1])) {
                    $number = [];
                    $start = $col - 1;
                    do {
                        $number[] = $lines[$row + 1][$start];
                        $start--;
                    } while (is_numeric($lines[$row + 1][$start]));
                    $gears[] = (int)implode("", array_reverse($number));
                }

                if (is_numeric($lines[$row + 1][$col+1])) {
                    $number = [];
                    $start = $col+1;
                    do {
                        $number[] = $lines[$row + 1][$start];
                        $start++;
                    } while (is_numeric($lines[$row + 1][$start]));
                    $gears[] = (int) implode("", $number);
                }
            }

            if (is_numeric($lines[$row - 1][$col])) {
                $number = [];
                if (is_numeric($lines[$row - 1][$col - 1])) {
                    $start = $col - 1;
                    do {
                        $number[] = $lines[$row - 1][$start];
                        $start--;
                    } while (is_numeric($lines[$row - 1][$start]));
                    $number = array_reverse($number);
                }
                $number[] = $lines[$row - 1][$col];
                if (is_numeric($lines[$row - 1][$col + 1])) {
                    $start = $col + 1;
                    do {
                        $number[] = $lines[$row - 1][$start];
                        $start++;
                    } while (is_numeric($lines[$row - 1][$start]));
                }
                $gears[] = (int) implode("", $number);
            }

            if (is_numeric($lines[$row + 1][$col])) {
                $number = [];
                if (is_numeric($lines[$row + 1][$col - 1])) {
                    $start = $col - 1;
                    do {
                        $number[] = $lines[$row + 1][$start];
                        $start--;
                    } while (is_numeric($lines[$row + 1][$start]));
                    $number = array_reverse($number);
                }
                $number[] = $lines[$row + 1][$col];
                if (is_numeric($lines[$row + 1][$col + 1])) {
                    $start = $col + 1;
                    do {
                        $number[] = $lines[$row + 1][$start];
                        $start++;
                    } while (is_numeric($lines[$row + 1][$start]));
                }
                $gears[] = (int) implode("", $number);
            }

            if (is_numeric($lines[$row][$col-1])) {
                $number = [];
                $start = $col - 1;
                do {
                    $number[] = $lines[$row][$start];
                    $start--;
                } while (is_numeric($lines[$row][$start]));
                $gears[] = implode("", array_reverse($number));
            }

            if (is_numeric($lines[$row][$col+1])) {
                $number = [];
                $start = $col + 1;
                do {
                    $number[] = $lines[$row][$start];
                    $start++;
                } while (is_numeric($lines[$row][$start]));
                $gears[] = implode("", $number);
            }

            if (count($gears) === 2) {
                $numbers[] = $gears[0] * $gears[1];
            }
        }
    }
}

echo 'Part 2 Total: '.array_sum($numbers)."\n";