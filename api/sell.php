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

        if($card->owner != $userId) {
            echo API::getRes(400, "Could not complete the sell: the card does not belong to you!");
        }else{
            API::setBalanceForUser($userId, $balance + ($card->price*0.9));
            API::setOwnerForCard(0, $cardId);
            echo API::getRes(200, "User selled card successfully", array("balance" => $balance + ($card->price*0.9), "cardCount" => API::getCardCountForUser($userId)));
        }
        die();
        
    }else{
        echo API::getRes(403, "Unauthorized, please log in!");
        die();
    }
}   



?>