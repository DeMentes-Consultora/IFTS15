# Bolsa de Trabajo - Referencia de Implementacion

## Objetivo

Este documento resume la implementacion final de la bolsa de trabajo en IFTS15 para reutilizarla como referencia en otros proyectos PHP con estructura MVC similar.

El modulo toma ideas de ComunidadIFTS, pero en IFTS15 se adapto a una logica mas directa:

- No existe una etapa de revision previa para publicar ofertas.
- Los roles de gestion publican directamente.
- Los alumnos pueden postularse con CV, actualizar ese CV y cancelar su postulacion.
- La misma fila de postulacion se reutiliza para la misma relacion usuario + oferta.

## Regla de negocio final

### Roles

- Rol 1: alumno. Puede ver ofertas publicadas, postularse, descargar su CV cargado, actualizarlo y cancelar su propia postulacion.
- Rol 3: gestion. Puede crear ofertas, activar/desactivar, ocultar y ver postulantes.
- Rol 5: administracion. Tiene las mismas capacidades operativas de bolsa que rol 3.

### Acceso

- `canAccessBolsaTrabajo()` habilita la vista para roles 1, 3 y 5.
- `canManageBolsaTrabajo()` habilita gestion para roles 3 y 5.

### Menu

- Para alumno se muestra la entrada de bolsa de trabajo como acceso directo.
- Para roles 3 y 5 se muestra el bloque `Gestion Bolsa de Trabajo` por encima de `Gestion Instituto`.
- Las subsecciones administrativas son:
  - `ofertas-laborales`
  - `postulaciones`

## Estructura de datos

### Tabla `bolsa_trabajo`

Representa la oferta laboral.

Campos funcionales relevantes:

- `id_bolsa_trabajo`
- `id_usuario`
- `titulo_oferta`
- `texto_oferta`
- `habilitado`
- `cancelado`

Semantica:

- `habilitado = 1` y `cancelado = 0`: oferta visible para alumnos.
- `habilitado = 0` y `cancelado = 0`: oferta inactiva.
- `cancelado = 1`: oferta oculta logicamente.

### Tabla `postulacion_bolsa_trabajo`

Representa la postulacion de un usuario a una oferta.

Campos funcionales relevantes:

- `id_postulacion_bolsa_trabajo`
- `id_bolsa_trabajo`
- `id_usuario`
- `cv_url`
- `cv_public_id`
- `cancelado`

Restriccion importante:

- Existe una clave unica por `id_bolsa_trabajo` + `id_usuario`.

Esto define el modelo correcto:

- Una fila representa la relacion entre un usuario y una oferta.
- Si esa relacion se cancela, no se crea otra fila nueva para la misma dupla.
- Si el alumno vuelve a postularse a la misma oferta, se reutiliza la misma fila con `UPDATE`.

## Flujo funcional

### Publicacion de ofertas

1. Rol 3 o 5 crea una oferta.
2. La oferta se inserta ya publicada (`habilitado = 1`, `cancelado = 0`).
3. Desde la grilla administrativa puede:
   - activar
   - desactivar
   - ocultar logicamente

### Postulacion de alumno

1. El alumno entra a una oferta publicada.
2. Sube un CV PDF, DOC o DOCX.
3. El backend valida extension, MIME y tamano.
4. El CV se sube a Cloudinary como archivo `raw`.
5. Se guarda en BD:
   - `cv_url`: URL cruda del recurso en Cloudinary
   - `cv_public_id`: public id del archivo raw
6. El alumno recibe mail de confirmacion.

### Cancelacion de postulacion

1. El alumno cancela su postulacion.
2. La fila no se elimina.
3. Se borra el archivo en Cloudinary.
4. La fila queda asi:
   - `cancelado = 1`
   - `cv_url = NULL`
   - `cv_public_id = NULL`

Resultado:

- La relacion historica usuario + oferta se conserva.
- La fila queda limpia y reutilizable.
- No quedan referencias colgadas a un archivo ya borrado.

### Re-postulacion a la misma oferta

1. El backend busca si ya existe una fila para ese usuario y esa oferta.
2. Si existe y esta cancelada, no crea una nueva.
3. Reactiva la misma fila con:
   - `cancelado = 0`
   - nuevo `cv_url`
   - nuevo `cv_public_id`

### Actualizacion de CV sobre una postulacion activa

1. El alumno usa la accion `Actualizar CV` desde `Mis postulaciones`.
2. Se abre la misma modal reutilizada, pero en modo actualizacion.
3. El sistema sube el nuevo CV.
4. Actualiza `cv_url` y `cv_public_id` en la misma fila activa.
5. Elimina en Cloudinary el archivo anterior si era distinto.

## Flujo visual final del alumno

En la tabla `Mis postulaciones` cada fila expone tres acciones en una misma linea:

- Descargar CV
- Actualizar o volver a subir CV
- Cancelar postulacion

Se implemento en formato compacto con iconos para evitar botones apilados verticalmente.

Ademas, en la version final:

- los botones tienen fondo pintado para destacar mejor cada accion
- se usan tooltips de Bootstrap para reforzar el significado de cada icono
- las acciones del alumno quedan resueltas sin cambiar de pantalla

## Cloudinary para CVs

### Tipo de recurso

Los CVs se suben como `raw`, no como imagen.

### Requisitos de entorno

```env
CLOUDINARY_CLOUD_NAME=...
CLOUDINARY_API_KEY=...
CLOUDINARY_API_SECRET=...
```

