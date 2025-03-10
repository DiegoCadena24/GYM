<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: Ulogin.php");
    exit();
}

echo "Bienvenido, " . $_SESSION['nombre'];
?>
<a href="logout.php">Cerrar Sesión</a>