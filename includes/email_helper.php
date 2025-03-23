<?php
function sendEmail($to, $subject, $message)
{
    $headers = "From: no-reply@schoolinventory.com\r\n";
    $headers .= "Reply-To: no-reply@schoolinventory.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    if (!mail($to, $subject, $message, $headers)) {
        error_log("Failed to send email to $to with subject $subject");
    }
}
?>