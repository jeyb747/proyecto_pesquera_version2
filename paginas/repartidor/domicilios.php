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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
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
        <div id="mapaRuta"></div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="../../js/avisos.js"></script>
<script>
const origenRestaurante = 'Cra. 79 #42B-07, Antonio Narino, Bogota, Colombia';
let mapaRuta;
let rutaLayer;
let marcadorActual;
let marcadorSalida;
let marcadoresDestino = [];
let seguimientoId;

function crearUrlGoogleMaps(destinos) {
  const pendientes = [...destinos];
  const destino = pendientes.pop();
  const url = new URL('https://www.google.com/maps/dir/?api=1');
  url.searchParams.set('origin', origenRestaurante);
  url.searchParams.set('destination', destino);
  url.searchParams.set('travelmode', 'driving');
  url.searchParams.set('dir_action', 'navigate');
  if (pendientes.length) url.searchParams.set('waypoints', pendientes.join('|'));
  return url.toString();
}

function setRutaEstado(mensaje) {
  document.getElementById('rutaEstado').textContent = mensaje;
}

async function geocodificar(direccion) {
  const url = new URL('https://nominatim.openstreetmap.org/search');
  url.searchParams.set('format', 'json');
  url.searchParams.set('limit', '1');
  url.searchParams.set('q', direccion);
  const respuesta = await fetch(url);
  const datos = await respuesta.json();
  if (!datos.length) throw new Error(`No se encontro la direccion: ${direccion}`);
  return [Number(datos[0].lat), Number(datos[0].lon)];
}

async function dibujarRuta(destinos) {
  const puntos = await Promise.all([origenRestaurante, ...destinos].map(geocodificar));
  const coordenadasOsrm = puntos.map(([lat, lon]) => `${lon},${lat}`).join(';');
  const respuesta = await fetch(`https://router.project-osrm.org/route/v1/driving/${coordenadasOsrm}?overview=full&geometries=geojson`);
  const datos = await respuesta.json();
  if (!datos.routes?.length) throw new Error('No se pudo calcular el trayecto.');

  if (!mapaRuta) {
    mapaRuta = L.map('mapaRuta');
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap'
    }).addTo(mapaRuta);
  }

  if (rutaLayer) rutaLayer.remove();
  if (marcadorSalida) marcadorSalida.remove();
  if (marcadorActual) marcadorActual.remove();
  marcadoresDestino.forEach((marcador) => marcador.remove());
  marcadoresDestino = [];
  marcadorActual = null;

  const linea = datos.routes[0].geometry.coordinates.map(([lon, lat]) => [lat, lon]);
  rutaLayer = L.polyline(linea, { color: '#0A3D62', weight: 6, opacity: 0.9 }).addTo(mapaRuta);
  marcadorSalida = L.marker(puntos[0]).addTo(mapaRuta).bindPopup('Salida: La Pesquera');
  puntos.slice(1).forEach((punto, index) => {
    marcadoresDestino.push(L.marker(punto).addTo(mapaRuta).bindPopup(`Entrega ${index + 1}`));
  });
  mapaRuta.fitBounds(rutaLayer.getBounds(), { padding: [28, 28] });
}

function iniciarSeguimiento() {
  if (!navigator.geolocation) {
    setRutaEstado('Ruta lista. Este navegador no permite mostrar tu ubicacion actual.');
    return;
  }

  if (seguimientoId) navigator.geolocation.clearWatch(seguimientoId);
  seguimientoId = navigator.geolocation.watchPosition(
    ({ coords }) => {
      const posicion = [coords.latitude, coords.longitude];
      if (!marcadorActual) {
        marcadorActual = L.circleMarker(posicion, {
          radius: 9,
          color: '#0A3D62',
          fillColor: '#F1C40F',
          fillOpacity: 1,
          weight: 3
        }).addTo(mapaRuta).bindPopup('Vas aqui');
      } else {
        marcadorActual.setLatLng(posicion);
      }
      mapaRuta.panTo(posicion, { animate: true });
      setRutaEstado('Ruta activa. El punto amarillo muestra donde vas.');
    },
    () => setRutaEstado('Ruta lista. Activa el permiso de ubicacion para ver donde vas.'),
    { enableHighAccuracy: true, maximumAge: 10000, timeout: 12000 }
  );
}

async function iniciarRuta(destinos) {
  const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalRuta'));
  document.getElementById('abrirGoogleMaps').href = crearUrlGoogleMaps(destinos);
  modal.show();
  setRutaEstado('Calculando trayecto desde La Pesquera...');

  setTimeout(() => mapaRuta?.invalidateSize(), 250);
  try {
    await dibujarRuta(destinos);
    setTimeout(() => mapaRuta?.invalidateSize(), 250);
    setRutaEstado('Ruta lista. Buscando tu ubicacion actual...');
    iniciarSeguimiento();
  } catch (error) {
    setRutaEstado(error.message + ' Puedes abrirla en Google Maps.');
  }
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
  if (seguimientoId) {
    navigator.geolocation.clearWatch(seguimientoId);
    seguimientoId = null;
  }
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
