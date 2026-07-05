<?php
// Fichier : class/VueDebutHTML.class.php

class VueDebutHTML
{
    public function __toString()
    {
        ob_start();
        ?>
<!DOCTYPE html>
<html lang="fr" xml:lang="fr" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Motus Star Wars</title>
    <link rel="stylesheet" href="css/motus.css" />
    <script src="js/menu.js" defer></script>
</head>
<body>

    <nav class="navbar">
        <div class="navbar-container">
            <a href="index.php?motus=accueil" class="navbar-logo">Motus Star Wars</a>
            <ul class="navbar-menu" id="navbar-menu">
                <li><a href="index.php?motus=accueil" class="nav-link">Accueil</a></li>
                <li><a href="index.php?motus=reglement" class="nav-link">Règles</a></li>
                <li><a href="index.php?motus=jeu" class="nav-link">Jeu</a></li>
            </ul>
            <div class="hamburger" id="hamburger-btn">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>
    <?php
        return ob_get_clean();
    }
}