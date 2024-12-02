<?php
// Incluye el archivo de autoload generado por Composer
require 'vendor/autoload.php'; 

// Crea una instancia de PHPMailer
$mail = new PHPMailer\PHPMailer\PHPMailer();

// Configuración del servidor SMTP
$mail->isSMTP();  // Usa SMTP
$mail->Host = 'smtp.gmail.com';  // Servidor SMTP de Gmail
$mail->SMTPAuth = true;  // Activar la autenticación SMTP
$mail->Username = 'javieraj2715@gmail.com';  // Tu dirección de correo
$mail->Password = 'mjnv lzem xzcb ossc';  // Tu contraseña de correo (considera usar contraseñas de aplicaciones para mayor seguridad)
$mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;  // Cifrado STARTTLS
$mail->Port = 587;  // Puerto SMTP

// Remitente y destinatario
$mail->setFrom('javierak2715@gmail.com', 'Tu Nombre');
$mail->addAddress('javieraj2715@gmail.com', 'Destinatario');  // Cambia el correo del destinatario

// Contenido del correo
$mail->isHTML(true);  // Correo en formato HTML
$mail->Subject = 'Prueba de correo desde PHPMailer';
$mail->Body    = 'Este es un correo de prueba enviado desde PHPMailer con Gmail.';

if ($mail->send()) {
    echo 'Correo enviado con éxito.';
} else {
    echo 'Error al enviar el correo: ' . $mail->ErrorInfo;
}
?>
