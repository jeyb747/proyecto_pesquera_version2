<?php
session_start();
require_once(__DIR__ . "/../../php/modelo/conexion.php");

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /index.php");
    exit();
}

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: /paginas/admin/pedidos.php?mensaje=error");
    exit();
}

$sql = "SELECT p.*, u.nombre AS cliente, u.correo, u.telefono
        FROM pedidos p
        LEFT JOIN usuarios u ON p.usuario_id = u.id
        WHERE p.id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$pedido = $stmt->get_result()->fetch_assoc();

if (!$pedido) {
    header("Location: /paginas/admin/pedidos.php?mensaje=error");
    exit();
}

$productos = json_decode($pedido['productos'] ?? '[]', true);
$total = preg_replace('/\D/', '', $pedido['total'] ?? 0);
$estado = $pedido['estado'] ?? 'pendiente';
$estado_class = str_replace(' ', '-', strtolower($estado));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Pedido | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/admin.css?v=11">
    <link rel="stylesheet" href="/css/admin/crud-pages.css?v=1">
</head>
<body>
<div class="dashboard-layout">
    <?php include(__DIR__ . "/../../includes/sidebar.php"); ?>

    <main class="main-content">
        <?php include(__DIR__ . "/../../includes/topbar.php"); ?>

        <div class="admin-page-shell">
            <div class="page-heading">
                <div>
                    <a href="/paginas/admin/pedidos.php" class="back-link">
                        <i class="bi bi-arrow-left"></i> Volver a pedidos
                    </a>
                    <h1>Detalle del pedido</h1>
                    <p>Consulta el cliente, estado, fecha y productos comprados.</p>
                </div>
                <span class="status-pill status-<?= htmlspecialchars($estado_class) ?>">
                    <?= htmlspecialchars(ucfirst($estado)) ?>
                </span>
            </div>

            <div class="info-grid">
                <article class="info-card">
                    <span>Cliente</span>
                    <strong><?= htmlspecialchars($pedido['cliente'] ?? 'Cliente no registrado') ?></strong>
                    <small><?= htmlspecialchars($pedido['correo'] ?? '') ?></small>
                </article>

                <article class="info-card">
                    <span>Telefono</span>
                    <strong><?= htmlspecialchars($pedido['telefono'] ?? 'No registrado') ?></strong>
                </article>

                <article class="info-card">
                    <span>Total</span>
                    <strong class="text-success">$<?= number_format((int)$total, 0, ',', '.') ?></strong>
                </article>

                <article class="info-card">
                    <span>Fecha</span>
                    <strong><?= htmlspecialchars($pedido['fecha'] ?? 'Sin fecha') ?></strong>
                </article>
            </div>

            <div class="form-card mt-4">
                <div class="section-title">
                    <i class="bi bi-basket"></i>
                    <h2>Productos del pedido</h2>
                </div>

                <?php if (is_array($productos) && count($productos) > 0): ?>
                    <div class="table-responsive">
                        <table class="table align-middle detail-table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-end">Precio</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($productos as $producto): ?>
                                <?php $precio = preg_replace('/\D/', '', $producto['precio'] ?? 0); ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($producto['nombre'] ?? 'Producto') ?></strong>
                                    </td>
                                    <td class="text-end text-success fw-bold">
                                        $<?= number_format((int)$precio, 0, ',', '.') ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="bi bi-info-circle"></i>
                        No hay productos registrados en este pedido.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
