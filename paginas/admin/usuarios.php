<?php
session_start();
require_once(__DIR__ . "/../../php/modelo/conexion.php");

// 🔒 PROTEGER ADMIN
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /index.php");
    exit();
}

// 📦 LISTAR USUARIOS CON SUS ROLES RELACIONADOS
$sql = "SELECT u.*, r.nombre_rol AS rol 
        FROM usuarios u 
        INNER JOIN roles r ON u.id_rol = r.id 
        ORDER BY u.id DESC";
        
$resultado = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios | Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="/css/admin.css">
    <link class="modulo-css" rel="stylesheet" href="/css/admin/usuarios.css">
</head>
<body>

<div class="wrapper d-flex">

    <?php include(__DIR__ . "/../../includes/sidebar.php"); ?>

    <div class="main-content flex-grow-1 p-4">
        
        <?php include(__DIR__ . "/../../includes/topbar.php"); ?>

        <div class="content-container-max mx-auto mt-4">

            <?php if(isset($_GET['mensaje'])): ?>
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> Operación realizada correctamente.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="card-body p-0">
                    
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                        <div>
                            <h2 class="h4 fw-bold text-dark mb-1">Gestión de Usuarios</h2>
                            <p class="text-muted mb-0">Administra las cuentas, asigna roles y cambia el estado de acceso a la plataforma</p>
                        </div>
                        <span class="badge bg-light text-dark border px-3 py-2 fs-6 rounded-pill">
                            <i class="bi bi-people-fill me-1"></i> Total: <?= mysqli_num_rows($resultado) ?> usuarios
                        </span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless align-middle header-styled-table">
                            <thead class="table-light text-uppercase fs-7 text-muted" style="letter-spacing: 0.5px;">
                                <tr>
                                    <th class="py-3">Nombre</th>
                                    <th class="py-3">Correo Electrónico</th>
                                    <th class="py-3 text-center">Rol</th>
                                    <th class="py-3 text-center">Acceso / Estado</th>
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

                                    <td class="text-dark small">
                                        <?= htmlspecialchars($row['correo']) ?>
                                    </td>

                                    <td class="text-center">
                                        <?php if($row['rol'] == 'admin'): ?>
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1.5 rounded-pill small fw-bold">
                                                Admin
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1.5 rounded-pill small fw-bold">
                                                Cliente
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">
                                        <form action="/php/controlador/usuarios/estado.php" method="POST" class="m-0">
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <select name="estado" class="form-select form-select-sm rounded-pill font-medium text-center bg-light" onchange="this.form.submit()" style="min-width: 120px;">
                                                <option value="1" <?= (!isset($row['estado']) || $row['estado'] == 1) ? 'selected' : '' ?>>Activo</option>
                                                <option value="0" <?= (isset($row['estado']) && $row['estado'] == 0) ? 'selected' : '' ?>>Inactivo</option>
                                            </select>
                                        </form>
                                    </td>

                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="editar_usuario.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                <i class="bi bi-pencil-square me-1"></i> Editar
                                            </a>
                                            <a href="/php/controlador/usuarios/eliminar.php?id=<?= $row['id'] ?>" 
                                               class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                               onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">
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
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
