<?php
// Incluir PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Asegúrate de que el archivo autoload.php de Composer está en la misma carpeta

// Recoger los datos del formulario
$name = $_POST['name'];
$email = $_POST['email'];
$id = $_POST['id'];
$province = $_POST['province'];
$date = $_POST['date'];
$time = $_POST['time'];

// Crear una nueva instancia de PHPMailer
$mail = new PHPMailer(); // Aquí ya no repites PHPMailer\PHPMailer\PHPMailer

try {
    // Configurar el servidor SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Usamos Gmail como servidor SMTP
    $mail->SMTPAuth = true;
    $mail->Username = 'javieraj2715@gmail.com'; // Tu dirección de correo de Gmail
    $mail->Password = 'mjnv lzem xzcb ossc'; // Tu contraseña de aplicación generada
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Cifrado STARTTLS
    $mail->Port = 587;

    // Destinatario y remitente
    $mail->setFrom('javieraj2715@gmail.com', 'Exámenes de Conducir');
    $mail->addAddress($email, $name); // Enviar a la dirección de correo proporcionada en el formulario

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Confirmación de inscripción al examen teórico';
    $mail->Body    = "¡Hola $name!<br><br>Has sido inscrito exitosamente en el examen teórico.<br><br>
                      <strong>Detalles del examen:</strong><br>
                      Número de identificación: $id<br>
                      Provincia: $province<br>
                      Fecha del examen: $date<br>
                      Horario: $time<br><br>
                      ¡Nos vemos en tu examen!<br><br>Atentamente,<br>El equipo de Exámenes de Conducir";

    // Enviar correo
    if($mail->send()) {
        echo '<script>window.location.href = "confirmacion.php";</script>';
    } else {
        echo "El mensaje no pudo ser enviado. Error de correo: {$mail->ErrorInfo}";
    }
} catch (Exception $e) {
    echo "El mensaje no pudo ser enviado. Error de PHPMailer: {$mail->ErrorInfo}";
}
?>
