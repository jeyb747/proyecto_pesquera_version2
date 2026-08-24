<?php
session_start();
require_once __DIR__ . '/../modelo/conexion.php';
require_once __DIR__ . '/../../includes/flash.php';
require_once __DIR__ . '/../configuracion/verificacion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recibir todos los datos del formulario
    $nombre = $_POST['nombre'];
    $tipo_documento = intval($_POST['tipo_documento']); // Forzar a entero (1, 2, 3 o 4)
    $numero_documento = $_POST['numero_documento']; 
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // encriptar contraseña

    // Verificar si el correo ya existe
    $consulta = $conexion->prepare("SELECT * FROM usuarios WHERE correo = ?");
    $consulta->bind_param("s", $correo);
    $consulta->execute();
    $resultado = $consulta->get_result();

    if ($resultado->num_rows > 0) {
        flash_set('warning', 'Este correo ya está registrado.');
        header("Location: /paginas/registro.php");
        exit();
    }

    // Insertar nuevo usuario utilizando la relación de la tabla maestra
    // El formato en bind_param es "sissss" porque el segundo valor (id_tipo_documento) es de tipo Entero (i)
    asegurar_columnas_verificacion($conexion);
    $insert = $conexion->prepare("INSERT INTO usuarios (nombre, id_tipo_documento, numero_documento, correo, telefono, password, correo_verificado) VALUES (?, ?, ?, ?, ?, ?, 0)");
    $insert->bind_param("sissss", $nombre, $tipo_documento, $numero_documento, $correo, $telefono, $password);

    if ($insert->execute()) {
        $_SESSION['correo_pendiente'] = $correo;
        $enviado = enviar_codigo_verificacion($conexion, (int)$conexion->insert_id, $correo);
        flash_set('success', 'Te enviamos un código a tu correo; revísalo para activar tu cuenta.');
        header("Location: /paginas/verificar_correo.php");
        exit();
        flash_set('success', 'Cuenta creada correctamente. Ya puedes iniciar sesión.');
        header("Location: /paginas/login.php");
        exit();
    } else {
        flash_set('danger', 'No fue posible crear la cuenta. Inténtalo nuevamente.');
        header("Location: /paginas/registro.php");
        exit();
    }
}
