<?php
session_start();require_once(__DIR__ . "/../../modelo/conexion.php");require_once(__DIR__ . "/../../../includes/flash.php");if(!isset($_SESSION['id'])){header('Location: /paginas/login.php');exit;}$id=(int)($_POST['id']??0);$q=$conexion->prepare("UPDATE reservas SET estado='cancelada' WHERE id=? AND usuario_id=? AND estado='pendiente'");$q->bind_param('ii',$id,$_SESSION['id']);$q->execute();flash_set($q->affected_rows?'success':'warning',$q->affected_rows?'Reserva cancelada.':'No fue posible cancelar la reserva.');header('Location: /paginas/mis_reservas.php');
?>
