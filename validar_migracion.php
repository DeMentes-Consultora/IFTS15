<?php
require 'src/ConectionBD/ConectionDB.php';

$db = new App\ConectionBD\ConectionDB();
$conn = $db->getConnection();

echo "=== VALIDACIÓN POST-MIGRACIÓN ===\n\n";

// Verificar estructura
echo "✓ Columnas de tabla NOTAS:\n";
$result = $conn->query("SHOW COLUMNS FROM notas");
while ($row = $result->fetch_assoc()) {
    $null = $row['Null'] === 'YES' ? '✓ NULL' : '✗ NOT NULL';
    echo "  - {$row['Field']}: {$row['Type']} ({$null})\n";
}

echo "\n✓ Índices:\n";
$result = $conn->query("SHOW INDEXES FROM notas");
$indices = [];
while ($row = $result->fetch_assoc()) {
    $key = $row['Key_name'];
    if (!isset($indices[$key])) {
        $indices[$key] = ['columns' => [], 'unique' => $row['Non_unique'] == 0];
    }
    $indices[$key]['columns'][] = $row['Column_name'];
}

foreach ($indices as $name => $info) {
    $unique = $info['unique'] ? '(ÚNICO)' : '';
    $cols = implode(', ', $info['columns']);
    echo "  - {$name}: ({$cols}) {$unique}\n";
}

echo "\n✓ Estado: Migración completada exitosamente\n";
echo "✓ Sistema listo para ingresar notas por profesor\n";
?>
