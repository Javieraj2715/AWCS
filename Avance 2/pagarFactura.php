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

$factura_id = $_POST['factura_id'];

if (empty($factura_id)) {
    echo json_encode(["status" => "error", "message" => "Factura no especificada"]);
    exit();
}

$sql = "UPDATE facturas SET estado = 'Pagada', fecha_pago = NOW() WHERE factura_id = ? AND usuario_cedula = ?";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Error en la preparación: " . $conexion->error]);
    exit();
}

$stmt->bind_param("ii", $factura_id, $usuario_cedula);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(["status" => "success", "message" => "Factura pagada exitosamente"]);
    } else {
        echo json_encode(["status" => "error", "message" => "No se pudo actualizar la factura. Verifica que la factura exista y pertenezca al usuario."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Error al pagar la factura"]);
}

$stmt->close();
$conexion->close();
?>
