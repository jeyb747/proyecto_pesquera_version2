<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/version_final/php/modelo/conexion.php");

// 🔒 PROTEGER ADMIN
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {

    header("Location: /version_final/index.php");
    exit();

}

// 📦 LISTAR PEDIDOS
$sql = "SELECT * FROM pedidos ORDER BY id DESC";

$resultado = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Pedidos Admin</title>

<!-- BOOTSTRAP -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- ICONOS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- CSS -->
<link rel="stylesheet" href="/version_final/css/admin.css">

</head>

<body>

<!-- ======================================================
     HEADER
====================================================== -->

<div class="header">

    <h1>
        📦 Pedidos
    </h1>

    <a
        href="/version_final/paginas/admin/dashboard.php">

        Volver

    </a>

</div>

<!-- ======================================================
     CONTENIDO
====================================================== -->

<div class="container">

    <!-- ALERTA -->
    <?php if(isset($_GET['mensaje'])): ?>

        <div class="alert alert-success shadow-sm border-0">

            <i class="bi bi-check-circle-fill"></i>
            Pedido actualizado correctamente.

        </div>

    <?php endif; ?>

    <!-- CARD -->
    <div class="box">

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

            <div>

                <h2 class="fw-bold mb-1">
                    Gestión de pedidos
                </h2>

                <p class="text-muted mb-0">
                    Visualiza todos los pedidos realizados.
                </p>

            </div>

            <span class="badge bg-dark fs-6 px-3 py-2">

                <?= mysqli_num_rows($resultado) ?> pedidos

            </span>

        </div>

        <!-- TABLA -->
        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-dark">

                    <tr>

                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Productos</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th>Acciones</th>

                    </tr>

                </thead>

                <tbody>

                <?php while($row = mysqli_fetch_assoc($resultado)) { ?>

                    <tr>

                        <!-- ID -->
                        <td>

                            <strong>
                                #<?= $row['id'] ?>
                            </strong>

                        </td>

                        <!-- USUARIO -->
                        <td>

                            <span class="fw-semibold">

                                <?= $row['usuario_id'] ?>

                            </span>

                        </td>

                        <!-- PRODUCTOS -->
                        <td>

                            <?php 
                            $productos = json_decode($row['productos'], true);

                            if (is_array($productos)) {

                                foreach ($productos as $p) {

                                    $nombre = $p['nombre'] ?? 'Producto';

                                    $precio = $p['precio'] ?? 0;

                                    $precio = preg_replace('/\D/', '', $precio);

                                    echo "

                                    <div class='mb-2'>

                                        🍽 <strong>{$nombre}</strong>

                                        <br>

                                        <span class='text-warning fw-bold'>
                                            $" . number_format($precio, 0, ',', '.') . "
                                        </span>

                                    </div>

                                    ";

                                }

                            } else {

                                echo "
                                <span class='text-muted'>
                                    Sin productos
                                </span>
                                ";

                            }
                            ?>

                        </td>

                        <!-- TOTAL -->
                        <td>

                            <?php
                            $total = preg_replace('/\D/', '', $row['total']);
                            ?>

                            <span class="fw-bold text-warning">

                                $<?= number_format($total, 0, ',', '.') ?>

                            </span>

                        </td>

                        <!-- FECHA -->
                        <td>

                            <?= $row['fecha'] ?>

                        </td>

                        <!-- ACCIONES -->
                        <td>

                            <div class="d-flex gap-2 flex-wrap">

                                <!-- VER -->
                                <a
                                    href="ver_pedido.php?id=<?= $row['id'] ?>"
                                    class="btn btn-warning btn-sm fw-bold">

                                    <i class="bi bi-eye"></i>
                                    Ver

                                </a>

                                <!-- ELIMINAR -->
                                <a
                                    href="/version_final/php/controlador/pedidos/eliminar.php?id=<?= $row['id'] ?>"
                                    class="btn btn-danger btn-sm fw-bold"
                                    onclick="return confirm('¿Eliminar pedido?')">

                                    <i class="bi bi-trash"></i>
                                    Eliminar

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

<!-- ======================================================
     BOOTSTRAP JS
====================================================== -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html> 