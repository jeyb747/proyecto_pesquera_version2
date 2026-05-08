<?php
session_start();

// 🔒 PROTEGER ADMIN
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /version_final/index.php");
    exit();
}

// ✅ RUTA ABSOLUTA
require_once($_SERVER['DOCUMENT_ROOT'] . "/version_final/php/controlador/productos/listar.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Productos | Admin</title>

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
        🍽 Gestión de Productos
    </h1>

    <a class="logout" href="/version_final/paginas/admin/dashboard.php">
        ← Volver
    </a>

</div>

<!-- ================= CONTENIDO ================= -->

<div class="container py-4">

    <!-- ================= AGREGAR ================= -->

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body p-4">

            <h3 class="mb-4 fw-bold">
                <i class="bi bi-plus-circle"></i>
                Agregar Producto
            </h3>

            <form action="/version_final/php/controlador/productos/crear.php" method="POST">

                <div class="row g-3">

                    <!-- NOMBRE -->
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Nombre
                        </label>

                        <input
                            type="text"
                            name="nombre"
                            class="form-control"
                            placeholder="Ej: Mojarra frita"
                            required>

                    </div>

                    <!-- PRECIO -->
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Precio
                        </label>

                        <input
                            type="number"
                            name="precio"
                            class="form-control"
                            placeholder="25000"
                            required>

                    </div>

                    <!-- CATEGORIA -->
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Categoría
                        </label>

                        <input
                            type="text"
                            name="categoria"
                            class="form-control"
                            placeholder="Pescados"
                            required>

                    </div>

                    <!-- DESCRIPCION -->
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Descripción
                        </label>

                        <input
                            type="text"
                            name="descripcion"
                            class="form-control"
                            placeholder="Descripción del producto"
                            required>

                    </div>

                </div>

                <!-- BOTON -->
                <div class="mt-4">

                    <button type="submit" class="btn btn-warning px-4 fw-bold">

                        <i class="bi bi-save"></i>
                        Guardar Producto

                    </button>

                </div>

            </form>

        </div>

    </div>

    <!-- ================= TABLA ================= -->

    <div class="card shadow-sm border-0">

        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

                <h3 class="fw-bold m-0">
                    <i class="bi bi-grid"></i>
                    Lista de Productos
                </h3>

                <span class="badge bg-dark fs-6">
                    <?= $resultado->num_rows ?> productos
                </span>

            </div>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Categoría</th>
                            <th>Acciones</th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php while($row = $resultado->fetch_assoc()) { ?>

                        <tr>

                            <!-- ID -->
                            <td>
                                #<?= $row['id']; ?>
                            </td>

                            <!-- NOMBRE -->
                            <td class="fw-semibold">
                                <?= $row['nombre']; ?>
                            </td>

                            <!-- PRECIO -->
                            <td class="text-success fw-bold">

                                $<?= number_format($row['precio'],0,',','.'); ?>

                            </td>

                            <!-- CATEGORIA -->
                            <td>

                                <span class="badge bg-warning text-dark">

                                    <?= $row['categoria']; ?>

                                </span>

                            </td>

                            <!-- ACCIONES -->
                            <td>

                                <div class="d-flex gap-2 flex-wrap">

                                    <!-- EDITAR -->
                                    <a
                                        href="editar_producto.php?id=<?= $row['id']; ?>"
                                        class="btn btn-sm btn-primary">

                                        <i class="bi bi-pencil-square"></i>
                                        Editar

                                    </a>

                                    <!-- ELIMINAR -->
                                    <a
                                        href="/version_final/php/controlador/productos/eliminar.php?id=<?= $row['id']; ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('¿Eliminar producto?')">

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

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>