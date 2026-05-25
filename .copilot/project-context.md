# IFTS15 - Contexto del Proyecto

## Actualizacion: 25 de mayo de 2026

### Estado actual verificado
- El proyecto ya corre con estructura MVC dentro de src.
- La documentacion humana vigente esta centralizada en docs/.
- El bootstrap central esta en src/config.php.
- La conexion real usa MySQLi mediante App\ConectionBD\ConectionDB y App\Database.
- El sitio publico arranca desde index.php y reutiliza src/Template/head.php, navBar.php, sidebar.php y footer.php.
- El registro y login del sitio publico viven principalmente en modales/componentes, no en una pagina register.php dedicada.

### Correcciones recientes consolidadas
- Se corrigio compatibilidad Linux/Windows por mayusculas/minusculas en rutas y namespaces de services.
- PerfilService y CloudinaryService quedaron alineados con src/services y el autoload.
- Se endurecio la validacion de Cloudinary para informar faltantes de configuracion.
- MailerService normaliza ADMIN_EMAILS, soporta variantes de configuracion SMTP y se sincronizo con deploy.
- La home publica puede seguir operando aunque falle la conexion inicial, usando fallback con conexion nula.
- Se implemento la funcionalidad de conceptos por alumno con promedio persistente.

## Estructura real del proyecto

```text
IFTS15/
├── .copilot/
├── index.php
├── login.php
├── logout.php
├── recuperar.php
├── resetear.php
├── procesar_reset.php
├── enviar_recupero.php
├── src/
│   ├── Components/
│   ├── ConectionBD/
│   │   ├── ConectionDB.php
│   │   ├── ifts15.sql
│   │   └── migrations/
│   ├── Controllers/
│   ├── Css/
│   ├── helpers/
│   ├── Model/
│   ├── Public/
│   ├── services/
│   ├── Template/
│   ├── Views/
│   ├── Database.php
│   └── config.php
├── tests/
└── vendor/
```

## Modulos funcionales vigentes

### Autenticacion y cuentas
- Login y logout.
- Registro desde modal.
- Recupero y reseteo de contraseña.
- Roles en sesion con helpers en src/config.php.

### Gestion academica
- ABM de usuarios.
- ABM de carreras y materias.
- Asignacion profesor-materia.
- Gestion de notas, matriculacion y conceptos dentro de perfil.

### Sitio publico y experiencia
- Home publica con carrusel configurable.
- Navbar, sidebar y footer desacoplados en Template.
- Modal de consultas reutilizable.
- Dashboard de personalizacion publica.

## Base de datos

### Tablas principales
- persona
- usuario
- roles
- carrera
- comision
- añocursada
- materia
- profesor_materia
- password_resets
- conceptos_alumno

### Migraciones recientes a considerar
- src/ConectionBD/migrations/2026-04-22_profesor_materia.sql
- src/ConectionBD/migrations/2026-04-22_matricula_materia_profesor.sql
- src/ConectionBD/migrations/20260427_notas_estructura.sql
- src/ConectionBD/migrations/20260514_password_resets.sql
- src/ConectionBD/migrations/20260514_conceptos_alumno.sql

## Configuracion de desarrollo

### Variables de entorno base
- DEBUG_MODE=true
- BASE_URL=http://localhost/IFTS15 o http://localhost:8000 segun entorno
- DB_HOST=localhost
- DB_PORT=3306
- DB_NAME=ifts15
- DB_USERNAME=root
- DB_PASSWORD=
- DB_CHARSET=utf8mb4

### Variables sensibles segun modulo
- Cloudinary: CLOUDINARY_CLOUD_NAME, CLOUDINARY_API_KEY, CLOUDINARY_API_SECRET.
- Mail: MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD, MAIL_ENCRYPTION, ADMIN_EMAILS o ADMIN_EMAIL.

### Comando de desarrollo comun
```bash
php -S localhost:8000
```

## Riesgos conocidos y criterios de trabajo

- No reintroducir referencias a carpetas legacy como layouts, includes, config o database fuera de src si el flujo actual ya paso a MVC.
- Mantener sincronizados src y cualquier paquete de deploy cuando un cambio afecte mail, perfil o config.
- En Linux, respetar exactamente el case de src/services y App\Services.
- Si una vista publica depende de BD, contemplar el fallback con conexion nula antes de agregar consultas directas.

## Fuente de verdad

Este archivo reemplaza la descripcion previa de 2025 que mezclaba estructura legacy con plan de refactor. Para documentacion humana general, priorizar docs/. Para contexto interno de asistentes, actualizar este archivo en la misma sesion.
