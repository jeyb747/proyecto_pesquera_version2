<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/version_final/php/modelo/conexion.php");

// 🔒 PERMITIR ADMIN Y REPARTIDOR
if (
    !isset($_SESSION['rol']) ||
    ($_SESSION['rol'] != 'admin' && $_SESSION['rol'] != 'repartidor')
) {
    header("Location: /version_final/index.php");
    exit();
}

// 📦 CONSULTA SEGÚN ROL
if ($_SESSION['rol'] == 'repartidor') {

    $sql = "SELECT 
                d.id,
                d.direccion,
                d.estado,
                p.productos,
                p.total,
                u.nombre
            FROM domicilios d
            INNER JOIN pedidos p ON d.pedido_id = p.id
            INNER JOIN usuarios u ON p.usuario_id = u.id
            WHERE d.estado != 'entregado'
            ORDER BY d.id DESC";

} else {

    $sql = "SELECT 
                d.id,
                d.direccion,
                d.estado,
                p.productos,
                p.total,
                u.nombre
            FROM domicilios d
            INNER JOIN pedidos p ON d.pedido_id = p.id
            INNER JOIN usuarios u ON p.usuario_id = u.id
            ORDER BY d.id DESC";
}

$resultado = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Domicilios | Admin</title>

<!-- ✅ Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- ✅ Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- ✅ CSS -->
<link rel="stylesheet" href="/version_final/css/admin.css">
<link rel="stylesheet" href="/version_final/css/repartidor.css">

</head>

<body>

<!-- ================= HEADER ================= -->

<div class="header">

    <h1>
        <i class="bi bi-truck"></i>
        Domicilios
    </h1>

    <a href="/version_final/paginas/admin/dashboard.php">
        <i class="bi bi-arrow-left"></i>
        Volver
    </a>

</div>

<!-- ================= CONTENIDO ================= -->

<div class="container py-4">

    <!-- CARD -->
    <div class="card shadow border-0 rounded-4">

        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

                <h2 class="fw-bold mb-0">
                    🚚 Gestión de Domicilios
                </h2>

                <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                    <?= mysqli_num_rows($resultado) ?> pedidos
                </span>

            </div>

            <!-- TABLA RESPONSIVE -->
            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-dark">

                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Dirección</th>
                            <th>Productos</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>

                    </thead>

                    <tbody>

                    <?php while($row = mysqli_fetch_assoc($resultado)) { ?>

                        <tr>

                            <!-- ID -->
                            <td>
                                <strong>#<?= $row['id'] ?></strong>
                            </td>

                            <!-- CLIENTE -->
                            <td>
                                <span class="fw-semibold">
                                    <?= $row['nombre'] ?>
                                </span>
                            </td>

                            <!-- DIRECCIÓN -->
                            <td>
                                <?= $row['direccion'] ?>
                            </td>

                            <!-- PRODUCTOS -->
                            <td>

                                <?php 
                                $productos = json_decode($row['productos'], true);

                                if (is_array($productos)) {

                                    foreach ($productos as $p) {
                                        echo "
                                            <div class='mb-1'>
                                                🍽 {$p['nombre']}
                                            </div>
                                        ";
                                    }

                                } else {

                                    echo "<span class='text-muted'>Sin productos</span>";

                                }
                                ?>

                            </td>

                            <!-- TOTAL -->
                            <td>

                                <span class="fw-bold text-success">

                                    $<?= number_format((int)$row['total'], 0, ',', '.') ?>

                                </span>

                            </td>

                            <!-- ESTADO -->
                            <td>

                                <form action="/version_final/php/controlador/domicilios/estado.php" method="POST">

                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">

                                    <select 
                                        name="estado"
                                        class="form-select"
                                        onchange="this.form.submit()"
                                    >

                                        <option value="pendiente"
                                            <?= $row['estado']=='pendiente'?'selected':'' ?>>
                                            Pendiente
                                        </option>

                                        <option value="en camino"
                                            <?= $row['estado']=='en camino'?'selected':'' ?>>
                                            En camino
                                        </option>

                                        <option value="entregado"
                                            <?= $row['estado']=='entregado'?'selected':'' ?>>
                                            Entregado
                                        </option>

                                    </select>

                                </form>

                            </td>

                            <!-- GOOGLE MAPS -->
                            <td>

                                <a 
                                  href="https://www.google.com/maps/dir/?api=1&destination=<?= urlencode($row['direccion']) ?>" 
                                  target="_blank"
                                  class="btn btn-warning fw-bold"
                                >

                                  <i class="bi bi-geo-alt-fill"></i>
                                  Ir

                                </a>

                            </td>

                        </tr>

                    <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<!-- ✅ Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>