<?php
session_start();
require_once(__DIR__ . "/../../modelo/conexion.php");

// Proteger que solo el admin pueda cambiar esto
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $estado = intval($_POST['estado']); // Recibe 1 (Activo) o 0 (Inactivo)

    // Actualizar el estado en la base de datos
    $sql = "UPDATE usuarios SET estado = $estado WHERE id = $id";
    
    if (mysqli_query($conexion, $sql)) {
        // Redirige de vuelta a la vista de usuarios con un mensaje de éxito
        header("Location: /paginas/admin/usuarios.php?mensaje=1");
    } else {
        echo "Error al actualizar el estado: " . mysqli_error($conexion);
    }
    exit();
}
?>