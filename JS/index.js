document.addEventListener('DOMContentLoaded', () => {
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

    modalCloseBtn.addEventListener('click', () => {
        modalContainer.classList.add('modal-hidden');
    });

    modalContainer.addEventListener('click', (e) => {
        if (e.target === modalContainer) {
            modalContainer.classList.add('modal-hidden');
        }
    });
});