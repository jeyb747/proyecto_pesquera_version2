<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>Registro | La Pesquera</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Fuente -->
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&display=swap" rel="stylesheet">

  <!-- CSS -->
  <link rel="stylesheet" href="../css/inicio.css">
  <link rel="stylesheet" href="../css/registro.css">

</head>

<body>

<!-- NAVBAR -->
<?php include("../includes/navbar.php"); ?>

<!-- CONTENEDOR -->
<main class="container-fluid registro-container">

  <div class="row min-vh-100 justify-content-center align-items-center">

    <div class="col-11 col-sm-10 col-md-8 col-lg-5">

      <!-- CARD -->
      <div class="registro-card card shadow-lg border-0 p-4 p-md-5">

        <!-- LOGO -->
        <div class="text-center">

          <img 
            src="../imagenes/logo.png"
            class="logo-img img-fluid mb-3"
            alt="Logo"
            style="max-width: 130px;"
          >

        </div>

        <!-- TITULO -->
        <div class="text-center mb-4">

          <h2 class="fw-bold">
            Crear Cuenta
          </h2>

          <p class="sub text-muted">
            Regístrate para poder reservar, pedir domicilio
            y usar el carrito de forma completa.
          </p>

        </div>

        <!-- FORM -->
        <form 
          id="formRegistro"
          class="registro-form"
          action="../php/controlador/registro.php"
          method="POST"
        >

          <!-- NOMBRE -->
          <div class="mb-3">

            <label class="form-label">
              Nombre completo
            </label>

            <div class="input-group">

              <span class="input-group-text">
                👤
              </span>

              <input 
                type="text"
                name="nombre"
                class="form-control"
                placeholder="Ingresa tu nombre completo"
                required
              >

            </div>

          </div>

          <!-- CORREO -->
          <div class="mb-3">

            <label class="form-label">
              Correo electrónico
            </label>

            <div class="input-group">

              <span class="input-group-text">
                ✉️
              </span>

              <input 
                type="email"
                name="correo"
                class="form-control"
                placeholder="ejemplo@correo.com"
                required
              >

            </div>

          </div>

          <!-- TELEFONO -->
          <div class="mb-3">

            <label class="form-label">
              Teléfono
            </label>

            <div class="input-group">

              <span class="input-group-text">
                📞
              </span>

              <input 
                type="tel"
                name="telefono"
                class="form-control"
                placeholder="3001234567"
                required
                pattern="[0-9]{7,10}"
              >

            </div>

          </div>

          <!-- PASSWORD -->
          <div class="mb-4">

            <label class="form-label">
              Contraseña
            </label>

            <div class="input-group">

              <span class="input-group-text">
                🔒
              </span>

              <input 
                type="password"
                name="password"
                id="password"
                class="form-control"
                placeholder="Crea una contraseña segura"
                required
              >

              <button 
                type="button"
                class="btn btn-outline-secondary"
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
            Crear cuenta ➕
          </button>

        </form>

        <!-- LOGIN -->
        <p class="login-link text-center mt-4 mb-0">

          ¿Ya tienes cuenta?

          <a href="login.php" class="fw-bold text-decoration-none">
            Inicia sesión
          </a>

        </p>

        <!-- ERROR -->
        <?php if (isset($_GET['error'])): ?>

          <div class="alert alert-danger mt-4 text-center">

            ❌ Este correo ya está registrado

          </div>

        <?php endif; ?>

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

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<!-- JS -->
<script>
function togglePassword() {

  const input = document.getElementById("password");

  input.type =
    input.type === "password"
    ? "text"
    : "password";

}
</script>

</body>
</html>