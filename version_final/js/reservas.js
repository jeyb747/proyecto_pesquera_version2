// reservas.js - guardado en localStorage, validación, WhatsApp y fecha mínima

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formReserva");
  const mensaje = document.getElementById("mensajeExito");
  const btnWhatsapp = document.getElementById("btnWhatsapp");
  const campoFecha = document.getElementById("fecha");
  const contador = document.getElementById("contador-carrito");

  // establecer fecha mínima (hoy)
  const hoy = new Date();
  const isoHoy = hoy.toISOString().split("T")[0];
  campoFecha.setAttribute("min", isoHoy);

  // actualizar contador carrito (si existe)
  function actualizarContador() {
    try {
      const carrito = JSON.parse(localStorage.getItem("carrito")) || [];
      if (contador) contador.textContent = carrito.length;
    } catch {
      if (contador) contador.textContent = 0;
    }
  }
  actualizarContador();

  // util: limpiar y formatear teléfono simple
  function soloDigitos(s) { return String(s || "").replace(/\D/g, ""); }

  // guardar reserva en localStorage
  function guardarReserva(obj) {
    const lista = JSON.parse(localStorage.getItem("reservas")) || [];
    lista.unshift(obj); // guardar al inicio (más reciente primero)
    localStorage.setItem("reservas", JSON.stringify(lista));
  }

  // crear mensaje WhatsApp (texto)
  function construirTextoWhatsApp(reserva) {
    const lines = [
      "*Reserva - La Pesquera*",
      "——————————————",
      `👤 Nombre: ${reserva.nombre}`,
      `📞 Teléfono: ${reserva.telefono}`,
      `📅 Fecha: ${reserva.fecha}`,
      `⏰ Hora: ${reserva.hora}`,
      `👥 Personas: ${reserva.personas}`,
      `📝 Comentarios: ${reserva.comentarios || "Ninguno"}`,
    ];
    return lines.join("\n");
  }

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const nombre = form.querySelector("#nombre").value.trim();
    const telefono = soloDigitos(form.querySelector("#telefono").value.trim());
    const fecha = form.querySelector("#fecha").value;
    const hora = form.querySelector("#hora").value;
    const personas = form.querySelector("#personas").value;
    const comentarios = form.querySelector("#comentarios").value.trim();

    // validaciones
    if (!nombre || !telefono || !fecha || !hora || !personas) {
      alert("Por favor completa todos los campos obligatorios.");
      return;
    }

    // verificar fecha >= hoy
    if (new Date(fecha) < new Date(isoHoy)) {
      alert("Selecciona una fecha hoy o futura.");
      return;
    }

    // crear objeto reserva
    const reserva = {
      id: Date.now(),
      nombre,
      telefono,
      fecha,
      hora,
      personas,
      comentarios,
      creado: new Date().toISOString()
    };

    // guardar y feedback
    guardarReserva(reserva);
    mensaje.style.display = "block";
    btnWhatsapp.style.display = "inline-block";

    // preparar enlace WhatsApp
    const numeroRestaurante = "573008404600"; 
    const texto = construirTextoWhatsApp(reserva);
    btnWhatsapp.href = `https://wa.me/${numeroRestaurante}?text=${encodeURIComponent(texto)}`;

    // limpiar formulario (opcional mantener fecha/hora)
    form.reset();
    campoFecha.setAttribute("min", isoHoy); // aseguramos min

    // actualizar historial contador (si usas página historial)
    setTimeout(() => {
      mensaje.style.display = "none";
      // opcional: redirigir a historial automáticamente
      // window.location.href = "historial.html";
    }, 3000);

    actualizarContador();
  });
});