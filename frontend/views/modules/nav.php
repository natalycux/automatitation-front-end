<!-- Navigation-->
<nav class="navbar navbar-expand-lg bg-secondary text-uppercase fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand" href="#page-top">Desarrollo Web Octavo</a>
        <button class="navbar-toggler text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menu
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" href="#portfolio">Mis proyectos</a></li>
                <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" href="#about">About</a></li>
                <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" href="#contact">Contact</a></li>
                <li class="nav-item mx-0 mx-lg-1">
                    <a class="nav-link py-3 px-0 px-lg-3" href="#" id="loginBtn" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a>
                </li>
                <li class="nav-item mx-0 mx-lg-1">
                    <a class="nav-link py-3 px-0 px-lg-3" href="#" id="registerBtn" data-bs-toggle="modal" data-bs-target="#registerModal">Register</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loginModalContent">
                <!-- El contenido se cargará dinámicamente -->
            </div>
        </div>
    </div>
</div>

<!-- Register Modal -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="registerModalContent">
                <!-- El contenido se cargará dinámicamente -->
            </div>
        </div>
    </div>
</div>

<script>
// Función mejorada para cargar modales con logs
function loadModalContent(modalId, url) {
    console.log(`Intentando cargar contenido para ${modalId} desde ${url}`);
    fetch(url)
        .then(response => {
            console.log('Respuesta recibida:', response);
            return response.text();
        })
        .then(html => {
            console.log('Contenido HTML recibido');
            const modalContent = document.querySelector(modalId + ' .modal-body');
            console.log('Modal content element:', modalContent);
            modalContent.innerHTML = html;
            console.log('Contenido insertado en el modal');
            
            if (modalId === '#loginModal') {
                console.log('Inicializando formulario de login');
                initializeLoginForm();
            } else if (modalId === '#registerModal') {
                console.log('Inicializando formulario de registro');
                initializeRegisterForm();
            }
        })
        .catch(error => console.error('Error cargando contenido del modal:', error));
}

// Inicializar el formulario de login
function initializeLoginForm() {
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            try {
                const response = await fetch('/automatitation-front-end/frontend/views/pages/login/login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        email: this.email.value,
                        password: this.password.value
                    })
                });

                const data = await response.json();
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    const messageContainer = document.getElementById('login-message');
                    if (messageContainer) {
                        messageContainer.className = 'message-container message-error';
                        messageContainer.textContent = data.message;
                    }
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    }
}

// Manejadores de eventos para los modales
document.getElementById('loginBtn').addEventListener('click', function() {
    loadModalContent('#loginModal', '/automatitation-front-end/frontend/views/pages/login/login.php');
});

document.getElementById('registerBtn').addEventListener('click', function() {
    loadModalContent('#registerModal', '/automatitation-front-end/frontend/views/pages/login/register.php');
});

// Inicializar los modales
const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
const registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
</script>