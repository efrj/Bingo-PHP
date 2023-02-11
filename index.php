<?php include('bingo.php') ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
        <link rel="stylesheet" href="css/style.css">
        <title>Bingo</title>
    </head>
    <body>
        <div class="container">
            <h1 class="text-center"><i class="bi bi-table"></i> Bingo</h1>
                <div class="bingo-cards">
                    <?php for ($i=1; $i <= 20; $i++): ?>
                        <?php if ($i == 1 || $i == 5 || $i == 9 || $i == 13 || $i == 17): ?>
                        <div class="row">
                        <?php endif; ?>
                            <div class="col-sm-12 col-md-3">
                                <!-- Cartela ' . <?=$i?> . ' -->
                                <?=bingoCard()?>
                            </div>
                        <?php if ($i == 4 || $i == 8 || $i == 12 || $i == 16 || $i == 20): ?>
                        </div>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
        </div>
    </body>
</html>