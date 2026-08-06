<?php
session_start();
require_once(__DIR__ . "/../../php/modelo/conexion.php");

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /index.php");
    exit();
}

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: /paginas/admin/usuarios.php?mensaje=error");
    exit();
}

$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    header("Location: /paginas/admin/usuarios.php?mensaje=error");
    exit();
}

$roles = mysqli_query($conexion, "SELECT id, nombre_rol FROM roles ORDER BY nombre_rol ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/admin.css?v=11">
    <link rel="stylesheet" href="/css/admin/crud-pages.css?v=1">
</head>
<body>
<div class="dashboard-layout">
    <?php include(__DIR__ . "/../../includes/sidebar.php"); ?>

    <main class="main-content">
        <?php include(__DIR__ . "/../../includes/topbar.php"); ?>

        <div class="admin-page-shell">
            <div class="page-heading">
                <div>
                    <a href="/paginas/admin/usuarios.php" class="back-link">
                        <i class="bi bi-arrow-left"></i> Volver a usuarios
                    </a>
                    <h1>Editar usuario</h1>
                    <p>Actualiza la informacion de acceso y estado de la cuenta.</p>
                </div>
            </div>

            <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'error'): ?>
                <div class="alert alert-danger border-0 rounded-4 shadow-sm">
                    No se pudo actualizar el usuario. Revisa los datos e intentalo de nuevo.
                </div>
            <?php endif; ?>

            <div class="form-card">
                <form action="/php/controlador/usuarios/actualizar.php" method="POST">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Nombre completo</label>
                            <input type="text" name="nombre" class="form-control form-control-lg"
                                   value="<?= htmlspecialchars($user['nombre'] ?? '') ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Correo electronico</label>
                            <input type="email" name="correo" class="form-control form-control-lg"
                                   value="<?= htmlspecialchars($user['correo'] ?? '') ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Telefono</label>
                            <input type="text" name="telefono" class="form-control form-control-lg"
                                   value="<?= htmlspecialchars($user['telefono'] ?? '') ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Rol</label>
                            <select name="id_rol" class="form-select form-select-lg" required>
                                <?php while ($rol = mysqli_fetch_assoc($roles)): ?>
                                    <option value="<?= htmlspecialchars($rol['id']) ?>"
                                        <?= intval($user['id_rol'] ?? 0) === intval($rol['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars(ucfirst($rol['nombre_rol'])) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Estado</label>
                            <select name="estado" class="form-select form-select-lg" required>
                                <option value="1" <?= (!isset($user['estado']) || intval($user['estado']) === 1) ? 'selected' : '' ?>>Activo</option>
                                <option value="0" <?= (isset($user['estado']) && intval($user['estado']) === 0) ? 'selected' : '' ?>>Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="/paginas/admin/usuarios.php" class="btn btn-light">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check-circle"></i> Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
