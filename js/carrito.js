// carrito.js - completo 100% funcional

document.addEventListener('DOMContentLoaded', () => {

  console.log('carrito.js cargado');

  const contenedor = document.getElementById('carrito-container');
  const totalEl = document.getElementById('total');
  const btnVaciar = document.getElementById('vaciarCarrito');
  const contadorGlobal = document.getElementById('contador-carrito');

  if (!contenedor) {
    console.error('No se encontró #carrito-container');
    return;
  }

  /* ================================
        Cargar datos del carrito
  ================================= */

  let carrito;

  try {

    carrito = JSON.parse(localStorage.getItem('carrito')) || [];

  } catch (err) {

    carrito = [];

  }

  console.log('Carrito cargado:', carrito);

  /* ================================
        Formato COP
  ================================= */

  const fmtCOP = new Intl.NumberFormat('es-CO', {
    style: 'currency',
    currency: 'COP',
    maximumFractionDigits: 0
  });

  /* ================================
        Escapar HTML
  ================================= */

  function escapeHtml(text) {

    return String(text || '')
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');

  }

  /* ================================
        Contador navbar
  ================================= */

  function actualizarContador() {

    if (contadorGlobal) {

      contadorGlobal.textContent = carrito.length;

    }

  }

  /* ================================
        Convertir precio
  ================================= */

  function obtenerPrecio(valor) {

    return parseInt(
      String(valor || '0').replace(/\D/g, '')
    ) || 0;

  }

  /* ================================
        Calcular total
  ================================= */

  function calcularTotal() {

    return carrito.reduce((suma, p) => {

      return suma + obtenerPrecio(
        p.precio || p.price
      );

    }, 0);

  }

  /* ================================
        Render carrito
  ================================= */

  function renderCarrito() {

    contenedor.innerHTML = '';

    if (carrito.length === 0) {

      contenedor.innerHTML = `
        <p class='vacio'>
          🛍️ Tu carrito está vacío.
        </p>
      `;

      totalEl.textContent = 'Total: $0';

      actualizarContador();

      return;
    }

    carrito.forEach((producto, index) => {

      const img =
        producto.img ||
        producto.imagen ||
        'imagenes/default.jpg';

      const precioNum = obtenerPrecio(
        producto.precio || producto.price
      );

      const item = document.createElement('div');

      item.className = 'carrito-item';

      item.innerHTML = `

        <img
          class="carrito-thumb"
          src="${escapeHtml(img)}"
          alt="${escapeHtml(producto.nombre)}">

        <div class="carrito-info">

          <h3>${escapeHtml(producto.nombre)}</h3>

          <p class="precio">
            ${fmtCOP.format(precioNum)}
          </p>

        </div>

        <button
          class="btn-eliminar"
          data-index="${index}">

          Eliminar

        </button>

      `;

      contenedor.appendChild(item);

    });

    totalEl.textContent =
      'Total: ' + fmtCOP.format(calcularTotal());

    actualizarContador();

  }

  /* ================================
        Eliminar producto
  ================================= */

  contenedor.addEventListener('click', (e) => {

    const btn = e.target.closest('.btn-eliminar');

    if (!btn) return;

    const idx = Number(btn.dataset.index);

    carrito.splice(idx, 1);

    localStorage.setItem(
      'carrito',
      JSON.stringify(carrito)
    );

    renderCarrito();

  });

  /* ================================
        Vaciar carrito
  ================================= */

  if (btnVaciar) {

    btnVaciar.addEventListener('click', () => {

      if (carrito.length === 0) {

        alert('El carrito ya está vacío.');
        return;

      }

      if (!confirm('¿Vaciar carrito?')) return;

      carrito = [];

      localStorage.removeItem('carrito');

      renderCarrito();

      alert('Carrito vaciado.');

    });

  }

  /* ================================
        MODAL DE PAGO
  ================================= */

  const btnPagar = document.getElementById('btnPagar');
  const modalPago = document.getElementById('modalPago');
  const opcionesPago = document.getElementById('opcionesPago');

  if (btnPagar) {

    btnPagar.addEventListener('click', () => {

      if (carrito.length === 0) {

        alert('El carrito está vacío.');
        return;

      }

      const modal = new bootstrap.Modal(modalPago);

      modal.show();

    });

  }

  /* ================================
        MÉTODOS DE PAGO
  ================================= */

  document.querySelectorAll('.metodo').forEach(btn => {

    btn.addEventListener('click', () => {

      const metodo = btn.dataset.metodo;

      let contenido = '';

      /* ===== EFECTIVO ===== */

      if (metodo === 'efectivo') {

        contenido = `

          <p>
            <strong>
              💵 Pagarás en efectivo al recibir el domicilio.
            </strong>
          </p>

          <button
            id="confirmarPago"
            class="btn btn-success mt-3 w-100">

            Confirmar pago

          </button>

        `;
      }

      /* ===== NEQUI ===== */

      if (metodo === 'nequi') {

        contenido = `

          <p>📱 Envía el pago a:</p>

          <h3>300 840 4600</h3>

          <button
            id="confirmarPago"
            class="btn btn-success mt-3 w-100">

            Confirmar pago

          </button>

        `;
      }

      /* ===== TARJETA ===== */

      if (metodo === 'tarjeta') {

        contenido = `

          <p>
            💳 Puedes pagar con tu tarjeta
            al recibir el pedido.
          </p>

          <button
            id="confirmarPago"
            class="btn btn-success mt-3 w-100">

            Confirmar pago

          </button>

        `;
      }

      opcionesPago.innerHTML = contenido;

      setTimeout(() => {

        const btnConfirmar =
          document.getElementById('confirmarPago');

        if (!btnConfirmar) return;

        btnConfirmar.addEventListener('click', () => {

          const total = calcularTotal();

          localStorage.setItem(
            'total_pedido',
            total
          );

          window.location.href = 'domicilio.php';

        });

      }, 50);

    });

  });

  /* ================================
        Render inicial
  ================================= */

  renderCarrito();

});