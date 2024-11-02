<?php
// Verifica si la solicitud es de tipo POST
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: index.html");
    exit();
}

// Requiere los archivos necesarios de PHPMailer
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Datos del formulario enviados por el usuario
$name = $_POST['name'] ?? 'anonimo';
$email = $_POST['email_address'] ?? 'no-email@example.com';
$phone = $_POST['phone'] ?? 'No proporcionado';
$message = $_POST['message'] ?? '';

// Cuerpo del correo que será enviado a karlamenesses19@gmail.com
$body = <<<HTML
    <h1>Solicitud de Contacto</h1>
    <p><strong>Nombre:</strong> $name</p>
    <p><strong>Teléfono:</strong> $phone</p>
    <p><strong>Correo Electrónico:</strong> $email</p>
    <h2>Mensaje:</h2>
    <p>$message</p>
HTML;

try {
    // Instancia de PHPMailer
    $mailer = new PHPMailer(true);

    // Configuración del servidor SMTP
    $mailer->isSMTP();                                        // Usar SMTP
    $mailer->Host = 'smtp.gmail.com';                         // Servidor SMTP
    $mailer->SMTPAuth = true;                                 // Activar autenticación SMTP
    $mailer->Username = 'andreageraldinne@gmail.com';          // Tu correo SMTP
    $mailer->Password = 'rdik mjcg shcm hmeo';                           // Tu contraseña SMTP generada por GOOGLE
    $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;     // Encriptación TLS
    $mailer->Port = 587;                                      // Puerto TCP para TLS

    // Configuración del correo
    $mailer->setFrom('cleansuperstar@hotmail.com', 'Formulario de Contacto'); // Correo remitente (tu cuenta SMTP)
    $mailer->addAddress('cleansuperstar@hotmail.com', 'Administrador');      // Correo de la empresa o administrador
    
    // Contenido del correo
    $mailer->isHTML(true);                                    // Enviar correo en formato HTML
    $mailer->Subject = 'Nueva Solicitud de Contacto';          // Asunto del correo
    $mailer->Body    = $body;                                 // Cuerpo del correo en HTML
    $mailer->AltBody = strip_tags($body);                     // Alternativa en texto plano
    $mailer->CharSet = 'UTF-8';                               // Codificación de caracteres

    // Enviar correo
    if ($mailer->send()) {
        echo 'La solicitud ha sido enviada correctamente.';
    } else {
        echo 'No se pudo enviar la solicitud.';
        //echo 'Mailer Error:aaaaaaaaaa ' . $mailer->ErrorInfo;

    }
} catch (Exception $e) {
    // Mostrar el error si ocurre
    echo "Error al enviar la solicitud: {$mailer->ErrorInfo}";
}