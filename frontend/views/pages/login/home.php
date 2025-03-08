<?php
session_start();

// Verificación más estricta de la sesión
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: /automatitation-front-end/frontend/views/pages/login/login.php");
    exit;
}

$email = $_SESSION['email'];
$firstname = $_SESSION['firstname'];
$lastname = $_SESSION['lastname'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/automatitation-front-end/frontend/views/assets/css/home.css">
    <title>HOME</title>
</head>
<body>
    <div class="nav">
        <div class="logo">
            <p>LOGO</p>
        </div>
        <div class="right-links">
            <a href="#">Change Profile</a>
            <a href="/automatitation-front-end/frontend/views/pages/login/logout.php"><button class="btn">Log Out</button></a>
        </div>
    </div>

    <main class="main">
        <div class="main-box">
            <div class="top">
                <div class="box">
                    <p>Hello <b><?php echo htmlspecialchars($firstname . ' ' . $lastname); ?></b>, Welcome</p>
                </div>
                <div class="box">
                    <p>Your email is <b><?php echo htmlspecialchars($email); ?></b></p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>