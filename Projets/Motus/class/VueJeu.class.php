<?php
// Fichier : class/VueJeu.class.php

class VueJeu
{
    private $longueur;
    private $messageErreur;
    private $messageVictoire;
    private $messageDefaite;
    private $motSecret;
    private $indice;
    private $tempsRestantCooldown;
    private $tempsRestantRevelation;

    public function __construct($donnees = [])
    {
        $this->longueur               = $donnees['longueur'] ?? 6;
        $this->messageErreur          = $donnees['messageErreur'] ?? '';
        $this->messageVictoire        = $donnees['messageVictoire'] ?? '';
        $this->messageDefaite         = $donnees['messageDefaite'] ?? '';
        $this->motSecret              = $donnees['motSecret'] ?? '';
        $this->indice                 = $donnees['indice'] ?? '';
        $this->tempsRestantCooldown   = $donnees['tempsRestantCooldown'] ?? 0;
        $this->tempsRestantRevelation = $donnees['tempsRestantRevelation'] ?? 0;
    }

    public function __toString()
    {
        ob_start();
        ?>
        <div class="jeu">
            <h1>Motus Star Wars</h1>
            
            <?php if ($this->messageErreur): ?><p class="erreur"><?php echo $this->messageErreur; ?></p><?php endif; ?>
            <?php if ($this->messageVictoire): ?><p class="victoire"><?php echo $this->messageVictoire; ?></p><?php endif; ?>
            <?php if ($this->messageDefaite): ?><p class="defaite"><?php echo $this->messageDefaite; ?></p><?php endif; ?>

            <?php if ($_SESSION['demande_confirmation'] ?? false): ?>
                <div class="modal-overlay">
                    <div class="modal-confirmation">
                        <h2>⚠️ Confirmation</h2>
                        <p>Voulez-vous abandonner ?</p>
                        <form method="post" class="modal-actions">
                            <button type="submit" name="confirmer_oui" class="btn-oui">Oui</button>
                            <button type="submit" name="confirmer_non" class="btn-non">Non</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <div class="grille" style="--mot-length: <?php echo $this->longueur; ?>;">
                <?php for ($ligne = 0; $ligne < 6; $ligne++): ?>
                    <div class="ligne">
                        <?php for ($col = 0; $col < $this->longueur; $col++): ?>
                            <?php
                            $classe = 'vide'; $contenu = '';
                            if (isset($_SESSION['propositions'][$ligne])) {
                                $propo = $_SESSION['propositions'][$ligne];
                                $contenu = $propo['mot'][$col];
                                $classe = $propo['resultat'][$col];
                            } elseif ($ligne == ($_SESSION['tentative'] ?? 0) && $col == 0) {
                                $contenu = $this->motSecret[0] ?? ''; 
                                $classe = 'bien-place';
                            } elseif ($ligne == 0 && $col == 0) {
                                $contenu = $this->motSecret[0] ?? ''; 
                                $classe = 'bien-place';
                            }
                            ?>
                            <div class="case <?php echo $classe; ?>"><?php echo htmlspecialchars($contenu); ?></div>
                        <?php endfor; ?>
                    </div>
                <?php endfor; ?>
            </div>

            <?php if (!($_SESSION['partie_terminee'] ?? false) && !($_SESSION['demande_confirmation'] ?? false)): ?>
                <form method="post" class="saisie-form">
                    <div class="saisie-container">
                        <span class="premiere-lettre"><?php echo htmlspecialchars($this->motSecret[0] ?? ''); ?></span>
                        <input type="text" name="mot_saisi"
                            maxlength="<?php echo $this->longueur - 1; ?>"
                            placeholder="<?php echo str_repeat('_', $this->longueur - 1); ?>"
                            autocomplete="off" autofocus />
                        <button type="submit" name="valider" class="touche-valider">Valider</button>
                    </div>
                </form>

                <?php 
                // APPEL DU SOUS-COMPOSANT : On instancie la sous-vue du clavier
                $clavierVisuel = new VueClavier($_SESSION['clavier_statut'] ?? []);
                echo $clavierVisuel; 
                ?>
                
                <form method="post" class="indice-form">
                    <button type="submit" name="indice" class="btn-indice" <?php echo ($this->tempsRestantCooldown > 0) ? 'disabled' : ''; ?>>
                        <?php echo ($this->tempsRestantCooldown > 0) ? "Indice ({$this->tempsRestantCooldown} s)" : "Indice disponible !"; ?>
                    </button>
                </form>
                
                <?php if ($_SESSION['indice_visible'] ?? false): ?>
                    <p class="indice">Indice : <?php echo htmlspecialchars($this->indice); ?></p>
                <?php endif; ?>
                
                <form method="post" class="revelation-form">
                    <button type="submit" name="demander_revelation" class="btn-revelation" <?php echo ($this->tempsRestantRevelation > 0) ? 'disabled' : ''; ?>>
                        <?php echo ($this->tempsRestantRevelation > 0) ? "Révélation ({$this->tempsRestantRevelation} s)" : "⚠️ Révéler le mot"; ?>
                    </button>
                </form>
            <?php else: ?>
                <form method="post" style="margin-top: 30px;">
                    <button type="submit" name="nouvelle_partie" class="btn-jouer">Recommencer une partie</button>
                </form>
            <?php endif; ?>
        </div>

        <!-- INCLUSION DU FICHIER JAVASCRIPT GÉRANT LE TIMER EN TEMPS RÉEL -->
        <script src="/js/jeu.js" defer></script>
        <?php
        return ob_get_clean();
    }
}