# Historial Tecnico

## Alcance

Este archivo concentra hitos tecnicos y snapshots historicos que antes estaban dispersos en varios Markdown de la raiz.

## 1 de diciembre de 2025 - Limpieza de codigo

### Acciones destacadas

- Eliminacion de archivos vacios o sin uso.
- Limpieza de debugging en AuthController y vistas/componentes JS.
- Mejora de PHPDoc en modelos y vistas.
- Mantenimiento de logs solo para errores criticos.

### Impacto

- Codigo mas limpio y mantenible.
- Menor ruido de debug en produccion.
- Mejor base documental para continuar el desarrollo.

## 14 de mayo de 2026 - Perfil, conceptos y promedio

### Cambio funcional

- Se implemento la columna Concepto con modal editable.
- El promedio de conceptos se calcula y persiste.
- El boton asociado quedo reducido a icono y badge amarillo institucional.

### Superficies involucradas

- src/Views/perfil.php
- src/Model/ConceptoAlumno.php
- src/services/PerfilService.php
- src/Controllers/perfilController.php
- src/ConectionBD/migrations/20260514_conceptos_alumno.sql

## 25 de mayo de 2026 - Bolsa de trabajo inicial

### Cambio funcional

- Se implemento una primera version de bolsa de trabajo en IFTS15.
- Los roles 3 y 5 pueden crear, publicar, rechazar y enviar nuevamente a pendientes las ofertas.
- Los alumnos pueden visualizar las ofertas publicadas desde una vista dedicada.
- Los alumnos pueden postularse con CV y cancelar su propia postulacion.

### Superficies involucradas

- src/Model/BolsaTrabajo.php
- src/Model/PostulacionBolsaTrabajo.php
- src/Controllers/bolsaTrabajoController.php
- src/Views/bolsa-trabajo.php
- src/ConectionBD/migrations/20260525_bolsa_trabajo.sql
- src/ConectionBD/migrations/20260525_bolsa_trabajo_postulaciones.sql
- src/config.php
- src/Controllers/viewController.php
- src/Template/sidebar.php

### Extension del modelo de datos

- Se agrego una migracion separada para postulaciones de alumnos.
- La tabla permite una sola postulacion activa por alumno y por oferta.
- Se implemento el almacenamiento de cv_url y cv_public_id para la postulacion.

## Hitos operativos relevantes

- Se corrigio compatibilidad Linux/Windows por case-sensitive en services y namespaces.
- Se endurecio la validacion de Cloudinary para errores de configuracion faltante.
- Se normalizo el envio de emails a admins y variantes SMTP.
- La home publica quedo tolerante a conexion nula en caso de falla inicial de BD.

## Relacion con .copilot

- El detalle cronologico fino sigue viviendo en .copilot/conversation-history.md.
- Este archivo resume hitos tecnicos para lectura humana general.