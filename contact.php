<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Recibir los datos del formulario
  $name = htmlspecialchars(strip_tags(trim($_POST["name"])));
  $email_address = filter_var(trim($_POST["email_address"]), FILTER_SANITIZE_EMAIL);
  $phone = htmlspecialchars(strip_tags(trim($_POST["phone"])));
  $message = htmlspecialchars(strip_tags(trim($_POST["message"])));

  // Validación de los campos
  if (empty($name) || empty($email_address) || empty($phone) || empty($message) || !filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
    echo "Por favor, completa todos los campos y usa un correo válido.";
    exit;
  }

  // Configuración del correo
  $to = "correo@gmial.com"; // Reemplaza con tu dirección de correo
  $subject = "Nuevo mensaje de contacto de $name";
  $email_body = "Has recibido un nuevo mensaje de contacto.\n\n" .
    "Nombre: $name\n" .
    "Correo: $email_address\n" .
    "Teléfono: $phone\n" .
    "Mensaje:\n$message";

  $headers = "From: $email_address\r\n";
  $headers .= "Reply-To: $email_address\r\n";
  $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

  // Enviar el correo
  if (mail($to, $subject, $email_body, $headers)) {
    echo "Mensaje enviado correctamente.";
  } else {
    echo "Hubo un error al enviar el mensaje.";
  }
}