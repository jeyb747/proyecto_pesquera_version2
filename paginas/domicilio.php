<?php require_once(__DIR__ . "/../php/configuracion/auth.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domicilio | La Pesquera</title>

    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="../css/inicio.css">
    <link rel="stylesheet" href="../css/domicilio.css?v=1012">

    <script defer src="../js/domicilio.js"></script>
</head>
<body class="pesquera-textured-theme">

<?php include(__DIR__ . "/../includes/navbar.php"); ?>

<main class="container py-5 min-vh-100 d-flex align-items-center justify-content-center">
    <div class="row justify-content-center w-100">
        <div class="col-xl-6 col-lg-8 col-md-10 col-sm-12">

            <div class="card shadow-lg border-0 domicilio-card">
                <div class="card-body p-4 p-md-5">

                    <div class="text-center mb-4">
                        <h1 class="text-azul-pesquera text-uppercase fw-bold h3 font-cinzel mb-2">
                            Confirmar Domicilio
                        </h1>
                        <p class="text-gris-pesquera text-uppercase tracking-wider extra-small mb-3">
                            Finaliza tu pedido y recibe lo mejor del mar
                        </p>
                        <div class="linea-decorativa mx-auto"></div>
                    </div>

                    <div class="resumen-box mb-4">
                        <div class="row text-center g-3">
                            <div class="col-6">
                                <div class="resumen-item">
                                    <h5 class="text-uppercase extra-small tracking-wider mb-1">Productos</h5>
                                    <span id="cantidadProductos" class="font-numeric">0</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="resumen-item">
                                    <h5 class="text-uppercase extra-small tracking-wider mb-1">Total</h5>
                                    <span id="totalCarrito" class="font-numeric">$0</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="productosCarrito" class="productos-box mb-4">
                        </div>

                    <form id="formDomicilio" action="/php/controlador/domicilios/crear.php" method="POST" enctype="multipart/form-data">
                        
                        <input type="hidden" name="productos" id="productosInput">
                        <input type="hidden" name="total" id="totalInput">

                        <div class="mb-3">
                            <label class="text-gris-pesquera small fw-semibold text-uppercase mb-1 label-spacing">Nombre Completo</label>
                            <div class="wrapper-input-pesquera">
                                <span class="icono-izq"><i class="bi bi-person"></i></span>
                                <input type="text" name="nombre" class="form-control input-pesquera" placeholder="INGRESA TU NOMBRE" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="text-gris-pesquera small fw-semibold text-uppercase mb-1 label-spacing">Dirección de Entrega</label>
                            <div class="wrapper-input-pesquera">
                                <span class="icono-izq"><i class="bi bi-geo-alt"></i></span>
                                <input type="text" name="direccion" class="form-control input-pesquera" placeholder="EJ: CALLE 10 #25-45" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="text-gris-pesquera small fw-semibold text-uppercase mb-1 label-spacing">Teléfono Celular</label>
                            <div class="wrapper-input-pesquera">
                                <span class="icono-izq"><i class="bi bi-telephone"></i></span>
                                <input type="tel" name="telefono" class="form-control input-pesquera" placeholder="EJ: 3001234567" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="text-gris-pesquera small fw-semibold text-uppercase mb-1 label-spacing">Método de Pago</label>
                            <div class="wrapper-input-pesquera">
                                <span class="icono-izq"><i class="bi bi-credit-card"></i></span>
                                <select name="pago" id="pago" class="form-select input-pesquera" required>
                                    <option value="" disabled selected>SELECCIONA UNA OPCIÓN</option>
                                    <option value="efectivo">Efectivo al recibir</option>
                                    <option value="nequi">Transferencia Nequi</option>
                                </select>
                            </div>
                        </div>

                        <div id="campoComprobante" class="mb-3 d-none">
                            <label class="text-gris-pesquera small fw-semibold text-uppercase mb-1 label-spacing">Adjuntar Comprobante de Pago</label>
                            <input type="file" id="comprobante" class="form-control input-pesquera pt-2">
                        </div>

                        <div class="mb-4">
                            <label class="text-gris-pesquera small fw-semibold text-uppercase mb-1 label-spacing">Indicaciones o Notas Adicionales</label>
                            <textarea name="observaciones" class="form-control input-pesquera pt-3" rows="3" placeholder="EJ: APTO 402, TIMBRAR DURO..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-amarillo-action w-100 py-2.5 text-uppercase fw-bold">
                            Confirmar Pedido
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>
</main>

<footer class="bg-gris-pesquera text-white text-center py-3">
    <p class="m-0 extra-small tracking-wider text-uppercase text-white-50">
        © 2026 La Pesquera · Todos los derechos reservados
    </p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>