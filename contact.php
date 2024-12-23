<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Collect form data
  $name = htmlspecialchars(strip_tags(trim($_POST["name"])));
  $email_address = filter_var(trim($_POST["email_address"]), FILTER_SANITIZE_EMAIL);
  $phone = htmlspecialchars(strip_tags(trim($_POST["phone"])));
  $message = htmlspecialchars(strip_tags(trim($_POST["message"])));
  $location = htmlspecialchars(strip_tags(trim($_POST["location"]))); // Location field

  // Validate fields
  if (empty($name) || empty($email_address) || empty($phone) || empty($message) || !filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
    echo "Please complete all fields and provide a valid email.";
    exit;
  }

  // Email settings
  $to = "andreageraldinne@gmail.com"; // Replace with your email address
  $subject = "New contact message from $name";
  $email_body = "You have received a new contact message.\n\n" .
    "Name: $name\n" .
    "Email: $email_address\n" .
    "Phone: $phone\n" .
    "Location: $location\n" . // Add location here
    "Message:\n$message";

  $headers = "From: $email_address\r\n";
  $headers .= "Reply-To: $email_address\r\n";
  $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

  // Send the email
  if (mail($to, $subject, $email_body, $headers)) {
    echo "Message sent successfully.";
  } else {
    echo "There was an error sending the message.";
  }
}