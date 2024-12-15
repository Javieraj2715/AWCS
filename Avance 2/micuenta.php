<?php
session_start(); // Inicia la sesión
if (!isset($_SESSION['usuario'])) {
    // Redirige al login si no hay sesión iniciada
    header("Location: login.html");
    exit();
}

// Obtén los datos del usuario desde la sesión
$usuario = $_SESSION['usuario'];



?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta</title>
    <link rel="stylesheet" href="stylesGlobal.css">
    <script src="script.js" defer></script>
</head>

<body>
    

    <header class="navbar">
        <div class="nav-logo">
            <h1>Exámenes de Conducir</h1>
        </div>
        <nav class="nav-menu">
            <a href="#" class="nav-item" onclick="irAInicio()">Inicio</a>
            <a href="#" class="nav-item" onclick="toggleOptions()">Citas</a>
            <a href="#" class="nav-item" onclick="irAFacturacion()">Facturacion</a>
            <a href="#" class="nav-item" onclick="irAContacto()">Contacto</a>
            <a href="#" class="nav-item">
                <span class="user-icon">👤</span> Mi Cuenta
            </a>
        </nav>

     
    </header>
        <!-- Opciones de Citas -->
        <script src="citas.js"></script>
    <div id="citas-options" class="hidden-options">
        <div class="exam-option" onclick="handleExamSelection('practico')">Examen Práctico</div>
        <div class="exam-option" onclick="handleExamSelection('teorico')">Examen Teórico</div>
    </div>

    <h2 >Datos Personales</h2>
    <div style="text-align: center;">
        <p><strong>Nombre Completo:</strong> <?php echo $usuario['nombre'] . ' ' . $usuario['primerApellido'] . ' ' . $usuario['segundoApellido']; ?></p>
        <p><strong>Cédula:</strong> <?php echo $usuario['cedula']; ?></p>
        <p><strong>Correo Electrónico:</strong> <?php echo $usuario['email']; ?></p>
        <p><strong>Teléfono:</strong> <?php echo $usuario['telefono']; ?></p>

        <form id="agregarForm"  style="background-color: antiquewhite;" onsubmit="agregarMetodo(event)">
    <h3>Agregar Método de Pago</h3>
    <label for="numero_tarjeta">Num. Tarjeta</label>
    <input id="numero_tarjeta" type="tel" placeholder="1234 5678 9012 3456" required>
    <label for="fecha_vencimiento_tarjeta">Fecha de Vencimiento</label>
    <input id="fecha_vencimiento_tarjeta" type="month" required>
    <label for="nombre_tarjeta">Nombre en Tarjeta</label>
    <input id="nombre_tarjeta" type="text" placeholder="Nombre del titular" required>
    <label for="cvv_tarjeta">CVV</label>
    <input id="cvv_tarjeta" type="number" placeholder="123" required>
    <br>
    <button type="submit">✔ Agregar</button>
    <button type="button" onclick="ocultarFormularioAgregar()">❌ Cancelar</button>
</form>
        <div style="text-align: center; margin-top: 30px;">
            <h2>Métodos de Pago</h2>
            <div id="metodosPago"></div> <!-- Aquí se mostrarán las tarjetas -->
        </div>
       

    </div>
    

    <div style="text-align: center; margin-top: 20px;">
        <a href="logout.php" style="text-decoration: none; color: white; background: red; padding: 10px 20px; border-radius: 5px;">Cerrar Sesión</a>
    </div>

    
</body>

</html>
