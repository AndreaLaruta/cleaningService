<?php
if($_SERVER['REQUEST_METHOD'] != 'POST' ){
    header("Location: index.html" );
}

require 'phpmailer/PHPMailer.php';
require 'phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;

$nombre = $_POST['name'];
$email = $_POST['email_address'];
$phone = $_POST['phone'];
$message = $_POST['message'];

if( empty(trim($nombre)) ) $nombre = 'anonimo';
if( empty(trim($email_address)) ) $email_address = '';
if( empty(trim($phone)) ) $phone = '';

$body = <<<HTML
    <h1>Contacto desde la web</h1>
    <p>De: $name</p>
    <p>phone: $phone</p>
    <p>mail: $email_address</p>
    <h2>Mensaje</h2>
    $message
HTML;

// $mailer = new PHPMailer();
// $mailer->setFrom( $email, "$name $phone" );
// $mailer->addAddress('servicio@superstarcleaningservices.com','Mensaje del Sitio web');
// $mailer->Subject = "Mensaje web";
// $mailer->msgHTML($body);
// $mailer->AltBody = strip_tags($body);
// $mailer->CharSet = 'UTF-8';



//$rta = $mailer->send( );

var_dump($rta, $nombre, $email, $phone);
//header("Location: gracias.html" );