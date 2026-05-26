<?php
require_once __DIR__ . '/src/config.php';
use App\Model\User;
use App\Model\PasswordReset;

const MAILER_SERVICE_CLASS = 'App\\Services\\MailerService';

function ensureMailerServiceLoaded($baseDir)
{
    if (class_exists(MAILER_SERVICE_CLASS)) {
        return;
    }

    $candidates = [
        $baseDir . '/src/services/MailerService.php',
        $baseDir . '/src/Services/MailerService.php',
    ];

    foreach ($candidates as $candidate) {
        if (file_exists($candidate)) {
            require_once $candidate;
            if (class_exists(MAILER_SERVICE_CLASS)) {
                return;
            }
        }
    }

    throw new RuntimeException('MailerService no disponible. Revisá si existen src/services/MailerService.php o src/Services/MailerService.php en producción.');
}

function redirectRecuperoError($errorCode, $email = '', $detail = '')
{
    $query = ['error' => $errorCode];
    $email = trim((string) $email);
    if ($email !== '') {
        $query['email'] = $email;
    }
    $detail = trim((string) $detail);
    if ($detail !== '') {
        $query['detail'] = substr($detail, 0, 500);
    }

    header('Location: recuperar.php?' . http_build_query($query));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        ensureMailerServiceLoaded(__DIR__);
        $email = trim($_POST['email'] ?? '');
        if (empty($email)) {
            redirectRecuperoError('campos_vacios');
        }

        $conn = getConnection();
        $user = User::buscarPorEmailParaRecupero($conn, $email);
        if (!$user) {
            redirectRecuperoError('email_no_encontrado', $email);
        }

        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        PasswordReset::crear($conn, $user->getId(), $token, $expires, $user->getEmail());

        $resetLink = BASE_URL . "/resetear.php?token=$token";
        $subject = "Recuperación de contraseña - IFTS15";
        $body = "<p>Hola,</p><p>Haz clic en el siguiente enlace para restablecer tu contraseña:</p><p><a href='$resetLink'>$resetLink</a></p><p>Este enlace expirará en 1 hora.</p>";

        $mailerClass = MAILER_SERVICE_CLASS;
        $mailer = new $mailerClass();
        $res = $mailer->send($email, $subject, $body, true);
        if ($res['success']) {
            error_log('[enviar_recupero] Recupero enviado correctamente a ' . $email);
            header('Location: recuperar.php?enviado=1');
        } else {
            $mailerError = (string) ($res['message'] ?? 'sin detalle');
            error_log('[enviar_recupero] Error enviando recupero a ' . $email . ': ' . $mailerError);
            $mailerErrorNormalized = strtolower($mailerError);
            if (strpos($mailerErrorNormalized, 'smtp') !== false || strpos($mailerErrorNormalized, 'authenticate') !== false || strpos($mailerErrorNormalized, 'connect') !== false || strpos($mailerErrorNormalized, 'recipient') !== false) {
                redirectRecuperoError('smtp_fallido', $email, $mailerError);
            }
            redirectRecuperoError('envio_fallido', $email, $mailerError);
        }
    } catch (\Throwable $e) {
        $message = (string) $e->getMessage();
        error_log('[enviar_recupero] ' . $message);
        $normalizedMessage = strtolower($message);

        if (strpos($normalizedMessage, 'password_resets') !== false || strpos($normalizedMessage, 'token') !== false || strpos($normalizedMessage, 'column') !== false || strpos($normalizedMessage, 'tabla') !== false || strpos($normalizedMessage, 'migracion') !== false) {
            redirectRecuperoError('token_fallido', $email ?? '', $message);
        }

        if (strpos($normalizedMessage, 'smtp') !== false || strpos($normalizedMessage, 'mail') !== false || strpos($normalizedMessage, 'authenticate') !== false || strpos($normalizedMessage, 'connect') !== false) {
            redirectRecuperoError('smtp_fallido', $email ?? '', $message);
        }

        redirectRecuperoError('envio_fallido', $email ?? '', $message);
    }

    exit;
}
header('Location: recuperar.php');
exit;
