<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;  // Importar la clase Dotenv para cargar el archivo .env

require 'vendor/autoload.php';

// Cargamos las variables del archivo credenciales.env
$dotenv = Dotenv::createImmutable(__DIR__, 'credenciales.env'); 
$dotenv->load();

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
     // Configuración del servidor SMTP usando las variables del archivo .env
     $mail->isSMTP();
     $mail->Host       = $_ENV['SMTP_HOST'];
     $mail->SMTPAuth   = true;
     $mail->Username   = $_ENV['SMTP_USER'];  // Correo de SMTP desde .env
     $mail->Password   = $_ENV['SMTP_PASS'];  // Contraseña desde .env
     $mail->SMTPSecure = $_ENV['SMTP_SECURE']; // tls o ssl según tu servidor
     $mail->Port       = $_ENV['SMTP_PORT'];

    // Destinatarios
    $mail->setFrom($_ENV['SMTP_USER'], 'Fabrizio Jurado'); // Usamos el correo desde el .env
    $mail->addAddress($destinatario);

     
    $rutaArchivo = $tipo === 'factura' ? 'facturas/' : 'boletas/';
    $rutaCompleta = $rutaArchivo . $archivo;

    if (!empty($archivo)) {
        $mail->addAttachment($rutaCompleta);  
    }

    // Contenido del correo
    $mail->isHTML(false);
    $mail->CharSet = 'UTF-8';  
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
