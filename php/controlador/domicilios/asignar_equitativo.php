<?php
session_start();
require_once(__DIR__ . '/../../modelo/conexion.php');
require_once(__DIR__ . '/../../../includes/flash.php');
if (($_SESSION['rol'] ?? '') !== 'admin') { header('Location: /index.php'); exit; }

$repartidores = $conexion->query("SELECT u.nombre, COUNT(d.id) AS carga FROM usuarios u JOIN roles r ON r.id=u.id_rol LEFT JOIN domicilios d ON d.repartidor=u.nombre AND d.estado!='entregado' WHERE r.nombre_rol='repartidor' AND COALESCE(u.estado,1)=1 GROUP BY u.id,u.nombre ORDER BY carga,u.nombre");
$lista = $repartidores ? $repartidores->fetch_all(MYSQLI_ASSOC) : [];
$pendientes = $conexion->query("SELECT id FROM domicilios WHERE (repartidor IS NULL OR repartidor='') AND estado='pendiente' ORDER BY id");
if (!$lista) { flash_set('warning','No hay repartidores activos para asignar pedidos.'); header('Location: /paginas/repartidor/domicilios.php'); exit; }
$update=$conexion->prepare('UPDATE domicilios SET repartidor=?, estado=\'en camino\' WHERE id=?'); $i=0;
while ($pendientes && ($pedido=$pendientes->fetch_assoc())) { $nombre=$lista[$i % count($lista)]['nombre']; $update->bind_param('si',$nombre,$pedido['id']); $update->execute(); $i++; }
flash_set('success', $i ? "$i pedidos se distribuyeron equitativamente." : 'No hay pedidos pendientes sin asignar.');
header('Location: /paginas/repartidor/domicilios.php');
?>
