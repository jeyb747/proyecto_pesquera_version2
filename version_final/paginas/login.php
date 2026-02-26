<?php
// iniciar sesión por si luego quieres usar variables
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar Sesión | La Pesquera</title>

  <!-- CSS -->
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/login.css">
</head>

<body>

<header class="navbar">
  <div class="container nav-content">
    <h1 class="logo">La Pesquera</h1>

    
    <nav class="nav-menu">
      <a href="../index.php">Inicio</a>
      <a href="login.php" class="active">Iniciar sesión</a>
      <a href="menu.php">Menú</a>
      <a href="domicilio.php">Domicilio</a>
      <a href="reservas.php">Reservas</a>
      <a href="contacto.php">Contacto</a>
      <a href="carrito.php" class="carrito-link">🛒 Carrito</a>
      <a href="historial.php">Historial</a>
    </nav>
  </div>
</header>

<main class="container login-box">
  <h2>🔐 Iniciar sesión</h2>

 
  <form class="login-form" action="../php/controlador/controlador.php" method="POST">

    <div class="campo">
      <label>Correo:</label>
      <input type="email" name="correo" required>
    </div>

    <div class="campo">
      <label>Contraseña:</label>
      <input type="password" name="password" required>
    </div>

    <button type="submit" class="btn-login"><a href="../../index.php">Ingresar</a></button>
  </form>

  <p class="register-link">
    ¿No tienes cuenta?
    <a href="registro.php">Crea una aquí</a>
  </p>

  
  <?php if (isset($_GET['error'])): ?>
    <div class="mensaje-error">Credenciales incorrectas ❌</div>
  <?php endif; ?>

</main>

<footer>
  <p>© 2025 La Pesquera · Todos los derechos reservados</p>
</footer>

</body>
</html>