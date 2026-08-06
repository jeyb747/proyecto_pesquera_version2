<?php
session_start();
require_once(__DIR__ . "/../../php/modelo/conexion.php");

// 🔒 PROTEGER ADMIN
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración del Sistema | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link class="modulo-css" rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/admin.css">
    <link rel="stylesheet" href="/css/admin/usuarios.css">
</head>
<body>

<div class="wrapper d-flex">
    <?php include(__DIR__ . "/../../includes/sidebar.php"); ?>

    <div class="main-content flex-grow-1 p-4">
        <?php include(__DIR__ . "/../../includes/topbar.php"); ?>

        <div class="content-container-max mx-auto mt-4">
            
            <div class="mb-4">
                <h2 class="h4 fw-bold text-dark mb-1">Configuración del Sistema</h2>
                <p class="text-muted mb-0">Selecciona la tabla de la base de datos que deseas administrar de forma independiente o gestiona la estructura general.</p>
            </div>

            <div class="row g-4">
                
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 55px; height: 55px;">
                                <i class="bi bi-tags-fill fs-4"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">Tabla Roles</h5>
                            <p class="text-muted small mb-0">Configura los perfiles de acceso y cargos del ecosistema de la plataforma.</p>
                        </div>
                        <a href="/paginas/admin/configuracion/config_roles.php" class="btn btn-outline-primary rounded-pill w-100 mt-4 fw-medium">
                            Gestionar Tabla <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 55px; height: 55px;">
                                <i class="bi bi-tags-fill fs-4"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">Categorías</h5>
                            <p class="text-muted small mb-0">Crea, edita y activa o desactiva las categorías del menú.</p>
                        </div>
                        <a href="/paginas/admin/categorias.php" class="btn btn-outline-warning rounded-pill w-100 mt-4 fw-medium">
                            Gestionar categorías <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="bg-info-subtle text-info rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 55px; height: 55px;">
                                <i class="bi bi-people-fill fs-4"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">Tabla Usuarios</h5>
                            <p class="text-muted small mb-0">Administra los campos estructurales de las cuentas registradas en el sistema.</p>
                        </div>
                        <a href="/paginas/admin/configuracion/config_usuarios.php" class="btn btn-outline-info rounded-pill w-100 mt-4 fw-medium">
                            Gestionar Tabla <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 55px; height: 55px;">
                                <i class="bi bi-calendar-event-fill fs-4"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">Tabla Reservas</h5>
                            <p class="text-muted small mb-0">Configura los parámetros, estados y columnas de las agendaciones de mesas.</p>
                        </div>
                        <a href="/paginas/admin/configuracion/config_reservas.php" class="btn btn-outline-warning rounded-pill w-100 mt-4 fw-medium">
                            Gestionar Tabla <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 55px; height: 55px;">
                                <i class="bi bi-cup-hot-fill fs-4"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">Tabla Productos</h5>
                            <p class="text-muted small mb-0">Administra el esquema del menú, categorías e inventario de artículos.</p>
                        </div>
                        <a href="/paginas/admin/configuracion/config_productos.php" class="btn btn-outline-success rounded-pill w-100 mt-4 fw-medium">
                            Gestionar Tabla <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="bg-secondary-subtle text-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 55px; height: 55px;">
                                <i class="bi bi-box-seam-fill fs-4"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">Tabla Pedidos</h5>
                            <p class="text-muted small mb-0">Gestiona los registros globales de las compras y transacciones del negocio.</p>
                        </div>
                        <a href="/paginas/admin/configuracion/config_pedidos.php" class="btn btn-outline-secondary rounded-pill w-100 mt-4 fw-medium">
                            Gestionar Tabla <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 55px; height: 55px;">
                                <i class="bi bi-truck-flatbed fs-4"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">Tabla Domicilios</h5>
                            <p class="text-muted small mb-0">Modifica y supervisa la estructura de los repartos y asignaciones logísticas.</p>
                        </div>
                        <a href="/paginas/admin/configuracion/config_domicilios.php" class="btn btn-outline-danger rounded-pill w-100 mt-4 fw-medium">
                            Gestionar Tabla <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

            </div> </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
