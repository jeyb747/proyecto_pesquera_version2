<?php require_once("../php/configuracion/auth.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>La Pesquera | Reservas</title>

  <!-- CSS -->
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/reservas.css">

  <!-- JS -->
  <script defer src="../js/script.js"></script>
  <script defer src="../js/reservas.js"></script>
</head>

<body>
  <!-- NAVBAR -->
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

  <!-- CONTENIDO -->
  <main class="container">
    <section class="reservas-section">
      <h2>📅 Reserva tu mesa</h2>
      <p>Completa los datos para asegurar tu mesa en La Pesquera.</p>

      <form id="formReserva" class="form-reserva" novalidate>
        
        <div class="campo">
          <label for="nombre">Nombre completo</label>
          <input type="text" id="nombre" required>
        </div>

        <div class="campo">
          <label for="telefono">Teléfono</label>
          <input type="tel" id="telefono" placeholder="3001234567" required pattern="[0-9]{7,10}">
        </div>

        <div class="campo">
          <label for="fecha">Fecha de reserva</label>
          <input type="date" id="fecha" required>
        </div>

        <div class="campo">
          <label for="hora">Hora</label>
          <input type="time" id="hora" required>
        </div>

        <div class="campo">
          <label for="personas">Número de personas</label>
          <input type="number" id="personas" min="1" max="20" value="2" required>
        </div>

        <div class="campo">
          <label for="comentarios">Comentarios (opcional)</label>
          <textarea id="comentarios" rows="3" placeholder="Ej: Mesa cerca a la ventana"></textarea>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn-reservar" id="btnReservar">Reservar ahora</button>
          <a id="btnWhatsapp" class="btn-wpp" target="_blank" rel="noopener noreferrer" style="display:none;">Enviar por WhatsApp</a>
        </div>

      </form>

      <div id="mensajeExito" class="mensaje-exito">
        ¡Reserva registrada! Puedes ver el historial o enviar por WhatsApp.
      </div>

    </section>
  </main>

  <footer>
    <p>© 2025 La Pesquera · Todos los derechos reservados</p>
  </footer>

</body>
</html>