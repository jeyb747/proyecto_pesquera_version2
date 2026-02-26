<?php require_once("../php/configuracion/auth.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carrito | La Pesquera</title>

  <!-- 🎨 Estilos -->
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/carro.css">
</head>

<body>
  <!-- ====== ENCABEZADO ====== -->
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

  <!-- ====== CONTENIDO PRINCIPAL ====== -->
  <main>
    <h1>Carrito de Compras</h1>

    <div class="carrito-container" id="carrito-container">
      <!-- Productos se cargan con JS -->
    </div>

    <div class="total" id="total">Total: $0</div>

    <div class="acciones">
      <button class="btn" id="vaciarCarrito">Vaciar carrito</button>
      <button class="btn" id="btnPagar" style="background:#27ae60;">Pagar</button>
      <a href="menu.html" class="btn">Volver al menú</a>
    </div>
  </main>

  <!-- ====== PIE DE PÁGINA ====== -->
  <footer>
    <p>© 2025 La Pesquera · Todos los derechos reservados</p>
  </footer>

  <!-- ====== MODAL DE PAGO ====== -->
  <div id="modalPago" class="modal-pago oculto">
    <div class="modal-contenido">
      <span id="cerrarPago" class="cerrar-pago">✕</span>

      <h2>💳 Selecciona un método de pago</h2>

      <div class="metodos">
        <button class="metodo" data-metodo="efectivo">Efectivo</button>
        <button class="metodo" data-metodo="nequi">nequi</button>
        <button class="metodo" data-metodo="tarjeta">Tarjeta</button>
      </div>

      <div id="opcionesPago" class="opciones"></div>

    </div>
  </div>

  <!-- ⚙️ Scripts -->
  <script defer src="../js/script.js"></script>
  <script defer src="../js/carrito.js"></script>
</body>
</html>