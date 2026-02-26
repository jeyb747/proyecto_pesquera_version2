<?php
require_once "../../php/modelo/conexion.php";

$id = $_GET['id'];

$sql = "SELECT * FROM productos WHERE id=$id";
$resultado = $conexion->query($sql);
$producto = $resultado->fetch_assoc();
?>

<form action="../../php/controlador/productos/actualizar.php" method="POST">

    <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">

    <input type="text" name="nombre" value="<?php echo $producto['nombre']; ?>"><br>
    <input type="text" name="descripcion" value="<?php echo $producto['descripcion']; ?>"><br>
    <input type="number" name="precio" value="<?php echo $producto['precio']; ?>"><br>
    <input type="text" name="categoria" value="<?php echo $producto['categoria']; ?>"><br>

    <button type="submit">Actualizar</button>

</form>