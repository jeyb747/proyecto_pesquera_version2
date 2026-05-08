<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/version_final/php/modelo/conexion.php");

// 🔒 PROTEGER ADMIN
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /version_final/index.php");
    exit();
}

// 🔍 VALIDAR ID
if (!isset($_GET['id'])) {
    header("Location: pedidos.php");
    exit();
}

$id = intval($_GET['id']);

// 📦 CONSULTAR PEDIDO
$sql = "SELECT * FROM pedidos WHERE id=$id";
$resultado = mysqli_query($conexion, $sql);

if (!$resultado || mysqli_num_rows($resultado) == 0) {
    die("Pedido no encontrado");
}

$pedido = mysqli_fetch_assoc($resultado);

// 🍽 PRODUCTOS
$productos = json_decode($pedido['productos'], true);
?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Detalle Pedido</title>

<!-- BOOTSTRAP -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- ICONOS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- CSS -->
<link rel="stylesheet" href="/version_final/css/admin.css">

</head>

<body>

<!-- ================= HEADER ================= -->

<div class="header">

    <h1>
        📦 Pedido #<?= $pedido['id'] ?>
    </h1>

    <a href="/version_final/paginas/admin/pedidos.php">

        ← Volver

    </a>

</div>

<!-- ================= CONTENIDO ================= -->

<div class="container py-4">

    <!-- CARD INFO -->
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body p-4">

            <h3 class="fw-bold mb-4">

                <i class="bi bi-receipt"></i>
                Información del Pedido

            </h3>

            <div class="row g-4">

                <!-- USUARIO -->
                <div class="col-md-6">

                    <div class="p-3 border rounded-4 bg-light">

                        <small class="text-muted">
                            Usuario
                        </small>

                        <h5 class="fw-bold m-0">

                            <?= $pedido['usuario_id'] ?>

                        </h5>

                    </div>

                </div>

                <!-- TOTAL -->
                <div class="col-md-6">

                    <div class="p-3 border rounded-4 bg-light">

                        <small class="text-muted">
                            Total
                        </small>

                        <h4 class="fw-bold text-success m-0">

                            $<?= number_format(
                                preg_replace('/\D/', '', $pedido['total']),
                                0,
                                ',',
                                '.'
                            ) ?>

                        </h4>

                    </div>

                </div>

                <!-- ESTADO -->
                <div class="col-md-6">

                    <div class="p-3 border rounded-4 bg-light">

                        <small class="text-muted">
                            Estado
                        </small>

                        <h5 class="m-0">

                            <?php if($pedido['estado'] == 'pendiente'): ?>

                                <span class="badge bg-warning text-dark">

                                    Pendiente

                                </span>

                            <?php elseif($pedido['estado'] == 'entregado'): ?>

                                <span class="badge bg-success">

                                    Entregado

                                </span>

                            <?php else: ?>

                                <span class="badge bg-primary">

                                    <?= $pedido['estado'] ?>

                                </span>

                            <?php endif; ?>

                        </h5>

                    </div>

                </div>

                <!-- FECHA -->
                <div class="col-md-6">

                    <div class="p-3 border rounded-4 bg-light">

                        <small class="text-muted">
                            Fecha
                        </small>

                        <h5 class="fw-bold m-0">

                            <?= $pedido['fecha'] ?>

                        </h5>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- PRODUCTOS -->
    <div class="card shadow-sm border-0">

        <div class="card-body p-4">

            <h3 class="fw-bold mb-4">

                <i class="bi bi-basket"></i>
                Productos del Pedido

            </h3>

            <?php if(is_array($productos) && count($productos) > 0): ?>

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-dark">

                            <tr>

                                <th>Producto</th>
                                <th>Precio</th>

                            </tr>

                        </thead>

                        <tbody>

                        <?php foreach($productos as $p): ?>

                            <tr>

                                <!-- NOMBRE -->
                                <td class="fw-semibold">

                                    🍽 <?= $p['nombre'] ?? 'Producto' ?>

                                </td>

                                <!-- PRECIO -->
                                <td class="text-success fw-bold">

                                    <?php
                                    $precio = preg_replace(
                                        '/\D/',
                                        '',
                                        $p['precio'] ?? 0
                                    );

                                    echo "$" . number_format(
                                        $precio,
                                        0,
                                        ',',
                                        '.'
                                    );
                                    ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php else: ?>

                <div class="alert alert-secondary">

                    No hay productos registrados.

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>

<!-- BOOTSTRAP -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>