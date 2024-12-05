<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

if (!isset($_SESSION['usuario'])) {
    echo json_encode(["status" => "error", "message" => "Usuario no autenticado"]);
    exit();
}

$conexion = new mysqli("localhost", "JAVIER", "123", "proyectofinalg8");

if ($conexion->connect_error) {
    echo json_encode(["status" => "error", "message" => "Conexión fallida: " . $conexion->connect_error]);
    exit();
}

$numero = $_POST['numero'];
$vencimiento = $_POST['vencimiento']; // Recibe el valor 'YYYY-MM'
$vencimiento = $vencimiento . "-01"; // Agrega el día '01' para convertirlo a 'YYYY-MM-DD'

$nombre = $_POST['nombre'];
$usuario_cedula = $_SESSION['usuario']['cedula']; // Usa la cédula del usuario

if (empty($numero) || empty($vencimiento) || empty($nombre)) {
    echo json_encode(["status" => "error", "message" => "Datos incompletos"]);
    exit();
}

$sql = "INSERT INTO tarjetas (usuario_id, numero, vencimiento, nombre) VALUES (?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Error en la preparación: " . $conexion->error]);
    exit();
}

$stmt->bind_param("isss", $usuario_cedula, $numero, $vencimiento, $nombre);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Tarjeta agregada exitosamente"]);
} else {
    echo json_encode(["status" => "error", "message" => $stmt->error]);
}

$stmt->close();
$conexion->close();
?>
