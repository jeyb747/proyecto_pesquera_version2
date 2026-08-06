<?php
session_start();
require_once(__DIR__ . '/../../modelo/conexion.php');
require_once(__DIR__ . '/../../../includes/flash.php');
if (!isset($_SESSION['id'])) { $_SESSION['flash_auth']='Debes iniciar sesión para editar una reserva.'; header('Location: /paginas/login.php'); exit; }
$id=(int)($_POST['id']??0); $fecha=$_POST['fecha']??''; $hora=trim($_POST['hora']??''); $personas=(int)($_POST['personas']??0);
if (!$id || !preg_match('/^\d{4}-\d{2}-\d{2}$/',$fecha) || $personas<1 || $personas>20) { flash_set('warning','Verifica la fecha y el número de personas (máximo 20).'); header('Location: /paginas/mis_reservas.php'); exit; }
$q=$conexion->prepare("UPDATE reservas SET fecha=?,hora=?,personas=? WHERE id=? AND usuario_id=? AND estado='pendiente'");
$q->bind_param('ssiii',$fecha,$hora,$personas,$id,$_SESSION['id']); $q->execute();
flash_set($q->affected_rows?'success':'warning',$q->affected_rows?'Reserva actualizada.':'No fue posible editar la reserva.');
header('Location: /paginas/mis_reservas.php');
?>
