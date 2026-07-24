<?php
session_start();
require_once(__DIR__ . "/../../php/modelo/conexion.php");

// 🔒 PROTEGER ADMIN
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /index.php");
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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="/css/admin.css">
    <link class="modulo-css" rel="stylesheet" href="/css/admin/reservas.css">
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
                        <h2 class="h4 fw-bold text-dark mb-1">Gestión de Reservas</h2>
                        <p class="text-muted mb-0">Visualiza, confirma o cancela las reservas de mesas del restaurante</p>
                    </div>
                    <span class="badge bg-light text-dark border px-3 py-2 fs-6 rounded-pill">
                        <i class="bi bi-calendar-check me-1"></i> Total: <?= mysqli_num_rows($resultado) ?>
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless align-middle header-styled-table">
                        <thead class="table-light text-uppercase fs-7 text-muted" style="letter-spacing: 0.5px;">
                            <tr>
                                <th class="py-3">Nombre</th>
                                <th class="py-3">Teléfono</th>
                                <th class="py-3 text-center">Fecha</th>
                                <th class="py-3 text-center">Hora</th>
                                <th class="py-3 text-center">Personas</th>
                                <th class="py-3 text-center">Estado</th>
                                <th class="py-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while($row = mysqli_fetch_assoc($resultado)) { ?>
                            <tr class="border-bottom">
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="bi bi-person text-muted"></i>
                                        </div>
                                        <span class="fw-semibold text-secondary"><?= htmlspecialchars($row['nombre']) ?></span>
                                    </div>
                                </td>

                                <td>
                                    <span class="text-dark small fw-medium">
                                        <i class="bi bi-telephone text-muted me-1"></i> <?= htmlspecialchars($row['telefono']) ?>
                                    </span>
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-light text-dark border px-2 py-2 rounded">
                                        <i class="bi bi-calendar3 text-primary me-1"></i> <?= $row['fecha'] ?>
                                    </span>
                                </td>

                                <td class="text-center">
                                    <span class="fw-medium text-dark">
                                        <i class="bi bi-clock text-muted me-1"></i> <?= $row['hora'] ?>
                                    </span>
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-warning-subtle text-warning-dark border border-warning px-3 py-1.5 rounded-pill fw-bold">
                                        <i class="bi bi-people me-1"></i> <?= $row['personas'] ?>
                                    </span>
                                </td>

                                <td class="text-center">
                                    <form action="/php/controlador/reservas/estado.php" method="POST" class="m-0">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <select name="estado" class="form-select form-select-sm rounded-pill font-medium text-center bg-light" onchange="this.form.submit()" style="min-width: 130px;">
                                            <option value="pendiente" <?= $row['estado']=='pendiente'?'selected':'' ?>>Pendiente</option>
                                            <option value="confirmada" <?= $row['estado']=='confirmada'?'selected':'' ?>>Confirmada</option>
                                            <option value="cancelada" <?= $row['estado']=='cancelada'?'selected':'' ?>>Cancelada</option>
                                        </select>
                                    </form>
                                </td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="/php/controlador/reservas/eliminar.php?id=<?= $row['id'] ?>" 
                                           class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                           onclick="return confirm('¿Seguro que deseas eliminar esta reserva?')">
                                            <i class="bi bi-trash me-1"></i> Eliminar
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

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
