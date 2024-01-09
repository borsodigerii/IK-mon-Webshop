<?php 
session_start();

if(!isset($_SESSION["user"])){
    header("Location: /");
    die();
}

require_once($_SERVER["DOCUMENT_ROOT"]."/api/API.php");
$nonce = API::generateNonce();
$_SESSION["nonce"] = $nonce;
define("SITE", "Adataim");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Adataim</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
    <link rel="stylesheet" href="styles/custom.css">
    <link rel="stylesheet" href="styles/user.css">
</head>

<body>
    <?php include($_SERVER["DOCUMENT_ROOT"]."/menu.php"); ?>
    <div id="content">
        <div class="userdata">
            <div>
                <h2><?= $_SESSION["user"]["name"] ?></h2>
                <hr>
                <div class="properties">
                    <?php $user = API::getUserById($_SESSION["user"]["id"]); ?>
                    <span class="balanceSpan">💰 <?= $user->balance ?></span>
                    <span>✉️ <?= $user->email ?></span>
                    <span class="cardsSpan">🃏 <?= API::getCardCountForUser($user->id) ?> / 5</span>
                </div>
            </div>
        </div>
        <div id="card-list">
        <?php 
            if(API::getCardCountForUser($user->id) > 0):
                $cards = API::getCards();
            
                foreach($cards as $card):
                    if($card->owner != $user->id) continue;
                    ?>
                    <div class="pokemon-card">
                        <div class="image clr-<?= $card->type ?>">
                            <img src="<?= $card->image ?>" alt="">
                        </div>
                        <div class="details">
                            <h2><a href="/details/<?= $card->id ?>"><?= $card->name ?></a></h2>
                            <span class="card-type"><span class="icon">🏷</span> <?= $card->type ?></span>
                            <span class="attributes">
                                <span class="card-hp"><span class="icon">❤</span> <?= $card->hp ?></span>
                                <span class="card-attack"><span class="icon">⚔</span> <?= $card->attack ?></span>
                                <span class="card-defense"><span class="icon">🛡</span> <?= $card->defense ?></span>
                            </span>
                        </div>
                        <?php 
                        if(isset($_SESSION["user"])):
                            ?>
                            <div class="buy" data-id="<?= $card->id ?>">
                                <span class="card-price"><span class="icon">Eladási ár: 💰</span> <s><?= $card->price ?></s> <span style="color: #fc3838;"><?= $card->price*0.9 ?></span></span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?> 
            <?php else: ?>
                <br><br>Még nincs kártyád. Látogass el a főoldalra, hogy jobbnál jobb kártyákat vásárolhass!
            <?php endif; ?>
            
        </div>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
    <script src="js/alerts.js"></script>
    <script src="js/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(() => {
            $("div.buy").on("click", (event) => {
                const target = $(event.currentTarget);
                $.ajax({
                    type: "POST",
                    url: "/api/sell.php",
                    data: {
                        cardId: target.data("id"),
                        nonce: "<?= $nonce ?>"
                    },
                    success: (response) => {
                        const res = JSON.parse(response);
                        /*  
                            code: 200 - successfull sell
                            code: 401 - nonexistent card
                            code: 400 - this user is not the owner of the card
                            code: 403 - unauthorized
                        */
                        console.log(res)
                        switch (res.code){
                            case 200:
                                target.parent().addClass("purchased");
                                $("span#balance").html("💰 " + res.data.balance);
                                $("span.balanceSpan").html("💰 " + res.data.balance);
                                $("span.cardsSpan").html("🃏 " + res.data.cardCount + " / 5");
                                
                                setTimeout(() => {
                                    target.parent().css("display", "none");
                                    if(res.data.cardCount == 0){
                                        $("div#card-list").html("<br><br>Még nincs kártyád. Látogass el a főoldalra, hogy jobbnál jobb kártyákat vásárolhass!");
                                    }
                                    createAlert("SUCCESS", "Sikeresen eladtad a kártyát.");
                                }, 300);
                                break;
                            case 401:
                                createAlert("ERROR", "A kártya amit megpróbáltál eladni, nem létezik.")
                                break;
                            case 400:
                                createAlert("ERROR", "Nem te vagy a kártya tulajdonosa, így nem adhatod el azt!");
                                break;
                            case 403:
                                createAlert("ERROR", "Nincs jogosultságod ehhez a művelethez!");
                                break;
                        }
                        
                    },
                    error: () => {
                        createAlert("ERROR", "Valamilyen hiba folytán nem tudtuk kezelni a kérésedet. Kérlek, próbáld meg újra!");
                        
                    }
                });
            });
        });
    </script>
    
</body>
</html>