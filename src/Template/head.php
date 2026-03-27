<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Meta tags adicionales -->
    <meta name="description" content="IFTS15 - Instituto de Formación Técnica Superior N° 15. Sistema de gestión educativa.">
    <meta name="keywords" content="IFTS15, instituto, educación, técnica, superior, Buenos Aires">
    <meta name="author" content="IFTS15 Team">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>/src/Public/images/logo_solo_circulo.ico">
    
    <!-- Bootstrap 5.3.2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" 
          crossorigin="anonymous">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- SortableJS para Drag & Drop -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.1/Sortable.min.js"></script>
    
    <!-- Font Awesome 6.4.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
          integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" 
          crossorigin="anonymous" referrerpolicy="no-referrer">
    

            <!-- CSS personalizado -->
            <link rel="stylesheet" href="<?php echo BASE_URL; ?>/src/Css/styles.css">

            <!-- CSS modulares -->
            <link rel="stylesheet" href="<?php echo BASE_URL; ?>/src/Css/navbarCss.css">
            <link rel="stylesheet" href="<?php echo BASE_URL; ?>/src/Css/sidebarCss.css">
            <link rel="stylesheet" href="<?php echo BASE_URL; ?>/src/Css/footerCss.css">
            <link rel="stylesheet" href="<?php echo BASE_URL; ?>/src/Css/consultasCss.css">
            <link rel="stylesheet" href="<?php echo BASE_URL; ?>/src/Css/carruselCss.css">

            <!-- CSS exclusivo para perfil de usuario -->
            <?php if (isset($esPerfilUsuario) && $esPerfilUsuario): ?>
                  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/src/Css/perfilUsuario.css">
            <?php endif; ?>

    
    <title>IFTS15 - Instituto de Formación Técnica Superior</title>
</head>

<body class="d-flex flex-column min-vh-100">