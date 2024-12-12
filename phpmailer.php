<?php
// Start output buffering to prevent header errors
ob_start();

// Allow only POST requests
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  header("Location: index.html");
  exit();
}

// Require PHPMailer classes
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Sanitize input data to prevent XSS and injection attacks
function sanitize_input($data)
{
  return htmlspecialchars(stripslashes(trim($data)));
}

$name = sanitize_input($_POST['name'] ?? 'Anonymous');
$email = filter_var($_POST['email_address'], FILTER_VALIDATE_EMAIL) ? sanitize_input($_POST['email_address']) : 'no-email@example.com';
$phone = sanitize_input($_POST['phone'] ?? 'Not Provided');
$location = sanitize_input($_POST['location'] ?? 'Not Specified');
$message = sanitize_input($_POST['message'] ?? '');

// Verify Google reCAPTCHA response
$recaptcha_secret = '6Lcky4AqAAAAAO7TOmMFFJgcIW6msye-IugiOEMJ';
$recaptcha_response = $_POST['g-recaptcha-response'] ?? '';
$recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
$recaptcha_verify = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
$recaptcha_result = json_decode($recaptcha_verify, true);

// You can choose to always send the email regardless of reCAPTCHA verification
// Comment out or remove the following block if you always want to send the email
// if (!$recaptcha_result['success']) {
//     // Redirect with an error parameter for failed reCAPTCHA
//     header("Location: index.php?error=recaptcha_failed");
//     exit();
// }

// Email body
$body = <<<HTML
    <h1>Contact Request</h1>
    <p><strong>Name:</strong> $name</p>
    <p><strong>Phone:</strong> $phone</p>
    <p><strong>Email:</strong> $email</p>
    <p><strong>Location:</strong> $location</p>
    <h2>Message:</h2>
    <p>$message</p>
HTML;

try {
  $mailer = new PHPMailer(true);

  // SMTP configuration
  $mailer->isSMTP();
  $mailer->Host = 'smtp.gmail.com';
  $mailer->SMTPAuth = true;
  $mailer->Username = 'andreageraldinne@gmail.com';
  $mailer->Password = 'rdik mjcg shcm hmeo'; // Use a secure app password
  $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $mailer->Port = 587;

  // Recipient settings
  $mailer->setFrom('andreageraldinne@gmail.com', 'Website Contact');
  $mailer->addAddress('cleansuperstar@hotmail.com', 'Administrator');

  // Email content
  $mailer->isHTML(true);
  $mailer->Subject = 'New Contact Request';
  $mailer->Body = $body;
  $mailer->AltBody = strip_tags($body);
  $mailer->CharSet = 'UTF-8';

  // Send email
  if ($mailer->send()) {
    // Redirect to index.html with a success message
    header("Location: index.html?success=true");
    exit();
  } else {
    // Redirect to index.html with an email sending failure error
    header("Location: index.html?error=email_failed");
    exit();
  }
} catch (Exception $e) {
  error_log("PHPMailer Error: {$mailer->ErrorInfo}");
  // Redirect to index.html with an email sending failure error
  header("Location: index.html?error=email_failed");
  exit();
}

// Clean output buffer
ob_end_flush();