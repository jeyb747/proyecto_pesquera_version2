<?php
session_start();
require_once(__DIR__ . "/../../modelo/conexion.php");

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    header("Location: /paginas/admin/productos.php?mensaje=error");
    exit();
}

$id = intval($_POST['id']);
$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$precio = floatval($_POST['precio'] ?? 0);
$categoria = trim($_POST['categoria'] ?? '');

if ($id <= 0 || $nombre === '' || $precio < 0 || $categoria === '') {
    header("Location: /paginas/admin/editar_producto.php?id=$id&mensaje=error");
    exit();
}

$sql = "UPDATE productos
        SET nombre = ?, descripcion = ?, precio = ?, categoria = ?
        WHERE id = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssdsi", $nombre, $descripcion, $precio, $categoria, $id);

if ($stmt->execute()) {
    header("Location: /paginas/admin/productos.php?mensaje=actualizado");
} else {
    header("Location: /paginas/admin/editar_producto.php?id=$id&mensaje=error");
}

exit();
