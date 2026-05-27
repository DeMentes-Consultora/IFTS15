# Documentacion IFTS15

Esta carpeta es la fuente de verdad para la documentacion humana del proyecto.

La estructura de este proyecto fue tomada como base para el estandar comun documentado en `../../ESTANDAR_DOCUMENTACION_PROYECTOS.md`.

## Que vive aqui

- estado-actual.md: arquitectura real, stack, estructura, configuracion y modulos vigentes.
- deploy.md: despliegue, checklist operativa, migraciones y notas de produccion.
- roles.md: resumen de roles operativos y permisos mas visibles del sistema.
- ui-y-componentes.md: documentacion funcional de CSS modular y modal de consultas.
- historial-tecnico.md: hitos, limpiezas y snapshots historicos relevantes.
- bolsa-trabajo-referencia.md: implementacion final de bolsa de trabajo, reglas de negocio, Cloudinary, CVs y checklist de portabilidad.

## Documentacion academica o historica de apoyo

- `tp-*`, `Requisitos TP.md` y `requerimiento-funcional-cliente.md` se conservan como soporte academico e historico.
- Esos archivos no reemplazan `estado-actual.md`, `deploy.md` ni `historial-tecnico.md` como fuente de verdad operativa.

## Reglas de mantenimiento

- Si una documentacion describe el estado actual del proyecto, debe actualizarse primero aqui.
- Si cambia arquitectura, despliegue o hitos tecnicos, actualizar primero `estado-actual.md`, `deploy.md` o `historial-tecnico.md` segun corresponda.
- Los Markdown de la raiz pueden quedar como puntos de entrada o compatibilidad, pero no deben divergir del contenido centralizado en docs.
- La carpeta .copilot conserva contexto interno para asistentes y trazabilidad historica; no reemplaza esta documentacion.
- Si una documentacion enumera roles, rutas, controladores o endpoints, debe contrastarse con el codigo vigente antes de darla por valida.

## Mapa rapido

1. Empezar por estado-actual.md para entender el proyecto.
2. Ir a deploy.md para subir o desplegar.
3. Ir a roles.md si la tarea toca permisos o comportamiento por perfil.
4. Ir a ui-y-componentes.md para cambios visuales o de componentes publicos.
5. Ir a historial-tecnico.md para ver decisiones y cambios relevantes de sesiones anteriores.
6. Ir a bolsa-trabajo-referencia.md si queres replicar el modulo de bolsa en otro proyecto.