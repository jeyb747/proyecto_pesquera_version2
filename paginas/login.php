<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Iniciar Sesión | La Pesquera</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Fuente -->
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&display=swap" rel="stylesheet">

  <!-- CSS -->
  <link rel="stylesheet" href="../css/inicio.css">
  <link rel="stylesheet" href="../css/login.css">
  <link rel="stylesheet" href="../css/alerts.css">

</head>

<body>
<?php require_once(__DIR__ . '/../includes/flash.php'); flash_render(); ?>

<!-- NAVBAR -->
<?php include(__DIR__ . "/../includes/navbar.php"); ?>

<main class="container-fluid p-0">

  <div class="row g-0 min-vh-100">

    <!-- IZQUIERDA -->
    <div class="col-lg-6 d-none d-lg-block login-left position-relative">

      <div class="overlay d-flex flex-column justify-content-center align-items-center text-center text-white h-100">

        <div class="px-5">

          <h1 class="display-5 fw-bold mb-3">
            Disfruta lo mejor del mar
          </h1>

          <p class="lead">
            Pedidos rápidos, frescos y sin complicaciones.
          </p>

        </div>

      </div>

    </div>

    <!-- DERECHA -->
    <div class="col-lg-6 d-flex justify-content-center align-items-center bg-light">

      <div class="login-box card shadow-lg border-0 p-4 p-md-5">

        <!-- LOGO -->
        <div class="text-center mb-4">

          <img 
            src="../imagenes/logo.png"
            class="login-logo img-fluid"
            alt="Logo"
            style="max-width: 120px;"
          >

        </div>

        <div class="text-center mb-4">

          <h2 class="fw-bold">
            Bienvenido
          </h2>

          <p class="text-muted">
            Inicia sesión en La Pesquera
          </p>

        </div>

        <!-- FORM -->
        <form 
          id="formLogin"
          class="login-form"
          action="/php/controlador/controlador.php"
          method="POST"
        >

          <!-- CORREO -->
          <div class="mb-3">

            <label class="form-label">
              Correo electrónico
            </label>

            <input 
              type="email"
              name="correo"
              id="correo"
              class="form-control"
              placeholder="Ingresa tu correo"
              required
            >

          </div>

          <!-- PASSWORD -->
          <div class="mb-4">

            <label class="form-label">
              Contraseña
            </label>

            <div class="input-group">

              <input 
                type="password"
                name="password"
                id="password"
                class="form-control"
                placeholder="Ingresa tu contraseña"
                required
              >

              <button 
                class="btn btn-outline-secondary"
                type="button"
                onclick="togglePassword()"
              >
                👁️
              </button>

            </div>

          </div>

          <!-- BOTON -->
          <button 
            type="submit"
            class="btn btn-warning w-100 py-2 fw-bold"
          >
            Ingresar
          </button>

        </form>

        <p class="text-center mt-3 mb-0"><a href="olvido_password.php" class="text-decoration-none">¿Olvidaste tu contraseña?</a></p>

        <!-- REGISTRO -->
        <p class="register-link text-center mt-4 mb-0">

          ¿No tienes cuenta?

          <a href="registro.php" class="text-decoration-none fw-bold">
            Crea una aquí
          </a>

        </p>

        <!-- ERROR -->
        <div 
          id="mensajeError"
          class="alert alert-danger mt-3 d-none"
        >
        </div>

      </div>

    </div>

  </div>

</main>

<!-- FOOTER -->
<footer class="bg-dark text-white text-center py-3">

  <div class="container">

    <p class="mb-0">
      © 2025 La Pesquera · Todos los derechos reservados
    </p>

  </div>

</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<!-- JS -->
<script src="../js/login.js"></script>

</body>
</html>
