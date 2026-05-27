# Trabajo Practico Integrador - Aseguramiento de Calidad de Software

## Analisis de calidad del sistema IFTS15

### Portada

- Sistema analizado: IFTS15 - Sistema Web Educativo.
- Materia: [Completar].
- Año de cursada: [Completar].
- Integrantes: [Completar].
- Roles asignados: [Completar].
- Fecha de entrega: [Completar].

## 1. Introduccion

El presente trabajo practico tiene por finalidad analizar el sistema IFTS15 desde la perspectiva del aseguramiento de calidad del software. Para ello, el grupo adopta el rol de un area de calidad y aplica marcos conceptuales y normas trabajadas en la materia con el objetivo de evaluar tanto el producto desarrollado como el proceso que hizo posible su construccion.

Este enfoque supone una revision integral del sistema: no solo interesa verificar que el software funcione, sino tambien determinar en que medida responde a criterios de calidad, que debilidades presenta, cuales son sus principales riesgos y que mejoras deberian incorporarse si el proyecto continuara evolucionando en un contexto mas profesional.

## 2. Descripcion del proyecto analizado

### 2.1. Nombre del sistema

IFTS15 - Sistema Web Educativo.

### 2.2. Descripcion general

IFTS15 es una aplicacion web orientada a centralizar funciones institucionales, academicas y administrativas de un instituto de formacion tecnica superior. El sistema combina una parte publica, accesible para visitantes, con una parte privada destinada a alumnos, profesores y perfiles de gestion.

Desde el punto de vista funcional, el sistema permite mostrar informacion institucional, registrar y autenticar usuarios, administrar carreras y materias, gestionar perfiles academicos, publicar novedades, recibir consultas y operar una bolsa de trabajo con postulaciones de alumnos.

Se trata de un desarrollo realizado en PHP con arquitectura MVC y persistencia en MySQL o MariaDB, complementado por librerias de interfaz, envio de correo y almacenamiento externo de archivos.

### 2.3. Materia y año de desarrollo

- Materia original del proyecto: [Completar].
- Año de desarrollo original: [Completar].

### 2.4. Tecnologias utilizadas

Las tecnologias identificadas en el proyecto son las siguientes:

- PHP como lenguaje principal de desarrollo.
- Arquitectura MVC.
- MySQL o MariaDB como motor de base de datos.
- Composer para la gestion de dependencias.
- Bootstrap 5.3.2, Bootstrap Icons y Font Awesome para la capa de presentacion.
- vlucas/phpdotenv para la configuracion por entorno.
- PHPMailer para el envio de correos.
- Cloudinary para almacenamiento de imagenes y archivos.
- SortableJS como libreria auxiliar de interfaz.

### 2.5. Funcionalidades principales

El sistema implementa, entre otras, las siguientes funcionalidades:

- pagina principal publica con informacion institucional;
- registro e inicio de sesion de usuarios;
- recuperacion y restablecimiento de contrasena;
- gestion del perfil del usuario;
- visualizacion de matriculacion, notas, conceptos y promedio;
- administracion de usuarios;
- gestion de carreras y materias;
- asignacion de profesores a materias;
- publicacion de novedades y recepcion de consultas;
- personalizacion de elementos visuales del sitio;
- bolsa de trabajo con ofertas laborales y postulaciones.

### 2.6. Problemas o limitaciones identificados

En base a la documentacion tecnica disponible y al historial del proyecto, pueden señalarse los siguientes aspectos como limitaciones o puntos de mejora:

- crecimiento incremental del sistema sin una estrategia formal de calidad desde el inicio;
- consolidacion documental posterior al desarrollo inicial;
- necesidad de limpieza de codigo y reduccion de debugging residual;
- dependencia de servicios externos para correo y almacenamiento;
- diferencias de comportamiento potenciales entre entornos Windows y Linux;
- falta visible de una bateria amplia de pruebas automatizadas;
- incremento de complejidad por incorporacion progresiva de modulos nuevos.

### 2.7. Justificacion de la seleccion del proyecto

El sistema IFTS15 resulta adecuado para este trabajo practico porque presenta un alcance funcional suficiente para aplicar criterios de calidad del producto y del proceso. Ademas, combina multiples perfiles de usuario, reglas de permisos, persistencia de datos, interfaz web y dependencias externas, lo cual lo convierte en un caso pertinente para un analisis integral desde el area de calidad.

