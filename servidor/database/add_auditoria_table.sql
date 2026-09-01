-- ================================================
-- TABLA DE AUDITORÍA
-- Registra todas las operaciones INSERT, UPDATE y DELETE
-- en las tablas sensibles del sistema.
-- ================================================

DROP TABLE IF EXISTS auditoria;

CREATE TABLE auditoria (
  id_auditoria INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NULL,
  usuario_mysql VARCHAR(100) NULL,
  accion ENUM('INSERT', 'UPDATE', 'DELETE') NOT NULL,
  tabla_afectada VARCHAR(100) NOT NULL,
  registro_id INT NULL,
  fecha_hora DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  descripcion TEXT NULL,
  valores_anteriores JSON NULL,
  valores_nuevos JSON NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
