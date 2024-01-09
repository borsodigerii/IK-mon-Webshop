<?php 
session_start();

if(!isset($_SESSION["user"]) || $_SESSION["user"]["isAdmin"] != true){
    header("Location: /");
    die();
}

require_once("../api/API.php");
$nonce = API::generateNonce();
$_SESSION["nonce"] = $nonce;

define("SITE", "Admin vezérlőpult");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Admin vezérlőpult</title>
    <link rel="stylesheet" href="../styles/main.css">
    <link rel="stylesheet" href="../styles/cards.css">
    <link rel="stylesheet" href="../styles/custom.css">
</head>

<body>
    <?php include("../menu.php"); ?>
    <div id="content">
        <a href="/admin/create" class="adminBtn">Új kártya felvitele</a>
        <span style="color: gray;margin-left: 10px;">Tipp: kártyát törölni a kártya "Módosítás" gombjára kattintva tudsz!</span>
        <div id="card-list">
            <?php 
            $cards = API::getCards();
            foreach($cards as $card):
                //if($card->owner != 0) continue;
                ?>
                <div class="pokemon-card">
                    <div class="image clr-<?= $card->type ?>">
                        <img src="<?= API::getCardImageUrl($card->id) ?>" alt="">
                    </div>
                    <div class="details">
                        <h2><?= $card->name ?></h2>
                        <span class="card-type"><span class="icon">🏷</span> <?= $card->type ?></span>
                        <span class="attributes">
                            <span class="card-hp"><span class="icon">❤</span> <?= $card->hp ?></span>
                            <span class="card-attack"><span class="icon">⚔</span> <?= $card->attack ?></span>
                            <span class="card-defense"><span class="icon">🛡</span> <?= $card->defense ?></span>
                        </span>
                    </div>
                    <?php if($card->owner == 0): ?>
                        <div class="buy" data-id="<?= $card->id ?>">
                            <span class="card-price">
                                ✏️ Módosítás
                            </span>
                        </div>
                    <?php else: ?>
                        <div class="adminbuy" data-id="<?= $card->id ?>">
                            <span class="card-price">
                                ⛔ Eladva
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?> 
        </div>
    </div>
    <footer <?= strpos($_SERVER["REQUEST_URI"], 'admin') !== false ? "style='background-color: #eb4034'" : "" ?>>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/alerts.js"></script>
    <script>
        $(document).ready(() => {
            $("div.buy").on("click", (event) => {
                window.location.href = "/admin/edit/" + $(event.currentTarget).data("id");
            });
        });
    </script>
    <?php if(isset($_GET["successfull-edit"]) && isset($_SESSION["user"])): ?>
        <script>
            createAlert("SUCCESS", "Sikeres módosítás");
        </script>
    <?php endif; ?>
    <?php if(isset($_GET["successfull-add"]) && isset($_SESSION["user"])): ?>
        <script>
            createAlert("SUCCESS", "Sikeres felvétel!");
        </script>
    <?php endif; ?>
    <?php if(isset($_GET["successfull-del"]) && isset($_SESSION["user"])): ?>
        <script>
            createAlert("SUCCESS", "Sikeres törlés!");
        </script>
    <?php endif; ?>
</body>

</html>