<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

if (!isset($_SESSION['usuario'])) {
    echo json_encode(["error" => "Usuario no autenticado"]);
    exit();
}

$usuario = $_SESSION['usuario'];
$usuario_cedula = $usuario['cedula']; // Usamos 'cedula' como identificador

$conexion = new mysqli("localhost", "JAVIER", "123", "proyectofinalg8");

if ($conexion->connect_error) {
    echo json_encode(["error" => "Conexión fallida: " . $conexion->connect_error]);
    exit();
}

$sql = "SELECT numero, vencimiento, nombre FROM tarjetas WHERE usuario_id = ?";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    echo json_encode(["error" => "Error en la preparación: " . $conexion->error]);
    exit();
}

$stmt->bind_param("i", $usuario_cedula); // Vinculamos 'cedula' en lugar de 'id'
$stmt->execute();

$result = $stmt->get_result();
$tarjetas = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($tarjetas);

$stmt->close();
$conexion->close();
?>
