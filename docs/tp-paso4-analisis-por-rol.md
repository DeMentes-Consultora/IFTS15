# TP Calidad - Paso 4 - Analisis individual por rol

## Proposito

Este documento funciona como base para desarrollar los analisis individuales del trabajo practico. Cada seccion responde a uno de los roles definidos en la consigna y ya esta orientada al sistema IFTS15 para reducir trabajo de redaccion posterior.

Cada integrante puede tomar su seccion, completarla y ampliarla con evidencias, ejemplos y conclusiones propias.

## A. Lider de QA - Informe de Gestion de Calidad

### 1. Organizacion del proceso segun ISO 9001:2015

En el caso de IFTS15, el proceso de desarrollo parece haber avanzado de manera incremental, incorporando modulos y mejoras sobre una base funcional existente. Desde la perspectiva de ISO 9001:2015, se observa que el proyecto logro producir un sistema operativo con valor funcional, pero no surge evidencia fuerte de que haya existido desde el inicio un sistema formal de gestion de calidad.

Puede inferirse que hubo al menos las siguientes actividades:

- definicion general del sistema y sus modulos principales;
- desarrollo progresivo de funcionalidades;
- correcciones y refinamientos sobre componentes ya implementados;
- consolidacion posterior de documentacion tecnica;
- mejoras operativas relacionadas con despliegue, validaciones y mantenimiento.

Sin embargo, no se observa una trazabilidad formal completa de:

- aprobacion de requisitos;
- responsables definidos por proceso;
- criterios formales de aceptacion;
- registro sistematico de revisiones;
- indicadores de calidad o seguimiento de defectos.

### 2. Puntos donde el proceso fallo o podria mejorarse

Se identifican al menos los siguientes puntos de mejora:

1. Falta de formalizacion de requisitos y alcance.
El proyecto cuenta hoy con mejor documentacion, pero parte de esa consolidacion fue posterior al desarrollo. Esto sugiere que algunos requisitos pueden haber evolucionado durante la construccion sin un control formal completo.

2. Ausencia visible de una estrategia de testing sistematica.
No se observa una bateria amplia de pruebas automatizadas ni evidencia clara de un plan formal de QA durante el desarrollo original.

3. Riesgo de deuda tecnica por crecimiento incremental.
La incorporacion progresiva de modulos como conceptos academicos y bolsa de trabajo indica evolucion real, pero tambien puede haber generado complejidad adicional y necesidad de refactorizacion continua.

4. Dependencia de conocimientos tacitos del equipo.
Parte del valor del proyecto parece haber quedado apoyado en conocimiento informal del grupo, algo que complica mantenimiento, onboarding y continuidad.

### 3. Propuesta de mejora continua basada en PDCA

#### Plan

- definir requisitos funcionales y no funcionales antes de iniciar cada modulo;
- establecer criterios de aceptacion por funcionalidad;
- asignar responsables claros para desarrollo, revision y testing;
- definir un tablero de riesgos y defectos.

#### Do

- desarrollar por iteraciones cortas;
- documentar decisiones relevantes;
- ejecutar pruebas funcionales sobre cada modulo terminado.

#### Check

- revisar cumplimiento de criterios de aceptacion;
- registrar defectos encontrados;
- verificar impacto de cambios sobre modulos existentes.

#### Act

- corregir defectos priorizados;
- ajustar procesos que generen errores recurrentes;
- actualizar la documentacion y lecciones aprendidas.

### 4. Politica de calidad propuesta

El equipo del proyecto IFTS15 se compromete a desarrollar y mantener un sistema funcional, seguro y mantenible, priorizando la claridad de requisitos, la validacion continua de las funcionalidades, la documentacion de decisiones tecnicas y la mejora permanente del proceso de trabajo. Cada entrega debera responder a criterios verificables de calidad y contribuir a la confiabilidad del sistema institucional.

### 5. Conclusion del rol

