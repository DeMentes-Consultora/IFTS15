<?php
// src/services/MailerService.php
namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailerService
{
    private $mailer;

    public function __construct()
    {
        // Cargar .env desde la raíz del proyecto (dos niveles arriba de /src/services/)
        $envPath = dirname(__DIR__, 2);
        if (file_exists($envPath . '/.env')) {
            $dotenv = \Dotenv\Dotenv::createImmutable($envPath);
            if (method_exists($dotenv, 'safeLoad')) {
                $dotenv->safeLoad();
            } else {
                try { $dotenv->load(); } catch (\Throwable $e) { /* ignore */ }
            }
        }

        $this->mailer = new PHPMailer(true);
        $this->configure();
    }

    private function configure()
    {
        $mail = $this->mailer;
        $mail->isSMTP();
        $mail->Host       = $_ENV['MAIL_HOST'] ?? $_ENV['MAIL_HOSTNAME'] ?? 'localhost';
        $mail->SMTPAuth   = (isset($_ENV['MAIL_SMTPAuth']) && $_ENV['MAIL_SMTPAuth'] === 'true');
        $mail->Username   = $_ENV['MAIL_USERNAME'] ?? '';
        $mail->Password   = $_ENV['MAIL_PASSWORD'] ?? '';
        $mail->SMTPSecure = defined('PHPMailer\\PHPMailer\\PHPMailer::ENCRYPTION_STARTTLS') ? PHPMailer::ENCRYPTION_STARTTLS : 'tls';
        $mail->Port       = intval($_ENV['MAIL_PORT'] ?? 587);
        $fromEmail = $_ENV['MAIL_FROM'] ?? $_ENV['MAIL_USERNAME'] ?? 'no-reply@localhost';
        $fromName = $_ENV['MAIL_FROM_NAME'] ?? 'IFTS15';
        $mail->setFrom($fromEmail, $fromName);
    }

    /**
     * Enviar un correo
     * @param string|array $to
     * @param string $subject
     * @param string $body
     * @param bool $isHtml
     * @param string|null $replyTo
     * @return array ['success'=>bool, 'message'=>string]
     */
    public function send($to, $subject, $body, $isHtml = true, $replyTo = null)
    {
        try {
            $mail = $this->mailer;
            $mail->clearAddresses();
            $mail->clearReplyTos();
            if (is_array($to)) {
                foreach ($to as $addr) $mail->addAddress($addr);
            } else {
                $mail->addAddress($to);
            }
            if ($replyTo) {
                $mail->addReplyTo($replyTo);
            }
            $mail->isHTML($isHtml);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->send();
            return ['success' => true, 'message' => 'Enviado correctamente'];
        } catch (Exception $e) {
            error_log('MailerService error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
