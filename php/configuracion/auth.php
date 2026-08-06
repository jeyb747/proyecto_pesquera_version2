<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    $_SESSION['flash_auth'] = 'Debes iniciar sesión para reservar o pedir a domicilio.';
    header("Location: /paginas/login.php?next=" . urlencode(basename($_SERVER['PHP_SELF'], '.php')));
    exit();
}
?>
