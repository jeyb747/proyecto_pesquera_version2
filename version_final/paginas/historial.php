<?php require_once("../php/configuracion/auth.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Historial de Reservas | La Pesquera</title>

  <!-- CSS -->
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="stylesheet" href="../css/historial.css" />

  <!-- JS -->
  <script defer src="../js/script.js"></script>
  <script defer src="../js/historial.js"></script>
</head>

<body>

  <!-- ====== NAVBAR ====== -->
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

  <!-- ====== CONTENIDO ====== -->
  <main class="container">
    <section class="historial-section">
      <h2>📚 Historial de Reservas</h2>
      <p>Ver y administrar las reservas guardadas en este equipo.</p>

      <div class="historial-actions">
        <button id="btnBorrarTodo" class="btn-danger">Borrar todo</button>
        <a href="reservas.html" class="btn">Nueva reserva</a>
      </div>

      <div id="listaReservas" class="lista-reservas"></div>
      <div id="sinReservas" class="vacio">No hay reservas guardadas.</div>
    </section>
  </main>

  <!-- ====== FOOTER ====== -->
  <footer>
    <p>© 2025 La Pesquera · Todos los derechos reservados</p>
  </footer>

</body>
</html>