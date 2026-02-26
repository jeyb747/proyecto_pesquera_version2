<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location:../paginas/login.php");
    exit();
}
?>