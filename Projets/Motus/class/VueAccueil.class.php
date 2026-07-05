<?php
// Fichier : class/VueAccueil.class.php
// Page d'accueil du projet Motus Star Wars

class VueAccueil
{
    public function __toString()
    {
        ob_start(); // Activation de la mise en tampon pour capturer le HTML
        ?>
        <div class="accueil">
            <h1>Bienvenue dans Motus Star Wars</h1>

            <p>Devinez des mots emblématiques de la galaxie en 6 tentatives !</p>
            <p>Personnages, planètes, vaisseaux, créatures... que la Force soit avec vous !</p>

            <!-- Bouton principal : Jouer -->
            <p style="text-align:center; margin:60px 0;">
                <a href="index.php?motus=jeu" class="btn-jouer">
                    Jouer maintenant !
                </a>
            </p>

            <!-- Liens secondaires -->
            <p style="text-align:center; font-size:1.1em; margin:30px 0;">
                <a href="index.php?motus=reglement" class="btn-secondaire">Lire les règles</a> | 
                <a href="index.php?motus=demonstration" class="btn-secondaire">Voir une démonstration</a>
            </p>
        </div>
         <?php
        return ob_get_clean();//  //Obtiens le contenu du tampon de sortie actif et désactive ce dernier
    }
}