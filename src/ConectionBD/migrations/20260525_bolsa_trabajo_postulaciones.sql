-- ============================================================
-- Migracion: Postulaciones Bolsa de Trabajo - IFTS15
-- Fecha: 2026-05-25
-- Basada en la logica de ComunidadIFTS, adaptada al esquema local
-- - Una postulacion por alumno y oferta
-- - CV opcional almacenado en Cloudinary
-- - Baja logica por cancelado=1
-- ============================================================

CREATE TABLE IF NOT EXISTS `postulacion_bolsa_trabajo` (
  `id_postulacion_bolsa_trabajo` int(11) NOT NULL AUTO_INCREMENT,
  `id_bolsa_trabajo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `cv_url` varchar(512) DEFAULT NULL,
  `cv_public_id` varchar(512) DEFAULT NULL,
  `cancelado` int(1) NOT NULL DEFAULT 0,
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_postulacion_bolsa_trabajo`),
  UNIQUE KEY `uq_postulacion_bolsa` (`id_bolsa_trabajo`, `id_usuario`),
  KEY `idx_postulacion_bolsa_usuario` (`id_usuario`),
  KEY `idx_postulacion_bolsa_cancelado` (`cancelado`),
  CONSTRAINT `fk_postulacion_bolsa_oferta`
    FOREIGN KEY (`id_bolsa_trabajo`) REFERENCES `bolsa_trabajo` (`id_bolsa_trabajo`)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_postulacion_bolsa_usuario`
    FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;