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

// 🔒 Lista negra: Estas tablas NO aparecerán para borrar ya que son el núcleo de tu app
$tablas_protegidas = ['usuarios', 'reservas', 'productos', 'pedidos', 'domicilios', 'roles'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Tabla Estructural | Admin</title>
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

        <div class="content-container-max mx-auto mt-4" style="max-width: 600px;">
            
            <a href="../configuracion_sistema.php" class="btn btn-link link-secondary p-0 text-decoration-none mb-2 small">
                <i class="bi bi-arrow-left"></i> Volver a Configuración
            </a>

            <div class="card border-0 shadow rounded-4 p-4 text-center border border-danger-subtle" style="border-style: dashed !important;">
                <div class="mx-auto bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="bi bi-trash3-fill fs-3"></i>
                </div>
                
                <h3 class="h4 fw-bold text-dark mb-2">Eliminar Tabla</h3>
                <p class="text-muted small px-3">Remueve tablas obsoletas o temporales de la base de datos.<br><strong class="text-danger">Acción irreversible.</strong></p>
                
                <hr class="my-4 text-muted opacity-25">

                <form action="/php/controlador/admin/procesar_eliminar_tabla.php" method="POST" id="formEliminar">
                    <div class="text-start mb-4">
                        <label class="form-label text-secondary small fw-medium">Selecciona la tabla a destruir:</label>
                        <select name="nombre_tabla" class="form-select rounded-3" required id="selectTabla">
                            <option value="" disabled selected>Selecciona una tabla...</option>
                            <?php 
                            foreach ($tablas_db as $tabla) {
                                // Solo muestra las tablas que NO están protegidas
                                if (!in_array($tabla, $tablas_protegidas)) {
                                    echo "<option value='$tabla'>$tabla</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <button type="button" class="btn btn-danger rounded-pill fw-semibold py-2.5 w-100" onclick="solicitarConfirmacion()">
                        Ir a Eliminar <i class="bi bi-exclamation-triangle ms-1"></i>
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function solicitarConfirmacion() {
        const tabla = document.getElementById('selectTabla').value;
        
        if (!tabla) {
            alert("Por favor, selecciona una tabla válida primero.");
            return;
        }

        if (confirm("⚠️ ¡ADVERTENCIA DE SEGURIDAD! ⚠️\n\n¿Estás completamente seguro de que deseas ELIMINAR la tabla '" + tabla + "' de forma permanente?\n\nTodos los datos y registros almacenados dentro de ella se perderán para siempre.")) {
            document.getElementById('formEliminar').submit();
        }
    }
</script>
</body>
</html>