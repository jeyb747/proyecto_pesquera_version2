<?php
session_start();
require_once(__DIR__ . "/../../../php/modelo/conexion.php");

// 🔒 PROTEGER ADMIN
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /index.php");
    exit();
}

// 📊 DETECCIÓN AUTOMÁTICA DE COLUMNAS
$columnas_query = mysqli_query($conexion, "DESCRIBE roles");
$columnas = [];
while ($col = mysqli_fetch_assoc($columnas_query)) {
    $columnas[] = $col['Field'];
}

// Asignamos dinámicamente según la cantidad de columnas reales que existan
$col_id     = isset($columnas[0]) ? $columnas[0] : 'id';
$col_nombre = isset($columnas[1]) ? $columnas[1] : 'nombre';

// Verificamos si existe una tercera columna para la descripción
$tiene_descripcion = isset($columnas[2]);
$col_descripcion   = $tiene_descripcion ? $columnas[2] : "''"; 

// Armamos la consulta adaptada
$query = "SELECT $col_id AS id, $col_nombre AS nombre, $col_descripcion AS descripcion FROM roles ORDER BY $col_id ASC";
$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Tabla Roles | Admin</title>
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
                    <h2 class="h4 fw-bold text-dark mb-0">Estructura de Tabla: Roles</h2>
                    <p class="text-muted small mb-0">Administra los niveles de acceso y perfiles del sistema.</p>
                </div>
                
                <button class="btn btn-primary rounded-pill fw-medium px-4" data-bs-toggle="modal" data-bs-target="#modalNuevoRol">
                    <i class="bi bi-plus-lg me-1"></i> Nuevo Rol
                </button>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-secondary small text-uppercase">
                            <tr>
                                <th class="py-3" style="width: 35%">Nombre del Rol</th>
                                <th class="py-3" style="width: 35%">Descripción / Permisos</th>
                                <th class="px-4 py-3 text-end" style="width: 15%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-dark">
                            <?php 
                            if ($resultado && mysqli_num_rows($resultado) > 0): 
                                while ($row = mysqli_fetch_assoc($resultado)): 
                            ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 fw-semibold">
                                            <?php echo htmlspecialchars($row['nombre']); ?>
                                        </span>
                                    </td>
                                    <td class="text-muted small">
                                        <?php echo $tiene_descripcion && !empty($row['descripcion']) ? htmlspecialchars($row['descripcion']) : '<em>Sin descripción disponible</em>'; ?>
                                    </td>
                                    <td class="px-4 text-end">
                                        <button class="btn btn-sm btn-outline-secondary rounded-circle me-1" 
                                                title="Editar" 
                                                onclick="abrirModalEditar(<?php echo $row['id']; ?>, '<?php echo addslashes($row['nombre']); ?>', '<?php echo addslashes($row['descripcion']); ?>')">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger rounded-circle" 
                                                title="Eliminar" 
                                                onclick="confirmarEliminacion(<?php echo $row['id']; ?>)">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php 
                                endwhile; 
                            else: 
                            ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted small">No se encontraron roles registrados en el sistema.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalNuevoRol" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <h5 class="fw-bold text-dark mb-0">Agregar Nuevo Rol</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/php/controlador/admin/procesar_rol.php?accion=crear" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-medium">Nombre Interno (slug)</label>
                        <input type="text" name="nombre_rol" class="form-control rounded-3" placeholder="ej. empleado, cliente" required>
                    </div>
                    <?php if ($tiene_descripcion): ?>
                    <div class="mb-0">
                        <label class="form-label text-secondary small fw-medium">Descripción del Rol</label>
                        <textarea name="descripcion" class="form-control rounded-3" rows="3" placeholder="Define brevemente el alcance de este rol..." required></textarea>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer border-0 p-4 pt-0 d-flex gap-2">
                    <button type="button" class="btn btn-light rounded-pill w-100 fw-medium" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill w-100 fw-medium">Guardar Rol</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarRol" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <h5 class="fw-bold text-dark mb-0">Modificar Rol</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/php/controlador/admin/procesar_rol.php?accion=editar" method="POST">
                <input type="hidden" name="id_rol" id="edit_id_rol" value="">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-medium">Nombre Interno</label>
                        <input type="text" name="nombre_rol" id="edit_nombre_rol" class="form-control rounded-3" required>
                    </div>
                    <?php if ($tiene_descripcion): ?>
                    <div class="mb-0">
                        <label class="form-label text-secondary small fw-medium">Descripción del Rol</label>
                        <textarea name="descripcion" id="edit_descripcion" class="form-control rounded-3" rows="3" required></textarea>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer border-0 p-4 pt-0 d-flex gap-2">
                    <button type="button" class="btn btn-light rounded-pill w-100 fw-medium" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill w-100 fw-medium">Actualizar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function abrirModalEditar(id, nombre, descripcion) {
        document.getElementById('edit_id_rol').value = id;
        document.getElementById('edit_nombre_rol').value = nombre;
        if (document.getElementById('edit_descripcion')) {
            document.getElementById('edit_descripcion').value = descripcion;
        }
        
        var modal = new Bootstrap.Modal(document.getElementById('modalEditarRol'));
        modal.show();
    }

    function confirmarEliminacion(id) {
        if(confirm("¿Estás seguro de que deseas eliminar este rol? Esto podría afectar a los usuarios que pertenezcan a este rango.")) {
            window.location.href = "/php/controlador/admin/procesar_rol.php?accion=eliminar&id=" + id;
        }
    }
</script>
</body>
</html>
