// ======================================================
// DOMICILIO.JS - VERSION FINAL CORREGIDA
// ======================================================

document.addEventListener("DOMContentLoaded", () => {

  const productosContainer = document.getElementById("productosCarrito");
  const cantidadSpan = document.getElementById("cantidadProductos");
  const totalSpan = document.getElementById("totalCarrito");

  const form = document.getElementById("formDomicilio");
  const selectPago = document.getElementById("pago");
  const campoComprobante = document.getElementById("campoComprobante");
  const inputComprobante = document.getElementById("comprobante");

  // 🛒 traer carrito
  const carrito = JSON.parse(localStorage.getItem("carrito")) || [];

  console.log("CARRITO:", carrito);

  // ❌ si está vacío
  if (carrito.length === 0) {
    productosContainer.innerHTML = "<p>No tienes productos en el carrito.</p>";
    form.style.display = "none";
    return;
  }

  // 💳 mostrar comprobante NEQUI
  selectPago.addEventListener("change", () => {
    if (selectPago.value === "nequi") {
      campoComprobante.classList.remove("oculto");
    } else {
      campoComprobante.classList.add("oculto");
      inputComprobante.value = "";
    }
  });

  // 🛒 MOSTRAR PRODUCTOS + CALCULAR TOTAL CORRECTO
  let total = 0;
  productosContainer.innerHTML = "";

  carrito.forEach(item => {

    // 🔥 LIMPIEZA DE PRECIO (CLAVE DEL ERROR)
    const precio = parseInt(
      String(item.precio).replace(/\D/g, "")
    ) || 0;

    total += precio;

    productosContainer.innerHTML += `
      <div class="producto">
        <span>${item.nombre}</span>
        <strong>$${precio.toLocaleString()}</strong>
      </div>
    `;
  });

  // 📊 RESUMEN
  cantidadSpan.textContent = carrito.length;
  totalSpan.textContent = `$${total.toLocaleString()}`;

  // 🚀 ENVIAR AL BACKEND
  form.addEventListener("submit", function () {

    document.getElementById("productosInput").value =
      JSON.stringify(carrito);

    document.getElementById("totalInput").value = total;

    // 🧹 limpiar carrito después de enviar
    localStorage.removeItem("carrito");
  });

});