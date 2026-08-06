<?php
session_start();
require_once(__DIR__ . "/../../php/modelo/conexion.php");

if (
    !isset($_SESSION['rol']) ||
    ($_SESSION['rol'] != 'admin' && $_SESSION['rol'] != 'repartidor')
) {
    header("Location: /index.php");
    exit();
}

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: /paginas/repartidor/domicilios.php?mensaje=error");
    exit();
}

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
        WHERE d.id = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    header("Location: /paginas/repartidor/domicilios.php?mensaje=error");
    exit();
}

$productos = json_decode($data['productos'] ?? '[]', true);
$total = preg_replace('/\D/', '', $data['total'] ?? 0);
$estado_class = str_replace(' ', '-', $data['estado']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Domicilio | Repartidor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/repartidor.css?v=1">
</head>
<body>
<header class="delivery-topbar">
    <div>
        <span class="eyebrow">Detalle de entrega</span>
        <h1><i class="bi bi-box-seam"></i> Domicilio</h1>
    </div>

    <a href="/paginas/repartidor/domicilios.php" class="btn btn-warning">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</header>

<main class="delivery-shell">
    <section class="delivery-card p-4">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="products-stack">
                    <span><strong>Cliente:</strong> <?= htmlspecialchars($data['nombre'] ?? 'Cliente') ?></span>
                    <span><strong>Telefono:</strong> <?= htmlspecialchars($data['telefono'] ?? 'No registrado') ?></span>
                    <span><strong>Direccion:</strong> <?= htmlspecialchars($data['direccion'] ?? '') ?></span>
                </div>
            </div>
            <div class="col-md-6 text-md-end">
                <span class="status-select status-<?= htmlspecialchars($estado_class) ?> d-inline-flex px-4 py-2">
                    <?= htmlspecialchars(ucfirst($data['estado'])) ?>
                </span>
                <h3 class="money mt-3">$<?= number_format((int)$total, 0, ',', '.') ?></h3>
            </div>
        </div>

        <hr class="my-4">

        <h2 class="h5 fw-bold mb-3" style="color: var(--delivery-blue);">Productos</h2>
        <div class="products-stack mb-4">
            <?php if (is_array($productos) && count($productos) > 0): ?>
                <?php foreach ($productos as $producto): ?>
                    <span><?= htmlspecialchars($producto['nombre'] ?? 'Producto') ?></span>
                <?php endforeach; ?>
            <?php else: ?>
                <span class="text-muted">Sin productos registrados</span>
            <?php endif; ?>
        </div>

        <a class="btn btn-warning"
           target="_blank"
           href="https://www.google.com/maps/dir/?api=1&destination=<?= urlencode($data['direccion'] ?? '') ?>">
            <i class="bi bi-geo-alt-fill"></i> Abrir ruta en Google Maps
        </a>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
