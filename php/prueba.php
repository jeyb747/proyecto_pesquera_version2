<?php
require_once(__DIR__ . "/modelo/conexion.php");

// Verificar conexión
if ($conexion) {
    echo "Base de datos conectada correctamente 👍";
} else {
    echo "Error en la conexión ❌";
}
?>