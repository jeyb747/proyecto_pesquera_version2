<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/version_final/php/modelo/conexion.php");

/* ======================================================
   SEGURIDAD
====================================================== */

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {

    header("Location: /version_final/index.php");
    exit();

}

/* ======================================================
   ESTADISTICAS
====================================================== */

$pedidos = mysqli_fetch_assoc(
    mysqli_query(
        $conexion,
        "SELECT COUNT(*) AS total FROM pedidos"
    )
);

$domicilios = mysqli_fetch_assoc(
    mysqli_query(
        $conexion,
        "SELECT COUNT(*) AS total FROM domicilios"
    )
);

$pendientes = mysqli_fetch_assoc(
    mysqli_query(
        $conexion,
        "SELECT COUNT(*) AS total
         FROM domicilios
         WHERE estado='pendiente'"
    )
);

$reservas = mysqli_fetch_assoc(
    mysqli_query(
        $conexion,
        "SELECT COUNT(*) AS total FROM reservas"
    )
);

$res_hoy = mysqli_fetch_assoc(
    mysqli_query(
        $conexion,
        "SELECT COUNT(*) AS total
         FROM reservas
         WHERE fecha = CURDATE()"
    )
);

/* ======================================================
   VENTAS
====================================================== */

$ventas = mysqli_fetch_assoc(
    mysqli_query(
        $conexion,
        "SELECT SUM(total) AS total
         FROM pedidos
         WHERE DATE(fecha)=CURDATE()"
    )
);

$totalVentas = $ventas['total'] ?? 0;

/* ======================================================
   ULTIMOS PEDIDOS
====================================================== */

$ultimos_pedidos = mysqli_query(
    $conexion,
    "SELECT id, total
     FROM pedidos
     ORDER BY id DESC
     LIMIT 5"
);

/* ======================================================
   ULTIMAS RESERVAS
====================================================== */

$ultimas_reservas = mysqli_query(
    $conexion,
    "SELECT nombre, fecha, hora
     FROM reservas
     ORDER BY id DESC
     LIMIT 5"
);

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<meta
  name="viewport"
  content="width=device-width, initial-scale=1.0">

<title>Dashboard Admin</title>

<!-- ======================================================
     BOOTSTRAP
====================================================== -->

<link
  href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
  rel="stylesheet">

<!-- ======================================================
     ICONOS
====================================================== -->

<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- ======================================================
     CSS
====================================================== -->

<link
  rel="stylesheet"
  href="/version_final/css/dashboard.css?v=2">

</head>

<body class="bg-light">

<!-- ======================================================
     NAVBAR
====================================================== -->

<nav class="navbar navbar-dark bg-dark shadow-sm py-3">

  <div class="container-fluid px-4">

    <h3 class="text-white fw-bold m-0">

      🔥 Dashboard Admin

    </h3>

    <a
      href="/version_final/index.php"
      class="btn btn-warning fw-bold rounded-pill px-4">

      Salir

    </a>

  </div>

</nav>

<!-- ======================================================
     CONTENIDO
====================================================== -->

