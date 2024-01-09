<?php 
session_start();
if(!isset($_SESSION["user"]) || $_SESSION["user"]["isAdmin"] != true){
    header("Location: /");
    die();
}
$errors = array();
require_once($_SERVER["DOCUMENT_ROOT"]."/api/API.php");
$target_dir = $_SERVER["DOCUMENT_ROOT"].DIRECTORY_SEPARATOR. "images" .DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR;
$id = uniqid();
if(isset($_POST["addCard"])){
    // edit folyamatban
    if(!isset($_POST["name"]) || empty($_POST["name"])){
        array_push($errors, "A név megadása kötelező!");
    }
    if(!isset($_POST["type"]) || empty($_POST["type"])){
        array_push($errors, "A típus megadása kötelező!");
    }
    if(!isset($_POST["hp"]) || empty($_POST["hp"]) || !filter_var($_POST["hp"], FILTER_VALIDATE_INT)){
        array_push($errors, "Az életerő megadása egész számként kötelező!");
    }
    if(!isset($_POST["attack"]) || empty($_POST["attack"]) || !filter_var($_POST["attack"], FILTER_VALIDATE_INT)){
        array_push($errors, "A támadás megadása egész számként kötelező!");
    }
    if(!isset($_POST["defense"]) || empty($_POST["defense"]) || !filter_var($_POST["defense"], FILTER_VALIDATE_INT)){
        array_push($errors, "A védekezés megadása egész számként kötelező!");
    }
    if(!isset($_POST["desc"]) || empty($_POST["desc"])){
        array_push($errors, "A leírás megadása kötelező!");
    }
    if(!isset($_FILES["image"]["name"])){
        array_push($errors, "A kép megadása kötelező!");
    }else{
        
        
        $imageFileType = strtolower(pathinfo(basename($_FILES["image"]["name"]), PATHINFO_EXTENSION));
        $target_file = $target_dir . $id . "." . $imageFileType;
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if(!$check){
            array_push($errors, "A feltöltött fájl nem kép!");
        }
    
        if($_FILES["image"]["size"] > 1000000){
            array_push($errors, "A feltöltött kép túl nagy! Max. fájlméret: 1MB");
        }
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            array_push($errors, "A feltöltött kép formátuma nem megfelelő! Elfogadott kiterjesztések: png,jpg,jpeg");
        }
    }
    

    if(!isset($_POST["price"]) || empty($_POST["price"]) || !filter_var($_POST["price"], FILTER_VALIDATE_INT)){
        array_push($errors, "Az ár megadása egész számként kötelező!");
    }

    $validTypes = ["bug", "dark", "dragon", "electric", "fairy", "fighting", "fire", "flying", "ghost", "grass", "ground", "ice", "normal", "poison", "psychic", "rock", "steel", "water"];
    if(!in_array($_POST["type"], $validTypes)){
        array_push($errors, "A megadott típus érvénytelen!");
    }

    if(count($errors) == 0){
        // nincs hiba
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $new = new Card(API::getNextCardId(), $_POST["name"], $_POST["type"], $_POST["hp"], $_POST["defense"], $_POST["attack"], $_POST["price"], $_POST["desc"], $id . "." . $imageFileType, 0);

            API::addCard($new);

            header("Location: /admin/?successfull-add");
            die();
        } else {
            array_push($errors, "A képet nem sikerült feltölteni egy ismeretlen hiba miatt.");
        }

        
    }
}


define("SITE", "Új kártya létrehozása");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Új kártya létrehozása></title>
    <link rel="stylesheet" href="/styles/main.css">
    <link rel="stylesheet" href="/styles/details.css">
    <link rel="stylesheet" href="/styles/custom.css">
</head>

