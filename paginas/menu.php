<?php session_start(); $usuario_autenticado = isset($_SESSION['id']); ?>
<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>La Pesquera | Menú</title>

  <!-- ✅ BOOTSTRAP -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- ✅ FUENTE -->
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&display=swap" rel="stylesheet">

  <!-- ✅ CSS -->
  <link rel="stylesheet" href="../css/inicio.css">
  <link rel="stylesheet" href="../css/menu.css?v=2">
  <link rel="stylesheet" href="../css/alerts.css">

</head>

<body>
<?php require_once(__DIR__ . '/../includes/flash.php'); flash_render(); ?>

<!-- ========================= -->
<!-- NAVBAR -->
<!-- ========================= -->
<?php include(__DIR__ . "/../includes/navbar.php"); ?>

<!-- ========================= -->
<!-- CONTENIDO -->
<!-- ========================= -->
<main class="container py-5">

  <!-- TITULO -->
  <section class="text-center mb-5">

    <h1 class="fw-bold">
      Nuestro Menú
    </h1>

    <p class="text-muted">
      Del mar a tu mesa — descubre los mejores sabores de La Pesquera.
    </p>

  </section>

  <!-- ================================================= -->
  <!-- 🐟 PESCADOS Y CARNES -->
  <!-- ================================================= -->
  <section class="mb-5">

    <h2 class="categoria-titulo mb-4">
      🐟 Pescados y Carnes
    </h2>

    <div class="row g-4">

      <!-- PRODUCTO -->
      <div class="col-md-6 col-lg-3">

        <div class="producto card h-100 shadow border-0"
          data-plato="Trucha frita"
          data-precio="25000"
          data-img="../imagenes/trucha.jpg"
          data-desc="Trucha frita con patacones">

          <img src="../imagenes/trucha.jpg"
            class="card-img-top"
            alt="Trucha frita">

          <div class="card-body d-flex flex-column">

            <h5 class="card-title">
              Trucha frita
            </h5>

            <p class="text-muted">
              Con patacones
            </p>

            <div class="mt-auto d-flex justify-content-between align-items-center">

              <span class="fw-bold text-primary">
                $25.000
              </span>

              <button class="btn btn-warning rounded-circle btn-add">
                +
              </button>

            </div>

          </div>

        </div>

      </div>

      <!-- PRODUCTO -->
      <div class="col-md-6 col-lg-3">

        <div class="producto card h-100 shadow border-0"
          data-plato="Bagre en salsa"
          data-precio="25000"
          data-img="../imagenes/bagre.jpg"
          data-desc="Bagre en salsa criolla">

          <img src="../imagenes/bagre.jpg"
            class="card-img-top"
            alt="Bagre">

          <div class="card-body d-flex flex-column">

            <h5 class="card-title">
              Bagre en salsa
            </h5>

            <p class="text-muted">
              Salsa criolla
            </p>

            <div class="mt-auto d-flex justify-content-between align-items-center">

              <span class="fw-bold text-primary">
                $25.000
              </span>

              <button class="btn btn-warning rounded-circle btn-add">
                +
              </button>

            </div>

          </div>

        </div>

      </div>

      <!-- PRODUCTO -->
      <div class="col-md-6 col-lg-3">

        <div class="producto card h-100 shadow border-0"
          data-plato="Bagre frito"
          data-precio="25000"
          data-img="../imagenes/bagre-frito.jpg">

          <img src="../imagenes/bagre-frito.jpg"
            class="card-img-top"
            alt="Bagre">

          <div class="card-body d-flex flex-column">

            <h5 class="card-title">
              Bagre frito
            </h5>

            <p class="text-muted">
              Crujiente
            </p>

            <div class="mt-auto d-flex justify-content-between align-items-center">

              <span class="fw-bold text-primary">
                $25.000
              </span>

              <button class="btn btn-warning rounded-circle btn-add">
                +
              </button>

            </div>

          </div>

        </div>

      </div>

      <!-- PRODUCTO -->
      <div class="col-md-6 col-lg-3">

        <div class="producto card h-100 shadow border-0"
          data-plato="Filete de robalo"
          data-precio="25000"
          data-img="../imagenes/Filete_de_robalo.jpg">

          <img src="../imagenes/Filete_de_robalo.jpg"
            class="card-img-top"
            alt="Robalo">

          <div class="card-body d-flex flex-column">

            <h5 class="card-title">
              Filete de robalo
            </h5>

            <div class="mt-auto d-flex justify-content-between align-items-center">

              <span class="fw-bold text-primary">
                $25.000
              </span>

              <button class="btn btn-warning rounded-circle btn-add">
                +
              </button>

            </div>

          </div>

        </div>

      </div>

      <!-- PRODUCTO -->
      <div class="col-md-6 col-lg-3">

        <div class="producto card h-100 shadow border-0"
          data-plato="Mojarra frita"
          data-precio="25000"
          data-img="../imagenes/mojarra.jpg">

          <img src="../imagenes/mojarra.jpg"
            class="card-img-top"
            alt="Mojarra">

          <div class="card-body d-flex flex-column">

            <h5 class="card-title">
              Mojarra frita
            </h5>

            <div class="mt-auto d-flex justify-content-between align-items-center">

              <span class="fw-bold text-primary">
                $25.000
              </span>

              <button class="btn btn-warning rounded-circle btn-add">
                +
              </button>

            </div>

          </div>

        </div>

      </div>

      <!-- PRODUCTO -->
      <div class="col-md-6 col-lg-3">

        <div class="producto card h-100 shadow border-0"
          data-plato="Cazuela de mariscos"
          data-precio="35000"
          data-img="../imagenes/Cazuela_de_mariscos.jpg">

          <img src="../imagenes/Cazuela_de_mariscos.jpg"
            class="card-img-top"
            alt="Cazuela">

          <div class="card-body d-flex flex-column">

            <h5 class="card-title">
              Cazuela de mariscos
            </h5>

            <div class="mt-auto d-flex justify-content-between align-items-center">

              <span class="fw-bold text-primary">
                $35.000
              </span>

              <button class="btn btn-warning rounded-circle btn-add">
                +
              </button>

            </div>

          </div>

        </div>

      </div>

      <!-- PRODUCTO -->
      <div class="col-md-6 col-lg-3">

        <div class="producto card h-100 shadow border-0"
          data-plato="Carne asada"
          data-precio="25000"
          data-img="../imagenes/carne_asada.jpg">

          <img src="../imagenes/carne_asada.jpg"
            class="card-img-top"
            alt="Carne">

          <div class="card-body d-flex flex-column">

            <h5 class="card-title">
              Carne asada
            </h5>

            <div class="mt-auto d-flex justify-content-between align-items-center">

              <span class="fw-bold text-primary">
                $25.000
              </span>

              <button class="btn btn-warning rounded-circle btn-add">
                +
              </button>

            </div>

          </div>

        </div>

      </div>

      <!-- PRODUCTO -->
      <div class="col-md-6 col-lg-3">

        <div class="producto card h-100 shadow border-0"
          data-plato="Pechuga a la plancha"
          data-precio="20000"
          data-img="../imagenes/pechuga.jpg">

          <img src="../imagenes/pechuga.jpg"
            class="card-img-top"
            alt="Pechuga">

          <div class="card-body d-flex flex-column">

            <h5 class="card-title">
              Pechuga a la plancha
            </h5>

            <div class="mt-auto d-flex justify-content-between align-items-center">

              <span class="fw-bold text-primary">
                $20.000
              </span>

              <button class="btn btn-warning rounded-circle btn-add">
                +
              </button>

            </div>

          </div>

        </div>

      </div>

    </div>

  </section>

  <!-- ================================================= -->
  <!-- 🍲 SOPAS -->
  <!-- ================================================= -->
  <section class="mb-5">

    <h2 class="categoria-titulo mb-4">
      🍲 Sopas
    </h2>

    <div class="row g-4">

      <div class="col-md-6 col-lg-4">

        <div class="producto card h-100 shadow border-0"
          data-plato="Sopa de pescado"
          data-precio="13000"
          data-img="../imagenes/sopa-pescado.jpg">

          <img src="../imagenes/sopa-pescado.jpg"
            class="card-img-top">

          <div class="card-body d-flex flex-column">

            <h5>Sopa de pescado</h5>

            <div class="mt-auto d-flex justify-content-between">

              <span class="fw-bold text-primary">
                $13.000
              </span>

              <button class="btn btn-warning rounded-circle btn-add">
                +
              </button>

            </div>

          </div>

        </div>

      </div>

      <div class="col-md-6 col-lg-4">

        <div class="producto card h-100 shadow border-0"
          data-plato="Sopa de mondongo"
          data-precio="13000"
          data-img="../imagenes/mondongo.jpg">

          <img src="../imagenes/mondongo.jpg"
            class="card-img-top">

          <div class="card-body d-flex flex-column">

            <h5>Sopa de mondongo</h5>

            <div class="mt-auto d-flex justify-content-between">

              <span class="fw-bold text-primary">
                $13.000
              </span>

              <button class="btn btn-warning rounded-circle btn-add">
                +
              </button>

            </div>

          </div>

        </div>

      </div>

      <div class="col-md-6 col-lg-4">

        <div class="producto card h-100 shadow border-0"
          data-plato="Sopa de menudencias"
          data-precio="13000"
          data-img="../imagenes/sopa de menudencias.jpg">

          <img src="../imagenes/sopa de menudencias.jpg"
            class="card-img-top">

          <div class="card-body d-flex flex-column">

            <h5>Sopa de menudencias</h5>

            <div class="mt-auto d-flex justify-content-between">

              <span class="fw-bold text-primary">
                $13.000
              </span>

              <button class="btn btn-warning rounded-circle btn-add">
                +
              </button>

            </div>

          </div>

        </div>

      </div>

    </div>

  </section>

  <!-- ================================================= -->
  <!-- 🍚 PORCIONES -->
  <!-- ================================================= -->
  <section class="mb-5">

    <h2 class="categoria-titulo mb-4">
      🍚 Porciones
    </h2>

    <div class="row g-4">

      <div class="col-md-6 col-lg-3">
        <div class="producto card h-100 shadow border-0"
          data-plato="Papa francesa"
          data-precio="5000"
          data-img="../imagenes/papa.jpg">

          <img src="../imagenes/papa.jpg"
            class="card-img-top">

          <div class="card-body d-flex flex-column">

            <h5>Papa francesa</h5>

            <div class="mt-auto d-flex justify-content-between">

              <span class="fw-bold text-primary">
                $5.000
              </span>

              <button class="btn btn-warning rounded-circle btn-add">
                +
              </button>

            </div>

          </div>

        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="producto card h-100 shadow border-0"
          data-plato="Ensalada"
          data-precio="5000"
          data-img="../imagenes/ensalada.jpg">

          <img src="../imagenes/ensalada.jpg"
            class="card-img-top">

          <div class="card-body d-flex flex-column">

            <h5>Ensalada</h5>

            <div class="mt-auto d-flex justify-content-between">

              <span class="fw-bold text-primary">
                $5.000
              </span>

              <button class="btn btn-warning rounded-circle btn-add">
                +
              </button>

            </div>

          </div>

        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="producto card h-100 shadow border-0"
          data-plato="Yuca frita"
          data-precio="5000"
          data-img="../imagenes/yuca.jpg">

          <img src="../imagenes/yuca.jpg"
            class="card-img-top">

          <div class="card-body d-flex flex-column">

            <h5>Yuca frita</h5>

            <div class="mt-auto d-flex justify-content-between">

              <span class="fw-bold text-primary">
                $5.000
              </span>

              <button class="btn btn-warning rounded-circle btn-add">
                +
              </button>

            </div>

          </div>

        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="producto card h-100 shadow border-0"
          data-plato="Arroz"
          data-precio="3000"
          data-img="../imagenes/arroz.jpg">

          <img src="../imagenes/arroz.jpg"
            class="card-img-top">

          <div class="card-body d-flex flex-column">

            <h5>Arroz</h5>

            <div class="mt-auto d-flex justify-content-between">

              <span class="fw-bold text-primary">
                $3.000
              </span>

              <button class="btn btn-warning rounded-circle btn-add">
                +
              </button>

            </div>

          </div>

        </div>
      </div>

    </div>

  </section>

  <!-- ================================================= -->
  <!-- 🍹 BEBIDAS -->
  <!-- ================================================= -->
  <section class="mb-5">

    <h2 class="categoria-titulo mb-4">
      🍹 Bebidas
    </h2>

    <div class="row g-4">

      <div class="col-md-6 col-lg-3">
        <div class="producto card h-100 shadow border-0"
          data-plato="Gaseosa"
          data-precio="3000"
          data-img="../imagenes/Gaseosa personal.jpg">

          <img src="../imagenes/Gaseosa personal.jpg"
            class="card-img-top">

          <div class="card-body d-flex flex-column">

            <h5>Gaseosa</h5>

            <div class="mt-auto d-flex justify-content-between">

              <span class="fw-bold text-primary">
                $3.000
              </span>

              <button class="btn btn-warning rounded-circle btn-add">
                +
              </button>

            </div>

          </div>

        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="producto card h-100 shadow border-0"
          data-plato="Cerveza"
          data-precio="4000"
          data-img="../imagenes/cerveza.jpg">

          <img src="../imagenes/cerveza.jpg"
            class="card-img-top">

          <div class="card-body d-flex flex-column">

            <h5>Cerveza</h5>

            <div class="mt-auto d-flex justify-content-between">

              <span class="fw-bold text-primary">
                $4.000
              </span>

              <button class="btn btn-warning rounded-circle btn-add">
                +
              </button>

            </div>

          </div>

        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="producto card h-100 shadow border-0"
          data-plato="Jugo natural"
          data-precio="5000"
          data-img="../imagenes/jugo natural.jpg">

          <img src="../imagenes/jugo natural.jpg"
            class="card-img-top">

          <div class="card-body d-flex flex-column">

            <h5>Jugo natural</h5>

            <div class="mt-auto d-flex justify-content-between">

              <span class="fw-bold text-primary">
                $5.000
              </span>

              <button class="btn btn-warning rounded-circle btn-add">
                +
              </button>

            </div>

          </div>

        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="producto card h-100 shadow border-0"
          data-plato="Limonada"
          data-precio="10000"
          data-img="../imagenes/limonada.jpg">

          <img src="../imagenes/limonada.jpg"
            class="card-img-top">

          <div class="card-body d-flex flex-column">

            <h5>Limonada</h5>

            <div class="mt-auto d-flex justify-content-between">

              <span class="fw-bold text-primary">
                $10.000
              </span>

              <button class="btn btn-warning rounded-circle btn-add">
                +
              </button>

            </div>

          </div>

        </div>
      </div>

    </div>

  </section>

