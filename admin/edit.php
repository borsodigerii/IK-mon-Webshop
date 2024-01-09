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
if(isset($_POST["cardId"]) && !empty($_POST["cardId"])){
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
    if(isset($_POST["editImg"]) && $_POST["editImg"] == "on"){
        $editedImg = true;
        if(!isset($_FILES["image"]["name"]) || empty($_FILES["image"]["name"])){
            array_push($errors, "A kép megadása kötelező, mivel kipipáltad a 'Kép módosítása' jelölőnégyzetet!");
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
    }else{
        $editedImg = false;
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
        $cardImg = API::getCardById(intval($_POST["cardId"]))->image;
        if($editedImg){
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                if(!filter_var($cardImg, FILTER_VALIDATE_URL)){
                    unlink(realpath($target_dir . $cardImg));
                }
                
                $update = new Card($_POST["cardId"], $_POST["name"], $_POST["type"], $_POST["hp"], $_POST["defense"], $_POST["attack"], $_POST["price"], $_POST["desc"], $id . "." . $imageFileType, 0);
    
                API::editCard($_POST["cardId"], $update);
    
                header("Location: /admin/?successfull-edit");
                die();
            } else {
                array_push($errors, "A képet nem sikerült feltölteni egy ismeretlen hiba miatt.");
            }
        }else{
            
            $update = new Card($_POST["cardId"], $_POST["name"], $_POST["type"], $_POST["hp"], $_POST["defense"], $_POST["attack"], $_POST["price"], $_POST["desc"], $cardImg, 0);
    
            API::editCard($_POST["cardId"], $update);

            header("Location: /admin/?successfull-edit");
            die();
        }
        
    }
}



if((!isset($_GET["id"]) || $_GET["id"] === "") && !isset($globalEditId)){
    header("Location: /admin");
    die();
}



