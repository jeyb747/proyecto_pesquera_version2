<?php
session_start(); require_once(__DIR__ . "/../../modelo/conexion.php"); require_once(__DIR__ . "/../../../includes/flash.php");
if (($_SESSION['rol'] ?? '') !== 'admin') { header('Location: /index.php'); exit; }
$id=(int)($_POST['id']??0); $estado=(int)($_POST['estado']??0); $q=$conexion->prepare('UPDATE categorias SET estado=? WHERE id=?'); $q->bind_param('ii',$estado,$id); $q->execute(); flash_set('success','Estado de categoría actualizado.'); header('Location: /paginas/admin/categorias.php');
?>
