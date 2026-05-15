# DEPLOYMENT IFTS15 (14-may-2026)

## Cambios recientes
- Columna "Concepto" con modal editable, promedio y guardado persistente.
- Visual: solo icono y badge amarillo en botón.
- Corrección: promedio y conceptos visibles tras recarga.

## Archivos a subir
- src/Views/perfil.php
- src/Model/ConceptoAlumno.php
- src/services/PerfilService.php
- src/Controllers/perfilController.php
- src/ConectionBD/migrations/20260514_conceptos_alumno.sql
- .copilot/conversation-history.md
- .copilot/copilot-guide.md
- .copilot/development-preferences.md
- .copilot/project-context.md

## Pasos
1. Subir todos los archivos listados.
2. Ejecutar la migración SQL.
3. Probar la funcionalidad de conceptos y promedio.
