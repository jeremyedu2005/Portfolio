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