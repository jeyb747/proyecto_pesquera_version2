<?php
session_start();
require_once(__DIR__ . "/../../../php/modelo/conexion.php");

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /index.php");
    exit();
}

// 🔎 LISTAR TABLAS EXISTENTES EN LA BASE DE DATOS
$tablas_db = [];
$resultado = mysqli_query($conexion, "SHOW TABLES");
if ($resultado) {
    while ($fila = mysqli_fetch_row($resultado)) {
        $tablas_db[] = $fila[0];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creador de Tablas Dinámicas | Admin</title>
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
                    <h2 class="h4 fw-bold text-dark mb-0">Constructor Estructural de Tablas</h2>
                    <p class="text-muted small mb-0">Crea nuevas tablas físicas directamente en tu base de datos SQL de manera automatizada.</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-12 col-md-7">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-plus-circle text-primary me-2"></i>Nueva Tabla Estructurada</h5>
                        
                        <form action="/php/controlador/admin/procesar_nueva_tabla.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label text-secondary small fw-medium">Nombre de la Tabla (Ej: proveedores, facturas)</label>
                                <input type="text" name="nombre_tabla" class="form-control rounded-3" placeholder="Ingresa el nombre en minúsculas y sin espacios" required>
                            </div>

                            <div class="alert alert-light border rounded-3 p-3 mb-4">
                                <div class="d-flex gap-2">
                                    <i class="bi bi-info-circle text-primary mt-0.5"></i>
                                    <p class="mb-0 small text-secondary">
                                        Por defecto, el sistema creará de forma automática la columna llave primaria **`id`** (INT AUTO_INCREMENT PRIMARY KEY).
                                    </p>
                                </div>
                            </div>

                            <h6 class="fw-bold text-dark mb-2">Columnas Adicionales Iniciales</h6>
                            <div id="contenedor-columnas">
                                <div class="row g-2 mb-2 columna-item">
                                    <div class="col-6">
                                        <input type="text" name="nombre_col[]" class="form-control form-control-sm rounded-3" placeholder="Nombre (ej: nombre_campo)" required>
                                    </div>
                                    <div class="col-6">
                                        <select name="tipo_col[]" class="form-select form-select-sm rounded-3" required>
                                            <option value="VARCHAR(255) DEFAULT NULL">Texto (VARCHAR 255)</option>
                                            <option value="TEXT DEFAULT NULL">Texto Largo (TEXT)</option>
                                            <option value="INT DEFAULT 0">Número Entero (INT)</option>
                                            <option value="DECIMAL(10,2) DEFAULT 0.00">Decimal / Moneda</option>
                                            <option value="DATE DEFAULT NULL">Fecha (DATE)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill mt-2" onclick="agregarCampo()">
                                <i class="bi bi-plus-lg me-1"></i> Agregar otra columna
                            </button>

                            <hr class="my-4 text-muted">

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary rounded-pill fw-medium px-4 w-100">
                                    <i class="bi bi-cpu me-1"></i> Ejecutar CREATE TABLE
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-12 col-md-5">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-database text-secondary me-2"></i>Tablas en Producción</h5>
                        <p class="text-muted small">Estado estructural actual de tu base de datos:</p>
                        
                        <div class="list-group list-group-flush border-top">
                            <?php foreach ($tablas_db as $tabla): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-0 small">
                                    <span class="text-dark fw-medium"><i class="bi bi-table text-primary me-2"></i><?php echo $tabla; ?></span>
                                    <span class="badge bg-light text-secondary border rounded-pill">Física</span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function agregarCampo() {
        const contenedor = document.getElementById('contenedor-columnas');
        const nuevaFila = document.createElement('div');
        nuevaFila.className = 'row g-2 mb-2 columna-item';
        nuevaFila.innerHTML = `
            <div class="col-6">
                <input type="text" name="nombre_col[]" class="form-control form-control-sm rounded-3" placeholder="Nombre" required>
            </div>
            <div class="col-5">
                <select name="tipo_col[]" class="form-select form-select-sm rounded-3" required>
                    <option value="VARCHAR(255) DEFAULT NULL">Texto (VARCHAR 255)</option>
                    <option value="TEXT DEFAULT NULL">Texto Largo (TEXT)</option>
                    <option value="INT DEFAULT 0">Número Entero (INT)</option>
                    <option value="DECIMAL(10,2) DEFAULT 0.00">Decimal / Moneda</option>
                    <option value="DATE DEFAULT NULL">Fecha (DATE)</option>
                </select>
            </div>
            <div class="col-1 d-flex align-items-center justify-content-center">
                <button type="button" class="btn btn-link link-danger p-0 border-0" onclick="this.closest('.columna-item').remove()">
                    <i class="bi bi-dash-circle-fill"></i>
                </button>
            </div>
        `;
        contenedor.appendChild(nuevaFila);
    }
</script>
</body>
</html>