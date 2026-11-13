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

    die("Producto no encontrado");

}

$id = intval($_GET['id']);

$sql = "SELECT * FROM productos WHERE id = $id";

$resultado = mysqli_query($conexion, $sql);

$producto = mysqli_fetch_assoc($resultado);

if (!$producto) {

    die("Producto no encontrado");

}
?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Editar Producto</title>

<!-- BOOTSTRAP -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

<!-- ICONOS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" integrity="sha384-XGjxtQfXaH2tnPFa9x+ruJTuLE3Aa6LhHSWRr1XeTyhezb4abCG4ccI5AkVDxqC+" crossorigin="anonymous">

<!-- CSS -->
<link rel="stylesheet" href="/version_final/css/admin.css">

</head>

<body>

<!-- ======================================================
     HEADER
====================================================== -->

<div class="header">

    <h1>
        ✏️ Editar Producto
    </h1>

    <a href="productos.php">
        Volver
    </a>

</div>

<!-- ======================================================
     CONTENIDO
====================================================== -->

<div class="container">

    <div class="row justify-content-center">

        <div class="col-lg-7">

            <div class="box">

                <!-- TITULO -->
                <div class="mb-4">

                    <h2 class="fw-bold mb-1">
                        Editar información
                    </h2>

                    <p class="text-muted mb-0">
                        Modifica los datos del producto.
                    </p>

                </div>

                <!-- FORM -->
                <form
                    action="/version_final/php/controlador/productos/actualizar.php"
                    method="POST">

                    <!-- ID -->
                    <input
                        type="hidden"
                        name="id"
                        value="<?= $producto['id']; ?>">

                    <!-- NOMBRE -->
                    <div class="mb-3">

                        <label for="producto-nombre" class="form-label fw-semibold">

                            Nombre del producto

                        </label>

                        <input
                            id="producto-nombre"
                            type="text"
                            name="nombre"
                            class="form-control"
                            value="<?= $producto['nombre']; ?>"
                            required>

                    </div>

                    <!-- DESCRIPCION -->
                    <div class="mb-3">

                        <label for="producto-descripcion" class="form-label fw-semibold">

                            Descripción

                        </label>

                        <textarea
                            id="producto-descripcion"
                            name="descripcion"
                            class="form-control"
                            rows="4"
                            required><?= $producto['descripcion']; ?></textarea>

                    </div>

                    <!-- PRECIO -->
                    <div class="mb-3">

                        <label for="producto-precio" class="form-label fw-semibold">

                            Precio

                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                $
                            </span>

                            <input
                                id="producto-precio"
                                type="number"
                                name="precio"
                                class="form-control"
                                value="<?= $producto['precio']; ?>"
                                required>

                        </div>

                    </div>

                    <!-- CATEGORIA -->
                    <div class="mb-4">

                        <label for="producto-categoria" class="form-label fw-semibold">

                            Categoría

                        </label>

                        <input
                            id="producto-categoria"
                            type="text"
                            name="categoria"
                            class="form-control"
                            value="<?= $producto['categoria']; ?>"
                            required>

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
                            href="productos.php"
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</body>
</html>
