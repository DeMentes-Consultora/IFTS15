# UI y Componentes Publicos

## CSS modular

La estructura CSS vigente esta organizada en src/Css y se integra desde src/Template/head.php.

### Archivos principales

```text
src/Css/
├── styles.css
├── navbarCss.css
├── sidebarCss.css
├── footerCss.css
└── consultasCss.css

src/Template/
├── head.php
├── navBar.php
├── sidebar.php
└── footer.php
```

### Orden de carga

1. Bootstrap CSS
2. Font Awesome
3. styles.css
4. navbarCss.css
5. sidebarCss.css
6. footerCss.css
7. consultasCss.css cuando corresponde desde head.php

### Variables globales

```css
:root {
    --primary-color: #ffd700;
    --primary-dark: #e6c200;
    --secondary-color: #6c757d;
    --text-on-primary: #212529;
    --text-on-secondary: #ffffff;
    --navbar-height: 56px;
    --content-padding: 20px;
}
```

## Modal de consultas

### Ubicacion

- src/Components/modalConsultas.php
- src/Css/consultasCss.css
- src/Template/head.php
- src/Template/navBar.php
- src/Template/sidebar.php
- src/Template/footer.php

### Capacidades

- Formulario con nombre, email y consulta obligatorios.
- Campos opcionales de telefono y carrera.
- Validacion visual y manejo de estados.
- Integracion global desde sidebar y footer.

### Puntos de acceso

- Navbar para usuarios no logueados.
- Sidebar para usuarios logueados.
- Footer para todos los usuarios.
- Apertura programatica via Bootstrap Modal API.

### Estado actual

- El modal esta disponible globalmente.
- El CSS se carga desde src/Template/head.php.
- La documentacion legacy sobre layouts fue reemplazada por Template.

## Criterio de mantenimiento

- Documentar aqui cambios estructurales de UI compartida.
- Si un componente publico cambia de ubicacion o integracion, actualizar primero este archivo.