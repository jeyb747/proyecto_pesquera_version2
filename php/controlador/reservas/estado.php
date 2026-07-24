<?php
session_start();
require_once(__DIR__ . "/../../modelo/conexion.php");
require_once(__DIR__ . "/../../configuracion/mail.php");
require_once(__DIR__ . "/../../../includes/flash.php");
if (($_SESSION['rol'] ?? '') !== 'admin') { header('Location: /index.php'); exit; }
$id=(int)($_POST['id']??0); $estado=$_POST['estado']??'';
if (!in_array($estado,['pendiente','confirmada','cancelada'],true)) { flash_set('danger','Estado no válido.'); header('Location: /paginas/admin/reservas.php'); exit; }
$q=$conexion->prepare('SELECT r.*,u.correo FROM reservas r JOIN usuarios u ON u.id=r.usuario_id WHERE r.id=?');$q->bind_param('i',$id);$q->execute();$reserva=$q->get_result()->fetch_assoc();
$q=$conexion->prepare('UPDATE reservas SET estado=? WHERE id=?');$q->bind_param('si',$estado,$id);$q->execute();
if($reserva && $estado==='confirmada') enviar_correo($reserva['correo'],'Reserva confirmada','Tu reserva fue aceptada',"Hola {$reserva['nombre']}, tu reserva para {$reserva['fecha']} a las {$reserva['hora']} fue confirmada.");
flash_set('success','Estado de la reserva actualizado.');header("Location: /paginas/admin/reservas.php");
