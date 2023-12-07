<?php

$inputFileName = $argv[1];
$input = file_get_contents(__DIR__.'/'.$inputFileName);

$lines = explode("\n", $input);

$hands = [];

foreach ($lines as $line) {
    list ($hand, $bid) = explode(" ", $line);
    $cards = str_split($hand);

    $replace = [
        'T' => 10,
        'J' => 11,
        'Q' => 12,
        'K' => 13,
        'A' => 14,
    ];
    foreach ($cards as &$card) {
        if (isset($replace[$card])) {
            $card = $replace[$card];
        } else {
            $card = (int) $card;
        }
    }

    $values = array_count_values($cards);

    if (count($values) === 5) {
        $rank = 0;
    } elseif (count($values) === 4) {
        $rank = 1;
    } elseif (count($values) === 3) {
        $points = array_values($values);
        rsort($points);
        if ($points[1] === 2) {
            $rank = 2;
        } else {
            $rank = 3;
        }
    } elseif (count($values) === 2) {
        $points = array_values($values);
        rsort($points);
        if ($points[0] === 3) {
            $rank = 4;
        } else {
            $rank = 5;
        }
    } else {
        $rank = 6;
    }

    $hands[] = [
        'hand' => $cards,
        'bid' => (int)$bid,
        'rank' => $rank,
    ];
}

function sortHands(array &$hands): void
{
    usort($hands, function ($a, $b) {
        if ($a['rank'] > $b['rank']) {
            return 1;
        } elseif ($a['rank'] < $b['rank']) {
            return -1;
        }

        for ($i = 0; $i < 5; $i++) {
            if ($a['hand'][$i] > $b['hand'][$i]) {
                return 1;
            } elseif ($a['hand'][$i] < $b['hand'][$i]) {
                return -1;
            }
        }

        return 0;
    });
}

function displayHands(array $hands): void
{

    $handRanks = [
        0 => 'Highest Card',
        1 => 'One Pair',
        2 => 'Two Pair',
        3 => 'Three of a Kind',
        4 => 'Full House',
        5 => 'Four of a Kind',
        6 => 'Five of a Kind',
    ];

    foreach ($hands as $index => $h) {
        echo implode("\t", $h['hand']) . "\t" . $handRanks[$h['rank']] . "\t" . $h['bid'] . "\n";
    }
}

sortHands($hands);
//displayHands($hands);

$totalWinnings = 0;

foreach ($hands as $index => $hand) {
    $totalWinnings += ($hand['bid'] * ($index + 1));
}

echo 'Part 1: '.$totalWinnings."\n";

foreach ($hands as &$hand) {
    foreach ($hand['hand'] as &$card) {
        if ($card === 11) {
            $card = 0;
        }
    }

    $values = array_count_values($hand['hand']);

    if (! array_key_exists(0, $values)) {
        continue;
    }

    if (count($values) === 5) {
        $rank = 1;
    } elseif (count($values) === 4) {
        $rank = 3;
    } elseif (count($values) === 3) {
        $points = array_values($values);
        rsort($points);
        if ($points[1] === 2) {
            if ($values[0] === 2) {
                $rank = 5;
            } else {
                $rank = 4;
            }
        } else {
            $rank = 5;
        }
    } else {
        $rank = 6;
    }

    $hand['rank'] = $rank;
}

sortHands($hands);

$totalWinnings = 0;

foreach ($hands as $index => $h) {
    $totalWinnings += ($h['bid'] * ($index + 1));
}

echo 'Part 2: '.$totalWinnings."\n";