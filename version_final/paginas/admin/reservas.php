<?php
require_once "../../php/configuracion/admin_auth.php";
require_once "../../php/controlador/reservas/listar.php";
?>

<h2>📅 Reservas</h2>

<table border="1">
<tr>
    <th>ID</th>
    <th>Usuario</th>
    <th>Fecha</th>
    <th>Hora</th>
    <th>Personas</th>
    <th>Estado</th>
    <th>Acciones</th>
</tr>

<?php while($row = $resultado->fetch_assoc()) { ?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['usuario_id']; ?></td>
    <td><?php echo $row['fecha']; ?></td>
    <td><?php echo $row['hora']; ?></td>
    <td><?php echo $row['personas']; ?></td>
    <td><?php echo $row['estado']; ?></td>

    <td>
        <a href="editar_reserva.php?id=<?php echo $row['id']; ?>">Editar</a>
        <a href="../../php/controlador/reservas/eliminar.php?id=<?php echo $row['id']; ?>">Eliminar</a>
    </td>
</tr>
<?php } ?>

</table>