document.addEventListener("DOMContentLoaded", () => {

  const form = document.getElementById("formContacto");
  const mensajeExito = document.getElementById("mensajeExito");
  const contador = document.getElementById("contador-carrito");

  // actualizar contador carrito
  function actualizarContador() {
    const carrito = JSON.parse(localStorage.getItem("carrito")) || [];
    if (contador) contador.textContent = carrito.length;
  }
  actualizarContador();

  // limpiar teléfono
  function limpiarTelefono(t) {
    return t.replace(/\D/g, "");
  }

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const nombre = form.nombre.value.trim();
    const telefono = limpiarTelefono(form.telefono.value.trim());
    const mensaje = form.mensaje.value.trim();

    if (!nombre || !telefono || !mensaje) {
      alert("Por favor completa todos los campos.");
      return;
    }

    if (telefono.length < 7) {
      alert("Número de teléfono inválido.");
      return;
    }

    const numeroRestaurante = "573008404600"; // Número real

    const texto = `
📩 *Nuevo mensaje desde La Pesquera*
👤 Nombre: ${nombre}
📞 Teléfono: ${telefono}
💬 Mensaje: ${mensaje}
    `;

    const url = `https://wa.me/${numeroRestaurante}?text=${encodeURIComponent(texto)}`;

    window.open(url, "_blank");

    mensajeExito.style.display = "block";
    form.reset();

    setTimeout(() => {
      mensajeExito.style.display = "none";
    }, 3000);
  });

});