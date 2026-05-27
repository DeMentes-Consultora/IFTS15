# Roles de IFTS15

## Alcance

Este archivo resume los roles operativos relevantes del proyecto segun la documentacion vigente.

## Roles principales detectados

| Rol | Alcance principal |
|---|---|
| 1 | Alumno. Puede acceder a su perfil y operar la bolsa de trabajo como postulante. |
| 3 | Gestion. Puede publicar y gestionar ofertas laborales, ademas de operar funciones de gestion segun el modulo. |
| 5 | Administracion. Comparte capacidades operativas de bolsa con el rol 3 y participa en funciones administrativas. |

## Reglas funcionales destacadas

### Bolsa de trabajo

- Rol `1`: ve ofertas publicadas, se postula, descarga su CV cargado, lo actualiza y cancela su propia postulacion.
- Roles `3` y `5`: crean ofertas, activan, desactivan, ocultan logicamente y consultan postulantes.

### Acceso al modulo de bolsa

- `canAccessBolsaTrabajo()` habilita acceso para roles `1`, `3` y `5`.
- `canManageBolsaTrabajo()` habilita gestion para roles `3` y `5`.

## Observacion

La documentacion de IFTS15 incluye otros perfiles institucionales y academicos. Este archivo se enfoca en los roles operativos que aparecen de forma mas clara en la documentacion vigente y especialmente en la bolsa de trabajo.

## Mantenimiento

Si cambian permisos, menus o restricciones por rol, actualizar este archivo junto con `estado-actual.md` y la documentacion del modulo afectado.