<?php
declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$envPath = __DIR__;
if (file_exists($envPath . '/.env')) {
    $dotenv = Dotenv\Dotenv::createMutable($envPath);
    if (method_exists($dotenv, 'safeLoad')) {
        $dotenv->safeLoad();
    } else {
        try {
            $dotenv->load();
        } catch (Throwable $e) {
        }
    }
}

function envValue(array $keys, string $default = ''): string
{
    foreach ($keys as $key) {
        if (isset($_ENV[$key])) {
            $value = trim((string) $_ENV[$key]);
            if ($value !== '') {
                return $value;
            }
        }

        $envValue = getenv($key);
        if ($envValue !== false) {
            $value = trim((string) $envValue);
            if ($value !== '') {
                return $value;
            }
        }
    }

    return $default;
}

function envBool(array $keys, bool $default = false): bool
{
    foreach ($keys as $key) {
        if (isset($_ENV[$key])) {
            $raw = trim((string) $_ENV[$key]);
            if ($raw === '') {
                continue;
            }

            $parsed = filter_var($raw, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            return $parsed === null ? $default : $parsed;
        }

        $envValue = getenv($key);
        if ($envValue !== false) {
            $raw = trim((string) $envValue);
            if ($raw === '') {
                continue;
            }

            $parsed = filter_var($raw, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            return $parsed === null ? $default : $parsed;
        }
    }

    return $default;
}

function maskValue(string $value): string
{
    $length = strlen($value);
    if ($length <= 4) {
        return str_repeat('*', $length);
    }

    return substr($value, 0, 2) . str_repeat('*', max(0, $length - 4)) . substr($value, -2);
}

$host = envValue(['MAIL_HOST', 'MAIL_HOSTNAME'], '');
$port = envValue(['MAIL_PORT'], '587');
$username = envValue(['MAIL_USERNAME'], '');
$password = envValue(['MAIL_PASSWORD'], '');
$encryption = strtolower(envValue(['MAIL_ENCRYPTION'], 'tls'));
$smtpAuth = envBool(['MAIL_SMTPAuth', 'MAIL_SMTPAUTH', 'MAIL_SMTP_AUTH'], true);
$fromEmail = envValue(['MAIL_FROM', 'MAIL_FROM_ADDRESS', 'MAIL_USERNAME'], '');
$fromName = envValue(['MAIL_FROM_NAME'], 'IFTS15');

$defaultTo = envValue(['SMTP_TEST_TO', 'MAIL_USERNAME', 'MAIL_FROM_ADDRESS'], '');
$to = trim((string) ($_POST['to'] ?? $defaultTo));
$subject = trim((string) ($_POST['subject'] ?? 'Prueba SMTP - IFTS15'));

$result = null;
$debugLines = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $host;
        $mail->Port = (int) $port;
        $mail->SMTPAuth = $smtpAuth;
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->CharSet = 'UTF-8';
        $mail->Timeout = 30;
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->Debugoutput = static function ($str, $level) use (&$debugLines): void {
            $debugLines[] = '[' . $level . '] ' . trim((string) $str);
        };

        if ($encryption === 'ssl' || $encryption === 'smtps') {
            $mail->SMTPSecure = defined('PHPMailer\\PHPMailer\\PHPMailer::ENCRYPTION_SMTPS') ? PHPMailer::ENCRYPTION_SMTPS : 'ssl';
        } elseif ($encryption === 'none' || $encryption === '') {
            $mail->SMTPSecure = '';
        } else {
            $mail->SMTPSecure = defined('PHPMailer\\PHPMailer\\PHPMailer::ENCRYPTION_STARTTLS') ? PHPMailer::ENCRYPTION_STARTTLS : 'tls';
        }

        if ($host === '' || $username === '' || $password === '' || $fromEmail === '' || $to === '') {
            throw new RuntimeException('Faltan variables SMTP obligatorias o el destinatario de prueba.');
        }

        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = '<h2>Prueba SMTP OK</h2><p>Este correo confirma que el servidor pudo autenticarse y enviar usando la configuración actual.</p>';
        $mail->AltBody = 'Prueba SMTP OK. Este correo confirma que el servidor pudo autenticarse y enviar usando la configuración actual.';
        $mail->send();

        $result = [
            'success' => true,
            'message' => 'Correo enviado correctamente a ' . $to,
        ];
    } catch (Throwable $e) {
        $result = [
            'success' => false,
            'message' => $e->getMessage(),
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMTP Test IFTS15</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            color: #222;
            margin: 0;
            padding: 24px;
        }
        .wrap {
            max-width: 960px;
            margin: 0 auto;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 24px;
        }
        h1 {
            margin-top: 0;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 12px;
            margin-bottom: 20px;
        }
        .card {
            background: #fafafa;
            border: 1px solid #e5e5e5;
            border-radius: 6px;
            padding: 12px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 700;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #bbb;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background: #ffcc00;
            border: 1px solid #d0a800;
            color: #111;
            padding: 10px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 700;
        }
        .ok {
            background: #e9f8ee;
            border: 1px solid #7bc48f;
            color: #1b5e20;
            padding: 12px;
            border-radius: 6px;
            margin: 16px 0;
        }
        .error {
            background: #fdeeee;
            border: 1px solid #e59595;
            color: #8b1e1e;
            padding: 12px;
            border-radius: 6px;
            margin: 16px 0;
        }
        pre {
            white-space: pre-wrap;
            word-break: break-word;
            background: #111;
            color: #eee;
            padding: 16px;
            border-radius: 6px;
            overflow: auto;
        }
        .note {
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="wrap">
        <h1>Prueba SMTP IFTS15</h1>
        <p class="note">Este archivo usa la configuración actual del .env y muestra el diálogo SMTP completo. Eliminar del servidor después de probar.</p>

        <div class="grid">
            <div class="card"><strong>MAIL_HOST</strong><br><?php echo htmlspecialchars($host !== '' ? $host : '(vacío)', ENT_QUOTES, 'UTF-8'); ?></div>
            <div class="card"><strong>MAIL_PORT</strong><br><?php echo htmlspecialchars($port, ENT_QUOTES, 'UTF-8'); ?></div>
            <div class="card"><strong>MAIL_ENCRYPTION</strong><br><?php echo htmlspecialchars($encryption !== '' ? $encryption : '(vacío)', ENT_QUOTES, 'UTF-8'); ?></div>
            <div class="card"><strong>MAIL_SMTPAuth</strong><br><?php echo $smtpAuth ? 'true' : 'false'; ?></div>
            <div class="card"><strong>MAIL_USERNAME</strong><br><?php echo htmlspecialchars($username !== '' ? maskValue($username) : '(vacío)', ENT_QUOTES, 'UTF-8'); ?></div>
            <div class="card"><strong>MAIL_FROM_ADDRESS</strong><br><?php echo htmlspecialchars($fromEmail !== '' ? maskValue($fromEmail) : '(vacío)', ENT_QUOTES, 'UTF-8'); ?></div>
        </div>

        <form method="post">
            <div class="grid">
                <div>
                    <label for="to">Destinatario de prueba</label>
                    <input id="to" name="to" type="email" required value="<?php echo htmlspecialchars($to, ENT_QUOTES, 'UTF-8'); ?>">
                </div>
                <div>
                    <label for="subject">Asunto</label>
                    <input id="subject" name="subject" type="text" required value="<?php echo htmlspecialchars($subject, ENT_QUOTES, 'UTF-8'); ?>">
                </div>
            </div>
            <button type="submit">Enviar prueba SMTP</button>
        </form>

        <?php if ($result !== null): ?>
            <div class="<?php echo $result['success'] ? 'ok' : 'error'; ?>">
                <?php echo htmlspecialchars($result['message'], ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <h2>Log SMTP</h2>
            <pre><?php echo htmlspecialchars(implode(PHP_EOL, $debugLines), ENT_QUOTES, 'UTF-8'); ?></pre>
        <?php endif; ?>
    </div>
</body>
</html>