-- ============================================================
-- Migracion: Bolsa de Trabajo - IFTS15
-- Fecha: 2026-05-25
-- Estados de oferta:
--   habilitado=0, cancelado=0 -> PENDIENTE
--   habilitado=1, cancelado=0 -> PUBLICADA
--   habilitado=0, cancelado=1 -> RECHAZADA / BAJA
-- ============================================================

CREATE TABLE IF NOT EXISTS `bolsa_trabajo` (
  `id_bolsa_trabajo` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `titulo_oferta` varchar(255) NOT NULL,
  `texto_oferta` text NOT NULL,
  `habilitado` int(1) NOT NULL DEFAULT 0,
  `cancelado` int(1) NOT NULL DEFAULT 0,
  `idCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `idUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_bolsa_trabajo`),
  KEY `idx_bolsa_usuario` (`id_usuario`),
  KEY `idx_bolsa_estado` (`habilitado`, `cancelado`),
  CONSTRAINT `fk_bolsa_trabajo_usuario`
    FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;