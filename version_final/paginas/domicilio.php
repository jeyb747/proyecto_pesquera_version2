<?php require_once("../php/configuracion/auth.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>La Pesquera | Domicilio</title>

  <!-- CSS -->
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="stylesheet" href="../css/domicilio.css" />

  <!-- JS -->
  <script defer src="../js/script.js"></script>
  <script defer src="../js/domicilio.js"></script>
</head>

<body>
  <!-- ====== Encabezado ====== -->
  <header class="navbar">
    <div class="container nav-content">
      <h1 class="logo">La Pesquera</h1>
      <button id="menu-toggle" class="menu-toggle" aria-label="Abrir menú">☰</button>

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

  <!-- ====== Contenido principal ====== -->
  <main class="container">
    <section class="domicilio-section">
      <h2>🚚 Solicitud de Domicilio</h2>
      <p>Revisa tu pedido antes de confirmar el domicilio.</p>

      <!-- 🛒 Resumen del carrito -->
      <div id="resumenCarrito" class="resumen-carrito oculto">
        <p><strong>Productos:</strong> <span id="cantidadProductos">0</span></p>
        <p><strong>Total:</strong> <span id="totalCarrito">$0</span></p>
      </div>

      <!-- 🛒 Lista de productos -->
      <div id="productosCarrito" class="productos-carrito vacio">
        <p>Cargando tus productos...</p>
      </div>

      <!-- 🧾 Formulario -->
      <form id="formDomicilio" class="form-domicilio">

        <div class="campo">
          <label for="nombre">Nombre completo:</label>
          <input type="text" id="nombre" name="nombre" required />
        </div>

        <div class="campo">
          <label for="direccion">Dirección:</label>
          <input type="text" id="direccion" name="direccion" required />
        </div>

        <div class="campo">
          <label for="telefono">Teléfono:</label>
          <input type="tel" id="telefono" name="telefono" required pattern="[0-9]{7,10}" />
        </div>

        <!-- MÉTODO DE PAGO -->
        <div class="campo">
          <label for="pago">Método de pago:</label>
          <select id="pago" name="pago" required>
            <option value="">Seleccione una opción</option>
            <option value="efectivo">Efectivo</option>
            <option value="tarjeta">Tarjeta</option>
            <option value="nequi">Nequi</option>
          </select>
        </div>

        <!-- CAMPO DEL COMPROBANTE (SOLO PARA NEQUI) -->
        <div class="campo oculto" id="campoComprobante">
          <label for="comprobante">Comprobante de pago (Nequi):</label>
          <input type="file" id="comprobante" accept="image/*">
          <p style="font-size:0.85rem; color:#555;">Sube el pantallazo del pago.</p>
        </div>

        <div class="campo">
          <label for="observaciones">Observaciones:</label>
          <textarea id="observaciones" 
                    name="observaciones" 
                    rows="3" 
                    placeholder="Instrucciones adicionales..."></textarea>
        </div>

        <button type="submit" class="btn-enviar">Confirmar pedido</button>
      </form>

      <!-- Mensaje éxito -->
      <div id="mensajeExito" class="mensaje-exito">
        ✅ Pedido enviado con éxito
      </div>
    </section>
  </main>

  <!-- ====== Pie de página ====== -->
  <footer>
    <p>© 2025 La Pesquera · Todos los derechos reservados</p>
  </footer>
</body>
</html>
