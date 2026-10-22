<?php
// Detecta automáticamente el nombre del archivo actual (ej: dashboard.php, pedidos.php)
$pagina_actual = basename($_SERVER['SCRIPT_NAME']);
?>

<link rel="stylesheet" href="/css/includes/sidebar.css?v=12">

<aside class="sidebar" id="admin-sidebar">
  <button class="sidebar-logo" type="button" aria-label="Abrir menú de administración" aria-expanded="false" aria-controls="admin-menu">
      <img src="/imagenes/logo.png" alt="La Pesquera">
      <span>La Pesquera</span>
      <small>Admin</small>
  </button>

  <nav class="sidebar-menu" id="admin-menu" aria-label="Navegación de administración">

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
