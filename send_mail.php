<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Recoger y sanitizar entradas del formulario
  $name = strip_tags(trim($_POST["name"]));
  $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
  $phone = strip_tags(trim($_POST["phone"]));
  $message = htmlspecialchars(trim($_POST["message"]));
  $location = htmlspecialchars(trim($_POST["location"] ?? 'No specified')); // Valor predeterminado si no se envía

  // Validar entradas
  if (empty($name) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($phone) || empty($message)) {
    http_response_code(400);
    echo "Please complete all required fields correctly and try again.";
    exit;
  }

  // Configuración del correo
  $recipient = "andreageraldinne@hotmail.com"; // Cambiar según sea necesario
  $subject = "New Contact from $name";

  // Contenido del correo
  $email_content = "Name: $name\n";
  $email_content .= "Email: $email\n";
  $email_content .= "Phone: $phone\n";
  $email_content .= "Location: $location\n\n";
  $email_content .= "Message:\n$message\n";

  // Encabezados del correo
  $email_headers = "From: $name <$email>\r\n";
  $email_headers .= "Reply-To: $email\r\n";

  // Intentar enviar el correo
  if (mail($recipient, $subject, $email_content, $email_headers)) {
    http_response_code(200);
    echo "Thank You! Your message has been sent.";
  } else {
    http_response_code(500);
    echo "Oops! Something went wrong, and we couldn't send your message.";
  }
} else {
  // Rechazar métodos no permitidos
  http_response_code(403);
  echo "Invalid request. Please try again.";
}