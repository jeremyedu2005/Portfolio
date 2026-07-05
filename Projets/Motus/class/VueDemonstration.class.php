<?php
// Fichier : class/VueDemonstration.class.php
class VueDemonstration
{
    public function __toString()
    {
        ob_start();
        
        // **NOUVEAU MOT seulement si on demande explicitement un reset**
        if (isset($_GET['nouveau'])) {
            unset($_SESSION['mot_demo']);
        }
        
        $etape = isset($_GET['etape']) ? (int)$_GET['etape'] : 1; 
        if ($etape > 6) $etape = 6; // incrémente l'url sous cette forme index.php?demonstration&etape=1 puis  index.php?demonstration&etape=2 puis à la suite  jussqu'a index.php?demonstration&etape=6
        
        // Étape suivante
        $next = $etape + 1;  
        if ($next > 6) $next = 6;
        
        ?>
        <div class="demonstration">
            <h1>Démonstration du Motus Star Wars</h1>
            <p>Cette image montre une simulation automatique d'une partie :</p>
            <ul>
                <li>Le mot secret est choisi aléatoirement dans le dictionnaire.</li>
                <li>La grille est toujours de <strong>6 lignes</strong> pour les tentatives.</li>
                <li>Chaque tentative descend d'une ligne à chaque étape.</li>
                <li>Les lettres bien placées deviennent <span class="rouge">rouges</span>.</li>
                <li>Les lettres présentes mais mal placées deviennent <span class="jaune">jaunes</span>.</li>
            </ul>
            
            <?php if ($etape < 6): ?>
                <meta http-equiv="refresh" content="1.5;url=index.php?motus=demonstration&etape=<?php echo $next; ?>">
            <?php endif; ?>
            
            <div class="demo-image-container">
                <img src="index.php?motus=gd_demo&etape=<?php echo $etape; ?>" 
                     alt="Démonstration Motus en image" />
            </div>
            
            <?php if ($etape < 6): ?>
                <p class="demo-progress">
                    La démonstration continue... (étape <?php echo $etape; ?>/6)
                </p>
            <?php else: ?>
                <p class="demo-complete">
                     Mot trouvé ! La démonstration est terminée à vos souris.
                </p>
                <p style="text-align:center;">
                    <a href="index.php?motus=demonstration&nouveau=1" class="btn-nouveau">
                         Nouvelle démonstration
                    </a>
                </p>
            <?php endif; ?>
            
            <div class="demo-actions">
                <a href="index.php?motus=reglement" class="btn-retour">Retour aux règles</a>
                <a href="index.php?motus=jeu" class="btn-jouer">Jouer maintenant !</a>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}