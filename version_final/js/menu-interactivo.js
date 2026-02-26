// ============================================================
// 💰 Formateo de precios en COP sin decimales
// ============================================================
const fmtCOP = new Intl.NumberFormat('es-CO', {
  style: 'currency',
  currency: 'COP',
  maximumFractionDigits: 0
});

// ============================================================
// 🧩 Elementos del modal
// ============================================================
const modal = document.getElementById('modalProducto');
const modalImg = document.getElementById('modalImg');
const modalTitulo = document.getElementById('modalTitulo');
const modalDescripcion = document.getElementById('modalDescripcion');
const modalPrecio = document.getElementById('modalPrecio');
const cerrarModal = document.getElementById('cerrarModal');
const btnCarrito = document.getElementById('btnCarrito');
const contadorCarrito = document.getElementById('contador-carrito');

// ============================================================
// 🛒 Cargar carrito guardado en localStorage
// ============================================================
let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
if (contadorCarrito) contadorCarrito.textContent = carrito.length;

// ============================================================
// 📦 Abre modal usando los data-* de cada fila
// ============================================================
document.querySelectorAll('table.menu-table tr[data-plato]').forEach(tr => {
  tr.addEventListener('click', () => {
    const nombre = tr.dataset.plato || '';
    const imagen = tr.dataset.img || 'imagenes/default.jpg';
    const desc   = tr.dataset.desc || '';
    const precio = Number(tr.dataset.precio || 0);

    modalImg.src = imagen;
    modalImg.alt = nombre;
    modalTitulo.textContent = nombre;
    modalDescripcion.textContent = desc;
    modalPrecio.textContent = precio ? fmtCOP.format(precio) : '';

    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
  });
});

// ============================================================
// ❌ Cerrar modal
// ============================================================
function cerrar(){
  modal.style.display = 'none';
  document.body.style.overflow = 'auto';
  modalImg.src = ''; // libera el recurso si cambian mucho de plato
}

cerrarModal.addEventListener('click', cerrar);
modal.addEventListener('click', (e) => {
  if (e.target === modal) cerrar();
});
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && modal.style.display === 'flex') cerrar();
});

// ============================================================
// 🛒 Acción del botón "Agregar al carrito"
// ============================================================
btnCarrito.addEventListener('click', () => {
  const producto = {
    nombre: modalTitulo.textContent,
    precio: modalPrecio.textContent,
    imagen: modalImg.src
  };

  // Añadir al arreglo y guardar en localStorage
  carrito.push(producto);
  localStorage.setItem('carrito', JSON.stringify(carrito));

  // Actualizar contador del carrito si existe
  if (contadorCarrito) contadorCarrito.textContent = carrito.length;

  // Animación visual de confirmación
  btnCarrito.textContent = '✅ Agregado';
  btnCarrito.style.backgroundColor = '#27ae60';
  btnCarrito.disabled = true;

  setTimeout(() => {
    btnCarrito.textContent = 'Agregar al carrito';
    btnCarrito.style.backgroundColor = '';
    btnCarrito.disabled = false;
    cerrar();
  }, 1200);
});