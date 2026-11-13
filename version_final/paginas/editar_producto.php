<?php
require_once "../php/modelo/conexion.php";

$id = $_GET['id'];

$sql = "SELECT * FROM productos WHERE id=$id";
$resultado = $conexion->query($sql);
$producto = $resultado->fetch_assoc();
?>

<form action="../php/controlador/productos/actualizar.php" method="POST">

    <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">

    <label for="producto-nombre">Nombre del producto</label>
    <input id="producto-nombre" type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre'] ?? '', ENT_QUOTES, 'UTF-8') ?>"><br>

    <label for="producto-descripcion">Descripción</label>
    <input id="producto-descripcion" type="text" name="descripcion" value="<?= htmlspecialchars($producto['descripcion'] ?? '', ENT_QUOTES, 'UTF-8') ?>"><br>

    <label for="producto-precio">Precio</label>
    <input id="producto-precio" type="number" name="precio" value="<?= htmlspecialchars((string) ($producto['precio'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"><br>

    <label for="producto-categoria">Categoría</label>
    <input id="producto-categoria" type="text" name="categoria" value="<?= htmlspecialchars($producto['categoria'] ?? '', ENT_QUOTES, 'UTF-8') ?>"><br>

    <button type="submit">Actualizar</button>

</form>
