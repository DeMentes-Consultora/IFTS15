<?php
require_once __DIR__ . '/src/config.php';
use App\Model\User;
use App\Model\PasswordReset;
use App\Services\MailerService;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    if (empty($email)) {
        header('Location: recuperar.php?error=campos_vacios');
        exit;
    }
    $conn = getConnection();
    $user = User::buscarPorEmail($conn, $email);
    if (!$user) {
        header('Location: recuperar.php?error=email_no_encontrado');
        exit;
    }
    // Generar token seguro
    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
    PasswordReset::crear($conn, $user->getId(), $token, $expires);
    $resetLink = BASE_URL . "/resetear.php?token=$token";
    $subject = "Recuperación de contraseña - IFTS15";
    $body = "<p>Hola,</p><p>Haz clic en el siguiente enlace para restablecer tu contraseña:</p><p><a href='$resetLink'>$resetLink</a></p><p>Este enlace expirará en 1 hora.</p>";
    $mailer = new MailerService();
    $res = $mailer->send($email, $subject, $body, true);
    if ($res['success']) {
     //   header('Location: recuperar.php?enviado=1'); redirige a la pagina de loguin de pagina completa
      header('Location: ' . BASE_URL . '?recupero=ok');
    } else {
        header('Location: recuperar.php?error=envio_fallido');
    }
    exit;
}
header('Location: recuperar.php');
exit;
