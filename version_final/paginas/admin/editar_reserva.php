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

<label for="reserva-fecha">Fecha:</label>
<input id="reserva-fecha" type="date" name="fecha" value="<?php echo htmlspecialchars($reserva['fecha'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"><br>

<label for="reserva-hora">Hora:</label>
<input id="reserva-hora" type="time" name="hora" value="<?php echo htmlspecialchars($reserva['hora'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"><br>

<label for="reserva-personas">Personas:</label>
<input id="reserva-personas" type="number" name="personas" value="<?php echo htmlspecialchars($reserva['personas'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"><br>

<label for="reserva-estado">Estado:</label>
<select id="reserva-estado" name="estado">
    <option value="pendiente">Pendiente</option>
    <option value="confirmada">Confirmada</option>
    <option value="cancelada">Cancelada</option>
</select>

<br><br>

<button type="submit">Actualizar</button>

</form>
