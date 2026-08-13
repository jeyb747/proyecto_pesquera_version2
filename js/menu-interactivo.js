// ============================================================
// FORMATO COP
// ============================================================

const fmtCOP = new Intl.NumberFormat('es-CO', {
  style: 'currency',
  currency: 'COP',
  maximumFractionDigits: 0
});

// ============================================================
// CARRITO
// ============================================================

let carrito = JSON.parse(localStorage.getItem('carrito')) || [];

function requiereInicioSesion() {
  if (window.usuarioAutenticado) return false;

  alert('Debes iniciar sesión para agregar productos al carrito.');
  window.location.href = 'login.php?next=menu';
  return true;
}

const contadorCarrito = document.getElementById('contador-carrito');

function actualizarContador() {

  if (contadorCarrito) {
    contadorCarrito.textContent = carrito.length;
  }

}

actualizarContador();

document.addEventListener('DOMContentLoaded', () => {
  const input = document.getElementById('buscarPlato');
  const empty = document.getElementById('sinResultados');
  if (!input) return;
  input.addEventListener('input', () => {
    const query = normalizar(input.value);
    let visible = 0;
    document.querySelectorAll('.producto').forEach(card => {
      const nombre = normalizar(card.dataset.plato || '');
      const show = !query || nombre.startsWith(query);
      const wrapper = card.parentElement;
      if (wrapper) wrapper.style.display = show ? '' : 'none';
      if (show) visible++;
    });
    empty?.classList.toggle('d-none', visible > 0);
  });
});

function normalizar(texto) {
  return String(texto)
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
    .toLowerCase()
    .trim();
}

// ============================================================
// MODAL BOOTSTRAP
// ============================================================

const modalElement = document.getElementById('modalProducto');

const modalBootstrap = new bootstrap.Modal(modalElement);

const modalImg = document.getElementById('modalImg');
const modalTitulo = document.getElementById('modalTitulo');
const modalDescripcion = document.getElementById('modalDescripcion');
const modalPrecio = document.getElementById('modalPrecio');
const btnCarrito = document.getElementById('btnCarrito');

// ============================================================
// ABRIR MODAL
// ============================================================

document.querySelectorAll('.producto').forEach(card => {

  card.addEventListener('click', () => {

    const nombre = card.dataset.plato;
    const imagen = card.dataset.img;
    const desc = card.dataset.desc || 'Delicioso plato de La Pesquera';
    const precio = Number(card.dataset.precio);

    modalImg.src = imagen;
    modalTitulo.textContent = nombre;
    modalDescripcion.textContent = desc;
    modalPrecio.textContent = fmtCOP.format(precio);

    modalBootstrap.show();

  });

});

// ============================================================
// ➕ BOTÓN +
// ============================================================

document.addEventListener('click', (e) => {

  const btn = e.target.closest('.btn-add');

  if (!btn) return;

  if (requiereInicioSesion()) return;

  e.stopPropagation();

  const card = btn.closest('.producto');

  const producto = {
    nombre: card.dataset.plato,
    precio: fmtCOP.format(Number(card.dataset.precio)),
    imagen: card.dataset.img
  };

  carrito.push(producto);

  localStorage.setItem('carrito', JSON.stringify(carrito));

  actualizarContador();

  btn.textContent = 'Agregado';
  btn.disabled = true;

  setTimeout(() => {
    btn.textContent = '+';
    btn.disabled = false;
  }, 700);

});

// ============================================================
// AGREGAR DESDE MODAL
// ============================================================

btnCarrito.addEventListener('click', () => {
  if (requiereInicioSesion()) return;

  const producto = {
    nombre: modalTitulo.textContent,
    precio: modalPrecio.textContent,
    imagen: modalImg.src
  };

  carrito.push(producto);

  localStorage.setItem('carrito', JSON.stringify(carrito));

  actualizarContador();

  btnCarrito.textContent = 'Agregado';

  setTimeout(() => {

    btnCarrito.innerHTML = 'Agregar al carrito';

    modalBootstrap.hide();

  }, 800);

});
