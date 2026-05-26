# Estado Actual de IFTS15

## Resumen

IFTS15 es un sistema web educativo en PHP con estructura MVC operativa dentro de src, autenticacion, gestion academica, personalizacion publica y modulos administrativos.

## Estado tecnico vigente

- Arquitectura MVC operativa con autoload PSR-4 desde Composer.
- Configuracion centralizada en src/config.php con phpdotenv.
- Conexion a base de datos con MySQLi mediante App\ConectionBD\ConectionDB y App\Database.
- Sitio publico con modales de login, registro y consultas.
- Modulos activos de perfil, usuarios, carreras, materias, profesor-materia, novedades y dashboard admin.
- Modulo de bolsa de trabajo con publicacion directa para roles 3 y 5, visualizacion de publicadas para alumnos y postulaciones con CV.

## Stack tecnico

- PHP 7.4+ compatible por composer; probado localmente en PHP 8.4.6.
- MySQL o MariaDB.
- Bootstrap 5.3.2, Bootstrap Icons y Font Awesome.
- PHPMailer.
- Cloudinary PHP SDK.
- vlucas/phpdotenv.
- SortableJS.

## Estructura real del proyecto

```text
IFTS15/
├── .copilot/                  # Contexto interno para asistentes
├── docs/                      # Documentacion humana centralizada
├── index.php                  # Home publica
├── login.php                  # Entrada legacy de login
├── logout.php                 # Cierre de sesion
├── recuperar.php              # Solicitud de recupero
├── resetear.php               # Formulario de nueva clave
├── procesar_reset.php         # Procesamiento del reset
├── enviar_recupero.php        # Envio de email de recupero
├── src/
│   ├── Components/            # Modales y bloques reutilizables
│   ├── ConectionBD/           # Conexion, esquema y migraciones SQL
│   ├── Controllers/           # Controladores MVC y endpoints
│   ├── Css/                   # CSS modular del sitio
│   ├── helpers/               # Helpers reutilizables
│   ├── Model/                 # Modelos de dominio y acceso a datos
│   ├── Public/                # Imagenes y utilidades front
│   ├── services/              # Servicios de mail, perfil, imagenes, Cloudinary
│   ├── Template/              # head, navBar, sidebar, footer
│   ├── Views/                 # Vistas principales
│   ├── Database.php           # Wrapper de consultas con prepared statements
│   └── config.php             # Bootstrap global y carga de entorno
├── tests/
└── vendor/
```

## Puesta en marcha local

1. Instalar dependencias.

```bash
composer install
```

2. Crear o completar .env en la raiz.

```env
BASE_URL=http://localhost/IFTS15
DB_HOST=localhost
DB_PORT=3306
DB_NAME=ifts15
DB_USERNAME=root
DB_PASSWORD=
DB_CHARSET=utf8mb4
DEBUG_MODE=true
```

3. Importar el esquema base.

```bash
mysql -u root -p ifts15 < src/ConectionBD/ifts15.sql
```

4. Aplicar migraciones pendientes segun el entorno.

- src/ConectionBD/migrations/2026-04-22_profesor_materia.sql
- src/ConectionBD/migrations/2026-04-22_matricula_materia_profesor.sql
- src/ConectionBD/migrations/20260427_notas_estructura.sql
- src/ConectionBD/migrations/20260514_password_resets.sql
- src/ConectionBD/migrations/20260514_conceptos_alumno.sql
- src/ConectionBD/migrations/20260525_bolsa_trabajo.sql
- src/ConectionBD/migrations/20260525_bolsa_trabajo_postulaciones.sql

5. Levantar el proyecto.

```bash
php -S localhost:8000
```

## Notas operativas criticas

- src/config.php usa Dotenv::createMutable.
- La home publica puede seguir operando con conexion nula si falla la BD al inicio.
- Cloudinary requiere CLOUDINARY_CLOUD_NAME, CLOUDINARY_API_KEY y CLOUDINARY_API_SECRET.
- El mail soporta ADMIN_EMAILS y ADMIN_EMAIL, y varias claves equivalentes para MAIL_SMTP_AUTH.
- En Linux hay que respetar exactamente el case de src/services y App\Services.

## Modulos principales

- Autenticacion y recupero de clave.
- Perfil de usuario con foto, matriculacion, conceptos y promedio.
- ABM de usuarios.
- ABM de carreras y materias.
- Asignacion de profesores a materias.
- Dashboard admin y personalizacion del sitio publico.
- Novedades y modal de consultas.
- Bolsa de trabajo con ofertas publicadas, activacion/desactivacion y ocultado logico.
- Postulaciones de alumnos con CV en Cloudinary, actualizacion del CV, cancelacion de la propia postulacion y tabla administrativa de postulantes.

## Referencia especifica del modulo bolsa

Para reutilizar o portar este modulo a otro proyecto, ver:

- bolsa-trabajo-referencia.md

## Rutas utiles

- index.php
- login.php
- logout.php
- src/Controllers/viewController.php
- src/Controllers/perfilController.php
- src/Controllers/usuarioController.php?action=listar
- src/Controllers/carreraController.php
- src/Controllers/profesorMateriaController.php
- src/Controllers/bolsaTrabajoController.php
- src/Model/PostulacionBolsaTrabajo.php
- src/Views/dashboard-admin.php