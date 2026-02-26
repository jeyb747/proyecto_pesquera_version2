<?php require_once("../php/configuracion/auth.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>La Pesquera | Menú</title>

  <!-- CSS -->
  <link rel="stylesheet" href="../css/style.css" />

  <!-- JS -->
  <script defer src="../js/script.js"></script>
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
    <section class="menu-section">
      <h2>Nuestro Menú</h2>
      <p>Del mar a tu mesa — descubre los mejores sabores de La Pesquera.</p>

      <!-- 🐟 PESCADOS Y CARNES -->
      <div class="menu-category">
        <h3>🐟 Pescados y Carnes</h3>

        <table class="menu-table">
          <tr data-plato="Trucha frita" data-precio="25000" data-img="../imagenes/trucha.jpg" data-desc="Deliciosa trucha frita con patacones y ensalada.">
            <td>Trucha frita</td><td>$25.000</td>
          </tr>

          <tr data-plato="Bagre en salsa" data-precio="25000" data-img="../imagenes/bagre.jpg" data-desc="Bagre en salsa criolla con arroz y ensalada.">
            <td>Bagre en salsa</td><td>$25.000</td>
          </tr>

          <tr data-plato="Bagre frito" data-precio="25000" data-img="../imagenes/bagre-frito.jpg" data-desc="Bagre frito acompañado de arroz y ensalada fresca.">
            <td>Bagre frito</td><td>$25.000</td>
          </tr>

          <tr data-plato="Filete de robalo" data-precio="25000" data-img="../imagenes/Filete_de_robalo.jpg" data-desc="Suave filete de robalo con papas y ensalada.">
            <td>Filete de robalo</td><td>$25.000</td>
          </tr>

          <tr data-plato="Mojarra frita ½" data-precio="25000" data-img="../imagenes/mojarra.jpg" data-desc="Mojarra frita mediana servida con patacones.">
            <td>Mojarra frita (½)</td><td>$25.000</td>
          </tr>

          <tr data-plato="Cazuela de mariscos" data-precio="35000" data-img="../imagenes/Cazuela_de_mariscos.jpg" data-desc="Mariscos en crema de coco servidos con arroz blanco.">
            <td>Cazuela de mariscos</td><td>$35.000</td>
          </tr>

          <tr data-plato="Carne asada" data-precio="25000" data-img="../imagenes/carne_asada.jpg" data-desc="Jugosa carne asada con arroz y yuca frita.">
            <td>Carne asada</td><td>$25.000</td>
          </tr>

          <tr data-plato="Pechuga a la plancha" data-precio="20000" data-img="../imagenes/pechuga.jpg" data-desc="Pechuga de pollo a la plancha, acompañada de arroz y ensalada.">
            <td>Pechuga a la plancha</td><td>$20.000</td>
          </tr>
        </table>
      </div>

      <!-- 🍲 SOPAS -->
      <div class="menu-category">
        <h3>🍲 Sopas (Sábados y Domingos)</h3>

        <table class="menu-table">
          <tr data-plato="Sopa de pescado" data-precio="13000" data-img="../imagenes/sopa-pescado.jpg" data-desc="Caldo de pescado tradicional con yuca y papa.">
            <td>Sopa de pescado</td><td>$13.000</td>
          </tr>

          <tr data-plato="Sopa de mondongo" data-precio="13000" data-img="../imagenes/mondongo.jpg" data-desc="Sopa espesa con callos, papa y especias típicas.">
            <td>Sopa de mondongo</td><td>$13.000</td>
          </tr>

          <tr data-plato="Sopa de menudencias" data-precio="13000" data-img="../imagenes/sopa de menudencias.jpg" data-desc="Sopa tradicional con menudencias y aliños caseros.">
            <td>Sopa de menudencias</td><td>$13.000</td>
          </tr>
        </table>
      </div>

      <!-- 🍚 PORCIONES -->
      <div class="menu-category">
        <h3>🍚 Porciones</h3>

        <table class="menu-table">
          <tr data-plato="Papa salada" data-precio="5000" data-img="../imagenes/papa.jpg" data-desc="Porción de papa salada recién cocida.">
            <td>Papa francesa</td><td>$5.000</td>
          </tr>

          <tr data-plato="Ensalada" data-precio="5000" data-img="../imagenes/ensalada.jpg" data-desc="Porción de ensalada fresca con tomate y lechuga.">
            <td>Ensalada</td><td>$5.000</td>
          </tr>

          <tr data-plato="Yuca frita" data-precio="5000" data-img="../imagenes/yuca.jpg" data-desc="Porción de yuca dorada y crujiente.">
            <td>Yuca frita</td><td>$5.000</td>
          </tr>

          <tr data-plato="Arroz" data-precio="3000" data-img="../imagenes/arroz.jpg" data-desc="Porción de arroz blanco recién hecho.">
            <td>Arroz</td><td>$3.000</td>
          </tr>
        </table>
      </div>

      <!-- 🍹 BEBIDAS -->
      <div class="menu-category">
        <h3>🍹 Bebidas</h3>

        <table class="menu-table">
          <tr data-plato="Gaseosa personal" data-precio="3000" data-img="../imagenes/Gaseosa personal.jpg" data-desc="Bebida gaseosa personal fría.">
            <td>Gaseosa personal</td><td>$3.000</td>
          </tr>

          <tr data-plato="Cerveza" data-precio="4000" data-img="../imagenes/cerveza.jpg" data-desc="Cerveza fría para acompañar tus comidas.">
            <td>Cerveza</td><td>$4.000</td>
          </tr>

          <tr data-plato="Jugo natural" data-precio="5000" data-img="../imagenes/jugo natural.jpg" data-desc="Jugo de frutas naturales al gusto.">
            <td>Jugo natural</td><td>$5.000</td>
          </tr>

          <tr data-plato="Limonada natural" data-precio="10000" data-img="../imagenes/limonada.jpg" data-desc="Limonada natural fría con hielo.">
            <td>Limonada natural</td><td>$10.000</td>
          </tr>
        </table>
      </div>

    </section>
  </main>

  <!-- ====== Modal ====== -->
  <div id="modalProducto" class="modal">
    <div class="modal-content">
      <span id="cerrarModal" class="cerrar">✕</span>

      <img id="modalImg" src="" alt="Producto">
      <h3 id="modalTitulo"></h3>
      <p id="modalDescripcion"></p>
      <span id="modalPrecio" class="price"></span>

      <button id="btnCarrito" class="btn">Agregar al carrito</button>
    </div>
  </div>

  <!-- ====== Footer ====== -->
  <footer>
    <p>© 2025 La Pesquera · Todos los derechos reservados</p>
  </footer>

  <!-- Script del menú -->
  <script defer src="../js/menu-interactivo.js"></script>

</body>
</html>