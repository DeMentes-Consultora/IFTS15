-- Migracion: tabla de matriculas por materia gestionadas por profesor
-- Fecha: 2026-04-22
-- Proyecto: IFTS15

USE `ifts15`;

CREATE TABLE IF NOT EXISTS `matricula_materia` (
  `id_matricula_materia` INT(11) NOT NULL AUTO_INCREMENT,
  `id_usuario_alumno` INT(11) NOT NULL,
  `id_materia` INT(11) NOT NULL,
  `id_profesor` INT(11) DEFAULT NULL,
  `estado` ENUM('espera','regular') NOT NULL DEFAULT 'espera',
  `fecha_matriculacion` TIMESTAMP NULL DEFAULT NULL,
  `habilitado` INT(1) NOT NULL DEFAULT 1,
  `cancelado` INT(1) NOT NULL DEFAULT 0,
  `idCreate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `idUpdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_matricula_materia`),
  UNIQUE KEY `uq_alumno_materia` (`id_usuario_alumno`, `id_materia`),
  KEY `idx_mm_materia` (`id_materia`),
  KEY `idx_mm_profesor` (`id_profesor`),
  CONSTRAINT `fk_mm_alumno` FOREIGN KEY (`id_usuario_alumno`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_mm_materia` FOREIGN KEY (`id_materia`) REFERENCES `materia` (`id_materia`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_mm_profesor` FOREIGN KEY (`id_profesor`) REFERENCES `usuario` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