Desde la mirada del Lider de QA, IFTS15 evidencia un producto con funcionalidad concreta y evolucion real, pero con necesidad de mayor formalizacion del proceso. La principal mejora requerida no es solamente tecnica, sino organizacional: planificar mejor, documentar antes, probar mas y revisar sistematicamente.

## B. Analista de Calidad de Producto - Evaluacion segun ISO/IEC 25010

### 1. Caracteristicas seleccionadas

Para este sistema se consideran especialmente relevantes las siguientes caracteristicas de la norma ISO/IEC 25010:

- adecuacion funcional;
- usabilidad;
- confiabilidad;
- seguridad;
- mantenibilidad.

La seleccion de estas caracteristicas responde al tipo de sistema analizado. IFTS15 no es un desarrollo experimental aislado, sino una aplicacion web con multiples perfiles de usuario, modulos administrativos, operaciones academicas y dependencias externas. Por ello, no alcanza con verificar que el sistema funcione; tambien resulta necesario analizar si la solucion es comprensible para sus usuarios, estable frente a cambios, segura en terminos de acceso y mantenible en el tiempo.

### 2. Evaluacion resumida

| Caracteristica | Nivel de cumplimiento | Justificacion |
| --- | --- | --- |
| Adecuacion funcional | Alto | El sistema cubre multiples necesidades institucionales concretas: autenticacion, gestion academica, usuarios, consultas, novedades y bolsa de trabajo. |
| Usabilidad | Medio | La interfaz parece funcional y organizada por modulos, pero no se observa evidencia formal de pruebas de usabilidad ni criterios UX sistematicos. |
| Confiabilidad | Medio | Existen validaciones y controles operativos, pero hay dependencias externas y poca evidencia de pruebas automatizadas o regresion continua. |
| Seguridad | Medio | Hay control de permisos y validaciones en acciones sensibles, pero no se evidencia una estrategia completa de seguridad o auditoria profunda. |
| Mantenibilidad | Medio/Bajo | La documentacion tecnica mejoro, pero el crecimiento incremental y la necesidad de limpiezas y ajustes sugieren deuda tecnica y dependencia de conocimiento del equipo. |

### 2.1. Desarrollo de la evaluacion

#### Adecuacion funcional

La adecuacion funcional presenta un nivel alto porque el sistema responde a necesidades concretas del contexto institucional. El proyecto no se limita a una funcionalidad puntual, sino que articula autenticacion, gestion de perfiles, administracion de usuarios, carreras, materias, novedades, consultas y bolsa de trabajo. Desde este punto de vista, existe correspondencia entre los objetivos del sistema y las funcionalidades efectivamente implementadas.

#### Usabilidad

La usabilidad se ubica en un nivel medio. El sistema parece ofrecer una estructura modular relativamente clara y separa funcionalidades segun roles, lo que favorece la comprension general. Sin embargo, no se observa evidencia de evaluaciones formales de experiencia de usuario, pruebas de usabilidad, criterios de accesibilidad ni mediciones de facilidad de aprendizaje. Por esta razon, no puede asignarse un nivel alto con fundamento suficiente.

#### Confiabilidad

La confiabilidad tambien se ubica en un nivel medio. Existen elementos que contribuyen a una operacion estable, como validaciones funcionales, controles de acceso y manejo de algunos errores operativos. No obstante, la falta de evidencia de testing sistematico, junto con la dependencia de servicios externos como correo y almacenamiento en la nube, introduce un margen de incertidumbre respecto del comportamiento sostenido del sistema frente a distintos escenarios de uso.

#### Seguridad

La seguridad puede evaluarse en un nivel medio. El sistema implementa diferenciacion de roles y restricciones de acceso sobre operaciones sensibles, lo cual constituye una fortaleza. Sin embargo, desde la documentacion disponible no surge una estrategia completa de seguridad, ni pruebas especificas de endurecimiento, auditoria, monitoreo o evaluacion de vulnerabilidades. En consecuencia, la seguridad aparece contemplada en un nivel funcional basico, pero no de manera integral.

