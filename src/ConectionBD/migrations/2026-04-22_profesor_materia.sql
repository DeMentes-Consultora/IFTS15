-- Migracion: relacion profesor-materia
-- Fecha: 2026-04-22
-- Proyecto: IFTS15

USE `ifts15`;

CREATE TABLE IF NOT EXISTS `profesor_materia` (
  `id_profesor_materia` INT(11) NOT NULL AUTO_INCREMENT,
  `id_profesor` INT(11) NOT NULL,
  `id_materia` INT(11) NOT NULL,
  `habilitado` INT(1) NOT NULL DEFAULT 1,
  `cancelado` INT(1) NOT NULL DEFAULT 0,
  `idCreate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `idUpdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_profesor_materia`),
  UNIQUE KEY `uq_profesor_materia` (`id_profesor`, `id_materia`),
  KEY `idx_pm_materia` (`id_materia`),
  CONSTRAINT `fk_pm_profesor` FOREIGN KEY (`id_profesor`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pm_materia` FOREIGN KEY (`id_materia`) REFERENCES `materia` (`id_materia`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Backfill inicial opcional:
-- Asigna automaticamente al profesor las materias de su misma carrera.
-- Comenta este bloque si queres manejar la asignacion manual desde un ABM.
INSERT INTO `profesor_materia` (`id_profesor`, `id_materia`)
SELECT u.id_usuario, m.id_materia
FROM usuario u
INNER JOIN materia m ON m.id_carrera = u.id_carrera
WHERE u.id_rol = 2
  AND u.habilitado = 1
  AND u.cancelado = 0
  AND m.habilitado = 1
  AND m.cancelado = 0
ON DUPLICATE KEY UPDATE idUpdate = CURRENT_TIMESTAMP;
