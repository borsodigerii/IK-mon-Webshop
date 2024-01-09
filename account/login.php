<?php 
session_start();
if(isset($_SESSION["user"])){
    header("Location: /");
    die();
}
define("SITE", "Bejelentkezés");
$errors = array();
if($_SERVER["REQUEST_METHOD"] == "POST"){
    require_once($_SERVER["DOCUMENT_ROOT"]."/api/API.php");
    // login attempt
    
    if(!isset($_POST["name"]) || empty($_POST["name"])){
        array_push($errors, "A név megadása kötelező!");
    }
    if(!isset($_POST["pass"]) || empty($_POST["pass"])){
        array_push($errors, "A jelszó megadása kötelező!");
    }
    if(count($errors) == 0){
        // nem volt hiba a megadassal
        $user = API::checkUser($_POST["name"], $_POST["pass"]);
        if($user != null){
            // user letezik
            $_SESSION["user"]["name"] = $user->name;
            $_SESSION["user"]["id"] = $user->id;
            $_SESSION["user"]["email"] = $user->email;
            $_SESSION["user"]["isAdmin"] = $user->isAdmin;
            header("Location: /?successfull-login");
            die();
        }else{
            array_push($errors, "Nincs felhasználó ilyen név-jelszó párossal!");
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
                <label for="name">Jelszó</label>
                <input type="password" name="pass" id="pass" value="<?= isset($_POST["pass"]) ? $_POST["pass"] : "" ?>">
                <button>Belépés</button>
            </form>
            <a href="signup.php">Nincs még fiókod? Regisztrálj!</a>
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