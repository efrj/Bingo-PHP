<?php
function cardNumbers(int $startNumber, int $endNumber)
{
    $randomNumbers = range($startNumber, $endNumber);
    shuffle($randomNumbers);
    $cardNumbers = array_slice($randomNumbers, 1, 5);
    return $cardNumbers;
}

function bingoCard()
{
    $bNumbers = cardNumbers(1, 15);
    $iNumbers = cardNumbers(16, 30);
    $nNumbers = cardNumbers(31, 45);
    $gNumbers = cardNumbers(46, 60);
    $oNumbers = cardNumbers(61, 75);

    $html = '<div class="bingo-card">';
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

    for ($i=1; $i <= 25; $i++) {
        if ($i == 1 || $i == 6 || $i == 11 || $i == 16) {
            $html .= '<tr>';
        }

        if ($i === 13) {
            $html .= '<td ' . 'class="not-number">@</td>';
        } else {
            if ($i == 1 || $i == 6 || $i == 11 || $i == 16 || $i == 21) {
                $number = array_pop($bNumbers);
                $html .= '<td ' . 'data-i="' . $number . '">' . sprintf("%02d", $number) . '</td>';
            }
            elseif ($i == 2 || $i == 7 || $i == 12 || $i == 17 || $i == 22) {
                $number = array_pop($iNumbers);
                $html .= '<td ' . 'data-i="' . $number . '">' . $number . '</td>';
            }
            elseif ($i == 3 || $i == 8 || $i == 18 || $i == 23) {
                $number = array_pop($nNumbers);
                $html .= '<td ' . 'data-i="' . $number . '">' . $number . '</td>';
            }
            elseif ($i == 4 || $i == 9 || $i == 14 || $i == 19 || $i == 24) {
                $number = array_pop($gNumbers);
                $html .= '<td ' . 'data-i="' . $number . '">' . $number . '</td>';
            }
            elseif ($i == 5 || $i == 10 || $i == 15 || $i == 20 || $i == 25) {
                $number = array_pop($oNumbers);
                $html .= '<td ' . 'data-i="' . $i . '">' . $number . '</td>';
            }
        }

        if ($i == 5 || $i == 10 || $i == 15 || $i == 20) {
            $html .= '</tr>';
        }
    }
    $html .= '</tbody>';
    $html .= '</table>';
    $html .= '</div>';

    return $html;
}
?>
