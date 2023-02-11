<?php
function cardNumbers()
{
    $randomNumbers = range(1, 75);
    shuffle($randomNumbers);
    $cardNumbers = array_slice($randomNumbers, 1, 25);
    return $cardNumbers;
}

function bingoCard()
{
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
            
    $cardNumbers = cardNumbers();
    foreach($cardNumbers as $key => $number) {
        if ($key == 0 || $key == 5 || $key == 10 || $key == 15) {
            $html .= '<tr>';
        }

        if ($key !== 12) {
            $html .= '<td ' . 'data-key="' . $key . '">' . $number . '</td>';
        } else {
            $html .= '<td ' . 'class="not-number">@</td>';
        }

        if ($key == 4 || $key == 9 || $key == 14 || $key == 19) {
            $html .= '</tr>';
        }
    }
    $html .= '</tbody>';
    $html .= '</table>';
    $html .= '</div>';

    return $html;
}
?>
