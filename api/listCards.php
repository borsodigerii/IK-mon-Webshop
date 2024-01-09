<?php

require_once("API.php");

//if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_POST["nonce"]) && $_POST["nonce"] == $_SESSION["nonce"]) {
    echo API::getRes(200, "Successfull retrieval", API::getCards());

//}



?>