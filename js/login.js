document.addEventListener("DOMContentLoaded", () => {

  const form = document.getElementById("formLogin");
  const correo = document.getElementById("correo");
  const password = document.getElementById("password");
  const msgError = document.getElementById("mensajeError");

  form.addEventListener("submit", (e) => {

    if (correo.value.trim() === "" || password.value.trim() === "") {
      e.preventDefault();
      mostrarError("Completa todos los campos");
      return;
    }

    if (!correo.value.includes("@")) {
      e.preventDefault();
      mostrarError("Correo inválido");
      return;
    }

  });

  function mostrarError(msg) {
    window.mostrarAviso(msg, "Revisa la información");
  }

});

/* MOSTRAR CONTRASEÑA */
function togglePassword() {
  const input = document.getElementById("password");
  const button = document.querySelector(".password-toggle");
  const passwordIsVisible = input.type === "password";

  input.type = passwordIsVisible ? "text" : "password";
  button.classList.toggle("is-visible", passwordIsVisible);
  button.setAttribute("aria-pressed", String(passwordIsVisible));
  button.setAttribute(
    "aria-label",
    passwordIsVisible ? "Ocultar contraseña" : "Mostrar contraseña"
  );
}
