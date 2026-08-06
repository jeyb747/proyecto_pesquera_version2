<?php
session_start();
require_once(__DIR__ . "/../../../php/modelo/conexion.php");

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /index.php");
    exit();
}

// 🔎 1. DETECTAR COLUMNAS DINÁMICAS DE PRODUCTOS
$columnas_query = mysqli_query($conexion, "DESCRIBE productos");
$columnas_productos = [];
while ($col = mysqli_fetch_assoc($columnas_query)) {
    $columnas_productos[] = $col['Field'];
}
$columnas_visibles_productos = array_values(array_filter($columnas_productos, function ($col) {
    return $col !== 'id' && strpos($col, 'id_') !== 0 && substr($col, -3) !== '_id';
}));

// 📊 2. CONSULTA DE REGISTROS
$query_productos = "SELECT * FROM productos ORDER BY id ASC";
$resultado_productos = mysqli_query($conexion, $query_productos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Tabla Productos | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/admin.css">
    <link rel="stylesheet" href="/css/admin/usuarios.css">
</head>
<body>

<div class="wrapper d-flex">
    <?php include(__DIR__ . "/../../../includes/sidebar.php"); ?>

    <div class="main-content flex-grow-1 p-4">
        <?php include(__DIR__ . "/../../../includes/topbar.php"); ?>

        <div class="content-container-max mx-auto mt-4">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <a href="../configuracion_sistema.php" class="btn btn-link link-secondary p-0 text-decoration-none mb-1 small">
                        <i class="bi bi-arrow-left"></i> Volver a Configuración
                    </a>
                    <h2 class="h4 fw-bold text-dark mb-0">Estructura de Tabla: Productos</h2>
                    <p class="text-muted small mb-0">Administra el inventario de la tienda y modifica la estructura física de campos al vuelo.</p>
                </div>
                
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-danger rounded-pill fw-medium px-3" data-bs-toggle="modal" data-bs-target="#modalEliminarColumna">
                        <i class="bi bi-layout-sidebar-reverse me-1"></i> Eliminar Columna
                    </button>
                    <button class="btn btn-outline-primary rounded-pill fw-medium px-3" data-bs-toggle="modal" data-bs-target="#modalNuevaColumna">
                        <i class="bi bi-layout-sidebar me-1"></i> Nueva Columna
                    </button>
                    <button class="btn btn-primary rounded-pill fw-medium px-4" data-bs-toggle="modal" data-bs-target="#modalNuevoProducto">
                        <i class="bi bi-box-seam-fill me-1"></i> Nuevo Producto
                    </button>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-secondary small text-uppercase">
                            <tr>
                                <?php foreach ($columnas_visibles_productos as $columna): ?>
                                    <th class="px-4 py-3"><?php echo htmlspecialchars($columna); ?></th>
                                <?php endforeach; ?>
                                <th class="px-4 py-3 text-end" style="width: 10%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-dark">
                            <?php 
                            if ($resultado_productos && mysqli_num_rows($resultado_productos) > 0): 
                                while ($prod = mysqli_fetch_assoc($resultado_productos)): 
                            ?>
                                <tr>
                                    <?php foreach ($columnas_visibles_productos as $columna): ?>
                                        <td class="px-4 small">
                                            <?php 
                                            if ($columna == 'id') {
                                                echo '<span class="fw-medium text-secondary">' . $prod[$columna] . '</span>';
                                            } elseif ($columna == 'nombre') {
                                                echo '<span class="fw-semibold text-dark">' . htmlspecialchars($prod[$columna]) . '</span>';
                                            } elseif ($columna == 'precio') {
                                                echo '<span class="text-dark fw-bold">$' . number_format($prod[$columna], 2) . '</span>';
                                            } elseif ($columna == 'stock') {
                                                $stock_num = intval($prod[$columna]);
                                                $stock_class = ($stock_num > 10) ? 'text-success fw-medium' : (($stock_num > 0) ? 'text-warning fw-medium' : 'text-danger fw-bold');
                                                echo '<span class="' . $stock_class . '">' . $stock_num . ' uds</span>';
                                            } elseif ($columna == 'estado') {
                                                echo $prod[$columna] == 1 ? '<span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1.5 fw-semibold small">Disponible</span>' : '<span class="badge bg-danger-subtle text-danger rounded-pill px-2.5 py-1.5 fw-semibold small">Agotado</span>';
                                            } else {
                                                echo htmlspecialchars($prod[$columna] ?? ''); 
                                            }
                                            ?>
                                        </td>
                                    <?php endforeach; ?>
                                    <td class="px-4 text-end">
                                        <button class="btn btn-sm btn-outline-danger rounded-circle" 
                                                title="Eliminar" 
                                                onclick="confirmarEliminacion(<?php echo $prod['id']; ?>)">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php 
                                endwhile; 
                            else: 
                            ?>
                                <tr>
                                    <td colspan="<?php echo count($columnas_visibles_productos) + 1; ?>" class="text-center py-4 text-muted small">No se encontraron productos registrados en el inventario.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalNuevaColumna" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-layout-sidebar text-primary me-2"></i>Añadir Columna Física</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/php/controlador/admin/procesar_producto.php?accion=crear_columna" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-medium">Nombre de la Columna (Sin espacios ni eñes)</label>
                        <input type="text" name="nombre_columna" class="form-control rounded-3" placeholder="ej. codigo_barras, marca, categoria" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-secondary small fw-medium">Tipo de Dato SQL</label>
                        <select name="tipo_columna" class="form-select rounded-3" required>
                            <option value="VARCHAR(255) DEFAULT NULL">Texto (VARCHAR 255)</option>
                            <option value="TEXT DEFAULT NULL">Texto Largo (TEXT)</option>
                            <option value="INT DEFAULT 0">Número Entero (INT)</option>
                            <option value="DECIMAL(10,2) DEFAULT 0.00">Decimal (Dinero/Precio)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0 d-flex gap-2">
                    <button type="button" class="btn btn-light rounded-pill w-100 fw-medium" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill w-100 fw-medium">Ejecutar ALTER TABLE</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEliminarColumna" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <h5 class="fw-bold text-danger mb-0"><i class="bi bi-exclamation-triangle-fill me-2"></i>Eliminar Columna Física</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/php/controlador/admin/procesar_producto.php?accion=eliminar_columna" method="POST">
                <div class="modal-body p-4">
                    <div class="alert alert-danger rounded-3 small mb-3">
                        <strong>¡Cuidado!</strong> Eliminar una columna borrará permanentemente todos los datos de inventario contenidos en ella.
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-secondary small fw-medium">Selecciona la columna a DESTRUIR</label>
                        <select name="nombre_columna" class="form-select rounded-3" required>
                            <option value="" disabled selected>Selecciona...</option>
                            <?php 
                            foreach ($columnas_productos as $col) {
                                if (!in_array($col, ['id', 'nombre', 'descripcion', 'precio', 'stock', 'estado'])) {
                                    echo "<option value='$col'>$col</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0 d-flex gap-2">
                    <button type="button" class="btn btn-light rounded-pill w-100 fw-medium" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger rounded-pill w-100 fw-medium">Eliminar para siempre</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalNuevoProducto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <h5 class="fw-bold text-dark mb-0">Registrar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/php/controlador/admin/procesar_producto.php?accion=crear" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-medium">Nombre del Producto</label>
                        <input type="text" name="nombre" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-medium">Descripción</label>
                        <textarea name="descripcion" class="form-control rounded-3" rows="2"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label text-secondary small fw-medium">Precio ($)</label>
                            <input type="number" step="0.01" name="precio" class="form-control rounded-3" placeholder="0.00" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label text-secondary small fw-medium">Stock Inicial</label>
                            <input type="number" name="stock" class="form-control rounded-3" value="0" required>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-secondary small fw-medium">Estado Comercial</label>
                        <select name="estado" class="form-select rounded-3" required>
                            <option value="1">Disponible / Activo</option>
                            <option value="0">No Disponible</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0 d-flex gap-2">
                    <button type="button" class="btn btn-light rounded-pill w-100 fw-medium" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill w-100 fw-medium">Guardar Producto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function confirmarEliminacion(id) {
        if(confirm("¿Eliminar de forma permanente este producto del sistema?")) {
            window.location.href = "/php/controlador/admin/procesar_producto.php?accion=eliminar&id=" + id;
        }
    }
</script>
</body>
</html>
