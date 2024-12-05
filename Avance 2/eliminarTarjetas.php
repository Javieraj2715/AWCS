<?php
session_start();
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

if (empty($numero)) {
    echo json_encode(["status" => "error", "message" => "Número de tarjeta no proporcionado"]);
    exit();
}

$sql = "DELETE FROM tarjetas WHERE numero = ? AND usuario_id = ?";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Error en la preparación: " . $conexion->error]);
    exit();
}

$usuario_cedula = $_SESSION['usuario']['cedula'];
$stmt->bind_param("si", $numero, $usuario_cedula);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Tarjeta eliminada exitosamente"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error al eliminar tarjeta"]);
}

$stmt->close();
$conexion->close();
?>
