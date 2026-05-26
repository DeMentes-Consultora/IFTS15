# Documentacion IFTS15

Esta carpeta es la fuente de verdad para la documentacion humana del proyecto.

## Que vive aqui

- estado-actual.md: arquitectura real, stack, estructura, configuracion y modulos vigentes.
- deploy.md: despliegue, checklist operativa, migraciones y notas de produccion.
- ui-y-componentes.md: documentacion funcional de CSS modular y modal de consultas.
- historial-tecnico.md: hitos, limpiezas y snapshots historicos relevantes.
- bolsa-trabajo-referencia.md: implementacion final de bolsa de trabajo, reglas de negocio, Cloudinary, CVs y checklist de portabilidad.

## Reglas de mantenimiento

- Si una documentacion describe el estado actual del proyecto, debe actualizarse primero aqui.
- Los Markdown de la raiz pueden quedar como puntos de entrada o compatibilidad, pero no deben divergir del contenido centralizado en docs.
- La carpeta .copilot conserva contexto interno para asistentes y trazabilidad historica; no reemplaza esta documentacion.

## Mapa rapido

1. Empezar por estado-actual.md para entender el proyecto.
2. Ir a deploy.md para subir o desplegar.
3. Ir a ui-y-componentes.md para cambios visuales o de componentes publicos.
4. Ir a historial-tecnico.md para ver decisiones y cambios relevantes de sesiones anteriores.
5. Ir a bolsa-trabajo-referencia.md si queres replicar el modulo de bolsa en otro proyecto.