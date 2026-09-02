<?php
// Componente autónomo del asistente de agrupación de rutas.
$pedidos_asistente_json = json_encode($pedidos_asistente ?? [], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
?>
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
    <div class="assistant-message"><p>¡Hola! Soy tu asistente de rutas. Elige el pedido base y te diré cuáles puedes agrupar.</p></div>
    <div id="listaPedidosAsistente" class="assistant-order-list" aria-label="Pedidos disponibles"></div>
    <div id="resultadoAgrupacion" class="route-suggestion d-none" aria-live="polite"></div>
  </div>
  <footer id="accionesAsistente" class="route-assistant-footer d-none">
    <button type="button" class="btn btn-outline-secondary btn-sm" id="elegirOtroPedido">Cambiar pedido</button>
    <button type="button" class="btn btn-warning btn-sm" id="usarSugerencia"><i class="bi bi-map"></i> Usar ruta</button>
  </footer>
</aside>
<script>window.pedidosAsistenteRuta = <?= $pedidos_asistente_json ?: '[]' ?>;</script>
<script src="/js/asistente-rutas.js?v=1" defer></script>
