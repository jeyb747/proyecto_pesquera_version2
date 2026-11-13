<?php
session_start();
require_once(__DIR__ . "/../../php/modelo/conexion.php");

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /index.php");
    exit();
}

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: /paginas/admin/productos.php?mensaje=error");
    exit();
}

$stmt = $conexion->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$producto = $stmt->get_result()->fetch_assoc();

if (!$producto) {
    header("Location: /paginas/admin/productos.php?mensaje=error");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" integrity="sha384-XGjxtQfXaH2tnPFa9x+ruJTuLE3Aa6LhHSWRr1XeTyhezb4abCG4ccI5AkVDxqC+" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/admin.css?v=14">
    <link rel="stylesheet" href="/css/admin/crud-pages.css?v=1">
</head>
<body>
<div class="dashboard-layout">
    <?php include(__DIR__ . "/../../includes/sidebar.php"); ?>

    <main class="main-content">
        <?php include(__DIR__ . "/../../includes/topbar.php"); ?>

        <div class="admin-page-shell">
            <div class="page-heading">
                <div>
                    <a href="/paginas/admin/productos.php" class="back-link">
                        <i class="bi bi-arrow-left"></i> Volver a productos
                    </a>
                    <h1>Editar producto</h1>
                    <p>Modifica el nombre, descripcion, precio y categoria del producto.</p>
                </div>
            </div>

            <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'error'): ?>
                <div class="alert alert-danger border-0 rounded-4 shadow-sm">
                    No se pudo actualizar el producto. Revisa los datos e intentalo de nuevo.
                </div>
            <?php endif; ?>

            <div class="form-card">
                <form action="/php/controlador/productos/actualizar.php" method="POST">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($producto['id']) ?>">

                    <div class="row g-4">
                        <div class="col-md-8">
                            <label for="producto-nombre" class="form-label">Nombre del producto</label>
                            <input id="producto-nombre" type="text" name="nombre" class="form-control form-control-lg"
                                   value="<?= htmlspecialchars($producto['nombre'] ?? '') ?>" required>
                        </div>

                        <div class="col-md-4">
                            <label for="producto-precio" class="form-label">Precio</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text">$</span>
                                <input id="producto-precio" type="number" step="0.01" min="0" name="precio" class="form-control"
                                       value="<?= htmlspecialchars($producto['precio'] ?? 0) ?>" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="producto-categoria" class="form-label">Categoria</label>
                            <input id="producto-categoria" type="text" name="categoria" class="form-control form-control-lg"
                                   value="<?= htmlspecialchars($producto['categoria'] ?? '') ?>" required>
                        </div>

                        <div class="col-12">
                            <label for="producto-descripcion" class="form-label">Descripcion</label>
                            <textarea id="producto-descripcion" name="descripcion" class="form-control form-control-lg" rows="5"><?= htmlspecialchars($producto['descripcion'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="/paginas/admin/productos.php" class="btn btn-light">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check-circle"></i> Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
