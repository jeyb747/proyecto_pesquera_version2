<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>

<!-- ======================================================
     NAVBAR BOOTSTRAP PRO
====================================================== -->

<nav class="navbar navbar-expand-lg navbar-dark shadow-sm py-3">

  <div class="container">

    <!-- LOGO -->
    <a
      class="navbar-brand d-flex align-items-center gap-2 fw-bold"
      href="/version_final/index.php">

      <img
        src="/version_final/imagenes/logo.png"
        alt="Logo"
        height="45">

      <span class="logo-text">
        La Pesquera
      </span>

    </a>

    <!-- BOTON MOBILE -->
    <button
      class="navbar-toggler border-0 shadow-none"
      type="button"
      data-bs-toggle="collapse"
      data-bs-target="#navbarPesquera">

      <span class="navbar-toggler-icon"></span>

    </button>

    <!-- LINKS -->
    <div
      class="collapse navbar-collapse"
      id="navbarPesquera">

      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">

        <!-- INICIO -->
        <li class="nav-item">

          <a
            class="nav-link"
            href="/version_final/index.php">

            Inicio

          </a>

        </li>

        <!-- MENU -->
        <li class="nav-item">

          <a
            class="nav-link"
            href="/version_final/paginas/menu.php">

            Menú

          </a>

        </li>

        <!-- RESERVAS -->
        <li class="nav-item">

          <a
            class="nav-link"
            href="/version_final/paginas/reservas.php">

            Reservas

          </a>

        </li>

        <!-- DOMICILIO -->
        <li class="nav-item">

          <a
            class="nav-link"
            href="/version_final/paginas/domicilio.php">

            Domicilio

          </a>

        </li>

        <?php if (isset($_SESSION['usuario'])): ?>

          <!-- USUARIO -->
          <li class="nav-item">

            <span class="nav-link text-warning fw-bold">

              👋 <?php echo $_SESSION['usuario']; ?>

            </span>

          </li>

          <!-- LOGOUT -->
          <li class="nav-item">

            <a
              class="btn btn-outline-light rounded-pill px-3"
              href="/version_final/php/modelo/loghout.php">

              Cerrar sesión

            </a>

          </li>

        <?php else: ?>

          <!-- LOGIN -->
          <li class="nav-item">

            <a
              class="btn btn-warning rounded-pill px-3 fw-bold"
              href="/version_final/paginas/login.php">

              👤 Login

            </a>

          </li>

        <?php endif; ?>

        <!-- CARRITO -->
        <li class="nav-item">

          <a
            href="/version_final/paginas/carrito.php"
            class="btn btn-warning rounded-pill position-relative px-3 fw-bold">

            🛒 Carrito

            <span
              id="contador-carrito"
              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">

              0

            </span>

          </a>

        </li>

      </ul>

    </div>

  </div>

</nav>