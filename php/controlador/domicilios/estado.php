<?php
session_start();
require_once(__DIR__ . "/../../modelo/conexion.php");
require_once(__DIR__ . "/../../configuracion/mail.php");
require_once(__DIR__ . "/../../../includes/flash.php");
if (!in_array($_SESSION['rol'] ?? '', ['admin','repartidor'], true)) { header('Location: /index.php'); exit; }
$id=(int)($_POST['id']??0); $estado=$_POST['estado']??'';
if (!in_array($estado,['pendiente','en camino','entregado'],true)) { header('Location: /index.php'); exit; }
$q=$conexion->prepare('SELECT d.*,u.correo FROM domicilios d JOIN pedidos p ON p.id=d.pedido_id JOIN usuarios u ON u.id=p.usuario_id WHERE d.id=?');$q->bind_param('i',$id);$q->execute();$domicilio=$q->get_result()->fetch_assoc();
$q=$conexion->prepare('UPDATE domicilios SET estado=? WHERE id=?');$q->bind_param('si',$estado,$id);$q->execute();
if($domicilio && $estado==='en camino') enviar_correo($domicilio['correo'],'Tu pedido va en camino','¡Tu pedido ya va en camino!',"Tu domicilio #{$domicilio['pedido_id']} salió del restaurante y llegará pronto.");
flash_set('success','Estado del domicilio actualizado.');header('Location: /paginas/'.(($_SESSION['rol']??'')==='admin'?'admin/domicilios.php':'repartidor/domicilios.php'));
