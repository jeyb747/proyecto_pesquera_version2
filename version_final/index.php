<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>La Pesquera</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Fuente -->
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&display=swap" rel="stylesheet">

  <!-- CSS -->
  <link rel="stylesheet" href="css/inicio.css">

</head>

<body>

<!-- NAVBAR -->
<?php include(__DIR__ . "/includes/navbar.php"); ?>

<!-- ===== HERO ===== -->
<section class="hero position-relative">

  <img 
    src="imagenes/fondo.png"
    alt="Plato de pescado"
    class="img-fluid w-100"
  >

  <div class="hero-content position-absolute top-50 start-50 translate-middle text-center text-white">

    <div class="container">

      <div class="row justify-content-center">

        <div class="col-lg-8">

          <h1 class="display-4 fw-bold mb-3">
            Bienvenido a La Pesquera
          </h1>

          <p class="lead mb-4">
            Un lugar donde el mar y la buena mesa se encuentran.
          </p>

          <a href="paginas/reservas.php" class="btn btn-warning btn-lg px-5">
            Reserva
          </a>

        </div>

      </div>

    </div>

  </div>

</section>

<!-- ===== SOBRE NOSOTROS ===== -->
<section class="about py-5">

  <div class="container">

    <div class="row justify-content-center">

      <div class="col-lg-8 text-center">

        <h2 class="mb-4">
          Sobre Nosotros
        </h2>

        <p class="fs-5 text-muted">
          En La Pesquera ofrecemos los sabores más frescos del mar,
          preparados con recetas tradicionales y un toque moderno.
          Ven a disfrutar una experiencia única.
        </p>

      </div>

    </div>

  </div>

</section>

<!-- ===== FOOTER ===== -->
<footer class="bg-dark text-white text-center py-3">

  <div class="container">

    <p class="mb-0">
      © 2026 La Pesquera - Todos los derechos reservados
    </p>

  </div>

</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<!-- Scripts -->
<script defer src="js/script.js"></script>

</body>
</html>