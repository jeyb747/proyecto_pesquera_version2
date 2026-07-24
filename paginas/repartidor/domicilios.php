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

$solo_pendientes = $_SESSION['rol'] == 'repartidor';
$where = $solo_pendientes ? "WHERE d.estado != 'entregado'" : "";

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
        $where
        ORDER BY d.id DESC";

$resultado = mysqli_query($conexion, $sql);
$total_domicilios = $resultado ? mysqli_num_rows($resultado) : 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domicilios | Repartidor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/repartidor.css?v=1">
</head>
<body>
<header class="delivery-topbar">
    <div>
        <span class="eyebrow">Panel de entregas</span>
        <h1><i class="bi bi-truck"></i> Domicilios</h1>
    </div>

    <a href="<?= $_SESSION['rol'] == 'admin' ? '/paginas/admin/dashboard.php' : '/index.php' ?>" class="btn btn-warning">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</header>

<main class="delivery-shell">
    <section class="delivery-summary">
        <div>
            <span class="summary-label">Pendientes y en ruta</span>
            <strong><?= $total_domicilios ?></strong>
        </div>
        <p>Gestiona el estado de cada entrega y abre la ruta directamente en Google Maps.</p>
    </section>

    <section class="delivery-card">
        <div class="table-responsive">
            <table class="table align-middle delivery-table">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Direccion</th>
                        <th>Productos</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($resultado && $total_domicilios > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($resultado)): ?>
                        <?php
                        $productos = json_decode($row['productos'], true);
                        $estado_class = str_replace(' ', '-', $row['estado']);
                        $total = preg_replace('/\D/', '', $row['total'] ?? 0);
                        ?>
                        <tr>
                            <td>
                                <div class="customer-cell">
                                    <span><i class="bi bi-person"></i></span>
                                    <strong><?= htmlspecialchars($row['nombre'] ?? 'Cliente') ?></strong>
                                </div>
                            </td>
                            <td>
                                <span class="address-text">
                                    <i class="bi bi-geo-alt"></i>
                                    <?= htmlspecialchars($row['direccion'] ?? '') ?>
                                </span>
                            </td>
                            <td>
                                <div class="products-stack">
                                    <?php if (is_array($productos) && count($productos) > 0): ?>
                                        <?php foreach ($productos as $producto): ?>
                                            <span><?= htmlspecialchars($producto['nombre'] ?? 'Producto') ?></span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="text-muted">Sin productos</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <strong class="money">$<?= number_format((int)$total, 0, ',', '.') ?></strong>
                            </td>
                            <td>
                                <form action="/php/controlador/domicilios/estado.php" method="POST" class="m-0">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
                                    <select name="estado" class="form-select status-select status-<?= htmlspecialchars($estado_class) ?>" onchange="this.form.submit()">
                                        <option value="pendiente" <?= $row['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                        <option value="en camino" <?= $row['estado'] == 'en camino' ? 'selected' : '' ?>>En camino</option>
                                        <option value="entregado" <?= $row['estado'] == 'entregado' ? 'selected' : '' ?>>Entregado</option>
                                    </select>
                                </form>
                            </td>
                            <td class="text-end">
                                <div class="action-group">
                                    <a href="/paginas/repartidor/ver_domicilio.php?id=<?= htmlspecialchars($row['id']) ?>" class="btn btn-outline-primary">
                                        <i class="bi bi-eye"></i> Ver
                                    </a>
                                    <a href="https://www.google.com/maps/dir/?api=1&destination=<?= urlencode($row['direccion'] ?? '') ?>"
                                       target="_blank"
                                       class="btn btn-warning">
                                        <i class="bi bi-geo-alt-fill"></i> Ruta
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="empty-state">
                            <i class="bi bi-check2-circle"></i>
                            No hay domicilios pendientes por gestionar.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
