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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domicilios | Repartidor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/repartidor.css?v=5">
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
        <p>Elige un pedido base y revisa los domicilios que se pueden agrupar antes de iniciar la ruta.</p>
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
                        <?php $pedido_json = htmlspecialchars(json_encode([
                            'id' => (int) $row['id'],
                            'cliente' => $row['nombre'] ?? 'Cliente',
                            'direccion' => $row['direccion'] ?? '',
                            'estado' => $row['estado'] ?? '',
                            'repartidor' => $row['repartidor'] ?? ''
                        ], JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8'); ?>
                        <tr data-pedido='<?= $pedido_json ?>'>
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

<button type="button" id="abrirAsistenteRuta" class="route-assistant-launcher" aria-label="Abrir asistente de rutas" aria-expanded="false">
  <span class="route-assistant-avatar" aria-hidden="true"></span>
  <span class="route-assistant-pulse"></span>
</button>

<aside id="asistenteRuta" class="route-assistant" aria-hidden="true" aria-label="Asistente virtual de rutas">
  <header class="route-assistant-header">
    <span class="route-assistant-avatar route-assistant-avatar-small" aria-hidden="true"></span>
    <div><strong>Capitán Ruta</strong><small><i class="bi bi-circle-fill"></i> Disponible</small></div>
    <button type="button" id="cerrarAsistenteRuta" class="btn-close btn-close-white" aria-label="Cerrar asistente"></button>
  </header>
  <div class="route-assistant-body">
    <div class="assistant-message">
      <p>¡Hola! Soy tu asistente de rutas. Elige el pedido base y te diré cuáles puedes agrupar.</p>
    </div>
    <div id="listaPedidosAsistente" class="assistant-order-list" aria-label="Pedidos disponibles"></div>
    <div id="resultadoAgrupacion" class="route-suggestion d-none" aria-live="polite"></div>
  </div>
  <footer id="accionesAsistente" class="route-assistant-footer d-none">
    <button type="button" class="btn btn-outline-secondary btn-sm" id="elegirOtroPedido">Cambiar pedido</button>
    <button type="button" class="btn btn-warning btn-sm" id="usarSugerencia"><i class="bi bi-map"></i> Usar ruta</button>
  </footer>
</aside>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../js/avisos.js"></script>
<script>
const origenRestaurante = 'Cra. 79 #42B-07, Antonio Narino, Bogota, Colombia';
const maxPedidosRuta = 5;
const maxSugeridos = 4;
let sugerenciaActual = [];

function obtenerPedidosActivos() {
  return [...document.querySelectorAll('tr[data-pedido]')].map((fila) => {
    try { return JSON.parse(fila.dataset.pedido); } catch (_) { return null; }
  }).filter(Boolean);
}

function direccionIncompleta(direccion) {
  const texto = String(direccion || '').trim();
  // Una dirección útil debe contener número y alguna referencia vial; evita sugerir datos como "JO" o "FJFJ".
  return texto.length < 8 || !/\d/.test(texto) || !/(cra|carrera|cl|calle|av|avenida|transv|diagonal|dg|kr)/i.test(texto);
}

function referenciaVial(direccion) {
  const texto = String(direccion || '').toLowerCase().replace(/\./g, ' ');
  const via = texto.match(/\b(cra|carrera|kr|cl|calle|av|avenida|transv|transversal|diag|diagonal|dg)\s*([\d]+[a-z-]*)/i);
  const cruce = texto.match(/#\s*(\d+[a-z-]*)/i);
  if (!via || !cruce) return null;
  return { tipo: via[1], viaNumero: parseInt(via[2], 10), cruceNumero: parseInt(cruce[1], 10) };
}

function mismaFamiliaVia(a, b) {
  const carreras = ['cra', 'carrera', 'kr'];
  const calles = ['cl', 'calle'];
  const diagonales = ['dg', 'diag', 'diagonal'];
  return (carreras.includes(a) && carreras.includes(b))
    || (calles.includes(a) && calles.includes(b))
    || (diagonales.includes(a) && diagonales.includes(b))
    || a === b;
}

function evaluarCercania(base, candidato) {
  const a = referenciaVial(base.direccion);
  const b = referenciaVial(candidato.direccion);
  if (!a || !b) return null;
  const deltaVia = Math.abs(a.viaNumero - b.viaNumero);
  const deltaCruce = Math.abs(a.cruceNumero - b.cruceNumero);
  const mismaVia = mismaFamiliaVia(a.tipo, b.tipo) && deltaVia === 0;

  if (mismaVia && deltaCruce <= 12) {
    return { prioridad: deltaCruce, texto: `misma vía, a unas ${Math.max(1, deltaCruce)} cuadras aprox.` };
  }
  if (mismaFamiliaVia(a.tipo, b.tipo) && deltaVia <= 3 && deltaCruce <= 8) {
    return { prioridad: deltaVia + deltaCruce + 4, texto: 'posible cercanía, confirmar en mapa' };
  }
  return null;
}

function escaparHtml(valor) {
  const nodo = document.createElement('span');
  nodo.textContent = String(valor || '');
  return nodo.innerHTML;
}

function cargarPedidosEnAsistente() {
  const lista = document.getElementById('listaPedidosAsistente');
  const pedidos = obtenerPedidosActivos();
  lista.innerHTML = pedidos.length
    ? pedidos.map((pedido) => `<button type="button" class="assistant-order" data-pedido-id="${pedido.id}"><strong>${escaparHtml(pedido.cliente)}</strong><span>${escaparHtml(pedido.direccion || 'Dirección sin registrar')}</span></button>`).join('')
    : '<p class="text-muted small mb-0">No hay pedidos disponibles.</p>';
  lista.querySelectorAll('.assistant-order').forEach((boton) => {
    boton.addEventListener('click', () => {
      const pedido = pedidos.find((item) => item.id === Number(boton.dataset.pedidoId));
      if (pedido) mostrarAgrupacion(pedido);
    });
  });
}

function abrirAsistenteRuta() {
  document.getElementById('asistenteRuta').classList.add('is-open');
  document.getElementById('asistenteRuta').setAttribute('aria-hidden', 'false');
  document.getElementById('abrirAsistenteRuta').setAttribute('aria-expanded', 'true');
  cargarPedidosEnAsistente();
}

function cerrarAsistenteRuta() {
  document.getElementById('asistenteRuta').classList.remove('is-open');
  document.getElementById('asistenteRuta').setAttribute('aria-hidden', 'true');
  document.getElementById('abrirAsistenteRuta').setAttribute('aria-expanded', 'false');
}

function mostrarAgrupacion(base) {
  const pedidos = obtenerPedidosActivos();
  const incompletos = pedidos.filter((p) => p.id !== base.id && direccionIncompleta(p.direccion));
  const cercanos = pedidos
    .filter((p) => p.id !== base.id && !direccionIncompleta(p.direccion))
    .map((p) => ({ pedido: p, evaluacion: evaluarCercania(base, p) }))
    .filter((item) => item.evaluacion)
    .sort((a, b) => a.evaluacion.prioridad - b.evaluacion.prioridad)
    .slice(0, maxSugeridos);

  sugerenciaActual = [base, ...cercanos.map((item) => item.pedido)];
  const salida = document.getElementById('resultadoAgrupacion');
  document.getElementById('listaPedidosAsistente').classList.add('d-none');
  document.getElementById('accionesAsistente').classList.remove('d-none');
  let html = `<p><strong>${escaparHtml(base.cliente)}</strong> está en ${escaparHtml(base.direccion)}.</p>`;
  if (!cercanos.length) {
    html += '<p class="mb-0"><strong>Este pedido va solo, no hay otros pendientes cerca de su ruta.</strong></p>';
  } else {
    html += '<p>Estos pedidos pasan cerca o quedan en el camino:</p><ol class="route-suggestion-list">';
    html += cercanos.map(({ pedido, evaluacion }) => `<li><strong>${escaparHtml(pedido.cliente)}</strong> – ${escaparHtml(pedido.direccion)} <span>(${escaparHtml(evaluacion.texto)})</span></li>`).join('');
    html += `</ol><p class="mb-0"><strong>Orden sugerido de entrega:</strong> ${cercanos.map(({ pedido }) => escaparHtml(pedido.cliente)).join(' → ')} → ${escaparHtml(base.cliente)}, para no cruzar zonas ni regresar.</p>`;
  }
  if (incompletos.length) {
    html += `<p class="route-warning mb-0"><i class="bi bi-exclamation-triangle"></i> Dirección incompleta, verificar antes de asignar: ${incompletos.map((p) => escaparHtml(p.cliente)).join(', ')}.</p>`;
  }
  salida.innerHTML = html;
  salida.classList.remove('d-none');
}

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

document.getElementById('abrirAsistenteRuta')?.addEventListener('click', abrirAsistenteRuta);
document.getElementById('cerrarAsistenteRuta')?.addEventListener('click', cerrarAsistenteRuta);

document.getElementById('elegirOtroPedido')?.addEventListener('click', () => {
  sugerenciaActual = [];
  document.getElementById('resultadoAgrupacion').classList.add('d-none');
  document.getElementById('accionesAsistente').classList.add('d-none');
  document.getElementById('listaPedidosAsistente').classList.remove('d-none');
  cargarPedidosEnAsistente();
});

document.getElementById('usarSugerencia')?.addEventListener('click', () => {
  if (!sugerenciaActual.length) return;
  cerrarAsistenteRuta();
  iniciarRuta([...sugerenciaActual.slice(1), sugerenciaActual[0]].map((pedido) => pedido.direccion));
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
