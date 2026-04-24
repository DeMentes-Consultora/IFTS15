<?php

require_once __DIR__ . '/../../vendor/autoload.php';

// Cargar variables de entorno desde .env
if (file_exists(__DIR__ . '/../../.env')) {
    $dotenv = Dotenv\Dotenv::createMutable(__DIR__ . '/../../');
    if (method_exists($dotenv, 'safeLoad')) {
        $dotenv->safeLoad();
    } else {
        try { $dotenv->load(); } catch (Throwable $e) { /* ignore */ }
    }
}

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Model/Consulta.php';
use App\Model\Consulta;
use App\ConectionBD\ConectionDB;
// Utilidad de correo
use App\Services\MailerService;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $carrera = trim($_POST['carrera'] ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');

    // Validar campos obligatorios
    if (empty($nombre) || empty($email) || empty($mensaje)) {
        $_SESSION['consultas_message'] = 'Debe completar todos los campos obligatorios (Nombre, Email y Consulta).';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Validar email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['consultas_message'] = 'Por favor ingrese un email válido.';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Crear objeto consulta
    $consulta = new Consulta($nombre, $email, $mensaje, $telefono, $carrera);

    try {
        // Obtener nombre de la carrera si se seleccionó una
        $nombreCarrera = 'Información general';
        if (!empty($consulta->getCarrera())) {
            $conectarDB = new ConectionDB();
            $conn = $conectarDB->getConnection();
            $stmt = $conn->prepare("SELECT carrera FROM carrera WHERE id_carrera = ?");
            $stmt->bind_param("i", $carrera);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $nombreCarrera = $row['carrera'];
            }
            $stmt->close();
        }

        $subject = 'Nueva consulta desde IFTS15 - ' . $nombreCarrera;
        $cuerpoMensaje = "<h3>Nueva consulta desde IFTS15</h3>";
        $cuerpoMensaje .= "<p><b>Nombre:</b> " . htmlspecialchars($consulta->getNombre()) . "</p>";
        $cuerpoMensaje .= "<p><b>Email:</b> " . htmlspecialchars($consulta->getEmail()) . "</p>";
        if (!empty($consulta->getTelefono())) {
            $cuerpoMensaje .= "<p><b>Teléfono:</b> " . htmlspecialchars($consulta->getTelefono()) . "</p>";
        }
        if (!empty($consulta->getCarrera())) {
            $cuerpoMensaje .= "<p><b>Carrera de interés:</b> " . htmlspecialchars($nombreCarrera) . "</p>";
        }
        $cuerpoMensaje .= "<p><b>Mensaje:</b></p><p>" . nl2br(htmlspecialchars($consulta->getMensaje())) . "</p>";

        // Destinatario(s): admin(s) o email configurado
        $destinatarioConfig = trim((string)($_ENV['ADMIN_EMAILS'] ?? $_ENV['ADMIN_EMAIL'] ?? $_ENV['MAIL_USERNAME'] ?? ''));
        $destinatarios = array_values(array_filter(array_map('trim', preg_split('/[,;]+/', $destinatarioConfig))));
        if (empty($destinatarios)) {
            throw new Exception('No hay destinatario configurado (ADMIN_EMAILS o MAIL_USERNAME)');
        }
        
        $mailer = new MailerService();
        $result = $mailer->send($destinatarios, $subject, $cuerpoMensaje, true, $consulta->getEmail());
        if ($result['success']) {
            $_SESSION['consultas_message'] = '¡Consulta enviada correctamente! Te responderemos a la brevedad a tu email: ' . htmlspecialchars($consulta->getEmail());
        } else {
            error_log('Error MailerService en consultas: ' . ($result['message'] ?? 'sin detalle'));
            $_SESSION['consultas_message'] = 'No se pudo enviar el mensaje. Por favor intenta nuevamente más tarde.';
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    } catch (Exception $e) {
        error_log('Excepción en consultasController: ' . $e->getMessage());
        $_SESSION['consultas_message'] = 'No se pudo enviar el mensaje. Por favor intenta nuevamente más tarde.';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
    exit;
}