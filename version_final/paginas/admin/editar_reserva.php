<?php
require_once "../../php/configuracion/admin_auth.php";
require_once "../../php/modelo/conexion.php";

$id = $_GET['id'];

$sql = "SELECT * FROM reservas WHERE id=$id";
$resultado = $conexion->query($sql);
$reserva = $resultado->fetch_assoc();
?>

<h2>Editar Reserva</h2>

<form action="../../php/controlador/reservas/actualizar.php" method="POST">

<input type="hidden" name="id" value="<?php echo $reserva['id']; ?>">

Fecha:
<input type="date" name="fecha" value="<?php echo $reserva['fecha']; ?>"><br>

Hora:
<input type="time" name="hora" value="<?php echo $reserva['hora']; ?>"><br>

Personas:
<input type="number" name="personas" value="<?php echo $reserva['personas']; ?>"><br>

Estado:
<select name="estado">
    <option value="pendiente">Pendiente</option>
    <option value="confirmada">Confirmada</option>
    <option value="cancelada">Cancelada</option>
</select>

<br><br>

<button type="submit">Actualizar</button>

</form>