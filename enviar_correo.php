<?php
if (isset($_POST["enviar"])) {
    // Verificar que los campos no estén vacíos
    if (!empty($_POST["asunto"]) && !empty($_POST["descripcion"]) && !empty($_POST["emailDestino"]) && !empty($_POST["emisor"])) {
        $asunto = $_POST["asunto"];
        $descripcion = $_POST["descripcion"];
        $emailDestino = $_POST["emailDestino"];
        $emisor = $_POST["emisor"];

        // Validar el formato del correo electrónico
        if (filter_var($emailDestino, FILTER_VALIDATE_EMAIL) && filter_var($emisor, FILTER_VALIDATE_EMAIL)) {
            $header = "From: $emisor" . "\r\n";
            $header .= "Reply-To: $emisor" . "\r\n";
            $header .= "X-Mailer: PHP/" . phpversion();

            // Enviar el correo
            $mail = mail($emailDestino, $asunto, $descripcion, $header);

            if ($mail) {
                echo "<h4>¡Correo enviado exitosamente!</h4>";
            } else {
                echo "<h4>Error al enviar el correo. Inténtalo de nuevo.</h4>";
            }
        } else {
            echo "<h4>Los correos proporcionados no son válidos.</h4>";
        }
    } else {
        echo "<h4>Por favor, completa todos los campos.</h4>";
    }
}
?>
