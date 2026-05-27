# TP Calidad - Paso 1 - Seleccion y descripcion del proyecto

## 1. Nombre del sistema

IFTS15 - Sistema Web Educativo

## 2. Descripcion general del sistema

IFTS15 es una aplicacion web desarrollada para centralizar funciones institucionales, academicas y administrativas de un instituto de formacion tecnica superior. El sistema combina una parte publica, accesible para cualquier visitante, con una parte privada orientada a alumnos, profesores y perfiles de gestion.

Desde el punto de vista funcional, el sistema permite mostrar informacion institucional, gestionar acceso de usuarios, administrar carreras y materias, realizar seguimiento academico, publicar novedades, recibir consultas y operar una bolsa de trabajo con postulaciones de alumnos.

Se trata de un sistema con arquitectura MVC en PHP, persistencia en MySQL o MariaDB y distintos modulos que fueron ampliandose durante el desarrollo.

## 3. Materia y año en que fue desarrollado

Completar por el grupo:

- Materia original: [indicar materia]
- Año de desarrollo original: [indicar año]

## 4. Tecnologias utilizadas

Las tecnologias identificadas en el proyecto son las siguientes:

- Lenguaje principal: PHP.
- Arquitectura: MVC.
- Base de datos: MySQL o MariaDB.
- Gestor de dependencias: Composer.
- Frontend: Bootstrap 5.3.2, Bootstrap Icons y Font Awesome.
- Variables de entorno: vlucas/phpdotenv.
- Envio de correos: PHPMailer.
- Almacenamiento de imagenes y CV: Cloudinary.
- Librerias auxiliares: SortableJS.
- Servidor de desarrollo utilizado localmente: PHP embebido o entorno XAMPP.

## 5. Funcionalidades principales que implementa

El sistema implementa las siguientes funcionalidades principales:

- pagina principal publica con informacion institucional;
- registro e inicio de sesion de usuarios;
- recuperacion y restablecimiento de contrasena;
- gestion de perfil del usuario con foto e informacion academica;
- visualizacion de matriculacion, notas, conceptos y promedio;
- administracion de usuarios y habilitacion de cuentas;
- gestion de carreras y materias;
- asignacion de profesores a materias;
- publicacion y visualizacion de novedades;
- envio de consultas institucionales;
- personalizacion del navbar, sidebar, carrusel y footer segun permisos;
- bolsa de trabajo con publicacion de ofertas, postulaciones y gestion de CV.

## 6. Modulos principales del sistema

Para efectos del trabajo practico, los modulos principales del sistema pueden agruparse de la siguiente manera:

### 6.1. Modulo publico institucional

- home publica;
- acceso a login y registro;
- consultas institucionales;
- visualizacion de novedades;
- informacion general del instituto.

### 6.2. Modulo de autenticacion y cuentas

- inicio de sesion;
- cierre de sesion;
- recuperacion de contrasena;
- alta de usuarios.

### 6.3. Modulo academico

- perfil del alumno;
- matriculacion por materia;
- carga y consulta de notas;
- gestion de conceptos academicos;
- asignacion de profesores a materias.

### 6.4. Modulo administrativo

- ABM de usuarios;
- ABM de carreras;
- ABM de materias;
- dashboard de personalizacion del sitio.

### 6.5. Modulo de bolsa de trabajo

- creacion de ofertas laborales;
- publicacion directa para perfiles de gestion;
- postulacion del alumno con CV;
- actualizacion y cancelacion de postulaciones;
- consulta administrativa de postulantes.

## 7. Problemas o limitaciones detectados o recordados durante el desarrollo

En base a la documentacion tecnica y al historial del proyecto, pueden identificarse los siguientes problemas, limitaciones o puntos de mejora:

- crecimiento incremental del sistema sin una estrategia formal de calidad desde el inicio;
- necesidad de limpieza de codigo y eliminacion de debugging residual;
- diferencias de comportamiento entre entornos Windows y Linux por sensibilidad de mayusculas y minusculas en rutas y namespaces;
- necesidad de endurecer validaciones de configuracion para servicios externos como Cloudinary y SMTP;
- documentacion funcional y tecnica que fue consolidada de forma posterior, no necesariamente desde el inicio del desarrollo;
- ausencia visible de una bateria de testing automatizado amplia dentro del repositorio;
- incorporacion progresiva de modulos nuevos, como bolsa de trabajo y conceptos academicos, que pueden haber incrementado la complejidad del mantenimiento.

## 8. Justificacion de la seleccion del proyecto para el TP

Este proyecto resulta adecuado para el analisis de calidad porque:

- posee alcance funcional suficiente para evaluar varias caracteristicas de calidad del producto;
- incluye perfiles de usuario diferenciados y reglas de permisos;
- combina interfaz publica, logica de negocio, base de datos y servicios externos;
- presenta evidencia de evolucion real del desarrollo, lo que permite analizar proceso, ciclo de vida y testing;
- ofrece un caso representativo de proyecto academico que puede ser revisado con criterios profesionales de aseguramiento de calidad.

## 9. Conclusion del Paso 1

El proyecto IFTS15 constituye un sistema web educativo de complejidad media, con multiples modulos funcionales y una base tecnica suficiente para realizar un analisis formal desde el area de calidad. Su estado actual permite evaluar tanto la calidad del producto como la madurez del proceso de desarrollo que lo originó.