#### Mantenibilidad

La mantenibilidad constituye la dimension mas debil. La necesidad de consolidar documentacion en etapas posteriores, la evolucion incremental del sistema y la identificacion de limpiezas y correcciones tecnicas sugieren una base funcional valiosa, pero con riesgos de deuda tecnica. Esto no implica que el sistema sea inmantenible, sino que mantenerlo o extenderlo puede exigir mayor esfuerzo si no se formalizan mejor las decisiones de diseño, los criterios de organizacion del codigo y la estrategia de pruebas.

### 3. Caracteristicas con menor cumplimiento

Las dos caracteristicas mas debiles del sistema parecen ser:

#### Mantenibilidad

Motivos:

- consolidacion documental posterior al desarrollo;
- crecimiento modular progresivo;
- ajustes operativos y limpiezas realizadas sobre la marcha.

Mejoras propuestas:

- estandarizar documentacion tecnica y funcional;
- incorporar revisiones de codigo formales;
- definir convenciones de arquitectura y mantenimiento.
- establecer criterios minimos de refactorizacion antes de agregar nuevos modulos.

#### Confiabilidad o seguridad

Motivos:

- falta de evidencia de testing sistematico;
- dependencia de servicios externos;
- ausencia visible de ensayos estructurados de regresion o seguridad.

Mejoras propuestas:

- crear una suite minima de pruebas funcionales;
- registrar incidentes y fallos recurrentes;
- reforzar validaciones y controles de acceso con revisiones dedicadas.
- documentar escenarios criticos y criterios de aceptacion por modulo.

### 3.1. Tabla sintesis para presentacion academica

| Caracteristica | Evidencia observada | Debilidad principal | Mejora sugerida |
| --- | --- | --- | --- |
| Adecuacion funcional | Amplia cobertura de modulos institucionales y academicos | Falta de trazabilidad formal entre requisito y funcionalidad | Formalizar matriz de requisitos y criterios de aceptacion |
| Usabilidad | Navegacion modular y separacion por perfiles | No hay evidencia de pruebas de usabilidad | Evaluar tareas criticas con usuarios reales |
| Confiabilidad | Validaciones y controles operativos basicos | No hay evidencia fuerte de regresion continua | Incorporar pruebas funcionales recurrentes |
| Seguridad | Control de roles y restricciones de acceso | No se observan evaluaciones especificas de seguridad | Revisar permisos y validar escenarios de abuso |
| Mantenibilidad | Documentacion tecnica consolidada y estructura reconocible | Crecimiento incremental y posible deuda tecnica | Estandarizar mantenimiento, revisiones y documentacion |

### 4. Conclusion del rol

El sistema puede considerarse funcionalmente valioso y adecuado para su contexto de uso, por lo que presenta un cumplimiento favorable en adecuacion funcional. No obstante, una evaluacion segun ISO/IEC 25010 muestra que la calidad del producto no debe analizarse solo por la existencia de funcionalidades, sino tambien por la capacidad del sistema para sostenerlas con estabilidad, claridad de uso, seguridad y facilidad de evolucion. En este sentido, IFTS15 evidencia una base funcional buena, pero requiere madurar en confiabilidad, seguridad y mantenibilidad para alcanzar un nivel de calidad mas solido y demostrable.

## C. Analista de Procesos / Ciclo de Vida - Evaluacion segun ISO/IEC 12207

### 1. Reconstruccion del ciclo de vida del proyecto

En base a la documentacion disponible, puede reconstruirse un ciclo de vida aproximado con las siguientes etapas:

1. Definicion inicial del sistema y sus modulos principales.
2. Desarrollo de funcionalidades nucleares de autenticacion, perfiles y administracion.
3. Ampliacion funcional progresiva con mejoras y nuevos modulos.
4. Correcciones operativas, ajustes de despliegue y consolidacion documental.
5. Incorporacion de funcionalidades recientes como conceptos academicos y bolsa de trabajo.
6. Mantenimiento evolutivo y refinamientos sobre experiencia de uso y consistencia tecnica.

