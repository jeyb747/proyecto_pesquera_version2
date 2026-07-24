<?php require_once(__DIR__ . "/../php/configuracion/auth.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas | La Pesquera</title>

    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="../css/inicio.css">
    <link rel="stylesheet" href="../css/reservas.css?v=1005">
</head>
<body class="pesquera-textured-theme">

<?php include(__DIR__ . "/../includes/navbar.php"); ?>

<main class="container py-5 min-vh-100 d-flex align-items-center justify-content-center">
    <div class="row justify-content-center w-100">
        <div class="col-xl-5 col-lg-6 col-md-8 col-sm-12">

            <div class="card reserva-card border-0">
                
                <div class="text-center mt-4 mb-2 card-badge-container">
                    <span class="badge-nautico-icon">
                        <i class="bi bi-compass"></i>
                    </span>
                </div>

                <div class="card-body px-4 px-md-5 pb-5 pt-2">

                    <div class="text-center mb-4">
                        <h1 class="text-azul-pesquera text-uppercase fw-bold h3 font-cinzel mb-2">
                            Reserva tu mesa
                        </h1>
                        <p class="text-gris-pesquera text-uppercase tracking-wider extra-small mb-3">
                            Disfruta de la mejor cocina de mar
                        </p>
                        <div class="linea-decorativa mx-auto"></div>
                    </div>

                    <div class="progress mb-4">
                        <div class="progress-bar bg-amarillo-pesquera" id="progressBar" style="width:25%"></div>
                    </div>

                    <div class="step active text-center" id="step1">
                        <h2 class="text-azul-pesquera h6 mb-4 text-uppercase fw-semibold">¿Cuántos comensales asistirán?</h2>
                        
                        <div class="d-flex justify-content-center align-items-center gap-4 mb-4">
                            <button type="button" class="btn btn-contador-pesquera" onclick="cambiarPersonas(-1)">-</button>
                            <span id="numPersonas" class="display-5 fw-bold text-gris-pesquera font-numeric">2</span>
                            <button type="button" class="btn btn-contador-pesquera" onclick="cambiarPersonas(1)">+</button>
                        </div>

                        <button type="button" onclick="nextStep(2)" class="btn btn-amarillo-action w-100 py-2.5 text-uppercase fw-bold">
                            Continuar
                        </button>
                    </div>

                    <div class="step d-none text-center" id="step2">
                        <h2 class="text-azul-pesquera h6 mb-4 text-uppercase fw-semibold">Selecciona la fecha</h2>
                        <div class="mb-4">
                            <input type="date" id="fecha" class="form-control input-pesquera text-center fw-medium">
                        </div>
                        <button type="button" onclick="validarFecha()" class="btn btn-amarillo-action w-100 py-2.5 text-uppercase fw-bold">
                            Continuar
                        </button>
                    </div>

                    <div class="step d-none text-center" id="step3">
                        <h2 class="text-azul-pesquera h6 mb-4 text-uppercase fw-semibold">Selecciona el horario</h2>
                        <div class="row g-2 mb-4 horas" id="contenedorHoras">
                            </div>
                        <button type="button" onclick="validarHora()" class="btn btn-amarillo-action w-100 py-2.5 text-uppercase fw-bold">
                            Continuar
                        </button>
                    </div>

                    <div class="step d-none" id="step4">
                        <h2 class="text-azul-pesquera h6 text-center mb-4 text-uppercase fw-semibold">Datos de contacto</h2>

                        <form id="formReserva">
                            <input type="hidden" name="personas" id="inputPersonas">
                            <input type="hidden" name="fecha" id="inputFecha">
                            <input type="hidden" name="hora" id="inputHora">

                            <div class="mb-3">
                                <label class="text-gris-pesquera small fw-semibold text-uppercase mb-1 label-spacing">Nombre Completo</label>
                                <div class="wrapper-input-pesquera">
                                    <span class="icono-izq"><i class="bi bi-person"></i></span>
                                    <input type="text" name="nombre" class="form-control input-pesquera" placeholder="INGRESA TU NOMBRE" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="text-gris-pesquera small fw-semibold text-uppercase mb-1 label-spacing">Teléfono</label>
                                <div class="wrapper-input-pesquera">
                                    <span class="icono-izq"><i class="bi bi-telephone"></i></span>
                                    <input type="tel" name="telefono" class="form-control input-pesquera" placeholder="EJ: 3001234567" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="text-gris-pesquera small fw-semibold text-uppercase mb-1 label-spacing">Observaciones</label>
                                <textarea name="observaciones" class="form-control input-pesquera pt-3" rows="3" placeholder="¿ALERGIAS O CELEBRACIONES?"></textarea>
                            </div>

                            <button type="submit" class="btn btn-azul-action w-100 py-2.5 fw-bold text-uppercase">
                                Confirmar Reserva
                            </button>
                        </form>

                        <div id="mensajeExito" class="alert alert-success mt-4 d-none text-center border-0 small fw-medium">
                            ✅ Reserva confirmada con éxito.
                        </div>

                        <a id="btnWhatsapp" target="_blank" class="btn btn-success w-100 mt-3 py-2.5 fw-bold text-uppercase small">
                            <i class="bi bi-whatsapp me-1"></i> Enviar Comprobante
                        </a>
                    </div>

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
<script src="../js/reservas.js"></script>

</body>
</html>