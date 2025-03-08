<?php
session_start();

// Limpiar todas las variables de sesión
$_SESSION = array();

// Destruir la sesión
session_destroy();

// Redirigir al index.html principal
header("Location: /automatitation-front-end/index.html");
exit;
?>