<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validateName($name) {
    return preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{2,}$/', $name);
}

function validatePhone($phone) {
    return preg_match('/^[0-9]{8}$/', $phone);
}

function validateAddress($address) {
    return strlen($address) >= 5;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if ($data) {
        $firstname = trim($data['firstname']);
        $lastname = trim($data['lastname']);
        $email = trim($data['email']);
        $phone = trim($data['phone']);
        $address = trim($data['address']);
        $password = $data['password'];
        $confirm_password = $data['confirm_password'];
        
        $response = ['success' => false, 'message' => ''];

        // Validaciones en el servidor
        if (!validateName($firstname)) {
            $response['message'] = 'Nombre inválido. Solo letras y espacios, mínimo 2 caracteres.';
        } elseif (!validateName($lastname)) {
            $response['message'] = 'Apellido inválido. Solo letras y espacios, mínimo 2 caracteres.';
        } elseif (!validateEmail($email)) {
            $response['message'] = 'Email inválido';
        } elseif (!validatePhone($phone)) {
            $response['message'] = 'El teléfono debe tener exactamente 8 dígitos';
        } elseif (!validateAddress($address)) {
            $response['message'] = 'La dirección debe tener al menos 5 caracteres';
        } elseif (strlen($password) < 8) {
            $response['message'] = 'La contraseña debe tener al menos 8 caracteres';
        } elseif (!preg_match('/[A-Z]/', $password)) {
            $response['message'] = 'La contraseña debe contener al menos una mayúscula';
        } elseif (!preg_match('/[a-z]/', $password)) {
            $response['message'] = 'La contraseña debe contener al menos una minúscula';
        } elseif (!preg_match('/[0-9]/', $password)) {
            $response['message'] = 'La contraseña debe contener al menos un número';
        } elseif ($password !== $confirm_password) {
            $response['message'] = 'Las contraseñas no coinciden';
        } else {
            $users_file = __DIR__ . '/users.json';
            $users = file_exists($users_file) ? json_decode(file_get_contents($users_file), true) : ['users' => []];
            
            // Verificar si el email existe
            $email_exists = false;
            foreach ($users['users'] as $user) {
                if ($user['email'] === $email) {
                    $email_exists = true;
                    break;
                }
            }
            
            if ($email_exists) {
                $response['message'] = 'Email ya registrado';
            } else {
                $users['users'][] = [
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'email' => $email,
                    'phone' => $phone,
                    'address' => $address,
                    'password' => password_hash($password, PASSWORD_DEFAULT)
                ];
                
                if (file_put_contents($users_file, json_encode($users, JSON_PRETTY_PRINT))) {
                    $_SESSION['register_success'] = 'Registro exitoso';
                    $response['success'] = true;
                    $response['message'] = 'Registro exitoso';
                } else {
                    $response['message'] = 'Error al guardar';
                }
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REGISTER</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="/automatitation-front-end/frontend/views/assets/css/register-styles.css">
    <script src="/automatitation-front-end/frontend/views/assets/js/form-validations.js"></script>
</head>

<body>
<div class="modal-register active">
    <div class="container-register">
        <div class="register-bg-1"></div>
        <div class="register-bg-2"></div>
        <div class="register-box">
            <div class="register-icon-wrapper">
                <i class="ri-user-add-fill register-icon-big"></i>
            </div>
            <h2 class="register-title">Sign Up</h2>
            <div id="register-message" class="message-container"></div>
            <form action="" method="POST" id="registerForm" novalidate>
                <div class="register-input-holder">
                    <div class="register-icon-bg">
                        <div class="register-icon-container">
                            <i class="ri-user-fill register-input-icon"></i>
                        </div>
                    </div>
                    <input type="text" name="firstname" placeholder="First Name" required 
                           pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{2,}"
                           title="Solo letras y espacios, mínimo 2 caracteres">
                    <div class="error-message" id="firstname-error"></div>
                </div>
                
                <div class="register-input-holder">
                    <div class="register-icon-bg">
                        <div class="register-icon-container">
                            <i class="ri-user-fill register-input-icon"></i>
                        </div>
                    </div>
                    <input type="text" name="lastname" placeholder="Last Name" required
                           pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{2,}"
                           title="Solo letras y espacios, mínimo 2 caracteres">
                    <div class="error-message" id="lastname-error"></div>
                </div>

                <div class="register-input-holder">
                    <div class="register-icon-bg">
                        <div class="register-icon-container">
                            <i class="ri-mail-fill register-input-icon"></i>
                        </div>
                    </div>
                    <input type="email" name="email" placeholder="Email" required>
                    <div class="error-message" id="email-error"></div>
                </div>

                <div class="register-input-holder">
                    <div class="register-icon-bg">
                        <div class="register-icon-container">
                            <i class="ri-phone-fill register-input-icon"></i>
                        </div>
                    </div>
                    <input type="tel" name="phone" placeholder="Phone (8 digits)" required
                           pattern="[0-9]{8}" title="Debe contener exactamente 8 dígitos">
                    <div class="error-message" id="phone-error"></div>
                </div>

                <div class="register-input-holder">
                    <div class="register-icon-bg">
                        <div class="register-icon-container">
                            <i class="ri-home-fill register-input-icon"></i>
                        </div>
                    </div>
                    <input type="text" name="address" placeholder="Address" required
                           minlength="5" title="Mínimo 5 caracteres">
                    <div class="error-message" id="address-error"></div>
                </div>

                <div class="register-input-holder">
                    <div class="register-icon-bg">
                        <div class="register-icon-container">
                            <i class="ri-lock-fill register-input-icon"></i>
                        </div>
                    </div>
                    <input type="password" name="password" placeholder="Password" required
                           pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}"
                           title="Mínimo 8 caracteres, una mayúscula, una minúscula y un número">
                    <div class="error-message" id="password-error"></div>
                </div>

                <div class="register-input-holder">
                    <div class="register-icon-bg">
                        <div class="register-icon-container">
                            <i class="ri-lock-fill register-input-icon"></i>
                        </div>
                    </div>
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                    <div class="error-message" id="confirm-password-error"></div>
                </div>

                <div class="register-button-wrapper">
                    <button type="submit" class="register-btn">REGISTER</button>
                    <div class="have-account">
                        <span class="register-text">Already have an account?</span>
                        <a href="#" class="register-link" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">Sign In</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="register-shadow"></div>
    </div>
</div>
<script>
document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    console.log('[Registro] Iniciando validación del formulario');
    let isValid = true;
    const errorMessages = {
        firstname: '',
        lastname: '',
        email: '',
        phone: '',
        address: '',
        password: '',
        confirm_password: ''
    };

    // Validar nombre
    if (!validateName(this.firstname.value)) {
        isValid = false;
        errorMessages.firstname = 'Nombre inválido. Solo letras y espacios, mínimo 2 caracteres.';
    }

    // Validar apellido
    if (!validateName(this.lastname.value)) {
        isValid = false;
        errorMessages.lastname = 'Apellido inválido. Solo letras y espacios, mínimo 2 caracteres.';
    }

    // Validar email
    if (!validateEmail(this.email.value)) {
        isValid = false;
        errorMessages.email = 'Email inválido.';
    }

    // Validar teléfono
    if (!validatePhone(this.phone.value)) {
        isValid = false;
        errorMessages.phone = 'El teléfono debe tener exactamente 8 dígitos.';
    }

    // Validar dirección
    if (!validateAddress(this.address.value)) {
        isValid = false;
        errorMessages.address = 'La dirección debe tener al menos 5 caracteres.';
    }

    // Validar contraseña
    const password = this.password.value;
    console.log('[Registro] Validando requisitos de contraseña');
    
    if (!validatePassword(password)) {
        isValid = false;
        console.log('[Registro] Contraseña inválida');
        errorMessages.password = 'La contraseña debe tener:\n' +
            '- Al menos 8 caracteres\n' +
            '- Al menos una letra mayúscula\n' +
            '- Al menos una letra minúscula\n' +
            '- Al menos un número';
    } else {
        console.log('[Registro] Contraseña válida');
    }

    // Validar confirmación de contraseña
    if (this.password.value !== this.confirm_password.value) {
        isValid = false;
        console.log('[Registro] Las contraseñas no coinciden');
        errorMessages.confirm_password = 'Las contraseñas no coinciden.';
    }

    // Mostrar mensajes de error
    Object.keys(errorMessages).forEach(field => {
        const errorElement = document.getElementById(`${field}-error`);
        if (errorElement) {
            errorElement.textContent = errorMessages[field];
            errorElement.style.display = errorMessages[field] ? 'block' : 'none';
            if (errorMessages[field]) {
                console.log(`[Registro] Error en ${field}:`, errorMessages[field]);
            }
        }
    });

    if (!isValid) {
        console.log('[Registro] Formulario contiene errores');
        return;
    }

    console.log('[Registro] Formulario válido, procediendo con el envío');
    
    // Continuar con el envío del formulario si todo es válido
    const messageContainer = document.getElementById('register-message');
    const formData = {
        firstname: this.firstname.value,
        lastname: this.lastname.value,
        email: this.email.value,
        phone: this.phone.value,
        address: this.address.value,
        password: this.password.value,
        confirm_password: this.confirm_password.value
    };

    try {
        const response = await fetch('/automatitation-front-end/frontend/views/pages/login/register.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        });

        const data = await response.json();
        console.log('[Debug] Respuesta del servidor:', data);
        
        if (data.success) {
            // Mostrar mensaje de éxito
            messageContainer.className = 'message-container message-success';
            messageContainer.textContent = data.message;
            
            // Limpiar el formulario
            this.reset();
            
            // Cerrar el modal después de 1.5 segundos
            setTimeout(() => {
                const modalRegister = document.querySelector('.modal-register');
                if (modalRegister) {
                    modalRegister.classList.remove('active');
                }
                // Limpiar el mensaje después de cerrar
                messageContainer.textContent = '';
            }, 1500);
            
            console.log('[Debug] Registro completado exitosamente');
        } else {
            messageContainer.className = 'message-container message-error';
            messageContainer.textContent = data.message;
            console.log('[Debug] Error en registro:', data.message);
        }
    } catch (error) {
        console.error('[Debug] Error en la petición:', error);
        messageContainer.className = 'message-container message-error';
        messageContainer.textContent = 'Error al procesar el registro. Por favor, intente nuevamente.';
    }
});

</script>
</body>
</html>
