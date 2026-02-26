<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registro | La Pesquera</title>

  <!-- CSS -->
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/registro.css">

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

<main class="container registro-box">
  <h2>📝 Crear cuenta</h2>
  <p>Regístrate para poder reservar, pedir domicilio y usar el carrito de forma completa.</p>

  <!-- FORMULARIO -->
  <form id="formRegistro" class="registro-form" action="../php/controlador/registro.php" method="POST">

    <div class="campo">
      <label for="nombre">Nombre completo:</label>
      <input type="text" id="nombre" name="nombre" required>
    </div>

    <div class="campo">
      <label for="correo">Correo electrónico:</label>
      <input type="email" id="correo" name="correo" required>
    </div>

    <div class="campo">
      <label for="telefono">Teléfono:</label>
      <input type="tel" id="telefono" name="telefono" required pattern="[0-9]{7,10}">
    </div>

    <div class="campo">
      <label for="password">Contraseña:</label>
      <input type="password" id="password" name="password" required minlength="4">
    </div>

    <button type="submit" class="btn-registrar">Crear cuenta</button>
  </form>

  <p class="login-link">
    ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
  </p>

  <!-- Mensajes de error o éxito -->
  <?php if (isset($_GET['error'])): ?>
    <div class="mensaje-error">Este correo ya está registrado ❌</div>
  <?php endif; ?>
</main>

<footer>
  <p>© 2025 La Pesquera · Todos los derechos reservados</p>
</footer>

</body>
</html>