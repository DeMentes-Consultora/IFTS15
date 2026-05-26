<?php
require_once __DIR__ . '/src/config.php';

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

$to = trim((string) ($_POST['to'] ?? ($_GET['to'] ?? '')));
$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        ensureMailerServiceLoaded(__DIR__);
        $mailerClass = MAILER_SERVICE_CLASS;
        $mailer = new $mailerClass();
        $result = $mailer->send(
            $to,
            'Prueba MailerService - IFTS15',
            '<h2>Prueba MailerService OK</h2><p>Este correo usa exactamente el mismo servicio que el recupero de contraseña.</p>',
            true
        );
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
    <title>MailerService Test IFTS15</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; color: #222; margin: 0; padding: 24px; }
        .wrap { max-width: 760px; margin: 0 auto; background: #fff; border-radius: 10px; padding: 24px; box-shadow: 0 8px 24px rgba(0,0,0,.08); }
        h1 { margin-top: 0; }
        label { display: block; margin-bottom: 8px; font-weight: 700; }
        input[type="email"] { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px; box-sizing: border-box; }
        button { margin-top: 16px; padding: 12px 18px; border: 0; border-radius: 8px; background: #0d6efd; color: #fff; cursor: pointer; }
        .result { margin-top: 20px; padding: 16px; border-radius: 8px; }
        .ok { background: #e9f7ef; color: #146c43; }
        .error { background: #fdeaea; color: #b02a37; }
        .note { color: #555; margin-bottom: 18px; }
        code { font-family: Consolas, monospace; }
    </style>
</head>
<body>
    <div class="wrap">
        <h1>Prueba MailerService IFTS15</h1>
        <p class="note">Este archivo usa el mismo <code>MailerService::send()</code> que el recupero. Si falla, el mensaje mostrado es el que devuelve el servicio real.</p>

        <form method="post">
            <label for="to">Destinatario</label>
            <input type="email" id="to" name="to" value="<?php echo htmlspecialchars($to, ENT_QUOTES, 'UTF-8'); ?>" required>
            <button type="submit">Enviar prueba</button>
        </form>

        <?php if (is_array($result)): ?>
            <div class="result <?php echo !empty($result['success']) ? 'ok' : 'error'; ?>">
                <strong><?php echo !empty($result['success']) ? 'Resultado OK' : 'Resultado con error'; ?></strong>
                <p><?php echo htmlspecialchars((string) ($result['message'] ?? 'Sin detalle'), ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>