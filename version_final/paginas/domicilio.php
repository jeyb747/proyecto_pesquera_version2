<?php require_once("../php/configuracion/auth.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Domicilio | La Pesquera</title>

  <!-- GOOGLE FONT -->
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&display=swap" rel="stylesheet">

  <!-- ✅ BOOTSTRAP CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- CSS -->
  <link rel="stylesheet" href="../css/inicio.css">
  <link rel="stylesheet" href="../css/domicilio.css?v=2">

  <!-- JS -->
  <script defer src="../js/domicilio.js"></script>

</head>

<body>

<!-- ===== NAVBAR ===== -->
<?php include("../includes/navbar.php"); ?>

<!-- ===================================================== -->
<!-- CONTENIDO -->
<!-- ===================================================== -->

<main class="container py-5">

  <div class="row justify-content-center">

    <div class="col-lg-8 col-md-10">

      <!-- ===== CARD PRINCIPAL ===== -->
      <div class="card shadow-lg border-0 domicilio-card">

        <div class="card-body p-4 p-md-5">

          <!-- TITULO -->
          <div class="text-center mb-5">

            <h1 class="titulo-domicilio">
              🚚 Confirmar domicilio
            </h1>

            <p class="text-muted">
              Finaliza tu pedido y recibe lo mejor del mar
            </p>

          </div>

          <!-- ===================================================== -->
          <!-- RESUMEN -->
          <!-- ===================================================== -->

          <div class="resumen-box mb-4">

            <div class="row text-center">

              <div class="col-md-6 mb-3 mb-md-0">

                <div class="resumen-item">

                  <h5>Productos</h5>

                  <span id="cantidadProductos">
                    0
                  </span>

                </div>

              </div>

              <div class="col-md-6">

                <div class="resumen-item">

                  <h5>Total</h5>

                  <span id="totalCarrito">
                    $0
                  </span>

                </div>

              </div>

            </div>

          </div>

          <!-- ===================================================== -->
          <!-- PRODUCTOS -->
          <!-- ===================================================== -->

          <div
            id="productosCarrito"
            class="productos-box mb-4">
          </div>

          <!-- ===================================================== -->
          <!-- FORM -->
          <!-- ===================================================== -->

          <form
            id="formDomicilio"
            action="/version_final/php/controlador/domicilios/crear.php"
            method="POST"
            enctype="multipart/form-data">

            <!-- HIDDEN -->
            <input type="hidden" name="productos" id="productosInput">
            <input type="hidden" name="total" id="totalInput">

            <!-- NOMBRE -->
            <div class="mb-3">

              <input
                type="text"
                name="nombre"
                class="form-control form-control-lg"
                placeholder="Nombre completo"
                required>

            </div>

            <!-- DIRECCION -->
            <div class="mb-3">

              <input
                type="text"
                name="direccion"
                class="form-control form-control-lg"
                placeholder="Dirección"
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

            <!-- PAGO -->
            <div class="mb-3">

              <select
                name="pago"
                id="pago"
                class="form-select form-select-lg"
                required>

                <option value="">
                  Método de pago
                </option>

                <option value="efectivo">
                  Efectivo
                </option>

                <option value="nequi">
                  Nequi
                </option>

              </select>

            </div>

            <!-- COMPROBANTE -->
            <div
              id="campoComprobante"
              class="mb-3 d-none">

              <label class="form-label fw-bold">
                Subir comprobante
              </label>

              <input
                type="file"
                id="comprobante"
                class="form-control">

            </div>

            <!-- OBSERVACIONES -->
            <div class="mb-4">

              <textarea
                name="observaciones"
                class="form-control"
                rows="4"
                placeholder="Observaciones"></textarea>

            </div>

            <!-- BOTON -->
            <button
              type="submit"
              class="btn btn-warning w-100 py-3 fw-bold">

              🚚 Hacer domicilio

            </button>

          </form>

        </div>

      </div>

    </div>

  </div>

</main>

<!-- ===================================================== -->
<!-- FOOTER -->
<!-- ===================================================== -->

<footer class="bg-dark text-white text-center py-3 mt-5">

  <p class="m-0">
    © 2025 La Pesquera · Todos los derechos reservados
  </p>

</footer>

<!-- ✅ BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>