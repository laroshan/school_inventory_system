<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php'; // Adjusted path to autoload.php

function sendEmail($to, $subject, $message)
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = getenv('SMTP_HOST'); // Retrieve SMTP server from .env
        $mail->SMTPAuth = true;
        $mail->Username = getenv('SMTP_USERNAME'); // Retrieve email from .env
        $mail->Password = getenv('SMTP_PASSWORD'); // Retrieve email password from .env
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use TLS
        $mail->Port = getenv('SMTP_PORT'); // Retrieve port from .env

        // Recipients
        $mail->setFrom(getenv('SMTP_FROM_EMAIL'), getenv('SMTP_FROM_NAME')); // Retrieve sender email and name from .env
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();
        error_log("Email successfully sent to $to with subject $subject");
    } catch (Exception $e) {
        error_log("Failed to send email. Error: {$mail->ErrorInfo}");
    }
}
?>