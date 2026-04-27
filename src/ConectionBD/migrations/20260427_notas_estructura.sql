-- Migración: Unificar estructura de tabla notas para P1/P2/Final
-- Fecha: 27-04-2026
-- Descripción:
-- 1. Renombrar columna 1er_piarcial (typo) a 1er_parcial
-- 2. Permitir NULL en las 3 columnas de notas (para carga progresiva)
-- 3. Agregar índice único alumno+materia para evitar duplicados

-- Paso 1: Renombrar columna si existe con typo
-- (Se verifica primero porque el rename es seguro en MariaDB/MySQL)
ALTER TABLE `notas` 
CHANGE COLUMN `1er_piarcial` `1er_parcial` INT(2) NULL DEFAULT NULL;

-- Paso 2: Permitir NULL en todas las columnas de notas
-- (Para soportar carga progresiva: guardar P1 sin P2/Final)
ALTER TABLE `notas` 
MODIFY COLUMN `1er_parcial` INT(2) NULL DEFAULT NULL,
MODIFY COLUMN `2do_parcial` INT(2) NULL DEFAULT NULL,
MODIFY COLUMN `final` INT(2) NULL DEFAULT NULL;

-- Paso 3: Agregar índice único por alumno+materia
-- (Previene múltiples registros por alumno/materia)
-- Si el índice ya existe, no genera error
ALTER TABLE `notas`
ADD UNIQUE INDEX `uq_usuario_materia` (`id_usuario`, `id_materia`)
  USING BTREE;
