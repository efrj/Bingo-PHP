<?php include('bingo.php') ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <div class="container">
            <h1 class="text-center">Jogo de Bingo</h1>
            <p class="text-center">Aqui estão as 20 cartelas de bingo:</p>
            <div class="bingo-cards">
                <?php
                for ($i=0; $i < 4; $i++) {
                    echo '<!-- Cartela ' . $i . ' -->';
                    echo bingoCard();
                }
                ?>
            </div>
        </div>
    </body>
</html>