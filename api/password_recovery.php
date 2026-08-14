<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;

require_once(__DIR__ . '/../php/modelo/conexion.php');
require_once(__DIR__ . '/../php/configuracion/mail.php');

$correo = trim($_POST['correo'] ?? '');
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'mensaje' => 'Ingresa un correo electrónico válido.']);
    exit;
}

$q = $conexion->prepare('SELECT id FROM usuarios WHERE correo = ? LIMIT 1');
$q->bind_param('s', $correo);
$q->execute();
$usuario = $q->get_result()->fetch_assoc();

if ($usuario) {
    $token = bin2hex(random_bytes(32));
    $hash = hash('sha256', $token);
    $q = $conexion->prepare('INSERT INTO password_resets (usuario_id, token_hash, expira_en) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR))');
    $q->bind_param('is', $usuario['id'], $hash);
    $q->execute();

    $baseUrl = rtrim(getenv('APP_BASE_URL') ?: 'https://la-pesquera-v2-g6ane0emd0etahgb.eastus-01.azurewebsites.net', '/');
    $url = $baseUrl . '/paginas/restablecer_password.php?token=' . rawurlencode($token);
    enviar_correo($correo, 'Restablece tu contraseña', 'Restablecimiento de contraseña', "Abre este enlace antes de una hora:\n$url");
}

echo json_encode(['success' => true, 'mensaje' => 'Si el correo está registrado, recibirás un enlace para restablecer tu contraseña.']);
