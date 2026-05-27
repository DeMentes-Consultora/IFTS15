# TP Calidad - Paso 3 - Estrategia General de Calidad

## 1. Objetivo del analisis de calidad

El objetivo del analisis de calidad es evaluar el sistema IFTS15 desde una perspectiva profesional de aseguramiento de calidad, identificando fortalezas, debilidades, riesgos y oportunidades de mejora tanto en el producto como en el proceso de desarrollo.

Con este analisis se busca demostrar:

- en que medida el sistema cumple con criterios basicos de calidad de producto;
- que riesgos existen por ausencia o informalidad de practicas de calidad;
- que mejoras podrian incorporarse si el proyecto se continuara o se rehaciera con un enfoque mas profesional;
- como se puede vincular el sistema analizado con normas y marcos vistos en la materia.

## 1.1 Riesgos principales del sistema

En base a la documentacion del proyecto y a la estructura actual del repositorio, se identifican como riesgos principales:

- falta de evidencia de testing automatizado sistematico;
- dependencia de servicios externos para correo e imagenes o archivos, lo que puede afectar disponibilidad o consistencia;
- crecimiento funcional incremental con riesgo de deuda tecnica;
- posible informalidad en la definicion original de requisitos, hitos y criterios de aceptacion;
- riesgo de errores por gestion de permisos y roles en modulos sensibles;
- riesgo de fallas de despliegue o portabilidad entre entornos distintos.

## 2. Normas y marcos seleccionados

Para este trabajo se propone utilizar las siguientes normas y marcos de referencia:

### 2.1. ISO 9001:2015

Se utiliza para analizar la organizacion del trabajo, la gestion del proceso y la mejora continua. Resulta adecuada porque permite observar si el desarrollo del sistema conto con planificacion, control, revision y acciones de mejora.

### 2.2 ISO/IEC 25010

Se utiliza para evaluar la calidad del producto de software. Resulta pertinente porque IFTS15 tiene multiples funcionalidades, perfiles de usuario y aspectos observables de adecuacion funcional, usabilidad, confiabilidad, mantenibilidad y seguridad.

### 2.3. ISO/IEC 12207

Se utiliza para reconstruir y evaluar el ciclo de vida del proyecto. Es adecuada porque el sistema fue desarrollado por etapas y permite analizar si existieron requisitos, diseño, desarrollo, pruebas, implementacion y mantenimiento de manera formal o informal.

### 2.4. IEEE 730

Se utiliza como base para estructurar un mini plan de SQA y orientar el diseño de actividades de control de calidad y testing. Resulta util para formalizar un proyecto que posiblemente no tuvo un plan de QA explicito en su desarrollo original.

### 2.5. CMMI y Scrum

Se utilizan para evaluar el grado de madurez del proceso y para comparar el desarrollo real con un marco agil mas ordenado. Esto permite valorar si el equipo trabajo de manera improvisada, parcialmente organizada o con una metodologia consistente.

## 2.6. Justificacion de la seleccion de normas

Se seleccionan estas normas y marcos porque, en conjunto, cubren dos dimensiones centrales del trabajo practico:

- calidad del producto desarrollado;
- calidad del proceso con el que ese producto fue construido.

ISO 25010 permite analizar lo que el sistema hace y como se comporta. ISO 9001, ISO 12207, IEEE 730 y CMMI o Scrum permiten analizar como se trabajo, que controles faltaron y que cambios se deberian introducir para profesionalizar el desarrollo.

## 3. Alcance del analisis

El analisis de calidad tendra como alcance principal los siguientes modulos y procesos del sistema IFTS15:

- autenticacion, registro y recuperacion de contrasena;
- gestion de usuarios;
- perfil del usuario y seguimiento academico;
- gestion de carreras y materias;
- asignacion de profesores a materias;
- consultas institucionales y novedades;
- personalizacion del sitio;
- bolsa de trabajo y postulaciones.

Adicionalmente, se tomaran en cuenta aspectos transversales como:

- roles y permisos;
- validaciones de formularios;
- manejo de datos persistentes;
- dependencia de servicios externos;
- evidencia de testing y mantenimiento.

## 3.1. Fuera de alcance

Quedan fuera del alcance de este trabajo:

