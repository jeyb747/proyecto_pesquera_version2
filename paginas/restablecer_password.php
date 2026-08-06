<?php
session_start();
$token = $_GET['token'] ?? '';
?>
<!doctype html>
<html lang="es">

<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">

<title>Nueva contraseña</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../css/inicio.css">
<link rel="stylesheet" href="../css/alerts.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

/*====================================
FONDO
====================================*/

body{

    min-height:100vh;

    background:#f6f8fb;

    position:relative;

}

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

/*====================================
CONTENEDOR
====================================*/

.reset-wrapper{

    min-height:100vh;

    display:flex;

    justify-content:center;

    align-items:center;

    padding:40px 15px;

}

/*====================================
TARJETA
====================================*/

.reset-card{

    width:100%;

    max-width:520px;

    background:#fff;

    border-radius:24px;

    overflow:hidden;

    box-shadow:0 15px 40px rgba(0,0,0,.08);

    animation:fade .45s ease;

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

/*====================================
HEADER
====================================*/

.card-header-custom{

    background:linear-gradient(135deg,#0A3D62,#0E6C92);

    color:#fff;

    text-align:center;

    padding:35px;

}

.card-header-custom i{

    width:80px;

    height:80px;

    border-radius:50%;

    background:rgba(255,255,255,.15);

    display:flex;

    justify-content:center;

    align-items:center;

    margin:auto auto 20px;

    font-size:32px;

}

.card-header-custom h2{

    margin-bottom:10px;

    font-weight:700;

}

.card-header-custom p{

    margin:0;

    color:#d8edf8;

}

/*====================================
BODY
====================================*/

.card-body{

    padding:35px;

}

.form-label{

    font-weight:600;

    color:#173a54;

}

.form-control{

    height:52px;

    border-radius:12px;

}

.form-control:focus{

    border-color:#0E6C92;

    box-shadow:0 0 0 .2rem rgba(14,108,146,.15);

}

/*====================================
BOTÓN
====================================*/

.btn-warning{

    height:52px;

    border:none;

    border-radius:12px;

    background:#f8c300;

    color:#173a54;

    font-weight:700;

    transition:.25s;

}

.btn-warning:hover{

    background:#ffd33d;

    transform:translateY(-2px);

    box-shadow:0 8px 18px rgba(248,195,0,.35);

}

.password-tip{

    background:#eef8fd;

    border-left:4px solid #0A3D62;

    border-radius:10px;

    padding:12px;

    margin-bottom:20px;

    color:#577084;

    font-size:.9rem;

}

</style>

</head>

<body>

<?php
require_once(__DIR__ . '/../includes/flash.php');
flash_render();
?>

<main>

<div class="container">

<div class="reset-wrapper">

<div class="reset-card">

<div class="card-header-custom">

<i class="fas fa-shield-halved"></i>

<h2>Crea una nueva contraseña</h2>

<p>
Protege tu cuenta utilizando una contraseña segura.
</p>

</div>

<div class="card-body">

<div class="password-tip">

<i class="fas fa-circle-info me-2"></i>

Utiliza mínimo <strong>8 caracteres</strong>,
incluye mayúsculas, minúsculas y números para una mayor seguridad.

</div>

<form method="post"
action="../php/controlador/password/restablecer.php">

<input
type="hidden"
name="token"
value="<?= htmlspecialchars($token) ?>">

<div class="mb-3">

<label class="form-label">

Nueva contraseña

</label>

<input

type="password"

name="password"

class="form-control"

placeholder="••••••••"

minlength="8"

required>

</div>

<div class="mb-4">

<label class="form-label">

Confirmar contraseña

</label>

<input

type="password"

name="confirmacion"

class="form-control"

placeholder="••••••••"

minlength="8"

required>

</div>

<button class="btn btn-warning w-100">

<i class="fas fa-key me-2"></i>

Actualizar contraseña

</button>

</form>

</div>

</div>

</div>

</div>

</main>

</body>
</html>