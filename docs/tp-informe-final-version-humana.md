# Trabajo Practico Integrador - Aseguramiento de Calidad

## Analisis del sistema IFTS15

### Datos generales

- Sistema analizado: IFTS15 - Sistema Web Educativo
- Materia: Aseguramiento de Calidad
- Integrantes: ALCARAZ Yanina,BARRIOS Julián,CAMILLI Emanuel,DÍAZ Ricardo,GALLORO Pablo,MINOTTI Sebastián
    ROLÓN Francisco, ROLÓN Isis,VARELA Javier,ZAPATA Jimena.
- Fecha de entrega: [Completar]

## 1. Introduccion

Para este trabajo practico tomamos el proyecto IFTS15 y lo analizamos desde una mirada de aseguramiento de calidad. La idea no fue solamente revisar si el sistema funciona, sino observarlo como si fueramos un area de calidad real: identificar sus fortalezas, sus debilidades, los riesgos que presenta y las mejoras que seria conveniente aplicar.

Este enfoque nos permitió volver sobre un proyecto ya desarrollado, pero desde otro lugar. En vez de pensar solo como desarrolladores, analizamos el sistema en relacion con normas, criterios de calidad, testing, metodologia y organizacion del proceso de trabajo.

## 1.a. Descripcion del proyecto

IFTS15 es un sistema web educativo pensado para cubrir necesidades institucionales, academicas y administrativas de un instituto de formacion tecnica superior. Tiene una parte publica, que permite mostrar informacion general del instituto, y una parte privada a la que acceden alumnos, profesores y perfiles de gestion.

Entre sus funcionalidades principales se encuentran el registro e inicio de sesion de usuarios, la recuperacion de contrasena, la administracion de usuarios, la gestion de carreras y materias, la asignacion de profesores, el seguimiento academico del alumno, la publicacion de novedades, el envio de consultas y la bolsa de trabajo con postulaciones.

Desde lo tecnico, el sistema esta desarrollado en PHP con arquitectura MVC, utiliza MySQL o MariaDB como base de datos y se apoya en herramientas como Composer, Bootstrap, PHPMailer, Cloudinary y phpdotenv.

## 1.b. Por que elegimos este proyecto

Elegimos IFTS15 porque es un sistema suficientemente completo como para ser analizado con criterios de calidad. No se trata de una aplicacion pequena o aislada, sino de un proyecto con varios modulos, distintos tipos de usuario, reglas de permisos, persistencia de datos y servicios externos.

Ademas, el sistema muestra una evolucion real. Se nota que fue creciendo con el tiempo, que se le fueron agregando funciones y que hubo correcciones y mejoras. Eso lo vuelve interesante para analizar no solo la calidad del producto final, sino tambien la forma en que fue construido.

## 2 Conformación del Área de Calidad: roles y responsabilidades
Líder de QA
▶ MINOTTI, Sebastián (estrategia)

Coordinador QA
▶ VARELA, Javier (seguimiento)

Analisis de Calidad
▶ ALCARAZ, Yanina
▶ ZAPATA, Jimena

Analista de procesos/ Ciclos de vida
▶ ROLÓN, Isis
▶ DÍAZ, Ricardo

Especialñista en Testing
▶ BARRIOS, Julián
▶ ROLÓN, Francisco

Analista de Metodologias
▶ CAMILLI, Emanuel
▶ GALLORO, Pablo

## 3 Estrategia general de calidad

Como grupo decidimos analizar el sistema desde dos dimensiones principales. Por un lado, la calidad del producto, es decir, como funciona el sistema y que tan adecuado resulta para su objetivo. Por otro lado, la calidad del proceso, es decir, como fue desarrollado, que tan ordenado fue ese trabajo y que practicas de calidad se pueden reconocer o echar en falta.

Para eso tomamos como referencia varias normas y marcos vistos en la materia:

- ISO 9001:2015, para pensar la organizacion del trabajo y la mejora continua.
- ISO/IEC 25010, para evaluar la calidad del producto de software.
- ISO/IEC 12207, para reconstruir el ciclo de vida del proyecto.
- IEEE 730, para orientar un mini plan de aseguramiento de calidad.
- CMMI y Scrum, para analizar la madurez del proceso y la metodologia de trabajo.

El alcance del analisis incluyo los modulos mas importantes del sistema: autenticacion, gestion de usuarios, perfiles, carreras, materias, asignacion docente, consultas, novedades, personalizacion del sitio y bolsa de trabajo. Quedaron fuera del trabajo aspectos mas especificos, como auditorias profundas de seguridad, pruebas de rendimiento avanzadas o certificaciones formales.

## 4. Analisis por roles

### 4.a. Lider de QA

Desde este rol, lo que mas se observa es que IFTS15 logro convertirse en un sistema funcional y util, pero sin que haya evidencia de un proceso de calidad completamente formalizado desde el principio. El proyecto fue creciendo de manera progresiva, resolviendo necesidades concretas, pero no siempre con requisitos, criterios de aceptacion o revisiones documentadas de forma clara.

