# DEPLOYMENT IFTS15

## Cambios recientes (14-may-2026)
- Agregada columna "Concepto" en la tabla de alumnos por materia (antes de P1).
- Modal para editar hasta 5 conceptos/nota por alumno/materia, con guardado persistente y promedio calculado.
- El promedio de conceptos se muestra en la tabla y se actualiza automáticamente.
- Mejoras visuales: botón solo icono y promedio, color amarillo institucional.

## Pasos para deploy
1. Subir archivos modificados y nuevos al servidor (ver lista abajo).
2. Ejecutar la migración SQL `src/ConectionBD/migrations/20260514_conceptos_alumno.sql` en la base de datos.
3. Verificar permisos de escritura en carpetas necesarias (logs, uploads si aplica).
4. Limpiar caché del navegador y del servidor si corresponde.
5. Probar funcionalidad de conceptos y promedio en el entorno productivo.

## Archivos modificados/creados para subir
- src/Views/perfil.php
- src/Model/ConceptoAlumno.php
- src/services/PerfilService.php
- src/Controllers/perfilController.php
- src/ConectionBD/migrations/20260514_conceptos_alumno.sql

## Notas
- No olvides respaldar la base de datos antes de aplicar la migración.
- Si usas composer, correr `composer install` si hay cambios en dependencias.
