<?php
/* Shared email-verification helpers. New accounts are inactive until their OTP is confirmed. */
function asegurar_columnas_verificacion(mysqli $conexion): void {
    $cols = [];
    $r = $conexion->query("SHOW COLUMNS FROM usuarios");
    while ($row = $r->fetch_assoc()) $cols[$row['Field']] = true;
    if (!isset($cols['correo_verificado'])) $conexion->query("ALTER TABLE usuarios ADD correo_verificado TINYINT(1) NULL DEFAULT NULL");
    if (!isset($cols['codigo_verificacion_hash'])) $conexion->query("ALTER TABLE usuarios ADD codigo_verificacion_hash VARCHAR(255) NULL");
    if (!isset($cols['codigo_verificacion_expira'])) $conexion->query("ALTER TABLE usuarios ADD codigo_verificacion_expira DATETIME NULL");
}

function enviar_codigo_verificacion(mysqli $conexion, int $usuarioId, string $correo): bool {
    $codigo = (string) random_int(100000, 999999);
    $hash = password_hash($codigo, PASSWORD_DEFAULT);
    $expira = date('Y-m-d H:i:s', time() + 15 * 60);
    $stmt = $conexion->prepare('UPDATE usuarios SET correo_verificado = 0, codigo_verificacion_hash = ?, codigo_verificacion_expira = ? WHERE id = ?');
    $stmt->bind_param('ssi', $hash, $expira, $usuarioId);
    $stmt->execute();
    require_once __DIR__ . '/mail.php';
    return enviar_correo($correo, 'Código de verificación | La Pesquera', 'Activa tu cuenta', "Tu código de verificación es: $codigo\n\nVence en 15 minutos. No lo compartas con nadie.");
}
