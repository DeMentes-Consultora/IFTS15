# Deploy IFTS15

## Objetivo

Desplegar la version actual del proyecto respetando la estructura real en src, el esquema correcto de base de datos y las migraciones vigentes.

## Archivos a subir

Subir a public_html el contenido necesario de la raiz:

```text
public_html/
├── .htaccess
├── .env
├── composer.json
├── composer.lock
├── index.php
├── login.php
├── logout.php
├── recuperar.php
├── resetear.php
├── procesar_reset.php
├── enviar_recupero.php
├── error404.php
├── error500.php
├── src/
└── vendor/
```

No omitir:

- src/Template
- src/Components
- src/services
- src/Public
- src/ConectionBD

## Base de datos

### Esquema base

- src/ConectionBD/ifts15.sql

### Migraciones relevantes

- src/ConectionBD/migrations/2026-04-22_profesor_materia.sql
- src/ConectionBD/migrations/2026-04-22_matricula_materia_profesor.sql
- src/ConectionBD/migrations/20260427_notas_estructura.sql
- src/ConectionBD/migrations/20260514_password_resets.sql
- src/ConectionBD/migrations/20260514_conceptos_alumno.sql
- src/ConectionBD/migrations/20260525_bolsa_trabajo.sql
- src/ConectionBD/migrations/20260525_bolsa_trabajo_postulaciones.sql

## Configuracion de .env para produccion

```env
BASE_URL=https://TU-DOMINIO.infinityfreeapp.com
DB_HOST=sqlXXX.infinityfree.com
DB_PORT=3306
DB_NAME=if0_XXXXXXXX_ifts15
DB_USERNAME=if0_XXXXXXXX
DB_PASSWORD=TU_PASSWORD
DB_CHARSET=utf8mb4
DEBUG_MODE=false
```

Si usas correo:

```env
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_correo
MAIL_PASSWORD=tu_clave_o_app_password
MAIL_ENCRYPTION=tls
ADMIN_EMAILS=correo1@dominio.com,correo2@dominio.com
```

Si usas imagenes en Cloudinary:

```env
CLOUDINARY_CLOUD_NAME=...
CLOUDINARY_API_KEY=...
CLOUDINARY_API_SECRET=...
```

La bolsa de trabajo usa Cloudinary tambien para CVs de postulacion, subidos como archivos raw.

## Pasos de despliegue

1. Crear la base de datos y guardar host, usuario, password y nombre.
2. Preparar .env con BASE_URL y credenciales reales de produccion.
3. Subir archivos por File Manager o FTP a public_html.
4. Importar src/ConectionBD/ifts15.sql.
5. Ejecutar las migraciones pendientes.
6. Verificar que vendor este completo.
7. Probar home, login, recupero, consultas, perfil y dashboard administrativo.

## Verificaciones post deploy

- La home publica carga aunque falle alguna consulta publica no critica.
- Navbar y footer encuentran correctamente assets en src/Css y src/Public.
- Login y registro por modal responden sin errores.
- Recupero de contraseña envia correo.
- Perfil actualiza foto solo si Cloudinary esta configurado.
- Conceptos y promedio se visualizan correctamente en perfil.

## Paquete historico 14-may-2026

Cambio principal:

- Conceptos por alumno con promedio persistente en perfil.

Archivos centrales del cambio:

- src/Views/perfil.php
- src/Model/ConceptoAlumno.php
- src/services/PerfilService.php
- src/Controllers/perfilController.php
- src/ConectionBD/migrations/20260514_conceptos_alumno.sql

## Problemas frecuentes

### Error 500

- Revisar .htaccess.
- Verificar que .env exista y tenga valores correctos.
- Confirmar permisos basicos de lectura.
- Confirmar que vendor y autoload.php fueron subidos.

### Error de base de datos

- Verificar DB_HOST, DB_NAME, DB_USERNAME y DB_PASSWORD.
- Confirmar importacion de src/ConectionBD/ifts15.sql.
- Confirmar que las migraciones recientes fueron aplicadas.

### Correos no salen

- Verificar MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD y MAIL_ENCRYPTION.
- Verificar ADMIN_EMAILS o ADMIN_EMAIL.
- Recordar que el proyecto normaliza multiples correos separados por coma o punto y coma.

### Imagenes no se suben

- Verificar variables de Cloudinary.
- Revisar mensajes de error en CloudinaryService.

## Checklist final

- [ ] Base de datos creada
- [ ] .env configurado para produccion
- [ ] src y vendor subidos completos
- [ ] Esquema ifts15.sql importado
- [ ] Migraciones pendientes ejecutadas
- [ ] Home publica operativa
- [ ] Login y recupero funcionando
- [ ] Consultas y correos verificados
- [ ] Perfil y subida de imagenes probados