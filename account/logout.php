<?php 
session_start();
if(isset($_SESSION["user"])){
    unset($_SESSION["user"]);
    header("Location: /?successfull-logout");
}else{
    header("Location: /");
}


?>