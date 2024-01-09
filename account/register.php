<?php 
session_start();
if(isset($_SESSION["user"])){
    header("Location: /");
    die();
}
define("SITE", "Regisztráció");
$errors = array();
if($_SERVER["REQUEST_METHOD"] == "POST"){
    require_once($_SERVER["DOCUMENT_ROOT"]."/api/API.php");
    // login attempt
    
    if(!isset($_POST["name"]) || empty($_POST["name"])){
        array_push($errors, "A név megadása kötelező!");
    }
    if(!isset($_POST["email"]) || empty($_POST["email"]) || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
        array_push($errors, "Az email cím megadása kötelező, a megfelelő formátumban!");
    }
    if(!isset($_POST["pass"]) || empty($_POST["pass"])){
        array_push($errors, "A jelszó megadása kötelező!");
    }
    if(!isset($_POST["pass2"]) || empty($_POST["pass2"])){
        array_push($errors, "A jelszó megadása kétszer kötelező!");
    }
    if($_POST["pass"] != $_POST["pass2"]){
        array_push($errors, "A két jelszó nem egyezik.");
    }
    if(count($errors) == 0){
        // nem volt hiba a megadassal
        $user = API::checkUser($_POST["name"]);
        if($user == null){
            // user letezik
            $user = new User(API::getNextUserId(), $_POST["name"], $_POST["email"], $_POST["pass"], 3000, false);
            API::addUser($user);
            $_SESSION["user"]["name"] = $user->name;
            $_SESSION["user"]["id"] = $user->id;
            $_SESSION["user"]["email"] = $user->email;
            $_SESSION["user"]["isAdmin"] = $user->isAdmin;
            header("Location: /?successfull-reg");
            die();
        }else{
            array_push($errors, "Már létezik felhasználó ilyen felh.névvel, kérlek válassz másikat!");
        }
    }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Bejelentkezés</title>
    <link rel="stylesheet" href="/styles/main.css">
    <link rel="stylesheet" href="/styles/cards.css">
    <link rel="stylesheet" href="/styles/custom.css">
</head>
<body>
    <?php include($_SERVER["DOCUMENT_ROOT"]."/menu.php"); ?>
    <div id="content" class="centered">
        <div class="login">
            <form method="post">
                <label for="name">Felhasználónév</label>
                <input type="text" name="name" id="name" value="<?= isset($_POST["name"]) ? $_POST["name"] : "" ?>">
                <label for="email">E-mail cím</label>
                <input type="email" name="email" id="email" value="<?= isset($_POST["email"]) ? $_POST["email"] : "" ?>">
                <label for="name">Jelszó</label>
                <input type="password" name="pass" id="pass" value="<?= isset($_POST["pass"]) ? $_POST["pass"] : "" ?>">
                <label for="name">Jelszó mégegyszer</label>
                <input type="password" name="pass2" id="pass2" value="<?= isset($_POST["pass2"]) ? $_POST["pass2"] : "" ?>">
                <button>Regisztráció</button>
            </form>
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
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>
</html>