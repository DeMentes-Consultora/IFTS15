# Requerimiento Funcional - Cliente

## 1. Introduccion

Como institucion educativa IFTS15, necesitamos contar con un sistema web que nos permita brindar informacion publica del instituto, gestionar el acceso de nuestros usuarios y administrar procesos academicos y operativos desde una unica plataforma.

El sistema debe contemplar perfiles diferenciados de uso, permitir la autogestion de datos por parte de alumnos y docentes, y ofrecer herramientas administrativas para la gestion del sitio, de los usuarios y de la oferta academica.

Este documento expresa el requerimiento funcional del cliente en base al alcance actualmente implementado en el proyecto.

## 2. Objetivo general

El sistema debera centralizar la gestion institucional, academica y comunicacional del IFTS15, permitiendo:

- difundir informacion publica del instituto;
- registrar y autenticar usuarios;
- administrar usuarios, carreras, materias y asignaciones docentes;
- facilitar el seguimiento academico del alumno;
- publicar novedades y recibir consultas;
- ofrecer una bolsa de trabajo para alumnos y areas de gestion;
- personalizar elementos visibles del sitio institucional.

## 3. Alcance funcional

El sistema debera cubrir los siguientes frentes funcionales:

- Sitio publico institucional.
- Acceso y gestion de cuentas de usuario.
- Perfil academico de alumnos y docentes.
- Gestion administrativa de usuarios.
- Gestion de carreras y materias.
- Asignacion de profesores a materias.
- Registro y consulta de matriculacion, notas y conceptos.
- Publicacion de novedades institucionales.
- Recepcion de consultas de interesados.
- Bolsa de trabajo institucional.
- Personalizacion visual del sitio.

## 4. Perfiles de usuario

El sistema debera contemplar, como minimo, los siguientes perfiles:

- Alumno.
- Profesor.
- Administrativo.
- Directivo.
- Administrador.

## 5. Requerimientos funcionales

### 5.1. Sitio publico institucional

RF-01. El sistema debera disponer de una pagina principal publica con informacion institucional visible sin necesidad de iniciar sesion.

RF-02. El sistema debera mostrar la oferta academica disponible, incluyendo al menos la informacion de carreras publicadas por la institucion.

RF-03. El sistema debera permitir acceder desde el sitio publico a las funciones de inicio de sesion, registro y envio de consultas.

RF-04. El sistema debera permitir la visualizacion de novedades institucionales publicadas.

RF-05. El sistema debera funcionar aun cuando determinados modulos internos no se encuentren disponibles temporalmente, priorizando la continuidad del acceso al sitio publico.

### 5.2. Registro, autenticacion y recuperacion de cuenta

RF-06. El sistema debera permitir el registro de nuevos usuarios mediante un formulario con datos personales, academicos y credenciales de acceso.

RF-07. El sistema debera validar que los datos obligatorios del registro hayan sido completados correctamente.

RF-08. El sistema debera impedir el alta duplicada de usuarios con documentos ya registrados.

RF-09. El sistema debera permitir el inicio de sesion mediante correo electronico y contrasena.

RF-10. El sistema debera mantener una sesion autenticada para los usuarios validados y redirigirlos a la experiencia correspondiente.

RF-11. El sistema debera permitir el cierre de sesion.

RF-12. El sistema debera ofrecer recuperacion de contrasena mediante correo electronico.

RF-13. El sistema debera permitir definir una nueva contrasena a traves de un flujo de restablecimiento.

RF-14. El sistema debera permitir que determinados perfiles administrativos registren usuarios indicando el rol a asignar, segun sus permisos.

### 5.3. Gestion de usuarios

RF-15. El sistema debera permitir a los perfiles administrativos visualizar un listado de usuarios registrados.

RF-16. El sistema debera permitir habilitar o deshabilitar usuarios.

RF-17. El sistema debera permitir habilitar usuarios en lote cuando la operatoria administrativa lo requiera.

RF-18. El sistema debera notificar por correo electronico al usuario cuando su cuenta haya sido habilitada.

RF-19. El sistema debera permitir al perfil Administrador modificar el rol de otros usuarios, respetando las restricciones de seguridad.

RF-20. El sistema no debera permitir que un usuario administrador cambie su propio rol desde la gestion operativa.

### 5.4. Perfil del usuario y seguimiento academico

RF-21. El sistema debera ofrecer a cada usuario autenticado una vista de perfil con sus datos personales y academicos.

RF-22. El sistema debera permitir al usuario actualizar su foto de perfil.

RF-23. El sistema debera mostrar al alumno su informacion academica asociada, incluyendo carrera, materias y datos de cursada cuando correspondan.

RF-24. El sistema debera mostrar al alumno su estado de matriculacion por materia.

RF-25. El sistema debera mostrar al alumno sus calificaciones registradas.

RF-26. El sistema debera mostrar al alumno los conceptos o valoraciones academicas que le correspondan.

RF-27. El sistema debera permitir aplicar filtros de consulta dentro del perfil cuando existan datos academicos asociados.

### 5.5. Funciones docentes

RF-28. El sistema debera permitir al profesor consultar informacion academica de los alumnos vinculados a sus materias.

RF-29. El sistema debera permitir al profesor actualizar el estado de matriculacion de un alumno en una materia.

RF-30. El sistema debera permitir al profesor registrar o actualizar notas parciales y finales de los alumnos.