- auditorias de rendimiento con herramientas especializadas;
- pruebas de penetracion o analisis profundo de ciberseguridad ofensiva;
- certificacion formal de cumplimiento normativo;
- reingenieria completa del sistema;
- implementacion real de todas las mejoras propuestas.

## 4. Division de roles y plan de accion

La siguiente tabla puede completarse con los integrantes reales del grupo:

| Integrante | Rol | Tarea concreta | Entregable esperado |
| --- | --- | --- | --- |
| [Nombre 1] | Lider de QA | Coordinar el trabajo, consolidar criterios, redactar estrategia y politica de calidad | Informe de gestion de calidad y coordinacion general |
| [Nombre 2] | Analista de Calidad de Producto | Evaluar el sistema segun ISO/IEC 25010 | Tabla de evaluacion y conclusion de calidad del producto |
| [Nombre 3] | Analista de Procesos / Ciclo de Vida | Reconstruir el ciclo de vida del proyecto segun ISO/IEC 12207 | Informe de ciclo de vida y riesgos de proceso |
| [Nombre 4] | Especialista en Testing | Diseñar casos de prueba y mini plan SQA | Plan de SQA y casos de prueba funcionales |
| [Nombre 5] | Analista de Metodologia | Evaluar metodologia real y proponer enfoque CMMI o Scrum | Informe de metodologia y madurez del proceso |

Si el grupo tiene menos integrantes, una misma persona puede asumir mas de un rol, dejando constancia de ello en la tabla final.

## 5. Plan de accion grupal

Se propone el siguiente plan de trabajo:

1. Relevar la documentacion existente del sistema y consolidar una descripcion comun del proyecto.
2. Delimitar el alcance funcional y tecnico que sera evaluado.
3. Asignar roles y acordar entregables individuales.
4. Elaborar la estrategia general de calidad.
5. Desarrollar los analisis individuales por rol.
6. Realizar una revision cruzada entre integrantes.
7. Integrar todos los aportes en un informe final unificado.
8. Redactar una conclusion grupal con propuestas de mejora.

## 5.1. Cronograma sugerido

El siguiente cronograma puede adaptarse a la fecha real de entrega:

| Etapa | Actividad | Fecha estimada |
| --- | --- | --- |
| 1 | Seleccion del proyecto y recopilacion de documentacion | [Fecha] |
| 2 | Asignacion de roles y definicion del alcance | [Fecha] |
| 3 | Redaccion de estrategia general de calidad | [Fecha] |
| 4 | Desarrollo de analisis individuales | [Fecha] |
| 5 | Revision grupal y correcciones | [Fecha] |
| 6 | Integracion del informe final | [Fecha] |
| 7 | Entrega final | [Fecha] |

## 6. Evidencia base disponible

Como insumo para el trabajo, el equipo dispone actualmente de la siguiente documentacion del proyecto:

- [README.md](docs/README.md)
- [estado-actual.md](docs/estado-actual.md)
- [historial-tecnico.md](docs/historial-tecnico.md)
- [ui-y-componentes.md](docs/ui-y-componentes.md)
- [bolsa-trabajo-referencia.md](docs/bolsa-trabajo-referencia.md)
- [requerimiento-funcional-cliente.md](docs/requerimiento-funcional-cliente.md)
- [tp-calidad-mapa-documentacion.md](docs/tp-calidad-mapa-documentacion.md)

## 7. Criterio general de evaluacion interna del equipo

Para mantener consistencia entre los informes individuales, el equipo tomara como criterios comunes:

- justificar cada afirmacion con evidencia observable del sistema o de la documentacion;
- distinguir entre problemas actuales del producto y problemas del proceso de desarrollo;
- proponer mejoras realistas y acordes al contexto academico del proyecto;
- mantener coherencia entre alcance, riesgos y recomendaciones.

## 8. Conclusion

La estrategia general de calidad para IFTS15 establece un marco de trabajo comun para analizar el sistema con criterios profesionales. A partir de esta base, cada integrante puede desarrollar su rol con foco claro, evidencia compartida y un objetivo grupal consistente: determinar el nivel de calidad del sistema y definir que deberia cambiarse para mejorar tanto el producto como el proceso de desarrollo.