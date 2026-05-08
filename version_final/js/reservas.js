let personas = 2;
let horaSeleccionada = "";

function cambiarPersonas(valor) {
  personas += valor;
  if (personas < 1) personas = 1;
  document.getElementById("numPersonas").textContent = personas;
}

function nextStep(step) {
  document.querySelectorAll(".step").forEach(s => s.classList.remove("active"));
  document.getElementById("step" + step).classList.add("active");

  document.getElementById("progressBar").style.width = (step * 25) + "%";

  if (step === 4) {
    document.getElementById("inputPersonas").value = personas;
    document.getElementById("inputFecha").value = document.getElementById("fecha").value;
    document.getElementById("inputHora").value = horaSeleccionada;
  }
}

function seleccionarHora(btn) {
  document.querySelectorAll(".horas button").forEach(b => b.classList.remove("active"));
  btn.classList.add("active");
  horaSeleccionada = btn.textContent;
}

document.addEventListener("DOMContentLoaded", () => {

  const form = document.getElementById("formReserva");

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const data = new FormData(form);

    await fetch("../php/controlador/reservas/crear.php", {
      method: "POST",
      body: data
    });

    document.getElementById("mensajeExito").style.display = "block";

    const texto = `Reserva La Pesquera
Personas: ${personas}
Fecha: ${data.get("fecha")}
Hora: ${data.get("hora")}`;

    const btn = document.getElementById("btnWhatsapp");
    btn.href = `https://wa.me/573008404600?text=${encodeURIComponent(texto)}`;
    btn.style.display = "block";
  });

});