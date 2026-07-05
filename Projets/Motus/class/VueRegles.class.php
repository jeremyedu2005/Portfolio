<?php
class VueRegles
{
    public function __toString()
    {
        ob_start();
        ?>
        <div class="regles">
            <h1>Règles du Motus</h1>

            <p>Le jeu repose sur la recherche de mots d’un nombre fixé de lettres.</p>
            <p>La grille s'affichera avec la première lettre.</p>
            <p>Le mot apparaît alors sur une grille :</p>
            <ul>
                <li>les lettres présentes et bien placées sont coloriées en rouge,</li>
                <li>les lettres présentes mais mal placées sont cerclées de jaune.</li>
            </ul>

            <p>Pour une lettre, on ne peut avoir au maximum que le nombre d’occurrences de cette lettre dans le mot de coloriées  
            (soit en jaune, soit en rouge si certaines sont bien placées).</p>

            <p class="demo-link">
                <strong>Envie de voir comment ça fonctionne?</strong><br />
                <a href="index.php?motus=demonstration" class="btn-demo">Voir la démonstration</a>
            </p>

            <p class="retour">
                <a href="index.php?motus=jeu">Retour au jeu</a>
            </p>

            <p style="text-align:center; margin-top:20px;">
                <a href="index.php?motus=accueil" class="btn-secondaire" style="font-size:1.3em;">
                     Retour à l'accueil
                </a>
            </p>
        </div>
        <?php
        return ob_get_clean();
    }
}