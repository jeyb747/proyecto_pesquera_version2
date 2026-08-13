<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>Registro | La Pesquera</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../css/inicio.css">
  <link rel="stylesheet" href="../css/registro.css?v=20260812-1">

</head>

<body>

<?php include(__DIR__ . "/../includes/navbar.php"); ?>

<main class="container-fluid registro-container">

  <div class="row min-vh-100 justify-content-center align-items-center">

    <div class="col-11 col-sm-10 col-md-8 col-lg-5">

      <div class="registro-card card shadow-lg border-0 p-4 p-md-5">

        <div class="registro-brand text-center">

          <img 
            src="../imagenes/logo.png"
            class="logo-img img-fluid mb-3"
            alt="Logo"
            style="max-width: 130px;"
          >

        </div>

        <div class="text-center mb-4">

          <h2 class="fw-bold">
            Crear Cuenta
          </h2>

          <p class="sub text-muted">
            Regístrate para poder reservar, pedir domicilio
            y usar el carrito de forma completa.
          </p>

        </div>

        <form 
          id="formRegistro"
          class="registro-form"
          action="../php/controlador/registro.php"
          method="POST"
        >

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

          <div class="mb-3">

            <label class="form-label">
              Tipo de documento
            </label>

            <div class="input-group">

              <span class="input-group-text">
                🪪
              </span>

              <select 
                name="tipo_documento" 
                class="form-select" 
                required
              >
                <option value="" disabled selected>Selecciona tu tipo de documento</option>
                <option value="1">Cédula de Ciudadanía (C.C.)</option>
                <option value="2">Cédula de Extranjería (C.E.)</option>
                <option value="3">Pasaporte</option>
                <option value="4">NIT</option>
              </select>

            </div>

          </div>

          <div class="mb-3">

            <label class="form-label">
              Número de documento
            </label>

            <div class="input-group">

              <span class="input-group-text">
                🔢
              </span>

              <input 
                type="text"
                name="numero_documento"
                class="form-control"
                placeholder="Ingresa tu número de documento"
                required
                pattern="[a-zA-Z0-9]{5,15}"
              >

            </div>

          </div>

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
                class="btn password-toggle"
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

          <button 
            type="submit"
            class="btn btn-warning w-100 py-2 fw-bold"
          >
            Crear cuenta ➕
          </button>

        </form>

        <p class="login-link text-center mt-4 mb-0">

          ¿Ya tienes cuenta?

          <a href="login.php" class="fw-bold text-decoration-none">
            Inicia sesión
          </a>

        </p>

        <?php if (isset($_GET['error'])): ?>

          <div class="alert alert-danger mt-4 text-center">

            ❌ Este correo ya está registrado

          </div>

        <?php endif; ?>

      </div>

    </div>

  </div>

</main>

<footer class="bg-dark text-white text-center py-3">

  <div class="container">

    <p class="mb-0">
      © 2025 La Pesquera · Todos los derechos reservados
    </p>

  </div>

</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>
function togglePassword() {

  const input = document.getElementById("password");
  const button = document.querySelector(".password-toggle");
  const passwordIsVisible = input.type === "password";

  input.type = passwordIsVisible ? "text" : "password";
  button.classList.toggle("is-visible", passwordIsVisible);
  button.setAttribute("aria-pressed", String(passwordIsVisible));
  button.setAttribute(
    "aria-label",
    passwordIsVisible ? "Ocultar contraseña" : "Mostrar contraseña"
  );

}
</script>

</body>
</html>
