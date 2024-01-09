<?php
session_start();
require_once("API.php");

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nonce"]) && $_POST["nonce"] == $_SESSION["nonce"] && isset($_POST["cardId"])) {
    // post and nonce
    if(isset($_SESSION["user"])){
        // is logged in
        $userId = intval($_SESSION["user"]["id"]);
        $cardId = intval($_POST["cardId"]);
        $balance = API::getBalanceByUserId($userId);
        $card = API::getCardById($cardId);
        if($card == null){
            echo API::getRes(401, "A card with the given id does not exists.");
            die();
        }

        if($card->owner != 0){
            echo API::getRes(405, "The card is already purchased by someone!", array("owner" => API::getUserById($card->owner)->name));
            die();
        }
        if(API::getCardCountForUser($userId) + 1 > 5){
            echo API::getRes(402, "You reached the maximum number of cards. Sell some first.");
            die();
        }

        if($balance - $card->price < 0) {
            echo API::getRes(400, "Could not complete the purchase: no sufficient funds");
        }else{
            API::setBalanceForUser($userId, $balance - $card->price);
            API::setOwnerForCard($userId, $cardId);
            echo API::getRes(200, "User purchased card successfully", array("balance" => $balance - $card->price));
        }
        die();
        
    }else{
        echo API::getRes(403, "Unauthorized, please log in!");
        die();
    }
}   



?>