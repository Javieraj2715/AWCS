<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php'; // Asegúrate de que el archivo autoload.php de Composer está en la misma carpeta

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexion = new mysqli("localhost", "JAVIER", "123", "proyectofinalg8");

    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    // Recoge los datos del formulario
    $name = $_POST['name'];
    $email = $_POST['email'];
    $id = $_POST['id'];
    $province = $_POST['province'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $tipoExamen = isset($_POST['tipo']) ? $_POST['tipo'] : null; // Verifica si se envió el campo

if (empty($tipoExamen)) {
    die("Error: El tipo de examen no se ha especificado.");
}
    // Guardar la matrícula en la base de datos
    $sqlMatricula = "INSERT INTO matriculas (nombre, email, cedula, provincia, fecha, horario, tipo_examen) 
                     VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sqlMatricula);
    $stmt->bind_param("sssssss", $name, $email, $id, $province, $date, $time, $tipoExamen);

    if (!$stmt->execute()) {
        die("Error al guardar la matrícula: " . $stmt->error);
    }

    // Generar una factura asociada
    $costo = 5000; // Monto fijo por examen
    $estado = "Pendiente";
    $descripcion = "Pago por examen $tipoExamen";
    $sqlFactura = "INSERT INTO facturas (usuario_cedula, monto, estado, fecha_pago) 
                   VALUES (?,?, ?, ?)";
    $stmtFactura = $conexion->prepare($sqlFactura);
    $stmtFactura->bind_param("siis", $id, $costo, $estado, $date);

    if (!$stmtFactura->execute()) {
        die("Error al generar la factura: " . $stmtFactura->error);
    }

    // Enviar correo de confirmación
    $mail = new PHPMailer();

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'javieraj2715@gmail.com'; // Cambia esto
        $mail->Password = 'mjnv lzem xzcb ossc'; // Cambia esto
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('javieraj2715@gmail.com', 'Exámenes de Conducir');
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = 'Confirmación de inscripción al examen ' . $tipoExamen;
        $mail->Body = "
            ¡Hola $name!<br><br>
            Has sido inscrito exitosamente en el examen $tipoExamen.<br><br>
            <strong>Detalles del examen:</strong><br>
            Número de identificación: $id<br>
            Provincia: $province<br>
            Fecha del examen: $date<br>
            Horario: $time<br><br>
            ¡Nos vemos en tu examen!<br><br>Atentamente,<br>El equipo de Exámenes de Conducir
        ";

        if (!$mail->send()) {
            echo "Error al enviar correo: {$mail->ErrorInfo}";
        }
    } catch (Exception $e) {
        echo "Error al enviar correo: {$mail->ErrorInfo}";
    }

    // Redirige a la confirmación o muestra un mensaje de éxito
    header("Location: confirmacion.html");
    exit();
}
?>
