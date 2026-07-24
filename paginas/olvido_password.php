<?php
session_start();
?>
<!doctype html>
<html lang="es">

<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">

<title>Recuperar contraseña</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../css/inicio.css">
<link rel="stylesheet" href="../css/alerts.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

/*==========================
FONDO
==========================*/

body{
    min-height:100vh;
    background:#f6f8fb;
    position:relative;
}

/* Olas */

body::before{

    content:"";
    position:fixed;
    inset:0;

    pointer-events:none;

    background-image:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='60' height='30' viewBox='0 0 60 30'><path d='M0 15 Q15 0 30 15 T60 15' fill='none' stroke='%230A3D62' stroke-width='1.5' stroke-opacity='0.04'/></svg>");

    background-size:60px 30px;

    z-index:0;
}

main{
    position:relative;
    z-index:1;
}

/*==========================
CONTENEDOR
==========================*/

.recuperar-wrapper{

    min-height:calc(100vh - 120px);

    display:flex;
    justify-content:center;
    align-items:center;

}

/*==========================
CARD
==========================*/

.recuperar-card{

    width:100%;
    max-width:520px;

    background:#fff;

    border:none;

    border-radius:24px;

    overflow:hidden;

    box-shadow:
    0 15px 40px rgba(0,0,0,.08);

    animation:fade .5s ease;

}

@keyframes fade{

from{
opacity:0;
transform:translateY(20px);
}

to{
opacity:1;
transform:none;
}

}

/*==========================
HEADER
==========================*/

.card-header-custom{

    background:linear-gradient(135deg,#0A3D62,#0E6C92);

    color:#fff;

    padding:35px;

    text-align:center;

}

.card-header-custom i{

    width:75px;
    height:75px;

    border-radius:50%;

    display:flex;
    justify-content:center;
    align-items:center;

    margin:auto auto 20px;

    background:rgba(255,255,255,.15);

    font-size:32px;

}

.card-header-custom h2{

    margin-bottom:8px;

    font-weight:700;

}

.card-header-custom p{

    margin:0;

    color:#d9edf8;

}

/*==========================
BODY
==========================*/

.card-body{

    padding:35px;

}

.form-label{

    font-weight:600;

    color:#27445b;

}

.form-control{

    height:52px;

    border-radius:12px;

}

.form-control:focus{

    border-color:#0E6C92;

    box-shadow:0 0 0 .2rem rgba(14,108,146,.15);

}

/*==========================
BOTÓN
==========================*/

.btn-warning{

    background:#f8c300;

    border:none;

    color:#15354f;

    height:52px;

    font-weight:700;

    border-radius:12px;

    transition:.25s;

}

.btn-warning:hover{

    transform:translateY(-2px);

    background:#ffcf2f;

    box-shadow:0 8px 18px rgba(248,195,0,.35);

}

/*==========================
LINK
==========================*/

.volver{

    display:block;

    text-align:center;

    margin-top:18px;

    text-decoration:none;

    color:#0A3D62;

    font-weight:600;

}

.volver:hover{

    color:#0E6C92;

}

</style>

</head>

<body>

<?php
include(__DIR__ . '/../includes/navbar.php');
require_once(__DIR__ . '/../includes/flash.php');
flash_render();
?>

<main>

<div class="container">

<div class="recuperar-wrapper">

<div class="recuperar-card">

<div class="card-header-custom">

<i class="fas fa-lock"></i>

<h2>Recuperar contraseña</h2>

<p>
Te enviaremos un enlace seguro para crear una nueva contraseña.
</p>

</div>

<div class="card-body">

<form method="post" action="../php/controlador/password/solicitar.php">

<div class="mb-4">

<label class="form-label">
Correo electrónico
</label>

<input
type="email"
name="correo"
class="form-control"
placeholder="ejemplo@correo.com"
required>

</div>

<button class="btn btn-warning w-100">

<i class="fas fa-paper-plane me-2"></i>

Enviar enlace

</button>

</form>

<a href="login.php" class="volver">

<i class="fas fa-arrow-left me-2"></i>

Volver al inicio de sesión

</a>

</div>

</div>

</div>

</div>

</main>

</body>

</html>