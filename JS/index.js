// Les variables globales du menu hamburger
const menuHamburger = document.querySelector('.menu-hamburger');
const navLinks = document.querySelector('.nav-links');

menuHamburger.addEventListener('click', () => {
    menuHamburger.classList.toggle('active');
    navLinks.classList.toggle('mobile-menu');
    document.body.classList.toggle('menu-open');
});

// Ferme le menu automatiquement quand on clique sur un lien
navLinks.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
        menuHamburger.classList.remove('active');
        navLinks.classList.remove('mobile-menu');
        document.body.classList.remove('menu-open');
    });
});

/* ---- GESTION DE LA FENETRE MODALE DETAIL ---- */
const modalContainer = document.querySelector('#modal-container');
const modalInjectContent = document.querySelector('#modal-inject-content');
const modalCloseBtn = document.querySelector('#modal-close');
const debugButtons = document.querySelectorAll('.btn-savoir-plus');

debugButtons.forEach(button => {
    button.addEventListener('click', (e) => {
        const templateId = e.currentTarget.getAttribute('data-template-id');
        const templateElement = document.querySelector(`#${templateId}`);
        if (templateElement) {
            modalInjectContent.innerHTML = '';
            const clone = templateElement.content.cloneNode(true);
            modalInjectContent.appendChild(clone);
            modalContainer.classList.remove('modal-hidden');
        }
    });
});

if (modalCloseBtn) {
    modalCloseBtn.addEventListener('click', () => {
        modalContainer.classList.add('modal-hidden');
    });
}

if (modalContainer) {
    modalContainer.addEventListener('click', (e) => {
        if (e.target === modalContainer) {
            modalContainer.classList.add('modal-hidden');
        }
    });
}

/* ---- GESTION DES ANIMATIONS REVERSIBLES (SAMSUNG STYLE) ---- */
const targetSections = document.querySelectorAll('.scroll-reveal');

// Message de contrôle pour vérifier que le script trouve tes sections
console.log(`Script de reveal activé ! Nombre de sections trouvées : ${targetSections.length}`);

// Options ajustées : déclenchement plus rapide (5% de visibilité)
const observerOptions = {
    root: null,
    rootMargin: '0px',
    threshold: 0.05
};

const scrollObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            // L'élément entre sur l'écran
            entry.target.classList.add('reveal-active');
        } else {
            // L'élément sort de l'écran (scroll arrière ou dépassement)
            entry.target.classList.remove('reveal-active');
        }
    });
}, observerOptions);

// On lance l'observation
targetSections.forEach(section => {
    scrollObserver.observe(section);
});