<?php
session_start(); require_once(__DIR__ . "/../../modelo/conexion.php"); require_once(__DIR__ . "/../../../includes/flash.php");
$token=$_POST['token']??''; $password=$_POST['password']??''; $confirm=$_POST['confirmacion']??'';
if(strlen($password)<8 || $password!==$confirm){ flash_set('danger','Las contraseñas deben coincidir y tener mínimo 8 caracteres.'); header('Location: /paginas/restablecer_password.php?token='.urlencode($token)); exit; }
$hash=hash('sha256',$token); $q=$conexion->prepare('SELECT id,usuario_id FROM password_resets WHERE token_hash=? AND usado_en IS NULL AND expira_en>NOW() ORDER BY id DESC LIMIT 1'); $q->bind_param('s',$hash); $q->execute(); $r=$q->get_result()->fetch_assoc();
if(!$r){flash_set('danger','El enlace no es válido o ya venció.');header('Location: /paginas/olvido_password.php');exit;}
$new=password_hash($password,PASSWORD_DEFAULT); $q=$conexion->prepare('UPDATE usuarios SET password=? WHERE id=?');$q->bind_param('si',$new,$r['usuario_id']);$q->execute();$q=$conexion->prepare('UPDATE password_resets SET usado_en=NOW() WHERE id=?');$q->bind_param('i',$r['id']);$q->execute();flash_set('success','Contraseña actualizada. Ya puedes iniciar sesión.');header('Location: /paginas/login.php');
?>
