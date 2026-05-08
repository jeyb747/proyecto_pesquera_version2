<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/version_final/php/modelo/conexion.php");

// 🔒 PROTEGER ADMIN
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /version_final/index.php");
    exit();
}

// 📦 LISTAR USUARIOS
$sql = "SELECT * FROM usuarios";
$resultado = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Usuarios | Admin</title>

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
        👤 Gestión de Usuarios
    </h1>

    <a class="logout" href="/version_final/paginas/admin/dashboard.php">

        ← Volver

    </a>

</div>

<!-- ================= CONTENIDO ================= -->

<div class="container py-4">

    <!-- MENSAJE -->
    <?php if(isset($_GET['mensaje'])): ?>

        <div class="alert alert-success shadow-sm border-0">

            <i class="bi bi-check-circle-fill"></i>
            Usuario eliminado correctamente

        </div>

    <?php endif; ?>

    <!-- CARD -->
    <div class="card shadow-sm border-0">

        <div class="card-body p-4">

            <!-- TITULO -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

                <h3 class="fw-bold m-0">

                    <i class="bi bi-people-fill"></i>
                    Lista de Usuarios

                </h3>

                <span class="badge bg-dark fs-6">

                    <?= mysqli_num_rows($resultado) ?> usuarios

                </span>

            </div>

            <!-- TABLA -->
            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>
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

                            <!-- CORREO -->
                            <td>

                                <?= $row['correo'] ?>

                            </td>

                            <!-- ROL -->
                            <td>

                                <?php if($row['rol'] == 'admin'): ?>

                                    <span class="badge bg-danger">

                                        Admin

                                    </span>

                                <?php else: ?>

                                    <span class="badge bg-primary">

                                        Cliente

                                    </span>

                                <?php endif; ?>

                            </td>

                            <!-- ACCIONES -->
                            <td>

                                <div class="d-flex gap-2 flex-wrap">

                                    <!-- EDITAR -->
                                    <a
                                        href="editar_usuario.php?id=<?= $row['id'] ?>"
                                        class="btn btn-sm btn-primary">

                                        <i class="bi bi-pencil-square"></i>
                                        Editar

                                    </a>

                                    <!-- ELIMINAR -->
                                    <a
                                        href="/version_final/php/controlador/usuarios/eliminar.php?id=<?= $row['id'] ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('¿Eliminar usuario?')">

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

</div>

<!-- BOOTSTRAP -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>