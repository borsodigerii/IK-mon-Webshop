<?php 
require_once("api/API.php");

?>
<header <?= strpos($_SERVER["REQUEST_URI"], 'admin') !== false ? "style='background-color: #eb4034'" : "" ?>>
    <h1><a href="/">IKémon</a> > <?= SITE ?></h1>
    <div class="account">
        <?php
        if(isset($_SESSION["user"]["name"])):
        ?>
            👤 <?= $_SESSION["user"]["name"] ?><br>
            <span id="balance">💰 <?= API::getUserById($_SESSION["user"]["id"])->balance ?></span>
            <div class="menus">
                <?php if($_SESSION["user"]["isAdmin"] == true): ?>
                    <a href="/admin">Vezérlőpult</a>
                <?php else: ?>
                    <a href="/account">Adataim</a>
                <?php endif; ?>
                <a href="/account/logout">Kijelentkezés</a>
            </div>
        <?php else: ?>
            Fiók
            <div class="menus">
                <a href="/account/login">Belépés</a>
                <a href="/account/register">Regisztráció</a>
            </div>
        <?php endif; ?>
    </div>
</header>
<div class="alerts">
    
</div>