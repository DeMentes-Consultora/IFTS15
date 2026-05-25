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

    public function notificarPostulacionAlumno(string $emailAlumno, string $nombreAlumno, string $tituloOferta, string $nombrePublicador): array
    {
        $nombreSeguro = htmlspecialchars($nombreAlumno, ENT_QUOTES, 'UTF-8');
        $tituloSeguro = htmlspecialchars($tituloOferta, ENT_QUOTES, 'UTF-8');
        $publicadorSeguro = htmlspecialchars($nombrePublicador, ENT_QUOTES, 'UTF-8');
        $asunto = 'Postulacion recibida - IFTS15';

        $body = <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,.08); overflow: hidden; }
        .header { background: linear-gradient(135deg, #0d6efd, #0a58ca); color: #ffffff; padding: 28px; text-align: center; }
        .header h1 { margin: 0; font-size: 22px; }
        .body { padding: 28px; color: #333333; }
        .box { background: #f8f9fa; border-left: 4px solid #0d6efd; padding: 16px; margin: 20px 0; }
        .box p { margin: 6px 0; }
        .footer { background: #f8f9fa; padding: 18px; text-align: center; font-size: 12px; color: #666666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Postulacion recibida</h1>
        </div>
        <div class="body">
            <p>Hola <strong>{$nombreSeguro}</strong>,</p>
            <p>Recibimos correctamente tu postulacion a la oferta laboral indicada a continuacion.</p>
            <div class="box">
                <p><strong>Oferta:</strong> {$tituloSeguro}</p>
                <p><strong>Publicada por:</strong> {$publicadorSeguro}</p>
                <p><strong>Estado:</strong> Postulacion recibida</p>
            </div>
            <p>Si tu postulacion avanza o hay novedades sobre este proceso, te avisaremos por este mismo medio.</p>
            <p>Saludos,<br>Equipo de IFTS15</p>
        </div>
        <div class="footer">
            Este es un correo automatico de IFTS15. No respondas este mensaje.
        </div>
    </div>
</body>
</html>
HTML;

        return $this->send($emailAlumno, $asunto, $body, true, null);
    }
}
