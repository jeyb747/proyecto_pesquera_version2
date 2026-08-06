<?php
session_start();

require_once(__DIR__ . "/../../php/modelo/conexion.php");

/* ======================================================
   SEGURIDAD
====================================================== */

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /index.php");
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

$totalVentas = 0;
$ventas_hoy = mysqli_query(
    $conexion,
    "SELECT total
     FROM pedidos
     WHERE DATE(fecha)=CURDATE()"
);

if ($ventas_hoy) {
    while ($venta = mysqli_fetch_assoc($ventas_hoy)) {
        $totalVentas += (int) preg_replace('/\D/', '', $venta['total'] ?? 0);
    }
}

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="/css/admin.css?v=10">
</head>
<body>

<div class="dashboard-layout">

    <?php include(__DIR__ . "/../../includes/sidebar.php"); ?>

    <main class="main-content">

        <?php include(__DIR__ . "/../../includes/topbar.php"); ?> 

        <div class="row g-4">

            <div class="col-md-6 col-xl-4">
                <div class="dashboard-card">
                    <div class="card-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <h2><?= $pedidos['total'] ?></h2>
                    <p>Pedidos</p>
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="dashboard-card">
                    <div class="card-icon">
                        <i class="bi bi-truck"></i>
                    </div>
                    <h2><?= $domicilios['total'] ?></h2>
                    <p>Domicilios</p>
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="dashboard-card">
                    <div class="card-icon">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <h2 class="text-danger"><?= $pendientes['total'] ?></h2>
                    <p>Pendientes</p>
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="dashboard-card">
                    <div class="card-icon">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <h2><?= $reservas['total'] ?></h2>
                    <p>Reservas</p>
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="dashboard-card">
                    <div class="card-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <h2 class="text-success"><?= $res_hoy['total'] ?></h2>
                    <p>Reservas Hoy</p>
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="dashboard-card">
                    <div class="card-icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <h2 class="text-warning">$<?= number_format($totalVentas,0,',','.') ?></h2>
                    <p>Ventas Hoy</p>
                </div>
            </div>

        </div>

        <div class="row mt-4 g-4">

            <div class="col-lg-6">
                <div class="custom-box">
                    <h4>Últimos Pedidos</h4>
                    <?php while($p = mysqli_fetch_assoc($ultimos_pedidos)) { ?>
                        <div class="list-item d-flex justify-content-between">
                            <span>Pedido reciente</span>
                            <strong class="text-primary">$<?= number_format((int) preg_replace('/\D/', '', $p['total'] ?? 0),0,',','.') ?></strong>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="custom-box">
                    <h4>Últimas Reservas</h4>
                    <?php while($r = mysqli_fetch_assoc($ultimas_reservas)) { ?>
                        <div class="list-item">
                            <strong><?= htmlspecialchars($r['nombre']) ?></strong>
                            <div class="text-muted small">
                                <?= htmlspecialchars($r['fecha']) ?> · <?= htmlspecialchars($r['hora']) ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

        </div>

    </main> </div> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
