<?php
session_start();
require_once "objects/player.php";
require_once "utils/config.php";
require_once "utils/elements.php";

$player = unserialize($_SESSION["player"]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Domovská stránka</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
</head>

<body>
    <div class="m-5">
        <div class="row">
            <div class="col-6">
                <a class="btn btn-outline-danger" href="sign/logout.php"><i class="bi bi-box-arrow-left"></i></a>
            </div>
            <div class="col-6">
                <?php echo "<p>Logged as " . $player->renderNameWithLevel() . "</p>"; ?>
            </div>
        </div>
        <h1 class="m-5"><?php echo $gameName; ?></h1>
        <div class="row mt-5">
            <div class="col-4" id="battlePanel">
                <h2>Battles</h2>
                <!-- BUG zakázat, pokud nevyužil všechny treepointy -->
                <a class="btn btn-danger" href="battles/casual/casual.php">Begin new</a>
                <h3 class="mt-3">Active battles</h3>
                <table class="table table-striped table-hover mt-3">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Attack initiated</th>
                            <th scope="col">Defender</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($player->get_casual_battle($link, true) as $log) {
                            $log->renderBattleLog();
                        }
                        ?>
                    </tbody>
                </table>
                <h3 class="mt-3">Incoming attacks</h3>
                <table class="table table-striped table-hover mt-3">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Attack arrived</th>
                            <th scope="col">Attacker</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($player->get_casual_battle($link, false) as $incomingAttack) {
                            $incomingAttack->renderAttackRow();
                        }
                        ?>
                    </tbody>
                </table>
                <h3 class="mt-3">History</h3>
                <?php echo "<p>Wins: <b>" . $player->get_wins($link) . "</b>, Loses: <b>" . $player->get_loses($link) . "</b>, Win-rate: <b>" . $player->get_win_rate() . "</b></p>";?>
                <table class="table table-striped table-hover mt-3">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Battle end</th>
                            <th scope="col">Opponent</th>
                            <th scope="col">Type</th>
                            <th scope="col">Result</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($player->get_history($link) as $battle) {
                            $battle->renderHistory($player->id);
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="col-4">
                <h2>Elements</h2>
                <a class="btn btn-danger" href="trees/showTree.php?type=0">Fire</a>
                <a class="btn btn-primary" href="trees/showTree.php?type=1">Water</a>
                <a class="btn btn-success" href="trees/showTree.php?type=2">Earth</a>
                <a class="btn btn-info" href="trees/showTree.php?type=3">Air</a>
            </div>
            <div class="col-4">
                <h2>třeba soc. centrum/shop/mage/clan/idk</h2>
                [ ] ošetřit quitnutí před zahájením battleu<br>
                [x] zíksat defender_id a jeho trees<br>
                [x] vybrat random moje elementy<br>
                [x] skládání<br>
                [x] get ids na attack<br>
                [x] INSERT INTO casual_games<br>
                [x] Attacker - Battle logs (waiting for response/results known)<br>
                [x] Defender - incoming attacks<br>
                [x] UPDATE casual_games o defense<br>
                [x] show results button<br>
                [x] update attacker_seen<br>
                [x] history na obou stranách
            </div>
        </div>
    </div>

    <div class="modal fade" id="fightModal" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Battle summary</h5>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-hover mt-3">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Name</th>
                                <th scope="col">Type</th>
                                <th scope="col">Tier</th>
                                <th scope="col">Description</th>
                            </tr>
                        </thead>
                        <tbody id="fightModalBody">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" id="dismissModal">Dismiss</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            var reload = false;
            $(".showResults").click(function() {
                var battleId = $(this).data("battleId");
                $("#fightModal").modal("show");
                $.ajax({
                    type: 'POST',
                    url: 'battles/casual/showSummary.php',
                    data: {
                        battleId: battleId
                    },
                    success: function(response) {
                        $("#fightModalBody").html(response);
                        $("#fightModal").modal("show");
                        reload = true;
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr);
                        console.log(status);
                        console.log(error);
                    },
                    dataType: "json"
                });
            });

            $(".historyDetail").click(function() {
                var battleId = $(this).data("battleId");
                $("#fightModal").modal("show");
                $.ajax({
                    type: 'POST',
                    url: 'battles/casual/showSummary.php',
                    data: {
                        battleId: battleId,
                        isHistory: true
                    },
                    success: function(response) {
                        $("#fightModalBody").html(response);
                        $("#fightModal").modal("show");
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr);
                        console.log(status);
                        console.log(error);
                    },
                    dataType: "json"
                });
            });

            $("#dismissModal").click(function() {
                $("#fightModal").modal("hide");
                if(reload){
                    $("#battlePanel").load(location.href + " #battlePanel");
                    reload = false;
                }
            });
        });
    </script>

    <?php footer($gameName); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>

</html>