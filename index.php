<?php 
include('bingo.php');
initializeDataFiles();

if (isset($_POST['winner_card']) && isset($_POST['winner_name'])) {
    $cardId = $_POST['winner_card'];
    $playerName = $_POST['winner_name'];
    checkWinner($cardId, $playerName);
    exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="css/style.css">
        <title>Bingo</title>
    </head>
    <body>
        <div class="container">
            <div class="vegas-title-container">
                <div class="luxury-header">
                    <div class="wealth-image">
                        <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a" alt="Successful businessman with luxury watch">
                    </div>
                    <div class="title-area">
                        <h1 class="vegas-title text-center">
                            <span class="vegas-dollar-sign">$</span> 
                            <span class="vegas-text">BINGO</span> 
                            <span class="vegas-dollar-sign">$</span>
                        </h1>
                        <div class="vegas-subtitle">MILLIONAIRE'S CLUB</div>
                    </div>
                </div>
                <div class="gold-bar"></div>
                <div class="vegas-lights"></div>
            </div>
            
            <div class="bingo-controls mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                Sorteio de Números
                            </div>
                            <div class="card-body">
                                <div class="drawn-numbers">
                                    <h4>Números Sorteados:</h4>
                                    <div id="drawn-numbers-display">
                                        <?php 
                                        $drawnNumbers = getDrawnNumbers();
                                        if (!empty($drawnNumbers)) {
                                            foreach ($drawnNumbers as $number) {
                                                echo "<span class='number-ball'>$number</span>";
                                            }
                                        } else {
                                            echo "<p>Nenhum número sorteado ainda.</p>";
                                        }
                                        ?>
                                    </div>
                                </div>
                                <form method="post" action="">
                                    <button type="submit" name="draw" class="btn btn-primary mt-3">Sortear Número</button>
                                    <button type="submit" name="reset" class="btn btn-danger mt-3">Reiniciar Jogo</button>
                                </form>
                                
                                <?php
                                if (isset($_POST['draw'])) {
                                    $newNumber = drawNumber();
                                    if ($newNumber !== false) {
                                        echo "<div class='alert alert-success mt-3'>Número sorteado: <strong>$newNumber</strong></div>";
                                        echo "<script>setTimeout(function(){ location.reload(); }, 2000);</script>";
                                    } else {
                                        echo "<div class='alert alert-warning mt-3'>Todos os números já foram sorteados!</div>";
                                    }
                                }

                                if (isset($_POST['reset'])) {
                                    resetGame();
                                    echo "<div class='alert alert-info mt-3'>Jogo reiniciado!</div>";
                                    echo "<script>setTimeout(function(){ location.reload(); }, 1000);</script>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                Vencedores
                            </div>
                            <div class="card-body">
                                <div id="winners-list">
                                    <?php
                                    if (file_exists(WINNERS_FILE)) {
                                        $winners = file_get_contents(WINNERS_FILE);
                                        $winners = trim($winners); // Remove espaços em branco
                                        
                                        if (!empty($winners)) {
                                            $winnersArray = explode("\n", $winners);
                                            $winnersArray = array_filter($winnersArray, function($line) {
                                                return trim($line) !== '';
                                            });
                                            
                                            if (!empty($winnersArray)) {
                                                echo "<ul class='list-group'>";
                                                foreach ($winnersArray as $winner) {
                                                    if (!empty(trim($winner))) {
                                                        echo "<li class='list-group-item'><i class='fas fa-trophy text-warning'></i> " . htmlspecialchars($winner) . "</li>";
                                                    }
                                                }
                                                echo "</ul>";
                                            } else {
                                                echo "<p>Nenhum vencedor ainda.</p>";
                                            }
                                        } else {
                                            echo "<p>Nenhum vencedor ainda.</p>";
                                        }
                                    } else {
                                        echo "<p>Nenhum vencedor ainda.</p>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bingo-cards">
                <?php for ($i=1; $i <= 20; $i++): ?>
                    <?php if ($i == 1 || $i == 5 || $i == 9 || $i == 13 || $i == 17): ?>
                    <div class="row">
                    <?php endif; ?>
                        <div class="col-sm-12 col-md-3">
                            <?=bingoCard($i)?>
                        </div>
                    <?php if ($i == 4 || $i == 8 || $i == 12 || $i == 16 || $i == 20): ?>
                    </div>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        </div>
        
        <!-- Winner Modal -->
        <div class="modal fade" id="winnerModal" tabindex="-1" role="dialog" aria-labelledby="winnerModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="winnerModalLabel">BINGO!</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="fas fa-trophy display-1 text-warning mb-3"></i>
                            <h3 id="winner-name">Temos um vencedor!</h3>
                            <p id="winner-card">Cartela #</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bootstrap Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        
        <script>
            $(document).ready(function() {
                const winnerCards = document.querySelectorAll('.winner-badge');
                
                if (winnerCards.length > 0) {
                    const winnerCard = winnerCards[0].closest('.bingo-card');
                    const cardId = winnerCard.id.replace('card-', '');
                    const playerName = winnerCard.getAttribute('data-player');

                    $('#winner-name').text('Parabéns, ' + playerName + '!');
                    $('#winner-card').text('Cartela #' + cardId);

                    $('#winnerModal').modal('show');

                    $.post(location.href, {
                        winner_card: cardId,
                        winner_name: playerName
                    }).done(function(response) {
                        console.log('Vencedor registrado com sucesso');
                    });
                }

                $('#winnerModal').on('hidden.bs.modal', function (e) {});
            });
        </script>
    </body>
</html>
