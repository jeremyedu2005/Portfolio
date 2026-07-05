<?php

class VueMentionlegales
{
    public function __toString()
    {
        ob_start();
        
        ?>
        
        <div class="mentions-legales">
            <div class="titre-mentions">
                <h1>Mentions légales</h1>
            </div>
            
            <div class="section-mentions proprietaire">
                <h2 class="editeur">Éditeur du site</h2>
                <p><strong>Propriétaire :</strong> RAODSON Miaro Jérémy</p>
                <p><strong>Statut :</strong> Étudiant en BUT MMI (Métiers du Multimédia et de l'Internet) à l'IUT de Bobigny</p>
                <p><strong>Adresse e-mail :</strong> jraodson@gmail.com</p>
            </div>
            
            <div class="section-mentions hebergeur">
                <h2>Hébergement du site</h2>
                <p><strong>Hébergeur :</strong> IUT de Bobigny</p>
                <p><strong>Adresse :</strong> 1 Rue de Chablis, 93000 Bobigny</p>
                <p><strong>Numéro de téléphone :</strong> 01 48 38 76 76</p>
                <p><strong>Lien hébergeur :</strong> <a href="https://81.194.40.26" target="_blank">https://81.194.40.26</a></p>
            </div>
            
            <div class="section-mentions propriete">
                <h2>Propriété intellectuelle</h2>
                <p>Les contenus présents sur ce site, notamment le jeu Motus Star Wars, sont la propriété de leur auteur, sauf mention contraire.</p>
                <p>L'univers Star Wars est la propriété de Lucasfilm Ltd. et The Walt Disney Company. Ce site est un projet académique sans but commercial.</p>
                <p>Toute reproduction, représentation ou utilisation sans autorisation préalable est interdite.</p>
            </div>
            
            <div class="section-mentions activite">
                <h2>Activité</h2>
                <p><strong>Activité :</strong> Jeu de type Motus sur le thème Star Wars avec dictionnaire personnalisé, système de tentatives et indices.</p>
                <p><strong>Activité professionnelle déclarée :</strong> Aucune</p>
                <p><strong>Nature du projet :</strong> Projet académique dans le cadre de la formation BUT MMI</p>
            </div>
            
            <div class="section-mentions cookies">
                <h2>Cookies et données personnelles</h2>
                <p>Le site utilise uniquement des cookies de session techniques nécessaires au fonctionnement du jeu (sauvegarde des tentatives, du mot secret, etc.).</p>
                <p>Aucun cookie de suivi, de mesure d'audience ou publicitaire n'est utilisé.</p>
                <p>Aucune donnée personnelle n'est collectée, stockée ou transmise à des tiers.</p>
                <p>Les données de session sont supprimées à la fermeture du navigateur.</p>
            </div>
            
            <div class="section-mentions description">
                <h2>Description du site</h2>
                <p>Ce site est un jeu de type Motus (mot mystère) sur le thème de l'univers Star Wars. Il a pour vocation académique dans le cadre d'un parcours universitaire en BUT MMI.</p>
                <p><strong>Fonctionnalités principales :</strong></p>
                <ul>
                    <li>Dictionnaire de mots Star Wars (personnages, planètes, vaisseaux, etc.)</li>
                    <li>Système de 6 tentatives pour deviner le mot secret</li>
                    <li>Indices chronométrés pour aider le joueur</li>
                    <li>Système de révélation après 3 minutes</li>
                    <li>Interface interactive avec clavier virtuel</li>
                </ul>
                <p>Ce site n'a aucun but commercial et est destiné uniquement à des fins éducatives.</p>
            </div>
            
            <footer class="footer-mentions">
                <p>&copy; 2026-RAODSON Miaro Jérémy-Tous droits réservés</p>
            </footer>
        </div>
        
        <?php
        return ob_get_clean();
    }
}