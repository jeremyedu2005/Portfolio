<?php
// index.php - Point d’entrée principal du projet Motus Star Wars

// Démarrer la session
session_start();

// Activer les erreurs en développement
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);

// Charger l’autoload
define("CHARGE_AUTOLOAD", true);
require_once("inc/poo.inc.php");


/* ==========================================
   CAS SPÉCIAL : GD DEMO CORRIGÉ
========================================== */
if (isset($_GET['motus']) && $_GET['motus'] === 'gd_demo') {
    
    // On fixe un mot emblématique pour que la démo soit toujours parfaite et parlante
    $motSecret = "VADER"; 
    $longueur = strlen($motSecret);
    
    // Tableau de fausses propositions réalistes pour la simulation
    $simulationMots = [
        0 => "VAKAR", // Ligne 1
        1 => "VODAS", // Ligne 2
        2 => "VALER", // Ligne 3
        3 => "VAMPI", // Ligne 4
        4 => "VADRE", // Ligne 5
        5 => "VADER"  // Ligne 6 (Victoire !)
    ];
    
    $lignes = 6;
    $tailleCase = 80;
    $largeurImage = $longueur * $tailleCase + 60;
    $hauteurImage = $lignes * $tailleCase + 60;
    
    $image = imagecreate($largeurImage, $hauteurImage);
    
    $cNoir  = imagecolorallocate($image, 0, 0, 0);
    $cBleu  = imagecolorallocate($image, 10, 10, 15); // Fond sombre galactique
    $cGris  = imagecolorallocate($image, 34, 34, 34);  // Case vide (#222)
    $cRouge = imagecolorallocate($image, 255, 64, 64); // Bien placé (#ff4040)
    $cjaune = imagecolorallocate($image, 255, 255, 153); // Mal placé (#ffff99)
    $cBlanc = imagecolorallocate($image, 255, 255, 255);
    
    imagefill($image, 0, 0, $cBleu);
    
    $etape = isset($_GET['etape']) ? (int)$_GET['etape'] : 1;
    if ($etape < 1) $etape = 1;
    if ($etape > 6) $etape = 6;
    
    for ($l = 0; $l < $lignes; $l++) {
        // Récupérer le mot simulé pour cette ligne
        $motSimule = $simulationMots[$l];
        
        for ($c = 0; $c < $longueur; $c++) {
            $lettre = " ";
            $couleurCase = $cGris;
            
            // Si la ligne est inférieure à l'étape actuelle, on l'affiche avec ses couleurs calculées
            if ($l < $etape) {
                $lettre = $motSimule[$c];
                
                // Logique des couleurs par rapport au mot secret "VADER"
                if ($motSimule[$c] === $motSecret[$c]) {
                    $couleurCase = $cRouge; // Bien placé
                } elseif (strpos($motSecret, $motSimule[$c]) !== false) {
                    $couleurCase = $cjaune; // Mal placé
                } else {
                    $couleurCase = $cGris;  // Absent
                }
            } 
            // Si c'est la ligne en cours de saisie, on montre au moins la première lettre en indice (Règle du Motus)
            elseif ($l == $etape - 1 && $c == 0) {
                $lettre = $motSecret[0];
                $couleurCase = $cRouge;
            }
            
            $x1 = $c * $tailleCase + 30;
            $y1 = $l * $tailleCase + 30;
            $x2 = $x1 + $tailleCase - 10;
            $y2 = $y1 + $tailleCase - 10;
            
            // Dessiner la case
            imagefilledrectangle($image, $x1, $y1, $x2, $y2, $couleurCase);
            
            // Dessiner la lettre centrée
            if ($lettre !== " ") {
                imagestring($image, 5, $x1 + 30, $y1 + 30, $lettre, ($couleurCase === $cjaune) ? $cNoir : $cBlanc);
            }
        }
    }
    
    header("Content-Type: image/png");
    imagepng($image);
    exit;
}


/* ==========================================
   TRAITEMENT DE LA LOGIQUE MÉTIER DU JEU (CONTRÔLEUR)
========================================== */
$donneesJeu = [
    'messageErreur' => '',
    'messageVictoire' => '',
    'messageDefaite' => '',
    'longueur' => 6,
    'motSecret' => '',
    'indice' => '',
    'tempsRestantCooldown' => 0,
    'tempsRestantRevelation' => 0
];

