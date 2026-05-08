<?php require_once("../php/configuracion/auth.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Reservas | La Pesquera</title>

<!-- GOOGLE FONT -->
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&display=swap" rel="stylesheet">

<!-- ✅ BOOTSTRAP CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- CSS -->
<link rel="stylesheet" href="../css/inicio.css">
<link rel="stylesheet" href="../css/reservas.css?v=2">

<!-- JS -->
<script defer src="../js/reservas.js"></script>

</head>

<body>

<!-- ===== NAVBAR ===== -->
<?php include("../includes/navbar.php"); ?>

<!-- ===== CONTENIDO ===== -->
<main class="container py-5">

  <div class="row justify-content-center">

    <div class="col-lg-7 col-md-10">

      <!-- ===== CARD PRINCIPAL ===== -->
      <div class="card reserva-card shadow-lg border-0">

        <div class="card-body p-4 p-md-5">

          <!-- TITULO -->
          <div class="text-center mb-4">
            <h1 class="fw-bold text-primary-custom">
              Reserva tu mesa
            </h1>

            <p class="text-muted">
              Vive una experiencia única en La Pesquera
            </p>
          </div>

          <!-- ===== PROGRESS ===== -->
          <div class="progress mb-5" style="height: 10px;">

            <div
              class="progress-bar bg-warning"
              id="progressBar"
              role="progressbar"
              style="width: 25%">
            </div>

          </div>

          <!-- ================================================= -->
          <!-- PASO 1 -->
          <!-- ================================================= -->
          <div class="step active text-center" id="step1">

            <h2 class="mb-4">
              ¿Cuántas personas?
            </h2>

            <!-- CONTADOR -->
            <div class="d-flex justify-content-center align-items-center gap-4 mb-4">

              <button
                class="btn btn-warning rounded-circle contador-btn"
                onclick="cambiarPersonas(-1)">
                -
              </button>

              <span id="numPersonas" class="display-5 fw-bold">
                2
              </span>

              <button
                class="btn btn-warning rounded-circle contador-btn"
                onclick="cambiarPersonas(1)">
                +
              </button>

            </div>

            <!-- BOTON -->
            <button
              onclick="nextStep(2)"
              class="btn btn-warning px-5 py-2 fw-bold">
              Continuar
            </button>

          </div>

          <!-- ================================================= -->
          <!-- PASO 2 -->
          <!-- ================================================= -->
          <div class="step d-none text-center" id="step2">

            <h2 class="mb-4">
              Selecciona la fecha
            </h2>

            <div class="mb-4">

              <input
                type="date"
                id="fecha"
                class="form-control form-control-lg">

            </div>

            <button
              onclick="nextStep(3)"
              class="btn btn-warning px-5 py-2 fw-bold">
              Continuar
            </button>

          </div>

          <!-- ================================================= -->
          <!-- PASO 3 -->
          <!-- ================================================= -->
          <div class="step d-none text-center" id="step3">

            <h2 class="mb-4">
              Selecciona la hora
            </h2>

            <!-- HORAS -->
            <div class="row g-3 mb-4">

              <div class="col-6 col-md-4">
                <button class="btn btn-outline-warning w-100 hora-btn"
                  onclick="seleccionarHora(this)">
                  12:00 pm
                </button>
              </div>

              <div class="col-6 col-md-4">
                <button class="btn btn-outline-warning w-100 hora-btn"
                  onclick="seleccionarHora(this)">
                  1:00 pm
                </button>
              </div>

              <div class="col-6 col-md-4">
                <button class="btn btn-outline-warning w-100 hora-btn"
                  onclick="seleccionarHora(this)">
                  2:00 pm
                </button>
              </div>

              <div class="col-6 col-md-4">
                <button class="btn btn-outline-warning w-100 hora-btn"
                  onclick="seleccionarHora(this)">
                  3:00 pm
                </button>
              </div>

              <div class="col-6 col-md-4">
                <button class="btn btn-outline-warning w-100 hora-btn"
                  onclick="seleccionarHora(this)">
                  4:00 pm
                </button>
              </div>

            </div>

            <button
              onclick="nextStep(4)"
              class="btn btn-warning px-5 py-2 fw-bold">
              Continuar
            </button>

          </div>

          <!-- ================================================= -->
          <!-- PASO 4 -->
          <!-- ================================================= -->
          <div class="step d-none" id="step4">

            <h2 class="text-center mb-4">
              Detalles de la reserva
            </h2>

            <!-- FORM -->
            <form id="formReserva">

              <!-- HIDDEN -->
              <input type="hidden" name="personas" id="inputPersonas">
              <input type="hidden" name="fecha" id="inputFecha">
              <input type="hidden" name="hora" id="inputHora">

              <!-- NOMBRE -->
              <div class="mb-3">

                <input
                  type="text"
                  name="nombre"
                  class="form-control form-control-lg"
                  placeholder="Nombre"
                  required>

              </div>

              <!-- TELEFONO -->
              <div class="mb-3">

                <input
                  type="tel"
                  name="telefono"
                  class="form-control form-control-lg"
                  placeholder="Teléfono"
                  required>

              </div>

              <!-- OBSERVACIONES -->
              <div class="mb-4">

                <textarea
                  name="observaciones"
                  class="form-control"
                  rows="4"
                  placeholder="Comentarios"></textarea>

              </div>

              <!-- BOTON -->
              <button
                type="submit"
                class="btn btn-warning w-100 py-3 fw-bold">

                Confirmar reserva

              </button>

            </form>

            <!-- MENSAJE -->
            <div
              id="mensajeExito"
              class="alert alert-success mt-4 d-none">

              ✅ Reserva hecha correctamente

            </div>

            <!-- WHATSAPP -->
            <a
              id="btnWhatsapp"
              target="_blank"
              class="btn btn-success w-100 mt-3 py-3 fw-bold d-none">

              Enviar WhatsApp

            </a>

          </div>

        </div>

      </div>

    </div>

  </div>

</main>

<!-- ===== FOOTER ===== -->
<footer class="bg-dark text-white text-center py-3 mt-5">
  <p class="m-0">
    © 2025 La Pesquera · Todos los derechos reservados
  </p>
</footer>

<!-- ✅ BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>