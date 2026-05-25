# IFTS15 - Sistema Web Educativo

Sistema web del Instituto de Formacion Tecnica Superior N. 15 con estructura MVC en src, autenticacion, gestion academica, personalizacion publica del sitio y modulos administrativos.

## Documentacion centralizada

La documentacion humana del proyecto fue consolidada en la carpeta docs.

- docs/README.md
- docs/estado-actual.md
- docs/deploy.md
- docs/ui-y-componentes.md
- docs/historial-tecnico.md

## Punto de inicio recomendado

1. Leer docs/README.md.
2. Seguir con docs/estado-actual.md para entender la arquitectura vigente.
3. Ir a docs/deploy.md para despliegue y produccion.

## Resumen tecnico rapido

- Arquitectura MVC operativa con autoload PSR-4 desde Composer.
- Configuracion centralizada en src/config.php usando phpdotenv.
- Conexion a base de datos con MySQLi mediante App\ConectionBD\ConectionDB y wrapper App\Database.
- Login, registro por modal, consultas, perfil, administracion de usuarios, carreras, materias, novedades y personalizacion publica activos.

## Nota sobre .copilot

La carpeta .copilot sigue existiendo para contexto interno de asistentes, historial y sincronizacion de trabajo. No es la fuente principal de documentacion humana del proyecto.