## 3. Requerimiento funcional del sistema

Como parte de la documentacion del proyecto, se elaboro un requerimiento funcional que sistematiza las capacidades observables del sistema desde una perspectiva de cliente. Ese requerimiento permite identificar el alcance funcional vigente, los perfiles de usuario involucrados y las operaciones principales del producto.

En sintesis, el sistema debe:

- brindar informacion institucional publica;
- permitir registro, autenticacion y recuperacion de acceso;
- ofrecer perfiles diferenciados para alumno, profesor y gestion;
- administrar usuarios, carreras, materias y asignaciones docentes;
- facilitar el seguimiento academico;
- publicar novedades y recibir consultas;
- operar una bolsa de trabajo institucional;
- permitir la personalizacion de sectores visibles del sitio.

Este apartado puede complementarse con el documento [requerimiento-funcional-cliente.md](docs/requerimiento-funcional-cliente.md), el cual funciona como anexo del presente informe.

## 4. Estrategia General de Calidad

### 4.1. Objetivo del analisis de calidad

El objetivo del analisis consiste en evaluar el sistema IFTS15 con criterios profesionales de aseguramiento de calidad, identificando fortalezas, debilidades, riesgos y oportunidades de mejora tanto en el producto como en el proceso de desarrollo.

De manera especifica, se busca:

- determinar en que medida el sistema satisface criterios de calidad del producto;
- identificar riesgos asociados a practicas informales de desarrollo;
- vincular el analisis con normas y marcos vistos en la materia;
- proponer mejoras realistas para futuras iteraciones del proyecto.

### 4.2. Riesgos principales del sistema

Se identifican como riesgos principales:

- falta de evidencia de testing automatizado sistematico;
- dependencia de servicios externos para correo y almacenamiento;
- crecimiento funcional incremental con riesgo de deuda tecnica;
- posible informalidad en la definicion de requisitos y hitos;
- riesgo de errores asociados a gestion de permisos y roles;
- fallas de despliegue o portabilidad entre entornos distintos.

### 4.3. Normas y marcos seleccionados

Para el analisis se seleccionan los siguientes marcos:

- ISO 9001:2015, para examinar organizacion del trabajo, gestion del proceso y mejora continua.
- ISO/IEC 25010, para evaluar la calidad del producto de software.
- ISO/IEC 12207, para reconstruir y comparar el ciclo de vida del proyecto.
- IEEE 730, para estructurar un mini plan de SQA.
- CMMI y Scrum, para evaluar madurez del proceso y proyectar una metodologia mas adecuada.

### 4.4. Justificacion de la seleccion de normas

La seleccion se justifica porque combina la mirada sobre el producto con la mirada sobre el proceso. ISO/IEC 25010 permite analizar la calidad observable del sistema en uso, mientras que ISO 9001, ISO/IEC 12207, IEEE 730 y CMMI o Scrum permiten evaluar como se trabajo, que practicas faltaron y como podria profesionalizarse el desarrollo.

### 4.5. Alcance del analisis

El trabajo abarca los siguientes modulos y procesos del sistema:

- autenticacion, registro y recuperacion de contrasena;
- gestion de usuarios;
- perfil del usuario y seguimiento academico;
- gestion de carreras y materias;
- asignacion de profesores a materias;
- consultas institucionales y novedades;
- personalizacion del sitio;
- bolsa de trabajo y postulaciones.

Ademas, se consideran aspectos transversales como roles, permisos, validaciones, persistencia de datos, dependencias externas y evidencia de testing.

### 4.6. Fuera de alcance

Quedan fuera del alcance del trabajo:

- auditorias especializadas de rendimiento;
- pruebas de penetracion profundas;
- certificaciones formales;
- reingenieria completa del sistema;
- implementacion efectiva de todas las mejoras propuestas.

### 4.7. Roles y plan de accion

