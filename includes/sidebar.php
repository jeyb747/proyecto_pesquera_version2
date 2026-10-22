<?php
// Detecta automáticamente el nombre del archivo actual (ej: dashboard.php, pedidos.php)
$pagina_actual = basename($_SERVER['SCRIPT_NAME']);
?>

<link rel="stylesheet" href="/css/includes/sidebar.css?v=14">

<aside class="sidebar" id="admin-sidebar">
  <button class="sidebar-logo" type="button" aria-label="Abrir menú de administración" aria-expanded="false" aria-controls="admin-menu">
      <img src="/imagenes/logo.png" alt="La Pesquera">
      <span>La Pesquera</span>
      <small>Admin</small>
  </button>

  <nav class="sidebar-menu" id="admin-menu" aria-label="Navegación de administración">

    <a href="/paginas/admin/dashboard.php" aria-label="Dashboard" title="Dashboard" class="<?= ($pagina_actual == 'dashboard.php') ? 'active' : '' ?>">
      <i class="bi bi-grid" aria-hidden="true"></i>
      <span class="nav-label">Dashboard</span>
    </a>

    <a href="/paginas/admin/pedidos.php" aria-label="Pedidos" title="Pedidos" class="<?= ($pagina_actual == 'pedidos.php') ? 'active' : '' ?>">
      <i class="bi bi-box-seam" aria-hidden="true"></i>
      <span class="nav-label">Pedidos</span>
    </a>

    <a href="/paginas/admin/domicilios.php" aria-label="Domicilios" title="Domicilios" class="<?= ($pagina_actual == 'domicilios.php') ? 'active' : '' ?>">
      <i class="bi bi-truck" aria-hidden="true"></i>
      <span class="nav-label">Domicilios</span>
    </a>

    <a href="/paginas/admin/reservas.php" aria-label="Reservas" title="Reservas" class="<?= ($pagina_actual == 'reservas.php') ? 'active' : '' ?>">
      <i class="bi bi-calendar-event" aria-hidden="true"></i>
      <span class="nav-label">Reservas</span>
    </a>

    <a href="/paginas/admin/productos.php" aria-label="Productos" title="Productos" class="<?= ($pagina_actual == 'productos.php') ? 'active' : '' ?>">
      <i class="bi bi-cup-hot" aria-hidden="true"></i>
      <span class="nav-label">Productos</span>
    </a>

    <a href="/paginas/admin/usuarios.php" aria-label="Usuarios" title="Usuarios" class="<?= ($pagina_actual == 'usuarios.php') ? 'active' : '' ?>">
      <i class="bi bi-people" aria-hidden="true"></i>
      <span class="nav-label">Usuarios</span>
    </a>

    <a href="/paginas/admin/configuracion_sistema.php" aria-label="Configuración" title="Configuración" class="<?= ($pagina_actual == 'configuracion_sistema.php') ? 'active' : '' ?>">
      <i class="bi bi-gear" aria-hidden="true"></i>
      <span class="nav-label">Configuración</span>
    </a>

  </nav>
</aside>
<script>
(() => {
  const sidebar = document.getElementById('admin-sidebar');
  const toggle = sidebar?.querySelector('.sidebar-logo');
  if (!sidebar || !toggle) return;
  const setOpen = (open) => {
    sidebar.classList.toggle('is-open', open);
    toggle.setAttribute('aria-expanded', String(open));
  };
  toggle.addEventListener('click', () => setOpen(!sidebar.classList.contains('is-open')));
  document.addEventListener('click', (event) => {
    if (window.matchMedia('(max-width: 768px)').matches && !sidebar.contains(event.target)) setOpen(false);
  });
  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') setOpen(false);
  });
})();
</script>
