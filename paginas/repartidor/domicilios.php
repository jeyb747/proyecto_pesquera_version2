<?php
session_start();
require_once(__DIR__ . "/../../php/modelo/conexion.php");

if (
    !isset($_SESSION['rol']) ||
    ($_SESSION['rol'] != 'admin' && $_SESSION['rol'] != 'repartidor')
) {
    header("Location: /index.php");
    exit();
}

$solo_pendientes = $_SESSION['rol'] == 'repartidor';
$where = $solo_pendientes ? "WHERE d.estado != 'entregado'" : "";

$sql = "SELECT
            d.id,
            d.direccion,
            d.telefono,
            d.estado,
            p.productos,
            p.total,
            u.nombre
        FROM domicilios d
        INNER JOIN pedidos p ON d.pedido_id = p.id
        INNER JOIN usuarios u ON p.usuario_id = u.id
        $where
        ORDER BY d.id DESC";

$resultado = mysqli_query($conexion, $sql);
$total_domicilios = $resultado ? mysqli_num_rows($resultado) : 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domicilios | Repartidor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/repartidor.css?v=3">
</head>
<body>
<header class="delivery-topbar">
    <div>
        <span class="eyebrow">Panel de entregas</span>
        <h1><i class="bi bi-truck"></i> Domicilios</h1>
    </div>

    <a href="<?= $_SESSION['rol'] == 'admin' ? '/paginas/admin/dashboard.php' : '/index.php' ?>" class="btn btn-warning delivery-back-btn">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</header>

