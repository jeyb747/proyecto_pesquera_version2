(() => {
  const pedidos = Array.isArray(window.pedidosAsistenteRuta) ? window.pedidosAsistenteRuta : [];
  const maxSugeridos = 4;
  let sugerenciaActual = [];

  const escaparHtml = (valor) => {
    const nodo = document.createElement('span');
    nodo.textContent = String(valor || '');
    return nodo.innerHTML;
  };

  const direccionIncompleta = (direccion) => {
    const texto = String(direccion || '').trim();
    return texto.length < 8 || !/\d/.test(texto) || !/\b(cra|carrera|kr|k|cl|cll|calle|c|av|avda|avenida|ac|ak|tv|transv|transversal|diag|diagonal|dg|autop|autopista|circ|circular)\b/i.test(texto);
  };

  const normalizarTipoVia = (tipo) => {
    const valor = String(tipo || '').replace(/\s+/g, ' ').trim();
    const equivalencias = {
      'calle': 'calle', 'cl': 'calle', 'cll': 'calle', 'c': 'calle',
      'carrera': 'carrera', 'cra': 'carrera', 'kr': 'carrera', 'k': 'carrera',
      'avenida': 'avenida', 'av': 'avenida', 'avda': 'avenida',
      'diagonal': 'diagonal', 'diag': 'diagonal', 'dg': 'diagonal',
      'transversal': 'transversal', 'transv': 'transversal', 'tv': 'transversal',
      'avenida calle': 'avenida-calle', 'av calle': 'avenida-calle', 'ac': 'avenida-calle',
      'avenida carrera': 'avenida-carrera', 'av carrera': 'avenida-carrera', 'ak': 'avenida-carrera',
      'autopista': 'autopista', 'autop': 'autopista', 'circular': 'circular', 'circ': 'circular'
    };
    return equivalencias[valor] || valor;
  };

  const referenciaVial = (direccion) => {
    const texto = String(direccion || '').toLowerCase().replace(/\./g, ' ');
    const via = texto.match(/\b(avenida\s*calle|av\s*calle|avenida\s*carrera|av\s*carrera|autopista|autop|transversal|transv|diagonal|circular|carrera|avenida|calle|avda|cra|cll|diag|circ|kr|cl|dg|tv|ac|ak|av|k|c)\s*([\d]+[a-z-]*)/i);
    const cruce = texto.match(/#\s*(\d+[a-z-]*)/i);
    if (!via || !cruce) return null;
    return { tipo: normalizarTipoVia(via[1]), viaNumero: parseInt(via[2], 10), cruceNumero: parseInt(cruce[1], 10) };
  };

  const mismaFamiliaVia = (a, b) => a === b
    || (a === 'avenida-calle' && b === 'calle') || (a === 'calle' && b === 'avenida-calle')
    || (a === 'avenida-carrera' && b === 'carrera') || (a === 'carrera' && b === 'avenida-carrera');

  const evaluarCercania = (base, candidato) => {
    const a = referenciaVial(base.direccion);
    const b = referenciaVial(candidato.direccion);
    if (!a || !b) return null;
    const deltaVia = Math.abs(a.viaNumero - b.viaNumero);
    const deltaCruce = Math.abs(a.cruceNumero - b.cruceNumero);
    if (mismaFamiliaVia(a.tipo, b.tipo) && deltaVia === 0 && deltaCruce <= 12) return { prioridad: deltaCruce, texto: `misma vía, a unas ${Math.max(1, deltaCruce)} cuadras aprox.` };
    if (mismaFamiliaVia(a.tipo, b.tipo) && deltaVia <= 3 && deltaCruce <= 8) return { prioridad: deltaVia + deltaCruce + 4, texto: 'posible cercanía, confirmar en mapa' };
    return null;
  };

  const cargarPedidos = () => {
    const lista = document.getElementById('listaPedidosAsistente');
    lista.innerHTML = pedidos.length ? pedidos.map((pedido) => `<button type="button" class="assistant-order" data-pedido-id="${pedido.id}"><strong>${escaparHtml(pedido.cliente)}</strong><span>${escaparHtml(pedido.direccion || 'Dirección sin registrar')}</span></button>`).join('') : '<p class="text-muted small mb-0">No hay pedidos disponibles.</p>';
    lista.querySelectorAll('.assistant-order').forEach((boton) => boton.addEventListener('click', () => {
      const pedido = pedidos.find((item) => item.id === Number(boton.dataset.pedidoId));
      if (pedido) mostrarAgrupacion(pedido);
    }));
  };

  const abrir = () => { document.getElementById('asistenteRuta').classList.add('is-open'); document.getElementById('asistenteRuta').setAttribute('aria-hidden', 'false'); document.getElementById('abrirAsistenteRuta').setAttribute('aria-expanded', 'true'); cargarPedidos(); };
  const cerrar = () => { document.getElementById('asistenteRuta').classList.remove('is-open'); document.getElementById('asistenteRuta').setAttribute('aria-hidden', 'true'); document.getElementById('abrirAsistenteRuta').setAttribute('aria-expanded', 'false'); };

  const mostrarAgrupacion = (base) => {
    const incompletos = pedidos.filter((p) => p.id !== base.id && direccionIncompleta(p.direccion));
    const cercanos = pedidos.filter((p) => p.id !== base.id && !direccionIncompleta(p.direccion)).map((p) => ({ pedido: p, evaluacion: evaluarCercania(base, p) })).filter((item) => item.evaluacion).sort((a, b) => a.evaluacion.prioridad - b.evaluacion.prioridad).slice(0, maxSugeridos);
    sugerenciaActual = [base, ...cercanos.map((item) => item.pedido)];
    document.getElementById('listaPedidosAsistente').classList.add('d-none');
    document.getElementById('accionesAsistente').classList.remove('d-none');
    const salida = document.getElementById('resultadoAgrupacion');
    let html = `<p><strong>${escaparHtml(base.cliente)}</strong> está en ${escaparHtml(base.direccion)}.</p>`;
    if (!cercanos.length) html += '<p class="mb-0"><strong>Este pedido va solo, no hay otros pendientes cerca de su ruta.</strong></p>';
    else html += `<p>Estos pedidos pasan cerca o quedan en el camino:</p><ol class="route-suggestion-list">${cercanos.map(({ pedido, evaluacion }) => `<li><strong>${escaparHtml(pedido.cliente)}</strong> – ${escaparHtml(pedido.direccion)} <span>(${escaparHtml(evaluacion.texto)})</span></li>`).join('')}</ol><p class="mb-0"><strong>Orden sugerido de entrega:</strong> ${cercanos.map(({ pedido }) => escaparHtml(pedido.cliente)).join(' → ')} → ${escaparHtml(base.cliente)}, para no cruzar zonas ni regresar.</p>`;
    if (incompletos.length) html += `<p class="route-warning mb-0"><i class="bi bi-exclamation-triangle"></i> Dirección incompleta, verificar antes de asignar: ${incompletos.map((p) => escaparHtml(p.cliente)).join(', ')}.</p>`;
    salida.innerHTML = html;
    salida.classList.remove('d-none');
  };

  document.getElementById('abrirAsistenteRuta')?.addEventListener('click', abrir);
  document.getElementById('cerrarAsistenteRuta')?.addEventListener('click', cerrar);
  document.getElementById('elegirOtroPedido')?.addEventListener('click', () => { sugerenciaActual = []; document.getElementById('resultadoAgrupacion').classList.add('d-none'); document.getElementById('accionesAsistente').classList.add('d-none'); document.getElementById('listaPedidosAsistente').classList.remove('d-none'); cargarPedidos(); });
  document.getElementById('usarSugerencia')?.addEventListener('click', () => { if (!sugerenciaActual.length || typeof window.iniciarRuta !== 'function') return; cerrar(); window.iniciarRuta([...sugerenciaActual.slice(1), sugerenciaActual[0]].map((pedido) => pedido.direccion)); });
})();