<body>
    <?php include($_SERVER["DOCUMENT_ROOT"]."/menu.php"); ?>
    <div id="content">
        <a href="/admin" class="adminBtn">< Mégse</a>
        <div class="details">
            <div class="image">
                <img src="/images/pokeball.png" alt="" id="prevImg">
                <div class="cycle" style="background: var(--poke-clr-bug);"></div>
            </div>
            <div class="text">
                <form method="post" enctype="multipart/form-data">
                    <input type="text" name="name" id="name" class="detailsH2" value="<?= isset($_POST["name"]) && !empty($_POST["name"]) ? $_POST["name"] : "" ?>" placeholder="Név..">
                    <div class="card-attributes">
                        <span class="card-type">
                            <span class="icon">🏷</span> <select name="type" id="type">
                                <option value="bug" <?= isset($_POST["type"]) && $_POST["type"] == "bug" ? "selected" : "" ?>>bug</option>
                                <option value="dark" <?= isset($_POST["type"]) && $_POST["type"] == "dark" ? "selected" : "" ?>>dark</option>
                                <option value="dragon" <?= isset($_POST["type"]) && $_POST["type"] == "dragon" ? "selected" : "" ?>>dragon</option>
                                <option value="electric" <?= isset($_POST["type"]) && $_POST["type"] == "electric" ? "selected" : "" ?>>electric</option>
                                <option value="fairy" <?= isset($_POST["type"]) && $_POST["type"] == "fairy" ? "selected" : "" ?>>fairy</option>
                                <option value="fighting" <?= isset($_POST["type"]) && $_POST["type"] == "fighting" ? "selected" : "" ?>>fighting</option>
                                <option value="fire" <?= isset($_POST["type"]) && $_POST["type"] == "fire" ? "selected" : "" ?>>fire</option>
                                <option value="flying" <?= isset($_POST["type"]) && $_POST["type"] == "flying" ? "selected" : "" ?>>flying</option>
                                <option value="ghost" <?= isset($_POST["type"]) && $_POST["type"] == "ghost" ? "selected" : "" ?>>ghost</option>
                                <option value="grass" <?= isset($_POST["type"]) && $_POST["type"] == "grass" ? "selected" : "" ?>>grass</option>
                                <option value="ground" <?= isset($_POST["type"]) && $_POST["type"] == "ground" ? "selected" : "" ?>>ground</option>
                                <option value="ice" <?= isset($_POST["type"]) && $_POST["type"] == "ice" ? "selected" : "" ?>>ice</option>
                                <option value="normal" <?= isset($_POST["type"]) && $_POST["type"] == "normal" ? "selected" : "" ?>>normal</option>
                                <option value="poison" <?= isset($_POST["type"]) && $_POST["type"] == "poison" ? "selected" : "" ?>>poison</option>
                                <option value="psychic" <?= isset($_POST["type"]) && $_POST["type"] == "psychic" ? "selected" : "" ?>>psychic</option>
                                <option value="rock" <?= isset($_POST["type"]) && $_POST["type"] == "rock" ? "selected" : "" ?>>rock</option>
                                <option value="steel" <?= isset($_POST["type"]) && $_POST["type"] == "steel" ? "selected" : "" ?>>steel</option>
                                <option value="water" <?= isset($_POST["type"]) && $_POST["type"] == "water" ? "selected" : "" ?>>water</option>
                            </select>
                        </span>
                        <span class="card-hp">
                            <span class="icon">❤</span> <input type="number" name="hp" id="hp" value="<?= isset($_POST["hp"]) && !empty($_POST["hp"]) ? $_POST["hp"] : "" ?>" style="width: 40px" placeholder="HP..">
                        </span>
                        <span class="card-attack">
                            <span class="icon">⚔</span> <input type="number" name="attack" id="attack" value="<?= isset($_POST["attack"]) && !empty($_POST["attack"]) ? $_POST["attack"] : "" ?>" style="width: 40px" placeholder="ATK..">
                        </span>
                        <span class="card-defense">
                            <span class="icon">🛡</span> <input type="number" name="defense" id="defense" value="<?= isset($_POST["defense"]) && !empty($_POST["defense"]) ? $_POST["defense"] : "" ?>" style="width: 40px" placeholder="DEF..">
                        </span>
                    </div>
                    <div style="display: flex; flex-direction: column;max-width: 400px;justify-content: center;align-items: center;margin-top: 10px; margin-bottom: 10px;gap: 10px;">
                        <span>
                            📷 <input type="file" name="image" id="image" value="<?= isset($_POST["image"]) && !empty($_FILES["image"]["tmp_name"]) ? $_FILES["image"]["tmp_name"] : "" ?>" accept="image/png, image/jpeg">
                        </span>
                        <span>
                            💰 <input type="number" name="price" id="price" value="<?= isset($_POST["price"]) && !empty($_POST["price"]) ? $_POST["price"] : "" ?>" style="width: 50px" placeholder="Ár..">
                        </span>
                        
                    </div>
                    <div class="description">
                        <textarea style="width: 100%;height: 100px;" name="desc"><?= isset($_POST["desc"]) && !empty($_POST["desc"]) ? $_POST["desc"] : "Leírás..." ?></textarea>
                    </div>
                    <div style="display: flex; justify-content: center;">
                        <button class="createBtn" name="addCard">Létrehozás</button>
                    </div>
                    <?php 
                    if(count($errors) > 0): ?>
                        <div class="errors">
                            A következő hibák fordultak elő:
                            <ul>
                                <?php
                                foreach($errors as $error):
                                ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </form>
                
            </div>
        </div>
    </div>
    <footer <?= strpos($_SERVER["REQUEST_URI"], 'admin') !== false ? "style='background-color: #eb4034'" : "" ?>>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
    <script src="/js/jquery-3.7.1.min.js"></script>
    <script>
        $("#type").on("change", (event) => {
            const target = event.target;
            $("div.cycle").css("background", "var(--poke-clr-" + target.value + ")")
        });

        $("#image").on("change", () => {
            let fileInput = document.getElementById("image");
            let file = fileInput.files[0];
            //console.log(file);
            $("#prevImg").attr("src", URL.createObjectURL(file));
        })
    </script>
</body>
</html>