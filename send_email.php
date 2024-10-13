<?php
require 'config.php';

function send_email($to, $subject, $message) {
    $headers = "From: " . SMTP_USER;

    // Configure SMTP settings
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USER;
    $mail->Password = SMTP_PASS;
    $mail->SMTPSecure = 'tls';
    $mail->Port = SMTP_PORT;

    $mail->setFrom(SMTP_USER, 'User Management');
    $mail->addAddress($to);
    $mail->Subject = $subject;
    $mail->Body = $message;

    if (!$mail->send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        echo "Message sent!";
    }
}
?>
