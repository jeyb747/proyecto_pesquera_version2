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

      <form action="../php/controlador/reservas/guardar.php" method="POST" class="form-reserva">

      <div class="campo">
        <label>Nombre</label>
        <input type="text" name="nombre" required>
      </div>

      <div class="campo">
        <label>Teléfono</label>
        <input type="text" name="telefono" required>
      </div>

      <div class="campo">
        <label>Fecha</label>
        <input type="date" name="fecha" required>
      </div>

      <div class="campo">
        <label>Hora</label>
        <input type="time" name="hora" required>
      </div>

      <div class="campo">
        <label>Personas</label>
        <input type="number" name="personas" required>
      </div>

      <div class="campo">
        <label>Comentarios</label>
        <textarea name="comentarios"></textarea>
      </div>

      <button type="submit" class="btn-reservar">Reservar</button>

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