| Integrante | Rol | Tarea concreta | Entregable esperado |
| --- | --- | --- | --- |
| [Nombre 1] | Lider de QA | Coordinar el trabajo, consolidar criterios y redactar estrategia general | Informe de gestion de calidad |
| [Nombre 2] | Analista de Calidad de Producto | Evaluar el sistema segun ISO/IEC 25010 | Tabla de evaluacion y conclusion |
| [Nombre 3] | Analista de Procesos / Ciclo de Vida | Reconstruir el ciclo de vida segun ISO/IEC 12207 | Informe de proceso y riesgos |
| [Nombre 4] | Especialista en Testing | Diseñar casos de prueba y mini plan SQA | Plan de SQA y pruebas funcionales |
| [Nombre 5] | Analista de Metodologia | Evaluar metodologia real y proponer mejoras | Informe de metodologia y madurez |

### 4.8. Cronograma sugerido

| Etapa | Actividad | Fecha estimada |
| --- | --- | --- |
| 1 | Relevamiento documental del sistema | [Completar] |
| 2 | Asignacion de roles y definicion del alcance | [Completar] |
| 3 | Elaboracion de estrategia general de calidad | [Completar] |
| 4 | Desarrollo de analisis individuales | [Completar] |
| 5 | Revision grupal y ajustes | [Completar] |
| 6 | Integracion del informe final | [Completar] |
| 7 | Entrega | [Completar] |

## 5. Analisis individual por rol

### 5.1. Lider de QA - Informe de Gestion de Calidad

Desde la perspectiva de ISO 9001:2015, IFTS15 evidencia haber seguido un proceso de desarrollo incremental, orientado a resolver necesidades funcionales concretas. Sin embargo, no se observa evidencia fuerte de que haya existido, desde el inicio, un sistema formal de gestion de calidad con trazabilidad completa de requisitos, criterios de aceptacion, indicadores y revisiones estructuradas.

Entre los principales puntos de mejora se destacan:

- falta de formalizacion temprana del alcance y los requisitos;
- ausencia visible de una estrategia sistematica de testing;
- riesgo de deuda tecnica por crecimiento incremental;
- dependencia de conocimiento tacito del equipo.

Como propuesta de mejora continua, se recomienda aplicar el ciclo PDCA:

- Plan: definir requisitos, criterios de aceptacion, responsables y riesgos.
- Do: desarrollar por iteraciones cortas y documentar decisiones.
- Check: verificar criterios de aceptacion y registrar defectos.
- Act: corregir desvíos y actualizar documentacion y aprendizajes.

En consecuencia, la principal recomendacion desde este rol es profesionalizar el proceso sin perder la capacidad iterativa del proyecto.

### 5.2. Analista de Calidad de Producto - Evaluacion segun ISO/IEC 25010

Para evaluar la calidad del producto se seleccionaron cinco caracteristicas relevantes de la norma ISO/IEC 25010: adecuacion funcional, usabilidad, confiabilidad, seguridad y mantenibilidad.

#### Tabla de evaluacion

| Caracteristica | Nivel de cumplimiento | Justificacion |
| --- | --- | --- |
| Adecuacion funcional | Alto | El sistema cubre necesidades institucionales y academicas concretas con multiples modulos operativos. |
| Usabilidad | Medio | La organizacion modular favorece el uso, pero no hay evidencia de pruebas de usabilidad ni criterios formales de accesibilidad. |
| Confiabilidad | Medio | Existen validaciones y controles operativos, aunque no se observa evidencia fuerte de regresion continua o pruebas automatizadas. |
| Seguridad | Medio | Hay control de roles y restricciones sobre acciones sensibles, pero no surge una estrategia integral de seguridad verificada. |
| Mantenibilidad | Medio/Bajo | El proyecto evoluciono funcionalmente, aunque con signos de deuda tecnica y consolidacion documental posterior. |

#### Desarrollo del analisis

La adecuacion funcional constituye la principal fortaleza del sistema, ya que existe correspondencia clara entre las necesidades institucionales observables y las funcionalidades implementadas. La usabilidad, si bien parece razonable en terminos generales, no puede calificarse con un nivel alto por falta de evidencia de evaluaciones centradas en el usuario. La confiabilidad y la seguridad se ubican en un nivel medio porque, aunque existen controles operativos y de acceso, no se observa una demostracion sistematica de su robustez. Finalmente, la mantenibilidad aparece como la dimension mas comprometida, dado que el crecimiento incremental del proyecto y la consolidacion posterior de documentacion sugieren un costo de evolucion potencialmente elevado.

