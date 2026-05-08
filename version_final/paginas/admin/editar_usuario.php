<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/version_final/php/modelo/conexion.php");

// 🔒 SOLO ADMIN
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {

    header("Location: /version_final/index.php");
    exit();

}

// VALIDAR ID
if (!isset($_GET['id'])) {

    die("Usuario no encontrado");

}

$id = intval($_GET['id']);

$sql = "SELECT * FROM usuarios WHERE id = $id";

$resultado = mysqli_query($conexion, $sql);

$user = mysqli_fetch_assoc($resultado);

if (!$user) {

    die("Usuario no encontrado");

}
?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Editar Usuario</title>

<!-- BOOTSTRAP -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- ICONOS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- CSS -->
<link rel="stylesheet" href="/version_final/css/admin.css">

</head>

<body>

<!-- ======================================================
     HEADER
====================================================== -->

<div class="header">

    <h1>
        👤 Editar Usuario
    </h1>

    <a href="usuarios.php">
        Volver
    </a>

</div>

<!-- ======================================================
     CONTENIDO
====================================================== -->

<div class="container">

    <div class="row justify-content-center">

        <div class="col-lg-6">

            <div class="box">

                <!-- TITULO -->
                <div class="mb-4">

                    <h2 class="fw-bold mb-1">
                        Información del usuario
                    </h2>

                    <p class="text-muted mb-0">
                        Actualiza los datos y permisos del usuario.
                    </p>

                </div>

                <!-- FORM -->
                <form
                    action="/version_final/php/controlador/usuarios/actualizar.php"
                    method="POST">

                    <!-- ID -->
                    <input
                        type="hidden"
                        name="id"
                        value="<?= $user['id'] ?>">

                    <!-- NOMBRE -->
                    <div class="mb-3">

                        <label class="form-label fw-semibold">

                            Nombre

                        </label>

                        <input
                            type="text"
                            name="nombre"
                            class="form-control"
                            value="<?= $user['nombre'] ?>"
                            required>

                    </div>

                    <!-- CORREO -->
                    <div class="mb-3">

                        <label class="form-label fw-semibold">

                            Correo electrónico

                        </label>

                        <input
                            type="email"
                            name="correo"
                            class="form-control"
                            value="<?= $user['correo'] ?>"
                            required>

                    </div>

                    <!-- ROL -->
                    <div class="mb-4">

                        <label class="form-label fw-semibold">

                            Rol del usuario

                        </label>

                        <select
                            name="rol"
                            class="form-select">

                            <option
                                value="cliente"
                                <?= $user['rol']=="cliente"?"selected":"" ?>>

                                Cliente

                            </option>

                            <option
                                value="admin"
                                <?= $user['rol']=="admin"?"selected":"" ?>>

                                Administrador

                            </option>

                        </select>

                    </div>

                    <!-- BOTONES -->
                    <div class="d-flex gap-3">

                        <button
                            type="submit"
                            class="btn btn-warning fw-bold px-4">

                            <i class="bi bi-check-circle"></i>
                            Actualizar

                        </button>

                        <a
                            href="usuarios.php"
                            class="btn btn-dark px-4">

                            Cancelar

                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

<!-- BOOTSTRAP -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>