<?php
session_start(); // Inicia la sesión
session_destroy(); // Destruye la sesión
header("Location: login.html"); // Redirige al login
exit();
?>
