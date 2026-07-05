<?php
// Fichier : class/VueDictionnaire_traitement.class.php
// Cette classe charge le fichier JSON et fournit des méthodes pour accéder aux mots

class VueDictionnaire_traitement
{
    private $data;  // Stocke tout le contenu du JSON une fois chargé

    // Constructeur : exécuté automatiquement à la création de l’objet
    public function __construct()
    {
        // Chemin fixe vers le fichier JSON
        $chemin = 'BD/bibliotheque.json';

        // Vérifie si le fichier existe vraiment
        if (!file_exists($chemin)) {
            die("Erreur : le fichier dictionnaire est introuvable : " . $chemin);
        }

        // Lit le contenu brut du fichier JSON
        $json = file_get_contents($chemin);

        // Décode le JSON en tableau PHP associatif
        $this->data = json_decode($json, true);

        // Vérifie s’il y a eu une erreur de décodage
        if (json_last_error() !== JSON_ERROR_NONE) {
            die("Erreur de décodage JSON : " . json_last_error_msg());
        }

        // Vérifie la présence de la clé principale 'dictionnaire'
        if (!isset($this->data['dictionnaire'])) {
            die("Erreur : structure JSON invalide (clé 'dictionnaire' manquante)");
        }
    }

    // Méthode magique : appelée quand on fait echo $objet
    public function __toString()
    {
        return 'VueDictionnaire_traitement instance';
    }

    // Retourne un mot aléatoire (avec mot, catégorie et indice)
    public function getMotAleatoire()
    {
        // Récupère toutes les lettres disponibles (A, B, C...)
        $lettres = array_keys($this->data['dictionnaire']);

        // Choisit une lettre au hasard
        $lettreAleatoire = $lettres[array_rand($lettres)];

        // Récupère tous les mots de cette lettre
        $mots = $this->data['dictionnaire'][$lettreAleatoire];

        // Choisit un mot au hasard parmi eux
        $motChoisi = $mots[array_rand($mots)];

        // Retourne le tableau complet du mot
        return $motChoisi;
    }

    // Retourne tous les mots commençant par une lettre donnée
    public function getMotsParLettre($lettre)
    {
        // Convertit en majuscule pour matcher le JSON
        $lettre = strtoupper($lettre);

        // Retourne les mots ou un tableau vide si la lettre n’existe pas
        return $this->data['dictionnaire'][$lettre] ?? [];
    }

    // Retourne la longueur d’un mot aléatoire (pratique pour la grille GD)
    public function getLongueurMotAleatoire()
    {
        // Récupère un mot aléatoire
        $mot = $this->getMotAleatoire();

        // Retourne le nombre de lettres
        return strlen($mot['mot']);
    }
}