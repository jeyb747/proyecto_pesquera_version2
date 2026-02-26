<?php require_once("../php/configuracion/auth.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contacto | La Pesquera</title>

  <!-- CSS -->
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/contacto.css">

  <!-- JS -->
  <script defer src="../js/script.js"></script>
  <script defer src="../js/contacto.js"></script>
</head>

<body>

  <header class="navbar">
    <div class="container nav-content">
      <h1 class="logo">La Pesquera</h1>

      <button id="menu-toggle" class="menu-toggle">☰</button>

      <nav id="nav-menu" class="nav-menu">
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

  <main class="container contacto-container">

    <h2>📩 Contáctanos</h2>
    <p>¡Estamos para ayudarte! Envíanos tus dudas o sugerencias.</p>

    <form id="formContacto" class="form-contacto">

      <div class="campo">
        <label for="nombre">Nombre completo:</label>
        <input type="text" id="nombre" required>
      </div>

      <div class="campo">
        <label for="telefono">Teléfono:</label>
        <input type="tel" id="telefono" required pattern="[0-9]{7,10}">
      </div>

      <div class="campo">
        <label for="mensaje">Mensaje:</label>
        <textarea id="mensaje" required rows="4"></textarea>
      </div>

      <button type="submit" class="btn-enviar">Enviar por WhatsApp</button>

    </form>

    <div id="mensajeExito" class="mensaje-exito">Mensaje enviado por WhatsApp ✔</div>

    <div class="mapa-box">
      <h3>📍 Nuestra Ubicación</h3>

      <iframe
        src="https://www.google.com/maps?q=42b%20Sur2%20Cra.%2079&output=embed"
        width="100%"
        height="300"
        style="border:0; border-radius:14px;"
        allowfullscreen
        loading="lazy">
      </iframe>
    </div>

  </main>

  <footer>
    <p>© 2025 La Pesquera · Todos los derechos reservados</p>
  </footer>

</body>
</html>