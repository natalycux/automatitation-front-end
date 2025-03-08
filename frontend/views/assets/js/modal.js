document.addEventListener('DOMContentLoaded', function() {
    const modalTriggers = document.querySelectorAll('[data-modal]');
    const loginModal = document.getElementById('loginModal');
    const registerModal = document.getElementById('registerModal');

    function closeAllModals() {
        loginModal.classList.remove('active');
        registerModal.classList.remove('active');
    }

    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            const modalId = trigger.getAttribute('data-modal');
            
            closeAllModals();
            document.getElementById(modalId).classList.add('active');
            document.body.style.overflow = 'hidden'; // Previene scroll
        });
    });

    // Eventos dentro de los modales para cambiar entre ellos
    loginModal.querySelector('.sign-up').addEventListener('click', (e) => {
        e.preventDefault();
        loginModal.classList.remove('active');
        registerModal.classList.add('active');
    });

    registerModal.querySelector('.register-link').addEventListener('click', (e) => {
        e.preventDefault();
        registerModal.classList.remove('active');
        loginModal.classList.add('active');
    });

    // Prevenir que el click en el contenido del modal lo cierre
    [loginModal, registerModal].forEach(modal => {
        modal.querySelector('.container, .container-register').addEventListener('click', (e) => {
            e.stopPropagation();
        });
    });
});