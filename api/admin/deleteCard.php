<?php 
session_start();
require_once($_SERVER["DOCUMENT_ROOT"] . "/api/API.php");
if(isset($_SESSION["user"]) && isset($_SESSION["user"]["isAdmin"]) && $_SESSION["user"]["isAdmin"] == true){
    // is admin
    if(isset($_GET["id"]) || isset($globalDeleteId)){
        $id = isset($globalDeleteId) ? $globalDeleteId : $_GET["id"];
        $card = API::getCardById($id);

        if(!filter_var($card->image, FILTER_VALIDATE_URL)){
            // feltoltott file, nem url
            $target_dir = $_SERVER["DOCUMENT_ROOT"].DIRECTORY_SEPARATOR. "images" .DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR;
            unlink(realpath($target_dir . $card->image));
        }
        API::removeCard($id);
        header("Location: /admin/?successfull-del");
        die();
    }else{
        header("Location /admin");
        die();
    }
}else{
    header("Location: /");
    die();
}



?>