// script.js - Interactividad básica de La Pesquera

// ======= MENÚ RESPONSIVO =======
const menuToggle = document.getElementById("menu-toggle");
const navMenu = document.getElementById("nav-menu");

menuToggle.addEventListener("click", () => {
  navMenu.classList.toggle("show");
  // Cambia el icono ☰ ↔ ✕
  menuToggle.textContent = navMenu.classList.contains("show") ? "✕" : "☰";
});

// Cierra el menú cuando se hace clic en un enlace (en móvil)
document.querySelectorAll(".nav-menu a").forEach(link => {
  link.addEventListener("click", () => {
    if (window.innerWidth <= 768) {
      navMenu.classList.remove("show");
      menuToggle.textContent = "☰";
    }
  });
});

// ======= SCROLL SUAVE =======
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener("click", function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute("href"));
    if (target) {
      target.scrollIntoView({
        behavior: "smooth",
        block: "start"
      });
    }
  });
});

// ======= EFECTO AL HACER SCROLL =======
window.addEventListener("scroll", () => {
  const navbar = document.querySelector(".navbar");
  if (window.scrollY > 100) {
    navbar.style.backgroundColor = "rgba(10, 61, 98, 0.9)";
    navbar.style.boxShadow = "0 2px 8px rgba(0, 0, 0, 0.3)";
  } else {
    navbar.style.backgroundColor = "var(--azul)";
    navbar.style.boxShadow = "0 3px 8px rgba(0, 0, 0, 0.15)";
  }
});
