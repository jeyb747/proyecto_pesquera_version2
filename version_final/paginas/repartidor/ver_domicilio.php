<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/version_final/php/modelo/conexion.php");

// 🔒 seguridad (admin o repartidor)
if (
    !isset($_SESSION['rol']) ||
    ($_SESSION['rol'] != 'admin' && $_SESSION['rol'] != 'repartidor')
) {
    header("Location: /version_final/index.php");
    exit();
}

// 📦 validar ID
if (!isset($_GET['id'])) {
    echo "ID no válido";
    exit();
}

$id = $_GET['id'];

// 🔥 CONSULTA CON JOIN
$sql = "SELECT 
            d.id,
            d.direccion,
            d.telefono,
            d.estado,
            p.productos,
            p.total,
            u.nombre
        FROM domicilios d
        INNER JOIN pedidos p ON d.pedido_id = p.id
        INNER JOIN usuarios u ON p.usuario_id = u.id
        WHERE d.id = '$id'";

$resultado = mysqli_query($conexion, $sql);
$data = mysqli_fetch_assoc($resultado);

if (!$data) {
    echo "Domicilio no encontrado";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Detalle domicilio</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- CSS -->
<link rel="stylesheet" href="/version_final/css/repartidor.css">

</head>

<body class="bg-light">

<!-- HEADER -->
<div class="bg-dark text-white p-3 d-flex justify-content-between align-items-center">

    <h4 class="m-0">
        📦 Detalle del domicilio
    </h4>

    <a href="domicilios.php" class="btn btn-warning fw-bold">
        <i class="bi bi-arrow-left"></i> Volver
    </a>

</div>

<!-- CONTENIDO -->
<div class="container py-4">

    <div class="card shadow border-0 rounded-4">

        <div class="card-body p-4">

            <h5 class="mb-3 fw-bold text-primary">
                👤 Cliente: <?= $data['nombre'] ?>
            </h5>

            <p><strong>📍 Dirección:</strong> <?= $data['direccion'] ?></p>
            <p><strong>📞 Teléfono:</strong> <?= $data['telefono'] ?? 'No registrado' ?></p>

            <!-- ESTADO -->
            <p>
                <strong>Estado:</strong>

                <span class="badge 
                    <?= $data['estado']=='pendiente' ? 'bg-warning text-dark' : '' ?>
                    <?= $data['estado']=='en camino' ? 'bg-primary' : '' ?>
                    <?= $data['estado']=='entregado' ? 'bg-success' : '' ?>
                ">
                    <?= $data['estado'] ?>
                </span>
            </p>

            <hr>

            <!-- PRODUCTOS -->
            <h5 class="fw-bold">🍽 Productos</h5>

            <div class="mt-2">

                <?php
                $productos = json_decode($data['productos'], true);

                if (is_array($productos)) {
                    foreach ($productos as $p) {
                        echo "<div>🍽 " . $p['nombre'] . "</div>";
                    }
                } else {
                    echo "<span class='text-muted'>Sin productos</span>";
                }
                ?>

            </div>

            <hr>

            <!-- TOTAL -->
            <h4 class="text-success fw-bold">
                💰 Total: $<?= number_format((int)$data['total'], 0, ',', '.') ?>
            </h4>

            <!-- MAPS -->
            <a 
              class="btn btn-warning mt-3 fw-bold"
              target="_blank"
              href="https://www.google.com/maps/dir/?api=1&destination=<?= urlencode($data['direccion']) ?>"
            >
                🚚 Abrir en Google Maps
            </a>

        </div>

    </div>

</div>

<!-- BOOTSTRAP -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>