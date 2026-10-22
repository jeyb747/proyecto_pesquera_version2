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
/* Un repartidor solo ve pedidos libres o los que le fueron asignados.  Así la
 * sugerencia de ruta nunca ofrece un pedido que otro repartidor ya lleva. */
$where = $solo_pendientes
    ? "WHERE ((d.estado = 'pendiente' AND (d.repartidor IS NULL OR d.repartidor = '' OR d.repartidor = ?))
        OR (d.estado = 'en camino' AND d.repartidor = ?))"
    : "WHERE d.estado != 'entregado'";

$sql = "SELECT
            d.id,
            d.direccion,
            d.telefono,
            d.estado,
            d.repartidor,
            p.productos,
            p.total,
            u.nombre
        FROM domicilios d
        INNER JOIN pedidos p ON d.pedido_id = p.id
        INNER JOIN usuarios u ON p.usuario_id = u.id
        $where
        ORDER BY d.id DESC";

$stmt = $conexion->prepare($sql);
if ($solo_pendientes) {
    $repartidor_actual = $_SESSION['usuario'] ?? '';
    $stmt->bind_param('ss', $repartidor_actual, $repartidor_actual);
}
$stmt->execute();
$resultado = $stmt->get_result();
$total_domicilios = $resultado ? mysqli_num_rows($resultado) : 0;
$pedidos_asistente = [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domicilios | Repartidor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/repartidor.css?v=6">
    <link rel="stylesheet" href="/css/asistente-rutas.css?v=2">
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
        <div class="summary-metric">
            <span class="summary-icon"><i class="bi bi-box-seam"></i></span>
            <div>
            <span class="summary-label">Pendientes y en ruta</span>
            <strong><?= $total_domicilios ?></strong>
            </div>
        </div>
        <p>Organiza las entregas disponibles, selecciona hasta cinco pedidos y comienza tu recorrido.</p>
    </section>

    <div class="delivery-controls">
        <div class="delivery-origin"><span class="origin-icon"><i class="bi bi-geo-alt-fill"></i></span><span><small>Punto de salida</small><strong>Cra. 79 #42B-07, Antonio Nariño, Bogotá</strong></span></div>
        <div class="d-flex gap-2 route-tools">
          <?php if ($_SESSION['rol'] === 'admin'): ?><form method="post" action="/php/controlador/domicilios/asignar_equitativo.php"><button class="btn btn-primary route-bulk-btn"><i class="bi bi-diagram-3"></i> Distribuir pedidos</button></form><?php endif; ?>
          <button type="button" id="abrirRutaConjunta" class="btn btn-warning route-bulk-btn"><i class="bi bi-sign-turn-right"></i> Iniciar ruta seleccionada</button>
        </div>
    </div>

    <section class="delivery-list" aria-label="Pedidos por entregar">
                <?php if ($resultado && $total_domicilios > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($resultado)): ?>
                        <?php
                        $productos = json_decode($row['productos'], true);
                        $estado_class = str_replace(' ', '-', $row['estado']);
                        $total = preg_replace('/\D/', '', $row['total'] ?? 0);
                        $pedidos_asistente[] = [
                            'id' => (int) $row['id'],
                            'cliente' => $row['nombre'] ?? 'Cliente',
                            'direccion' => $row['direccion'] ?? ''
                        ];
                        ?>
                        <article class="delivery-order">
                            <header class="order-heading">
                                <label class="order-select">
                                    <input class="form-check-input pedido-ruta" type="checkbox" value="<?= htmlspecialchars($row['direccion'] ?? '') ?>" aria-label="Incluir pedido de <?= htmlspecialchars($row['nombre'] ?? 'Cliente') ?> en la ruta">
                                    <span>Agregar a ruta</span>
                                </label>
                                <div class="customer-cell">
                                    <span class="customer-icon"><i class="bi bi-person"></i></span>
                                    <div><small>Pedido #<?= (int)$row['id'] ?></small><strong><?= htmlspecialchars($row['nombre'] ?? 'Cliente') ?></strong></div>
                                </div>
                                <form action="/php/controlador/domicilios/estado.php" method="POST" class="status-form">
                                    <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                                    <select name="estado" aria-label="Cambiar estado del pedido #<?= (int)$row['id'] ?>" class="form-select status-select status-<?= htmlspecialchars($estado_class) ?>" onchange="this.form.submit()">
                                        <option value="pendiente" <?= $row['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                        <option value="en camino" <?= $row['estado'] == 'en camino' ? 'selected' : '' ?>>En camino</option>
                                        <option value="entregado" <?= $row['estado'] == 'entregado' ? 'selected' : '' ?>>Entregado</option>
                                    </select>
                                </form>
                            </header>
                            <div class="order-details">
                                <div class="order-detail address-detail">
                                    <span class="detail-label">Dirección de entrega</span>
                                    <span class="address-text"><i class="bi bi-geo-alt-fill"></i><?= htmlspecialchars($row['direccion'] ?? '') ?></span>
                                </div>
                                <div class="order-detail products-detail">
                                    <span class="detail-label">Productos</span>
                                    <div class="products-stack">
                                    <?php if (is_array($productos) && count($productos) > 0): ?>
                                        <?php foreach ($productos as $producto): ?>
                                            <span><?= htmlspecialchars($producto['nombre'] ?? 'Producto') ?></span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="text-muted">Sin productos</span>
                                    <?php endif; ?>
                                    </div>
                                </div>
                                <div class="order-detail total-detail">
                                    <span class="detail-label">Total del pedido</span>
                                    <strong class="money">$<?= number_format((int)$total, 0, ',', '.') ?></strong>
                                </div>
                            </div>
                            <footer class="order-actions">
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
                            </footer>
                        </article>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="empty-state">
                            <i class="bi bi-check2-circle"></i>
                            <strong>No hay domicilios pendientes</strong>
                            <span>Cuando tengas nuevas entregas asignadas, aparecerán aquí.</span>
                    </div>
                <?php endif; ?>
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

<?php include(__DIR__ . '/../../includes/asistente_rutas.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../js/avisos.js"></script>
<script>
const origenRestaurante = 'Cra. 79 #42B-07, Antonio Narino, Bogota, Colombia';
const maxPedidosRuta = 5;

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
  if (destinosValidos.length > maxPedidosRuta) {
    alert(`Solo puedes seleccionar maximo ${maxPedidosRuta} pedidos por ruta.`);
    return;
  }

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

document.querySelectorAll('.pedido-ruta').forEach((checkbox) => {
  checkbox.addEventListener('change', () => {
    const seleccionados = document.querySelectorAll('.pedido-ruta:checked');
    if (seleccionados.length > maxPedidosRuta) {
      checkbox.checked = false;
      alert(`Solo puedes seleccionar maximo ${maxPedidosRuta} pedidos para llevarlos.`);
    }
  });
});

document.getElementById('abrirRutaConjunta')?.addEventListener('click', (event) => {
  event.stopImmediatePropagation();
  const destinos = [...document.querySelectorAll('.pedido-ruta:checked')].map(x => x.value).filter(Boolean);
  if (!destinos.length) return alert('Selecciona al menos un pedido para crear la ruta.');
  if (destinos.length > maxPedidosRuta) return alert(`Solo puedes seleccionar maximo ${maxPedidosRuta} pedidos por ruta.`);
  iniciarRuta(destinos);
});

document.getElementById('modalRuta')?.addEventListener('hidden.bs.modal', () => {
  document.getElementById('mapaRuta').src = '';
});
</script>
</body>
</html>
