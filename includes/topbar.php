<?php
$titulos_admin = [
  'dashboard.php' => 'Dashboard',
  'pedidos.php' => 'Pedidos',
  'domicilios.php' => 'Domicilios',
  'reservas.php' => 'Reservas',
  'productos.php' => 'Productos',
  'usuarios.php' => 'Usuarios',
  'configuracion_sistema.php' => 'Configuración',
  'categorias.php' => 'Categorías',
  'ver_pedido.php' => 'Detalle del pedido',
  'editar_producto.php' => 'Editar producto',
  'editar_usuario.php' => 'Editar usuario',
  'config_domicilios.php' => 'Configurar domicilios',
  'config_pedidos.php' => 'Configurar pedidos',
  'config_productos.php' => 'Configurar productos',
  'config_reservas.php' => 'Configurar reservas',
  'config_roles.php' => 'Configurar roles',
  'config_tipo_documentos.php' => 'Configurar documentos',
  'config_usuarios.php' => 'Configurar usuarios',
  'crear_tabla.php' => 'Crear tabla',
  'eliminar_tabla.php' => 'Eliminar tabla',
];
$titulo_admin = $titulos_admin[basename($_SERVER['SCRIPT_NAME'])] ?? 'Panel administrativo';
?>
<div class="topbar">
  <link
  rel="stylesheet"
  href="/css/includes/topbar.css?v=11">
  <div>

    <h2>
      <?= htmlspecialchars($titulo_admin) ?>
    </h2>

    <small class="text-muted">
      Panel administrativo
    </small>

  </div>

  <a
    href="/index.php"
    class="btn btn-warning">

    Cerrar sesión

  </a>

</div>
