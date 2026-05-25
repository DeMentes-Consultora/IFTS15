# Guia GitHub Copilot - IFTS15

## Contexto del proyecto

IFTS15 es un sistema educativo en PHP con estructura MVC operativa dentro de src. La prioridad no es una refactorizacion futura abstracta sino mantener y extender la implementacion actual sin romper el flujo publico ni administrativo.

La documentacion humana del proyecto vive en docs/. La carpeta .copilot queda para contexto interno de asistentes y trazabilidad de sesiones.

## Estado tecnico vigente

- PHP con autoload PSR-4 por Composer.
- Configuracion global en src/config.php.
- Base de datos con MySQLi usando App\ConectionBD\ConectionDB y el wrapper App\Database.
- Templates reales en src/Template.
- Modales reutilizables en src/Components.
- Servicios de negocio e integraciones en src/services.

## Archivos y superficies clave

- src/config.php: bootstrap, entorno, sesion y helpers globales.
- src/ConectionBD/ConectionDB.php: conexion MySQLi.
- src/Database.php: wrapper de consultas preparadas.
- src/Template/head.php, navBar.php, sidebar.php, footer.php: layout compartido.
- src/Components/modalLogin.php, modalRegistrar.php, modalConsultas.php: modales reutilizados por el sitio publico.
- src/Controllers/perfilController.php: acceso al perfil y acciones asociadas.
- src/services/PerfilService.php: logica de perfil, matriculacion y conceptos.
- src/services/MailerService.php: normalizacion de destinatarios y envio.
- src/services/CloudinaryService.php: validacion y subida de imagenes.

## Reglas practicas para asistir en este repo

1. Leer primero los .md de la raiz y .copilot si hace falta sincronizacion.
2. Tratar como legacy cualquier referencia a layouts, includes, config o database fuera de src, salvo que el codigo actual la siga usando de verdad.
3. Mantener el case correcto de src/services y App\Services; en Linux un error de mayusculas rompe produccion.
4. No asumir que register.php sea el flujo principal de alta: el sitio actual usa modales y componentes.
5. Si tocas mail, contemplar ADMIN_EMAILS y ADMIN_EMAIL, comas y punto y coma, y las variantes de MAIL_SMTP_AUTH.
6. Si tocas imagenes, verificar antes las variables CLOUDINARY_CLOUD_NAME, CLOUDINARY_API_KEY y CLOUDINARY_API_SECRET.
7. Si tocas el sitio publico, considerar que algunas rutas deben tolerar conexion nula para no romper la home.
8. Si un cambio afecta despliegue, sincronizar tambien la documentacion de deploy y cualquier carpeta de deploy asociada.

## Criterios tecnicos

- Preferir cambios pequenos e iterativos.
- Usar consultas preparadas y transacciones cuando el flujo lo requiera.
- Mantener separadas vista, controlador, modelo y servicio cuando ya existe esa separacion.
- No reintroducir mezcla innecesaria de HTML, SQL y logica de negocio en una misma superficie.
- Documentar solo lo que cambie el entendimiento operativo del proyecto.

## Riesgos ya conocidos

- Cloudinary falla si faltan variables de entorno.
- El envio de correo puede fallar si ADMIN_EMAILS llega como string sin normalizar.
- Procesos PHP reutilizados pueden seguir con variables viejas si no se carga bien .env.
- La home publica no debe asumir siempre una conexion valida.

## Expectativa de colaboracion

- El usuario prefiere analisis primero y cambios despues.
- Quiere feedback directo cuando una idea tecnica sea floja o arriesgada.
- Prefiere pasos concretos, no reescrituras masivas.
- Valora que la documentacion quede actualizada en la misma sesion.

## Mantenimiento de esta guia

Actualizar esta guia cuando cambie una de estas cosas:

- estructura real del proyecto
- flujo principal de autenticacion
- integraciones de correo o Cloudinary
- convenciones de despliegue
- rutas o nombres de archivos clave
