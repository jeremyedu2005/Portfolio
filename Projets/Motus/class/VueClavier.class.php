<?php
// Fichier : class/VueClavier.class.php

class VueClavier
{
    private $clavierStatut;

    // Le constructeur reçoit le tableau des statuts depuis la session
    public function __construct($clavierStatut = [])
    {
        $this->clavierStatut = $clavierStatut;
    }

    public function __toString()
    {
        ob_start();
        ?>
        <div class="clavier" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 6px; max-width: 650px; margin: 30px auto;">
            <?php 
            $azerty = ['A','Z','E','R','T','Y','U','I','O','P','Q','S','D','F','G','H','J','K','L','M','W','X','C','V','B','N'];
            foreach ($azerty as $lettreClavier): 
                $statut = $this->clavierStatut[$lettreClavier] ?? 'neutre';
                
                $classeBouton = 'case ';
                if ($statut === 'bien-place') $classeBouton .= 'bien-place';
                elseif ($statut === 'mal-place') $classeBouton .= 'mal-place';
                elseif ($statut === 'absent') $classeBouton .= 'absent';
                else $classeBouton .= 'neutre';
                ?>
                <div class="<?php echo $classeBouton; ?>" style="width: 45px; height: 45px; font-size: 1.2em; border-radius: 4px; cursor: default; user-select: none;">
                    <?php echo $lettreClavier; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}