<?php
session_start();
require_once(__DIR__ . "/../../../php/modelo/conexion.php");

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /index.php");
    exit();
}

// 🔎 1. DETECTAR COLUMNAS DINÁMICAS DE USUARIOS
$columnas_query = mysqli_query($conexion, "DESCRIBE usuarios");
$columnas_usuarios = [];
while ($col = mysqli_fetch_assoc($columnas_query)) {
    $columnas_usuarios[] = $col['Field'];
}
$columnas_visibles_usuarios = array_values(array_filter($columnas_usuarios, function ($col) {
    return $col !== 'id' && strpos($col, 'id_') !== 0 && substr($col, -3) !== '_id';
}));

// 📊 2. CONSULTA DE REGISTROS DE USUARIOS
$query_usuarios = "SELECT * FROM usuarios ORDER BY id ASC";
$resultado_usuarios = mysqli_query($conexion, $query_usuarios);

// 🗂️ 3. CONSULTA DE REGISTROS DE TIPOS DE DOCUMENTO (Para mapear nombres y llenar el select)
$query_docs = "SELECT * FROM tipo_documento ORDER BY id ASC";
$resultado_docs = mysqli_query($conexion, $query_docs);
$tipos_documento_lista = [];
if ($resultado_docs) {
    while ($row = mysqli_fetch_assoc($resultado_docs)) {
        $tipos_documento_lista[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Tabla Usuarios | Admin</title>
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
            
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <a href="../configuracion_sistema.php" class="btn btn-link link-secondary p-0 text-decoration-none mb-1 small">
                        <i class="bi bi-arrow-left"></i> Volver a Configuración
                    </a>
                    <h2 class="h4 fw-bold text-dark mb-0">Estructura de Tabla: Usuarios</h2>
                    <p class="text-muted small mb-0">Administra las credenciales, datos de contacto y la estructura física de la tabla.</p>
                </div>
                
                <div class="d-flex flex-wrap gap-2">
                    <a href="config_tipo_documentos.php" class="btn btn-outline-secondary rounded-pill fw-medium px-3">
                        <i class="bi bi-gear me-1"></i> Config. Tipos de Documento
                    </a>
                    <button class="btn btn-outline-danger rounded-pill fw-medium px-3" data-bs-toggle="modal" data-bs-target="#modalEliminarColumna">
                        <i class="bi bi-layout-sidebar-reverse me-1"></i> Eliminar Columna
                    </button>
                    <button class="btn btn-outline-primary rounded-pill fw-medium px-3" data-bs-toggle="modal" data-bs-target="#modalNuevaColumna">
                        <i class="bi bi-layout-sidebar me-1"></i> Nueva Columna
                    </button>
                    <button class="btn btn-primary rounded-pill fw-medium px-4" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario">
                        <i class="bi bi-person-plus-fill me-1"></i> Nuevo Usuario
                    </button>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-secondary small text-uppercase">
                            <tr>
                                <?php foreach ($columnas_visibles_usuarios as $columna): ?>
                                    <th class="px-4 py-3"><?php echo htmlspecialchars($columna); ?></th>
                                <?php endforeach; ?>
                                <th class="px-4 py-3 text-end" style="width: 10%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-dark">
                            <?php 
                            if ($resultado_usuarios && mysqli_num_rows($resultado_usuarios) > 0): 
                                while ($user = mysqli_fetch_assoc($resultado_usuarios)): 
                            ?>
                                <tr>
                                    <?php foreach ($columnas_visibles_usuarios as $columna): ?>
                                        <td class="px-4 small">
                                            <?php 
                                            if ($columna == 'id') {
                                                echo '<span class="fw-medium text-secondary">' . $user[$columna] . '</span>';
                                            } elseif ($columna == 'nombre') {
                                                echo '<span class="fw-semibold text-dark">' . htmlspecialchars($user[$columna]) . '</span>';
                                            } elseif ($columna == 'estado') {
                                                echo $user[$columna] == 1 ? '<span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1.5 fw-semibold small">Activo</span>' : '<span class="badge bg-danger-subtle text-danger rounded-pill px-2.5 py-1.5 fw-semibold small">Inactivo</span>';
                                            } elseif ($columna == 'id_rol') {
                                                echo '<span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-1.5 fw-medium small">' . htmlspecialchars($user[$columna]) . '</span>';
                                            } elseif ($columna == 'id_tipo_documento') {
                                                // Muestra el nombre real del documento en lugar de sólo el ID numérico
                                                $nombre_doc = 'No asignado';
                                                foreach ($tipos_documento_lista as $td) {
                                                    if ($td['id'] == $user[$columna]) {
                                                        $nombre_doc = $td['nombre'];
                                                        break;
                                                    }
                                                }
                                                echo '<span class="text-primary fw-medium">' . htmlspecialchars($nombre_doc) . '</span>';
                                            } else {
                                                echo htmlspecialchars($user[$columna] ?? ''); 
                                            }
                                            ?>
                                        </td>
                                    <?php endforeach; ?>
                                    <td class="px-4 text-end">
                                        <button class="btn btn-sm btn-outline-danger rounded-circle" 
                                                title="Eliminar" 
                                                onclick="confirmarEliminacion(<?php echo $user['id']; ?>)">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php 
                                endwhile; 
                            else: 
                            ?>
                                <tr>
                                    <td colspan="<?php echo count($columnas_visibles_usuarios) + 1; ?>" class="text-center py-4 text-muted small">No se encontraron usuarios registrados en la base de datos.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalNuevoUsuario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <h5 class="fw-bold text-dark mb-0">Registrar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/php/controlador/admin/procesar_usuario.php?accion=crear" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-medium">Nombre Completo</label>
                        <input type="text" name="nombre" class="form-control rounded-3" required>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label text-secondary small fw-medium">Tipo de Documento</label>
                            <select name="id_tipo_documento" class="form-select rounded-3" required>
                                <option value="" disabled selected>Selecciona...</option>
                                <?php foreach ($tipos_documento_lista as $td): ?>
                                    <option value="<?php echo $td['id']; ?>"><?php echo htmlspecialchars($td['nombre']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-secondary small fw-medium">Número de Documento</label>
                            <input type="text" name="numero_documento" class="form-control rounded-3" placeholder="Ej. 1023456" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-medium">Correo Electrónico</label>
                        <input type="email" name="correo" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-medium">Teléfono</label>
                        <input type="text" name="telefono" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-medium">Contraseña</label>
                        <input type="password" name="password" class="form-control rounded-3" required>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label text-secondary small fw-medium">Rol ID</label>
                            <input type="number" name="id_rol" class="form-control rounded-3" value="1" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-secondary small fw-medium">Estado</label>
                            <select name="estado" class="form-select rounded-3" required>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0 d-flex gap-2">
                    <button type="button" class="btn btn-light rounded-pill w-100 fw-medium" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill w-100 fw-medium">Guardar Registro</button>
                </div>
            </form>
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
            <form action="/php/controlador/admin/procesar_usuario.php?accion=crear_columna" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-medium">Nombre de la Columna (Sin espacios ni eñes)</label>
                        <input type="text" name="nombre_columna" class="form-control rounded-3" placeholder="ej. direccion, edad" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-secondary small fw-medium">Tipo de Dato SQL</label>
                        <select name="tipo_columna" class="form-select rounded-3" required>
                            <option value="VARCHAR(255) DEFAULT NULL">Texto (VARCHAR 255)</option>
                            <option value="TEXT DEFAULT NULL">Texto Largo (TEXT)</option>
                            <option value="INT DEFAULT 0">Número Entero (INT)</option>
                            <option value="DATE DEFAULT NULL">Fecha (DATE)</option>
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
            <form action="/php/controlador/admin/procesar_usuario.php?accion=eliminar_columna" method="POST">
                <div class="modal-body p-4">
                    <div class="alert alert-danger rounded-3 small mb-3">
                        <strong>¡Cuidado!</strong> Eliminar una columna borrará permanentemente todos los datos contenidos en ella.
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-secondary small fw-medium">Selecciona la columna a DESTRUIR</label>
                        <select name="nombre_columna" class="form-select rounded-3" required>
                            <option value="" disabled selected>Selecciona...</option>
                            <?php 
                            foreach ($columnas_usuarios as $col) {
                                if (!in_array($col, ['id', 'nombre', 'correo', 'password', 'id_rol', 'id_tipo_documento', 'numero_documento', 'telefono', 'estado'])) {
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function confirmarEliminacion(id) {
        if(confirm("¿Eliminar este registro de usuario?")) {
            window.location.href = "/php/controlador/admin/procesar_usuario.php?accion=eliminar&id=" + id;
        }
    }
</script>
</body>
</html>