#### Caracteristicas con menor cumplimiento

Las dos caracteristicas con menor nivel de cumplimiento son mantenibilidad y confiabilidad.

Las mejoras propuestas son:

- estandarizar documentacion tecnica y funcional;
- incorporar revisiones de codigo y criterios de refactorizacion;
- definir una base minima de pruebas funcionales recurrentes;
- documentar escenarios criticos y criterios de aceptacion por modulo.

En conclusion, el sistema presenta una buena adecuacion funcional, pero todavia requiere madurar en dimensiones que permitan sostener esa funcionalidad con mayor estabilidad y capacidad de evolucion.

### 5.3. Analista de Procesos / Ciclo de Vida - Evaluacion segun ISO/IEC 12207

La reconstruccion del ciclo de vida del proyecto sugiere que existieron etapas de definicion inicial, desarrollo funcional, ampliacion de modulos, correcciones operativas, mantenimiento evolutivo y consolidacion documental. No obstante, estas etapas parecen haber sido ejecutadas con un grado importante de informalidad.

En comparacion con ISO/IEC 12207, puede afirmarse que hubo desarrollo, implementacion y mantenimiento, pero no se evidencia con la misma claridad la formalizacion de requisitos, el diseño documentado, el cierre de testing ni la definicion de hitos verificables.

Los riesgos principales derivados de esta situacion son:

- retrabajo por cambios sin aprobacion formal;
- dependencia del conocimiento del equipo original;
- defectos detectados tardíamente por falta de testing estructurado.

Desde este rol, se concluye que el ciclo de vida existio en la practica, pero no en una forma suficientemente documentada y trazable.

### 5.4. Especialista en Testing - Plan de SQA basado en IEEE 730

El objetivo del mini plan de SQA es establecer una base formal minima para verificar el funcionamiento del sistema, registrar evidencia y ordenar el tratamiento de defectos.

#### Alcance del plan

Se priorizan las funcionalidades criticas:

- login y registro;
- recuperacion de contrasena;
- gestion de usuarios;
- perfil academico;
- bolsa de trabajo.

#### Actividades de QA

- revision de documentacion funcional disponible;
- diseño de casos de prueba;
- ejecucion manual de pruebas funcionales;
- registro de resultados;
- seguimiento de defectos.

#### Casos de prueba sugeridos

| Caso | Precondicion | Pasos | Resultado esperado |
| --- | --- | --- | --- |
| CP-01 | Usuario habilitado | Ingresar credenciales validas | El sistema inicia sesion y redirige al entorno autenticado |
| CP-02 | Datos validos disponibles | Completar y enviar registro | El sistema procesa el alta segun reglas vigentes |
| CP-03 | Usuario con email existente | Ejecutar recuperacion de contrasena | El sistema permite restablecer el acceso |
| CP-04 | Usuario de gestion autenticado | Crear oferta laboral | La oferta queda registrada y visible segun estado |
| CP-05 | Alumno autenticado y oferta activa | Postularse con CV valido | La postulacion queda registrada correctamente |
| CP-06 | Administrador autenticado | Cambiar rol de otro usuario | El rol se actualiza respetando restricciones |
| CP-07 | Profesor autenticado | Cargar o actualizar notas | Las notas quedan asociadas correctamente |
| CP-08 | Alumno con postulacion activa | Actualizar CV | El sistema reemplaza el archivo y conserva la relacion |

#### Tipos de testing observados y faltantes

Se presume la realizacion de pruebas funcionales manuales e integracion basica durante el desarrollo. Sin embargo, no se observa evidencia suficiente de testing unitario automatizado, regresion automatizada, pruebas de seguridad ni cobertura formal documentada.

Desde este rol se concluye que el sistema probablemente fue validado por uso funcional e iteracion correctiva, pero no mediante un esquema formal de aseguramiento de calidad. La principal mejora recomendada consiste en institucionalizar una practica minima y recurrente de pruebas, evidencia y seguimiento de defectos.

### 5.5. Analista de Metodologia - Evaluacion CMMI / Scrum