Los principales puntos de mejora tienen que ver con ordenar mejor el proceso: definir mejor el alcance, documentar decisiones, establecer revisiones y sostener una estrategia de testing mas visible. En otras palabras, el problema principal no parece haber sido la falta de trabajo, sino la falta de formalizacion de ese trabajo.

### 4.b. Analista de Calidad de Producto

Tomando como base ISO/IEC 25010, el sistema muestra una fortaleza clara en adecuacion funcional. IFTS15 cumple con muchas de las necesidades que se esperan de un sistema de este tipo: administra usuarios, carreras, materias, perfiles y tambien incorpora funciones como novedades, consultas y bolsa de trabajo.

Donde aparecen mas debilidades es en confiabilidad, seguridad y mantenibilidad. No porque el sistema no tenga controles, sino porque no hay suficiente evidencia de pruebas sistematicas, evaluaciones formales o criterios estables que permitan demostrar con mas solidez el nivel de calidad alcanzado. Por eso puede decirse que el sistema funciona bien en terminos generales, pero todavia necesita madurar en aspectos que hacen a su sostenibilidad en el tiempo.

### 4.c. Analista de Procesos / Ciclo de Vida

Al reconstruir el ciclo de vida del proyecto, se puede reconocer que hubo etapas de definicion, desarrollo, ampliacion de funcionalidades, correcciones y mantenimiento. Sin embargo, esas etapas parecen haberse dado de una manera bastante informal.

La mayor diferencia con lo que plantea ISO/IEC 12207 no es que las etapas no hayan existido, sino que no quedaron claramente cerradas, documentadas ni trazadas. Esto puede generar problemas de retrabajo, dependencia del conocimiento del equipo original y dificultad para sostener el proyecto a largo plazo.

### 4.d. Especialista en Testing

Desde la mirada de testing, la conclusion principal es que el sistema seguramente fue probado en la practica, pero sin una estrategia formal de aseguramiento de calidad suficientemente visible. Es razonable pensar que se hicieron pruebas funcionales manuales y validaciones sobre la marcha, pero no se observa una base fuerte de testing automatizado, regresion o evidencia documentada.

Para este trabajo se propuso un mini plan de SQA y una serie de casos de prueba sobre funciones clave como login, registro, recuperacion de contrasena, gestion de usuarios y bolsa de trabajo. La idea central es que el sistema necesita una practica minima, pero constante, de pruebas y registro de resultados para que la calidad no dependa solo de la experiencia o intuicion del equipo.

### 4.e. Analista de Metodologia

En cuanto a metodologia, el proyecto no parece haber seguido un marco formal estricto desde el inicio. Lo mas probable es que haya sido desarrollado de forma incremental, con una logica agil pero informal, incorporando modulos y mejoras a medida que surgian necesidades.

Eso explica por que el sistema pudo crecer y volverse funcional, pero tambien por que quedaron debilidades en documentacion, testing y control del proceso. Si el proyecto se hiciera de nuevo, seria conveniente trabajar con una metodologia agil mas clara, como Scrum, combinada con practicas concretas de calidad.

##  Conclusion grupal

Como conclusion general, consideramos que IFTS15 es un sistema valioso, funcional y adecuado para el contexto en el que fue pensado. Su principal fortaleza esta en que efectivamente resuelve necesidades reales de gestion institucional y academica.

Al mismo tiempo, el trabajo muestra que existe una diferencia entre tener un sistema que funciona y tener un proceso de desarrollo maduro. En IFTS15 se ve con claridad que el producto pudo crecer y consolidarse, pero el proceso no siempre estuvo igual de formalizado. Por eso, las principales mejoras no pasan solo por agregar nuevas funcionalidades, sino por fortalecer la forma de trabajar: documentar mejor, definir criterios de aceptacion, sostener testing y dejar mayor trazabilidad.

En ese sentido, el analisis de calidad no solo nos permitió detectar debilidades, sino tambien entender mejor como un proyecto puede ser bueno en su resultado y, al mismo tiempo, seguir teniendo mucho margen de mejora en su proceso.

##  Reflexion sobre el trabajo en equipo

Este trabajo fue util porque nos obligo a mirar un proyecto propio desde un lugar distinto. En vez de enfocarnos solo en programar o corregir, tuvimos que pensar en normas, proceso, evidencia, metodologia y calidad.

Tambien fue importante la division por roles, porque cada integrante pudo mirar el mismo sistema desde un angulo diferente. Eso hizo mas evidente que la calidad del software no depende solamente del codigo, sino tambien de la organizacion del equipo, de la documentacion, de la validacion y de la capacidad de mejorar con el tiempo.

Completar por el grupo:

- como se repartieron el trabajo;
- que rol fue el mas desafiante;
- que aprendieron al analizar un proyecto propio desde calidad.

##  Anexos sugeridos

- requerimiento funcional del sistema;
- estrategia general de calidad;
- analisis por rol;
- tabla de evaluacion ISO/IEC 25010;
- casos de prueba;
- capturas del sistema.