### Decisiones tecnicas clave

- El archivo se sube con nombre normalizado y extension real.
- El `public_id` se genera sin duplicar la carpeta cuando tambien se envia `folder`.
- El archivo anterior se elimina de Cloudinary al cancelar o reemplazar.
- En base se guarda la URL cruda del recurso, no la URL transformada para descarga.

### Descarga correcta

La URL de descarga no debe guardarse como dato persistido.

Modelo correcto:

- Persistir `cv_url` cruda.
- Persistir `cv_public_id`.
- Construir `cv_download_url` al listar usando `fl_attachment`.

Esto evita:

- URLs encadenadas con transformaciones previas.
- nombres de archivo erraticos
- enlaces que abren una pagina de Cloudinary en vez de descargar el CV

Implementacion final recomendada:

- guardar en BD la `secure_url` cruda del archivo
- derivar `cv_download_url` al listar
- no persistir la URL de descarga transformada
- no forzar `target="_blank"` en el enlace de descarga del alumno

## Superficies principales del modulo

### Backend

- `src/config.php`
- `src/Controllers/viewController.php`
- `src/Controllers/bolsaTrabajoController.php`
- `src/Model/BolsaTrabajo.php`
- `src/Model/PostulacionBolsaTrabajo.php`
- `src/services/CloudinaryService.php`
- `src/services/MailerService.php`

### Frontend

- `src/Views/bolsa-trabajo.php`
- `src/Template/sidebar.php`

### Base de datos

- `src/ConectionBD/migrations/20260525_bolsa_trabajo.sql`
- `src/ConectionBD/migrations/20260525_bolsa_trabajo_postulaciones.sql`

### Deploy

- `deploy_IFTS15/src/Controllers/bolsaTrabajoController.php`
- `deploy_IFTS15/src/Model/BolsaTrabajo.php`
- `deploy_IFTS15/src/Model/PostulacionBolsaTrabajo.php`
- `deploy_IFTS15/src/Views/bolsa-trabajo.php`
- `deploy_IFTS15/src/Template/sidebar.php`
- `deploy_IFTS15/src/services/CloudinaryService.php`

## Endpoints y acciones relevantes

Archivo central:

- `src/Controllers/bolsaTrabajoController.php`

Acciones principales:

- `listar-publicadas`
- `listar-pendientes`
- `listar-postulaciones-gestion`
- `resumen`
- `crear`
- `gestionar`
- `listar-mis-postulaciones`
- `postularse`
- `actualizar-cv-postulacion`
- `cancelar-postulacion`

## Validaciones implementadas

### CV

- extensiones permitidas: PDF, DOC, DOCX
- validacion MIME
- tamano maximo funcional: 5 MB
- chequeo de limites efectivos del entorno PHP

### Permisos

- alumno solo puede operar sus propias postulaciones
- gestion solo puede operar ofertas y ver postulantes
- el controlador responde con JSON y codigos HTTP acordes

## Criterios de reutilizacion en otros proyectos

Este modulo se puede portar bien si el proyecto destino tambien tiene:

- autenticacion por sesion
- roles diferenciados
- estructura MVC o similar
- modelo `usuario`
- acceso a Cloudinary o almacenamiento equivalente

### Checklist de portabilidad

1. Crear helpers de permisos equivalentes a `canAccessBolsaTrabajo()` y `canManageBolsaTrabajo()`.
2. Crear tablas `bolsa_trabajo` y `postulacion_bolsa_trabajo` con restriccion unica por oferta + usuario.
3. Implementar soft delete / cancelacion logica en ambas tablas.
4. Subir CVs en almacenamiento de archivos y conservar `public_id` o clave equivalente.
5. Guardar la URL cruda del archivo y derivar la URL de descarga al listar.
6. Reutilizar la misma fila de postulacion para la misma dupla usuario + oferta.
7. Al cancelar, limpiar los campos del archivo en la fila.
8. Al reemplazar CV, borrar el archivo anterior del storage.
9. Mantener separadas las vistas de:
   - ofertas laborales
   - postulaciones
10. Validar el flujo completo con un alumno real y un rol de gestion.

## Errores y lecciones aprendidas

### 1. No copiar deploy a ciegas

- Hubo casos donde la carpeta deploy quedo desincronizada pese a comandos sin error visible.
- Leccion: verificar contenido real del deploy, no asumir que la copia salio bien.

### 2. No guardar URLs de descarga transformadas como dato persistido

- Guardar una URL ya transformada con `fl_attachment` llevo a enlaces invalidos o nombres incorrectos.
- Leccion: persistir URL cruda + public id, y construir la descarga al vuelo.

### 3. No dejar archivos huerfanos en Cloudinary

- Si se reemplaza un CV y no se elimina el viejo, Cloudinary acumula basura.
- Leccion: borrar el archivo anterior en cancelacion y reemplazo.

### 4. No reciclar filas de otras relaciones

- Reutilizar filas vacias para otro usuario o para otra oferta no es un buen modelo.
- Leccion: solo reutilizar la misma fila cuando sigue representando la misma relacion logica usuario + oferta.

## Recomendacion final

Si vas a replicar este modulo en otro proyecto, tomar como base primero:

1. modelo de datos
2. reglas de roles
3. ciclo de vida del CV
4. render de alumno con acciones compactas

La parte visual se adapta facil. Lo importante es no perder el contrato entre BD, almacenamiento de archivos y reglas de reutilizacion de la postulacion.