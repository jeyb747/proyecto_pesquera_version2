{"id","58219","variant","standard","title","registro.js corregido"}
document.addEventListener("DOMContentLoaded", () => {

  const form = document.getElementById("formRegistro");
  const msgOk = document.getElementById("mensajeExito");
  const msgError = document.getElementById("mensajeError");

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const nombre = document.getElementById("nombre").value.trim();
    const correo = document.getElementById("correo").value.trim();
    const telefono = document.getElementById("telefono").value.trim();
    const password = document.getElementById("password").value.trim();

    let usuarios = JSON.parse(localStorage.getItem("usuarios")) || [];

    // Validar si el correo ya existe
    const existe = usuarios.some(u => u.correo === correo);

    if (existe) {
      msgError.style.display = "block";
      msgOk.style.display = "none";
      setTimeout(() => msgError.style.display = "none", 2500);
      return;
    }

    // Crear usuario
    usuarios.push({
      nombre,
      correo,
      telefono,
      password,
      rol: "cliente",
      creado: new Date().toISOString()
    });

    // Guardar en localStorage
    localStorage.setItem("usuarios", JSON.stringify(usuarios));

    msgError.style.display = "none";
    msgOk.style.display = "block";

    // Redirigir después de crear usuario
    setTimeout(() => {
      window.location.href = "login.html";
    }, 1500);
  });

});