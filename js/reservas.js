let personas = 2;
let horaSeleccionada = "";

/* =========================
   HACER FUNCIONES GLOBALES (CLAVE)
========================= */
window.cambiarPersonas = cambiarPersonas;
window.validarFecha = validarFecha;
window.validarHora = validarHora;
window.nextStep = nextStep;
window.seleccionarHora = seleccionarHora;

/* =========================
   PERSONAS
========================= */
function cambiarPersonas(valor) {

  personas += valor;

  if (personas < 1) personas = 1;

  document.getElementById("numPersonas").textContent = personas;
}

/* =========================
   FECHA MINIMA
========================= */
document.addEventListener("DOMContentLoaded", () => {

  const hoy = new Date().toISOString().split("T")[0];

  const inputFecha = document.getElementById("fecha");

  if (inputFecha) {
    inputFecha.min = hoy;
  }
});

/* =========================
   VALIDAR FECHA
========================= */
async function validarFecha() {

  const fecha = document.getElementById("fecha").value;

  if (!fecha) {
    alert("Selecciona una fecha");
    return;
  }

  await cargarHoras(fecha);

  nextStep(3);
}

/* =========================
   CARGAR HORAS
========================= */
async function cargarHoras(fecha) {

  const contenedor = document.getElementById("contenedorHoras");

  contenedor.innerHTML = "";

  const horas = [
    "12:00 pm", "12:30 pm",
    "1:00 pm", "1:30 pm",
    "2:00 pm", "2:30 pm",
    "3:00 pm", "3:30 pm",
    "4:00 pm", "4:30 pm",
    "5:00 pm"
  ];

  let ocupadas = [];

  try {
    const response = await fetch(`../php/controlador/reservas/horas_ocupadas.php?fecha=${fecha}`);
    ocupadas = await response.json();
  } catch (error) {
    console.log("Error cargando horas ocupadas:", error);
  }

  horas.forEach(hora => {

    const col = document.createElement("div");
    col.className = "col-6 col-md-4";

    const disabled = ocupadas.includes(hora);

    col.innerHTML = `
      <button
        type="button"
        class="btn ${disabled ? 'btn-secondary' : 'btn-outline-warning'} w-100 hora-btn"
        ${disabled ? 'disabled' : ''}
        onclick="seleccionarHora(this)">
        ${disabled ? hora + ' ❌' : hora}
      </button>
    `;

    contenedor.appendChild(col);
  });
}

/* =========================
   SELECCIONAR HORA
========================= */
function seleccionarHora(btn) {

  document.querySelectorAll(".hora-btn").forEach(b => {
    b.classList.remove("active");
  });

  btn.classList.add("active");

  horaSeleccionada = btn.textContent.replace("❌", "").trim();
}

/* =========================
   VALIDAR HORA
========================= */
function validarHora() {

  if (!horaSeleccionada) {
    alert("Selecciona una hora");
    return;
  }

  nextStep(4);
}

/* =========================
   CAMBIAR PASOS
========================= */
function nextStep(step) {

  document.querySelectorAll(".step").forEach(s => {
    s.classList.add("d-none");
    s.classList.remove("active");
  });

  const actual = document.getElementById("step" + step);

  if (actual) {
    actual.classList.remove("d-none");
    actual.classList.add("active");
  }

  const progress = document.getElementById("progressBar");
  if (progress) {
    progress.style.width = (step * 25) + "%";
  }

  if (step === 4) {

    document.getElementById("inputPersonas").value = personas;
    document.getElementById("inputFecha").value = document.getElementById("fecha").value;
    document.getElementById("inputHora").value = horaSeleccionada;
  }
}

/* =========================
   ENVIAR RESERVA
========================= */
document.addEventListener("DOMContentLoaded", () => {

  const form = document.getElementById("formReserva");

  if (!form) return;

  form.addEventListener("submit", async (e) => {

    e.preventDefault();

    const data = new FormData(form);

    try {

      await fetch("../php/controlador/reservas/crear.php", {
        method: "POST",
        body: data
      });

    } catch (error) {
      console.log("Error al guardar reserva:", error);
    }

    document.getElementById("mensajeExito").classList.remove("d-none");

    const texto = `Reserva La Pesquera
Personas: ${personas}
Fecha: ${data.get("fecha")}
Hora: ${data.get("hora")}`;

    const btn = document.getElementById("btnWhatsapp");

    btn.href = `https://wa.me/573008404600?text=${encodeURIComponent(texto)}`;
    btn.classList.remove("d-none");

  });

});