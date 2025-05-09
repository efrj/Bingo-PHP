<?php
include_once('data/players.php');

define('DRAWN_NUMBERS_FILE', 'data/drawn_numbers.txt');
define('WINNERS_FILE', 'data/winners.txt');

function initializeDataFiles()
{
    if (!file_exists('data')) {
        mkdir('data', 0755, true);
    }
    
    if (!file_exists(DRAWN_NUMBERS_FILE)) {
        file_put_contents(DRAWN_NUMBERS_FILE, '');
    }
    
    if (!file_exists(WINNERS_FILE)) {
        file_put_contents(WINNERS_FILE, '');
    }
}

function cardNumbers(int $startNumber, int $endNumber)
{
    $randomNumbers = range($startNumber, $endNumber);
    shuffle($randomNumbers);
    $cardNumbers = array_slice($randomNumbers, 1, 5);
    return $cardNumbers;
}

function getDrawnNumbers()
{
    if (!file_exists(DRAWN_NUMBERS_FILE)) {
        return [];
    }
    
    $content = file_get_contents(DRAWN_NUMBERS_FILE);
    return $content ? explode(',', $content) : [];
}

function drawNumber()
{
    $drawnNumbers = getDrawnNumbers();
    $allNumbers = range(1, 75);
    $availableNumbers = array_diff($allNumbers, $drawnNumbers);
    
    if (empty($availableNumbers)) {
        return false;
    }
    
    $newNumber = $availableNumbers[array_rand($availableNumbers)];
    $drawnNumbers[] = $newNumber;
    file_put_contents(DRAWN_NUMBERS_FILE, implode(',', $drawnNumbers));
    
    return $newNumber;
}

function resetGame()
{
    file_put_contents(DRAWN_NUMBERS_FILE, '');
    file_put_contents(WINNERS_FILE, '');
}

function checkWinner($cardId, $playerName)
{
    if (!file_exists(WINNERS_FILE)) {
        file_put_contents(WINNERS_FILE, '');
    }
    
    $winners = file_get_contents(WINNERS_FILE);
    $winners = trim($winners);
    $winnersArray = $winners ? explode("\n", $winners) : [];
    
    $winnerEntry = "Cartela #$cardId - $playerName - " . date('Y-m-d H:i:s');
    
    foreach ($winnersArray as $winner) {
        if (strpos($winner, "Cartela #$cardId - $playerName") !== false) {
            return count($winnersArray);
        }
    }
    
    $winnersArray[] = $winnerEntry;
    
    $winnersArray = array_filter($winnersArray, function($line) {
        return trim($line) !== '';
    });
    
    $content = implode("\n", $winnersArray);
    if (!empty($content)) {
        $content .= "\n";
    }
    
    file_put_contents(WINNERS_FILE, $content);
    
    return count($winnersArray);
}

