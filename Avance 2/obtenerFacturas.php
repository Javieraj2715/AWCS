<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['usuario'])) {
    echo json_encode(["status" => "error", "message" => "Usuario no autenticado"]);
    exit();
}

$usuario = $_SESSION['usuario'];
$usuario_cedula = $usuario['cedula'];

$conexion = new mysqli("localhost", "JAVIER", "123", "proyectofinalg8");

if ($conexion->connect_error) {
    echo json_encode(["status" => "error", "message" => "Conexión fallida: " . $conexion->connect_error]);
    exit();
}

$sql = "SELECT factura_id, monto, tipo_examen, fecha_examen, provincia, estado, fecha_pago FROM facturas WHERE usuario_cedula = ?";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Error en la preparación: " . $conexion->error]);
    exit();
}

$stmt->bind_param("i", $usuario_cedula);
$stmt->execute();

$result = $stmt->get_result();
$facturas = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($facturas);

$stmt->close();
$conexion->close();
?>