<main class="delivery-shell">
    <section class="delivery-summary">
        <div>
            <span class="summary-label">Pendientes y en ruta</span>
            <strong><?= $total_domicilios ?></strong>
        </div>
        <p>Selecciona varios pedidos para abrir una ruta conjunta desde La Pesquera.</p>
    </section>

    <div class="d-flex justify-content-between align-items-center mb-3 gap-3 flex-wrap">
        <small class="text-muted"><i class="bi bi-geo-alt-fill"></i> Salida: Cra. 79 #42B-07, Antonio Nariño, Bogotá</small>
        <div class="d-flex gap-2 route-tools">
          <?php if ($_SESSION['rol'] === 'admin'): ?><form method="post" action="/php/controlador/domicilios/asignar_equitativo.php"><button class="btn btn-primary route-bulk-btn"><i class="bi bi-diagram-3"></i> Distribuir pedidos</button></form><?php endif; ?>
          <button type="button" id="abrirRutaConjunta" class="btn btn-warning route-bulk-btn"><i class="bi bi-sign-turn-right"></i> Iniciar ruta seleccionada</button>
        </div>
    </div>

    <section class="delivery-card">
        <div class="table-responsive">
            <table class="table align-middle delivery-table">
                <thead>
                    <tr>
                        <th>Ruta</th>
                        <th>Cliente</th>
                        <th>Direccion</th>
                        <th>Productos</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($resultado && $total_domicilios > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($resultado)): ?>
                        <?php
                        $productos = json_decode($row['productos'], true);
                        $estado_class = str_replace(' ', '-', $row['estado']);
                        $total = preg_replace('/\D/', '', $row['total'] ?? 0);
                        ?>
                        <tr>
                            <td><input class="form-check-input pedido-ruta" type="checkbox" value="<?= htmlspecialchars($row['direccion'] ?? '') ?>" aria-label="Incluir pedido en ruta"></td>
                            <td>
                                <div class="customer-cell">
                                    <span><i class="bi bi-person"></i></span>
                                    <strong><?= htmlspecialchars($row['nombre'] ?? 'Cliente') ?></strong>
                                </div>
                            </td>
                            <td>
                                <span class="address-text">
                                    <i class="bi bi-geo-alt"></i>
                                    <?= htmlspecialchars($row['direccion'] ?? '') ?>
                                </span>
                            </td>
                            <td>
                                <div class="products-stack">
                                    <?php if (is_array($productos) && count($productos) > 0): ?>
                                        <?php foreach ($productos as $producto): ?>
                                            <span><?= htmlspecialchars($producto['nombre'] ?? 'Producto') ?></span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="text-muted">Sin productos</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <strong class="money">$<?= number_format((int)$total, 0, ',', '.') ?></strong>
                            </td>
                            <td>
                                <form action="/php/controlador/domicilios/estado.php" method="POST" class="m-0">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
                                    <select name="estado" class="form-select status-select status-<?= htmlspecialchars($estado_class) ?>" onchange="this.form.submit()">
                                        <option value="pendiente" <?= $row['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                        <option value="en camino" <?= $row['estado'] == 'en camino' ? 'selected' : '' ?>>En camino</option>
                                        <option value="entregado" <?= $row['estado'] == 'entregado' ? 'selected' : '' ?>>Entregado</option>
                                    </select>
                                </form>
                            </td>
                            <td class="text-end">
                                <div class="action-group">
                                    <a href="/paginas/repartidor/ver_domicilio.php?id=<?= htmlspecialchars($row['id']) ?>" class="btn delivery-action-btn action-view">
                                        <i class="bi bi-eye"></i> Ver
                                    </a>
                                    <a href="https://www.google.com/maps/dir/?api=1&origin=<?= urlencode('Cra. 79 #42B-07, Antonio Nariño, Bogotá') ?>&destination=<?= urlencode($row['direccion'] ?? '') ?>"
                                       target="_blank"
                                       class="btn delivery-action-btn action-route"
                                       data-destination="<?= htmlspecialchars($row['direccion'] ?? '') ?>">
                                        <i class="bi bi-geo-alt-fill"></i> Iniciar ruta
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="empty-state">
                            <i class="bi bi-check2-circle"></i>
                            No hay domicilios pendientes por gestionar.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<div class="modal fade" id="modalRuta" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content route-modal">
      <div class="modal-header">
        <div>
          <span class="eyebrow route-eyebrow">Ruta en tiempo real</span>
          <h5 class="modal-title">Trayecto de entrega</h5>
        </div>
        <div class="route-modal-actions">
          <a id="abrirGoogleMaps" class="btn btn-warning delivery-action-btn action-route" target="_blank" rel="noopener">
            <i class="bi bi-map"></i> Google Maps
          </a>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
      </div>
      <div class="modal-body">
        <div id="rutaEstado" class="route-status">Preparando ruta...</div>
        <iframe id="mapaRuta" title="Ruta de entrega" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../js/avisos.js"></script>
<script>
const origenRestaurante = 'Cra. 79 #42B-07, Antonio Narino, Bogota, Colombia';

function normalizarDireccion(direccion) {
  const limpia = String(direccion || '').trim();
  if (!limpia) return '';
  return /colombia/i.test(limpia) ? limpia : `${limpia}, Colombia`;
}

function crearUrlGoogleMaps(destinos) {
  const pendientes = destinos.map(normalizarDireccion).filter(Boolean);
  const destino = pendientes.pop();
  const url = new URL('https://www.google.com/maps/dir/?api=1');
  url.searchParams.set('origin', origenRestaurante);
  url.searchParams.set('destination', destino);
  url.searchParams.set('travelmode', 'driving');
  url.searchParams.set('dir_action', 'navigate');
  if (pendientes.length) url.searchParams.set('waypoints', pendientes.join('|'));
  return url.toString();
}

function crearUrlGoogleEmbed(destinos) {
  const puntos = destinos.map(normalizarDireccion).filter(Boolean);
  const destino = puntos.length > 1 ? puntos.join(' to:') : puntos[0];
  const url = new URL('https://www.google.com/maps');
  url.searchParams.set('output', 'embed');
  url.searchParams.set('saddr', origenRestaurante);
  url.searchParams.set('daddr', destino);
  return url.toString();
}

function setRutaEstado(mensaje) {
  document.getElementById('rutaEstado').textContent = mensaje;
}

function iniciarRuta(destinos) {
  const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalRuta'));
  const destinosValidos = destinos.map(normalizarDireccion).filter(Boolean);
  if (!destinosValidos.length) return;

  document.getElementById('abrirGoogleMaps').href = crearUrlGoogleMaps(destinosValidos);
  document.getElementById('mapaRuta').src = crearUrlGoogleEmbed(destinosValidos);
  modal.show();
  setRutaEstado('Ruta cargada desde La Pesquera. Para ver tu ubicacion moviendose, toca Google Maps e inicia la navegacion.');
}

document.querySelectorAll('.action-route[data-destination]').forEach((btn) => {
  btn.addEventListener('click', (event) => {
    event.preventDefault();
    const destino = btn.dataset.destination;
    if (destino) iniciarRuta([destino]);
  });
});

document.getElementById('abrirRutaConjunta')?.addEventListener('click', (event) => {
  event.stopImmediatePropagation();
  const destinos = [...document.querySelectorAll('.pedido-ruta:checked')].map(x => x.value).filter(Boolean);
  if (!destinos.length) return alert('Selecciona al menos un pedido para crear la ruta.');
  iniciarRuta(destinos);
});

document.getElementById('modalRuta')?.addEventListener('hidden.bs.modal', () => {
  document.getElementById('mapaRuta').src = '';
});
</script>
<script>
document.getElementById('abrirRutaConjunta')?.addEventListener('click', () => {
  const destinos = [...document.querySelectorAll('.pedido-ruta:checked')].map(x => x.value).filter(Boolean);
  if (!destinos.length) return alert('Selecciona al menos un pedido para crear la ruta.');
  const origen = 'Cra. 79 #42B-07, Antonio Nariño, Bogotá';
  const destino = destinos.pop();
  const url = new URL('https://www.google.com/maps/dir/?api=1');
  url.searchParams.set('origin', origen); url.searchParams.set('destination', destino);
  if (destinos.length) url.searchParams.set('waypoints', destinos.join('|'));
  window.open(url.toString(), '_blank', 'noopener');
});
</script>
</body>
</html>
