// historial.js FINAL - 100% FUNCIONAL

document.addEventListener("DOMContentLoaded", () => {

  const lista = document.getElementById("listaReservas");
  const sinReservas = document.getElementById("sinReservas");
  const btnBorrarTodo = document.getElementById("btnBorrarTodo");

  // cargar reservas desde localStorage
  function cargarReservas() {
    const reservas = JSON.parse(localStorage.getItem("reservas")) || [];
    lista.innerHTML = "";

    if (reservas.length === 0) {
      sinReservas.style.display = "block";
      return;
    }

    sinReservas.style.display = "none";

    reservas.forEach(r => {
      const card = document.createElement("div");
      card.className = "reserva-card";
      card.dataset.id = r.id;

      card.innerHTML = `
        <div class="reserva-info">
          <strong>${r.nombre}</strong>
          <div class="reserva-meta">${r.fecha} ${r.hora} · ${r.personas} personas</div>
          <div class="reserva-meta">Tel: ${r.telefono} · Comentarios: ${r.comentarios || "Ninguno"}</div>
        </div>

        <div class="reserva-actions">
          <button class="btn-small" data-id="${r.id}" data-action="whatsapp">WhatsApp</button>
          <button class="btn-small secondary" data-id="${r.id}" data-action="borrar">Eliminar</button>
        </div>
      `;

      lista.appendChild(card);
    });
  }

  // delegación de eventos
  lista.addEventListener("click", (e) => {
    const btn = e.target.closest("button[data-action]");
    if (!btn) return;

    const action = btn.dataset.action;
    const id = Number(btn.dataset.id);

    let reservas = JSON.parse(localStorage.getItem("reservas")) || [];
    const index = reservas.findIndex(r => Number(r.id) === id);
    if (index === -1) return;

    // borrar un registro
    if (action === "borrar") {
      if (!confirm("¿Eliminar esta reserva?")) return;
      reservas.splice(index, 1);
      localStorage.setItem("reservas", JSON.stringify(reservas));
      cargarReservas();
      return;
    }

    // enviar por WhatsApp
    if (action === "whatsapp") {
      const r = reservas[index];
      const numero = "573008404600";

      const texto = `
*Reserva - La Pesquera*
👤 ${r.nombre}
📞 ${r.telefono}
📅 ${r.fecha}
⏰ ${r.hora}
👥 ${r.personas} personas
📝 ${r.comentarios || "Ninguno"}
      `;

      const url = `https://wa.me/${numero}?text=${encodeURIComponent(texto)}`;
      window.open(url, "_blank");
    }
  });

  // borrar TODO
  btnBorrarTodo.addEventListener("click", () => {
    const reservas = JSON.parse(localStorage.getItem("reservas")) || [];
    if (!reservas.length) {
      alert("No hay reservas para borrar.");
      return;
    }
    if (!confirm("¿Seguro que deseas borrar todo?")) return;
    localStorage.removeItem("reservas");
    cargarReservas();
  });

  cargarReservas();
});