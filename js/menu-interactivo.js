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
const MAX_PRODUCTOS_POR_CATEGORIA = 5;
let categoriaProductoModal = '';

function requiereInicioSesion() {
  if (window.usuarioAutenticado) return false;

  mostrarAviso('Debes iniciar sesión para agregar productos al carrito.', 'Inicia sesión para continuar', () => {
    window.location.href = 'login.php?next=menu';
  });
  return true;
}

const contadorCarrito = document.getElementById('contador-carrito');

function actualizarContador() {

  if (contadorCarrito) {
    contadorCarrito.textContent = carrito.length;
  }

}

actualizarContador();

function obtenerCategoria(card) {
  const titulo = card
    ?.closest('section')
    ?.querySelector('.categoria-titulo');

  return titulo?.textContent.trim() || 'General';
}

function puedeAgregarCategoria(categoria) {
  const categoriaNormalizada = normalizar(categoria);
  const cantidadCategoria = carrito.filter(producto => {
    return normalizar(producto.categoria || '') === categoriaNormalizada;
  }).length;

  return cantidadCategoria < MAX_PRODUCTOS_POR_CATEGORIA;
}

function avisarPedidoGrande() {
  mostrarAviso(
    'Solo puedes agregar maximo 5 productos por categoria. Si necesitas un pedido mas grande, contactate con nosotros en la seccion Contacto.',
    'Pedido grande'
  );
}

function agregarProductoAlCarrito(producto) {
  if (!puedeAgregarCategoria(producto.categoria)) {
    avisarPedidoGrande();
    return false;
  }

  carrito.push(producto);
  localStorage.setItem('carrito', JSON.stringify(carrito));
  actualizarContador();
  return true;
}

document.addEventListener('DOMContentLoaded', () => {
  const input = document.getElementById('buscarPlato');
  const empty = document.getElementById('sinResultados');
  if (!input) return;
  input.addEventListener('input', () => {
    const query = normalizar(input.value);
    let visible = 0;
    document.querySelectorAll('section').forEach(seccion => {
      const productos = seccion.querySelectorAll('.producto');
      if (!productos.length) return;

      let productosVisibles = 0;
      productos.forEach(card => {
        const nombre = normalizar(card.dataset.plato || '');
        const show = !query || nombre.startsWith(query);
        const wrapper = card.parentElement;
        if (wrapper) wrapper.style.display = show ? '' : 'none';
        if (show) productosVisibles++;
      });

      seccion.style.display = !query || productosVisibles > 0 ? '' : 'none';
      visible += productosVisibles;
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

function sincronizarCategoriasCarrito() {
  const categoriasPorProducto = new Map();

  document.querySelectorAll('.producto').forEach(card => {
    categoriasPorProducto.set(
      normalizar(card.dataset.plato || ''),
      obtenerCategoria(card)
    );
  });

  let huboCambios = false;

  carrito = carrito.map(producto => {
    if (producto.categoria) return producto;

    const categoria = categoriasPorProducto.get(
      normalizar(producto.nombre || '')
    );

    if (!categoria) return producto;

    huboCambios = true;
    return { ...producto, categoria };
  });

  if (huboCambios) {
    localStorage.setItem('carrito', JSON.stringify(carrito));
  }
}

sincronizarCategoriasCarrito();

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
const modalAvisoElement = document.getElementById('modalAviso');
const modalAviso = new bootstrap.Modal(modalAvisoElement);
const avisoTitulo = document.getElementById('avisoTitulo');
const avisoMensaje = document.getElementById('avisoMensaje');

function mostrarAviso(mensaje, titulo = 'Aviso', alCerrar) {
  if (!modalAvisoElement) return;

  avisoTitulo.textContent = titulo;
  avisoMensaje.textContent = mensaje;
  if (alCerrar) modalAvisoElement.addEventListener('hidden.bs.modal', alCerrar, { once: true });
  modalAviso.show();
}

// ============================================================
// ABRIR MODAL
// ============================================================

document.querySelectorAll('.producto').forEach(card => {
  const imagenProducto = card.querySelector('.card-img-top');

  imagenProducto?.addEventListener('click', () => {

    window.location.href = `producto.php?nombre=${encodeURIComponent(card.dataset.plato)}&imagen=${encodeURIComponent(card.dataset.img)}&precio=${encodeURIComponent(card.dataset.precio)}`;
    return;

    const nombre = card.dataset.plato;
    const imagen = card.dataset.img;
    const desc = card.dataset.desc || 'Delicioso plato de La Pesquera';
    const precio = Number(card.dataset.precio);
    categoriaProductoModal = obtenerCategoria(card);

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

document.querySelectorAll('.btn-add').forEach(btn => {
  btn.addEventListener('click', event => {
    event.preventDefault();
    event.stopPropagation();

    if (requiereInicioSesion()) return;

    const card = btn.closest('.producto');
    const producto = {
      nombre: card.dataset.plato,
      precio: fmtCOP.format(Number(card.dataset.precio)),
      imagen: card.dataset.img,
      categoria: obtenerCategoria(card)
    };

    if (agregarProductoAlCarrito(producto)) {
      contadorCarrito?.classList.remove('carrito-pop');
      void contadorCarrito?.offsetWidth;
      contadorCarrito?.classList.add('carrito-pop');
      mostrarAviso(`${producto.nombre} fue agregado al carrito.`, 'Producto agregado');
    }
  });
});

// ============================================================
// AGREGAR DESDE MODAL
// ============================================================

btnCarrito.addEventListener('click', () => {
  if (requiereInicioSesion()) return;

  const producto = {
    nombre: modalTitulo.textContent,
    precio: modalPrecio.textContent,
    imagen: modalImg.src,
    categoria: categoriaProductoModal
  };

  if (!puedeAgregarCategoria(producto.categoria)) {
    modalElement.addEventListener('hidden.bs.modal', avisarPedidoGrande, { once: true });
    modalBootstrap.hide();
    return;
  }

  agregarProductoAlCarrito(producto);

  modalElement.addEventListener('hidden.bs.modal', () => {
    mostrarAviso(`${producto.nombre} fue agregado al carrito.`, 'Producto agregado');
  }, { once: true });
  modalBootstrap.hide();

});
