<?php 
session_start();
require_once("api/API.php");
$nonce = API::generateNonce();
$_SESSION["nonce"] = $nonce;

define("SITE", "Home");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Home</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
    <link rel="stylesheet" href="styles/custom.css">
</head>

<body>
    <?php include("menu.php"); ?>
    <div id="content" class="pos-rel">
        <div class="filter closed">
            <span id="filterToggle">🔍 Szűrés</span>
            <div class="filterContainer">
                <div style="padding: 15px;">
                    <input type="checkbox" name="filterType[]" id="all" value="all"> <label for="all">Összes típus</label>
                    <hr>
                    <div class="filterAll">
                        <span>
                            <input type="checkbox" name="filterType[]" id="bug" value="bug"> <label for="bug">Bug</label>
                        </span>
                        <span>
                            <input type="checkbox" name="filterType[]" id="dark" value="dark"> <label for="dark">Dark</label>
                        </span>
                        <span>
                            <input type="checkbox" name="filterType[]" id="dragon" value="dragon"> <label for="dragon">Dragon</label>
                        </span>
                        <span>
                            <input type="checkbox" name="filterType[]" id="electric" value="electric"> <label for="electric">Electric</label>
                        </span>
                        <span>
                            <input type="checkbox" name="filterType[]" id="fairy" value="fairy"> <label for="fairy">Fairy</label>
                        </span>
                        <span>
                            <input type="checkbox" name="filterType[]" id="fighting" value="fighting"> <label for="fighting">Fighting</label>
                        </span>
                        <span>
                            <input type="checkbox" name="filterType[]" id="fire" value="fire"> <label for="fire">Fire</label>
                        </span>
                        <span>
                            <input type="checkbox" name="filterType[]" id="flying" value="flying"> <label for="flying">Flying</label>
                        </span>
                        <span>
                            <input type="checkbox" name="filterType[]" id="ghost" value="ghost"> <label for="ghost">Ghost</label>
                        </span>
                        <span>
                            <input type="checkbox" name="filterType[]" id="grass" value="grass"> <label for="grass">Grass</label>
                        </span>
                        <span>
                            <input type="checkbox" name="filterType[]" id="ground" value="ground"> <label for="ground">Ground</label>
                        </span>
                        <span>
                            <input type="checkbox" name="filterType[]" id="ice" value="ice"> <label for="ice">Ice</label>
                        </span>
                        <span>
                            <input type="checkbox" name="filterType[]" id="normal" value="normal"> <label for="normal">Normal</label>
                        </span>
                        <span>
                            <input type="checkbox" name="filterType[]" id="poison" value="poison"> <label for="poison">Poison</label>
                        </span>
                        <span>
                            <input type="checkbox" name="filterType[]" id="psychic" value="psychic"> <label for="psychic">Psychic</label>
                        </span>
                        <span>
                            <input type="checkbox" name="filterType[]" id="rock" value="rock"> <label for="rock">Rock</label>
                        </span>
                        <span>
                            <input type="checkbox" name="filterType[]" id="steel" value="steel"> <label for="steel">Steel</label>
                        </span>
                        <span>
                            <input type="checkbox" name="filterType[]" id="water" value="water"> <label for="water">Water</label>
                        </span>
                    </div>
                </div>
                
                <button id="filterBtn">Szűrés >></button>
            </div>
        </div>
        <div id="card-list">
            <?php 
            $cards = API::getCards();
            foreach($cards as $card):
                //if($card->owner != 0) continue;
                if(isset($_GET["filter"]) && !empty($_GET["filter"]) && $_GET["filter"] != "all" && !in_array($card->type, explode(",", $_GET["filter"]))) continue;
                ?>
                <div class="pokemon-card">
                    <div class="image clr-<?= $card->type ?>">
                        <img src="<?= API::getCardImageUrl($card->id) ?>" alt="">
                    </div>
                    <div class="details">
                        <h2><a href="details/<?= $card->id ?>"><?= $card->name ?></a></h2>
                        <span class="card-type"><span class="icon">🏷</span> <?= $card->type ?></span>
                        <span class="attributes">
                            <span class="card-hp"><span class="icon">❤</span> <?= $card->hp ?></span>
                            <span class="card-attack"><span class="icon">⚔</span> <?= $card->attack ?></span>
                            <span class="card-defense"><span class="icon">🛡</span> <?= $card->defense ?></span>
                        </span>
                    </div>
                    <?php 
                    if(isset($_SESSION["user"]) && $_SESSION["user"]["isAdmin"] == false):
                        ?>
                        <div class="buy" data-id="<?= $card->id ?>">
                            <span class="card-price">
                                <?php if($card->owner != 0): ?>
                                    ⛔ Eladva
                                <?php else: ?>
                                    <span class="icon">💰</span> <?= $card->price ?>
                                <?php endif; ?>
                            </span>
                        </div>
                    <?php elseif(isset($_SESSION["user"]) && $_SESSION["user"]["isAdmin"] == true): ?>
                        <div class="adminbuy" data-id="<?= $card->id ?>">
                            <span class="card-price">
                                Tulaj: <?= API::getUserById($card->owner)->name ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?> 
        </div>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/alerts.js"></script>
    <script>
        $(document).ready(() => {
            $("div.buy").on("click", (event) => {
                const target = $(event.currentTarget);
                $.ajax({
                    type: "POST",
                    url: "/api/purchase.php",
                    data: {
                        cardId: target.data("id"),
                        nonce: "<?= $nonce ?>"
                    },
                    success: (response) => {
                        const res = JSON.parse(response);
                        /*  
                            code: 200 - successfull purchase
                            code: 401 - nonexistent card
                            code: 400 - not enough money
                            code: 403 - unauthorized
                        */
                        console.log(res)
                        switch (res.code){
                            case 200:
                                $("span#balance").html("💰 " + res.data.balance);
                                target.find('span.card-price').first().html("⛔ Eladva");
                                createAlert("SUCCESS", "Sikeresen megvetted a kártyát.");
                                break;
                            case 401:
                                createAlert("ERROR", "A kártya amelyet megpróbáltál megvenni, nem létezik.");
                                break;
                            case 400:
                                createAlert("ERROR", "A kártya megvételéhez nincs elegendő pénzed.");
                                break;
                            case 402:
                                createAlert("ERROR", "Elérted a maximálisan birtokolható kártyák számát (5). Előbb adj el párat!");
                                break;
                            case 403:
                                createAlert("ERROR", "Nincs jogosultságod ehhez a művelethez!");
                                break;
                            case 405:
                                createAlert("INFO", "A kártya tulajdonosa <b>" + res.data.owner + "</b>.");
                                break;

                        }
                        
                    },
                    error: () => {
                        createAlert("ERROR", "Valamilyen hiba folytán nem tudtuk kezelni a kérésedet. Kérlek, próbáld meg újra!");
                        
                    }
                });
            });

            $(".filterAll span").on("click", () => {
                $("div.filter #all").prop("checked", false);
            })
            $("div.filter #all").on("click", () => {
                $("div.filterAll input").prop("checked", false);
            })

            $("button#filterBtn").on("click", () => {
                let checkedValues = $('input[name="filterType[]"]:checked').map(function() {
                    return $(this).val();
                }).get();
                let getString = "";
                checkedValues.forEach(value => {
                    getString += value + ","
                });
                if(getString == ""){
                    window.location.href = "/?filter=all"
                }else{
                    getString = getString.substring(0, getString.length-1)
                    window.location.href = "/?filter=" + getString
                }
                
            })

            function getParameter(key) {
                let address = window.location.search;
                let parameterList = new URLSearchParams(address);
                return parameterList.get(key);
            }

            let getString = getParameter("filter");
            if(getString != null){
                $('input[name="filterType[]"]').map(function() {
                    if(getString.includes($(this).val())){
                        $(this).prop("checked", true);
                    }
                })
            }
            if(getString == null || getString.includes("all") || getString == ""){
                $('input[name="filterType[]"]').map(function() {
                    if($(this).val() == "all"){
                        $(this).prop("checked", true);
                    }else{
                        $(this).prop("checked", false);
                    }
                    
                })
            }
            $("#filterToggle").on("click", () => {
                $(".filterContainer").toggleClass("open");
                $(".filter").toggleClass("closed");
            })
        });
    </script>
    <?php if(isset($_GET["successfull-login"]) && isset($_SESSION["user"])): ?>
        <script>
            createAlert("SUCCESS", "Sikeres bejelentkezés, üdv <b><?= API::getUserById($_SESSION["user"]["id"])->name ?>!</b>");
        </script>
    <?php elseif(isset($_GET["successfull-logout"])): ?>
        <script>
            createAlert("SUCCESS", "Sikeres kijelentkezés!");
        </script>
    <?php elseif(isset($_GET["successfull-reg"]) && isset($_SESSION["user"])): ?>
        <script>
            createAlert("SUCCESS", "Sikeres regisztráció! Üdvözlünk az oldalon, <b><?= API::getUserById($_SESSION["user"]["id"])->name ?></b> :) A vásárlást azonnal el is kezdheted, ehhez jóváírtunk a számládon 💰3000-et!");
        </script>
    <?php endif; ?>
</body>

</html>