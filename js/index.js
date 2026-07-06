const modalContainer = document.getElementById('modal-container');
const modalClose = document.getElementById('modal-close');
const btnSavoirPlus = document.querySelectorAll('.btn-savoir-plus');
const modalInjectContent = document.getElementById('modal-inject-content');

// Fonction pour ouvrir la modale
btnSavoirPlus.forEach(btn => {
    btn.addEventListener('click', () => {
        const templateId = btn.getAttribute('data-template-id');
        const template = document.getElementById(templateId);
        const clone = template.content.cloneNode(true);
        
        modalInjectContent.innerHTML = '';
        modalInjectContent.appendChild(clone);
        
        modalContainer.classList.remove('modal-hidden');
        // EMPECHE LE SCROLL : On ajoute la classe au body
        document.body.classList.add('menu-open'); 
    });
});

// Fonction pour fermer la modale
modalClose.addEventListener('click', () => {
    modalContainer.classList.add('modal-hidden');
    // REATIVE LE SCROLL : On retire la classe du body
    document.body.classList.remove('menu-open'); 
});

// Fermeture en cliquant à l'extérieur du cadre
modalContainer.addEventListener('click', (e) => {
    if (e.target === modalContainer) {
        modalContainer.classList.add('modal-hidden');
        document.body.classList.remove('menu-open');
    }
});

// ==================== ANIMATION AU DEFILEMENT (SCROLL REVEAL) ====================
// Ajoute/retire .reveal-active selon que l'élément est visible ou non
// (effet "va-et-vient" façon pages Samsung : l'animation se rejoue si on remonte)
const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('reveal-active');
        } else {
            entry.target.classList.remove('reveal-active');
        }
    });
}, {
    threshold: 0.15 // se déclenche quand 15% de l'élément est visible
});

document.querySelectorAll('.scroll-reveal').forEach(el => {
    revealObserver.observe(el);
});