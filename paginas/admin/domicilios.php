<?php
session_start();
require_once(__DIR__ . "/../../php/modelo/conexion.php");

// 🔒 Solo admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /index.php");
    exit();
}

// 📦 Traer domicilios con su respectivo pedido
$sql = "SELECT d.*, p.usuario_id, p.productos, p.total
        FROM domicilios d
        INNER JOIN pedidos p ON d.pedido_id = p.id
        ORDER BY d.id DESC";

$resultado = mysqli_query($conexion, $sql);
$domicilios = [];
$resumen = [
    'pendiente' => 0,
    'en camino' => 0,
    'entregado' => 0,
];

while ($row = mysqli_fetch_assoc($resultado)) {
    $domicilios[] = $row;
    if (isset($resumen[$row['estado']])) {
        $resumen[$row['estado']]++;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gestión de Domicilios</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link rel="stylesheet" href="/css/admin.css">
    <link class="modulo-css" rel="stylesheet" href="/css/admin/domicilios.css">
</head>
<body>

<div class="wrapper d-flex">

    <?php include(__DIR__ . "/../../includes/sidebar.php"); ?>

    <div class="main-content flex-grow-1 p-4">
        
        <?php include(__DIR__ . "/../../includes/topbar.php"); ?>

        <div class="content-container-max mx-auto mt-4">
            
            <div class="card border-0 shadow-sm rounded-4 p-4">
                
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                    <div>
                        <h2 class="h4 fw-bold text-dark mb-1">Gestión de Domicilios</h2>
                        <p class="text-muted mb-0">Visualiza y administra las órdenes de entrega a domicilio</p>
                    </div>
                    <span class="badge bg-light text-dark border px-3 py-2 fs-6 rounded-pill">
                        <i class="bi bi-list-task me-1"></i> Total: <?= count($domicilios) ?>
                    </span>
                </div>

                <div class="delivery-summary-grid mb-4">
                    <div class="delivery-summary-card">
                        <span class="summary-icon pending"><i class="bi bi-hourglass-split"></i></span>
                        <div>
                            <small>Pendientes</small>
                            <strong><?= $resumen['pendiente'] ?></strong>
                        </div>
                    </div>
                    <div class="delivery-summary-card">
                        <span class="summary-icon moving"><i class="bi bi-truck"></i></span>
                        <div>
                            <small>En camino</small>
                            <strong><?= $resumen['en camino'] ?></strong>
                        </div>
                    </div>
                    <div class="delivery-summary-card">
                        <span class="summary-icon delivered"><i class="bi bi-check2-circle"></i></span>
                        <div>
                            <small>Entregados</small>
                            <strong><?= $resumen['entregado'] ?></strong>
                        </div>
                    </div>
                </div>

                <div class="table-responsive desktop-delivery-table">
                    <table class="table table-borderless align-middle header-styled-table">
                        <thead class="table-light text-uppercase fs-7 text-muted" style="letter-spacing: 0.5px;">
                            <tr>
                                <th class="py-3" style="width: 25%;">Dirección</th>
                                <th class="py-3" style="width: 35%;">Productos</th>
                                <th class="py-3 text-end">Total del Pedido</th>
                                <th class="py-3 text-center">Estado</th>
                                <th class="py-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($domicilios as $row) { ?>
                            <tr class="border-bottom">
                                <td>
                                    <span class="text-dark small fw-medium">
                                        <i class="bi bi-geo-alt text-danger me-1"></i> <?= htmlspecialchars($row['direccion']) ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="d-flex flex-column gap-1">
                                    <?php 
                                    $productos = json_decode($row['productos'], true);
                                    if (is_array($productos)) {
                                        foreach ($productos as $p) { ?>
                                            <div class="inner-product-row d-flex justify-content-between align-items-center shadow-sm">
                                                <span class="fw-medium text-dark"><?= htmlspecialchars($p['nombre']) ?></span>
                                                
                                                <?php 
                                                // Filtramos cualquier caracter no numérico de los precios individuales
                                                $precio_limpio = isset($p['precio']) ? preg_replace('/\D/', '', $p['precio']) : ''; 
                                                ?>
                                                <span class="text-muted small">
                                                    $<?= !empty($precio_limpio) ? number_format($precio_limpio, 0, ',', '.') : '--.--' ?>
                                                </span>
                                            </div>
                                        <?php }
                                    } else { ?>
                                        <span class="text-muted small italic">Sin productos registrados</span>
                                    <?php } ?>
                                    </div>
                                </td>

                                <td class="text-end">
                                    <span class="fw-bold text-success fs-5">
                                        $<?= number_format(preg_replace('/\D/', '', $row['total']), 0, ',', '.') ?>
                                    </span>
                                </td>

                                <td class="text-center">
                                    <form action="/php/controlador/domicilios/estado.php" method="POST" class="m-0">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <select name="estado" class="form-select form-select-sm rounded-pill font-medium text-center bg-light" onchange="this.form.submit()" style="min-width: 130px;">
                                            <option value="pendiente" <?= $row['estado']=='pendiente'?'selected':'' ?>>Pendiente</option>
                                            <option value="en camino" <?= $row['estado']=='en camino'?'selected':'' ?>>En camino</option>
                                            <option value="entregado" <?= $row['estado']=='entregado'?'selected':'' ?>>Entregado</option>
                                        </select>
                                    </form>
                                </td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="/paginas/repartidor/ver_domicilio.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="bi bi-eye me-1"></i> Ver
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="mobile-delivery-list">
                    <?php foreach($domicilios as $row) { ?>
                        <article class="delivery-card-mobile">
                            <div class="delivery-card-header">
                                <div>
                                    <h3>Domicilio</h3>
                                </div>
                                <span class="status-pill status-<?= str_replace(' ', '-', $row['estado']) ?>">
                                    <?= htmlspecialchars(ucfirst($row['estado'])) ?>
                                </span>
                            </div>

                            <p class="delivery-address">
                                <i class="bi bi-geo-alt"></i>
                                <?= htmlspecialchars($row['direccion']) ?>
                            </p>

                            <div class="delivery-products">
                                <?php
                                $productos = json_decode($row['productos'], true);
                                if (is_array($productos)) {
                                    foreach ($productos as $p) {
                                        $precio_limpio = isset($p['precio']) ? preg_replace('/\D/', '', $p['precio']) : '';
                                ?>
                                    <div class="inner-product-row">
                                        <span><?= htmlspecialchars($p['nombre']) ?></span>
                                        <span>$<?= !empty($precio_limpio) ? number_format($precio_limpio, 0, ',', '.') : '--.--' ?></span>
                                    </div>
                                <?php
                                    }
                                } else {
                                ?>
                                    <span class="text-muted small">Sin productos registrados</span>
                                <?php } ?>
                            </div>

                            <div class="delivery-card-footer">
                                <strong>$<?= number_format(preg_replace('/\D/', '', $row['total']), 0, ',', '.') ?></strong>
                                <form action="/php/controlador/domicilios/estado.php" method="POST" class="m-0">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <select name="estado" class="form-select form-select-sm rounded-pill" onchange="this.form.submit()">
                                        <option value="pendiente" <?= $row['estado']=='pendiente'?'selected':'' ?>>Pendiente</option>
                                        <option value="en camino" <?= $row['estado']=='en camino'?'selected':'' ?>>En camino</option>
                                        <option value="entregado" <?= $row['estado']=='entregado'?'selected':'' ?>>Entregado</option>
                                    </select>
                                </form>
                                <a href="/paginas/repartidor/ver_domicilio.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="bi bi-eye me-1"></i> Ver
                                </a>
                            </div>
                        </article>
                    <?php } ?>
                </div>

            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
