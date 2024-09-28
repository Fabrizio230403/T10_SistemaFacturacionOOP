<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

// Definir destinatario y datos del formulario
$destinatario = $_POST['email'];
$nombre = $_POST['nombre'];
$telefono = $_POST['telefono'];
$asunto = $_POST['asunto'];
$archivo = $_POST['archivo'];
$tipo = $_POST['tipo']; // 'factura' o 'boleta'
 

$mensajeCompleto = "Atentamente: " . $nombre . "\nContacto: " . $telefono;

try {
    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'estefano.jurado.c52@gmail.com';
    $mail->Password   = 'nsmu wleg wogy hyip'; // Contraseña de aplicación
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Destinatarios
    $mail->setFrom('estefano.jurado.c52@gmail.com', 'Fabrizio Jurado');
    $mail->addAddress($destinatario);

     
    $rutaArchivo = $tipo === 'factura' ? 'facturas/' : 'boletas/';
    $rutaCompleta = $rutaArchivo . $archivo;

    if (!empty($archivo)) {
        $mail->addAttachment($rutaCompleta);  
    }

    // Contenido del correo
    $mail->isHTML(false);
    $mail->CharSet = 'UTF-8'; // Establecer el conjunto de caracteres
    $mail->Subject = $asunto;
    $mail->Body    = $mensajeCompleto;

    // Enviar el correo
    $mail->send();
    echo "<script>alert('Correo enviado exitosamente')</script>";
    if ($tipo === 'boleta') {
        echo "<script>setTimeout(\"location.href='listar_boletas.php'\", 1000)</script>";
    } else {
        echo "<script>setTimeout(\"location.href='listar_facturas.php'\", 1000)</script>";
    }
} catch (Exception $e) {
    echo "El correo no pudo ser enviado. Error: {$mail->ErrorInfo}";
}
?>
