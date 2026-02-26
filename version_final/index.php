<?php
// iniciar sesión por si luego quieres usar variables
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>La Pesquera | Inicio</title>

  <!-- CSS -->
  <link rel="stylesheet" href="css/style.css" />

  <!-- JS -->
  <script defer src="js/script.js"></script>
</head>

<body>
  <!-- ====== ENCABEZADO ====== -->
  <header class="navbar">
    <div class="container nav-content">
      <h1 class="logo">La Pesquera</h1>

      <button id="menu-toggle" class="menu-toggle" aria-label="Abrir menú">☰</button>

      <nav id="nav-menu" class="nav-menu">
        <a href="index.php" class="active">Inicio</a>
        <a href="paginas/login.php">Iniciar sesión</a>
        <a href="paginas/menu.php">Menú</a>
        <a href="paginas/domicilio.php">Domicilio</a>
        <a href="paginas/reservas.php">Reservas</a>
        <a href="paginas/contacto.php">Contacto</a>
        <a href="paginas/carrito.php" class="carrito-link">🛒 Carrito <span id="contador-carrito">0</span></a>
        <a href="paginas/historial.php">Historial</a>
        <?php if(isset($_SESSION['usuario'])): ?>
        <a href="#">👤 <?php echo $_SESSION['usuario']; ?></a>
        <a href="php/modelo/loghout.php">Cerrar sesión</a>
        <?php else: ?>
        <a href="paginas/login.php">Iniciar sesión</a>
      <?php endif; ?>
      </nav>
    </div>
  </header>

  <!-- ====== SECCIÓN PRINCIPAL ====== -->
  <main>
    <section class="hero">
      <div class="overlay"></div>

      <div class="hero-content">
        <h2>Bienvenido a La Pesquera</h2>
        <p>Un lugar donde el mar y la buena mesa se encuentran.</p>

        <a href="/version_final/paginas/reservas.php" class="btn">Hacer una reserva</a>
      </div>
    </section>

    <section class="about container">
      <h2>Sobre Nosotros</h2>
      <p>
        En <strong>La Pesquera</strong> ofrecemos los sabores más frescos del mar,
        preparados con recetas tradicionales y un toque moderno.
        Ven a disfrutar una experiencia gastronómica única frente al sabor del océano.
      </p>
    </section>
  </main>

  <!-- ====== PIE DE PÁGINA ====== -->
  <footer>
    <p>© 2025 La Pesquera · Todos los derechos reservados</p>
  </footer>

  <!-- Contador del carrito -->
  <script>
    const contador = document.getElementById("contador-carrito");
    const carrito = JSON.parse(localStorage.getItem("carrito")) || [];
    contador.textContent = carrito.length;
  </script>
</body>
</html>
