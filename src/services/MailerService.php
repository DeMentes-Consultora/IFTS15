<?php
// src/services/MailerService.php
namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;

class MailerService
{
    private $mailer;

    public function __construct()
    {
        // Cargar .env desde la raíz del proyecto (dos niveles arriba de /src/services/)
        $envPath = dirname(__DIR__, 2);
        if (file_exists($envPath . '/.env')) {
            $dotenv = \Dotenv\Dotenv::createMutable($envPath);
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
        $mail->Host       = $this->envString(['MAIL_HOST', 'MAIL_HOSTNAME'], 'localhost');
        $mail->SMTPAuth   = $this->envBool(['MAIL_SMTPAuth', 'MAIL_SMTPAUTH', 'MAIL_SMTP_AUTH'], true);
        $mail->Username   = $this->envString(['MAIL_USERNAME'], '');
        $mail->Password   = $this->envString(['MAIL_PASSWORD'], '');
        $mail->Port       = intval($this->envString(['MAIL_PORT'], '587'));

        $encryption = strtolower($this->envString(['MAIL_ENCRYPTION'], 'tls'));
        if ($encryption === 'ssl' || $encryption === 'smtps') {
            $mail->SMTPSecure = defined('PHPMailer\\PHPMailer\\PHPMailer::ENCRYPTION_SMTPS') ? PHPMailer::ENCRYPTION_SMTPS : 'ssl';
        } elseif ($encryption === 'none' || $encryption === '') {
            $mail->SMTPSecure = '';
        } else {
            $mail->SMTPSecure = defined('PHPMailer\\PHPMailer\\PHPMailer::ENCRYPTION_STARTTLS') ? PHPMailer::ENCRYPTION_STARTTLS : 'tls';
        }

        $fromEmail = $this->envString(['MAIL_FROM', 'MAIL_FROM_ADDRESS', 'MAIL_USERNAME'], 'no-reply@localhost');
        $fromName = $_ENV['MAIL_FROM_NAME'] ?? 'IFTS15';
        $mail->setFrom($fromEmail, $fromName);
    }

    private function envString(array $keys, $default = '')
    {
        foreach ($keys as $key) {
            if (isset($_ENV[$key])) {
                $value = trim((string)$_ENV[$key]);
                if ($value !== '') {
                    return $value;
                }
            }

            $envValue = getenv($key);
            if ($envValue !== false) {
                $value = trim((string)$envValue);
                if ($value !== '') {
                    return $value;
                }
            }
        }

        return $default;
    }

    private function envBool(array $keys, $default = false)
    {
        foreach ($keys as $key) {
            if (isset($_ENV[$key])) {
                $raw = trim((string)$_ENV[$key]);
                if ($raw === '') {
                    continue;
                }

                $parsed = filter_var($raw, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                return $parsed === null ? $default : $parsed;
            }
        }

        return $default;
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

            $rawAddresses = is_array($to) ? $to : preg_split('/[,;]+/', (string)$to);
            $validAddresses = [];

            foreach ($rawAddresses as $addr) {
                $email = trim((string)$addr);
                if ($email === '') {
                    continue;
                }
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $validAddresses[] = $email;
                }
            }

            if (empty($validAddresses)) {
                throw new \RuntimeException('No hay destinatarios válidos para el envío.');
            }

            foreach ($validAddresses as $addr) {
                $mail->addAddress($addr);
            }

            if ($replyTo) {
                $replyToSanitized = trim((string)$replyTo);
                if (filter_var($replyToSanitized, FILTER_VALIDATE_EMAIL)) {
                    $mail->addReplyTo($replyToSanitized);
                }
            }

            $mail->isHTML($isHtml);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->send(); // Activado para envío real
            return ['success' => true, 'message' => 'Enviado correctamente'];
        } catch (\Throwable $e) {
            error_log('MailerService error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
