<?php
require_once "../../php/configuracion/admin_auth.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Administrador</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        ul { list-style: none; padding: 0; }
        li { margin: 10px 0; }
        a { text-decoration: none; color: white; background: #007bff; padding: 8px 12px; border-radius: 4px; }
        a:hover { background: #0056b3; }
    </style>
</head>
<body>

<h1>Bienvenido Admin <?php echo $_SESSION['usuario']; ?> 👨‍💼</h1>

<ul>
    <li><a href="productos.php">Gestionar Productos</a></li>
    <li><a href="pedidos.php">Gestionar Pedidos</a></li>
    <li><a href="reservas.php">Gestionar Reservas</a></li>
    <li><a href="domicilios.php">Gestionar Domicilios</a></li>
    <li><a href="usuarios.php">Gestionar Usuarios</a></li>
    <li><a href="../../php/logout.php">Cerrar Sesión</a></li>
</ul>

</body>
</html>