### 2. Comparacion con ISO/IEC 12207

Etapas aparentemente presentes:

- requisitos, aunque posiblemente de modo parcial o informal;
- desarrollo e implementacion;
- mantenimiento evolutivo;
- cierta validacion funcional sobre la marcha.

Etapas debiles o informales:

- documentacion formal de requisitos;
- diseño documentado;
- testing estructurado;
- cierre formal de hitos;
- control de configuracion y aseguramiento de calidad formal.

### 3. Riesgos derivados de un ciclo de vida poco formal

1. Riesgo de retrabajo.
Sin hitos claros de aprobacion, los requisitos o decisiones de implementacion pueden cambiar durante el desarrollo y generar correcciones posteriores.

2. Riesgo de fallas en mantenimiento.
Si las decisiones no quedan bien documentadas, los cambios futuros dependen excesivamente del conocimiento del equipo original.

3. Riesgo de defectos no detectados.
La falta de una etapa formal de testing aumenta la probabilidad de que algunos errores lleguen a etapas tardias o a uso real.

### 4. Hitos que deberian haberse documentado

Para un mejor alineamiento con ISO/IEC 12207, el proyecto deberia haber documentado formalmente:

- aprobacion del requerimiento inicial;
- cierre del diseño funcional y tecnico;
- fin del desarrollo de cada modulo principal;
- cierre de testing funcional;
- pase a produccion o despliegue;
- registro de mantenimiento y mejoras.

### 5. Conclusion del rol

El proyecto muestra que el ciclo de vida existio en la practica, pero de forma parcialmente informal. La principal brecha respecto de ISO/IEC 12207 no es la ausencia total de etapas, sino la falta de formalizacion, trazabilidad y cierre documental de esas etapas.

## D. Especialista en Testing - Plan de SQA basado en IEEE 730

### 1. Mini plan de SQA

#### Objetivo

Establecer un conjunto minimo, pero formalmente justificable, de actividades de aseguramiento de calidad que permita verificar el comportamiento de las funcionalidades principales de IFTS15, documentar evidencias de prueba y registrar defectos de manera ordenada.

#### Alcance

Se evaluaran las funcionalidades criticas del sistema:

- login y registro;
- recuperacion de contrasena;
- gestion de usuarios;
- perfil academico;
- bolsa de trabajo.

El enfoque del plan prioriza funcionalidades de alto impacto para la operacion del sistema, es decir, aquellas cuya falla comprometeria el acceso, la administracion de usuarios o el flujo principal de valor para alumnos y perfiles de gestion.

#### Actividades de QA

- revision de requerimientos funcionales disponibles;
- diseño de casos de prueba;
- ejecucion manual de pruebas funcionales;
- registro de resultados;
- seguimiento de defectos detectados.

#### Responsables

- Especialista en Testing: diseña y ejecuta pruebas.
- Lider de QA: revisa cobertura y consistencia.
- Equipo de desarrollo: corrige defectos detectados.

#### Evidencia esperada

- casos de prueba documentados;
- registro de resultados obtenidos;
- listado de defectos;
- conclusion sobre cobertura funcional minima.

### 1.1. Criterios de entrada y salida

#### Criterios de entrada

- disponibilidad de la documentacion funcional base;
- acceso al sistema en entorno operativo;
- existencia de usuarios de prueba segun roles;
- configuracion minima de base de datos y servicios requeridos.

#### Criterios de salida

- ejecucion de los casos de prueba definidos;
- registro de resultados esperados y obtenidos;
- identificacion de defectos criticos o relevantes;
- conclusion sobre el estado funcional minimo del sistema.

### 2. Casos de prueba funcionales sugeridos

