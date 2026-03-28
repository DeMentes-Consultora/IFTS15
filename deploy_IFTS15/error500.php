<?php
/**
 * Página de Error 500 - IFTS15
 */
$pageTitle = 'Error del Servidor';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - IFTS15</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6 text-center">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <h1 class="display-1 text-danger">500</h1>
                        <h2 class="mb-3">Error del Servidor</h2>
                        <p class="lead">Ocurrió un error interno. Por favor, inténtalo más tarde.</p>
                        <div class="mt-4">
                            <a href="<?php echo $_SERVER['REQUEST_SCHEME']; ?>://<?php echo $_SERVER['HTTP_HOST']; ?>/" class="btn btn-primary">
                                <i class="fa fa-home"></i> Volver al Inicio
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
