<?php

function generateEmailVerificationToken(int $bytes = 32, string $ttlSpec = '+24 hours'): array
{
    $raw = random_bytes($bytes);
    $token = bin2hex($raw);
    $expiresAt = new DateTimeImmutable($ttlSpec);
    return [$token, $expiresAt];
}

require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();




function sendVerificationEmail(string $email, string $username, string $url): void
{

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // o live.smtp.mailtrap.io per stream reali
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['EMAIL_USER']; // username
        $mail->Password = $_ENV['EMAIL_PASSWORD']; // password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // o 'ssl' su porta 465
        $mail->Port = 587; // 25, 465, 587 o 2525 sono possibili

        // Mittente e destinatario
        $mail->setFrom($_ENV['EMAIL_USER'], 'Registration Email Verification');
        $mail->addAddress($email, $username);

        // Content
        $mail->isHTML(true);


        // Subject
        $mail->Subject = 'Registration Email Verification';

        $mail->Body = 'Hi ' . $username . 'The verification url code is: ' . $url;


        $mail->send();
    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
    }

}