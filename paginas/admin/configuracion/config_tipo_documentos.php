<?php
session_start();
require_once(__DIR__ . "/../../../php/modelo/conexion.php");

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /index.php");
    exit();
}

// Obtener todos los tipos de documentos registrados
$query = "SELECT * FROM tipo_documento ORDER BY id ASC";
$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración Tipos de Documento | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/admin.css">
</head>
<body>

<div class="wrapper d-flex">
    <?php include(__DIR__ . "/../../../includes/sidebar.php"); ?>

    <div class="main-content flex-grow-1 p-4">
        <?php include(__DIR__ . "/../../../includes/topbar.php"); ?>

        <div class="content-container-max mx-auto mt-4" style="max-width: 800px;">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <a href="gestionar_usuarios.php" class="btn btn-link link-secondary p-0 text-decoration-none mb-1 small">
                        <i class="bi bi-arrow-left"></i> Volver a Usuarios
                    </a>
                    <h2 class="h4 fw-bold text-dark mb-0">Configuración: Tipos de Documento</h2>
                    <p class="text-muted small mb-0">Administra las opciones maestros que se vinculan a la tabla de usuarios.</p>
                </div>
            </div>

            <?php if (isset($_GET['status'])): ?>
                <?php if ($_GET['status'] == 'creado'): ?>
                    <div class="alert alert-success alert-dismissible fade show rounded-3 small" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> Tipo de documento añadido con éxito.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php elseif ($_GET['status'] == 'eliminado'): ?>
                    <div class="alert alert-warning alert-dismissible fade show rounded-3 small" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> El registro ha sido eliminado del sistema.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="row g-4">
                <div class="col-12 col-md-5">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h5 class="fw-bold text-dark h6 mb-3">Agregar Nuevo Tipo</h5>
                        <form action="/php/controlador/admin/config_tipo_documentos.php?accion=crear" method="POST">
                            <div class="mb-3">
                                <label class="form-label text-secondary small fw-medium">Nombre Completo del Tipo</label>
                                <input 
                                    type="text" 
                                    name="nombre_documento" 
                                    class="form-control rounded-3" 
                                    placeholder="Ej. Cédula de Ciudadanía (C.C.)" 
                                    required
                                >
                            </div>
                            <button type="submit" class="btn btn-primary w-100 rounded-pill fw-medium btn-sm py-2">
                                <i class="bi bi-plus-lg me-1"></i> Guardar en Tabla
                            </button>
                        </form>
                    </div>
                </div>

                <div class="col-12 col-md-7">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-secondary small text-uppercase">
                                    <tr>
                                        <th class="px-4 py-3">Nombre en Base de Datos</th>
                                        <th class="px-4 py-3 text-end" style="width: 20%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="text-dark">
                                    <?php if ($resultado && mysqli_num_rows($resultado) > 0): ?>
                                        <?php while ($row = mysqli_fetch_assoc($resultado)): ?>
                                            <tr>
                                                <td class="px-4 small fw-medium text-dark"><?php echo htmlspecialchars($row['nombre']); ?></td>
                                                <td class="px-4 text-end">
                                                    <button 
                                                        class="btn btn-sm btn-outline-danger rounded-circle" 
                                                        title="Eliminar Tipo Maestro"
                                                        onclick="confirmarEliminacionDoc(<?php echo $row['id']; ?>)"
                                                    >
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="2" class="text-center py-4 text-muted small">No hay tipos de documento registrados.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function confirmarEliminacionDoc(id) {
        if(confirm("¿Estás seguro de eliminar este tipo de documento?\n\n¡Atención!: Si hay usuarios vinculados a este tipo de ID, sus registros pasarán a quedar en blanco o sin tipo asignado debido a la integridad de la base de datos.")) {
            window.location.href = "/php/controlador/admin/config_tipo_documentos.php?accion=eliminar&id=" + id;
        }
    }
</script>
</body>
</html>
