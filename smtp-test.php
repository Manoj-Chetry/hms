<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // adjust path if necessary

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp-relay.brevo.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = '8cb154001@smtp-brevo.com'; // your Brevo SMTP login
    $mail->Password   = 'MmWAj1zFqfs5PO9H';       // your Brevo SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // Recipients
    $mail->setFrom('8cb154001@smtp-brevo.com', 'SMTP Test');
    $mail->addAddress('mchetry606@gmail.com'); // your actual email

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Brevo SMTP Test';
    $mail->Body    = 'This is a test email sent via Brevo SMTP using PHPMailer.';

    $mail->send();
    echo '✅ Email has been sent successfully';
} catch (Exception $e) {
    echo "❌ Email could not be sent. Error: {$mail->ErrorInfo}";
}
