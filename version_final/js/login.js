document.addEventListener("DOMContentLoaded", () => {

  const form = document.getElementById("formLogin");
  const msgError = document.getElementById("mensajeError");

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const correo = document.getElementById("correo").value.trim();
    const password = document.getElementById("password").value;

    // Obtener usuarios registrados
    let usuarios = JSON.parse(localStorage.getItem("usuarios")) || [];

    // Buscar usuario por correo y contraseña
    const usuario = usuarios.find(u => u.correo === correo && u.password === password);

    if (usuario) {
      // Guardar sesión
      localStorage.setItem("sesion", JSON.stringify({
        usuario: usuario.correo,
        nombre: usuario.nombre,
        rol: usuario.rol
      }));

      // Redirigir al inicio
     window.location.href = "../index.php";
    } 
    else {
      msgError.style.display = "block";
      setTimeout(() => (msgError.style.display = "none"), 3000);
    }
  });

});