</main>

<!-- ========================= -->
<!-- MODAL -->
<!-- ========================= -->
<div class="modal fade"
  id="modalProducto"
  tabindex="-1">

  <div class="modal-dialog modal-dialog-centered">

    <div class="modal-content border-0 shadow-lg">

      <div class="modal-header">

        <h5 class="modal-title"
          id="modalTitulo">
        </h5>

        <button type="button"
          class="btn-close"
          data-bs-dismiss="modal">
        </button>

      </div>

      <div class="modal-body text-center">

        <img id="modalImg"
          src=""
          alt="Producto"
          class="img-fluid rounded mb-3">

        <p id="modalDescripcion"></p>

        <h4 id="modalPrecio"
          class="fw-bold text-primary">
        </h4>

      </div>

      <div class="modal-footer">

        <button id="btnCarrito"
          class="btn btn-warning w-100">

          Agregar al carrito

        </button>

      </div>

    </div>

  </div>

</div>

<!-- ========================= -->
<!-- FOOTER -->
<!-- ========================= -->
<footer class="bg-dark text-white text-center py-3">

  <div class="container">

    <p class="mb-0">
      © 2025 La Pesquera · Todos los derechos reservados
    </p>

  </div>

</footer>


<!-- ✅ Scripts -->
<script defer src="../js/script.js"></script>
<script>window.usuarioAutenticado = <?= $usuario_autenticado ? 'true' : 'false' ?>;</script>
<script defer src="../js/menu-interactivo.js"></script>
<!-- ✅ Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>
