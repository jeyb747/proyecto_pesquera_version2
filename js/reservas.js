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
  if (personas > 20) { personas = 20; alert("El límite por reserva es de 20 personas."); }

  document.getElementById("numPersonas").textContent = personas;
}

function fechaLocalISO(fecha) {
  const anio = fecha.getFullYear();
  const mes = String(fecha.getMonth() + 1).padStart(2, "0");
  const dia = String(fecha.getDate()).padStart(2, "0");
  return `${anio}-${mes}-${dia}`;
}

function fechaMaximaReserva(hoy) {
  const limite = new Date(hoy);
  const diaOriginal = limite.getDate();
  limite.setDate(1);
  limite.setMonth(limite.getMonth() + 6);
  const ultimoDiaDelMes = new Date(limite.getFullYear(), limite.getMonth() + 1, 0).getDate();
  limite.setDate(Math.min(diaOriginal, ultimoDiaDelMes));
  return limite;
}

/* =========================
   RANGO DE FECHAS
 ========================= */
document.addEventListener("DOMContentLoaded", () => {
  const hoy = new Date();
  const inputFecha = document.getElementById("fecha");
  if (inputFecha) {
    inputFecha.min = fechaLocalISO(hoy);
    inputFecha.max = fechaLocalISO(fechaMaximaReserva(hoy));
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

  const inputFecha = document.getElementById("fecha");
  if (fecha < inputFecha.min || fecha > inputFecha.max) {
    alert("La reserva debe ser desde hoy y con máximo 6 meses de anticipación.");
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

      const response = await fetch("../php/controlador/reservas/crear.php", {
        method: "POST",
        body: data
      });
      if ((await response.text()).trim() !== "ok") throw new Error("No se pudo guardar la reserva. Verifica los datos e inténtalo de nuevo.");

    } catch (error) {
      alert(error.message); return;
    }

    window.mostrarAviso("Tu reserva fue creada correctamente.", "Reserva realizada");

    const texto = `Reserva La Pesquera
Personas: ${personas}
Fecha: ${data.get("fecha")}
Hora: ${data.get("hora")}`;

    const btn = document.getElementById("btnWhatsapp");

    btn.href = `https://wa.me/573008404600?text=${encodeURIComponent(texto)}`;
    btn.classList.remove("d-none");
    btn.focus();

  });

});
