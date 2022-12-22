<?php
// Importación de las clases de PHPMailer dentro de de los namespaces
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Carga de archivos PHP
require '../phpmailer/PHPMailer.php';
require '../phpmailer/SMTP.php';
require '../phpmailer/Exception.php';

// Instancia de PHPMailer (con excepciones habilitadas)
$mail = new PHPMailer(true);

// Intancia de fecha y hora
date_default_timezone_set("America/Argentina/Buenos_Aires");
$date = date('d/m/Y - H:i:s');

// Validación POST
if($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: https://www.manuelferrero.com.ar/index.html#contact");
}

// Instancia de campos de formulario
$name = $_POST['name'];
$email = $_POST['email'];
$othSubject = $_POST['other-subject'];
if ($othSubject) {
    $subject = $_POST['subject'] . ' (' . $othSubject . ')';
} else {
    $subject = $_POST['subject'];
}
$message = $_POST['message'];

// Creación del cuerpo del mensaje
$body = "<h1>Contacto desde la web</h1>";
$body.= "<p><b>De: </b>$name <i>($email)</i><br>";
$body.= "<b>Asunto: </b>$subject<br>";
$body.= "<b>Fecha: </b>$date (Hora de Argentina)<br>";
$body.= "<b>Mensaje: </b><br>";
$body.= "$message</p>";


// Configuración y envío
try {
    // Configuración del servidor
    $mail->SMTPDebug = SMTP::DEBUG_OFF;
    $mail->isSMTP();
    $mail->Host       = 'smtp.correoseguro.co';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'no-responder@manuelferrero.com.ar';
    $mail->Password   = 'Pety21RC!';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    // Destinatarios
    $mail->setFrom("no-responder@manuelferrero.com.ar", "Mensaje Web");
    $mail->addAddress('contacto@manuelferrero.com.ar', 'Manuel Ferrero');
    $mail->addReplyTo("$email", "$name");
    // $mail->addCC('');
    // $mail->addBCC('');

    // Archivos adjuntos
    // $mail->addAttachment('', '');    // ('ruta_del_archivo/archivo.ext', 'nombre_archivo(opcional).ext')

    // Contenido del correo
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = $subject;
    $mail->Body    = $body;
    $mail->AltBody = strip_tags($body);

    $mail->send();
    echo 'OK';
} catch (Exception $e) {
    echo "{$mail->ErrorInfo}";
}