| Caso | Precondicion | Pasos | Resultado esperado | Resultado obtenido |
| --- | --- | --- | --- | --- |
| CP-01 Login valido | Usuario registrado y habilitado | Ingresar email y contrasena correctos | El sistema inicia sesion y redirige al entorno autenticado | [Completar] |
| CP-02 Registro de usuario | Datos validos disponibles | Completar formulario de registro y enviar | El usuario se registra correctamente o queda pendiente segun reglas del sistema | [Completar] |
| CP-03 Recuperacion de contrasena | Usuario con email existente | Solicitar recuperacion y seguir enlace de reseteo | El sistema permite definir una nueva contrasena | [Completar] |
| CP-04 Alta de oferta laboral | Usuario con rol de gestion autenticado | Crear oferta desde la bolsa de trabajo | La oferta queda registrada y visible segun su estado | [Completar] |
| CP-05 Postulacion con CV | Alumno autenticado y oferta activa | Subir CV valido y confirmar postulacion | La postulacion se registra y el sistema confirma la operacion | [Completar] |

### 2.1. Desarrollo narrativo de los casos de prueba

#### CP-01. Inicio de sesion con credenciales validas

Este caso permite validar el punto de entrada principal al sistema. Si el usuario posee credenciales correctas y una cuenta habilitada, el comportamiento esperado es que el sistema cree la sesion correspondiente y otorgue acceso al entorno autenticado. El valor de este caso radica en que una falla en login afecta transversalmente al resto de los modulos.

#### CP-02. Registro de nuevo usuario

Este caso busca verificar que el sistema reciba correctamente los datos del formulario de alta, aplique las validaciones necesarias y persista la informacion segun las reglas vigentes. Resulta importante porque combina validacion de datos personales, datos academicos y reglas de negocio sobre el alta.

#### CP-03. Recuperacion de contrasena

Este caso permite verificar si el sistema ofrece continuidad operativa cuando el usuario pierde acceso a su cuenta. La correcta ejecucion del flujo de recuperacion impacta directamente en la usabilidad y disponibilidad del servicio.

#### CP-04. Creacion de oferta laboral

Este caso valida una funcionalidad relevante para perfiles de gestion. Debe comprobarse que la oferta se genere correctamente, que respete las reglas de acceso por rol y que su estado de publicacion sea consistente con lo esperado por el negocio.

#### CP-05. Postulacion con CV

Este caso cubre uno de los recorridos mas representativos del modulo de bolsa de trabajo. Debe verificarse que el sistema acepte un archivo valido, lo asocie correctamente a la postulacion y emita una respuesta consistente para el usuario.

### 2.2. Casos adicionales recomendados

| Caso | Precondicion | Pasos | Resultado esperado | Resultado obtenido |
| --- | --- | --- | --- | --- |
| CP-06 Cambio de rol de usuario | Administrador autenticado | Modificar el rol de otro usuario desde gestion de usuarios | El sistema actualiza el rol y mantiene restricciones de seguridad | [Completar] |
| CP-07 Carga de notas por docente | Profesor autenticado con alumnos asignados | Registrar o actualizar notas de un alumno | Las notas quedan persistidas y asociadas a la materia correspondiente | [Completar] |
| CP-08 Actualizacion de CV en postulacion activa | Alumno autenticado con postulacion existente | Reemplazar el CV de una postulacion activa | El sistema actualiza el archivo y conserva la relacion con la oferta | [Completar] |

### 3. Tipos de testing observados y faltantes

Testing posiblemente realizado de manera informal:

- funcional manual;
- pruebas de integracion basicas entre modulos y base de datos;
- validacion operativa de despliegue y configuracion.

Testing faltante o no evidenciado con claridad:

- unitario automatizado;
- regresion automatizada;
- pruebas sistematicas de seguridad;
- pruebas de rendimiento;
- cobertura formal documentada.

Desde una perspectiva academica, este punto es especialmente relevante: la ausencia de evidencia de testing no implica necesariamente que no se hayan realizado pruebas, pero si limita la posibilidad de demostrar calidad de forma objetiva, repetible y auditable.

### 4. Esquema de testing continuo integrable en Scrum

