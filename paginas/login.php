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
  <link rel="stylesheet" href="../css/login.css?v=20260812-2">
  <link rel="stylesheet" href="../css/alerts.css">

</head>

<body>
<?php require_once(__DIR__ . '/../includes/flash.php'); flash_render(); ?>
<?php if (!empty($_SESSION['flash_auth'])): ?><div class="container pt-3"><div class="alert alert-warning alert-dismissible fade show" role="alert"><?= htmlspecialchars($_SESSION['flash_auth']); unset($_SESSION['flash_auth']); ?><button class="btn-close" data-bs-dismiss="alert"></button></div></div><?php endif; ?>

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
    <div class="col-lg-6 d-flex justify-content-center align-items-center login-right">

      <div class="login-box card shadow-lg border-0 p-4 p-md-5">

        <!-- LOGO -->
        <div class="login-brand text-center">

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
                class="btn password-toggle"
                type="button"
                onclick="togglePassword()"
                aria-label="Mostrar contraseña"
                aria-controls="password"
                aria-pressed="false"
              >
                <svg class="password-icon password-icon--hidden" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                  <path d="M3 3l18 18M10.6 10.7a2 2 0 0 0 2.7 2.7M9.9 5.2A10.6 10.6 0 0 1 12 5c5.2 0 8.7 4.4 9.7 6.1a1.8 1.8 0 0 1 0 1.8 15.2 15.2 0 0 1-3 3.6M6.2 6.2A15.5 15.5 0 0 0 2.3 11a1.8 1.8 0 0 0 0 1.8C3.3 14.6 6.8 19 12 19c.9 0 1.8-.1 2.6-.4" />
                </svg>
                <svg class="password-icon password-icon--visible" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                  <path d="M2.3 11.1a1.8 1.8 0 0 0 0 1.8C3.3 14.6 6.8 19 12 19s8.7-4.4 9.7-6.1a1.8 1.8 0 0 0 0-1.8C20.7 9.4 17.2 5 12 5S3.3 9.4 2.3 11.1Z" />
                  <circle cx="12" cy="12" r="3" />
                </svg>
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