if (isset($_GET['motus']) && $_GET['motus'] === 'jeu') {
    $dico = new VueDictionnaire_traitement();

    if (!isset($_SESSION['mot_secret']) || isset($_POST['nouvelle_partie'])) {
        $motAleatoire = $dico->getMotAleatoire();
        $_SESSION['mot_secret'] = strtoupper($motAleatoire['mot']);
        $_SESSION['categorie'] = $motAleatoire['categorie'];
        $_SESSION['indice'] = $motAleatoire['indice'];
        $_SESSION['tentative'] = 0;
        $_SESSION['propositions'] = [];
        $_SESSION['indice_time'] = time();
        $_SESSION['indice_visible'] = false;
        $_SESSION['revelation_time'] = time();
        $_SESSION['partie_terminee'] = false;
        $_SESSION['demande_confirmation'] = false;

        $_SESSION['clavier_statut'] = [];
        foreach (range('A', 'Z') as $lettreAlphabet) {
            $_SESSION['clavier_statut'][$lettreAlphabet] = 'neutre';
        }
    }

    $motSecret = $_SESSION['mot_secret'];
    $longueur = strlen($motSecret);
    $donneesJeu['longueur'] = $longueur;
    $donneesJeu['motSecret'] = $motSecret;
    $donneesJeu['indice'] = $_SESSION['indice'];

    $_SESSION['clavier_statut'][$motSecret[0]] = 'bien-place';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        if (isset($_POST['valider']) && !$_SESSION['partie_terminee']) {
            $motSaisi = isset($_POST['mot_saisi']) ? trim($_POST['mot_saisi']) : '';
            $premiereLettre = $motSecret[0];
            $motComplet = strtoupper($premiereLettre . $motSaisi);

            if (strlen($motComplet) !== $longueur) {
                $donneesJeu['messageErreur'] = "Le mot doit faire exactement {$longueur} lettres.";
            } else {
                $motsPossibles = $dico->getMotsParLettre($premiereLettre);
                $existe = false;
                foreach ($motsPossibles as $m) {
                    if (strtoupper($m['mot']) === $motComplet) {
                        $existe = true;
                        break;
                    }
                }

                if (!$existe) {
                    $donneesJeu['messageErreur'] = "Le mot [" . htmlspecialchars($motComplet) . "] n'existe pas dans la base galactique.";
                } else {
                    $resultat = array_fill(0, $longueur, 'absent');
                    $copieMotSecret = $motSecret;

                    for ($i = 0; $i < $longueur; $i++) {
                        if ($motComplet[$i] === $motSecret[$i]) {
                            $resultat[$i] = 'bien-place';
                            $copieMotSecret[$i] = '_';
                            $_SESSION['clavier_statut'][$motComplet[$i]] = 'bien-place';
                        }
                    }

                    for ($i = 0; $i < $longueur; $i++) {
                        if ($resultat[$i] !== 'bien-place') {
                            $pos = strpos($copieMotSecret, $motComplet[$i]);
                            if ($pos !== false) {
                                $resultat[$i] = 'mal-place';
                                $copieMotSecret[$pos] = '_';
                                if ($_SESSION['clavier_statut'][$motComplet[$i]] !== 'bien-place') {
                                    $_SESSION['clavier_statut'][$motComplet[$i]] = 'mal-place';
                                }
                            } else {
                                $resultat[$i] = 'absent';
                                if ($_SESSION['clavier_statut'][$motComplet[$i]] === 'neutre') {
                                    $_SESSION['clavier_statut'][$motComplet[$i]] = 'absent';
                                }
                            }
                        }
                    }

                    $_SESSION['propositions'][$_SESSION['tentative']] = [
                        'mot' => $motComplet,
                        'resultat' => $resultat
                    ];

                    $_SESSION['tentative']++;

                    if ($motComplet === $motSecret) {
                        $_SESSION['partie_terminee'] = true;
                        $donneesJeu['messageVictoire'] = "Bravo ! Vous avez trouvé le mot secret : " . $motSecret;
                    } elseif ($_SESSION['tentative'] >= 6) {
                        $_SESSION['partie_terminee'] = true;
                        $donneesJeu['messageDefaite'] = "Dommage, vous avez épuisé vos 6 tentatives. Le mot était : " . $motSecret;
                    }
                }
            }
        }

        if (isset($_POST['indice']) && !$_SESSION['partie_terminee']) {
            if ((time() - $_SESSION['indice_time']) >= 10) {
                $_SESSION['indice_visible'] = true;
            }
        }

        if (isset($_POST['demander_revelation']) && !$_SESSION['partie_terminee']) {
            if ((time() - $_SESSION['revelation_time']) >= 15) {
                $_SESSION['demande_confirmation'] = true;
            }
        }

        if (isset($_POST['confirmer_oui'])) {
            $_SESSION['demande_confirmation'] = false;
            $_SESSION['partie_terminee'] = true;
            $donneesJeu['messageDefaite'] = "Vous avez abandonné. Le mot secret était : " . $motSecret;
        }

        if (isset($_POST['confirmer_non'])) {
            $_SESSION['demande_confirmation'] = false;
        }
    }

    $donneesJeu['tempsRestantCooldown'] = max(0, 10 - (time() - ($_SESSION['indice_time'] ?? time())));
    $donneesJeu['tempsRestantRevelation'] = max(0, 15 - (time() - ($_SESSION['revelation_time'] ?? time())));
}


/* ==========================================
   DÉBUT HTML COMMUN
========================================== */
$debutHTML = new VueDebutHTML();
echo $debutHTML;

// Router principal
if (isset($_GET['motus']) && !empty($_GET['motus'])) {
    $action = $_GET['motus'];

    switch ($action) {
        case "accueil":
            $vue = new VueAccueil();
            echo $vue;
            break;
        
        case "reglement":
            $vue = new VueRegles();
            echo $vue;
            break;

        case "jeu":
            $vue = new VueJeu($donneesJeu);
            echo $vue;
            break;

        case "demonstration":
            $vue = new VueDemonstration();
            echo $vue;
            break;
        
        case "mentions":
            $vue = new VueMentionlegales();
            echo $vue;
            break;

        default:
            echo "<h2>Désolé, je ne comprends pas l'ordre [" . htmlspecialchars($action) . "]</h2>";
            break;
    }
} else {
    $vue = new VueAccueil();
    echo $vue;
}

/* ==========================================
   FIN HTML COMMUN
========================================== */
$finHTML = new VueFinHTML();
echo $finHTML;