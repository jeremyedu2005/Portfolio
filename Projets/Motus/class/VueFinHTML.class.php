<?php
// Fichier : class/VueFinHTML.class.php

class VueFinHTML
{
    public function __toString()
    {
        ob_start();  ob_start(); // fonction en PHP qui est utilisée pour activer la mise en mémoire tampon de sortie
        ?>
        <footer>
            <div class="footer">
                <p>&copy; 2026-RAODSON Miaro Jérémy-Tous droits réservés-<a href="index.php?motus=mentions">-Mentions légales</p></a>
                
            </div>
        </footer>
        </body>
        </html>
        <?php
        return ob_get_clean();  //Obtiens le contenu du tampon de sortie actif et désactive ce dernier
    }
}

