<?php
$conexion = new mysqli("localhost", "JAVIER", "123", "proyectofinalg8");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>

<?php
$cedula = $_POST['cedula'];
$nombre = $_POST['nombre'];
$primerApellido = $_POST['PrimerApellido'];
$segundoApellido = $_POST['SegundoApellido'];
$email = $_POST['email'];
$telefono = $_POST['telefono'];
$contraseña = $_POST['contraseña'];

// Encripta la contraseña
$contraseña_encriptada = password_hash($contraseña, PASSWORD_DEFAULT);

// Inserta el nuevo usuario en la base de datos
$sql = "INSERT INTO usuarios (Cedula,nombre,Primer_Apellido,Segundo_Apellido, Correo_Electronico, Numero_Telefono, Password) VALUES ('$cedula','$nombre','$primerApellido','$segundoApellido', '$email', '$telefono', '$contraseña_encriptada')";

if ($conexion->query($sql) === TRUE) {
    // Redirige al login después de registrar
    header("Location: login.html");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conexion->error;
}

// Cierra la conexión
$conexion->close();
?>