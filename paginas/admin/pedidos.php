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

$sql = "SELECT * FROM pedidos ORDER BY id DESC";
$resultado = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/admin.css?v=10">
    <link rel="stylesheet" href="/css/admin/pedidos.css?v=10">
</head>

<body>

<div class="dashboard-layout">

    <?php include(__DIR__ . "/../../includes/sidebar.php"); ?>

    <main class="main-content">

        <?php include(__DIR__ . "/../../includes/topbar.php"); ?>

        <div class="container-fluid px-0">

            <div class="custom-box">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold mb-1" style="color: var(--azul);">Gestión de Pedidos</h2>
                        <p class="text-muted mb-0">Visualiza y administra las órdenes del restaurante</p>
                    </div>
                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fs-6 fw-semibold border border-primary-subtle">
                        <i class="bi bi-receipt me-1"></i> Total: <?= mysqli_num_rows($resultado) ?>
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle border-0">
                        <thead class="table-light">
                            <tr style="border-bottom: 2px solid #edf2f7;">
                                <th class="py-3" style="color: var(--azul); font-weight: 700; width: 35%;">Productos</th>
                                <th class="py-3" style="color: var(--azul); font-weight: 700;">Total del Pedido</th>
                                <th class="py-3" style="color: var(--azul); font-weight: 700;">Fecha y Hora</th>
                                <th class="py-3 text-end px-4" style="color: var(--azul); font-weight: 700;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while($row = mysqli_fetch_assoc($resultado)) { ?>
                            <tr style="border-bottom: 1px solid #edf2f7;">
                                
                                <td class="py-3">
                                    <div class="d-flex flex-column gap-2">
                                    <?php 
                                    $productos = json_decode($row['productos'], true);

                                    if(is_array($productos)){
                                        foreach($productos as $p){
                                            $nombre = $p['nombre'] ?? 'Producto';
                                            $precio = preg_replace('/\D/', '', $p['precio'] ?? 0);
                                            ?>
                                            <div class="p-2 rounded bg-light border-start border-3 border-warning d-flex justify-content-between align-items-center" style="font-size: 0.9rem;">
                                                <span class="text-dark fw-medium"> <?= htmlspecialchars($nombre) ?></span>
                                                <span class="text-muted font-monospace">$<?= number_format($precio,0,',','.') ?></span>
                                            </div>
                                            <?php
                                        }
                                    } else {
                                        echo "<span class='text-muted small italic'><i class='bi bi-exclamation-circle me-1'></i>Sin detalles</span>";
                                    }
                                    ?>
                                    </div>
                                </td>

                                <td class="py-3">
                                    <span class="fs-6 fw-bold text-success font-monospace">
                                        $<?= number_format(preg_replace('/\D/', '', $row['total']),0,',','.') ?>
                                    </span>
                                </td>

                                <td class="py-3 text-secondary small">
                                    <i class="bi bi-clock me-1"></i><?= $row['fecha'] ?>
                                </td>

                                <td class="py-3 text-end px-4">
                                    <div class="d-inline-flex gap-2">
                                        <a href="ver_pedido.php?id=<?= $row['id'] ?>" class="btn btn-outline-primary btn-sm rounded-3 d-flex align-items-center gap-1 px-3 py-15" title="Ver detalles">
                                            <i class="bi bi-eye-fill"></i> Ver
                                        </a>
                                        <a href="/php/controlador/pedidos/eliminar.php?id=<?= $row['id'] ?>"
                                           class="btn btn-outline-danger btn-sm rounded-3 d-flex align-items-center gap-1 px-2 py-15"
                                           onclick="return confirm('¿Estás seguro de que deseas eliminar este pedido permanentemente?')" 
                                           title="Eliminar">
                                            <i class="bi bi-trash3-fill"></i>
                                        </a>
                                    </div>
                                </td>

                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
