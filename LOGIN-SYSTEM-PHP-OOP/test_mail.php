<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/src/Exception.php';
require_once __DIR__ . '/src/PHPMailer.php';
require_once __DIR__ . '/src/SMTP.php';

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = 'sandbox.smtp.mailtrap.io';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'b43a53e064b8c7';
    $mail->Password   = '4c96d739b083e7';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 2525;
    $mail->SMTPDebug  = 2;

    $mail->setFrom('no-reply@titan.com', 'TITAN');
    $mail->addAddress('aronditee@gmail.com');
    $mail->Subject = 'TITAN Test';
    $mail->Body    = 'Test email from TITAN.';
    $mail->send();
    echo 'SUCCESS - check Mailtrap inbox!';
} catch (Exception $e) {
    echo 'FAILED: ' . $mail->ErrorInfo;
}