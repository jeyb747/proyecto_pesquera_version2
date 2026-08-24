<?php
session_start();
require_once __DIR__ . '/../modelo/conexion.php';
require_once __DIR__ . '/../configuracion/verificacion.php';
require_once __DIR__ . '/../../includes/flash.php';
asegurar_columnas_verificacion($conexion);
$correo = strtolower(trim($_POST['correo'] ?? $_SESSION['correo_pendiente'] ?? ''));
$accion = $_POST['accion'] ?? 'confirmar';
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) { flash_set('danger', 'Ingresa un correo válido.'); header('Location: /paginas/verificar_correo.php'); exit; }
$stmt = $conexion->prepare('SELECT id, correo_verificado, codigo_verificacion_hash, codigo_verificacion_expira FROM usuarios WHERE correo = ?');
$stmt->bind_param('s', $correo); $stmt->execute(); $usuario = $stmt->get_result()->fetch_assoc();
if (!$usuario) { flash_set('danger', 'No encontramos una cuenta con ese correo.'); header('Location: /paginas/registro.php'); exit; }
if ($accion === 'reenviar') {
    if (!enviar_codigo_verificacion($conexion, (int)$usuario['id'], $correo)) { flash_set('danger', 'No fue posible enviar el código. Configura el correo SMTP e inténtalo de nuevo.'); }
    else flash_set('success', 'Te enviamos un nuevo código; revisa tu correo.');
    $_SESSION['correo_pendiente'] = $correo; header('Location: /paginas/verificar_correo.php'); exit;
}
$codigo = trim($_POST['codigo'] ?? '');
if (!preg_match('/^\d{6}$/', $codigo) || empty($usuario['codigo_verificacion_hash']) || strtotime((string)$usuario['codigo_verificacion_expira']) < time() || !password_verify($codigo, $usuario['codigo_verificacion_hash'])) {
    flash_set('danger', 'El código no es válido o venció. Solicita uno nuevo.'); $_SESSION['correo_pendiente'] = $correo; header('Location: /paginas/verificar_correo.php'); exit;
}
$ok = $conexion->prepare('UPDATE usuarios SET correo_verificado = 1, codigo_verificacion_hash = NULL, codigo_verificacion_expira = NULL WHERE id = ?'); $ok->bind_param('i', $usuario['id']); $ok->execute();
unset($_SESSION['correo_pendiente']); flash_set('success', 'Cuenta activada. Ya puedes iniciar sesión.'); header('Location: /paginas/login.php');
