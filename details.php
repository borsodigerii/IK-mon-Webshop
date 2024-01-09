<?php 

if((!isset($_GET["id"]) || $_GET["id"] === "") && !isset($globalDetailsId)){
    header("Location: /");
    die();
}
session_start();
require_once("api/API.php");
$id = isset($globalDetailsId) ? $globalDetailsId : $_GET["id"];
$card = API::getCardById(intval($id));
define("SITE", "Kártyák > " .$card->name);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | <?= $card->name ?></title>
    <link rel="stylesheet" href="/styles/main.css">
    <link rel="stylesheet" href="/styles/details.css">
    <link rel="stylesheet" href="/styles/custom.css">
</head>

<body>
    <?php include("menu.php"); ?>
    <div id="content">
        <div class="details">
            <div class="image">
                <img src="<?= API::getCardImageUrl($card->id) ?>" alt="">
                <div class="cycle" style="background: var(--poke-clr-<?= $card->type ?>);"></div>
            </div>
            <div class="text">
                <h2><?= $card->name ?></h2>
                <h3>Tulajdonos: <?= API::getUserById($card->owner)->name ?></h3>
                <div class="card-attributes">
                    <span class="card-type">
                        <span class="icon">🏷</span> <?= $card->type ?>
                    </span>
                    <span class="card-hp">
                        <span class="icon">❤</span> <?= $card->hp ?>
                    </span>
                    <span class="card-attack">
                        <span class="icon">⚔</span> <?= $card->attack ?>
                    </span>
                    <span class="card-defense">
                        <span class="icon">🛡</span> <?= $card->defense ?>
                    </span>
                </div>
                <div class="description">
                    <?= $card->description ?>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>
</html>