<div class="container py-5">

  <!-- ======================================================
       TARJETAS
  ====================================================== -->

  <div class="row g-4">

    <!-- PEDIDOS -->
    <div class="col-md-6 col-xl-4">

      <div class="card border-0 shadow-lg h-100 dashboard-card">

        <div class="card-body">

          <h2 class="fw-bold text-primary">

            <?= $pedidos['total'] ?>

          </h2>

          <p class="text-muted mb-0">

            📦 Pedidos

          </p>

        </div>

      </div>

    </div>

    <!-- DOMICILIOS -->
    <div class="col-md-6 col-xl-4">

      <div class="card border-0 shadow-lg h-100 dashboard-card">

        <div class="card-body">

          <h2 class="fw-bold text-primary">

            <?= $domicilios['total'] ?>

          </h2>

          <p class="text-muted mb-0">

            🚚 Domicilios

          </p>

        </div>

      </div>

    </div>

    <!-- PENDIENTES -->
    <div class="col-md-6 col-xl-4">

      <div class="card border-0 shadow-lg h-100 dashboard-card">

        <div class="card-body">

          <h2 class="fw-bold text-danger">

            <?= $pendientes['total'] ?>

          </h2>

          <p class="text-muted mb-0">

            ⏳ Pendientes

          </p>

        </div>

      </div>

    </div>

    <!-- RESERVAS -->
    <div class="col-md-6 col-xl-4">

      <div class="card border-0 shadow-lg h-100 dashboard-card">

        <div class="card-body">

          <h2 class="fw-bold text-primary">

            <?= $reservas['total'] ?>

          </h2>

          <p class="text-muted mb-0">

            📅 Reservas

          </p>

        </div>

      </div>

    </div>

    <!-- RESERVAS HOY -->
    <div class="col-md-6 col-xl-4">

      <div class="card border-0 shadow-lg h-100 dashboard-card">

        <div class="card-body">

          <h2 class="fw-bold text-success">

            <?= $res_hoy['total'] ?>

          </h2>

          <p class="text-muted mb-0">

            ✅ Reservas Hoy

          </p>

        </div>

      </div>

    </div>

    <!-- VENTAS -->
    <div class="col-md-6 col-xl-4">

      <div class="card border-0 shadow-lg h-100 dashboard-card">

        <div class="card-body">

          <h2 class="fw-bold text-warning">

            $<?= number_format($totalVentas,0,',','.') ?>

          </h2>

          <p class="text-muted mb-0">

            💰 Ventas Hoy

          </p>

        </div>

      </div>

    </div>

  </div>

  <!-- ======================================================
       LISTAS
  ====================================================== -->

  <div class="row mt-5 g-4">

    <!-- PEDIDOS -->
    <div class="col-lg-6">

      <div class="card border-0 shadow-lg h-100">

        <div class="card-body">

          <h4 class="fw-bold mb-4">

            📦 Últimos Pedidos

          </h4>

          <?php while($p = mysqli_fetch_assoc($ultimos_pedidos)) { ?>

            <div class="d-flex justify-content-between align-items-center border-bottom py-3">

              <span>

                Pedido #<?= $p['id'] ?>

              </span>

              <strong class="text-primary">

                $<?= number_format($p['total'],0,',','.') ?>

              </strong>

            </div>

          <?php } ?>

        </div>

      </div>

    </div>

    <!-- RESERVAS -->
    <div class="col-lg-6">

      <div class="card border-0 shadow-lg h-100">

        <div class="card-body">

          <h4 class="fw-bold mb-4">

            📅 Últimas Reservas

          </h4>

          <?php while($r = mysqli_fetch_assoc($ultimas_reservas)) { ?>

            <div class="border-bottom py-3">

              <strong>

                <?= $r['nombre'] ?>

              </strong>

              <div class="text-muted small">

                <?= $r['fecha'] ?>
                ·
                <?= $r['hora'] ?>

              </div>

            </div>

          <?php } ?>

        </div>

      </div>

    </div>

  </div>

  <!-- ======================================================
       MENU ADMIN
  ====================================================== -->

  <div class="row mt-5 g-4">

    <div class="col-md-4">

      <a
        href="pedidos.php"
        class="btn btn-dark w-100 py-4 fw-bold rounded-4 shadow">

        📦 Pedidos

      </a>

    </div>

    <div class="col-md-4">

      <a
        href="domicilios.php"
        class="btn btn-dark w-100 py-4 fw-bold rounded-4 shadow">

        🚚 Domicilios

      </a>

    </div>

    <div class="col-md-4">

      <a
        href="reservas.php"
        class="btn btn-dark w-100 py-4 fw-bold rounded-4 shadow">

        📅 Reservas

      </a>

    </div>

    <div class="col-md-6">

      <a
        href="productos.php"
        class="btn btn-warning w-100 py-4 fw-bold rounded-4 shadow">

        🍽 Productos

      </a>

    </div>

    <div class="col-md-6">

      <a
        href="usuarios.php"
        class="btn btn-warning w-100 py-4 fw-bold rounded-4 shadow">

        👤 Usuarios

      </a>

    </div>

  </div>

</div>

<!-- ======================================================
     BOOTSTRAP JS
====================================================== -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>