function bingoCard($cardId = null)
{
    global $playerNames, $avatarIcons;
    
    if ($cardId === null) {
        $cardId = uniqid();
    }
    
    $playerIndex = array_rand($playerNames);
    $playerName = $playerNames[$playerIndex];
    $avatarIcon = $avatarIcons[array_rand($avatarIcons)];
    
    $bNumbers = cardNumbers(1, 15);
    $iNumbers = cardNumbers(16, 30);
    $nNumbers = cardNumbers(31, 45);
    $gNumbers = cardNumbers(46, 60);
    $oNumbers = cardNumbers(61, 75);
    
    $drawnNumbers = getDrawnNumbers();

    $html = '<div class="bingo-card" id="card-' . $cardId . '" data-player="' . $playerName . '">';
    
    $html .= '<div class="player-info">';
    $html .= '<i class="fas fa-' . $avatarIcon . '"></i> ';
    $html .= '<span class="player-name">' . $playerName . '</span>';
    $html .= '</div>';
    
    $html .= '<table class="table">';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>B</th>';
    $html .= '<th>I</th>';
    $html .= '<th>N</th>';
    $html .= '<th>G</th>';
    $html .= '<th>O</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';

    $cardAllNumbers = [];

    for ($i=1; $i <= 25; $i++) {
        if ($i == 1 || $i == 6 || $i == 11 || $i == 16 || $i == 21) {
            $html .= '<tr>';
        }

        if ($i === 13) {
            $html .= '<td class="free-space marked" data-number="free">@</td>';
        } else {
            if ($i == 1 || $i == 6 || $i == 11 || $i == 16 || $i == 21) {
                $number = array_pop($bNumbers);
                $cardAllNumbers[] = $number;
                $marked = in_array($number, $drawnNumbers) ? 'marked' : '';
                $html .= '<td class="' . $marked . '" data-number="' . $number . '">' . sprintf("%02d", $number) . '</td>';
            }
            elseif ($i == 2 || $i == 7 || $i == 12 || $i == 17 || $i == 22) {
                $number = array_pop($iNumbers);
                $cardAllNumbers[] = $number;
                $marked = in_array($number, $drawnNumbers) ? 'marked' : '';
                $html .= '<td class="' . $marked . '" data-number="' . $number . '">' . $number . '</td>';
            }
            elseif ($i == 3 || $i == 8 || $i == 18 || $i == 23) {
                $number = array_pop($nNumbers);
                $cardAllNumbers[] = $number;
                $marked = in_array($number, $drawnNumbers) ? 'marked' : '';
                $html .= '<td class="' . $marked . '" data-number="' . $number . '">' . $number . '</td>';
            }
            elseif ($i == 4 || $i == 9 || $i == 14 || $i == 19 || $i == 24) {
                $number = array_pop($gNumbers);
                $cardAllNumbers[] = $number;
                $marked = in_array($number, $drawnNumbers) ? 'marked' : '';
                $html .= '<td class="' . $marked . '" data-number="' . $number . '">' . $number . '</td>';
            }
            elseif ($i == 5 || $i == 10 || $i == 15 || $i == 20 || $i == 25) {
                $number = array_pop($oNumbers);
                $cardAllNumbers[] = $number;
                $marked = in_array($number, $drawnNumbers) ? 'marked' : '';
                $html .= '<td class="' . $marked . '" data-number="' . $number . '">' . $number . '</td>';
            }
        }

        if ($i == 5 || $i == 10 || $i == 15 || $i == 20 || $i == 25) {
            $html .= '</tr>';
        }
    }
    $html .= '</tbody>';
    $html .= '</table>';
    
    $winningPatterns = checkWinningPattern($cardAllNumbers, $drawnNumbers, $cardId, $playerName);
    if ($winningPatterns) {
        $html .= '<div class="winner-badge">BINGO!</div>';
    }
    
    $html .= '</div>';

    return $html;
}

function checkWinningPattern($cardNumbers, $drawnNumbers, $cardId = null, $playerName = null)
{
    $drawnNumbers[] = 'free';
    
    $winPatterns = [
        // Lines
        [0, 1, 2, 3, 4],
        [5, 6, 7, 8, 9],
        [10, 11, 12, 13, 14],
        [15, 16, 17, 18, 19],
        [20, 21, 22, 23, 24],
        // Coluns
        [0, 5, 10, 15, 20],
        [1, 6, 11, 16, 21],
        [2, 7, 12, 17, 22],
        [3, 8, 13, 18, 23],
        [4, 9, 14, 19, 24],
        // Diagonals
        [0, 6, 12, 18, 24],
        [4, 8, 12, 16, 20]
    ];
    
    $matches = [];
    foreach ($winPatterns as $pattern) {
        $match = true;
        foreach ($pattern as $index) {
            if (!isset($cardNumbers[$index]) || !in_array($cardNumbers[$index], $drawnNumbers)) {
                $match = false;
                break;
            }
        }
        if ($match) {
            $matches[] = $pattern;
        }
    }
    
    if (!empty($matches) && $cardId !== null && $playerName !== null) {
        $winnersData = file_get_contents(WINNERS_FILE);
        $existingWinners = explode("\n", trim($winnersData));
        $alreadyWinner = false;
        
        foreach ($existingWinners as $winner) {
            if (strpos($winner, "Cartela #$cardId") !== false) {
                $alreadyWinner = true;
                break;
            }
        }
        
        if (!$alreadyWinner) {
            checkWinner($cardId, $playerName);
        }
    }
    
    return $matches;
}

?>