El proyecto no muestra evidencia de una metodologia estricta y formal aplicada desde el inicio. La interpretacion mas razonable es que se trato de un desarrollo incremental con rasgos agiles informales, guiado por necesidades funcionales sucesivas.

En terminos de madurez, el proceso parece ubicarse entre un nivel inicial y un nivel parcialmente gestionado. Existen resultados funcionales concretos, evolucion del producto y cierta consolidacion documental, pero no se observa una gestion formal y estable de procesos, metricas, QA y testing.

Si el proyecto se realizara nuevamente, una alternativa adecuada seria adoptar Scrum con roles definidos, backlog priorizado, sprints por modulo, criterios de aceptacion y definicion de terminado, complementado con practicas de calidad propias de CMMI e IEEE 730.

En consecuencia, la metodologia recomendada para una nueva iteracion del proyecto seria una combinacion de enfoque agil y practicas formales de calidad.

## 6. Conclusion grupal

El analisis integral permite concluir que IFTS15 es un sistema funcional, con utilidad concreta y cobertura real de necesidades institucionales y academicas. Su principal fortaleza reside en la adecuacion funcional: el producto efectivamente resuelve problemas relevantes del contexto para el cual fue concebido.

Sin embargo, el trabajo tambien evidencia debilidades importantes vinculadas al proceso de desarrollo. Entre ellas se destacan la informalidad parcial en la definicion de requisitos, la falta de una estrategia sistematica de testing, la baja trazabilidad de hitos y la necesidad de fortalecer la mantenibilidad del sistema.

Si el proyecto se volviera a realizar hoy, el grupo considera necesario incorporar una metodologia mas ordenada, criterios de aceptacion definidos, revisiones sistematicas, documentacion mas temprana y un esquema minimo, pero sostenido, de aseguramiento de calidad.

En terminos globales, puede afirmarse que IFTS15 cumple aceptablemente con los objetivos funcionales del producto, pero no alcanza aun un grado alto de madurez en calidad de proceso. Precisamente, esa tension entre un producto util y un proceso perfectible constituye el principal hallazgo del presente trabajo.

## 7. Reflexion sobre el trabajo en equipo

La conformacion del area de calidad permitio revisar un proyecto propio desde una perspectiva distinta a la del desarrollo original. El trabajo por roles favorecio la distribucion de responsabilidades y la construccion de una mirada mas completa sobre el sistema.

Este ejercicio tambien puso en evidencia que la calidad del software no depende exclusivamente del codigo fuente, sino de un entramado mas amplio de definicion de requisitos, organizacion del trabajo, validacion, documentacion y mejora continua.

Completar por el grupo:

- como se organizo efectivamente la coordinacion del equipo;
- que rol resulto mas desafiante y por que;
- que aprendizajes surgieron al analizar un proyecto propio desde el enfoque de calidad.

## 8. Anexos

Se recomienda adjuntar como anexos:

- requerimiento funcional del sistema;
- tabla de evaluacion ISO/IEC 25010;
- casos de prueba detallados;
- cronograma;
- capturas del sistema;
- documentacion tecnica complementaria.

## 9. Documentacion de apoyo utilizada

Para la elaboracion de este informe se tomaron como base los siguientes documentos del proyecto:

- [tp-paso1-descripcion-proyecto.md](docs/tp-paso1-descripcion-proyecto.md)
- [tp-paso3-estrategia-general-calidad.md](docs/tp-paso3-estrategia-general-calidad.md)
- [tp-paso4-analisis-por-rol.md](docs/tp-paso4-analisis-por-rol.md)
- [requerimiento-funcional-cliente.md](docs/requerimiento-funcional-cliente.md)
- [estado-actual.md](docs/estado-actual.md)
- [historial-tecnico.md](docs/historial-tecnico.md)

## 10. Cierre

El presente informe permite afirmar que el sistema IFTS15 constituye un caso apropiado para el estudio de aseguramiento de calidad en un contexto academico. La revision realizada no solo permite valorar el producto desarrollado, sino tambien comprender las consecuencias de haber trabajado con distintos niveles de formalizacion. En ese sentido, el trabajo no se limita a señalar errores o carencias, sino que aporta una base argumentada para futuras mejoras tanto tecnicas como metodologicas.