<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendEmail($to, $subject, $body)
{
    require_once '../vendor/autoload.php';

    $mail = new PHPMailer(true);

    try {
        // Configuration SMTP
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = SMTP_PORT;

        // Paramètres de l'email
        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('Erreur lors de l\'envoi de l\'email : ' . $mail->ErrorInfo);
        return false;
    }
}

function showOrderConfirmation()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Vérifier si l'utilisateur est connecté
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
        header('Location: ?route=login');
        exit();
    }

    // Afficher la vue de confirmation de commande
    include __DIR__ . '/../views/order-confirmation.php';
}