$id = isset($globalEditId) ? $globalEditId : $_GET["id"];
$card = API::getCardById(intval($id));
if($card->owner != 0){
    header("Location: /admin");
    die();
}
define("SITE", "Módosítás > " .$card->name);
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
    <?php include($_SERVER["DOCUMENT_ROOT"]."/menu.php"); ?>
    <div id="content">
        <a href="/admin" class="adminBtn">< Mégse</a>
        <a href="#" class="adminBtn reverse" id="deleteCard">Kártya törlése</a>
        <div class="details">
            <div class="image">
                <img src="<?= API::getCardImageUrl($card->id) ?>" alt="" id="prevImg">
                <div class="cycle" style="background: var(--poke-clr-<?= $card->type ?>);"></div>
            </div>
            <div class="text">
                <form method="post" enctype="multipart/form-data">
                    <input type="text" name="name" id="name" class="detailsH2" value="<?= isset($_POST["name"]) && !empty($_POST["name"]) ? $_POST["name"] : $card->name ?>">
                    <h3>Tulajdonos: <?= API::getUserById($card->owner)->name ?></h3>
                    <div class="card-attributes">
                        <span class="card-type">
                            <span class="icon">🏷</span> <select name="type" id="type">
                                <option value="bug" <?= $card->type == "bug" ? "selected" : "" ?>>bug</option>
                                <option value="dark" <?= $card->type == "dark" ? "selected" : "" ?>>dark</option>
                                <option value="dragon" <?= $card->type == "dragon" ? "selected" : "" ?>>dragon</option>
                                <option value="electric" <?= $card->type == "electric" ? "selected" : "" ?>>electric</option>
                                <option value="fairy" <?= $card->type == "fairy" ? "selected" : "" ?>>fairy</option>
                                <option value="fighting" <?= $card->type == "fighting" ? "selected" : "" ?>>fighting</option>
                                <option value="fire" <?= $card->type == "fire" ? "selected" : "" ?>>fire</option>
                                <option value="flying" <?= $card->type == "flying" ? "selected" : "" ?>>flying</option>
                                <option value="ghost" <?= $card->type == "ghost" ? "selected" : "" ?>>ghost</option>
                                <option value="grass" <?= $card->type == "grass" ? "selected" : "" ?>>grass</option>
                                <option value="ground" <?= $card->type == "ground" ? "selected" : "" ?>>ground</option>
                                <option value="ice" <?= $card->type == "ice" ? "selected" : "" ?>>ice</option>
                                <option value="normal" <?= $card->type == "normal" ? "selected" : "" ?>>normal</option>
                                <option value="poison" <?= $card->type == "poison" ? "selected" : "" ?>>poison</option>
                                <option value="psychic" <?= $card->type == "psychic" ? "selected" : "" ?>>psychic</option>
                                <option value="rock" <?= $card->type == "rock" ? "selected" : "" ?>>rock</option>
                                <option value="steel" <?= $card->type == "steel" ? "selected" : "" ?>>steel</option>
                                <option value="water" <?= $card->type == "water" ? "selected" : "" ?>>water</option>
                            </select>
                        </span>
                        <span class="card-hp">
                            <span class="icon">❤</span> <input type="number" name="hp" id="hp" value="<?= isset($_POST["hp"]) && !empty($_POST["hp"]) ? $_POST["hp"] : $card->hp ?>" style="width: 40px">
                        </span>
                        <span class="card-attack">
                            <span class="icon">⚔</span> <input type="number" name="attack" id="attack" value="<?= isset($_POST["attack"]) && !empty($_POST["attack"]) ? $_POST["attack"] : $card->attack ?>" style="width: 40px">
                        </span>
                        <span class="card-defense">
                            <span class="icon">🛡</span> <input type="number" name="defense" id="defense" value="<?= isset($_POST["defense"]) && !empty($_POST["defense"]) ? $_POST["defense"] : $card->defense ?>" style="width: 40px">
                        </span>
                    </div>
                    <div style="display: flex; flex-direction: column;max-width: 400px;justify-content: center;align-items: center;margin-top: 10px; margin-bottom: 10px;gap: 10px;">
                        <span style="text-align: center;">
                            Kép módosítása <input type="checkbox" name="editImg" id="editImg" style="cursor: pointer;" onchange="editImgChange(this)"><br>
                            <span style="border: 1px solid black;display: block;transform: scaleY(0);transition: transform .3s ease;transform-origin: top;max-height: 0" id="imgContainer">
                                📷 <input type="file" name="image" id="image" onchange="imgChanged(this)">
                            </span>
                            
                        </span>
                        <span>
                            💰 <input type="number" name="price" id="price" value="<?= isset($_POST["price"]) && !empty($_POST["price"]) ? $_POST["price"] : $card->price ?>" style="width: 50px">
                        </span>
                    </div>
                    <div class="description">
                        <textarea style="width: 100%;height: 100px;" name="desc"><?= isset($_POST["desc"]) && !empty($_POST["desc"]) ? $_POST["desc"] : $card->description ?></textarea>
                    </div>
                    <div style="display: flex; justify-content: center;">
                        <button class="editBtn">Módosítás</button>
                    </div>
                    <input type="hidden" name="cardId" value="<?= $card->id ?>">
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
    <script>
        function editImgChange(checkbox){
            if(checkbox.checked){
                document.getElementById("imgContainer").style.maxHeight = "unset";
                document.getElementById("imgContainer").style.padding = "5px";
                document.getElementById("imgContainer").style.transform = "scaleY(100%)";
                
                if(document.getElementById("image").files.length > 0){
                    let newImg = document.getElementById("image").files[0];
                    document.getElementById("prevImg").src = URL.createObjectURL(newImg);
                }
            }else{
                document.getElementById("imgContainer").style.transform = "scaleY(0)";
                document.getElementById("prevImg").src = "<?= API::getCardImageUrl($card->id) ?>";
                setTimeout(() => {
                    document.getElementById("imgContainer").style.maxHeight = "0";
                    document.getElementById("imgContainer").style.padding = "0px";
                }, 300);
            }
        }

        function imgChanged(input){
            let file = input.files[0];
            console.log(file);
            document.getElementById("prevImg").src = URL.createObjectURL(file);
        }
        document.getElementById("type").addEventListener("change", (event) => {
            const target = event.target;
            document.querySelector("div.cycle").style.background = "var(--poke-clr-" + target.value + ")";
        });

        document.querySelector("a#deleteCard").addEventListener("click", (event) => {
            event.preventDefault();
            const res = confirm("Biztosan ki akarod törölni a kártyát?");
            if(res){
                // igen
                window.location.href = "/admin/deletecard/" + <?= $id ?>;
            }
        })
    </script>
</body>
</html>