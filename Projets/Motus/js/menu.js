// js/menu.js - Gestion du menu responsive mobile
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.getElementById('hamburger-btn');
    const menu = document.getElementById('navbar-menu');

    if (hamburger && menu) {
        hamburger.addEventListener('click', function() {
            // Ajoute ou supprime la classe active au menu et au bouton
            menu.classList.toggle('active');
            hamburger.classList.toggle('active');
        });
    }
});