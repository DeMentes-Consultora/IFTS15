-- Migración: Crear tabla conceptos_alumno para guardar hasta 5 conceptos y sus notas por alumno/materia
-- Fecha: 14-05-2026

CREATE TABLE IF NOT EXISTS conceptos_alumno (
    id_concepto INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_materia INT NOT NULL,
    concepto VARCHAR(100) NOT NULL,
    nota DECIMAL(4,2) NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    habilitado TINYINT(1) DEFAULT 1,
    cancelado TINYINT(1) DEFAULT 0,
    CONSTRAINT fk_concepto_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    CONSTRAINT fk_concepto_materia FOREIGN KEY (id_materia) REFERENCES materia(id_materia) ON DELETE CASCADE
);

-- Índice para evitar más de 5 conceptos por alumno/materia
CREATE UNIQUE INDEX IF NOT EXISTS uq_concepto_alumno_materia ON conceptos_alumno (id_usuario, id_materia, concepto);
