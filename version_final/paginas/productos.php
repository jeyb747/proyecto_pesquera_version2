<?php
require_once "../php/controlador/productos/listar.php";
?>

<h2>Agregar Producto</h2>

<form action="../php/controlador/productos/crear.php" method="POST">
    <label for="producto-nombre">Nombre</label>
    <input id="producto-nombre" type="text" name="nombre" placeholder="Nombre" required><br>
    <label for="producto-descripcion">Descripción</label>
    <input id="producto-descripcion" type="text" name="descripcion" placeholder="Descripción" required><br>
    <label for="producto-precio">Precio</label>
    <input id="producto-precio" type="number" name="precio" placeholder="Precio" required><br>
    <label for="producto-categoria">Categoría</label>
    <input id="producto-categoria" type="text" name="categoria" placeholder="Categoría" required><br>
    <button type="submit">Guardar</button>
</form>

<h2>Lista de Productos</h2>

<table border="1">
<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Precio</th>
    <th>Acciones</th>
</tr>

<?php while($row = $resultado->fetch_assoc()) { ?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['nombre']; ?></td>
    <td><?php echo $row['precio']; ?></td>
    <td>
        <a href="editar_producto.php?id=<?php echo $row['id']; ?>">Editar</a>
        <a href="../php/controlador/productos/eliminar.php?id=<?php echo $row['id']; ?>">Eliminar</a>
    </td>
</tr>
<?php } ?>

</table>
