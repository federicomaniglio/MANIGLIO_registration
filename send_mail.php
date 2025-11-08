<?php
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


function sendLoginMail($toAddress, $toName)
{

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // o live.smtp.mailtrap.io per stream reali
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['EMAIL_USER']; // username fornito da Mailtrap
        $mail->Password = $_ENV['EMAIL_PASSWORD']; // password fornita da Mailtrap
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // o 'ssl' su porta 465
        $mail->Port = 587; // 25, 465, 587 o 2525 sono possibili

        // Mittente e destinatario
        $mail->setFrom($_ENV['EMAIL_USER'], 'Calcetto Website');
        $mail->addAddress($toAddress, $toName);

        // Content
        $mail->isHTML(true);
        // Context data
        $accessTime = (new DateTime('now', new DateTimeZone('Europe/Rome')))->format('Y-m-d H:i:s');
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'Unknown IP';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown device';

        // Subject
        $mail->Subject = 'New sign-in to your Calcetto account';

        // HTML Body
        $mail->Body = '
<!DOCTYPE html>
<html lang="en">
<body style="margin:0;padding:0;background:#ffffff;">
  <div style="font-family:Arial,Helvetica,sans-serif;color:#222;line-height:1.6;max-width:600px;margin:auto;padding:24px;">
    <div style="border-bottom:1px solid #eee;padding-bottom:12px;margin-bottom:20px;">
      <h1 style="margin:0;font-size:20px;color:#111;">Sign-in notification</h1>
      <p style="margin:6px 0 0;color:#666;font-size:12px;">Calcetto Website</p>
    </div>

    <p>Hi <strong>' . htmlspecialchars($toName, ENT_QUOTES, 'UTF-8') . '</strong>,</p>
    <p>We detected a new sign-in to your account.</p>

    <div style="background:#f7f7f9;border:1px solid #e6e6ef;border-radius:8px;padding:14px;margin:16px 0;">
      <p style="margin:0;"><strong>Date &amp; time:</strong> ' . $accessTime . '</p>
      <p style="margin:6px 0 0;"><strong>Sign-in origin (IP):</strong> ' . htmlspecialchars($ipAddress, ENT_QUOTES, 'UTF-8') . '</p>
      <p style="margin:6px 0 0;"><strong>Device/Browser:</strong> ' . htmlspecialchars($userAgent, ENT_QUOTES, 'UTF-8') . '</p>
    </div>

    <p>If this was you, no action is required.</p>
    <p>If you don’t recognize this activity, please reset your password immediately and contact our support team.</p>

    <div style="border-top:1px solid #eee;padding-top:12px;margin-top:20px;color:#777;font-size:12px;">
      <p style="margin:0;">This is an automated message. For assistance, reply to this email.</p>
    </div>
  </div>
</body>
</html>
';

// Plain-text alternative
        $mail->AltBody = "Sign-in notification - Calcetto Website\n"
            . "Hi " . $toName . ",\n"
            . "We detected a new sign-in to your account.\n\n"
            . "Date & time: " . $accessTime . "\n"
            . "Sign-in origin (IP): " . $ipAddress . "\n"
            . "Device/Browser: " . $userAgent . "\n\n"
            . "If this was you, no action is required.\n"
            . "If you don’t recognize this activity, reset your password immediately and contact support.";
        $mail->send();
    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
    }
}
