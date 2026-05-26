<?php
require_once __DIR__ . '/src/config.php';

$serviceLower = __DIR__ . '/src/services/MailerService.php';
$serviceUpper = __DIR__ . '/src/Services/MailerService.php';
$className = 'App\\Services\\MailerService';

$result = [
    'script' => __FILE__,
    'time' => date('Y-m-d H:i:s'),
    'lower_exists' => file_exists($serviceLower),
    'upper_exists' => file_exists($serviceUpper),
    'class_before' => class_exists($className),
];

$errors = [];

foreach ([$serviceLower, $serviceUpper] as $candidate) {
    if (!file_exists($candidate)) {
        continue;
    }

    try {
        require_once $candidate;
    } catch (Throwable $e) {
        $errors[] = basename($candidate) . ': ' . $e->getMessage();
    }
}

$result['class_after'] = class_exists($className);
$result['errors'] = $errors;

header('Content-Type: text/plain; charset=UTF-8');
echo "mailer_autoload_check\n";
echo 'script=' . $result['script'] . "\n";
echo 'time=' . $result['time'] . "\n";
echo 'src/services/MailerService.php=' . ($result['lower_exists'] ? 'YES' : 'NO') . "\n";
echo 'src/Services/MailerService.php=' . ($result['upper_exists'] ? 'YES' : 'NO') . "\n";
echo 'class_before=' . ($result['class_before'] ? 'YES' : 'NO') . "\n";
echo 'class_after=' . ($result['class_after'] ? 'YES' : 'NO') . "\n";
if (!empty($result['errors'])) {
    echo 'errors=' . implode(' | ', $result['errors']) . "\n";
}