RF-31. El sistema debera permitir al profesor consultar y guardar conceptos academicos por alumno y por materia.

### 5.6. Gestion de carreras y materias

RF-32. El sistema debera permitir a los perfiles administrativos crear, editar y eliminar carreras.

RF-33. El sistema debera permitir a los perfiles administrativos crear, editar y eliminar materias.

RF-34. El sistema debera permitir asociar materias a carreras.

RF-35. El sistema debera permitir desasociar materias de carreras.

RF-36. El sistema debera ofrecer vistas administrativas para consultar la composicion academica de cada carrera.

### 5.7. Asignacion de profesores a materias

RF-37. El sistema debera permitir a los perfiles Administrativo y Administrador consultar la asignacion vigente de profesores por materia.

RF-38. El sistema debera permitir asignar materias a profesores habilitados.

RF-39. El sistema debera permitir remover asignaciones de materias a profesores.

RF-40. El sistema debera validar que solo se asignen materias validas a usuarios con perfil de profesor.

### 5.8. Novedades y comunicacion institucional

RF-41. El sistema debera permitir gestionar novedades institucionales para su publicacion en el sitio.

RF-42. El sistema debera permitir visualizar las novedades publicadas desde las interfaces correspondientes.

RF-43. El sistema debera disponer de un formulario de consultas institucionales accesible desde distintos puntos del sitio.

RF-44. El formulario de consultas debera solicitar como minimo nombre, correo electronico y mensaje.

RF-45. El formulario de consultas debera permitir registrar opcionalmente telefono y carrera de interes.

RF-46. El sistema debera enviar las consultas a las casillas administrativas configuradas.

RF-47. El sistema debera informar al usuario si la consulta fue enviada correctamente o si ocurrio un error.

### 5.9. Bolsa de trabajo

RF-48. El sistema debera incluir una bolsa de trabajo accesible para alumnos, Administrativo y Administrador.

RF-49. El sistema debera permitir a los perfiles Administrativo y Administrador crear ofertas laborales.

RF-50. El sistema debera publicar las ofertas creadas sin requerir una instancia adicional de aprobacion.

RF-51. El sistema debera permitir activar, desactivar y ocultar logicamente ofertas laborales.

RF-52. El sistema debera permitir al alumno visualizar las ofertas laborales publicadas.

RF-53. El sistema debera permitir al alumno postularse a una oferta mediante la carga de su CV.

RF-54. El sistema debera validar el archivo de CV segun formato permitido y tamano maximo admitido.

RF-55. El sistema debera enviar una confirmacion de postulacion al alumno.

RF-56. El sistema debera permitir al alumno descargar el CV asociado a su postulacion.

RF-57. El sistema debera permitir al alumno actualizar el CV de una postulacion ya existente.

RF-58. El sistema debera permitir al alumno cancelar su propia postulacion.

RF-59. El sistema debera conservar la relacion historica entre alumno y oferta aunque la postulacion sea cancelada.

RF-60. El sistema debera permitir a los perfiles de gestion consultar las postulaciones recibidas por oferta.

### 5.10. Personalizacion del sitio

RF-61. El sistema debera permitir a los perfiles Administrativo y Administrador personalizar elementos visuales del sitio.

RF-62. El sistema debera permitir gestionar el contenido visual del navbar.

RF-63. El sistema debera permitir gestionar el contenido visual del sidebar.

RF-64. El sistema debera permitir gestionar las imagenes, titulos, descripciones y enlaces del carrusel principal.

RF-65. El sistema debera permitir habilitar o deshabilitar cada seccion personalizada.

RF-66. El sistema debera permitir al perfil Administrador gestionar especificamente la configuracion del footer.

### 5.11. Seguridad funcional y permisos

RF-67. El sistema debera restringir el acceso a vistas y acciones segun el perfil del usuario autenticado.

RF-68. El sistema debera impedir que usuarios no autenticados accedan a funcionalidades internas.

RF-69. El sistema debera responder con mensajes claros o redirecciones apropiadas ante accesos no autorizados.

RF-70. El sistema debera contemplar validaciones de datos obligatorios en formularios y acciones criticas.

## 6. Requerimientos no incluidos en este alcance

Quedan fuera del alcance funcional actualmente verificado los siguientes puntos:

- integracion con sistemas externos de gestion academica ajenos al proyecto;
- inscripcion online a carreras con circuito administrativo completo;
- aprobacion multinivel de publicaciones laborales;
- aula virtual o gestion de contenidos pedagogicos;
- reporteria avanzada o tableros BI institucionales.

## 7. Criterios de aceptacion generales

- Cada perfil debera ver solo las opciones y acciones habilitadas para su rol.
- Las operaciones administrativas deberan ejecutarse desde pantallas o endpoints protegidos.
- Las altas, modificaciones y cancelaciones deberan reflejarse en la interfaz correspondiente.
- Las acciones que involucren correo electronico deberan informar resultado exitoso o fallido.
- La bolsa de trabajo debera permitir el ciclo completo de publicacion, postulacion, actualizacion de CV y cancelacion.
- La personalizacion visual debera impactar en los elementos publicos o privados configurados.

## 8. Observacion final

Este requerimiento funcional representa el alcance actual del sistema IFTS15 observado en la documentacion y en la implementacion existente. Puede utilizarse como base para presentacion al cliente, validacion de alcance, planificacion de mejoras y posterior armado de historias de usuario o especificaciones tecnicas.