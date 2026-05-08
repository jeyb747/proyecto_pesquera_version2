<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/version_final/php/modelo/conexion.php");

// 🔒 solo admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /version_final/index.php");
    exit();
}

// 📦 traer domicilios con pedido
$sql = "SELECT d.*, p.usuario_id, p.productos, p.total
        FROM domicilios d
        INNER JOIN pedidos p ON d.pedido_id = p.id
        ORDER BY d.id DESC";

$resultado = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Domicilios Admin</title>

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
        🚚 Domicilios
    </h1>

    <a href="dashboard.php">
        Volver
    </a>

</div>

<!-- ======================================================
     CONTENIDO
====================================================== -->

<div class="container">

    <!-- CARD -->
    <div class="box">

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

            <div>
                <h2 class="mb-1">
                    Gestión de domicilios
                </h2>

                <p class="text-muted mb-0">
                    Administra los pedidos y estados de entrega.
                </p>
            </div>

            <span class="badge bg-dark fs-6 px-3 py-2">
                <?= mysqli_num_rows($resultado) ?> pedidos
            </span>

        </div>

        <!-- TABLA -->
        <div class="table-responsive">

            <table class="table align-middle table-hover">

                <thead class="table-dark">

                    <tr>

                        <th>#</th>
                        <th>Usuario</th>
                        <th>Dirección</th>
                        <th>Productos</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>

                    </tr>

                </thead>

                <tbody>

                <?php while($row = mysqli_fetch_assoc($resultado)) { ?>

                    <tr>

                        <!-- ID -->
                        <td>
                            <strong>#<?= $row['id'] ?></strong>
                        </td>

                        <!-- USUARIO -->
                        <td>
                            <span class="fw-semibold">
                                <?= $row['usuario_id'] ?>
                            </span>
                        </td>

                        <!-- DIRECCION -->
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

                            <span class="fw-bold text-warning">

                                $<?= number_format(
                                    preg_replace('/\D/','',$row['total']),
                                    0,
                                    ',',
                                    '.'
                                ) ?>

                            </span>

                        </td>

                        <!-- ESTADO -->
                        <td>

                            <form
                                action="/version_final/php/controlador/domicilios/estado.php"
                                method="POST">

                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?= $row['id'] ?>">

                                <select
                                    name="estado"
                                    class="form-select"
                                    onchange="this.form.submit()">

                                    <option
                                        value="pendiente"
                                        <?= $row['estado']=='pendiente'?'selected':'' ?>>

                                        Pendiente

                                    </option>

                                    <option
                                        value="en camino"
                                        <?= $row['estado']=='en camino'?'selected':'' ?>>

                                        En camino

                                    </option>

                                    <option
                                        value="entregado"
                                        <?= $row['estado']=='entregado'?'selected':'' ?>>

                                        Entregado

                                    </option>

                                </select>

                            </form>

                        </td>

                        <!-- ACCIONES -->
                        <td>

                            <a
                                href="ver_domicilio.php?id=<?= $row['id'] ?>"
                                class="btn btn-warning btn-sm fw-bold">

                                <i class="bi bi-eye"></i>
                                Ver

                            </a>

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