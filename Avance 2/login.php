<?php
$conexion = new mysqli("localhost", "JAVIER", "123", "proyectofinalg8");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtén los datos del formulario de login
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];

    // Busca al usuario en la base de datos
    $sql = "SELECT * FROM usuarios WHERE Correo_Electronico = '$usuario'";
    $result = $conexion->query($sql);

    // Si el usuario existe
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verifica la contraseña
        if (password_verify($contraseña, $row['Password'])) {
            // Redirige al área principal
            header("Location: main.html");
            exit();
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }
}

// Cierra la conexión
$conexion->close();
?>
