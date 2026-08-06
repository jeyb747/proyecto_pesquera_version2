<?php
session_start();
require_once(__DIR__ . "/../../../php/modelo/conexion.php");

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /index.php");
    exit();
}

// 🔎 1. DETECTAR COLUMNAS DINÁMICAS DE DOMICILIOS
$columnas_query = mysqli_query($conexion, "DESCRIBE domicilios");
$columnas_domicilios = [];
while ($col = mysqli_fetch_assoc($columnas_query)) {
    $columnas_domicilios[] = $col['Field'];
}
$columnas_visibles_domicilios = array_values(array_filter($columnas_domicilios, function ($col) {
    return $col !== 'id' && strpos($col, 'id_') !== 0 && substr($col, -3) !== '_id';
}));

// 📊 2. CONSULTA DE REGISTROS
$query_domicilios = "SELECT * FROM domicilios ORDER BY id DESC";
$resultado_domicilios = mysqli_query($conexion, $query_domicilios);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Tabla Domicilios | Admin</title>
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
                    <h2 class="h4 fw-bold text-dark mb-0">Estructura de Tabla: Domicilios</h2>
                    <p class="text-muted small mb-0">Gestiona las rutas de reparto, repartidores asignados y altera las columnas físicas de la tabla.</p>
                </div>
                
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-danger rounded-pill fw-medium px-3" data-bs-toggle="modal" data-bs-target="#modalEliminarColumna">
                        <i class="bi bi-layout-sidebar-reverse me-1"></i> Eliminar Columna
                    </button>
                    <button class="btn btn-outline-primary rounded-pill fw-medium px-3" data-bs-toggle="modal" data-bs-target="#modalNuevaColumna">
                        <i class="bi bi-layout-sidebar me-1"></i> Nueva Columna
                    </button>
                    <button class="btn btn-primary rounded-pill fw-medium px-4" data-bs-toggle="modal" data-bs-target="#modalNuevoDomicilio">
                        <i class="bi bi-truck me-1"></i> Nuevo Domicilio
                    </button>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-secondary small text-uppercase">
                            <tr>
                                <?php foreach ($columnas_visibles_domicilios as $columna): ?>
                                    <th class="px-4 py-3"><?php echo htmlspecialchars($columna); ?></th>
                                <?php endforeach; ?>
                                <th class="px-4 py-3 text-end" style="width: 10%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-dark">
                            <?php 
                            if ($resultado_domicilios && mysqli_num_rows($resultado_domicilios) > 0): 
                                while ($dom = mysqli_fetch_assoc($resultado_domicilios)): 
                            ?>
                                <tr>
                                    <?php foreach ($columnas_visibles_domicilios as $columna): ?>
                                        <td class="px-4 small">
                                            <?php 
                                            if ($columna == 'id') {
                                                echo '<span class="fw-medium text-secondary">#' . $dom[$columna] . '</span>';
                                            } elseif ($columna == 'pedido_id' || $columna == 'id_pedido') {
                                                echo '<span class="fw-semibold text-primary">Pedido ' . htmlspecialchars($dom[$columna]) . '</span>';
                                            } elseif ($columna == 'direccion') {
                                                echo '<span class="text-dark text-truncate d-inline-block" style="max-width: 180px;" title="'.htmlspecialchars($dom[$columna]).'">' . htmlspecialchars($dom[$columna]) . '</span>';
                                            } elseif ($columna == 'estado') {
                                                $est = strtolower($dom[$columna]);
                                                $cls = 'secondary';
                                                if ($est == 'entregado') $cls = 'success';
                                                elseif ($est == 'en camino' || $est == 'despachado') $cls = 'info';
                                                elseif ($est == 'pendiente' || $est == 'preparación') $cls = 'warning';
                                                elseif ($est == 'cancelado') $cls = 'danger';
                                                
                                                echo "<span class='badge bg-{$cls}-subtle text-{$cls} rounded-pill px-2.5 py-1.5 fw-semibold small text-capitalize'>$est</span>";
                                            } else {
                                                echo htmlspecialchars($dom[$columna] ?? ''); 
                                            }
                                            ?>
                                        </td>
                                    <?php endforeach; ?>
                                    <td class="px-4 text-end">
                                        <button class="btn btn-sm btn-outline-danger rounded-circle" 
                                                title="Eliminar" 
                                                onclick="confirmarEliminacion(<?php echo $dom['id']; ?>)">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php 
                                endwhile; 
                            else: 
                            ?>
                                <tr>
                                    <td colspan="<?php echo count($columnas_visibles_domicilios) + 1; ?>" class="text-center py-4 text-muted small">No se encontraron entregas a domicilio registradas.</td>
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
            <form action="/php/controlador/admin/procesar_domicilio.php?accion=crear_columna" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-medium">Nombre de la Columna (Sin espacios ni eñes)</label>
                        <input type="text" name="nombre_columna" class="form-control rounded-3" placeholder="ej. repartidor, telefono_contacto, costo_envio" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-secondary small fw-medium">Tipo de Dato SQL</label>
                        <select name="tipo_columna" class="form-select rounded-3" required>
                            <option value="VARCHAR(255) DEFAULT NULL">Texto (VARCHAR 255)</option>
                            <option value="TEXT DEFAULT NULL">Texto Largo (TEXT)</option>
                            <option value="DECIMAL(10,2) DEFAULT 0.00">Decimal (Costos/Precios)</option>
                            <option value="INT DEFAULT 0">Número Entero (INT)</option>
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
            <form action="/php/controlador/admin/procesar_domicilio.php?accion=eliminar_columna" method="POST">
                <div class="modal-body p-4">
                    <div class="alert alert-danger rounded-3 small mb-3">
                        <strong>¡Cuidado!</strong> Al borrar la columna se destruirán permanentemente todos los datos de logística en ella contenidos.
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-secondary small fw-medium">Selecciona la columna a DESTRUIR</label>
                        <select name="nombre_columna" class="form-select rounded-3" required>
                            <option value="" disabled selected>Selecciona...</option>
                            <?php 
                            foreach ($columnas_domicilios as $col) {
                                if (!in_array($col, ['id', 'pedido_id', 'id_pedido', 'direccion', 'telefono', 'estado', 'fecha'])) {
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

<div class="modal fade" id="modalNuevoDomicilio" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <h5 class="fw-bold text-dark mb-0">Asignar Entrega a Domicilio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/php/controlador/admin/procesar_domicilio.php?accion=crear" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-medium">ID Pedido Asociado</label>
                        <input type="number" name="pedido_id" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-medium">Dirección de Envío Completa</label>
                        <input type="text" name="direccion" class="form-control rounded-3" placeholder="Calle, Carrera, Barrio, Apto" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label text-secondary small fw-medium">Teléfono Destino</label>
                            <input type="text" name="telefono" class="form-control rounded-3" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label text-secondary small fw-medium">Estado Inicial</label>
                            <select name="estado" class="form-select rounded-3" required>
                                <option value="pendiente">Pendiente</option>
                                <option value="en camino">En Camino</option>
                                <option value="entregado">Entregado</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0 d-flex gap-2">
                    <button type="button" class="btn btn-light rounded-pill w-100 fw-medium" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill w-100 fw-medium">Guardar Ruta</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function confirmarEliminacion(id) {
        if(confirm("¿Eliminar este registro de reparto de forma permanente?")) {
            window.location.href = "/php/controlador/admin/procesar_domicilio.php?accion=eliminar&id=" + id;
        }
    }
</script>
</body>
</html>
