<?php require_once(__DIR__ . "/../php/configuracion/auth.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Carrito | La Pesquera</title>

  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <link rel="stylesheet" href="../css/inicio.css">
  <link rel="stylesheet" href="../css/carro.css?v=1013">

</head>

<body class="pesquera-textured-theme">

<?php include(__DIR__ . "/../includes/navbar.php"); ?>

<main class="container py-5 min-vh-100 d-flex align-items-center justify-content-center">
    <div class="row justify-content-center w-100">
        <div class="col-xl-8 col-lg-10 col-md-12">

          <div class="text-center mb-4">
            <h1 class="text-azul-pesquera text-uppercase fw-bold h3 font-cinzel mb-2">
              <i class="bi bi-cart3 me-2"></i>Carrito de Compras
            </h1>
            <p class="text-gris-pesquera text-uppercase tracking-wider extra-small mb-3">
              Revisa tus productos antes de finalizar tu pedido
            </p>
            <div class="linea-decorativa mx-auto"></div>
          </div>

          <div class="card shadow-lg border-0 carrito-card">
            <div class="card-body p-4 p-lg-5">

              <div id="carrito-container" class="carrito-container">
                </div>

              <div class="total-box mt-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                  <div>
                    <h4 class="mb-1 fw-bold text-uppercase small tracking-wider text-gris-pesquera">
                      Total del pedido
                    </h4>
                    <p class="text-muted extra-small text-uppercase tracking-wider mb-0">
                      Incluye todos los productos agregados
                    </p>
                  </div>
                  <div id="total" class="precio-total font-numeric">
                    $0
                  </div>
                </div>
              </div>

              <div class="row mt-4 g-3">
                <div class="col-md-4 order-2 order-md-1">
                  <button class="btn btn-outline-danger-formal w-100 py-2.5 text-uppercase fw-bold" id="vaciarCarrito">
                    <i class="bi bi-trash3 me-2"></i>Vaciar carrito
                  </button>
                </div>
                <div class="col-md-4 order-3 order-md-2">
                  <a href="menu.php" class="btn btn-outline-secondary-formal w-100 py-2.5 text-uppercase fw-bold">
                    <i class="bi bi-arrow-left me-2"></i>Volver al menú
                  </a>
                </div>
                <div class="col-md-4 order-1 order-md-3">
                  <button class="btn btn-amarillo-action w-100 py-2.5 text-uppercase fw-bold" id="btnPagar">
                    <i class="bi bi-credit-card me-2"></i>Pagar pedido
                  </button>
                </div>
              </div>

            </div>
          </div>

        </div>
    </div>
</main>

<div class="modal fade" id="modalPago" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg modal-premium">

      <div class="modal-header border-0 pb-0 pt-4 px-4 d-flex align-items-center justify-content-between">
        <h4 class="modal-title fw-bold text-azul-pesquera text-uppercase h5 font-cinzel m-0">
          <i class="bi bi-credit-card me-2 text-amarillo-pesquera"></i>Método de pago
        </h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-4">
        <p class="text-gris-pesquera text-uppercase tracking-wider extra-small mb-4">
          Selecciona cómo deseas pagar tu pedido corporativo.
        </p>

        <div class="row g-3">
          <div class="col-12">
            <button class="metodo btn btn-light-formal w-100 py-3 text-start px-4 text-uppercase fw-semibold" data-metodo="efectivo">
              <i class="bi bi-cash-coin me-3 text-azul-pesquera"></i>Efectivo al recibir
            </button>
          </div>

          <div class="col-12">
            <button class="metodo btn btn-light-formal w-100 py-3 text-start px-4 text-uppercase fw-semibold" data-metodo="nequi">
              <i class="bi bi-phone me-3 text-azul-pesquera"></i>Transferencia Nequi
            </button>
          </div>

          <div class="col-12">
            <button class="metodo btn btn-light-formal w-100 py-3 text-start px-4 text-uppercase fw-semibold" data-metodo="tarjeta">
              <i class="bi bi-credit-card-2-front me-3 text-azul-pesquera"></i>Tarjeta de Crédito / Débito
            </button>
          </div>
        </div>

        <div id="opcionesPago" class="mt-4"></div>
      </div>

    </div>
  </div>
</div>

<footer class="bg-gris-pesquera text-white text-center py-3">
  <p class="mb-0 extra-small tracking-wider text-uppercase text-white-50">
    © 2026 La Pesquera · Todos los derechos reservados
  </p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script defer src="../js/script.js"></script>
<script defer src="../js/carrito.js"></script>

</body>
</html>