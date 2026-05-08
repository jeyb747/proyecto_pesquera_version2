<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/version_final/php/modelo/conexion.php");

// 🔒 PROTEGER ADMIN
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /version_final/index.php");
    exit();
}

// 📊 TRAER RESERVAS
$sql = "SELECT * FROM reservas ORDER BY id DESC";
$resultado = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Reservas | Admin</title>

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
        📅 Gestión de Reservas
    </h1>

    <a href="dashboard.php">
        ← Volver
    </a>

</div>

<!-- ================= CONTENIDO ================= -->

<div class="container py-4">

    <!-- CARD -->
    <div class="card shadow-sm border-0">

        <div class="card-body p-4">

            <!-- TITULO -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

                <h3 class="fw-bold m-0">

                    <i class="bi bi-calendar-check"></i>
                    Lista de Reservas

                </h3>

                <span class="badge bg-dark fs-6">

                    <?= mysqli_num_rows($resultado) ?> reservas

                </span>

            </div>

            <!-- TABLA -->
            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Personas</th>
                            <th>Estado</th>
                            <th>Acciones</th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php while($row = mysqli_fetch_assoc($resultado)) { ?>

                        <tr>

                            <!-- ID -->
                            <td>

                                #<?= $row['id'] ?>

                            </td>

                            <!-- NOMBRE -->
                            <td class="fw-semibold">

                                <?= $row['nombre'] ?>

                            </td>

                            <!-- TELEFONO -->
                            <td>

                                <?= $row['telefono'] ?>

                            </td>

                            <!-- FECHA -->
                            <td>

                                <span class="badge bg-light text-dark border">

                                    <?= $row['fecha'] ?>

                                </span>

                            </td>

                            <!-- HORA -->
                            <td>

                                <?= $row['hora'] ?>

                            </td>

                            <!-- PERSONAS -->
                            <td>

                                <span class="badge bg-warning text-dark">

                                    <?= $row['personas'] ?> personas

                                </span>

                            </td>

                            <!-- ESTADO -->
                            <td>

                                <form action="/version_final/php/controlador/reservas/estado.php" method="POST">

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
                                            value="confirmada"
                                            <?= $row['estado']=='confirmada'?'selected':'' ?>>

                                            Confirmada

                                        </option>

                                        <option
                                            value="cancelada"
                                            <?= $row['estado']=='cancelada'?'selected':'' ?>>

                                            Cancelada

                                        </option>

                                    </select>

                                </form>

                            </td>

                            <!-- ACCIONES -->
                            <td>

                                <a
                                    href="/version_final/php/controlador/reservas/eliminar.php?id=<?= $row['id'] ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('¿Eliminar reserva?')">

                                    <i class="bi bi-trash"></i>
                                    Eliminar

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

<!-- BOOTSTRAP -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>