Se propone que en cada sprint:

1. cada historia tenga criterios de aceptacion claros;
2. se diseñen casos de prueba asociados a cada funcionalidad;
3. se ejecuten pruebas funcionales antes del cierre del sprint;
4. se registren defectos y su severidad;
5. no se cierre una historia sin evidencia minima de validacion.

Adicionalmente, seria conveniente definir una pequena matriz de prioridad para defectos:

- critico: impide operar una funcionalidad esencial;
- alto: afecta una funcionalidad relevante con impacto claro en el usuario;
- medio: genera error parcial o comportamiento inconsistente;
- bajo: defecto menor, visual o de impacto acotado.

### 5. Conclusion del rol

El analisis desde testing permite concluir que IFTS15 probablemente fue validado de manera funcional e incremental durante su construccion, pero sin un esquema suficientemente formal de aseguramiento de calidad. Esto no invalida el valor del sistema ni su utilidad real, pero si representa una debilidad metodologica importante: la calidad puede percibirse, pero no siempre demostrarse con evidencia estructurada. En consecuencia, la principal recomendacion consiste en institucionalizar una practica minima de pruebas, registro de resultados y seguimiento de defectos.

## E. Analista de Metodologia - Evaluacion CMMI / Scrum

### 1. Metodologia aparentemente utilizada

El proyecto no muestra evidencia de haber seguido una metodologia estricta y formal desde el inicio. Lo mas razonable es concluir que el desarrollo fue incremental, con rasgos agiles informales, orientado a resolver necesidades funcionales concretas a medida que aparecian nuevas demandas o mejoras.

### 2. Nivel de madurez estimado segun CMMI

Una ubicacion razonable para este proyecto seria entre un nivel inicial y un nivel parcialmente gestionado.

Justificacion:

- el sistema funciona y posee modulos concretos, lo que implica cierto orden practico;
- existe evolucion y mantenimiento sobre el producto;
- hay documentacion tecnica consolidada;
- pero no se evidencia una gestion formal y estable de procesos, metricas, QA ni testing estructurado.

Puede argumentarse que el proyecto se acerca mas a un escenario de madurez baja o intermedia que a un proceso verdaderamente definido y repetible.

### 3. Como se organizaria con Scrum

Si el proyecto se realizara hoy con Scrum, deberian definirse:

- Product Owner: responsable de priorizar necesidades del sistema;
- Scrum Master: responsable de facilitar el proceso;
- Equipo de desarrollo: responsable de implementacion y testing.

Los sprints podrian organizarse por modulos, por ejemplo:

- Sprint 1: autenticacion y registro;
- Sprint 2: perfiles y seguimiento academico;
- Sprint 3: ABM administrativos;
- Sprint 4: bolsa de trabajo y mejoras transversales.

Artefactos recomendados:

- product backlog;
- sprint backlog;
- criterios de aceptacion;
- definicion de terminado;
- registro de defectos;
- retrospectivas por sprint.

### 4. Metodologia mas adecuada si se rehaciera hoy

La opcion mas adecuada seria una combinacion entre Scrum y practicas formales de calidad. Scrum permitiria organizar entregas progresivas y priorizacion funcional, mientras que elementos de CMMI e IEEE 730 ayudarían a profesionalizar documentacion, control de calidad y seguimiento de defectos.

### 5. Conclusion del rol

La metodologia aparentemente usada fue suficiente para construir un sistema funcional, pero no para demostrar alta madurez de proceso. Si el proyecto se volviera a hacer hoy, convendria trabajar con una metodologia agil real, apoyada por practicas concretas de QA, definicion de criterios y seguimiento formal del trabajo.

## Cierre general del Paso 4

Cada uno de los analisis individuales muestra una idea comun: IFTS15 es un sistema funcional y valioso, pero su principal oportunidad de mejora aparece en la profesionalizacion del proceso. El producto existe, funciona y evoluciono; el desafio de calidad pasa por hacerlo mas verificable, mantenible y trazable.