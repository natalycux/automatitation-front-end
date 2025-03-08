<?php
session_start();

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Agregar log para debug
    error_log("Datos POST recibidos: " . file_get_contents('php://input'));
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if ($data) {
        $email = $data['email'];
        $password = $data['password'];
        $response = ['success' => false, 'message' => ''];

        if (!validateEmail($email)) {
            $response['message'] = "Formato de email inválido";
        } else {
            $users_file = __DIR__ . '/users.json';
            if (file_exists($users_file)) {
                $users = json_decode(file_get_contents($users_file), true);
                foreach ($users['users'] as $user) {
                    if ($user['email'] === $email && password_verify($password, $user['password'])) {
                        $_SESSION['loggedin'] = true;
                        $_SESSION['email'] = $email;
                        $_SESSION['firstname'] = $user['firstname'];
                        $_SESSION['lastname'] = $user['lastname'];
                        $response['success'] = true;
                        $response['redirect'] = 'http://localhost/automatitation-front-end/frontend/views/pages/login/home.php';
                        break;
                    }
                }
                if (!$response['success']) {
                    $response['message'] = "Email o contraseña incorrectos";
                }
            } else {
                $response['message'] = "Error al acceder a los datos de usuarios";
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
    <title>LOGIN</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="/automatitation-front-end/frontend/views/assets/css/signin.css">
</head>

<body>
    <div class="modal-signin active">
        <div class="container">
            <div class="login-bg-1"></div>
            <div class="login-bg-2"></div>
            <div class="login-container">
                <div class="icon-wrapper">
                    <i class="ri-user-fill icon-big"></i>
                </div>
                <h2 class="sign-in">Sign In</h2>
                <div id="login-message" class="message-container"></div>

                <form id="loginForm">
                    <div class="input-holder">
                        <div class="input-icon-wrapper-bg">
                            <div class="input-icon-wrapper">
                                <i class="ri-mail-fill input-icon"></i>
                            </div>
                        </div>
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="input-holder">
                        <div class="input-icon-wrapper-bg">
                            <div class="input-icon-wrapper">
                                <i class="ri-lock-fill input-icon"></i>
                            </div>
                        </div>
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="remember-forgot">
                        <div class="checkbox-wrapper">
                            <input type="checkbox" id="lbl">
                            <label class="lbl-remember" for="lbl">Remember</label>
                        </div>
                        <a href="#" class="forgot">Forgot password?</a>
                    </div>
                    <div class="login-wrapper">
                        <button type="submit" class="login">LOGIN</button>
                        <div class="no-account">
                            <span class="forgot">Don't have account?</span>
                            <a href="#" class="sign-up" data-bs-toggle="modal" data-bs-target="#registerModal" data-bs-dismiss="modal">Sign Up</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="shadow"></div>
        </div>
    </div>

    <script>
    console.log('Script de login.php cargado');
    
    document.getElementById('loginForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        console.log('Formulario de login enviado');
        const messageContainer = document.getElementById('login-message');
        
        const formData = {
            email: this.email.value,
            password: this.password.value
        };
        
        console.log('Datos del formulario:', formData);

        try {
            console.log('Iniciando petición fetch');
            const response = await fetch('/automatitation-front-end/frontend/views/pages/login/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            console.log('Respuesta del servidor recibida:', response);
            const responseText = await response.text();
            console.log('Texto de respuesta:', responseText);

            let data;
            try {
                data = JSON.parse(responseText);
                console.log('Datos JSON parseados:', data);
            } catch (e) {
                console.error('Error al parsear JSON:', e);
                throw new Error('Respuesta del servidor no es JSON válido');
            }

            if (data.success) {
                console.log('Login exitoso, redirigiendo a:', data.redirect);
                window.location.href = data.redirect;
            } else {
                console.log('Error en login:', data.message);
                messageContainer.className = 'message-container message-error';
                messageContainer.textContent = data.message;
            }
        } catch (error) {
            console.error('Error completo:', error);
            messageContainer.className = 'message-container message-error';
            messageContainer.textContent = 'Error al procesar el inicio de sesión';
        }
    });

    document.getElementById('switchToRegister').addEventListener('click', function(e) {
        e.preventDefault();
        // Cerrar el modal de login
        const loginModal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
        loginModal.hide();
        
        // Simular clic en el botón de registro después de un breve retraso
        setTimeout(() => {
            document.getElementById('registerBtn').click();
        }, 500);
    });
    </script>
</body>

</html>
