<?php
session_start();

// 🔒 PROTEGER ADMIN
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /index.php");
    exit();
}

// ✅ RUTA ABSOLUTA
require_once(__DIR__ . "/../../php/controlador/productos/listar.php");
$categorias = $conexion->query("SELECT nombre FROM categorias WHERE estado=1 ORDER BY nombre");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos | Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="/css/admin.css">
    <link class="modulo-css" rel="stylesheet" href="/css/admin/productos.css">
</head>
<body>

<div class="wrapper d-flex">

    <?php include(__DIR__ . "/../../includes/sidebar.php"); ?>

    <div class="main-content flex-grow-1 p-4">
        
        <?php include(__DIR__ . "/../../includes/topbar.php"); ?>

        <div class="content-container-max mx-auto mt-4">

            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <div class="card-body p-0">
                    <h3 class="h5 fw-bold text-dark mb-4">
                        <i class="bi bi-plus-circle text-warning me-2"></i> Agregar Producto
                    </h3>

                    <form action="/php/controlador/productos/crear.php" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary">Nombre del Producto</label>
                                <input type="text" name="nombre" class="form-control custom-input" placeholder="Ej: Mojarra frita" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary">Precio (COP)</label>
                                <input type="number" name="precio" class="form-control custom-input" placeholder="25000" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary">Categoría</label>
                                <select name="categoria" class="form-select custom-input" required><option value="">Selecciona una categoría</option><?php while($categoria = $categorias->fetch_assoc()): ?><option value="<?= htmlspecialchars($categoria['nombre']) ?>"><?= htmlspecialchars($categoria['nombre']) ?></option><?php endwhile; ?></select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary">Descripción Breve</label>
                                <input type="text" name="descripcion" class="form-control custom-input" placeholder="Ej: Acompañado de arroz y patacón" required>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-warning px-4 py-2 rounded-pill fw-bold btn-action shadow-sm">
                                <i class="bi bi-save me-1"></i> Guardar Producto
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="card-body p-0">
                    
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                        <div>
                            <h2 class="h4 fw-bold text-dark mb-1">Gestión de Productos</h2>
                            <p class="text-muted mb-0">Visualiza, edita o remueve los platillos disponibles en el menú</p>
                        </div>
                        <span class="badge bg-light text-dark border px-3 py-2 fs-6 rounded-pill">
                            <i class="bi bi-grid me-1"></i> Total: <?= $resultado->num_rows ?> platos
                        </span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless align-middle header-styled-table">
                            <thead class="table-light text-uppercase fs-7 text-muted" style="letter-spacing: 0.5px;">
                                <tr>
                                    <th class="py-3" style="width: 35%;">Nombre</th>
                                    <th class="py-3 text-end">Precio</th>
                                    <th class="py-3 text-center">Categoría</th>
                                    <th class="py-3 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php while($row = $resultado->fetch_assoc()) { ?>
                                <tr class="border-bottom">
                                    <td class="fw-semibold text-dark">
                                        <?= htmlspecialchars($row['nombre']); ?>
                                    </td>

                                    <td class="text-end">
                                        <span class="fw-bold text-success fs-6">
                                            $<?= number_format($row['precio'], 0, ',', '.'); ?>
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <span class="badge bg-warning-subtle text-warning-dark border border-warning px-3 py-1.5 rounded-pill small fw-semibold">
                                            <?= htmlspecialchars($row['categoria']); ?>
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="editar_producto.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                <i class="bi bi-pencil-square me-1"></i> Editar
                                            </a>

                                            <a href="/php/controlador/productos/eliminar.php?id=<?= $row['id']; ?>" 
                                               class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                               onclick="return confirm('¿Seguro que deseas eliminar este producto?')">
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
