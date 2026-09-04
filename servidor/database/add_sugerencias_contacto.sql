-- ================================================
-- TABLAS: sugerencias y contacto
-- Almacenan formularios enviados desde el sitio público
-- ================================================
DROP TABLE IF EXISTS sugerencias;
DROP TABLE IF EXISTS contacto;
-- ================================================
-- TABLA: sugerencias
-- ================================================
CREATE TABLE sugerencias (
  id_sugerencia INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  apellido VARCHAR(100) NOT NULL,
  correo VARCHAR(100) NOT NULL,
  telefono VARCHAR(20) NULL,
  asunto VARCHAR(100) NOT NULL,
  mensaje TEXT NOT NULL,
  estado ENUM('Nuevo', 'Leido', 'Respondido') NOT NULL DEFAULT 'Nuevo',
  fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
-- ================================================
-- TABLA: contacto
-- ================================================
CREATE TABLE contacto (
  id_contacto INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  apellido VARCHAR(100) NOT NULL,
  correo VARCHAR(100) NOT NULL,
  telefono VARCHAR(20) NULL,
  asunto VARCHAR(100) NOT NULL,
  mensaje TEXT NOT NULL,
  estado ENUM('Nuevo', 'Leido', 'Respondido') NOT NULL DEFAULT 'Nuevo',
  fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;