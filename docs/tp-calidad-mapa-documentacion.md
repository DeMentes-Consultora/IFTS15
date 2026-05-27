# TP Calidad - Mapa de Documentacion

## Proposito

Este documento relaciona la consigna del trabajo practico de Aseguramiento de Calidad con la documentacion existente del proyecto IFTS15, para identificar que contenido ya esta cubierto y que contenido aun debe elaborarse para la entrega final.

## 1. Estado del requerimiento funcional dentro del TP

El documento [requerimiento-funcional-cliente.md](docs/requerimiento-funcional-cliente.md) sirve como parte de la documentacion del sistema y resulta util para:

- describir el objetivo general del sistema;
- detallar funcionalidades principales;
- delimitar alcance funcional;
- identificar perfiles de usuario y permisos;
- usarlo como insumo para testing, analisis ISO 25010 y estrategia general de calidad.

Conclusion: el requerimiento funcional suma valor a la entrega, pero no reemplaza por si solo ninguno de los pasos completos de la consigna.

## 2. Cruce entre consigna y documentacion actual

### Paso 1. Seleccion y descripcion del proyecto

Cubierto parcialmente con documentacion existente:

- Nombre y descripcion general del sistema: cubierto en [README.md](docs/README.md), [estado-actual.md](docs/estado-actual.md) y [requerimiento-funcional-cliente.md](docs/requerimiento-funcional-cliente.md).
- Tecnologias utilizadas: cubierto en [estado-actual.md](docs/estado-actual.md).
- Funcionalidades principales: cubierto en [requerimiento-funcional-cliente.md](docs/requerimiento-funcional-cliente.md) y [estado-actual.md](docs/estado-actual.md).
- Problemas o limitaciones del desarrollo: parcialmente cubierto en [historial-tecnico.md](docs/historial-tecnico.md) y en observaciones de modulos especificos.

Pendiente de completar manualmente:

- Materia y año en que fue desarrollado.
- Problemas recordados por el equipo durante el desarrollo original, redactados desde la experiencia del grupo.

### Paso 2. Conformacion del area de calidad

No esta cubierto por la documentacion tecnica actual.

Pendiente de completar:

- roles del equipo;
- responsabilidades por integrante;
- norma o herramienta base asociada a cada rol.

### Paso 3. Estrategia General de Calidad

Cubierto solo como insumo, no como entregable final.

La documentacion existente sirve para apoyar:

- alcance del analisis: [estado-actual.md](docs/estado-actual.md) y [requerimiento-funcional-cliente.md](docs/requerimiento-funcional-cliente.md);
- riesgos tecnicos y modulos sensibles: [historial-tecnico.md](docs/historial-tecnico.md) y [bolsa-trabajo-referencia.md](docs/bolsa-trabajo-referencia.md).

Pendiente de redactar especificamente para el TP:

- objetivo del analisis de calidad;
- normas seleccionadas y su justificacion;
- alcance del analisis como trabajo academico;
- division de roles y plan de accion;
- cronograma.

### Paso 4. Analisis individual por rol

No esta cubierto como informe formal.

La documentacion existente puede usarse como evidencia base para:

- evaluar calidad del producto;
- reconstruir parte del ciclo de vida;
- diseñar casos de prueba;
- identificar debilidades de proceso y mantenimiento.

Pero cada rol debe redactar su informe propio segun la consigna.

### Paso 5. Integracion grupal y presentacion final

No esta cubierto aun.

Pendiente de armar:

- portada;
- estrategia general de calidad;
- informes individuales por rol;
- conclusion grupal;
- reflexion sobre trabajo en equipo.

## 3. Que aporta concretamente el requerimiento funcional

El requerimiento funcional ya redactado aporta evidencia util para estas partes del TP:

- Paso 1: funcionalidades principales del sistema.
- Paso 3: alcance del analisis.
- Paso 4B: evaluacion de adecuacion funcional, usabilidad y otras caracteristicas ISO 25010.
- Paso 4D: definicion de funcionalidades a cubrir con casos de prueba.

Tambien ayuda a distinguir:

- que hace hoy el sistema;
- que perfiles existen;
- que modulos son nucleares;
- que funcionalidades quedan fuera del alcance actual.

## 4. Recomendacion para la entrega

Para que la documentacion quede alineada con la consigna, conviene presentar el trabajo con esta estructura:

1. Descripcion del proyecto.
2. Requerimiento funcional del sistema.
3. Estrategia general de calidad.
4. Analisis individual por rol.
5. Conclusion grupal.

## 5. Siguiente contenido a producir

El siguiente documento que mas conviene preparar es la Estrategia General de Calidad, porque es el entregable grupal que conecta la documentacion del sistema con los analisis individuales del TP.