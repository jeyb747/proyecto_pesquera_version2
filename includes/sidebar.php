<?php
// Detecta automáticamente el nombre del archivo actual (ej: dashboard.php, pedidos.php)
$pagina_actual = basename($_SERVER['SCRIPT_NAME']);
?>

<div class="sidebar">
  <link rel="stylesheet" href="/css/includes/sidebar.css?v=10">

  <div class="sidebar-logo">
      AdminPanel
  </div>

  <div class="sidebar-menu">

    <a href="/paginas/admin/dashboard.php" class="<?= ($pagina_actual == 'dashboard.php') ? 'active' : '' ?>">
      <i class="bi bi-grid"></i>
      Dashboard
    </a>

    <a href="/paginas/admin/pedidos.php" class="<?= ($pagina_actual == 'pedidos.php') ? 'active' : '' ?>">
      <i class="bi bi-box-seam"></i>
      Pedidos
    </a>

    <a href="/paginas/admin/domicilios.php" class="<?= ($pagina_actual == 'domicilios.php') ? 'active' : '' ?>">
      <i class="bi bi-truck"></i>
      Domicilios
    </a>

    <a href="/paginas/admin/reservas.php" class="<?= ($pagina_actual == 'reservas.php') ? 'active' : '' ?>">
      <i class="bi bi-calendar-event"></i>
      Reservas
    </a>

    <a href="/paginas/admin/productos.php" class="<?= ($pagina_actual == 'productos.php') ? 'active' : '' ?>">
      <i class="bi bi-cup-hot"></i>
      Productos
    </a>

    <a href="/paginas/admin/usuarios.php" class="<?= ($pagina_actual == 'usuarios.php') ? 'active' : '' ?>">
      <i class="bi bi-people"></i>
      Usuarios
    </a>

    <a href="/paginas/admin/configuracion_sistema.php" class="<?= ($pagina_actual == 'configuracion_sistema.php') ? 'active' : '' ?>">
      <i class="bi bi-gear"></i>
      Configuración
    </a>

  </div>
</div>
