// ======================================================
// domicilio.js - Mostrar resumen, validar y pedir comprobante de NEQUI
// ======================================================

document.addEventListener('DOMContentLoaded', () => {

  const productosContainer = document.getElementById('productosCarrito');
  const resumen = document.getElementById('resumenCarrito');
  const cantidadSpan = document.getElementById('cantidadProductos');
  const totalSpan = document.getElementById('totalCarrito');
  const form = document.getElementById('formDomicilio');
  const mensaje = document.getElementById('mensajeExito');

  // Campo del comprobante NEQUI
  const campoComprobante = document.getElementById('campoComprobante');
  const inputComprobante = document.getElementById('comprobante');
  const selectPago = document.getElementById('pago');

  // Mostrar / ocultar comprobante según método de pago
  selectPago.addEventListener('change', () => {
    if (selectPago.value === "nequi") {
      campoComprobante.classList.remove("oculto");
    } else {
      campoComprobante.classList.add("oculto");
      inputComprobante.value = "";
    }
  });

  // Cargar carrito desde localStorage
  const carrito = JSON.parse(localStorage.getItem('carrito')) || [];

  // Si no hay productos
  if (carrito.length === 0) {
    productosContainer.innerHTML = `
      <p>No tienes productos en el carrito.</p>
      <a href="menu.html" class="btn-enviar" style="display:block; text-align:center; margin-top:10px;">Ir al menú</a>
    `;
    productosContainer.classList.add('vacio');
    form.style.display = 'none';
    resumen.style.display = 'none';
    return;
  }

  // Mostrar productos
  productosContainer.classList.remove('vacio');
  resumen.classList.remove('oculto');

  let total = 0;
  productosContainer.innerHTML = ""; // limpiar antes de pintar

  carrito.forEach(item => {
    const imagenSrc = item.imagen || item.img || 'imagenes/default.jpg';
    const precioNum = parseInt(String(item.precio).replace(/\D/g, '')) || 0;
    total += precioNum;

    productosContainer.innerHTML += `
      <div class="producto-item">
        <img src="${imagenSrc}" alt="${item.nombre}">
        <div class="producto-info">
          <span>${item.nombre}</span>
          <strong>$${precioNum.toLocaleString()}</strong>
        </div>
      </div>
    `;
  });

  // Mostrar resumen
  cantidadSpan.textContent = carrito.length;
  totalSpan.textContent = `$${total.toLocaleString()}`;

  // Envío del formulario
  form.addEventListener('submit', e => {
    e.preventDefault();

    const nombre = form.nombre.value.trim();
    const direccion = form.direccion.value.trim();
    const telefono = form.telefono.value.trim();
    const pago = form.pago.value;

    if (!nombre || !direccion || !telefono || !pago) {
      alert('Por favor completa todos los campos obligatorios.');
      return;
    }

    // Validar comprobante si es NEQUI
    if (pago === "nequi") {
      if (!inputComprobante.files || inputComprobante.files.length === 0) {
        alert("Debes subir el comprobante del pago por Nequi.");
        return;
      }
    }

    mensaje.style.display = 'block';
    mensaje.textContent = '✅ Pedido enviado con éxito. ¡Gracias por elegirnos!';

    // Borrar carrito
    localStorage.removeItem('carrito');
    form.reset();
    campoComprobante.classList.add("oculto");

    setTimeout(() => {
      mensaje.style.display = 'none';
      window.location.href = 'index.html';
    }, 4000);
  });

});