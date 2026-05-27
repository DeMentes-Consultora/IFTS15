CONTEXTO DEL TRABAJO	
A lo largo de la carrera han desarrollado proyectos de software en otras materias: sistemas de gestión, aplicaciones web, bases de datos, etc. Ahora, con las herramientas que incorporaron en Aseguramiento de Calidad, van a mirar esos proyectos con nuevos ojos.
El grupo deberá conformarse como un área de calidad de software real, elegir uno de sus proyectos anteriores, diseñar una estrategia de aseguramiento de calidad, repartir roles profesionales y ejecutar un análisis formal del sistema seleccionado.
PASO 1 — Selección y descripción del proyecto
El grupo selecciona un proyecto de software realizado en cualquier otra materia de la carrera. Puede ser un sistema de gestión, una aplicación web, una base de datos, un prototipo funcional, etc.
Deben documentar:
    • Nombre y descripción general del sistema.
    • Materia y año en que fue desarrollado.
    • Tecnologías utilizadas (lenguaje, framework, base de datos, etc.).
    • Funcionalidades principales que implementa.
    • Problemas o limitaciones que recuerdan haber tenido durante el desarrollo.


PASO 2 — Conformación del Área de Calidad: roles y responsabilidades
Cada integrante del grupo asume un rol profesional dentro del área de calidad. Los roles se asignan según la cantidad de integrantes del grupo:

ROL	RESPONSABILIDAD PRINCIPAL	NORMA / HERRAMIENTA BASE
Líder de QA (Quality Assurance Manager)	Coordina el equipo, diseña la estrategia general de calidad, supervisa el cumplimiento del plan.	ISO 9001:2015 — Gestión de procesos
Analista de Calidad de Producto	Evalúa el sistema según las características de ISO/IEC 25010 (funcionalidad, usabilidad, seguridad, etc.).	ISO/IEC 25010 — Calidad del producto
Analista de Procesos / Ciclo de Vida	Verifica si el desarrollo siguió un ciclo de vida ordenado y documenta las etapas según ISO 12207.	ISO/IEC 12207 — Ciclo de vida
Especialista en Testing	Diseña y ejecuta casos de prueba, documenta resultados, detecta	IEEE 730 — Plan de SQA
	defectos y hace el seguimiento de su corrección.	
Analista de Metodología	Evalúa si el proceso de desarrollo fue ágil o estructurado, analiza ventajas y desventajas según CMMI/Scrum.	CMMI — Madurez de procesos / Scrum


PASO 3 — Estrategia General de Calidad (entregable grupal)
Antes de que cada integrante trabaje su parte individual, el grupo debe elaborar en conjunto un documento de Estrategia General de Calidad que incluya:

    1. Objetivo del análisis de calidad
¿Qué se quiere demostrar o mejorar con este análisis? ¿Cuáles son los riesgos más importantes del sistema?
    2. Normas seleccionadas y justificación
¿Qué normas o marcos aplicarán (ISO 9001, ISO 25010, ISO 12207, IEEE 730, CMMI, Scrum)? ¿Por qué esas y no otras?
    3. Alcance del análisis
¿Qué módulos o funcionalidades del sistema van a analizar? ¿Qué queda fuera del alcance?
    4. División de roles y plan de acción
Tabla que especifique: integrante → rol → tarea concreta a realizar → entregable esperado.
    5. Cronograma de trabajo
Fechas estimadas para la recopilación de documentación, análisis individual, revisión grupal y entrega final.


PASO 4 — Plan de Acción Individual: análisis por rol
Cada integrante desarrolla su parte del análisis de manera individual, siguiendo el rol asignado. A continuación se detalla qué debe hacer cada rol:

    A) Líder de QA — Informe de Gestión de Calidad	
        ◦ Describir cómo se organizó (o debería haberse organizado) el proceso de desarrollo del proyecto seleccionado, aplicando los principios de ISO 9001:2015.
        ◦ Identificar al menos 3 puntos donde el proceso falló o podría mejorarse (falta de comunicación, ausencia de revisiones, roles poco claros, etc.).
        ◦ Proponer un proceso de mejora continua (ciclo PDCA) para el proyecto: qué se planificaría diferente, qué controles se agregarían, cómo se actuaría ante los errores detectados.
        ◦ Redactar una política de calidad para el proyecto: una declaración formal de cómo el equipo se compromete a trabajar con calidad.

    B) Analista de Calidad de Producto — Evaluación según ISO/IEC 25010	
        ◦ Seleccionar al menos 5 características de la norma ISO/IEC 25010 que sean relevantes para el sistema analizado.
        ◦ Para cada característica: describir cómo se manifiesta (o no) en el sistema, asignar un nivel de cumplimiento (Alto / Medio / Bajo) con justificación.
        ◦ Identificar cuáles son las 2 características con menor cumplimiento y proponer acciones de mejora concretas.
        ◦ Elaborar una tabla resumen de evaluación con los resultados.
        ◦ Conclusión: ¿el sistema puede considerarse de calidad según ISO/IEC 25010?

    C) Analista de Procesos / Ciclo de Vida — Evaluación según ISO/IEC 12207	
        ◦ Reconstruir el ciclo de vida del proyecto elegido: ¿qué etapas se siguieron? (requisitos, diseño, desarrollo, testing, implementación, mantenimiento).
        ◦ Comparar el ciclo real con el que establece la norma ISO/IEC 12207: ¿qué etapas se respetaron, cuáles se saltaron o se hicieron de manera informal?
        ◦ Identificar al menos 2 riesgos que surgieron (o podrían haber surgido) por no seguir el ciclo de vida de forma ordenada.
        ◦ Proponer cómo se deberían haber documentado los hitos del proyecto (por ejemplo: aprobación de requisitos, cierre de testing, pase a producción).

    D) Especialista en Testing — Plan de SQA basado en IEEE 730	
        ◦ Elaborar un miniplan de SQA (Software Quality Assurance Plan) para el proyecto, basado en los requisitos del IEEE 730. Debe incluir: objetivo, alcance, actividades de QA, responsables y evidencia esperada.
        ◦ Diseñar al menos 5 casos de prueba funcionales para las funcionalidades principales del sistema (formato: precondición, pasos, resultado esperado, resultado obtenido).
        ◦ Identificar qué tipos de testing se realizaron durante el desarrollo original (unitario, funcional, integración, regresión, seguridad) y cuáles faltaron.
        ◦ Proponer un esquema de testing continuo integrable en sprints de Scrum.

    E) Analista de Metodología — Evaluación CMMI / Scrum	
        ◦ Analizar qué metodología de desarrollo se usó durante el proyecto original (¿fue ágil, en cascada, sin metodología clara?).
        ◦ Evaluar el nivel de madurez del proceso utilizando los niveles del CMMI: ¿en qué nivel ubicarías el proceso de desarrollo del proyecto? Justificar con evidencias concretas.
        ◦ Si el proyecto hubiera usado Scrum: ¿qué roles se deberían haber definido? ¿Cómo se organizarían los sprints? ¿Qué artefactos se generarían?
        ◦ Concluir: ¿qué metodología sería más adecuada para este proyecto si se volviera a realizar hoy?
¿CMMI, Scrum o una combinación?


PASO 5 — Integración grupal y presentación final
Una vez que cada integrante terminó su análisis individual, el grupo elabora un informe final integrado que reúne todos los análisis y los articula en una conclusión grupal. El informe debe contener:
        ◦ Portada con nombre del sistema analizado, integrantes, roles y fecha.
        ◦ Estrategia General de Calidad (Paso 3).
        ◦ Análisis individual de cada integrante (Paso 4), claramente identificado por rol.
        ◦ Conclusión grupal: ¿el sistema y su proceso de desarrollo cumplen con los estándares de calidad vistos en la materia? ¿Qué cambiarían si lo volvieran a hacer?
        ◦ Reflexión sobre el trabajo en equipo: ¿cómo funcionó el área de calidad que conformaron? ¿Qué rol fue más desafiante y por qué?